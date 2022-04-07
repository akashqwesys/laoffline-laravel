<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Traits\HasRoles;
use App\Models\FinancialYear;
use App\Models\Employee;
use App\Models\Logs;
use App\Models\settings\BankDetails;
use App\Models\Company\Company;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;

class PaymentsController extends Controller
{
    use HasRoles;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $page_title = 'Payment List';
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
        $logs->log_path = 'payment / View';
        $logs->log_subject = 'Payment view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('payment.payment',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function createPayment() {
        $page_title = 'Add Payments step - 1';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('payment.createPayment',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function listSeller() {
        $seller = Company::where('company_type',3)->get();
        return $seller;
    }

    public function listCustomer() {
        $customer = Company::where('company_type',2)->get();
        return $customer;
    }

    public function listBank() {
        $bank = BankDetails::all();
        return $bank;
    }

    public function searchSaleBill(Request $request) {
        
    }

    public function generatePaymentData(Request $request){
        $request->session()->forget('customer');
        $request->session()->forget('seller');
        $request->session()->forget('saleBill');
        $request->session()->put('customer', $request->customer);
        $request->session()->put('seller', $request->seller);
        $request->session()->put('saleBill', $request->salebill);
        return true;
    }
    public function selectSaleBills(Request $request){
        print_r($request->input());
        exit;
    }
    public function insertPaymentData(Request $request){
        print_r($request->input());exit;
    }
    public function getBasicData(Request $request) {
        
        $customer_id = $request->session()->get('customer');
        $seller_id = $request->session()->get('seller');
        $salebill_ids = $request->session()->get('saleBill');
        $customer = Company::where('id', $customer_id)->first();
        $seller = Company::where('id', $seller_id)->first();
        $salebill = [array('id' => '101', 'sup_inv' => '1025', 'amount' => '5000'),
        array('id' => '103', 'sup_inv' => '1028', 'amount' => '15000')];
        $data['customer'] = $customer;
        $data['seller'] = $seller;
        $data['salebill'] = $salebill;
        return $data;
    }
    public function addPayment(Request $request){
        $page_title = 'Add Payments';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('payment.addpayment',compact('financialYear', 'page_title'))->with('employees', $employees);
    }
}
