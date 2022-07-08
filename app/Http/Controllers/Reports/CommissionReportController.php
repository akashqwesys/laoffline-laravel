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

    public function listOutstandingCommissionData(Request $request) {
        $data1 = DB::table('payments as p1')
                ->leftJoin(DB::raw('(SELECT "commission_percentage", "customer_id", "supplier_id", "flag" FROM company_commissions group by "commission_percentage","customer_id", "supplier_id", "flag") as "ccomm_per1"'), function($join){
                    $join->on('p1.receipt_from', '=', 'ccomm_per1.customer_id')
                    ->on('p1.supplier_id', '=', 'ccomm_per1.supplier_id')
                    ->where('ccomm_per1.flag', 1);
                })
                ->select('p1.suppllier_id',DB::raw('SUM(ROUND(p1.receipt_amount * ccomm_per1.commission_percentage / 100)) as total_comm_amount'))
                ->get();

        $data2 = DB::table('payments as p')
                ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'p.receipt_from', '=', 'cc.id')
                ->leftJoin(DB::raw('(SELECT "company_name", "id", "company_city" FROM companies group by "company_name", "id", "company_city") as "cs"'), 'p.supplier_id', '=', 'cs.id')
                ->leftJoin(DB::raw('(SELECT "address", "company_id" FROM company_addresses group by "address", "company_id") as "cadd"'), 'p.supplier_id', '=', 'cadd.company_id')
                ->leftJoin(DB::raw('(SELECT "commission_percentage", "customer_id", "supplier_id", "flag" FROM company_commissions group by "commission_percentage","customer_id", "supplier_id", "flag") as "ccomm_per2"'), function($join){
                    $join->on('p.receipt_from', '=', 'ccomm_per2.customer_id')
                    ->on('p.supplier_id', '=', 'ccomm_per2.supplier_id')
                    ->where('ccomm_per2.flag', 1);
                })
                ->select('cc.company_name as customer_name', 'cs.company_city as city_name','cs.company_name as supplier_name', 'cadd.address as company_address', 'p.*', DB::raw('ROUND(p.receipt_amount * ccomm_per2.commission_percentage / 100) as commission_amount'));
        
                if ($request->supplier && $request->supplier['id']) {
                    $company_details = Company::where('id', $request->supplier['id'])->first();
                    $link_companies = LinkCompanies::where('company_id', $request->supplier['id'])->get();
                    if (empty($link_companies)) {
                        $is_linked = LinkCompanies::where('link_companies_id', $request->supplier['id'])->get();
                        if (!empty($is_linked)) {
                            $company_details = Company::where('id', $is_linked->company_id)->first();
                            $link_companies = LinkCompanies::where('company_id', $is_linked->company_id)->get();
                        }
                    }
                    if ($company_details) {
                        $main_cmp_id = $company_details->id;
                        array_push($supplier, $main_cmp_id);
                        foreach ($link_companies as $row_link_companies) {
                            array_push($supplier, $row_link_companies->link_companies_id);
                        }
                        $data1 = $data2->WhereIn('p.supplier_id', $supplier);
                        foreach($supplier as $row) {
                            $supplier_data[] = Company::where('id', $row)->select('company_name')->first()->company_name;
                        }
                        $data['sup_disp_name'] = implode(',  ', $supplier_data);
                    }
                }

                if ($request->city && $request->city['id']) {
                    $cmp_sql .= " AND cs.company_city = '".$request->city['name']."'";
                }
                $data2 = $data2->get();
                $morethan = '';
                $sup = '';
                if ($request->day != '' && $request->day['report_days'] != 0) {
                    $morethan .= "( More then ". $request->day['report_days'] ." Days)";
                } else {
                    $morethan .= "";
                }

                if ($request->supplier != '') {
                    if($data['sup_disp_name']) {
                        $sup .= "Supplier: " .$data['sup_disp_name'] . $morethan;
                    } else {
                        $sup .= "All Parties" . $morethan;
                    }
                }
                print_r($data1);exit;
                $html = '';
                $html .= '<tr width="100%">
                            <th colspan="6" class="text-center">'.$sup.'</th>
                        </tr>
                        <tr>
						    <th width="10%">Payment Id</th>
							<th width="20%">Date</th>
							<th width="10%">Amount</th>
							<th width="10%">Commission Amount</th>
							<th width="10%">Percent</th>
							<th width="20%">Customer</th>
							<th width="8%">Days</th>
							<th width="12%" class="text-right">Invoice</th>
						</tr>';
                $supplier_name = ""; $prev_com = ""; $tot_payment = $total_payment = $total_commission_amount = 0;
                foreach ($data2 as $keys => $row) {
                    $color = "";
                    $paymentdate = strtotime($row->date);
                    $currentdate = strtotime(Carbon::now()->format('d-m-Y'));
                    $due_day = ($currentdate - $paymentdate) / 84600;
                    if($due_day >= 90) {
                        $color = "style='color:red'";
                    }
                    if($supplier_name != $row->supplier_name) {
                        $supplier_name = $row->supplier_name;
                        $address_supp = $row->company_address;
                        if($keys != 0) {
                            $html .= '<tr>
                                    <td colspan="2"><b>Party Total</b></td>
                                    <td><b>'.$tot_payment.'</b></td>
                                    <td><b>'.$prev_com.'</b></td>
                                    <td colspan="4"></td>
								</tr>';
                            $tot_payment = 0;
                        }
                        $html .='<tr>
									<td colspan="8"></td>
								</tr>
                                <tr>
                                    <td colspan="2"><b>'.$supplier_name.'</b></td>
                                    <td colspan="6"><b>'.$address_supp.'</b></td>
                                </tr>';
                    }}
                //         $html .= '<tr'.$color.'>
                //                     <td>'.$row->payment_id.'</td>
                //                     <td>'.date("d-m-Y", strtotime($row->date)).'</td>
                //                     <td>'.$row->receipt_amount.'</td>
                //                     <td>'.$row->commission_amount.'</td>';
                        $invoices = DB::table('commission_invoices as ci')
                                    ->leftJoin(DB::raw('(SELECT "payment_id", "commission_invoice_id", "financial_year_id", "flag" FROM invoice_payment_details group by "payment_id", "commission_invoice_id", "financial_year_id", "flag") as ipd'), 'ci.id', '=', 'ipd.commission_invoice_id')
                                    ->leftJoin(DB::raw('(SELECT "commission_invoice_id" FROM commission_details group by "commission_invoice_id") as cd'), 'ci.id', '=', 'cd.commission_invoice_id')
                                    ->where('ipd.flag', 1)
                                    ->where('ipd.payment_id', $row->payment_id)
                                    ->where('ipd.financial_year_id', $row->financial_year_id)
                                    ->select('ci.id','ci.bill_no', 'ipd.payment_id', 'ipd.financial_year_id', 'ci.final_amount', 'ci.total_payment_received_amount')
                                    ->get();
                        print_r($invoices);
                        $invoice = collect($invoices)->groupBy('ci.id');
                        print_r($invoice);
                // }
                exit;
                $data['table'] = $html; 
                return response()->json($data);

    }
}
