<?php

namespace App\Http\Controllers\databank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\FinancialYear;
use App\Models\UserGroup;
use App\Models\Employee;
use App\Models\Logs;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;

class UserGroupController extends Controller
{
    use HasRoles;

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

        return view('databank.userGroups.userGroup',compact('financialYear'))->with('employees', $employees);
    }

    public function createUserGroup() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('databank.userGroups.createUserGroup',compact('financialYear'))->with('employees', $employees);
    }

    public function getPermissions() {
        $permissions = Permission::all();

        return $permissions;
    }

    public function listData(Request $request) {
        $userGroup = UserGroup::where('is_delete', '0')->get();

        return $userGroup;
    }

    public function listUserGroup(Request $request) {
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
        $totalRecords = UserGroup::select('count(*) as allcount')->count();
        if (!empty(trim($searchValue))) {
            $totalRecordswithFilter = UserGroup::select('count(*) as allcount')
                ->where('is_delete', 0)
                ->where('name', 'ILIKE', '%' .$searchValue . '%')
                ->count();
        } else {
            $totalRecordswithFilter = $totalRecords;
        }

        // Fetch records
        $records = UserGroup::select('*')
            ->where('is_delete', 0);
        if (!empty(trim($searchValue))) {
            $records = $records->where('name', 'ILIKE', '%' .$searchValue . '%');
        }
        $records = $records->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            $name = $record->name;
            $action = '<a href="./users-group/edit-user-group/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./users-group/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

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

    public function editUserGroup($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('databank.userGroups.editUserGroup',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchUserGroup($id) {
        $userGroupData = UserGroup::where('id', $id)->first();

        return $userGroupData;
    }

    public function deleteUserGroup($id){
        $userGroupData = UserGroup::where('id', $id)->first();
        $userGroupData->is_delete = 1;
        $userGroupData->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Usergroup / Delete';
        $logs->log_subject = 'Usergroup - '.$userGroupData->name.' was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('users-group');
    }

    public function insertUserGroupData(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'access_permission' => 'required',
            'modify_permission' => 'required',
        ]);

        $permissions = array_merge($request->access_permission,$request->modify_permission);

        $roleLastId = Role::orderBy('id', 'DESC')->first('id');
        $roleId = !empty($roleLastId) ? $roleLastId->id + 1 : 1;

        // $role = Role::create(['name' => $request->name]);
        $role = new Role;
        $role->id = $roleId;
        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($permissions);

        $userGroupLastId = UserGroup::orderBy('id', 'DESC')->first('id');
        $userGroupId = !empty($userGroupLastId) ? $userGroupLastId->id + 1 : 1;

        $userGroup = new UserGroup;
        $userGroup->id = $userGroupId;
        $userGroup->name = $request->name;
        $userGroup->roles_id = $role->id;
        $userGroup->access_permissions = json_encode($request->access_permission);
        $userGroup->modify_permissions = json_encode($request->modify_permission);
        $userGroup->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Usergroup / Add';
        $logs->log_subject = 'Usergroup - "'.$userGroup->name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateUserGroupData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'access_permission' => 'required',
            'modify_permission' => 'required',
        ]);

        $id = $request->id;

        $userGroup = UserGroup::where('id', $id)->first();
        $userGroup->name = $request->name;
        $userGroup->access_permissions = json_encode($request->access_permission);
        $userGroup->modify_permissions = json_encode($request->modify_permission);
        $userGroup->save();

        $role = Role::where('id', $userGroup->roles_id)->first();
        $role->name = $request->name;
        $role->save();

        $permissions = array_merge($request->access_permission,$request->modify_permission);
        $role->syncPermissions($permissions);

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Usergroup / Update';
        $logs->log_subject = 'Usergroup - "'.$userGroup->name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
