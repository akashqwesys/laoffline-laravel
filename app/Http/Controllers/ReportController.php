<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\Employee;
use App\Models\Comboids\Comboids;
use App\Models\SaleBill;
use App\Models\SaleBillTransport;
use Illuminate\Support\Facades\Session;
use DB;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function salesRegister(Request $request)
    {
        $page_title = 'Sale Register Report';
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
        $logs->log_path = 'Sales Register Report / View';
        $logs->log_subject = 'Sales Register Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.sales_register_report', compact('page_title', 'employees'));
    }

    public function viewSalesRegisterData(Request $request)
    {

    }

}
