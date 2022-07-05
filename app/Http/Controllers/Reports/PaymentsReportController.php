<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\Employee;
use App\Models\settings\Cities;
use App\Models\Company\Company;
use App\Models\LinkCompanies;
use App\Models\Company\CompanyAddress;
use Illuminate\Support\Facades\Session;
use DB;
use PDF;
use Excel;
use App\Exports\OutstandingPaymentExport;
use App\Exports\PaymentsRegisterExport;
use App\Exports\OutstandingPaymentMonthWiseSummeryExport;
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
                ->selectRaw('cc.company_name as customer_name, cs.company_name as supplier_name, bank.name as bank_name, cheque_bank.name as cheque_bank, p.customer_id, p.supplier_id, to_char(p.date, \'dd-mm-yyyy\') as date, p.payment_id, p.financial_year_id, p.reciept_mode, to_char(p.cheque_date, \'dd-mm-yyyy\') as cheque_date, p.cheque_dd_no, cheque_bank.name as cheque_bank, p.receipt_amount, p.total_amount');
        
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

    public function outstandingPaymentReport(Request $request) {
        $page_title = 'Outstanding Payments Report';
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
        $logs->log_path = 'Outstanding Payments Report / View';
        $logs->log_subject = 'Outstanding Payments Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.outstanding_payments_report', compact('page_title', 'employees'));
    }

    public function outstandingPaymentMonthWiseSummeryReport(Request $request) {
        $page_title = 'Outstanding Payment Month Summery Payments Report';
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
        $logs->log_path = 'Outstanding Payments Report / View';
        $logs->log_subject = 'Outstanding Payments Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.outstanding_payments_month_wise_summery_report', compact('page_title', 'employees'));
    }
    

    public function listCities() {
        $cities = Cities::where('is_delete', '0')->get();
        return $cities;
    }

    public function listOutstandingPaymentData(Request $request) {
        $data1 = DB::table('sale_bills as s');
        $data1 = $data1->leftJoin(DB::raw('(SELECT "company_name", "id", "company_city" FROM companies group by "company_name", "id", "company_city") as "cc"'), 's.company_id', '=', 'cc.id')
                ->leftJoin(DB::raw('(SELECT "company_name", "id", "company_city" FROM companies group by "company_name", "id", "company_city") as "cs"'), 's.supplier_id', '=', 'cs.id')
                ->leftJoin(DB::raw('(SELECT "address", "company_id" FROM company_addresses group by "address", "company_id") as "cadd"'), 's.company_id', '=', 'cadd.company_id')
                ->where('s.sale_bill_flag', 0)
                ->where('s.is_deleted', 0)
                ->where('s.payment_status', 0)
                ->selectRaw('s.*,to_char(s.select_date, \'dd-mm-yyyy\') as select_date1,cc.company_name as customer_name, cs.company_name as supplier_name,cc.company_city as cc_city, cs.company_city as cs_city, cadd.address as company_address');

        if ($request->agent && $request->agent['id']) {
            $data1 = $data1->where('s.agent_id', $request->agent['id']);
        }

        if ($request->city && $request->city['id']) {
            $data1 = $data1->where('cc.company_city', $request->city['name'])
                    ->orWhere('cs.company_city', $request->city['name']);
        }

        if ($request->customer && $request->customer['id']) {
            $company = array();
            $company_data=array();
            $company_details = Company::where('id', $request->customer['id'])->first();
            $link_companies = LinkCompanies::where('company_id', $request->customer['id'])->get();
            if (empty($link_companies)) {
                $is_linked = LinkCompanies::where('link_companies_id', $request->customer['id'])->get();
                if (!empty($is_linked)) {
                    $company_details = Company::where('id', $is_linked->company_id)->first();
                    $link_companies = LinkCompanies::where('company_id', $is_linked->company_id)->get();
                }
            }
            if ($company_details) {
                $main_cmp_id = $company_details->id;
                array_push($company, $main_cmp_id);
                foreach ($link_companies as $row_link_companies) {
                    array_push($company, $row_link_companies->link_companies_id);
                }
                $data1 = $data1->WhereIn('s.company_id', $company); 
            }
            
            foreach($company as $row) {
                $companydata = Company::where('id', $row)->select('company_name')->first();
                if ($companydata) {
                    $company_data[] = $companydata->company_name;
                }
            }
            $data['cus_disp_name'] = implode(', ', $company_data);    
        }
        $supplier = array();
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
                $data1 = $data1->WhereIn('s.supplier_id', $supplier);
                foreach($supplier as $row) {
                    $supplier_data[] = Company::where('id', $row)->select('company_name')->first()->company_name;
                }
                $data['sup_disp_name'] = implode(',  ', $supplier_data);
            }
           
        }

        if ($request->sorting && $request->sorting['id']) {
            $sorting = $request->sorting['id'];
            if ($sorting == 5) {
                $data1 = $data1->orderBy('s.select_date', 'asc');
            } else if ($sorting == 6) {
                $data1 = $data1->orderBy('s.select_date', 'desc');
            } else if ($sorting == 1) {
                $data1 = $data1->orderBy('cs.company_name', 'asc');
            } else if ($sorting == 2) {
                $data1 = $data1->orderBy('cs.company_name', 'desc');
            } else if ($sorting == 3) {
                $data1 = $data1->orderBy('cc.company_name', 'asc');
            }  else if ($sorting == 4) {
                $data1 = $data1->orderBy('cc.company_name', 'desc');
            }
        }
        if ($request->start_date && $request->end_date) {
            $data1 = $data1->whereRaw("s.select_date::date >= '" . $request->start_date . "'")
                    ->whereRaw("s.select_date::date <= '" . $request->end_date . "'");
        }
        if ($request->group) {
            $supplier_data = array();
            if ($supplier) {
                foreach($supplier as $row) {
                    $supplier_data[] = Company::where('id', $row)->select('company_name')->first();
                }
            }
            $data['sup_disp_name'] = implode(', ', $supplier_data);
        }
        $morethan = '';
        if ($request->day != '' && $request->day['report_days'] != 0) {
            $morethan .= "( More then ". $request->day['report_days'] ." Days)";
        } else {
            $morethan .= "";
        }
        $sup="";
        $agent="";
        if ($request->agent == '') {
            $agent .= 'All Agents';  
        } else {
            $agent .= $request->agent['name'];
        }
        if ($request->customer != '') {
            $sup .= "Customer: ".$data['cus_disp_name'] . "<br>";
        }

        if ($request->supplier != '') {
            if($data['sup_disp_name']) {
                $sup .= "Supplier: " .$data['sup_disp_name'] ."<br>";
            } else {
                $sup .= "Supplier: " . $request->supplier->company_name . "<br>";
            }
        }

        if($request->customer == '' && $request->supplier == '') {
            $sup .= "All Parties";
        }
        $sup .= $morethan;
        $html = '';
        $html .= '<tr width="100%">
                    <th colspan="6" class="text-center">'.$sup.'</th>
                </tr>
                <tr width="100%">
                    <td colspan="6" class="text-center">'.$agent.'</td>
                </tr>';
        if ($request->show_detail == 0) {
            $html .='<tr width="100%">
                    <th>Bill Date</th>
                    <th>Sr.</th>
                    <th>Bill Amount</th>
                    <th>Days</th>
                    <th>Purchase Party</th>
                    <th>Bill No</th>
                </tr>';
        } else {
            $html .='<tr width="100%">
                        <th>No.</th>
                        <th>Name</th>
                        <th>Party Amount</th>
                </tr>';
        }
        $data1 = $data1->get();
        $customer_details = array();
        $report_days = $request->day ? $request->day['report_days'] : 0;
        $grand_total = 0;
		$prev_company = '';
		$prev_company_id = '';
		$prev_address = '';
		$incr = 0;
		$total = 0;
		$hincr = 0;
        $k=-1;
        foreach($data1 as $datas) {
            $cnt = 0;
			$hincr += 1;
            $startTimeStamp = strtotime($datas->select_date);
			$endTimeStamp = strtotime(date('Y-m-d'));
			$timeDiff = abs($endTimeStamp - $startTimeStamp);
			$numberDays = $timeDiff/86400;
			$numberDays = intval($numberDays);

            if ($numberDays >= $report_days){
                if ($datas->customer_name != $prev_company){
                    $k=0;
                    $address=$datas->company_address;
                    if($incr != 0) {
                        $maindata[]=array('company_id'=>$prev_company_id, 'name'=> $prev_company, 'address' => $prev_address, 'total' => $total);
                        $total=0;
                    }
                    $incr += 1;
                    $prev_company_id = $datas->company_id;
                    $prev_company = $datas->customer_name;
                    $prev_address = $address;
                    $prev_total = $total;
                } else {
                    $k++;
                }
                if($datas->pending_payment == 0) {
                    $final_amount = $datas->total;
                } else {
                    $final_amount = $datas->pending_payment;
                }
                $total += $final_amount;
                $grand_total += $final_amount;
                $company_id = $datas->company_id;

                $company_name = $datas->customer_name;
                $company_address = $address;
                $company_total = $total;
                $data[$datas->company_id]['date'][$k] = $datas->select_date1;
                $data[$datas->company_id]['srno'][$k] = $datas->sale_bill_id;
                $data[$datas->company_id]['financial_year_id'][$k] = $datas->financial_year_id;
                $data[$datas->company_id]['amount'][$k] = $final_amount;
                $data[$datas->company_id]['numberDays'][$k] = $numberDays;
                $data[$datas->company_id]['supplier'][$k] = $datas->supplier_name;
                $data[$datas->company_id]['bill_no'][$k] = $datas->supplier_invoice_no;
                
            }
        }
        if(!empty($company_id)) {
            $maindata[]=array('company_id'=>$company_id, 'name'=>$company_name, 'address' => $company_address, 'total' => $total);
        }
        
        if(!empty($maindata)) {
            foreach ($maindata as $key => $row) {
                $company_id1[$key]  = $row['company_id'];
                $name1[$key]  = $row['name'];
                $address1[$key] = $row['address'];
                $total1[$key] = $row['total'];
            }
            if($request->sorting && $sorting == 7) {
                array_multisort($total1, SORT_ASC, $name1, SORT_ASC, $address1, SORT_ASC,  $company_id1, SORT_ASC, $maindata);
            } elseif($request->sorting && $sorting == 8) {
                array_multisort($total1, SORT_DESC, $name1, SORT_ASC, $address1, SORT_ASC,  $company_id1, SORT_ASC, $maindata);
            }
            
            $gtotal = 0;$i = 1;
            foreach($maindata as $row) {
                if($row['total'] != 0) {
                    if ($request->show_detail == 1) {
                    
                        $html .= '<tr width="100%"><td>'.$i++.'</td>
                        <td>'.$row['name'].'</td>';
                        
                        $ptotal = 0;
                        for($j=0; $j<((is_array($data[$row['company_id']]['srno'])) ? count($data[$row['company_id']]['srno']) : 0); $j++) {
                            $tr_color='';
                            $ptotal += $data[$row['company_id']]['amount'][$j]; 
                        }
                        $html .= '<td colspan="4">'.$ptotal.'</td></tr>';
                
                        $gtotal += $ptotal;        
                        
                    } else {
                        $html .= '<tr width="100%">
                                <td colspan="6" class="text-center" style="height:35px"></td>
                            </tr>
                            <tr width="100%">
                                <td colspan="2"><b>'.$row['name'].'</b></td>
                                <td colspan="4"><b>'.$row['address'].'</b></td>
                            </tr>';
                        $ptotal = 0;
                        for($j=0; $j<((is_array($data[$row['company_id']]['srno'])) ? count($data[$row['company_id']]['srno']) : 0); $j++) {
                            $tr_color='';
                            $ptotal += $data[$row['company_id']]['amount'][$j]; 
                            if(isset($data[$row['company_id']]['numberDays'][$j])) {
                                if($data[$row['company_id']]['numberDays'][$j] >= 90) {
                                    $tr_color="style='color:red'";
                                }
                                    $html .='<tr width="100%"'.$tr_color.'>
                                        <td>'.$data[$row['company_id']]['date'][$j].'</td>
                                        <td><a target="_blank" href="/sale_bill/viewbill/"'.$data[$row['company_id']]['srno'][$j].'/'.$data[$row['company_id']]['financial_year_id'][$j].'>'.$data[$row['company_id']]['srno'][$j].'</a></td>
                                        <td>'.$data[$row['company_id']]['amount'][$j].'</td>
                                        <td>'.$data[$row['company_id']]['numberDays'][$j].'</td>
                                        <td>'.$data[$row['company_id']]['supplier'][$j].'</td>
                                        <td>'.$data[$row['company_id']]['bill_no'][$j].'</td>
                                    </tr>';
                            } 
                        }
                        $html .='<tr width="100%">
                            <td><b>Party Total</b></td>
                            <td></td>
                            <td><b>'.$ptotal.'</b></td>
                            <td colspan="3"></td>
                        </tr>';
                
                $gtotal += $ptotal;
            }
            
            }
            }
            if ($request->show_detail == 0) {
            $html .='<tr width="100%">
                 <td colspan="2"><b>Grand Total</b></td>
                <td><b>'.$gtotal.'</b></td>
                <td colspan="3"></td>
                </tr>';
            } else {
                $html .='<tr width="100%">
                    <td></td>
                    <td><b>Grand Total</b></td>
                    <td><b>'.$gtotal.'</b></td>
                </tr>';
            } 
        } else { 
            $html .='<tr width="100%">
                <td class="text-center" colspan="6" style="height: 50px;">Record Not Found</td>
            </tr>';
        }

        $data['maindata'] = $html;
        if (!empty($maindata)) {
            $data['company_data'] = $maindata; 
        } else {
            $data['company_data'] = [];
        }
        if ($request->export_pdf == 1) {
            $pdf = PDF::loadView('reports.outstanding_payment_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/outstanding-payment-reports');
            $fileName =  'Outstanding-Payment-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/outstanding-payment-reports/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Outstanding-Payment-Report-' . time() . '.xlsx';
            Excel::store(new OutstandingPaymentExport($data, $request), 'excel-sheets/outstanding-payment-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/outstanding-payment-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }
    }

    public function listOutstandingPaymentMonthWiseSummeryData(Request $request) {
        $data1 = DB::table('sale_bills as s');
        $data1 = $data1->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 's.company_id', '=', 'cc.id')
                ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 's.supplier_id', '=', 'cs.id')
                ->leftJoin(DB::raw('(SELECT "address", "company_id" FROM company_addresses group by "address", "company_id") as "cadd"'), 's.company_id', '=', 'cadd.company_id')
                ->where('s.sale_bill_flag', 0)
                ->where('s.is_deleted', 0)
                ->where('s.payment_status', 0)
                ->selectRaw('s.*,to_char(s.select_date, \'dd-mm-yyyy\') as select_date1,cc.company_name as customer_name, cs.company_name as supplier_name, cadd.address as company_address');

        if ($request->agent && $request->agent['id']) {
            $data1 = $data1->where('s.agent_id', $request->agent['id']);
        }

        if ($request->customer && $request->customer['id']) {
            $company = array();
            $company_data=array();
            $company_details = Company::where('id', $request->customer['id'])->first();
            $link_companies = LinkCompanies::where('company_id', $request->customer['id'])->get();
            if (empty($link_companies)) {
                $is_linked = LinkCompanies::where('link_companies_id', $request->customer['id'])->get();
                if (!empty($is_linked)) {
                    $company_details = Company::where('id', $is_linked->company_id)->first();
                    $link_companies = LinkCompanies::where('company_id', $is_linked->company_id)->get();
                }
            }
            if ($company_details) {
                $main_cmp_id = $company_details->id;
                array_push($company, $main_cmp_id);
                foreach ($link_companies as $row_link_companies) {
                    array_push($company, $row_link_companies->link_companies_id);
                }
                $data1 = $data1->WhereIn('s.company_id', $company); 
            }
            
            foreach($company as $row) {
                $companydata = Company::where('id', $row)->select('company_name')->first();
                if ($companydata) {
                    $company_data[] = $companydata->company_name;
                }
            }
            $data['cus_disp_name'] = implode(', ', $company_data);    
        }
        $supplier = array();
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
                $data1 = $data1->WhereIn('s.supplier_id', $supplier);
                foreach($supplier as $row) {
                    $supplier_data[] = Company::where('id', $row)->select('company_name')->first()->company_name;
                }
                $data['sup_disp_name'] = implode(',  ', $supplier_data);
            }
           
        }

        if ($request->start_date && $request->end_date) {
            $data1 = $data1->whereRaw("s.select_date::date >= '" . $request->start_date . "'")
                    ->whereRaw("s.select_date::date <= '" . $request->end_date . "'");
        }
        if ($request->group) {
            $supplier_data = array();
            if ($supplier) {
                foreach($supplier as $row) {
                    $supplier_data[] = Company::where('id', $row)->select('company_name')->first();
                }
            }
            $data['sup_disp_name'] = implode(', ', $supplier_data);
        }
        $time   = strtotime($request->start_date);
		$last   = date('m-Y', strtotime($request->end_date));
		$date_array=array();
		do {
			$month = date('m-Y', $time);
		   	$fdate="01-".$month;
		   	array_push($date_array, $fdate);
		    $time = strtotime('+1 month', $time);
		} while ($month != $last);
		$data['date_array']=$date_array;
        $morethan = '';
        $sup="";
        $agent="";
        if ($request->agent == '') {
            $agent .= 'All Agents';  
        } else {
            $agent .= $request->agent['name'];
        }
        if ($request->customer != '') {
            $sup .= "Customer: ".$data['cus_disp_name'] . "<br>";
        }

        if ($request->supplier != '') {
            if($data['sup_disp_name']) {
                $sup .= "Supplier: " .$data['sup_disp_name'] ."<br>";
            } else {
                $sup .= "Supplier: " . $request->supplier->company_name . "<br>";
            }
        }

        if($request->customer == '' && $request->supplier == '') {
            $sup .= "All Parties";
        }
        $sup .= $morethan;
        $html = '';
        $html .= '<tr width="100%">
                    <th colspan="3" class="text-center">'.$sup.'</th>
                </tr>
                <tr width="100%">
                    <td colspan="3" class="text-center">'.$agent.'</td>
                </tr>';
        
        if ($request->start_date != '' && $request->end_date != '') {
            $grand_total = 0;
            $date_data=array();
			
			$maindata=array();

            foreach ($date_array as $row_date) {
                $prev_company='';
                $prev_company_id='';
                $prev_address='';
                $incr=0;
                $total = 0;
                $hincr=0;
                $k=-1;
                $month_total=0;
                $date_cnt=0;
                $start_date=date('Y-m-d',strtotime($row_date));
                $end_date=date('Y-m-d',strtotime('+1 month',strtotime($start_date)));
                $date_data[]=date("F-Y",strtotime($start_date));
                $temp_month=date("F-Y",strtotime($start_date));
                $customer_details = $data1->whereRaw("s.select_date::date >= '" . $start_date . "'")
                                ->whereRaw("s.select_date::date <= '" . $end_date . "'")
                                ->get();
                
                foreach ($customer_details as $row_customer) {
                    $cnt = 0;
                    $hincr+=1;
                    $startTimeStamp = strtotime($row_customer->select_date);
                    $endTimeStamp = strtotime(date('Y-m-d'));
                    $timeDiff = abs($endTimeStamp - $startTimeStamp);
                    $numberDays = $timeDiff/86400;
                    $numberDays = intval($numberDays);
                    $Purchase_Party = $row_customer->supplier_name;
                    $company = $row_customer->customer_name;
                    $tr_color='';
                    if($numberDays >= 90) {
                        $tr_color='style="color:red"';
                    }
                    if($company != $prev_company) {
                        $k=0;
                        
                        $address="";
                        if($row_customer->company_address != '') {
                            $address = $row_customer->company_address;
                        }
                        if($incr != 0) {
                            $maindata[$temp_month][]=array('month'=>$temp_month, 'company_id'=>$prev_company_id,'name'=>$prev_company, 'address' => $prev_address, 'party_total'=>$total);
                            $total=0;
                        }
                            $incr+=1;
                            $prev_company_id=$row_customer->company_id;
                            $prev_company=$company;
                            $prev_address=$address;
                            $prev_total=$total;
                    } else {
                        $k++;
                    }
                    
                    if($row_customer->pending_payment == 0) {
                        $final_amount=$row_customer->total;
                    } else {
                        $final_amount=$row_customer->pending_payment;
                    }
                    $total += $final_amount;
                    $month_total += $final_amount;
                    $grand_total += $final_amount;
                    $company_id = $row_customer->company_id;
                    $company_name = $row_customer->customer_name;
                    $company_address = $address;
                    $company_total=$total;
                      $data[$temp_month][$row_customer->company_id]['sr'][$k] = $row_customer->sale_bill_id;
                      $data[$temp_month][$row_customer->company_id]['supplier_total'][$k] = $final_amount;
                      $data[$temp_month][$row_customer->company_id]['purchase_Party'][$k]= $Purchase_Party;
                }
                $month_data[$temp_month]=$month_total;
                if(!empty($company_id)) {
                    $maindata[$temp_month][]=array('month'=>$temp_month, 'company_id'=>$company_id,'name'=>$company_name, 'address' => $company_address, 'party_total'=>$company_total);
                }
            }
            
            $m = -1;
            if (!empty($date_data)) {
                foreach($date_data as $row_date) {
                    $m++;
                    if(isset($data[$row_date]) && is_array($data[$row_date]) && count($data[$row_date]) != 0) {
                        $html .= '<tr>
                                    <td height="35" colspan="3"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-center"><b style="font-size: 20px">'.$row_date.'</b></td>
                                </tr>';
                        $month1=array(); $company_id1=array();	$name1=array();	$address1=array();
                        $party_total1=array();
                        foreach ($maindata[$row_date] as $key => $row) {
                            $month1[$key] = $row['month'];
                            $company_id1[$key]  = $row['company_id'];
                            $name1[$key]  = $row['name'];
                            $address1[$key] = $row['address'];
                            $party_total1[$key] = $row['party_total'];
                        }
                        if($request->sorting == 1) {
                            array_multisort($party_total1, SORT_ASC, $name1, SORT_ASC, $address1, SORT_ASC,  $company_id1, SORT_ASC, $month1, SORT_ASC, $maindata[$row_date]);
                        } elseif($request->sorting == 2) {
                            array_multisort($party_total1, SORT_DESC, $name1, SORT_ASC, $address1, SORT_ASC,  $company_id1, SORT_ASC, $month1, SORT_ASC, $maindata[$row_date]);
                        }

                        foreach ($maindata[$row_date] as $row) {
                            if ($request->show_detail == 0) {
                            $html .= '<tr>
                                        <td colspan="3" class="text-center" style="height:35px"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="1"><b>'.$row['name'].'</b></td>
                                        <td colspan="2"><b>'.$row['address'].'</b></td>
                                    </tr>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Purchase Party</th>
                                        <th class="text-right">Bill Amount</th>
                                    </tr>';
                            for($i=0; $i<((is_array($data[$row_date][$row['company_id']]['sr'])) ? count($data[$row_date][$row['company_id']]['sr']) : 0); $i++) {
                                $html .='<tr>
                                            <td>'.$data[$row_date][$row['company_id']]['sr'][$i].'</td>
                                            <td>'.$data[$row_date][$row['company_id']]['purchase_Party'][$i].'</td>
                                            <td class="text-right">'.$data[$row_date][$row['company_id']]['supplier_total'][$i].'</td>
                                    </tr>';
                            }
                            $html .= '<tr>
                                    <td></td>
                                    <td colspan="" class="text-right"><b>Party Totals</b></td>
                                    <td colspan="" class="text-right"><b>'.$row['party_total'].'</b></td>
                                    </tr>';
                        } else {
                            $html .= '<tr>
                                        <td colspan="2"><b>'.$row['name'].'</b></td>';
                            
                            $html .= '<td colspan="" class="text-right"><b>'.$row['party_total'].'</b></td>
                                    </tr>';
                        }
                        }
                        $html .= '<tr>
                                    <td colspan="2" class="text-right"><b>Month Total</b></td>
                                    <td class="text-right"><b>'.$month_data[$row_date].'</b></td>
                                </tr>';
                    }
                }
                $html .= '<tr>
                            <td colspan="2" class="text-right"><b>Grand Total</b></td>
                            <td class="text-right"><b>'.$grand_total.'</b></td>
                        </tr>';
            }   
        }
        $data['table'] = $html;
        $data['maindata'] = $maindata;
        $data['date_data'] = $date_data;
        $data['month_data'] = $month_data;
        $data['grand_total'] = $grand_total;
        if ($request->export_pdf == 1) {
            $pdf = PDF::loadView('reports.outstanding_payment_month_wise_summery_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/outstanding-payment-month-wise-summery-reports');
            $fileName =  'Outstanding-Payment-Month-Wise-Summery-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/outstanding-payment-month-wise-summery-reports/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Outstanding-Payment-Month-Wise-Summery-Report' . time() . '.xlsx';
            Excel::store(new OutstandingPaymentMonthWiseSummeryExport($data, $request), 'excel-sheets/outstanding-payment-month-wise-summery-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/outstanding-payment-month-wise-summery-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }
    }
}