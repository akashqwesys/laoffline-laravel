<?php

namespace App\Http\Controllers\Auth;

use Carbon;
use App\Models\Logs;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use App\Models\Settings\DefaultSettings;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        } else {
            return view('auth.login');
        }
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt(array('username' => $input['email'], 'password' => $input['password'], 'is_active' => 1))) {
            $financialYear = FinancialYear::where('current_year_flag', '1')->first();

            $user = Auth::User();
            $employee = Employee::where('id', $user->employee_id)->first();
            $defaultSetting = DefaultSettings::first();
            $user['excel_access'] = $employee->excel_access;
            $user['user_email'] = $employee->email_id;
            $user['financial_year'] = $financialYear->name;
            $user['financial_year_start_date'] = $financialYear->start_date;
            $user['financial_year_end_date'] = $financialYear->end_date;
            $user['service_tax'] = $defaultSetting->service_tax_limit ?? 15;
            $user['tds'] = $defaultSetting->tds ?? 5;
            $user['tax_limit'] = 0;
            $user['cgst'] = $defaultSetting->cgst ?? 9;
            $user['sgst'] = $defaultSetting->sgst ?? 9;
            $user['sgst_per'] = 9;
            $user['igst'] = $defaultSetting->igst ?? 18;

            $user['financial_year_id'] = $financialYear->id;

            $user['extension_port_id'] = $employee->extension_port_id;

            $all_fy = FinancialYear::orderBy('id', 'desc')->get();

            Session::put('user', $user);
            Session::put('all_fy', $all_fy);

            $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
            $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

            $logs = new Logs;
            $logs->id = $logsId;
            $logs->employee_id = $user->employee_id;
            $logs->log_path = 'Login';
            $logs->log_subject = 'Login Successfully.';
            $logs->log_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $logs->save();

            $employee = Employee::where('id', $user->employee_id)->first();
            $employee->web_login = Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $employee->save();

            return redirect()->route('home');
        } else {
            return redirect()->route('login')
                ->with('error', 'Incorrect username or password.')
                ->withInput();
        }
    }
}
