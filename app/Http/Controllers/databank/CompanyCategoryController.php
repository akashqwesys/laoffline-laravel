<?php

namespace App\Http\Controllers\databank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use App\Models\CompanyCategory;
use App\Models\Logs;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\Session;

class CompanyCategoryController extends Controller
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
        $logs->log_path = 'CompanyCategory / View';
        $logs->log_subject = 'Company Category view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('databank.companyCategories.companyCategory',compact('financialYear'))->with('employees', $employees);
    }

    public function createCompanyCategory() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('databank.companyCategories.createCompanyCategory',compact('financialYear'))->with('employees', $employees);
    }

    public function listData(Request $request) {
        $companyCategory = CompanyCategory::where('is_delete', '0')->get();

        return $companyCategory;
    }

    public function listCompanyCategory(Request $request) {
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

        // Total records
        $totalRecords = CompanyCategory::select('count(*) as allcount')->count();
        $totalRecordswithFilter = CompanyCategory::select('count(*) as allcount')->
                                           where('category_name', 'ILIKE', '%' .$searchValue . '%')->
                                           count();

        // Fetch records
        $companyCategory = CompanyCategory::orderBy($columnName,$columnSortOrder)->
                                            where('category_name', 'ILIKE', '%' .$searchValue . '%')->
                                            where('is_delete', 0)->
                                            skip($start)->
                                            take($rowperpage)->
                                            get();

        $data_arr = array();
        $sno = $start+1;

        foreach($companyCategory as $category) {
            $id = $category->id;
            $name = $category->category_name;

            $action = '<a href="./companyCategory/edit-company-category/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./companyCategory/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

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

    public function editCompanyCategory($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('databank.companyCategories.editCompanyCategory',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchCompanyCategory($id) {        
        $companyCategoriesData = CompanyCategory::where('id', $id)->first();

        return $companyCategoriesData;
    }

    public function deleteCompanyCategory($id){
        $companyCategoryData = CompanyCategory::where('id',$id)->first();
        $companyCategoryData->is_delete = 1;
        $companyCategoryData->save();
        
        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Company Category / Delete';
        $logs->log_subject = 'Company Category - "'.$companyCategoryData->category_name.'" was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('companyCategory');
    }

    public function insertCompanyCategoryData(Request $request) {
        $this->validate($request, [
            'category_name' => 'required',
        ]);

        $comapnyCategoryLastId = CompanyCategory::orderBy('id', 'DESC')->first('id');
        $companyCategoryId = !empty($comapnyCategoryLastId) ? $comapnyCategoryLastId->id + 1 : 1;

        $companyCategory = new CompanyCategory;
        $companyCategory->id = $companyCategoryId;
        $companyCategory->category_name = $request->category_name;
        $companyCategory->sort_order = $request->sort_order;
        $companyCategory->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Company category / Add';
        $logs->log_subject = 'Company category - "'.$request->category_name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateCompanyCategoryData(Request $request) {
        $this->validate($request, [
            'category_name' => 'required',
        ]);
        
        $id = $request->id;

        $companyCategory = CompanyCategory::where('id', $id)->first();
        $companyCategory->category_name = $request->category_name;
        $companyCategory->sort_order = $request->sort_order;
        $companyCategory->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Company category / Edit';
        $logs->log_subject = 'Company category - "'.$request->category_name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
