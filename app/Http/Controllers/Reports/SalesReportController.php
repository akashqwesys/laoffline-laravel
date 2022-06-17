<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\Employee;
use Illuminate\Support\Facades\Session;
use DB;
use PDF;
use Excel;
use App\Exports\SalesRegisterExport;
use App\Exports\ConsolidateMonthlySalesExport;

class SalesReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $page_title = 'All Reports';
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        return view('reports.index', compact('page_title', 'employees'));
    }

    public function salesRegister(Request $request)
    {
        $page_title = 'Sales Register Report';
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        $employees['excelAccess'] = $user->excel_access;

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Sales Register Report / View';
        $logs->log_subject = 'Sales Register Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.sales_register_report', compact('page_title', 'employees'));
    }

    public function listSalesRegisterData(Request $request)
    {
        $data = DB::table('sale_bills as s');
        if ($request->show_detail == 1) {
            $data = $data->join('companies as c', 's.company_id', '=', 'c.id')
                ->selectRaw('SUM(s.total) as total, SUM(s.received_payment) as received_payment, c.company_name, s.company_id');
        } else {
            $data = $data->join('sale_bill_items as sbi', function ($j) {
                    $j->on('s.sale_bill_id', '=', 'sbi.sale_bill_id')
                    ->on('s.financial_year_id', '=', 'sbi.financial_year_id');
                })
                ->leftJoin('sale_bill_transports as sbt', function ($j) {
                    $j->on('s.sale_bill_id', '=', 'sbt.sale_bill_id')
                    ->on('s.financial_year_id', '=', 'sbt.financial_year_id')
                    ->where('sbt.is_deleted', 0);
                })
                ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 's.company_id', '=', 'cc.id')
                ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 's.supplier_id', '=', 'cs.id')
                ->join('cities as c', DB::raw('cast(sbt.station as integer)'), '=', 'c.id')
                ->join('transport_details as td', 'sbt.transport_id', '=', 'td.id')
                ->join('sale_bill_agents as sba', 's.agent_id', '=', 'sba.id')
                ->selectRaw('cc.company_name as customer_name, cs.company_name as supplier_name, s.company_id, s.supplier_id, to_char(s.select_date, \'dd-mm-yyyy\') as select_date, s.sale_bill_id, s.financial_year_id, s.total, s.received_payment, s.change_in_amount, s.sign_change, s.supplier_invoice_no, SUM(sbi.pieces) AS tot_pieces, SUM(sbi.meters) AS tot_meters, SUM(sbi.cgst_amount + sbi.sgst_amount + sbi.igst_amount) AS total_gst, sbt.transport_id, sbt.lr_mr_no, td.name as transport_name, sba.name as agent_name, c.name as city_name');
        }
        if ($request->customer && $request->customer['id']) {
            $data = $data->where('s.company_id', $request->customer['id']);
        }
        if ($request->supplier && $request->supplier['id']) {
            $data = $data->where('s.supplier_id', $request->supplier['id']);
        }
        if ($request->payment_status && $request->payment_status['id'] == 1) {
            $data = $data->where('s.payment_status', 0);
        } else if ($request->payment_status && $request->payment_status['id'] == 2) {
            $data = $data->where('s.payment_status', 1);
        }
        if ($request->start_date && $request->end_date) {
            $data = $data->whereBetween('s.select_date', [$request->start_date, $request->end_date]);
        }
        if ($request->show_detail == 1) {
            $data = $data->whereRaw('s.is_deleted = 0 AND s.sale_bill_flag = 0')
                ->groupByRaw('c.company_name, s.company_id, s.select_date')
                ->orderByRaw('c.company_name, s.select_date asc')
                ->get();
        } else {
            $data = $data->whereRaw('s.is_deleted = 0 AND s.sale_bill_flag = 0 AND sbi.is_deleted = 0')
                ->groupByRaw('s.company_id, s.supplier_id, cc.company_name, cs.company_name, s.select_date, s.sale_bill_id, s.financial_year_id, s.total, s.received_payment, s.change_in_amount, s.sign_change, s.supplier_invoice_no, sbt.transport_id, sbt.lr_mr_no, td.name, sba.name, c.name')
                ->orderBy('s.sale_bill_id', 'asc')
                ->get();
        }
        if ($request->export_pdf == 1) {
            $pdf = PDF::loadView('reports.sales_register_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            if ($request->show_detail == 0) {
                $pdf = $pdf->setPaper('a4', 'landscape');
            }
            $path = storage_path('app/public/pdf/sales-register-reports');
            $fileName =  'Sales-Register-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/sales-register-reports/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Sales-Register-Report-' . time() . '.xlsx';
            Excel::store(new SalesRegisterExport($data, $request), 'excel-sheets/sales-register-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/sales-register-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }
    }

    public function consolidateMonthlySales(Request $request)
    {
        $page_title = 'Consolidate Monthly Sales Report';
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        $employees['excelAccess'] = $user->excel_access;

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Consolidate Monthly Sales Report / View';
        $logs->log_subject = 'Consolidate Monthly Sales Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.consolidate_monthly_sales_export', compact('page_title', 'employees'));
    }

    public function listConsolidateMonthlySales(Request $request)
    {
        $data = DB::table('sale_bills')
            ->selectRaw("date_trunc('month', select_date)::date as month_begin, (date_trunc('month', select_date) + interval '1 month -1 day')::date as month_end, SUM(total) as total_payment, SUM(CASE WHEN pending_payment = 0 and payment_status = 0 THEN total WHEN pending_payment <> 0 and payment_status = 0 THEN pending_payment ELSE 0 END) AS total_pending, SUM(received_payment) as total_received");

        if ($request->customer && $request->customer['id']) {
            $data = $data->where('company_id', $request->customer['id']);
        }
        if ($request->supplier && $request->supplier['id']) {
            $data = $data->where('supplier_id', $request->supplier['id']);
        }
        if ($request->agent && $request->agent['id'] != 0) {
            $data = $data->where('agent_id', $request->agent['id']);
        }
        if ($request->category && $request->category['id']) {
            $data = $data->whereRaw('product_category_id @> ' . $request->category['id']);
        }
        if ($request->start_date && $request->end_date) {
            $data = $data->whereBetween('select_date', [$request->start_date, $request->end_date]);
        }
        $data = $data->whereRaw('is_deleted = 0 AND sale_bill_flag = 0')
            ->groupByRaw("date_trunc('month', select_date)")
            ->orderByRaw("date_trunc('month', select_date) asc")
            ->get();

        foreach ($data as $k => $v) {
            $v->month_year = date('F, Y', strtotime($v->month_begin));
        }

        if ($request->export_pdf == 1) {
            $pdf = PDF::loadView('reports.consolidate_monthly_sales_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/consolidate-monthly-sales-reports');
            $fileName =  'Consolidate-Monthly-Sales-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/consolidate-monthly-sales-reports/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Consolidate-Monthly-Sales-Report-' . time() . '.xlsx';
            Excel::store(new ConsolidateMonthlySalesExport($data, $request), 'excel-sheets/consolidate-monthly-sales-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/consolidate-monthly-sales-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }
    }

    public function listCities()
    {
        $cities = DB::table('cities as c')
            ->join('sale_bill_transports as sbt', 'c.id', '=', DB::raw('cast(station as integer)'))
            ->select('c.id', 'c.name')
            ->groupByRaw('c.id, c.name')
            ->orderBy('c.name', 'asc')
            ->get();
        return response()->json($cities);
    }
}