<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\Employee;
use App\Models\Settings\Cities;
use App\Models\Company\Company;
use App\Models\LinkCompanies;
use App\Models\Company\CompanyAddress;
use Illuminate\Support\Facades\Session;
use DB;
use PDF;
use Excel;
use App\Exports\OutstandingPaymentExport;
use App\Exports\PaymentsRegisterExport;
use App\Exports\AvaragePaymentDaysExport;
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
                ->where('p.is_deleted', 0)
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
            $pdf = $pdf->setPaper('a4', 'landscape');
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

    public function avaPaymentDaysReport(Request $request) {
        $page_title = 'Avarage Payment Days Report';
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
        $logs->log_path = 'Avarage Payment Days Report / View';
        $logs->log_subject = 'Outstanding Payments Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.ava_payment_days_report', compact('page_title', 'employees'));
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
                ->leftJoin(DB::raw('(SELECT "address", "company_id" FROM company_addresses Where address_type = 1 group by "address", "company_id" limit 1) as "cadd"'), 's.company_id', '=', 'cadd.company_id')
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

                $data1 = $data1->WhereIn('s.supplier_id', $supplier);
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

                $data1 = $data1->WhereIn('s.company_id', $customer);
                foreach($customer as $row) {
                    $customer_data[] = Company::where('id', $row)->select('company_name')->first()->company_name;
                }
                $data['cus_disp_name'] = implode(',  ', $customer_data);
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
        $data1 = $data1->orderBy('s.select_date', 'asc');
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
                        <th colspan="4">Party Amount</th>
                </tr>';
        }
        $data1 = $data1->get();
        $data2 = collect($data1)->groupBy('company_id');

        $customer_details = array();
        $report_days = $request->day ? $request->day['report_days'] : 0;
        $grand_total = 0;
        $i = 0;
        foreach($data2 as $key => $row) {
            $ptotal = 0;
            $final_amount = 0;
            if ($request->show_detail == 1) {
                    $html .= '<tr width="100%">
                                <td>'.++$i.'</td>
                                <td>'.$row[0]->customer_name.'</td>';
                    foreach($row as $key1 => $row2){
                        $startTimeStamp = strtotime($row2->select_date);
			            $endTimeStamp = strtotime(date('Y-m-d'));
                        $timeDiff = abs($endTimeStamp - $startTimeStamp);
                        $numberDays = $timeDiff/86400;
                        $numberDays = intval($numberDays);

                        if ($numberDays >= $report_days){
                            if($row2->pending_payment == 0) {
                                $final_amount=$row2->total;
                            } else {
                                $final_amount=$row2->pending_payment;
                            }
                            $ptotal += $final_amount;
                        }
                    }
            } else {
            $html .= '<tr width="100%">
                        <td colspan="6" class="text-center" style="height:35px"></td>
                    </tr>';
            if ($request->export_pdf == 1) {
                    $html .='<tr width="100%">
                            <td colspan="6"><b>Customer : '.$row[0]->customer_name.'</b></td>
                        </tr>
                        <tr width="100%">
                            <td colspan="6"><b>Address : '.$row[0]->company_address.'</b></td>
                        </tr>';
                        $ptotal += $final_amount;
            } else {
                $html .='<tr width="100%">
                            <td colspan="2"><b>'.$row[0]->customer_name.'</b></td>
                            <td colspan="4"><b>'.$row[0]->company_address.'</b></td>
                        </tr>';
            }

            foreach($row as $key1 => $row2){
                $startTimeStamp = strtotime($row2->select_date);
			    $endTimeStamp = strtotime(date('Y-m-d'));
			    $timeDiff = abs($endTimeStamp - $startTimeStamp);
			    $numberDays = $timeDiff/86400;
			    $numberDays = intval($numberDays);

                if ($numberDays >= $report_days){
                    if ($numberDays >= 90) {
                        $tr_color = "style='color:red'";
                    } else {
                        $tr_color = "";
                    }
                    if($row2->pending_payment == 0) {
                        $final_amount=$row2->total;
                    } else {
                        $final_amount=$row2->pending_payment;
                    }
                    $final_amount1 = number_format($final_amount);

                    $html .='<tr width="100%"'.$tr_color.'>
                                <td>'.$row2->select_date.'</td>';
                            if ($request->export_pdf == 1) {
                                $html .= '<td>'.$row2->sale_bill_id.'</td>';
                            } else {
                                $html .= '<td><a target="_blank" href="/account/sale-bill/view-sale-bill/'.$row2->sale_bill_id.'/' .$row2->financial_year_id.'">'.$row2->sale_bill_id.'</a></td>';
                            }

                            $html .= '<td>'. $final_amount1 .'</td>
                                    <td>'.$numberDays.'</td>
                                    <td>'.$row2->supplier_name.'</td>
                                    <td>'.$row2->supplier_invoice_no.'</td>
                                </tr>';
                            $ptotal += $final_amount;
                }
            }
            }
            $ptotal1 = number_format($ptotal);
            if ($request->show_detail == 0) {
            $html .='<tr width="100%">
                            <td><b>Party Total</b></td>
                            <td></td>
                            <td><b>'.$ptotal1.'</b></td>
                            <td colspan="3"></td>
                        </tr>';
            } else {
                $html .= '<td colspan="4">'.$ptotal1.'</td></tr>';
            }
            $grand_total += $ptotal;
        }
        $grand_total1 = number_format($grand_total);
        if ($request->show_detail == 0) {
           $html .='<tr width="100%">
                        <td colspan="2"><b>Grand Total</b></td>
                        <td><b>'.$grand_total1.'</b></td>
                        <td colspan="3"></td>
                    </tr>';
            } else {
                $html .='<tr width="100%">
                        <td colspan="2"><b>Grand Total</b></td>
                        <td colspan="4"><b>'.$grand_total1.'</b></td>
                    </tr>';
            }


        $data['maindata'] = $html;
        if (!empty($data2)) {
            $data['company_data'] = $data2;
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
                ->leftJoin(DB::raw('(SELECT "address", "company_id" FROM company_addresses WHERE address_type = 1 group by "address", "company_id" limit 1) as "cadd"'), 's.company_id', '=', 'cadd.company_id')
                ->where('s.sale_bill_flag', 0)
                ->where('s.is_deleted', 0)
                ->where('s.payment_status', 0)
                ->orderBy('s.select_date', 'asc')
                ->orderBy('s.company_id', 'asc')
                ->select(DB::raw("CONCAT(TO_CHAR(s.select_date, 'MON'),'-',TO_CHAR(s.select_date, 'YYYY')) as monthyear"),'s.*', 'cc.company_name as customer_name', 'cs.company_name as supplier_name', 'cadd.address as company_address');

        if ($request->agent && $request->agent['id']) {
            $data1 = $data1->where('s.agent_id', $request->agent['id']);
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

                $data1 = $data1->WhereIn('s.supplier_id', $supplier);
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

                $data1 = $data1->WhereIn('s.company_id', $customer);
                foreach($customer as $row) {
                    $customer_data[] = Company::where('id', $row)->select('company_name')->first()->company_name;
                }
                $data['cus_disp_name'] = implode(',  ', $customer_data);
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
        $data1 = $data1->get();
        $data2 = collect($data1)->groupBy('monthyear');

        // $time   = strtotime($request->start_date);
		// $last   = date('m-Y', strtotime($request->end_date));
		// $date_array=array();
		// do {
		// 	$month = date('m-Y', $time);
		//    	$fdate="01-".$month;
		//    	array_push($date_array, $fdate);
		//     $time = strtotime('+1 month', $time);
		// } while ($month != $last);
		// $data['date_array']=$date_array;
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
            $gtotal = 0;
            foreach ($data2 as $keys => $row) {
                $html .= '<tr>
                            <td colspan="3" class="text-center" style="height:35px"></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-center"><b style="font-size: 20px">'.$keys.'</b></td>
                        </tr>';
                        if ($request->show_detail == 1) {
                            $html .= '<tr>
                                    <th>No.</th>
                                    <th>Customer</th>
                                    <th class="text-right">Party Total</th>
                            </tr>';
                        }
                $mtotal = 0;
                $i = 0;
                $company_data = collect($row)->groupBy('customer_name');
                $finaldata[$keys] = $company_data;
                foreach ($company_data as $key1 =>$row1) {
                    if ($request->show_detail == 0) {
                    $html .= '<tr>
                                <td colspan="3" class="text-center" style="height:35px"></td>
                            </tr>
                            <tr>
                                <td colspan="1"><b>'.$key1.'</b></td>
                                <td colspan="2"><b>'.$row1[0]->company_address.'</b></td>
                            </tr>
                            <tr>
                                <th>Sr.</th>
                                <th>Purchase Party</th>
                                <th class="text-right">Bill Amount</th>
                            </tr>';
                    } else {
                        $html .= '<tr>
                                <td>'.++$i.'</td>
                                <td>'.$key1.'</td>';
                    }
                    $ptotal = 0;
                    foreach ($row1 as $key2 =>$row2) {
                        if($row2->pending_payment == 0) {
                            $final_amount=$row2->total;
                        } else {
                            $final_amount=$row2->pending_payment;
                        }
                        $final_amount1 = number_format($final_amount);
                        $ptotal += $final_amount;
                        if ($request->show_detail == 0) {
                        $html .= '<tr>
                                    <td>'.$row2->sale_bill_id.'</td>
                                    <td>'.$row2->supplier_name.'</td>
                                    <td class="text-right">'.$final_amount1.'</td>
                                </tr>';
                        }
                    }
                    $ptotal1 = number_format($ptotal);
                    if ($request->show_detail == 0) {
                        $html .= '<tr>
                                    <td><b>Party Total</b></td>
                                    <td class="text-right" colspan="2"><b>'.$ptotal1.'</b></td>
                                </tr>';

                    } else {
                        $html .= '<td class="text-right">'.$ptotal1.'</td>
                            </tr>';
                    }
                    $mtotal += $ptotal;
                }
                $mtotal1 = number_format($mtotal);
                $html .= '<tr>
                            <td><b>Montly Total</b></td>
                            <td class="text-right" colspan="2"><b>'.$mtotal1.'</b></td>
                        </tr>';
                $gtotal += $mtotal;
            }
            $gtotal1 = number_format($gtotal);
            $html .= '<tr>
                            <td><b>Grand Total</b></td>
                            <td class="text-right" colspan="2"><b>'.$gtotal.'</b></td>
                        </tr>';

        }
        $data['table'] = $html;
        $data['finaldata'] = $finaldata;

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

    public function listAvaragePaymentDaysData(Request $request) {
        $data1 = DB::table('sale_bills')
            ->select('companies.company_name', 'sale_bills.sale_bill_id', 'sale_bills.financial_year_id', 'sale_bills.company_id', 'sale_bills.select_date', 'payments.date')
            ->join('payment_details as pd',function($join) {
                $join->on('pd.sr_no','=','sale_bills.sale_bill_id')
                    ->on('pd.financial_year_id','=','sale_bills.financial_year_id');
                })
            ->join('payments','payments.id','=','pd.p_increment_id')
            ->join('companies','companies.id','=','sale_bills.company_id')
            ->where('sale_bills.is_deleted', 0)
            ->where('payments.is_deleted', 0)
            ->where('pd.is_deleted', 0);

        if ($request->customer && $request->customer['id']) {
            $data1 = $data1->where('sale_bills.company_id', $request->customer['id']);
        }

        if ($request->supplier && $request->supplier['id']) {
            $data1 = $data1->where('sale_bills.supplier_id', $request->supplier['id']);
        }

        if ($request->start_date && $request->end_date) {
            $data1 = $data1->whereRaw("sale_bills.select_date::date >= '" . $request->start_date . "'")
                    ->whereRaw("sale_bills.select_date::date <= '" . $request->end_date . "'");
        }

        if ($request->sorting && $request->sorting['id']) {
            $sorting = $request->sorting['id'];
            if ($sorting == 1) {
                $data1 = $data1->orderBy('companies.company_name', 'asc');
            } else if ($sorting == 2) {
                $data = $data1->orderBy('companies.company_name', 'desc');
            }
        }
        $data1 = $data1->get();
        $data1 = collect($data1)->groupBy('company_id');

        $companydata = array();
        foreach($data1 as $key => $company) {
            $companydetail = array();

            $noofbill = count($company);
            $totalday = 0;
            foreach ($company as $c) {
                $companyname = $c->company_name;
                $billdate = strtotime($c->select_date);
                $paymentdate = strtotime($c->date);
                $diff = $paymentdate - $billdate;
                $day = $diff / 84600;
                $totalday += $day;
            }
            $avaragedays = $totalday / $noofbill;
            $company_detail['company_id'] = $key;
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
            $pdf = PDF::loadView('reports.avarage_payments_days_export_pdf', compact('data', 'request'))
                ->setOptions(['defaultFont' => 'sans-serif']);
            $path = storage_path('app/public/pdf/avarage-payment-days-reports');
            $fileName =  'Avarage-Payment-Days-Report-' . time() . '.pdf';
            $pdf->save($path . '/' . $fileName);
            return response()->json(['url' => url('/storage/pdf/avarage-payment-days-reports/' . $fileName)]);
        } else if ($request->export_sheet == 1) {
            $fileName =  'Avarage-Payment-Days-Report' . time() . '.xlsx';
            Excel::store(new AvaragePaymentDaysExport($data, $request), 'excel-sheets/avarage-payment-days-reports/' . $fileName, 'public');
            return response()->json(['url' => url('/storage/excel-sheets/avarage-payment-days-reports/' . $fileName)]);
        } else {
            return response()->json($data);
        }

    }
}
