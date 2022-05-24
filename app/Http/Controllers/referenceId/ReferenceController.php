<?php

namespace App\Http\Controllers\referenceId;

use Carbon\Carbon;
use App\Models\Logs;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\Settings\Cities;
use App\Models\Settings\Country;
use App\Models\Comboids\Comboids;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Settings\Designation;
use App\Models\Company\CompanyEmails;
use App\Models\Reference\ReferenceId;
use App\Models\Company\CompanyAddress;
use Illuminate\Support\Facades\Session;
use App\Models\Settings\TransportDetails;
use App\Models\Company\CompanyAddressOwner;
use App\Models\Company\CompanyContactDetails;
use App\Models\Company\CompanyPackagingDetails;
use Symfony\Component\Mailer\Transport;

class ReferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = 'Reference';
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

        return view('referenceId.referenceId',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function listReference(Request $request)
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

        $employees = Employee::select('user_group', 'id')
            ->where('id', $user->employee_id)
            ->first();

        $totalRecords = ReferenceId::select('id')
            ->where('id', '!=', '0')
            ->where('financial_year_id', $user->financial_year_id)
            ->where('reference_id', '!=', '0')
            ->count();
        $totalRecordswithFilter = ReferenceId::selectRaw('count(id) as allcount')
            ->where('financial_year_id', $user->financial_year_id)
            ->where('reference_id', '!=', '0')
            ->where('id', '!=', '0');
        if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('reference_id', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereDate('reference_ids.created_at', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('selection_date', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('type_of_inward', 'ilike', '%'.$columnName_arr[3]['search']['value'].'%');
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $cc_id = DB::table('companies')->select('id')->where('company_name', 'ilike', '%' . $columnName_arr[4]['search']['value'] . '%')->pluck('id')->toArray();
            $totalRecordswithFilter = $totalRecordswithFilter->whereIn('company_id', $cc_id);
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $emp_id = DB::table('employees')
                ->select('id')
                ->where('firstname', 'ILIKE', '%' . $columnName_arr[5]['search']['value'] . '%')
                ->orWhere('middlename', 'ILIKE', '%' . $columnName_arr[5]['search']['value'] . '%')
                ->orWhere('lastname', 'ILIKE', '%' . $columnName_arr[5]['search']['value'] . '%')
                ->first();
            $totalRecordswithFilter = $totalRecordswithFilter->where('employee_id', $emp_id->id ?? 0);
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            if (in_array($columnName_arr[6]['search']['value'], ['inward', 'Inward'])) {
                $totalRecordswithFilter = $totalRecordswithFilter->where('inward_or_outward', 1);
            } else {
                $totalRecordswithFilter = $totalRecordswithFilter->where('inward_or_outward', 0);
            }
        }
        if ($employees->user_group == 21) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('employee_id', '=', $employees->id);
        } else if (!in_array($employees->user_group, [21, 1])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('employee_id', '=', $employees->id)
                ->orWhere('employee_id', '=', 15);
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();

        $ReferenceId = ReferenceId::join('companies','company_id','=','companies.id')
            ->join('employees','employee_id','=','employees.id')
            ->select('reference_ids.id', 'reference_ids.reference_id', 'reference_ids.created_at', 'reference_ids.selection_date', 'reference_ids.type_of_inward', 'reference_ids.inward_or_outward', 'reference_ids.company_id', 'companies.company_name', 'employees.firstname', 'employees.lastname')
            ->where('reference_ids.financial_year_id', $user->financial_year_id)
            ->where('reference_ids.reference_id', '!=', '0')
            ->where('reference_ids.is_deleted', '0');
            if (isset($columnName_arr[0]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $ReferenceId = $ReferenceId->where('reference_id', '=', $columnName_arr[0]['search']['value']);
        }
        if (isset($columnName_arr[1]['search']['value']) && !empty($columnName_arr[1]['search']['value'])) {
            $ReferenceId = $ReferenceId->whereDate('reference_ids.created_at', '=', $columnName_arr[1]['search']['value']);
        }
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $ReferenceId = $ReferenceId->where('selection_date', '=', $columnName_arr[2]['search']['value']);
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $ReferenceId = $ReferenceId->where('type_of_inward', 'ilike', '%'.$columnName_arr[3]['search']['value'].'%');
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $ReferenceId = $ReferenceId->where('companies.company_name', 'ilike', '%' . $columnName_arr[4]['search']['value'] . '%');
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $ReferenceId = $ReferenceId->where(function ($q) use($columnName_arr) {
                $q->orWhere('employees.firstname', 'ILIKE', '%' . $columnName_arr[5]['search']['value'] . '%')
                ->orWhere('employees.middlename', 'ILIKE', '%' . $columnName_arr[5]['search']['value'] . '%')
                ->orWhere('employees.lastname', 'ILIKE', '%' . $columnName_arr[5]['search']['value'] . '%');
            });
        }
        if (isset($columnName_arr[6]['search']['value']) && !empty($columnName_arr[6]['search']['value'])) {
            if (in_array($columnName_arr[6]['search']['value'], ['in', 'In', 'inward', 'Inward'])) {
                $ReferenceId = $ReferenceId->where('inward_or_outward', 1);
            } else {
                $ReferenceId = $ReferenceId->where('inward_or_outward', 0);
            }
        }
        if ($employees->user_group == 21) {
            $ReferenceId = $ReferenceId->where('employee_id', '=', $employees->id);
        } else if ($employees->user_group != 21 && $employees->user_group != 1) {
            $ReferenceId = $ReferenceId->where('employee_id', '=', $employees->id)
                ->orWhere('employee_id', '=', 15);
        }
        if ($columnName == 'firstname') {
            $columnName = 'employees.firstname';
        } else if ($columnName == 'company_name') {
            $columnName = 'companies.company_name';
        } else {
            $columnName = 'reference_ids.' . $columnName;
        }
        $ReferenceId = $ReferenceId->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

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
            elseif ($type_of_inward == 'Whatsapp') {
                $type_of_inward = '<em class="icon ni ni-whatsapp" title="Whatsapp"></em>';
            }
            elseif ($type_of_inward == 'Email') {
                $type_of_inward = '<em class="icon ni ni-mail" title="Email"></em>';
            }
            elseif ($type_of_inward == 'Letter') {
                $type_of_inward = '<em class="icon ni ni-cards" title="Letter"></em>';
            }
            elseif ($type_of_inward == 'Courier') {
                $type_of_inward = '<em class="icon ni ni-inbox-out-fill" title="Courier"></em>';
            }
            $company_name = '<a href="#" class="view-details" data-id="'.$record->company_id.'">'.$record->company_name.'</a>';
            $firstname = $record->firstname . ' - ' . $record->lastname;
            if($record->inward_or_outward == '1') {
                $inward_or_outward = 'Inward';
            } else {
                $inward_or_outward = 'Outward';
            }
            $action = '<a href="./reference/view-reference/'.$reference_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="View"><em class="icon ni ni-eye"></em></a>
            <a href="./reference/edit-reference/'.$reference_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./reference/referenceId/delete/'.$reference_id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "reference_id" => $reference_id,
                "created_at" => $date_added,
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
        $company = Company::select('id', 'company_name', 'company_type')->get();

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

    public function listTransport(){
        $Transport = TransportDetails::get(['transport_details.id','transport_details.name']);

        return $Transport;
    }

    public function createReferenceId(){
        $page_title = 'Add Reference';
        $financialYear = $this->financialYear();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        return view('referenceId.createReferenceId',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function referenceView($id)
    {
        $page_title = 'View Reference';
        $financialYear = $this->financialYear();
        $user = Session::get('user');

        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();
        /* $reference_data = DB::table('reference_ids as r')
            ->join('employees as e', 'r.employee_id', '=', 'e.id')
            ->join('companies as c', 'r.company_id', '=', 'c.id')
            ->select('r.*', 'e.firstname', 'c.company_name')
            ->where('r.reference_id', $id)
            ->where('r.financial_year_id', $user->financial_year_id)
            ->first();
        $comboId = DB::table('comboids')
            // ->select()
            ->where('general_ref_id', $id)
            ->where('financial_year_id', $user->financial_year_id)
            ->orderBy('comboid', 'desc')
            ->get(); */

        $employees['id'] = $id;
        return view('referenceId.viewReferenceId', compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function editReferenceId($id){
        $page_title = 'Update Reference';
        $financialYear = $this->financialYear();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();
        $employees['id'] =$id;

        return view('referenceId.updateReferenceId',compact('financialYear', 'page_title'))->with('employees', $employees);
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
        $User = Session::get('user');

        $Reference = ReferenceId::where('reference_id', $id)->where('financial_year_id', $User->financial_year_id)->first();

        $Company = Company::select('id', 'company_name')->where('id', $Reference->company_id)->first();

        $Courier = TransportDetails::select('id', 'name')->where('id', $Reference->courier_name ?: 0)->first();

        /* if ($Reference->inward_or_outward == '1') {
            $inward_outward = comboids::join('reference_ids','comboids.general_ref_id','=','reference_ids.id')
                ->join('inwards','comboids.iuid','=','inwards.iuid')
                ->join('users','comboids.outward_employe_id','=','users.employee_id')
                ->join('companies','comboids.company_id','=','companies.id')
                ->where('reference_ids.financial_year_id', $User->financial_year_id)
                ->where('comboids.company_id', $Reference->company_id)
                ->get();
        }
        else{
            $inward_outward = comboids::join('reference_ids','comboids.general_ref_id','=','reference_ids.id')
                ->join('outwards','comboids.ouid','=','outwards.ouid')
                ->join('users','comboids.outward_employe_id','=','users.employee_id')
                ->join('companies','comboids.company_id','=','companies.id')
                ->where('reference_ids.financial_year_id', $User->financial_year_id)
                ->where('comboids.company_id',$Reference->company_id)
                ->get();
        } */
        $inward_outward = DB::table('comboids as cb')
            ->leftJoin('companies as cp', 'cb.company_id', '=', 'cp.id')
            ->join('employees as e1', 'cb.generated_by', '=', 'e1.id')
            ->join('employees as e2', 'cb.assigned_to', '=', 'e2.id')
            ->select('cb.comboid', 'cb.iuid', 'cb.ouid', 'cb.general_ref_id', 'cb.system_module_id', 'cb.selection_date', 'cb.inward_or_outward_via', 'cb.followup_via', 'cb.subject', 'cb.created_at', 'cp.company_name', 'e1.firstname as generated_by', 'e2.firstname as assigned_to')
            ->where('cb.general_ref_id', $id)
            ->where('cb.financial_year_id', $User->financial_year_id)
            ->orderBy('cb.comboid', 'desc')
            ->get();

        $ReferenceData['Reference'] = $Reference;
        $ReferenceData['User'] = $User;
        $ReferenceData['Company'] = $Company;
        $ReferenceData['inward_outward'] = $inward_outward;
        $ReferenceData['Courier'] = $Courier;
        return $ReferenceData;
    }

    public function fetchcompany(){

        $companyData = [];
        // $companyList = Company::get();
        $companyCity = Cities::get();
        $companyAddress = CompanyAddress::join('type_of_addresses', 'company_addresses.address_type', '=', 'type_of_addresses.id')->get();
        $companyContactDetails = CompanyContactDetails::join('designations', 'company_contact_details.contact_person_designation', '=', 'designations.id')->get();
        $multipleEmails = CompanyEmails::get();
        $packagingDetails = CompanyPackagingDetails::get();
        // select company_address_owners.*, designations.name as designation_name from "company_address_owners" inner join "designations" on "company_address_owners"."designation" ? "designations"."id"::text
        // $multipleAddress = DB::table('company_address_owners')
        //     ->join('designations', function($j) {
        //         $j->on('company_address_owners.designation', DB::raw("?"), DB::raw('designations.id'));
        //     })
        // ->select('company_address_owners.*', 'designations.name as designation_name')
        // ->get();
        // $multipleAddress = DB::connection('pgsql')->getPdo()->prepare('select company_address_owners.*, designations.name as designation_name from "company_address_owners" inner join "designations" on "company_address_owners"."designation" ?? "designations"."id"::text')->fetchAll();
        // dd($multipleAddress);

        // $companyData['companyList'] = $companyList;
        $companyData['companyCity'] = $companyCity;
        $companyData['companyAddress'] = $companyAddress;
        $companyData['companyContactDetails'] = $companyContactDetails;
        $companyData['multipleEmails'] = $multipleEmails;
        $companyData['multipleAddress'] = $multipleAddress ?? [];
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
            if ($request->inward_outward == 1) {
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
            elseif ($request->inward_outward == 0) {
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
        else {
            if ($request->Reference_via['name'] == 'Courier') {
                $latterBy = '1';
            }
            elseif ($request->Reference_via['name'] == 'Hand'){
                $latterBy = '0';
            }
            else {
                $latterBy = '0';
            }
            $marks = 0;
            if (isset($request->markssample)) {
                $marks = 1;
            }

            $ReferenceId = new ReferenceId;
            $ReferenceId->inward_or_outward = $request->inward_outward;
            if ($Reference == null) {
                $ReferenceId->reference_id = '1';
                $ReferenceId->inward_or_outward = $request->inward_outward;
            }
            else {
                $ReferenceId->reference_id = $Reference->reference_id + '1';
            }
            $ReferenceId->id = $ReferencesId;
            $ReferenceId->financial_year_id = Session::get('user')->financial_year_id;
            $ReferenceId->type_of_inward= !empty($request->Reference_via['name']) ? $request->Reference_via['name'] : '';
            $ReferenceId->company_id = !empty($request->companyName) ? $request->companyName['id'] : '';
            $ReferenceId->employee_id = $user->employee_id;
            $ReferenceId->selection_date = date('Y-m-d', strtotime($request->Date_Time));
            $ReferenceId->latter_by_id = $latterBy;
            $ReferenceId->courier_name = !empty($request->courier_company['id']) ? $request->courier_company['id'] : '';
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
