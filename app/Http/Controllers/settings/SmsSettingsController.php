<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use App\Models\Logs;
use App\Models\FinancialYear;
use App\Models\Settings\SmsSettings;
use App\Models\Settings\Designation;
use Illuminate\Support\Facades\Session;

class SmsSettingsController extends Controller
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

        $employees['scope'] = 'edit';
        $employees['editedId'] = $user->employee_id;
                        
        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / SmsSettings / View';
        $logs->log_subject = 'Sms Settings view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('settings.smsSettings.createSmsSetting',compact('financialYear'))->with('employees', $employees);
    }

    public function editSmsSettings($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('settings.smsSettings.editSmsSetting',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchSmsSettings($id) {        
        $smsSettingsData = SmsSettings::where('employee_id', $id)->first();
        $smsSettings = [];

        if(!empty($smsSettingsData->enquiry_general)) {
            $egeneral = explode(',', $smsSettingsData->enquiry_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['enquiry_general'] = $des;
        } else {
            $smsSettings['enquiry_general'] = [];
        }

        if(!empty($smsSettingsData->enquiry_supplier)) {
            $esupplier = explode(',', $smsSettingsData->enquiry_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['enquiry_supplier'] = $des;
        } else {
            $smsSettings['enquiry_supplier'] = [];
        }

        if(!empty($smsSettingsData->enquiry_footer_message)) {
            $smsSettings['enquiry_footer_message'] = $smsSettingsData->enquiry_footer_message;
        } else {
            $smsSettings['enquiry_footer_message'] = '';
        }

        if(!empty($smsSettingsData->enquiry_followup_general)) {
            $egeneral = explode(',', $smsSettingsData->enquiry_followup_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['enquiry_followup_general'] = $des;
        } else {
            $smsSettings['enquiry_followup_general'] = [];
        }

        if(!empty($smsSettingsData->enquiry_followup_supplier)) {
            $esupplier = explode(',', $smsSettingsData->enquiry_followup_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['enquiry_followup_supplier'] = $des;
        } else {
            $smsSettings['enquiry_followup_supplier'] = [];
        }

        if(!empty($smsSettingsData->order_general)) {
            $egeneral = explode(',', $smsSettingsData->order_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['order_general'] = $des;
        } else {
            $smsSettings['order_general'] = [];
        }

        if(!empty($smsSettingsData->order_supplier)) {
            $esupplier = explode(',', $smsSettingsData->order_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['order_supplier'] = $des;
        } else {
            $smsSettings['order_supplier'] = [];
        }

        if(!empty($smsSettingsData->order_footer_message)) {
            $smsSettings['order_footer_message'] = $smsSettingsData->order_footer_message;
        } else {
            $smsSettings['order_footer_message'] = '';
        }

        if(!empty($smsSettingsData->order_followup_general)) {
            $egeneral = explode(',', $smsSettingsData->order_followup_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['order_followup_general'] = $des;
        } else {
            $smsSettings['order_followup_general'] = [];
        }

        if(!empty($smsSettingsData->order_followup_supplier)) {
            $esupplier = explode(',', $smsSettingsData->order_followup_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['order_followup_supplier'] = $des;
        } else {
            $smsSettings['order_followup_supplier'] = [];
        }

        if(!empty($smsSettingsData->complain_general)) {
            $egeneral = explode(',', $smsSettingsData->complain_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['complain_general'] = $des;
        } else {
            $smsSettings['complain_general'] = [];
        }

        if(!empty($smsSettingsData->complain_supplier)) {
            $esupplier = explode(',', $smsSettingsData->complain_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['complain_supplier'] = $des;
        } else {
            $smsSettings['complain_supplier'] = [];
        }

        if(!empty($smsSettingsData->complain_footer_message)) {
            $smsSettings['complain_footer_message'] = $smsSettingsData->complain_footer_message;
        } else {
            $smsSettings['complain_footer_message'] = '';
        }

        if(!empty($smsSettingsData->complain_followup_general)) {
            $egeneral = explode(',', $smsSettingsData->complain_followup_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['complain_followup_general'] = $des;
        } else {
            $smsSettings['complain_followup_general'] = [];
        }

        if(!empty($smsSettingsData->complain_followup_supplier)) {
            $esupplier = explode(',', $smsSettingsData->complain_followup_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['complain_followup_supplier'] = $des;
        } else {
            $smsSettings['complain_followup_supplier'] = [];
        }

        if(!empty($smsSettingsData->general_general)) {
            $egeneral = explode(',', $smsSettingsData->general_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['general_general'] = $des;
        } else {
            $smsSettings['general_general'] = [];
        }

        if(!empty($smsSettingsData->general_supplier)) {
            $esupplier = explode(',', $smsSettingsData->general_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['general_supplier'] = $des;
        } else {
            $smsSettings['general_supplier'] = [];
        }

        if(!empty($smsSettingsData->general_footer_message)) {
            $smsSettings['general_footer_message'] = $smsSettingsData->general_footer_message;
        } else {
            $smsSettings['general_footer_message'] = '';
        }

        if(!empty($smsSettingsData->general_followup_general)) {
            $egeneral = explode(',', $smsSettingsData->general_followup_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['general_followup_general'] = $des;
        } else {
            $smsSettings['general_followup_general'] = [];
        }

        if(!empty($smsSettingsData->general_followup_supplier)) {
            $esupplier = explode(',', $smsSettingsData->general_followup_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['general_followup_supplier'] = $des;
        } else {
            $smsSettings['general_followup_supplier'] = [];
        }

        if(!empty($smsSettingsData->salebill_inward_general)) {
            $egeneral = explode(',', $smsSettingsData->salebill_inward_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['salebill_inward_general'] = $des;
        } else {
            $smsSettings['salebill_inward_general'] = [];
        }

        if(!empty($smsSettingsData->salebill_inward_supplier)) {
            $esupplier = explode(',', $smsSettingsData->salebill_inward_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['salebill_inward_supplier'] = $des;
        } else {
            $smsSettings['salebill_inward_supplier'] = [];
        }

        if(!empty($smsSettingsData->salebill_inward_footer_message)) {
            $smsSettings['salebill_inward_footer_message'] = $smsSettingsData->salebill_inward_footer_message;
        } else {
            $smsSettings['salebill_inward_footer_message'] = '';
        }

        if(!empty($smsSettingsData->salebill_outward_followup_general)) {
            $egeneral = explode(',', $smsSettingsData->salebill_outward_followup_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['salebill_outward_followup_general'] = $des;
        } else {
            $smsSettings['salebill_outward_followup_general'] = [];
        }

        if(!empty($smsSettingsData->salebill_outward_followup_supplier)) {
            $esupplier = explode(',', $smsSettingsData->salebill_outward_followup_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['salebill_outward_followup_supplier'] = $des;
        } else {
            $smsSettings['salebill_outward_followup_supplier'] = [];
        }

        if(!empty($smsSettingsData->salebill_outward_followup_footer_message)) {
            $smsSettings['salebill_outward_followup_footer_message'] = $smsSettingsData->salebill_outward_followup_footer_message;
        } else {
            $smsSettings['salebill_outward_followup_footer_message'] = '';
        }

        if(!empty($smsSettingsData->salebill_followup_general)) {
            $egeneral = explode(',', $smsSettingsData->salebill_followup_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['salebill_followup_general'] = $des;
        } else {
            $smsSettings['salebill_followup_general'] = [];
        }

        if(!empty($smsSettingsData->salebill_followup_supplier)) {
            $esupplier = explode(',', $smsSettingsData->salebill_followup_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['salebill_followup_supplier'] = $des;
        } else {
            $smsSettings['salebill_followup_supplier'] = [];
        }

        if(!empty($smsSettingsData->payment_general)) {
            $egeneral = explode(',', $smsSettingsData->payment_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['payment_general'] = $des;
        } else {
            $smsSettings['payment_general'] = [];
        }

        if(!empty($smsSettingsData->payment_supplier)) {
            $esupplier = explode(',', $smsSettingsData->payment_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['payment_supplier'] = $des;
        } else {
            $smsSettings['payment_supplier'] = [];
        }

        if(!empty($smsSettingsData->payment_footer_message)) {
            $smsSettings['payment_footer_message'] = $smsSettingsData->payment_footer_message;
        } else {
            $smsSettings['payment_footer_message'] = '';
        }

        if(!empty($smsSettingsData->payment_outward_followup_general)) {
            $egeneral = explode(',', $smsSettingsData->payment_outward_followup_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['payment_outward_followup_general'] = $des;
        } else {
            $smsSettings['payment_outward_followup_general'] = [];
        }

        if(!empty($smsSettingsData->payment_outward_followup_supplier)) {
            $esupplier = explode(',', $smsSettingsData->payment_outward_followup_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['payment_outward_followup_supplier'] = $des;
        } else {
            $smsSettings['payment_outward_followup_supplier'] = [];
        }

        if(!empty($smsSettingsData->payment_outward_footer_message)) {
            $smsSettings['payment_outward_footer_message'] = $smsSettingsData->payment_outward_footer_message;
        } else {
            $smsSettings['payment_outward_footer_message'] = '';
        }

        if(!empty($smsSettingsData->payment_followup_general)) {
            $egeneral = explode(',', $smsSettingsData->payment_followup_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['payment_followup_general'] = $des;
        } else {
            $smsSettings['payment_followup_general'] = [];
        }

        if(!empty($smsSettingsData->payment_followup_supplier)) {
            $esupplier = explode(',', $smsSettingsData->payment_followup_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['payment_followup_supplier'] = $des;
        } else {
            $smsSettings['payment_followup_supplier'] = [];
        }

        if(!empty($smsSettingsData->commission_general)) {
            $egeneral = explode(',', $smsSettingsData->commission_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['commission_general'] = $des;
        } else {
            $smsSettings['commission_general'] = [];
        }

        if(!empty($smsSettingsData->commission_supplier)) {
            $esupplier = explode(',', $smsSettingsData->commission_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['commission_supplier'] = $des;
        } else {
            $smsSettings['commission_supplier'] = [];
        }

        if(!empty($smsSettingsData->commission_footer_message)) {
            $smsSettings['commission_footer_message'] = $smsSettingsData->commission_footer_message;
        } else {
            $smsSettings['commission_footer_message'] = '';
        }

        if(!empty($smsSettingsData->commission_followup_general)) {
            $egeneral = explode(',', $smsSettingsData->commission_followup_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['commission_followup_general'] = $des;
        } else {
            $smsSettings['commission_followup_general'] = [];
        }

        if(!empty($smsSettingsData->commission_followup_supplier)) {
            $esupplier = explode(',', $smsSettingsData->commission_followup_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['commission_followup_supplier'] = $des;
        } else {
            $smsSettings['commission_followup_supplier'] = [];
        }

        if(!empty($smsSettingsData->automated_payment_general)) {
            $egeneral = explode(',', $smsSettingsData->automated_payment_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['automated_payment_general'] = $des;
        } else {
            $smsSettings['automated_payment_general'] = [];
        }

        if(!empty($smsSettingsData->automated_payment_supplier)) {
            $esupplier = explode(',', $smsSettingsData->automated_payment_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['automated_payment_supplier'] = $des;
        } else {
            $smsSettings['automated_payment_supplier'] = [];
        }

        if(!empty($smsSettingsData->automated_payment_footer_message)) {
            $smsSettings['automated_payment_footer_message'] = $smsSettingsData->automated_payment_footer_message;
        } else {
            $smsSettings['automated_payment_footer_message'] = '';
        }

        if(!empty($smsSettingsData->automated_commission_followup_general)) {
            $egeneral = explode(',', $smsSettingsData->automated_commission_followup_general);
            foreach($egeneral as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['automated_commission_followup_general'] = $des;
        } else {
            $smsSettings['automated_commission_followup_general'] = [];
        }

        if(!empty($smsSettingsData->automated_commission_followup_supplier)) {
            $esupplier = explode(',', $smsSettingsData->automated_commission_followup_supplier);
            foreach($esupplier as $key => $eg) {
                $designation = Designation::where('id', $eg)->first(['id', 'name']);
                $des[$key]['id'] = $designation->id;
                $des[$key]['name'] = $designation->name;
            }
            $smsSettings['automated_commission_followup_supplier'] = $des;
        } else {
            $smsSettings['automated_commission_followup_supplier'] = [];
        }

        if(!empty($smsSettingsData->automated_commission_followup_footer_message)) {
            $smsSettings['automated_commission_followup_footer_message'] = $smsSettingsData->automated_commission_followup_footer_message;
        } else {
            $smsSettings['automated_commission_followup_footer_message'] = '';
        }

        return $smsSettings;
    }

    public function listDesignation() {        
        $smsSettingsData = Designation::get(['id', 'name']);

        return $smsSettingsData;
    }

    public function updateSmsSettingsData(Request $request) {
        $egeneral = [];
        $esupplier = [];
        $efgeneral = [];
        $efsupplier = [];
        $ogeneral = [];
        $osupplier = [];
        $ofgeneral = [];
        $ofsupplier = [];
        $cgeneral = [];
        $csupplier = [];
        $cfgeneral = [];
        $cfsupplier = [];
        $ggeneral = [];
        $gsupplier = [];
        $gfgeneral = [];
        $gfsupplier = [];
        $sigeneral = [];
        $sisupplier = [];
        $sofgeneral = [];
        $sofsupplier = [];
        $sfgeneral = [];
        $sfsupplier = [];
        $pgeneral = [];
        $psupplier = [];
        $pofgeneral = [];
        $pofsupplier = [];
        $pfgeneral = [];
        $pfsupplier = [];
        $cgeneral = [];
        $csupplier = [];
        $cfgeneral = [];
        $cfsupplier = [];
        $apgeneral = [];
        $apsupplier = [];
        $acfgeneral = [];
        $acfsupplier = [];

        if($request->enquiry_general) {
            foreach ($request->enquiry_general as $key => $enquiryGeneral) {
                $egeneral[$key] = $enquiryGeneral['id'];
            }
        }

        if($request->enquiry_supplier) {
            foreach ($request->enquiry_supplier as $key => $enquirySupplier) {
                $esupplier[$key] = $enquirySupplier['id'];
            }
        }
        
        if($request->enquiry_followup_general) {
            foreach ($request->enquiry_followup_general as $key => $efg) {
                $efgeneral[$key] = $efg['id'];
            }
        }

        if($request->enquiry_followup_supplier) {
            foreach ($request->enquiry_followup_supplier as $key => $efs) {
                $efsupplier[$key] = $efs['id'];
            }
        }

        if($request->order_general) {
            foreach ($request->order_general as $key => $og) {
                $ogeneral[$key] = $og['id'];
            }
        }

        if($request->order_supplier) {
            foreach ($request->order_supplier as $key => $os) {
                $osupplier[$key] = $os['id'];
            }
        }
        
        if($request->order_followup_general) {
            foreach ($request->order_followup_general as $key => $ofg) {
                $ofgeneral[$key] = $ofg['id'];
            }
        }

        if($request->order_followup_supplier) {
            foreach ($request->order_followup_supplier as $key => $ofs) {
                $ofsupplier[$key] = $ofs['id'];
            }
        }

        if($request->complain_general) {
            foreach ($request->complain_general as $key => $cg) {
                $cgeneral[$key] = $cg['id'];
            }
        }

        if($request->complain_supplier) {
            foreach ($request->complain_supplier as $key => $cs) {
                $csupplier[$key] = $cs['id'];
            }
        }
        
        if($request->complain_followup_general) {
            foreach ($request->complain_followup_general as $key => $cfg) {
                $cfgeneral[$key] = $cfg['id'];
            }
        }

        if($request->complain_followup_supplier) {
            foreach ($request->complain_followup_supplier as $key => $cfs) {
                $cfsupplier[$key] = $cfs['id'];
            }
        }

        if($request->general_general) {
            foreach ($request->general_general as $key => $gg) {
                $ggeneral[$key] = $gg['id'];
            }
        }

        if($request->general_supplier) {
            foreach ($request->general_supplier as $key => $gs) {
                $gsupplier[$key] = $gs['id'];
            }
        }
        
        if($request->general_followup_general) {
            foreach ($request->general_followup_general as $key => $gfg) {
                $gfgeneral[$key] = $gfg['id'];
            }
        }

        if($request->general_followup_supplier) {
            foreach ($request->general_followup_supplier as $key => $gfs) {
                $gfsupplier[$key] = $gfs['id'];
            }
        }

        if($request->salebill_inward_general) {
            foreach ($request->salebill_inward_general as $key => $sig) {
                $sigeneral[$key] = $sig['id'];
            }
        }

        if($request->salebill_inward_supplier) {
            foreach ($request->salebill_inward_supplier as $key => $sis) {
                $sisupplier[$key] = $sis['id'];
            }
        }
        
        if($request->salebill_outward_followup_general) {
            foreach ($request->salebill_outward_followup_general as $key => $sofg) {
                $sofgeneral[$key] = $sofg['id'];
            }
        }

        if($request->salebill_outward_followup_supplier) {
            foreach ($request->salebill_outward_followup_supplier as $key => $sofs) {
                $sofsupplier[$key] = $sofs['id'];
            }
        }

        if($request->salebill_followup_general) {
            foreach ($request->salebill_followup_general as $key => $sfg) {
                $sfgeneral[$key] = $sfg['id'];
            }
        }

        if($request->salebill_followup_supplier) {
            foreach ($request->salebill_followup_supplier as $key => $sfs) {
                $sfsupplier[$key] = $sfs['id'];
            }
        }
        
        if($request->payment_general) {
            foreach ($request->payment_general as $key => $pg) {
                $pgeneral[$key] = $pg['id'];
            }
        }

        if($request->payment_supplier) {
            foreach ($request->payment_supplier as $key => $ps) {
                $psupplier[$key] = $ps['id'];
            }
        }

        if($request->payment_outward_followup_general) {
            foreach ($request->payment_outward_followup_general as $key => $pofg) {
                $pofgeneral[$key] = $pofg['id'];
            }
        }

        if($request->payment_outward_followup_supplier) {
            foreach ($request->payment_outward_followup_supplier as $key => $pofs) {
                $pofsupplier[$key] = $pofs['id'];
            }
        }
        
        if($request->payment_followup_general) {
            foreach ($request->payment_followup_general as $key => $pfg) {
                $pfgeneral[$key] = $pfg['id'];
            }
        }

        if($request->payment_followup_supplier) {
            foreach ($request->payment_followup_supplier as $key => $pfs) {
                $pfsupplier[$key] = $pfs['id'];
            }
        }

        if($request->commission_general) {
            foreach ($request->commission_general as $key => $cg) {
                $cgeneral[$key] = $cg['id'];
            }
        }

        if($request->commission_supplier) {
            foreach ($request->commission_supplier as $key => $cs) {
                $csupplier[$key] = $cs['id'];
            }
        }
        
        if($request->commission_followup_general) {
            foreach ($request->commission_followup_general as $key => $cfg) {
                $cfgeneral[$key] = $cfg['id'];
            }
        }

        if($request->commission_followup_supplier) {
            foreach ($request->commission_followup_supplier as $key => $cfs) {
                $cfsupplier[$key] = $cfs['id'];
            }
        }

        if($request->automated_payment_general) {
            foreach ($request->automated_payment_general as $key => $apg) {
                $apgeneral[$key] = $apg['id'];
            }
        }

        if($request->automated_payment_supplier) {
            foreach ($request->automated_payment_supplier as $key => $aps) {
                $apsupplier[$key] = $aps['id'];
            }
        }
        
        if($request->automated_commission_followup_general) {
            foreach ($request->automated_commission_followup_general as $key => $acfg) {
                $acfgeneral[$key] = $acfg['id'];
            }
        }

        if($request->automated_commission_followup_supplier) {
            foreach ($request->automated_commission_followup_supplier as $key => $acfs) {
                $acfsupplier[$key] = $acfs['id'];
            }
        }

        $id = Session::get('user')->employee_id;

        $smsSettings = SmsSettings::where('employee_id', $id)->first();
        if ($smsSettings) {
            $smsSettings->enquiry_general = implode(',', $egeneral);
            $smsSettings->enquiry_supplier = implode(',', $esupplier);
            $smsSettings->enquiry_footer_message = !empty($request->enquiry_footer_message) ? $request->enquiry_footer_message : '';
            $smsSettings->enquiry_followup_general = implode(',', $efgeneral);
            $smsSettings->enquiry_followup_supplier = implode(',', $efsupplier);
            $smsSettings->order_general = implode(',', $ogeneral);
            $smsSettings->order_supplier = implode(',', $osupplier);
            $smsSettings->order_footer_message = !empty($request->order_footer_message) ? $request->order_footer_message : '';
            $smsSettings->order_followup_general = implode(',', $ofgeneral);
            $smsSettings->order_followup_supplier = implode(',', $ofsupplier);
            $smsSettings->complain_general = implode(',', $cgeneral);
            $smsSettings->complain_supplier = implode(',', $csupplier);
            $smsSettings->complain_footer_message = !empty($request->complain_footer_message) ? $request->complain_footer_message : '';
            $smsSettings->complain_followup_general = implode(',', $cfgeneral);
            $smsSettings->complain_followup_supplier = implode(',', $cfsupplier);
            $smsSettings->general_general = implode(',', $ggeneral);
            $smsSettings->general_supplier = implode(',', $gsupplier);
            $smsSettings->general_footer_message = !empty($request->general_footer_message) ? $request->general_footer_message : '';
            $smsSettings->general_followup_general = implode(',', $gfgeneral);
            $smsSettings->general_followup_supplier = implode(',', $gfsupplier);
            $smsSettings->salebill_inward_general = implode(',', $sigeneral);
            $smsSettings->salebill_inward_supplier = implode(',', $sisupplier);
            $smsSettings->salebill_inward_footer_message = !empty($request->salebill_inward_footer_message) ? $request->salebill_inward_footer_message : '';
            $smsSettings->salebill_outward_followup_general = implode(',', $sofgeneral);
            $smsSettings->salebill_outward_followup_supplier = implode(',', $sofsupplier);
            $smsSettings->salebill_outward_followup_footer_message = !empty($request->salebill_outward_followup_footer_message) ? $request->salebill_outward_followup_footer_message : '';
            $smsSettings->salebill_followup_general = implode(',', $sfgeneral);
            $smsSettings->salebill_followup_supplier = implode(',', $sfsupplier);
            $smsSettings->payment_general = implode(',', $pgeneral);
            $smsSettings->payment_supplier = implode(',', $psupplier);
            $smsSettings->payment_footer_message = !empty($request->payment_footer_message) ? $request->payment_footer_message : '';
            $smsSettings->payment_outward_followup_general = implode(',', $pofgeneral);
            $smsSettings->payment_outward_followup_supplier = implode(',', $pofsupplier);
            $smsSettings->payment_outward_footer_message = !empty($request->payment_outward_footer_message) ? $request->payment_outward_footer_message : '';
            $smsSettings->payment_followup_general = implode(',', $pfgeneral);
            $smsSettings->payment_followup_supplier = implode(',', $pfsupplier);
            $smsSettings->commission_general = implode(',', $cgeneral);
            $smsSettings->commission_supplier = implode(',', $csupplier);
            $smsSettings->commission_footer_message = !empty($request->commission_footer_message) ? $request->commission_footer_message : '';
            $smsSettings->commission_followup_general = implode(',', $cfgeneral);
            $smsSettings->commission_followup_supplier = implode(',', $cfsupplier);
            $smsSettings->automated_payment_general = implode(',', $apgeneral);
            $smsSettings->automated_payment_supplier = implode(',', $apsupplier);
            $smsSettings->automated_payment_footer_message = !empty($request->automated_payment_footer_message) ? $request->automated_payment_footer_message : '';
            $smsSettings->automated_commission_followup_general = implode(',', $acfgeneral);
            $smsSettings->automated_commission_followup_supplier = implode(',', $acfsupplier);
            $smsSettings->automated_commission_followup_footer_message = !empty($request->automated_commission_followup_footer_message) ? $request->automated_commission_followup_footer_message : '';
            $smsSettings->save();

        } else {
            $smsSettings = new SmsSettings;
            $smsSettings->employee_id = $id;
            $smsSettings->enquiry_general = implode(',', $egeneral);
            $smsSettings->enquiry_supplier = implode(',', $esupplier);
            $smsSettings->enquiry_footer_message = !empty($request->enquiry_footer_message) ? $request->enquiry_footer_message : '';
            $smsSettings->enquiry_followup_general = implode(',', $efgeneral);
            $smsSettings->enquiry_followup_supplier = implode(',', $efsupplier);
            $smsSettings->order_general = implode(',', $ogeneral);
            $smsSettings->order_supplier = implode(',', $osupplier);
            $smsSettings->order_footer_message = !empty($request->order_footer_message) ? $request->order_footer_message : '';
            $smsSettings->order_followup_general = implode(',', $ofgeneral);
            $smsSettings->order_followup_supplier = implode(',', $ofsupplier);
            $smsSettings->complain_general = implode(',', $cgeneral);
            $smsSettings->complain_supplier = implode(',', $csupplier);
            $smsSettings->complain_footer_message = !empty($request->complain_footer_message) ? $request->complain_footer_message : '';
            $smsSettings->complain_followup_general = implode(',', $cfgeneral);
            $smsSettings->complain_followup_supplier = implode(',', $cfsupplier);
            $smsSettings->general_general = implode(',', $ggeneral);
            $smsSettings->general_supplier = implode(',', $gsupplier);
            $smsSettings->general_footer_message = !empty($request->general_footer_message) ? $request->general_footer_message : '';
            $smsSettings->general_followup_general = implode(',', $gfgeneral);
            $smsSettings->general_followup_supplier = implode(',', $gfsupplier);
            $smsSettings->salebill_inward_general = implode(',', $sigeneral);
            $smsSettings->salebill_inward_supplier = implode(',', $sisupplier);
            $smsSettings->salebill_inward_footer_message = !empty($request->salebill_inward_footer_message) ? $request->salebill_inward_footer_message : '';
            $smsSettings->salebill_outward_followup_general = implode(',', $sofgeneral);
            $smsSettings->salebill_outward_followup_supplier = implode(',', $sofsupplier);
            $smsSettings->salebill_outward_followup_footer_message = !empty($request->salebill_outward_followup_footer_message) ? $request->salebill_outward_followup_footer_message : '';
            $smsSettings->salebill_followup_general = implode(',', $sfgeneral);
            $smsSettings->salebill_followup_supplier = implode(',', $sfsupplier);
            $smsSettings->payment_general = implode(',', $pgeneral);
            $smsSettings->payment_supplier = implode(',', $psupplier);
            $smsSettings->payment_footer_message = !empty($request->payment_footer_message) ? $request->payment_footer_message : '';
            $smsSettings->payment_outward_followup_general = implode(',', $pofgeneral);
            $smsSettings->payment_outward_followup_supplier = implode(',', $pofsupplier);
            $smsSettings->payment_outward_footer_message = !empty($request->payment_outward_footer_message) ? $request->payment_outward_footer_message : '';
            $smsSettings->payment_followup_general = implode(',', $pfgeneral);
            $smsSettings->payment_followup_supplier = implode(',', $pfsupplier);
            $smsSettings->commission_general = implode(',', $cgeneral);
            $smsSettings->commission_supplier = implode(',', $csupplier);
            $smsSettings->commission_footer_message = !empty($request->commission_footer_message) ? $request->commission_footer_message : '';
            $smsSettings->commission_followup_general = implode(',', $cfgeneral);
            $smsSettings->commission_followup_supplier = implode(',', $cfsupplier);
            $smsSettings->automated_payment_general = implode(',', $apgeneral);
            $smsSettings->automated_payment_supplier = implode(',', $apsupplier);
            $smsSettings->automated_payment_footer_message = !empty($request->automated_payment_footer_message) ? $request->automated_payment_footer_message : '';
            $smsSettings->automated_commission_followup_general = implode(',', $acfgeneral);
            $smsSettings->automated_commission_followup_supplier = implode(',', $acfsupplier);
            $smsSettings->automated_commission_followup_footer_message = !empty($request->automated_commission_followup_footer_message) ? $request->automated_commission_followup_footer_message : '';
            $smsSettings->save();
        }

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Settings / SmsSettings / Edit';
        $logs->log_subject = 'SmsSettings - "'.$request->name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
