<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\Settings\TransportDetails;
use App\Models\comboids\Comboids;
use App\Models\SaleBill;
use App\Models\SaleBillTransport;
use Illuminate\Support\Facades\Session;
use Carbon;
use DB;

class SaleBillController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $page_title = 'Sale Bill';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)->first();

        $employees['excelAccess'] = $user->excel_access;

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Account / Sale Bill';
        $logs->log_subject = 'Sale Bill view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('account.sale_bill.sale_bills',compact('financialYear', 'page_title', 'employees'));
    }

    public function listSaleBill(Request $request)
    {
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

        $user = Session::get('user');

        $totalRecords = SaleBill::select('id')
            ->where('financial_year_id', $user->financial_year_id)
            ->where('is_deleted', 0)
            ->count();
        $totalRecordswithFilter = SaleBill::selectRaw('count(id) as allcount')
            ->where('financial_year_id', $user->financial_year_id);
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('sale_bill_id', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('general_ref_id', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('updated_at', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('select_date', '=', $columnName_arr[4]['search']['value']);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[5]['search']['value'] . '%')->where('company_type', 2)->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('company_id', $cc_id);
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            $ss_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[6]['search']['value'] . '%')->where('company_type', 3)->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('supplier_id', $ss_id);
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('supplier_invoice_no', '=', $columnName_arr[7]['search']['value']);
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();

        $SaleBill = DB::table('sale_bills as s')
            ->leftJoin('companies as cc', 's.company_id', '=', 'cc.id')
            ->leftJoin('companies as cs', 's.company_id', '=', 'cs.id')
            ->leftJoin('payment_details as pd', 's.sale_bill_id', '=', 'pd.sr_no')
            ->leftJoin('payments as p', 'pd.p_increment_id', '=', 'p.id')
            // ->leftJoin('outward_sale_bills as o', 's.sale_bill_id', '=', 'o.sale_bill_id')
            // ->leftJoin('comboids as cid', 's.sale_bill_id', '=', 'cid.sale_bill_id')
            ->select('s.id', 's.sale_bill_id', 's.select_date', 's.iuid', 's.general_ref_id', 's.updated_at', 's.company_id', 's.supplier_id', 's.supplier_invoice_no', 'cc.company_name as customer_name', 'cs.company_name as supplier_name', 's.total', 's.financial_year_id', 's.done_outward', 's.sale_bill_flag', 'p.payment_id', 'pd.sr_no'/* , 'o.outward_id', 'cid.is_completed', 'cid.color_flag_id' */)
            ->where('s.financial_year_id', $user->financial_year_id);
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $SaleBill = $SaleBill->where('s.sale_bill_id', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $SaleBill = $SaleBill->where('s.general_ref_id', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $SaleBill = $SaleBill->whereDate('s.updated_at', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $SaleBill = $SaleBill->where('s.select_date', '=', $columnName_arr[4]['search']['value']);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[5]['search']['value'] . '%')->where('company_type', 2)->pluck('id')->toArray();
            $SaleBill = $SaleBill->whereIn('s.company_id', $cc_id);
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            $ss_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[6]['search']['value'] . '%')->where('company_type', 3)->pluck('id')->toArray();
            $SaleBill = $SaleBill->whereIn('s.supplier_id', $ss_id);
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            $SaleBill = $SaleBill->where('s.supplier_invoice_no', '=', $columnName_arr[7]['search']['value']);
        }

        $SaleBill = $SaleBill->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($SaleBill as $s) {
            $updated_at = date('d-m-Y H:i A', strtotime($s->updated_at));
            $select_date = date('d-m-Y', strtotime($s->select_date));
            if ($s->done_outward == 0) {
                $outward_status = '<em class="icon ni ni-cross" title="No"></em>';
            } else {
                $outward_status = '<a href="' . ($s->outward_id ?? 0) . '" class="" ><em class="icon ni ni-check-thick" title="Yes"></em></a>';
            }
            $ref_id = '<a href="/reference/view-reference/'.$s->general_ref_id.'" class="" target="_blank">' . $s->general_ref_id . '</a>';
            if ($s->payment_id) {
                $payment_status = '<a href="' . $s->payment_id . '" class="" ><em class="icon ni ni-check-thick" title="Yes"></em></a>';
            } else {
                $payment_status = '<em class="icon ni ni-cross" title="No"></em>';
            }
            $action = null;
            if ($s->sale_bill_flag == 0) {
                $action .= '<a href="/account/sale-bill/view-bill/' . $s->sale_bill_id . '/'. $user->financial_year_id .'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="View"><em class="icon ni ni-eye"></em></a> ';
            }
            if (/* $s->is_completed == 0 && */ empty($s->payment_id)) {
                $action .= '<a href="/account/sale-bill/edit-sale-bill/' . $s->sale_bill_id . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a> ';
            }
            if ($s->sale_bill_id != $s->sr_no) {
                $action .= '<a href="/account/sale-bill/delete/' . $s->sale_bill_id . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a> ';
            }
            $action .= '<a href="account/sale-bill/copy/' . $s->sale_bill_id . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="View"><em class="icon ni ni-copy"></em></a>';

            $data_arr[] = array(
                "sale_bill_id" => $s->sale_bill_id,
                "iuid" => $s->iuid,
                "general_ref_id" => $ref_id,
                "updated_at" => $updated_at,
                "select_date" => $select_date,
                "customer" => $s->customer_name,
                "supplier" => $s->supplier_name,
                "supplier_invoice_no" => $s->supplier_invoice_no,
                "total" => $s->total,
                "payment_status" => $payment_status,
                "outward_status" => $outward_status,
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

    public function createSaleBill()
    {
        $page_title = 'Create Sale Bill';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        return view('account.sale_bill.createSaleBill', compact('financialYear', 'page_title', 'employees'));
    }

    public function AddSaleBill(Request $request)
    {
        $referenceDetails = json_decode($request->referenceDetails);
        $referenceDetails->sale_bill_via = 3;
        $productDetails = json_decode($request->productDetails);
        $fabricDetails = json_decode($request->fabricDetails);
        $totals = json_decode($request->totals);
        $transportDetails = json_decode($request->transportDetails);
        $changeAmount = json_decode($request->changeAmount);

        $user = Session::get('user');
        if ($request->hasFile('extra_attachment')) {
            $extra_attachment = date('YmdHis') . "_." . $request->extra_attachment->getClientOriginalExtension();
            $request->extra_attachment->move(public_path('upload/sale_bill'), $extra_attachment);
        } else {
            $extra_attachment = '';
        }

        $dateAdded = date('Y-m-d H:i:s');
        $select_date = date('Y-m-d', strtotime($referenceDetails->bill_date));
        $transport_date = date('Y-m-d', strtotime($transportDetails->transport_date));
        $customer_id = $referenceDetails->customer->id ?? 0;
        $transport_id = $transportDetails->transport->id ?? 0;
        $supplier_id = $referenceDetails->supplier->id ?? 0;
        $ref_company_id = $customer_id;
        if ($ref_company_id == 0 || $ref_company_id == '') {
            return response()->json(['redirect' => 'account/sale-bill']);
        }

        $companyName = $this->getCompanyNameWithId($ref_company_id);

        if ($companyName->company_type != 0) {
            $companyTypeName = $this->getCompanyTypeName($companyName->company_type);
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }
        $companyPerson = $this->getCompanyDetails($ref_company_id);
        if ($companyPerson) {
            $personName = $companyPerson->contact_person_name;
        } else {
            $personName = '';
        }
        $increment_id_details = $this->getIncrementIdDetails($user->financial_year_id);
        $receiver_details = $user->user_email;
        $general_ref = $this->getReferenceDetails($referenceDetails->sale_bill_via, $referenceDetails->reference_via);
        if ($referenceDetails->new_old_sale_bill == 1 || count($general_ref) < 1) {
            $ref_via = $referenceDetails->reference_via->name;
            if ($referenceDetails->reference_via->name == "Email") {
                $from_name = null;
                $from_email_id = $referenceDetails->from_email;
                $latter_by_id = null;
                $courier_name = null;
                $weight_of_parcel = null;
                $courier_receipt_no = null;
                $courier_received_time = '0000-00-00 00:00:00';
                $delivery_by = null;
            } else if ($referenceDetails->reference_via->name == "Courier") {
                $from_name = null;
                $from_email_id = null;
                $latter_by_id = '1';
                $courier_name = $transport_id;
                $weight_of_parcel = $transportDetails->courier_weight;
                $courier_receipt_no = $transportDetails->lr_mr_no;
                $courier_received_time = $transport_date;
                $delivery_by = $referenceDetails->delivery_by;
            } else if ($referenceDetails->reference_via->name == "Hand") {
                $from_name = null;
                $from_email_id = null;
                $latter_by_id = '0';
                $courier_name = null;
                $courier_weight_of_parcel = $transportDetails->courier_weight;
                $courier_receipt_no = null;
                $courier_received_time = $transport_date;
                $delivery_by = $referenceDetails->delivery_by;
            }
            if ($increment_id_details) {
                $ref_id = $increment_id_details->reference_id + 1;
                $data_ref = array('reference_id' => $ref_id, 'updated_at' => $dateAdded);
                $this->updateIncrementIds($data_ref, $user->financial_year_id);
            } else {
                $ref_id = 1;
                $data_ref = array(
                    'reference_id' => $ref_id,
                    'financial_year_id' => $user->financial_year_id,
                    'created_at' => $dateAdded,
                    'updated_at' => $dateAdded
                );
                $this->insertIncrementIds($data_ref);
            }

            DB::table('reference_ids')->insert([
                'reference_id' => $ref_id,
                'financial_year_id' => $user->financial_year_id,
                'employee_id' => $user->employee_id,
                'type_of_inward' => $ref_via,
                'inward_or_outward' => 1,
                'company_id' => $ref_company_id,
                'selection_date' => $select_date,
                'from_name' => $from_name,
                'receiver_email_id' => $receiver_details,
                'from_email_id' => $from_email_id,
                'latter_by_id' => (int)$latter_by_id,
                'courier_name' => $courier_name,
                'weight_of_parcel' => $weight_of_parcel,
                'courier_receipt_no' => $courier_receipt_no,
                'courier_received_time' => $courier_received_time,
                'delivery_by' => $delivery_by,
                'created_at' => $dateAdded,
                'updated_at' => $dateAdded
            ]);
            $general_ref_no = $ref_id;
        } else {
            $general_ref_no = $referenceDetails->reference_id;
        }
        if ($increment_id_details) {
            $iuid = $increment_id_details->iuid + 1;
            $data_iuid = array('iuid' => $iuid, 'updated_at' => $dateAdded);
            $this->updateIncrementIds($data_iuid, $user->financial_year_id);
        } else {
            $iuid = 1;
            $data_iuid = array(
                'iuid' => $iuid,
                'financial_year_id' => $user->financial_year_id,
                'created_at' => $dateAdded,
                'updated_at' => $dateAdded
            );
            $this->insertIncrementIds($data_iuid);
        }
        $dataentry_iuid = array(
            'iuid' => $iuid,
            'financial_year_id' => $user->financial_year_id,
            'created_at' => $dateAdded,
            'updated_at' => $dateAdded
        );
        DB::table('iuids')->insert($dataentry_iuid);

        $combo_id = new Comboids;
        $combo_id->iuid                          = $iuid;
        $combo_id->general_ref_id                = $general_ref_no;
        $combo_id->inward_ref_via                = $referenceDetails->sale_bill_via;
        $combo_id->new_or_old_inward_or_outward = $referenceDetails->new_old_sale_bill;
        $combo_id->system_module_id             = 5;
        $combo_id->main_or_followup             = 0;
        $combo_id->generated_by                 = $user->employee_id;
        $combo_id->assigned_to                  = $user->employee_id;
        $combo_id->updated_by                   = $user->employee_id;
        $combo_id->company_id                   = $customer_id;
        $combo_id->supplier_id                  = $supplier_id;
        $combo_id->company_type                 = $typeName;
        $combo_id->followup_via                 = 'Sale Bill';
        $combo_id->inward_or_outward_via        = $referenceDetails->reference_via->name;
        $combo_id->selection_date               = $select_date;
        $combo_id->from_name                    = $personName;
        $combo_id->subject                      = 'For ' . $companyName->name . ' Of Rs. ' . $request->final_total . '/-';
        $combo_id->default_category_id          = $referenceDetails->sale_bill_for->id;
        $combo_id->main_category_id             = $referenceDetails->product_category->id;
        $combo_id->agent_id                     = $referenceDetails->agent;
        $combo_id->supplier_invoice_no          = $referenceDetails->supplier_invoice_no;
        $combo_id->total                        = intval($request->final_total);
        $combo_id->sale_bill_flag               = 0;
        $combo_id->financial_year_id            = $user->financial_year_id;
        $combo_id->required_followup            = 0;
        $combo_id->color_flag_id                = 0;
        $combo_id->attachments                  = $extra_attachment;
        $combo_id->ouid = 0;
        $combo_id->follow_as_inward_or_outward = 0;
        $combo_id->inward_or_outward_flag = 0;
        $combo_id->inward_or_outward_id = 0;
        $combo_id->sale_bill_id = 0;
        $combo_id->payment_id = 0;
        $combo_id->payment_followup_id = 0;
        $combo_id->goods_return_id = 0;
        $combo_id->good_return_followup_id = 0;
        $combo_id->commission_id = 0;
        $combo_id->commission_followup_id = 0;
        $combo_id->commission_invoice_id = 0;
        $combo_id->is_invoice = 0;
        $combo_id->sample_id = 0;
        $combo_id->inform_md = 0;
        $combo_id->from_number = 0;
        $combo_id->receiver_number = 0;
        $combo_id->from_email_id = 0;
        $combo_id->receiver_email_id = 0;
        $combo_id->outward_attachments = 0;
        $combo_id->outward_employe_id = 0;
        $combo_id->receipt_mode = 0;
        $combo_id->receipt_amount = 0;
        $combo_id->tds = 0;
        $combo_id->net_received_amount = 0;
        $combo_id->received_commission_amount = 0;
        $combo_id->action_date = 0;
        $combo_id->action_instruction = 0;
        $combo_id->next_follow_up_date = 0;
        $combo_id->next_follow_up_time = 0;
        $combo_id->being_late = 0;
        $combo_id->system_url = 0;
        $combo_id->enjay_uniqueid = 0;
        $combo_id->is_completed = 0;
        $combo_id->mark_as_draft = 0;
        $combo_id->product_qty = 0;
        $combo_id->fabric_meters = 0;
        $combo_id->sample_return_qty = 0;
        $combo_id->mobile_flag = 0;
        $combo_id->is_deleted = 0;
        $combo_id->save();

        $comboid = $combo_id->comboid;

        if ( $referenceDetails->sale_bill_for->id == 1) {
            $subCategory = array();
            if (count($referenceDetails->product_sub_category)) {
                foreach ($referenceDetails->product_sub_category as $v) {
                    $subCategory[] = $v->id;
                }
            }
        } else {
            $subCategory = 0;
        }
        if ($increment_id_details) {
            $sale_bill_id = $increment_id_details->sale_bill_id + 1;
            $data_sale_bill_id = array('sale_bill_id' => $sale_bill_id, 'updated_at' => $dateAdded);
            $this->updateIncrementIds($data_sale_bill_id, $user->financial_year_id);
        } else {
            $sale_bill_id = 1;
            $data_sale_bill_id = array(
                'sale_bill_id' => $sale_bill_id,
                'financial_year_id' => $user->financial_year_id,
                'created_at' => $dateAdded,
                'updated_at' => $dateAdded
            );
            $this->insertIncrementIds($data_sale_bill_id);
        }

        $sale_bill = new SaleBill;
        $sale_bill->sale_bill_id             = $sale_bill_id;
        $sale_bill->sale_bill_for            = $referenceDetails->sale_bill_for->id;
        $sale_bill->attachments              = $extra_attachment;
        $sale_bill->general_ref_id           = $general_ref_no;
        $sale_bill->sale_bill_via            = $referenceDetails->sale_bill_via;
        $sale_bill->new_or_old_reference     = $referenceDetails->new_old_sale_bill;
        $sale_bill->product_default_category_id = $referenceDetails->product_category->id;
        $sale_bill->product_category_id      = json_encode($subCategory);
        $sale_bill->inward_id                = $referenceDetails->reference_inward;
        $sale_bill->company_id               = $customer_id;
        $sale_bill->address                  = $referenceDetails->customer_address->id;
        $sale_bill->supplier_id              = $supplier_id;
        $sale_bill->agent_id                 = $referenceDetails->agent;
        $sale_bill->supplier_invoice_no      = $referenceDetails->supplier_invoice_no;
        $sale_bill->select_date              = $select_date;
        $sale_bill->financial_year_id        = $user->financial_year_id;
        $sale_bill->change_in_amount         = (int)$changeAmount->change_in_amount;
        $sale_bill->sign_change              = $changeAmount->change_in_sign->name;
        $sale_bill->total                    = (int)$request->final_total;
        $sale_bill->remark                   = $changeAmount->transport_remark;
        $sale_bill->sale_bill_flag           = 0;
        $sale_bill->required_followup        = 0;
        $sale_bill->iuid                     = $iuid;
        $sale_bill->save();

        $dataentry_update = array('sale_bill_id' => $sale_bill_id, 'updated_at' => $dateAdded);
        $this->updateComboId($dataentry_update, $comboid);
        $total_peices = 0;
        $total_meters = 0;
        if (count($productDetails)) {
            foreach ($productDetails as $row) {
                $dataentry_item[] = array(
                    'sale_bill_id'         => $sale_bill_id,
                    'product_or_fabric_id' => intval($row->product_name->id),
                    'financial_year_id'    => $user->financial_year_id,
                    'sub_product_id'       => intval($row->sub_product_name->id),
                    'pieces'               => intval($row->pieces),
                    'rate'                 => floatval($row->rate),
                    'hsn_code'             => $row->hsn_code,
                    'discount'             => floatval($row->discount),
                    'cgst'                 => floatval($row->cgst),
                    'sgst'                 => floatval($row->sgst),
                    'igst'                 => floatval($row->igst),
                    'discount_amount'      => floatval($row->discount_amount),
                    'cgst_amount'          => floatval($row->cgst_amount),
                    'sgst_amount'          => floatval($row->sgst_amount),
                    'igst_amount'          => floatval($row->igst_amount),
                    'amount'               => floatval($row->amount),
                    'created_at'           => $dateAdded,
                    'updated_at'           => $dateAdded
                );
                $total_peices += intval($row->pieces);
            }
        }
        if (count($fabricDetails)) {
            foreach ($fabricDetails as $row) {
                $dataentry_item[] = array(
                    'sale_bill_id'         => $sale_bill_id,
                    'product_or_fabric_id' => intval($row->fabric_name->id),
                    'financial_year_id'    => $user->financial_year_id,
                    'pieces'               => intval($row->pieces),
                    'meters'               => floatval($row->meters),
                    'pieces_meters'        => intval($row->pieces_or_meters),
                    'rate'                 => floatval($row->rate),
                    'hsn_code'             => $row->hsn_code,
                    'discount'             => floatval($row->discount),
                    'cgst'                 => floatval($row->cgst),
                    'sgst'                 => floatval($row->sgst),
                    'igst'                 => floatval($row->igst),
                    'discount_amount'      => floatval($row->discount_amount),
                    'cgst_amount'          => floatval($row->cgst_amount),
                    'sgst_amount'          => floatval($row->sgst_amount),
                    'igst_amount'          => floatval($row->igst_amount),
                    'amount'               => floatval($row->amount),
                    'created_at'           => $dateAdded,
                    'updated_at'           => $dateAdded
                );
                $total_peices += intval($row->pieces);
                $total_meters += floatval($row->meters);
            }
        }
        DB::table('sale_bill_items')->insert($dataentry_item);
        // 07-03-2017 to update total_peices & total_meters which is usefull for sales_register_report.
        $wr = array("is_deleted" => 0, "sale_bill_id" => $sale_bill_id, "financial_year_id" => $user->financial_year_id);
        $fld = array("total_peices" => $total_peices, "total_meters" => $total_meters);
        DB::table('sale_bills')->where($wr)->update($fld);
        // end

        $transport_detail = new SaleBillTransport;
        $transport_detail->sale_bill_id = $sale_bill_id;
        $transport_detail->transport_id = $transport_id;
        $transport_detail->financial_year_id = $user->financial_year_id;
        $transport_detail->station = $transportDetails->station->id;
        $transport_detail->lr_mr_no = $transportDetails->lr_mr_no;
        $transport_detail->date = $transport_date;
        $transport_detail->cases = $transportDetails->transport_cases;
        $transport_detail->weight = $transportDetails->courier_weight;
        $transport_detail->freight = $transportDetails->courier_freight;
        $transport_detail->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = $user->employee_id;
        $logs->log_path = 'Sale Bill / Insert';
        $logs->log_subject = 'Sale Bill Details was inserted by ' . $user->username . '.';
        $logs->log_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->iuid = $iuid;
        $logs->save();

        return response()->json(['success' => 1, 'redirect' => '/account/sale-bill']);
    }

    public function copySaleBill($id)
    {
        $user = Session::get('user');
        $getsalebill = DB::table('sale_bills')
            ->where('sale_bill_id', $id)
            ->where('financial_year_id', $user->financial_year_id)
            ->where('is_deleted', 0)
            ->first();
        $getsalebillcombo = DB::table('comboids')
            ->select('inward_or_outward_via')
            ->where('sale_bill_id', $id)
            ->where('sale_bill_flag', 0)
            ->where('financial_year_id', $user->financial_year_id)
            ->where('is_deleted', 0)
            ->first();
        $getsalebillTransport = DB::table('sale_bill_transports')
            ->where('sale_bill_id', $id)
            ->where('financial_year_id', $user->financial_year_id)
            ->where('is_deleted', 0)
            ->first();

        $dateAdded = date('Y-m-d H:i:s');

        $companyName = $this->getCompanyNameWithId($getsalebill->company_id);

        if ($companyName->company_type_id != 0) {
            $companyTypeName = $this->getCompanyTypeName($companyName->company_type_id);
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }
        $companyPerson = $this->getCompanyDetails($getsalebill->company_id);
        if ($companyPerson) {
            $personName = $companyPerson->name;
        } else {
            $personName = '';
        }

        $increment_id_details = $this->getIncrementIdDetails($user->financial_year_id);
        if ($increment_id_details) {
            $iuid = $increment_id_details->iuid + 1;
            $data_iuid = array('iuid' => $iuid);
            $this->updateIncrementIds($data_iuid, $user->financial_year_id);
        } else {
            $iuid = 1;
            $data_iuid = array(
                'iuid' => $iuid,
                'financial_year_id' => $user->financial_year_id,
                'created_at' => $dateAdded,
                'updated_at' => $dateAdded
            );
            $this->insertIncrementIds($data_iuid);
        }
        $dataentry_iuid = array(
            'iuid' => $iuid,
            'financial_year_id' => $user->financial_year_id,
            'created_at' => $dateAdded,
            'updated_at' => $dateAdded
        );
        DB::table('iuids')->insert($dataentry_iuid);

        $combo_id = new Comboids;
        $combo_id->iuid                         = $iuid;
        $combo_id->general_ref_id               = $getsalebill->general_ref_id;
        $combo_id->inward_ref_via               = 0;
        $combo_id->new_or_old_inward_or_outward = 0;
        $combo_id->system_module_id             = 5;
        $combo_id->main_or_followup             = 0;
        $combo_id->generated_by                 = $user->employee_id;
        $combo_id->assigned_to                  = $user->employee_id;
        $combo_id->updated_by                   = $user->employee_id;
        $combo_id->company_id                   = $getsalebill->company_id;
        $combo_id->supplier_id                  = $getsalebill->supplier_id;
        $combo_id->company_type                 = $typeName;
        $combo_id->followup_via                 = 'Sale Bill';
        $combo_id->inward_or_outward_via        = $getsalebillcombo->inward_or_outward_via;
        $combo_id->selection_date               = null;
        $combo_id->from_name                    = $personName;
        $combo_id->subject                      = 'For ' . $companyName->name . ' Of Rs._____/-';
        $combo_id->default_category_id          = $getsalebill->sale_bill_for->id;
        $combo_id->main_category_id             = $getsalebill->product_main_category_id->id;
        $combo_id->agent_id                     = $getsalebill->agent_id;
        $combo_id->supplier_invoice_no          = null;
        $combo_id->total                        = 0;
        $combo_id->sale_bill_flag               = 1;
        $combo_id->financial_year_id            = $user->financial_year_id;
        $combo_id->required_followup            = 0;
        $combo_id->color_flag_id                = 2;
        $combo_id->attachments                  = null;
        $combo_id->ouid = 0;
        $combo_id->follow_as_inward_or_outward = 0;
        $combo_id->inward_or_outward_flag = 0;
        $combo_id->inward_or_outward_id = 0;
        $combo_id->sale_bill_id = 0;
        $combo_id->payment_id = 0;
        $combo_id->payment_followup_id = 0;
        $combo_id->goods_return_id = 0;
        $combo_id->good_return_followup_id = 0;
        $combo_id->commission_id = 0;
        $combo_id->commission_followup_id = 0;
        $combo_id->commission_invoice_id = 0;
        $combo_id->is_invoice = 0;
        $combo_id->sample_id = 0;
        $combo_id->inform_md = 0;
        $combo_id->from_number = 0;
        $combo_id->receiver_number = 0;
        $combo_id->from_email_id = 0;
        $combo_id->receiver_email_id = 0;
        $combo_id->outward_attachments = 0;
        $combo_id->outward_employe_id = 0;
        $combo_id->receipt_mode = 0;
        $combo_id->receipt_amount = 0;
        $combo_id->tds = 0;
        $combo_id->net_received_amount = 0;
        $combo_id->received_commission_amount = 0;
        $combo_id->action_date = 0;
        $combo_id->action_instruction = 0;
        $combo_id->next_follow_up_date = 0;
        $combo_id->next_follow_up_time = 0;
        $combo_id->being_late = 0;
        $combo_id->system_url = 0;
        $combo_id->enjay_uniqueid = 0;
        $combo_id->is_completed = 0;
        $combo_id->mark_as_draft = 0;
        $combo_id->product_qty = 0;
        $combo_id->fabric_meters = 0;
        $combo_id->sample_return_qty = 0;
        $combo_id->mobile_flag = 0;
        $combo_id->is_deleted = 0;
        $combo_id->save();

        $comboid = $combo_id->comboid;

        if ($increment_id_details) {
            $sale_bill_id = $increment_id_details->sale_bill_id + 1;
            $data_sale_bill_id = array('sale_bill_id' => $sale_bill_id, 'updated_at' => $dateAdded);
            $this->updateIncrementIds($data_sale_bill_id, $user->financial_year_id);
        } else {
            $sale_bill_id = 1;
            $data_sale_bill_id = array(
                'sale_bill_id' => $sale_bill_id,
                'financial_year_id' => $user->financial_year_id,
                'created_at' => $dateAdded,
                'updated_at' => $dateAdded
            );
            $this->insertIncrementIds($data_sale_bill_id);
        }
        $sale_bill = new SaleBill;
        $sale_bill->sale_bill_id             = $sale_bill_id;
        $sale_bill->sale_bill_for            = $getsalebill->sale_bill_for;
        $sale_bill->general_ref_id           = $getsalebill->general_ref_id;
        $sale_bill->sale_bill_via            = $getsalebill->sale_bill_via;
        $sale_bill->new_or_old_reference     = 0;
        $sale_bill->product_default_category_id = $getsalebill->product_main_category_id;
        $sale_bill->product_category_id      = '[]';
        $sale_bill->inward_id                = $getsalebill->inward_id;
        $sale_bill->company_id               = $getsalebill->company_id;
        $sale_bill->address                  = $getsalebill->address;
        $sale_bill->supplier_id              = $getsalebill->supplier_id;
        $sale_bill->agent_id                 = $getsalebill->agent_id;
        $sale_bill->financial_year_id        = $user->financial_year_id;
        $sale_bill->change_in_amount         = $getsalebill->change_in_amount;
        $sale_bill->sign_change              = $getsalebill->sign_change;
        $sale_bill->total                    = $getsalebill->total;
        $sale_bill->remark                   = $getsalebill->remark;
        $sale_bill->sale_bill_flag           = 1;
        $sale_bill->required_followup        = 0;
        $sale_bill->is_copied                = 1;
        $sale_bill->iuid                     = $iuid;
        $sale_bill->save();

        $dataentry_update = array('sale_bill_id' => $sale_bill_id, 'updated_at' => $dateAdded);
        $this->updateComboId($dataentry_update, $comboid);

        $transport_detail = new SaleBillTransport;
        $transport_detail->sale_bill_id = $sale_bill_id;
        $transport_detail->transport_id = $getsalebillTransport->transport_id;
        $transport_detail->financial_year_id = $user->financial_year_id;
        $transport_detail->station = $getsalebillTransport->station;
        $transport_detail->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = $user->employee_id;
        $logs->log_path = 'Sale Bill / Insert';
        $logs->log_subject = 'Sale Bill Details was inserted by ' . $user->username . '.';
        $logs->log_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->iuid = $iuid;
        $logs->save();

        return response()->json(['success' => 1, 'redirect' => '/account/sale-bill']);
    }

    public function getReferenceForSaleBillUpdate(Request $request)
    {
        $reference = DB::table('reference_ids')
            ->select('r.employee_id', 'r.reference_id', 'r.created_at', 'r.company_id', 'r.selection_date', 'r.type_of_inward', 'r.from_name', 'r.from_number', 'r.receiver_number', 'r.from_email_id', 'r.receiver_email_id', 'r.latter_by_id', 'r.courier_name', 'r.weight_of_parcel', 'r.courier_receipt_no', 'r.courier_received_time', 'r.delivery_by', 'c.company_name')
            // ->where('r.reference_id', $id)
            ->where('c.company_type', $request->sale_bill_via)
            ->where('r.financial_year_id', Session::get('user')->financial_year_id)
            ->where('r.inward_or_outward', $request->ref_via)
            ->whereRaw("(r.type_of_inward = 'Email' OR r.type_of_inward = 'Courier' OR r.type_of_inward = 'Hand')")
            ->whereRaw("(r.employee_id = " . Session::get('user')->employee_id . " or r.employee_id = 15)")
            ->where('r.is_deleted', 0)
            ->limit(1)
            ->first();
    }

    public function editSaleBill($id)
    {
        $page_title = 'Update Sale Bill';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('account.sale_bill.editSaleBill', compact('financialYear', 'page_title', 'employees'));
    }

    public function fetchSaleBill($id)
    {
        $user = Session::get('user');
        $sale_bill = DB::table('sale_bills')
            ->where('sale_bill_id', $id)
            ->where('financial_year_id', $user->financial_year_id)
            ->where('is_deleted', 0)
            ->first();
        $all = [];
        return response()->json($all);
    }

    // FOR BOTH PRODUCT & FABRIC
    public function listProductMainCategory($id)
    {
        $categories = DB::table('product_categories')
            ->select('id', 'name')
            ->where('product_default_category_id', $id)
            ->get();

        return response()->json($categories);
    }

    // FOR BOTH SUB PRODUCT & SUB FABRIC
    public function listProductSubCategory($product_id, $supplier_id)
    {
        $company_details = $this->getCompanyDetailsForLinkCompanies($supplier_id);
        $link_companies = $this->getLinkCompaniesDetails($supplier_id);
        if (empty($link_companies)) {
            $is_linked = $this->isCompanyLinkedWithOtherMainCompany($supplier_id);
            if (!empty($is_linked)) {
                $company_details = $this->getCompanyDetailsForLinkCompanies($is_linked->company_id);
                $link_companies = $this->getLinkCompaniesDetails($is_linked->company_id);
            }
        }
        $main_cmp_id = $company_details->id;
        array_push($link_companies, $main_cmp_id);
        $subCategory = $this->getProductSubCategoriesForUpdate($link_companies, $product_id);
        // array_push($subCategory, ['id' => 12, 'name' => 'Hi']);
        if (count($subCategory) > 0) {
            return response()->json($subCategory);
        } else {
            return null;
        }
    }

    public function getProductsFromSubCategory(Request $request)
    {
        $company_details = $this->getCompanyDetailsForLinkCompanies($request->supplier_id);
        $link_companies = $this->getLinkCompaniesDetails($request->supplier_id);
        if (empty($link_companies)) {
            $is_linked = $this->isCompanyLinkedWithOtherMainCompany($request->supplier_id);
            if (!empty($is_linked)) {
                $company_details = $this->getCompanyDetailsForLinkCompanies($is_linked->company_id);
                $link_companies = $this->getLinkCompaniesDetails($is_linked->company_id);
            }
        }
        $main_cmp_id = $company_details->id;

        $subCategory = explode(',', $request->subcategory);

        array_push($link_companies, $main_cmp_id);

        $productSubCategory = $this->getProductFromSubCategoriesForUpdate($subCategory, $link_companies, $request->maincategory);

        if (count($productSubCategory) > 0) {
            return response()->json($productSubCategory);
        } else {
            return null;
        }
    }

    public function getCompanyDetailsForLinkCompanies($id)
    {
        return DB::table('companies')->select('id')->where('id', $id)->limit(1)->first();
    }

    public function getLinkCompaniesDetails($id)
    {
        return DB::table('link_companies')->select('link_companies_id')->where('company_id', $id)->pluck('link_companies_id')->toArray();
    }

    public function isCompanyLinkedWithOtherMainCompany($id)
    {
        return DB::table('link_companies')->select('company_id')->where('link_companies_id', $id)->limit(1)->first();
    }

    public function getProductSubCategoriesForUpdate($link_companies, $id)
    {
        $where = null;
        foreach ($link_companies as $v) {
            $where .= "company_id @> '\"" . strval($v) . "\"' or ";
        }
        return DB::table('product_categories')
            ->select('id', 'product_default_category_id', 'name', 'main_category_id')
            ->where('main_category_id', $id)
            ->whereRaw('(' . rtrim($where, 'or ') . ')')
            ->orderBy('product_default_category_id', 'desc')
            ->get()
            ->toArray();
    }

    public function getProductFromSubCategoriesForUpdate($subCategory, $link_companies, $category_id)
    {
        $where = null;
        foreach ($subCategory as $v) {
            $where .= "sub_category @> '\"" . strval($v) . "\"' or ";
        }
        return DB::table('products')
            ->select('id', 'product_name as name')
            ->where('category', $category_id)
            ->whereIn('company', $link_companies)
            ->whereRaw('(' . rtrim($where, 'or ') . ')')
            ->orderBy('product_name', 'asc')
            ->get()
            ->toArray();
    }

    public function getCompanyFromInward($id = 0)
    {
        $companies = DB::table('companies as c')
            ->join('inwards as i', 'c.id', '=', 'i.company_id')
            ->select('c.id', 'c.company_name as name')
            ->where('inward_id', $id)
            ->get();

        return response()->json($companies);
    }

    public function getCustomersAndSuppliers()
    {
        $customers = DB::table('companies')
            ->select('id', 'company_name as name')
            ->where('company_type', 2)
            ->get();
        $suppliers = DB::table('companies')
            ->select('id', 'company_name as name')
            ->where('company_type', 3)
            ->get();

        return response()->json([$customers, $suppliers]);
    }

    public function getCustomerAddress($id)
    {
        $address = DB::table('company_addresses')
            ->select('id', 'address as name')
            ->where('company_id', $id)
            ->get();

        return response()->json($address);
    }

    public function checkSupplierInvoiceNo(Request $request)
    {
        if (empty($request->supplier_id) || empty($request->invoice_no)) {
            return;
        }
        if ($request->type == "insert") {
            $sameData = DB::table('sale_bills')
                ->select('sale_bill_id')
                ->where('is_deleted', 0)
                ->where('supplier_id', $request->supplier_id)
                ->where('supplier_invoice_no', $request->invoice_no)
                ->where('financial_year_id', Session::get('user')->financial_year_id)
                ->limit(1)
                ->first();
        } else {
            $sameData = DB::table('sale_bills')
                ->select('sale_bill_id')
                ->where('is_deleted', 0)
                ->where('supplier_id', $request->supplier_id)
                ->where('supplier_invoice_no', $request->invoice_no)
                ->where('financial_year_id', Session::get('user')->financial_year_id)
                ->where('sale_bill_id', '<>', $request->sale_bill_id)
                ->limit(1)
                ->first();
        }

        if ($sameData) {
            $link = "/account/sale-bill/view-sale-bill/" . $sameData->sale_bill_id;
            echo '<br><div class="text-danger">Supplier Invoice Number already exist!!! &nbsp;&nbsp;<a href="' . $link . '"> Visit Link</a></div>';
        } else {
            echo '<div class="text-success">SUCCESS</div>';
        }
    }

    public function getReferenceForSaleBill(Request $request)
    {
        $general_ref = DB::table('reference_ids as r')
            ->join('companies as c', 'r.company_id', '=', 'c.id')
            ->select('r.employee_id', 'r.reference_id', 'r.created_at')
            ->where('c.company_type', $request->sale_bill_via)
            ->where('r.financial_year_id', Session::get('user')->financial_year_id)
            ->where('r.type_of_inward', $request->ref_via)
            ->where('r.inward_or_outward', 1)
            ->whereRaw("(r.employee_id = " . Session::get('user')->employee_id . " or r.employee_id = 15)")
            ->orderBy('r.reference_id', 'desc')
            ->limit(4)
            ->get();
        $html = '';
        if (count($general_ref)) {
            $html .= '<div class="form-group row"><label class="col-sm-2 control-label"></label><div class="col-sm-8"><div class="table-responsive"><table class="table"><thead><tr><th></th><th>Ref. No</th><th>Generated By</th><th>Date</th><th>Time</th></tr></thead><tbody>';
            foreach ($general_ref as $row_general_ref) {
                if (Session::get('user')->employee_id == $row_general_ref->employee_id) {
                    $empName = "Me";
                } else {
                    $empName = "Rec.";
                }
                $html .= '<tr><td><div class="custom-control custom-radio"><input class="custom-control-input" type="radio" name="reference_id_sale_bill" value="' . $row_general_ref->reference_id . '" id="r-' . $row_general_ref->reference_id . '"><label class="custom-control-label" for="r-'.$row_general_ref->reference_id.'"></label></div></td><td>' . $row_general_ref->reference_id . '</td><td>' . $empName . '</td><td>' . date('Y-m-d', strtotime($row_general_ref->created_at)) . '</td><td>' . date('H:i A', strtotime($row_general_ref->created_at)) . '</td></tr>';
            }
            $html .= '<tr><td colspan="5"><div class="input-group"><input type="text" class="form-control" name="sale_bill_ref_search" id="sale_bill_ref_search" placeholder="Enter Reference Number"><span class="input-group-btn"><button type="button" class="btn btn-primary" id="sale_bill_ref_search_btn">Go</button></span></div></td></tr><tr id="sale_bill_ref_msg"></tr>';
            $html .= '</tbody></table></div></div><label class="col-sm-2 control-label"></label></div>';
        }
        return $html;
    }

    public function getOldReferenceForSaleBill(Request $request, $id)
    {
        $html = "";
        $reference = DB::table('reference_ids as r')
            ->join('companies as c', 'r.company_id', '=', 'c.id')
            ->select('r.employee_id', 'r.reference_id', 'r.created_at', 'r.company_id', 'r.selection_date', 'r.type_of_inward', 'r.from_name', 'r.from_number', 'r.receiver_number', 'r.from_email_id', 'r.receiver_email_id', 'r.latter_by_id', 'r.courier_name', 'r.weight_of_parcel', 'r.courier_receipt_no', 'r.courier_received_time', 'r.delivery_by', 'c.company_name')
            ->where('r.reference_id', $id)
            ->where('c.company_type', $request->sale_bill_via)
            ->where('r.financial_year_id', Session::get('user')->financial_year_id)
            ->where('r.inward_or_outward', 1)
            ->whereRaw("(r.type_of_inward = 'Email' OR r.type_of_inward = 'Courier' OR r.type_of_inward = 'Hand')")
            ->whereRaw("(r.employee_id = " . Session::get('user')->employee_id . " or r.employee_id = 15)")
            ->where('r.is_deleted', 0)
            ->limit(1)
            ->first();

        if ($reference) {
            if ($reference->company_id != 0) {
                if (Session::get('user')->employee_id == $reference->employee_id) {
                    $empName = "Own";
                } else {
                    $empName = "Rec.";
                }
                $html .= "<input type='hidden' id='hidden_sale_bill_date' value='" . date('Y-m-d', strtotime($reference->selection_date)) . "'><input type='hidden' id='hidden_reference_via' value='" . $reference->type_of_inward . "'><input type='hidden' id='hidden_from_name' value='" . $reference->from_name . "'><input type='hidden' id='hidden_from_number' value='" . $reference->from_number . "'><input type='hidden' id='hidden_receiver_number' value='" . $reference->receiver_number . "'><input type='hidden' id='hidden_from_email_id' value='" . $reference->from_email_id . "'><input type='hidden' id='hidden_receiver_email_id' value='" . $reference->receiver_email_id . "'><input type='hidden' id='hidden_latter_by_id' value='" . $reference->latter_by_id . "'><input type='hidden' name='hidden_courier_name' id='hidden_courier_name' value='" . $reference->courier_name . "'><input type='hidden' id='hidden_weight_of_parcel' value='" . $reference->weight_of_parcel . "'><input type='hidden' id='hidden_courier_receipt_no' value='" . $reference->courier_receipt_no . "'><input type='hidden' id='hidden_courier_received_time' value='" . date('Y-m-d', strtotime($reference->courier_received_time)) . "'><input type='hidden' id='hidden_delivery_by' value='" . $reference->delivery_by . "'><input type='hidden' name='hidden_cmp_id' id='hidden_cmp_id' value='" . $reference->company_id . "'><input type='hidden' name='hidden_cmp_name' id='hidden_cmp_name' value='" . $reference->company_name . "'><input type='hidden' id='hidden_reference_id_input' name='hidden_reference_id_input' value='" . $reference->reference_id . "'><input type='hidden' id='hidden_ref_emp_name' name='hidden_ref_emp_name' value='" . $empName . "'><input type='hidden' id='hidden_ref_date_added' name='hidden_ref_date_added' value='" . date('Y-m-d', strtotime($reference->created_at)) . "'><input type='hidden' id='hidden_ref_time_added' name='hidden_ref_time_added' value='" . date('h:i A', strtotime($reference->created_at)) . "'>";
            }
        }
        return $html;
    }

    public function getSubProductFromProduct(Request $request)
    {
        $subProducts = DB::table('products_images')
            ->select('id', 'supplier_code as name', 'price')
            ->where('product_id', $request->product_id)
            ->get()
            ->toArray();
        $productRate = DB::table('product_details')
            ->select('catalogue_price')
            ->where('product_id', $request->product_id)
            ->first();
        $subProducts[] = ['id' => 'full', 'name' => 'Full Catalogue', 'price' => $productRate->catalogue_price];
        return response()->json($subProducts);
    }

    public function listTransports(Request $request)
    {
        $transportDetails = TransportDetails::select('id', 'name')->where('is_delete', '0')->get();
        return response()->json($transportDetails);
    }

    public function getStations($id)
    {
        $cities = DB::table('cities')
            ->select('id', 'name')
            ->get();

        $city_s = DB::table('companies')
            ->select('id', 'company_city as name')
            ->where('id', $id)
            ->first();

        return response()->json([$cities, $city_s]);
    }

    public function getCompanyNameWithId($id)
    {
        return DB::table('companies')->select('company_name', 'company_type')->where('id', $id)->first();
    }

    public function getCompanyTypeName($id)
    {
        return DB::table('company_types')->select('id', 'name')->where('id', $id)->first();
    }

    public function getCompanyDetails($id)
    {
        return DB::table('company_contact_details')->select('contact_person_name', 'contact_person_mobile', 'contact_person_email')->where('company_id', $id)->orderBy('id', 'desc')->first();
    }


    public function getIncrementIdDetails($id)
    {
        return DB::table('increment_ids')->where('financial_year_id', $id)->first();
    }

    public function updateIncrementIds($data, $id)
    {
        return DB::table('increment_ids')->where('financial_year_id', $id)->update($data);
    }

    public function insertIncrementIds($data)
    {
        return DB::table('increment_ids')->insert($data);
    }

    public function getReferenceDetails($sale_bill_via, $ref_via)
    {
        return DB::table('reference_ids as r')
            ->join('companies as c', 'r.company_id', '=', 'c.id')
            ->select('r.employee_id', 'r.reference_id', 'r.created_at')
            ->where('c.company_type', $sale_bill_via)
            ->where('r.financial_year_id', Session::get('user')->financial_year_id)
            ->where('r.type_of_inward', $ref_via)
            ->where('r.inward_or_outward', 1)
            ->whereRaw("(r.employee_id = " . Session::get('user')->employee_id . " or r.employee_id = 15)")
            ->orderBy('r.reference_id', 'desc')
            ->limit(4)
            ->get();
    }

    public function updateComboId($data, $id)
    {
        return DB::table('comboids')->where('financial_year_id', Session::get('user')->financial_year_id)->where('comboid', $id)->update($data);
    }

}
