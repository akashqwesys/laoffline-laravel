<?php

namespace App\Http\Controllers\referenceId;

use Carbon\Carbon;
use App\Models\Logs;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\settings\Cities;
use App\Models\settings\Country;
use App\Models\comboids\comboids;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\settings\Designation;
use App\Models\Company\CompanyEmails;
use App\Models\Reference\ReferenceId;
use App\Models\Company\CompanyAddress;
use Illuminate\Support\Facades\Session;
use App\Models\settings\TransportDetails;
use App\Models\Company\CompanyAddressOwner;
use App\Models\Company\CompanyContactDetails;
use App\Models\Company\CompanyPackagingDetails;

class ReferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $financialYear = $this->financialYear();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'reference / View';
        $logs->log_subject = 'reference view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('referenceId.referenceId',compact('financialYear'))->with('employees', $employees);
    }

    public function listReference(Request $request){
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

        $totalRecords = ReferenceId::where('id', '!=', '0')->select('count(*) as allcount')->count();
        $totalRecordswithFilter = ReferenceId::select('count(*) as allcount')->
                                                   where('id', '!=', '0')->
                                                   where('reference_id', 'like', '%' .$searchValue . '%')->
                                                   count();

        $ReferenceId = ReferenceId::join('companies','company_id','=','companies.id')
                        ->join('employees','employee_id','=','employees.id')
                        ->orderBy('reference_ids.'.$columnName,$columnSortOrder)->
                        where('reference_ids.reference_id', 'like', '%' .$searchValue . '%')->
                        where('reference_ids.is_deleted', '0')->
                        skip($start)->
                        take($rowperpage)->
                        get();

        $data_arr = array();
        $sno = $start+1;

        foreach($ReferenceId as $record){
            $reference_id = $record->reference_id;
            $date_added = date('Y-m-d H:i:s', strtotime($record->created_at));
            $selection_date = $record->selection_date;
            $type_of_inward = $record->type_of_inward;
            if ($type_of_inward == 'Hand') {
                $type_of_inward = '<em class="icon ni ni-thumbs-up" title="Hand"></em>';
            }
            elseif ($type_of_inward == 'Call') {
                $type_of_inward = '<em class="icon ni ni-call" title="Call"></em>';
            }
            elseif ($type_of_inward == 'Message') {
                $type_of_inward = '<em class="icon ni ni-emails" title="Message"></em>';
            }
            elseif ($type_of_inward == 'Whatsapp')
            {
                $type_of_inward = '<em class="icon ni ni-whatsapp" title="Whatsapp"></em>';
            }
            elseif ($type_of_inward == 'Email')
            {
                $type_of_inward = '<em class="icon ni ni-mail" title="Email"></em>';
            }
            elseif ($type_of_inward == 'Courier')
            {
                $type_of_inward = '<em class="icon ni ni-emails-fill" title="Courier"></em>';
            }
            $company_name = '<a href="#'.$record->company_name.'" data-toggle="modal">'.$record->company_name.'</a>';
            $firstname = $record->firstname;
            if($record->inward_or_outward == '1')
            {
                $inward_or_outward = 'Inward';
            }
            else
            {
                $inward_or_outward = 'Outward';
            }
            $action = '<a href="./reference/view-reference/'.$reference_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="View"><em class="icon ni ni-eye"></em></a>
            <a href="./reference/edit-reference/'.$reference_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./reference/referenceId/delete/'.$reference_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "reference_id" => $reference_id,
                "date_added" => $date_added,
                "selection_date" => $selection_date,
                "type_of_inward" => $type_of_inward,
                "company_name" => $company_name,
                "firstname" => $firstname,
                "inward_or_outward" => $inward_or_outward,
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

    public function financialYear()
    {
        $financialYear = DB::table('financial_year')->get();

        return $financialYear;
    }

    public function getCompany()
    {
        $company = Company::all();

        return $company;
    }

    public function receiverDetails()
    {
        $user = Session::get('user');
        $receiverDetails = Employee::where('employees.id', $user->employee_id)->first();

        $Receiver['receiverDetails'] = $receiverDetails;
        return $Receiver;
    }

    public function fromName($id)
    {
        $fromName = CompanyContactDetails::where('company_id', $id)->first('contact_person_name');

        return $fromName;
    }

    public function designation()
    {
        $designation = Designation::get();

        return $designation;
    }

    public function listCountries() {
        $countries = Country::all();

        return $countries;
    }

    // public function fetchCities($id) {
    //     $statesData = Cities::where('id', $id)->first();

    //     return $statesData;
    // }

    public function listTransport(){
        $Transport = TransportDetails::get(['transport_details.id','transport_details.name']);

        return $Transport;
    }


    public function createReferenceId(){
        $financialYear = $this->financialYear();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        return view('referenceId.createReferenceId',compact('financialYear'))->with('employees', $employees);
    }

    public function referenceView($id)
    {
        $financialYear = $this->financialYear();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['id'] = $id;
        return view('referenceId.viewReferenceId',compact('financialYear'))->with('employees', $employees);
    }

    public function editReferenceId($id){
        $financialYear = $this->financialYear();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['id'] =$id;

        return view('referenceId.updateReferenceId',compact('financialYear'))->with('employees', $employees);
    }

    public function deleteReferenceId($id){
        $Reference = ReferenceId::where('reference_id',$id)->first();
        $Reference->is_deleted = 1;
        $Reference->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Product Sub Category / Delete';
        $logs->log_subject = 'Product Sub Category - "'.$Reference->reference_id.'" was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('reference');
    }

    public function fetchreference($id){
        $ReferenceData = [];

        $Reference = ReferenceId::where('reference_id', $id)->first();

        $User = Session::get('user');

        $Company = Company::where('id',$Reference->company_id)->first();

        if ($Reference->inward_or_outward == '1') {
            $inward_outward = comboids::join('reference_ids','comboids.general_ref_id','=','reference_ids.id')
                                        ->join('inwards','comboids.iuid','=','inwards.iuid')
                                        ->join('users','comboids.outward_employe_id','=','users.employee_id')
                                        ->join('companies','comboids.company_id','=','companies.id')
                                        ->where('comboids.company_id',$Reference->company_id)->get();
        }
        else{
            $inward_outward = comboids::join('reference_ids','comboids.general_ref_id','=','reference_ids.id')
                                        ->join('outwards','comboids.ouid','=','outwards.ouid')
                                        ->join('users','comboids.outward_employe_id','=','users.employee_id')
                                        ->join('companies','comboids.company_id','=','companies.id')
                                        ->where('comboids.company_id',$Reference->company_id)->get();
        }


        $ReferenceData['Reference'] = $Reference;
        $ReferenceData['User'] = $User;
        $ReferenceData['Company'] = $Company;
        $ReferenceData['inward_outward'] = $inward_outward;
        return $ReferenceData;
    }


    public function fetchcompany(){

        $companyData = [];
        $companyList = Company::get();
        $companyCity = Cities::get();
        $companyAddress = CompanyAddress::join('type_of_addresses', 'company_addresses.address_type', '=', 'type_of_addresses.id')->get();
        $companyContactDetails = CompanyContactDetails::join('designations', 'company_contact_details.contact_person_designation', '=', 'designations.id')->get();
        $multipleEmails = CompanyEmails::get();
        $packagingDetails = CompanyPackagingDetails::get();
        $multipleAddress = CompanyAddressOwner::join('designations', 'company_address_owners.designation', '=', 'designations.id')->
        get(['company_address_owners.*', 'designations.name as designation_name']);

        $companyData['companyList'] = $companyList;
        $companyData['companyCity'] = $companyCity;
        $companyData['companyAddress'] = $companyAddress;
        $companyData['companyContactDetails'] = $companyContactDetails;
        $companyData['multipleEmails'] = $multipleEmails;
        $companyData['multipleAddress'] = $multipleAddress;
        $companyData['packagingDetails'] = $packagingDetails;

        return $companyData;
    }

    public function AddReferenceId(Request $request)
    {
        $Date = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');
        $user = Session::get('user');
        $Reference = ReferenceId::orderBy('reference_id','DESC')->first();
        $ref = ReferenceId::orderBy('id','DESC')->first();
        $ReferencesId = !empty($ref) ? $ref->id + 1 : 1;
        if($request->Reference_via['name'] == 'Call' || $request->Reference_via['name'] == 'Message' || $request->Reference_via['name'] =='Whatsapp')
        {
            $ReferenceId = new ReferenceId;

            if ($request->inward_outward == 1)
            {
                $ReferenceId->inward_or_outward = $request->inward_outward;
                if ($Reference == null)
                {
                    $ReferenceId->reference_id = '1';
                    $ReferenceId->inward_or_outward = $request->inward_outward;
                }
                else
                {
                    $ReferenceId->reference_id = $Reference->reference_id + '1';
                }
            }
            elseif ($request->inward_outward == 0)
            {
                $ReferenceId->inward_or_outward = $request->inward_outward;
                if ($Reference == null)
                {
                    $ReferenceId->reference_id = '1';
                    $ReferenceId->inward_or_outward = $request->inward_outward;
                }
                else
                {
                    $ReferenceId->reference_id = $Reference->reference_id + '1';
                }
            }
                    $ReferenceId->id = $ReferencesId;
                    $ReferenceId->financial_year_id = Session::get('user')->financial_year_id;
                    $ReferenceId->selection_date = $Date;
                    $ReferenceId->employee_id = $user->employee_id;
                    $ReferenceId->company_id = !empty($request->companyName) ? $request->companyName['id'] : 0;
                    $ReferenceId->type_of_inward= !empty($request->Reference_via) ? $request->Reference_via['name'] : '';
                    $ReferenceId->latter_by_id = 0;
                    $ReferenceId->courier_name = "";
                    $ReferenceId->weight_of_parcel = "";
                    $ReferenceId->courier_receipt_no = "";
                    $ReferenceId->courier_received_time = NULL;
                    $ReferenceId->delivery_by = "";
                    $ReferenceId->receiver_email_id = "";
                    $ReferenceId->from_email_id = "";
                    $ReferenceId->receiver_number = $request->receiver_number;
                    $ReferenceId->from_number = $request->from_number;
                    $ReferenceId->from_name = $request->from_name;
                    $ReferenceId->is_deleted = '0';
                    $ReferenceId->mark_as_sample="0";
                    $ReferenceId->save();
        }
        elseif($request->Reference_via['name'] == 'Email')
        {
            if ($request->inward_outward == 1)
            {
                $ReferenceId = new ReferenceId;
                $ReferenceId->inward_or_outward = $request->inward_outward;
                if ($Reference == null)
                {
                    $ReferenceId->reference_id = '1';
                    $ReferenceId->inward_or_outward = $request->inward_outward;
                }
                else
                {
                    $ReferenceId->reference_id = $Reference->reference_id + '1';
                }
            }
            elseif ($request->inward_outward == 0)
            {
                $ReferenceId = new ReferenceId;
                $ReferenceId->inward_or_outward = $request->inward_outward;
                if ($Reference == null)
                {
                    $ReferenceId->reference_id = '1';
                    $ReferenceId->inward_or_outward = $request->inward_outward;
                }
                else
                {
                    $ReferenceId->reference_id = $Reference->reference_id + '1';
                }
            }
            $ReferenceId->id = $ReferencesId;
            $ReferenceId->financial_year_id = Session::get('user')->financial_year_id;
            $ReferenceId->type_of_inward= !empty($request->Reference_via['name']) ? $request->Reference_via['name'] : '';
            $ReferenceId->company_id = !empty($request->companyName['id']) ? $request->companyName['id'] : '';
            $ReferenceId->employee_id = $user->employee_id;
            $ReferenceId->selection_date = $Date;
            $ReferenceId->latter_by_id = 0;
            $ReferenceId->courier_name = "";
            $ReferenceId->weight_of_parcel = "";
            $ReferenceId->courier_receipt_no = "";
            $ReferenceId->courier_received_time = NULL;
            $ReferenceId->delivery_by = "";
            $ReferenceId->receiver_email_id = $request->receiver_email;
            $ReferenceId->from_email_id = $request->from_email;
            $ReferenceId->receiver_number = "";
            $ReferenceId->from_number = "";
            $ReferenceId->from_name = "";
            $ReferenceId->mark_as_sample="0";
            $ReferenceId->is_deleted = '0';
            $ReferenceId->created_at=$Date;
            $ReferenceId->updated_at=$Date;
            $ReferenceId->save();
        }
        else
        {
            if ($request->Reference_via['name'] == 'Courier') {
                $latterBy = '1';
            }
            elseif ($request->Reference_via['name'] == 'Hand'){
                $latterBy = '0';
            }
            else{
                $latterBy = '0';
            }
            $marks = 0;
            if (isset($request->markssample)) {
                $marks = 1;
            }

            $ReferenceId = new ReferenceId;
            $ReferenceId->inward_or_outward = $request->inward_outward;
            if ($Reference == null)
                {
                    $ReferenceId->reference_id = '1';
                    $ReferenceId->inward_or_outward = $request->inward_outward;
                }
                else
                {
                    $ReferenceId->reference_id = $Reference->reference_id + '1';
                }
            $ReferenceId->id = $ReferencesId;
            $ReferenceId->financial_year_id = Session::get('user')->financial_year_id;
            $ReferenceId->type_of_inward= !empty($request->Reference_via['name']) ? $request->Reference_via['name'] : '';
            $ReferenceId->company_id = !empty($request->companyName) ? $request->companyName['id'] : '';
            $ReferenceId->employee_id = $user->employee_id;
            $ReferenceId->selection_date = date('Y-m-d', strtotime($request->Date_Time));
            $ReferenceId->latter_by_id = $latterBy;
            $ReferenceId->courier_name = !empty($request->courier_company['name']) ? $request->courier_company['name'] : '';
            $ReferenceId->weight_of_parcel = $request->parcel_weight;
            $ReferenceId->courier_receipt_no = $request->courier_recepit_no;
            $ReferenceId->courier_received_time = date('Y-m-d H:i:s', strtotime($request->received_date_time));
            $ReferenceId->delivery_by = $request->delivery_by;
            $ReferenceId->receiver_email_id = "";
            $ReferenceId->from_email_id = "";
            $ReferenceId->receiver_number = "";
            $ReferenceId->from_number = "";
            $ReferenceId->from_name = $request->from_name;
            $ReferenceId->mark_as_sample = $marks;
            $ReferenceId->is_deleted = '0';
            $ReferenceId->created_at=$Date;
            $ReferenceId->updated_at=$Date;
            $ReferenceId->save();
        }

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'ReferenceId / Add';
        $logs->log_subject = 'Reference Id "'.$request->Reference_via['name'].'" was added from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

    }

    public function AddCompany(Request $request)
    {
        foreach($request->designationList as $key => $com) {
            $designationList_id[$key] = $com['id'];
        }

        $companyList = Company::orderBy('id', 'DESC')->first('id');
        $companyId = !empty($companyList) ? $companyList->id + 1 : 1;
        $company = new Company;
        $company->id = $companyId;
        $company->company_name = $request->name;
        $company->company_type = $request->type['name'];
        $company->company_country = $request->country['name'];
        $company->company_state = $request->state['name'];
        $company->company_city = $request->city['name'];
        $company->company_landline = $request->num;
        $company->company_about = $request->company;
        $company->save();

        $CompanyContactDetailsList = CompanyContactDetails::orderBy('id', 'DESC')->first('id');
        $CompanyContactDetailsListId = !empty($CompanyContactDetailsList) ? $CompanyContactDetailsList->id + 1 : 1;
        $CompanyId = Company::where('company_name','=',$request->name)->first();
        $CompanyContact = new CompanyContactDetails;
        $CompanyContact->id = $CompanyContactDetailsListId;
        $CompanyContact->company_id = $CompanyId->id;
        $CompanyContact->contact_person_name = $request->Contactname;
        $CompanyContact->contact_person_designation = json_encode($designationList_id);
        $CompanyContact->contact_person_mobile = $request->contact;
        $CompanyContact->contact_person_email = $request->email;
        $CompanyContact->save();
    }

    public function AddPerson(Request $request) {
        foreach($request->designationList as $key => $com) {
            $designationList_id[$key] = $com['id'];
        }

        $CompanyContactDetailsList = CompanyContactDetails::orderBy('id', 'DESC')->first('id');
        $CompanyContactDetailsListId = !empty($CompanyContactDetailsList) ? $CompanyContactDetailsList->id + 1 : 1;
        $CompanyContact = new CompanyContactDetails;
        $CompanyContact->id = $CompanyContactDetailsListId;
        $CompanyContact->company_id = $request->companyList['id'];
        $CompanyContact->contact_person_name = $request->Contactname;
        $CompanyContact->contact_person_designation = json_encode($designationList_id);
        $CompanyContact->contact_person_mobile = $request->contact;
        $CompanyContact->contact_person_email = $request->email;
        $CompanyContact->save();
    }

    public function updateReference(Request $request)
    {
        $Date = Carbon::now()->format('Y-m-d H:i:s');
        $user = Session::get('user');
        $Reference = ReferenceId::orderBy('reference_id','DESC')->first();
        if($request->Reference_via['name'] == 'Call' || $request->Reference_via['name'] == 'Message' || $request->Reference_via['name'] =='Whatsapp')
        {
            $id = $request->id;
            $ReferenceId = ReferenceId::where('reference_id', $id)->first();
                    $ReferenceId->inward_or_outward = $request->inward_outward;
                    $ReferenceId->financial_year_id = Session::get('user')->financial_year_id;
                    $ReferenceId->selection_date = $Date;
                    $ReferenceId->employee_id = $user->employee_id;
                    $ReferenceId->company_id = $request->companyName['id'];
                    $ReferenceId->type_of_inward= $request->Reference_via['name'];
                    $ReferenceId->latter_by_id = 0;
                    $ReferenceId->courier_name = "";
                    $ReferenceId->weight_of_parcel = "";
                    $ReferenceId->courier_receipt_no = "";
                    $ReferenceId->courier_received_time = NULL;
                    $ReferenceId->delivery_by = "";
                    $ReferenceId->receiver_email_id = "";
                    $ReferenceId->from_email_id = "";
                    $ReferenceId->receiver_number = $request->receiver_number;
                    $ReferenceId->from_number = $request->from_number;
                    $ReferenceId->from_name = $request->from_name['contact_person_name'];
                    $ReferenceId->is_deleted = '0';
                    $ReferenceId->mark_as_sample="0";
                    $ReferenceId->save();
        }
        elseif($request->Reference_via == 'Email')
        {
            $id = $request->id;
            $ReferenceId = ReferenceId::where('reference_id', $id)->first();
            $ReferenceId->inward_or_outward = $request->inward_outward;
            $ReferenceId->financial_year_id = Session::get('user')->financial_year_id;
            $ReferenceId->type_of_inward= $request->Reference_via['name'];
            $ReferenceId->company_id = $request->companyName['id'];
            $ReferenceId->employee_id = $user->employee_id;
            $ReferenceId->selection_date = $Date;
            $ReferenceId->latter_by_id = 0;
            $ReferenceId->courier_name = "";
            $ReferenceId->weight_of_parcel = "";
            $ReferenceId->courier_receipt_no = "";
            $ReferenceId->courier_received_time = NULL;
            $ReferenceId->delivery_by = "";
            $ReferenceId->receiver_email_id = $request->receiver_email;
            $ReferenceId->from_email_id = $request->from_email;
            $ReferenceId->receiver_number = "";
            $ReferenceId->from_number = "";
            $ReferenceId->from_name = $request->from_name['contact_person_name'];
            $ReferenceId->mark_as_sample="0";
            $ReferenceId->is_deleted = '0';
            $ReferenceId->created_at=$Date;
            $ReferenceId->updated_at=$Date;
            $ReferenceId->save();
        }
        else
        {
            if ($request->Reference_via == 'Courier') {
                $latterBy = '1';
            }
            elseif ($request->Reference_via == 'Hand'){
                $latterBy = '0';
            }
            else{
                $latterBy = $request->latter_by_id;
            }
            $marks = 0;
            if (isset($request->markssample)) {
                $marks = 1;
            }

            $id = $request->id;
            $ReferenceId = ReferenceId::where('reference_id', $id)->first();
            $ReferenceId->inward_or_outward = $request->inward_outward;
            $ReferenceId->financial_year_id = Session::get('user')->financial_year_id;
            $ReferenceId->type_of_inward= $request->Reference_via['name'];
            $ReferenceId->company_id = $request->companyName['id'];
            $ReferenceId->employee_id = $user->employee_id;
            $ReferenceId->selection_date = $Date;
            $ReferenceId->latter_by_id = $latterBy;
            $ReferenceId->courier_name = $request->courier_company['name'];
            $ReferenceId->weight_of_parcel = $request->parcel_weight;
            $ReferenceId->courier_receipt_no = $request->courier_recepit_no;
            $ReferenceId->courier_received_time = date('Y-m-d', strtotime($request->received_date_time));
            $ReferenceId->delivery_by = $request->delivery_by;
            $ReferenceId->receiver_email_id = $request->receiver_email;
            $ReferenceId->from_email_id = $request->from_email;
            $ReferenceId->receiver_number = $request->receiver_number;
            $ReferenceId->from_number = $request->from_number;
            $ReferenceId->from_name = $request->from_name['contact_person_name'];
            $ReferenceId->mark_as_sample = $marks;
            $ReferenceId->is_deleted = '0';
            $ReferenceId->created_at=$Date;
            $ReferenceId->updated_at=$Date;
            $ReferenceId->save();
        }

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'ReferenceId / Updated';
        $logs->log_subject = 'Reference Id "'.$request->id.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

    }
}
