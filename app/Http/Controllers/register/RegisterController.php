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
use App\Models\inwardOutward\outward;
use App\Models\CompanyType;
use App\Models\OutwardSaleBill;
use App\Models\ProductsImages;
use App\Models\InwardLinkWith;
use App\Models\EnjayCallRecordsId;
use App\Models\ProductCategory;
use App\Models\Payment;
use App\Models\settings\TransportDetails;
use App\Models\settings\Agent;
use App\Models\Company\Company;
use App\Models\Commission\Commission;
use App\Models\Commission\CommissionInvoice;
use App\Models\Company\CompanyContactDetails;
use App\Models\Reference\ReferenceId;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

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
        $suppliers = Company::where('company_type', 3)->get();

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

    public function getReferenceDetails($type, $flag, $refrenceVia) {
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
    }

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

    public function getReferenceSampleData() {
        $user = Session::get('user');
        $references = ReferenceId::join('companies', 'reference_ids.company_id', '=', 'companies.id')->
                                   where('companies.company_type', 2)->
                                   orwhere('companies.company_type', 3)->
                                   where('reference_ids.type_of_inward', 'Courier')->
                                   where('reference_ids.employee_id', 15)->
                                   where('reference_ids.inward_or_outward', 1)->
                                   where('reference_ids.financial_year_id', $user->financial_year_id)->
                                   where('reference_ids.is_deleted', 0)->
                                   orderBy('reference_ids.reference_id', 'DESC')->
                                   take('5')->
                                   get(['reference_ids.*']);

        return $references;
    }

    public function getOldReferenceDetails($inwardRefSearch, $typeOfInward, $inwardType) {
        print_r($inwardRefSearch); echo "<br><br>";
        print_r($typeOfInward); echo "<br><br>";
        print_r($inwardType); echo "<br><br>";

        
        dd("HELLO");
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
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('system_module_id', '=', $columnName_arr[4]['search']['value']);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('inward_or_outward_via', '=', $columnName_arr[5]['search']['value']);
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
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('company_type', '=', $columnName_arr[8]['search']['value']);
        }
        if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
            $generated_id = DB::table('employees')->select('id')->where('firstname', 'ilike', '%' . $columnName_arr[9]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->where('generated_by', '=', $columnName_arr[8]['search']['value']);
        }
        if (isset($columnName_arr[10]['search']['value']) && !empty($columnName_arr[10]['search']['value'])) {
            $assign_id = DB::table('employees')->select('id')->where('firstname', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->where('assigned_to', '=', $columnName_arr[10]['search']['value']);
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
            $records = $records->whereDate('company_type', '=', $columnName_arr[8]['search']['value']);
        }
        if (isset($columnName_arr[9]['search']['value']) && !empty($columnName_arr[9]['search']['value'])) {
            $generated_id = DB::table('employees')->select('id')->where('firstname', 'ilike', '%' . $columnName_arr[9]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->where('generated_by', '=', $columnName_arr[8]['search']['value']);
        }
        if (isset($columnName_arr[10]['search']['value']) && !empty($columnName_arr[10]['search']['value'])) {
            $assign_id = DB::table('employees')->select('id')->where('firstname', 'ilike', '%' . $columnName_arr[7]['search']['value'] . '%')->pluck('id')->toArray();
            $records = $records->where('assigned_to', '=', $columnName_arr[10]['search']['value']);
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
                $iomedium = 'Email';
            } else if($record->inward_or_outward_via == 'Hand') {
                $iomedium = 'Hand';
            } else if($record->inward_or_outward_via == 'Courier') {
                $iomedium = 'Courier';
            }
            $iodetail = $record->subject;
            $customer_company = Company::where('id', $record->company_id)->first();
            $seller_company = Company::where('id', $record->supplier_id)->first();
            if (empty($customer_company)) {
                $company = '';  
            } else {
                $company = '<a href="#" class="view-details text-danger" data-id="' . $customer_company->id . '">' . $customer_company->company_name . '</a>';
            }
            if (empty($seller_company)) {
                $supplier = '';  
            } else {
                $supplier = '<a href="#" class="view-details text-danger" data-id="' . $seller_company->id . '">' . $seller_company->company_name . '</a>';
            }
           
            $cmptype = $record->company_type;
            $genrateby = Employee::where('id', $record->generated_by)->first()->firstname;
            $assignto = Employee::where('id', $record->assigned_to)->first()->firstname;
            
            if ($record->system_module_id == 5) {
                $action = '<a href="/account/sale-bill/view-sale-bill/'.$record->sale_bill_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/account/sale-bill/edit-sale-bill/'.$record->sale_bill_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';
            } else if ($record->system_module_id == 6) {
                $action = '';
            } else if ($record->system_module_id == 7) {
                $action = '';
            } else {
                $action = '';
            }

            $data_arr[] = array(
                "iuid" => $iuid,
                "ouid" => $ouid,
                "referenceid" => $ref_id,
                "date" => $date,
                "timecompleted" => $timecompleted,
                "iotype" => $iotype,
                "iomedium" => $iomedium,
                "iodetail" => $iodetail,
                "company" => $company,
                "supplier" => $supplier,
                "cmptype" => $cmptype,
                "genby" => $genrateby,
                "assignto" => $assignto,
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
        $buyer = Company::where('company_type', 2)->get();
       
        $data['buyer'] = $buyer;
        
        return $data;
    }

    public function listAgentCourier() {
        $courier = TransportDetails::where('is_delete', 0)->get();
        $agent = Agent::where('is_delete', 0)->get();
       
        $data['courier'] = $courier;
        $data['agent'] = $agent;
        return $data;
    }

    public function listSupplier() {
        $supplier = Company::where('company_type', 3)->get();
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
                  ->where('sale_bills.financial_year_id', $user->financial_year_id)
                  ->whereBetween('sale_bills.created_at', [$request->fromdate." 00:00:00", $request->todate." 00:00:00"])
                  ->where('sale_bills.sale_bill_flag', 0)
                  ->whereNot('sale_bills.done_outward', 1)
                  ->select('sale_bills.*', 'companies.company_name')
                  ->get();
        $data['salebill'] = $salebill;
        $data['company'] = $company;
        return $data;
    }
    public function searchPayment(Request $request) {
        $user = Session::get('user');
        $company = Company::where('id', $request->supplier)->first();
        $payment = Payment::join('companies', 'companies.id', '=', 'payments.customer_id')
                  ->where('payments.supplier_id', $request->supplier)
                  ->where('payments.financial_year_id', $user->financial_year_id)
                  ->whereBetween('payments.created_at', [$request->fromdate." 00:00:00", $request->todate." 00:00:00"])
                  ->whereNot('payments.done_outward', 1)
                  ->select('payments.*', 'companies.company_name')
                  ->get();
        $data['payment'] = $payment;
        $data['company'] = $company;
        return $data;
    }

    public function searchCommission(Request $request) {
        $user = Session::get('user');
        $company = Company::where('id', $request->supplier)->first();
        $commission = Commission::where('supplier_id', $request->supplier)
                  ->where('financial_year_id', $user->financial_year_id)
                  ->whereBetween('created_at', [$request->fromdate." 00:00:00", $request->todate." 00:00:00"])
                  ->whereNot('done_outward', 1)
                  ->get();
        $data['commission'] = $commission;
        $data['company'] = $company;
        return $data;
    }

    public function searchCommissionInvoice(Request $request) {
        $user = Session::get('user');
        $company = Company::where('id', $request->supplier)->first();
        $commissioninvoice = CommissionInvoice::where('supplier_id', $request->supplier)
                  ->where('financial_year_id', $user->financial_year_id)
                  ->whereBetween('created_at', [$request->date." 00:00:00", $request->date." 23:59:59"])
                  ->whereNot('done_outward', 1)
                  ->get();
        $data['commissioninvoice'] = $commissioninvoice;
        $data['company'] = $company;
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
            $courier_name = $referncedata->courrier->name;
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

        $outwardLastid = outward::orderBy('outward_id', 'DESC')->first('outward_id');
        $outward_id = !empty($outwardLastid) ? $outwardLastid->outward_id + 1 : 1;
        $outward = new outward();
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
            $osb->sale_bill_id = $salebill;
            $osb->payment_id = 0;
            $osb->commission_id = 0;
            $osb->commission_invoice_id = 0;
            $osb->is_deleted = 0;
            $osb->save();

            $sb = SaleBill::where('sale_bill_id', $salebill)
                ->where('financial_year_id', $financialid)
                ->first();
            $sb->done_outward = 1;
            $sb->save();
        }
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
            $courier_name = $referncedata->courrier->name;
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

        $outwardLastid = outward::orderBy('outward_id', 'DESC')->first('outward_id');
        $outward_id = !empty($outwardLastid) ? $outwardLastid->outward_id + 1 : 1;
        $outward = new outward();
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
            $osb->payment_id = $payment;
            $osb->commission_id = 0;
            $osb->commission_invoice_id = 0;
            $osb->is_deleted = 0;
            $osb->save();

            $sb = Payment::where('payment_id', $payment)
                ->where('financial_year_id', $financialid)
                ->first();
            $sb->done_outward = 1;
            $sb->save();
        }
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
            $courier_name = $referncedata->courrier->name;
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

        $outwardLastid = outward::orderBy('outward_id', 'DESC')->first('outward_id');
        $outward_id = !empty($outwardLastid) ? $outwardLastid->outward_id + 1 : 1;
        $outward = new outward();
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
            $osb->commission_id = $commission;
            $osb->commission_invoice_id = 0;
            $osb->is_deleted = 0;
            $osb->save();

            $sb = Commission::where('commission_id', $commission)
                ->where('financial_year_id', $financialid)
                ->first();
            $sb->done_outward = 1;
            $sb->save();
        }
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
            $courier_name = $referncedata->courrier->name;
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

        $outwardLastid = outward::orderBy('outward_id', 'DESC')->first('outward_id');
        $outward_id = !empty($outwardLastid) ? $outwardLastid->outward_id + 1 : 1;
        $outward = new outward();
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
            $osb->commission_invoice_id = $invoice;
            $osb->is_deleted = 0;
            $osb->save();

            $sb = CommissionInvoice::where('id', $invoice)
                ->where('financial_year_id', $financialid)
                ->first();
            $sb->done_outward = 1;
            $sb->save();
        }
    }
     
    public function outward() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['excelAccess'] = $user->excel_access;
        return view('register.outward.outward',compact('financialYear'))->with('employees', $employees);
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

        $totalRecords = outward::where('is_deleted', '0')->select('count(*) as count')->count();

        $totalRecordswithFilter = outward::where('is_deleted', '0');
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


        $records = outward::where('is_deleted', '0');
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
            $action = '<a href="/register/view-outward/'.$record->outward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="show"><em class="icon ni ni-eye"></em></a><a href="/register/edit-outward/'.$record->outward_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>';

            $data_arr[] = array(
                "outward_id" => $outward_id,
                "ouid" => $ouid,
                "date" => $date,
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

    public function viewOutward($id) {
        
    }
}
