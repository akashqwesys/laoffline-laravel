<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Commission\CommissionInvoice;
use App\Models\FinancialYear;
use App\Models\Logs;
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
}
