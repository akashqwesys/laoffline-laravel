<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use App\Models\Logs;
use App\Models\FinancialYear;
use App\Models\Settings\BankDetails;
use Illuminate\Support\Facades\Session;

class BankDetailsController extends Controller
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
        $logs->log_path = 'Settings / Bank Details / View';
        $logs->log_subject = 'Bank Details view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('settings.bankDetails.bankDetail',compact('financialYear'))->with('employees', $employees);
    }

    public function createBankDetails() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('settings.bankDetails.createBankDetail',compact('financialYear'))->with('employees', $employees);
    }

    public function listData(Request $request) {
        $bankDetails = BankDetails::where('is_delete', '0')->get();

        return $bankDetails;
    }

    public function listBankDetails(Request $request) {
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

        $totalRecords = BankDetails::where('id', '!=', '0')->select('count(*) as allcount')->count();
        $totalRecordswithFilter = BankDetails::select('count(*) as allcount')->
                                                   where('id', '!=', '0')->
                                                   where('name', 'like', '%' .$searchValue . '%')->
                                                   count();

        $BankDetails = BankDetails::orderBy('bank_details.'.$columnName,$columnSortOrder)->
                where('bank_details.name', 'like', '%' .$searchValue . '%')->
                where('bank_details.is_delete', '0')->
                skip($start)->
                take($rowperpage)->
                get();

        $data_arr = array();
        $sno = $start+1;

        foreach($BankDetails as $record){
            $id = $record->id;
            $name = $record->name;
            $action = '<a href="./bank-details/edit-bank-details/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./bank-details/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

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

    public function editBankDetails($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('settings.bankDetails.editBankDetail',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchBankDetails($id) {
        $bankDetailsData = BankDetails::where('id', $id)->first();

        return $bankDetailsData;
    }

    public function deleteBankDetails($id){
        $bankDetailsData = BankDetails::where('id',$id)->first();
        $bankDetailsData->is_delete = 1;
        $bankDetailsData->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Bank Details / Delete';
        $logs->log_subject = 'Bank Details - "'.$bankDetailsData->name.'" was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('bank-details');
    }

    public function insertBankDetailsData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'sort_order' => 'required',
        ]);

        $bankDetailsLastId = BankDetails::orderBy('id', 'DESC')->first('id');
        $bankDetailsId = !empty($bankDetailsLastId) ? $bankDetailsLastId->id + 1 : 1;

        $bankDetails = new BankDetails;
        $bankDetails->id = $bankDetailsId;
        $bankDetails->name = $request->name;
        $bankDetails->sort_order = $request->sort_order;
        $bankDetails->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Bank Details / Add';
        $logs->log_subject = 'Bank Details - "'.$request->category_name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateBankDetailsData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'sort_order' => 'required',
        ]);

        $id = $request->id;

        $bankDetails = BankDetails::where('id', $id)->first();
        $bankDetails->name = $request->name;
        $bankDetails->sort_order = $request->sort_order;
        $bankDetails->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Bank Details / Edit';
        $logs->log_subject = 'Bank Details - "'.$request->category_name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
