<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use App\Models\Logs;
use App\Models\FinancialYear;
use App\Models\Settings\Cities;
use App\Models\Settings\Country;
use App\Models\Settings\State;
use Illuminate\Support\Facades\Session;

class CitiesController extends Controller
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
        $logs->log_path = 'Settings / City / View';
        $logs->log_subject = 'City view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('settings.cities.city',compact('financialYear'))->with('employees', $employees);
    }

    public function createCities() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('settings.cities.createCity',compact('financialYear'))->with('employees', $employees);
    }

    public function listCountries() {
        $countries = Country::all();

        return $countries;
    }

    public function listState() {
        $state = State::all();

        return $state;
    }

    public function listStateByCountryId($id) {
        $state = State::where('country_id', $id)->get();

        return $state;
    }

    public function listCityByStateId($id) {
        $city = Cities::where('state', $id)->get();

        return $city;
    }

    public function listData(Request $request) {
        $cities = Cities::where('is_delete', '0')->get();

        return $cities;
    }

    public function listCities(Request $request) {
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

        $totalRecords = Cities::where('id', '!=', '0')->select('count(*) as allcount')->count();
        $totalRecordswithFilter = Cities::select('count(*) as allcount')->
                                                   where('id', '!=', '0')->
                                                   where('name', 'like', '%' .$searchValue . '%')->
                                                   count();

        $Cities = Cities::orderBy('cities.'.$columnName,$columnSortOrder)->
                where('cities.name', 'like', '%' .$searchValue . '%')->
                where('cities.is_delete', '0')->
                skip($start)->
                take($rowperpage)->
                get();

        $data_arr = array();
        $sno = $start+1;

        foreach($Cities as $record){
            $id = $record->id;
            $name = $record->name;
            $action = '<a href="./cities/edit-cities/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./cities/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

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

    public function editCities($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('settings.cities.editCity',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchCities($id) {
        $citiesData = Cities::where('id', $id)->first();

        return $citiesData;
    }

    public function deleteCities($id){
        $citiesData = Cities::where('id',$id)->first();
        $citiesData->is_delete = 1;
        $citiesData->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Cities / Delete';
        $logs->log_subject = 'Cities - "'.$citiesData->name.'" was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('cities');
    }

    public function insertCitiesData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'std_code' => 'required',
            'country' => 'required',
            'state' => 'required',
        ]);

        $citiesLastId = Cities::orderBy('id', 'DESC')->first('id');
        $citiesId = !empty($citiesLastId) ? $citiesLastId->id + 1 : 1;

        $cities = new Cities;
        $cities->id = $citiesId;
        $cities->name = $request->name;
        $cities->std_code = $request->std_code;
        $cities->country = $request->country;
        $cities->state = $request->state;
        $cities->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Cities / Add';
        $logs->log_subject = 'Cities - "'.$request->name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateCitiesData(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'std_code' => 'required',
            'country' => 'required',
            'state' => 'required',
        ]);

        $id = $request->id;

        $cities = Cities::where('id', $id)->first();
        $cities->name = $request->name;
        $cities->std_code = $request->std_code;
        $cities->country = $request->country;
        $cities->state = $request->state;
        $cities->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / Cities / Edit';
        $logs->log_subject = 'Cities - "'.$request->name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
