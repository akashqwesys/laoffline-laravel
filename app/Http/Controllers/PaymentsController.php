<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Iuid;
use App\Models\Logs;
use App\Models\Ouid;
use App\Models\Payment;
use App\Models\Employee;
use App\Models\SaleBill;
use App\Models\CompanyType;
use App\Models\IncrementId;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\PaymentDetail;
use App\Models\Company\Company;
use App\Models\Comboids\Comboids;
use App\Models\Goods\GoodsReturn;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Goods\GrSaleBillItem;
use App\Models\Settings\BankDetails;
use App\Models\Reference\ReferenceId;
use Spatie\Permission\Traits\HasRoles;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;
use App\Models\Settings\TransportDetails;


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

    public function goodReturn(Request $request) {
        $page_title = 'Good Return List';
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

        return view('payment.goodreturn',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function goodreturnList(Request $request) {
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

        // Total records
        $totalRecords = DB::table('goods_returns')
                        ->select('count(*) as count')
                        ->count();
        $totalRecordswithFilter = DB::table('goods_returns')
                                ->where('financial_year_id', $user->financial_year_id)
                                ->where('is_deleted', 0);
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('goods_return_id', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('iuid', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('reference_id', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('goods_returns.created_at', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[4]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('company_id', $cc_id);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $sp_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[4]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('supplier_id', $sp_id);
        }

        $totalRecordswithFilter = $totalRecordswithFilter->count();

        // Fetch records
        $records = DB::table('goods_returns')
                    ->where('financial_year_id', $user->financial_year_id)
                    ->where('is_deleted', 0);
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $records = $records->where('goods_return_id', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $records = $records->where('iuid', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $records = $records->where('reference_id', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $records = $records->whereDate('goods_returns.created_at', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[4]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->whereIn('company_id', $cc_id);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $sp_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[4]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->whereIn('supplier_id', $sp_id);
        }
        $records = $records->select('goods_return_id','iuid', 'reference_id', 'created_at', 'company_id', 'supplier_id', 'goods_return');

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $customer_company = Company::where('id', $record->company_id)->first();
            $customer_address = DB::table('company_addresses')
                                ->select('id', 'company_id')
                                ->whereRaw("(address is not null or address <> '')")
                                ->where('company_id', $record->company_id)
                                ->get();
            $customer_owners = DB::table('company_address_owners as cao')
                            ->join('company_addresses as ca', 'cao.company_address_id', '=', 'ca.id')
                            ->select('cao.id', 'ca.company_id')
                            ->whereRaw("(cao.name is not null or cao.name <> '') and (cao.mobile is not null or cao.mobile <> '') and cao.designation @> '0'")
                            ->where('ca.company_id', $record->company_id)
                            ->get();

            $seller_company = Company::where('id', $record->supplier_id)->first();
            $seller_address = DB::table('company_addresses')
                                ->select('id', 'company_id')
                                ->whereRaw("(address is not null or address <> '')")
                                ->where('company_id', $record->supplier_id)
                                ->get();
            $seller_owners = DB::table('company_address_owners as cao')
                            ->join('company_addresses as ca', 'cao.company_address_id', '=', 'ca.id')
                            ->select('cao.id', 'ca.company_id')
                            ->whereRaw("(cao.name is not null or cao.name <> '') and (cao.mobile is not null or cao.mobile <> '') and cao.designation @> '0'")
                            ->where('ca.company_id', $record->supplier_id)
                            ->get();
            if ((count($customer_address) == 0 || count($customer_owners) == 0)) {
                $customer_color = '';
            } else {
                $customer_color = ' text-danger ';
            }

            if ((count($seller_address) == 0 || count($seller_owners) == 0)) {
                $seller_color = '';
            } else {
                $seller_color = ' text-danger ';
            }
            $id = $record->goods_return_id;
            $iuid = $record->iuid;
            $ref_id = $record->reference_id;
            $date_add = $record->created_at;
            $customer = '<a href="#" class="view-details ' . $customer_color . '" data-id="' . $customer_company->id . '">' . $customer_company->company_name . '</a>';
            $seller = '<a href="#" class="view-details ' . $seller_color . '" data-id="' . $seller_company->id . '">' . $seller_company->company_name . '</a>';
            $gramount = $record->goods_return;

            $action = '<a href="/payments/view-goodreturn/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/payments/edit-goodreturn/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="/payments/deletegoodreturn/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "id" => $id,
                "iuid" => $iuid,
                "reference_id" => $ref_id,
                "created_at" => $date_add,
                "customer" => $customer,
                "supplier" => $seller,
                "goods_return" => $gramount,
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

    public function completelistpayment(Request $request) {
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
        $totalRecords = Payment::where('payments.financial_year_id', $user->financial_year_id)->where('payments.is_deleted', 0)->select('count(*) as allcount')->count();

        $totalRecordswithFilter = Payment::where('payments.financial_year_id', $user->financial_year_id)->where('payments.is_deleted', 0);
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.payment_id', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.iuid', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.ouid', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.reference_id', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('payments.created_at', '=', $columnName_arr[4]['search']['value']);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('payments.date', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[6]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('payments.customer_id', $cc_id);
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            $sp_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('payments.supplier_id', $sp_id);
        }
        if (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.payment_id', '=', $columnName_arr[8]['search']['value']);
        }
        if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.tot_adjust_amount', '=', $columnName_arr[9]['search']['value']);
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();


        $records = Payment::where('payments.financial_year_id', $user->financial_year_id)->where('payments.is_deleted', 0);
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $records = $records->where('payments.payment_id', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $records = $records->where('payments.iuid', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $records = $records->where('payments.ouid', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $records = $records->where('payments.reference_id', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $records = $records->whereDate('payments.created_at', '=', $columnName_arr[4]['search']['value']);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $records = $records->whereDate('payments.date', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[6]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->whereIn('payments.customer_id', $cc_id);
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            $sp_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->whereIn('payments.supplier_id', $sp_id);
        }
        if (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value'])) {
            $records = $records->where('payments.payment_id', '=', $columnName_arr[8]['search']['value']);
        }
        if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
            $records = $records->where('payments.tot_adjust_amount', '=', $columnName_arr[9]['search']['value']);
        }


        // Fetch records
        $records = $records->select('payments.payment_id', 'payments.financial_year_id','payments.iuid', 'payments.reference_id', 'payments.created_at', 'payments.date', 'payments.customer_id', 'payments.supplier_id', 'payments.payment_id', 'payments.receipt_amount', 'payments.customer_commission_status','payments.old_commission_status' , 'payments.done_outward', DB::raw('(SELECT "color_flag_id" FROM "comboids" WHERE "comboids"."payment_id" = "payments"."payment_id" and goods_return_id = 0 ORDER BY "id" DESC LIMIT 1) as color_flag_id'));

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();

        $data_arr = array();
        $recorddata = array();
        foreach($records as $data){
            if ($data->color_flag_id == 3) {
                $recorddata[] = $data;
            }
        }
        foreach($recorddata as $record){

            $customer_company = Company::where('id', $record->customer_id)->first();
            $customer_address = DB::table('company_addresses')
                                ->select('id', 'company_id')
                                ->whereRaw("(address is not null or address <> '')")
                                ->where('company_id', $record->customer_id)
                                ->get();
            $customer_owners = DB::table('company_address_owners as cao')
                            ->join('company_addresses as ca', 'cao.company_address_id', '=', 'ca.id')
                            ->select('cao.id', 'ca.company_id')
                            ->whereRaw("(cao.name is not null or cao.name <> '') and (cao.mobile is not null or cao.mobile <> '') and cao.designation @> '0'")
                            ->where('ca.company_id', $record->customer_id)
                            ->get();

            $seller_company = Company::where('id', $record->supplier_id)->first();
            $seller_address = DB::table('company_addresses')
                                ->select('id', 'company_id')
                                ->whereRaw("(address is not null or address <> '')")
                                ->where('company_id', $record->supplier_id)
                                ->get();
            $seller_owners = DB::table('company_address_owners as cao')
                            ->join('company_addresses as ca', 'cao.company_address_id', '=', 'ca.id')
                            ->select('cao.id', 'ca.company_id')
                            ->whereRaw("(cao.name is not null or cao.name <> '') and (cao.mobile is not null or cao.mobile <> '') and cao.designation @> '0'")
                            ->where('ca.company_id', $record->supplier_id)
                            ->get();
            if ((count($customer_address) == 0 || count($customer_owners) == 0)) {
                $customer_color = '';
            } else {
                $customer_color = ' text-danger ';
            }

            if ((count($seller_address) == 0 || count($seller_owners) == 0)) {
                $seller_color = '';
            } else {
                $seller_color = ' text-danger ';
            }
            $id = $record->payment_id;
            $iuid = $record->iuid;
            $ouid = '';
            $ref_id = $record->reference_id;
            $date_add = date_format($record->created_at, "Y/m/d H:i:s");
            $payment_date = $record->date;
            $customer = '<a href="#" class="view-details ' . $customer_color . '" data-id="' . $customer_company->id . '">' . $customer_company->company_name . '</a>';
            $seller = '<a href="#" class="view-details ' . $seller_color . '" data-id="' . $seller_company->id . '">' . $seller_company->company_name . '</a>';
            $voucher = $record->payment_id;
            $paid_amount = $record->receipt_amount;
            if (!$record->old_commission_status) {
                $scs = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-cross"></em></a>';
            } else {
                $comissionlink = DB::table('invoice_payment_details')->where('payment_id', $id)->where('financial_year_id', $record->financial_year_id)->first();
                if (!empty($comissionlink)) {
                    $commissiondetail = DB::table('commission_details')->where('commission_invoice_id', $comissionlink->commission_invoice_id)->first();
                    if (!empty($commissiondetail)) {
                        $commission = DB::table('commissions')->where('id', $commissiondetail->c_increment_id)->first();
                    }
                }
                $scs = '<a href="/commission/view-commission/'.$commission->commission_id.'/'.$commission->financial_year_id.'" class="btn btn-trigger btn-icon"><em class="icon ni ni-check"></em></a>';
            }
            if (!$record->customer_commission_status) {
                $ccs = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-cross"></em></a>';
            } else {
                $comissionlink = DB::table('invoice_payment_details')->where('payment_id', $id)->where('financial_year_id', $record->financial_year_id)->first();
                if (!empty($comissionlink)) {
                    $commissiondetail = DB::table('commission_details')->where('commission_invoice_id', $comissionlink->commission_invoice_id)->first();
                    if (!empty($commissiondetail)) {
                        $commission = DB::table('commissions')->where('id', $commissiondetail->c_increment_id)->first();
                    }
                }
                $ccs = '<a href="/commission/view-commission/'.$commission->commission_id.'/'.$commission->financial_year_id.'" class="btn btn-trigger btn-icon"><em class="icon ni ni-check"></em></a>';
            }
            if (!$record->done_outward) {
                $outward = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-cross"></em></a>';
            } else {
                $outwardlink = DB::table('outward_sale_bills')->where('payment_id', $id)->where('is_deleted', 0)->where('financial_year_id', $record->financial_year_id)->first();
                $outward = '<a href="/register/view-outward/'.$outwardlink->outward_id.'" class="btn btn-trigger btn-icon"><em class="icon ni ni-check"></em></a>';
            }
            $action = '<a href="/payments/view-voucher/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="voucher"><em class="icon ni ni-file-docs"></em></a><a href="/payments/view-payment/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/payments/edit-payment/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="/payments/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "id" => $id,
                "iuid" => $iuid,
                "ouid" => $ouid,
                "reference_id" => $ref_id,
                "created_at" => $date_add,
                "date" => $payment_date,
                "customer" => $customer,
                "supplier" => $seller,
                "payment_id" => $voucher,
                "tot_adjust_amount" => $paid_amount,
                "suppiler_commission_status" => $scs,
                "customer_commission_status" => $ccs,
                "outward_status" => $outward,
                "action" => $action
            );

        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => count($recorddata),
            "iTotalDisplayRecords" => count($recorddata),
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function inCompletelistpayment(Request $request) {
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
        $totalRecords = Payment::where('payments.financial_year_id', $user->financial_year_id)->where('payments.is_deleted', 0)->select('count(*) as allcount')->count();

        $totalRecordswithFilter = Payment::where('payments.financial_year_id', $user->financial_year_id)->where('payments.is_deleted', 0);
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.payment_id', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.iuid', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.ouid', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.reference_id', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('payments.created_at', '=', $columnName_arr[4]['search']['value']);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('payments.date', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[6]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('payments.customer_id', $cc_id);
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            $sp_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('payments.supplier_id', $sp_id);
        }
        if (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.payment_id', '=', $columnName_arr[8]['search']['value']);
        }
        if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.tot_adjust_amount', '=', $columnName_arr[9]['search']['value']);
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();


        $records = Payment::where('payments.financial_year_id', $user->financial_year_id)->where('payments.is_deleted', 0);
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $records = $records->where('payments.payment_id', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $records = $records->where('payments.iuid', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $records = $records->where('payments.ouid', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $records = $records->where('payments.reference_id', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $records = $records->whereDate('payments.created_at', '=', $columnName_arr[4]['search']['value']);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $records = $records->whereDate('payments.date', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[6]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->whereIn('payments.customer_id', $cc_id);
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            $sp_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->whereIn('payments.supplier_id', $sp_id);
        }
        if (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value'])) {
            $records = $records->where('payments.payment_id', '=', $columnName_arr[8]['search']['value']);
        }
        if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
            $records = $records->where('payments.tot_adjust_amount', '=', $columnName_arr[9]['search']['value']);
        }


        // Fetch records
        $records = $records->select('payments.payment_id', 'payments.financial_year_id','payments.iuid', 'payments.reference_id', 'payments.created_at', 'payments.date', 'payments.customer_id', 'payments.supplier_id', 'payments.payment_id', 'payments.receipt_amount', 'payments.customer_commission_status', 'payments.old_commission_status', 'payments.done_outward', DB::raw('(SELECT "color_flag_id" FROM "comboids" WHERE "comboids"."payment_id" = "payments"."payment_id" and goods_return_id = 0 ORDER BY "id" DESC LIMIT 1) as color_flag_id'));

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();
        $data_arr = array();
        $recorddata = array();
        foreach($records as $data){
            if ($data->color_flag_id != 3) {
                $recorddata[] = $data;
            }
        };

        foreach($recorddata as $record){

            $customer_company = Company::where('id', $record->customer_id)->first();
            $customer_address = DB::table('company_addresses')
                                ->select('id', 'company_id')
                                ->whereRaw("(address is not null or address <> '')")
                                ->where('company_id', $record->customer_id)
                                ->get();
            $customer_owners = DB::table('company_address_owners as cao')
                            ->join('company_addresses as ca', 'cao.company_address_id', '=', 'ca.id')
                            ->select('cao.id', 'ca.company_id')
                            ->whereRaw("(cao.name is not null or cao.name <> '') and (cao.mobile is not null or cao.mobile <> '') and cao.designation @> '0'")
                            ->where('ca.company_id', $record->customer_id)
                            ->get();

            $seller_company = Company::where('id', $record->supplier_id)->first();
            $seller_address = DB::table('company_addresses')
                                ->select('id', 'company_id')
                                ->whereRaw("(address is not null or address <> '')")
                                ->where('company_id', $record->supplier_id)
                                ->get();
            $seller_owners = DB::table('company_address_owners as cao')
                            ->join('company_addresses as ca', 'cao.company_address_id', '=', 'ca.id')
                            ->select('cao.id', 'ca.company_id')
                            ->whereRaw("(cao.name is not null or cao.name <> '') and (cao.mobile is not null or cao.mobile <> '') and cao.designation @> '0'")
                            ->where('ca.company_id', $record->supplier_id)
                            ->get();
            if ((count($customer_address) == 0 || count($customer_owners) == 0)) {
                $customer_color = '';
            } else {
                $customer_color = ' text-danger ';
            }

            if ((count($seller_address) == 0 || count($seller_owners) == 0)) {
                $seller_color = '';
            } else {
                $seller_color = ' text-danger ';
            }

            $id = $record->payment_id;
            $iuid = $record->iuid;
            $ouid = '';
            $ref_id = $record->reference_id;
            $date_add = date_format($record->created_at, "d-m-Y H:i:s");
            $payment_date = date('d-m-Y', strtotime($record->date));
            $customer = '<a href="#" class="view-details ' . $customer_color . '" data-id="' . $customer_company->id . '">' . $customer_company->company_name . '</a>';
            $seller = '<a href="#" class="view-details ' . $seller_color . '" data-id="' . $seller_company->id . '">' . $seller_company->company_name . '</a>';
            $voucher = $record->payment_id;
            $paid_amount = $record->receipt_amount;
            if (!$record->old_commission_status) {
                $scs = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-cross"></em></a>';
            } else {
                $comissionlink = DB::table('invoice_payment_details')->where('payment_id', $id)->where('financial_year_id', $record->financial_year_id)->first();
                if (!empty($comissionlink)) {
                    $commissiondetail = DB::table('commission_details')->where('commission_invoice_id', $comissionlink->commission_invoice_id)->first();
                    if (!empty($commissiondetail)) {
                        $commission = DB::table('commissions')->where('id', $commissiondetail->c_increment_id)->first();
                    }
                }
                $scs = '<a href="/commission/view-commission/'.$commission->commission_id.'/'.$commission->financial_year_id.'" class="btn btn-trigger btn-icon"><em class="icon ni ni-check"></em></a>';
            }
            if (!$record->customer_commission_status) {
                $ccs = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-cross"></em></a>';
            } else {
                $comissionlink = DB::table('invoice_payment_details')->where('payment_id', $id)->where('financial_year_id', $record->financial_year_id)->first();
                if (!empty($comissionlink)) {
                    $commissiondetail = DB::table('commission_details')->where('commission_invoice_id', $comissionlink->commission_invoice_id)->first();
                    if (!empty($commissiondetail)) {
                        $commission = DB::table('commissions')->where('id', $commissiondetail->c_increment_id)->first();
                    }
                }
                $ccs = '<a href="/commission/view-commission/'.$commission->commission_id.'/'.$commission->financial_year_id.'" class="btn btn-trigger btn-icon"><em class="icon ni ni-check"></em></a>';
            }
            if (!$record->done_outward) {
                $outward = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-cross"></em></a>';
            } else {
                $outwardlink = DB::table('outward_sale_bills')->where('payment_id', $id)->where('is_deleted', 0)->where('financial_year_id', $record->financial_year_id)->first();
                $outward = '<a href="/register/view-outward/'.$outwardlink->outward_id.'" class="btn btn-trigger btn-icon"><em class="icon ni ni-check"></em></a>';
            }

            $action = '<a href="/payments/view-voucher/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="voucher"><em class="icon ni ni-file-docs"></em></a><a href="/payments/view-payment/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/payments/edit-payment/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="/payments/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "id" => $id,
                "iuid" => $iuid,
                "ouid" => $ouid,
                "reference_id" => $ref_id,
                "created_at" => $date_add,
                "date" => $payment_date,
                "customer" => $customer,
                "supplier" => $seller,
                "payment_id" => $voucher,
                "tot_adjust_amount" => $paid_amount,
                "suppiler_commission_status" => $scs,
                "customer_commission_status" => $ccs,
                "outward_status" => $outward,
                "action" => $action
            );

        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => count($recorddata),
            "iTotalDisplayRecords" => count($recorddata),
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function getPaymentSearch(Request $request) {
        $searchdata = $request->session()->get('payment_search');
        return $searchdata;
    }
    public function clearPaymentSearch(Request $request) {
        $request->session()->forget('payment_search');
        return true;
    }
    public function storePaymentSearch(Request $request) {
        $request->session()->put('payment_search', $request->all());
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

        $totalRecords = Payment::where('payments.is_deleted', '0')->where('payments.financial_year_id', $user->financial_year_id)->select('count(*) as allcount')->count();

        $totalRecordswithFilter = Payment::where('payments.is_deleted', '0')->where('payments.financial_year_id', $user->financial_year_id);
        if ((isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) || (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value']))) {
            if (!empty($columnName_arr[0]['search']['value'])) {
                $totalRecordswithFilter = $totalRecordswithFilter->where('payments.payment_id', '=', $columnName_arr[0]['search']['value']);
            } else {
                $totalRecordswithFilter = $totalRecordswithFilter->where('payments.payment_id', '=', $columnName_arr[8]['search']['value']);
            }
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.iuid', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.ouid', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.reference_id', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('payments.created_at', '=', $columnName_arr[4]['search']['value']);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('payments.date', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', '=', $columnName_arr[6]['search']['value'])->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('customer_id', $cc_id);
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            $sp_id = DB::table('companies')->select('id')->where('company_name', '=', $columnName_arr[7]['search']['value'])->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('payments.supplier_id', $sp_id);
        }

        if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('payments.tot_adjust_amount', '=', $columnName_arr[9]['search']['value']);
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();


        $records = Payment::where('payments.is_deleted', '0')->where('payments.financial_year_id', $user->financial_year_id);
        if ((isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) || (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value']))) {
            if (!empty($columnName_arr[0]['search']['value'])) {
                $records = $records->where('payments.payment_id', '=', $columnName_arr[0]['search']['value']);
            } else {
                $records = $records->where('payments.payment_id', '=', $columnName_arr[8]['search']['value']);
            }
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $records = $records->where('payments.iuid', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $records = $records->where('payments.ouid', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $records = $records->where('payments.reference_id', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $records = $records->whereDate('payments.created_at', '=', $columnName_arr[4]['search']['value']);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $records = $records->whereDate('payments.date', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', '=', $columnName_arr[6]['search']['value'] )->pluck('id')->toArray();
            $records = $records->whereIn('payments.customer_id', $cc_id);
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            $sp_id = DB::table('companies')->select('id')->where('company_name', '=', $columnName_arr[7]['search']['value'] )->pluck('id')->toArray();
            $records = $records->whereIn('payments.supplier_id', $sp_id);
        }
        if (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value']) && is_int($columnName_arr[8]['search']['value']) ) {
            $records = $records->where('payments.payment_id', '=', $columnName_arr[8]['search']['value']);
        }
        if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
            $records = $records->where('payments.tot_adjust_amount', '=', $columnName_arr[9]['search']['value']);
        }

        // Fetch records
        $records = $records->select('payments.id', 'payments.financial_year_id', 'payments.reciept_mode', 'payments.payment_id', 'payments.iuid', 'payments.reference_id', 'payments.created_at', 'payments.date', 'payments.customer_id', 'payments.supplier_id', 'payments.payment_id', 'payments.receipt_amount', 'payments.tot_adjust_amount', 'payments.tot_good_returns', 'payments.customer_commission_status', 'payments.old_commission_status', 'payments.done_outward', 'payments.reciept_mode', DB::raw('(SELECT "color_flag_id" FROM "comboids" WHERE "comboids"."payment_id" = "payments"."payment_id" and goods_return_id = 0 and financial_year_id = payments.financial_year_id ORDER BY "id" DESC LIMIT 1) as color_flag_id'));

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $customer_company = Company::where('id', $record->customer_id)->first();
            $customer_address = DB::table('company_addresses')
                                ->select('id', 'company_id')
                                ->whereRaw("(address is not null or address <> '')")
                                ->where('company_id', $record->customer_id)
                                ->get();
            $customer_owners = DB::table('company_address_owners as cao')
                            ->join('company_addresses as ca', 'cao.company_address_id', '=', 'ca.id')
                            ->select('cao.id', 'ca.company_id')
                            ->whereRaw("(cao.name is not null or cao.name <> '') and (cao.mobile is not null or cao.mobile <> '') and cao.designation @> '0'")
                            ->where('ca.company_id', $record->customer_id)
                            ->get();

            $seller_company = Company::where('id', $record->supplier_id)->first();
            $seller_address = DB::table('company_addresses')
                                ->select('id', 'company_id')
                                ->whereRaw("(address is not null or address <> '')")
                                ->where('company_id', $record->supplier_id)
                                ->get();
            $seller_owners = DB::table('company_address_owners as cao')
                            ->join('company_addresses as ca', 'cao.company_address_id', '=', 'ca.id')
                            ->select('cao.id', 'ca.company_id')
                            ->whereRaw("(cao.name is not null or cao.name <> '') and (cao.mobile is not null or cao.mobile <> '') and cao.designation @> '0'")
                            ->where('ca.company_id', $record->supplier_id)
                            ->get();

            $action = '';
            $id = $record->payment_id;
            if ($record->reciept_mode == 'fullreturn' || $record->reciept_mode == 'full return') {
                $sign = '<em class="icon ni ni-alert-fill"></em>';
            } else if ($record->reciept_mode == 'partreturn' || $record->reciept_mode == 'part return'){
                $sign = '<em class="icon ni ni-archived-fill"></em>';
            } else {
                $sign = '';
            }
            $iuid = $record->iuid;
            $ouid = '';
            $ref_id = $record->reference_id;
            $date_add = date_format($record->created_at, "d-m-Y H:i:s");
            $payment_date = date('d-m-Y', strtotime($record->date));
            if ((count($customer_address) == 0 || count($customer_owners) == 0)) {
                $customer_color = '';
            } else {
                $customer_color = ' text-danger ';
            }

            if ((count($seller_address) == 0 || count($seller_owners) == 0)) {
                $seller_color = '';
            } else {
                $seller_color = ' text-danger ';
            }
            $customer = '<a href="#" class="view-details ' . $customer_color . '"  data-id="' . $customer_company->id . '">' . $customer_company->company_name . '</a>';
            $seller = '<a href="#" class="view-details ' . $seller_color . '" data-id="' . $seller_company->id . '">' . $seller_company->company_name . '</a>';
            $voucher = $record->payment_id;
            if ($record->reciept_mode == 'partreturn') {
                $paid_amount = $record->tot_good_returns;
            } else {
                $paid_amount = $record->tot_adjust_amount;
            }
            if (!$record->old_commission_status) {
                $scs = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-cross"></em></a>';
            } else {
                $comissionlink = DB::table('invoice_payment_details')->where('payment_id', $id)->where('financial_year_id', $record->financial_year_id)->first();
                if (!empty($comissionlink)) {
                    $commissiondetail = DB::table('commission_details')->where('commission_invoice_id', $comissionlink->commission_invoice_id)->first();
                    if (!empty($commissiondetail)) {
                        $commission = DB::table('commissions')->where('id', $commissiondetail->c_increment_id)->first();
                    }
                }
                $scs = '<a href="/commission/view-commission/'.$commission->commission_id.'/'.$commission->financial_year_id.'" class="btn btn-trigger btn-icon"><em class="icon ni ni-check"></em></a>';
            }
            if (!$record->customer_commission_status) {
                $ccs = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-cross"></em></a>';
            } else {
                $comissionlink = DB::table('invoice_payment_details')->where('payment_id', $id)->where('financial_year_id', $record->financial_year_id)->first();
                if (!empty($comissionlink)) {
                    $commissiondetail = DB::table('commission_details')->where('commission_invoice_id', $comissionlink->commission_invoice_id)->first();
                    if (!empty($commissiondetail)) {
                        $commission = DB::table('commissions')->where('id', $commissiondetail->c_increment_id)->first();
                    }
                }
                $ccs = '<a href="/commission/view-commission/'.$commission->commission_id.'/'.$commission->financial_year_id.'" class="btn btn-trigger btn-icon"><em class="icon ni ni-check"></em></a>';
            }
            if (!$record->done_outward) {
                $outward = '<a href="#" class="btn btn-trigger btn-icon"><em class="icon ni ni-cross"></em></a>';
            } else {
                $outwardlink = DB::table('outward_sale_bills')->where('payment_id', $id)->where('is_deleted', 0)->where('financial_year_id', $record->financial_year_id)->first();
                $outward = '<a href="/register/view-outward/'.$outwardlink->outward_id.'" class="btn btn-trigger btn-icon"><em class="icon ni ni-check"></em></a>';
            }
            if ($record->reciept_mode == 'partreturn' || $record->reciept_mode == 'fullreturn' || $record->reciept_mode == 'part return' || $record->reciept_mode == 'full return') {
                $goodretun = GoodsReturn::where('p_increment_id', $record->id)
                            ->where('is_deleted', 0)
                            ->first();
                if (!$goodretun) {
                    $action = '<a href="/payments/add-goodreturn/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Good Return"><em class="icon ni ni-plus"></em></a>';
                } else {
                    $action = '';
                }
            }
            $color_flag_id = $record->color_flag_id;
            $action = $action.'<a href="/payments/view-voucher/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="voucher"><em class="icon ni ni-file-docs"></em></a><a href="/payments/view-payment/'.$id. '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show" target="_blank"><em class="icon ni ni-eye"></em></a>';
            // if (!$record->done_outward) {
                $action = $action.'<a href="/payments/edit-payment/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a><a href="javascript:void(0)" class="btn btn-trigger btn-icon delete-payment" data-id="'. $id . '" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';
            // }
            $data_arr[] = array(
                "id" => $id,
                "sign" => $sign,
                "iuid" => $iuid,
                "ouid" => $ouid,
                "reference_id" => $ref_id,
                "created_at" => $date_add,
                "date" => $payment_date,
                "customer" => $customer,
                "supplier" => $seller,
                "payment_id" => $voucher,
                "tot_adjust_amount" => $paid_amount,
                "suppiler_commission_status" => $scs,
                "customer_commission_status" => $ccs,
                "outward_status" => $outward,
                "color_flag_id" => $color_flag_id,
                "action" => $action
            );
        }
        $request->session()->put('payment_search', $columnName_arr);
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
        $seller = Company::where('is_delete', 0)->where('company_type',3)->get();
        return $seller;
    }

    public function listCustomer() {
        $customer = Company::where('is_delete', 0)->where('company_type',2)->get();
        return $customer;
    }

    public function listBank() {
        $bank = BankDetails::all();
        return $bank;
    }

    public function searchSaleBill(Request $request) {
        $user = Session::get('user');
        $customer_id = $request->input('customer');
        $supplier_id = $request->input('seller');

        $supplier =  Company::where('id', $supplier_id)->first();
        $salebill = DB::table('sale_bills')->where('company_id', $customer_id)
                    ->where('supplier_id', $supplier_id)
                    ->Where('payment_status', 0)
                    ->where('is_deleted', 0)
                    ->where('sale_bill_flag', 0)
                    ->orderBy('sale_bill_id', 'desc')
                    ->get();

        $salebills = array();
        foreach($salebill as $bill) {
            if ($bill->received_payment != 0){
                $pendingpayment = $bill->total - $bill->received_payment;
            } else {
                $pendingpayment = $bill->total;
            }
            $financialyear = FinancialYear::where('id', $bill->financial_year_id)->first();
            $overdue = floor((time() - strtotime($bill->select_date)) / (60 * 60 * 24));
            $bill_date = date('d-m-Y', strtotime($bill->select_date));
            $salebill = array('sallbillid' => $bill->sale_bill_id, 'financialyear' => $financialyear, 'invoiceid' => $bill->supplier_invoice_no, 'date'=> $bill_date, 'supplier' => $supplier->company_name, 'amount' => $bill->total, 'pending_payment' => $pendingpayment, 'overdue' => $overdue);
            array_push($salebills, $salebill);
        }
        usort($salebills, function($a, $b) {
            if ($a['overdue'] == $b['overdue']) {
              return 0;
            }
            return ($a['overdue'] > $b['overdue']) ? -1 : 1;
        });

        $data['salebill'] = $salebills;
        return $data;
    }

    public function generatePaymentData(Request $request){
        $request->session()->forget('customer');
        $request->session()->forget('seller');
        $request->session()->forget('saleBill');
        $request->session()->put('customer', $request->customer);
        $request->session()->put('seller', $request->seller);
        $request->session()->put('saleBill', $request->salebill);
        //$request->session()->put('finacial_year_id', 1);
        return true;
    }

    public function selectSaleBills(Request $request){
        $customer_id = $request->session()->get('customer');
        $seller_id = $request->session()->get('seller');
        $newSalebill = $request->input('salebill');

        $salebill_data = array();
        foreach($newSalebill as $ids){
            $salebills = DB::table('sale_bills')
                ->where('company_id', $customer_id)
                ->where('supplier_id', $seller_id)
                ->where('financial_year_id', $ids['fid'])
                ->where('sale_bill_id', $ids['id'])
                ->where('payment_status', 0)
                ->first();
            if ($salebills) {
                $status_c = new \stdClass;
                $status_c->code = 1;
                $status_c->status = 'Complete';
                $salebill = array('id' => $salebills->sale_bill_id, 'sup_inv' => $salebills->supplier_invoice_no, 'amount' => $salebills->total, 'adjustamount' => $salebills->total, 'status' => $status_c, 'discount' => 0, 'discountamount' => 0, 'goodreturn' => 0, 'ratedifference' => 0, 'bankcommission' => 0,  'vatav' => 0, 'agentcommission' => 0, 'claim' => 0, 'short' => 0,'interest'=> 0, 'remark' => '');
                array_push($salebill_data, $salebill);
            }
        }
        $data['salebill'] = $salebill_data;
        $oldSalebill = $request->session()->get('saleBill');
        $salebills = array_merge($oldSalebill,$newSalebill);
        $request->session()->forget('saleBill');
        $request->session()->put('saleBill', $salebills);
        return $data;
    }

    public function insertPaymentData(Request $request) {

        $user = Session::get('user');
        $paymentData = json_decode($request->formdata);
        $paymentSalebill = json_decode($request->billdata);
        $financialid = Session::get('user')->financial_year_id;
        $attachments = array();
        $cheque_image = array();
        if (!file_exists(public_path('upload/payments'))) {
            mkdir(public_path('upload/payments'), 0777, true);
        }

        $ChequeImage = $LetterImage = null;
        if ($request->chequeimage) {
        foreach($request->chequeimage as $key => $image) {
            $ChequeImage = date('YmdHis') . "_chequeImage".$key."." . $image->getClientOriginalExtension();
            $paymentData->chequeImage = $ChequeImage;
            $image->move(public_path('upload/payments/'), $ChequeImage);
            array_push($cheque_image, $ChequeImage);
        }}
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
            $ref_id = 1;
            $payment_id = 1;
            $iuid = 1;
            $increment_id = new IncrementId();
            $increment_id->reference_id = $ref_id;
            $increment_id->payment_id = $payment_id;
            $increment_id->id = $Incrementids;
            $increment_id->iuid = $iuid;
            $increment_id->financial_year_id = $financialid;
            $increment_id->save();
        }

        if ($paymentData->refrence == '1') {
            if ($paymentData->refrencevia->name == 'Email') {
                $courier_name = '';
                $courier_receipt_no = '';
                $courier_received_time = date('Y-m-d H:i:s');
            } else if ($paymentData->refrencevia->name == 'Whatsapp') {
                $courier_name = '';
                $courier_receipt_no = '';
                $courier_received_time = date('Y-m-d H:i:s');
            } else if ($paymentData->refrencevia->name == 'Hand') {
                $courier_name = '';
                $courier_receipt_no = '';
                $courier_received_time = date('Y-m-d H:i:s', strtotime($paymentData->recivedate));
            } else {
                $courier_name = $paymentData->courrier ? $paymentData->courrier->name : '';
                $courier_receipt_no = $paymentData->reciptno;
                $courier_received_time = date('Y-m-d H:i:s', strtotime($paymentData->recivedate));
            }

            $refrenceLastid = ReferenceId::orderBy('id', 'DESC')->first('id');
            $refrenceid = !empty($refrenceLastid) ? $refrenceLastid->id + 1 : 1;

            $user = Session::get('user');
            $refence = new ReferenceId();
            $refence->id = $refrenceid;
            $refence->reference_id = $ref_id;
            $refence->financial_year_id = $financialid;
            $refence->employee_id = $user->employee_id;
            $refence->inward_or_outward = 1;
            $refence->type_of_inward = $paymentData->refrencevia->name;
            $refence->company_id = $request->session()->get('customer');
            $refence->selection_date = Carbon::now()->format('Y-m-d');
            $refence->from_name = $paymentData->fromname ?? '';
            $refence->from_number = $paymentData->whatsapp ?? '';
            $refence->receiver_number = $paymentData->reciveno ?? '';
            $refence->from_email_id = $paymentData->emailfrom ?? '';
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
        $nextAutoID = !empty($iuids) ? $iuids->id + 1 : 1;

        $companyName = Company::where('id', $request->session()->get('seller'))->first();
        $cmpTypeName = Company::where('id', $request->session()->get('customer'))->first();

        if ($cmpTypeName && $cmpTypeName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $cmpTypeName->company_type)->first();
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
        $comboids->comboid = $combo_id;
        $comboids->payment_id = $payment_id;
        $comboids->iuid = $iuid;
        $comboids->ouid = 0;
        $comboids->system_module_id = 6;
        $comboids->general_ref_id = intval($ref_id);
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
        $comboids->subject = 'For '. $companyName->name .' RS '.$paymentData->totalamount .'/-';
        $comboids->financial_year_id = $financialid;
        $comboids->attachments = json_encode($attachments);
        $comboids->updated_by = Session::get('user')->employee_id;
        $comboids->inward_or_outward_flag = 0;
        $comboids->inward_or_outward_id = 0;
        $comboids->sale_bill_id = 0;
        $comboids->goods_return_id = 0;
        $comboids->commission_id = 0;
        $comboids->commission_invoice_id = 0;
        $comboids->is_invoice = 0;
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
        $comboids->is_completed = 0;
        $comboids->mark_as_draft = 0;
        $comboids->color_flag_id = 0;
        $comboids->product_qty = 0;
        $comboids->fabric_meters = 0;
        $comboids->sample_return_qty = 0;
        $comboids->mobile_flag = 0;
        $comboids->is_deleted = 0;
        $comboids->save();

        if ($paymentData->recipt_mode == 'cheque') {
            $cheque_date = $paymentData->reciptdate;
            $cheque_dd_no = $paymentData->chequeno;
            $cheque_dd_bank = $paymentData->chequebank->id;
        } else {
            $cheque_date = null;
            $cheque_dd_no = '';
            $cheque_dd_bank = 0;
        }

        if ($paymentData->recipt_mode == 'partreturn' || $paymentData->recipt_mode == 'part
        return') {
            $payment_tot_adjust_amount = 0;
        } else if ($paymentData->recipt_mode == 'fullreturn' || $paymentData->recipt_mode == 'full return') {
            $payment_tot_adjust_amount= $paymentData->totalamount;
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
        $payment->reference_id = intval($ref_id);
        $payment->attachments = json_encode($cheque_image);
        $payment->letter_attachment = $LetterImage;
        $payment->financial_year_id = $financialid;
        $payment->date = $payment_date;
        $payment->deposite_bank = 4;
        $payment->cheque_date = $cheque_date;
        $payment->cheque_dd_no = $cheque_dd_no;
        $payment->cheque_dd_bank = (int)$cheque_dd_bank;
        $payment->receipt_from = $request->session()->get('customer');
        $payment->trns = $paymentData->term;
        $payment->supplier_id = $request->session()->get('seller');
        $payment->customer_id = $request->session()->get('customer');
        $payment->receipt_amount = $paymentData->reciptamount ? $paymentData->reciptamount : 0;
        $payment->total_amount = $paymentData->totalamount;
        $payment->tot_adjust_amount = $payment_tot_adjust_amount;

        if ($paymentData->recipt_mode == 'partreturn' || $paymentData->recipt_mode == 'fullreturn' || $paymentData->recipt_mode == 'full return' || $paymentData->recipt_mode == 'part return') {
            $payment->tot_rate_difference = 0;
        } else {
            $payment->tot_rate_difference = $paymentData->ratedifference;
        }

        $payment->tot_discount = $paymentData->discountamount;
        $payment->tot_vatav = $paymentData->vatav;
        $payment->tot_agent_commission = $paymentData->agentcommission;
        $payment->tot_bank_cpmmission = $paymentData->bankcommission;
        $payment->tot_claim = $paymentData->claim;
        $payment->tot_good_returns = $paymentData->goodreturn;
        $payment->tot_short = $paymentData->short;
        $payment->tot_interest = $paymentData->interest;

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

                $lastid = PaymentDetail::orderBy('id', 'DESC')->first('id');
                $Id = !empty($lastid) ? $lastid->id + 1 : 1;
                if ($paymentData->recipt_mode == 'partreturn') {
                    $paymentDetail = new PaymentDetail();
                    $paymentDetail->id = $Id;
                    $paymentDetail->payment_id = $payment_id;
                    $paymentDetail->payment_details_id = $paymentDetailId;
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $salebill->fid;
                    $paymentDetail->sr_no = $salebill->id;
                    $paymentDetail->flag_sale_bill_sr_no = 1;
                    $paymentDetail->status = 1;
                    $paymentDetail->supplier_invoice_no = $salebill->sup_inv;
                    $paymentDetail->amount = $salebill->amount ?? 0;
                    $paymentDetail->adjust_amount = 0;
                    $paymentDetail->goods_return = $salebill->goodreturn ?? 0;
                    $paymentDetail->remark = $salebill->remark ?? 0;
                    $paymentDetail->rate_difference = 0;
                    $paymentDetail->save();

                    $bill = SaleBill::where('sale_bill_id', $salebill->id)->where('financial_year_id', $salebill->fid)->where('is_deleted', '0')->first();
                    $bill->payment_status = 0;
                    $bill->received_payment = (int)$bill->received_payment + (int)$salebill->goodreturn;
                    $bill->save();

                    $paymentDetail2 = PaymentDetail::where('sr_no', $salebill->id)->where('financial_year_id', $salebill->fid)->where('is_deleted', '0')->first();
                    $Pending = $paymentDetail2->total - $paymentDetail2->adjust_amount + $paymentDetail2->discount_amount + $paymentDetail2->vatav + $paymentDetail2->agent_commission + $paymentDetail2->bank_commission + $paymentDetail2->claim + $paymentDetail2->goods_return + $paymentDetail2->short - $paymentDetail2->interest;

                    $bill2 = SaleBill::where('sale_bill_id', $salebill->id)->where('financial_year_id', $salebill->fid)->where('is_deleted', '0')->first();
                    $bill2->pending_payment = $bill2->total - $bill2->received_payment;
                    $bill2->save();

                    $tot_good_returns += $salebill->goodreturn;
                } else if ($paymentData->recipt_mode == 'fullreturn' || $paymentData->recipt_mode == 'full return') {
                    $paymentDetail = new PaymentDetail();
                    $paymentDetail->id = $Id;
                    $paymentDetail->payment_id = $payment_id;
                    $paymentDetail->payment_details_id = $paymentDetailId;
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $salebill->fid;;
                    $paymentDetail->sr_no = $salebill->id;
                    $paymentDetail->status = 0;
                    $paymentDetail->flag_sale_bill_sr_no = 1;
                    $paymentDetail->supplier_invoice_no = $salebill->sup_inv;
                    $paymentDetail->amount = $salebill->amount ?? 0;
                    $paymentDetail->adjust_amount = $salebill->amount ?? 0;
                    $paymentDetail->goods_return = $salebill->goodreturn ?? 0;
                    $paymentDetail->remark = $salebill->remark ?? 0;
                    $paymentDetail->rate_difference = 0;
                    $paymentDetail->save();
                    $tot_discount += 0;
					$tot_vatav += 0;
					$tot_agent_commission += 0;
					$tot_bank_cpmmission += 0;
					$tot_claim += 0;
					$tot_good_returns += (int)$salebill->goodreturn;
					$tot_short += 0;
					$tot_interest += 0;
					$tot_rate_difference += 0;
					$tot_adjust_amount += 0;

                    $bill = SaleBill::where('sale_bill_id', $salebill->id)->where('financial_year_id', $salebill->fid)->first();
                    $bill->payment_status = 1;
                    $bill->received_payment = (int)$bill->received_payment + (int)$salebill->goodreturn;
                    $bill->save();

                    $bill2 = SaleBill::where('sale_bill_id', $salebill->id)->where('financial_year_id', $salebill->fid)->where('is_deleted', '0')->first();
                    $bill2->pending_payment = $bill2->total - $bill2->received_payment;
                    $bill2->save();

                } else {
                    $paymentDetail = new PaymentDetail();
                    $paymentDetail->id = $Id;
                    $paymentDetail->payment_details_id = $paymentDetailId;
                    $paymentDetail->payment_id = $payment_id;
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $salebill->fid;;
                    $paymentDetail->sr_no = $salebill->id;
                    $paymentDetail->flag_sale_bill_sr_no = 1;
                    $paymentDetail->status = $salebill->status->code;
                    $paymentDetail->supplier_invoice_no = $salebill->sup_inv;
                    $paymentDetail->discount = isset($salebill->discount) ? intval($salebill->discount) : 0;
                    $paymentDetail->discount_amount = isset($salebill->discountamount) ? intval($salebill->discountamount) : 0;
                    $paymentDetail->vatav = isset($salebill->vatav) ? intval($salebill->vatav) : 0;
                    $paymentDetail->agent_commission = isset($salebill->agentcommission) ? intval($salebill->agentcommission) : 0;
                    $paymentDetail->claim = isset($salebill->claim) ? intval($salebill->claim) : 0;
                    $paymentDetail->bank_commission = isset($salebill->bankcommission) ? intval($salebill->bankcommission) : 0;
                    $paymentDetail->short = isset($salebill->short) ? intval($salebill->short) : 0;
                    $paymentDetail->interest = isset($salebill->interest) ? intval($salebill->interest) : 0;
                    $paymentDetail->rate_difference = isset($salebill->ratedifference) ? intval($salebill->ratedifference) : 0;
                    $paymentDetail->amount = isset($salebill->amount) ? intval($salebill->amount) : 0;
                    $paymentDetail->adjust_amount = isset($salebill->adjustamount) ? intval($salebill->adjustamount) : 0;
                    $paymentDetail->goods_return = isset($salebill->goodreturn) ? intval($salebill->goodreturn) : 0;
                    $paymentDetail->remark = isset($salebill->remark) ? intval($salebill->remark) : 0;
                    $paymentDetail->save();

                    $vatav = isset($salebill->vatav) ? intval($salebill->vatav) : 0;
                    $agentcommission = isset($salebill->agentcommission) ? intval($salebill->agentcommission) : 0;
                    $claim = isset($salebill->claim) ? intval($salebill->claim) : 0;
                    $bankcommission = isset($salebill->bankcommission) ? intval($salebill->bankcommission) : 0;
                    $short = isset($salebill->short) ? intval($salebill->short) : 0;
                    $goodreturn = isset($salebill->goodreturn) ? intval($salebill->goodreturn) : 0;
                    $interest = isset($salebill->interest) ? intval($salebill->interest) : 0;

                    $bill = SaleBill::where('sale_bill_id', $salebill->id)->where('financial_year_id', $salebill->fid)->first();
                    $bill->payment_status = $salebill->status->code;
                    $bill->received_payment = (int)$bill->received_payment + (int)$salebill->adjustamount + (int)$salebill->discountamount + (int)$bankcommission + (int)$agentcommission + (int)$vatav + (int)$claim + (int)$short + (int)$goodreturn - (int)$interest;
                    $bill->save();

                    $paymentDetail2 = PaymentDetail::where('sr_no', $salebill->id)->where('financial_year_id', $salebill->fid)->where('is_deleted', '0')->first();
                    $Pending = (int)$bill->total - (int)$paymentDetail2->adjust_amount + (int)$paymentDetail2->discount_amount + (int)$paymentDetail2->vatav + (int)$paymentDetail2->agent_commission + (int)$paymentDetail2->bank_commission + (int)$paymentDetail2->claim + (int)$paymentDetail2->goods_return + $paymentDetail2->short - (int)$paymentDetail2->interest;
                    //print_r($Pending);exit;
                    $bill2 = SaleBill::where('sale_bill_id', $salebill->id)->where('financial_year_id', $salebill->fid)->where('is_deleted', 0)->first();

                    $bill2->pending_payment = $bill2->total - $bill2->received_payment;

                    $bill2->save();
                    $tot_adjust_amount += isset($salebill->adjustamount) ? (int)$salebill->adjustamount : 0;
                }
            }
        }
        if ($paymentData->recipt_mode != 'fullreturn' && $paymentData->recipt_mode == 'full return') {
            $tot_receipt_adjust_amt = (int)$paymentData->reciptamount - $tot_adjust_amount;
            if ($tot_receipt_adjust_amt != 0) {
                $payment_ok_or_not = 0;
            } else {
                $payment_ok_or_not = 1;
            }
        } else if($paymentData->recipt_mode != 'partreturn' && $paymentData->recipt_mode != 'part return') {
            $payment_ok_or_not = 0;
        } else {
            $payment_ok_or_not = 1;
        }

        $payment1 = Payment::where('payment_id', $payment_id)->first();
        $payment1->payment_ok_or_not = $payment_ok_or_not;
        $payment1->save();

        if ($paymentData->recipt_mode != 'fullreturn' && $paymentData->recipt_mode != 'full return') {
            if ($paymentData->goodreturn == 0) {
                $color_flag_id = 3;
            } else {
                $color_flag_id = 1;
            }
        } else if($paymentData->recipt_mode != 'partreturn' && $paymentData->recipt_mode != 'part return') {
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
        $request->session()->forget('customer');
        $request->session()->forget('seller');
        $request->session()->forget('saleBill');

        $redirect_url = '';
        if ($paymentData->goodreturn != 0) {
            $redirect_url = '/payments/add-goodreturn/'. $payment_id;
        }
        $data['redirect_url'] = $redirect_url;
        return $data;
    }

    public function getBasicData(Request $request)
    {
        $user = session()->get('user');
        if ($request->session()->has('saleBill')) {
            $customer_id = $request->session()->get('customer');
            $seller_id = $request->session()->get('seller');
            $salebill_ids = $request->session()->get('saleBill');
        }

        $customer = Company::where('id', $customer_id)->first();
        $seller = Company::where('id', $seller_id)->first();
        $salebill_data = array();
        $salebill_data2 = array();
        foreach ($salebill_ids as $ids) {
            $salebills = DB::table('sale_bills')
            ->where('company_id', $customer_id)
                ->where('supplier_id', $seller_id)
                ->where('financial_year_id', $ids['fid'])
                ->where('sale_bill_id', $ids['id'])
                ->where('payment_status', 0)
                ->first();
            $salebills2 = DB::table('sale_bills')
            ->where('company_id', $customer_id)
                ->where('supplier_id', $seller_id)
                ->where('financial_year_id', $ids['fid'])
                ->whereNot('sale_bill_id', $ids['id'])
                ->orderBy('sale_bill_id', 'desc')
                ->first();

            $status_c = new \stdClass;
            $status_c->code = 1;
            $status_c->status = 'Complete';
            $overdue = floor((time() - strtotime($salebills->select_date)) / (60 * 60 * 24));
            if ($salebills->pending_payment != 0) {
                $salebill = [
                    'id' => $salebills->sale_bill_id,
                    'fid' => $ids['fid'],
                    'sup_inv' => $salebills->supplier_invoice_no,
                    'amount' => $salebills->pending_payment,
                    'adjustamount' => $salebills->pending_payment,
                    'status' => $status_c,
                    'overdue' => $overdue,
                    'discount' => 0,
                    'discountamount' => 0,
                    'goodreturn' => 0,
                    'ratedifference' => 0,
                    'bankcommission' => 0,
                    'vatav' => 0,
                    'agentcommission' => 0,
                    'claim' => 0,
                    'short' => 0,
                    'interest' => 0,
                    'remark' => ''
                ];
            } else {
                $salebill = [
                    'id' => $salebills->sale_bill_id,
                    'fid' => $ids['fid'],
                    'sup_inv' => $salebills->supplier_invoice_no,
                    'amount' => $salebills->total,
                    'adjustamount' => $salebills->total,
                    'status' => $status_c,
                    'overdue' => $overdue,
                    'discount' => 0,
                    'discountamount' => 0,
                    'goodreturn' => 0,
                    'ratedifference' => 0,
                    'bankcommission' => 0,
                    'vatav' => 0,
                    'agentcommission' => 0,
                    'claim' => 0,
                    'short' => 0,
                    'interest' => 0,
                    'remark' => ''
                ];
            }
            array_push($salebill_data, $salebill);

            if ($salebills2) {
                $financialyear = FinancialYear::where('id', $ids['fid'])->first();
                $overdue = floor((time() - strtotime($salebills2->select_date)) / (60 * 60 * 24));
                $salebill2 = array('sallbillid' => $salebills2->sale_bill_id, 'financialyear' => $financialyear, 'invoiceid' => $salebills2->supplier_invoice_no, 'date' => $salebills2->select_date, 'supplier' => $seller->company_name, 'amount' => $salebills2->total, 'overdue' => $overdue);
                array_push($salebill_data2, $salebill2);
            }
        }
        usort($salebill_data, function ($a, $b) {
            if ($a['overdue'] == $b['overdue']) {
                return 0;
            }
            return ($a['overdue'] > $b['overdue']) ? -1 : 1;
        });
        $courier = TransportDetails::where('is_delete', 0)->get();
        $data['customer'] = $customer;
        $data['seller'] = $seller;
        $data['courier'] = $courier;
        $data['salebill'] = $salebill_data;
        $data['salebilldata'] = $salebill_data2;
        $data['financial_year_start_date'] = session()->get('user')->financial_year_start_date;
        $data['financial_year_end_date'] = session()->get('user')->financial_year_end_date;
        return $data;
    }

    public function addPayment(Request $request){
        $page_title = 'Add Payments';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('payment.addPayment',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function removeSalebill(Request $request) {
        $user = session()->get('user');
        if ($request->session()->has('saleBill')) {
            $customer_id = $request->session()->get('customer');
            $seller_id = $request->session()->get('seller');
        } else {
            $payment = Payment::where('payment_id', $request->payment_id)->where('financial_year_id', $user->financial_year_id)->first();
            $customer_id = $payment->receipt_from;
            $seller_id = $payment->supplier_id;
            $salebill_ids = PaymentDetail::select('sr_no')->where('payment_id', $payment->payment_id)->where('financial_year_id', $user->financial_year_id)->pluck('sr_no')->toArray();
        }
        $financialyear = FinancialYear::where('id', $user->financial_year_id)->first();
        $customer = Company::where('id', $customer_id)->first();
        $seller = Company::where('id', $seller_id)->first();
        $salebillid = $request->salebill;
        $salebill_ids = $request->session()->get('saleBill');
        $key = array_search($salebillid[0]['id'] , array_column($salebill_ids, 'id'));

        unset($salebill_ids[$key]);

        $request->session()->forget('saleBill');
        $request->session()->put('saleBill', $salebill_ids);
        $salebill_data2 = array();
        $sid = array();
        foreach ($salebill_ids as $ids) {
            array_push($sid, $ids['id']);
        }

        $salebills2 = DB::table('sale_bills')
                        ->where('company_id', $customer_id)
                        ->where('supplier_id', $seller_id)
                        ->where('payment_status', 0)
                        ->where('is_deleted', 0)
                        ->whereNotIn('sale_bill_id', $sid)
                        ->orderBy('sale_bill_id', 'desc')
                        ->get();
        foreach($salebills2 as $bills){
            $financialyear = FinancialYear::where('id', $bills->financial_year_id)->first();
            $overdue = floor((time() - strtotime($bills->select_date)) / (60 * 60 * 24));
            $salebill2 = array('sallbillid' => $bills->sale_bill_id, 'financialyear' => $financialyear, 'invoiceid' => $bills->supplier_invoice_no, 'date'=> $bills->select_date, 'supplier' => $seller->company_name, 'amount' => $bills->total, 'overdue' => $overdue);
            array_push($salebill_data2, $salebill2);
        }
        $data['customer'] = $customer;
        $data['seller'] = $seller;
        $data['salebilldata'] = $salebill_data2;
        return $data;

    }

    public function getSalbillforAdd(Request $request) {
        $user = session()->get('user');
        $sid = array();
        if ($request->session()->has('saleBill') && empty($request->payment_id)) {
            $customer_id = $request->session()->get('customer');
            $seller_id = $request->session()->get('seller');
            $salebill_ids = $request->session()->get('saleBill');

            foreach ($salebill_ids as $ids) {
                array_push($sid, $ids['id']);
            }
        } else {
            $payment = Payment::where('payment_id', $request->payment_id)->where('financial_year_id', $user->financial_year_id)->first();
            $customer_id = $payment->receipt_from;
            $seller_id = $payment->supplier_id;
            $sid = PaymentDetail::select('sr_no')->where('payment_id', $payment->payment_id)->where('financial_year_id', $user->financial_year_id)->pluck('sr_no')->toArray();
        }

        $customer = Company::where('id', $customer_id)->first();
        $seller = Company::where('id', $seller_id)->first();
        $salebill_data2 = array();


        $salebills2 = DB::table('sale_bills')
                        ->where('company_id', $customer_id)
                        ->where('supplier_id', $seller_id)
                        ->where('payment_status', 0)
                        ->where('is_deleted', 0)
                        ->whereNotIn('sale_bill_id', $sid)
                        ->orderBy('sale_bill_id', 'desc')
                        ->get();
        foreach($salebills2 as $bills){
            if ($bills->received_payment != 0){
                $pendingpayment = $bills->total - $bills->received_payment;
            } else {
                $pendingpayment = $bills->total;
            }
            $bill_date = date('d-m-Y', strtotime($bills->select_date));
            $financialyear = FinancialYear::where('id', $bills->financial_year_id)->first();
            $overdue = floor((time() - strtotime($bills->select_date)) / (60 * 60 * 24));
            $salebill2 = array('sallbillid' => $bills->sale_bill_id, 'financialyear' => $financialyear, 'invoiceid' => $bills->supplier_invoice_no, 'date'=> $bill_date, 'supplier' => $seller->company_name,'pending' => $pendingpayment, 'amount' => $bills->total, 'overdue' => $overdue);
            array_push($salebill_data2, $salebill2);
        }
        usort($salebill_data2, function($a, $b) {
            if ($a['overdue'] == $b['overdue']) {
              return 0;
            }
            return ($a['overdue'] > $b['overdue']) ? -1 : 1;
        });
        $data['customer'] = $customer;
        $data['seller'] = $seller;
        $data['salebilldata'] = $salebill_data2;
        return $data;
    }

    public function getReferenceForSaleBill(Request $request)
    {
        $customer_id = $request->session()->get('customer');
        $general_ref = DB::table('reference_ids as r')
            ->join('companies as c', 'r.company_id', '=', 'c.id')
            ->select('r.employee_id', 'r.reference_id', 'r.created_at')
            ->where('r.financial_year_id', Session::get('user')->financial_year_id)
            ->where('r.type_of_inward', $request->ref_via)
            ->where('r.company_id', $customer_id)
            ->where('r.inward_or_outward', 1)
            ->whereRaw("(r.employee_id = " . Session::get('user')->employee_id . " or r.employee_id = 15)")
            ->orderBy('r.reference_id', 'desc')
            ->limit(4)
            ->get();
        $html = '';
        if (count($general_ref)) {
            $html .= '<div class="form-group row"><label class="col-sm-2 control-label"></label><div style="z-index:0" class="col-sm-8"><div class="table-responsive"><table class="table"><thead><tr><th></th><th>Ref. No</th><th>Generated By</th><th>Date</th><th>Time</th></tr></thead><tbody>';
            foreach ($general_ref as $row_general_ref) {
                if (Session::get('user')->employee_id == $row_general_ref->employee_id) {
                    $empName = "Me";
                } else {
                    $empName = "Rec.";
                }
                $html .= '<tr><td><div class="custom-control custom-radio"><input class="custom-control-input old-reference" type="radio" name="reference_id_sale_bill" value="' . $row_general_ref->reference_id . '" id="r-' . $row_general_ref->reference_id . '"><label class="custom-control-label" for="r-'.$row_general_ref->reference_id.'"></label></div></td><td>' . $row_general_ref->reference_id . '</td><td>' . $empName . '</td><td>' . date('d-m-Y', strtotime($row_general_ref->created_at)) . '</td><td>' . date('H:i A', strtotime($row_general_ref->created_at)) . '</td></tr>';
            }
            $html .= '<tr><td colspan="5"><div class="input-group"><input type="text" class="form-control" name="sale_bill_ref_search" id="sale_bill_ref_search" placeholder="Enter Reference Number"><span class="input-group-btn"><button type="button" class="btn btn-primary" id="sale_bill_ref_search_btn">Go</button></span></div></td></tr><tr id="sale_bill_ref_msg"></tr>';
            $html .= '</tbody></table></div></div><label class="col-sm-2 control-label"></label></div>';
        } else {
            $html .= '<div class="form-group row"><label class="col-sm-2 control-label"></label><div class="col-sm-8"><div class="table-responsive"><table class="table"><tbody><tr><td>No Refernce found</td></tr></tbody></table></div></div>';
        }
        return $html;
    }

    public function getOldReferenceForSaleBill(Request $request, $id)
    {
        $html = "";
        $customer_id = $request->session()->get('customer');
        $reference = DB::table('reference_ids as r')
            ->join('companies as c', 'r.company_id', '=', 'c.id')
            ->select('r.employee_id', 'r.reference_id', 'r.created_at', 'r.company_id', 'r.selection_date', 'r.type_of_inward', 'r.from_name', 'r.from_number', 'r.receiver_number', 'r.from_email_id', 'r.receiver_email_id', 'r.latter_by_id', 'r.courier_name', 'r.weight_of_parcel', 'r.courier_receipt_no', 'r.courier_received_time', 'r.delivery_by', 'c.company_name')
            ->where('r.reference_id', $id)
            ->where('r.company_id', $customer_id)
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
                $html .= "<input type='hidden' id='hidden_sale_bill_date' value='" . date('d-m-Y', strtotime($reference->selection_date)) . "'><input type='hidden' id='hidden_reference_via' value='" . $reference->type_of_inward . "'><input type='hidden' id='hidden_from_name' value='" . $reference->from_name . "'><input type='hidden' id='hidden_from_number' value='" . $reference->from_number . "'><input type='hidden' id='hidden_receiver_number' value='" . $reference->receiver_number . "'><input type='hidden' id='hidden_from_email_id' value='" . $reference->from_email_id . "'><input type='hidden' id='hidden_receiver_email_id' value='" . $reference->receiver_email_id . "'><input type='hidden' id='hidden_latter_by_id' value='" . $reference->latter_by_id . "'><input type='hidden' name='hidden_courier_name' id='hidden_courier_name' value='" . $reference->courier_name . "'><input type='hidden' id='hidden_weight_of_parcel' value='" . $reference->weight_of_parcel . "'><input type='hidden' id='hidden_courier_receipt_no' value='" . $reference->courier_receipt_no . "'><input type='hidden' id='hidden_courier_received_time' value='" . date('d-m-Y', strtotime($reference->courier_received_time)) . "'><input type='hidden' id='hidden_delivery_by' value='" . $reference->delivery_by . "'><input type='hidden' name='hidden_cmp_id' id='hidden_cmp_id' value='" . $reference->company_id . "'><input type='hidden' name='hidden_cmp_name' id='hidden_cmp_name' value='" . $reference->company_name . "'><input type='hidden' id='hidden_reference_id_input' name='hidden_reference_id_input' value='" . $reference->reference_id . "'><input type='hidden' id='hidden_ref_emp_name' name='hidden_ref_emp_name' value='" . $empName . "'><input type='hidden' id='hidden_ref_date_added' name='hidden_ref_date_added' value='" . date('d-m-Y', strtotime($reference->created_at)) . "'><input type='hidden' id='hidden_ref_time_added' name='hidden_ref_time_added' value='" . date('h:i A', strtotime($reference->created_at)) . "'>";
            }
        }
        return $html;
    }

    public function editPayment($id) {
        $page_title = 'Update Payment';
        $financialYear = FinancialYear::where('id',$id);
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('payment.editPayment',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function fetchPayment(Request $request, $id) {

        $user = Session::get('user');
        if ($request->fid != 0 && $request->fid != $user->financial_year_id) {
            $user->financial_year_id = $request->fid;
        }
        $payment = Payment::where('payment_id', $id)->where('financial_year_id', $user->financial_year_id)->first();
        $customer = Company::where('id', $payment->receipt_from)->first();
        $supplier = Company::where('id', $payment->supplier_id)->first();
        $paymentDetail = PaymentDetail::where('p_increment_id', $payment->id)->get();
        $generated_by = DB::table('comboids as c')
            ->join('employees as e', 'c.generated_by', '=', 'e.id')
            ->select('e.firstname', 'e.lastname', 'c.created_at', 'c.updated_at', 'c.updated_by')
            ->where('c.iuid', $payment->iuid)
            ->where('c.financial_year_id', $user->financial_year_id)
            ->where('c.is_deleted', 0)
            ->first();
        /* $updated_by = DB::table('employees')
            ->select('id', 'firstname', 'lastname', 'email_id', 'mobile')
            ->where('id', $generated_by->updated_by ?? 0)
            ->first(); */
        $attch = explode(',', trim(trim($payment->attachments,'"[\"'), '\"]"'));
        $itmdata = array();
        foreach ($attch as $itm) {
            $item = trim(trim($itm,'"'), '\"');
            array_push($itmdata, $item);
        }
        $letter_attch = explode(',', trim(trim($payment->letter_attachment,'"[\"'), '\"]"'));
        $itmdata2 = array();
        foreach ($letter_attch as $itm) {
            $item = trim(trim($itm,'"'), '\"');
            array_push($itmdata2, $item);
        }
        $salebill = array();
        foreach ($paymentDetail as $details) {
            $bill = SaleBill::where('sale_bill_id', $details->sr_no)
                    ->where('financial_year_id', $details->financial_year_id)
                    ->first();
            $overdue = floor((strtotime($payment->date) - strtotime($bill->select_date)) / (60 * 60 * 24));
            $bill_date = date('d-m-Y', strtotime($bill->select_date));
            $status = array("status" => 'Pending', "code" => 0);
            if ($details->status == 1) {
                $status = array("status" => 'Complete', "code" => 1);
            }
            if ($details->goods_return != 0) {
                $goodreturn = GoodsReturn::where('p_increment_id', $details->p_increment_id)->where('sale_bill_id', $details->sr_no)->first();
                $goodreturndata = $goodreturn;
            } else {
                $goodreturndata = array();
            }
            $salebilldata = array('id'=> $details->sr_no, 'fid'=> $details->financial_year_id, 'sup_inv' => $details->supplier_invoice_no,
                        'amount' => $details->amount, 'adjustamount' => $details->adjust_amount, 'status' => $status,
                        'discount' => $details->discount, 'discountamount' => $details->discount_amount,
                        'goodreturn' => $details->goods_return, 'ratedifference' => $details->rate_difference,
                        'bankcommission' => $details->bank_commission, 'vatav' => $details->vatav, 'agentcommission' => $details->agent_commission,
                        'claim' => $details->claim, 'short' => $details->short, 'interest'=> $details->interest, 'remark' => $details->remark);
            $salebilldata['goodreturndata'] = $goodreturndata;
            $salebilldata['bill_date'] = $bill_date;
            $salebilldata['day'] = $overdue;
            array_push($salebill, $salebilldata);
        }

        $data['paymentData'] = $payment;
        $data['paymentData']['cheque_image'] = $itmdata;
        $data['paymentData']['letter_image'] = $itmdata2;
        $data['paymentData']['generated_by'] = $generated_by;
        $data['paymentData']['generated_at'] = $generated_by ? date('d-m-Y H:i A', strtotime($generated_by->created_at)) : '';
        $data['paymentData']['updated'] = $generated_by ? date('d-m-Y H:i A', strtotime($generated_by->updated_at)) : '';
        $data['created_at'] = date_format($payment->created_at,"Y/m/d H:i:s");
        $data['salebill'] = $salebill;
        $data['customer'] = $customer;
        $data['supplier'] = $supplier;
        return $data;
    }

    public function fetchGoodReturn($id) {
        $user = session()->get('user');
        $goodReturn = GoodsReturn::where('goods_return_id', $id)->where('financial_year_id', $user->financial_year_id)->first();
        $goodReturnItem = GrSaleBillItem::where('gr_increment_id', $goodReturn->id)->get();
        $grItemData = array();

        foreach ($goodReturnItem as $Item){

            $product_name = DB::table('products')->where('id', $Item->product_or_fabric_id)->first();
                if ($product_name) {
                    $productName = $product_name->product_name;
                } else {
                    $productName = $Item->product_or_fabric_id;
                }
            $product = DB::table('products')->where('id', $Item->product_or_fabric_id)->first();
            array_push($grItemData,array("id" => $Item->id, "product_or_fabric_id" => $Item->product_or_fabric_id, "name" => $productName, "meter" => $Item->meters, "pieces" => $Item->peices, "pieces_meter" => $Item->peices_meters, "rate" => $Item->rate, "amount" => ''));
        }
        $data = $goodReturn;
        $data['products'] = $grItemData;

        return $data;
    }

    public function getGoodReturnView($id) {
        $user = session()->get('user');
        $goodReturn = GoodsReturn::where('goods_return_id', $id)->where('financial_year_id', $user->financial_year_id)->first();
        $goodReturnItem = GrSaleBillItem::where('gr_increment_id', $goodReturn->id)->get();
        $customer = Company::where('id', $goodReturn->company_id)->first();
        $supplier = Company::where('id', $goodReturn->supplier_id)->first();
        $grItemData = array();
        $attch = explode(',', trim(trim($goodReturn->multiple_attachment,'"[\"'), '\"]"'));
        $itmdata = array();
        foreach ($attch as $itm) {
            $item = trim(trim($itm,'"'), '\"');
            array_push($itmdata, $item);
        }
        foreach ($goodReturnItem as $Item){

            $product_name = DB::table('products')->where('id', $Item->product_or_fabric_id)->first();
                if ($product_name) {
                    $productName = $product_name->product_name;
                } else {
                    $productName = $Item->product_or_fabric_id;
                }
            $Item['name'] = $productName;
            array_push($grItemData, $Item);
        }
        $data['goodreturn'] = $goodReturn;
        $data['goodreturn']['attachment'] = $itmdata;
        $data['item'] = $grItemData;
        $data['customer'] = $customer;
        $data['supplier'] = $supplier;
        return $data;
    }

    public function updatePaymentData(Request $request){
        $attachments = array();
        $user = Session::get('user');
        $financialid = Session::get('user')->financial_year_id;
        $paymentData = json_decode($request->formdata);
        $paymentSalebill = json_decode($request->billdata);
        $cheque_image = array();
        if (!file_exists(public_path('upload/payments'))) {
            mkdir(public_path('upload/payments'), 0777, true);
        }
        $payment = Payment::where('payment_id', $paymentData->id)->where('financial_year_id', $financialid)->first();
        $p_increment_id = $payment->id;
        //$ChequeImage = $LetterImage = null;
        if ($request->chequeimage) {
            foreach($request->chequeimage as $key => $image) {
                $ChequeImage = date('YmdHis') . "_chequeImage".$key."." . $image->getClientOriginalExtension();
                $paymentData->chequeImage = $ChequeImage;
                $image->move(public_path('upload/payments/'), $ChequeImage);
                array_push($cheque_image, $ChequeImage);
            }
            $payment->attachments = json_encode($cheque_image);
        }

        if ($image = $request->letterimage) {
            $LetterImage = date('YmdHis') . "_letterImage." . $image->getClientOriginalExtension();
            $payment->letter_attachment = $LetterImage;
            $image->move(public_path('upload/payments/'), $LetterImage);
            array_push($attachments, $LetterImage);
        }
        $payment2 = Payment::where('payment_id', $paymentData->id)->where('financial_year_id', $financialid)->first();

        $cmpTypeName = Company::where('id', $payment2->receipt_from)->first();
        $companyName = Company::where('id', $payment2->supplier_id)->first();

        if ($cmpTypeName && $cmpTypeName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $cmpTypeName->company_type)->first();
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }

        $combo = Comboids::where('payment_id', $paymentData->id)->first();
        $personName = '';
        $ref_id = $paymentData->refrence_type;
        $comboids = Comboids::where('iuid', $combo->iuid)->first();
        $comboids->general_ref_id = $ref_id;
        $comboids->generated_by = $user->employee_id;
        $comboids->assigned_to = $user->employee_id;
        $comboids->company_id = $cmpTypeName->id;
        $comboids->supplier_id = $companyName->id;
        $comboids->company_type = $typeName;
        $comboids->followup_via = 'Payment';
        $comboids->selection_date = $paymentData->reciptdate;
        $comboids->from_name = $personName;
        $comboids->receipt_mode = $paymentData->recipt_mode;
        $comboids->receipt_amount = (int)$paymentData->reciptamount;
        $comboids->total = $paymentData->totalamount;
        $comboids->subject = 'For '. $companyName->name .' RS '.$paymentData->totalamount .'/-';
        $comboids->attachments = json_encode($attachments);
        $comboids->updated_by = Session::get('user')->employee_id;
        $comboids->inward_or_outward_flag = 0;
        $comboids->inward_or_outward_id = 0;
        $comboids->sale_bill_id = 0;
        $comboids->goods_return_id = 0;
        $comboids->commission_id = 0;
        $comboids->commission_invoice_id = 0;
        $comboids->is_invoice = 0;
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
        $comboids->is_completed = 0;
        $comboids->mark_as_draft = 0;
        $comboids->color_flag_id = 0;
        $comboids->product_qty = 0;
        $comboids->fabric_meters = 0;
        $comboids->sample_return_qty = 0;
        $comboids->mobile_flag = 0;
        $comboids->is_deleted = 0;
        $comboids->save();

        if ($paymentData->recipt_mode == 'cheque') {
            $cheque_date = $paymentData->reciptdate;
            $cheque_dd_no = $paymentData->chequeno;
            $cheque_dd_bank = $paymentData->chequebank->id ?? 0;
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
        $payment_date = $paymentData->reciptdate;

        $payment->reciept_mode = $paymentData->recipt_mode;
        $payment->date = $payment_date;
        $payment->deposite_bank = 4;
        $payment->cheque_date = $cheque_date;
        $payment->cheque_dd_no = $cheque_dd_no;
        $payment->cheque_dd_bank = (int)$cheque_dd_bank;
        $payment->receipt_from = $cmpTypeName->id;
        $payment->trns = $paymentData->term;
        $payment->supplier_id = $companyName->id;
        $payment->customer_id = $cmpTypeName->id;
        $payment->receipt_amount = $paymentData->reciptamount ?? 0;
        $payment->total_amount = $paymentData->totalamount ?? 0;
        $payment->tot_adjust_amount = $payment_tot_adjust_amount ?? 0;

        if ($paymentData->recipt_mode == 'partreturn' || $paymentData->recipt_mode == 'fullreturn' || $paymentData->recipt_mode == 'full return' || $paymentData->recipt_mode == 'part return') {
            $payment->tot_rate_difference = 0;
        } else {
            $payment->tot_rate_difference = $paymentData->ratedifference;
        }
        $payment->tot_discount = $paymentData->discountamount;
        $payment->tot_vatav = $paymentData->vatav;
        $payment->tot_agent_commission = $paymentData->agentcommission;
        $payment->tot_bank_cpmmission = $paymentData->bankcommission;
        $payment->tot_claim = $paymentData->claim;
        $payment->tot_good_returns = $paymentData->goodreturn;
        $payment->tot_short = $paymentData->short;
        $payment->tot_interest = $paymentData->interest;
        $payment->save();

        $paymentsalebilldata = PaymentDetail::where('p_increment_id', $p_increment_id)->get();
        foreach ($paymentsalebilldata as $salebilldata) {
            $pd = PaymentDetail::where('id', $salebilldata->id)->where('is_deleted', 0)->first();
            $recive_payment =(int)$pd->adjust_amount + (int)$pd->discount_amount + (int)$pd->vatav + (int)$pd->agent_commission + (int)$pd->bank_commission + (int)$pd->claim + (int)$pd->goods_return + $pd->short - (int)$pd->interest;

            $salebill = SaleBill::where('sale_bill_id', $salebilldata->sr_no)->where('financial_year_id', $salebilldata->financial_year_id)->where('is_deleted', 0)->first();
            $salebill->received_payment = $salebill->received_payment - $recive_payment;
            $salebill->pending_payment = $salebill->pending_payment + $recive_payment;
            $salebill->save();
        }
        PaymentDetail::where('p_increment_id', $p_increment_id)->delete();

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
                if ($paymentData->recipt_mode == 'partreturn' || $paymentData->recipt_mode == 'part return') {
                    $paymentDetail = new PaymentDetail();
                    $paymentDetail->payment_id = $paymentData->id;
                    $paymentDetail->payment_details_id = $paymentDetailId;
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $salebill->fid;
                    $paymentDetail->sr_no = $salebill->id;
                    $paymentDetail->flag_sale_bill_sr_no = 1;
                    $paymentDetail->status = 1;
                    $paymentDetail->supplier_invoice_no = $salebill->sup_inv;
                    $paymentDetail->amount = $salebill->amount ?? 0;
                    $paymentDetail->adjust_amount = 0;
                    $paymentDetail->goods_return = $salebill->goodreturn ?? 0;
                    $paymentDetail->remark = $salebill->remark ?? 0;
                    $paymentDetail->save();

                    $bill = SaleBill::where('sale_bill_id', $salebill->id)->where('financial_year_id', $salebill->fid)->where('is_deleted', '0')->first();
                    $bill->payment_status = 0;
                    $bill->received_payment = (int)$bill->received_payment + (int)$salebill->goodreturn;
                    $bill->save();

                    $paymentDetail2 = PaymentDetail::where('sr_no', $salebill->id)->where('financial_year_id', $salebill->fid)->where('is_deleted', '0')->first();
                    $Pending = $paymentDetail2->total - $paymentDetail2->adjust_amount + $paymentDetail2->discount_amount + $paymentDetail2->vatav + $paymentDetail2->agent_commission + $paymentDetail2->bank_commission + $paymentDetail2->claim + $paymentDetail2->goods_return + $paymentDetail2->short - $paymentDetail2->interest;

                    $bill2 = SaleBill::where('sale_bill_id', $salebill->id)->where('financial_year_id', $salebill->fid)->where('is_deleted', '0')->first();
                    $bill2->pending_payment = $bill2->total - $bill2->received_payment;
                    $bill2->save();

                    $tot_good_returns += $salebill->goodreturn;
                } else if ($paymentData->recipt_mode == 'fullreturn' || $paymentData->recipt_mode == 'full return') {
                    $paymentDetail = new PaymentDetail();
                    $paymentDetail->payment_id = $paymentData->id;
                    $paymentDetail->payment_details_id = $paymentDetailId;
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $salebill->fid;
                    $paymentDetail->sr_no = $salebill->id;
                    $paymentDetail->status = 0;
                    $paymentDetail->flag_sale_bill_sr_no = 1;
                    $paymentDetail->supplier_invoice_no = $salebill->sup_inv;
                    $paymentDetail->amount = $salebill->amount ?? 0;
                    $paymentDetail->goods_return = $salebill->goodreturn ?? 0;
                    $paymentDetail->remark = $salebill->remark ?? 0;
                    $paymentDetail->save();

                    $bill = SaleBill::where('sale_bill_id', $salebill->id)->where('financial_year_id', $salebill->fid)->first(1);
                    $bill->payment_status = 1;
                    $bill->received_payment = (int)$bill->received_payment + (int)$salebill->goodreturn;
                    $bill->save();

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
                    $paymentDetail->id = (getLastID('payment_details', 'id') + 1);
                    $paymentDetail->payment_details_id = $paymentDetailId;
                    $paymentDetail->payment_id = $paymentData->id;
                    $paymentDetail->p_increment_id = $p_increment_id;
                    $paymentDetail->financial_year_id = $salebill->fid;
                    $paymentDetail->sr_no = $salebill->id;
                    $paymentDetail->flag_sale_bill_sr_no = 1;
                    $paymentDetail->status = $salebill->status->code;
                    $paymentDetail->supplier_invoice_no = $salebill->sup_inv;
                    $paymentDetail->discount = $salebill->discount ?? 0;
                    $paymentDetail->discount_amount = $salebill->discountamount ?? 0;
                    $paymentDetail->vatav = $salebill->vatav ?? 0;
                    $paymentDetail->agent_commission = $salebill->agentcommission ?? 0;
                    $paymentDetail->claim = $salebill->claim ?? 0;
                    $paymentDetail->bank_commission = $salebill->bankcommission ?? 0;
                    $paymentDetail->short = $salebill->short ?? 0;
                    $paymentDetail->interest = $salebill->interest ?? 0;
                    $paymentDetail->rate_difference = $salebill->ratedifference ?? 0;
                    $paymentDetail->amount = $salebill->amount ?? 0;
                    $paymentDetail->adjust_amount = $salebill->adjustamount ?? 0;
                    $paymentDetail->goods_return = $salebill->goodreturn ?? 0;
                    $paymentDetail->remark = $salebill->remark ?? 0;
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

                    $bill = SaleBill::where('sale_bill_id', $salebill->id)->where('financial_year_id', $salebill->fid)->first();

                    $paymentDetail2 = PaymentDetail::where('sr_no', $salebill->id)->where('financial_year_id',$salebill->fid)->where('is_deleted', '0')->get();

                    $receive_amount = 0;
                    foreach ($paymentDetail2 as $pdetai){
                        $totalamount = (int)$pdetai->adjust_amount + (int)$pdetai->discount_amount + (int)$pdetai->vatav + (int)$pdetai->agent_commission + (int)$pdetai->bank_commission + (int)$pdetai->claim + (int)$pdetai->goods_return + $pdetai->short - (int)$pdetai->interest;
                        $receive_amount += $totalamount;
                    }
                    $bill->payment_status = $salebill->status->code;
                    $bill->received_payment = (int)$receive_amount;
                    $bill->save();

                    $bill2 = SaleBill::where('sale_bill_id', $salebill->id)->where('financial_year_id', $salebill->fid)->where('is_deleted', 0)->first();
                    $bill2->pending_payment = $bill2->total - $bill2->received_payment;
                    $bill2->save();
                }
            }
        }
        if ($paymentData->recipt_mode == 'fullreturn' || $paymentData->recipt_mode == 'full return') {
            $tot_receipt_adjust_amt = (int)$paymentData->reciptamount - $tot_adjust_amount;
            if ($tot_receipt_adjust_amt != 0) {
                $payment_ok_or_not = 0;
            } else {
                $payment_ok_or_not = 1;
            }
        } else if($paymentData->recipt_mode == 'partreturn' || $paymentData->recipt_mode == 'part return') {
            $payment_ok_or_not = 0;
        } else {
            $payment_ok_or_not = 1;
        }

        $payment1 = Payment::where('payment_id', $paymentData->id)->first();

        $payment1->payment_ok_or_not = $payment_ok_or_not;

        $payment1->save();

        if ($paymentData->recipt_mode == 'fullreturn' || $paymentData->recipt_mode == 'full return') {
            if ($tot_good_returns != 0) {
                $color_flag_id = 3;
            } else {
                $color_flag_id = 1;
            }
        } else if($paymentData->recipt_mode == 'partreturn' || $paymentData->recipt_mode == 'part return') {
            $color_flag_id = 1;
        } else {
            $color_flag_id = 3;
        }

        $comboid1 = Comboids::where('payment_id', $paymentData->id)->first();
        $comboid1->color_flag_id = $color_flag_id;
        $comboid1->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'payment / update';
        $logs->log_subject = 'Payment update page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
        $redirect_url = '';
    }

    public function addGoodRetuen(Request $request) {
        $page_title = 'Add Good Return';
        $p_id = $request->id;
        $request->session()->put('p_id', $p_id);
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('payment.addgoodreturn',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function editGoodRetuen($id) {
        $page_title = 'Edit Good Return';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['id'] = $id;
        return view('payment.editgoodreturn',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function getSalebillWithProduct(Request $request) {
        $p_id = $request->session()->get('p_id');
        $data = array();
        $user = Session::get('user');
        $payment = Payment::where('payment_id', $p_id)->where('financial_year_id', $user->financial_year_id)->first();
        $salebill = PaymentDetail::where('p_increment_id', $payment->id)->get();
        foreach($salebill as $bill) {
            $product_list = array();
            $product = DB::table('sale_bill_items')
            ->where('sale_bill_items.sale_bill_id', $bill->sr_no)
            ->where('sale_bill_items.financial_year_id', $bill->financial_year_id)
            ->get();
            foreach($product as $p){
                $product_name = DB::table('products')->where('id', $p->product_or_fabric_id)->first();
                if ($product_name) {
                    $productName = $product_name->product_name;
                } else {
                    $productName = $p->product_or_fabric_id;
                }
                $product = array('id' => $p->id, 'meter' => $p->meters, 'pieces_meter' => $p->pieces_meters ,'product_or_fabric_id' => $p->product_or_fabric_id, 'pieces'=> $p->pieces, 'name' => $productName, 'rate' =>$p->rate, 'amount' => $p->amount);
                array_push($product_list, $product);
            }
            $data1 = array('sr_no' => $bill->sr_no, 'supplier_invoice_no' => $bill->supplier_invoice_no, 'amount' => $bill->amount, 'goods_return' => $bill->goods_return);
            $data1['products'] = $product_list;
            array_push($data, $data1);
        }
        return $data;
    }

    public function insertGoodRetuen(Request $request) {
        $grattechment = $request->grattechment;
        $user = Session::get('user');
        $salebilldata = json_decode($request->salebills);
        $attachments = array();
        if ($grattechment) {
            foreach ($grattechment as $key => $attechment) {
                        $attechmentImage = '';
                        $attechmentImage = rand() . "_grattechment." . $attechment->getClientOriginalExtension();
                        $attechment->move(public_path('upload/goodreturn/'), $attechmentImage);
                        $attachments[$key] = $attechmentImage;
            }
        }



        $pid = $request->session()->get('p_id');
        $payment = Payment::where('payment_id', $pid)->where('financial_year_id', $user->financial_year_id)->first();
        $reference = ReferenceId::where('reference_id', $payment->reference_id)->first();
        $companyName = Company::where('id', $payment->supplier_id)->first();
        $cmpTypeName = Company::where('id', $payment->customer_id)->first();
        $financialid = $payment->financial_year_id;
        if ($cmpTypeName && $cmpTypeName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $cmpTypeName->company_type)->first();
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }
        $personName = '';
        foreach ($salebilldata as $key => $salebill) {

            $total_pieces = 0; $total_meter = 0; $totalamount = 0;
            $payment = Payment::where('payment_id', $pid)->where('financial_year_id', $user->financial_year_id)->first();
            $paymentDatail = PaymentDetail::where('sr_no', $salebill->sr_no)->where('p_increment_id', $payment->id)->first();
            $salebill2 = DB::table('sale_bills')->where('sale_bill_id', $salebill->sr_no)
                        ->where('financial_year_id', $paymentDatail->financial_year_id)
                        ->first();
            $bill = DB::table('sale_bill_items')->where('sale_bill_id', $salebill->sr_no)->get();

            $increment_id_details = IncrementId::where('financial_year_id', $user->financial_year_id)->first();
            $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
            $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;

            if ($increment_id_details) {
                $iuid = $increment_id_details->iuid + 1;
                $goods_return_id = $increment_id_details->goods_return_id + 1;
                $increment_id = IncrementId::where('financial_year_id', $user->financial_year_id)->first();
                $increment_id->iuid = $iuid;
                $increment_id->goods_return_id = $goods_return_id;
                $increment_id->save();
            } else {
                $iuid = '1';
                $goods_return_id = '1';
                $increment_id = new IncrementId();
                $increment_id->id = $Incrementids;
                $increment_id->iuid = $iuid;
                $increment_id->goods_return_id = $goods_return_id;
                $increment_id->financial_year_id = $user->financial_year_id;
                $increment_id->save();
            }
            $iuids = Iuid::orderBy('id', 'DESC')->first('id');
            $nextAutoID = !empty($iuids) ? $iuids->id + 1 : 1;

            $iuid_ids = new Iuid();
            $iuid_ids->id = $nextAutoID;
            $iuid_ids->iuid = $iuid;
            $iuid_ids->financial_year_id = $financialid;
            $iuid_ids->save();

            $comboLastid = Comboids::orderBy('comboid', 'DESC')->first('comboid');
            $combo_id = !empty($comboLastid) ? $comboLastid->comboid + 1 : 1;

            $comboids = new Comboids();
            $comboids->comboid = $combo_id;
            $comboids->payment_id = $payment->payment_id;
            $comboids->iuid = $iuid;
            $comboids->ouid = 0;
            $comboids->system_module_id = '20';
            $comboids->general_ref_id = $payment->reference_id;
            $comboids->generated_by = $user->employee_id;
            $comboids->assigned_to = $user->employee_id;
            $comboids->company_id = $payment->customer_id;
            $comboids->supplier_id = $payment->supplier_id;
            $comboids->company_type = $typeName;
            $comboids->followup_via = 'Good Return';
            $comboids->inward_or_outward_via = $reference->type_of_inward;
            $comboids->selection_date = $payment->date;
            $comboids->from_name = $personName;
            $comboids->total = $payment->tot_good_returns;
            $comboids->subject = 'For '. $companyName->name .' RS '.$payment->tot_good_returnst .'/-';
            $comboids->financial_year_id = $user->financial_year_id;
            $comboids->attachments = json_encode($attachments);
            $comboids->updated_by = Session::get('user')->employee_id;
            $comboids->inward_or_outward_flag = 0;
            $comboids->inward_or_outward_id = 0;
            $comboids->receipt_amount = 0;
            $comboids->sale_bill_id = 0;
            $comboids->goods_return_id = 0;
            $comboids->commission_id = 0;
            $comboids->commission_invoice_id = 0;
            $comboids->is_invoice = 0;
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
            $comboids->is_completed = 0;
            $comboids->mark_as_draft = 0;
            $comboids->color_flag_id = 0;
            $comboids->product_qty = 0;
            $comboids->fabric_meters = 0;
            $comboids->sample_return_qty = 0;
            $comboids->mobile_flag = 0;
            $comboids->is_deleted = 0;
            $comboids->save();

            $goodreturnLastid = GoodsReturn::orderBy('id', 'DESC')->first('id');
            $goodreturnId = !empty($goodreturnLastid) ? $goodreturnLastid->id + 1 : 1;
            $goodretun = new GoodsReturn();
            $goodretun->id = $goodreturnId;
            $goodretun->goods_return_id = $goods_return_id;
            $goodretun->p_increment_id = $paymentDatail->p_increment_id;
            $goodretun->iuid = $iuid;
            $goodretun->reference_id = $reference->reference_id;
            $goodretun->sale_bill_id = $salebill->sr_no;
            $goodretun->company_id = $payment->customer_id;
            $goodretun->supplier_id = $payment->supplier_id;
            $goodretun->financial_year_id = $user->financial_year_id;
            $goodretun->generated_by = $user->employee_id;
            $goodretun->supp_invoice_no = $salebill->supplier_invoice_no;
            $goodretun->multiple_attachment = array_key_exists($key,$attachments) ? json_encode($attachments[$key]) :  '[]';
            $goodretun->amount = $salebill->amount;
            $goodretun->adjust_amount = $paymentDatail->adjust_amount;
            $goodretun->goods_return = $salebill->goods_return;
            $goodretun->tot_peices = (int)$salebill->pieces;
            $goodretun->tot_meters = (int)$salebill->meter;
            $goodretun->tot_amount = (int)$salebill->totamount;
            $goodretun->sale_bill_for = $salebill2->sale_bill_for;
            $goodretun->is_deleted = '0';
            $goodretun->date_added = date('Y-m-d H:i:s');
            $goodretun->save();

            $comboid1 = Comboids::where('comboid', $combo_id)->first();
            $comboid1->goods_return_id = $goods_return_id;
            $comboid1->save();

            foreach ($salebill->products as $product) {

                $bill = DB::table('sale_bill_items')->where('id', $product->id)->first();
                $gritemLastid = GrSaleBillItem::orderBy('id', 'DESC')->first('id');
                $gritemId = !empty($gritemLastid) ? $gritemLastid->id + 1 : 1;
                $grsalebillitem = new GrSaleBillItem();
                $grsalebillitem->id = $gritemId;
                $grsalebillitem->gr_increment_id = $goodreturnId;
                $grsalebillitem->goods_return_id = $goods_return_id;
                $grsalebillitem->product_or_fabric_id = $product->product_or_fabric_id;
                $grsalebillitem->peices = (int)$product->pieces;
                $grsalebillitem->meters = (int)$product->meter;
                $grsalebillitem->peices_meters = (int)$product->pieces_meter;
                $grsalebillitem->rate = $product->rate;
                $grsalebillitem->discount_per = $bill->discount;
                $grsalebillitem->discount_amt = $bill->discount_amount;
                $grsalebillitem->cgst_per = $bill->cgst;
                $grsalebillitem->cgst_amt = $bill->cgst_amount;
                $grsalebillitem->sgst_per = $bill->sgst;
                $grsalebillitem->sgst_amt = $bill->sgst_amount;
                $grsalebillitem->igst_per = $bill->igst;
                $grsalebillitem->igst_amt = $bill->igst_amount;
                $grsalebillitem->amount = $product->amount;

                $grsalebillitem->save();
            }

        }
        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'payment / good return add';
        $logs->log_subject = 'Payment good return page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateGoodRetuen(Request $request) {
        $grattechment = $request->grattechment;
        $user = Session::get('user');
        $salebilldata = json_decode($request->salebills);
        $products = json_decode($request->products);
        $goodreturn = GoodsReturn::where('goods_return_id', $salebilldata->id)
                    ->where('financial_year_id', $user->financial_year_id)
                    ->first();
        $attachments = array();
        if ($grattechment) {
                    $attechmentImage = '';
                    $attechmentImage = rand() . "_grattechment." . $grattechment->getClientOriginalExtension();
                    $grattechment->move(public_path('upload/goodreturn/'), $attechmentImage);
                    $goodreturn->multiple_attachment = $attechmentImage;
        }
        $goodreturn->save();

        //$bill = GrSaleBillItem::where('gr_increment_id', $goodreturn->id)->first();
        //GrSaleBillItem::where('goods_return_id', $salebilldata->id)->delete();
        //$goodreturnId = DB::table('goods_returns')->where('goods_return_id', $salebilldata->id)->select('id')->first();
        foreach ($products as $product) {
            $grsalebillitem = GrSaleBillItem::where('id', $product->id)->first();
            $grsalebillitem->product_or_fabric_id = $product->product_or_fabric_id;
            $grsalebillitem->peices = $product->pieces;
            $grsalebillitem->meters = (int)$product->meter;
            $grsalebillitem->peices_meters = (int)$product->pieces_meter;
            $grsalebillitem->rate = $product->rate;
            $grsalebillitem->amount = $product->amount;
            $grsalebillitem->save();
        }


        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'payment / good return add';
        $logs->log_subject = 'Payment good return page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function paymentStatus(Request $request) {
        $status = $request->status;
        $page_title = 'Payment List';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            -> join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        $employees['excelAccess'] = $user->excel_access;
        $employees['status'] = $status;

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'payment / View';
        $logs->log_subject = 'Payment view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('payment.paymentstatus',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function deletePayment($id)
    {
        $user = Session::get('user');
        $payment = Payment::where('payment_id', $id)->where('financial_year_id', $user->financial_year_id)->first();
        $paymentDetail = PaymentDetail::where('payment_id', $id)
            ->where('p_increment_id', $payment->id)
            ->get();

        foreach ($paymentDetail as $paymentData) {
            $salebill = SaleBill::where('sale_bill_id', $paymentData->sr_no)
                ->where('financial_year_id', $paymentData->financial_year_id)
                ->where('is_deleted', 0)
                ->first();

            $pending_amount = $paymentData->adjust_amount + $paymentData->discount_amount + $paymentData->vatav + $paymentData->agent_commission + $paymentData->bank_commission + $paymentData->claim + $paymentData->short - $paymentData->interest;

            if ($payment->reciept_mode != 'fullreturn') {
                $pending_amount += $paymentData->goods_return;
            }

            if ($pending_amount != 0) {
                $salebill->pending_payment = $salebill->pending_payment + $pending_amount;
            }
            $salebill->received_payment = $salebill->received_payment - $paymentData->adjust_amount;
            $salebill->payment_status = 0;
            $salebill->save();
        }

        foreach ($paymentDetail as $pd) {
            $pdetail = PaymentDetail::where('id', $pd->id)->first();
            $pdetail->is_deleted = 1;
            $pdetail->save();
        }

        $paymentcomboids = Comboids::where('payment_id', $id)->where('financial_year_id', $payment->financial_year_id)->get();
        foreach ($paymentcomboids as $comboids) {
            $comboid = Comboids::where('payment_id', $comboids->payment_id)->where('financial_year_id', $payment->financial_year_id)->where('is_deleted', 0)->first();
            $comboid->is_deleted = 1;
            $comboid->save();
            Iuid::where('iuid', $comboids->iuid)->where('financial_year_id', $payment->financial_year_id)->delete();
            Ouid::where('ouid', $comboids->ouid)->where('financial_year_id', $payment->financial_year_id)->delete();
        }

        $paymentgoodreturn = GoodsReturn::where('p_increment_id', $payment->id)->where('financial_year_id', $payment->financial_year_id)->get();
        foreach ($paymentgoodreturn as $goodreturn) {
            $getgoodsreturn = GoodsReturn::where('id', $goodreturn->id)->where('financial_year_id', $payment->financial_year_id)->where('is_deleted', 0)->first();

            $goods_return = Comboids::where('goods_return_id', $getgoodsreturn->goods_return_id)->where('financial_year_id', $payment->financial_year_id)->get();
            foreach ($goods_return as $row_goods_return) {
                $grcomboids = Comboids::where('goods_return_id', $row_goods_return->goods_return_id)->where('financial_year_id', $payment->financial_year_id)->first();
                $grcomboids->is_deleted = 1;
                $grcomboids->save();
                Iuid::where('iuid', $row_goods_return->iuid)->where('financial_year_id', $payment->financial_year_id)->delete();
                Ouid::where('ouid', $row_goods_return->ouid)->where('financial_year_id', $payment->financial_year_id)->delete();
            }

            $grsalebillitem = GrSaleBillItem::where('gr_increment_id', $goodreturn->id)->get();
            foreach ($grsalebillitem as $gritem) {
                $gritem = GrSaleBillItem::where('id', $gritem->id)->first();
                $gritem->is_deleted = 1;
                $gritem->save();
            }
            $getgoodsreturn->is_deleted = 1;
            $getgoodsreturn->save();
        }
        $payment->is_deleted = 1;
        $payment->save();

        $data['status'] = 1;
        return redirect('/payments');
    }

    public function deleteGoodReturn($id)
    {
        $user = Session::get('user');
        $goodretun = GoodsReturn::where('goods_return_id', $id)->where('financial_year_id', $user->financial_year_id)->first();
        $goodretun->is_deleted = 1;
        $goodretun->save();

        $sale_bill = SaleBill::where('sale_bill_id', $goodretun->sale_bill_id)->where('financial_year_id', $user->financial_year_id)->first();
        $sale_bill->receipt_payment = $goodretun->goods_return + $sale_bill->receipt_payment;
        $sale_bill->pending_payment = $goodretun->pending_payment - $sale_bill->goods_return;
        $sale_bill->save();

        $data['status'] = 1;
        return $data;
    }

    public function viewPayment(Request $request, $id)
    {
        $page_title = 'View Payment';
        $financialYear = FinancialYear::where('id', $id);
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['id'] = $id;
        $fid = isset($request->fid) && !empty($request->fid) ? $request->fid : 0;

        return view('payment.viewpayment', compact('financialYear', 'page_title', 'fid'))->with('employees', $employees);
    }

    public function viewVoucher($id) {
        $page_title = 'View Voucher';
        $financialYear = FinancialYear::where('id',$id);
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['id'] = $id;

        return view('payment.viewvoucher',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function viewGoodReturn($id) {
        $page_title = 'View Good Return';
        $financialYear = FinancialYear::where('id',$id);
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['id'] = $id;

        return view('payment.viewgoodreturn',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function fetchVoucher($id) {
        $user = Session::get('user');
        $payment = Payment::where('payment_id', $id)->where('financial_year_id', $user->financial_year_id)->first();
        $customer = Company::where('id', $payment->receipt_from)->first();
        $supplier = Company::where('id', $payment->supplier_id)->first();
        $salebill = PaymentDetail::where('p_increment_id', $payment->id)->get();
        $salebilldata = array();
        foreach($salebill as $sale) {
            $salebill_date = DB::table('sale_bills')->where('sale_bill_id', $sale->sr_no)->where('financial_year_id', $sale->financial_year_id)->first();

            $bill_date = date('d-m-Y', strtotime($salebill_date->select_date));
            $payment_date = strtotime($payment->date);
			$salebill_date = strtotime($salebill_date->select_date);
            $datediff  = $payment_date - $salebill_date;
			$days = floor($datediff / (60 * 60 * 24));
            $data1 = $sale;
            $data1['bill_date'] = $bill_date;
            $data1['day'] = $days;
            array_push($salebilldata, $data1);
        }
        $bank = DB::table('bank_details')->where('id', $sale->cheque_dd_bank)->first();
        if ($bank) {
            $data['bank'] = $bank->name;
        } else {
            $data['bank'] = '';
        }
        $data['paymentData'] = $payment;
        $data['created_at'] = date_format($payment->created_at,"Y/m/d");
        $data['salebill'] = $salebilldata;
        $data['customer'] = $customer;
        $data['supplier'] = $supplier;
        return $data;
    }

    public function checkPendingPayment(Request $request)
    {
        $old_amount = $request->old_amount;
        $new_amount = $request->new_amount;
        $salebillid = $request->salebillid;
        $fid = $request->fid;

        $salebill = SaleBill::where('sale_bill_id', $salebillid)->where('financial_year_id', $fid)->first();
        $received_payment = $salebill->received_payment;
        $status = $salebill->payment_status;
        $pending_payment = $salebill->pending_payment;

        if ($pending_payment > 0) {
            $amount = $old_amount + $pending_payment;
            $diff = $new_amount - $amount;
            if ($diff > 0) {
                $data['amount'] = $amount;
            } else {
                $data['amount'] = 0;
            }
        } else if ($pending_payment == 0) {
            $diff = $new_amount - $old_amount;
            if ($diff > 0) {
                $data['amount'] = $old_amount;
            } else {
                $data['amount'] = 0;
            }
        }
        return $data;
    }
}
