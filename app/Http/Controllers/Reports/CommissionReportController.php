<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\Employee;
use App\Models\Settings\Agent;
use Illuminate\Support\Facades\Session;
use DB;
use PDF;
use Excel;
use App\Exports\CommissionRegisterExport;

class CommissionReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function listAgents() {
        $agent = Agent::where('is_delete', '0')->get();
        return $agent;
    }
    public function commissionRegister(Request $request)
    {
        $page_title = 'Commission Register Report';
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
        $logs->log_path = 'Commission Register Report / View';
        $logs->log_subject = 'Commission Register Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.commission_register_report', compact('page_title', 'employees'));
    }

    public function listCommissionRegisterData(Request $request)
    {
        $data = DB::table('commissions as c');
        $data = $data->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'c.customer_id', '=', 'cc.id')
                ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'c.supplier_id', '=', 'cs.id')
                ->leftJoin(DB::raw('(SELECT "name", "id" FROM agents group by "name", "id") as "agent"'), 'c.commission_account', '=', 'agent.id')
                ->leftJoin(DB::raw('(SELECT SUM(tds) as tds, SUM(service_tax) as service_tax, c_increment_id FROM commission_details group by "tds", "service_tax", "c_increment_id") as "commission_details"'), 'c.id', '=', 'commission_details.c_increment_id')
                ->leftJoin(DB::raw('(SELECT "name", "id" FROM bank_details group by "name", "id") as "bank"'), 'c.commission_deposite_bank', '=', 'bank.id')
                ->leftJoin(DB::raw('(SELECT "name", "id" FROM bank_details group by "name", "id") as "cheque_bank"'), 'c.commission_cheque_dd_bank', '=', 'cheque_bank.id')
                ->selectRaw('cc.company_name as customer_name, cs.company_name as supplier_name, agent.name as agent ,bank.name as bank_name, cheque_bank.name as commission_cheque_dd_bank, c.customer_id, c.supplier_id, to_char(c.commission_date, \'dd-mm-yyyy\') as commission_date, c.commission_id, c.financial_year_id, c.commission_reciept_mode, to_char(c.commission_cheque_date, \'dd-mm-yyyy\') as commission_cheque_date, c.commission_cheque_dd_no, c.commission_payment_amount, commission_details.tds, commission_details.service_tax');
        
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
            $pdf = PDF::loadView('reports.commission_register_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/commission-register-reports');
            $fileName =  'Commission-Register-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/commission-register-reports/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Commission-Register-Report-' . time() . '.xlsx';
            Excel::store(new CommissionRegisterExport($data, $request), 'excel-sheets/commission-register-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/commission-register-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }
    }
}
