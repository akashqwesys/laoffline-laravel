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
        $user = Session::get('user');
        $financialid = Session::get('user')->financial_year_id;
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

        if ($request->notify_client){
            $notify_clients = '1';
        } else {
            $notify_clients = '0';
        }

        if($request->notify_md) {
            $notify_md = '1';
        } else {
            $notify_md = '0';
        }

        if ($inward_type == 'email') {
            $receiver_email_id = $request->receiver_email_id;
            $from_email_id = $request->from_email_id;
        } else {
            $receiver_email_id = '';
            $from_email_id = '';
        }

        if ($inward_type == 'sample') {
            if ($request->sample_via->id == '1') {
                $latterBy_id = $request->sample_via->name;
                $courier_name = "";
                $weight_of_parcel = $request->weight_of_parcel;
                $courier_receipt_no = '';
                $courier_received_time = Carbon::now()->format('d-m-Y');
                $delivery_by = $request->delivery_by; 
            } else if ($request->sample_via->id == '2') {
                $latterBy_id = $request->sample_via->name;
                $courier_name = $request->courier_name->id;
                $weight_of_parcel = $request->weight_of_parcel;
                $courier_receipt_no = $request->courier_receipt_number;
                $courier_received_time = $request->received_date_time;
                $delivery_by = $request->delivery_by;
            }
        } else {
            $latterBy_id  = "";
            $courier_name = "";
            $weight_of_parcel = "";
            $courier_receipt_no = "";
            $courier_received_time = Carbon::now()->format('d-m-Y');
            $delivery_by = "";
        }

        $receiver_number = '';
        $from_number = '';
        $from_name = $request->from_name;

        if ($inward_type != 'sample') {
            $inward_link_with_id = $request->link_with;
        } else {
            $inward_link_with_id = 0;
        }
        $assigned_to = $request->assign_to->id;
        $supplierID = 0;

        if ($inward_type != 'sample') {
          $supplierID = $request->supplier->id;  
        } 
        if ($inward_type != 'sample') {
            $company_id = $request->company->id;  
        } else {
            $company_id = $request->company->id;
        }
        $companyType_id = Company::where('company_id', $company_id)->first();
        if($companyType_id && $companyType_id->company_type_id != '0') {
            $companyTypeName = $this->dbinward->getCompanyTypeName($companyType_id->company_type_id);
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }

        $increment_id_details = IncrementId::where('financial_year_id', Session::get('user')->financial_year_id)->first();
        $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
        $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;

        if ($increment_id_details) {
            $ref_id = $increment_id_details->reference_id + 1;
            $iuid = $increment_id_details->iuid + 1;
            $increment_id = IncrementId::where('financial_year_id', $financialid)->first();
            $increment_id->reference_id = $ref_id;
            $increment_id->payment_id = $payment_id;
            $increment_id->iuid = $iuid;
            $increment_id->save();
        } else {
            $ref_id = '1';
            $iuid = '1';
            $increment_id = new IncrementId();
            $increment_id->reference_id = $ref_id;
            $increment_id->payment_id = $payment_id;
            $increment_id->id = $Incrementids;
            $increment_id->iuid = $iuid;
            $increment_id->financial_year_id = $financialid;
            $increment_id->save();
        }

        $iuids = Iuid::orderBy('id', 'DESC')->first('id');
        $nextAutoID = !empty($iuids) ? $iuids->id + 1 : 1;

        $iuid_ids = new Iuid();
        $iuid_ids->id = $nextAutoID;
        $iuid_ids->iuid = $iuid;
        $iuid_ids->financial_year_id = $financialid;
        $iuid_ids->save();

        $refrenceLastid = ReferenceId::orderBy('id', 'DESC')->first('id');
        $refrenceid = !empty($refrenceLastid) ? $refrenceLastid->id + 1 : 1;

        $refence = new ReferenceId();
        $refence->id = $refrenceid;
        $refence->reference_id = $ref_id;
        $refence->financial_year_id = $financialid;
        $refence->employee_id = $user->employee_id;
        $refence->inward_or_outward = '1';
        $refence->type_of_inward = $inward_type;
        $refence->company_id = (int)$company_id;
        $refence->selection_date = $request->dateTime;
        $refence->from_name = $request->from_name;
        $refence->from_email_id = $from_email_id;
        $refence->courier_name = $courier_name;
        $refence->weight_of_parcel = $weight_of_parcel;
        $refence->courier_receipt_no = $courier_receipt_no;
        $refence->courier_received_time = $courier_received_time;
        $refence->delivery_by = $delivery_by;
        $refence->save();

        $comboLastid = Comboids::orderBy('comboid', 'DESC')->first('comboid');
        $combo_id = !empty($comboLastid) ? $comboLastid->comboid + 1 : 1;

        $comboids = new Comboids();
        $comboids->comboid = $combo_id;
        $comboids->payment_id = $payment_id;
        $comboids->iuid = $iuid;
        $comboids->ouid = 0;
        $comboids->system_module_id = '6';
        $comboids->general_ref_id = $ref_id;
        $comboids->main_or_followup = '0';
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
        $comboids->required_followup = 0;
        $comboids->is_completed = 0;
        $comboids->mark_as_draft = 0;
        $comboids->color_flag_id = 0;
        $comboids->product_qty = 0;
        $comboids->fabric_meters = 0;
        $comboids->sample_return_qty = 0;
        $comboids->mobile_flag = 0;
        $comboids->is_deleted = 0;
        $comboids->save();
        
    }

    public function insertOutward(Request $request) {
        $outward_type = $request->type;
        $user = Session::get('user');
        $financialid = Session::get('user')->financial_year_id;
        if ($outward_type == 'email') {
            $folder_name = 'mail';
        } else {
            $folder_name = $outward_type;
        }
        $attachments = array();
        foreach ($multipleattechment as $attechment) {
            $attechmentImage = '';
            $attechmentImage = rand() . "_grattechment." . $attechment->getClientOriginalExtension();
            $attechment->move(public_path('upload/app_ws/'.$folder_name.'/'.date('d-m-Y',strtotime($request->dateTime))), $attechmentImage);
            array_push($attachments, $attechmentImage);
        }

        if ($request->notify_client){
            $notify_clients = '1';
        } else {
            $notify_clients = '0';
        }

        if($request->notify_md) {
            $notify_md = '1';
        } else {
            $notify_md = '0';
        }

        if ($outward_type == 'email') {
            $receiver_email_id = $request->receiver_email_id;
            $from_email_id = $request->from_email_id;
        } else {
            $receiver_email_id = '';
            $from_email_id = '';
        }

        if ($outward_type == 'sample') {
            if ($request->sample_via->id == '1') {
                $latterBy_id = $request->sample_via->name;
                $courier_name = "";
                $weight_of_parcel = $request->weight_of_parcel;
                $courier_receipt_no = '';
                $courier_received_time = Carbon::now()->format('d-m-Y');
                $delivery_by = $request->delivery_by; 
            } else if ($request->sample_via->id == '2') {
                $latterBy_id = $request->sample_via->name;
                $courier_name = $request->courier_name->id;
                $weight_of_parcel = $request->weight_of_parcel;
                $courier_receipt_no = $request->courier_receipt_number;
                $courier_received_time = $request->received_date_time;
                $delivery_by = $request->delivery_by;
            }
        } else {
            $latterBy_id  = "";
            $courier_name = "";
            $weight_of_parcel = "";
            $courier_receipt_no = "";
            $courier_received_time = Carbon::now()->format('d-m-Y');
            $delivery_by = "";
        }

        $receiver_number = '';
        $from_number = '';
        $from_name = $request->from_name;

        if ($outward_type != 'sample') {
            $inward_link_with_id = $request->link_with;
        } else {
            $inward_link_with_id = 0;
        }
        $assigned_to = $request->assign_to->id;
        $supplierID = 0;

        if ($outward_type != 'sample') {
          $supplierID = $request->supplier->id;  
        } 
        if ($outward_type != 'sample') {
            $company_id = $request->company->id;  
        } else {
            $company_id = $request->company->id;
        }
        $companyType_id = Company::where('company_id', $company_id)->first();
        if($companyType_id && $companyType_id->company_type_id != '0') {
            $companyTypeName = $this->dbinward->getCompanyTypeName($companyType_id->company_type_id);
            $typeName = $companyTypeName->name;
        } else {
            $typeName = '';
        }

        $outward_link_with_id = $request->link_with->id;

        if ($outward_link_with_id == 6) {
            $typeId = 1;
        } else {    
            $typeId = $request->reference_via->id;    
        } 

        $general_ref = ReferenceId::join('companies', 'reference_ids.company_id', '=', 'companies.company_id')
                    ->where('reference_ids.type_of_inward', ucfirst($outward_type))
                    ->where('reference_ids.inward_or_outward', 0)
                    ->where('reference_ids.employe_id', $user->employee_id)
                    ->where('companies.company_type', $typeId)
                    ->select('reference_ids.*')
                    ->orderBy('reference_ids.reference_id', 'DESC')
                    ->limit(1)
                    ->get();

        if($outward_link_with_id == 1) {
			$sys_module_id = 8;
		} elseif ($outward_link_with_id == 2) {
			$sys_module_id = 9;
		} elseif ($outward_link_with_id == 3) {
			$sys_module_id = 10;
		} elseif ($outward_link_with_id == 6) {
			$sys_module_id = 11;
		}

        $outwardType = InwardLinkWith::where('inward_link_with_id', $outward_link_with_id)->first();
        
        $increment_id_details = IncrementId::where('financial_year_id', Session::get('user')->financial_year_id)->first();
        $IncrementLastid = IncrementId::orderBy('id', 'DESC')->first('id');
        $Incrementids = !empty($IncrementLastid) ? $IncrementLastid->id + 1 : 1;

        if ($increment_id_details) {
            $ref_id = $increment_id_details->reference_id + 1;
            $ouid = $increment_id_details->iuid + 1;
            $increment_id = IncrementId::where('financial_year_id', $financialid)->first();
            $increment_id->reference_id = $ref_id;
            $increment_id->payment_id = $payment_id;
            $increment_id->ouid = $ouid;
            $increment_id->save();
        } else {
            $ref_id = '1';
            $ouid = '1';
            $increment_id = new IncrementId();
            $increment_id->reference_id = $ref_id;
            $increment_id->payment_id = $payment_id;
            $increment_id->id = $Incrementids;
            $increment_id->ouid = $ouid;
            $increment_id->financial_year_id = $financialid;
            $increment_id->save();
        }

        $ouids = Ouid::orderBy('id', 'DESC')->first('id');
        $nextAutoID = !empty($ouids) ? $ouids->id + 1 : 1;

        $ouid_ids = new Ouid();
        $ouid_ids->id = $nextAutoID;
        $ouid_ids->iuid = $ouid;
        $ouid_ids->financial_year_id = $financialid;
        $ouid_ids->save();

        $refrenceLastid = ReferenceId::orderBy('id', 'DESC')->first('id');
        $refrenceid = !empty($refrenceLastid) ? $refrenceLastid->id + 1 : 1;

        $refence = new ReferenceId();
        $refence->id = $refrenceid;
        $refence->reference_id = $ref_id;
        $refence->financial_year_id = $financialid;
        $refence->employee_id = $user->employee_id;
        $refence->inward_or_outward = '0';
        $refence->type_of_inward = ucfirst($outward_type);
        $refence->company_id = (int)$company_id;
        $refence->selection_date = $request->dateTime;
        $refence->from_name = $request->from_name;
        $refence->from_email_id = $from_email_id;
        $refence->courier_name = $courier_name;
        $refence->weight_of_parcel = $weight_of_parcel;
        $refence->courier_receipt_no = $courier_receipt_no;
        $refence->courier_received_time = $courier_received_time;
        $refence->delivery_by = $delivery_by;
        $refence->save();

        $comboLastid = Comboids::orderBy('comboid', 'DESC')->first('comboid');
        $combo_id = !empty($comboLastid) ? $comboLastid->comboid + 1 : 1;

        $comboids = new Comboids();
        $comboids->comboid = $combo_id;
        $comboids->payment_id = $payment_id;
        $comboids->iuid = '0';
        $comboids->ouid = $ouid;
        $comboids->system_module_id = '6';
        $comboids->general_ref_id = $ref_id;
        $comboids->main_or_followup = '0';
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
        $comboids->required_followup = 0;
        $comboids->is_completed = 0;
        $comboids->mark_as_draft = 0;
        $comboids->color_flag_id = 0;
        $comboids->product_qty = 0;
        $comboids->fabric_meters = 0;
        $comboids->sample_return_qty = 0;
        $comboids->mobile_flag = 0;
        $comboids->is_deleted = 0;
        $comboids->save();
    }
                            
}
