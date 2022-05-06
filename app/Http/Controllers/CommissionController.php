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
use App\Models\Commission\commission;
use App\Models\SaleBill;
use App\Models\CommissionDetail;
use App\Models\PaymentDetail;
use App\Models\IncrementId;
use App\Models\Goods\GoodsReturn;
use App\Models\Goods\GrSaleBillItem;
use App\Models\settings\BankDetails;
use App\Models\settings\Agent;
use App\Models\Company\Company;
use App\Models\comboids\Comboids;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class CommissionController extends Controller
{
    use HasRoles;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $page_title = 'Commssion List';
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
        $logs->log_path = 'commission / View';
        $logs->log_subject = 'Commission view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('commission.commission',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function listCommission(Request $request) {
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
            $columnName = 'commissions.'.$columnName;
        }
        // Total records
        $totalRecords = commission::where('is_deleted', '0')->select('count(*) as allcount')->count();

        $totalRecordswithFilter = commission::where('financial_year_id', $user->financial_year_id)->where('is_deleted', '0');
        // if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
        //     $totalRecordswithFilter = $totalRecordswithFilter->where('payment_id', '=', $columnName_arr[0]['search']['value']);
        // }
        // if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
        //     $totalRecordswithFilter = $totalRecordswithFilter->where('iuid', '=', $columnName_arr[1]['search']['value']);
        // }
        // if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
        //     $totalRecordswithFilter = $totalRecordswithFilter->where('ouid', '=', $columnName_arr[2]['search']['value']);
        // }
        // if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
        //     $totalRecordswithFilter = $totalRecordswithFilter->where('reference_id', '=', $columnName_arr[3]['search']['value']);
        // }
        // if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
        //     $totalRecordswithFilter = $totalRecordswithFilter->whereDate('payments.created_at', '=', $columnName_arr[4]['search']['value']);
        // }
        // if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
        //     $totalRecordswithFilter = $totalRecordswithFilter->whereDate('payments.date', '=', $columnName_arr[3]['search']['value']);
        // }
        // if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
        //     $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[6]['search']['value'] . '%')->pluck('id')->toArray();
        //     $totalRecordswithFilter = $totalRecordswithFilter->whereIn('company_id', $cc_id);
        // }
        // if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
        //     $sp_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
        //     $totalRecordswithFilter = $totalRecordswithFilter->whereIn('supplier_id', $sp_id);
        // }
        // if (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value'])) {
        //     $totalRecordswithFilter = $totalRecordswithFilter->where('payment_id', '=', $columnName_arr[8]['search']['value']);
        // }
        // if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
        //     $totalRecordswithFilter = $totalRecordswithFilter->where('tot_adjust_amount', '=', $columnName_arr[9]['search']['value']);
        // }
        $totalRecordswithFilter = $totalRecordswithFilter->count();


        $records = commission::where('financial_year_id', $user->financial_year_id)->where('is_deleted', '0');
        // if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
        //     $records = $records->where('payment_id', '=', $columnName_arr[0]['search']['value']);
        // }
        // if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
        //     $records = $records->where('iuid', '=', $columnName_arr[1]['search']['value']);
        // }
        // if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
        //     $records = $records->where('ouid', '=', $columnName_arr[2]['search']['value']);
        // }
        // if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
        //     $records = $records->where('reference_id', '=', $columnName_arr[3]['search']['value']);
        // }
        // if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
        //     $records = $records->whereDate('payments.created_at', '=', $columnName_arr[4]['search']['value']);
        // }
        // if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
        //     $records = $records->whereDate('payments.date', '=', $columnName_arr[3]['search']['value']);
        // }
        // if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
        //     $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[6]['search']['value'] . '%')->pluck('id')->toArray();
        //     $records = $records->whereIn('company_id', $cc_id);
        // }
        // if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
        //     $sp_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
        //     $records = $records->whereIn('supplier_id', $sp_id);
        // }
        // if (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value'])) {
        //     $records = $records->where('payment_id', '=', $columnName_arr[8]['search']['value']);
        // }
        // if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
        //     $records = $records->where('tot_adjust_amount', '=', $columnName_arr[9]['search']['value']);
        // }


        // Fetch records
        $records = $records->select('*');

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $iscompleted = Comboids::where('commission_id', $record->commission_id)->where('financial_year_id', $user->financial_year_id)
                            ->where('is_deleted', 0)->select('is_completed','commission_id','color_flag_id')->first();
            $id = $record->commission_id;
            $iuid = $record->iuid;
            $ref_id = $record->reference_id;
            $date_add = date_format($record->created_at, "Y/m/d H:i:s");
            $seller = Company::where('id', $record->supplier_id)->first()->company_name;
            $paid_amount = $record->commission_payment_amount;
            if ($iscompleted && $iscompleted->is_completed == '1') {
                $completed = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-check"></em></a>';
            } else {
                $completed = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-cross"></em></a>';
            }
            if (!$record->done_outward) {
                $outward = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-cross"></em></a>';
            } else {
                $outward = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-check"></em></a>';
            }
            
            $action = '<a href="/commission/view-commission/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/commission/edit-commission/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="/commission/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "id" => $id,
                "iuid" => $iuid,
                "referenceid" => $ref_id,
                "dateadd" => $date_add,
                "company" => $seller,
                "Recivecommission" => $paid_amount,
                "completed" => $completed,
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

    public function createCommission() {
        $page_title = 'Add commission step - 1';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('commission.createcommission',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function listCompany() {
        $company = Company::where('is_delete', 0)->get();
        return $company;
    }

    public function searchCommissionInvoice(Request $request) {
        $company_id = $request->input('company');
        $user = Session::get('user');
        $commissioninvoice = DB::table('commission_invoices')
                    ->where('supplier_id', $company_id)
                    ->where('financial_year_id', $user->financial_year_id)
                    ->where('commission_status', 0)
                    ->orderBy('commission_invoice_id', 'desc')
                    ->get();
        $commissioninvoices = array();
        $commissioninvoices = [array('commission_id' => '257', 'financialyear' => '2022-2023', 'invoiceno' => 'AF-20212022-257', 'date' => '2022-05-01', 'amount' => '30000', 'overdue' => "60"),
                                array('commission_id' => '258', 'financialyear' => '2022-2023', 'invoiceno' => 'AF-20212022-260', 'date' => '2022-05-01', 'amount' => '36000', 'overdue' => "60")
    ];
        foreach($commissioninvoice as $invoice) {
            $invoice = array('commission_id' => $invoice->commission_invoice_id, 'financialyear' => $bill->financial_year_id, 'invoiceno' => $bill->bill_no, 'date' => $bill->bill_date, 'amount' => $bill->final_amount, 'overdue' => "60");
            array_push($commissioninvoices, $invoice);
        }
        $data['commissioninvoice'] = $commissioninvoices;
        return $data;
    }

    public function generateCommissionData(Request $request) {
        $request->session()->forget('company');
        $request->session()->forget('commissioninvoice');
        $request->session()->put('company', $request->company);
        $request->session()->put('commissioninvoice', $request->commissioninvoice);
    }

    public function addCommission(Request $request){
        $page_title = 'Add Commission';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('commission.addcommission',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function getBasicData(Request $request) {
        $company_id = $request->session()->get('company');
        
        $commissioninvoice_id = $request->session()->get('commissioninvoice');
        
        $company = Company::where('id', $company_id)->first();
        $agent = Agent::where('is_delete', '0')->get();
        $commissioninvoice = DB::table('commission_invoices')->where('financial_year_id', Session::get('user')->financial_year_id)->whereIn('commission_invoice_id', $commissioninvoice_id)->get();
        $commissioninvoice_data = array();
        $commissioninvoice_data = [
            array('commission_id' => '256', 'invoiceno' => 'AF-20212022-256', 'date' => '2022-05-01', 'totalCommission' => '30000')
        ];
        foreach ($commissioninvoice as $invoice) {
            $commission_invoice = array('commission_id' => $invoice->commission_invoice_id, 'invoiceno' => $invoice->bill_no, 'date' => $invoice->bill_date, 'totalCommission' => $invoice->final_amount);
            array_push($commissioninvoice_data, $commission_invoice);
        }
        $data['company'] = $company;
        $data['agent'] = $agent;
        $data['commissioninvoice'] = $commissioninvoice_data;
        return $data;
    }

    public function insertCommission(Request $request) {
        $user = Session::get('user');
        $commissionData = json_decode($request->formdata);
        $invoiceData = json_decode($request->invoicedata);
        $financialid = Session::get('user')->financial_year_id;
        $attachments = array();

        if (!file_exists(public_path('upload/commission'))) {
            mkdir(public_path('upload/commission'), 0777, true);
        }

        $ExtraImage = null;
        if ($image = $request->extraimage) {
            $ExtraImage = date('YmdHis') . "_extraImage." . $image->getClientOriginalExtension();
            $image->move(public_path('upload/commission/'), $ExtraImage);
            array_push($attachments, $ExtraImage);
        }

        $increment_id_details = IncrementId::where('financial_year_id', $financialid)->first();
        $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
        $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;

        if ($increment_id_details) {
            $ref_id = $increment_id_details->reference_id + 1;
            $commission_id = $increment_id_details->commission_id + 1;
            $iuid = $increment_id_details->iuid + 1;
            $increment_id = IncrementId::where('financial_year_id', $financialid)->first();
            $increment_id->reference_id = $ref_id;
            $increment_id->commission_id = $commission_id;
            $increment_id->iuid = $iuid;
            $increment_id->save();
        } else {
            $ref_id = '1';
            $commission_id = '1';
            $iuid = '1';
            $increment_id = new IncrementId();
            $increment_id->reference_id = $ref_id;
            $increment_id->commission_id = $commission_id;
            $increment_id->id = $Incrementids;
            $increment_id->iuid = $iuid;
            $increment_id->financial_year_id = $financialid;
            $increment_id->save();
        }
        if ($commissionData->refrence == '1') {
            if ($commissionData->refrencevia->name == 'Email') {
                $courier_name = '';
                $courier_receipt_no = '';
                $courier_received_time = Carbon::now()->format('d-m-Y');
            } else if ($commissionData->refrencevia->name == 'Hand') {
                $courier_name = '';
                $courier_receipt_no = '';
                $courier_received_time = $commissionData->recivedate;
            } else {
                $courier_name = $commissionData->courrier->name;
                $courier_receipt_no = $commissionData->reciptno;
                $courier_received_time = $commissionData->recivedate;
            }

            $refrenceLastid = ReferenceId::orderBy('id', 'DESC')->first('id');
            $refrenceid = !empty($refrenceLastid) ? $refrenceLastid->id + 1 : 1;

            $refence = new ReferenceId();
            $refence->id = $refrenceid;
            $refence->reference_id = $ref_id;
            $refence->financial_year_id = $financialid;
            $refence->employee_id = $user->employee_id;
            $refence->inward_or_outward = '1';
            $refence->type_of_inward = $commissionData->refrencevia->name;
            $refence->company_id = $request->session()->get('company');
            $refence->selection_date = Carbon::now()->format('d-m-Y');
            $refence->from_name = $commissionData->fromname;
            $refence->from_email_id = $commissionData->emailfrom;
            $refence->courier_name = $courier_name;
            $refence->weight_of_parcel = $commissionData->weight;
            $refence->courier_receipt_no = $courier_receipt_no;
            $refence->courier_received_time = $courier_received_time;
            $refence->delivery_by = $commissionData->delivery;
            $refence->save();
        } else {
            $ref_id = $paymentData->refrence_type;
        }

        $iuids = Iuid::orderBy('id', 'DESC')->first('id');
        $nextAutoID = !empty($iuids) ? $iuids->id + 1 : 1;

        $companyName = Company::where('id', $request->session()->get('company'))->first();
        

        if ($companyName && $companyName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $companyName->company_type)->first();
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }

        $personName = '';
        
        $iuid_ids = new Iuid();
        $iuid_ids->id = $nextAutoID;
        $iuid_ids->iuid = $iuid;
        $iuid_ids->financial_year_id = $financialid;
        $iuid_ids->save();

        $comboLastid = Comboids::orderBy('comboid', 'DESC')->first('comboid');
        $combo_id = !empty($comboLastid) ? $comboLastid->comboid + 1 : 1;
    
        $comboids = new Comboids();
        
        if ($typeName == "Supplier") {    
            $comboids->supplier_id = $request->session()->get('company');
        } else {
            $comboids->company_id = $request->session()->get('company');
        }
        $comboids->comboid = $combo_id;
        $comboids->payment_id = 0;
        $comboids->iuid = $iuid;
        $comboids->ouid = 0;
        $comboids->system_module_id = '7';
        $comboids->general_ref_id = $ref_id;
        $comboids->main_or_followup = '0';
        $comboids->generated_by = $user->employee_id;
        $comboids->assigned_to = $user->employee_id;
        $comboids->company_type = $typeName;
        $comboids->followup_via = 'Commission';
        $comboids->inward_or_outward_via = $commissionData->refrencevia->name;
        $comboids->from_name = $personName;
        $comboids->subject = 'For '. $companyName->name .' RS '.$commissionData->commissionamount .'/-';
        $comboids->financial_year_id = $financialid;
        $comboids->attachments = serialize($attachments);
        $comboids->updated_by = Session::get('user')->employee_id;
        $comboids->inward_or_outward_flag = 0;
        $comboids->inward_or_outward_id = 0;
        $comboids->sale_bill_id = 0;
        $comboids->payment_followup_id = 0;
        $comboids->goods_return_id = 0;
        $comboids->good_return_followup_id = 0;
        $comboids->commission_id = 0;
        $comboids->commission_followup_id = 0;
        $comboids->commission_invoice_id = 0;
        $comboids->is_invoice = '1';
        $comboids->sample_id = 0;
        $comboids->inward_ref_via = 0;
        $comboids->new_or_old_inward_or_outward = 0;
        $comboids->outward_employe_id = 0;
        $comboids->default_category_id = 0;
        $comboids->main_category_id = 0;
        $comboids->agent_id = 0;
        $comboids->sale_bill_flag = 0;
        $comboids->tds = 0;
        $comboids->net_received_amount = 0;
        $comboids->received_commission_amount = 0;
        $comboids->required_followup = 0;
        $comboids->is_completed = 0;
        $comboids->mark_as_draft = 0;
        $comboids->color_flag_id = '3';
        $comboids->product_qty = 0;
        $comboids->fabric_meters = 0;
        $comboids->sample_return_qty = 0;
        $comboids->mobile_flag = 0;
        $comboids->is_deleted = 0;
        $comboids->save();

        $CommissionLastId = commission::orderBy('id', 'DESC')->first('id');
        $commissionId = !empty($CommissionLastId) ? $CommissionLastId->id + 1 : 1;
        if(!empty($commissionData->commissiondate)) {
            $commissiondate = date('d-m-Y',strtotime($commissionData->commissiondate));
        } else {
            $commissiondate = Carbon::now()->format('d-m-Y');;
        }
        $commissions = new commission();
        if ($typeName == "Supplier") {    
            $commissions->supplier_id = $request->session()->get('company');
            $commissions->customer_id = '0';
        } else {
            $commissions->supplier_id = '0';
            $commissions->customer_id = $request->session()->get('company');
        }
        if ($commissionData->recipt_mode == 'cheque') {
            $depositebank = $commissionData->depositebank->id;
            $chequedate = $commissionData->chequedate;
            $chequebank = $commissionData->chequebank->id;
            $chequeno = $commissionData->chequeno;
        } else {
            $depositebank = '0';
            $chequedate = Carbon::now()->format('d-m-Y');
            $chequebank = '0';
            $chequeno = '';
        }
        $commissions->id = $commissionId;
        $commissions->commission_id = $commission_id;
        $commissions->iuid = $iuid;
        $commissions->reference_id = $ref_id;
        $commissions->payment_id = '0';
        $commissions->bill_no = '0';
        $commissions->bill_amount = '0';
        $commissions->received_amount = '0';
        $commissions->attachments = serialize($attachments);
        $commissions->deposite_bank = (int)$depositebank;
        $commissions->cheque_date = $chequedate;
        $commissions->cheque_dd_no = (int)$chequeno;
        $commissions->cheque_dd_bank = (int)$chequebank;
        $commissions->financial_year_id = $financialid;
        $commissions->commission_date = $commissiondate;
        $commissions->commission_account = $commissionData->commissionacc->id;
        $commissions->commission_reciept_mode = $commissionData->recipt_mode;
        $commissions->commission_deposite_bank = (int)$depositebank;
        $commissions->commission_cheque_date = $chequedate;
        $commissions->commission_cheque_dd_no = (int)$chequeno;
        $commissions->commission_cheque_dd_bank = (int)$chequebank;
        $commissions->commission_payment_amount = $commissionData->commissionamount;
        $commissions->tds = '0';
        $commissions->net_received_amount = '0';
        $commissions->received_commission_amount = '0';
        $commissions->required_followup = '0';
        $commissions->done_outward = '0';
        $commissions->normal_amt_flag = '3';
        $commissions->date_added = Carbon::now();
        $commissions->is_deleted = '0';
        $commissions->is_invoice = '1';
        $commissions->save();

        $c_increment_id = $commissionId;
        $comboid1 = Comboids::where('comboid', $combo_id)->first();
        $comboid1->commission_id = $commission_id;
        $comboid1->save();

        foreach ($invoiceData as $invoice) {
            $bill_date = Carbon::now()->format('d-m-Y');
            $cheque_date = Carbon::now()->format('d-m-Y');
            $tds = 0;
            $service_tax = 0;
            $commission_status = $invoice->status->code;
            $commission_pay_amount = $invoice->amount;
            $remark = $invoice->remark;
           // $financial_year_id = commissionInvoice::where('commission_invoice_id', $invoice->id)->first()->financial_year_id;
            $commission_detail = new CommissionDetail();
            $CommissionDetailLastId = CommissionDetail::orderBy('id', 'DESC')->first('id');
            $commissionDetailId = !empty($CommissionDetailLastId) ? $CommissionDetailLastId->id + 1 : 1;
            $commission_detail->id = $commissionDetailId;
            $commission_detail->commission_details_id = $commissionDetailId;
            $commission_detail->c_increment_id = $c_increment_id;
            $commission_detail->payment_id = '0';
            $commission_detail->financial_year_id = '7';
            $commission_detail->commission_id = $commission_id;
            $commission_detail->commission_invoice_id = $invoice->commission_id;
            $commission_detail->bill_date = $invoice->date;
            $commission_detail->deposite_bank = '0';
            $commission_detail->cheque_date = $cheque_date;
            $commission_detail->cheque_dd_bank = '0';
            $commission_detail->cheque_dd_no = '';
            $commission_detail->percentage = '';
            $commission_detail->bill_amount = $invoice->totalCommission;
            $commission_detail->received_amount = '0';
            $commission_detail->service_tax = '0';
            $commission_detail->tds = '0';
            $commission_detail->commission_date = $commissiondate;
            $commission_detail->commission_account = $commissionData->commissionacc->id;
            $commission_detail->net_received_amount = $commission_pay_amount;
            $commission_detail->received_commission_amount = $commission_pay_amount;
            $commission_detail->status = $commission_status;
            $commission_detail->remark = $remark;
            $commission_detail->is_deleted = '0';
            $commission_detail->save();
            
        }
        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Commission / Insert';
        $logs->log_subject = 'Commission insert page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
        $request->session()->forget('company');
        $request->session()->forget('commissioninvoice');
    }

    public function editCommission(Request $request) {
        $page_title = 'Edit Commission';
        $id = $request->id;
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['editedId'] = $id;
        $employees['scope'] = "edit";
        return view('commission.editcommission',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function fetchCommission($id) {
        $commission = commission::where('commission_id', $id)->first();
        $supplier = Company::where('id', $commission->supplier_id)->first();
        $customer = Company::where('id', $commission->customer_id)->first();
        $agent = Agent::where('is_delete', '0')->get();
        $commissioninvoice = CommissionDetail::where('commission_id', $commission->commission_id)->get();
        
        $data['commission'] = $commission;
        $data['created_at'] = date_format($commission->created_at,"Y/m/d H:i:s");
        $data['commissioninvoice'] = $commissioninvoice;
        $data['customer'] = $customer;
        $data['agent'] = $agent;
        $data['supplier'] = $supplier;
        return $data;
    }

    public function updateCommission(Request $request) {
        $user = Session::get('user');
        $commissionData = json_decode($request->formdata);
        $invoiceData = json_decode($request->invoicedata);
        $financialid = Session::get('user')->financial_year_id;

        $attachments = array();
        $commissions = commission::where('commission_id', $commissionData->id)->where('financial_year_id', $financialid)->first();
        $comboids = Comboids::where('iuid', $commissions->iuid)->where('financial_year_id', $financialid)->first();
        if (!file_exists(public_path('upload/commission'))) {
            mkdir(public_path('upload/commission'), 0777, true);
        }

        $ExtraImage = null;
        if ($image = $request->extraimage) {
            $ExtraImage = date('YmdHis') . "_extraImage." . $image->getClientOriginalExtension();
            $image->move(public_path('upload/commission/'), $ExtraImage);
            array_push($attachments, $ExtraImage);
        }
        $companyName = Company::where('id', $commissionData->companyid)->first();
        

        if ($companyName && $companyName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $companyName->company_type)->first();
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }
        $personName = '';

        if ($commissionData->referecechange == '1'){
            $increment_id_details = IncrementId::where('financial_year_id', $financialid)->first();
            $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
            $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;

            if ($increment_id_details) {
                $ref_id = $increment_id_details->reference_id + 1;
                $increment_id = IncrementId::where('financial_year_id', $financialid)->first();
                $increment_id->reference_id = $ref_id;
                $increment_id->save();
            } else {
                $ref_id = '1';
                $iuid = '1';
                $increment_id = new IncrementId();
                $increment_id->reference_id = $ref_id;
                $increment_id->id = $Incrementids;
                $increment_id->financial_year_id = $financialid;
                $increment_id->save();
            }
            if ($commissionData->refrence == '1') {
                if ($commissionData->refrencevia->name == 'Email') {
                    $courier_name = '';
                    $courier_receipt_no = '';
                    $courier_received_time = Carbon::now()->format('d-m-Y');
                } else if ($commissionData->refrencevia->name == 'Hand') {
                    $courier_name = '';
                    $courier_receipt_no = '';
                    $courier_received_time = $commissionData->recivedate;
                } else {
                    $courier_name = $commissionData->courrier->name;
                    $courier_receipt_no = $commissionData->reciptno;
                    $courier_received_time = $commissionData->recivedate;
                }
    
                $refrenceLastid = ReferenceId::orderBy('id', 'DESC')->first('id');
                $refrenceid = !empty($refrenceLastid) ? $refrenceLastid->id + 1 : 1;
    
                $refence = new ReferenceId();
                $refence->id = $refrenceid;
                $refence->reference_id = $ref_id;
                $refence->financial_year_id = $financialid;
                $refence->employee_id = $user->employee_id;
                $refence->inward_or_outward = '1';
                $refence->type_of_inward = $commissionData->refrencevia->name;
                $refence->company_id = $request->session()->get('company');
                $refence->selection_date = Carbon::now()->format('d-m-Y');
                $refence->from_name = $commissionData->fromname;
                $refence->from_email_id = $commissionData->emailfrom;
                $refence->courier_name = $courier_name;
                $refence->weight_of_parcel = $commissionData->weight;
                $refence->courier_receipt_no = $courier_receipt_no;
                $refence->courier_received_time = $courier_received_time;
                $refence->delivery_by = $commissionData->delivery;
                $refence->save();

                $comboids->general_ref_id = $ref_id;
                $comboids->inward_or_outward_via = $commissionData->refrencevia->name;
                $comboids->attachments = serialize($attachments);
                $comboids->subject = 'For '. $companyName->name .' RS '.$commissionData->commissionamount .'/-';
                $comboids->save();

            } else {
                $ref_id = $commissionData->refrence_type;
                $comboids->general_ref_id = $ref_id;
                $comboids->attachments = serialize($attachments);
                $comboids->subject = 'For '. $companyName->name .' RS '.$commissionData->commissionamount .'/-';
                $comboids->save();
            }
        } else {
            $ref_id = $commissions->reference_id;
            $comboids->general_ref_id = $ref_id;
            $comboids->attachments = serialize($attachments);
            $comboids->subject = 'For '. $companyName->name .' RS '.$commissionData->commissionamount .'/-';
            $comboids->save();
        }
        if(!empty($commissionData->commissiondate)) {
            $commissiondate = date('d-m-Y',strtotime($commissionData->commissiondate));
        } else {
            $commissiondate = Carbon::now()->format('d-m-Y');
        }
        
        if ($typeName == "Supplier") {    
            $commissions->supplier_id = $request->session()->get('company');
            $commissions->customer_id = '0';
        } else {
            $commissions->supplier_id = '0';
            $commissions->customer_id = $request->session()->get('company');
        }
        if ($commissionData->recipt_mode == 'cheque') {
            $depositebank = $commissionData->depositebank->id;
            $chequedate = $commissionData->chequedate;
            $chequebank = $commissionData->chequebank->id;
            $chequeno = $commissionData->chequeno;
        } else {
            $depositebank = '0';
            $chequedate = Carbon::now()->format('d-m-Y');
            $chequebank = '0';
            $chequeno = '';
        }
    
        $commissions->reference_id = $ref_id;
        $commissions->attachments = serialize($attachments);
        $commissions->deposite_bank = (int)$depositebank;
        $commissions->cheque_date = $chequedate;
        $commissions->cheque_dd_no = (int)$chequeno;
        $commissions->cheque_dd_bank = (int)$chequebank;
        $commissions->financial_year_id = $financialid;
        $commissions->commission_date = $commissiondate;
        $commissions->commission_account = $commissionData->commissionacc;
        $commissions->commission_reciept_mode = $commissionData->recipt_mode;
        $commissions->commission_deposite_bank = (int)$depositebank;
        $commissions->commission_cheque_date = $chequedate;
        $commissions->commission_cheque_dd_no = (int)$chequeno;
        $commissions->commission_cheque_dd_bank = (int)$chequebank;
        $commissions->commission_payment_amount = $commissionData->commissionamount;
        $commissions->normal_amt_flag = '3';
        $commissions->save();

        foreach ($invoiceData as $invoice) {
            $commission_status = $invoice->status->code;
            $commission_pay_amount = $invoice->amount;
            $remark = $invoice->remark;
            $commission_detail = CommissionDetail::where('commission_invoice_id', $invoice->id)->first();
            
            $commission_detail->bill_date = $invoice->date;
            $commission_detail->bill_amount = $invoice->totalCommission;
            $commission_detail->received_amount = '0';
            $commission_detail->service_tax = '0';
            $commission_detail->tds = '0';
            $commission_detail->commission_date = $commissiondate;
            $commission_detail->commission_account = $commissionData->commissionacc;
            $commission_detail->net_received_amount = $commission_pay_amount;
            $commission_detail->received_commission_amount = $commission_pay_amount;
            $commission_detail->status = $commission_status;
            $commission_detail->remark = $remark;
            $commission_detail->save();
        }

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Commission / update';
        $logs->log_subject = 'Commission update page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();       
    }

    public function viewCommission($id) {
        $page_title = 'View Commission';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['id'] = $id;
        return view('commission.viewcommission',compact('financialYear', 'page_title'))->with('employees', $employees);       
    }

    
}