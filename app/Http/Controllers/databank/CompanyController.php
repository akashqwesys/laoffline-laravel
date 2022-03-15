<?php

namespace App\Http\Controllers\databank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\CompanyCategory;
use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\Company\Company;
use App\Models\Company\CompanyAddress;
use App\Models\Company\CompanyAddressOwner;
use App\Models\Company\CompanyBankDetails;
use App\Models\Company\CompanyContactDetails;
use App\Models\Company\CompanyEmails;
use App\Models\Company\CompanyPackagingDetails;
use App\Models\Company\CompanyReferences;
use App\Models\Company\CompanySwotDetails;
use App\Models\Settings\Designation;
use App\Models\CompanyType;
use App\Models\Settings\Country;
use App\Models\Settings\State;
use App\Models\Settings\Cities;
use App\Models\Settings\TypeOfAddress;
use Illuminate\Support\Facades\Session;
use Carbon;

class CompanyController extends Controller
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

        return view('databank.companies.company',compact('financialYear'))->with('employees', $employees);
    }

    public function essentialCompany() {
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

        return view('databank.companies.essentialCompany',compact('financialYear'))->with('employees', $employees);
    }

    public function createCompany() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('databank.companies.createCompany',compact('financialYear'))->with('employees', $employees);
    }

    public function listData(Request $request) {
        $company = Company::where('is_delete', '0')->get();

        return $company;
    }

    public function listCompany(Request $request) {
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

        // Total records
        $totalRecords = Company::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Company::select('count(*) as allcount')->
                                           where('company_name', 'like', '%' .$searchValue . '%')->
                                           count();

        // Fetch records
        $company = Company::orderBy($columnName,$columnSortOrder)->
                            where('company_name', 'like', '%' .$searchValue . '%')->
                            where('is_delete', 0)->
                            skip($start)->
                            take($rowperpage)->
                            get();
        
        $data_arr = array();
        $sno = $start+1;

        foreach($company as $cmp) {
            $id = $cmp->id;
            $companyType = '';

            if($cmp->company_type == 1) {
                $companyType = 'General';
            } elseif($cmp->company_type == 2) {
                $companyType = 'Customer';
            } elseif($cmp->company_type == 3) {
                $companyType = 'Supplier';
            }

            if(!empty($cmp->company_category)) {
                if(is_array(json_decode($cmp->company_category))) {
                    $companyName = [];
                    $companyArr = json_decode($cmp->company_category);
        
                    foreach($companyArr as $key => $c) {
                        $companyCat = CompanyCategory::where('id', $c)->first('category_name');
                        $companyName[$key] = $companyCat->category_name;
                    }

                } else {
                    $companyCatId = json_decode($cmp->company_category);

                    $companyCat = CompanyCategory::where('id', $companyCatId)->first('category_name');
                    $companyName = $companyCat->category_name;
                }
                $companyCategory = is_array($companyName) ? implode(", ", $companyName) : $companyName;
            } else {
                $companyCategory = '';
            }

            if(!empty($cmp->company_landline)){
                if(is_array(json_decode($cmp->company_landline))) {
                    $landlineNo = json_decode($cmp->company_landline);

                    $cmp['company_landline'] = implode(", ", $landlineNo);
                } else {
                    $cmp['company_landline'] = json_decode($cmp->company_landline);
                }
            } else {
                $cmp['company_landline'] = '';
            }

            if(!empty($cmp->company_mobile)){
                if(is_array(json_decode($cmp->company_mobile))) {
                    $mobileNo = json_decode($cmp->company_mobile);

                    $cmp['company_mobile'] = implode(", ", $mobileNo);
                } else {
                    $cmp['company_mobile'] = json_decode($cmp->company_mobile);
                }                
            } else {
                $cmp['company_mobile'] = '';
            }

            if(!is_numeric($cmp->company_city)) {
                if($cmp->company_city == 0) {                    
                    $city = '';
                } else {
                    $city = $cmp->company_city;
                }
            } else {
                if($cmp->company_city == 0) {
                    $city = '';
                } else {
                    $cityname = Cities::where('id', $cmp->company_city)->first('name');

                    $city = $cityname->name;
                }
            }

            $name = '<a href="./companies/view-company/'.$id.'">'.$cmp->company_name.'</a>';
            
            $officeNo = '<ul>
                            <li><b>L: </b> '.$cmp->company_landline.' </li>
                            <li><b>M: </b> '.$cmp->company_mobile.' </li>
                        </ul>';

            $action = '<a href="#" class="btn btn-trigger btn-icon icon-verify" onclick="showModal('.$id.')" title="View Company"><em class="icon ni ni-eye"></em></a>
            <a href="./companies/edit-company/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./companies/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';
            
            // $action = "<a href='#' class='btn btn-trigger btn-icon icon-verify' data-toggle='modal' data-target='#viewCompany".$id."' @click='showModal(".$id.")' title='View Company'><em class='icon ni ni-eye'></em></a>
            // <a href='./users-group/edit-user-group/".$id."' class='btn btn-trigger btn-icon' data-toggle='tooltip' data-placement='top' title='Update'><em class='icon ni ni-edit-alt'></em></a>
            // <a href='./users-group/delete/".$id."' class='btn btn-trigger btn-icon' data-toggle='tooltip' data-placement='top' title='Remove'><em class='icon ni ni-trash'></em></a>";

            if($cmp->favorite_flag == 0) {
                $flag = '<em class="icon ni ni-star"></em>';
                // $action .= '<a href="./companies/favorite/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Add into Favorite"><em class="icon ni ni-star"></em></a>';
            } else {
                $flag = '<em class="icon ni ni-star-fill"></em>';
                // $action .= '<a href="./companies/favorite/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove from Favorite"><em class="icon ni ni-star-fill"></em></a>';
            }

            if($cmp->is_verified == 0) {
                $isvarified = '<em class="icon ni ni-alert-fill"></em>';
                // $action .= '<a href="./companies/verify/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Verify"><em class="icon ni ni-check-thick"></em></a>';
            } else {
                $isvarified = '<em class="icon ni ni-check-thick"></em>';
            }

            $data_arr[] = array(
                "id" => $id,
                "flag" => $flag,
                "varified" => $isvarified,
                "name" => $name,
                "office_no" => $officeNo,
                "company_type" => $companyType,
                "company_category" => $companyCategory,
                "city" => $city,
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

    public function listEssentialCompany(Request $request) {
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

        // Total records
        $totalRecords = Company::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Company::select('count(*) as allcount')->
                                           where('company_name', 'like', '%' .$searchValue . '%')->
                                           count();

        // Fetch records
        $company = Company::orderBy($columnName,$columnSortOrder)->
                            where('company_name', 'like', '%' .$searchValue . '%')->
                            where('favorite_flag', '1')->
                            where('is_delete', 0)->
                            skip($start)->
                            take($rowperpage)->
                            get();
        
        $data_arr = array();
        $sno = $start+1;

        foreach($company as $cmp) {
            $id = $cmp->id;

            if($cmp->company_type == 1) {
                $companyType = 'General';
            } elseif($cmp->company_type == 2) {
                $companyType = 'Customer';
            } elseif($cmp->company_type == 3) {
                $companyType = 'Supplier';
            }

            if(!empty($cmp->company_category)) {
                if(is_array(json_decode($cmp->company_category))) {
                    $companyName = [];
                    $companyArr = json_decode($cmp->company_category);
        
                    foreach($companyArr as $key => $c) {
                        $companyCat = CompanyCategory::where('id', $c)->first('category_name');
                        $companyName[$key] = $companyCat->category_name;
                    }

                } else {
                    $companyCatId = json_decode($cmp->company_category);

                    $companyCat = CompanyCategory::where('id', $companyCatId)->first('category_name');
                    $companyName = $companyCat->category_name;
                }
                $companyCategory = is_array($companyName) ? implode(", ", $companyName) : $companyName;
            } else {
                $companyCategory = '';
            }

            if(!empty($cmp->company_landline)){
                if(is_array(json_decode($cmp->company_landline))) {
                    $landlineNo = json_decode($cmp->company_landline);

                    $cmp['company_landline'] = implode(", ", $landlineNo);
                } else {
                    $cmp['company_landline'] = json_decode($cmp->company_landline);
                }
            } else {
                $cmp['company_landline'] = '';
            }

            if(!empty($cmp->company_mobile)){
                if(is_array(json_decode($cmp->company_mobile))) {
                    $mobileNo = json_decode($cmp->company_mobile);

                    $cmp['company_mobile'] = implode(", ", $mobileNo);
                } else {
                    $cmp['company_mobile'] = json_decode($cmp->company_mobile);
                }                
            } else {
                $cmp['company_mobile'] = '';
            }

            if(!is_numeric($cmp->company_city)) {
                if($cmp->company_city == 0) {                    
                    $city = '';
                } else {
                    $city = $cmp->company_city;
                }
            } else {
                if($cmp->company_city == 0) {
                    $city = '';
                } else {
                    $cityname = Cities::where('id', $cmp->company_city)->first('name');

                    $city = $cityname->name;
                }
            }

            $name = '<a href="./companies/view-company/'.$id.'">'.$cmp->company_name.'</a>';
            
            $officeNo = '<ul>
                            <li><b>L: </b> '.$cmp->company_landline.' </li>
                            <li><b>M: </b> '.$cmp->company_mobile.' </li>
                        </ul>';

            $action = '<a href="./companies/view-company/'.$id.'" onclick="showModal('.$id.')" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="View"><em class="icon ni ni-eye"></em></a>
            <a href="./users-group/edit-user-group/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./users-group/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            if($cmp->favorite_flag == 0) {
                $flag = '<em class="icon ni ni-star"></em>';
                // $action .= '<a href="./companies/favorite/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Add into Favorite"><em class="icon ni ni-star"></em></a>';
            } else {
                $flag = '<em class="icon ni ni-star-fill"></em>';
                // $action .= '<a href="./companies/favorite/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove from Favorite"><em class="icon ni ni-star-fill"></em></a>';
            }

            if($cmp->is_verified == 0) {
                $isvarified = '<em class="icon ni ni-alert-fill"></em>';
                // $action .= '<a href="./companies/verify/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Verify"><em class="icon ni ni-check-thick"></em></a>';
            } else {
                $isvarified = '<em class="icon ni ni-check-thick"></em>';
            }

            $data_arr[] = array(
                "id" => $id,
                "flag" => $flag,
                "varified" => $isvarified,
                "name" => $name,
                "office_no" => $officeNo,
                "company_type" => $companyType,
                "company_category" => $companyCategory,
                "city" => $city,
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

    public function isVerify($id) {
        $user = Session::get('user');
 
        $company = Company::where('id', $id)->first();
        $company->is_verified = 1;
        $company->verified_by = $user->employee_id;
        $company->verified_date = Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $company->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Company / Verify';
        $logs->log_subject = 'Company "'.$company->company_name.'" was verified by "'.$user->username.'".';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return true;
    }

    public function isFavorite($id) {
        $user = Session::get('user');

        $company = Company::where('id', $id)->first();
        $company->favorite_flag = 1;
        $company->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Company / Favorite';
        $logs->log_subject = 'Company "'.$company->company_name.'" is in favorite list of "'.$user->username.'".';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return true;
    }

    public function isUnFavorite($id) {
        $user = Session::get('user');

        $company = Company::where('id', $id)->first();
        $company->favorite_flag = 0;
        $company->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Company / Verify';
        $logs->log_subject = 'Company "'.$company->company_name.'" is not in favorite list of "'.$user->username.'".';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return true;
    }

    public function viewCompany($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('databank.companies.viewCompany',compact('financialYear'))->with('employees', $employees);
    }

    public function editCompany($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('databank.companies.editCompany',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchCompany($id) {
        $companyData = [];
        $company = Company::where('id', $id)->first();

        if($company->company_type != 0) {
            $company->company_type = CompanyType::where('id', $company->company_type)->first();
        }
        if($company->company_country != 0) {
            $company->company_country = Country::where('id', $company->company_country)->first();
        }
        if($company->company_state != 0) {
            $company->company_state = State::where('id', $company->company_state)->first();
        }
        if($company->company_city != 0) {
            $company->company_city = Cities::where('id', $company->company_city)->first();
        }
        if($company->company_category != 0) {
            $companyCat = json_decode($company->company_category);

            if(is_array($companyCat)) {
                $company->company_category = CompanyCategory::whereIn('id', $companyCat)->get();
            } else {
                $company->company_category = CompanyCategory::where('id', $companyCat)->get();
            }
        }
        if($company->company_transport != 0) {
            $company->company_transport = Cities::where('id', $company->company_transport)->first();
        }
        if($company->company_landline != 0) {
            $company->company_landline = json_decode($company->company_landline);
        }
        if($company->company_mobile != 0) {
            $company->company_mobile = json_decode($company->company_mobile);
        }

        $companyContactDetails = CompanyContactDetails::where('company_id', $id)->get();

        foreach($companyContactDetails as $contact) {            
            $contactDesignation = $contact->contact_person_designation;
            if (!empty($contactDesignation)) {
                $contact->contact_person_designation = Designation::where('id', $contactDesignation)->where('is_delete', 0)->first();
            }
        }
                                                
        $multipleAddresses = CompanyAddress::where('company_addresses.company_id', $id)->get();

        foreach($multipleAddresses as $multipleAddress) {
            if($multipleAddress->address_type != 0) {
                $multipleAddress->address_type =  TypeOfAddress::where('id', $multipleAddress->address_type)->first();
            }
            $multipleAddress['multipleAddressesOwners'] = CompanyAddressOwner::where('company_address_id', $multipleAddress->id)->get();
            if ($multipleAddress['multipleAddressesOwners']) {
                foreach($multipleAddress['multipleAddressesOwners'] as $addressOwner) {
                    $addressOwnerDesignation = json_decode($addressOwner->designation);
                    if (!empty($addressOwnerDesignation)) {
                        $addressOwner->designation = Designation::whereIn('id', $addressOwnerDesignation)->where('is_delete', 0)->get();
                    }
                }
            }
        }

        $multipleEmails = CompanyEmails::where('company_id', $id)->get();
        $swotDetails = CompanySwotDetails::where('company_id', $id)->first();
        $bankDetails = CompanyBankDetails::where('company_id', $id)->first();
        $packagingDetails = CompanyPackagingDetails::where('company_id', $id)->first();
        $referencesDetails = CompanyReferences::where('company_id', $id)->first();

        $companyData['company'] = $company;
        $companyData['contact_details'] = $companyContactDetails;
        $companyData['multiple_address'] = $multipleAddresses;
        $companyData['multiple_emails'] = $multipleEmails;
        $companyData['swot_details'] = $swotDetails;
        $companyData['bank_details'] = $bankDetails;
        $companyData['packaging_details'] = $packagingDetails;
        $companyData['references_details'] = $referencesDetails;

        return $companyData;
    }

    public function deleteCompany($id){
        $company = Company::where('id', $id)->first();
        $company->is_delete = 1;
        $company->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Company / Delete';
        $logs->log_subject = 'Company - '.$company->company_name.' was deleted by"'.Session::get('user')->username.'".';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('companies');
    }

    public function insertCompanyData(Request $request) {
        $this->validate($request, [
            'company_name' => 'required',
        ]);

        $companyData = json_decode($request->company_data);
        $contactDetails = json_decode($request->contact_details);
        $multipleAddresses = json_decode($request->multiple_addresses);
        $multipleEmails = json_decode($request->multiple_emails);
        $swotDetails = json_decode($request->swot_details);
        $referencesDetails = json_decode($request->references_details);
        $packagingDetails = json_decode($request->packaging_details);
        $bankDetails = json_decode($request->bank_details);
        $contactDetailsProfilePic = $request->contact_details_profile_pic;
        $multipleAddressProfilePic = $request->multiple_address_profile_pic;

        if(is_array($multipleAddresses) && !empty($multipleAddresses)) {
            foreach($multipleAddresses as $multipleAddress) {
                if(is_array($multipleAddress->multipleAddressesOwners) && !empty($multipleAddress->multipleAddressesOwners)) {
                    foreach($multipleAddress->multipleAddressesOwners as $owner) {
                        $multipleAddressOwnerDesignation = [];
                        if (!empty($owner->designation)) {
                            foreach($owner->designation as $key => $des) {
                                $multipleAddressOwnerDesignation[$key] = $des->id;
                            }
                            $owner->designation = json_encode($multipleAddressOwnerDesignation);
                        } else {
                            $owner->designation = 0;
                        }
                    }
                }
            }
        }

        if (!file_exists(public_path('upload/company'))) {
            mkdir(public_path('upload/company'), 0777, true);
        }

        if (!file_exists(public_path('upload/company/profilePic'))) {
            mkdir(public_path('upload/company/profilePic'), 0777, true);
        }

        if (!file_exists(public_path('upload/company/multipleAddressProfilePic'))) {
            mkdir(public_path('upload/company/multipleAddressProfilePic'), 0777, true);
        }

        if(is_array($contactDetailsProfilePic) && !empty($contactDetailsProfilePic)) {
            $length = count($contactDetailsProfilePic);
            for ($i=0; $i<$length; $i++) {                
                if ($image = $contactDetailsProfilePic[$i]) {
                    if(!is_string($image)) {                        
                        $profileImage = date('YmdHis') . "_" . $i . "." . $image->getClientOriginalExtension();
                        $contactDetails[$i]->contact_person_profile_pic = $profileImage;
                        $image->move(public_path('upload/company/profilePic'), $profileImage);
                    } else {
                        $contactDetails[$i]->contact_person_profile_pic = '';
                    }
                }
            }            
        }

        if(is_array($multipleAddressProfilePic) && !empty($multipleAddressProfilePic)) {
            $length = count($multipleAddressProfilePic);
            for ($i=0; $i<$length; $i++) {
                $ownerimage = $multipleAddressProfilePic[$i];
                $ownerLength = count($ownerimage['ownerImage']);
                for ($j=0; $j<$ownerLength; $j++) {
                    if ($image = $ownerimage['ownerImage'][$j]) {
                        if(!is_string($image)) {                        
                            $profileImage = date('YmdHis') . "_" . $i . "." . $image->getClientOriginalExtension();
                            $multipleAddresses[$i]->multipleAddressesOwners[$j]->profile_pic = $profileImage;
                            $image->move(public_path('upload/company/multipleAddressProfilePic'), $profileImage);
                        } else {
                            $multipleAddresses[$i]->multipleAddressesOwners[$j]->profile_pic = '';
                        }
                    }
                }
            }
        }

        if(!empty($companyData->company_landline)) {
            $landline = explode(',', trim($companyData->company_landline));
            $companyData->company_landline = json_encode($landline);
        }
        
        if(!empty($companyData->company_mobile)) {
            $mobile = explode(',', trim($companyData->company_mobile));
            $companyData->company_mobile = json_encode($mobile);
        }

        $comapnyLastId = Company::orderBy('id', 'DESC')->first('id');
        $companyId = !empty($comapnyLastId) ? $comapnyLastId->id + 1 : 1;

        $company = new Company;
        $company->id = $companyId;
        $company->company_name = $companyData->company_name;
        $company->company_type = !empty($companyData->company_type) ? $companyData->company_type->id : 0;
        $company->company_country = !empty($companyData->company_country) ? $companyData->company_country->id : 0;
        $company->company_state = !empty($companyData->company_state) ? $companyData->company_state->id : 0;
        $company->company_city = !empty($companyData->company_city) ? $companyData->company_city->id : 0;
        $company->company_website = $companyData->company_website;
        $company->company_landline = $companyData->company_landline;
        $company->company_mobile = $companyData->company_mobile;
        $company->company_watchout = $companyData->company_watchout;
        $company->company_remark_watchout = $companyData->company_remark_watchout;
        $company->company_about = $companyData->company_about;
        $company->company_category = !empty($companyData->company_category) ? $companyData->company_category->id : 0;
        $company->company_transport = !empty($companyData->company_transport) ? $companyData->company_transport->id : 0;
        $company->company_discount = $companyData->company_discount;
        $company->company_payment_terms_in_days = $companyData->company_payment_terms_in_days;
        $company->company_opening_balance = $companyData->company_opening_balance;
        $company->favorite_flag = 0;
        $company->is_verified = 0;
        $company->verified_by = 0;
        $company->generated_by = Session::get('user')->employee_id;
        $company->updated_by = 0;
        $company->is_linked = 0;
        $company->is_active = 0;
        $company->verified_date = NULL;
        $company->save();
        
        // Contact Details Data
        if(is_array($contactDetails) && !empty($contactDetails)) {
            foreach($contactDetails as $contactDetail) {
                $companyContactLastId = CompanyContactDetails::orderBy('id', 'DESC')->first('id');
                $companyContactId = !empty($companyContactLastId) ? $companyContactLastId->id + 1 : 1;

                $companyContactDetails = new CompanyContactDetails;
                $companyContactDetails->id = $companyContactId;
                $companyContactDetails->company_id = $companyId;
                $companyContactDetails->contact_person_name = $contactDetail->contact_person_name;
                $companyContactDetails->contact_person_designation = !empty($contactDetail->contact_person_designation) ? $contactDetail->contact_person_designation->id : 0;
                $companyContactDetails->contact_person_profile_pic = $contactDetail->contact_person_profile_pic;
                $companyContactDetails->contact_person_mobile = $contactDetail->contact_person_mobile;
                $companyContactDetails->contact_person_email = $contactDetail->contact_person_email;
                $companyContactDetails->save();
            }
        }

        // Multiple Address Data
        if(is_array($multipleAddresses) && !empty($multipleAddresses)) {
            foreach($multipleAddresses as $multipleAddress) {
                $companyAddressLastId = CompanyAddress::orderBy('id', 'DESC')->first('id');
                $companyAddressId = !empty($companyAddressLastId) ? $companyAddressLastId->id + 1 : 1;

                $companyAddress = new CompanyAddress;
                $companyAddress->id = $companyAddressId;
                $companyAddress->company_id = $companyId;
                $companyAddress->address_type = !empty($multipleAddress->address_type) ? $multipleAddress->address_type->id : 0;
                $companyAddress->address = $multipleAddress->address;
                $companyAddress->country_code = $multipleAddress->country_code;
                $companyAddress->mobile = $multipleAddress->mobile;
                $companyAddress->save();

                if(is_array($multipleAddress->multipleAddressesOwners) && !empty($multipleAddress->multipleAddressesOwners)) {
                    foreach($multipleAddress->multipleAddressesOwners as $owner) {
                        $companyAddressOwnerLastId = CompanyAddressOwner::orderBy('id', 'DESC')->first('id');
                        $companyAddressOwnerId = !empty($companyAddressOwnerLastId) ? $companyAddressOwnerLastId->id + 1 : 1;
        
                        $companyAddressOwner = new CompanyAddressOwner;
                        $companyAddressOwner->id = $companyAddressOwnerId;
                        $companyAddressOwner->company_address_id = !empty($companyAddress) ? $companyAddress->id : 0;
                        $companyAddressOwner->name = $owner->name;
                        $companyAddressOwner->designation = $owner->designation;
                        $companyAddressOwner->profile_pic = $owner->profile_pic;
                        $companyAddressOwner->mobile = $owner->mobile;
                        $companyAddressOwner->email = $owner->email;
                        $companyAddressOwner->save();
                    }
                }
            }
        }

        // Multiple Emails Data
        if(is_array($multipleEmails) && !empty($multipleEmails)) {
            foreach($multipleEmails as $multipleEmail) {
                $companyEmailsLastId = CompanyEmails::orderBy('id', 'DESC')->first('id');
                $companyEmailsId = !empty($companyEmailsLastId) ? $companyEmailsLastId->id + 1 : 1;

                $companyEmail = new CompanyEmails;
                $companyEmail->id = $companyEmailsId;
                $companyEmail->company_id = $companyId;
                $companyEmail->email_id = $multipleEmail->email_id;
                $companyEmail->save();
            }
        }

        // SWOT Data
        if(!empty($swotDetails)) {
            $swotDetailsLastId = CompanySwotDetails::orderBy('id', 'DESC')->first('id');
            $swotDetailsId = !empty($swotDetailsLastId) ? $swotDetailsLastId->id + 1 : 1;

            $swotData = new CompanySwotDetails;
            $swotData->id = $swotDetailsId;
            $swotData->company_id = $companyId;
            $swotData->strength = $swotDetails->strength;
            $swotData->weakness = $swotDetails->weakness;
            $swotData->opportunity = $swotDetails->opportunity;
            $swotData->threat = $swotDetails->threat;
            $swotData->save();
        }

        // Bank Data
        if(!empty($bankDetails)) {
            $bankDetailsLastId = CompanyBankDetails::orderBy('id', 'DESC')->first('id');
            $bankDetailsId = !empty($bankDetailsLastId) ? $bankDetailsLastId->id + 1 : 1;

            $bankDetail = new CompanyBankDetails;
            $bankDetail->id = $bankDetailsId;
            $bankDetail->company_id = $companyId;
            $bankDetail->bank_name = $bankDetails->bank_name;
            $bankDetail->account_holder_name = $bankDetails->account_holder_name;
            $bankDetail->account_no = $bankDetails->account_no;
            $bankDetail->branch_name = $bankDetails->branch_name;
            $bankDetail->ifsc_code = $bankDetails->ifsc_code;
            $bankDetail->save();
        }

        // Packaging Data
        if(!empty($packagingDetails)) {
            $packagingDetailsLastId = CompanyPackagingDetails::orderBy('id', 'DESC')->first('id');
            $packagingDetailsId = !empty($packagingDetailsLastId) ? $packagingDetailsLastId->id + 1 : 1;

            $package = new CompanyPackagingDetails;
            $package->id = $packagingDetailsId;
            $package->company_id = $companyId;
            $package->gst_no = $packagingDetails->gst_no;
            $package->cst_no = $packagingDetails->cst_no;
            $package->tin_no = $packagingDetails->tin_no;
            $package->vat_no = $packagingDetails->vat_no;
            $package->save();
        }

        // Reference Data
        if(!empty($referencesDetails)) {
            $companyReferencesLastId = CompanyReferences::orderBy('id', 'DESC')->first('id');
            $companyReferencesId = !empty($companyReferencesLastId) ? $companyReferencesLastId->id + 1 : 1;

            $reference = new CompanyReferences;
            $reference->id = $companyReferencesId;
            $reference->company_id = $companyId;
            $reference->ref_person_name = $referencesDetails->ref_person_name;
            $reference->ref_person_mobile = $referencesDetails->ref_person_mobile;
            $reference->ref_person_company = $referencesDetails->ref_person_company;
            $reference->ref_person_address = $referencesDetails->ref_person_address;
            $reference->save();
        }

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Company / Add';
        $logs->log_subject = 'Company - "'.$company->company_name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateCompanyData(Request $request) {
        $this->validate($request, [
            'company_name' => 'required',
        ]);

        $companyData = json_decode($request->company_data);
        $contactDetails = json_decode($request->contact_details);
        $multipleAddresses = json_decode($request->multiple_addresses);
        $multipleEmails = json_decode($request->multiple_emails);
        $swotDetails = json_decode($request->swot_details);
        $referencesDetails = json_decode($request->references_details);
        $packagingDetails = json_decode($request->packaging_details);
        $bankDetails = json_decode($request->bank_details);
        $contactDetailsProfilePic = $request->contact_details_profile_pic;
        $multipleAddressProfilePic = $request->multiple_address_profile_pic;

        $id = $companyData->id;

        if(is_array($multipleAddresses) && !empty($multipleAddresses)) {
            foreach($multipleAddresses as $multipleAddress) {
                if(is_array($multipleAddress->multipleAddressesOwners) && !empty($multipleAddress->multipleAddressesOwners)) {
                    foreach($multipleAddress->multipleAddressesOwners as $owner) {
                        $multipleAddressOwnerDesignation = [];
                        if (!empty($owner->designation)) {
                            foreach($owner->designation as $key => $des) {
                                $multipleAddressOwnerDesignation[$key] = $des->id;
                            }
                            $owner->designation = json_encode($multipleAddressOwnerDesignation);
                        } else {
                            $owner->designation = 0;
                        }
                    }
                }
            }
        }

        if(is_array($contactDetailsProfilePic) && !empty($contactDetailsProfilePic)) {
            $length = count($contactDetailsProfilePic);
            for ($i=0; $i<$length; $i++) {                
                if ($image = $contactDetailsProfilePic[$i]) {
                    if(!is_string($image)) {                        
                        $profileImage = date('YmdHis') . "_" . $i . "." . $image->getClientOriginalExtension();
                        $contactDetails[$i]->contact_person_profile_pic = $profileImage;
                        $image->move(public_path('upload/company/profilePic'), $profileImage);
                    } else {
                        $contactDetails[$i]->contact_person_profile_pic = $contactDetails[$i]->contact_person_profile_pic;
                    }
                }
            }            
        }

        if(is_array($multipleAddressProfilePic) && !empty($multipleAddressProfilePic)) {
            $length = count($multipleAddressProfilePic);
            for ($i=0; $i<$length; $i++) {
                $ownerimage = $multipleAddressProfilePic[$i];
                $ownerLength = count($ownerimage['ownerImage']);
                for ($j=0; $j<$ownerLength; $j++) {
                    if ($image = $ownerimage['ownerImage'][$j]) {
                        if(!is_string($image)) {                        
                            $profileImage = date('YmdHis') . "_" . $i . "." . $image->getClientOriginalExtension();
                            $multipleAddresses[$i]->multipleAddressesOwners[$j]->profile_pic = $profileImage;
                            $image->move(public_path('upload/company/multipleAddressProfilePic'), $profileImage);
                        } else {
                            $multipleAddresses[$i]->multipleAddressesOwners[$j]->profile_pic = $multipleAddresses[$i]->multipleAddressesOwners[$j]->profile_pic;
                        }
                    }
                }
            }
        }
        
        if(!empty($companyData->company_landline)) {
            if(is_array($companyData->company_landline)) {
                $companyData->company_landline = json_encode($companyData->company_landline);
            } else {
                $landline = explode(',', trim($companyData->company_landline));
                $companyData->company_landline = json_encode($landline);
            }
        }
        
        if(!empty($companyData->company_mobile) ) {
            if(is_array($companyData->company_mobile) ) {
                $companyData->company_mobile = json_encode($companyData->company_mobile);
            } else {
                $mobile = explode(',', trim($companyData->company_mobile));
                $companyData->company_mobile = json_encode($mobile);
            }
        }

        if(is_array($companyData->company_category) && !empty($companyData->company_category)) {
            $companyCategory = [];
            foreach($companyData->company_category as $key => $category) {
                array_push($companyCategory, $category->id);
            }
            $companyData->company_category = json_encode($companyCategory);
        }

        $company = Company::where('id', $id)->first();
        $company->company_name = $companyData->company_name;
        $company->company_type = !empty($companyData->company_type) ? $companyData->company_type->id : 0;
        $company->company_country = !empty($companyData->company_country) ? $companyData->company_country->id : 0;
        $company->company_state = !empty($companyData->company_state) ? $companyData->company_state->id : 0;
        $company->company_city = !empty($companyData->company_city) ? $companyData->company_city->id : 0;
        $company->company_website = $companyData->company_website;
        $company->company_landline = $companyData->company_landline;
        $company->company_mobile = $companyData->company_mobile;
        $company->company_watchout = $companyData->company_watchout;
        $company->company_remark_watchout = $companyData->company_remark_watchout;
        $company->company_about = $companyData->company_about;
        $company->company_category = $companyData->company_category;
        $company->company_transport = !empty($companyData->company_transport) ? $companyData->company_transport->id : 0;
        $company->company_discount = $companyData->company_discount;
        $company->company_payment_terms_in_days = $companyData->company_payment_terms_in_days;
        $company->company_opening_balance = $companyData->company_opening_balance;
        $company->updated_by = Session::get('user')->employee_id;
        $company->save();
        
        // Contact Details Data
        if(is_array($contactDetails) && !empty($contactDetails)) {
            foreach($contactDetails as $contactDetail) {
                $companyContactDetails = CompanyContactDetails::where('company_id', $id)->first();
                $companyContactDetails->contact_person_name = $contactDetail->contact_person_name;
                $companyContactDetails->contact_person_designation = !empty($contactDetail->contact_person_designation) ? $contactDetail->contact_person_designation->id : 0;
                $companyContactDetails->contact_person_profile_pic = $contactDetail->contact_person_profile_pic;
                $companyContactDetails->contact_person_mobile = $contactDetail->contact_person_mobile;
                $companyContactDetails->contact_person_email = $contactDetail->contact_person_email;
                $companyContactDetails->save();
            }
        }

        // Multiple Address Data
        if(is_array($multipleAddresses) && !empty($multipleAddresses)) {
            foreach($multipleAddresses as $multipleAddress) {
                $companyAddress = CompanyAddress::where('company_id', $id)->first();
                $companyAddress->address_type = !empty($multipleAddress->address_type) ? $multipleAddress->address_type->id : 0;
                $companyAddress->address = $multipleAddress->address;
                $companyAddress->mobile = $multipleAddress->mobile;
                $companyAddress->save();

                if(is_array($multipleAddress->multipleAddressesOwners) && !empty($multipleAddress->multipleAddressesOwners)) {
                    foreach($multipleAddress->multipleAddressesOwners as $owner) {
                        $companyAddressOwner = CompanyAddressOwner::where('company_address_id', $companyAddress->id)->first();
                        $companyAddressOwner->name = $owner->name;
                        $companyAddressOwner->designation = $owner->designation;
                        $companyAddressOwner->profile_pic = $owner->profile_pic;
                        $companyAddressOwner->mobile = $owner->mobile;
                        $companyAddressOwner->email = $owner->email;
                        $companyAddressOwner->save();
                    }
                }
            }
        }

        // Multiple Emails Data
        if(is_array($multipleEmails) && !empty($multipleEmails)) {
            foreach($multipleEmails as $multipleEmail) {
                $companyEmail = CompanyEmails::where('company_id', $id)->first();
                $companyEmail->email_id = $multipleEmail->email_id;
                $companyEmail->save();
            }
        }

        // SWOT Data
        if(!empty($swotDetails)) {
            $swotData = CompanySwotDetails::where('company_id', $id)->first();
            $swotData->strength = $swotDetails->strength;
            $swotData->weakness = $swotDetails->weakness;
            $swotData->opportunity = $swotDetails->opportunity;
            $swotData->threat = $swotDetails->threat;
            $swotData->save();
        }

        // Bank Data
        if(!empty($bankDetails)) {
            $bankDetail = CompanyBankDetails::where('company_id', $id)->first();
            $bankDetail->bank_name = $bankDetails->bank_name;
            $bankDetail->account_holder_name = $bankDetails->account_holder_name;
            $bankDetail->account_no = $bankDetails->account_no;
            $bankDetail->branch_name = $bankDetails->branch_name;
            $bankDetail->ifsc_code = $bankDetails->ifsc_code;
            $bankDetail->save();
        }

        // Packaging Data
        if(!empty($packagingDetails)) {
            $package = CompanyPackagingDetails::where('company_id', $id)->first();
            $package->gst_no = $packagingDetails->gst_no;
            $package->cst_no = $packagingDetails->cst_no;
            $package->tin_no = $packagingDetails->tin_no;
            $package->vat_no = $packagingDetails->vat_no;
            $package->save();
        }

        // Reference Data
        if(!empty($referencesDetails)) {
            $reference = CompanyReferences::where('company_id', $id)->first();
            $reference->ref_person_name = $referencesDetails->ref_person_name;
            $reference->ref_person_mobile = $referencesDetails->ref_person_mobile;
            $reference->ref_person_company = $referencesDetails->ref_person_company;
            $reference->ref_person_address = $referencesDetails->ref_person_address;
            $reference->save();
        }

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Company / Edit';
        $logs->log_subject = 'Company - "'.$companyData->company_name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
