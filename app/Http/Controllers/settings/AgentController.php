<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use App\Models\Logs;
use App\Models\FinancialYear;
use App\Models\Settings\Agent;
use Illuminate\Support\Facades\Session;

class AgentController extends Controller
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
        $logs->log_path = 'Settings / Agent / View';
        $logs->log_subject = 'Agent view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('settings.agents.agent',compact('financialYear'))->with('employees', $employees);
    }

    public function createAgent() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('settings.agents.createAgent',compact('financialYear'))->with('employees', $employees);
    }

    public function listData(Request $request) {
        $agent = Agent::where('is_delete', '0')->get();

        return $agent;
    }

    public function listAgent(Request $request) {
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

        $totalRecords = Agent::where('id', '!=', '0')->select('count(*) as allcount')->count();
        $totalRecordswithFilter = Agent::select('count(*) as allcount')->
                                                   where('id', '!=', '0')->
                                                   where('name', 'like', '%' .$searchValue . '%')->
                                                   count();

        $Agent = Agent::orderBy('agents.'.$columnName,$columnSortOrder)->
                        where('agents.name', 'like', '%' .$searchValue . '%')->
                        where('agents.is_delete', '0')->
                        skip($start)->
                        take($rowperpage)->
                        get();

        $data_arr = array();
        $sno = $start+1;

        foreach($Agent as $record){
            $id = $record->id;
            $name = $record->name;
            $panNo = $record->pan_no;
            $default = $record->default;
            $action = '<a href="./agent/edit-agent/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./agent/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "panNo" => $panNo,
                "default" => $default,
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

    public function editAgent($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('settings.agents.editAgent',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchAgent($id) {
        $agentData = Agent::where('id', $id)->first();

        return $agentData;
    }

    public function deleteAgent($id){
        $agentData = Agent::where('id',$id)->first();
        $agentData->is_delete = 1;
        $agentData->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Agent / Delete';
        $logs->log_subject = 'Agent - "'.$agentData->name.'" was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('agent');
    }

    public function insertAgentData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'pan_no' => 'required',
            'gst_no' => 'required',
            'default' => 'required',
        ]);

        $agentLastId = Agent::orderBy('id', 'DESC')->first('id');
        $agentId = !empty($agentLastId) ? $agentLastId->id + 1 : 1;

        $agent = new Agent;
        $agent->id = $agentId;
        $agent->name = $request->name;
        $agent->pan_no = $request->pan_no;
        $agent->gst_no = $request->gst_no;
        $agent->default = $request->default;
        $agent->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Agent / Add';
        $logs->log_subject = 'Agent - "'.$request->name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateAgentData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'pan_no' => 'required',
            'gst_no' => 'required',
            'default' => 'required',
        ]);

        $id = $request->id;

        $agent = Agent::where('id', $id)->first();
        $agent->name = $request->name;
        $agent->pan_no = $request->pan_no;
        $agent->gst_no = $request->gst_no;
        $agent->default = $request->default;
        $agent->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Agent / Edit';
        $logs->log_subject = 'Agent - "'.$request->name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
