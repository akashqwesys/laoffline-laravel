<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Traits\HasRoles;
use App\Models\FinancialYear;
use App\Models\Employee;
use App\Models\Reference\ReferenceId;
use App\Models\Logs;
use App\Models\Iuid;
use App\Models\Payment;
use App\Models\CompanyType;
use App\Models\PaymentDetail;
use App\Models\IncrementId;
use App\Models\settings\BankDetails;
use App\Models\Company\Company;
use App\Models\comboids\Comboids;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class PaymentsController extends Controller
{
    use HasRoles;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $page_title = 'Payment List';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            -> join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        $employees['excelAccess'] = $user->excel_access;

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'payment / View';
        $logs->log_subject = 'Payment view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('payment.payment',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function listpayment(Request $request) {
        $user = Session::get('user');

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        if($columnName == 'active') {
            $columnName = 'users.is_active';
        } else {
            $columnName = 'payments.'.$columnName;
        }
        // Total records
        $totalRecords = Payment::select('count(*) as allcount')->count();

        $totalRecordswithFilter = Payment::select('count(*) as allcount');
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where(function ($q) use ($columnName_arr) {
                $q->orWhere('reference_id', 'ILIKE', '%' . $searchValue . '%');
            });
        }
        $totalRecordswithFilter = Payment::select('count(*) as allcount')->
                                                   where('reference_id', 'ilike', '%' .$searchValue . '%')->
                                                   count();


        // Fetch records
        $records = Payment::select('payment_id','iuid', 'reference_id', 'created_at', 'date', 'customer_id', 'supplier_id', 'payment_id', 'receipt_amount', 'customer_commission_status', 'done_outward')->
                                where('reference_id', 'ilike', '%' .$searchValue . '%');

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $id = $record->payment_id;
            $iuid = $record->iuid;
            $ouid = '';
            $ref_id = $record->reference_id;
            $date_add = $record->created_at;
            $payment_date = $record->date;
            $customer = Company::where('id', $record->customer_id)->first()->company_name;
            $seller = Company::where('id', $record->supplier_id)->first()->company_name;
            $voucher = $record->payment_id;
            $paid_amount = $record->receipt_amount;
            $scs = 0;
            $ccs = $record->customer_commission_status;
            $outward = $record->done_outward;
            $action = '<a href="./payments/edit-payments/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./payments/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "id" => $id,
                "iuid" => $iuid,
                "ouid" => $ouid,
                "refeenceid" => $ref_id,
                "dateadd" => $date_add,
                "paymentdate" => $payment_date,
                "customer" => $customer,
                "supplier" => $seller,
                "voucherno" => $voucher,
                "paidamount" => $paid_amount,
                "suppiler_commission_status" => $scs,
                "customer_commission_status" => $ccs,
                "outward_status" => $outward,
                "action" => $action
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }
    public function createPayment() {
        $page_title = 'Add Payments step - 1';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('payment.createPayment',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function listSeller() {
        $seller = Company::where('company_type',3)->get();
        return $seller;
    }

    public function listCustomer() {
        $customer = Company::where('company_type',2)->get();
        return $customer;
    }

    public function listBank() {
        $bank = BankDetails::all();
        return $bank;
    }

    public function searchSaleBill(Request $request) {
        
    }

    public function generatePaymentData(Request $request){
        $request->session()->forget('customer');
        $request->session()->forget('seller');
        $request->session()->forget('saleBill');
        $request->session()->put('customer', $request->customer);
        $request->session()->put('seller', $request->seller);
        $request->session()->put('saleBill', $request->salebill);
        $request->session()->put('finacial_year_id', 1);
        return true;
    }
    public function selectSaleBills(Request $request){
        print_r($request->input());
        exit;
    }
    public function insertPaymentData(Request $request) {
        $user = Session::get('user');
        $paymentData = json_decode($request->formdata);
        $paymentSalebill = json_decode($request->billdata);
        $financialid = 1;
        $attachments = array();

        if (!file_exists(public_path('upload/payments'))) {
            mkdir(public_path('upload/payments'), 0777, true);
        }
        if ($image = $request->chequeimage) {
            $ChequeImage = date('YmdHis') . "_chequeImage." . $image->getClientOriginalExtension();
            $paymentData->chequeImage = $ChequeImage;
            $image->move(public_path('upload/payments/'), $ChequeImage);
            array_push($attachments, $ChequeImage);
        }
        if ($image = $request->letterimage) {
            $LetterImage = date('YmdHis') . "_letterImage." . $image->getClientOriginalExtension();
            $paymentData->letterImage = $LetterImage;
            $image->move(public_path('upload/payments/'), $LetterImage);
            array_push($attachments, $LetterImage);
        }
        
        $increment_id_details = IncrementId::where('financial_year_id', $financialid)->first();
        $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
        $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;
        
        //reference_id
        if ($increment_id_details) {
            $ref_id = $increment_id_details->reference_id + 1;
            $payment_id = $increment_id_details->payment_id + 1;
            $iuid = $increment_id_details->iuid + 1;
            $increment_id = IncrementId::where('financial_year_id', $financialid)->first();
            $increment_id->reference_id = $ref_id;
            $increment_id->payment_id = $payment_id;
            $increment_id->iuid = $iuid;
            $increment_id->save();
        } else {
            $ref_id = '1';
            $payment_id = '1';
            $iuid = '1';
            $increment_id = new IncrementId();
            $increment_id->reference_id = $ref_id;
            $increment_id->payment_id = $payment_id;
            $increment_id->id = $Incrementids;
            $increment_id->iuid = $iuid;
            $increment_id->financial_year_id = $financialid;
            $increment_id->save();
        }

        
        if ($paymentData->refrence == 'new') {
            if ($paymentData->refrencevia->name == 'Email') {
                $courier_name = '';
                $courier_receipt_no = '';
                $courier_received_time = '';
            } else if ($paymentData->refrencevia->name == 'Email') {
                $courier_name = '';
                $courier_receipt_no = '';
                $courier_received_time = $paymentData->recivedate;
            } else {
                $courier_name = $paymentData->courrier->name;
                $courier_receipt_no = $paymentData->reciptno;
                $courier_received_time = $paymentData->recivedate;
            }

            $refrenceLastid = ReferenceId::orderBy('id', 'DESC')->first('id');
            $refrenceid = !empty($refrenceLastid) ? $refrenceLastid->id + 1 : 1;

            $user = Session::get('user');
            $refence = new ReferenceId();
            $refence->id = $refrenceid;
            $refence->reference_id = $ref_id;
            $refence->financial_year_id = $financialid;
            $refence->employee_id = $user->employee_id;
            $refence->inward_or_outward = '1';
            $refence->type_of_inward = $paymentData->refrencevia->name;
            $refence->company_id = $request->session()->get('customer');
            $refence->selection_date = Carbon::now()->format('d-m-Y');
            $refence->from_name = $paymentData->fromname;
            $refence->from_email_id = $paymentData->emailfrom;
            $refence->courier_name = $courier_name;
            $refence->weight_of_parcel = $paymentData->weight;
            $refence->courier_receipt_no = $courier_receipt_no;
            $refence->courier_received_time = $courier_received_time;
            $refence->delivery_by = $paymentData->delivery;
            $refence->save();
        } else {
            $ref_id = $paymentData->refrence_type;
        }
        $payment_date = $paymentData->reciptdate;
        $iuids = Iuid::orderBy('id', 'DESC')->first('id');
        $nextAutoID = !empty($iuids) ? $iuids->ids + 1 : 1;

        $companyName = Company::where('id', $request->session()->get('seller'))->first();
        $cmpTypeName = Company::where('id', $request->session()->get('customer'))->first();

        if ($cmpTypeName && $cmpTypeName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $cmpTypeName->company_type)->first();
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }

        // $companyPerson = CompanyOwner::where('company_id', $request->session->get('customer'))->first();
        // if($companyPerson) {
        //     $personName = $companyPerson->name;
        // } else {
        //     $personName = '';
        // }

        $personName = '';

        $iuid_ids = new Iuid();
        $iuid_ids->id = $nextAutoID;
        $iuid_ids->iuid = $iuid;
        $iuid_ids->financial_year_id = $financialid;
        $iuid_ids->save();

        $comboLastid = Comboids::orderBy('comboid', 'DESC')->first('comboid');
        $combo_id = !empty($comboLastid) ? $comboLastid->comboid + 1 : 1;

        $comboLastids = Comboids::orderBy('id', 'DESC')->first('id');
        $combo_ids = !empty($comboLastids) ? $comboLastids->id + 1 : 1;

        $comboids = new Comboids();
        $comboids->id = $combo_ids;
        $comboids->comboid = $combo_id;
        $comboids->payment_id = $payment_id;
        $comboids->iuid = $iuid;
        $comboids->system_module_id = '6';
        $comboids->general_ref_id = $ref_id;
        $comboids->main_or_followup = '0';
        $comboids->generated_by = $user->employee_id;
        $comboids->assigned_to = $user->employee_id;
        $comboids->company_id = $request->session()->get('customer');
        $comboids->supplier_id = $request->session()->get('seller');
        $comboids->company_type = $typeName;
        $comboids->followup_via = 'Payment';
        $comboids->inward_or_outward_via = $paymentData->refrencevia->name;
        $comboids->selection_date = $paymentData->reciptdate;
        $comboids->from_name = $personName;
        $comboids->receipt_mode = $paymentData->recipt_mode;
        $comboids->receipt_amount = (int)$paymentData->reciptamount;
        $comboids->total = $paymentData->totalamount;
        $comboids->subject = 'For'. $companyName->name .' RS '.$paymentData->totalamount .'/-';
        $comboids->financial_year_id = $financialid;
        $comboids->attachments = serialize($attachments);
        //$comboids->date_added = Carbon::now();
        $comboids->save();

        
        if ($paymentData->recipt_mode == 'cheque') {
            $cheque_date = $paymentData->reciptdate;
            $cheque_dd_no = $paymentData->chequeno;
            $cheque_dd_bank = $paymentData->chequebank;
        } else {
            $cheque_date = null;
            $cheque_dd_no = '';
            $cheque_dd_bank = 0;
        }

        if ($paymentData->recipt_mode == 'partreturn') {
            $payment_tot_adjust_amount = 0;
        } else {
            $payment_tot_adjust_amount= $paymentData->totaladjustamount;
        }

        $paymentLastid = Payment::orderBy('id', 'DESC')->first('id');
        $paymentId = !empty($paymentLastid) ? $paymentLastid->id + 1 : 1;

        $payment = new Payment();
        $payment->id = $paymentId;
        $payment->payment_id = $payment_id;
        $payment->reciept_mode = $paymentData->recipt_mode;
        $payment->iuid = $iuid;
        $payment->reference_id = $ref_id;
        $payment->attachments = $ChequeImage;
        $payment->letter_attachment = $LetterImage;
        $payment->financial_year_id = $financialid;
        $payment->date = $payment_date;
        $payment->deposite_bank = '4';
        $payment->cheque_date = $cheque_date;
        $payment->cheque_dd_no = $cheque_dd_no;
        $payment->cheque_dd_bank = (int)$cheque_dd_bank;
        $payment->receipt_from = $request->session()->get('customer');
        $payment->trns = $paymentData->term;
        $payment->supplier_id = $request->session()->get('seller');
        $payment->customer_id = $request->session()->get('customer');
        $payment->receipt_amount = $paymentData->reciptamount;
        $payment->total_amount = $paymentData->totalamount;
        $payment->tot_adjust_amount = $payment_tot_adjust_amount;

        $payment->save();
        $p_increment_id = $paymentId;

        if ($paymentSalebill) {
            $i=0;
			$tot_discount = 0;
		    $tot_vatav = 0;
			$tot_agent_commission = 0;
			$tot_bank_cpmmission = 0;
		    $tot_claim = 0;
		    $tot_good_returns = 0;
		    $tot_short = 0;
		    $tot_interest = 0;
		    $tot_rate_difference = 0;
		    $tot_adjust_amount = 0;

            foreach($paymentSalebill as $salebill) {
                $paymentDetailLastid = PaymentDetail::orderBy('payment_details_id', 'DESC')->first('payment_details_id');
                $paymentDetailId = !empty($paymentDetailLastid) ? $paymentDetailLastid->payment_details_id + 1 : 1;
                if ($paymentData->recipt_mode == 'partreturn') {
                    $paymentDetail = new PaymentDetail();
                    $paymentDetail->payment_id = $payment_id;
                    $paymentDetail->payment_details_id = $paymentDetailId;
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $financialid;
                    $paymentDetail->sr_no = $salebill->id;
                    $paymentDetail->flag_sale_bill_sr_no = '1';
                    $paymentDetail->status = '1';
                    $paymentDetail->supplier_invoice_no = $salebill->sup_inv;
                    $paymentDetail->amount = $salebill->amount;
                    $paymentDetail->adjust_amount = $salebill->adjustamount;
                    $paymentDetail->goods_return = $salebill->goodreturn;
                    $paymentDetail->remark = $salebill->remark;
                    $paymentDetail->save();
                    $tot_good_returns += $salebill->goodreturn;
                } else if ($paymentData->recipt_mode == 'fullreturn') {
                    $paymentDetail = new PaymentDetail();
                    $paymentDetail->payment_id = $payment_id;
                    $paymentDetail->payment_details_id = $paymentDetailId;
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $financialid;
                    $paymentDetail->sr_no = $salebill->id;
                    $paymentDetail->status = '0';
                    $paymentDetail->flag_sale_bill_sr_no = '1';
                    $paymentDetail->supplier_invoice_no = $salebill->sup_inv;
                    $paymentDetail->amount = $salebill->amount;
                    $paymentDetail->goods_return = $salebill->goodreturn;
                    $paymentDetail->remark = $salebill->remark;
                    $paymentDetail->save();
                    $tot_discount += 0;
					$tot_vatav += 0;
					$tot_agent_commission += 0;
					$tot_bank_cpmmission += 0;
					$tot_claim += 0;
					$tot_good_returns += (int)$_POST['goods_return'][$i];
					$tot_short += 0;
					$tot_interest += 0;
					$tot_rate_difference += 0;
					$tot_adjust_amount += 0;
                } else {
                    $paymentDetail = new PaymentDetail();
                    $paymentDetail->payment_details_id = $paymentDetailId;
                    $paymentDetail->payment_id = $payment_id;
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $financialid;
                    $paymentDetail->sr_no = $salebill->id;
                    $paymentDetail->flag_sale_bill_sr_no = '1';
                    $paymentDetail->status = '1';
                    $paymentDetail->supplier_invoice_no = $salebill->sup_inv;
                    $paymentDetail->discount = $salebill->discount;
                    $paymentDetail->discount_amount = $salebill->discountamount;
                    $paymentDetail->vatav = $salebill->vatav;
                    $paymentDetail->agent_commission = $salebill->agentcommission;
                    $paymentDetail->claim = $salebill->claim;
                    $paymentDetail->bank_commission = $salebill->bankcommission;
                    $paymentDetail->short = $salebill->short;
                    $paymentDetail->interest = $salebill->interest;
                    $paymentDetail->rate_difference = $salebill->ratedifference;
                    $paymentDetail->amount = $salebill->amount;
                    $paymentDetail->adjust_amount = $salebill->adjustamount;
                    $paymentDetail->goods_return = $salebill->goodreturn;
                    $paymentDetail->remark = $salebill->remark;
                    $paymentDetail->save();

                    $tot_discount += $salebill->discountamount;
					$tot_vatav += $salebill->vatav;
					$tot_agent_commission += $salebill->agentcommission;
					$tot_bank_cpmmission += $salebill->bankcommission;
					$tot_claim += $salebill->claim;
					$tot_good_returns += $salebill->goodreturn;
					$tot_short += $salebill->short;
					$tot_interest += $salebill->interest;
					$tot_rate_difference += $salebill->ratedifference;
					$tot_adjust_amount += $salebill->adjustamount;
                } 
            }   
        }
        if ($paymentData->recipt_mode == 'fullreturn') {
            $tot_receipt_adjust_amt = (int)$paymentData->reciptamount - $tot_adjust_amount;
            if ($tot_receipt_adjust_amt != 0) {
                $payment_ok_or_not = 0;
            } else {
                $payment_ok_or_not = 1;
            }
        } else if($paymentData->recipt_mode == 'partreturn') { 
            $payment_ok_or_not = 0;
        } else {
            $payment_ok_or_not = 1;
        }

        $payment1 = Payment::where('payment_id', $payment_id)->first();
        $payment1->tot_discount = $tot_discount;
        $payment1->tot_vatav = $tot_vatav;
        $payment1->tot_agent_commission = $tot_agent_commission;
        $payment1->tot_bank_cpmmission = $tot_bank_cpmmission;
        $payment1->tot_claim = $tot_claim;
        $payment1->tot_good_returns = $tot_good_returns;
        $payment1->tot_short = $tot_short;
        $payment1->tot_interest = $tot_interest;
        $payment->tot_rate_difference = $tot_rate_difference;
        $payment1->payment_ok_or_not = $payment_ok_or_not;
        $payment1->save();

        if ($paymentData->recipt_mode == 'fullreturn') {
            if ($tot_good_returns != 0) {
                $color_flag_id = 3;
            } else {
                $color_flag_id = 1;
            }
        } else if($paymentData->recipt_mode == 'partreturn') { 
            $color_flag_id = 1;
        } else {
            $color_flag_id = 3;
        }

        $comboid1 = Comboids::where('comboid', $combo_id)->first();
        $comboid1->color_flag_id = $color_flag_id;
        $comboid1->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'payment / Insert';
        $logs->log_subject = 'Payment insert page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

    }
    public function getBasicData(Request $request) {
        
        $customer_id = $request->session()->get('customer');
        $seller_id = $request->session()->get('seller');
        $salebill_ids = $request->session()->get('saleBill');
        $customer = Company::where('id', $customer_id)->first();
        $seller = Company::where('id', $seller_id)->first();
        $salebill = [array('id' => '101', 'sup_inv' => '1025', 'amount' => '5000', 'adjustamount' => '5000'),
        array('id' => '103', 'sup_inv' => '1028', 'amount' => '15000', 'adjustamount' => '15000')];
        $reference = ReferenceId::where('company_id', $customer_id)->get();
        $item = array();
        foreach ($reference as $ref) {
            array_push($item, array('reference_id' => $ref->reference_id, 'generateby' => $ref->employee_id, 'date' => $ref->selection_date));
        }
        $data['reference'] = $item;
        $data['customer'] = $customer;
        $data['seller'] = $seller;
        $data['salebill'] = $salebill;
        return $data;
    }
    public function addPayment(Request $request){
        $page_title = 'Add Payments';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('payment.addpayment',compact('financialYear', 'page_title'))->with('employees', $employees);
    }
}
