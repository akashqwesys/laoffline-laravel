<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Traits\HasRoles;
use App\Models\FinancialYear;
use App\Models\Employee;
use App\Models\Reference;
use App\Models\Logs;
use App\Models\Payment;
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
        return true;
    }
    public function selectSaleBills(Request $request){
        print_r($request->input());
        exit;
    }
    public function insertPaymentData(Request $request){
        $request->session()->put('finacial_year_id', '1');
        $user = Session::get('user');
        $paymentData = json_decode($request->formdata);
        $paymentSalebill = json_decode($request->billdata);
        $attachments = array();
        //print_r($paymentData->refrencevia->name);exit;

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
        
        $increment_id_details = IncrementId::where('financial_year_id', $request->session->get('finacial_year_id'));
        
        
        //reference_id
        if ($increment_id_details) {
            $ref_id = $increment_id_details->reference_id + 1;
            $increment_id = IncrementId::where('financial_year_id', $request->session->get('finacial_year_id'))->first();
            $increment_id->reference_id = $ref_id;
            $increment_id->save();
        } else {
            $ref_id = '1';
            $increment_id = new IncrementId();
            $increment_id->reference_id = $ref_id;
            $increment_id->financial_year_id = $request->session->get('finacial_year_id');
            $increment_id->save();
        }

        //payment_id 
        if ($increment_id_details) {
            $payment_id = $increment_id_details->payment_id + 1;
            $increment_id = IncrementId::where('financial_year_id', $request->session->get('finacial_year_id'))->first();
            $increment_id->payment_id = $payment_id;
            $increment_id->save();
        } else {
            $payment_id = '1';
            $increment_id = new IncrementId();
            $increment_id->payment_id = $payment_id;
            $increment_id->financial_year_id = $request->session->get('finacial_year_id');
            $increment_id->save();
        }

        //iuid
        if ($increment_id_details) {
            $iuid = $increment_id_details->iuid + 1;
            $increment_id = IncrementId::where('financial_year_id', $request->session->get('finacial_year_id'))->first();
            $increment_id->iuid = $iuid;
            $increment_id->save();
        } else {
            $iuid = '1';
            $increment_id = new IncrementId();
            $increment_id->reference_id = $iuid;
            $increment_id->financial_year_id = $request->session->get('finacial_year_id');
            $increment_id->save();
        }


        $user = Session::get('user');
        $refence = new Reference();
        $refence->reference_id = $ref_id;
        $refence->financial_year_id = $this->session->get('finacial_year_id');
        $refence->employe_id = $user->employee_id;
        $refence->inward_or_outward = '1';
        $refence->type_of_inward = $paymentData->refrencevia->name;
        $refence->company_id = $this->session->get('customer');
        $refence->selection_date = date('dd-mm-yyyy');
        $refence->from_name = $paymentData->from_name;
        $refence->from_email_id = $paymentData->emailfrom;
        $refence->courier_name = $paymentData->courrier->name;
        $refence->weight_of_parcel = $paymentData->weight;
        $refence->courier_receipt_no = $paymentData->reciptno;
        $refence->courier_received_time = $paymentData->recivedate;
        $refence->delivery_by = $paymentData->delivery;
        $refence->save();

        $payment_date = $paymentData->reciptdate;
        $iuid = Iuid::orderBy('id', 'DESC')->first('id');
        $nextAutoID = $iuid->id + 1;

        $companyName = Company::where('id', $request->session->get('seller'))->first();
        $cmpTypeName = Company::where('id', $request->session->get('customer'))->first();

        if ($cmpTypeName && $cmpTypeName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $company_type)->first();
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
        $iuid_ids->iuid = $iuid;
        $iuid_ids->financial_year_id = $request->session->get('finacial_year_id');
        $iuid_ids->save();

        $comboLastid = Comboids::orderBy('comboid', 'DESC')->first('comboid');
        $combo_id = !empty($comboLastid) ? $comboLastid->comboid + 1 : 1;

        $comboids = new Comboids();
        $comboids->comboid = $combo_id;
        $comboids->payment_id = $payment_id;
        $comboids->iuid = $iuid;
        $comboids->system_module_id = '6';
        $comboids->general_ref_id = $ref_id;
        $comboids->main_or_followup = '0';
        $comboids->generated_by = $user->employee_id;
        $comboids->assigned_to = $user->employee_id;
        $comboids->company_id = $request->session->get('customer');
        $comboids->supplier_id = $request->session->get('seller');
        $comboids->company_type = $typeName;
        $comboids->followup_via = 'Payment';
        $comboids->inward_or_outward_via = $paymentData->refrencevia->name;
        $comboids->selection_date = $paymentData->reciptdate;
        $comboids->from_name = $personName;
        $comboids->receipt_mode = $paymentData->recipt_mode;
        $comboids->receipt_amount = (int)$paymentData->reciptamount;
        $comboids->total = $paymentData->totalamount;
        $comboids->subject = 'For'. $companyName->name .' RS '.$paymentData->totalamount .'/-';
        $comboids->financial_year_id = $request->session->get('finacial_year_id');
        $comboids->attechment = serialize($attachments);
        $comboids->date_added = Carbon::now();
        $comboids->save();

        $cheque_date = "0000-00-00";
		$cheque_dd_no = "0";
		$cheque_dd_bank = "0";
		$trns = "0";
		$receipt_amt = "0";

        if ($paymentData->recipt_mode == 'cheque') {
            $cheque_date = $paymentData->reciptdate;
            $cheque_dd_no = $paymentData->chequeno;
            $cheque_dd_bank = $paymentData->chequebank;
        }

        if ($paymentData->recipt_mode == 'partreturn') {
            $payment_tot_adjust_amount = 0;
        } else {
            $payment_tot_adjust_amount= $payment->tot_adjust_amount;
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
        $payment->financial_year_id = $request->session->get('financial_year_id');
        $payment->date = $payment_date;
        $payment->deposite_bank = '4';
        $payment->cheque_date = $cheque_date;
        $payment->cheque_dd_no = $cheque_dd_no;
        $payment->cheque_dd_bank = (int)$cheque_dd_bank;
        $payment->receipt_from = $request->session->get('customer');
        $payment->trns = $paymentData->term;
        $payment->supplier_id = $request->session->get('seller');
        $payment->customer_id = $request->session->get('customer');
        $payment->receipt_amount = $paymentData->reciptamount;
        $payment->total_amount = $paymentData->totalAmount;
        $payment->tot_adjust_amount = $payment_tot_adjust_amount;

        $Payment->save();
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
                if ($paymentData->recipt_mode == 'partreturn') {
                    $paymentDetail = new PaymentDetail();
                    $paymentDetail->payment_id = $payment_id;
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $request->session->get('financial_year_id');
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
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $request->session->get('financial_year_id');
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
                    $paymentDetail->payment_id = $payment_id;
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $request->session->get('financial_year_id');
                    $paymentDetail->sr_no = $salebill->id;
                    $paymentDetail->flag_sale_bill_sr_no = '1';
                    $paymentDetail->status = '1';
                    $paymentDetail->supplier_invoice_no = $salebill->sup_inv;
                    $paymentDetail->discount = $salebill->discount;
                    $paymentDetail->discount_amount = $salebill->discountamount;
                    $paymentDetail->vatav = $salebill->vatav;
                    $paymentDetail->agentcommission = $salebill->agentcommission;
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
        $salebill = [array('id' => '101', 'sup_inv' => '1025', 'amount' => '5000'),
        array('id' => '103', 'sup_inv' => '1028', 'amount' => '15000')];
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
