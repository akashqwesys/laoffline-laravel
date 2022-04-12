<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\CompanyCategory;
use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\Company\Company;
use App\Models\Company\CompanyAddress;
use App\Models\Company\CompanyAddressOwner;
use App\Models\Company\CompanyBankDetails;
use App\Models\Company\CompanyContactDetails;
use App\Models\Company\CompanyEmails;
use App\Models\Company\CompanyPackagingDetails;
use App\Models\Company\CompanyReferences;
use App\Models\Company\CompanySwotDetails;
use App\Models\Settings\Designation;
use App\Models\CompanyType;
use App\Models\Settings\Country;
use App\Models\Settings\State;
use App\Models\Settings\Cities;
use App\Models\Settings\TransportDetails;
use App\Models\Settings\TypeOfAddress;
use Illuminate\Support\Facades\Session;
use Carbon;
use DB;

class SaleBillController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $page_title = 'Sale Bill';
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
        $logs->log_path = 'Account / Sale Bill';
        $logs->log_subject = 'Sale Bill view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('account.sale_bill.sale_bills',compact('financialYear', 'page_title', 'employees'));
    }

    public function createSaleBill()
    {
        $page_title = 'Create Sale Bill';
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        return view('account.sale_bill.createSaleBill', compact('financialYear', 'page_title', 'employees'));
    }

    public function AddSaleBill(Request $request)
    {
        $company_name = json_decode($request->company_data)->company_name;
        if (empty(trim($company_name))) {
            return response()->json(['errors' => 'Company name is required'], 422);
        }
        /* $this->validate($request, [
            'company_name' => 'required',
        ]); */

        $companyData = json_decode($request->company_data);
        $contactDetails = json_decode($request->contact_details);
        $multipleAddresses = json_decode($request->multiple_addresses);
        $multipleEmails = json_decode($request->multiple_emails);
        $swotDetails = json_decode($request->swot_details);
        $referencesDetails = json_decode($request->references_details);
        $packagingDetails = json_decode($request->packaging_details);
        $bankDetails = json_decode($request->bank_details);
        $contactDetailsProfilePic = $request->contact_details_profile_pic;
        $multipleAddressProfilePic = $request->multiple_address_profile_pic;

        if (is_array($multipleAddresses) && !empty($multipleAddresses)) {
            foreach ($multipleAddresses as $multipleAddress) {
                if (is_array($multipleAddress->multipleAddressesOwners) && !empty($multipleAddress->multipleAddressesOwners)) {
                    foreach ($multipleAddress->multipleAddressesOwners as $owner) {
                        $multipleAddressOwnerDesignation = [];
                        if (!empty($owner->designation)) {
                            foreach ($owner->designation as $key => $des) {
                                $multipleAddressOwnerDesignation[$key] = $des->id;
                            }
                            $owner->designation = json_encode($multipleAddressOwnerDesignation);
                        } else {
                            $owner->designation = 0;
                        }
                    }
                }
            }
        }

        if (!file_exists(public_path('upload/company'))) {
            mkdir(public_path('upload/company'), 0777, true);
        }

        if (!file_exists(public_path('upload/company/profilePic'))) {
            mkdir(public_path('upload/company/profilePic'), 0777, true);
        }

        if (!file_exists(public_path('upload/company/multipleAddressProfilePic'))) {
            mkdir(public_path('upload/company/multipleAddressProfilePic'), 0777, true);
        }

        if (is_array($contactDetailsProfilePic) && !empty($contactDetailsProfilePic)) {
            $length = count($contactDetailsProfilePic);
            for ($i = 0; $i < $length; $i++) {
                if ($image = $contactDetailsProfilePic[$i]) {
                    if (!is_string($image)) {
                        $profileImage = date('YmdHis') . "_" . $i . "." . $image->getClientOriginalExtension();
                        $contactDetails[$i]->contact_person_profile_pic = $profileImage;
                        $image->move(public_path('upload/company/profilePic'), $profileImage);
                    } else {
                        $contactDetails[$i]->contact_person_profile_pic = '';
                    }
                }
            }
        }

        if (is_array($multipleAddressProfilePic) && !empty($multipleAddressProfilePic)) {
            $length = count($multipleAddressProfilePic);
            for ($i = 0; $i < $length; $i++) {
                $ownerimage = $multipleAddressProfilePic[$i];
                $ownerLength = count($ownerimage['ownerImage']);
                for ($j = 0; $j < $ownerLength; $j++) {
                    if ($image = $ownerimage['ownerImage'][$j]) {
                        if (!is_string($image)) {
                            $profileImage = date('YmdHis') . "_" . $i . "." . $image->getClientOriginalExtension();
                            $multipleAddresses[$i]->multipleAddressesOwners[$j]->profile_pic = $profileImage;
                            $image->move(public_path('upload/company/multipleAddressProfilePic'), $profileImage);
                        } else {
                            $multipleAddresses[$i]->multipleAddressesOwners[$j]->profile_pic = '';
                        }
                    }
                }
            }
        }

        if (!empty($companyData->company_landline)) {
            $landline = explode(',', trim($companyData->company_landline));
            $companyData->company_landline = json_encode($landline);
        }

        if (!empty($companyData->company_mobile)) {
            $mobile = explode(',', trim($companyData->company_mobile));
            $companyData->company_mobile = json_encode($mobile);
        }

        $comapnyLastId = Company::orderBy('id', 'DESC')->first('id');
        $companyId = !empty($comapnyLastId) ? $comapnyLastId->id + 1 : 1;

        $company_category = count($companyData->company_category) > 0 ? collect($companyData->company_category)->pluck('id')->all() : [];

        $company = new Company;
        $company->id = $companyId;
        $company->company_name = $companyData->company_name;
        $company->company_type = !empty($companyData->company_type) ? $companyData->company_type->id : 0;
        $company->company_country = !empty($companyData->company_country) ? $companyData->company_country->id : 0;
        $company->company_state = !empty($companyData->company_state) ? $companyData->company_state->id : 0;
        $company->company_city = !empty($companyData->company_city) ? $companyData->company_city->id : 0;
        $company->company_website = $companyData->company_website;
        $company->company_landline = $companyData->company_landline ?? '[]';
        $company->company_mobile = $companyData->company_mobile ?? '[]';
        $company->company_watchout = $companyData->company_watchout;
        $company->company_remark_watchout = $companyData->company_remark_watchout;
        $company->company_about = $companyData->company_about;
        $company->company_category = json_encode($company_category);
        $company->company_transport = !empty($companyData->company_transport) ? $companyData->company_transport->id : 0;
        $company->company_discount = $companyData->company_discount;
        $company->company_payment_terms_in_days = $companyData->company_payment_terms_in_days;
        $company->company_opening_balance = $companyData->company_opening_balance;
        $company->favorite_flag = 0;
        $company->is_verified = 0;
        $company->verified_by = 0;
        $company->generated_by = Session::get('user')->employee_id;
        $company->updated_by = 0;
        $company->is_linked = 0;
        $company->is_active = 0;
        $company->verified_date = NULL;
        $company->save();

        // Contact Details Data
        if (is_array($contactDetails) && !empty($contactDetails)) {
            foreach ($contactDetails as $contactDetail) {
                $companyContactLastId = CompanyContactDetails::orderBy('id', 'DESC')->first('id');
                $companyContactId = !empty($companyContactLastId) ? $companyContactLastId->id + 1 : 1;

                $companyContactDetails = new CompanyContactDetails;
                $companyContactDetails->id = $companyContactId;
                $companyContactDetails->company_id = $companyId;
                $companyContactDetails->contact_person_name = $contactDetail->contact_person_name;
                $companyContactDetails->contact_person_designation = !empty($contactDetail->contact_person_designation) ? $contactDetail->contact_person_designation->id : 0;
                $companyContactDetails->contact_person_profile_pic = $contactDetail->contact_person_profile_pic;
                $companyContactDetails->contact_person_mobile = $contactDetail->contact_person_mobile;
                $companyContactDetails->contact_person_email = $contactDetail->contact_person_email;
                $companyContactDetails->save();
            }
        }

        // Multiple Address Data
        if (is_array($multipleAddresses) && !empty($multipleAddresses)) {
            foreach ($multipleAddresses as $multipleAddress) {
                $companyAddressLastId = CompanyAddress::orderBy('id', 'DESC')->first('id');
                $companyAddressId = !empty($companyAddressLastId) ? $companyAddressLastId->id + 1 : 1;

                $companyAddress = new CompanyAddress;
                $companyAddress->id = $companyAddressId;
                $companyAddress->company_id = $companyId;
                $companyAddress->address_type = !empty($multipleAddress->address_type) ? $multipleAddress->address_type->id : 0;
                $companyAddress->address = $multipleAddress->address;
                $companyAddress->country_code = $multipleAddress->country_code;
                $companyAddress->mobile = $multipleAddress->mobile;
                $companyAddress->save();

                if (is_array($multipleAddress->multipleAddressesOwners) && !empty($multipleAddress->multipleAddressesOwners)) {
                    foreach ($multipleAddress->multipleAddressesOwners as $owner) {
                        $companyAddressOwnerLastId = CompanyAddressOwner::orderBy('id', 'DESC')->first('id');
                        $companyAddressOwnerId = !empty($companyAddressOwnerLastId) ? $companyAddressOwnerLastId->id + 1 : 1;

                        $companyAddressOwner = new CompanyAddressOwner;
                        $companyAddressOwner->id = $companyAddressOwnerId;
                        $companyAddressOwner->company_address_id = !empty($companyAddress) ? $companyAddress->id : 0;
                        $companyAddressOwner->name = $owner->name;
                        $companyAddressOwner->designation = $owner->designation;
                        $companyAddressOwner->profile_pic = $owner->profile_pic;
                        $companyAddressOwner->mobile = $owner->mobile;
                        $companyAddressOwner->email = $owner->email;
                        $companyAddressOwner->save();
                    }
                }
            }
        }

        // Multiple Emails Data
        if (is_array($multipleEmails) && !empty($multipleEmails)) {
            foreach ($multipleEmails as $multipleEmail) {
                $companyEmailsLastId = CompanyEmails::orderBy('id', 'DESC')->first('id');
                $companyEmailsId = !empty($companyEmailsLastId) ? $companyEmailsLastId->id + 1 : 1;

                $companyEmail = new CompanyEmails;
                $companyEmail->id = $companyEmailsId;
                $companyEmail->company_id = $companyId;
                $companyEmail->email_id = $multipleEmail->email_id;
                $companyEmail->save();
            }
        }

        // SWOT Data
        if (!empty($swotDetails)) {
            $swotDetailsLastId = CompanySwotDetails::orderBy('id', 'DESC')->first('id');
            $swotDetailsId = !empty($swotDetailsLastId) ? $swotDetailsLastId->id + 1 : 1;

            $swotData = new CompanySwotDetails;
            $swotData->id = $swotDetailsId;
            $swotData->company_id = $companyId;
            $swotData->strength = $swotDetails->strength;
            $swotData->weakness = $swotDetails->weakness;
            $swotData->opportunity = $swotDetails->opportunity;
            $swotData->threat = $swotDetails->threat;
            $swotData->save();
        }

        // Bank Data
        if (!empty($bankDetails)) {
            $bankDetailsLastId = CompanyBankDetails::orderBy('id', 'DESC')->first('id');
            $bankDetailsId = !empty($bankDetailsLastId) ? $bankDetailsLastId->id + 1 : 1;

            $bankDetail = new CompanyBankDetails;
            $bankDetail->id = $bankDetailsId;
            $bankDetail->company_id = $companyId;
            $bankDetail->bank_name = $bankDetails->bank_name;
            $bankDetail->account_holder_name = $bankDetails->account_holder_name;
            $bankDetail->account_no = $bankDetails->account_no;
            $bankDetail->branch_name = $bankDetails->branch_name;
            $bankDetail->ifsc_code = $bankDetails->ifsc_code;
            $bankDetail->save();
        }

        // Packaging Data
        if (!empty($packagingDetails)) {
            $packagingDetailsLastId = CompanyPackagingDetails::orderBy('id', 'DESC')->first('id');
            $packagingDetailsId = !empty($packagingDetailsLastId) ? $packagingDetailsLastId->id + 1 : 1;

            $package = new CompanyPackagingDetails;
            $package->id = $packagingDetailsId;
            $package->company_id = $companyId;
            $package->gst_no = $packagingDetails->gst_no;
            $package->cst_no = $packagingDetails->cst_no;
            $package->tin_no = $packagingDetails->tin_no;
            $package->vat_no = $packagingDetails->vat_no;
            $package->save();
        }

        // Reference Data
        if (!empty($referencesDetails)) {
            $companyReferencesLastId = CompanyReferences::orderBy('id', 'DESC')->first('id');
            $companyReferencesId = !empty($companyReferencesLastId) ? $companyReferencesLastId->id + 1 : 1;

            $reference = new CompanyReferences;
            $reference->id = $companyReferencesId;
            $reference->company_id = $companyId;
            $reference->ref_person_name = $referencesDetails->ref_person_name;
            $reference->ref_person_mobile = $referencesDetails->ref_person_mobile;
            $reference->ref_person_company = $referencesDetails->ref_person_company;
            $reference->ref_person_address = $referencesDetails->ref_person_address;
            $reference->save();
        }

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Company / Add';
        $logs->log_subject = 'Company - "' . $company->company_name . '" was inserted from ' . Session::get('user')->username . '.';
        $logs->log_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function listProductMainCategory($id)
    {
        $categories = DB::table('product_categories')
            ->select('id', 'name')
            ->where('product_default_category_id', $id)
            ->get();

        return response()->json($categories);
    }

    public function listProductSubCategory($product_id, $supplier_id)
    {
        $company_details = $this->getCompanyDetailsForLinkCompanies($supplier_id);
        $link_companies = $this->getLinkCompaniesDetails($supplier_id);
        if (empty($link_companies)) {
            $is_linked = $this->isCompanyLinkedWithOtherMainCompany($supplier_id);
            if (!empty($is_linked)) {
                $company_details = $this->getCompanyDetailsForLinkCompanies($is_linked->company_id);
                $link_companies = $this->getLinkCompaniesDetails($is_linked->company_id);
            }
        }
        $main_cmp_id = $company_details->id;
        array_push($link_companies, $main_cmp_id);
        $subCategory = $this->getProductSubCategoriesForUpdate($link_companies, $product_id);
        if (count($subCategory) > 0) {
            return response()->json($subCategory);
        } else {
            return null;
        }
    }

    public function getCompanyDetailsForLinkCompanies($id)
    {
        return DB::table('companies')->select('id')->where('id', $id)->limit(1)->first();
    }

    public function getLinkCompaniesDetails($id)
    {
        return DB::table('link_companies')->select('link_companies_id')->where('company_id', $id)->pluck('link_companies_id')->toArray();
    }

    public function isCompanyLinkedWithOtherMainCompany($id)
    {
        return DB::table('link_companies')->select('company_id')->where('link_companies_id', $id)->limit(1)->first();
    }

    public function getProductSubCategoriesForUpdate($link_companies, $id)
    {
        $where = null;
        foreach ($link_companies as $v) {
            $where .= "company_id @> '\"" . strval($v) . "\"' or ";
        }
        return DB::table('product_categories')
            ->select('id', 'product_default_category_id', 'name', 'main_category_id')
            ->where('main_category_id', $id)
            ->whereRaw('(' . rtrim($where, 'or ') . ')')
            ->orderBy('product_default_category_id', 'desc')
            ->get();
    }

    public function getCompanyFromInward($id = 0)
    {
        $companies = DB::table('companies as c')
            ->join('inwards as i', 'c.id', '=', 'i.company_id')
            ->select('c.id', 'c.company_name as name')
            ->where('inward_id', $id)
            ->get();

        return response()->json($companies);
    }

    public function getCustomersAndSuppliers()
    {
        $customers = DB::table('companies')
            ->select('id', 'company_name as name')
            ->where('company_type', 2)
            ->get();
        $suppliers = DB::table('companies')
            ->select('id', 'company_name as name')
            ->where('company_type', 3)
            ->get();

        return response()->json([$customers, $suppliers]);
    }

    public function getCustomerAddress($id)
    {
        $address = DB::table('company_addresses')
            ->select('id', 'address as name')
            ->where('company_id', $id)
            ->get();

        return response()->json($address);
    }

    public function checkSupplierInvoiceNo(Request $request)
    {
        if (empty($request->supplier_id) || empty($request->invoice_no)) {
            return;
        }
        if ($request->type == "insert") {
            $sameData = DB::table('sale_bills')
                ->select('sale_bill_id')
                ->where('is_deleted', 0)
                ->where('supplier_id', $request->supplier_id)
                ->where('supplier_invoice_no', $request->invoice_no)
                ->where('financial_year_id', Session::get('user')->financial_year_id)
                ->limit(1)
                ->first();
        } else {
            $sameData = DB::table('sale_bills')
                ->select('sale_bill_id')
                ->where('is_deleted', 0)
                ->where('supplier_id', $request->supplier_id)
                ->where('supplier_invoice_no', $request->invoice_no)
                ->where('financial_year_id', Session::get('user')->financial_year_id)
                ->where('sale_bill_id', '<>', $request->sale_bill_id)
                ->limit(1)
                ->first();
        }

        if ($sameData) {
            $link = "/account/sale-bill/view-sale-bill/" . $sameData->sale_bill_id;
            echo '<br><div class="text-danger">Supplier Invoice Number already exist!!! &nbsp;&nbsp;<a href="' . $link . '"> Visit Link</a></div>';
        } else {
            echo '<div class="text-success">SUCCESS</div>';
        }
    }

    public function getReferenceForSaleBill(Request $request)
    {
        $general_ref = DB::table('references as r')
            ->join('companies as c', 'r.company_id', '=', 'c.id')
            ->select('r.employee_id', 'r.reference_id', 'r.created_at')
            ->where('c.company_type', 3)
            ->where('r.financial_year_id', Session::get('user')->financial_year_id)
            ->where('r.type_of_inward', $request->ref_via)
            ->where('r.inward_or_outward', 1)
            ->whereRaw("(r.employee_id = " . Session::get('user')->employee_id . " or r.employee_id = 15)")
            ->orderBy('r.reference_id', 'desc')
            ->limit(4)
            ->get();
        $html = '';
        if (count($general_ref)) {
            $html .= '<div class="form-group row"><label class="col-sm-2 control-label"></label><div class="col-sm-8"><div class="table-responsive"><table class="table"><thead><tr><td></td><td>Ref. No</td><td>Generated By</td><td>Date</td><td>Time</td></tr></thead><tbody>';
            foreach ($general_ref as $row_general_ref) {
                if (Session::get('user')->employee_id == $row_general_ref->employee_id) {
                    $empName = "Me";
                } else {
                    $empName = "Rec.";
                }
                $html .= '<tr><td><div class="custom-control custom-radio"><input class="custom-control-input" type="radio" name="reference_id_sale_bill" value="' . $row_general_ref->reference_id . '"></div></td><td>' . $row_general_ref->reference_id . '</td><td>' . $empName . '</td><td>' . date('d-m-Y', strtotime($row_general_ref->created_at)) . '</td><td>' . date('H:i A', strtotime($row_general_ref->created_at)) . '</td></tr>';
            }
            $html .= '<tr><td colspan="5"><div class="input-group"><input type="text" class="form-control" name="sale_bill_ref_search" id="sale_bill_ref_search" placeholder="Enter Reference Number"><span class="input-group-btn"><button type="button" class="btn btn-default" id="sale_bill_ref_search_btn">Go</button></span></div></td></tr><tr id="sale_bill_ref_msg"></tr>';
            $html .= '</tbody></table></div></div><label class="col-sm-2 control-label"></label></div>';
        }
        return $html;
    }
}
