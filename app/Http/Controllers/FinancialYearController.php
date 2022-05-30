<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Traits\HasRoles;
use App\Models\FinancialYear;
use App\Models\Employee;
use App\Models\Logs;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;

class FinancialYearController extends Controller
{
    use HasRoles;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $page_title = 'Financial Year';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            -> join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        $employees['excelAccess'] = $user->excel_access;

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'financialyear / View';
        $logs->log_subject = 'Employee view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('financialyear.financialYear',compact('financialYear', 'page_title'))->with('employees', $employees);
    }


    public function createFinancialYear() {
        $page_title = 'Add Financial Year';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('financialyear.createFinancialYear',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function getPermissions() {
        $permissions = Permission::all();

        return $permissions;
    }

    public function listData(Request $request) {
        $financialyears = FinancialYear::all();

        return $financialyears;
    }

    public function listFinancialYear(Request $request) {
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
            $columnName = 'financial_year.'.$columnName;
        }
        // Total records
        $totalRecords = FinancialYear::select('count(*) as allcount')->count();

        $totalRecordswithFilter = FinancialYear::select('count(*) as allcount');
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[2]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where(function ($q) use ($columnName_arr, $searchValue) {
                $q->orWhere('financial_year.name', 'ILIKE', '%' . $searchValue . '%');
            });
        }
        $totalRecordswithFilter = FinancialYear::select('count(*) as allcount')->
                                                   where('name', 'ilike', '%' .$searchValue . '%')->
                                                   count();


        // Fetch records
        $records = FinancialYear::select('id','name', 'start_date', 'end_date', 'current_year_flag', 'inv_prefix')->
                                where('name', 'ilike', '%' .$searchValue . '%');

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            $name = $record->name;
            $startdate = $record->start_date;
            $enddate = $record->end_date;
            $envprefix = $record->inv_prefix;
            if ($record->current_year_flag == 1) {
                $active = '<span class="badge badge-dot badge-dot-xs badge-success">Yes</span>';
            } else {
                $active = '<span class="badge badge-dot badge-dot-xs badge-danger">No</span>';
            }
            $action = '<a href="./financialyear/edit-financialyear/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./financialyear/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "start_date" => $startdate,
                "end_date" => $enddate,
                "current_year_flag" => $active,
                "inv_prefix" => $envprefix,
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

    public function editFinancialYear($id) {
        $page_title = 'Update Financial Year';
        $financialYear = FinancialYear::where('id',$id);
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('financialyear.editFinancialYear',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function fetchFinancialYear($id) {
        $financialyearData = FinancialYear::where('id', $id)->first();

        return $financialyearData;
    }

    public function insertFinancialYear(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'startdate' => 'required',
            'enddate' => 'required',
            'current_year' => 'required',
            'invprefix' => 'required',
        ]);
        if ($request->current_year == '1') {
            DB::table('financial_year')
                ->update([
                    'current_year_flag' => '0'
                ]);
        }
        $financialYear = new FinancialYear;
        $financialYear->name = $request->name;
        $financialYear->start_date = $request->startdate;
        $financialYear->end_date = $request->enddate;
        $financialYear->current_year_flag = $request->current_year;
        $financialYear->inv_prefix = $request->invprefix;
        $financialYear->save();


        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Financial Year / Add';
        $logs->log_subject = 'financial year - was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateFinancialYear(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'startdate' => 'required',
            'enddate' => 'required',
            'current_year' => 'required',
            'invprefix' => 'required',
        ]);

        $id = $request->id;
        if ($request->current_year == '1') {
            DB::table('financial_year')
                ->whereNotIn('id',[$id])
                ->update([
                    'current_year_flag' => '0'
                ]);
        }

        $financialYear = FinancialYear::where('id', $id)->first();
        $financialYear->name = $request->name ? $request->name : '';
        $financialYear->start_date = $request->startdate ? $request->startdate : '';
        $financialYear->end_date = $request->enddate ? $request->enddate : '';
        $financialYear->current_year_flag = $request->current_year ? $request->current_year : '0';
        $financialYear->inv_prefix = $request->invprefix ? $request->invprefix : '';
        $financialYear->save();


        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Financial year / Edit';
        $logs->log_subject = 'Financial Year -  was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function deleteFinancialYear($id){
        $financialYear = FinancialYear::where('id',$id)->first();
        $financialYear->delete();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Finacial Year / Delete';
        $logs->log_subject = 'Finacial year  was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('financialyear');
    }

    public function updateCurrentFinancialYear($id)
    {
        $fy = FinancialYear::select('id', 'name', 'start_date', 'end_date')->where('id', $id)->first();
        session()->get('user')->financial_year_id = $id;
        session()->get('user')->financial_year = $fy->name;
        session()->get('user')->financial_year_start_date = $fy->start_date;
        session()->get('user')->financial_year_end_date = $fy->end_date;
        return redirect()->back();
    }

}
