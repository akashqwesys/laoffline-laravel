<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logs;
use DB;
use App\Models\Employee;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\Session;

class LogsController extends Controller
{
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
        $logs->log_path = 'Employe / View';
        $logs->log_subject = 'Employee view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('logs',compact('financialYear'))->with('employees', $employees);
    }

    public function listLogs(Request $request) {
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
            $columnName = 'logs.'.$columnName;
        }
        // Total records
        $totalRecords = Logs::select('count(*) as allcount')->count();

        $totalRecordswithFilter = Logs::select('count(*) as allcount');
        if (isset($columnName_arr[2]['search']['value']) && !empty($columnName_arr[0]['search']['value'])) {
            $totalRecordswithFilter = $totalRecordswithFilter->where(function ($q) use ($columnName_arr, $searchValue) {
                $q->orWhere('logs.log_subject', 'ILIKE', '%' . $searchValue . '%');
            });
        }
        $totalRecordswithFilter = Logs::select('count(*) as allcount')->
                                                   where('log_subject', 'ilike', '%' .$searchValue . '%')->
                                                   count();


        // Fetch records
        $records = Logs::select('*')->
                                where('log_subject', 'ilike', '%' .$searchValue . '%');

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $employee = DB::table('employees')->where('id',$record->employee_id)->first();
            $id = $record->id;
            $subject = $record->log_subject;
            $log_path = $record->log_path;
            $emp  = $employee->firstname;
            $time = date_format($record->created_at, "Y/m/d H:i:s");
            
            $data_arr[] = array(
                "id" => $id,
                "log_subject" => $subject,
                "log_path" => $log_path,
                "Employee" => $emp,
                "created_at" => $time,
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
}
