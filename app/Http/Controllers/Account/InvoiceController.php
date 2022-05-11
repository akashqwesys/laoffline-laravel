<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Commission\CommissionInvoice;
use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\comboids\Comboids;
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
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('a.agent_id', $ag_id);
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
            $totalRecordswithFilter = $totalRecordswithFilter->where('due_days', '>=', $columnName_arr[7]['search']['value']);
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();

        $invoice = DB::table('commission_invoices as ci')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'ci.customer_id', '=', 'cc.id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'ci.supplier_id', '=', 'cs.id')
            ->leftJoin('agents as a', 'ci.agent_id', '=', 'a.id')
            ->join('employees as e', 'ci.generated_by', '=', 'e.id')
            ->select('ci.id', 'ci.bill_no', 'ci.bill_date', 'ci.final_amount', 'ci.commission_status', 'ci.created_at', 'ci.customer_id', 'ci.supplier_id', 'ci.generated_by', 'ci.financial_year_id', 'ci.done_outward', 'ci.total_payment_received_amount', 'cc.company_name as customer_name', 'cs.company_name as supplier_name',  DB::raw('(SELECT "outward_id" FROM "outward_sale_bills" WHERE "commission_invoice_id" = "ci"."id" ORDER BY "id" DESC LIMIT 1) as outward_id'), 'a.name as agent_name', DB::raw('(date_part(\'day\', now()) - date_part (\'day\', bill_date)) as due_days'), 'e.firstname')
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
            $invoice = $invoice->whereIn('a.agent_id', $ag_id);
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
            $invoice = $invoice->where('due_days', '>=', $columnName_arr[7]['search']['value']);
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
                $outward_status = '<a href="' . ($s->outward_id ?? 0) . '" class="" ><em class="icon ni ni-check-thick" title="Yes"></em></a> ';
            }

            $action = '<a href="/account/commission/invoice/view-invoice/' . $s->id . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="View"><em class="icon ni ni-eye"></em></a> <a href="/account/commission/invoice/edit-invoice/' . $s->id . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';
            $delete_flag = 0;
            if ($s->commission_status == 0) {
                $delete_flag = 1;
                $commission_status = '<em class="icon ni ni-cross" title="None"></em>';
            } else if ($s->commission_status == 1) {
                $commission_status = '<em class="icon ni ni-check-thick" title="Complete"></em>';
            } else {
                $commission_status = '<em class="icon ni ni-check-thick" title="Complete"></em>';
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
                'agent_name' => $s->agent_name,
                'total_payment_received_amount' => $s->total_payment_received_amount,
                'commission_status' => $commission_status,
                "outward_status" => $outward_status,
                'due_days' => floor((time() - strtotime($s->bill_date)) / (60 * 60 * 24)),
                'generated_by' => $s->firstname,
                "action" => $action
            );
            $d_total += $s->total_payment_received_amount;
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

        return view('account.commission.invoice.createInvoice', compact('financialYear', 'page_title', 'employees'));
    }

    public function saveInvoice(Request $request)
    {
        dd($request->all());
        $user = Session::get('user');

        $companyName = $request->company;
        $sale_bill_cont = new SaleBillController;
        if ($companyName->company_type != 0) {
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
        }

        $combo_id = new Comboids;
        $combo_id->comboid            = (getLastID('comboids', 'comboid') + 1);
        $combo_id->system_module_id   = '19';
        $combo_id->main_or_followup   = '0';
        $combo_id->generated_by       = $user->employee_id;
        $combo_id->supplier_id        = $companyName->id;
        $combo_id->company_type       = $typeName;
        $combo_id->from_name          = $personName;
        $combo_id->subject            = 'Commission Invoice For : ' . $companyName->name;
        $combo_id->financial_year_id  = $user->financial_year_id;

        if ($typeName == "Supplier") {
            $combo_id->supplier_id = $companyName->id;
        } else {
            $combo_id->company_id = $companyName->id;
        }
        $combo_id->save();
        $comboid = $combo_id->comboid;

        $commi_total_amount  = $request->comm_total_amount;
        $rounded_off         = $request->rounded_off;
        $final_amount        = $request->final_amount;
        $invoice_others      = $request->invoice_others;
        $payment_comm        = $request->payment_comm;
        $service_tax_flag    = 0;
        $tds_flag            = 0;
        $cgst                = 0;
        $cgst_amt            = 0;
        $sgst                = 0;
        $sgst_amt            = 0;
        $igst                = 0;
        $igst_amt            = 0;
        $tds_amt             = 0;
        if (isset($request->comm_invoice_gst)) {
            $service_tax_flag = 1;
            if (isset($request->cgst_amount)) {    // for gujarat
                $cgst     = $request->cgst;
                $cgst_amt = $request->cgst_amount;
                $sgst     = $request->sgst;
                $sgst_amt = $request->sgst_amount;
            }
            if (isset($request->igst_amount)) {    // for other state
                $igst     = $request->igst;
                $igst_amt = $request->igst_amount;
            }
        }
        if (isset($request->comm_invoice_tds)) {
            $tds_flag = 1;
            $tds_amt  = $request->tds_amount;
        }
        $invoice = new CommissionInvoice;
        $invoice->financial_year_id  = $user->financial_year_id;
        $invoice->generated_by       = $user->employee_id;
        $invoice->bill_no            = $request->full_bill_no;
        $invoice->bill_period_to     = date('Y-m-d', strtotime($request->bill_period_to));
        $invoice->bill_period_from   = date('Y-m-d', strtotime($request->bill_period_from));
        $invoice->bill_date          = date('Y-m-d', strtotime($request->invoice_bill_date));
        $invoice->service_tax_amount = 0;
        $invoice->service_tax        = 0;
        $invoice->commission_amount  = $commi_total_amount;
        $invoice->service_tax_flag   = $service_tax_flag;
        $invoice->tds_flag           = (int)$tds_flag;
        $invoice->with_without_gst   = $request->with_without_gst;
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
        $invoice->agent_id           = $request->courier_agent->id;

        if ($typeName == "Supplier") {
            $invoice->supplier_id  = $companyName->id;
        } else {
            $invoice->customer_id  = $companyName->id;
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
        foreach ($request->payments as $p) {
            $dataentry_payment[] = array(
                'commission_invoice_id' => $commission_invoice_id,
                'payment_id'            => $p->payment_id,
                'financial_year_id'     => $p->financial_year_id,
                'payment_date'          => date('Y-m-d', strtotime($p->date)),
                'company_id'            => $p->$invoice_company_id,
                'received_amount'       => $p->receipt_amount,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
                'flag'                  => $flag,
            );
        }
        DB::table('invoice_payment_details')->insert($dataentry_payment);

        $invoice->total_payment_received_amount = $request->total_amount;
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
        if ($request->company_type == 3) {
            $flag = 1; // for supplier
        } else if ($request->company_type == 2) {
            $flag = 2; // for customer
        }
        $payment = DB::table('payments as p')
            ->join('company_commissions as ccm', function ($j) use($flag) {
                $j->on('p.receipt_from', '=', 'ccm.customer_id')
                ->on('p.supplier_id', '=', 'ccm.supplier_id')
                ->where('ccm.flag', '=', $flag);
            })
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'p.receipt_from', '=', 'cc.id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'p.supplier_id', '=', 'cs.id')
            ->leftJoin('financial_year as fy', 'p.financial_year_id', '=', 'fy.id')
            ->select('p.id', 'p.payment_id', 'p.financial_year_id', 'p.date', 'p.receipt_amount', 'p.receipt_from', 'p.supplier_id', 'fy.name', 'cc.company_name as customer_name', 'cs.company_name as supplier_name');
        if ($company_details) {
            $main_cmp_id = $company_details->id;
            $payment = $payment->whereRaw('"p"."supplier_id" = ' . $main_cmp_id . ' or "p"."customer_id" = ' . $main_cmp_id )
                ->whereIn('p.supplier_id', $link_companies);
        }
        $payment = $payment->where('p.is_deleted', 0)
            ->where('p.right_of_amount', 0)
            ->where('p.receipt_amount', 0)
            ->orderBy('p.date', 'asc')
            ->get();

        if (count($payment)) {
            $payment_ids = collect($payment)->pluck('payment_id')->unique()->toArray();
            $finan_ids = collect($payment)->pluck('payment_id')->unique()->toArray();

            $commission = DB::table('commissions as c')
                ->join('commission_details as cd', 'c.id', '=', 'cd.c_increment_id')
                ->select('c.id')
                ->whereIn('cd.payment_id', $payment_ids)
                ->whereIn('cd.financial_year_id', $finan_ids)
                ->get();

            $invoice_payment = DB::table('invoice_payment_details')
                ->select('id')
                ->whereIn('cd.payment_id', $payment_ids)
                ->whereIn('cd.financial_year_id', $finan_ids);
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
                        ->toArray();
                    if (count($invoice_exist) == 0) {
                        $date = date('d-m-Y', strtotime($p->date));
                        $data_arr[] = array(
                            'payment_id' => $p->payment_id,
                            "financial_year" => $p->name,
                            "financial_year_id" => $p->financial_year_id,
                            "date" => $date,
                            "supplier" => $p->supplier_name,
                            "customer" => $p->customer_name,
                            'amount' => $p->receipt_amount,
                            'due_days' => floor((time() - strtotime($p->bill_date)) / (60 * 60 * 24))
                        );
                    }
                }
            }
        }
        $data_arr[] = array(
            'payment_id' => 123,
            "financial_year" => 123,
            "financial_year_id" => 8,
            "date" => 12,
            "supplier" => 123,
            "customer" => 123,
            'amount' => 25000,
            'due_days' => 44
        );
        $data_arr[] = array(
            'payment_id' => 124,
            "financial_year" => 123,
            "financial_year_id" => 8,
            "date" => 12,
            "supplier" => 123,
            "customer" => 123,
            'amount' => 60000,
            'due_days' => 91
        );
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
                ->pluck('commission')
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
                "payment_ok_or_not" => 1
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
        $p_ids = [];
        $total_amount = 0;
        foreach ($paymentIds as $v) {
            $a = explode('-', $v);
            $p_ids[] = $a[0];
            $total_amount += floatval($a[2]);
        }
        $payments = DB::table('payments as p')
            ->join('payment_details as pd', 'p.id', '=', 'pd.p_increment_id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cc"'), 'p.receipt_from', '=', 'cc.id')
            ->leftJoin(DB::raw('(SELECT "company_name", "id" FROM companies group by "company_name", "id") as "cs"'), 'p.supplier_id', '=', 'cs.id')
            ->leftJoin('financial_year as fy', 'p.financial_year_id', '=', 'fy.id')
            ->select('p.id', 'p.payment_id', 'p.financial_year_id', 'p.date', 'p.receipt_amount', 'p.receipt_from', 'p.supplier_id', 'fy.name', 'cc.company_name as customer_name', 'cs.company_name as supplier_name', 'pd.adjust_amount', DB::raw('(SELECT (100 + cgst + sgst + igst) as gst from sale_bill_items WHERE financial_year_id = p.financial_year_id AND sale_bill_id = pd.sr_no AND is_deleted = 0 LIMIT 1) as gst' ) )
            ->whereIn('p.payment_id', $p_ids)
            ->where('p.is_deleted', 0)
            ->where('pd.is_deleted', 0)
            ->get();
        $without_gst_amt = 0;
        foreach ($payments as $v) {
            $without_gst = round((($v->adjust_amount * 100) / $v->gst), 2);
            $without_gst_amt += $without_gst;
        }
        return response()->json([
            'agents' => $agents,
            'company' => $company,
            'bill' => $bill,
            'payments' => $payments,
            'fid_prefix' => $fid_prefix,
            'suffix_bill' => $billNo,
            'bill_period_from' => $payments[0]->date ?? date('01-m-Y'),
            'bill_period_to' => date('t-m-Y', strtotime('+2 months', strtotime($payments[0]->date ?? date('Y-m-d')))),
            'invoice_bill_date' => date('d-m-Y'),
            'total_amount' => $total_amount,
            'without_gst_amt' => $without_gst_amt,
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
}
