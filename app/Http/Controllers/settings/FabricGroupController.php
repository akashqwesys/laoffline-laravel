<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use App\Models\Logs;
use App\Models\FinancialYear;
use App\Models\Settings\ProductFabricGroup;
use Illuminate\Support\Facades\Session;

class FabricGroupController extends Controller
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
        $logs->log_path = 'Settings / Fabric Group / View';
        $logs->log_subject = 'Fabric Group view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('settings.fabricGroups.fabricGroup',compact('financialYear'))->with('employees', $employees);
    }

    public function createFabricGroup() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('settings.fabricGroups.createFabricGroup',compact('financialYear'))->with('employees', $employees);
    }

    public function listData(Request $request) {
        $productFabricGroup = ProductFabricGroup::where('is_delete', '0')->get();

        return $productFabricGroup;
    }

    public function listFabricGroup(Request $request) {
        // $fabricGroup = ProductFabricGroup::all();

        // return $fabricGroup;
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

        $totalRecords = ProductFabricGroup::where('id', '!=', '0')->select('count(*) as allcount')->count();
        $totalRecordswithFilter = ProductFabricGroup::select('count(*) as allcount')->
                                                   where('id', '!=', '0')->
                                                   where('name', 'like', '%' .$searchValue . '%')->
                                                   count();

        $ProductFabricGroup = ProductFabricGroup::orderBy('product_fabric_groups.'.$columnName,$columnSortOrder)->
                where('product_fabric_groups.name', 'like', '%' .$searchValue . '%')->
                where('product_fabric_groups.is_delete', '0')->
                skip($start)->
                take($rowperpage)->
                get();

        $data_arr = array();
        $sno = $start+1;

        foreach($ProductFabricGroup as $record){
            $id = $record->id;
            $name = $record->name;
            $action = '<a href="./fabricGroup/edit-fabricGroup/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./fabricGroup/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

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

    public function editFabricGroup($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('settings.fabricGroups.editFabricGroup',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchFabricGroup($id) {
        $fabricGroupData = ProductFabricGroup::where('id', $id)->first();

        return $fabricGroupData;
    }

    public function deleteFabricGroup($id){
        $fabricGroupData = ProductFabricGroup::where('id',$id)->first();
        $fabricGroupData->is_delete = 1;
        $fabricGroupData->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Fabric Group / Delete';
        $logs->log_subject = 'Fabric Group - "'.$fabricGroupData->name.'" was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('fabricGroup');
    }

    public function insertFabricGroupData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $fabricGroupsLastId = ProductFabricGroup::orderBy('id', 'DESC')->first('id');
        $fabricGroupsId = !empty($fabricGroupsLastId) ? $fabricGroupsLastId->id + 1 : 1;

        $fabricGroups = new ProductFabricGroup;
        $fabricGroups->id = $fabricGroupsId;
        $fabricGroups->name = $request->name;
        $fabricGroups->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Fabric Group / Add';
        $logs->log_subject = 'Fabric Group - "'.$request->name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateFabricGroupData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $id = $request->id;

        $fabricGroups = ProductFabricGroup::where('id', $id)->first();
        $fabricGroups->name = $request->name;
        $fabricGroups->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Fabric Group / Edit';
        $logs->log_subject = 'Fabric Group - "'.$request->name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
