<?php

namespace App\Http\Controllers\databank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\UserGroup;
use App\Models\Employee;
use App\Models\User;
use App\Models\Logs;
use App\Models\FinancialYear;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;

class EmployeesController extends Controller
{
    use HasRoles;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $page_title = 'Employees List';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        $employees['excelAccess'] = $user->excel_access;

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Employe / View';
        $logs->log_subject = 'Employee view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('databank.employees.employee', compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function createEmployee() {
        $page_title = 'Add Employee';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('databank.employees.createEmployee',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function getPermissions() {
        $permissions = Permission::all();

        return $permissions;
    }

    public function listData(Request $request) {
        $employee = Employee::where('is_delete', '0')->get();

        return $employee;
    }

    public function listEmployee(Request $request) {
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
            $columnName = 'employees.'.$columnName;
        }
        // Total records
        $totalRecords = Employee::select('count(*) as allcount')->count();

        $totalRecordswithFilter = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->select('count(*) as allcount')
            ->where('employees.id', '!=', $user->employee_id)
            ->where('employees.user_group', '!=', 1)
            ->where('employees.is_delete', '=', 0);
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where(function ($q) use ($columnName_arr) {
                $q->orWhere('employees.firstname', 'ILIKE', '%' . $columnName_arr[2]['search']['value'] . '%')
                ->orWhere('employees.middlename', 'ILIKE', '%' . $columnName_arr[2]['search']['value'] . '%')
                ->orWhere('employees.lastname', 'ILIKE', '%' . $columnName_arr[2]['search']['value'] . '%');
            });
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where(function ($q) use ($columnName_arr) {
                $q->orWhere('employees.email_id', 'ILIKE', '%' . $columnName_arr[3]['search']['value'] . '%');
            });
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where(function ($q) use ($columnName_arr) {
                $q->orWhere('employees.mobile', 'ILIKE', '%' . $columnName_arr[4]['search']['value'] . '%');
            });
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where(function ($q) use ($columnName_arr) {
                $q->orWhere('user_groups.name', 'ILIKE', '%' . $columnName_arr[5]['search']['value'] . '%');
            });
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();

        // Fetch records
        $records = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->select('employees.firstname', 'employees.middlename', 'employees.lastname', 'users.employee_id', 'employees.profile_pic', 'employees.email_id', 'employees.mobile', 'employees.web_login', 'user_groups.name', 'users.is_active')
            // ->where('employees.id', '!=', $user->employee_id)
            // ->where('employees.user_group', '!=', 1)
            ->where('employees.is_delete', '=', 0);
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $records = $records->where(function ($q) use ($columnName_arr) {
                $q->orWhere('employees.firstname', 'ILIKE', '%' . $columnName_arr[2]['search']['value'] . '%')
                ->orWhere('employees.middlename', 'ILIKE', '%' . $columnName_arr[2]['search']['value'] . '%')
                ->orWhere('employees.lastname', 'ILIKE', '%' . $columnName_arr[2]['search']['value'] . '%');
            });
        }
        if (isset($columnName_arr[3]['search']['value']) && !empty($columnName_arr[3]['search']['value'])) {
            $records = $records->where(function ($q) use ($columnName_arr) {
                $q->orWhere('employees.email_id', 'ILIKE', '%' .$columnName_arr[3]['search']['value'] . '%');
            });
        }
        if (isset($columnName_arr[4]['search']['value']) && !empty($columnName_arr[4]['search']['value'])) {
            $records = $records->where(function ($q) use ($columnName_arr) {
                $q->orWhere('employees.mobile', 'ILIKE', '%' .$columnName_arr[4]['search']['value'] . '%');
            });
        }
        if (isset($columnName_arr[5]['search']['value']) && !empty($columnName_arr[5]['search']['value'])) {
            $records = $records->where(function ($q) use ($columnName_arr) {
                $q->orWhere('user_groups.name', 'ILIKE', '%' .$columnName_arr[5]['search']['value'] . '%');
            });
        }
        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $id = $record->employee_id;
            $f = substr($record->firstname, 0, 1);
            $l = substr($record->lastname, 0, 1);
            if($record->profile_pic != '') {
                $profilePic = '<img src="/upload/profilePic/'.$record->profile_pic.'" alt="">';
            } else {
                $profilePic = '<span>'.strtoupper($f.''.$l).'</span>';
            }
            $name = '<div class="user-card">';
            $name .= '<div class="user-avatar user-avatar-sm bg-warning">';
            $name .= $profilePic;
            $name .= '</div>';
            $name .= '<div class="user-name"><span class="tb-lead">'.$record->firstname.' '.$record->middlename.' '.$record->lastname.'</span></div></div>';
            $email = $record->email_id;
            $mobile = $record->mobile;
            $userGroup = $record->name;
            $webLogin = $record->web_login;
            if ($record->is_active == 1) {
                $active = '<span class="badge badge-dot badge-dot-xs badge-success">Yes</span>';
            } else {
                $active = '<span class="badge badge-dot badge-dot-xs badge-danger">No</span>';
            }
            $action = '<a href="./employee/edit-employee/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./employee/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "email" => $email,
                "mobile" => $mobile,
                "user_group" => $userGroup,
                "web_login" => $webLogin,
                "active" => $active,
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

    public function editEmployee($id) {
        $page_title = 'Update Employee';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('databank.employees.editEmployee',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function fetchEmployee($id) {
        $employeeData = Employee::join('users', 'employees.id', '=', 'users.employee_id')->where('employees.id', $id)->first(['users.*', 'employees.*', 'employees.id as employee_id']);
        $employeeData['user_group'] = UserGroup::where('id', $employeeData->user_group)->first();

        return $employeeData;
    }

    public function deleteEmployee($id){
        $employeeData = Employee::where('id',$id)->first();
        $employeeData->is_delete = 1;
        $employeeData->save();

        $user = User::where('employee_id',$id)->first();
        $user->is_delete = 1;
        $user->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Employe / Delete';
        $logs->log_subject = 'Employee - '.$user->username.' was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('employee');
    }

    public function insertEmployeeData(Request $request) {
        $profileImage = '';
        $idProofName = '';
        $referencePassPicName = '';

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email_id' => 'required|email|unique:employees',
            'user_group' => 'required',
            'excel_access' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed',
        ]);

        if ($image = $request->file('profile_pic')) {
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('upload/profilePic'), $profileImage);
        }

        if ($image = $request->file('id_proof')) {
            $idProofName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('upload/idProof'), $idProofName);
        }

        if ($image = $request->file('ref_pass_pic')) {
            $referencePassPicName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('upload/referencePassPic'), $referencePassPicName);
        }

        $employeeLastId = Employee::orderBy('id', 'DESC')->first('id');
        $employeeId = !empty($employeeLastId) ? $employeeLastId->id + 1 : 1;

        $userLastId = User::orderBy('id', 'DESC')->first('id');
        $userId = !empty($userLastId) ? $userLastId->id + 1 : 1;

        $employee = new Employee;
        $employee->id = $employeeId;
        $employee->firstname = $request->firstname;
        $employee->middlename = $request->middlename;
        $employee->lastname = $request->lastname;
        $employee->profile_pic = $profileImage;
        $employee->email_id = $request->email_id;
        $employee->mobile = $request->mobile;
        $employee->address = $request->address;
        $employee->user_group = $request->user_group['id'];
        $employee->excel_access = $request->excel_access;
        $employee->id_proof = $idProofName;
        $employee->ref_full_name = $request->ref_full_name;
        $employee->ref_pass_pic = $referencePassPicName;
        $employee->ref_mobile = $request->ref_mobile;
        $employee->ref_address = $request->ref_address;
        $employee->extension_port_id = $request->extension_port_id;
        $employee->extension_port_password = $request->extension_port_password;
        $employee->web_login = '';
        $employee->save();

        $user = new User;
        $user->id = $userId;
        $user->employee_id = $employeeId;
        $user->username = trim($request->username);
        $user->password = bcrypt($request->password);
        $user->is_active = 1;
        $user->save();

        $userGroupData = UserGroup::where('id', $request->user_group['id'])->first();

        $role = Role::where('id', $userGroupData['roles_id'])->first();

        $user->assignRole($role);

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Employe / Add';
        $logs->log_subject = 'Employee - "'.$user->username.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateEmployeeData(Request $request) {
        $profileImage = '';
        $idProofName = '';
        $referencePassPicName = '';

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email_id' => 'required|email',
            'user_group' => 'required',
            'excel_access' => 'required',
            'username' => 'required',
        ]);

        $id = $request->id;

        if ($image = $request->file('profile_pic')) {
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('upload/profilePic'), $profileImage);
        }

        if ($image = $request->file('id_proof')) {
            $idProofName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('upload/idProof'), $idProofName);
        }

        if ($image = $request->file('ref_pass_pic')) {
            $referencePassPicName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('upload/referencePassPic'), $referencePassPicName);
        }

        $employee = Employee::where('id', $id)->first();
        $employee->firstname = $request->firstname ? $request->firstname : '';
        $employee->middlename = $request->middlename ? $request->middlename : '';
        $employee->lastname = $request->lastname ? $request->lastname : '';
        $employee->profile_pic = $profileImage == '' ? $employee->profile_pic : $profileImage;
        $employee->email_id = $request->email_id ? $request->email_id : '';
        $employee->mobile = $request->mobile ? $request->mobile : '';
        $employee->address = $request->address ? $request->address : '';
        $employee->user_group = $request->user_group['id'] ? $request->user_group['id'] : '';
        $employee->excel_access = $request->excel_access ? $request->excel_access : 0;
        $employee->id_proof = $idProofName == '' ? $employee->id_proof : $idProofName;
        $employee->ref_full_name = $request->ref_full_name ? $request->ref_full_name : '';
        $employee->ref_pass_pic = $referencePassPicName == '' ? $employee->ref_pass_pic : $referencePassPicName ;
        $employee->ref_mobile = $request->ref_mobile ? $request->ref_mobile : '';
        $employee->ref_address = $request->ref_address ? $request->ref_address : '';
        $employee->extension_port_id = $request->extension_port_id ? $request->extension_port_id : '';
        $employee->extension_port_password = $request->extension_port_password ? $request->extension_port_password : '';
        $employee->save();

        $user = User::where('employee_id', $id)->first();
        $user->username = $request->username;
        $user->is_active = $request->is_active;
        $user->save();

        $userGroupData = UserGroup::where('id', $request->user_group['id'])->first();

        $role = Role::where('id', $userGroupData['roles_id'])->first();

        $user->assignRole($role);

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Employe / Edit';
        $logs->log_subject = 'Employee - "'.$user->username.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
