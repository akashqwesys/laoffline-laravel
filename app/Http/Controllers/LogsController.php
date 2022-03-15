<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logs;
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

    public function listLogs() {
        $user = Session::get('user');
        $logs = Logs::join('employees', 'logs.employee_id', '=', 'employees.id')->where('employees.id', $user->employee_id)->get(['logs.*', 'employees.firstname']);

        return $logs;
    }
}
