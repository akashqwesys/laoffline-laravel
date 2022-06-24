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
        if ($request->show_detail == 1) {
            $data = $data->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'c.customer_id', '=', 'cc.id')
                    ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'c.supplier_id', '=', 'cs.id')
                    ->selectRaw('cc.company_name as customer_name, cs.company_name as supplier_name, c.supplier_id, c.customer_id, SUM(c.commission_payment_amount) as total')
                    ->groupBy('c.supplier_id', 'c.customer_id','cc.company_name', 'cs.company_name');
        } else {
        $data = $data->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'c.customer_id', '=', 'cc.id')
                ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'c.supplier_id', '=', 'cs.id')
                ->leftJoin(DB::raw('(SELECT "name", "id" FROM agents group by "name", "id") as "agent"'), 'c.commission_account', '=', 'agent.id')
                ->leftJoin(DB::raw('(SELECT SUM(tds) as tds, SUM(service_tax) as service_tax, c_increment_id FROM commission_details group by "tds", "service_tax", "c_increment_id") as "commission_details"'), 'c.id', '=', 'commission_details.c_increment_id')
                ->leftJoin(DB::raw('(SELECT "name", "id" FROM bank_details group by "name", "id") as "bank"'), 'c.commission_deposite_bank', '=', 'bank.id')
                ->leftJoin(DB::raw('(SELECT "name", "id" FROM bank_details group by "name", "id") as "cheque_bank"'), 'c.commission_cheque_dd_bank', '=', 'cheque_bank.id')
                ->selectRaw('cc.company_name as customer_name, cs.company_name as supplier_name, agent.name as agent ,bank.name as bank_name, cheque_bank.name as commission_cheque_dd_bank, c.customer_id, c.supplier_id, to_char(c.commission_date, \'dd-mm-yyyy\') as commission_date, c.commission_id, c.financial_year_id, c.commission_reciept_mode, to_char(c.commission_cheque_date, \'dd-mm-yyyy\') as commission_cheque_date, c.commission_cheque_dd_no, c.commission_payment_amount, commission_details.tds, commission_details.service_tax');
        }
        if ($request->company && $request->company['id']) {
            $company_details = Company::where('id', $request->company['id'])->first();
            $link_companies = LinkCompanies::where('company_id', $request->company['id'])->get();
            if (empty($link_companies)) {
                $is_linked = LinkCompanies::where('link_companies_id', $request->company['id'])->get();
                if (!empty($is_linked)) {
                    $company_details = Company::where('id', $is_linked->company_id)->first();
                    $link_companies = LinkCompanies::where('company_id', $is_linked->company_id)->get();
                }
            }
            if ($company_details) {
                $main_cmp_id = $company_details->company_id;
                $data = $data->where('c.supplier_id', $main_cmp_id)->OrWhere('c.customer_id', $main_cmp_id);
                foreach ($link_companies as $row_link_companies) {
                    $data = $data->OrWhere('c.supplier_id', $row_link_companies->link_companies_id)->OrWhere('c.customer_id', $row_link_companies->link_companies_id);
                }
            }
        }

        if ($request->start_date && $request->end_date) {
            $data = $data->whereBetween('c.commission_date', [$request->start_date, $request->end_date]);
        }

        if ($request->agent && $request->agent['id']) {
            $data = $data->where('c.commission_account', $request->agent['id']);
        }

        if ($request->mode && $request->mode['id']) {
            $data = $data->where('c.commission_reciept_mode', $request->mode['name']);
        }

        if ($request->sorting && $request->sorting['id']) {
            $sorting = $request->sorting['id'];
            if ($request->show_detail == 1) {
                if ($sorting == 1) {
                    $data = $data->orderBy('cs.company_name', 'asc');
                } else if ($sorting == 2) {
                    $data = $data->orderBy('cs.company_name', 'desc');
                }   
            } else {
                if ($sorting == 3) {
                    $data = $data->orderBy('c.commission_date', 'asc');
                } else if ($sorting == 4) {
                    $data = $data->orderBy('c.commission_date', 'desc');
                } else if ($sorting == 1) {
                    $data = $data->orderBy('cs.company_name', 'asc');
                } else if ($sorting == 2) {
                    $data = $data->orderBy('cs.company_name', 'desc');
                } 
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
