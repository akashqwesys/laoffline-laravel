<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use App\Models\Logs;
use App\Models\FinancialYear;
use App\Models\Settings\DefaultSettings;
use Illuminate\Support\Facades\Session;

class DefaultSettingsController extends Controller
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
        
        $employees['scope'] = 'edit';
        $employees['editedId'] = $user->employee_id;

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Default Settings / View';
        $logs->log_subject = 'Default Settings view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('settings.defaultSettings.createDefaultSetting',compact('financialYear'))->with('employees', $employees);
    }

    public function editDefaultSettings($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('settings.defaultSettings.editDefaultSetting',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchDefaultSettings($id) {
        $defaultSettingsData = DefaultSettings::where('employee_id', $id)->first();

        return $defaultSettingsData;
    }

    public function updateDefaultSettingsData(Request $request) {
        $this->validate($request, [
            'cgst' => 'required',
            'sgst' => 'required',
            'igst' => 'required',
            'tds' => 'required',
            'service_tax_limit' => 'required',
        ]);
        
        $id = Session::get('user')->employee_id;

        $defaultSettings = DefaultSettings::where('employee_id', $id)->first();
        if ($defaultSettings) {
            $defaultSettings->cgst = $request->cgst;
            $defaultSettings->sgst = $request->sgst;
            $defaultSettings->igst = $request->igst;
            $defaultSettings->tds = $request->tds;
            $defaultSettings->service_tax_limit = $request->service_tax_limit;
            $defaultSettings->save();

        } else {
            $defaultSettings = new DefaultSettings;
            $defaultSettings->employee_id = $id;
            $defaultSettings->cgst = $request->cgst;
            $defaultSettings->sgst = $request->sgst;
            $defaultSettings->igst = $request->igst;
            $defaultSettings->tds = $request->tds;
            $defaultSettings->service_tax_limit = $request->service_tax_limit;
            $defaultSettings->save();
        }

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Default Settings';
        $logs->log_subject = 'Default Settings - "'.$request->name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
