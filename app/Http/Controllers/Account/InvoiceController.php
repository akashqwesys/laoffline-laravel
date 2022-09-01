<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Commission\CommissionInvoice;
use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\InvoicePaymentDetails;
use App\Models\Comboids\Comboids;
use App\Http\Controllers\Account\SaleBillController;
use Illuminate\Support\Facades\Session;
use DB;

class InvoiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $page_title = 'Commission Invoice';
        $financialYear = FinancialYear::get();
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
        $logs->log_path = 'Account / Commission / Invoice';
        $logs->log_subject = 'Invoice view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('account.commission.invoice.invoice', compact('financialYear', 'page_title', 'employees'));
    }

    public function storeInvoiceSearch(Request $request) {
        $request->session()->put('invoice_search', $request->all());
    }

    public function getInvoiceSearch(Request $request) {
        $searchdata = $request->session()->get('invoice_search');
        return $searchdata;
    }

    public function listInvoice(Request $request)
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

        $totalRecords = CommissionInvoice::select('id')
            ->where('financial_year_id', $user->financial_year_id)
            ->where('is_deleted', 0)
            ->count();
        $totalRecordswithFilter = DB::table('commission_invoices as ci')
            ->selectRaw('count(id) as allcount')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'ci.customer_id', '=', 'cc.id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'ci.supplier_id', '=', 'cs.id')
            ->leftJoin('agents as a', 'ci.agent_id', '=', 'a.id')
            ->join('employees as e', 'ci.generated_by', '=', 'e.id')
            ->where('ci.financial_year_id', $user->financial_year_id);
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('ci.bill_no', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('ci.bill_date', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[3]['search']['value'] . '%')->whereIn('company_type', [2, 3])->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->where(function($q) use($cc_id) {
                $q->whereIn('ci.customer_id', $cc_id)
                ->orWhereIn('ci.supplier_id', $cc_id);
            });
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $ag_id = DB::table('agents')->select('id')->where('name', 'ilike', '%' . $columnName_arr[4]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('ci.agent_id', $ag_id);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('ci.final_amount', $columnName_arr[5]['search']['value']);
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            if (in_array($columnName_arr[6]['search']['value'], ['none', 'non', 'None'])) {
                $totalRecordswithFilter = $totalRecordswithFilter->where('ci.commission_status', '=', 0);
            } else if (in_array($columnName_arr[6]['search']['value'], ['complete', 'comp'])) {
                $totalRecordswithFilter = $totalRecordswithFilter->where('ci.commission_status', '=', 1);
            } else if (in_array($columnName_arr[6]['search']['value'], ['pending', 'pend'])) {
                $totalRecordswithFilter = $totalRecordswithFilter->where('ci.commission_status', '=', 2);
            }
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            if ($columnName_arr[7]['search']['value'] == 'pending') {
                $totalRecordswithFilter = $totalRecordswithFilter->where('ci.done_outward', '=', 0);
            } else if ($columnName_arr[7]['search']['value'] == 'complete') {
                $totalRecordswithFilter = $totalRecordswithFilter->where('ci.done_outward', '=', 1);
            }
        }
        if (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereRaw('(DATE_PART(\'day\', now()::timestamp - bill_date::timestamp)) >= ' . $columnName_arr[8]['search']['value']);
        }
        $totalRecordswithFilter = $totalRecordswithFilter->where('ci.is_deleted', 0)->count();

        $invoice = DB::table('commission_invoices as ci')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'ci.customer_id', '=', 'cc.id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'ci.supplier_id', '=', 'cs.id')
            ->leftJoin('agents as a', 'ci.agent_id', '=', 'a.id')
            ->join('employees as e', 'ci.generated_by', '=', 'e.id')
            ->select('ci.id', 'ci.bill_no', 'ci.bill_date', 'ci.final_amount', 'ci.commission_status', 'ci.created_at', 'ci.customer_id', 'ci.supplier_id', 'ci.generated_by', 'ci.financial_year_id', 'ci.done_outward', 'ci.total_payment_received_amount', 'cc.company_name as customer_name', 'cs.company_name as supplier_name',  DB::raw('(SELECT "outward_id" FROM "outward_sale_bills" WHERE "commission_invoice_id" = "ci"."id" ORDER BY "id" DESC LIMIT 1) as outward_id'), 'a.name as agent_name', DB::raw('(DATE_PART(\'day\', now()::timestamp - bill_date::timestamp)) as due_days'), 'e.firstname', DB::raw('(case when ci.commission_status <> 0 then (select c.commission_id from commissions as c inner join commission_details as cd on c.id = cd.c_increment_id where c.financial_year_id = ci.financial_year_id and cd.commission_invoice_id = ci.id limit 1) else 0 end) as commission_id'))
            ->where('ci.is_deleted', 0)
            ->where('ci.financial_year_id', $user->financial_year_id);
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $invoice = $invoice->where('ci.bill_no', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $invoice = $invoice->whereDate('ci.bill_date', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[3]['search']['value'] . '%')->whereIn('company_type', [2, 3])->pluck('id')->toArray();
            $invoice = $invoice->where(function($q) use($cc_id) {
                $q->whereIn('ci.customer_id', $cc_id)
                ->orWhereIn('ci.supplier_id', $cc_id);
            });
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $ag_id = DB::table('agents')->select('id')->where('name', 'ilike', '%' . $columnName_arr[4]['search']['value'] . '%')->pluck('id')->toArray();
            $invoice = $invoice->whereIn('ci.agent_id', $ag_id);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $invoice = $invoice->where('ci.final_amount', $columnName_arr[5]['search']['value']);
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            if (in_array($columnName_arr[6]['search']['value'], ['none', 'non', 'None'])) {
                $invoice = $invoice->where('ci.commission_status', '=', 0);
            } else if (in_array($columnName_arr[6]['search']['value'], ['complete', 'comp'])) {
                $invoice = $invoice->where('ci.commission_status', '=', 1);
            } else if (in_array($columnName_arr[6]['search']['value'], ['pending', 'pend'])) {
                $invoice = $invoice->where('ci.commission_status', '=', 2);
            }
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            if ($columnName_arr[7]['search']['value'] == 'pending') {
                $invoice = $invoice->where('ci.done_outward', '=', 0);
            } else if ($columnName_arr[7]['search']['value'] == 'complete') {
                $invoice = $invoice->where('ci.done_outward', '=', 1);
            }
        }
        if (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value'])) {
            $invoice = $invoice->whereRaw('(DATE_PART(\'day\', now()::timestamp - bill_date::timestamp)) >= ' . $columnName_arr[8]['search']['value']);
        }

        $invoice = $invoice->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $customer_ids = collect($invoice)->pluck('company_id')->toArray();
        $supplier_ids = collect($invoice)->pluck('supplier_id')->toArray();
        $company_ids = array_unique(array_merge($customer_ids, $supplier_ids));
        $companies = DB::table('companies')
            ->select('id')
            ->where('is_delete', 0)
            ->whereRaw("(company_name is not null or company_name <> '') and company_type <> 0 and company_country <> 0 and (company_city is not null or company_city <> '') and company_landline @> '0'")
            ->whereIn('id', $company_ids)
            ->get();
        $company_addresses = DB::table('company_addresses')
            ->select('id', 'company_id')
            ->whereRaw("(address is not null or address <> '')")
            ->whereIn('company_id', $company_ids)
            ->get();
        $company_owners = DB::table('company_address_owners as cao')
            ->join('company_addresses as ca', 'cao.company_address_id', '=', 'ca.id')
            ->select('cao.id', 'ca.company_id')
            ->whereRaw("(cao.name is not null or cao.name <> '') and (cao.mobile is not null or cao.mobile <> '') and cao.designation @> '0'")
            ->whereIn('ca.company_id', $company_ids)
            ->get();
        $d_total = 0;
        foreach ($invoice as $s) {
            $created_at = date('d-m-Y H:i A', strtotime($s->created_at));
            $bill_date = date('d-m-Y', strtotime($s->bill_date));

            if ($s->done_outward == 0) {
                $outward_status = '<em class="icon ni ni-cross" title="No"></em>';
            } else {
                $outward_status = '<a href="/register/view-outward/' . ($s->outward_id ?? 0) . '" class="" ><em class="icon ni ni-check-thick" title="Yes"></em></a> ';
            }

            $action = '<a href="/account/commission/invoice/view-invoice/' . $s->id . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="View" target="_blank"><em class="icon ni ni-eye"></em></a> <a href="/account/commission/invoice/edit-invoice/' . $s->id . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update" target="_blank"><em class="icon ni ni-edit-alt"></em></a>';
            $delete_flag = 0;
            if ($s->commission_status == 0) {
                $delete_flag = 1;
                $commission_status = '<em class="icon ni ni-cross" title="None"></em>';
            } else if ($s->commission_status == 1) {
                $commission_status = '<a href="/commission/view-commission/' . ($s->commission_id ?? 0) . '" class="" ><em class="icon ni ni-check-thick" title="Complete"></em></a>';
            } else {
                $commission_status = '<em class="icon ni ni-more-h" title="Pending"></em>';
            }
            if ($delete_flag == 1) {
                $action .= ' <a href="javascript:void(0)" data-id="' . $s->id . '" class="btn btn-trigger btn-icon delete-invoice" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a> ';
            }

            if ($s->supplier_id == 0) {
                $company = collect($companies)->where('id', $s->customer_id)->toArray();
                $address = collect($company_addresses)->where('company_id', $s->customer_id)->toArray();
                $company_owner = collect($company_owners)->where('company_id', $s->customer_id)->toArray();
                if ((count($company) == 0 || count($company_owner) == 0 || count($address) == 0)) {
                    $customer_color = '';
                } else {
                    $customer_color = ' text-danger ';
                }
                $company_row = '<a href="#" class="view-details ' . $customer_color . '" data-id="' . $s->customer_id . '">' . $s->customer_name . '</a>';
            } else {
                $company_s = collect($companies)->where('id', $s->supplier_id)->toArray();
                $address_s = collect($company_addresses)->where('company_id', $s->supplier_id)->toArray();
                $company_owner_s = collect($company_owners)->where('company_id', $s->supplier_id)->toArray();

                if ((count($company_s) == 0 || count($company_owner_s) == 0 || count($address_s) == 0)) {
                    $supplier_color = '';
                } else {
                    $supplier_color = ' text-danger ';
                }
                $company_row = '<a href="#" class="view-details ' . $supplier_color . '" data-id="' . $s->supplier_id . '">' . $s->supplier_name . '</a>';
            }

            $data_arr[] = array(
                'bill_no' => $s->bill_no,
                "bill_date" => $bill_date,
                "created_at" => $created_at,
                "company" => $company_row,
                'agent_id' => $s->agent_name,
                // 'total_payment_received_amount' => $s->total_payment_received_amount,
                'final_amount' => $s->final_amount,
                'commission_status' => $commission_status,
                "outward_status" => $outward_status,
                'due_days' => floor((time() - strtotime($s->bill_date)) / (60 * 60 * 24)),
                'generated_by' => $s->firstname,
                "action" => $action
            );
            // $d_total += $s->total_payment_received_amount;
            $d_total += $s->final_amount;
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
            "extra_data" => [
                'display_total' => $d_total
            ]
        );

        echo json_encode($response);
        exit;
    }

    public function createInvoice()
    {
        $page_title = 'Create Invoice';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();
        $employees['editedId'] = 0;
        $employees['scope'] = "create";
        return view('account.commission.invoice.createInvoice', compact('financialYear', 'page_title', 'employees'));
    }

    public function saveInvoice(Request $request)
    {
        $user = Session::get('user');
        $new_req = $request->all();

        $companyName = $new_req['company'];
        $sale_bill_cont = new SaleBillController;
        if ($companyName['company_type'] != 0) {
            $companyTypeName = $sale_bill_cont->getCompanyTypeName($companyName['company_type']);
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }
        $companyPerson = $sale_bill_cont->getCompanyDetails($companyName['id']);
        if ($companyPerson) {
            $personName = $companyPerson->contact_person_name;
        } else {
            $personName = '';
        }

        $combo_id = new Comboids;
        $combo_id->comboid            = (getLastID('comboids', 'comboid') + 1);
        $combo_id->system_module_id   = '19';
        $combo_id->generated_by       = $user->employee_id;
        $combo_id->supplier_id        = $companyName['id'];
        $combo_id->company_type       = $typeName;
        $combo_id->from_name          = $personName;
        $combo_id->subject            = 'Commission Invoice For : ' . $companyName['company_name'];
        $combo_id->financial_year_id  = $user->financial_year_id;

        $combo_id->iuid                         = 0;
        $combo_id->general_ref_id               = 0;
        $combo_id->inward_ref_via               = 0;
        $combo_id->new_or_old_inward_or_outward = 0;
        $combo_id->assigned_to                  = 0;
        $combo_id->updated_by                   = $user->employee_id;
        $combo_id->company_id                   = 0;
        $combo_id->followup_via                 = 'Commission Invoice';
        $combo_id->inward_or_outward_via        = 0;
        $combo_id->selection_date               = null;
        $combo_id->default_category_id          = 0;
        $combo_id->main_category_id             = 0;
        $combo_id->agent_id                     = 0;
        $combo_id->supplier_invoice_no          = 0;
        $combo_id->total                        = 0;
        $combo_id->sale_bill_flag               = 0;
        $combo_id->color_flag_id                = 0;
        $combo_id->attachments                  = null;
        $combo_id->ouid = 0;
        $combo_id->follow_as_inward_or_outward = 0;
        $combo_id->inward_or_outward_flag = 0;
        $combo_id->inward_or_outward_id = 0;
        $combo_id->sale_bill_id = 0;
        $combo_id->payment_id = 0;
        $combo_id->goods_return_id = 0;
        $combo_id->commission_id = 0;
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
        $combo_id->action_date = null;
        $combo_id->action_instruction = 0;
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

        if ($typeName == "Supplier") {
            $combo_id->supplier_id = $companyName['id'];
        } else {
            $combo_id->company_id = $companyName['id'];
        }
        $combo_id->save();

        $commi_total_amount  = $new_req['comm_total_amount'];
        $rounded_off         = $new_req['rounded_off'];
        $final_amount        = $new_req['final_amount'];
        $invoice_others      = $new_req['invoice_others'];
        $payment_comm        = $new_req['payment_comm'];
        $service_tax_flag    = 0;
        $tds_flag            = 0;
        $cgst                = 0;
        $cgst_amt            = 0;
        $sgst                = 0;
        $sgst_amt            = 0;
        $igst                = 0;
        $igst_amt            = 0;
        $tds_amt             = 0;
        if (isset($new_req['comm_invoice_gst'])) {
            $service_tax_flag = 1;
            if (isset($new_req['cgst_amount'])) {    // for gujarat
                $cgst     = $new_req['cgst'];
                $cgst_amt = $new_req['cgst_amount'];
                $sgst     = $new_req['sgst'];
                $sgst_amt = $new_req['sgst_amount'];
            }
            if (isset($new_req['igst_amount'])) {    // for other state
                $igst     = $new_req['igst'];
                $igst_amt = $new_req['igst_amount'];
            }
        }
        if (isset($new_req['comm_invoice_tds']) && $new_req['comm_invoice_tds'] == 1) {
            $tds_flag = 1;
            $tds_amt  = $new_req['tds_amount'];
        }
        $invoice = new CommissionInvoice;
        $invoice->id                 = (getLastID('commission_invoices', 'id') + 1);
        $invoice->financial_year_id  = $user->financial_year_id;
        $invoice->generated_by       = $user->employee_id;
        $invoice->bill_no            = $new_req['full_bill_no'];
        $invoice->bill_period_to     = date('Y-m-d', strtotime($new_req['bill_period_to']));
        $invoice->bill_period_from   = date('Y-m-d', strtotime($new_req['bill_period_from']));
        $invoice->bill_date          = date('Y-m-d', strtotime($new_req['invoice_bill_date']));
        $invoice->service_tax_amount = 0;
        $invoice->service_tax        = 0;
        $invoice->commission_amount  = $commi_total_amount;
        $invoice->service_tax_flag   = $service_tax_flag;
        $invoice->tds_flag           = (int)$tds_flag;
        $invoice->with_without_gst   = $new_req['with_without_gst'];
        $invoice->cgst               = $cgst;
        $invoice->cgst_amount        = $cgst_amt;
        $invoice->sgst               = $sgst;
        $invoice->sgst_amount        = $sgst_amt;
        $invoice->igst               = $igst;
        $invoice->igst_amount        = $igst_amt;
        $invoice->commission_percent = $payment_comm;
        $invoice->other_amount       = $invoice_others;
        $invoice->rounded_off        = $rounded_off;
        $invoice->tds_amount         = (int)$tds_amt;
        $invoice->final_amount       = $final_amount;
        $invoice->agent_id           = $new_req['courier_agent']['id'];

        if ($typeName == "Supplier") {
            $invoice->supplier_id  = $companyName['id'];
        } else {
            $invoice->customer_id  = $companyName['id'];
        }
        $invoice->save();
        $commission_invoice_id = $invoice->id;

        $combo_id->commission_invoice_id = $commission_invoice_id;
        $combo_id->save();

        if ($typeName == "Supplier") {
            $flag = 1;
            $invoice_company_id = "receipt_from";
        } else {
            $flag = 2;
            $invoice_company_id = "supplier_id";
        }

        $dataentry_payment = [];
        $ipd_id = (getLastID('invoice_payment_details', 'id') + 1);
        foreach ($new_req['payments'] as $p) {
            $dataentry_payment[] = array(
                'id'                    => $ipd_id++,
                'commission_invoice_id' => $commission_invoice_id,
                'payment_id'            => $p['payment_id'],
                'financial_year_id'     => $p['financial_year_id'],
                'payment_date'          => date('Y-m-d', strtotime($p['date'])),
                'company_id'            => $p[$invoice_company_id],
                'received_amount'       => $p['receipt_amount'],
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
                'flag'                  => $flag,
            );
        }
        DB::table('invoice_payment_details')->insert($dataentry_payment);

        $invoice->total_payment_received_amount = $new_req['total_amount'];
        $invoice->save();

        $request->session()->forget(['commission_payment_id', 'commission_supplier']);
        return response()->json(['success' => 1]);
    }

    public function listCompany()
    {
        $company = DB::table('companies')->select('id', 'company_name as name', 'company_type')->where('is_delete', 0)->get();
        return response()->json($company);
    }

    public function getPayments(Request $request)
    {
        $sale_bill_cont = new SaleBillController;
        $company_details = $sale_bill_cont->getCompanyDetailsForLinkCompanies($request->company);
        $link_companies = $sale_bill_cont->getLinkCompaniesDetails($request->company);
        if (empty($link_companies)) {
            $is_linked = $sale_bill_cont->isCompanyLinkedWithOtherMainCompany($request->company);
            if (!empty($is_linked)) {
                $company_details = $sale_bill_cont->getCompanyDetailsForLinkCompanies($is_linked->company_id);
                $link_companies = $sale_bill_cont->getLinkCompaniesDetails($is_linked->company_id);
            }
        }
        $flag = 0;
        if ($request->type == 3) {
            $flag = 1; // for supplier
        } else if ($request->type == 2) {
            $flag = 2; // for customer
        }
        $payment = DB::table('payments as p')
            ->join('company_commissions as ccm', function ($j) use($flag) {
                $j->on('p.receipt_from', '=', 'ccm.customer_id')
                ->on('p.supplier_id', '=', 'ccm.supplier_id')
                ->where('ccm.flag', '=', $flag);
            })
            ->join(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'p.receipt_from', '=', 'cc.id')
            ->join(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'p.supplier_id', '=', 'cs.id')
            ->join('financial_year as fy', 'p.financial_year_id', '=', 'fy.id')
            ->select('p.id', 'p.payment_id', 'p.financial_year_id', 'p.date', 'p.receipt_amount', 'p.receipt_from', 'p.supplier_id', 'fy.name', 'cc.company_name as customer_name', 'cs.company_name as supplier_name');
        if ($company_details) {
            $main_cmp_id = $company_details->id;
            $add_in = null;
            if (count($link_companies)) {
                $add_in = ' or "p"."supplier_id" in (' . implode(',', $link_companies) . ')';
            }
            $payment = $payment->whereRaw('("p"."supplier_id" = ' . $main_cmp_id . ' or "p"."customer_id" = ' . $main_cmp_id . ' ' . $add_in . ' )');
        }
        $payment = $payment->where('p.is_deleted', 0)
            ->where('p.reciept_mode', 'cheque')
            ->where('p.right_of_amount', 0)
            ->where('p.receipt_amount', '<>', 0)
            ->orderBy('p.date', 'asc')
            ->get();
        $data_arr = [];
        if (count($payment)) {
            $payment_ids = collect($payment)->pluck('payment_id')->unique()->toArray();
            $finan_ids = collect($payment)->pluck('financial_year_id')->unique()->toArray();

            $commission = DB::table('commissions as c')
                ->join('commission_details as cd', 'c.id', '=', 'cd.c_increment_id')
                ->select('c.id', 'cd.payment_id', 'cd.financial_year_id')
                ->whereIn('cd.payment_id', $payment_ids)
                ->whereIn('cd.financial_year_id', $finan_ids)
                ->where('cd.status', 1)
                ->get();

            $invoice_payment = DB::table('invoice_payment_details')
                ->select('id', 'payment_id', 'financial_year_id', 'flag')
                ->whereIn('payment_id', $payment_ids)
                ->whereIn('financial_year_id', $finan_ids);
            if ($flag != 0) {
                $invoice_payment = $invoice_payment->where('flag', $flag);
            }
            $invoice_payment = $invoice_payment->get();

            $data_arr = [];
            foreach ($payment as $p) {
                $commission_exist = collect($commission)
                    ->where('payment_id', $p->payment_id)
                    ->where('financial_year_id', $p->financial_year_id)
                    ->toArray();
                if (count($commission_exist) == 0) {
                    $invoice_exist = collect($invoice_payment)
                        ->where('payment_id', $p->payment_id)
                        ->where('financial_year_id', $p->financial_year_id)
                        ->where('flag', $flag)
                        ->toArray();
                    $company_commission = DB::table('company_commissions')
                        ->select('id')
                        ->where('customer_id', $p->receipt_from)
                        ->where('supplier_id', $p->supplier_id)
                        ->where('flag', $flag)
                        ->limit(1)
                        ->first();
                    if (count($invoice_exist) == 0 && !empty($company_commission)) {
                        $date = date('d-m-Y', strtotime($p->date));
                        $data_arr[] = array(
                            'payment_id' => $p->payment_id,
                            "financial_year" => $p->name,
                            "financial_year_id" => $p->financial_year_id,
                            "date" => $date,
                            "supplier" => $p->supplier_name,
                            "customer" => $p->customer_name,
                            'amount' => $p->receipt_amount,
                            'due_days' => floor((time() - strtotime($p->date)) / (60 * 60 * 24))
                        );
                    }
                }
            }
        }
        echo json_encode($data_arr);
        exit;
    }

    public function updatePaymentRemark(Request $request)
    {
        $payments = $request->payments;
        $right_of_remark = $request->right_of_comment;
        foreach ($payments as $row) {
            $detail            = explode("-", $row);
            $payment_id        = $detail[0];
            $financial_year_id = $detail[1];
            $final_amount      = $detail[2];

            $percentage = DB::table('payments as p')
                ->join('company_commissions as cc', 'p.supplier_id', '=', 'cc.supplier_id')
                ->select('cc.commission_percentage')
                ->where('p.payment_id', $payment_id)
                ->where('p.financial_year_id', $financial_year_id)
                ->pluck('commission_percentage')
                ->first();
            if ($percentage) {
                $right_of_amount = round((($final_amount * $percentage) / 100), 2);
            } else {
                $right_of_amount = round((($final_amount * 2) / 100), 2);
            }

            DB::table('payments')
            ->where('payment_id', $payment_id)
            ->where('financial_year_id', $financial_year_id)
            ->update([
                "right_of_amount"   => $right_of_amount,
                "right_of_remark"   => $right_of_remark,
                "payment_ok_or_not" => 1,
                "updated_at"        => date('Y-m-d H:i:s')
            ]);
        }
        return response()->json(['success' => 1]);
    }

    public function setSessionForPaymentDetails(Request $request)
    {
        $request->session()->put('commission_supplier', $request->supplier);
        $request->session()->put('commission_payment_id', $request->payment_ids);

        return response()->json(['success' => 1]);
    }

    public function invoiceView(Request $request)
    {
        if (!$request->session()->exists('commission_supplier')) {
            return redirect('/account/commission/invoice');
        }
        $page_title = 'Generate Commission Invoice';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        return view('account.commission.invoice.generateInvoice', compact('financialYear', 'page_title', 'employees'));
    }

    public function getData()
    {
        $user = Session::get('user');
        $agents = DB::table('agents')->select('id', 'name', 'gst_no', 'pan_no', 'inv_prefix')->where('is_delete', '0')->get();

        $company = DB::table('companies as c')
            ->leftJoin('company_packaging_details as cpd', 'c.id', '=', 'cpd.company_id')
            ->leftJoin('company_addresses as ca', 'c.id', '=', 'ca.company_id')
            ->select('c.id', 'c.company_name', 'c.company_type', 'cpd.gst_no', 'c.company_state', 'ca.address')
            ->where('c.id', session()->get('commission_supplier'))
            ->where('c.is_delete', 0)
            ->first();

        $bill = DB::table('commission_invoices')
            ->select('id', 'bill_no')
            ->where('financial_year_id', $user->financial_year_id)
            ->where('agent_id', 1)
            ->where('service_tax_flag', 1)
            ->orderBy('id', 'desc')
            ->first();

        $fid_prefix = DB::table('financial_year')->select('inv_prefix')->where('id', $user->financial_year_id)->pluck('inv_prefix')->first();

        if (empty($bill)) {
            $billNo = "0001";
        } else {
            $billNo = explode('-', $bill->bill_no);
            if (empty($billNo[2])) {
                $billNo = "0001";
            } else {
                $billNo = $billNo[2] + 1;
                $billNo = sprintf('%04d', $billNo);
            }
        }

        $paymentIds = json_decode(session()->get('commission_payment_id'));
        $p_ids = $fy_ids = [];
        $total_amount = 0;
        foreach ($paymentIds as $v) {
            $a = explode('-', $v);
            $p_ids[] = $a[0];
            $fy_ids[] = $a[1];
            $total_amount += floatval($a[2]);
        }

        $without_gst_amt = $with_gst_amt = 0;
        $payments = DB::table('payments as p')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'p.receipt_from', '=', 'cc.id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'p.supplier_id', '=', 'cs.id')
            ->select('p.payment_id', 'p.financial_year_id', DB::raw("TO_CHAR(p.date, 'dd-mm-yyyy') as date"), 'p.receipt_amount', 'p.receipt_from', 'p.supplier_id', 'cc.company_name as customer_name', 'cs.company_name as supplier_name', DB::raw("TO_CHAR(p.date, 'dd-mm-yyyy') as date"), 'p.id as p_id')
            ->whereIn('p.financial_year_id', $fy_ids)
            ->whereIn('p.payment_id', $p_ids)
            ->get();
        foreach ($payments as $v) {
            $with_gst_amt += $v->receipt_amount;
        }
        $p_ids = collect($payments)->pluck('p_id')->toArray();

        $pay_n_pay_det = DB::table('payments as p')
            ->join('payment_details as pd', 'p.id', '=', 'pd.p_increment_id')
            ->select('pd.adjust_amount', DB::raw('(SELECT (100 + cgst + sgst + igst) as gst from sale_bill_items WHERE financial_year_id = p.financial_year_id AND sale_bill_id = pd.sr_no AND is_deleted = 0 LIMIT 1) as gst'))
            ->whereIn('p.id', $p_ids)
            ->where('p.is_deleted', 0)
            ->where('pd.is_deleted', 0)
            ->get();
        foreach ($pay_n_pay_det as $v) {
            $without_gst_amt += round((($v->adjust_amount * 100) / ($v->gst == 0 ? 1 : $v->gst)), 2);
        }
        /* $payments = DB::table('payments as p')
            ->leftJoin('payment_details as pd', 'p.id', '=', 'pd.p_increment_id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'p.receipt_from', '=', 'cc.id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'p.supplier_id', '=', 'cs.id')
            ->leftJoin('financial_year as fy', 'p.financial_year_id', '=', 'fy.id')
            ->select('p.id', 'p.payment_id', 'p.financial_year_id', DB::raw("TO_CHAR(p.date, 'dd-mm-yyyy') as date"), 'p.receipt_amount', 'p.receipt_from', 'p.supplier_id', 'fy.name', 'cc.company_name as customer_name', 'cs.company_name as supplier_name', 'pd.adjust_amount', DB::raw('(SELECT (100 + cgst + sgst + igst) as gst from sale_bill_items WHERE financial_year_id = p.financial_year_id AND sale_bill_id = pd.sr_no AND is_deleted = 0 LIMIT 1) as gst' ) )
            ->whereIn('p.payment_id', $p_ids)
            ->where('p.is_deleted', 0)
            ->where('pd.is_deleted', 0)
            ->get();
        $without_gst_amt = 0;
        foreach ($payments as $v) {
            $without_gst = round((($v->adjust_amount * 100) / $v->gst), 2);
            $without_gst_amt += $without_gst;
        } */

        return response()->json([
            'agents' => $agents,
            'company' => $company,
            'bill' => $bill,
            'payments' => $payments,
            'fid_prefix' => $fid_prefix,
            'suffix_bill' => $billNo,
            // 'bill_period_from' => $payments[0]->date ?? date('01-m-Y'),
            'bill_period_from' => $payments[0]->date ? date('01-m-Y', strtotime($payments[0]->date)) : date('01-m-Y'),
            'bill_period_to' => date('t-m-Y', strtotime('+2 months', strtotime($payments[0]->date ?? date('Y-m-d')))),
            'invoice_bill_date' => date('d-m-Y'),
            'total_amount' => $total_amount,
            'without_gst_amt' => $without_gst_amt,
            'with_gst_amt' => $with_gst_amt,
            'cgst' => $user->cgst,
            'sgst' => $user->sgst,
            'igst' => $user->igst,
            'tds' => $user->tds,
        ]);
    }

    public function getInvoiceBillNo(Request $request, $id)
    {
        $user = Session::get('user');
        $agent = DB::table('agents')->select('id', 'inv_prefix')->where('id', $id)->first();

        $bill = DB::table('commission_invoices')
            ->select('id', 'bill_no')
            ->where('financial_year_id', $user->financial_year_id)
            ->where('agent_id', 1)
            ->where('service_tax_flag', ($request->gst == 1 ? 1 : 0))
            ->orderBy('id', 'desc')
            ->first();

        $fid_prefix = DB::table('financial_year')->select('inv_prefix')->where('id', $user->financial_year_id)->pluck('inv_prefix')->first();

        if ($request->gst == 1) {
            if (empty($bill)) {
                $billNo = "0001";
            } else {
                $billNo = explode('-', $bill->bill_no);
                if (empty($billNo[2])) {
                    $billNo = "0001";
                } else {
                    $billNo = $billNo[2] + 1;
                    $billNo = sprintf('%04d', $billNo);
                }
            }
        } else {
            if (empty($bill)) {
                $billNo = "1";
            } else {
                $billNo = explode('-', $bill->bill_no);
                if (empty($billNo[2])) {
                    $billNo = $billNo[0] + 1;
                } else {
                    $billNo = $billNo[2] + 1;
                }
            }
        }
        $billNo = $agent->inv_prefix . "-" . $fid_prefix . "-" . $billNo;
        echo $billNo;
        exit;
    }

    public function editInvoice($id)
    {
        $page_title = 'Update Invoice';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();
        $employees['editedId'] = $id;
        $employees['scope'] = "edit";
        return view('account.commission.invoice.generateInvoice', compact('financialYear', 'page_title', 'employees'));
    }

    public function getInvoiceData($id)
    {
        $user = Session::get('user');
        $invoice_details = DB::table('commission_invoices')
            ->where('id', $id)
            ->where('financial_year_id', $user->financial_year_id)
            ->first();

        $without_gst_amt = $with_gst_amt = 0;
        /* $payment_details = DB::table('invoice_payment_details as ipd')
            ->join('payments as p', 'ipd.payment_id', '=', 'p.payment_id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'p.receipt_from', '=', 'cc.id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'p.supplier_id', '=', 'cs.id')
            ->select('ipd.payment_date', 'ipd.id', 'ipd.received_amount', 'ipd.commission_invoice_id', 'ipd.payment_id','cc.company_name as customer_name', 'cs.company_name as supplier_name', DB::raw("TO_CHAR(p.date, 'dd-mm-yyyy') as date"), 'p.id as p_id', 'p.financial_year_id')
            ->where('commission_invoice_id', $id)
            ->get(); */
        $invoice_payment_details = DB::table('invoice_payment_details')
            ->select(DB::raw("TO_CHAR(payment_date, 'dd-mm-yyyy') as date"), 'id', 'received_amount', 'commission_invoice_id', 'payment_id', 'company_id', 'financial_year_id')
            ->where('commission_invoice_id', $id)
            ->get();
        $p_ids = implode(',', collect($invoice_payment_details)->pluck('payment_id')->toArray());
        $f_ids = implode(',', collect($invoice_payment_details)->pluck('financial_year_id')->toArray());

        $payment_details = DB::table('payments as p')
            ->leftJoin('companies as c', function ($j) {
                $j->on('p.receipt_from', '=', 'c.id')
                ->orOn('p.supplier_id', '=', 'c.id');
            })
            ->select('c.id as company_id', 'c.company_name')
            ->whereRaw('p.payment_id in (' . $p_ids . ') and p.financial_year_id in (' . $f_ids . ')')
            // ->whereIn('p.payment_id', $p_ids)
            // ->whereIn('p.financial_year_id', $f_ids)
            ->get();

        foreach ($invoice_payment_details as $v) {
            $with_gst_amt += $v->received_amount;
            $v->company_name = collect($payment_details)->where('company_id', $v->company_id)->pluck('company_name')->first();
        }

        $pay_n_pay_det = DB::table('payments as p')
            ->leftJoin('payment_details as pd', 'p.id', '=', 'pd.p_increment_id')
            ->select('pd.adjust_amount', DB::raw('(SELECT (100 + cgst + sgst + igst) as gst from sale_bill_items WHERE financial_year_id = p.financial_year_id AND sale_bill_id = pd.sr_no AND is_deleted = 0 LIMIT 1) as gst'))
            ->whereRaw('p.payment_id in (' . $p_ids . ') and p.financial_year_id in (' . $f_ids . ')')
            ->where('p.is_deleted', 0)
            ->where('pd.is_deleted', 0)
            ->get();
        foreach ($pay_n_pay_det as $v) {
            $without_gst_amt += round((($v->adjust_amount * 100) / ($v->gst == 0 ? 1 : $v->gst)), 2);
        }

        /* $payment_details = DB::table('invoice_payment_details as ipd')
            ->join('payments as p', 'ipd.payment_id', '=', 'p.payment_id')
            ->join('payment_details as pd', 'p.id', '=', 'pd.p_increment_id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'p.receipt_from', '=', 'cc.id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'p.supplier_id', '=', 'cs.id')
            ->leftJoin('financial_year as fy', 'p.financial_year_id', '=', 'fy.id')
            ->select('ipd.payment_date', 'ipd.id', 'ipd.received_amount', 'ipd.commission_invoice_id', 'p.payment_id', 'p.financial_year_id', DB::raw("TO_CHAR(p.date, 'dd-mm-yyyy') as date"), 'p.receipt_amount', 'p.receipt_from', 'p.supplier_id', 'fy.name', 'cc.company_name as customer_name', 'cs.company_name as supplier_name', 'pd.adjust_amount', DB::raw('(SELECT (100 + cgst + sgst + igst) as gst from sale_bill_items WHERE financial_year_id = p.financial_year_id AND sale_bill_id = pd.sr_no AND is_deleted = 0 LIMIT 1) as gst') )
            ->where('ipd.commission_invoice_id', $id)
            ->where('p.is_deleted', 0)
            ->where('pd.is_deleted', 0)
            ->get();
        foreach ($payment_details as $v) {
            $without_gst = round((($v->adjust_amount * 100) / $v->gst), 2);
            $without_gst_amt += $without_gst;
            $with_gst_amt += $v->received_amount;
        } */

        $agents = DB::table('agents')->select('id', 'name', 'gst_no', 'pan_no', 'inv_prefix')->where('is_delete', '0')->get();

        $supplier = DB::table('companies as c')
            ->leftJoin('company_packaging_details as cpd', 'c.id', '=', 'cpd.company_id')
            ->leftJoin('company_addresses as ca', 'c.id', '=', 'ca.company_id')
            ->leftJoin('states as s', 'c.company_state', '=', 's.id')
            ->select('c.id', 'c.company_name', 'c.company_type', 'cpd.gst_no', 'c.company_state', 'ca.address', 's.name as state_name')
            ->where('c.id', $invoice_details->supplier_id)
            ->where('c.is_delete', 0)
            ->first();

        $customer = DB::table('companies as c')
            ->leftJoin('company_packaging_details as cpd', 'c.id', '=', 'cpd.company_id')
            ->leftJoin('company_addresses as ca', 'c.id', '=', 'ca.company_id')
            ->leftJoin('states as s', 'c.company_state', '=', 's.id')
            ->select('c.id', 'c.company_name', 'c.company_type', 'cpd.gst_no', 'c.company_state', 'ca.address', 's.name as state_name')
            ->where('c.id', $invoice_details->customer_id)
            ->where('c.is_delete', 0)
            ->first();

        $commission_details = DB::table('commission_details')
            ->select('id')
            ->where('commission_invoice_id', $id)
            ->where('is_deleted', 0)
            ->get();

        return response()->json([
            'invoice_details' => $invoice_details,
            'invoice_payment_details' => $invoice_payment_details,
            // 'payment_details' => $payment_details,
            'agents' => $agents,
            'supplier' => $supplier,
            'customer' => $customer,
            'commission_details' => $commission_details,
            'bill_period_from' => date('d-m-Y', strtotime($invoice_details->bill_period_from)),
            'bill_period_to' => date('d-m-Y', strtotime($invoice_details->bill_period_to)),
            'invoice_bill_date' => date('d-m-Y', strtotime($invoice_details->bill_date)),
            'without_gst_amt' => $without_gst_amt,
            'with_gst_amt' => $with_gst_amt,
            'cgst' => $user->cgst,
            'sgst' => $user->sgst,
            'igst' => $user->igst,
            'tds' => $user->tds,
        ]);
    }

    public function updateInvoice(Request $request)
    {
        // $user = Session::get('user');
        $new_req = $request->all();

        // $companyName = $new_req['company'];
        // $sale_bill_cont = new SaleBillController;
        /* if ($companyName->company_type != 0) {
            $companyTypeName = $sale_bill_cont->getCompanyTypeName($companyName->company_type);
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }
        $companyPerson = $sale_bill_cont->getCompanyDetails($companyName->id);
        if ($companyPerson) {
            $personName = $companyPerson->name;
        } else {
            $personName = '';
        } */

        $commi_total_amount  = $new_req['comm_total_amount'];
        $rounded_off         = $new_req['rounded_off'];
        $final_amount        = $new_req['final_amount'];
        $invoice_others      = $new_req['invoice_others'];
        $payment_comm        = $new_req['payment_comm'];
        $tax_class           = $new_req['select_tax'];
        $service_tax_flag    = 0;
        $tds_flag            = 0;
        $cgst                = 0;
        $cgst_amt            = 0;
        $sgst                = 0;
        $sgst_amt            = 0;
        $igst                = 0;
        $igst_amt            = 0;
        $tds_amt             = 0;
        if (isset($new_req['comm_invoice_gst'])) {
            $service_tax_flag = 1;
            if (isset($new_req['cgst_amount'])) {    // for gujarat
                $cgst     = $new_req['cgst'];
                $cgst_amt = $new_req['cgst_amount'];
                $sgst     = $new_req['sgst'];
                $sgst_amt = $new_req['sgst_amount'];
            }
            if (isset($new_req['igst_amount'])) {    // for other state
                $igst     = $new_req['igst'];
                $igst_amt = $new_req['igst_amount'];
            }
        }
        if (isset($new_req['comm_invoice_tds']) && $new_req['comm_invoice_tds'] == 1) {
            $tds_flag = 1;
            $tds_amt  = $new_req['tds_amount'];
        }
        $invoice = CommissionInvoice::where('id', $new_req['id'])->first();
        // $invoice->bill_no            = $new_req['full_bill_no'];
        $invoice->bill_period_to     = date('Y-m-d', strtotime($new_req['bill_period_to']));
        $invoice->bill_period_from   = date('Y-m-d', strtotime($new_req['bill_period_from']));
        $invoice->bill_date          = date('Y-m-d', strtotime($new_req['invoice_bill_date']));
        $invoice->service_tax_amount = 0;
        $invoice->service_tax        = 0;
        $invoice->commission_amount  = $commi_total_amount;
        $invoice->service_tax_flag   = $service_tax_flag;
        $invoice->tds_flag           = (int)$tds_flag;
        $invoice->with_without_gst   = $new_req['with_without_gst'];
        $invoice->tax_class          = $tax_class;
        $invoice->cgst               = $cgst;
        $invoice->cgst_amount        = $cgst_amt;
        $invoice->sgst               = $sgst;
        $invoice->sgst_amount        = $sgst_amt;
        $invoice->igst               = $igst;
        $invoice->igst_amount        = $igst_amt;
        $invoice->commission_percent = $payment_comm;
        $invoice->other_amount       = $invoice_others;
        $invoice->rounded_off        = $rounded_off;
        $invoice->tds_amount         = (int)$tds_amt;
        $invoice->final_amount       = $final_amount;
        $invoice->agent_id           = $new_req['courier_agent']['id'];
        $invoice->total_payment_received_amount = $new_req['total_amount'];
        $invoice->save();

        return response()->json(['success' => 1]);
    }

    public function deleteInvoicePaymentDetail(Request $request)
    {
        $id = $request->invoice_payment_detail_id;
        $invoice_id = $request->invoice_id;

        DB::table('invoice_payment_details')->where('id', $id)->delete();

        // get sum of rec_amount from invoice_payment_receive_details.
        $sum = DB::table('invoice_payment_details')
            ->selectRaw('SUM(received_amount) as new_rec_amount')
            ->where('commission_invoice_id', $invoice_id)
            ->pluck('new_rec_amount')
            ->first();

        $invoice = CommissionInvoice::where('id', $invoice_id)->first();
        $invoice->commission_amount = round((($sum * $invoice->commission_percent) / 100), 2);
        $invoice->total_payment_received_amount = $sum;
        $invoice->save();
        return response()->json(['success' => 1]);
    }

    public function refreshInvoicePaymentDetail(Request $request)
    {
        $id                = $request->invoice_payment_detail_id;
        $invoice_id        = $request->invoice_id;
        $payment_id        = $request->payment_id;
        $financial_year_id = $request->financial_year_id;
        $amount            = $request->amount;

        $invinfo = DB::table('commission_invoices')
            ->select('with_without_gst')
            ->where('id', $invoice_id)
            ->first();

        $with_without_gst = $invinfo->with_without_gst;
        $total_inv_amount = $amount;
        $paymentinfo = DB::table('payments')
            ->select('receipt_amount', /* DB::raw("TO_CHAR(date, 'dd-mm-yyyy') as date") */ 'date')
            ->where('payment_id', $payment_id)
            ->where('financial_year_id', $financial_year_id)
            ->where('is_deleted', 0)
            ->first();
        $receipt_amount = $paymentinfo->receipt_amount;

        $invpaymentinfo = InvoicePaymentDetails::select('id', 'received_amount', 'payment_date', 'updated_at')
            ->where('id', $id)
            ->first();
        $rec_amount = $invpaymentinfo->received_amount;

        $diff_amount = $rec_amount - $receipt_amount;
        if ($with_without_gst == 1 && $diff_amount >= 0) {
            $new_rec_amount       = $rec_amount - $diff_amount;
            $new_total_inv_amount = $total_inv_amount - $diff_amount;
            $invpaymentinfo->received_amount = $new_rec_amount;

        } else if ($with_without_gst == 1 && $diff_amount < 0) {
            $new_rec_amount       = $rec_amount + abs($diff_amount);
            $new_total_inv_amount = $total_inv_amount + abs($diff_amount);
            $invpaymentinfo->received_amount = $new_rec_amount;

        } else {
            if ($diff_amount >= 0) {
                $new_rec_amount = $rec_amount - $diff_amount;
            } else {
                $new_rec_amount = $rec_amount + abs($diff_amount);
            }
            $invpaymentinfo->received_amount = $new_rec_amount;

            $res = DB::table('invoice_payment_details')
                ->selectRaw('SUM(received_amount) as total_rec_amount')
                ->where('commission_invoice_id', $invoice_id)
                ->first();
            $new_total_inv_amount = $res->total_rec_amount;
        }
        $invpaymentinfo->payment_date = $paymentinfo->date;
        $invpaymentinfo->save();
        $data = array('success' => 1, "new_rec_amount" => $new_rec_amount, "new_total_inv_amount" => round($new_total_inv_amount), 'date' => $paymentinfo->date);
        echo json_encode($data);
        exit;
    }

    public function viewInvoiceDetails(Request $request, $id)
    {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();
        $employees['invoice_id'] = $id;
        if ($request->is('account/commission/invoice/print-invoice/*')) {
            $page_title = 'Print Invoice';
            return view('account.commission.invoice.printInvoice', compact('financialYear', 'page_title', 'employees'));
        } else {
            $page_title = 'View Invoice Details';
            return view('account.commission.invoice.viewInvoice', compact('financialYear', 'page_title', 'employees'));
        }
    }
}
