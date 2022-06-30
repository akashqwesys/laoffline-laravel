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
use DB;

class LinkCompaniesController extends Controller
{
    use HasRoles;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $page_title = 'Link Companies';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');

        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)->first();

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

        return view('databank.linkCompanies.linkCompany',compact('financialYear', 'page_title'))->with('employees', $employees);
    }

    public function createLinkCompanies() {
        $page_title = 'Add Link Companies';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                               join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('databank.linkCompanies.createLinkCompany',compact('financialYear', 'page_title'))->with('employees', $employees);
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

    public function listLinkCompanies_dt(Request $request)
    {
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
        $totalRecords = linkCompanies::select('count(*) as allcount')->count();
        if (!empty(trim($searchValue))) {
            $totalRecordswithFilter = linkCompanies::join('companies as c', 'link_companies.company_id', '=', 'c.id')
                ->join('companies as c1', 'link_companies.link_companies_id', '=', 'c1.id')
                ->select('count(*) as allcount')
                ->where('c.company_name', 'ILIKE', '%' . $searchValue . '%')
                ->orWhere('c1.company_name', 'ILIKE', '%' . $searchValue . '%')
                ->count();
        } else {
            $totalRecordswithFilter = $totalRecords;
        }

        // Fetch records
        $records = linkCompanies::join('companies as c', 'link_companies.company_id', '=', 'c.id')
            ->join('companies as c1', 'link_companies.link_companies_id', '=', 'c1.id')
            ->select('link_companies.company_id', 'link_companies.link_companies_id', 'c.company_name', 'c1.company_name as link_company_name', 'link_companies.id');
        if (!empty(trim($searchValue))) {
            $records = $records->where('c.company_name', 'ILIKE', '%' . $searchValue . '%')
                ->orWhere('c1.company_name', 'ILIKE', '%' . $searchValue . '%');
        }
        $records = $records->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage == 'all' ? $totalRecords : $rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {

            $action = '<button type="button" class="btn btn-primary showModal" data-toggle="modal" data-target="#mergeCompany'.$record->id.'" title="Merge company" data-id="'.$record->id.'"  data-company="'.$record->company_id.'" >Merge</button>';

            $data_arr[] = array(
                "id" => $record->id,
                "company_id" => '<a href="./companies/view-company/'.$record->company_id.'">' . $record->company_name . '</a>',
                "link_companies_id" => '<a href="./companies/view-company/' . $record->link_companies_id . '">' . $record->link_company_name . '</a>',
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

    public function listCompanies() {
        $linkCompanies = Company::where('is_delete', 0)->get(['id', 'company_name']);

        return $linkCompanies;
    }

    public function getCompanyById($id) {
        $companyDetails = Company::where('id', $id)->first(['id', 'company_name']);

        return $companyDetails;
    }

    public function getLinkedCompanyById($id) {
        $linkedCompany = linkCompanies::where('company_id', $id)->get();
        $linkedCompanyDetails = [];

        foreach($linkedCompany as $key => $company) {
            $company = Company::where('id', $company->link_companies_id)->first(['id','company_name']);
            $linkedCompanyDetails[$key]['linkedCompanies'] = $company;
        }

        return $linkedCompanyDetails;
    }

    public function editLinkCompanies($id) {
        $page_title = 'Update Link Companies';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                               join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('databank.linkCompanies.editLinkCompany',compact('financialYear', 'page_title'))->with('employees', $employees);
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
        $this->validate($request, [
            'company_id' => 'required',
            'link_company_id' => 'required',
        ]);

        $companyId = $request->company_id;
        $linkCompanyId = $request->link_company_id;

        $companyType = Company::where('id', $companyId['id'])->first(['company_name', 'company_type']);
        $companyOwner = CompanyContactDetails::where('company_id', $companyId['id'])->first(['contact_person_name', 'contact_person_mobile', 'contact_person_email']);
        $from_name = "";
        if ($companyOwner) {
            $from_name = $companyOwner->name;
        }

        $linkedCompany = [];

        foreach($linkCompanyId as $key => $data) {
            $linkedCompany[$key] = $data['id'];
        }

        if ($companyType->company_type == 1 && $companyType->company_type == 2) {
            foreach ($linkedCompany as $row_cmp) {
                if ($row_cmp != '') {
                    $moduleId = DB::table('comboids')
                        ->select('company_id', 'comboid', 'system_module_id', 'subject', 'inward_or_outward_flag', 'inward_or_outward_id', 'supplier_id', 'payment_id', 'commission_id')
                        ->where('company_id', $row_cmp)
                        ->orderBy('comboid', 'desc')
                        ->get();

                    foreach ($moduleId as $row_module) {
                        if ($row_module->system_module_id == 1 || $row_module->system_module_id == 2 || $row_module->system_module_id == 3 || $row_module->system_module_id == 4 || $row_module->system_module_id == 8 || $row_module->system_module_id == 9 || $row_module->system_module_id == 10 || $row_module->system_module_id == 11) {

                            $searchCmp = "/by(.*)or/";
                            $replaceCmp = "by " . $companyType->name . " or";
                            $newSubjectByCompany = preg_replace($searchCmp, $replaceCmp, $row_module->subject);
                            $searchName = "/ or(.*)/";
                            $replaceName = " or " . $from_name . ".";
                            $newSubject = preg_replace($searchName, $replaceName, $newSubjectByCompany);

                            if ($row_module->inward_or_outward_flag == 1) {
                                $inwardDetails = DB::table('inwards')
                                    ->select('inward_ref_via')
                                    ->where('inward_id', $row_module->inward_or_outward_id)
                                    ->first();
                                if ($inwardDetails && ($inwardDetails->inward_ref_via == 2 || $inwardDetails->inward_ref_via == 1)) {
                                    if ($row_module->main_or_followup == 0) {
                                        $finalSubject = $newSubject;
                                        $this->updateSubjectInComboIds('company_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 0, $finalSubject);
                                    } elseif ($row_module->main_or_followup == 1) {
                                        $finalSubject = $newSubject;
                                        $this->updateSubjectInComboIds('company_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 1, $finalSubject);
                                    }
                                    $this->updateTables('inwards', 'company_id', $row_cmp, 'subject', $finalSubject);
                                }
                            } else {
                                $outwardDetails = DB::table('outwards')
                                    ->select('outward_ref_via')
                                    ->where('outward_id', $row_module->inward_or_outward_id)
                                    ->first();
                                if ($outwardDetails && ($outwardDetails->outward_ref_via == 2 || $outwardDetails->outward_ref_via == 1)) {
                                    if ($row_module->main_or_followup == 0) {
                                        $finalSubject = $newSubject;
                                        $this->updateSubjectInComboIds('company_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 0, $finalSubject);
                                    } elseif ($row_module->main_or_followup == 1) {
                                        $finalSubject = $newSubject;
                                        $this->updateSubjectInComboIds('company_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 1, $finalSubject);
                                    }
                                    $this->updateTables('outwards', 'company_id', $row_cmp, 'subject', $finalSubject);
                                }
                            }
                        } elseif ($row_module->system_module_id == 5 || $row_module->system_module_id == 13) {
                            $searchCmp = "/For(.*)Of/";
                            $replaceCmp = "For " . $companyType->name . " Of";
                            $newSubjectByCompany = preg_replace($searchCmp, $replaceCmp, $row_module->subject);

                            if ($row_module->system_module_id == 5) {
                                if ($row_module->main_or_followup == 0) {
                                    $finalSubject = $newSubjectByCompany;
                                    $this->updateSubjectInComboIds('company_id', $row_cmp, 'sale_bill_id', $row_module->sale_bill_id, 0, $finalSubject);
                                } elseif ($row_module->main_or_followup == 1) {
                                    $finalSubject = $newSubjectByCompany;
                                    $this->updateSubjectInComboIds('company_id', $row_cmp, 'sale_bill_id', $row_module->sale_bill_id, 1, $finalSubject);
                                }
                            } elseif ($row_module->system_module_id == 13) {
                                if ($row_module->main_or_followup == 0) {
                                    $finalSubject = $newSubjectByCompany;
                                    $this->updateSubjectInComboIds('company_id', $row_cmp, 'goods_return_followup_id', $row_module->goods_return_followup_id, 0, $finalSubject);
                                } elseif ($row_module->main_or_followup == 1) {
                                    $finalSubject = $newSubjectByCompany;
                                    $this->updateSubjectInComboIds('company_id', $row_cmp, 'goods_return_followup_id', $row_module->goods_return_followup_id, 1, $finalSubject);
                                }
                            }
                        } elseif ($row_module->system_module_id == 15) {
                            $searchCmp = "/For(.*)/";
                            $replaceCmp = "For " . $companyType->name;
                            $newSubjectByCompany = preg_replace($searchCmp, $replaceCmp, $row_module->subject);
                            if ($row_module->main_or_followup == 0) {
                                $finalSubject = $newSubjectByCompany;
                                $this->updateSubjectInComboIds('company_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 0, $finalSubject);
                            } elseif ($row_module->main_or_followup == 1) {
                                $finalSubject = $newSubjectByCompany;
                                $this->updateSubjectInComboIds('company_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 1, $finalSubject);
                            }
                            $this->updateTables('inwards', 'company_id', $row_cmp, 'subject', $finalSubject);
                        }
                    }
                    $this->updateTables('comboids', 'company_id', $row_cmp, 'company_id', $companyId['id']);
                    $this->updateTables('inwards', 'company_id', $row_cmp, 'company_id', $companyId['id']);
                    $this->updateTables('sale_bills', 'company_id', $row_cmp, 'company_id', $companyId['id']);
                    $this->updateTables('outwards', 'company_id', $row_cmp, 'company_id', $companyId['id']);
                    $this->updateTables('products', 'company', $row_cmp, 'company', $companyId['id']);
                    $this->updateTables('reference_ids', 'company_id', $row_cmp, 'company_id', $companyId['id']);
                    $this->updateTables('payments', 'receipt_from', $row_cmp, 'receipt_from', $companyId['id']);
                }
            }
        } elseif ($companyType->company_type == 3) {
            foreach ($linkedCompany as $row_cmp) {
                if ($row_cmp != '') {
                    $moduleId = DB::table('comboids')
                        ->select('company_id', 'comboid', 'system_module_id', 'subject', 'inward_or_outward_flag', 'inward_or_outward_id', 'main_or_followup', 'goods_return_followup_id', 'supplier_id', 'payment_id', 'commission_id', 'sale_bill_id')
                        ->where('supplier_id', $row_cmp)
                        ->orderBy('comboid', 'desc')
                        ->get();

                    foreach ($moduleId as $row_module) {
                        if ($row_module->system_module_id == 1 || $row_module->system_module_id == 2 || $row_module->system_module_id == 3 || $row_module->system_module_id == 4 || $row_module->system_module_id == 8 || $row_module->system_module_id == 9 || $row_module->system_module_id == 10 || $row_module->system_module_id == 11) {

                            $searchCmp = "/by(.*)or/";
                            $replaceCmp = "by " . $companyType->name . " or";

                            $newSubjectByCompany = preg_replace($searchCmp, $replaceCmp, $row_module->subject);
                            $searchName = "/ or(.*)/";
                            $replaceName = " or " . $from_name . ".";
                            $newSubject = preg_replace($searchName, $replaceName, $newSubjectByCompany);

                            if ($row_module->system_module_id == 1 || $row_module->system_module_id == 2 || $row_module->system_module_id == 3 || $row_module->system_module_id == 4) {
                                $inwardDetails = DB::table('inwards')
                                    ->select('inward_ref_via')
                                    ->where('inward_id', $row_module->inward_or_outward_id)
                                    ->first();
                                if ($inwardDetails && $inwardDetails->inward_ref_via == 3) {
                                    if ($row_module->main_or_followup == 0) {
                                        $finalSubject = $newSubject;
                                        $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 0, $finalSubject);
                                    } elseif ($row_module->main_or_followup == 1) {
                                        $finalSubject = $newSubject;
                                        $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 1, $finalSubject);
                                    }
                                    $this->updateTables('inwards', 'supplier_id', $row_cmp, 'subject', $finalSubject);
                                }
                            } elseif ($row_module->system_module_id == 8 || $row_module->system_module_id == 9 || $row_module->system_module_id == 10 || $row_module->system_module_id == 11) {
                                $outwardDetails = DB::table('outwards')
                                    ->select('outward_ref_via')
                                    ->where('outward_id', $row_module->inward_or_outward_id)
                                    ->first();
                                if ($outwardDetails && $outwardDetails->outward_ref_via == 3) {
                                    if ($row_module->main_or_followup == 0) {
                                        $finalSubject = $newSubject;
                                        $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 0, $finalSubject);
                                    } elseif ($row_module->main_or_followup == 1) {
                                        $finalSubject = $newSubject;
                                        $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 1, $finalSubject);
                                    }
                                    $this->updateTables('outwards', 'company_id', $row_cmp, 'subject', $finalSubject);
                                }
                            }
                        } elseif ($row_module->system_module_id == 6 || $row_module->system_module_id == 7) {
                            $searchCmp = "/For(.*)Of/";
                            $replaceCmp = "For " . $companyType->name . " Of";
                            $newSubjectByCompany = preg_replace($searchCmp, $replaceCmp, $row_module->subject);

                            if ($row_module->system_module_id == 6) {
                                if ($row_module->main_or_followup == 0) {
                                    $finalSubject = $newSubjectByCompany;
                                    $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'payment_id', $row_module->payment_id, 0, $finalSubject);
                                } elseif ($row_module->main_or_followup == 1) {
                                    $finalSubject = $newSubjectByCompany;
                                    $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'payment_id', $row_module->payment_id, 1, $finalSubject);
                                }
                            } elseif ($row_module->system_module_id == 7) {
                                if ($row_module->main_or_followup == 0) {
                                    $finalSubject = $newSubjectByCompany;
                                    $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'commission_id', $row_module->commission_id, 0, $finalSubject);
                                } elseif ($row_module->main_or_followup == 1) {
                                    $finalSubject = $newSubjectByCompany;
                                    $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'commission_id', $row_module->commission_id, 1, $finalSubject);
                                }
                            }
                        } elseif ($row_module->system_module_id == 15) {
                            $searchCmp = "/For(.*)/";
                            $replaceCmp = "For " . $companyType->name;
                            $newSubjectByCompany = preg_replace($searchCmp, $replaceCmp, $row_module->subject);

                            if ($row_module->main_or_followup == 0) {
                                $finalSubject = $newSubjectByCompany;
                                $dataentry_subject = array('subject' => $finalSubject);
                                $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 0, $finalSubject);
                            } elseif ($row_module->main_or_followup == 1) {
                                $finalSubject = $newSubjectByCompany;
                                $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'inward_or_outward_id', $row_module->inward_or_outward_id, 1, $finalSubject);
                            }
                            $this->updateTables('inwards', 'supplier_id', $row_cmp, 'subject', $finalSubject);
                        } elseif ($row_module->system_module_id == 5) {
                            if ($row_module->comboid < 12207) {
                                $searchCmp = "/For(.*)Of/";
                                $replaceCmp = "For " . $companyType->name . " Of";
                                $newSubjectByCompany = preg_replace($searchCmp, $replaceCmp, $row_module->subject);

                                if ($row_module->main_or_followup == 0) {
                                    $finalSubject = $newSubjectByCompany;
                                    $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'sale_bill_id', $row_module->sale_bill_id, 0, $finalSubject);
                                } elseif ($row_module->main_or_followup == 1) {
                                    $finalSubject = $newSubjectByCompany;
                                    $this->updateSubjectInComboIds('supplier_id', $row_cmp, 'sale_bill_id', $row_module->sale_bill_id, 1, $finalSubject);
                                }
                            }
                        }
                    }
                    $this->updateTables('comboids', 'supplier_id', $row_cmp, 'supplier_id', $companyId['id']);
                    $this->updateTables('inwards', 'supplier_id', $row_cmp, 'supplier_id', $companyId['id']);
                    $this->updateTables('sale_bills', 'supplier_id', $row_cmp, 'supplier_id', $companyId['id']);
                    $this->updateTables('payments', 'supplier_id', $row_cmp, 'supplier_id', $companyId['id']);
                    $this->updateTables('commissions', 'supplier_id', $row_cmp, 'supplier_id', $companyId['id']);
                    $this->updateTables('outwards', 'supplier_id', $row_cmp, 'supplier_id', $companyId['id']);
                    $this->updateTables('products', 'company', $row_cmp, 'company', $companyId['id']);
                    $this->updateTables('reference_ids', 'company_id', $row_cmp, 'company_id', $companyId['id']);

                    //product Category
                    $prodSubCateCmp = DB::table('product_categories')
                        ->select('company_id', 'id')
                        ->where('company_id', '@>', $row_cmp)
                        ->get();
                    foreach ($prodSubCateCmp as $row_prodSubCateCmp) {
                        $newSubCateArray = array();
                        $subCateArray = json_decode($row_prodSubCateCmp->company_id);
                        foreach ($subCateArray as $row_subCate) {
                            if (in_array($row_subCate, $linkedCompany)) {
                                array_push($newSubCateArray, $companyId['id']);
                            } else {
                                array_push($newSubCateArray, $row_subCate);
                            }
                        }
                        $this->updateTables('product_categories', 'id', $row_prodSubCateCmp->id, 'company_id', json_encode($newSubCateArray));
                    }
                }
            }
        }
        $multiLinkCmpname = '';
        foreach ($linkedCompany as $row_cmp) {
            if ($row_cmp != '') {
                $companyDetails = DB::table('companies')->select('company_name', 'company_type')->where('id', $row_cmp)->first();
                $multiLinkCmpname .= $companyDetails->name . ',';
            }
        }

        $linkLastLogsId = linkCompaniesLog::select('id')->orderBy('id', 'DESC')->limit(1)->first();
        $linkComapniesLogs = new linkCompaniesLog;
        $linkComapniesLogs->id = !empty($linkLastLogsId) ? $linkLastLogsId->id + 1 : 1;
        $linkComapniesLogs->company_id = $companyId['id'];
        $linkComapniesLogs->subject = $companyType->name . ' is Link with ' . substr($multiLinkCmpname, 0, -1) . ' Companies.';
        $linkComapniesLogs->save();

        return true;
    }

    public function insertLinkCompaniesData(Request $request) {
        $this->validate($request, [
            'company_id' => 'required',
            'link_companies_id' => 'required',
        ]);

        $linkLastId = linkCompanies::select('id')->orderBy('id', 'DESC')->limit(1)->first();
        $userGroup = new linkCompanies;
        $userGroup->id = !empty($linkLastId) ? $linkLastId->id + 1 : 1;
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

        $linkLastLogsId = linkCompaniesLog::select('id')->orderBy('id', 'DESC')->limit(1)->first();
        $linkComapniesLogs = new linkCompaniesLog;
        $linkComapniesLogs->id = !empty($linkLastLogsId) ? $linkLastLogsId->id + 1 : 1;
        $linkComapniesLogs->company_id = $request->company_id['id'];
        $linkComapniesLogs->subject = '"'.$request->company_id['company_name'].'" was linked with "'.$request->link_companies_id['company_name'].'".';
        $linkComapniesLogs->save();

        return true;
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

    public function updateSubjectInComboIds($where1, $id, $where_column, $column_value, $main_or_followup, $subject)
    {
        DB::table('comboids')->where([
            $where1 => $id,
            $where_column => $column_value,
            'main_or_followup' => $main_or_followup
        ])->update(['subject' => $subject]);
    }

    public function updateTables($table, $where_column, $column_value, $update_column, $update_value)
    {
        DB::table($table)->where($where_column, $column_value)->update([$update_column => $update_value]);
    }
}
