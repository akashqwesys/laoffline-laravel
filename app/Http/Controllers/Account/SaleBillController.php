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
        return true;
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
        // array_push($subCategory, ['id' => 12, 'name' => 'Hi']);
        if (count($subCategory) > 0) {
            return response()->json($subCategory);
        } else {
            return null;
        }
    }

    public function getProductsFromSubCategory(Request $request)
    {
        $company_details = $this->getCompanyDetailsForLinkCompanies($request->supplier_id);
        $link_companies = $this->getLinkCompaniesDetails($request->supplier_id);
        if (empty($link_companies)) {
            $is_linked = $this->isCompanyLinkedWithOtherMainCompany($request->supplier_id);
            if (!empty($is_linked)) {
                $company_details = $this->getCompanyDetailsForLinkCompanies($is_linked->company_id);
                $link_companies = $this->getLinkCompaniesDetails($is_linked->company_id);
            }
        }
        $main_cmp_id = $company_details->id;

        $subCategory = explode(',', $request->subcategory);

        array_push($link_companies, $main_cmp_id);

        $productSubCategory = $this->getProductFromSubCategoriesForUpdate($subCategory, $link_companies, $request->maincategory);

        if (count($productSubCategory) > 0) {
            return response()->json($productSubCategory);
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
            ->get()
            ->toArray();
    }

    public function getProductFromSubCategoriesForUpdate($subCategory, $link_companies, $category_id)
    {
        $where = null;
        foreach ($subCategory as $v) {
            $where .= "sub_category @> '\"" . strval($v) . "\"' or ";
        }
        return DB::table('products')
            ->select('id', 'product_name as name')
            ->where('category', $category_id)
            ->whereIn('company', $link_companies)
            ->whereRaw('(' . rtrim($where, 'or ') . ')')
            ->orderBy('product_name', 'asc')
            ->get()
            ->toArray();
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
        $general_ref = DB::table('reference_ids as r')
            ->join('companies as c', 'r.company_id', '=', 'c.id')
            ->select('r.employee_id', 'r.reference_id', 'r.created_at')
            ->where('c.company_type', $request->sale_bill_via)
            ->where('r.financial_year_id', Session::get('user')->financial_year_id)
            ->where('r.type_of_inward', $request->ref_via)
            ->where('r.inward_or_outward', 1)
            ->whereRaw("(r.employee_id = " . Session::get('user')->employee_id . " or r.employee_id = 15)")
            ->orderBy('r.reference_id', 'desc')
            ->limit(4)
            ->get();
        $html = '';
        if (count($general_ref)) {
            $html .= '<div class="form-group row"><label class="col-sm-2 control-label"></label><div class="col-sm-8"><div class="table-responsive"><table class="table"><thead><tr><th></th><th>Ref. No</th><th>Generated By</th><th>Date</th><th>Time</th></tr></thead><tbody>';
            foreach ($general_ref as $row_general_ref) {
                if (Session::get('user')->employee_id == $row_general_ref->employee_id) {
                    $empName = "Me";
                } else {
                    $empName = "Rec.";
                }
                $html .= '<tr><td><div class="custom-control custom-radio"><input class="custom-control-input" type="radio" name="reference_id_sale_bill" value="' . $row_general_ref->reference_id . '" id="r-' . $row_general_ref->reference_id . '"><label class="custom-control-label" for="r-'.$row_general_ref->reference_id.'"></label></div></td><td>' . $row_general_ref->reference_id . '</td><td>' . $empName . '</td><td>' . date('Y-m-d', strtotime($row_general_ref->created_at)) . '</td><td>' . date('H:i A', strtotime($row_general_ref->created_at)) . '</td></tr>';
            }
            $html .= '<tr><td colspan="5"><div class="input-group"><input type="text" class="form-control" name="sale_bill_ref_search" id="sale_bill_ref_search" placeholder="Enter Reference Number"><span class="input-group-btn"><button type="button" class="btn btn-primary" id="sale_bill_ref_search_btn">Go</button></span></div></td></tr><tr id="sale_bill_ref_msg"></tr>';
            $html .= '</tbody></table></div></div><label class="col-sm-2 control-label"></label></div>';
        }
        return $html;
    }

    public function getOldReferenceForSaleBill(Request $request, $id)
    {
        $html = "";
        $reference = DB::table('reference_ids as r')
            ->join('companies as c', 'r.company_id', '=', 'c.id')
            ->select('r.employee_id', 'r.reference_id', 'r.created_at', 'r.company_id', 'r.selection_date', 'r.type_of_inward', 'r.from_name', 'r.from_number', 'r.receiver_number', 'r.from_email_id', 'r.receiver_email_id', 'r.latter_by_id', 'r.courier_name', 'r.weight_of_parcel', 'r.courier_receipt_no', 'r.courier_received_time', 'r.delivery_by', 'c.company_name')
            ->where('r.reference_id', $id)
            ->where('c.company_type', $request->sale_bill_via)
            ->where('r.financial_year_id', Session::get('user')->financial_year_id)
            ->where('r.inward_or_outward', 1)
            ->whereRaw("(r.type_of_inward = 'Email' OR r.type_of_inward = 'Courier' OR r.type_of_inward = 'Hand')")
            ->whereRaw("(r.employee_id = " . Session::get('user')->employee_id . " or r.employee_id = 15)")
            ->where('r.is_deleted', 0)
            ->limit(1)
            ->first();

        if ($reference) {
            if ($reference->company_id != 0) {
                if (Session::get('user')->employee_id == $reference->employee_id) {
                    $empName = "Own";
                } else {
                    $empName = "Rec.";
                }
                $html .= "<input type='hidden' id='hidden_sale_bill_date' value='" . date('Y-m-d', strtotime($reference->selection_date)) . "'><input type='hidden' id='hidden_reference_via' value='" . $reference->type_of_inward . "'><input type='hidden' id='hidden_from_name' value='" . $reference->from_name . "'><input type='hidden' id='hidden_from_number' value='" . $reference->from_number . "'><input type='hidden' id='hidden_receiver_number' value='" . $reference->receiver_number . "'><input type='hidden' id='hidden_from_email_id' value='" . $reference->from_email_id . "'><input type='hidden' id='hidden_receiver_email_id' value='" . $reference->receiver_email_id . "'><input type='hidden' id='hidden_latter_by_id' value='" . $reference->latter_by_id . "'><input type='hidden' name='hidden_courier_name' id='hidden_courier_name' value='" . $reference->courier_name . "'><input type='hidden' id='hidden_weight_of_parcel' value='" . $reference->weight_of_parcel . "'><input type='hidden' id='hidden_courier_receipt_no' value='" . $reference->courier_receipt_no . "'><input type='hidden' id='hidden_courier_received_time' value='" . date('d-m-Y', strtotime($reference->courier_received_time)) . "'><input type='hidden' id='hidden_delivery_by' value='" . $reference->delivery_by . "'><input type='hidden' name='hidden_cmp_id' id='hidden_cmp_id' value='" . $reference->company_id . "'><input type='hidden' name='hidden_cmp_name' id='hidden_cmp_name' value='" . $reference->company_name . "'><input type='hidden' id='hidden_reference_id_input' name='hidden_reference_id_input' value='" . $reference->reference_id . "'><input type='hidden' id='hidden_ref_emp_name' name='hidden_ref_emp_name' value='" . $empName . "'><input type='hidden' id='hidden_ref_date_added' name='hidden_ref_date_added' value='" . date('Y-m-d', strtotime($reference->created_at)) . "'><input type='hidden' id='hidden_ref_time_added' name='hidden_ref_time_added' value='" . date('h:i A', strtotime($reference->created_at)) . "'>";
            }
        }
        return $html;
    }

    public function getSubProductFromProduct(Request $request)
    {
        $subProducts = DB::table('products_images')
            ->select('id', 'supplier_code as name', 'price')
            ->where('product_id', $request->product_id)
            ->get()
            ->toArray();
        $productRate = DB::table('product_details')
            ->select('catalogue_price')
            ->where('product_id', $request->product_id)
            ->first();
        $subProducts[] = ['id' => 'full', 'name' => 'Full Catalogue', 'price' => $productRate->catalogue_price];
        return response()->json($subProducts);
    }

    public function listTransports(Request $request)
    {
        $transportDetails = TransportDetails::select('id', 'name')->where('is_delete', '0')->get();
        return response()->json($transportDetails);
    }

    public function getStations($id)
    {
        $cities = DB::table('cities')
            ->select('id', 'name')
            ->get();

        $city_s = DB::table('companies')
            ->select('id', 'company_city as name')
            ->where('id', $id)
            ->first();

        return response()->json([$cities, $city_s]);
    }

}
