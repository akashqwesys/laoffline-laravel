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

    public function listCities() {
        $cities = Cities::where('is_delete', '0')->get();
        return $cities;
    }

    public function listOutstandingPaymentData(Request $request) {
        $data1 = DB::table('sale_bills as s');
        $data1 = $data1->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 's.company_id', '=', 'cc.id')
                ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 's.supplier_id', '=', 'cs.id')
                ->leftJoin(DB::raw('(SELECT "address", "company_id" FROM company_addresses group by "address", "company_id") as "cadd"'), 's.company_id', '=', 'cadd.company_id')
                ->where('s.sale_bill_flag', 0)
                ->where('s.is_deleted', 0)
                ->where('s.payment_status', 0)
                ->selectRaw('s.*,cc.company_name as customer_name, cs.company_name as supplier_name, cadd.address as company_address');

        if ($request->agent && $request->agent['id']) {
            $data1 = $data1->where('s.agent_id', $request->agent['id']);
        }

        if ($request->city && $request->city['id']) {
            $data1 = $data1->where('cc_city_name', $request->city['name'])
                    ->orWhere('cs_city_name', $request->city['name']);
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
        if ($request->supplier && $request->supplier['id']) {
            $supplier = array();
            
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
            $data1 = $data1->whereBetween('s.select_date', [$request->start_date, $request->end_date]);
        }
        if ($request->group) {
            $supplier_data = array();
            foreach($supplier as $row) {
                $supplier_data[] = Company::where('id', $row)->select('company_name')->first();
            }
            $data['sup_disp_name'] = implode(',  ', $supplier_data);
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
                        $maindata[]=array('company_id'=>$prev_company_id, 'name'=> $prev_company, 'address' => $prev_address);
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
                    $final_amount=$datas->total;
                } else {
                    $final_amount=$datas->pending_payment;
                }
                $total += $final_amount;
                $grand_total += $final_amount;
                $company_id = $datas->company_id;

                $company_name = $datas->customer_name;
                $company_address = $address;
                $company_total = $total;
                $data2[$datas->company_id]['date'][$k] = date('d-m-Y',strtotime($datas->select_date));
                $data2[$datas->company_id]['srno'][$k] = $datas->sale_bill_id;
                $data2[$datas->company_id]['financial_year_id'][$k] = $datas->financial_year_id;
                $data2[$datas->company_id]['amount'][$k] = $final_amount;
                $data2[$datas->company_id]['numberDays'][$k] = $numberDays;
                $data2[$datas->company_id]['supplier'][$k] = $datas->supplier_name;
                $data2[$datas->company_id]['bill_no'][$k] = $datas->supplier_invoice_no;
                if(!empty($company_id)) {
                    $maindata[]=array('company_id'=>$company_id, 'name'=>$company_name, 'address' => $company_address);
                }
            }
            

            if(!empty($maindata)) {
                foreach ($maindata as $key => $row) {
                    $company_id1[$key]  = $row['company_id'];
                    $name1[$key]  = $row['name'];
                    $address1[$key] = $row['address'];
                }
                if($sorting == 7) {
                    array_multisort($total1, SORT_ASC, $name1, SORT_ASC, $address1, SORT_ASC,  $company_id1, SORT_ASC, $maindata);
                } elseif($sorting == 8) {
                    array_multisort($total1, SORT_DESC, $name1, SORT_ASC, $address1, SORT_ASC,  $company_id1, SORT_ASC, $maindata);
                }
            }
        }
        $temp = array_unique(array_column($maindata, 'company_id'));
        $maindata = array_intersect_key($maindata, $temp);   
        
        $data['customer_details'] = $maindata;
        $data['salebill__data'] = $data2;
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
