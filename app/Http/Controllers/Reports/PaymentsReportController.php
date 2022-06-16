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
use App\Exports\PaymentsRegisterExport;

class PaymentsReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function paymentRegister(Request $request)
    {
        $page_title = 'Payments Register Report';
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
        $logs->log_path = 'Payments Register Report / View';
        $logs->log_subject = 'Payments Register Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.payments_register_report', compact('page_title', 'employees'));
    }

    public function listPaymentRegisterData(Request $request)
    {
        $data = DB::table('payments as p');
        $data = $data->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'p.customer_id', '=', 'cc.id')
                ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'p.supplier_id', '=', 'cs.id')
                ->leftJoin(DB::raw('(SELECT "name", "id" FROM bank_details group by "name", "id") as "bank"'), 'p.deposite_bank', '=', 'bank.id')
                ->leftJoin(DB::raw('(SELECT "name", "id" FROM bank_details group by "name", "id") as "cheque_bank"'), 'p.cheque_dd_bank', '=', 'cheque_bank.id')
                ->selectRaw('cc.company_name as customer_name, cs.company_name as supplier_name, bank.name as bank_name, cheque_bank.name as cheque_bank, p.customer_id, p.supplier_id, to_char(p.date, \'dd-mm-yyyy\') as date, p.payment_id, p.financial_year_id, p.reciept_mode, p.cheque_date, p.cheque_dd_no, cheque_bank.name as cheque_bank, p.receipt_amount, p.total_amount');
        
        if ($request->customer && $request->customer['id']) {
            $data = $data->where('p.customer_id', $request->customer['id']);
        }
        if ($request->supplier && $request->supplier['id']) {
            $data = $data->where('p.supplier_id', $request->supplier['id']);
        }
        
        if ($request->start_date && $request->end_date) {
            $data = $data->whereBetween('p.date', [$request->start_date, $request->end_date]);
        }

        if ($request->sorting && $request->sorting['id']) {
            $sorting = $request->sorting['id'];
            if ($sorting == 5) {
                $data = $data->orderBy('p.date', 'asc');
            } else if ($sorting == 6) {
                $data = $data->orderBy('p.date', 'desc');
            } else if ($sorting == 1) {
                $data = $data->orderBy('cs.company_name', 'asc');
            } else if ($sorting == 2) {
                $data = $data->orderBy('cs.company_name', 'desc');
            } else if ($sorting == 3) {
                $data = $data->orderBy('cc.company_name', 'asc');
            }  else if ($sorting == 4) {
                $data = $data->orderBy('cc.company_name', 'desc');
            }
            
        }
        $data = $data->get();
        if ($request->export_pdf == 1) {
            $pdf = PDF::loadView('reports.payments_register_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/payments-register-reports');
            $fileName =  'Payments-Register-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/payments-register-reports/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Payments-Register-Report-' . time() . '.xlsx';
            Excel::store(new PaymentsRegisterExport($data, $request), 'excel-sheets/payments-register-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/payments-register-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }
    }
}
