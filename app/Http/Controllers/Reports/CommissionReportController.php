<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\Employee;
use App\Models\Settings\Agent;
use App\Models\Company\Company;
use App\Models\LinkCompanies;
use Illuminate\Support\Facades\Session;
use DB;
use PDF;
use Excel;
use App\Exports\CommissionRegisterExport;
use App\Exports\OutstandingCommissionExport;
use App\Exports\AvarageCommissionDaysExport;
use App\Exports\OutstandingCommissionMonthWiseSummeryExport;
use App\Exports\CommissionCollectionExport;
use App\Exports\CommissionRightofExport;
use Carbon\Carbon;

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

    public function outstandingCommissionRegister(Request $request)
    {
        $page_title = 'Outstanding Commission Register Report - '. $request->type;
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        $employees['excelAccess'] = $user->excel_access;
        $employees['type'] = $request->type;

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Outstanding Commission Register Report / View';
        $logs->log_subject = 'Commission Register Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.outsstanding_commission_report', compact('page_title', 'employees'));
    }

    public function outstandingCommissionMonthWiseSummeryReport(Request $request) {
        $page_title = 'Outstanding Commission Month Wise Summery Report - '. $request->type;
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        $employees['excelAccess'] = $user->excel_access;
        $employees['type'] = $request->type;

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Outstanding Commission Month Wise Summery Report / View';
        $logs->log_subject = 'Outstanding Commission Month Wise Summery Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.outstanding_commission_month_wise_summery_report', compact('page_title', 'employees'));
    }

    public function avaCommissionDaysReport(Request $request) {
        $page_title = 'Avarage Commission Days Report';
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
        $logs->log_path = 'Avarage Commission Days Report / View';
        $logs->log_subject = 'Outstanding Commission Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.ava_commission_days_report', compact('page_title', 'employees'));
    }

    public function dailyCommissionReport(Request $request) {
        $page_title = 'Daily Commission Report';
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
        $logs->log_path = 'Daily Commission Report / View';
        $logs->log_subject = 'Daily Commission Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.daily_commission_report', compact('page_title', 'employees'));
    }
    public function commissionCollectionReport(Request $request) {
        $page_title = 'Commission Collection Report';
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
        $logs->log_path = 'Commission Collection Report / View';
        $logs->log_subject = 'Commission Collection Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.commission_collection_report', compact('page_title', 'employees'));
    }

    public function commissionRightofReport(Request $request) {
        $page_title = 'Commission Right Of Report';
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
        $logs->log_path = 'Commission Right Of Report / View';
        $logs->log_subject = 'Commission Right Of Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.commission_rightof_report', compact('page_title', 'employees'));
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
                ->leftJoin(DB::raw('(SELECT "name", "id" FROM agents group by "name", "id") as "agent"'), DB::raw('cast(c.commission_account as integer)'), '=', 'agent.id')
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
            if ($request->show_detail == 0) {
                $pdf = $pdf->setPaper('a4', 'landscape');
            }
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

    public function listOutstandingCommissionData(Request $request) {
        if ($request->report_type == 'supplier') {
        if ($request->show_detail == 1) {
        $data1 = DB::table('payments as p1')
                    ->join(DB::raw('(SELECT "commission_percentage", "customer_id", "supplier_id", "flag" FROM company_commissions group by "commission_percentage","customer_id", "supplier_id", "flag") as "ccomm_per1"'), function($join){
                    $join->on('p1.receipt_from', '=', 'ccomm_per1.customer_id')
                    ->on('p1.supplier_id', '=', 'ccomm_per1.supplier_id')
                    ->where('ccomm_per1.flag', 1);
                })
                ->leftJoin(DB::raw('(SELECT "company_name", "id", "company_city" FROM companies group by "company_name", "id", "company_city") as "cs"'), 'p1.supplier_id', '=', 'cs.id')
                ->groupBy('p1.supplier_id', 'cs.company_name', 'cs.company_city')
                ->orderBy('cs.company_name')
                ->where('p1.is_deleted', 0)->whereNot('p1.receipt_amount', 0)->where('p1.old_commission_status', 0)->where('p1.right_of_amount', 0)
                ->select('cs.company_name as supplier_name', DB::raw('SUM(ROUND(p1.receipt_amount * ccomm_per1.commission_percentage / 100)) as total_comm_amount'), DB::raw('SUM(p1.receipt_amount) as receipt_amount'));

        } else {
        $data1 = DB::table('payments as p1')
                ->join(DB::raw('(SELECT "commission_percentage", "customer_id", "supplier_id", "flag" FROM company_commissions group by "commission_percentage","customer_id", "supplier_id", "flag") as "ccomm_per1"'), function($join){
                    $join->on('p1.receipt_from', '=', 'ccomm_per1.customer_id')
                    ->on('p1.supplier_id', '=', 'ccomm_per1.supplier_id')
                    ->where('ccomm_per1.flag', 1);
                })
                ->leftJoin(DB::raw('(SELECT "company_name", "id", "company_city" FROM companies group by "company_name", "id", "company_city") as "cs"'), 'p1.supplier_id', '=', 'cs.id')
                ->groupBy('p1.supplier_id', 'cs.company_name', 'cs.company_city')
                ->where('p1.is_deleted', 0)->whereNot('p1.receipt_amount', 0)->where('p1.old_commission_status', 0)->where('p1.right_of_amount', 0)
                ->select('p1.supplier_id',DB::raw('SUM(ROUND(p1.receipt_amount * ccomm_per1.commission_percentage / 100)) as total_comm_amount'));
        }
        $data2 = DB::table('payments as p')
                ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'p.receipt_from', '=', 'cc.id')
                ->leftJoin(DB::raw('(SELECT "company_name", "id", "company_city" FROM companies group by "company_name", "id", "company_city") as "cs"'), 'p.supplier_id', '=', 'cs.id')
                ->leftJoin(DB::raw('(SELECT "address", "company_id" FROM company_addresses WHERE address_type = 1 group by "address", "company_id" limit 1) as "cadd"'), 'p.supplier_id', '=', 'cadd.company_id')
                ->join(DB::raw('(SELECT "commission_percentage", "customer_id", "supplier_id", "flag" FROM company_commissions group by "commission_percentage","customer_id", "supplier_id", "flag") as "ccomm_per2"'), function($join){
                    $join->on('p.receipt_from', '=', 'ccomm_per2.customer_id')
                    ->on('p.supplier_id', '=', 'ccomm_per2.supplier_id')
                    ->where('ccomm_per2.flag', 1);
                })
                ->where('p.is_deleted', 0)->whereNot('p.receipt_amount', 0)->where('p.old_commission_status', 0)->where('p.right_of_amount', 0)
                ->select('cc.company_name as customer_name', 'cs.company_city as city_name','cs.company_name as supplier_name', 'cadd.address as company_address', 'p.*', DB::raw('ROUND(p.receipt_amount * ccomm_per2.commission_percentage / 100) as commission_amount'));
        } else {
            if ($request->show_detail == 1) {
                $data1 = DB::table('payments as p1')
                        ->join(DB::raw('(SELECT "commission_percentage", "customer_id", "supplier_id", "flag" FROM company_commissions group by "commission_percentage","customer_id", "supplier_id", "flag") as "ccomm_per1"'), function($join){
                            $join->on('p1.customer_id', '=', 'ccomm_per1.customer_id')
                            ->on('p1.supplier_id', '=', 'ccomm_per1.supplier_id')
                            ->where('ccomm_per1.flag', 2);
                        })
                        ->join(DB::raw('(SELECT "company_name", "id", "company_city" FROM companies group by "company_name", "id", "company_city") as "cc"'), 'p1.customer_id', '=', 'cc.id')
                        ->groupBy('p1.customer_id', 'cc.company_name', 'cc.company_city')
                        ->orderBy('cc.company_name')
                        ->where('p1.is_deleted', 0)->whereNot('p1.receipt_amount', 0)->where('p1.customer_commission_status', 0)->where('p1.right_of_amount', 0)
                        ->select('cc.company_name as customer_name', DB::raw('SUM(ROUND(p1.receipt_amount * ccomm_per1.commission_percentage / 100)) as total_comm_amount'), DB::raw('SUM(p1.receipt_amount) as receipt_amount'));

                } else {
                $data1 = DB::table('payments as p1')
                        ->join(DB::raw('(SELECT "commission_percentage", "customer_id", "supplier_id", "flag" FROM company_commissions group by "commission_percentage","customer_id", "supplier_id", "flag") as "ccomm_per1"'), function($join){
                            $join->on('p1.customer_id', '=', 'ccomm_per1.customer_id')
                            ->on('p1.supplier_id', '=', 'ccomm_per1.supplier_id')
                            ->where('ccomm_per1.flag', 2);
                        })
                        ->join(DB::raw('(SELECT "company_name", "id", "company_city" FROM companies group by "company_name", "id", "company_city") as "cc"'), 'p1.customer_id', '=', 'cc.id')
                        ->groupBy('p1.customer_id', 'cc.company_name', 'cc.company_city')
                        ->where('p1.is_deleted', 0)->whereNot('p1.receipt_amount', 0)->where('p1.customer_commission_status', 0)->where('p1.right_of_amount', 0)
                        ->select('p1.customer_id',DB::raw('SUM(ROUND(p1.receipt_amount * ccomm_per1.commission_percentage / 100)) as total_comm_amount'));
                }
                $data2 = DB::table('payments as p')
                        ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'p.supplier_id', '=', 'cs.id')
                        ->leftJoin(DB::raw('(SELECT "company_name", "id", "company_city" FROM companies group by "company_name", "id", "company_city") as "cc"'), 'p.customer_id', '=', 'cc.id')
                        ->leftJoin(DB::raw('(SELECT "address", "company_id" FROM company_addresses WHERE address_type = 1 group by "address", "company_id" limit 1) as "cadd"'), 'p.customer_id', '=', 'cadd.company_id')
                        ->join(DB::raw('(SELECT "commission_percentage", "customer_id", "supplier_id", "flag" FROM company_commissions group by "commission_percentage","customer_id", "supplier_id", "flag") as "ccomm_per2"'), function($join){
                            $join->on('p.customer_id', '=', 'ccomm_per2.customer_id')
                            ->on('p.supplier_id', '=', 'ccomm_per2.supplier_id')
                            ->where('ccomm_per2.flag', 2);
                        })
                        ->where('p.is_deleted', 0)->whereNot('p.receipt_amount', 0)->where('p.customer_commission_status', 0)->where('p.right_of_amount', 0)
                        ->select('cc.company_name as customer_name', 'cc.company_city as city_name','cs.company_name as supplier_name', 'cadd.address as company_address', 'p.*', DB::raw('ROUND(p.receipt_amount * ccomm_per2.commission_percentage / 100) as commission_amount'));
        }
        $supplier = array();
        $customer = array();
        if ($request->supplier && $request->supplier['id']) {
            $company_details = Company::where('id', $request->supplier['id'])->first();
            $link_companies = LinkCompanies::whereRaw('company_id = ' . $request->supplier['id'] . ' OR company_id = (SELECT company_id FROM link_companies WHERE link_companies_id = ' . $request->supplier['id'] . ')')->get();
            foreach ($link_companies as $key => $value) {
                array_push($supplier, $value->company_id);
                array_push($supplier, $value->link_companies_id);
            }
            $supplier = array_unique($supplier);
            if (count($supplier) < 1) {
                $supplier = [$request->supplier['id']];
            }
            if ($company_details) {
                $data2 = $data2->WhereIn('p.supplier_id', $supplier);
                $data1 = $data1->WhereIn('p1.supplier_id', $supplier);
                $supplier_data = [];
                foreach($supplier as $row) {
                    $supplier_data[] = Company::where('id', $row)->select('company_name')->first()->company_name;
                }
                $data['sup_disp_name'] = implode(', ', $supplier_data);
            }
        }

        if ($request->customer && $request->customer['id']) {
            $company_details = Company::where('id', $request->customer['id'])->first();
            $link_companies = LinkCompanies::whereRaw('company_id = ' . $request->customer['id'] . ' OR company_id = (SELECT company_id FROM link_companies WHERE link_companies_id = ' . $request->customer['id'] . ')')->get();
            foreach ($link_companies as $key => $value) {
                array_push($customer, $value->company_id);
                array_push($customer, $value->link_companies_id);
            }
            $customer = array_unique($customer);
            if (count($customer) < 1) {
                $customer = [$request->customer['id']];
            }
            if ($company_details) {
                $data2 = $data2->WhereIn('p.customer_id', $customer);
                $data1 = $data1->WhereIn('p1.customer_id', $customer);
                foreach($customer as $row) {
                    $customer_data[] = Company::where('id', $row)->select('company_name')->pluck('company_name')->first();
                }
                $data['cust_disp_name'] = implode(',  ', $customer_data);
            }
        }

        if ($request->start_date && $request->end_date) {
            $data1 = $data1->whereRaw("p1.date::date >= '" . $request->start_date . "'")
                    ->whereRaw("p1.date::date <= '" . $request->end_date . "'");
            $data2 = $data2->whereRaw("p.date::date >= '" . $request->start_date . "'")
                    ->whereRaw("p.date::date <= '" . $request->end_date . "'");
        }

        if ($request->city && $request->city['id']) {
            if ($request->report_type == 'supplier') {
                $data1 = $data1->Where('cs.company_city', $request->city['name']);
                $data2 = $data2->Where('cs.company_city', $request->city['name']);
            } else {
                $data1 = $data1->Where('cc.company_city', $request->city['name']);
                $data2 = $data2->Where('cc.company_city', $request->city['name']);
            }
        }
        if ($request->day != '' && $request->day['report_days'] != 0) {
            $todaydate = Carbon::now()->format('Y-m-d');
            $data2 = $data2->whereRaw('\'' . $todaydate . '\'' . ' - p.date > '. $request->day['report_days']);
            $data1 = $data1->whereRaw('\'' . $todaydate . '\'' . ' - p1.date > '. $request->day['report_days']);
        }

        if ($request->sorting && $request->sorting['id']) {
            $sorting = $request->sorting['id'];
            if ($sorting == 1) {
                $data2 = $data2->orderBy('cs.company_name', 'asc');
            } else if ($sorting == 2) {
                $data2 = $data2->orderBy('cs.company_name', 'desc');
            } else if ($sorting == 3) {
                $data2 = $data2->orderBy('cc.company_name', 'asc');
            } else if ($sorting == 4) {
                $data2 = $data2->orderBy('cc.company_name', 'desc');
            } else if ($sorting == 5) {
                $data2 = $data2->orderBy('p.date', 'asc');
            } else if ($sorting == 6) {
                $data2 = $data2->orderBy('p.date', 'desc');
            } else if ($sorting == 7) {
                $data2 = $data2->orderBy('commission_amount', 'asc');
            } else if ($sorting == 8) {
                $data2 = $data2->orderBy('commission_amount', 'desc');
            }

        }
        $data2 = $data2->get();
        $data1 = $data1->get();
        $morethan = '';
        $sup = '';
        $sup1 = '';
        $data2 =collect($data2)->groupBy('supplier_name');

        if ($request->day != '' && $request->day['report_days'] != 0) {
            $morethan .= "( More then ". $request->day['report_days'] ." Days)";
        } else {
            $morethan .= "";
        }

        if ($request->supplier != '') {
            if($data['sup_disp_name']) {
                $sup .= "Supplier: " .$data['sup_disp_name'] . $morethan;
            } else {
                $sup .= "Supplier: " .$data['sup_disp_name'] . $morethan;
            }
        } else {
            $sup .= "All Parties". $morethan;
        }

        if ($request->customer != '') {
            if($data['cust_disp_name']) {
                $sup1 .= "Customer: " .$data['cust_disp_name'] . $morethan;
            } else {
                $sup1 .= "Customer: " .$data['cust_disp_name'] . $morethan;
            }
        } else {
            $sup1 .= "All Parties". $morethan;
        }

        $html = '';
        if ($request->report_type == 'supplier'){
            if ($request->show_detail == 1) {
                $html .= '<tr width="100%">
                            <th colspan="4" class="text-center">'.$sup.'</th>
                        </tr>
                        <tr width="100%">
                                <th>Sr_No</th>
                                <th>Supplier Name</th>
                                <th class="text-right">Amount</th>
                                <th>Commission Amount</th>
                            </tr>';
            } else {
                $html .= '<tr width="100%">
                            <th colspan="8" class="text-center">'.$sup.'</th>
                        </tr>
                        <tr width="100%" class=""text-center>
                                <th>Payment Id</th>
                                <th>Date</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Commission Amount</th>
                                <th>Percent</th>
                                <th>Customer</th>
                                <th>Days</th>
                                <th>Invoice</th>
                            </tr>';
            }
        } else {
            if ($request->show_detail == 1) {
                $html .= '<tr width="100%">
                            <th colspan="4" class="text-center">'.$sup1.'</th>
                        </tr>
                        <tr width="100%">
                                <th>Sr_No</th>
                                <th>Customer Name</th>
                                <th class="text-right">Amount</th>
                                <th>Commission Amount</th>
                            </tr>';
            } else {
                $html .= '<tr width="100%">
                            <th colspan="8" class="text-center">'.$sup1.'</th>
                        </tr>
                        <tr width="100%" class=""text-center>
                                <th>Payment Id</th>
                                <th>Date</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Commission Amount</th>
                                <th>Percent</th>
                                <th>Supplier</th>
                                <th>Days</th>
                                <th>Invoice</th>
                            </tr>';
            }
        }

        if ($request->show_detail == 1) {
            $tot_payment = $total_payment = $total_commission_amount = 0;
            foreach ($data1 as $keys => $row) {
                if ($request->report_type == 'supplier') {
                    $company_name = $row->supplier_name;
                } else {
                    $company_name = $row->customer_name;
                }
                $receipt_amount = number_format($row->receipt_amount);
                $total_comm_amount = number_format($row->total_comm_amount);
                $html .= '<tr width="100%">
                            <td>'.++$keys.'</td>
                            <td>'.$company_name.'</td>
                            <td class="text-right">'.$receipt_amount.'</td>
                            <td class="text-right">'.$total_comm_amount.'</td>
                        </tr>';
                $tot_payment += $row->receipt_amount;
                $total_commission_amount += $row->total_comm_amount;
            }
            if (!empty($data1)){
                $tot_payment1 = number_format($tot_payment);
                $total_commission_amount1 = number_format($total_commission_amount);
                $html .= '<tr width="100%">
                            <td colspan="2"><b>Party Total</b></td>
                            <td class="text-right"><b>'.$tot_payment1.'</b></td>
                            <td class="text-right"><b>'.$total_commission_amount1.'</b></td>
                        </tr>';
            }
            $data['detail'] = $data1;
        } else {
            $data3 = array();
            $supplier_name = "";$customer_name = "";$prev_com = 0; $tot_payment = $total_payment = $total_commission_amount = 0;
            $gtotal = $gtotal_commission = 0;
            foreach ($data2 as $key1 => $sc) {
                if ($request->report_type == 'supplier') {

                        $supplier_name = $key1;
                        $address_supp = $sc[0]->company_address;

                        $html .= '<tr width="100%">
		    					<td colspan="8"></td>
							</tr>
                            <tr width="100%">
                                <td colspan="2"><b>' . $supplier_name . '</b></td>
                                <td colspan="6"><b>' . $address_supp . '</b></td>
                            </tr>';

                } else {

                        $customer_name = $key1;
                        $address_supp = $sc[0]->company_address;

                        $html .= '<tr width="100%">
                                    <td colspan="8"></td>
                                </tr>
                                <tr width="100%">
                                    <td colspan="2"><b>' . $customer_name . '</b></td>
                                    <td colspan="6"><b>' . $address_supp . '</b></td>
                                </tr>';

                }
                $ptotal = $ptotal_commission = 0;
                foreach ($sc as $key1 => $row) {
                    $color = "";
                    $paymentdate = strtotime($row->date);
                    $currentdate = strtotime(Carbon::now()->format('d-m-Y'));
                    $due_day = ($currentdate - $paymentdate) / 84600;
                    if ($due_day)
                    if ($due_day >= 90) {
                        $color = "style='color:red'";
                    }


                if ($request->report_type == 'supplier') {
                    $invoices = DB::table('commission_invoices as ci')
                    ->leftJoin(DB::raw('(SELECT "payment_id", "commission_invoice_id", "financial_year_id", "flag" FROM invoice_payment_details group by "payment_id", "commission_invoice_id", "financial_year_id", "flag") as ipd'), 'ci.id', '=', 'ipd.commission_invoice_id')
                    ->leftJoin(DB::raw('(SELECT "commission_invoice_id", "received_commission_amount" FROM commission_details where is_deleted = 0 group by "commission_invoice_id", "received_commission_amount") as cd'), 'ci.id', '=', 'cd.commission_invoice_id')
                    ->where('ipd.flag', 1)
                        ->where('ci.is_deleted', 0)
                        ->where('ipd.payment_id', $row->payment_id)
                        ->where('ipd.financial_year_id', $row->financial_year_id)
                        ->select('ci.id', 'ci.bill_no', 'ipd.payment_id', 'ipd.financial_year_id', 'ci.final_amount', 'cd.received_commission_amount')
                        ->get();
                } else {
                    $invoices = DB::table('commission_invoices as ci')
                    ->leftJoin(DB::raw('(SELECT "payment_id", "commission_invoice_id", "financial_year_id", "flag" FROM invoice_payment_details group by "payment_id", "commission_invoice_id", "financial_year_id", "flag") as ipd'), 'ci.id', '=', 'ipd.commission_invoice_id')
                    ->leftJoin(DB::raw('(SELECT "commission_invoice_id", "received_commission_amount" FROM commission_details where is_deleted = 0 group by "commission_invoice_id", "received_commission_amount") as cd'), 'ci.id', '=', 'cd.commission_invoice_id')
                    ->where('ipd.flag', 2)
                        ->where('ci.is_deleted', 0)
                        ->where('ipd.payment_id', $row->payment_id)
                        ->where('ipd.financial_year_id', $row->financial_year_id)
                        ->select('ci.id', 'ci.bill_no', 'ipd.payment_id', 'ipd.financial_year_id', 'ci.final_amount', 'cd.received_commission_amount')
                        ->get();
                }
                if (count($invoices)) {
                    $total = 0;
                    $pending_parcent = collect($invoices)->groupBy('id');
                    foreach ($pending_parcent as $inv) {
                        foreach ($inv as $pp) {
                            $total += (int)$pp->received_commission_amount;
                            $final_amount = $pp->final_amount;
                            $commission_invoice_id = $pp->id;
                            $bill_no = $pp->bill_no;
                        }
                    }

                    $pending_amount = (int)$final_amount - (int)$total;
                    $pending_percentage = round((($pending_amount * 100) / $final_amount), 2);
                    $pending_percentage = $pending_percentage . " %";
                } else {
                    $pending_percentage = "100 %";
                    $commission_invoice_id = '';
                    $bill_no = '';
                }
                if ($pending_percentage == '0 %') {
                } else {
                    $receipt_amount = number_format($row->receipt_amount);
                    $commission_amount = number_format($row->commission_amount);
                    $html .= '<tr width="100%" ' . $color . '>
                                <td>' . $row->payment_id . '</td>
                                <td>' . date("d-m-Y", strtotime($row->date)) . '</td>
                                <td class="text-right">' . $receipt_amount . '</td>
                                <td class="text-right">' . $commission_amount . '</td>';

                    if ($request->report_type == 'supplier') {
                        $cust_supp_name = $row->customer_name;
                    } else {
                        $cust_supp_name = $row->supplier_name;
                    }
                    $html .= '<td>' . $pending_percentage . '</td>
                              <td>' . $cust_supp_name . '</td>
                              <td>' . floor($due_day) . '</td>';
                    if ($request->export_pdf == 0) {
                        $html .=  '<td><a href="/account/commission/invoice/view-invoice/' . $commission_invoice_id . '" target="_blank">' . $bill_no . '</a></td>';
                    } else {
                        $html .=  '<td>' . $bill_no . '</td>';
                    }
                    $html .=  '</tr>';
                    $ptotal += $row->receipt_amount;
                    $ptotal_commission += $row->commission_amount;
                }
            }
                $ptotal1 = number_format($ptotal);
                $ptotal_commission1 = number_format($ptotal_commission);
                $html .= '<tr width="100%">
                        <td colspan="2"><b>Party Total</b></td>
                        <td class="text-right"><b>' . $ptotal1 . '</b></td>
                        <td class="text-right"><b>' . $ptotal_commission1 . '</b></td>
                        <td colspan="4"></td>
                        </tr>';
                $gtotal += $ptotal;
                $gtotal_commission += $ptotal_commission;
        }
            $gtotal1 = number_format($gtotal);
            $gtotal_commission1 = number_format($gtotal_commission);
            $html .= '<tr width="100%">
                        <td colspan="2"><b>Party Total</b></td>
                        <td class="text-right"><b>' . $gtotal1 . '</b></td>
                        <td class="text-right"><b>' . $gtotal_commission1 . '</b></td>
                        <td colspan="4"></td>
                        </tr>';
            // foreach ($data3 as $keys => $row) {
            //     $color = "";
            //     $paymentdate = strtotime($row->date);
            //     $currentdate = strtotime(Carbon::now()->format('d-m-Y'));
            //     $due_day = ($currentdate - $paymentdate) / 84600;
            //     if ($due_day )
            //     if($due_day >= 90) {
            //         $color = "style='color:red'";
            //     }
            //     $i = 0;
            //     $tot_payment1 = number_format($tot_payment);
            //     $prev_com1 = number_format($prev_com);
            //     if ($request->report_type == 'supplier') {
            //     if($supplier_name != $row->supplier_name) {
            //         $supplier_name = $row->supplier_name;
            //         $address_supp = $row->company_address;

            //         if($keys != 0) {
            //             $html .= '<tr width="100%">
            //                     <td colspan="2"><b>Party Total</b></td>
            //                     <td class="text-right"><b>'.$tot_payment1.'</b></td>
            //                     <td class="text-right"><b>'.$prev_com1.'</b></td>
            //                     <td colspan="4"></td>
			// 				</tr>';
            //             $tot_payment = 0;
            //         }

            //         $html .='<tr width="100%">
		    // 					<td colspan="8"></td>
			// 				</tr>
            //                 <tr width="100%">
            //                     <td colspan="2"><b>'.$supplier_name.'</b></td>
            //                     <td colspan="6"><b>'.$address_supp.'</b></td>
            //                 </tr>';
            //     }
            //     } else {
            //         if($customer_name != $row->customer_name) {
            //             $customer_name = $row->customer_name;
            //             $address_supp = $row->company_address;

            //             if($keys != 0) {

            //                 $html .= '<tr width="100%">
            //                         <td colspan="2"><b>Party Total</b></td>
            //                         <td class="text-right"><b>'.$tot_payment1.'</b></td>
            //                         <td class="text-right"><b>'.$prev_com1.'</b></td>
            //                         <td colspan="4"></td>
            //                     </tr>';
            //                 $tot_payment = 0;
            //             }

            //             $html .='<tr width="100%">
            //                         <td colspan="8"></td>
            //                     </tr>
            //                     <tr width="100%">
            //                         <td colspan="2"><b>'.$customer_name.'</b></td>
            //                         <td colspan="6"><b>'.$address_supp.'</b></td>
            //                     </tr>';
            //         }
            //     }


            //     if ($request->report_type == 'supplier') {
            //         $invoices = DB::table('commission_invoices as ci')
            //                     ->leftJoin(DB::raw('(SELECT "payment_id", "commission_invoice_id", "financial_year_id", "flag" FROM invoice_payment_details group by "payment_id", "commission_invoice_id", "financial_year_id", "flag") as ipd'), 'ci.id', '=', 'ipd.commission_invoice_id')
            //                     ->leftJoin(DB::raw('(SELECT "commission_invoice_id", "received_commission_amount" FROM commission_details where is_deleted = 0 group by "commission_invoice_id", "received_commission_amount") as cd'), 'ci.id', '=', 'cd.commission_invoice_id')
            //                     ->where('ipd.flag', 1)
            //                     ->where('ci.is_deleted', 0)
            //                     ->where('ipd.payment_id', $row->payment_id)
            //                     ->where('ipd.financial_year_id', $row->financial_year_id)
            //                     ->select('ci.id','ci.bill_no', 'ipd.payment_id', 'ipd.financial_year_id', 'ci.final_amount', 'cd.received_commission_amount')
            //                     ->get();
            //     } else {
            //         $invoices = DB::table('commission_invoices as ci')
            //                     ->leftJoin(DB::raw('(SELECT "payment_id", "commission_invoice_id", "financial_year_id", "flag" FROM invoice_payment_details group by "payment_id", "commission_invoice_id", "financial_year_id", "flag") as ipd'), 'ci.id', '=', 'ipd.commission_invoice_id')
            //                     ->leftJoin(DB::raw('(SELECT "commission_invoice_id", "received_commission_amount" FROM commission_details where is_deleted = 0 group by "commission_invoice_id", "received_commission_amount") as cd'), 'ci.id', '=', 'cd.commission_invoice_id')
            //                     ->where('ipd.flag', 2)
            //                     ->where('ci.is_deleted', 0)
            //                     ->where('ipd.payment_id', $row->payment_id)
            //                     ->where('ipd.financial_year_id', $row->financial_year_id)
            //                     ->select('ci.id','ci.bill_no', 'ipd.payment_id', 'ipd.financial_year_id', 'ci.final_amount', 'cd.received_commission_amount')
            //                     ->get();
            //     }
            //     if (count($invoices)) {
            //         $total = 0;
            //         $pending_parcent = collect($invoices)->groupBy('id');
            //         foreach($pending_parcent as $inv) {
            //             foreach($inv as $pp){
            //                 $total += (int)$pp->received_commission_amount;
            //                 $final_amount = $pp->final_amount;
            //                 $commission_invoice_id = $pp->id;
            //                 $bill_no = $pp->bill_no;
            //             }
            //         }

            //         $pending_amount = (int)$final_amount - (int)$total;
            //         $pending_percentage = round((($pending_amount * 100) / $final_amount),2);
            //         $pending_percentage = $pending_percentage." %";

            //     } else {
            //         $pending_percentage = "100 %";
			// 		$commission_invoice_id = '';
			// 		$bill_no = '';
            //     }

            //     if ($pending_percentage == '0 %') {

            //     } else {
            //     $receipt_amount = number_format($row->receipt_amount);
            //     $commission_amount = number_format($row->commission_amount);
            //         $html .= '<tr width="100%" '.$color.'>
            //                     <td>'.$row->payment_id.'</td>
            //                     <td>'.date("d-m-Y", strtotime($row->date)).'</td>
            //                     <td class="text-right">'.$receipt_amount.'</td>
            //                     <td class="text-right">'.$commission_amount.'</td>';

            //     if ($request->report_type == 'supplier') {
            //         $cust_supp_name = $row->customer_name;
            //     } else {
            //         $cust_supp_name = $row->supplier_name;
            //     }
            //         $html .= '<td>'.$pending_percentage.'</td>
            //                   <td>'.$cust_supp_name.'</td>
            //                   <td>'.floor($due_day).'</td>';
            //         if ($request->export_pdf == 0) {
            //             $html .=  '<td><a href="/account/commission/invoice/view-invoice/'.$commission_invoice_id.'" target="_blank">'.$bill_no.'</a></td>';
            //         } else {
            //             $html .=  '<td>'.$bill_no.'</td>';
            //         }
            //         $html .=  '</tr>';
            //     $prev_com = $row->total_comm_amount;
            //     $tot_payment += $row->receipt_amount;
            //     $total_payment += $row->receipt_amount;
            //     $total_commission_amount += $row->commission_amount;
            //     }
            // }
            // $total_payment1 = number_format($total_payment);
            // $total_commission_amount1 = number_format($total_commission_amount);
            // if (!empty($data3)) {
            //     $tot_payment1 = number_format($tot_payment);
            //     $prev_com1 = number_format($prev_com);
            //     $html .= '<tr width="100%">
            //             <td colspan="2"><b>Party Total</b></td>
            //             <td class="text-right"><b>'.$tot_payment1.'</b></td>
            //             <td class="text-right"><b>'.$prev_com1.'</b></td>
            //             <td colspan="4"></td>
            //             </tr>
            //             <tr width="100%">
            //                 <td colspan="8">&nbsp;</td>
            //             </tr>
            //             <tr width="100%">
            //                 <td colspan="2"><b>Grand Total</b></td>
            //                 <td class="text-right"><b>'.$total_payment1.'</b></td>
            //                 <td class="text-right"><b>'.$total_commission_amount1.'</b></td>
            //                 <td colspan="4"></td>
            //             </tr>';
            // }
            $data['detail'] = $data3;
        }
        $data['table'] = $html;
        $data['report_type'] = $request->report_type;

        if ($request->export_pdf == 1) {
            $pdf = PDF::loadView('reports.outstanding_commission_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/outstanding-commission-reports');
            $fileName =  'Outstanding-Commission-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/outstanding-commission-reports/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Outstanding-Commission-Report-' . time() . '.xlsx';
            Excel::store(new OutstandingCommissionExport($data, $request), 'excel-sheets/outstanding-commission-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/outstanding-commission-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }
    }

    public function listOutstandingCommissionMonthWiseSummeryData(Request $request) {
        if ($request->report_type == 'supplier') {
            $data1 = DB::table('payments as p')
            ->join(DB::raw('(SELECT "commission_percentage", "customer_id", "supplier_id", "flag" FROM company_commissions group by "commission_percentage","customer_id", "supplier_id", "flag") as "ccomm_per2"'), function($join){
                $join->on('p.receipt_from', '=', 'ccomm_per2.customer_id')
                ->on('p.supplier_id', '=', 'ccomm_per2.supplier_id')
                ->where('ccomm_per2.flag', 1);
            })
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'p.receipt_from', '=', 'cc.id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id", "company_city" FROM companies group by "company_name", "id", "company_city") as "cs"'), 'p.supplier_id', '=', 'cs.id')
            ->leftJoin(DB::raw('(SELECT "address", "company_id" FROM company_addresses WHERE address_type = 1 group by "address", "company_id" limit 1) as "cadd"'), 'p.supplier_id', '=', 'cadd.company_id')
            ->select('p.payment_id', 'p.financial_year_id', 'p.date', 'p.receipt_from', 'p.supplier_id', 'p.receipt_amount', 'cs.company_name as supplier_name', 'cc.company_name as customer_name', DB::raw('COALESCE(ccomm_per2.commission_percentage, 2) as commission_percentage'), DB::raw("CONCAT(TO_CHAR(p.date, 'MON'),'-',TO_CHAR(p.date, 'YYYY')) as monthyear"),DB::raw('EXTRACT(YEAR FROM p.date) as year'), DB::raw("TO_CHAR(p.date, 'Month') as month"),'cadd.address as company_address')
            ->where('p.is_deleted', 0)
            ->whereNot('p.receipt_amount', 0)
            ->where('p.old_commission_status', 0)
            ->where('p.right_of_amount', 0);
        } else {
            $data1 = DB::table('payments as p')
            ->join(DB::raw('(SELECT "commission_percentage", "customer_id", "supplier_id", "flag" FROM company_commissions group by "commission_percentage","customer_id", "supplier_id", "flag") as "ccomm_per2"'), function($join){
                $join->on('p.receipt_from', '=', 'ccomm_per2.customer_id')
                ->on('p.supplier_id', '=', 'ccomm_per2.supplier_id')
                ->where('ccomm_per2.flag', 2);
            })
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'p.supplier_id', '=', 'cs.id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id", "company_city" FROM companies group by "company_name", "id", "company_city") as "cc"'), 'p.receipt_from', '=', 'cc.id')
            ->leftJoin(DB::raw('(SELECT "address", "company_id" FROM company_addresses WHERE address_type = 1 group by "address", "company_id" limit 1) as "cadd"'), 'p.receipt_from', '=', 'cadd.company_id')
            ->select('p.payment_id', 'p.financial_year_id', 'p.date', 'p.receipt_from', 'p.supplier_id', 'p.receipt_amount', 'cs.company_name as supplier_name', 'cc.company_name as customer_name', DB::raw('COALESCE(ccomm_per2.commission_percentage, 2) as commission_percentage'), DB::raw("CONCAT(TO_CHAR(p.date, 'MON'),'-',TO_CHAR(p.date, 'YYYY')) as monthyear"),DB::raw('EXTRACT(YEAR FROM p.date) as year'), DB::raw("TO_CHAR(p.date, 'Month') as month"),'cadd.address as company_address')
            ->where('p.is_deleted', 0)
            ->whereNot('p.receipt_amount', 0)
            ->where('p.customer_commission_status', 0)
            ->where('p.right_of_amount', 0);
        }

        $supplier = array();
        $customer = array();

        if ($request->supplier && $request->supplier['id']) {
            $company_details = Company::where('id', $request->supplier['id'])->first();
            $link_companies = LinkCompanies::whereRaw('company_id = ' . $request->supplier['id'] . ' OR company_id = (SELECT company_id FROM link_companies WHERE link_companies_id = ' . $request->supplier['id'] . ')')->get();
            foreach ($link_companies as $key => $value) {
                array_push($supplier, $value->company_id);
                array_push($supplier, $value->link_companies_id);
            }
            $supplier = array_unique($supplier);
            if (count($supplier) < 1) {
                $supplier = [$request->supplier['id']];
            }
            if ($company_details) {

                $data1 = $data1->WhereIn('p1.supplier_id', $supplier);
                $supplier_data = [];
                foreach($supplier as $row) {
                    $supplier_data[] = Company::where('id', $row)->select('company_name')->first()->company_name;
                }
                $data['sup_disp_name'] = implode(', ', $supplier_data);
            }
        }

        if ($request->customer && $request->customer['id']) {
            $company_details = Company::where('id', $request->customer['id'])->first();
            $link_companies = LinkCompanies::whereRaw('company_id = ' . $request->customer['id'] . ' OR company_id = (SELECT company_id FROM link_companies WHERE link_companies_id = ' . $request->customer['id'] . ')')->get();
            foreach ($link_companies as $key => $value) {
                array_push($customer, $value->company_id);
                array_push($customer, $value->link_companies_id);
            }
            $customer = array_unique($customer);
            if (count($customer) < 1) {
                $customer = [$request->customer['id']];
            }
            if ($company_details) {

                $data1 = $data1->WhereIn('p1.customer_id', $customer);
                foreach($customer as $row) {
                    $customer_data[] = Company::where('id', $row)->select('company_name')->first()->company_name;
                }
                $data['cust_disp_name'] = implode(',  ', $customer_data);
            }
        }
        if ($request->start_date && $request->end_date) {
            $data1 = $data1->whereRaw("p.date::date >= '" . $request->start_date . "'")
                    ->whereRaw("p.date::date <= '" . $request->end_date . "'");
        }
        if ($request->sorting && $request->sorting['id']) {
            $sorting = $request->sorting['id'];
            if ($sorting == 1) {
                $data1 = $data1->orderBy('cs.company_name', 'asc');
            } else if ($sorting == 2) {
                $data1 = $data1->orderBy('cs.company_name', 'desc');
            } else if ($sorting == 3) {
                $data1 = $data1->orderBy('cc.company_name', 'asc');
            }  else if ($sorting == 4) {
                $data1 = $data1->orderBy('cc.company_name', 'desc');
            } else if ($sorting == 5) {
                $data1 = $data1->orderBy('p.date', 'asc');
            } else if ($sorting == 6) {
                $data1 = $data1->orderBy('p.date', 'desc');
            }
        }

        $data1 = collect($data1->get())->groupBy('monthyear');

        $sup = '';
        $sup1 = '';

        if ($request->supplier != '') {
            if($data['sup_disp_name']) {
                $sup .= "Supplier: " .$data['sup_disp_name'];
            } else {
                $sup .= "Supplier: " .$request->supplier['company_name'];
            }
        } else {
            $sup .= "All Parties";
        }

        if ($request->customer != '') {
            if($data['sup_disp_name']) {
                $sup1 .= "Customer: " .$data['cust_disp_name'];
            } else {
                $sup1 .= "Customer: " .$request->customer['company_name'] ;
            }
        } else {
            $sup1 .= "All Parties";
        }

        $html = '';
        if ($request->report_type == 'supplier'){
                $html .= '<tr width="100%">
                            <th colspan="4" class="text-center">'.$sup.'</th>
                        </tr>';
        } else {
                $html .= '<tr width="100%">
                            <th colspan="4" class="text-center">'.$sup1.'</th>
                        </tr>';
        }
        $finaldata = array();
        $grandtotal = $grandcommissiontoal = 0;
        if ($request->show_detail == 1) {
            foreach ($data1 as $keys => $row) {
                $totalmonthamount = $totalmonthcommission = 0;
                if ($request->report_type == 'supplier') {
                    $supplierdata = collect($row)->groupBy('supplier_name');
                } else {
                    $supplierdata = collect($row)->groupBy('customer_name');
                }
                $finaldata[$keys] = $supplierdata;
                $html .= '<tr width="100%">
                            <td colspan="4"><b></td>
                        </tr>
                        <tr width="100%" class="bg-gray">
                            <td class="text-center" style="background-color:#383634; color:#ffffff" colspan="4"><b>'.$keys.'</b></td>
                        </tr>
                        <tr width="100%">
                            <th>Sr No</th>
                            <th>Party Name</th>
                            <th class="text-right">Amount</th>
                            <th>Commission Amout</th>
                        </tr>';
                $i = 0;
                foreach($supplierdata as $key1=>$row1) {
                    $html .= '<tr width="100%">
                                <td>'.++$i.'</td>
                                <td>'.$key1.'</td>';
                    $totalamount = $commissionamount = 0;
                    foreach ($row1 as $key2 => $paymentdata) {

                        $commission_amount = round($paymentdata->receipt_amount * $paymentdata->commission_percentage / 100);
                        $totalamount += $paymentdata->receipt_amount;
                        $commissionamount += $commission_amount;
                    }
                    $totalamount1 = number_format($totalamount);
                    $commissionamount1 = number_format($commissionamount);
                    $html .= '<td class="text-right">'.$totalamount1.'</td>
                                <td class="text-right">'.$commissionamount1.'</td>
                            </tr>';
                    $totalmonthamount += $totalamount;
                    $totalmonthcommission += $commissionamount;
                }
                $totalmonthamount1 = number_format($totalmonthamount);
                $totalmonthcommission1 = number_format($totalmonthcommission);
                $html .= '<tr width="100%" style="background-color:#e0cebc">
                                <td colspan="2"><b>Monthly Total</b></td>
                                <td class="text-right"><b>'.$totalmonthamount1.'</b></td>
                                <td class="text-right"><b>'.$totalmonthcommission1.'</b></td>

                            </tr>';
                $grandtotal += $totalmonthamount;
                $grandcommissiontoal += $totalmonthcommission;
            }
            $grandtotal1 = number_format($grandtotal);
            $grandcommissiontoal1 = number_format($grandcommissiontoal);
            if (count($data1) != 0) {
                        $html .= '<tr width="100%">
                                <td colspan="2"><b>Grand Total</b></td>
                                <td class="text-right"><b>'.$grandtotal1.'</b></td>
                                <td class="text-right"><b>'.$grandcommissiontoal1.'</b></td>

                            </tr>';
            } else {
                $html .= '<tr width="100%">
                                <td colspan="4" class="text-center"><b>Record Not Found</b></td>
                            </tr>';
            }
        } else {
        foreach ($data1 as $keys => $row) {
            $totalmonthamount = $totalmonthcommission = 0;
            if ($request->report_type == 'supplier') {
                $supplierdata = collect($row)->groupBy('supplier_name');
            } else {
                $supplierdata = collect($row)->groupBy('customer_name');
            }
            $finaldata[$keys] = $supplierdata;
            $html .= '<tr width="100%">
                        <td colspan="4"><b></td>
                    </tr>
                    <tr width="100%" class="bg-gray">
                        <td class="text-center" style="background-color:#383634; color:#ffffff" colspan="4"><b>'.$keys.'</b></td>
                    </tr>
                    <tr width="100%">
                        <th>Sr No</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Commission Amout</th>';
            if ($request->report_type == 'supplier') {
                $html .= '<th>Customer</th>';
            } else {
                $html .= '<th>Supplier</th>';
            }

            $html .= '</tr>';

            foreach($supplierdata as $key1=>$row1) {
                $html .= '<tr width="100%" style="background-color:#f8f8f8">
                            <td colspan="2"><b>'.$key1.'</b></td>
                            <td colspan="2"><b>'.$row1[0]->company_address.'</b></td>
                        </tr>';
                $totalamount = $commissionamount = 0;
                foreach ($row1 as $key2 => $paymentdata) {

                    $commission_amount = round($paymentdata->receipt_amount * $paymentdata->commission_percentage / 100);
                    $totalamount += $paymentdata->receipt_amount;
                    $commissionamount += $commission_amount;
                    $receipt_amount = number_format($paymentdata->receipt_amount);
                    $commission_amount1 = number_format($commission_amount);
                    $html .= '<tr width="100%">
                            <td>'.++$key2.'</td>
                            <td class="text-right">'.$receipt_amount.'</td>
                            <td class="text-right">'.$commission_amount1.'</td>';
                    if ($request->report_type == 'supplier') {
                        $html .=  '<td>'.$paymentdata->customer_name.'</td>';
                    } else {
                        $html .= '<td>'.$paymentdata->supplier_name.'</td>';
                    }

                        $html .= '</tr>';
                }
                $totalamount1 = number_format($totalamount);
                $commissionamount1 = number_format($commissionamount);
                $html .= '<tr width="100%">
                            <td><b>Party Total</b></td>
                            <td class="text-right"><b>'.$totalamount1.'</b></td>
                            <td class="text-right"><b>'.$commissionamount1.'</b></td>
                            <td></td>
                        </tr>';
                $totalmonthamount += $totalamount;
                $totalmonthcommission += $commissionamount;
            }
            $totalmonthamount1 = number_format($totalmonthamount);
            $totalmonthcommission1 = number_format($totalmonthcommission);
            $html .= '<tr width="100%" style="background-color:#e0cebc">
                            <td><b>Monthly Total</b></td>
                            <td class="text-right"><b>'.$totalmonthamount1.'</b></td>
                            <td class="text-right"><b>'.$totalmonthcommission1.'</b></td>
                            <td></td>
                        </tr>';
            $grandtotal += $totalmonthamount;
            $grandcommissiontoal += $totalmonthcommission;
        }
        $grandtotal1 = number_format($grandtotal);
        $grandcommissiontoal1 = number_format($grandcommissiontoal);
             if (count($data1) != 0) {
                $html .= '<tr width="100%">
                    <td><b>Grand Total</b></td>
                    <td class="text-right"><b>'.$grandtotal1.'</b></td>
                    <td class="text-right"><b>'.$grandcommissiontoal1.'</b></td>
                    <td></td>
                    </tr>';
            } else {
                $html .= '<tr width="100%">
                                <td colspan="4" class="text-center"><b>Record Not Found</b></td>
                            </tr>';
            }
        }

        $data['table'] = $html;
        $data['finaldata'] = $finaldata;
        if ($request->export_pdf == 1) {
            $pdf = PDF::loadView('reports.outstanding_commission_month_wise_summery_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/outstanding_commission_month_wise_summery_export_pdf');
            $fileName =  'Outstanding-Commission-Month-Wise-Summery-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/outstanding_commission_month_wise_summery_export_pdf/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Outstanding-Commission-Month-Wise-Summery-Report-' . time() . '.xlsx';
            Excel::store(new OutstandingCommissionMonthWiseSummeryExport($data, $request), 'excel-sheets/outstanding-commission-month-wise-summery-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/outstanding-commission-month-wise-summery-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }
    }

    public function listAvarageCommissionDaysData(Request $request) {
        $data1 = DB::table('commission_invoices as ci')
                    ->join('commission_details as cd',function($join) {
                        $join->on('cd.commission_invoice_id','=','ci.id')
                                ->where('cd.is_deleted', 0);
                    })
                    ->join('commissions as c',function($join) {
                        $join->on('c.id','=','cd.c_increment_id')
                            ->where('c.is_deleted', 0);
                    })
                    ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'ci.customer_id', '=', 'cc.id')
                    ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'ci.supplier_id', '=', 'cs.id')
                    ->select('ci.id', 'ci.bill_date', 'c.commission_date', 'ci.supplier_id', 'ci.customer_id', 'cc.company_name as customer_name', 'cs.company_name as supplier_name');

        if ($request->supplier && $request->supplier['id']) {
            $data1 = $data1->where('ci.supplier_id', $request->supplier['id']);
        }

        if ($request->start_date && $request->end_date) {
            $data1 = $data1->whereRaw("ci.bill_date::date >= '" . $request->start_date . "'")
                    ->whereRaw("ci.bill_date::date <= '" . $request->end_date . "'");
        }
        $data1 = $data1->get();
        $cidata = array();
        foreach($data1 as $key => $row) {
            $cidata[$key]['id'] = $row->id;
            $cidata[$key]['bill_date'] = $row->bill_date;
            $cidata[$key]['commission_date'] = $row->commission_date;
            if ($row->supplier_id == 0){
                $cidata[$key]['company_id'] = $row->customer_id;
                $cidata[$key]['company_name'] = $row->customer_name;
            } else {
                $cidata[$key]['company_id'] = $row->supplier_id;
                $cidata[$key]['company_name'] = $row->supplier_name;
            }
        }
        $cidata = collect($cidata)->groupBy('company_id');
        $companydata = array();
        foreach($cidata as $key1 => $row1) {
            $companydetail = array();

            $noofbill = count($row1);
            $totalday = 0;
            foreach ($row1 as $c) {
                $companyname = $c['company_name'];
                $billdate = strtotime($c['bill_date']);
                $commissiondate = strtotime($c['commission_date']);
                $diff = $commissiondate - $billdate;
                $day = $diff / 84600;
                $totalday += $day;
            }
            $avaragedays = $totalday / $noofbill;
            $company_detail['company_id'] = $key1;
            $company_detail['company_name'] = $companyname;
            $company_detail['totalbill'] = $noofbill;
            $company_detail['avarageday'] = floor($avaragedays);
            array_push($companydata, $company_detail);
        }
        if ($request->sorting && $request->sorting['id']) {
            $sorting = $request->sorting['id'];
            if ($sorting == 3) {
                usort($companydata, function ($item1, $item2) {
                    if ($item1['avarageday'] == $item2['avarageday']) return 0;
                    return $item1['avarageday'] < $item2['avarageday'] ? -1 : 1;
                });
            } else if ($sorting == 4) {
                usort($companydata, function ($item1, $item2) {
                    if ($item1['avarageday'] == $item2['avarageday']) return 0;
                    return $item1['avarageday'] > $item2['avarageday'] ? -1 : 1;
                });
            }
        }

        $html = '';

        if (!empty($companydata)) {
            $i = 1;
            $html .= '<tr>
                <td><b>No</b></td>
                <td><b>Company Name</b></td>
                <td><b>Avarage Days</b></td>
            </tr>';
            foreach ($companydata as $cd) {
                $html .= '<tr>
                            <td>'.$i++.'</td>';
                        if ($request->export_pdf != 1) {
                            $html.= '<td><a href="#" class="view-details" data-id="'.$cd['company_id'].'">'.$cd['company_name'].'</a></td>';
                        } else {
                            $html.= '<td>'.$cd['company_name'].'</td>';
                        }

                    $html .='<td>'.$cd['avarageday'].' Days</td>
                        </tr>';
            }
        } else {
            $html .= '<tr>
            <td colspan=3>No Data Found<td>
            </tr>';
        }
        $data['company_data'] = $companydata;
        $data['table'] = $html;
        if ($request->export_pdf == 1) {
            $pdf = PDF::loadView('reports.avarage_commission_days_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/avarage-commission-days-reports');
            $fileName =  'Avarage-Commission-Days-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/avarage-commission-days-reports/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Avarage-Commission-Days-Report' . time() . '.xlsx';
            Excel::store(new AvarageCommissionDaysExport($data, $request), 'excel-sheets/avarage-commission-days-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/avarage-commission-days-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }

    }

    public function listDailyCommissionData(Request $request) {
        $data1 = DB::table('commissions as c')
                    ->where('c.is_deleted', 0)
                    ->whereRaw("c.created_at::date >= '" . $request->start_date . " 00:00:00'")
                    ->whereRaw("c.created_at::date <= '" . $request->start_date . " 23:59:59'")
                    ->orderBy('c.commission_id', 'desc')
                    ->get();
        $data1 = collect($data1)->groupBy('commission_reciept_mode');
        $html = '';
        $gtotal = 0;
        if (count($data1) != 0) {
        foreach ($data1 as $key => $row) {
            $html .= '<tr width="100%" class="bg-gray text-white">
                <td colspan = "4" class="text-center"><b> Received By '.$key.'</b></td>
                </tr>
                <tr width="100%">
                    <td><b>Commission Id</b></td>
                    <td><b>Commission Date</b></td>
                    <td class="text-right"><b>Commission Amount</b></td>
                    <td><b>Received Date</b></td>
                </tr>';
            $total = 0;
            foreach($row as $key1 => $row1){
                $commission_payment_amount = number_format($row1->commission_payment_amount);
                $html .= '<tr width="100%">
                    <td>'.$row1->commission_id.'</td>
                    <td>'.$row1->commission_date.'</td>
                    <td class="text-right">'.$commission_payment_amount.'</td>
                    <td>'.date("h:i A",strtotime($row1->created_at)).'</br>'.date("d M, Y",strtotime($row1->created_at)).'</td>
                </tr>';
                $total += $row1->commission_payment_amount;
            }
            $total1 = number_format($total);
            $html .= '<tr width="100%">
                <td></td>
                <td><b>Total</b></td>
                <td class="text-right"><b>'.$total.'</b></td>
                <td></td>
                </tr>';
            $gtotal += $total;
        }
        $gtotal1 = number_format($gtotal);
        $html .= '<tr width="100%">
                <td></td>
                <td><b>Grand Total</b></td>
                <td class="text-right"><b>'.$gtotal1.'</b></td>
                <td></td>
                </tr>';
        } else {
            $html .= '<tr class="text-center" width="100%">
                <td>Record Not Found</td>
                </tr>';
        }
        $data['table'] = $html;
        if ($request->export_pdf == 1) {
            $pdf = PDF::loadView('reports.daily_commission_report_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/daily-commission-reports');
            $fileName =  'Daily-Commission-Days' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/daily-commission-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }
    }

    public function listCommissionCollectionData(Request $request) {
        $data1 = DB::table('commissions as co')
                    ->Join(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'co.supplier_id', '=', 'cs.id')
                    ->Join(DB::raw('(SELECT "c_increment_id", "commission_invoice_id", "is_deleted" FROM commission_details group by "c_increment_id", "commission_invoice_id", "is_deleted") as "cd"'), 'co.id', '=', 'cd.c_increment_id')
                    ->Join(DB::raw('(SELECT "id", "is_deleted", "financial_year_id", "bill_date" FROM commission_invoices group by "id", "is_deleted", "financial_year_id", "bill_date") as "ci"'), 'cd.commission_invoice_id', '=', 'ci.id')
                    ->where('co.is_deleted', 0)
                    ->where('ci.is_deleted', 0)
                    ->where('cd.is_deleted', 0)
                    ->select('co.*', 'cs.company_name as company_name', 'cs.id as company_id');
        $data2 = DB::table('commissions as co')
                    ->Join(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'co.customer_id', '=', 'cc.id')
                    ->Join(DB::raw('(SELECT "c_increment_id", "commission_invoice_id", "is_deleted" FROM commission_details group by "c_increment_id", "commission_invoice_id", "is_deleted") as "cd"'), 'co.id', '=', 'cd.c_increment_id')
                    ->Join(DB::raw('(SELECT "id", "is_deleted", "financial_year_id", "bill_date" FROM commission_invoices group by "id", "is_deleted", "financial_year_id", "bill_date") as "ci"'), 'cd.commission_invoice_id', '=', 'ci.id')
                    ->where('co.is_deleted', 0)
                    ->where('ci.is_deleted', 0)
                    ->where('cd.is_deleted', 0)
                    ->select('co.*','cc.company_name as company_name', 'cc.id as company_id');

        $companies = array();
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
                $main_cmp_id = $company_details->id;
                array_push($companies, $main_cmp_id);
                foreach ($link_companies as $row_link_companies) {
                    array_push($companies, $row_link_companies->link_companies_id);
                }
            }
            if ($request->company['company_type'] == 2)
            {
                $data1 = $data1->whereIn('co.customer_id', $companies);
                $data2 = $data2->whereIn('co.customer_id', $companies);
            } else if ($request->company['company_type'] == 3) {
                $data1 = $data1->whereIn('co.supplier_id', $companies);
                $data2 = $data2->whereIn('co.supplier_id', $companies);
            }
        }

        if ($request->mode && $request->mode['id']) {
            $mode = '';
            if ($request->mode['id'] == 2) {
                $mode = 'cash';
            } else if ($request->mode['id'] == 3) {
                $mode = 'cheque';
            }
            if ($request->mode['id'] != 1){
                $data1 = $data1->where('co.commission_reciept_mode', $mode);
                $data2 = $data2->where('co.commission_reciept_mode', $mode);
            }
        }

        if ($request->agent && $request->agent['id']) {
            $data1 = $data1->where('co.commission_account', $request->agent['id']);
            $data2 = $data2->where('co.commission_account', $request->agent['id']);
        }

        if ($request->fyear && $request->fyear['id']) {
            $data1 = $data1->where('ci.financial_year_id', $request->fyear['id']);
            $data2 = $data2->where('ci.financial_year_id', $request->fyear['id']);
        }

        if ($request->start_date && $request->end_date) {
            $data1 = $data1->whereRaw("ci.bill_date::date >= '" . $request->start_date . "'")
                    ->whereRaw("ci.bill_date::date <= '" . $request->end_date . "'");
            $data2 = $data2->whereRaw("ci.bill_date::date >= '" . $request->start_date . "'")
                    ->whereRaw("ci.bill_date::date <= '" . $request->end_date . "'");
        }

        if ($request->sorting && $request->sorting['id']) {
            $sorting = $request->sorting['id'];
            if ($sorting == 1) {
                $data1 = $data1->orderBy('cs.company_name', 'asc');
                $data2 = $data2->orderBy('cs.company_name', 'asc');
            } else if ($sorting == 2) {
                $data1 = $data1->orderBy('cs.company_name', 'desc');
                $data2 = $data2->orderBy('cs.company_name', 'desc');
            }
        }
        $data2 = $data2->union($data1)->get();

        $html = '';
        $html .= '<tr width="100%">
                    <th>Id</th>
                    <th>Company</th>
                    <th class="text-center">Date</th>
                    <th>Account</th>
                    <th>Mode</th>
                    <th>Dep.Bank</th>
                    <th>Chq.Date</th>
                    <th>Chq/DD No</th>
                    <th>Chq/DD Bank</th>
                    <th class="text-right">Amount</th>
                </tr>';
        $total = 0;
        foreach ($data2 as $key => $row) {
            if ($row->commission_reciept_mode == 'cash') {
               $commission_deposite_bank = '-';
               $commission_cheque_date = '-';
               $commission_cheque_dd_no = '-';
               $commission_cheque_dd_bank = '-';
            } else {
                $commission_deposite_bank = DB::table('bank_details')->where('id', $row->commission_deposite_bank)->first()->name;
                $commission_cheque_dd_bank = DB::table('bank_details')->where('id', $row->commission_cheque_dd_bank)->first()->name;
                $commission_cheque_date = $row->commission_cheque_date;
                $commission_cheque_dd_no = $row->commission_cheque_dd_no;
            }
            $commission_account = DB::table('agents')->where('id', $row->commission_account)->first()->name;
            $html .= '<tr width="100%">
                        <td>'.$row->commission_id.'</td>
                        <td>'.$row->company_name.'</td>
                        <td>'.$row->commission_date.'</td>
                        <td>'.$commission_account.'</td>
                        <td>'.$row->commission_reciept_mode.'</td>
                        <td>'.$commission_deposite_bank.'</td>
                        <td>'.$commission_cheque_date.'</td>
                        <td>'.$commission_cheque_dd_no.'</td>
                        <td>'.$commission_cheque_dd_bank.'</td>
                        <td class="text-right">'.$row->commission_payment_amount.'</td>
                    </tr>';
                    $total += $row->commission_payment_amount;
        }
        $html .= '<tr width="100%">
            <td colspan="9"><b>Total</b></td>
            <td class="text-right"><b>'.$total.'</b></td>
        </tr>';
        $data['table'] = $html;
        $data['result'] = $data2;
        if ($request->export_pdf == 1) {
            $pdf = PDF::loadView('reports.commission_collection_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/commission-collection-reports');
            $fileName =  'Commission-Commission-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/commission-collection-reports/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Commission-Commission-Report-' . time() . '.xlsx';
            Excel::store(new CommissionCollectionExport($data, $request), 'excel-sheets/commission-collection-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/commission-collection-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }
    }

    public function listCommissionRightofData(Request $request) {
        $data1 = DB::table('commission_invoices as ci')
                ->leftJoin(DB::raw('(SELECT "company_name", "id", "company_state" FROM companies group by "company_name", "id", "company_state") as "cc"'), 'ci.customer_id', '=', 'cc.id')
                ->leftJoin(DB::raw('(SELECT "company_name", "id", "company_state" FROM companies group by "company_name", "id", "company_state") as "cs"'), 'ci.supplier_id', '=', 'cs.id')
                ->leftJoin(DB::raw('(SELECT "name", "id" FROM agents group by "name", "id") as "agent"'), DB::raw('cast(ci.agent_id as integer)'), '=', 'agent.id')
                ->where('ci.is_deleted', 0)
                ->whereNot('ci.right_of_amount', 0)
                ->orderBy('ci.id', 'asc')
                ->select('ci.*', 'cc.company_name as customer_name', 'cs.company_name as supplier_name', 'cc.company_state as customer_state', 'cs.company_state as supplier_state', 'agent.name as agentname');

        if ($request->start_date && $request->end_date) {
            $data1 = $data1->whereRaw("ci.bill_date::date >= '" . $request->start_date . "'")
                            ->whereRaw("ci.bill_date::date <= '" . $request->end_date . "'");
        }

        if ($request->recipient && $request->recipient['id']) {
            if ($request->recipient['company_type'] == 2) {
                $data1 = $data1->where('ci.customer_id', $request->recipient['id']);
            } else {
                $data1 = $data1->where('ci.supplier_id', $request->recipient['id']);
            }
        }

        if ($request->agent && $request->agent['id']) {
            $data1 = $data1->where('ci.agent_id', $request->agent['id']);
        }

        if ($request->invno) {
            $data1 = $data1->where('ci.bill_no','ilike', '%' . $request->invno . '%');
        }

        if ($request->due) {
            $todaydate = Carbon::now()->format('Y-m-d');
            $data1 = $data1->whereRaw('\'' . $todaydate . '\'' . ' - ci.bill_date >= '. $request->due);;
        }

        $data1 = $data1->get();
        $html = '';
        $html .= '<tr>
                    <th>Inv NO</th>
                    <th>Inv Date</th>
                    <th>Recipient</th>
                    <th>Agent</th>
                    <th>State</th>
                    <th>Gross Amount</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                    <th>Total GST</th>
                    <th>TDS</th>
                    <th>Net Amount</th>
                    <th>Right of Amt</th>
                    <th>Remark</th>
                </tr>';
        $total=0; $tot_cgst=0; $tot_sgst=0; $tot_igst=0; $tot_gst=0; $tot_tds=0; $tot_net=0; $tot_right=0;
        if (count($data1) != 0) {
        foreach ($data1 as $key => $row) {
            if ($row->supplier_id != 0) {
                $company = $row->supplier_name;
                $state = DB::table('states')->where('id', $row->supplier_state)->first()->name;
            } else {
                $company = $row->customer_name;
                $state = $state = DB::table('states')->where('id', $row->customer_state)->first()->name;;
            }
            $total_gst = 0;

            $total_gst = $row->cgst_amount + $row->cgst_amount + $row->igst_amount;
            $total += $row->commission_amount;
            $tot_gst += $total_gst;
            $tot_cgst += $row->cgst_amount;
            $tot_sgst += $row->sgst_amount;
            $tot_igst += $row->igst_amount;
            $tot_tds +=  $row->tds_amount;
            $tot_net +=  $row->final_amount;
            $tot_right +=  $row->right_of_amount;
            $html .= '<tr>
                        <td>'.$row->bill_no.'</td>
                        <td>'.$row->bill_date.'</td>
                        <td>'.$company.'</td>
                        <td>'.$row->agentname.'</td>
                        <td>'.$state.'</td>
                        <td>'.$row->commission_amount.'</td>
                        <td>'.$row->cgst_amount.'</td>
                        <td>'.$row->sgst_amount.'</td>
                        <td>'.$row->igst_amount.'</td>
                        <td>'.$total_gst.'</td>
                        <td>'.$row->tds_amount.'</td>
                        <td>'.$row->final_amount.'</td>
                        <td>'.$row->right_of_amount.'</td>
                        <td>'.$row->right_of_remark.'</td>
                    </tr>';
        }
        $html .= '<tr>
                        <td colspan="5"><b>Total</td>
                        <td><b>'.$total.'</b></td>
                        <td><b>'.$tot_cgst.'</b></td>
                        <td><b>'.$tot_sgst.'</b></td>
                        <td><b>'.$tot_igst.'</b></td>
                        <td><b>'.$tot_gst.'</b></td>
                        <td><b>'.$tot_tds.'</b></td>
                        <td><b>'.$tot_net.'</b></td>
                        <td><b>'.$tot_right.'</b></td>
                        <td></td>
                    </tr>';
        } else {
            $html .= '<tr>
                    <td class="text-center" colspan="14">Record Not Found </td>
                </tr>';
        }
        $data['table'] = $html;
        $data['result'] = $data1;
        if ($request->export_pdf == 1) {
            $pdf = PDF::loadView('reports.commission_rightof_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/commission-rightof-reports');
            $fileName =  'Commission-Rightof-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/commission-rightof-reports/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Commission-Rightof-Report-' . time() . '.xlsx';
            Excel::store(new CommissionRightofExport($data, $request), 'excel-sheets/commission-rightof-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/commission-rightof-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }
    }
}
