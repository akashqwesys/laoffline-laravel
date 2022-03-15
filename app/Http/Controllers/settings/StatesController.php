<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use App\Models\Logs;
use App\Models\FinancialYear;
use App\Models\Settings\Country;
use App\Models\Settings\State;
use Illuminate\Support\Facades\Session;

class StatesController extends Controller
{
    use HasRoles;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
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
        $logs->log_path = 'Settings / State / View';
        $logs->log_subject = 'State view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('settings.states.state',compact('financialYear'))->with('employees', $employees);
    }

    public function createStates() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('settings.states.createState',compact('financialYear'))->with('employees', $employees);
    }

    public function listCountries() {
        $countries = Country::all();

        return $countries;
    }

    public function listData(Request $request) {
        $state = State::where('is_delete', '0')->get();

        return $state;
    }

    public function listStates(Request $request) {
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

        $totalRecords = State::where('id', '!=', '0')->select('count(*) as allcount')->count();
        $totalRecordswithFilter = State::select('count(*) as allcount')->
                                                   where('id', '!=', '0')->
                                                   where('name', 'like', '%' .$searchValue . '%')->
                                                   count();

        $State = State::orderBy('states.'.$columnName,$columnSortOrder)->
                where('states.name', 'like', '%' .$searchValue . '%')->
                where('states.is_delete', '0')->
                skip($start)->
                take($rowperpage)->
                get();

        $data_arr = array();
        $sno = $start+1;

        foreach($State as $record){
            $id = $record->id;
            $name = $record->name;
            $action = '<a href="./states/edit-states/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./states/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
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

    public function editStates($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('settings.states.editState',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchStates($id) {
        $statesData = State::where('id', $id)->first();

        return $statesData;
    }

    public function deleteStates($id){
        $statesData = State::where('id',$id)->first();
        $statesData->is_delete = 1;
        $statesData->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / States / Delete';
        $logs->log_subject = 'States - "'.$statesData->name.'" was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('states');
    }

    public function insertStatesData(Request $request) {
        $this->validate($request, [
            'country_id' => 'required',
            'name' => 'required',
        ]);

        $stateLastId = State::orderBy('id', 'DESC')->first('id');
        $stateId = !empty($stateLastId) ? $stateLastId->id + 1 : 1;

        $states = new State;
        $states->id = $stateId;
        $states->country_id = $request->country_id;
        $states->name = $request->name;
        $states->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / States / Add';
        $logs->log_subject = 'States - "'.$request->name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateStatesData(Request $request) {
        $this->validate($request, [
            'country_id' => 'required',
            'name' => 'required',
        ]);

        $id = $request->id;

        $states = State::where('id', $id)->first();
        $states->country_id = $request->country_id;
        $states->name = $request->name;
        $states->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / States / Edit';
        $logs->log_subject = 'States - "'.$request->name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
