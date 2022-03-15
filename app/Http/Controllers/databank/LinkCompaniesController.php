<?php

namespace App\Http\Controllers\databank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use App\Models\Company\Company;
use App\Models\Company\CompanyContactDetails;
use App\Models\linkCompanies;
use App\Models\linkCompaniesLog;
use App\Models\Logs;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\Session;

class LinkCompaniesController extends Controller
{
    use HasRoles;

    public function __construct()
    {
        $this->middleware('auth');
    }

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
        $logs->log_path = 'Link Companies / View';
        $logs->log_subject = 'Link Companies view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('databank.linkCompanies.linkCompany',compact('financialYear'))->with('employees', $employees);
    }

    public function createLinkCompanies() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                               join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('databank.linkCompanies.createLinkCompany',compact('financialYear'))->with('employees', $employees);
    }

    public function listLinkCompanies() {
        $linkCompanies = linkCompanies::all();
        $linkCompaniesData = [];

        foreach($linkCompanies as $key => $linkCompany) {
            $linkCompaniesData[$key]['linkId'] = $linkCompany->id;

            $company = Company::where('id', $linkCompany->company_id)->first(['id', 'company_name']);
            $linkCompaniesData[$key]['company'] = $company;

            $linkedCompany = Company::where('id', $linkCompany->link_companies_id)->first(['id', 'company_name']);
            $linkCompaniesData[$key]['link_company'] = $linkedCompany;
        }

        return $linkCompaniesData;
    }

    public function listCompanies() {
        $linkCompanies = Company::get(['id', 'company_name']);

        return $linkCompanies;
    }

    public function getComapnyById($id) {
        $companyDetails = Company::where('id', $id)->first(['id', 'company_name']);

        return $companyDetails;
    }

    public function getLinkedComapnyById($id) {
        $linkedCompany = linkCompanies::where('company_id', $id)->get();
        $linkedCompanyDetails = [];

        foreach($linkedCompany as $key => $company) {
            $company = Company::where('id', $company->link_companies_id)->first(['id','company_name']);
            $linkedCompanyDetails[$key]['linkedCompanies'] = $company;
        }

        return $linkedCompanyDetails;
    }

    public function editLinkCompanies($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                               join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('databank.linkCompanies.editLinkCompany',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchLinkCompanies($id) { 
        $userGroupData = linkCompanies::where('id', $id)->first();

        return $userGroupData;
    }

    public function deleteLinkCompanies($id){
        $userGroupData = linkCompanies::where('id', $id)->first();
        
        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Link Companies / Delete';
        $logs->log_subject = 'Link Companies - '.$userGroupData->name.' was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        $userGroupData->delete();
    }

    public function mergeLinkCompaniesData(Request $request) {
        echo "<pre>";
        $this->validate($request, [
            'company_id' => 'required',
            'link_company_id' => 'required',
        ]);

        $companyId = $request->company_id;
        $linkCompanyId = $request->link_company_id;

        $companyType = Company::where('id', $companyId['id'])->first(['company_name', 'company_type']);
        $companyOwner = CompanyContactDetails::where('company_id', $companyId['id'])->first(['contact_person_name', 'contact_person_mobile', 'contact_person_email']);

        $linkedCompany = [];

        foreach($linkCompanyId as $key => $data) {
            $linkedCompany[$key] = $data['id'];
        }

        if ($companyType->company_type == 1 && $companyType->company_type == 2) {

        } elseif ($companyType->company_type == 3) {
            foreach($linkedCompany as $cmpid) {
                if($cmpid != '') {
                    
                }
            }
        }

        dd("Hello");
    }

    public function insertLinkCompaniesData(Request $request) {
        $this->validate($request, [
            'company_id' => 'required',
            'link_companies_id' => 'required',
        ]);

        $userGroup = new linkCompanies;
        $userGroup->company_id = $request->company_id['id'];
        $userGroup->link_companies_id = $request->link_companies_id['id'];
        $userGroup->save();

        $company = Company::where('id',$request->company_id['id'])->first();
        $company->is_linked = 1;
        $company->save();
        

        $linkCompany = Company::where('id',$request->link_companies_id['id'])->first();
        $linkCompany->is_linked = 1;
        $linkCompany->save();
        
        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Link Companies / Add';
        $logs->log_subject = 'Link Companies - "'.$request->company_id['company_name'].'" was linked by '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
        
        $linkComapniesLogs = new linkCompaniesLog;
        $linkComapniesLogs->company_id = $request->company_id['id'];
        $linkComapniesLogs->subject = '"'.$request->company_id['company_name'].'" was linked with "'.$request->link_companies_id['company_name'].'".';
        $linkComapniesLogs->save();
    }

    public function updateLinkCompaniesData(Request $request) {
        $this->validate($request, [ 
            'name' => 'required',
            'access_permission' => 'required',
            'modify_permission' => 'required',
        ]);
        
        $id = $request->id;        
        
        $userGroup = linkCompanies::where('id', $id)->first();
        $userGroup->name = $request->name;
        $userGroup->access_permission = json_encode($request->access_permission);
        $userGroup->modify_permission = json_encode($request->modify_permission);
        $userGroup->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Link Companies / Update';
        $logs->log_subject = 'Link Companies - "'.$userGroup->name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
