<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use App\Models\Logs;
use App\Models\FinancialYear;
use App\Models\Settings\TypeOfAddress;
use Illuminate\Support\Facades\Session;

class TypeOfAddressController extends Controller
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
        $logs->log_path = 'Settings / TypeOfAddress / View';
        $logs->log_subject = 'TypeOfAddress view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('settings.typeOfAddresses.typeOfAddress',compact('financialYear'))->with('employees', $employees);
    }

    public function createTypeOfAddress() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('settings.typeOfAddresses.createTypeOfAddress',compact('financialYear'))->with('employees', $employees);
    }

    public function listData(Request $request) {
        $typeOfAddress = TypeOfAddress::where('is_delete', '0')->get();

        return $typeOfAddress;
    }

    public function listTypeOfAddress(Request $request) {
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

        $totalRecords = TypeOfAddress::where('id', '!=', '0')->select('count(*) as allcount')->count();
        $totalRecordswithFilter = TypeOfAddress::select('count(*) as allcount')->
                                                   where('id', '!=', '0')->
                                                   where('name', 'like', '%' .$searchValue . '%')->
                                                   count();

        $TypeOfAddress = TypeOfAddress::orderBy($columnName,$columnSortOrder)->
                where('name', 'like', '%' .$searchValue . '%')->
                where('is_delete', '0')->
                skip($start)->
                take($rowperpage)->
                get();

        $data_arr = array();
        $sno = $start+1;

        foreach($TypeOfAddress as $record){
            $id = $record->id;
            $name = $record->name;
            $action = '<a href="./type-of-address/edit-type-of-address/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./type-of-address/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

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

    public function editTypeOfAddress($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('settings.typeOfAddresses.editTypeOfAddress',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchTypeOfAddress($id) {
        $typeOfAddressData = TypeOfAddress::where('id', $id)->first();

        return $typeOfAddressData;
    }

    public function deleteTypeOfAddress($id){
        $typeOfAddressData = TypeOfAddress::where('id',$id)->first();
        $typeOfAddressData->is_delete = 1;
        $typeOfAddressData->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / TypeOfAddress / Delete';
        $logs->log_subject = 'TypeOfAddress - "'.$typeOfAddressData->name.'" was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('type-of-address');
    }

    public function insertTypeOfAddressData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'sort_order' => 'required'
        ]);

        $typeOfAddressLastId = TypeOfAddress::orderBy('id', 'DESC')->first('id');
        $typeOfAddressId = !empty($typeOfAddressLastId) ? $typeOfAddressLastId->id + 1 : 1;

        $typeOfAddress = new TypeOfAddress;
        $typeOfAddress->id = $typeOfAddressId;
        $typeOfAddress->name = $request->name;
        $typeOfAddress->sort_order = $request->sort_order;
        $typeOfAddress->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / TypeOfAddress / Add';
        $logs->log_subject = 'TypeOfAddress - "'.$request->name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateTypeOfAddressData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'sort_order' => 'required'
        ]);

        $id = $request->id;

        $typeOfAddress = TypeOfAddress::where('id', $id)->first();
        $typeOfAddress->name = $request->name;
        $typeOfAddress->sort_order = $request->sort_order;
        $typeOfAddress->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / TypeOfAddress / Edit';
        $logs->log_subject = 'TypeOfAddress - "'.$request->name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
