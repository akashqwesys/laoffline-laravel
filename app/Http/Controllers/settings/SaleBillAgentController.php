<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use App\Models\Logs;
use App\Models\FinancialYear;
use App\Models\Settings\SaleBillAgent;
use Illuminate\Support\Facades\Session;

class SaleBillAgentController extends Controller
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
        $logs->log_path = 'Settings / Sale Bill Agent / View';
        $logs->log_subject = 'Sale Bill Agent view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('settings.saleBillAgents.saleBillAgent',compact('financialYear'))->with('employees', $employees);
    }

    public function createSaleBillAgent() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('settings.saleBillAgents.createSaleBillAgent',compact('financialYear'))->with('employees', $employees);
    }

    public function listData(Request $request) {
        $saleBillAgent = SaleBillAgent::where('is_delete', '0')->get();

        return $saleBillAgent;
    }

    public function listSaleBillAgent(Request $request) {
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

        $totalRecords = SaleBillAgent::where('id', '!=', '0')->select('count(*) as allcount')->count();
        $totalRecordswithFilter = SaleBillAgent::select('count(*) as allcount')->
                                                   where('id', '!=', '0')->
                                                   where('name', 'like', '%' .$searchValue . '%')->
                                                   count();

        $SaleBillAgent = SaleBillAgent::orderBy('sale_bill_agents.'.$columnName,$columnSortOrder)->
                where('sale_bill_agents.name', 'like', '%' .$searchValue . '%')->
                where('sale_bill_agents.is_delete', '0')->
                skip($start)->
                take($rowperpage)->
                get();

        $data_arr = array();
        $sno = $start+1;

        foreach($SaleBillAgent as $record){
            $id = $record->id;
            $name = $record->name;
            $default = $record->default;
            $action = '<a href="./sale-bill-agent/edit-sale-bill-agent/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./sale-bill-agent/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
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

    public function editSaleBillAgent($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('settings.saleBillAgents.editSaleBillAgent',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchSaleBillAgent($id) {
        $saleBillAgentData = SaleBillAgent::where('id', $id)->first();

        return $saleBillAgentData;
    }

    public function deleteSaleBillAgent($id){
        $saleBillAgentData = SaleBillAgent::where('id',$id)->first();
        $saleBillAgentData->is_delete = 1;
        $saleBillAgentData->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / SaleBillAgent / Delete';
        $logs->log_subject = 'SaleBillAgent - "'.$saleBillAgentData->name.'" was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('sale-bill-agent');
    }

    public function insertSaleBillAgentData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'default' => 'required',
        ]);

        $saleBillAgentLastId = SaleBillAgent::orderBy('id', 'DESC')->first('id');
        $saleBillAgentId = !empty($saleBillAgentLastId) ? $saleBillAgentLastId->id + 1 : 1;

        $saleBillAgent = new SaleBillAgent;
        $saleBillAgent->id = $saleBillAgentId;
        $saleBillAgent->name = $request->name;
        $saleBillAgent->default = $request->default;
        $saleBillAgent->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / SaleBillAgent / Add';
        $logs->log_subject = 'SaleBillAgent - "'.$request->name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateSaleBillAgentData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'default' => 'required',
        ]);

        $id = $request->id;

        $saleBillAgent = SaleBillAgent::where('id', $id)->first();
        $saleBillAgent->name = $request->name;
        $saleBillAgent->default = $request->default;
        $saleBillAgent->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / SaleBillAgent / Edit';
        $logs->log_subject = 'SaleBillAgent - "'.$request->name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
