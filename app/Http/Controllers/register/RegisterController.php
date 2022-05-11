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
use App\Models\Comboids\Comboids;
use App\Models\ProductsImages;
use App\Models\InwardLinkWith;
use App\Models\EnjayCallRecordsId;
use App\Models\ProductCategory;
use App\Models\Company\Company;
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

    public function addOutward($type) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        if($type == 'call') {
            $employees['outwardType'] = 1;

        } elseif($type == 'message') {
            $employees['outwardType'] = 2;

        } elseif($type == 'whatsapp') {
            $employees['outwardType'] = 3;

        } elseif($type == 'letter') {
            $employees['outwardType'] = 4;

        } elseif($type == 'sample') {
            $employees['outwardType'] = 5;

        } elseif($type == 'email') {
            $employees['outwardType'] = 6;
        }

        return view('register.outward.insertOutward',compact('financialYear'))->with('employees', $employees);
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

    public function insertInward(Request $request){
        $inward_type = $request->type;

        if ($inward_type == 'email') {
            $folder_name = 'mail';
        } else {
            $folder_name = $inward_type;
        }
        $attachments = array();
        foreach ($multipleattechment as $attechment) {
            $attechmentImage = '';
            $attechmentImage = rand() . "_grattechment." . $attechment->getClientOriginalExtension();
            $attechment->move(public_path('upload/app_ws/'.$folder_name.'/'.date('d-m-Y',strtotime($request->dateTime))), $attechmentImage);
            array_push($attachments, $attechmentImage);
        }

        
    }
                            
}
