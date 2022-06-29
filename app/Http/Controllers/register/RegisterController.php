<?php

namespace App\Http\Controllers\register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Logs;
use App\Models\FinancialYear;
use App\Models\IncrementId;
use App\Models\linkCompanies;
use App\Models\Product;
use App\Models\SaleBill;
use App\Models\Comboids\Comboids;
use App\Models\Ouid;
use App\Models\Iuid;
use App\Models\InwardOutward\Outward;
use App\Models\InwardOutward\Inward;
use App\Models\CompanyType;
use App\Models\OutwardSaleBill;
use App\Models\ProductsImages;
use App\Models\InwardLinkWith;
use App\Models\EnjayCallRecordsId;
use App\Models\ProductCategory;
use App\Models\Payment;
use App\Models\InwardSample;
use App\Models\Settings\TransportDetails;
use App\Models\Settings\Agent;
use App\Models\Company\Company;
use App\Models\Commission\Commission;
use App\Models\Commission\CommissionInvoice;
use App\Models\Company\CompanyContactDetails;
use App\Models\Reference\ReferenceId;
use App\Models\Settings\BankDetails;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');

        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['excelAccess'] = $user->excel_access;

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'UserGroup / View';
        $logs->log_subject = 'User Group view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('register.register',compact('financialYear'))->with('employees', $employees);
    }

    public function createInward(Request $request) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
        join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('register.inward.createInward',compact('financialYear'))->with('employees', $employees);
    }

    public function createOutward(Request $request) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
        join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('register.outward.createOutward',compact('financialYear'))->with('employees', $employees);
    }

    public function addInward($type) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        if($type == 'call') {
            $employees['inwardType'] = 1;

        } elseif($type == 'message') {
            $employees['inwardType'] = 2;

        } elseif($type == 'whatsapp') {
            $employees['inwardType'] = 3;

        } elseif($type == 'letter') {
            $employees['inwardType'] = 4;

        } elseif($type == 'sample') {
            $employees['inwardType'] = 5;

        } elseif($type == 'email') {
            $employees['inwardType'] = 6;
        }

        return view('register.inward.insertInward',compact('financialYear'))->with('employees', $employees);
    }

    public function addSalebillOutward() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('register.outward.insertSalebillOutward',compact('financialYear'))->with('employees', $employees);
    }

    public function addPaymentOutward() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('register.outward.insertPaymentOutward',compact('financialYear'))->with('employees', $employees);
    }

    public function addCommissionOutward() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('register.outward.insertCommissionOutward',compact('financialYear'))->with('employees', $employees);
    }

    public function addCommissionInvoiceOutward() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('register.outward.insertCommissioninvoiceOutward',compact('financialYear'))->with('employees', $employees);
    }

    public function listSuppliers() {
        $suppliers = Company::where('company_type', 3)->where('is_delete', 0)->get();

        return $suppliers;
    }

    public function receiverDetails() {
        $user = Session::get('user');
        $receiverDetails = Employee::where('is_delete', '0')->where('employees.id', $user->employee_id)->first();

        return $receiverDetails;
    }

    public function fromName($id) {
        $fromNameNumber = CompanyContactDetails::where('company_id', $id)->first(['contact_person_name', 'contact_person_mobile']);

        return $fromNameNumber;
    }

    /* public function getReferenceDetails($type, $flag, $refrenceVia) {
        $user = Session::get('user');
        echo "<pre>";
        print_r($flag); echo "<br><br>";
        print_r($refrenceVia);

        if ($type == 'call') {
            $generalRef = ReferenceId::join('companies', 'reference_ids.company_id', '=', 'companies.id')->
                                       where('companies.company_type', $refrenceVia)->
                                       where('reference_ids.type_of_inward', ucwords($type))->
                                       where('reference_ids.employee_id', $user->employee_id)->
                                       where('reference_ids.inward_or_outward', $flag)->
                                       where('reference_ids.financial_year_id', $user->financial_year_id)->
                                       orderBy('reference_ids.reference_id', 'DESC')->
                                       take('5')->
                                       get();

            $enjayDetails = EnjayCallRecordsId::join('reference_ids', 'reference_ids.reference_id', '=', 'enjay_call_records_ids.reference_id')->
                                                where('reference_ids.financial_year_id', $user->financial_year_id)->
                                                where('enjay_call_records_ids.reference_id', '!=', 0)->
                                                where('reference_ids.inward_or_outward', 1)->
                                                where('enjay_call_records_ids.extension', $user->extension_port_id)->
                                                groupBy('enjay_call_records_ids.uniqueid')->
                                                orderBy('enjay_call_records_ids.reference_id', 'DESC')->
                                                take('5')->
                                                get();

            dd($generalRef);
        } else {

        }
        $fromNameNumber = CompanyContactDetails::where('company_id', $id)->first(['contact_person_name', 'contact_person_mobile']);

        return $fromNameNumber;
    } */

    public function getProductWithSupplier($id) {
        $company = Company::where('id', $id)->first();
        $linkCompanies = linkCompanies::where('company_id', $id)->get();
        if (empty($linkCompanies)) {
            $isLinked = linkCompanies::where('link_companies_id', $id)->first();
            if(!empty($isLinked)) {
                $company = Company::where('id', $isLinked->company_id)->first();
                $linkCompanies = linkCompanies::where('company_id', $isLinked->company_id)->get();
            }
        }

        $mainCompanyId = $company->id;

        $products = Product::query();
        if ($mainCompanyId) {
            $products = $products->orwhere('company', $mainCompanyId);
            foreach ($linkCompanies as $rowLinkCompanies) {
                $products = $products->orwhere('company', $rowLinkCompanies->link_companies_id);
            }
        }
        $products = $products->get(['id', 'product_name']);

        $fabricMainCategory = ProductCategory::where('product_default_category_id', '2')->get();

        $sqlVar = '';
        if ($fabricMainCategory) {
            $sqlVar = "(";
            foreach($fabricMainCategory as $fabricCategory) {
                $sqlVar .= "main_category_id=".$fabricCategory->id." OR ";
            }
            $sqlVar .= "0>1)";
        }

        if ($mainCompanyId) {
            $sqlVar .= " AND (";
            $sqlVar .= "company_id::text LIKE '%".'"'.$mainCompanyId.'"'."%' OR ";
            foreach ($linkCompanies as $rowLinkCompanies) {
                $sqlVar .= "company_id::text LIKE '%".'"'.$rowLinkCompanies->link_companies_id.'"'."%' OR ";
            }
            $sqlVar .= "0>1)";
        }
        $fabrics = DB::select("SELECT * FROM product_categories WHERE ".$sqlVar." ORDER BY name");

        $productOrFabric = [];
        $i=0;
        foreach ($products as $rowProduct) {
            $productOrFabric[$i]['productOrFabricId'] = 'p-'.$rowProduct->id;
            $productOrFabric[$i]['name'] = $rowProduct->product_name;
            $i++;
        }
        foreach ($fabrics as $rowFabric) {
            $productOrFabric[$i]['productOrFabricId'] = 'f-'.$rowFabric->id;
            $productOrFabric[$i]['name'] = $rowFabric->name;
            $i++;
        }
        function sortByName($a,$b) {
            $d1 = $a['name'];
            $d2 = $b['name'];
            return $d1 == $d2 ? 0 : ($d1 > $d2 ? 1 : -1);
        }
        // usort($productOrFabric,'sortByName');

        return $productOrFabric;
    }

    public function getmainCategories() {
        $mainCategories = ProductCategory::where('main_category_id', '0')->where('product_default_category_id', '2')->get();

        return $mainCategories;
    }

    public function addFabricDetails(Request $request) {
        $fabricName = ProductCategory::where('name', $request->name)->first();

        if ($fabricName) {
            $companyId = json_decode($fabricName->company_id);
            if (!in_array($request->supplierId['id'],$companyId)) {
                array_push($companyId, $request->supplierId['id']);
                $productCategory = ProductCategory::where('id', $fabricName->id)->first();
                $productCategory->name = $request->name;
                $productCategory->company_id = json_encode($companyId);
                $productCategory->main_category_id = $request->mainCategory['id'];
                $productCategory->save();
            } else {
                $productCategory = ProductCategory::where('id', $fabricName->id)->first();
                $productCategory->name = $request->name;
                $productCategory->company_id = json_encode($companyId);
                $productCategory->main_category_id = $request->mainCategory['id'];
                $productCategory->save();
            }
        } else {
            $companyId = [];
            if (isset($request->supplierId) && !empty($request->supplierId['id'])) {
                array_push($companyId, $request->supplierId['id']);
            }
            $productCategory = new ProductCategory;
            $productCategory->name = $request->name;
            $productCategory->company_id = json_encode($companyId);
            $productCategory->main_category_id = $request->mainCategory['id'];
            $productCategory->save();
        }

        return true;
    }

    public function getSubProducts($value) {
        $productMainID = explode('-', $value);
        $productMainID = array_filter($productMainID);
        $subProducts = [];
        if($productMainID) {
            $subProducts = ProductsImages::whereIn('product_id', $productMainID)->get();
        }

        return $subProducts;
    }

    public function listInwardLinkWith() {
        $inwardLinkWith = InwardLinkWith::where('is_delete', '0')->get();

        return $inwardLinkWith;
    }

    public function listAllEmployees() {
        $employees = Employee::select("*", DB::raw("CONCAT(firstname,' ',lastname) as name"))->where('is_delete', '0')->get();

        return $employees;
    }

    public function getReferenceSampleData(Request $request) {
        $type = $request->type['name'];
        $user = Session::get('user');

        $references = ReferenceId::join('companies', 'reference_ids.company_id', '=', 'companies.id')
            ->select('reference_ids.*')
            ->whereIn('companies.company_type', [2,3])
            ->where('reference_ids.type_of_inward', $type);
        if ($user->id == 15) {
            $references = $references->where('reference_ids.employee_id', 15);
        }
        $references = $references->where('reference_ids.inward_or_outward', 1)
            ->where('reference_ids.financial_year_id', $user->financial_year_id)
            ->where('reference_ids.is_deleted', 0)
            ->orderBy('reference_ids.id', 'desc')
            ->take('5')
            ->get();

        return $references;
    }

    public function getOldReferenceDetails($inwardRefSearch, $typeOfInward, $inwardType) {
        $html = "";
        $reference = DB::table('reference_ids as r')
            ->join('companies as c', 'r.company_id', '=', 'c.id')
            ->select('r.employee_id', 'r.reference_id', 'r.created_at', 'r.company_id', 'r.selection_date', 'r.type_of_inward', 'r.from_name', 'r.from_number', 'r.receiver_number', 'r.from_email_id', 'r.receiver_email_id', 'r.latter_by_id', 'r.courier_name', 'r.weight_of_parcel', 'r.courier_receipt_no', 'r.courier_received_time', 'r.delivery_by', 'c.company_name')
            ->where('r.reference_id', $inwardRefSearch)
            ->where('r.financial_year_id', Session::get('user')->financial_year_id)
            ->where('r.inward_or_outward', 1)
            ->where('r.type_of_inward', $typeOfInward)
            ->where('r.is_deleted', 0)
            ->limit(1)
            ->first();

        if ($reference) {
            $referenceid = $reference->reference_id;
            /* if ($reference->company_id != 0) {
                if (Session::get('user')->employee_id == $reference->employee_id) {
                    $empName = "Own";
                } else {
                    $empName = "Rec.";
                }
                $html .= '<table class="table table-hover table-bordered" > <tbody> <tr> <td> <div class="custom-control custom-control-sm custom-radio notext"> <input type="radio" class="custom-control-input" id="referenceSample5"  name="" value="' . $referenceid. '" > <label class="custom-control-label" for="referenceSample5"></label> </div> </td> <td>' . $referenceid . '</td> <td>Rec.</td> <td>' . date('Y-m-d', strtotime($reference->created_at)) . '</td> <td>' . date('H:i A', strtotime($reference->created_at)) . '</td> </tr> </tbody> </table>';
            } */
        } else {
            $referenceid = 0;
        }
        $data['ref_id'] = $referenceid;
        $data['reference'] = $reference;
        return $data;
    }

    public function listRegister(Request $request) {
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
            $columnName = 'comboids.'.$columnName;
        }

        $totalRecords = Comboids::where('is_deleted', '0')->select('count(*) as allcount')->count();

        $totalRecordswithFilter = Comboids::where('financial_year_id', $user->financial_year_id)->where('is_deleted', '0');
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('iuid', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('ouid', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('reference_id', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('created_at', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('system_module_id', '=', $columnName_arr[4]['search']['value']);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('inward_or_outward_via', 'ilike', '%' . $columnName_arr[5]['search']['value']. '%');
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[6]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('company_id', $cc_id);
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            $sc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('supplier_id', $sc_id);
        }
        if (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('company_type', '=', $columnName_arr[8]['search']['value']);
        }
        if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
            $generated_id = DB::table('employees')->select('id')->where('firstname', 'ilike', '%' . $columnName_arr[9]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->where('generated_by', '=', $generated_id);
        }
        if (isset($columnName_arr[10]['search']['value']) && !empty($columnName_arr[10]['search']['value'])) {
            $assign_id = DB::table('employees')->select('id')->where('firstname', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->where('assigned_to', '=', $assign_id);
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();


        $records = Comboids::where('financial_year_id', $user->financial_year_id)->where('is_deleted', '0');
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $records = $records->where('iuid', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $records = $records->where('ouid', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $records = $records->where('reference_id', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $records = $records->whereDate('created_at', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $records = $records->where('system_module_id', '=', $columnName_arr[4]['search']['value']);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $records = $records->where('inward_or_outward_via', 'ilike', '%' . $columnName_arr[5]['search']['value']. '%');
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[6]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->whereIn('company_id', $cc_id);
        }
        if (isset($columnName_arr[7]['search']['value']) && !empty($columnName_arr[7]['search']['value'])) {
            $sc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->whereIn('supplier_id', $sc_id);
        }
        if (isset($columnName_arr[8]['search']['value']) && !empty($columnName_arr[8]['search']['value'])) {
            $records = $records->where('company_type', 'ilike', '%' . $columnName_arr[8]['search']['value'] . '%');
        }
        if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
            $generated_id = DB::table('employees')->select('id')->where('firstname', 'ilike', '%' . $columnName_arr[9]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->where('generated_by', '=', $generated_id);
        }
        if (isset($columnName_arr[10]['search']['value']) && !empty($columnName_arr[10]['search']['value'])) {
            $assign_id = DB::table('employees')->select('id')->where('firstname', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->where('assigned_to', '=', $assign_id);
        }

        // Fetch records
        $records = $records->select('*');

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();

        $data_arr = array();
        foreach($records as $record){
            $iuid = $record->iuid;
            $ouid = $record->ouid;
            $ref_id = $record->general_ref_id;
            $date = date_format($record->created_at, 'Y/m/d H:i:s');
            $timecompleted = '';
            if ($record->system_module_id == 1 || $record->system_module_id == 8) {
                $iotype = "Enquiry";
            } elseif ($record->system_module_id == 2 || $record->system_module_id == 9) {
                $iotype =  "Order";
            } elseif ($record->system_module_id == 3 || $record->system_module_id == 10) {
                $iotype =  "Complain";
            } elseif ($record->system_module_id == 4 || $record->system_module_id == 11) {
                $iotype =  "General";
            } elseif ($record->system_module_id == 15) {
                $iotype =  "Sample";
            } elseif ($record->system_module_id == 5) {
                $iotype = "Sale Bill";
            } elseif ($record->system_module_id == 6) {
                $iotype =  "Payment";
            } elseif ($record->system_module_id == 7) {
                $iotype =  "Commission";
            }  elseif ($record->system_module_id == 16) {
                $iotype =  "Outward Sale Bill";
            }  elseif ($record->system_module_id == 17) {
                $iotype =  "Outward Payment";
            }  elseif ($record->system_module_id == 18) {
                $iotype =  "Outward Commission";
            }  elseif ($record->system_module_id == 19) {
                $iotype =  "Commission Invoice";
            }  elseif ($record->system_module_id == 20) {
                $iotype =  "Goods Return";
            } elseif ($record->system_module_id == 21) {
                $iotype =  "Outward Commission Invoice";
            }

            if ($record->inward_or_outward_via == 'Email') {
                $iomedium = '<em class="icon ni ni-mail" title="Email"></em>';
            } else if($record->inward_or_outward_via == 'Hand') {
                $iomedium = '<em class="icon ni ni-thumbs-up" title="Hand"></em>';
            } else if($record->inward_or_outward_via == 'Courier') {
                $iomedium = '<em class="icon ni ni-inbox-out-fill" title="Courier"></em>';
            } else if($record->inward_or_outward_via == 'Whatsapp') {
                $iomedium = '<em class="icon ni ni-whatsapp" title="Whatsapp"></em>';
            } else {
                $iomedium = '';
            }
            $iodetail = $record->subject;
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


            if (empty($customer_company)) {
                $company = '';
            } else {
                if ((count($customer_address) == 0 || count($customer_owners) == 0)) {
                    $customer_color = '';
                } else {
                    $customer_color = ' text-danger ';
                }
                $company = '<a href="#" class="view-details ' . $customer_color . '" data-id="' . $customer_company->id . '">' . $customer_company->company_name . '</a>';
            }
            if (empty($seller_company)) {
                $supplier = '';
            } else {
                if ((count($seller_address) == 0 || count($seller_owners) == 0)) {
                    $seller_color = '';
                } else {
                    $seller_color = ' text-danger ';
                }
                $supplier = '<a href="#" class="view-details ' . $seller_color . '" data-id="' . $seller_company->id . '">' . $seller_company->company_name . '</a>';
            }

            $cmptype = $record->company_type;
            $genrateby = Employee::where('id', $record->generated_by)->first();
            $assignto = Employee::where('id', $record->assigned_to)->first();

            if (!empty($genrateby)) {
                $genratebyname =  $genrateby->firstname;
            } else {
                $genratebyname = '';
            }

            if (!empty($assignto)) {
                $assigntoname =  $assignto->firstname;
            } else {
                $assigntoname = '';
            }

            if ($record->system_module_id == 5) {
                $action = '<a href="/account/sale-bill/view-sale-bill/'.$record->sale_bill_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/account/sale-bill/edit-sale-bill/'.$record->sale_bill_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';
            } else if ($record->system_module_id == 6) {
                $action = '<a href="/payments/view-payment/'.$record->payment_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/payments/edit-payment/'.$record->payment_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';
            } else if ($record->system_module_id == 7) {
                $action = '<a href="/commission/view-commission/'.$record->commission_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/commission/edit-commission/'.$record->commission_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';
            } else if ($record->system_module_id == 20) {
                $action = '<a href="/payments/view-goodreturn/'.$record->goods_return_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/payments/edit-goodreturn/'.$record->goods_return_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';
            } else if ($record->system_module_id == 19) {
                $action = '<a href="/account/commission/invoice/view-invoice/' . $record->commission_invoice_id . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="View"><em class="icon ni ni-eye"></em></a> <a href="/account/commission/invoice/edit-invoice/' . $record->commission_invoice_id . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';
            } else if ($record->system_module_id == 16 || $record->system_module_id == 17 || $record->system_module_id == 18 || $record->system_module_id == 21) {
                $action = '<a href="/register/outward/outward-view/'.$record->inward_or_outward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="print"><em class="icon ni ni-file-doc"></em></a><a href="/register/view-outward/'.$record->inward_or_outward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/register/edit-outward/'.$record->inward_or_outward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';
            } else if ($record->system_module_id == 15) {
                $action = '<a href="/register/view-inward/'.$record->inward_or_outward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/register/edit-inward/'.$record->inward_or_outward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';
            } else {
                $action = '';
            }
            $color_flag = $record->color_flag_id;
            $timecompleted = 00;
            if ($record->color_flag_id == 3 && $record->inward_or_outward_flag == 1 ) {
                $complete = Comboids::where('inward_or_outward_id', $record->inward_or_outward_id)
                            ->where('is_deleted', 0)->where('finanacial_year_id', $user->financial_year_id)
                            ->first();
                if ($complete) {
                    $date_add = DB::table('inwards')->where('is_deleted', 0)->where('inward_id', $record->inward_or_outward_id)
                                ->where('financial_year_id', $user->financial_year_id)->first();
                    $timecompleted = time_elapsed_string(date('Y-m-d H:i',strtotime($complete->created_at)),date('Y-m-d H:i',strtotime($date_add->created_at)));
                }
            } else if ($record->color_flag_id == 3 && $record->sale_bill_id != 0 ) {
                $complete = Comboids::where('sale_bill_id', $record->sale_bill_id)
                            ->where('is_deleted', 0)->where('finanacial_year_id', $user->financial_year_id)
                            ->first();
                if ($complete) {
                    $date_add = DB::table('sale_bills')->where('is_deleted', 0)->where('sale_bill_id', $record->sale_bill_id)
                                ->where('financial_year_id', $user->financial_year_id)->first();
                    $timecompleted = time_elapsed_string(date('Y-m-d H:i',strtotime($complete->created_at)),date('Y-m-d H:i',strtotime($date_add->created_at)));
                }
            }


            $data_arr[] = array(
                "iuid" => $iuid,
                "ouid" => $ouid,
                "reference_id" => $ref_id,
                "created_at" => $date,
                "timecompleted" => $timecompleted,
                "iotype" => $iotype,
                "iomedium" => $iomedium,
                "iodetail" => $iodetail,
                "company" => $company,
                "supplier" => $supplier,
                "cmptype" => $cmptype,
                "genby" => $genratebyname,
                "assignto" => $assigntoname,
                "color_flag" => $color_flag,
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

    public function listBuyer() {
        $buyer = Company::where('company_type', 2)->where('is_delete', 0)->get();

        $data['buyer'] = $buyer;

        return $data;
    }
    public function getAllDetails(Request $request) {
       $user = Session::get('user');
       $referenceid = $request->input('refernceid');

       $reference =  ReferenceId::where('reference_id', $referenceid)
                    ->where('financial_year_id', $user->financial_year_id)
                    ->where('is_deleted', 0)
                    ->first();
        $company = Company::where('id', $reference->company_id)
                   ->where('is_delete', 0)
                   ->first();
        $data['courier_name'] = [];
        if (!empty($reference->courier_name)){
            $courier = TransportDetails::where('id', $reference->courier_name)->where('is_delete', 0)->first();
            $data['courier_name'] = $courier;
        }
        $data['reference'] = $reference;
        $data['company'] = $company;
        return $data;
    }

    public function listAgentCourier() {
        $courier = TransportDetails::where('is_delete', 0)->get();
        $agent = Agent::where('is_delete', 0)->get();

        $data['courier'] = $courier;
        $data['agent'] = $agent;
        $data['today'] = Carbon::now()->format('Y-m-d');
        return $data;
    }

    public function listSupplier() {
        $supplier = Company::where('company_type', 3)->where('is_delete', 0)->get();
        $courier = TransportDetails::where('is_delete', 0)->get();
        $agent = Agent::where('is_delete', 0)->get();
        $data['supplier'] = $supplier;
        $data['courier'] = $courier;
        $data['agent'] = $agent;
        return $data;
    }

    public function searchSalebill(Request $request) {
        $user = Session::get('user');
        $company = Company::where('id', $request->buyer)->first();
        $salebill = SaleBill::join('companies', 'companies.id', '=', 'sale_bills.supplier_id')
                  ->where('sale_bills.company_id', $request->buyer)
            //   ->whereBetween('sale_bills.created_at', [$request->fromdate, $request->todate])
                ->whereRaw("sale_bills.created_at::date >= '" . $request->fromdate . "'")
                ->whereRaw("sale_bills.created_at::date <= '" . $request->todate . "'")
                  ->where('sale_bills.sale_bill_flag', 0)
                  ->whereNot('sale_bills.done_outward', 1)
                  ->where('is_deleted', 0)
                  ->select('sale_bills.*', 'companies.company_name')
                  ->get();
        $data['salebill'] = $salebill;
        $data['company'] = $company;
        $data['todaydate'] = Carbon::now()->format('Y-m-d');
        return $data;
    }
    public function searchPayment(Request $request) {
        $user = Session::get('user');
        $company = Company::where('id', $request->supplier)->first();
        $payment = Payment::join('companies', 'companies.id', '=', 'payments.customer_id')
                  ->where('payments.supplier_id', $request->supplier)
                //   ->whereBetween('payments.created_at', [$request->fromdate." 00:00:00", $request->todate." 00:00:00"])
                  ->whereRaw("payments.created_at::date >= '" . $request->fromdate . "'")
                  ->whereRaw("payments.created_at::date <= '" . $request->todate . "'" )
                  ->whereNot('payments.done_outward', 1)
                  ->where('is_deleted', 0)
                  ->where('payments.reciept_mode', 'cheque')
                  ->select('payments.*', 'companies.company_name')
                  ->get();
        $data['payment'] = $payment;
        $data['company'] = $company;
        $data['todaydate'] = Carbon::now()->format('Y-m-d');
        return $data;
    }

    public function searchCommission(Request $request) {
        $user = Session::get('user');
        $company = Company::where('id', $request->supplier)->first();
        $commission = Commission::where('supplier_id', $request->supplier)
                //   ->whereBetween('created_at', [$request->fromdate." 00:00:00", $request->todate." 00:00:00"])
                  ->whereRaw("created_at::date >= '" . $request->fromdate . "'")
                  ->whereRaw("created_at::date <= '" . $request->todate . "'" )
                  ->whereNot('done_outward', 1)
                  ->where('is_deleted', 0)
                  ->get();
        $data['commission'] = $commission;
        $data['company'] = $company;
        $data['todaydate'] = Carbon::now()->format('Y-m-d');
        return $data;
    }

    public function searchCommissionInvoice(Request $request) {
        $user = Session::get('user');
        $company = Company::where('id', $request->supplier)->first();
        $commissioninvoice = CommissionInvoice::where('supplier_id', $request->supplier)
                //   ->whereBetween('created_at', [$request->date." 00:00:00", $request->date." 23:59:59"])
                  ->whereRaw("created_at::date >= '" . $request->date . "'")
                  ->whereRaw("created_at::date <= '" . $request->date . "'" )
                  ->whereNot('done_outward', 1)
                  ->where('is_deleted', 0)
                  ->get();
        $data['commissioninvoice'] = $commissioninvoice;
        $data['company'] = $company;
        $data['todaydate'] = Carbon::now()->format('Y-m-d');
        return $data;
    }

    public function insertCourier(Request $request) {
        $referncedata = json_decode($request->refenceform);
        $salebilldata = json_decode($request->salebill);
        $reference = $referncedata->refrence;

        $user = Session::get('user');
        $financialid = Session::get('user')->financial_year_id;
        $agent_id = $referncedata->agent->id;
        $latter_by_id = $referncedata->referncevia->name;
        $from_name = '';
        if ($latter_by_id == 'Courier') {
            $ref_via = "Courier";
            $letterid = 1;
            $courier_name = $referncedata->courrier->id;
            $weight_of_parcel = '';//$_POST['weight_of_parcel'];
            $courier_receipt_no = $referncedata->reciptno;
            $courier_received_time = date('Y-m-d',strtotime($referncedata->recivetime));
            $delivery_by =$referncedata->delivery;
        } else {
            $ref_via = "Hand";
            $letterid = 0;
            $courier_name = '';
            $weight_of_parcel = '';//$_POST['weight_of_parcel'];
            $courier_receipt_no = '';
            $courier_received_time = date('Y-m-d',strtotime($referncedata->recivetime));
            $delivery_by =$referncedata->delivery;
        }

        if ($reference == '1') {

            $increment_id_details = IncrementId::where('financial_year_id', $financialid)->first();
            $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
            $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;

            if ($increment_id_details) {
                $ref_id = $increment_id_details->reference_id + 1;
                $ouid = $increment_id_details->ouid + 1;
                $increment_id = IncrementId::where('financial_year_id', $financialid)->first();
                $increment_id->reference_id = $ref_id;
                $increment_id->ouid = $ouid;
                $increment_id->save();
            } else {
                $ref_id = '1';
                $ouid = '1';
                $increment_id = new IncrementId();
                $increment_id->reference_id = $ref_id;
                $increment_id->id = $Incrementids;
                $increment_id->ouid = $ouid;
                $increment_id->financial_year_id = $financialid;
                $increment_id->save();
            }

            $refrenceLastid = ReferenceId::orderBy('id', 'DESC')->first('id');
            $refrenceid = !empty($refrenceLastid) ? $refrenceLastid->id + 1 : 1;


            $refence = new ReferenceId();
            $refence->id = $refrenceid;
            $refence->reference_id = $ref_id;
            $refence->financial_year_id = $financialid;
            $refence->employee_id = $user->employee_id;
            $refence->inward_or_outward = 0;
            $refence->type_of_inward = $ref_via;
            $refence->company_id = $referncedata->companyid;
            $refence->selection_date = $referncedata->datetime;
            $refence->from_name = $from_name;
            $refence->courier_name = $courier_name;
            $refence->weight_of_parcel = $weight_of_parcel;
            $refence->courier_receipt_no = $courier_receipt_no;
            $refence->courier_received_time = $courier_received_time;
            $refence->delivery_by = $delivery_by;
            $refence->save();
        }

        $ouids = Ouid::orderBy('id', 'DESC')->first('id');
        $nextAutoID = !empty($ouids) ? $ouids->id + 1 : 1;
        $ouid_ids = new Ouid();
        $ouid_ids->id = $nextAutoID;
        $ouid_ids->ouid = $ouid;
        $ouid_ids->financial_year_id = $financialid;
        $ouid_ids->save();

        if($courier_receipt_no == '') {
            $color_flag_id = 1;
        } else {
            $color_flag_id = 3;
        }

        $cmpTypeName = Company::where('id', $referncedata->companyid)->first();

        if ($cmpTypeName && $cmpTypeName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $cmpTypeName->company_type)->first();
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }
        $comboLastid = Comboids::orderBy('comboid', 'DESC')->first('comboid');
        $combo_id = !empty($comboLastid) ? $comboLastid->comboid + 1 : 1;

        $comboids = new Comboids();
        $comboids->comboid = $combo_id;
        $comboids->payment_id = 0;
        $comboids->iuid = 0;
        $comboids->ouid = $ouid;
        $comboids->system_module_id = '16';
        $comboids->general_ref_id = $ref_id;
        $comboids->generated_by = $user->employee_id;
        $comboids->assigned_to = $user->employee_id;
        $comboids->company_id = $referncedata->companyid;
        $comboids->supplier_id = 0;
        $comboids->company_type = $typeName;
        $comboids->followup_via = $ref_via;
        $comboids->inward_or_outward_via = $ref_via;
        $comboids->selection_date = $referncedata->datetime;
        $comboids->from_name = $from_name;
        $comboids->receipt_mode = 0;
        $comboids->receipt_amount = 0;
        $comboids->total = 0;
        $comboids->subject = 'Sale Bill Outward Details';
        $comboids->financial_year_id = $financialid;
        $comboids->attachments = '';
        $comboids->updated_by = Session::get('user')->employee_id;
        $comboids->inward_or_outward_flag = 2;
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
        $comboids->color_flag_id = $color_flag_id;
        $comboids->product_qty = 0;
        $comboids->fabric_meters = 0;
        $comboids->sample_return_qty = 0;
        $comboids->mobile_flag = 0;
        $comboids->is_deleted = 0;
        $comboids->save();

        $outwardLastid = Outward::orderBy('outward_id', 'DESC')->first('outward_id');
        $outward_id = !empty($outwardLastid) ? $outwardLastid->outward_id + 1 : 1;
        $outward = new Outward();
        $outward->outward_id = $outward_id;
        $outward->ouid = $ouid;
        $outward->outward_ref_via = 0;
        $outward->general_output_ref_id = $ref_id;
        $outward->new_or_old_outward = $reference;
        $outward->connected_outward = 0;
        $outward->outward_date = $referncedata->datetime;
        $outward->subject = 'Sale Bill Outward Details';
        $outward->employee_id = Session::get('user')->employee_id;
        $outward->type_of_outward = $ref_via;
        //$outward->receiver_number = '';
        $outward->from_number = '';
        $outward->company_id = $referncedata->companyid;
        $outward->supplier_id = 0;
        $outward->courier_name = $courier_name;
        $outward->weight_of_parcel = $weight_of_parcel;
        $outward->courier_receipt_no = $courier_receipt_no;
        $outward->courier_received_time = $courier_received_time;
        $outward->no_of_parcel = 0;
        $outward->from_name = $from_name;
        $outward->attachments = 0;
        $outward->remarks = '';
        $outward->latter_by_id = $letterid;
        $outward->delivery_by = $delivery_by;
        $outward->receiver_email_id = '';
        $outward->from_email_id = '';
        $outward->courier_agent = $agent_id;
        $outward->outward_courier_flag = '0';
        $outward->product_main_id = 0;
        $outward->product_image_id = 0;
        $outward->outward_link_with_id = 0;
        $outward->enquiry_complain_for = 0;
        $outward->client_remark = '';
        $outward->notify_client = 0;
        $outward->notify_md = 0;
        $outward->mark_as_draft = 0;
        $outward->outward_employee_id = 0;
        $outward->is_deleted = 0;
        $outward->save();

        $comboids = Comboids::where('comboid', $combo_id)->first();
        $comboids->inward_or_outward_id = $outward_id;
        $comboids->save();
        $saleBillDetails = '';
        foreach ($salebilldata as $salebill) {
            $outwardsalebillLastid = OutwardSaleBill::orderBy('id', 'DESC')->first('id');
            $outwardsalebill_id = !empty($outwardsalebillLastid) ? $outwardsalebillLastid->id + 1 : 1;
            $osb = new OutwardSaleBill();
            $osb->id = $outwardsalebill_id;
            $osb->outward_id = $outward_id;
            $osb->sale_bill_id = $salebill->id;
            $osb->financial_year_id = $salebill->fid;
            $osb->payment_id = 0;
            $osb->commission_id = 0;
            $osb->commission_invoice_id = 0;
            $osb->is_deleted = 0;
            $osb->save();

            $sb = SaleBill::where('sale_bill_id', $salebill->id)
                ->where('financial_year_id', $salebill->fid)
                ->first();
            $sb->done_outward = 1;
            $sb->save();
        }
        $data['outwardid'] = $outward_id;
        return $data;
    }

    public function insertPaymentOutward(Request $request) {
        $referncedata = json_decode($request->refenceform);
        $paymentdata = json_decode($request->payment);
        $reference = $referncedata->refrence;

        $user = Session::get('user');
        $financialid = Session::get('user')->financial_year_id;
        $agent_id = $referncedata->agent->id;
        $latter_by_id = $referncedata->referncevia->name;
        $from_name = $referncedata->fromname;
        if ($latter_by_id == 'Courier') {
            $ref_via = "Courier";
            $letterid = 1;
            $courier_name = $referncedata->courrier->id;
            $weight_of_parcel = $referncedata->weightparcel;//$_POST['weight_of_parcel'];
            $courier_receipt_no = $referncedata->reciptno;
            $courier_received_time = date('Y-m-d',strtotime($referncedata->recivetime));
            $delivery_by =$referncedata->delivery;
        } else {
            $ref_via = "Hand";
            $letterid = 0;
            $courier_name = '';
            $weight_of_parcel = $referncedata->weightparcel;//$_POST['weight_of_parcel'];
            $courier_receipt_no = '';
            $courier_received_time = date('Y-m-d',strtotime($referncedata->recivetime));
            $delivery_by =$referncedata->delivery;
        }

        if ($reference == '1') {

            $increment_id_details = IncrementId::where('financial_year_id', $financialid)->first();
            $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
            $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;

            if ($increment_id_details) {
                $ref_id = $increment_id_details->reference_id + 1;
                $ouid = $increment_id_details->ouid + 1;
                $increment_id = IncrementId::where('financial_year_id', $financialid)->first();
                $increment_id->reference_id = $ref_id;
                $increment_id->ouid = $ouid;
                $increment_id->save();
            } else {
                $ref_id = '1';
                $ouid = '1';
                $increment_id = new IncrementId();
                $increment_id->reference_id = $ref_id;
                $increment_id->id = $Incrementids;
                $increment_id->ouid = $ouid;
                $increment_id->financial_year_id = $financialid;
                $increment_id->save();
            }

            $refrenceLastid = ReferenceId::orderBy('id', 'DESC')->first('id');
            $refrenceid = !empty($refrenceLastid) ? $refrenceLastid->id + 1 : 1;


            $refence = new ReferenceId();
            $refence->id = $refrenceid;
            $refence->reference_id = $ref_id;
            $refence->financial_year_id = $financialid;
            $refence->employee_id = $user->employee_id;
            $refence->inward_or_outward = '0';
            $refence->type_of_inward = $ref_via;
            $refence->company_id = $referncedata->companyid;
            $refence->selection_date = $referncedata->datetime;
            $refence->from_name = $from_name;
            $refence->courier_name = $courier_name;
            $refence->weight_of_parcel = $weight_of_parcel;
            $refence->courier_receipt_no = $courier_receipt_no;
            $refence->courier_received_time = $courier_received_time;
            $refence->delivery_by = $delivery_by;
            $refence->save();
        }

        $ouids = Ouid::orderBy('id', 'DESC')->first('id');
        $nextAutoID = !empty($ouids) ? $ouids->id + 1 : 1;
        $ouid_ids = new Ouid();
        $ouid_ids->id = $nextAutoID;
        $ouid_ids->ouid = $ouid;
        $ouid_ids->financial_year_id = $financialid;
        $ouid_ids->save();

        $color_flag_id = 1;

        $cmpTypeName = Company::where('id', $referncedata->companyid)->first();

        if ($cmpTypeName && $cmpTypeName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $cmpTypeName->company_type)->first();
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }
        $comboLastid = Comboids::orderBy('comboid', 'DESC')->first('comboid');
        $combo_id = !empty($comboLastid) ? $comboLastid->comboid + 1 : 1;

        $comboids = new Comboids();
        $comboids->comboid = $combo_id;
        $comboids->payment_id = 0;
        $comboids->iuid = 0;
        $comboids->ouid = $ouid;
        $comboids->system_module_id = '17';
        $comboids->general_ref_id = $ref_id;
        $comboids->generated_by = $user->employee_id;
        $comboids->assigned_to = $user->employee_id;
        $comboids->company_id = 0;
        $comboids->supplier_id =  $referncedata->companyid;
        $comboids->company_type = $typeName;
        $comboids->followup_via = $ref_via;
        $comboids->inward_or_outward_via = $ref_via;
        $comboids->selection_date = $referncedata->datetime;
        $comboids->from_name = $from_name;
        $comboids->receipt_mode = 0;
        $comboids->receipt_amount = 0;
        $comboids->total = 0;
        $comboids->subject = 'Payment Outward Details';
        $comboids->financial_year_id = $financialid;
        $comboids->attachments = '';
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
        $comboids->color_flag_id = $color_flag_id;
        $comboids->product_qty = 0;
        $comboids->fabric_meters = 0;
        $comboids->sample_return_qty = 0;
        $comboids->mobile_flag = 0;
        $comboids->is_deleted = 0;
        $comboids->save();

        $outwardLastid = Outward::orderBy('outward_id', 'DESC')->first('outward_id');
        $outward_id = !empty($outwardLastid) ? $outwardLastid->outward_id + 1 : 1;
        $outward = new Outward();
        $outward->outward_id = $outward_id;
        $outward->ouid = $ouid;
        $outward->outward_ref_via = 0;
        $outward->general_output_ref_id = $ref_id;
        $outward->new_or_old_outward = $reference;
        $outward->connected_outward = 0;
        $outward->outward_date = $referncedata->datetime;
        $outward->subject = 'Payment Outward Details';
        $outward->employee_id = Session::get('user')->employee_id;
        $outward->type_of_outward = $ref_via;
        //$outward->receiver_number = '';
        $outward->from_number = '';
        $outward->company_id = 0;
        $outward->supplier_id =  $referncedata->companyid;
        $outward->courier_name = $courier_name;
        $outward->weight_of_parcel = $weight_of_parcel;
        $outward->courier_receipt_no = $courier_receipt_no;
        $outward->courier_received_time = $courier_received_time;
        $outward->no_of_parcel = 0;
        $outward->from_name = $from_name;
        $outward->attachments = 0;
        $outward->remarks = '';
        $outward->latter_by_id = $letterid;
        $outward->delivery_by = $delivery_by;
        $outward->receiver_email_id = '';
        $outward->from_email_id = '';
        $outward->courier_agent = $agent_id;
        $outward->outward_courier_flag = '1';
        $outward->product_main_id = 0;
        $outward->product_image_id = 0;
        $outward->outward_link_with_id = 0;
        $outward->enquiry_complain_for = 0;
        $outward->client_remark = '';
        $outward->notify_client = 0;
        $outward->notify_md = 0;
        $outward->mark_as_draft = 0;
        $outward->outward_employee_id = 0;
        $outward->is_deleted = 0;
        $outward->save();

        $comboids = Comboids::where('comboid', $combo_id)->first();
        $comboids->inward_or_outward_id = $outward_id;
        $comboids->save();
        $saleBillDetails = '';
        foreach ($paymentdata as $payment) {
            $outwardpaymentLastid = OutwardSaleBill::orderBy('id', 'DESC')->first('id');
            $outwardpayment_id = !empty($outwardpaymentLastid) ? $outwardpaymentLastid->id + 1 : 1;
            $osb = new OutwardSaleBill();
            $osb->id = $outwardpayment_id;
            $osb->outward_id = $outward_id;
            $osb->sale_bill_id = 0;
            $osb->payment_id = $payment->id;
            $osb->financial_year_id = $payment->fid;
            $osb->commission_id = 0;
            $osb->commission_invoice_id = 0;
            $osb->is_deleted = 0;
            $osb->save();

            $sb = Payment::where('payment_id', $payment->id)
                ->where('financial_year_id', $payment->fid)
                ->first();
            $sb->done_outward = 1;
            $sb->save();
        }
        $data['outwardid'] = $outward_id;
        return $data;
    }

    public function insertCommissionOutward(Request $request) {
        $referncedata = json_decode($request->refenceform);
        $commissondata = json_decode($request->commission);
        $reference = $referncedata->refrence;

        $user = Session::get('user');
        $financialid = Session::get('user')->financial_year_id;
        $agent_id = $referncedata->agent->id;
        $latter_by_id = $referncedata->referncevia->name;
        $from_name = $referncedata->fromname;
        if ($latter_by_id == 'Courier') {
            $ref_via = "Courier";
            $letterid = 1;
            $courier_name = $referncedata->courrier->id;
            $weight_of_parcel = $referncedata->weightparcel;//$_POST['weight_of_parcel'];
            $courier_receipt_no = $referncedata->reciptno;
            $courier_received_time = date('Y-m-d',strtotime($referncedata->recivetime));
            $delivery_by =$referncedata->delivery;
        } else {
            $ref_via = "Hand";
            $letterid = 0;
            $courier_name = '';
            $weight_of_parcel = $referncedata->weightparcel;//$_POST['weight_of_parcel'];
            $courier_receipt_no = '';
            $courier_received_time = date('Y-m-d',strtotime($referncedata->recivetime));
            $delivery_by =$referncedata->delivery;
        }

        if ($reference == '1') {

            $increment_id_details = IncrementId::where('financial_year_id', $financialid)->first();
            $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
            $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;

            if ($increment_id_details) {
                $ref_id = $increment_id_details->reference_id + 1;
                $ouid = $increment_id_details->ouid + 1;
                $increment_id = IncrementId::where('financial_year_id', $financialid)->first();
                $increment_id->reference_id = $ref_id;
                $increment_id->ouid = $ouid;
                $increment_id->save();
            } else {
                $ref_id = '1';
                $ouid = '1';
                $increment_id = new IncrementId();
                $increment_id->reference_id = $ref_id;
                $increment_id->id = $Incrementids;
                $increment_id->ouid = $ouid;
                $increment_id->financial_year_id = $financialid;
                $increment_id->save();
            }

            $refrenceLastid = ReferenceId::orderBy('id', 'DESC')->first('id');
            $refrenceid = !empty($refrenceLastid) ? $refrenceLastid->id + 1 : 1;


            $refence = new ReferenceId();
            $refence->id = $refrenceid;
            $refence->reference_id = $ref_id;
            $refence->financial_year_id = $financialid;
            $refence->employee_id = $user->employee_id;
            $refence->inward_or_outward = '0';
            $refence->type_of_inward = $ref_via;
            $refence->company_id = $referncedata->companyid;
            $refence->selection_date = $referncedata->datetime;
            $refence->from_name = $from_name;
            $refence->courier_name = $courier_name;
            $refence->weight_of_parcel = $weight_of_parcel;
            $refence->courier_receipt_no = $courier_receipt_no;
            $refence->courier_received_time = $courier_received_time;
            $refence->delivery_by = $delivery_by;
            $refence->save();
        }

        $ouids = Ouid::orderBy('id', 'DESC')->first('id');
        $nextAutoID = !empty($ouids) ? $ouids->id + 1 : 1;
        $ouid_ids = new Ouid();
        $ouid_ids->id = $nextAutoID;
        $ouid_ids->ouid = $ouid;
        $ouid_ids->financial_year_id = $financialid;
        $ouid_ids->save();

        $color_flag_id = 1;

        $cmpTypeName = Company::where('id', $referncedata->companyid)->first();

        if ($cmpTypeName && $cmpTypeName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $cmpTypeName->company_type)->first();
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }
        $comboLastid = Comboids::orderBy('comboid', 'DESC')->first('comboid');
        $combo_id = !empty($comboLastid) ? $comboLastid->comboid + 1 : 1;

        $comboids = new Comboids();
        $comboids->comboid = $combo_id;
        $comboids->payment_id = 0;
        $comboids->iuid = 0;
        $comboids->ouid = $ouid;
        $comboids->system_module_id = '18';
        $comboids->general_ref_id = $ref_id;
        $comboids->generated_by = $user->employee_id;
        $comboids->assigned_to = $user->employee_id;
        $comboids->company_id = 0;
        $comboids->supplier_id =  $referncedata->companyid;
        $comboids->company_type = $typeName;
        $comboids->followup_via = $ref_via;
        $comboids->inward_or_outward_via = $ref_via;
        $comboids->selection_date = $referncedata->datetime;
        $comboids->from_name = $from_name;
        $comboids->receipt_mode = 0;
        $comboids->receipt_amount = 0;
        $comboids->total = 0;
        $comboids->subject = 'Commission Outward Details';
        $comboids->financial_year_id = $financialid;
        $comboids->attachments = '';
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
        $comboids->color_flag_id = $color_flag_id;
        $comboids->product_qty = 0;
        $comboids->fabric_meters = 0;
        $comboids->sample_return_qty = 0;
        $comboids->mobile_flag = 0;
        $comboids->is_deleted = 0;
        $comboids->save();

        $outwardLastid = Outward::orderBy('outward_id', 'DESC')->first('outward_id');
        $outward_id = !empty($outwardLastid) ? $outwardLastid->outward_id + 1 : 1;
        $outward = new Outward();
        $outward->outward_id = $outward_id;
        $outward->ouid = $ouid;
        $outward->outward_ref_via = 0;
        $outward->general_output_ref_id = $ref_id;
        $outward->new_or_old_outward = $reference;
        $outward->connected_outward = 0;
        $outward->outward_date = $referncedata->datetime;
        $outward->subject = 'Commission Outward Details';
        $outward->employee_id = Session::get('user')->employee_id;
        $outward->type_of_outward = $ref_via;
        //$outward->receiver_number = '';
        $outward->from_number = '';
        $outward->company_id = 0;
        $outward->supplier_id =  $referncedata->companyid;
        $outward->courier_name = $courier_name;
        $outward->weight_of_parcel = $weight_of_parcel;
        $outward->courier_receipt_no = $courier_receipt_no;
        $outward->courier_received_time = $courier_received_time;
        $outward->no_of_parcel = 0;
        $outward->from_name = $from_name;
        $outward->attachments = 0;
        $outward->remarks = '';
        $outward->latter_by_id = $letterid;
        $outward->delivery_by = $delivery_by;
        $outward->receiver_email_id = '';
        $outward->from_email_id = '';
        $outward->courier_agent = $agent_id;
        $outward->outward_courier_flag = '1';
        $outward->product_main_id = 0;
        $outward->product_image_id = 0;
        $outward->outward_link_with_id = 0;
        $outward->enquiry_complain_for = 0;
        $outward->client_remark = '';
        $outward->notify_client = 0;
        $outward->notify_md = 0;
        $outward->mark_as_draft = 0;
        $outward->outward_employee_id = 0;
        $outward->is_deleted = 0;
        $outward->save();

        $comboids = Comboids::where('comboid', $combo_id)->first();
        $comboids->inward_or_outward_id = $outward_id;
        $comboids->save();
        $saleBillDetails = '';
        foreach ($commissondata as $commission) {
            $outwardcommissionLastid = OutwardSaleBill::orderBy('id', 'DESC')->first('id');
            $outwardcommission_id = !empty($outwardcommissionLastid) ? $outwardcommissionLastid->id + 1 : 1;
            $osb = new OutwardSaleBill();
            $osb->id = $outwardcommission_id;
            $osb->outward_id = $outward_id;
            $osb->sale_bill_id = 0;
            $osb->payment_id = 0;
            $osb->commission_id = $commission->id;
            $osb->financial_year_id = $commission->fid;
            $osb->commission_invoice_id = 0;
            $osb->is_deleted = 0;
            $osb->save();

            $sb = Commission::where('commission_id', $commission->id)
                ->where('financial_year_id', $commission->fid)
                ->first();
            $sb->done_outward = 1;
            $sb->save();
        }
        $data['outwardid'] = $outward_id;
        return $data;
    }

    public function insertCommissionInvoiceOutward(Request $request) {
        $referncedata = json_decode($request->refenceform);
        $commissoninvoicedata = json_decode($request->commissioninvoice);
        $reference = $referncedata->refrence;
        $company_supplier = Company::where('id', $referncedata->companyid)->first()->company_type_id;
        if ($company_supplier == 3) {
			$company_type_outward = "Supplier";
        } else {
			$company_type_outward = "Customer";
        }
        $user = Session::get('user');
        $financialid = Session::get('user')->financial_year_id;
        $agent_id = $referncedata->agent->id;
        $latter_by_id = $referncedata->referncevia->name;
        $from_name = $referncedata->fromname;
        if ($latter_by_id == 'Courier') {
            $ref_via = "Courier";
            $letterid = 1;
            $courier_name = $referncedata->courrier->id;
            $weight_of_parcel = $referncedata->weightparcel;//$_POST['weight_of_parcel'];
            $courier_receipt_no = $referncedata->reciptno;
            $courier_received_time = date('Y-m-d',strtotime($referncedata->recivetime));
            $delivery_by =$referncedata->delivery;
        } else {
            $ref_via = "Hand";
            $letterid = 0;
            $courier_name = '';
            $weight_of_parcel = $referncedata->weightparcel;//$_POST['weight_of_parcel'];
            $courier_receipt_no = '';
            $courier_received_time = date('Y-m-d',strtotime($referncedata->recivetime));
            $delivery_by =$referncedata->delivery;
        }

        if ($reference == '1') {

            $increment_id_details = IncrementId::where('financial_year_id', $financialid)->first();
            $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
            $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;

            if ($increment_id_details) {
                $ref_id = $increment_id_details->reference_id + 1;
                $ouid = $increment_id_details->ouid + 1;
                $increment_id = IncrementId::where('financial_year_id', $financialid)->first();
                $increment_id->reference_id = $ref_id;
                $increment_id->ouid = $ouid;
                $increment_id->save();
            } else {
                $ref_id = '1';
                $ouid = '1';
                $increment_id = new IncrementId();
                $increment_id->reference_id = $ref_id;
                $increment_id->id = $Incrementids;
                $increment_id->ouid = $ouid;
                $increment_id->financial_year_id = $financialid;
                $increment_id->save();
            }

            $refrenceLastid = ReferenceId::orderBy('id', 'DESC')->first('id');
            $refrenceid = !empty($refrenceLastid) ? $refrenceLastid->id + 1 : 1;


            $refence = new ReferenceId();
            $refence->id = $refrenceid;
            $refence->reference_id = $ref_id;
            $refence->financial_year_id = $financialid;
            $refence->employee_id = $user->employee_id;
            $refence->inward_or_outward = '0';
            $refence->type_of_inward = $ref_via;
            $refence->company_id = $referncedata->companyid;
            $refence->selection_date = $referncedata->datetime;
            $refence->from_name = $from_name;
            $refence->courier_name = $courier_name;
            $refence->weight_of_parcel = $weight_of_parcel;
            $refence->courier_receipt_no = $courier_receipt_no;
            $refence->courier_received_time = $courier_received_time;
            $refence->delivery_by = $delivery_by;
            $refence->save();
        }

        $ouids = Ouid::orderBy('id', 'DESC')->first('id');
        $nextAutoID = !empty($ouids) ? $ouids->id + 1 : 1;
        $ouid_ids = new Ouid();
        $ouid_ids->id = $nextAutoID;
        $ouid_ids->ouid = $ouid;
        $ouid_ids->financial_year_id = $financialid;
        $ouid_ids->save();

        $color_flag_id = 1;

        $cmpTypeName = Company::where('id', $referncedata->companyid)->first();

        if ($cmpTypeName && $cmpTypeName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $cmpTypeName->company_type)->first();
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }
        $comboLastid = Comboids::orderBy('comboid', 'DESC')->first('comboid');
        $combo_id = !empty($comboLastid) ? $comboLastid->comboid + 1 : 1;

        $comboids = new Comboids();
        if ($company_supplier == 3) {
            $comboids->supplier_id = $referncedata->companyid;
            $comboids->company_id = 0;
        } else {
            $comboids->supplier_id = 0;
            $comboids->company_id = $referncedata->companyid;
        }

        $comboids->comboid = $combo_id;
        $comboids->payment_id = 0;
        $comboids->iuid = 0;
        $comboids->ouid = $ouid;
        $comboids->system_module_id = '21';
        $comboids->general_ref_id = $ref_id;
        $comboids->generated_by = $user->employee_id;
        $comboids->assigned_to = $user->employee_id;
        $comboids->company_type = $typeName;
        $comboids->followup_via = $ref_via;
        $comboids->inward_or_outward_via = $ref_via;
        $comboids->selection_date = $referncedata->datetime;
        $comboids->from_name = $from_name;
        $comboids->receipt_mode = 0;
        $comboids->receipt_amount = 0;
        $comboids->total = 0;
        $comboids->subject = 'Commission Invoice Outward Details';
        $comboids->financial_year_id = $financialid;
        $comboids->attachments = '';
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
        $comboids->color_flag_id = $color_flag_id;
        $comboids->product_qty = 0;
        $comboids->fabric_meters = 0;
        $comboids->sample_return_qty = 0;
        $comboids->mobile_flag = 0;
        $comboids->is_deleted = 0;
        $comboids->save();

        $outwardLastid = Outward::orderBy('outward_id', 'DESC')->first('outward_id');
        $outward_id = !empty($outwardLastid) ? $outwardLastid->outward_id + 1 : 1;
        $outward = new Outward();
        $outward->outward_id = $outward_id;
        $outward->ouid = $ouid;
        $outward->outward_ref_via = 0;
        $outward->general_output_ref_id = $ref_id;
        $outward->new_or_old_outward = $reference;
        $outward->connected_outward = 0;
        $outward->outward_date = $referncedata->datetime;
        $outward->subject = 'Commission Invoice Outward Details';
        $outward->employee_id = Session::get('user')->employee_id;
        $outward->type_of_outward = $ref_via;
        //$outward->receiver_number = '';
        $outward->from_number = '';
        $outward->company_id = 0;
        $outward->supplier_id =  $referncedata->companyid;
        $outward->courier_name = $courier_name;
        $outward->weight_of_parcel = $weight_of_parcel;
        $outward->courier_receipt_no = $courier_receipt_no;
        $outward->courier_received_time = $courier_received_time;
        $outward->no_of_parcel = 0;
        $outward->from_name = $from_name;
        $outward->attachments = 0;
        $outward->remarks = '';
        $outward->latter_by_id = $letterid;
        $outward->delivery_by = $delivery_by;
        $outward->receiver_email_id = '';
        $outward->from_email_id = '';
        $outward->courier_agent = $agent_id;
        $outward->outward_courier_flag = '1';
        $outward->product_main_id = 0;
        $outward->product_image_id = 0;
        $outward->outward_link_with_id = 0;
        $outward->enquiry_complain_for = 0;
        $outward->client_remark = '';
        $outward->notify_client = 0;
        $outward->notify_md = 0;
        $outward->mark_as_draft = 0;
        $outward->outward_employee_id = 0;
        $outward->is_deleted = 0;
        $outward->save();

        $comboids = Comboids::where('comboid', $combo_id)->first();
        $comboids->inward_or_outward_id = $outward_id;
        $comboids->save();
        $saleBillDetails = '';
        foreach ($commissoninvoicedata as $invoice) {
            $outwardcommissioninvoiceLastid = OutwardSaleBill::orderBy('id', 'DESC')->first('id');
            $outwardcommissioninvoice_id = !empty($outwardcommissioninvoiceLastid) ? $outwardcommissioninvoiceLastid->id + 1 : 1;
            $osb = new OutwardSaleBill();
            $osb->id = $outwardcommissioninvoice_id;
            $osb->outward_id = $outward_id;
            $osb->sale_bill_id = 0;
            $osb->payment_id = 0;
            $osb->commission_id = 0;
            $osb->commission_invoice_id = $invoice->id;
            $osb->financial_year_id = $invoice->fid;
            $osb->is_deleted = 0;
            $osb->save();

            $sb = CommissionInvoice::where('id', $invoice->id)
                ->where('financial_year_id', $invoice->fid)
                ->first();
            $sb->done_outward = 1;
            $sb->save();
        }
        $data['outwardid'] = $outward_id;
        return $data;
    }

    public function outward() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['excelAccess'] = $user->excel_access;
        return view('register.outward.outward',compact('financialYear'))->with('employees', $employees);
    }

    public function inward() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['excelAccess'] = $user->excel_access;
        return view('register.inward.inward',compact('financialYear'))->with('employees', $employees);
    }

    public function outwardList(Request $request) {
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
            $columnName = 'outwards.'.$columnName;
        }

        $totalRecords = Outward::where('is_deleted', '0')->select('count(*) as count')->count();

        $totalRecordswithFilter = Outward::where('is_deleted', '0');
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('ouid', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('created_at', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('subject', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('employe_id', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('type_of_outward', '=', $columnName_arr[4]['search']['value']);
        }

        $totalRecordswithFilter = $totalRecordswithFilter->count();


        $records = Outward::where('is_deleted', '0');
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $records = $records->where('ouid', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $records = $records->where('created_at', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $records = $records->where('subject', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $records = $records->where('employe_id', '=', $columnName_arr[3]['search']['value']);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $records = $records->where('type_of_outward', '=', $columnName_arr[4]['search']['value']);
        }

        // Fetch records
        $records = $records->select('*');

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();

        $data_arr = array();
        foreach($records as $record){
            $outward_id = $record->outward_id;
            $ouid = $record->ouid;
            $date = date_format($record->created_at, 'Y/m/d H:i:s');
            $subject = $record->subject;
            $generatedby = Employee::where('id', $record->employee_id)->first()->firstname;
            $type = $record->type_of_outward;
            $action = '<a href="/register/outward/outward-view/'.$record->outward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Print"><em class="icon ni ni-file-docs"></em></a><a href="/register/view-outward/'.$record->outward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/register/edit-outward/'.$record->outward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';

            $data_arr[] = array(
                "outward_id" => $outward_id,
                "ouid" => $ouid,
                "created_at" => $date,
                "subject" => $subject,
                "generatedby" => $generatedby,
                "type" => $type,
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

    public function InwardList(Request $request) {
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
            $columnName = 'inwards.'.$columnName;
        }

        $totalRecords = Inward::where('is_deleted', '0')->select('count(*) as count')->count();

        $totalRecordswithFilter = Inward::where('is_deleted', '0');
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('iuid', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('created_at', 'ilike', $columnName_arr[1]['search']['value']. '%');
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('subject', 'ilike', '%' . $columnName_arr[2]['search']['value'] . '%');
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $emp_id = DB::table('employees')->select('id')->where('firstname', 'ilike', '%' . $columnName_arr[3]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('employee_id', $emp_id);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('type_of_inward', 'ilike', '%' . $columnName_arr[4]['search']['value']. '%');
        }

        $totalRecordswithFilter = $totalRecordswithFilter->count();


        $records = Inward::where('is_deleted', '0');
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $records = $records->where('iuid', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $records = $records->whereDate('created_at', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $records = $records->where('subject', 'ilike', '%' . $columnName_arr[2]['search']['value']. '%');
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $emp_id = DB::table('employees')->select('id')->where('firstname', 'ilike', '%' . $columnName_arr[3]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->whereIn('employee_id', $emp_id);
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $records = $records->where('type_of_inward', 'ilike', '%' . $columnName_arr[4]['search']['value']. '%');
        }

        // Fetch records
        $records = $records->select('*');

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();

        $data_arr = array();
        foreach($records as $record){
            $inward_id = $record->inward_id;
            $iuid = $record->iuid;
            $date = date_format($record->created_at, 'd/m/Y H:i:s');
            $subject = $record->subject;
            $generatedby = Employee::where('id', $record->employee_id)->first()->firstname;
            $type = $record->type_of_inward;
            $action = '<a href="/register/view-inward/'.$record->inward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/register/edit-inward/'.$record->inward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';

            $data_arr[] = array(
                "inward_id" => $inward_id,
                "iuid" => $iuid,
                "created_at" => $date,
                "subject" => $subject,
                "generatedby" => $generatedby,
                "type_of_inward" => $type,
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

    public function viewOutward($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['excelAccess'] = $user->excel_access;
        $employees['id'] = $id;
        return view('register.outward.viewoutward',compact('financialYear'))->with('employees', $employees);
    }

    public function outwardView($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['excelAccess'] = $user->excel_access;
        $employees['id'] = $id;
        return view('register.outward.outwardview',compact('financialYear'))->with('employees', $employees);
    }

    public function viewInward($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['excelAccess'] = $user->excel_access;
        $employees['id'] = $id;
        return view('register.inward.viewinward',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchInward($id) {
        $user = Session::get('user');
        $inward = Inward::where('inward_id', $id)
                ->first();
        $created_at = $date = date_format($inward->created_at, 'Y/m/d H:i:s');
        $employee = Employee::where('id', $inward->employee_id)->first()->firstname;

        if ($inward->company_id) {
            $company = Company::where('id', $inward->company_id)->first();
        } else {
            $company = Company::where('id', $inward->supplier_id)->first();
        }
        $comboid = Comboids::where('inward_or_outward_id', $id)->where('iuid', $inward->iuid)->where('financial_year_id', $inward->financial_year_id)->first();
        $assignemp = Employee::select("*", DB::raw("CONCAT(firstname,' ',lastname) as name"))->where('id', $comboid->assigned_to)->first();
        $courier = '';
        if (!empty($inward->courier_name)) {
            $courier = TransportDetails::where('id', $inward->courier_name)->first();
        }

        $data['inward'] = $inward;
        if ($inward->sample_for  == 1) {
            $data['inward']['samplefor'] = 'Product';
        } else if ($inward->sample_for  == 2) {
            $data['inward']['samplefor'] = 'Fabric';
        } else if ($inward->sample_for  == 3) {
            $data['inward']['samplefor'] = 'Unit';
        } else {
            $data['inward']['samplefor'] = '';
        }
        $attch = explode(',', trim(trim($inward->attachments,'"[\"'), '\"]"'));
        $itmdata = array();
        foreach ($attch as $itm) {
            $item = trim(trim($itm,'"'), '\"');
            array_push($itmdata, $item);
        }
        $sample = InwardSample::where('inward_id', $id)->where('is_deleted', 0)->get();
        $sampledata = array();
        foreach ($sample as $itm) {
            array_push($sampledata, array('name'=> $itm->name, 'price' => $itm->price, 'quantity' => $itm->qty, 'image' => $itm->image, 'meter' => $itm->meters));
        }

        $data['inward']['attachment'] = $itmdata;
        $data['inward']['courier'] = $courier;
        $data['inward']['todaydate'] = Carbon::now()->format('Y-m-d');
        $data['inward']['recivedate'] = substr($inward->courier_received_time,0,10);
        $data['inward']['recivedateedit'] = str_replace(" ", "T", $inward->courier_received_time);
        $data['sample'] = $sampledata;

        $data['inward']['assignemp'] = $assignemp;
        $data['inward']['generatedate'] = $created_at;
        $data['inward']['generateby'] = $employee;
        $data['inward']['company'] = $company;
        $data['inward']['comboids'] = $comboid;
        return $data;
    }

    public function fetchOutward($id) {
        $user = Session::get('user');
        $outward = Outward::where('outward_id', $id)
                ->first();
        $created_at = $date = date_format($outward->created_at, 'Y/m/d H:i:s');
        $employee = Employee::where('id', $outward->employee_id)->first()->firstname;
        $agent = Agent::where('id', $outward->courier_agent)->first();
        if ($outward->subject == 'Sale Bill Outward Details') {
            $type = 1;
        } else if ($outward->subject == 'Payment Outward Details') {
            $type = 2;
        } else if ($outward->subject == 'Commission Outward Details') {
            $type = 3;
        } else if ($outward->subject == 'Commission Invoice Outward Details') {
            $type = 4;
        }


        if ($outward->company_id) {
            $company = Company::where('id', $outward->company_id)->first()->company_name;
        } else {
            $company = Company::where('id', $outward->supplier_id)->first()->company_name;
        }
        $outwardSalebill = OutwardSaleBill::where('outward_id', $id)->get();
        $salebilldata = array();
        foreach ($outwardSalebill as $salebill) {
            if ($type == 1) {
                $transport = DB::table('sale_bill_transports as a')
                            ->join('transport_details as b', 'a.transport_id', '=', 'b.id')
                            ->where('a.sale_bill_id', $salebill->sale_bill_id)
                            ->where('a.financial_year_id', $user->financial_year_id)
                            ->where('a.is_deleted', 0)
                            ->select('a.*', 'b.name as name')
                            ->first();
                $salebilldetail = DB::table('sale_bills')
                                ->where('sale_bill_id', $salebill->sale_bill_id)
                                ->where('financial_year_id', $salebill->financial_year_id)
                                ->where('is_deleted', 0)
                                ->first();
                $supplier = Company::where('id', $salebilldetail->supplier_id)->first();
                $salebill['salebilldetail'] = $salebilldetail;
                $salebill['company_name'] = $supplier->company_name;
                $salebill['transport'] = $transport;
                array_push($salebilldata, $salebill);

            } else if ($type == 2) {
                $paymentDetail = DB::table('payments')
                                ->where('payment_id', $salebill->payment_id)
                                ->where('financial_year_id', $salebill->financial_year_id)
                                ->where('is_deleted', 0)
                                ->first();
                $customer = Company::where('id', $paymentDetail->receipt_from)->first();
                $salebill['paymentdetail'] = $paymentDetail;
                $salebill['company_name'] = $customer->company_name;
                array_push($salebilldata, $salebill);

            } else if ($type == 3) {
                $commissionDetail = DB::table('commissions')
                                ->where('commission_id', $salebill->commission_id)
                                ->where('financial_year_id', $salebill->financial_year_id)
                                ->where('is_deleted', 0)
                                ->first();
                $agent = Agent::where('id', $commissionDetail->commission_account)->first();
                if ($commissionDetail->cheque_dd_bank) {
                    $bank = BankDetails::where('id', $commissionDetail->cheque_dd_bank)->first();
                    $salebill['bank'] = $bank->name;
                } else {
                    $salebill['bank'] = '-';
                }
                $salebill['commissionDetail'] = $commissionDetail;
                $salebill['account'] = $agent->name;

                array_push($salebilldata, $salebill);

            } else if ($type == 4) {
                $invoicedetail = DB::table('commission_invoices')
                                ->where('id', $salebill->commission_invoice_id)
                                ->where('financial_year_id', $salebill->financial_year_id)
                                ->where('is_deleted', 0)
                                ->first();
                $customer = Company::where('id', $invoicedetail->supplier_id)->first();
                $salebill['invoicedetail'] = $invoicedetail;
                $salebill['billdate'] = date('d-m-Y', strtotime($invoicedetail->bill_date));
                $salebill['date_add'] = date('d-m-Y H:i:s',strtotime($invoicedetail->created_at));
                $salebill['company_name'] = $customer->company_name;
                array_push($salebilldata, $salebill);
            }

        }
        $courier = '';
        if (!empty($outward->courier_name)) {
            $courier = TransportDetails::where('id', $outward->courier_name)->first();
        }

        $agent = Agent::where('id', $outward->courier_agent)->first();
        $data['salebill'] = $salebilldata;
        $data['outward'] = $outward;
        $data['outward']['courier_agent'] = $agent;
        $data['outward_type'] = $type;
        $data['outward']['courier'] = $courier;
        $data['outward']['todaydate'] = Carbon::now()->format('Y-m-d');
        $data['outward']['recivedate'] = substr($outward->courier_received_time,0,10);

        $data['outward']['generatedate'] = $created_at;
        $data['outward']['generateby'] = $employee;
        $data['outward']['company'] = $company;
        return $data;
    }

    public function editOutward($id){
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['editedId'] = $id;
        return view('register.outward.editoutward',compact('financialYear'))->with('employees', $employees);
    }

    public function editInward($id){
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $inward = Inward::where('inward_id', $id)->first();

        if ($inward->type_of_inward == 'call'){
            $type = 1;
        } else if ($inward->type_of_inward == 'message') {
            $type = 2;
        } else if ($inward->type_of_inward == 'whatsapp') {
            $type = 3;
        } else if ($inward->type_of_inward == 'letter') {
            $type = 4;
        } else if ($inward->type_of_inward == 'sample') {
            $type = 5;
        } else if ($inward->type_of_inward == 'email') {
            $type = 6;
        }
        $employees['inwardType'] =  $type;
        $employees['editedId'] = $id;
        $employees['scope'] = 'edit';
        return view('register.inward.editinward',compact('financialYear'))->with('employees', $employees);
    }

    public function updateOutward(Request $request) {

        $referncedata = json_decode($request->refenceform);
        $outwarddata = Outward::where('outward_id', $referncedata->id)->first();

        $reference = $referncedata->refrence;
        $company_supplier = Company::where('id', $referncedata->companyid)->first()->company_type_id;
        if ($company_supplier == 3) {
			$company_type_outward = "Supplier";
        } else {
			$company_type_outward = "Customer";
        }
        $user = Session::get('user');
        $financialid = Session::get('user')->financial_year_id;
        $agent_id = $referncedata->agent->id;
        $latter_by_id = $referncedata->referncevia->name;
        $from_name = $referncedata->fromname;
        if ($latter_by_id == 'Courier') {
            $ref_via = "Courier";
            $letterid = 1;
            $courier_name = $referncedata->courrier->id;
            $weight_of_parcel = $referncedata->weightparcel;//$_POST['weight_of_parcel'];
            $courier_receipt_no = $referncedata->reciptno;
            $courier_received_time = date('Y-m-d',strtotime($referncedata->recivetime));
            $delivery_by =$referncedata->delivery;
        } else {
            $ref_via = "Hand";
            $letterid = 0;
            $courier_name = '';
            $weight_of_parcel = $referncedata->weightparcel;//$_POST['weight_of_parcel'];
            $courier_receipt_no = '';
            $courier_received_time = date('Y-m-d',strtotime($referncedata->recivetime));
            $delivery_by =$referncedata->delivery;
        }
        $refence = ReferenceId::where('reference_id', $outwarddata->general_output_ref_id)
                    ->where('financial_year_id', $user->financial_year_id)
                    ->first();

        $refence->employee_id = $user->employee_id;
        $refence->inward_or_outward = '0';
        $refence->type_of_inward = $ref_via;
        $refence->company_id = $referncedata->companyid;
        $refence->selection_date = $referncedata->datetime;
        $refence->from_name = $from_name;
        $refence->courier_name = $courier_name;
        $refence->weight_of_parcel = $weight_of_parcel;
        $refence->courier_receipt_no = $courier_receipt_no;
        $refence->courier_received_time = $courier_received_time;
        $refence->delivery_by = $delivery_by;
        $refence->save();

        $cmpTypeName = Company::where('id', $referncedata->companyid)->first();

        if ($cmpTypeName && $cmpTypeName->company_type != 0) {
            $companyTypeName = CompanyType::where('id', $cmpTypeName->company_type)->first();
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }

        $comboids = Comboids::where('ouid', $outwarddata->ouid)->first();
        $outward = Outward::where('outward_id', $referncedata->id)->first();
        if ($company_supplier == 3) {
            $comboids->supplier_id = $referncedata->companyid;
            $comboids->company_id = 0;
            $outward->company_id = 0;
            $outward->supplier_id =  $referncedata->companyid;
        } else {
            $comboids->supplier_id = 0;
            $comboids->company_id = $referncedata->companyid;
            $outward->company_id = $referncedata->companyid;
            $outward->supplier_id = 0;
        }


        $comboids->generated_by = $user->employee_id;
        $comboids->assigned_to = $user->employee_id;
        $comboids->company_type = $typeName;
        $comboids->followup_via = $ref_via;
        $comboids->inward_or_outward_via = $ref_via;
        $comboids->selection_date = $referncedata->datetime;
        $comboids->from_name = $from_name;
        $comboids->updated_by = Session::get('user')->employee_id;
        $comboids->inward_or_outward_flag = 2;
        $comboids->save();


        $outward->new_or_old_outward = $reference;
        $outward->connected_outward = 0;
        $outward->outward_date = $referncedata->datetime;
        $outward->employee_id = Session::get('user')->employee_id;
        $outward->type_of_outward = $ref_via;
        //$outward->receiver_number = '';
        $outward->from_number = '';

        $outward->courier_name = $courier_name;
        $outward->weight_of_parcel = $weight_of_parcel;
        $outward->courier_receipt_no = $courier_receipt_no;
        $outward->courier_received_time = $courier_received_time;
        $outward->no_of_parcel = 0;
        $outward->from_name = $from_name;
        $outward->attachments = 0;
        $outward->remarks = '';
        $outward->latter_by_id = $letterid;
        $outward->delivery_by = $delivery_by;
        $outward->receiver_email_id = '';
        $outward->from_email_id = '';
        $outward->courier_agent = $agent_id;
        $outward->outward_courier_flag = '1';
        $outward->save();

    }

    public function insertInward(Request $request) {
        $inward_data = json_decode($request->inwarddata);
        $sampledata =  json_decode($request->sampleData);
        $attechments = $request->attechment;
        $files = array();
        if ($attechments) {
            foreach ($attechments as $attechment) {
                $attechmentfile = date('YmdHis') . "_inward." . $attechment->getClientOriginalExtension();
                $attechment->move(public_path('upload/Inwards/'), $attechmentfile);
                array_push($files, $attechmentfile);
            }
        }

        $user = Session::get('user');
        $financialid = $user->financial_year_id;
        if($inward_data->notify_client) {
            $notify_clients = 1;
        } else {
            $notify_clients = 0;
        }

        if($inward_data->notify_md) {
            $notify_md = 1;
        } else {
            $notify_md = 0;
        }

        $latterBy_id = $inward_data->sample_via->id;
        $ref_id = $inward_data->reference_sample_data;


        if ($latterBy_id == 2) {
            $latter_by_id = $latterBy_id;
            $courier_name = $inward_data->courier_name->id;
            $weight_of_parcel = $inward_data->weight_of_parcel;
            $courier_receipt_no = $inward_data->courier_receipt_number;
            $courier_received_time = date('Y-m-d',strtotime($inward_data->received_date_time));
            $delivery_by = $inward_data->delivery_by;
        } else {
            $latter_by_id = $latterBy_id;
            $courier_name = '';
            $weight_of_parcel = $inward_data->weight_of_parcel;
            $courier_receipt_no = '';
            $courier_received_time = date('Y-m-d',strtotime($inward_data->received_date_time));
            $delivery_by = $inward_data->delivery_by;
        }

        $receiver_number = '';
		$from_number = '';
		$from_name = $inward_data->from_name;

        $company_id = $inward_data->companyid;
        $companytype = $inward_data->companytype;
        $typeName = DB::table('company_types')->where('id', $companytype)->first();
        $increment_id_details = IncrementId::where('financial_year_id', $financialid)->first();
        $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
        $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;

        if ($increment_id_details) {
            $iuid = $increment_id_details->iuid + 1;
            $increment_id = IncrementId::where('financial_year_id', $financialid)->first();
            $increment_id->iuid = $iuid;
            $increment_id->save();
        } else {
            $iuid = '1';
            $increment_id = new IncrementId();
            $increment_id->id = $Incrementids;
            $increment_id->iuid = $iuid;
            $increment_id->financial_year_id = $financialid;
            $increment_id->save();
        }
        $iuids = Iuid::orderBy('id', 'DESC')->first('id');
        $nextAutoID = !empty($iuids) ? $iuids->id + 1 : 1;
        $iuid_data = new Iuid();
        $iuid_data->id = $nextAutoID;
        $iuid_data->iuid = $iuid;
        $iuid_data->financial_year_id = $financialid;
        $iuid_data->save();

        $subject = "Sample for ". $inward_data->company;
        $comboids = new Comboids();
        if ($companytype == 3) {
            $comboids->supplier_id = $inward_data->companyid;
            $comboids->company_id = 0;
        } else {
            $comboids->supplier_id = 0;
            $comboids->company_id = $inward_data->companyid;
        }
        $comboLastid = Comboids::orderBy('comboid', 'DESC')->first('comboid');
        $combo_id = !empty($comboLastid) ? $comboLastid->comboid + 1 : 1;

        $comboids->comboid = $combo_id;
        $comboids->payment_id = 0;
        $comboids->iuid = $iuid;
        $comboids->ouid = 0;
        $comboids->system_module_id = '15';
        $comboids->general_ref_id = $ref_id;
        $comboids->generated_by = $user->employee_id;
        $comboids->assigned_to = $inward_data->assign_to->id;
        $comboids->company_type = $typeName->name;
        $comboids->followup_via = $inward_data->sample_via->name;
        $comboids->inward_or_outward_via = $inward_data->sample_via->name;
        $comboids->selection_date = $inward_data->dateTime;
        $comboids->from_name = $from_name;
        //$combo_id->receiver_number = "".$inward_data['receiver_number'];
        //$combo_id->receiver_email_id = $inward_data['receiver_email'];
        $comboids->receipt_mode = 0;
        $comboids->receipt_amount = 0;
        $comboids->total = 0;
        $comboids->subject = $subject;
        $comboids->financial_year_id = $financialid;
        $comboids->attachments = json_encode($files);
        $comboids->action_date = $inward_data->assignToDateTime;
        $comboids->action_instruction = $inward_data->instruction;
        $comboids->updated_by = Session::get('user')->employee_id;
        $comboids->inward_or_outward_flag = 1;
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

        $inward = new Inward();
        if ($companytype == 3) {
            $inward->supplier_id = $inward_data->companyid;
            $inward->company_id = 0;
        } else {
            $inward->supplier_id = 0;
            $inward->company_id = $inward_data->companyid;
        }
        $insertLastid = Inward::orderBy('inward_id', 'DESC')->first('inward_id');
        $inward_id = !empty($insertLastid) ? $insertLastid->inward_id + 1 : 1;

        $inward->inward_id = $inward_id;
        $inward->inward_date = $inward_data->dateTime;
        $inward->inward_ref_via = $inward_data->sample_via->id;
        $inward->sample_via = $inward_data->sample_via->name;
        $inward->sample_for = $inward_data->sample_for->id;
        $inward->connected_inward = 0;
        $inward->product_main_id = 0;
        $inward->product_image_id = 0;
        $inward->inward_link_with_id = 0;
        $inward->mark_as_draft = 0;
        $inward->general_input_ref_id = $ref_id;
        $inward->new_or_old_inward = 1;
        $inward->receiver_number = $inward_data->receiver_number;
        //$inward->from_number = '';
        $inward->latter_by_id = $latter_by_id;
        $inward->courier_name = (int)$courier_name;
        $inward->weight_of_parcel = $weight_of_parcel;
        $inward->courier_receipt_no = $courier_receipt_no;
        $inward->courier_received_time = $courier_received_time;
        $inward->delivery_by = $delivery_by;
        $inward->from_name = $from_name;
        $inward->to_name = '';
        $inward->delivery_period = 0;
        $inward->attachments = json_encode($files);
        $inward->remarks = $inward_data->remark;
        $inward->client_remark = $inward_data->remark ? $inward_data->remark : '';
        $inward->employee_id = Session::get('user')->employee_id;
        $inward->type_of_inward = 'sample';
        $inward->subject = $subject;
        $inward->notify_client = $notify_clients;
        $inward->notify_md = $notify_md;
        $inward->iuid = $iuid;
        $inward->financial_year_id = $financialid;
        $inward->enquiry_complain_for = 0;
        $inward->product_qty = 0;
        $inward->fabric_meters = 0;
        $inward->is_deleted = 0;
        $inward->save();

        $comboids = Comboids::where('comboid', $combo_id)->first();
        $comboids->inward_or_outward_id = $inward_id;
        $comboids->save();

        if ($sampledata){
            $key = 0;
            foreach($sampledata as $sample) {
                $samplefile = '';
                if($request->pimage[$key] != 'null') {
                    $samplefile = date('YmdHis') . "_inwardsample." . $request->pimage[$key]->getClientOriginalExtension();
                    $request->pimage[$key]->move(public_path('upload/InwardSample/'), $samplefile);
                }
                $inwardsample = new InwardSample();
                if ($sample->quantity == '') {
                    $qty = 0;
                } else {
                    $qty = $sample->quantity;
                }
                if ($inward_data->sample_for->id == 1 || $inward_data->sample_for->id == 3) {
                    $inwardsample->qty = $qty;
                    $inwardsample->meters = 0;
                } else {
                    $inwardsample->meters = $qty;
                    $inwardsample->qty = 0;
                }
                $insertLastid = InwardSample::orderBy('inward_sample_id', 'DESC')->first('inward_sample_id');
                $inwardsample_id = !empty($insertLastid) ? $insertLastid->inward_sample_id + 1 : 1;
                $inwardsample->inward_sample_id = $inwardsample_id;
                $inwardsample->inward_id = $inward_id;
                $inwardsample->name = $sample->name;
                $inwardsample->price = $sample->price == '' ? 0 : $sample->price;
                $inwardsample->image = $samplefile;
                $inwardsample->is_deleted = 0;
                $inwardsample->save();
                $key++;
            }
        }
    }

    public function updateInward(Request $request) {

        $inward_data = json_decode($request->inwarddata);
        $sampledata =  json_decode($request->sampleData);

        $updateinward = Inward::where('inward_id', $inward_data->id)->first();
        $attechments = $request->attechment;
        $files = array();
        if ($attechments) {
            foreach ($attechments as $attechment) {
                $attechmentfile = date('YmdHis') . "_inward." . $attechment->getClientOriginalExtension();
                $attechment->move(public_path('upload/Inwards/'), $attechmentfile);
                array_push($files, $attechmentfile);
            }
        }

        $user = Session::get('user');
        $financialid = $updateinward->financial_year_id;
        if($inward_data->notify_client) {
            $notify_clients = 1;
        } else {
            $notify_clients = 0;
        }

        if($inward_data->notify_md) {
            $notify_md = 1;
        } else {
            $notify_md = 0;
        }

        $latterBy_id = $inward_data->sample_via->id;
        $ref_id = $inward_data->reference_sample_data;

        $typeName = DB::table('company_types')->where('id', $inward_data->company->company_type)->first();
        if ($latterBy_id == 2) {
            $latter_by_id = $latterBy_id;
            $courier_name = $inward_data->courier_name->id;
            $weight_of_parcel = $inward_data->weight_of_parcel;
            $courier_receipt_no = $inward_data->courier_receipt_number;
            $courier_received_time = date('Y-m-d',strtotime($inward_data->received_date_time));
            $delivery_by = $inward_data->delivery_by;
        } else {
            $latter_by_id = $latterBy_id;
            $courier_name = '';
            $weight_of_parcel = $inward_data->weight_of_parcel;
            $courier_receipt_no = '';
            $courier_received_time = date('Y-m-d',strtotime($inward_data->received_date_time));
            $delivery_by = $inward_data->delivery_by;
        }

        $receiver_number = '';
		$from_number = '';
		$from_name = $inward_data->from_name;
        if ($updateinward->company_id == $inward_data->company->id || $updateinward->supplier_id == $inward_data->company->id)
        {
            $general_ref_no = $inward_data->reference_sample_data;
            $companyId = $inward_data->company->id;
        } else {
            $increment_id_details = IncrementId::where('financial_year_id', $financialid)->first();
            $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
            $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;
            $companyId = $inward_data->company->id;
            if ($increment_id_details) {
                $general_ref_no = $increment_id_details->reference_id + 1;
                $increment_id = IncrementId::where('financial_year_id', $financialid)->first();
                $increment_id->reference_id = $general_ref_no;
                $increment_id->save();
            } else {
                $general_ref_no = '1';
                $increment_id = new IncrementId();
                $increment_id->id = $Incrementids;
                $increment_id->reference_id = $general_ref_no;
                $increment_id->financial_year_id = $financialid;
                $increment_id->save();
            }

            $refrenceLastid = ReferenceId::orderBy('id', 'DESC')->first('id');
            $refrenceid = !empty($refrenceLastid) ? $refrenceLastid->id + 1 : 1;
            $refence = new ReferenceId();
            $refence->id = $refrenceid;
            $refence->reference_id = $general_ref_no;
            $refence->financial_year_id = $financialid;
            $refence->employee_id = $user->employee_id;
            $refence->inward_or_outward = 1;
            $refence->type_of_inward = $inward_data->sample_via->name;
            $refence->company_id = $inward_data->company->id;
            $refence->selection_date = $inward_data->dateTime;
            $refence->from_name = $inward_data->from_name;
            $refence->from_number = $from_number;
            $refence->receiver_number = $receiver_number;
            $refence->from_email_id = '';
            $refence->courier_name = $courier_name;
            $refence->weight_of_parcel = $weight_of_parcel;
            $refence->courier_receipt_no = $courier_receipt_no;
            $refence->courier_received_time = $courier_received_time;
            $refence->delivery_by = $delivery_by;
            $refence->save();
        }

        $subject = "Sample for". $inward_data->company->company_name;
        $comboids = Comboids::where('iuid', $updateinward->iuid)->where('financial_year_id', $updateinward->financial_year_id)->first();
        if ($inward_data->company->company_type == 3) {
            $comboids->supplier_id = $inward_data->company->id;
            $comboids->company_id = 0;
        } else {
            $comboids->supplier_id = 0;
            $comboids->company_id = $inward_data->company->id;
        }
        $comboids->general_ref_id = $general_ref_no;
        $comboids->assigned_to = $inward_data->assign_to->id;
        $comboids->company_type = $typeName->name;
        $comboids->followup_via = $inward_data->sample_via->name;
        $comboids->inward_or_outward_via = $inward_data->sample_via->name;
        $comboids->selection_date = $inward_data->dateTime;
        $comboids->from_name = $inward_data->from_name;
        //$combo_id->receiver_number = "".$inward_data['receiver_number'];
        //$combo_id->receiver_email_id = $inward_data['receiver_email'];
        $comboids->receipt_mode = 0;
        $comboids->receipt_amount = 0;
        $comboids->total = 0;
        $comboids->subject = $subject;
        $comboids->financial_year_id = $financialid;
        $comboids->attachments = json_encode($files);
        $comboids->action_date = $inward_data->assignToDateTime;
        $comboids->action_instruction = $inward_data->instruction;
        $comboids->updated_by = Session::get('user')->employee_id;

        $comboids->save();

        $inward = Inward::where('inward_id', $inward_data->id)->first();
        if ($inward_data->company->company_type == 3) {
            $inward->supplier_id = $inward_data->company->id;
            $inward->company_id = 0;
        } else {
            $inward->supplier_id = 0;
            $inward->company_id = $inward_data->company->id;
        }

        $inward->inward_date = $inward_data->dateTime;
        $inward->inward_ref_via = $inward_data->sample_via->id;
        $inward->sample_via = $inward_data->sample_via->name;
        $inward->sample_for = $inward_data->sample_for->id;

        $inward->general_input_ref_id = $general_ref_no;
        $inward->receiver_number = $inward_data->receiver_number;
        //$inward->from_number = '';
        $inward->latter_by_id = $latter_by_id;
        $inward->courier_name = (int)$courier_name;
        $inward->weight_of_parcel = $weight_of_parcel;
        $inward->courier_receipt_no = $courier_receipt_no;
        $inward->courier_received_time = $courier_received_time;
        $inward->delivery_by = $delivery_by;
        $inward->from_name = $from_name;
        $inward->to_name = '';
        $inward->delivery_period = 0;
        $inward->attachments = json_encode($files);
        $inward->remarks = $inward_data->remark;
        $inward->client_remark = $inward_data->client_comments ? $inward_data->client_comments : '';
        $inward->employee_id = Session::get('user')->employee_id;
        $inward->subject = $subject;
        $inward->notify_client = $notify_clients;
        $inward->notify_md = $notify_md;
        $inward->financial_year_id = $financialid;
        $inward->enquiry_complain_for = 0;
        $inward->product_qty = 0;
        $inward->fabric_meters = 0;
        $inward->is_deleted = 0;

        $inward->save();

        if ($sampledata){
            $key = 0;
            InwardSample::where('inward_id', $inward_data->id)->delete();
            foreach($sampledata as $sample) {
                $samplefile = '';
                if($request->pimage[$key] != 'null') {
                    if (is_file($request->pimage[$key])){
                        $samplefile = date('YmdHis') . "_inwardsample." . $request->pimage[$key]->getClientOriginalExtension();
                        $request->pimage[$key]->move(public_path('upload/InwardSample/'), $samplefile);
                    } else {
                        $samplefile = $sample->image;
                    }
                }
                $inwardsample = new InwardSample();
                if ($sample->quantity == '') {
                    $qty = 0;
                } else {
                    $qty = $sample->quantity;
                }
                if ($inward_data->sample_for->id == 1 || $inward_data->sample_for->id == 3) {
                    $inwardsample->qty = $qty;
                    $inwardsample->meters = 0;
                } else {
                    $inwardsample->meters = $qty;
                    $inwardsample->qty = 0;
                }
                $insertLastid = InwardSample::orderBy('inward_sample_id', 'DESC')->first('inward_sample_id');
                $inwardsample_id = !empty($insertLastid) ? $insertLastid->inward_sample_id + 1 : 1;
                $inwardsample->inward_sample_id = $inwardsample_id;
                $inwardsample->inward_id = $inward_data->id;
                $inwardsample->name = $sample->name;
                $inwardsample->price = $sample->price == '' ? 0 : $sample->price;
                $inwardsample->image = $samplefile;
                $inwardsample->is_deleted = 0;
                $inwardsample->save();
                $key++;
            }
        }
    }
}
