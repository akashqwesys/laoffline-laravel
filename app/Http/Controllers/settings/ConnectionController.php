<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\UserGroup;
// use App\Models\Employee;
// use App\Models\User;
// use App\Models\Settings\Cities;
// use App\Models\Settings\Country;
use App\Models\Comboids\Comboids;
use App\Models\Commission\Commission;
use App\Models\CommissionDetail;
use App\Models\InvoicePaymentDetails;
use App\Models\Commission\CommissionInvoice;
use App\Models\ProductCategory;
use App\Models\Settings\TransportDetails;
use App\Models\Settings\TransportMultipleAddressDetails;
use App\Models\Product;
use App\Models\InwardOutward\Inward;
use App\Models\InwardOutward\Outward;
use App\Models\InwardOrderDetail;
use App\Models\InwardOrderAction;
use App\Models\InwardOrder;
use App\Models\InwardSample;
use App\Models\FabricField;
// use App\Models\EnjayCallRecordsId;
// use App\Models\CompanyType;
use App\Models\ProductDetails;
// use App\Models\FinancialYear;
use App\Models\ProductsImages;
use App\Models\InwardLinkWith;
use App\Models\InwardProductFabric;
use App\Models\ProductFabricDetails;
use App\Models\Goods\GoodsReturn;
use App\Models\Goods\GrSaleBillItem;
use App\Models\Company\Company;
use App\Models\Company\CompanyAddress;
use App\Models\Company\CompanyAddressOwner;
use App\Models\Company\CompanyBankDetails;
use App\Models\Company\CompanyContactDetails;
use App\Models\Company\CompanyEmails;
use App\Models\Company\CompanyPackagingDetails;
use App\Models\Company\CompanyReferences;
use App\Models\Company\CompanySwotDetails;
use App\Models\CompanyCommission;
use App\Models\CompanyCategory;
use App\Models\IncrementId;
use App\Models\Iuid;
use App\Models\Ouid;
use App\Models\SaleBill;
use App\Models\SaleBillTransport;
use App\Models\SaleBillItem;
// use App\Models\Settings\SaleBillAgent;
use App\Models\ProductFabricGroup;
use App\Models\Payment;
// use App\Models\ProductDefaultCategory;
use App\Models\PaymentDetail;
use App\Models\OutwardSaleBill;
use App\Models\OutwardOrder;
use App\Models\OutwardProductFabric;
use App\Models\OutwardOrderDetail;
use App\Models\OutwardFrequentMessage;
use App\Models\linkCompanies;
// use App\Models\linkCompaniesLog;
use App\Models\Reference\ReferenceId;
// use App\Models\Settings\Agent;
// use App\Models\Settings\State;
use App\Models\Settings\BankDetails;
// use App\Models\Settings\Designation;
use App\Models\InwardOutward\InwardActions;
use App\Models\Settings\TypeOfAddress;
use App\Models\Logs;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use DB;

class ConnectionController extends Controller
{
    public function __construct()
    {
        // $servername = "localhost";
        // $username = "root";
        // $password = "";
        $servername = "139.59.23.41";
        $username = "new_la";
        $password = "LA@remote2022";
        $database = "laoffline";
        // $username = "akashs_laoffline123";
        // $password = "laoffline123";
        // $database = "akashs_laoffline";

        // Create connection
        $this->conn = mysqli_connect($servername, $username, $password, $database);
        mysqli_set_charset($this->conn, 'utf8');

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function truncateDB()
    {
        DB::table('comboids')->truncate();
        DB::table('iuids')->truncate();
        DB::table('ouids')->truncate();
        DB::table('outwards')->truncate();
        DB::table('outward_frequent_messages')->truncate();
        DB::table('outward_order_details')->truncate();
        DB::table('outward_orders')->truncate();
        DB::table('outward_product_fabrics')->truncate();
        DB::table('outward_sale_bills')->truncate();
        DB::table('payments')->truncate();
        DB::table('payment_details')->truncate();
        DB::table('reference_ids')->truncate();
        DB::table('sale_bills')->truncate();
        DB::table('sale_bill_items')->truncate();
        DB::table('sale_bill_transports')->truncate();
        DB::table('logs')->truncate();
    }

    public function products()
    {
        $productCategoryData = [];
        $products = [];
        $productImages = [];
        $ProductFabricGroupList = [];
        $FabricFeildList = [];
        $pcat = "SELECT * FROM product_category";
        $pquery = mysqli_query($this->conn, $pcat);
        if (mysqli_num_rows($pquery) != 0) {
            $i = 0;
            $companyId = [];
            while ($result = mysqli_fetch_assoc($pquery)) {
                if (!empty($result['company_id'])) {
                    $companyId = unserialize($result['company_id']);
                    if ($companyId) {
                        $result['company_id'] = json_encode($companyId);
                    } else {
                        $result['company_id'] = json_encode([]);
                    }
                } else {
                    $result['company_id'] = json_encode([]);
                }

                $productCategoryData[$i]['id'] = $result['product_category_id'];
                $productCategoryData[$i]['product_default_category_id'] = $result['product_default_category_id'];
                $productCategoryData[$i]['name'] = $result['name'];
                $productCategoryData[$i]['main_category_id'] = $result['main_category_id'];
                $productCategoryData[$i]['company_id'] = $result['company_id'];
                $productCategoryData[$i]['product_fabric_id'] = $result['product_fabric_id'];
                $productCategoryData[$i]['sort_order'] = $result['sort_order'];
                $productCategoryData[$i]['multiple_company'] = $result['multiple_company'];
                $productCategoryData[$i]['rate'] = $result['rate'];
                $i++;
            }
        }

        if (!empty($productCategoryData)) {
            DB::table('product_categories')->truncate();
            foreach ($productCategoryData as $productCategory) {
                $pCategory = new ProductCategory;
                $pCategory->id = $productCategory['id'];
                $pCategory->product_default_category_id = $productCategory['product_default_category_id'];
                $pCategory->name = $productCategory['name'];
                $pCategory->main_category_id = $productCategory['main_category_id'];
                $pCategory->company_id = $productCategory['company_id'];
                $pCategory->product_fabric_id = $productCategory['product_fabric_id'];
                $pCategory->multiple_company = $productCategory['multiple_company'];
                $pCategory->rate = $productCategory['rate'];
                $pCategory->sort_order = $productCategory['sort_order'];
                $pCategory->save();
            }
        }

        $prod = "SELECT * FROM product_main";
        $pdquery = mysqli_query($this->conn, $prod);
        if (mysqli_num_rows($pdquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($pdquery)) {

                if (!empty($result['sub_category_id'])) {
                    $subCatId = unserialize($result['sub_category_id']);
                    if (!empty($result['sub_category_id'])) {
                        $subCatId = unserialize($result['sub_category_id']);
                        if ($subCatId) {
                            $result['sub_category_id'] = json_encode($subCatId);
                        } else {
                            $result['sub_category_id'] = json_encode([]);
                        }
                    } else {
                        $result['sub_category_id'] = json_encode([]);
                    }
                }

                if ($result['launch_date'] == '0000-00-00') {
                    $result['launch_date'] = NULL;
                } else {
                    $result['launch_date'] = $result['launch_date'];
                }

                $products[$i]['productData']['id'] = $result['product_main_id'];
                $products[$i]['productData']['product_name'] = $result['name'];
                $products[$i]['productData']['catalogue_name'] = $result['catalogue_name'];
                $products[$i]['productData']['brand_name'] = $result['brand_name'];
                $products[$i]['productData']['model'] = $result['model_name'];
                $products[$i]['productData']['launch_date'] = $result['launch_date'];
                $products[$i]['productData']['company'] = $result['company_id'];
                $products[$i]['productData']['category'] = $result['category_id'];
                $products[$i]['productData']['sub_category'] = $result['sub_category_id'];
                $products[$i]['productData']['main_image'] = $result['main_image'];
                $products[$i]['productData']['price_list_image'] = $result['price_list_image'];
                $products[$i]['productData']['description'] = $result['description'];
                $products[$i]['productData']['complete_flag'] = $result['complete_flag'];
                $products[$i]['productData']['generated_by'] = 1;
                $products[$i]['productData']['updated_by'] = 0;
                $products[$i]['productData']['date_added'] = $result['date_added'];

                $products[$i]['productDetails']['product_id'] = $result['product_main_id'];
                $products[$i]['productDetails']['catalogue_price'] = $result['price'];
                $products[$i]['productDetails']['average_price'] = $result['average_price'];
                $products[$i]['productDetails']['wholesale_discount'] = $result['wh_discount'];
                $products[$i]['productDetails']['wholesale_brokerage'] = $result['wh_brokerage'];
                $products[$i]['productDetails']['retail_discount'] = $result['re_discount'];
                $products[$i]['productDetails']['retail_brokerage'] = $result['re_brokerage'];
                $products[$i]['productDetails']['date_added'] = $result['date_added'];

                $products[$i]['fabrics']['product_id'] = $result['product_main_id'];
                $products[$i]['fabrics']['saree_fabric'] = $result['saree_fabric'];
                $products[$i]['fabrics']['saree_cut'] = $result['saree_cut'];
                $products[$i]['fabrics']['blouse_fabric'] = $result['blouse_fabric'];
                $products[$i]['fabrics']['blouse_cut'] = $result['blouse_cut'];
                $products[$i]['fabrics']['top_fabric'] = $result['top_fabric'];
                $products[$i]['fabrics']['top_cut'] = $result['top_cut'];
                $products[$i]['fabrics']['bottom_fabric'] = $result['bottom_fabric'];
                $products[$i]['fabrics']['bottom_cut'] = $result['bottom_cut'];
                $products[$i]['fabrics']['dupatta_fabric'] = $result['dupatta_fabric'];
                $products[$i]['fabrics']['dupatta_cut'] = $result['dupatta_cut'];
                $products[$i]['fabrics']['inner_fabric'] = $result['inner_fabric'];
                $products[$i]['fabrics']['inner_cut'] = $result['inner_cut'];
                $products[$i]['fabrics']['sleeves_fabric'] = $result['sleeves_fabric'];
                $products[$i]['fabrics']['sleeves_cut'] = $result['sleeves_cut'];
                $products[$i]['fabrics']['choli_fabric'] = $result['choli_fabric'];
                $products[$i]['fabrics']['choli_cut'] = $result['choli_cut'];
                $products[$i]['fabrics']['lehenga_fabric'] = $result['lehenga_fabric'];
                $products[$i]['fabrics']['lehenga_cut'] = $result['lehenga_cut'];
                $products[$i]['fabrics']['lining_fabric'] = $result['lining_fabric'];
                $products[$i]['fabrics']['lining_cut'] = $result['lining_cut'];
                $products[$i]['fabrics']['gown_fabric'] = $result['gown_fabric'];
                $products[$i]['fabrics']['gown_cut'] = $result['gown_cut'];
                $products[$i]['fabrics']['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($products)) {
            DB::table('products')->truncate();
            DB::table('product_details')->truncate();
            DB::table('product_fabric_details')->truncate();
            foreach ($products as $product) {
                $productsData = new Product;
                $productsData->id = $product['productData']['id'];
                $productsData->product_name = $product['productData']['product_name'];
                $productsData->catalogue_name = $product['productData']['catalogue_name'];
                $productsData->brand_name = $product['productData']['brand_name'];
                $productsData->model = $product['productData']['model'];
                $productsData->launch_date = $product['productData']['launch_date'];
                $productsData->company = $product['productData']['company'];
                $productsData->category = $product['productData']['category'];
                $productsData->sub_category = $product['productData']['sub_category'];
                $productsData->main_image = $product['productData']['main_image'];
                $productsData->price_list_image = $product['productData']['price_list_image'];
                $productsData->description = $product['productData']['description'];
                $productsData->complete_flag = $product['productData']['complete_flag'];
                $productsData->generated_by = $product['productData']['generated_by'];
                $productsData->updated_by = $product['productData']['updated_by'];
                $productsData->created_at = $product['productData']['date_added'];
                $productsData->save();

                $pdId = ProductDetails::orderBy('id', 'DESC')->first('id');
                $podId = !empty($pdId) ? $pdId->id + 1 : 1;

                $productsDetail = new ProductDetails;
                $productsDetail->id = $podId;
                $productsDetail->product_id = $product['productDetails']['product_id'];
                $productsDetail->catalogue_price = $product['productDetails']['catalogue_price'];
                $productsDetail->average_price = $product['productDetails']['average_price'];
                $productsDetail->wholesale_discount = $product['productDetails']['wholesale_discount'];
                $productsDetail->wholesale_brokerage = $product['productDetails']['wholesale_brokerage'];
                $productsDetail->retail_discount = $product['productDetails']['retail_discount'];
                $productsDetail->retail_brokerage = $product['productDetails']['retail_brokerage'];
                $productsDetail->created_at = $product['productDetails']['date_added'];
                $productsDetail->save();

                $pfdId = ProductFabricDetails::orderBy('id', 'DESC')->first('id');
                $fdId = !empty($pfdId) ? $pfdId->id + 1 : 1;

                $productFabrics = new ProductFabricDetails;
                $productFabrics->id = $fdId;
                $productFabrics->product_id = $product['fabrics']['product_id'];
                $productFabrics->saree_fabric = $product['fabrics']['saree_fabric'];
                $productFabrics->saree_cut = $product['fabrics']['saree_cut'];
                $productFabrics->blouse_fabric = $product['fabrics']['blouse_fabric'];
                $productFabrics->blouse_cut = $product['fabrics']['blouse_cut'];
                $productFabrics->top_fabric = $product['fabrics']['top_fabric'];
                $productFabrics->top_cut = $product['fabrics']['top_cut'];
                $productFabrics->bottom_fabric = $product['fabrics']['bottom_fabric'];
                $productFabrics->bottom_cut = $product['fabrics']['bottom_cut'];
                $productFabrics->dupatta_fabric = $product['fabrics']['dupatta_fabric'];
                $productFabrics->dupatta_cut = $product['fabrics']['dupatta_cut'];
                $productFabrics->inner_fabric = $product['fabrics']['inner_fabric'];
                $productFabrics->inner_cut = $product['fabrics']['inner_cut'];
                $productFabrics->sleeves_fabric = $product['fabrics']['sleeves_fabric'];
                $productFabrics->sleeves_cut = $product['fabrics']['sleeves_cut'];
                $productFabrics->choli_fabric = $product['fabrics']['choli_fabric'];
                $productFabrics->choli_cut = $product['fabrics']['choli_cut'];
                $productFabrics->lehenga_fabric = $product['fabrics']['lehenga_fabric'];
                $productFabrics->lehenga_cut = $product['fabrics']['lehenga_cut'];
                $productFabrics->lining_fabric = $product['fabrics']['lining_fabric'];
                $productFabrics->lining_cut = $product['fabrics']['lining_cut'];
                $productFabrics->gown_fabric = $product['fabrics']['gown_fabric'];
                $productFabrics->gown_cut = $product['fabrics']['gown_cut'];
                $productFabrics->created_at = $product['fabrics']['date_added'];
                $productFabrics->save();
            }
        }

        $productfabricgroup = "SELECT * FROM product_fabric_group";
        $productfabricgroupquery = mysqli_query($this->conn, $productfabricgroup);
        if (mysqli_num_rows($productfabricgroupquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($productfabricgroupquery)) {
                $ProductFabricGroupList[$i]['id'] = $result['product_fabric_group_id'];
                $ProductFabricGroupList[$i]['name'] = $result['name'];
                $i++;
            }
        }

        if (!empty($ProductFabricGroupList)) {
            DB::table('product_fabric_groups')->truncate();
            foreach ($ProductFabricGroupList as $pfg) {
                $productfabricgroupdata = new ProductFabricGroup();
                $productfabricgroupdata->id = $pfg['id'];
                $productfabricgroupdata->name = $pfg['name'];
                $productfabricgroupdata->save();
            }
        }

        $pisql = "SELECT * FROM product_image";
        $piquery = mysqli_query($this->conn, $pisql);
        if (mysqli_num_rows($piquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($piquery)) {
                $productImages[$i]['id'] = $result['product_image_id'];
                $productImages[$i]['product_id'] = $result['product_main_id'];
                $productImages[$i]['supplier_code'] = $result['name'];
                $productImages[$i]['product_code'] = !empty($result['product_code']) ? $result['product_code'] : 0;
                $productImages[$i]['image'] = $result['image_name'];
                $productImages[$i]['price'] = $result['price'];
                $productImages[$i]['sort_order'] = $result['sort_order'];
                $i++;
            }
        }

        if (!empty($productImages)) {
            DB::table('products_images')->truncate();
            foreach ($productImages as $productImage) {
                $pImages = new ProductsImages;
                $pImages->id = $productImage['id'];
                $pImages->product_id = $productImage['product_id'];
                $pImages->supplier_code = $productImage['supplier_code'];
                $pImages->product_code = $productImage['product_code'];
                $pImages->image = $productImage['image'];
                $pImages->price = $productImage['price'];
                $pImages->sort_order = $productImage['sort_order'];
                $pImages->save();
            }
        }

        $fabricfeild = "SELECT * FROM fabric_field";
        $fabricfeildquery = mysqli_query($this->conn, $fabricfeild);
        if (mysqli_num_rows($fabricfeildquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($fabricfeildquery)) {
                $FabricFeildList[$i]['id'] = $result['fabric_field_id'];
                $FabricFeildList[$i]['product_fabric_id'] = $result['product_fabric_id'];
                $FabricFeildList[$i]['name'] = $result['name'];
                $FabricFeildList[$i]['sort_order'] = $result['sort_order'];
                $i++;
            }
        }

        if (!empty($FabricFeildList)) {
            DB::table('fabric_fields')->truncate();
            foreach ($FabricFeildList as $ff) {
                $fabricfeild = new FabricField();
                $fabricfeild->id = $ff['id'];
                $fabricfeild->product_fabric_id = $ff['product_fabric_id'];
                $fabricfeild->name = $ff['name'];
                $fabricfeild->sort_order = $ff['sort_order'];
                $fabricfeild->save();
            }
        }
    }

    public function bankDetails()
    {
        $bankDetailData = [];
        $IncrementIdList = [];
        $bdsql = "SELECT * FROM bank_details";
        $bdquery = mysqli_query($this->conn, $bdsql);
        if (mysqli_num_rows($bdquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($bdquery)) {
                $bankDetailData[$i]['id'] = $result['bank_details_id'];
                $bankDetailData[$i]['name'] = $result['name'];
                $bankDetailData[$i]['sort_order'] = $result['sort_order'];
                $i++;
            }
        }

        if (!empty($bankDetailData)) {
            DB::table('bank_details')->truncate();
            foreach ($bankDetailData as $bankDetail) {
                $bankDetails = new BankDetails;
                $bankDetails->id = $bankDetail['id'];
                $bankDetails->name = $bankDetail['name'];
                $bankDetails->sort_order = $bankDetail['sort_order'];
                $bankDetails->save();
            }
        }

        $incrementids = "SELECT * FROM increment_ids";
        $incrementidsquery = mysqli_query($this->conn, $incrementids);
        if (mysqli_num_rows($incrementidsquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($incrementidsquery)) {
                $IncrementIdList[$i]['id'] = $result['id'];
                $IncrementIdList[$i]['financial_year_id'] = $result['financial_year_id'];
                $IncrementIdList[$i]['iuid'] = $result['iuid'];
                $IncrementIdList[$i]['ouid'] = $result['ouid'];
                $IncrementIdList[$i]['reference_id'] = $result['reference_id'];
                $IncrementIdList[$i]['sale_bill_id'] = $result['sale_bill_id'];
                $IncrementIdList[$i]['payment_id'] = $result['payment_id'];
                $IncrementIdList[$i]['commission_id'] = $result['commission_id'];
                $IncrementIdList[$i]['goods_return_id'] = $result['goods_return_id'];
                $i++;
            }
        }

        if (!empty($IncrementIdList)) {
            DB::table('increment_ids')->truncate();
            foreach ($IncrementIdList as $iid) {
                $incremeniddata = new IncrementId();
                $incremeniddata->id = $iid['id'];
                $incremeniddata->financial_year_id = $iid['financial_year_id'];
                $incremeniddata->iuid = $iid['iuid'];
                $incremeniddata->ouid = $iid['ouid'];
                $incremeniddata->reference_id = $iid['reference_id'];
                $incremeniddata->sale_bill_id = $iid['sale_bill_id'];
                $incremeniddata->payment_id = $iid['payment_id'];
                $incremeniddata->commission_id = $iid['commission_id'];
                $incremeniddata->goods_return_id = $iid['goods_return_id'];
                $incremeniddata->save();
            }
        }
    }

    public function comboID($limit, $offset)
    {
        $comboList = [];
        $comboid = "SELECT * FROM comboid limit $limit offset $offset";
        $comboquery = mysqli_query($this->conn, $comboid);
        if (mysqli_num_rows($comboquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($comboquery)) {
                if ($result['date_added'] == '0000-00-00 00:00:00') {
                    $result['date_added'] = NULL;
                } else {
                    $result['date_added'] = $result['date_added'];
                }
                if ($result['date_updated'] == '0000-00-00 00:00:00') {
                    $result['date_updated'] = NULL;
                } else {
                    $result['date_updated'] = $result['date_updated'];
                }
                if ($result['action_date'] == '0000-00-00') {
                    $result['action_date'] = NULL;
                } else {
                    $result['action_date'] = $result['action_date'];
                }
                if ($result['selection_date'] == '0000-00-00') {
                    $result['selection_date'] = NULL;
                } else {
                    $result['selection_date'] = $result['selection_date'];
                }
                $comboList[$i]['comboid'] = $result['comboid'];
                $comboList[$i]['iuid'] = $result['iuid'];
                $comboList[$i]['ouid'] = $result['ouid'];
                $comboList[$i]['general_ref_id'] = $result['general_ref_id'];
                $comboList[$i]['system_module_id'] = $result['system_module_id'];
                $comboList[$i]['generated_by'] = $result['generated_by'];
                $comboList[$i]['updated_by'] = $result['updated_by'];
                $comboList[$i]['inward_or_outward_flag'] = $result['inward_or_outward_flag'];
                $comboList[$i]['inward_or_outward_id'] = $result['inward_or_outward_id'];
                $comboList[$i]['follow_as_inward_or_outward'] = $result['followup_as_inward_or_outward'];
                $comboList[$i]['sale_bill_id'] = $result['sale_bill_id'];
                $comboList[$i]['payment_id'] = $result['payment_id'];
                $comboList[$i]['goods_return_id'] = $result['goods_return_id'];
                $comboList[$i]['commission_id'] = $result['commission_id'];
                $comboList[$i]['commission_invoice_id'] = $result['commission_invoice_id'];
                $comboList[$i]['is_invoice'] = $result['is_invoice'];
                $comboList[$i]['sample_id'] = $result['sample_id'];
                $comboList[$i]['company_id'] = $result['company_id'];
                $comboList[$i]['supplier_id'] = $result['supplier_id'];
                $comboList[$i]['inward_ref_via'] = $result['inward_ref_via'];
                $comboList[$i]['company_type'] = $result['company_type'];
                $comboList[$i]['inform_md'] = $result['inform_md'];
                $comboList[$i]['followup_via'] = $result['followup_via'];
                $comboList[$i]['inward_or_outward_via'] = $result['inward_or_outward_via'];
                $comboList[$i]['selection_date'] = $result['selection_date'];
                $comboList[$i]['from_name'] = $result['from_name'];
                $comboList[$i]['from_number'] = $result['from_number'];
                $comboList[$i]['receiver_number'] = $result['receiver_number'];
                $comboList[$i]['from_email_id'] = $result['from_email_id'];
                $comboList[$i]['receiver_email_id'] = $result['receiver_email_id'];
                $comboList[$i]['new_or_old_inward_or_outward'] = $result['new_or_old_inward_or_outward'];
                $comboList[$i]['subject'] = $result['subject'];
                $comboList[$i]['attachments'] = $result['attachments'];
                $comboList[$i]['outward_attachments'] = $result['outward_attachments'];
                $comboList[$i]['outward_employe_id'] = $result['outward_employe_id'];
                $comboList[$i]['default_category_id'] = $result['default_category_id'];
                $comboList[$i]['main_category_id'] = $result['main_category_id'];
                $comboList[$i]['agent_id'] = $result['agent_id'];
                $comboList[$i]['supplier_invoice_no'] = $result['supplier_invoice_no'];
                $comboList[$i]['total'] = $result['total'];
                $comboList[$i]['sale_bill_flag'] = $result['sale_bill_flag'];
                $comboList[$i]['receipt_mode'] = $result['receipt_mode'];
                $comboList[$i]['receipt_amount'] = $result['receipt_amount'];
                $comboList[$i]['tds'] = $result['tds'];
                $comboList[$i]['net_received_amount'] = $result['net_received_amount'];
                $comboList[$i]['received_commission_amount'] = $result['received_commission_amount'];
                $comboList[$i]['action_date'] = $result['action_date'];
                $comboList[$i]['action_instruction'] = $result['action_instruction'];
                $comboList[$i]['assigned_to'] = $result['assigned_to'];
                $comboList[$i]['remark'] = $result['remark'];
                $comboList[$i]['being_late'] = $result['being_late'];
                $comboList[$i]['financial_year_id'] = $result['financial_year_id'];
                $comboList[$i]['system_url'] = $result['system_url'];
                $comboList[$i]['enjay_uniqueid'] = $result['enjay_uniqueid'];
                $comboList[$i]['is_completed'] = $result['is_completed'];
                $comboList[$i]['mark_as_draft'] = $result['mark_as_draft'];
                $comboList[$i]['color_flag_id'] = $result['color_flag_id'];
                $comboList[$i]['product_qty'] = $result['product_qty'];
                $comboList[$i]['fabric_meters'] = $result['fabric_meters'];
                $comboList[$i]['sample_return_qty'] = $result['sample_return_qty'];
                $comboList[$i]['mobile_flag'] = $result['mobile_flag'];
                $comboList[$i]['is_deleted'] = $result['is_deleted'];
                $comboList[$i]['date_added'] = $result['date_added'];
                $comboList[$i]['date_updated'] = $result['date_updated'];
                $i++;
            }
        }

        if (!empty($comboList)) {
            foreach ($comboList as $combo) {
                $Comboids = new Comboids;
                $Comboids->comboid = $combo['comboid'];
                $Comboids->iuid = $combo['iuid'];
                $Comboids->ouid = $combo['ouid'];
                $Comboids->general_ref_id = $combo['general_ref_id'];
                $Comboids->follow_as_inward_or_outward = $combo['follow_as_inward_or_outward'];
                $Comboids->system_module_id = $combo['system_module_id'];
                $Comboids->generated_by = $combo['generated_by'];
                $Comboids->updated_by = $combo['updated_by'];
                $Comboids->inward_or_outward_flag = $combo['inward_or_outward_flag'];
                $Comboids->inward_or_outward_id = $combo['inward_or_outward_id'];
                $Comboids->follow_as_inward_or_outward = $combo['follow_as_inward_or_outward'];
                $Comboids->sale_bill_id = $combo['sale_bill_id'];
                $Comboids->payment_id = $combo['payment_id'];
                $Comboids->goods_return_id = $combo['goods_return_id'];
                $Comboids->commission_id = $combo['commission_id'];
                $Comboids->commission_invoice_id = $combo['commission_invoice_id'];
                $Comboids->is_invoice = $combo['is_invoice'];
                $Comboids->sample_id = $combo['sample_id'];
                $Comboids->company_id = $combo['company_id'];
                $Comboids->supplier_id = $combo['supplier_id'];
                $Comboids->inward_ref_via = $combo['inward_ref_via'];
                $Comboids->company_type = $combo['company_type'];
                $Comboids->inform_md = $combo['inform_md'];
                $Comboids->followup_via = $combo['followup_via'];
                $Comboids->inward_or_outward_via = $combo['inward_or_outward_via'];
                $Comboids->selection_date = $combo['selection_date'];
                $Comboids->from_name = $combo['from_name'];
                $Comboids->from_number = $combo['from_number'];
                $Comboids->receiver_number = $combo['receiver_number'];
                $Comboids->from_email_id = $combo['from_email_id'];
                $Comboids->receiver_email_id = $combo['receiver_email_id'];
                $Comboids->new_or_old_inward_or_outward = $combo['new_or_old_inward_or_outward'];
                $Comboids->subject = $combo['subject'];
                $Comboids->attachments = $combo['attachments'];
                $Comboids->outward_attachments = $combo['outward_attachments'];
                $Comboids->outward_employe_id = $combo['outward_employe_id'];
                $Comboids->default_category_id = $combo['default_category_id'];
                $Comboids->main_category_id = $combo['main_category_id'];
                $Comboids->agent_id = $combo['agent_id'];
                $Comboids->supplier_invoice_no = $combo['supplier_invoice_no'];
                $Comboids->total = $combo['total'];
                $Comboids->sale_bill_flag = $combo['sale_bill_flag'];
                $Comboids->receipt_mode = $combo['receipt_mode'];
                $Comboids->receipt_amount = $combo['receipt_amount'];
                $Comboids->tds = $combo['tds'];
                $Comboids->net_received_amount = $combo['net_received_amount'];
                $Comboids->received_commission_amount = $combo['received_commission_amount'];
                $Comboids->action_date = $combo['action_date'];
                $Comboids->action_instruction = $combo['action_instruction'];
                $Comboids->assigned_to = $combo['assigned_to'];
                $Comboids->remark = $combo['remark'];
                $Comboids->being_late = $combo['being_late'];
                $Comboids->financial_year_id = $combo['financial_year_id'];
                $Comboids->system_url = $combo['system_url'];
                $Comboids->enjay_uniqueid = $combo['enjay_uniqueid'];
                $Comboids->is_completed = $combo['is_completed'];
                $Comboids->mark_as_draft = $combo['mark_as_draft'];
                $Comboids->color_flag_id = $combo['color_flag_id'];
                $Comboids->product_qty = $combo['product_qty'];
                $Comboids->fabric_meters = $combo['fabric_meters'];
                $Comboids->sample_return_qty = $combo['sample_return_qty'];
                $Comboids->mobile_flag = $combo['mobile_flag'];
                $Comboids->is_deleted = $combo['is_deleted'];
                $Comboids->created_at = $combo['date_added'];
                $Comboids->updated_at = $combo['date_updated'];
                $Comboids->save();
            }
        }
    }

    public function commissionAndInvoice()
    {
        $commisionInvoiceList = [];
        $commissionList = [];
        $commissionDetail = [];
        $InvoicePayment = [];
        $commissionInvoice = "SELECT * FROM commission_invoice";
        $ciquery = mysqli_query($this->conn, $commissionInvoice);
        if(mysqli_num_rows($ciquery) != 0) {
            $i = 0;
            while($result = mysqli_fetch_assoc($ciquery)) {
                $commisionInvoiceList[$i]['id'] = $result['commission_invoice_id'];
                $commisionInvoiceList[$i]['reference_id'] = $result['reference_id'];
                $commisionInvoiceList[$i]['customer_id'] = $result['customer_id'];
                $commisionInvoiceList[$i]['supplier_id'] = $result['supplier_id'];
                $commisionInvoiceList[$i]['financial_year_id'] = $result['financial_year_id'];
                $commisionInvoiceList[$i]['generated_by'] = $result['generated_by'];
                $commisionInvoiceList[$i]['bill_no'] = $result['bill_no'];
                $commisionInvoiceList[$i]['bill_period_to'] = $result['bill_period_to'];
                $commisionInvoiceList[$i]['bill_period_from'] = $result['bill_period_from'];
                $commisionInvoiceList[$i]['bill_date'] = $result['bill_date'];
                $commisionInvoiceList[$i]['commission_amount'] = floatval($result['commission_amount']);
                $commisionInvoiceList[$i]['service_tax_amount'] = floatval($result['service_tax_amount']);
                $commisionInvoiceList[$i]['service_tax'] = floatval($result['service_tax']);
                $commisionInvoiceList[$i]['other_amount'] = floatval($result['other_amount']);
                $commisionInvoiceList[$i]['rounded_off'] = floatval($result['rounded_off']);
                $commisionInvoiceList[$i]['tds_amount'] = floatval($result['tds_amount']);
                $commisionInvoiceList[$i]['final_amount'] = floatval($result['final_amount']);
                $commisionInvoiceList[$i]['service_tax_flag'] = $result['service_tax_flag'];
                $commisionInvoiceList[$i]['tds_flag'] = $result['tds_flag'];
                $commisionInvoiceList[$i]['tax_class'] = $result['tax_class'];
                $commisionInvoiceList[$i]['with_without_gst'] = $result['with_without_gst'];
                $commisionInvoiceList[$i]['cgst'] = floatval($result['cgst']);
                $commisionInvoiceList[$i]['cgst_amount'] = floatval($result['cgst_amount']);
                $commisionInvoiceList[$i]['sgst'] = floatval($result['sgst']);
                $commisionInvoiceList[$i]['sgst_amount'] = floatval($result['sgst_amount']);
                $commisionInvoiceList[$i]['igst'] = floatval($result['igst']);
                $commisionInvoiceList[$i]['igst_amount'] = floatval($result['igst_amount']);
                $commisionInvoiceList[$i]['commission_percent'] = floatval($result['commission_percent']);
                $commisionInvoiceList[$i]['total_payment_received_amount'] = floatval($result['total_payment_rec_amount']);
                $commisionInvoiceList[$i]['agent_id'] = $result['agent_id'];
                $commisionInvoiceList[$i]['done_outward'] = $result['done_outward'];
                $commisionInvoiceList[$i]['commission_status'] = $result['commission_status'];
                $commisionInvoiceList[$i]['right_of_amount'] = $result['right_of_amount'];
                $commisionInvoiceList[$i]['is_deleted'] = $result['is_deleted'];
                $commisionInvoiceList[$i]['right_of_remark'] = $result['right_of_remark'];
                $commisionInvoiceList[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if(!empty($commisionInvoiceList)) {
            DB::table('commission_invoices')->truncate();
            foreach($commisionInvoiceList as $commissionInvoice) {
                $commissioninvoice = new CommissionInvoice();
                $commissioninvoice->id = $commissionInvoice['id'];
                $commissioninvoice->reference_id = $commissionInvoice['reference_id'];
                $commissioninvoice->customer_id = $commissionInvoice['customer_id'];
                $commissioninvoice->supplier_id = $commissionInvoice['supplier_id'];
                $commissioninvoice->financial_year_id = $commissionInvoice['financial_year_id'];
                $commissioninvoice->generated_by = $commissionInvoice['generated_by'];
                $commissioninvoice->bill_no = $commissionInvoice['bill_no'];
                $commissioninvoice->bill_period_to = $commissionInvoice['bill_period_to'];
                $commissioninvoice->bill_period_from = $commissionInvoice['bill_period_from'];
                $commissioninvoice->bill_date = $commissionInvoice['bill_date'];
                $commissioninvoice->commission_amount = $commissionInvoice['commission_amount'];
                $commissioninvoice->service_tax_amount = $commissionInvoice['service_tax_amount'];
                $commissioninvoice->service_tax = $commissionInvoice['service_tax'];
                $commissioninvoice->other_amount = $commissionInvoice['other_amount'];
                $commissioninvoice->rounded_off = $commissionInvoice['rounded_off'];
                $commissioninvoice->tds_amount = $commissionInvoice['tds_amount'];
                $commissioninvoice->final_amount = $commissionInvoice['final_amount'];
                $commissioninvoice->service_tax_flag = $commissionInvoice['service_tax_flag'];
                $commissioninvoice->tds_flag = $commissionInvoice['tds_flag'];
                $commissioninvoice->tax_class = $commissionInvoice['tax_class'];
                $commissioninvoice->with_without_gst = $commissionInvoice['with_without_gst'];
                $commissioninvoice->cgst = $commissionInvoice['cgst'];
                $commissioninvoice->cgst_amount = $commissionInvoice['cgst_amount'];
                $commissioninvoice->sgst = $commissionInvoice['sgst'];
                $commissioninvoice->sgst_amount = $commissionInvoice['sgst_amount'];
                $commissioninvoice->igst = $commissionInvoice['igst'];
                $commissioninvoice->igst_amount = $commissionInvoice['igst_amount'];
                $commissioninvoice->commission_percent = $commissionInvoice['commission_percent'];
                $commissioninvoice->total_payment_received_amount = $commissionInvoice['total_payment_received_amount'];
                $commissioninvoice->agent_id = $commissionInvoice['agent_id'];
                $commissioninvoice->done_outward = $commissionInvoice['done_outward'];
                $commissioninvoice->commission_status = $commissionInvoice['commission_status'];
                $commissioninvoice->right_of_amount = $commissionInvoice['right_of_amount'];
                $commissioninvoice->is_deleted = $commissionInvoice['is_deleted'];
                $commissioninvoice->right_of_remark = $commissionInvoice['right_of_remark'];
                $commissioninvoice->created_at = $commissionInvoice['date_added'];
                $commissioninvoice->save();
            }
        }

        $commission = "SELECT * FROM commission";
        $commissionquery = mysqli_query($this->conn, $commission);
        if(mysqli_num_rows($commissionquery) != 0) {
            $i = 0;
            while($result = mysqli_fetch_assoc($commissionquery)) {
                $commissionList[$i]['id'] = $result['id'];
                $commissionList[$i]['commission_id'] = $result['commission_id'];
                $commissionList[$i]['iuid'] = $result['iuid'];
                $commissionList[$i]['reference_id'] = $result['reference_id'];
                $commissionList[$i]['financial_year_id'] = $result['financial_year_id'];
                $commissionList[$i]['attachments'] = $result['attachments'];
                $commissionList[$i]['payment_id'] = $result['payment_id'];
                $commissionList[$i]['customer_id'] = $result['customer_id'];
                $commissionList[$i]['supplier_id'] = $result['supplier_id'];
                $commissionList[$i]['bill_no'] = $result['bill_no'];
                $commissionList[$i]['bill_date'] = $result['bill_date'];
                $commissionList[$i]['deposite_bank'] = $result['deposite_bank'];
                $commissionList[$i]['cheque_date'] = $result['cheque_date'];
                $commissionList[$i]['cheque_dd_no'] = $result['cheque_dd_no'];
                $commissionList[$i]['cheque_dd_bank'] = $result['cheque_dd_bank'];
                $commissionList[$i]['bill_amount'] = $result['bill_amount'];
                $commissionList[$i]['received_amount'] = $result['received_amount'];
                $commissionList[$i]['tds'] = $result['tds'];
                $commissionList[$i]['net_received_amount'] = $result['net_received_amount'];
                $commissionList[$i]['received_commission_amount'] = $result['received_commission_amount'];
                $commissionList[$i]['commission_date'] = $result['commission_date'];
                $commissionList[$i]['commission_account'] = $result['commission_account'];
                $commissionList[$i]['remark'] = $result['remark'];
                // $commissionList[$i]['required_followup'] = $result['required_followup'];
                $commissionList[$i]['commission_reciept_mode'] = $result['commission_reciept_mode'];
                $commissionList[$i]['commission_payment_date'] = $result['commission_payment_date'];
                $commissionList[$i]['commission_deposite_bank'] = $result['commission_deposite_bank'];
                $commissionList[$i]['commission_cheque_date'] = $result['commission_cheque_date'];
                $commissionList[$i]['commission_cheque_dd_no'] = $result['commission_cheque_dd_no'];
                $commissionList[$i]['commission_cheque_dd_bank'] = $result['commission_cheque_dd_bank'];
                $commissionList[$i]['commission_payment_amount'] = $result['commission_payment_amount'];
                $commissionList[$i]['done_outward'] = $result['done_outward'];
                $commissionList[$i]['service_tax_val'] = $result['service_tax_val'];
                $commissionList[$i]['normal_amt_flag'] = $result['normal_amt_flag'];
                $commissionList[$i]['is_invoice'] = $result['is_invoice'];
                $commissionList[$i]['date_added'] = $result['date_added'];
                $commissionList[$i]['is_deleted'] = $result['is_deleted'];
                $i++;
            }
        }

        if(!empty($commissionList)) {
            DB::table('commissions')->truncate();
            foreach($commissionList as $commissionData) {
                $commission = new Commission();
                $commission->id = $commissionData['id'];
                $commission->commission_id = $commissionData['commission_id'];
                $commission->iuid = $commissionData['iuid'];
                $commission->reference_id = $commissionData['reference_id'];
                $commission->financial_year_id = $commissionData['financial_year_id'];
                $commission->attachments = $commissionData['attachments'];
                $commission->payment_id = $commissionData['payment_id'];
                $commission->customer_id = $commissionData['customer_id'];
                $commission->supplier_id = $commissionData['supplier_id'];
                $commission->bill_no = $commissionData['bill_no'];
                $commission->bill_date = ($commissionData['bill_date'] == '0000-00-00') ? null : $commissionData['bill_date'];
                $commission->deposite_bank = $commissionData['deposite_bank'];
                $commission->cheque_date = ($commissionData['cheque_date'] == '0000-00-00') ? null : $commissionData['cheque_date'];
                $commission->cheque_dd_no = $commissionData['cheque_dd_no'];
                $commission->cheque_dd_bank = $commissionData['cheque_dd_bank'];
                $commission->bill_amount = $commissionData['bill_amount'];
                $commission->received_amount = $commissionData['received_amount'];
                $commission->tds = $commissionData['tds'];
                $commission->net_received_amount = $commissionData['net_received_amount'];
                $commission->received_commission_amount = $commissionData['received_commission_amount'];
                $commission->commission_date = ($commissionData['commission_date'] == '0000-00-00') ? null : $commissionData['commission_date'];
                $commission->commission_account = $commissionData['commission_account'];
                $commission->remark = $commissionData['remark'];
                // $commission->required_followup = $commissionData['required_followup'];
                $commission->commission_reciept_mode = $commissionData['commission_reciept_mode'];
                $commission->commission_payment_date = ($commissionData['commission_payment_date'] == '0000-00-00') ? null : $commissionData['commission_payment_date'];
                $commission->commission_deposite_bank = $commissionData['commission_deposite_bank'];
                $commission->commission_cheque_date = ($commissionData['commission_cheque_date'] == '0000-00-00') ? null : $commissionData['commission_cheque_date'];
                $commission->commission_cheque_dd_no = $commissionData['commission_cheque_dd_no'];
                $commission->commission_cheque_dd_bank = $commissionData['commission_cheque_dd_bank'];
                $commission->commission_payment_amount = $commissionData['commission_payment_amount'];
                $commission->done_outward = $commissionData['done_outward'];
                $commission->service_tax_val = $commissionData['service_tax_val'];
                $commission->normal_amt_flag = $commissionData['normal_amt_flag'];
                $commission->is_invoice = $commissionData['is_invoice'];
                $commission->date_added = $commissionData['date_added'];
                $commission->created_at = $commissionData['date_added'];
                $commission->is_deleted = $commissionData['is_deleted'];
                $commission->save();
            }
        }

        $commissionD = "SELECT * FROM commission_details";
        $commissionDquery = mysqli_query($this->conn, $commissionD);
        if(mysqli_num_rows($commissionDquery) != 0) {
            $i = 0;
            while($result = mysqli_fetch_assoc($commissionDquery)) {
                $commissionDetail[$i]['id'] = $result['commission_details_id'];
                $commissionDetail[$i]['c_increment_id'] = $result['c_increment_id'];
                $commissionDetail[$i]['commission_id'] = $result['commission_id'];
                $commissionDetail[$i]['commission_invoice_id'] = $result['commission_invoice_id'];
                $commissionDetail[$i]['financial_year_id'] = $result['financial_year_id'];
                $commissionDetail[$i]['payment_id'] = $result['payment_id'];
                $commissionDetail[$i]['bill_date'] = $result['bill_date'];
                $commissionDetail[$i]['deposite_bank'] = $result['deposite_bank'];
                $commissionDetail[$i]['cheque_date'] = $result['cheque_date'];
                $commissionDetail[$i]['cheque_dd_no'] = $result['cheque_dd_no'];
                $commissionDetail[$i]['cheque_dd_bank'] = $result['cheque_dd_bank'];
                $commissionDetail[$i]['percentage'] = $result['percentage'];
                $commissionDetail[$i]['bill_amount'] = $result['bill_amount'];
                $commissionDetail[$i]['received_amount'] = $result['received_amount'];
                $commissionDetail[$i]['service_tax'] = $result['service_tax'];
                $commissionDetail[$i]['tds'] = $result['tds'];
                $commissionDetail[$i]['net_received_amount'] = $result['net_received_amount'];
                $commissionDetail[$i]['received_commission_amount'] = $result['received_commission_amount'];
                $commissionDetail[$i]['commission_date'] = $result['commission_date'];
                $commissionDetail[$i]['commission_account'] = $result['commission_account'];
                $commissionDetail[$i]['status'] = $result['status'];
                $commissionDetail[$i]['remark'] = $result['remark'];
                $commissionDetail[$i]['is_deleted'] = $result['is_deleted'];
                $commissionDetail[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if(!empty($commissionDetail)) {
            DB::table('commission_details')->truncate();
            foreach($commissionDetail as $commissionData) {
                $commission = new CommissionDetail();
                $commission->id = $commissionData['id'];
                $commission->c_increment_id = $commissionData['c_increment_id'];
                $commission->commission_id = $commissionData['commission_id'];
                $commission->commission_invoice_id = $commissionData['commission_invoice_id'];
                $commission->financial_year_id = $commissionData['financial_year_id'];
                $commission->payment_id = $commissionData['payment_id'];
                $commission->bill_date = $commissionData['bill_date'] == '0000-00-00' ? null : $commissionData['bill_date'];
                $commission->deposite_bank = $commissionData['deposite_bank'];
                $commission->cheque_date = $commissionData['cheque_date'] == '0000-00-00' ? null : $commissionData['cheque_date'];
                $commission->cheque_dd_no = $commissionData['cheque_dd_no'];
                $commission->cheque_dd_bank = $commissionData['cheque_dd_bank'];
                $commission->percentage = $commissionData['percentage'];
                $commission->bill_amount = $commissionData['bill_amount'];
                $commission->received_amount = $commissionData['received_amount'];
                $commission->service_tax = $commissionData['service_tax'];
                $commission->tds = $commissionData['tds'];
                $commission->net_received_amount = $commissionData['net_received_amount'];
                $commission->received_commission_amount = $commissionData['received_commission_amount'];
                $commission->commission_date = $commissionData['commission_date'] == '0000-00-00' ? null : $commissionData['commission_date'];
                $commission->commission_account = $commissionData['commission_account'];
                $commission->status = $commissionData['status'];
                $commission->remark = $commissionData['remark'];
                $commission->is_deleted = $commissionData['is_deleted'];
                $commission->created_at = $commissionData['date_added'];
                $commission->save();
            }
        }

        $invP = "SELECT * FROM invoice_payment_receive_details";
        $invPquery = mysqli_query($this->conn, $invP);
        if (mysqli_num_rows($invPquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($invPquery)) {
                $InvoicePayment[$i]['id'] = $result['id'];
                $InvoicePayment[$i]['commission_invoice_id'] = $result['commission_invoice_id'];
                $InvoicePayment[$i]['financial_year_id'] = $result['financial_year_id'];
                $InvoicePayment[$i]['payment_id'] = $result['payment_id'];
                $InvoicePayment[$i]['payment_date'] = $result['payment_date'];
                $InvoicePayment[$i]['company_id'] = $result['company_id'];
                $InvoicePayment[$i]['rec_amount'] = $result['rec_amount'];
                $InvoicePayment[$i]['total_amount'] = $result['total_amount'];
                $InvoicePayment[$i]['flag'] = $result['flag'];
                $InvoicePayment[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($InvoicePayment)) {
            DB::table('invoice_payment_details')->truncate();
            foreach ($InvoicePayment as $result) {
                $inv = new InvoicePaymentDetails();
                $inv->id = $result['id'];
                $inv->commission_invoice_id = $result['commission_invoice_id'];
                $inv->financial_year_id = $result['financial_year_id'];
                $inv->payment_id = $result['payment_id'];
                $inv->payment_date = $result['payment_date'];
                $inv->company_id = $result['company_id'];
                $inv->received_amount = $result['rec_amount'];
                $inv->total_amount = $result['total_amount'];
                $inv->flag = $result['flag'];
                $inv->created_at = $result['date_added'];
                $inv->save();
            }
        }
    }

    public function company()
    {
        $companyData = [];
        $companyTypeList = [];
        $companyAddressData = [];
        $companyAddressOwnerData = [];
        $companyEmailData = [];
        $companyOwnerData = [];
        $companyCategoryData = [];
        $ComapnyLinkList = [];
        $LinkCompanyLogList = [];

        $comsql = "SELECT * FROM company";
        $comquery = mysqli_query($this->conn, $comsql);
        if (mysqli_num_rows($comquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($comquery)) {
                if (!empty($result['company_category_id'])) {
                    $subCatId = @unserialize($result['company_category_id']);
                    if ($subCatId) {
                        $result['company_category_id'] = json_encode($subCatId['company_category']);
                    } else {
                        $result['company_category_id'] = json_encode([]);
                    }
                } else {
                    $result['company_category_id'] = json_encode([]);
                }

                if (!empty($result['landline_no'])) {
                    $lno = @unserialize($result['landline_no']);
                    if ($lno) {
                        $result['landline_no'] = json_encode($lno);
                    } else {
                        $result['landline_no'] = json_encode([]);
                    }
                } else {
                    $result['landline_no'] = json_encode([]);
                }

                if (!empty($result['mobile_no'])) {
                    $lno = @unserialize($result['mobile_no']);
                    if ($lno) {
                        $result['mobile_no'] = json_encode($lno);
                    } else {
                        $result['mobile_no'] = json_encode([]);
                    }
                } else {
                    $result['mobile_no'] = json_encode([]);
                }

                if ($result['verified_date'] == '0000-00-00 00:00:00') {
                    $result['verified_date'] = NULL;
                } else {
                    $result['verified_date'] = $result['verified_date'];
                }

                if ($result['date_added'] == '0000-00-00 00:00:00') {
                    $result['date_added'] = NULL;
                } else {
                    $result['date_added'] = $result['date_added'];
                }
                if ($result['date_updated'] == '0000-00-00 00:00:00') {
                    $result['date_updated'] = NULL;
                } else {
                    $result['date_updated'] = $result['date_updated'];
                }

                $companyData[$i]['companyData']['id'] = $result['company_id'];
                $companyData[$i]['companyData']['company_name'] = $result['name'];
                $companyData[$i]['companyData']['company_type'] = $result['company_type_id'];
                if ($result['country_name'] == 'India') {
                    $companyData[$i]['companyData']['company_country'] = 105;
                } else if ($result['country_name'] == 'United Kingdom') {
                    $companyData[$i]['companyData']['company_country'] = 77;
                } else if ($result['country_name'] == 'Poland') {
                    $companyData[$i]['companyData']['company_country'] = 179;
                } else if ($result['country_name'] == 'Singapore') {
                    $companyData[$i]['companyData']['company_country'] = 198;
                } else if ($result['country_name'] == 'France') {
                    $companyData[$i]['companyData']['company_country'] = 75;
                } else if ($result['country_name'] == 'Pakistan') {
                    $companyData[$i]['companyData']['company_country'] = 178;
                } else if ($result['country_name'] == 'United Arab Emirates') {
                    $companyData[$i]['companyData']['company_country'] = 2;
                } else if ($result['country_name'] == 'Japan') {
                    $companyData[$i]['companyData']['company_country'] = 114;
                } else if ($result['country_name'] == 'Sri Lanka') {
                    $companyData[$i]['companyData']['company_country'] = 130;
                } else if ($result['country_name'] == 'Turkey') {
                    $companyData[$i]['companyData']['company_country'] = 225;
                } else {
                    $companyData[$i]['companyData']['company_country'] = 0;
                }
                $companyData[$i]['companyData']['company_state'] = !empty($result['state_id']) ? intval($result['state_id']) : 0;
                $companyData[$i]['companyData']['company_city'] = !empty($result['city_name']) ? $result['city_name'] : 0;
                $companyData[$i]['companyData']['company_website'] = $result['website'];
                $companyData[$i]['companyData']['company_landline'] = $result['landline_no'];
                $companyData[$i]['companyData']['company_mobile'] = $result['mobile_no'];
                $companyData[$i]['companyData']['company_watchout'] = $result['watchout'];
                $companyData[$i]['companyData']['company_remark_watchout'] = $result['remark_watchout'];
                $companyData[$i]['companyData']['company_about'] = $result['about_cmp'];
                $companyData[$i]['companyData']['company_category'] = $result['company_category_id'];
                $companyData[$i]['companyData']['company_transport'] = $result['transport_id'];
                $companyData[$i]['companyData']['company_discount'] = $result['discount'];
                $companyData[$i]['companyData']['company_payment_terms_in_days'] = $result['pay_term_days'];
                $companyData[$i]['companyData']['company_opening_balance'] = $result['opening_balance'];
                $companyData[$i]['companyData']['favorite_flag'] = $result['favorite_flag'];
                $companyData[$i]['companyData']['is_verified'] = $result['is_verified'];
                $companyData[$i]['companyData']['is_active'] = $result['is_active'];
                $companyData[$i]['companyData']['is_linked'] = $result['is_linked'];
                $companyData[$i]['companyData']['verified_by'] = $result['verified_by'];
                $companyData[$i]['companyData']['generated_by'] = $result['generated_by'];
                $companyData[$i]['companyData']['updated_by'] = $result['updated_by'];
                $companyData[$i]['companyData']['verified_date'] = $result['verified_date'];
                $companyData[$i]['companyData']['created_at'] = $result['date_added'];
                $companyData[$i]['companyData']['updated_at'] = $result['date_updated'];

                $companyData[$i]['swotData']['company_id'] = $result['company_id'];
                $companyData[$i]['swotData']['strength'] = !empty($result['strength']) ? $result['strength'] : 0;
                $companyData[$i]['swotData']['weakness'] = !empty($result['weakness']) ? $result['weakness'] : 0;
                $companyData[$i]['swotData']['opportunity'] = !empty($result['opportunity']) ? $result['opportunity'] : 0;
                $companyData[$i]['swotData']['threat'] = !empty($result['threat']) ? $result['threat'] : 0;
                $companyData[$i]['swotData']['created_at'] = $result['date_added'];
                $companyData[$i]['swotData']['updated_at'] = $result['date_updated'];

                $companyData[$i]['bankData']['company_id'] = $result['company_id'];
                $companyData[$i]['bankData']['bank_name'] = $result['bank_name'];
                $companyData[$i]['bankData']['account_holder_name'] = $result['acc_name'];
                $companyData[$i]['bankData']['account_no'] = $result['acc_num'];
                $companyData[$i]['bankData']['branch_name'] = $result['branch_name'];
                $companyData[$i]['bankData']['ifsc_code'] = $result['ifsc_code'];
                $companyData[$i]['bankData']['created_at'] = $result['date_added'];
                $companyData[$i]['bankData']['updated_at'] = $result['date_updated'];

                $companyData[$i]['packagingData']['company_id'] = $result['company_id'];
                $companyData[$i]['packagingData']['gst_no'] = !empty($result['gst_no']) ? $result['gst_no'] : 0;
                $companyData[$i]['packagingData']['cst_no'] = !empty($result['cst_no']) ? $result['cst_no'] : 0;
                $companyData[$i]['packagingData']['tin_no'] = !empty($result['tin_no']) ? $result['tin_no'] : 0;
                $companyData[$i]['packagingData']['vat_no'] = !empty($result['vat_no']) ? $result['vat_no'] : 0;
                $companyData[$i]['packagingData']['created_at'] = $result['date_added'];
                $companyData[$i]['packagingData']['updated_at'] = $result['date_updated'];

                $companyData[$i]['referencesData']['company_id'] = $result['company_id'];
                $companyData[$i]['referencesData']['ref_person_name'] = $result['ref_person'];
                $companyData[$i]['referencesData']['ref_person_mobile'] = $result['ref_mobile'];
                $companyData[$i]['referencesData']['ref_person_company'] = $result['ref_company'];
                $companyData[$i]['referencesData']['ref_person_address'] = $result['ref_address'];
                $companyData[$i]['referencesData']['created_at'] = $result['date_added'];
                $companyData[$i]['referencesData']['updated_at'] = $result['date_updated'];
                $i++;
            }
        }

        if (!empty($companyData)) {
            DB::table('companies')->truncate();
            DB::table('company_swot_details')->truncate();
            DB::table('company_bank_details')->truncate();
            DB::table('company_packaging_details')->truncate();
            DB::table('company_references')->truncate();
            foreach ($companyData as $cData) {
                $company = new Company;
                $company->id = $cData['companyData']['id'];
                $company->company_name = $cData['companyData']['company_name'];
                $company->company_type = $cData['companyData']['company_type'];
                $company->company_country = $cData['companyData']['company_country'];
                $company->company_state = $cData['companyData']['company_state'];
                $company->company_city = $cData['companyData']['company_city'];
                $company->company_website = $cData['companyData']['company_website'];
                $company->company_landline = $cData['companyData']['company_landline'];
                $company->company_mobile = $cData['companyData']['company_mobile'];
                $company->company_watchout = $cData['companyData']['company_watchout'];
                $company->company_remark_watchout = $cData['companyData']['company_remark_watchout'];
                $company->company_about = $cData['companyData']['company_about'];
                $company->company_category = $cData['companyData']['company_category'];
                $company->company_transport = $cData['companyData']['company_transport'];
                $company->company_discount = $cData['companyData']['company_discount'];
                $company->company_payment_terms_in_days = $cData['companyData']['company_payment_terms_in_days'];
                $company->company_opening_balance = $cData['companyData']['company_opening_balance'];
                $company->favorite_flag = $cData['companyData']['favorite_flag'];
                $company->is_verified = $cData['companyData']['is_verified'];
                $company->is_active = $cData['companyData']['is_active'];
                $company->is_linked = $cData['companyData']['is_linked'];
                $company->verified_by = $cData['companyData']['verified_by'];
                $company->generated_by = $cData['companyData']['generated_by'];
                $company->updated_by = $cData['companyData']['updated_by'];
                $company->verified_date = $cData['companyData']['verified_date'];
                $company->created_at = $cData['companyData']['created_at'];
                $company->updated_at = $cData['companyData']['updated_at'];
                $company->save();

                $csdId = CompanySwotDetails::orderBy('id', 'DESC')->first('id');
                $sdId = !empty($csdId) ? $csdId->id + 1 : 1;

                $swotData = new CompanySwotDetails;
                $swotData->id = $sdId;
                $swotData->company_id = $cData['swotData']['company_id'];
                $swotData->strength = $cData['swotData']['strength'];
                $swotData->weakness = $cData['swotData']['weakness'];
                $swotData->opportunity = $cData['swotData']['opportunity'];
                $swotData->threat = $cData['swotData']['threat'];
                $swotData->created_at = $cData['swotData']['created_at'];
                $swotData->updated_at = $cData['swotData']['updated_at'];
                $swotData->save();

                $cbdId = CompanyBankDetails::orderBy('id', 'DESC')->first('id');
                $bdId = !empty($cbdId) ? $cbdId->id + 1 : 1;

                $bankDetail = new CompanyBankDetails;
                $bankDetail->id = $bdId;
                $bankDetail->company_id = $cData['bankData']['company_id'];
                $bankDetail->bank_name = $cData['bankData']['bank_name'];
                $bankDetail->account_holder_name = $cData['bankData']['account_holder_name'];
                $bankDetail->account_no = $cData['bankData']['account_no'];
                $bankDetail->branch_name = $cData['bankData']['branch_name'];
                $bankDetail->ifsc_code = $cData['bankData']['ifsc_code'];
                $bankDetail->created_at = $cData['bankData']['created_at'];
                $bankDetail->updated_at = $cData['bankData']['updated_at'];
                $bankDetail->save();

                $cpdId = CompanyPackagingDetails::orderBy('id', 'DESC')->first('id');
                $pdId = !empty($cpdId) ? $cpdId->id + 1 : 1;

                $package = new CompanyPackagingDetails;
                $package->id = $pdId;
                $package->company_id = $cData['packagingData']['company_id'];
                $package->gst_no = $cData['packagingData']['gst_no'];
                $package->cst_no = $cData['packagingData']['cst_no'];
                $package->tin_no = $cData['packagingData']['tin_no'];
                $package->vat_no = $cData['packagingData']['vat_no'];
                $package->created_at = $cData['packagingData']['created_at'];
                $package->updated_at = $cData['packagingData']['updated_at'];
                $package->save();

                $crId = CompanyReferences::orderBy('id', 'DESC')->first('id');
                $crefId = !empty($crId) ? $crId->id + 1 : 1;

                $reference = new CompanyReferences;
                $reference->id = $crefId;
                $reference->company_id = $cData['referencesData']['company_id'];
                $reference->ref_person_name = $cData['referencesData']['ref_person_name'];
                $reference->ref_person_mobile = $cData['referencesData']['ref_person_mobile'];
                $reference->ref_person_company = $cData['referencesData']['ref_person_company'];
                $reference->ref_person_address = $cData['referencesData']['ref_person_address'];
                $reference->created_at = $cData['referencesData']['created_at'];
                $reference->updated_at = $cData['referencesData']['updated_at'];
                $reference->save();
            }
        }

        $casql = "SELECT * FROM company_address";
        $caquery = mysqli_query($this->conn, $casql);
        if (mysqli_num_rows($caquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($caquery)) {
                $companyAddressData[$i]['id'] = $result['company_address_id'];
                $companyAddressData[$i]['company_id'] = $result['company_id'];
                $companyAddressData[$i]['address_type'] = $result['type_of_address_id'];
                $companyAddressData[$i]['address'] = $result['address'];
                $companyAddressData[$i]['country_code'] = $result['country_code'];
                $companyAddressData[$i]['mobile'] = $result['landline_no'];
                $i++;
            }
        }

        if (!empty($companyAddressData)) {
            DB::table('company_addresses')->truncate();
            foreach ($companyAddressData as $caData) {
                $companyAddress = new CompanyAddress;
                $companyAddress->id = $caData['id'];
                $companyAddress->company_id = $caData['company_id'];
                $companyAddress->address_type = $caData['address_type'];
                $companyAddress->address = $caData['address'];
                $companyAddress->country_code = $caData['country_code'];
                $companyAddress->mobile = $caData['mobile'];
                $companyAddress->save();
            }
        }

        $caosql = "SELECT * FROM company_address_owner";
        $caoquery = mysqli_query($this->conn, $caosql);
        if (mysqli_num_rows($caoquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($caoquery)) {
                $des = $result['designation'];
                if (is_numeric($des)) {
                    $result['designation'] = json_encode($des);
                } else {
                    $desg = @unserialize($result['designation']);
                    if (is_array($desg)) {
                        if ($desg) {
                            $result['designation'] = json_encode($desg);
                        } else {
                            $result['designation'] = json_encode([]);
                        }
                    } else {
                        $designation = "SELECT * FROM designation WHERE designation_name = '$des'";
                        $designationData = mysqli_query($this->conn, $designation);

                        $dResult = mysqli_fetch_row($designationData);
                        if ($dResult != NULL) {
                            $result['designation'] = json_encode($dResult[0]);
                        } else {
                            $result['designation'] = json_encode([]);
                        }
                    }
                }

                if ($result['date_added'] == '0000-00-00 00:00:00') {
                    $result['date_added'] = NULL;
                } else {
                    $result['date_added'] = $result['date_added'];
                }

                $companyAddressOwnerData[$i]['id'] = $result['company_address_owner_id'];
                $companyAddressOwnerData[$i]['company_address_id'] = $result['company_address_id'];
                $companyAddressOwnerData[$i]['name'] = $result['name'];
                $companyAddressOwnerData[$i]['designation'] = $result['designation'];
                $companyAddressOwnerData[$i]['profile_pic'] = $result['profile_pic'];
                $companyAddressOwnerData[$i]['mobile'] = $result['mobile_no'];
                $companyAddressOwnerData[$i]['email'] = $result['email'];
                $companyAddressOwnerData[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($companyAddressOwnerData)) {
            DB::table('company_address_owners')->truncate();
            foreach ($companyAddressOwnerData as $caoData) {
                $companyAddressOwner = new CompanyAddressOwner;
                $companyAddressOwner->id = $caoData['id'];
                $companyAddressOwner->company_address_id = $caoData['company_address_id'];
                $companyAddressOwner->name = $caoData['name'];
                $companyAddressOwner->designation = $caoData['designation'];
                $companyAddressOwner->profile_pic = $caoData['profile_pic'];
                $companyAddressOwner->mobile = $caoData['mobile'];
                $companyAddressOwner->email = $caoData['email'];
                $companyAddressOwner->created_at = $caoData['date_added'];
                $companyAddressOwner->save();
            }
        }

        $ccsql = "SELECT * FROM company_category";
        $ccquery = mysqli_query($this->conn, $ccsql);
        if (mysqli_num_rows($ccquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($ccquery)) {
                $companyCategoryData[$i]['id'] = $result['company_category_id'];
                $companyCategoryData[$i]['category_name'] = $result['name'];
                $companyCategoryData[$i]['sort_order'] = $result['sort_order'];
                $i++;
            }
        }

        if (!empty($companyCategoryData)) {
            DB::table('company_categories')->truncate();
            foreach ($companyCategoryData as $ccData) {
                $companyCategory = new CompanyCategory;
                $companyCategory->id = $ccData['id'];
                $companyCategory->category_name = $ccData['category_name'];
                $companyCategory->sort_order = $ccData['sort_order'];
                $companyCategory->save();
            }
        }

        $cesql = "SELECT * FROM company_email";
        $cequery = mysqli_query($this->conn, $cesql);
        if (mysqli_num_rows($cequery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($cequery)) {
                $companyEmailData[$i]['id'] = $result['company_email_id'];
                $companyEmailData[$i]['company_id'] = $result['company_id'];
                $companyEmailData[$i]['email_id'] = $result['email_id'];
                $i++;
            }
        }

        if (!empty($companyEmailData)) {
            DB::table('company_emails')->truncate();
            foreach ($companyEmailData as $ceData) {
                $companyEmail = new CompanyEmails;
                $companyEmail->id = $ceData['id'];
                $companyEmail->company_id = $ceData['company_id'];
                $companyEmail->email_id = $ceData['email_id'];
                $companyEmail->save();
            }
        }

        $cosql = "SELECT * FROM company_owner";
        $coquery = mysqli_query($this->conn, $cosql);

        if (mysqli_num_rows($coquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($coquery)) {
                $des = $result['designation'];
                if (is_numeric($des)) {
                    $result['designation'] = json_encode($des);
                } else {
                    $desg = @unserialize($result['designation']);
                    if (is_array($desg)) {
                        if ($desg) {
                            $result['designation'] = json_encode($desg);
                        } else {
                            $result['designation'] = json_encode([]);
                        }
                    } else {
                        $designation = "SELECT * FROM designation WHERE designation_name = '$des'";
                        $designationData = mysqli_query($this->conn, $designation);

                        $dResult = mysqli_fetch_row($designationData);

                        if ($dResult != NULL) {
                            $result['designation'] = json_encode($dResult[0]);
                        } else {
                            $result['designation'] = json_encode([]);
                        }
                    }
                }

                $companyOwnerData[$i]['id'] = $result['company_owner_id'];
                $companyOwnerData[$i]['company_id'] = $result['company_id'];
                $companyOwnerData[$i]['contact_person_name'] = $result['name'];
                $companyOwnerData[$i]['contact_person_designation'] = $result['designation'];
                $companyOwnerData[$i]['contact_person_profile_pic'] = $result['image'];
                $companyOwnerData[$i]['contact_person_mobile'] = $result['mobile_no'];
                $companyOwnerData[$i]['contact_person_email'] = $result['email_id'];
                $i++;
            }
        }

        if (!empty($companyOwnerData)) {
            DB::table('company_contact_details')->truncate();
            foreach ($companyOwnerData as $coData) {
                $companyContactDetails = new CompanyContactDetails;
                $companyContactDetails->id = $coData['id'];
                $companyContactDetails->company_id = $coData['company_id'];
                $companyContactDetails->contact_person_name = $coData['contact_person_name'];
                $companyContactDetails->contact_person_designation = $coData['contact_person_designation'];
                $companyContactDetails->contact_person_profile_pic = $coData['contact_person_profile_pic'];
                $companyContactDetails->contact_person_mobile = $coData['contact_person_mobile'];
                $companyContactDetails->contact_person_email = $coData['contact_person_email'];
                $companyContactDetails->save();
            }
        }

        sleep(2);
        $companylink = "SELECT * FROM link_companies";
        $companylinkquery = mysqli_query($this->conn, $companylink);
        if(mysqli_num_rows($companylinkquery) != 0) {
            $i = 0;
            while($result = mysqli_fetch_assoc($companylinkquery)) {
                $ComapnyLinkList[$i]['id'] = $result['id'];
                $ComapnyLinkList[$i]['company_id'] = $result['company_id'];
                $ComapnyLinkList[$i]['link_companies_id'] = $result['link_companies_id'];
                $i++;
            }
        }

        if(!empty($ComapnyLinkList)) {
            DB::table('link_companies')->truncate();
            foreach($ComapnyLinkList as $cl) {
                $linkcompany = new linkCompanies();
                $linkcompany->id = $cl['id'];
                $linkcompany->company_id = $cl['company_id'];
                $linkcompany->link_companies_id = $cl['link_companies_id'];
                $linkcompany->save();
            }
        }

        $cc = "SELECT * FROM company_commission";
        $ccq = mysqli_query($this->conn, $cc);
        if (mysqli_num_rows($ccq) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($ccq)) {
                $ccq_a[$i]['id'] = $result['id'];
                $ccq_a[$i]['customer_id'] = $result['customer_id'];
                $ccq_a[$i]['supplier_id'] = $result['supplier_id'];
                $ccq_a[$i]['commission_percentage'] = $result['commission_percentage'];
                $ccq_a[$i]['flag'] = $result['flag'];
                $i++;
            }
        }

        if (!empty($ccq_a)) {
            DB::table('company_commissions')->truncate();
            foreach ($ccq_a as $cl) {
                $ccq_x = new CompanyCommission();
                $ccq_x->id = $cl['id'];
                $ccq_x->customer_id = $cl['customer_id'];
                $ccq_x->supplier_id = $cl['supplier_id'];
                $ccq_x->commission_percentage = $cl['commission_percentage'];
                $ccq_x->flag = $cl['flag'];
                $ccq_x->save();
            }
        }

    }

    public function goodsReturn()
    {
        $GoodReturnList = [];
        $GRItemList = [];

        $goodreturn = "SELECT * FROM goods_return";
        $grquery = mysqli_query($this->conn, $goodreturn);
        if (mysqli_num_rows($grquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($grquery)) {
                $GoodReturnList[$i]['id'] = $result['id'];
                $GoodReturnList[$i]['goods_return_id'] = $result['goods_return_id'];
                $GoodReturnList[$i]['p_increment_id'] = $result['p_increment_id'];
                $GoodReturnList[$i]['iuid'] = $result['iuid'];
                $GoodReturnList[$i]['reference_id'] = $result['reference_id'];
                $GoodReturnList[$i]['financial_year_id'] = $result['financial_year_id'];
                $GoodReturnList[$i]['generated_by'] = $result['generated_by'];
                $GoodReturnList[$i]['sale_bill_id'] = $result['sale_bill_id'];
                $GoodReturnList[$i]['sale_bill_for'] = $result['sale_bill_for'];
                $GoodReturnList[$i]['company_id'] = $result['company_id'];
                $GoodReturnList[$i]['supplier_id'] = $result['supplier_id'];
                $GoodReturnList[$i]['supp_invoice_no'] = $result['supp_invoice_no'];
                $GoodReturnList[$i]['multiple_attachment'] = $result['multiple_attachment'];
                $GoodReturnList[$i]['amount'] = $result['amount'];
                $GoodReturnList[$i]['adjust_amount'] = $result['adjust_amount'];
                $GoodReturnList[$i]['goods_return'] = $result['goods_return'];
                $GoodReturnList[$i]['tot_peices'] = $result['tot_peices'];
                $GoodReturnList[$i]['tot_meters'] = $result['tot_meters'];
                $GoodReturnList[$i]['tot_amount'] = $result['tot_amount'];
                $GoodReturnList[$i]['is_deleted'] = $result['is_deleted'];
                $GoodReturnList[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($GoodReturnList)) {
            DB::table('goods_returns')->truncate();
            foreach ($GoodReturnList as $goodreturn) {
                $gr = new GoodsReturn();
                $gr->id = $goodreturn['id'];
                $gr->goods_return_id = $goodreturn['goods_return_id'];
                $gr->p_increment_id = $goodreturn['p_increment_id'];
                $gr->iuid = $goodreturn['iuid'];
                $gr->reference_id = $goodreturn['reference_id'];
                $gr->financial_year_id = $goodreturn['financial_year_id'];
                $gr->generated_by = $goodreturn['generated_by'];
                $gr->sale_bill_id = $goodreturn['sale_bill_id'];
                $gr->sale_bill_for = $goodreturn['sale_bill_for'];
                $gr->company_id = $goodreturn['company_id'];
                $gr->supplier_id = $goodreturn['supplier_id'];
                $gr->supp_invoice_no = $goodreturn['supp_invoice_no'];
                $gr->multiple_attachment = $goodreturn['multiple_attachment'];
                $gr->amount = $goodreturn['amount'];
                $gr->adjust_amount = $goodreturn['adjust_amount'];
                $gr->goods_return = $goodreturn['goods_return'];
                $gr->tot_peices = $goodreturn['tot_peices'];
                $gr->tot_meters = $goodreturn['tot_meters'];
                $gr->tot_amount = $goodreturn['tot_amount'];
                $gr->is_deleted = $goodreturn['is_deleted'];
                $gr->date_added = $goodreturn['date_added'];
                $gr->created_at = $goodreturn['date_added'];
                $gr->save();
            }
        }

        $gritem = "SELECT * FROM gr_sale_bill_item";
        $gritemquery = mysqli_query($this->conn, $gritem);
        if (mysqli_num_rows($gritemquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($gritemquery)) {
                $GRItemList[$i]['id'] = $result['gr_sale_bill_item_id'];
                $GRItemList[$i]['gr_increment_id'] = $result['gr_increment_id'];
                $GRItemList[$i]['goods_return_id'] = $result['goods_return_id'];
                $GRItemList[$i]['product_or_fabric_id'] = $result['product_or_fabric_id'];
                $GRItemList[$i]['peices'] = $result['peices'];
                $GRItemList[$i]['meters'] = $result['meters'];
                $GRItemList[$i]['peices_meters'] = $result['peices_meters'];
                $GRItemList[$i]['rate'] = $result['rate'];
                $GRItemList[$i]['discount_per'] = $result['discount_per'];
                $GRItemList[$i]['discount_amt'] = $result['discount_amt'];
                $GRItemList[$i]['cgst_per'] = $result['cgst_per'];
                $GRItemList[$i]['cgst_amt'] = $result['cgst_amt'];
                $GRItemList[$i]['sgst_per'] = $result['sgst_per'];
                $GRItemList[$i]['sgst_amt'] = $result['sgst_amt'];
                $GRItemList[$i]['igst_per'] = $result['igst_per'];
                $GRItemList[$i]['igst_amt'] = $result['igst_amt'];
                $GRItemList[$i]['is_deleted'] = $result['is_deleted'];
                $GRItemList[$i]['amount'] = $result['amount'];
                $GRItemList[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($GRItemList)) {
            DB::table('gr_sale_bill_items')->truncate();
            foreach ($GRItemList as $gritem) {
                $grsalebillitem = new GrSaleBillItem();
                $grsalebillitem->id = $gritem['id'];
                $grsalebillitem->gr_increment_id = $gritem['gr_increment_id'];
                $grsalebillitem->goods_return_id = $gritem['goods_return_id'];
                $grsalebillitem->product_or_fabric_id = $gritem['product_or_fabric_id'];
                $grsalebillitem->peices = $gritem['peices'];
                $grsalebillitem->meters = $gritem['meters'];
                $grsalebillitem->peices_meters = $gritem['peices_meters'];
                $grsalebillitem->rate = $gritem['rate'];
                $grsalebillitem->discount_per = $gritem['discount_per'];
                $grsalebillitem->discount_amt = $gritem['discount_amt'];
                $grsalebillitem->cgst_per = $gritem['cgst_per'];
                $grsalebillitem->cgst_amt = $gritem['cgst_amt'];
                $grsalebillitem->sgst_per = $gritem['sgst_per'];
                $grsalebillitem->sgst_amt = $gritem['sgst_amt'];
                $grsalebillitem->igst_per = $gritem['igst_per'];
                $grsalebillitem->igst_amt = $gritem['igst_amt'];
                $grsalebillitem->is_deleted = $gritem['is_deleted'];
                $grsalebillitem->amount = $gritem['amount'];
                $grsalebillitem->created_at = $gritem['date_added'];
                $grsalebillitem->save();
            }
        }
    }

    public function inwardAll()
    {
        $InwardActionList = [];
        $InwardLinkList = [];
        $InwardOrderActionList = [];
        $InwardOrderDetailList = [];
        $InwardOrderList = [];
        $InwardProductFabricList = [];
        $InwardSampleList = [];
        $InwardList = [];

        $inward = "SELECT * FROM inward";
        $inwardquery = mysqli_query($this->conn, $inward);
        if (mysqli_num_rows($inwardquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($inwardquery)) {
                if ($result['date_added'] == '0000-00-00 00:00:00') {
                    $result['date_added'] = NULL;
                } else {
                    $result['date_added'] = $result['date_added'];
                }
                if ($result['date_updated'] == '0000-00-00 00:00:00') {
                    $result['date_updated'] = NULL;
                } else {
                    $result['date_updated'] = $result['date_updated'];
                }
                if ($result['courier_received_time'] == '0000-00-00 00:00:00') {
                    $result['courier_received_time'] = NULL;
                } else {
                    $result['courier_received_time'] = $result['courier_received_time'];
                }
                $InwardList[$i]['inward_id'] = $result['inward_id'];
                $InwardList[$i]['iuid'] = $result['iuid'];
                $InwardList[$i]['call_by'] = $result['call_by'];
                $InwardList[$i]['inward_ref_via'] = $result['inward_ref_via'];
                $InwardList[$i]['general_input_ref_id'] = $result['general_input_ref_id'];
                $InwardList[$i]['new_or_old_inward'] = $result['new_or_old_inward'];
                $InwardList[$i]['financial_year_id'] = $result['financial_year_id'];
                $InwardList[$i]['connected_inward'] = $result['connected_inward'];
                $InwardList[$i]['inward_date'] = $result['inward_date'];
                $InwardList[$i]['subject'] = $result['subject'];
                $InwardList[$i]['employee_id'] = $result['employe_id'];
                $InwardList[$i]['type_of_inward'] = $result['type_of_inward'];
                $InwardList[$i]['from_number'] = $result['from_number'];
                $InwardList[$i]['receiver_number'] = $result['receiver_number'];
                $InwardList[$i]['company_id'] = $result['company_id'];
                $InwardList[$i]['supplier_id'] = $result['supplier_id'];
                $InwardList[$i]['courier_name'] = $result['courier_name'];
                $InwardList[$i]['weight_of_parcel'] = $result['weight_of_parcel'];
                $InwardList[$i]['courier_receipt_no'] = $result['courier_receipt_no'];
                $InwardList[$i]['courier_received_time'] = $result['courier_received_time'];
                $InwardList[$i]['from_name'] = $result['from_name'];
                $InwardList[$i]['attachments'] = $result['attachments'] ? json_encode(@unserialize($result['attachments'])) : '[]';
                $InwardList[$i]['remarks'] = $result['remarks'];
                $InwardList[$i]['latter_by_id'] = $result['latter_by_id'];
                $InwardList[$i]['delivery_by'] = $result['delivery_by'];
                $InwardList[$i]['receiver_email_id'] = $result['receiver_email_id'];
                $InwardList[$i]['from_email_id'] = $result['from_email_id'];
                $InwardList[$i]['product_main_id'] = $result['product_main_id'];
                $InwardList[$i]['product_image_id'] = $result['product_image_id'] ? json_encode(@unserialize($result['product_image_id'])) : '[]';
                $InwardList[$i]['inward_link_with_id'] = $result['inward_link_with_id'];
                $InwardList[$i]['enquiry_complain_for'] = $result['enquiry_complain_for'];
                $InwardList[$i]['client_remark'] = $result['client_remark'];
                $InwardList[$i]['notify_client'] = $result['notify_client'];
                $InwardList[$i]['notify_md'] = $result['notify_md'];
                // $InwardList[$i]['required_followup'] = $result['required_followup'];
                $InwardList[$i]['delivery_period'] = $result['delivery_period'];
                $InwardList[$i]['to_name'] = $result['to_name'];
                $InwardList[$i]['mark_as_draft'] = $result['mark_as_draft'];
                $InwardList[$i]['sample_via'] = $result['sample_via'];
                $InwardList[$i]['notify_md'] = $result['notify_md'];
                $InwardList[$i]['sample_for'] = $result['sample_for'];
                $InwardList[$i]['sample_prod_or_fabric'] = $result['sample_prod_or_fabric'];
                $InwardList[$i]['product_qty'] = $result['product_qty'];
                $InwardList[$i]['fabric_meters'] = $result['fabric_meters'];
                $InwardList[$i]['is_deleted'] = $result['is_deleted'];
                $InwardList[$i]['date_added'] = $result['date_added'];
                $InwardList[$i]['date_updated'] = $result['date_updated'];
                $i++;
            }
        }

        if (!empty($InwardList)) {
            DB::table('inwards')->truncate();
            foreach ($InwardList as $inwardata) {
                $inward = new inward();
                $inward->inward_id = $inwardata['inward_id'];
                $inward->iuid = $inwardata['iuid'];
                $inward->call_by = $inwardata['call_by'];
                $inward->inward_ref_via = $inwardata['inward_ref_via'];
                $inward->general_input_ref_id = $inwardata['general_input_ref_id'];
                $inward->new_or_old_inward = $inwardata['new_or_old_inward'];
                $inward->financial_year_id = $inwardata['financial_year_id'];
                $inward->connected_inward = $inwardata['connected_inward'];
                $inward->inward_date = $inwardata['inward_date'];
                $inward->subject = $inwardata['subject'];
                $inward->employee_id = $inwardata['employee_id'];
                $inward->type_of_inward = $inwardata['type_of_inward'];
                $inward->from_number = $inwardata['from_number'];
                $inward->receiver_number = $inwardata['receiver_number'];
                $inward->company_id = $inwardata['company_id'];
                $inward->supplier_id = $inwardata['supplier_id'];
                $inward->courier_name = $inwardata['courier_name'];
                $inward->weight_of_parcel = $inwardata['weight_of_parcel'];
                $inward->courier_receipt_no = $inwardata['courier_receipt_no'];
                $inward->courier_received_time = $inwardata['courier_received_time'];
                $inward->from_name = $inwardata['from_name'];
                $inward->attachments = $inwardata['attachments'];
                $inward->remarks = $inwardata['remarks'];
                $inward->latter_by_id = $inwardata['latter_by_id'];
                $inward->delivery_by = $inwardata['delivery_by'];
                $inward->receiver_email_id = $inwardata['receiver_email_id'];
                $inward->from_email_id = $inwardata['from_email_id'];
                $inward->product_main_id = $inwardata['product_main_id'];
                $inward->product_image_id = $inwardata['product_image_id'];
                $inward->inward_link_with_id = $inwardata['inward_link_with_id'];
                $inward->enquiry_complain_for = $inwardata['enquiry_complain_for'];
                $inward->client_remark = $inwardata['client_remark'];
                $inward->notify_client = $inwardata['notify_client'];
                $inward->notify_md = $inwardata['notify_md'];
                // $inward->required_followup = $inwardata['required_followup'];
                $inward->delivery_period = $inwardata['delivery_period'];
                $inward->to_name = $inwardata['to_name'];
                $inward->mark_as_draft = $inwardata['mark_as_draft'];
                $inward->sample_via = $inwardata['sample_via'];
                $inward->notify_md = $inwardata['notify_md'];
                $inward->sample_for = $inwardata['sample_for'];
                $inward->sample_prod_or_fabric = $inwardata['sample_prod_or_fabric'];
                $inward->product_qty = $inwardata['product_qty'];
                $inward->fabric_meters = $inwardata['fabric_meters'];
                $inward->is_deleted = $inwardata['is_deleted'];
                $inward->created_at = $inwardata['date_added'];
                $inward->updated_at = $inwardata['date_updated'];
                $inward->save();
            }
        }

        $inwardsample = "SELECT * FROM inward_sample";
        $inwardsamplequery = mysqli_query($this->conn, $inwardsample);
        if (mysqli_num_rows($inwardsamplequery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($inwardsamplequery)) {
                $InwardSampleList[$i]['inward_sample_id'] = $result['inward_sample_id'];
                $InwardSampleList[$i]['inward_id'] = $result['inward_id'];
                $InwardSampleList[$i]['name'] = $result['name'];
                $InwardSampleList[$i]['image'] = $result['image'];
                $InwardSampleList[$i]['price'] = $result['price'];
                $InwardSampleList[$i]['qty'] = $result['qty'];
                $InwardSampleList[$i]['meters'] = $result['meters'];
                $InwardSampleList[$i]['is_deleted'] = $result['is_deleted'];
                $InwardSampleList[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($InwardSampleList)) {
            DB::table('inward_samples')->truncate();
            foreach ($InwardSampleList as $is) {
                $inwardsample = new InwardSample();
                $inwardsample->inward_sample_id = $is['inward_sample_id'];
                $inwardsample->inward_id = $is['inward_id'];
                $inwardsample->name = $is['name'];
                $inwardsample->image = $is['image'];
                $inwardsample->price = $is['price'];
                $inwardsample->qty = $is['qty'];
                $inwardsample->meters = $is['meters'];
                $inwardsample->is_deleted = $is['is_deleted'];
                $inwardsample->created_at = $is['date_added'];
                $inwardsample->save();
            }
        }

        $inwardproductfabric = "SELECT * FROM inward_product_or_fabric";
        $inwardproductfabricquery = mysqli_query($this->conn, $inwardproductfabric);
        if (mysqli_num_rows($inwardproductfabricquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($inwardproductfabricquery)) {
                $InwardProductFabricList[$i]['id'] = $result['inward_product_or_fabric_id'];
                $InwardProductFabricList[$i]['inward_id'] = $result['inward_id'];
                $InwardProductFabricList[$i]['product_or_fabric_id'] = $result['product_or_fabric_id'];
                $InwardProductFabricList[$i]['product_or_fabric_flag'] = $result['product_or_fabric_flag'];
                $InwardProductFabricList[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if(!empty($InwardProductFabricList)) {
            DB::table('inward_product_fabrics')->truncate();
            foreach($InwardProductFabricList as $ipf) {
                $inwardfabricproduct = new InwardProductFabric();
                $inwardfabricproduct->id = $ipf['id'];
                $inwardfabricproduct->inward_id = $ipf['inward_id'];
                $inwardfabricproduct->product_or_fabric_id = $ipf['product_or_fabric_id'];
                $inwardfabricproduct->product_or_fabric_flag = $ipf['product_or_fabric_flag'];
                $inwardfabricproduct->created_at = $ipf['date_added'];
                $inwardfabricproduct->save();
            }
        }

        $inwardaction = "SELECT * FROM inward_action";
        $inwardactionquery = mysqli_query($this->conn, $inwardaction);
        if (mysqli_num_rows($inwardactionquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($inwardactionquery)) {
                $InwardActionList[$i]['inward_action_id'] = $result['inward_action_id'];
                $InwardActionList[$i]['inward_id'] = $result['inward_id'];
                $InwardActionList[$i]['action_date'] = $result['action_date'];
                $InwardActionList[$i]['employee_id'] = $result['employe_id'];
                $InwardActionList[$i]['instruction'] = $result['instruction'];
                $InwardActionList[$i]['status'] = $result['status'];
                $i++;
            }
        }

        if (!empty($InwardActionList)) {
            DB::table('inward_actions')->truncate();
            foreach ($InwardActionList as $ia) {
                $inwardaction = new InwardActions();
                $inwardaction->inward_action_id = $ia['inward_action_id'];
                $inwardaction->inward_id = $ia['inward_id'];
                $inwardaction->action_date = $ia['action_date'];
                $inwardaction->employee_id = $ia['employee_id'];
                $inwardaction->instruction = $ia['instruction'];
                $inwardaction->status = $ia['status'];
                $inwardaction->save();
            }
        }

        $inwardorderdetail = "SELECT * FROM inward_order_for_details";
        $inwardorderdetailquery = mysqli_query($this->conn, $inwardorderdetail);
        if (mysqli_num_rows($inwardorderdetailquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($inwardorderdetailquery)) {
                if ($result['date_added'] == '0000-00-00 00:00:00') {
                    $result['date_added'] = NULL;
                } else {
                    $result['date_added'] = $result['date_added'];
                }
                if ($result['packing_date'] == '0000-00-00') {
                    $result['packing_date'] = NULL;
                } else {
                    $result['packing_date'] = $result['packing_date'];
                }
                $InwardOrderDetailList[$i]['id'] = $result['inward_order_for_details_id'];
                $InwardOrderDetailList[$i]['inward_id'] = $result['inward_id'];
                $InwardOrderDetailList[$i]['order_for'] = $result['order_for'];
                $InwardOrderDetailList[$i]['packing_id'] = $result['packin_id'];
                $InwardOrderDetailList[$i]['packing_date'] = $result['packing_date'];
                $InwardOrderDetailList[$i]['lump'] = $result['lump'];
                $InwardOrderDetailList[$i]['cut'] = $result['cut'];
                $InwardOrderDetailList[$i]['top'] = $result['top'];
                $InwardOrderDetailList[$i]['bottom'] = $result['bottom'];
                $InwardOrderDetailList[$i]['duppatta'] = $result['duppatta'];
                $InwardOrderDetailList[$i]['is_deleted'] = $result['is_deleted'];
                $InwardOrderDetailList[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($InwardOrderDetailList)) {
            DB::table('inward_order_details')->truncate();
            foreach ($InwardOrderDetailList as $iod) {
                $inwardorderdetail = new InwardOrderDetail();
                $inwardorderdetail->id = $iod['id'];
                $inwardorderdetail->inward_id = $iod['inward_id'];
                $inwardorderdetail->order_for = $iod['order_for'];
                $inwardorderdetail->packing_id = $iod['packing_id'];
                $inwardorderdetail->packing_date = $iod['packing_date'];
                $inwardorderdetail->lump = $iod['lump'];
                $inwardorderdetail->cut = $iod['cut'];
                $inwardorderdetail->top = $iod['top'];
                $inwardorderdetail->bottom = $iod['bottom'];
                $inwardorderdetail->duppatta = $iod['duppatta'];
                $inwardorderdetail->is_deleted = $iod['is_deleted'];
                $inwardorderdetail->created_at = $iod['date_added'];
                $inwardorderdetail->save();
            }
        }

        $inwardorderaction = "SELECT * FROM inward_order_action";
        $inwardorderactionquery = mysqli_query($this->conn, $inwardorderaction);
        if (mysqli_num_rows($inwardorderactionquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($inwardorderactionquery)) {
                $InwardOrderActionList[$i]['id'] = $result['inward_order_action_id'];
                $InwardOrderActionList[$i]['inward_order_id'] = $result['inward_order_id'];
                $InwardOrderActionList[$i]['action_flag'] = $result['action_flag'];
                $InwardOrderActionList[$i]['inward_id'] = $result['inward_id'];
                $InwardOrderActionList[$i]['order_for'] = $result['order_for'];
                $InwardOrderActionList[$i]['product_or_fabric_id'] = $result['product_or_fabric_id'];
                $InwardOrderActionList[$i]['sub_product_id'] = $result['sub_product_id'];
                $InwardOrderActionList[$i]['shade_no'] = $result['shade_no'];
                $InwardOrderActionList[$i]['qty'] = $result['qty'];
                $InwardOrderActionList[$i]['rate'] = $result['rate'];
                $InwardOrderActionList[$i]['discount'] = $result['discount'];
                $InwardOrderActionList[$i]['sale_bill_flag'] = $result['sale_bill_flag'];
                $InwardOrderActionList[$i]['is_deleted'] = $result['is_deleted'];
                $InwardOrderActionList[$i]['date_added'] = $result['date_added'];
            }
        }

        if(!empty($InwardOrderActionList)) {
            DB::table('inward_order_actions')->truncate();
            foreach($InwardOrderActionList as $ioa) {
                $inwardorderactiondata = new InwardOrderAction();
                $inwardorderactiondata->id = $ioa['id'];
                $inwardorderactiondata->inward_order_id = $ioa['inward_order_id'];
                $inwardorderactiondata->action_flag = $ioa['action_flag'];
                $inwardorderactiondata->inward_id = $ioa['inward_id'];
                $inwardorderactiondata->order_for = $ioa['order_for'];
                $inwardorderactiondata->product_or_fabric_id = $ioa['product_or_fabric_id'];
                $inwardorderactiondata->sub_product_id = $ioa['sub_product_id'];
                $inwardorderactiondata->shade_no = $ioa['shade_no'];
                $inwardorderactiondata->qty = $ioa['qty'];
                $inwardorderactiondata->rate = $ioa['rate'];
                $inwardorderactiondata->qty = $ioa['qty'];
                $inwardorderactiondata->discount = $ioa['discount'];
                $inwardorderactiondata->sale_bill_flag = $ioa['sale_bill_flag'];
                $inwardorderactiondata->is_deleted = $ioa['is_deleted'];
                $inwardorderactiondata->created_at = $ioa['date_added'];
                $inwardorderactiondata->save();
            }
        }

        $inwardlink = "SELECT * FROM inward_link_with";
        $inwardlinkquery = mysqli_query($this->conn, $inwardlink);
        if (mysqli_num_rows($inwardlinkquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($inwardlinkquery)) {
                $InwardLinkList[$i]['id'] = $result['inward_link_with_id'];
                $InwardLinkList[$i]['name'] = $result['name'];
                $i++;
            }
        }

        if (!empty($InwardLinkList)) {
            DB::table('inward_link_withs')->truncate();
            foreach ($InwardLinkList as $ilw) {
                $inwardlink = new InwardLinkWith();
                $inwardlink->id = $ilw['id'];
                $inwardlink->name = $ilw['name'];
                $inwardlink->is_delete = 0;
                $inwardlink->save();
            }
        }

        $inwardOrder = "SELECT * FROM inward_order";
        $inwardOrderquery = mysqli_query($this->conn, $inwardOrder);
        if (mysqli_num_rows($inwardOrderquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($inwardOrderquery)) {
                if ($result['packing_date'] == '0000-00-00') {
                    $result['packing_date'] = NULL;
                } else {
                    $result['packing_date'] = $result['packing_date'];
                }
                $InwardOrderList[$i]['id'] = $result['inward_order_id'];
                $InwardOrderList[$i]['inward_id'] = $result['inward_id'];
                $InwardOrderList[$i]['order_for'] = $result['order_for'];
                $InwardOrderList[$i]['product_or_fabric_id'] = $result['product_or_fabric_id'];
                $InwardOrderList[$i]['sub_product_id'] = $result['sub_product_id'];
                $InwardOrderList[$i]['shade_no'] = $result['shade_no'];
                $InwardOrderList[$i]['qty'] = $result['qty'];
                $InwardOrderList[$i]['rate'] = $result['rate'];
                $InwardOrderList[$i]['discount'] = $result['discount'];
                $InwardOrderList[$i]['packing_id'] = $result['packin_id'];
                $InwardOrderList[$i]['packing_date'] = $result['packing_date'];
                $InwardOrderList[$i]['lump'] = $result['lump'];
                $InwardOrderList[$i]['cut'] = $result['cut'];
                $InwardOrderList[$i]['top'] = $result['top'];
                $InwardOrderList[$i]['bottom'] = $result['bottom'];
                $InwardOrderList[$i]['duppatta'] = $result['duppatta'];
                $InwardOrderList[$i]['sale_bill_flag'] = $result['sale_bill_flag'];
                $InwardOrderList[$i]['is_deleted'] = $result['is_deleted'];
                $InwardOrderList[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($InwardOrderList)) {
            DB::table('inward_orders')->truncate();
            foreach ($InwardOrderList as $io) {
                $inwardorder = new InwardOrder();
                $inwardorder->id = $io['id'];
                $inwardorder->inward_id = $io['inward_id'];
                $inwardorder->order_for = $io['order_for'];
                $inwardorder->product_or_fabric_id = $io['product_or_fabric_id'];
                $inwardorder->sub_product_id = $io['sub_product_id'];
                $inwardorder->shade_no = $io['shade_no'];
                $inwardorder->qty = $io['qty'];
                $inwardorder->rate = $io['rate'];
                $inwardorder->discount = $io['discount'];
                $inwardorder->packing_id = $io['packing_id'];
                $inwardorder->packing_date = $io['packing_date'];
                $inwardorder->lump = $io['lump'];
                $inwardorder->cut = $io['cut'];
                $inwardorder->top = $io['top'];
                $inwardorder->bottom = $io['bottom'];
                $inwardorder->duppatta = $io['duppatta'];
                $inwardorder->sale_bill_flag = $io['sale_bill_flag'];
                $inwardorder->is_deleted = $io['is_deleted'];
                $inwardorder->save();
            }
        }

    }

    public function iuids($limit, $offset)
    {
        $IuidList = [];
        $iuid = "SELECT * FROM iuid limit $limit offset $offset";
        $iuidquery = mysqli_query($this->conn, $iuid);
        if (mysqli_num_rows($iuidquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($iuidquery)) {
                $IuidList[$i]['id'] = $result['id'];
                $IuidList[$i]['iuid'] = $result['iuid'];
                $IuidList[$i]['financial_year_id'] = $result['financial_year_id'];
                $IuidList[$i]['name'] = $result['name'];
                $IuidList[$i]['inward_type'] = $result['inward_type'];
                $IuidList[$i]['inward_medium'] = $result['inward_medium'];
                $IuidList[$i]['inward_details'] = $result['inward_details'];
                $IuidList[$i]['company_id'] = $result['company_id'];
                $IuidList[$i]['company_type'] = $result['company_type'];
                $IuidList[$i]['company_person'] = $result['company_person'];
                $IuidList[$i]['generated_by'] = $result['generated_by'];
                $IuidList[$i]['assigned_to'] = $result['assigned_to'];
                $IuidList[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($IuidList)) {
            foreach ($IuidList as $iuidlist) {
                $iuidData = new Iuid();
                $iuidData->id = $iuidlist['id'];
                $iuidData->iuid = $iuidlist['iuid'];
                $iuidData->financial_year_id = $iuidlist['financial_year_id'];
                $iuidData->name = $iuidlist['name'];
                $iuidData->inward_type = $iuidlist['inward_type'];
                $iuidData->inward_medium = $iuidlist['inward_medium'];
                $iuidData->inward_details = $iuidlist['inward_details'];
                $iuidData->company_id = $iuidlist['company_id'];
                $iuidData->company_type = $iuidlist['company_type'];
                $iuidData->company_person = $iuidlist['company_person'];
                $iuidData->generated_by = $iuidlist['generated_by'];
                $iuidData->assigned_to = $iuidlist['assigned_to'];
                $iuidData->created_at = $iuidlist['date_added'];
                $iuidData->save();
            }
        }
    }

    public function ouids($limit, $offset)
    {
        $OuidList = [];
        $ouid = "SELECT * FROM ouid limit $limit offset $offset";
        $ouidquery = mysqli_query($this->conn, $ouid);
        if (mysqli_num_rows($ouidquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($ouidquery)) {
                $OuidList[$i]['id'] = $result['id'];
                $OuidList[$i]['ouid'] = $result['ouid'];
                $OuidList[$i]['financial_year_id'] = $result['financial_year_id'];
                $OuidList[$i]['name'] = $result['name'];
                $OuidList[$i]['inward_type'] = $result['inward_type'];
                $OuidList[$i]['inward_medium'] = $result['inward_medium'];
                $OuidList[$i]['inward_details'] = $result['inward_details'];
                $OuidList[$i]['company_id'] = $result['company_id'];
                $OuidList[$i]['company_type'] = $result['company_type'];
                $OuidList[$i]['company_person'] = $result['company_person'];
                $OuidList[$i]['generated_by'] = $result['generated_by'];
                $OuidList[$i]['assigned_to'] = $result['assigned_to'];
                $OuidList[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($OuidList)) {
            foreach ($OuidList as $ouidlist) {
                $ouidData = new Ouid();
                $ouidData->id = $ouidlist['id'];
                $ouidData->ouid = $ouidlist['ouid'];
                $ouidData->financial_year_id = $ouidlist['financial_year_id'];
                $ouidData->name = $ouidlist['name'];
                $ouidData->inward_type = $ouidlist['inward_type'];
                $ouidData->inward_medium = $ouidlist['inward_medium'];
                $ouidData->inward_details = $ouidlist['inward_details'];
                $ouidData->company_id = $ouidlist['company_id'];
                $ouidData->company_type = $ouidlist['company_type'];
                $ouidData->company_person = $ouidlist['company_person'];
                $ouidData->generated_by = $ouidlist['generated_by'];
                $ouidData->assigned_to = $ouidlist['assigned_to'];
                $ouidData->created_at = $ouidlist['date_added'];
                $ouidData->save();
            }
        }
    }

    public function outwardAll($limit, $offset)
    {
        $OutwardList = [];
        $OutwardFMList = [];
        $OutwardOrderDetailList = [];
        $OutwardOrderList = [];
        $OutwardproductList = [];
        $OutwardSalebillList = [];

        $outward = "SELECT * FROM outward limit $limit offset $offset";
        $outwardquery = mysqli_query($this->conn, $outward);
        if (mysqli_num_rows($outwardquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($outwardquery)) {
                $OutwardList[$i]['outward_id'] = $result['outward_id'];
                $OutwardList[$i]['ouid'] = $result['ouid'];
                $OutwardList[$i]['outward_ref_via'] = $result['outward_ref_via'];
                $OutwardList[$i]['general_output_ref_id'] = $result['general_output_ref_id'];
                $OutwardList[$i]['new_or_old_outward'] = $result['new_or_old_outward'];
                $OutwardList[$i]['connected_outward'] = $result['connected_outward'];
                $OutwardList[$i]['outward_date'] = $result['outward_date'];
                $OutwardList[$i]['subject'] = $result['subject'];
                $OutwardList[$i]['employee_id'] = $result['employe_id'];
                $OutwardList[$i]['type_of_outward'] = $result['type_of_outward'];
                $OutwardList[$i]['receiver_number'] = $result['receiver_number'];
                $OutwardList[$i]['from_number'] = $result['from_number'];
                $OutwardList[$i]['company_id'] = $result['company_id'];
                $OutwardList[$i]['supplier_id'] = $result['supplier_id'];
                $OutwardList[$i]['courier_name'] = $result['courier_name'];
                $OutwardList[$i]['weight_of_parcel'] = $result['weight_of_parcel'];
                $OutwardList[$i]['courier_receipt_no'] = $result['courier_receipt_no'];
                $OutwardList[$i]['courier_received_time'] = $result['courier_received_time'] == '0000-00-00 00:00:00' ? null : $result['courier_received_time'];
                $OutwardList[$i]['no_of_parcel'] = $result['no_of_parcel'];
                $OutwardList[$i]['from_name'] = $result['from_name'];
                $OutwardList[$i]['attachments'] = json_encode(unserialize($result['attachments']));
                $OutwardList[$i]['remarks'] = $result['remarks'];
                $OutwardList[$i]['latter_by_id'] = $result['latter_by_id'];
                $OutwardList[$i]['delivery_by'] = $result['delivery_by'];
                $OutwardList[$i]['receiver_email_id'] = $result['receiver_email_id'];
                $OutwardList[$i]['from_email_id'] = $result['from_email_id'];
                $OutwardList[$i]['product_main_id'] = $result['product_main_id'];
                $OutwardList[$i]['product_image_id'] = json_encode(unserialize($result['product_image_id']));
                $OutwardList[$i]['outward_link_with_id'] = $result['outward_link_with_id'];
                $OutwardList[$i]['enquiry_complain_for'] = $result['enquiry_complain_for'];
                $OutwardList[$i]['client_remark'] = $result['client_remark'];
                $OutwardList[$i]['notify_client'] = $result['notify_client'];
                $OutwardList[$i]['notify_md'] = $result['notify_md'];
                // $OutwardList[$i]['required_followup'] = $result['required_followup'];
                $OutwardList[$i]['courier_agent'] = $result['courier_agent'];
                $OutwardList[$i]['mark_as_draft'] = $result['mark_as_draft'];
                $OutwardList[$i]['outward_courier_flag'] = $result['outward_courier_flag'];
                $OutwardList[$i]['outward_employee_id'] = $result['outward_employe_id'];
                $OutwardList[$i]['is_deleted'] = $result['is_deleted'];
                $OutwardList[$i]['date_added'] = $result['date_added'] == '0000-00-00 00:00:00' ? null : $result['date_added'];
                $OutwardList[$i]['date_updated'] = $result['date_updated'] == '0000-00-00 00:00:00' ? null : $result['date_updated'];
                $i++;
            }
        }

        if (!empty($OutwardList)) {
            foreach ($OutwardList as $outward) {
                $outwarddata = new Outward();
                $outwarddata->outward_id = $outward['outward_id'];
                $outwarddata->ouid = $outward['ouid'];
                $outwarddata->outward_ref_via = $outward['outward_ref_via'];
                $outwarddata->general_output_ref_id = $outward['general_output_ref_id'];
                $outwarddata->new_or_old_outward = $outward['new_or_old_outward'];
                $outwarddata->connected_outward = $outward['connected_outward'];
                $outwarddata->outward_date = $outward['outward_date'];
                $outwarddata->subject = $outward['subject'];
                $outwarddata->employee_id = $outward['employee_id'];
                $outwarddata->type_of_outward = $outward['type_of_outward'];
                $outwarddata->receiver_number = $outward['receiver_number'];
                $outwarddata->from_number = $outward['from_number'];
                $outwarddata->company_id = $outward['company_id'];
                $outwarddata->supplier_id = $outward['supplier_id'];
                $outwarddata->courier_name = $outward['courier_name'];
                $outwarddata->weight_of_parcel = $outward['weight_of_parcel'];
                $outwarddata->courier_receipt_no = $outward['courier_receipt_no'];
                $outwarddata->courier_received_time = $outward['courier_received_time'];
                $outwarddata->no_of_parcel = $outward['no_of_parcel'];
                $outwarddata->from_name = $outward['from_name'];
                $outwarddata->attachments = $outward['attachments'];
                $outwarddata->remarks = $outward['remarks'];
                $outwarddata->latter_by_id = $outward['latter_by_id'];
                $outwarddata->delivery_by = $outward['delivery_by'];
                $outwarddata->receiver_email_id = $outward['receiver_email_id'];
                $outwarddata->from_email_id = $outward['from_email_id'];
                $outwarddata->product_main_id = $outward['product_main_id'];
                $outwarddata->product_image_id = $outward['product_image_id'];
                $outwarddata->outward_link_with_id = $outward['outward_link_with_id'];
                $outwarddata->enquiry_complain_for = $outward['enquiry_complain_for'];
                $outwarddata->client_remark = $outward['client_remark'];
                $outwarddata->notify_client = $outward['notify_client'];
                $outwarddata->notify_md = $outward['notify_md'];
                // $outwarddata->required_followup = $outward['required_followup'];
                $outwarddata->courier_agent = $outward['courier_agent'];
                $outwarddata->mark_as_draft = $outward['mark_as_draft'];
                $outwarddata->outward_courier_flag = $outward['outward_courier_flag'];
                $outwarddata->outward_employee_id = $outward['outward_employee_id'];
                $outwarddata->is_deleted = $outward['is_deleted'];
                $outwarddata->created_at = $outward['date_added'];
                $outwarddata->updated_at = $outward['date_updated'];
                $outwarddata->save();
            }
        }

        $outwardfremessage = "SELECT * FROM outward_frequent_message limit $limit offset $offset";
        $outwardfremessagequery = mysqli_query($this->conn, $outwardfremessage);
        if (mysqli_num_rows($outwardfremessagequery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($outwardfremessagequery)) {
                $OutwardFMList[$i]['id'] = $result['id'];
                $OutwardFMList[$i]['supplier_id'] = $result['supplier_id'];
                $OutwardFMList[$i]['next_date'] = $result['next_date'];
                $i++;
            }
        }

        if (!empty($OutwardFMList)) {
            foreach ($OutwardFMList as $ofm) {
                $outwardfremessage = new OutwardFrequentMessage();
                $outwardfremessage->id = $ofm['id'];
                $outwardfremessage->supplier_id = $ofm['supplier_id'];
                $outwardfremessage->next_date = $ofm['next_date'];
                $outwardfremessage->save();
            }
        }

        $outwardOrderdetail = "SELECT * FROM outward_order_for_details limit $limit offset $offset";
        $outwardOrderdetailquery = mysqli_query($this->conn, $outwardOrderdetail);
        if (mysqli_num_rows($outwardOrderdetailquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($outwardOrderdetailquery)) {
                $OutwardOrderDetailList[$i]['id'] = $result['outward_order_for_details_id'];
                $OutwardOrderDetailList[$i]['outward_id'] = $result['outward_id'];
                $OutwardOrderDetailList[$i]['order_for'] = $result['order_for'];
                $OutwardOrderDetailList[$i]['packing_id'] = $result['packin_id'];
                $OutwardOrderDetailList[$i]['packing_date'] = ($result['packing_date'] == '0000-00-00') ? null : $result['packing_date'];
                $OutwardOrderDetailList[$i]['lump'] = $result['lump'];
                $OutwardOrderDetailList[$i]['cut'] = $result['cut'];
                $OutwardOrderDetailList[$i]['top'] = $result['top'];
                $OutwardOrderDetailList[$i]['bottom'] = $result['bottom'];
                $OutwardOrderDetailList[$i]['duppatta'] = $result['duppatta'];
                $OutwardOrderDetailList[$i]['is_deleted'] = $result['is_deleted'];
                $i++;
            }
        }

        if (!empty($OutwardOrderDetailList)) {
            foreach ($OutwardOrderDetailList as $ood) {
                $outwardorderdetaildata = new OutwardOrderDetail();
                $outwardorderdetaildata->id = $ood['id'];
                $outwardorderdetaildata->outward_id = $ood['outward_id'];
                $outwardorderdetaildata->order_for = $ood['order_for'];
                $outwardorderdetaildata->packing_id = $ood['packing_id'];
                $outwardorderdetaildata->packing_date = $ood['packing_date'];
                $outwardorderdetaildata->lump = $ood['lump'];
                $outwardorderdetaildata->cut = $ood['cut'];
                $outwardorderdetaildata->top = $ood['top'];
                $outwardorderdetaildata->bottom = $ood['bottom'];
                $outwardorderdetaildata->duppatta = $ood['duppatta'];
                $outwardorderdetaildata->is_deleted = $ood['is_deleted'];
                $outwardorderdetaildata->save();
            }
        }

        $outwardOrder = "SELECT * FROM outward_order limit $limit offset $offset";
        $outwardOrderquery = mysqli_query($this->conn, $outwardOrder);
        if (mysqli_num_rows($outwardOrderquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($outwardOrderquery)) {
                $OutwardOrderList[$i]['id'] = $result['outward_order_id'];
                $OutwardOrderList[$i]['outward_id'] = $result['outward_id'];
                $OutwardOrderList[$i]['product_or_fabric_id'] = $result['product_or_fabric_id'];
                $OutwardOrderList[$i]['sub_product_id'] = $result['sub_product_id'];
                $OutwardOrderList[$i]['shade_no'] = $result['shade_no'];
                $OutwardOrderList[$i]['qty'] = $result['qty'];
                $OutwardOrderList[$i]['rate'] = $result['rate'];
                $OutwardOrderList[$i]['discount'] = $result['discount'];
                $OutwardOrderList[$i]['is_deleted'] = $result['is_deleted'];
                $i++;
            }
        }

        if (!empty($OutwardOrderList)) {
            foreach ($OutwardOrderList as $oo) {
                $outwardorder = new OutwardOrder();
                $outwardorder->id = $oo['id'];
                $outwardorder->outward_id = $oo['outward_id'];
                $outwardorder->product_or_fabric_id = $oo['product_or_fabric_id'];
                $outwardorder->sub_product_id = $oo['sub_product_id'];
                $outwardorder->shade_no = $oo['shade_no'];
                $outwardorder->qty = $oo['qty'];
                $outwardorder->rate = $oo['rate'];
                $outwardorder->discount = $oo['discount'];
                $outwardorder->is_deleted = $oo['is_deleted'];
                $outwardorder->save();
            }
        }

        $outwardproduct = "SELECT * FROM outward_product_or_fabric limit $limit offset $offset";
        $outwardproductquery = mysqli_query($this->conn, $outwardproduct);
        if (mysqli_num_rows($outwardproductquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($outwardproductquery)) {
                $OutwardproductList[$i]['id'] = $result['outward_product_or_fabric_id'];
                $OutwardproductList[$i]['outward_id'] = $result['outward_id'];
                $OutwardproductList[$i]['product_or_fabric_id'] = $result['product_or_fabric_id'];
                $OutwardproductList[$i]['product_or_fabric_flag'] = $result['product_or_fabric_flag'];
                $OutwardproductList[$i]['is_deleted'] = $result['is_deleted'];
                $i++;
            }
        }

        if (!empty($OutwardproductList)) {
            foreach ($OutwardproductList as $op) {
                $outwardproduct = new OutwardProductFabric();
                $outwardproduct->id = $op['id'];
                $outwardproduct->outward_id = $op['outward_id'];
                $outwardproduct->product_or_fabric_id = $op['product_or_fabric_id'];
                $outwardproduct->product_or_fabric_flag = $op['product_or_fabric_flag'];
                $outwardproduct->is_deleted = $op['is_deleted'];
                $outwardproduct->save();
            }
        }

        sleep(3);

        $outwardsalebill = "SELECT outward_sale_bill.*, comboid.financial_year_id as fid from outward_sale_bill inner join comboid ON comboid.inward_or_outward_id = outward_sale_bill.outward_id where comboid.system_module_id IN ('16', '17', '18', '21') AND outward_sale_bill.is_deleted = 0 and comboid.financial_year_id > 2 limit $limit offset $offset";
        $outwardsalebillquery = mysqli_query($this->conn, $outwardsalebill);
        if (mysqli_num_rows($outwardsalebillquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($outwardsalebillquery)) {
                $OutwardSalebillList[$i]['id'] = $result['outward_sale_bill_id'];
                $OutwardSalebillList[$i]['outward_id'] = $result['outward_id'];
                $OutwardSalebillList[$i]['sale_bill_id'] = $result['sale_bill_id'];
                $OutwardSalebillList[$i]['payment_id'] = $result['payment_id'];
                $OutwardSalebillList[$i]['commission_id'] = $result['commission_id'];
                $OutwardSalebillList[$i]['commission_invoice_id'] = $result['commission_invoice_id'];
                $OutwardSalebillList[$i]['is_deleted'] = $result['is_deleted'];
                $OutwardSalebillList[$i]['financial_year_id'] = $result['fid'];
                $i++;
            }
        }

        if (!empty($OutwardSalebillList)) {
            foreach ($OutwardSalebillList as $osalebill) {
                $outwardsalebill = new OutwardSaleBill();
                $outwardsalebill->id = $osalebill['id'];
                $outwardsalebill->outward_id = $osalebill['outward_id'];
                $outwardsalebill->sale_bill_id = $osalebill['sale_bill_id'];
                $outwardsalebill->payment_id = $osalebill['payment_id'];
                $outwardsalebill->commission_id = $osalebill['commission_id'];
                $outwardsalebill->commission_invoice_id = $osalebill['commission_invoice_id'];
                $outwardsalebill->is_deleted = $osalebill['is_deleted'];
                $outwardsalebill->financial_year_id = $osalebill['financial_year_id'];
                $outwardsalebill->save();
            }
        }
    }

    public function payment($limit, $offset)
    {
        $PaymentDetailList = [];
        $PaymentList = [];
        $paymentdetail = "SELECT * FROM payment_details limit $limit offset $offset";
        $paymentdetailquery = mysqli_query($this->conn, $paymentdetail);
        if (mysqli_num_rows($paymentdetailquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($paymentdetailquery)) {
                $PaymentDetailList[$i]['id'] = $result['payment_details_id'];
                $PaymentDetailList[$i]['payment_details_id'] = $result['payment_details_id'];
                $PaymentDetailList[$i]['payment_id'] = $result['payment_id'];
                $PaymentDetailList[$i]['p_increment_id'] = $result['p_increment_id'];
                $PaymentDetailList[$i]['financial_year_id'] = $result['financial_year_id'];
                // $PaymentDetailList[$i]['payment_followup_id'] = $result['payment_followup_id'];
                $PaymentDetailList[$i]['sr_no'] = $result['sr_no'];
                $PaymentDetailList[$i]['supplier_invoice_no'] = $result['supplier_invoice_no'];
                $PaymentDetailList[$i]['amount'] = $result['amount'];
                $PaymentDetailList[$i]['adjust_amount'] = $result['adjust_amount'];
                $PaymentDetailList[$i]['status'] = $result['status'];
                $PaymentDetailList[$i]['discount'] = $result['discount'];
                $PaymentDetailList[$i]['discount_amount'] = $result['discount_amount'];
                $PaymentDetailList[$i]['vatav'] = $result['vatav'];
                $PaymentDetailList[$i]['agent_commission'] = $result['agent_commission'];
                $PaymentDetailList[$i]['bank_commission'] = $result['bank_commission'];
                $PaymentDetailList[$i]['claim'] = $result['claim'];
                $PaymentDetailList[$i]['goods_return'] = $result['goods_return'];
                $PaymentDetailList[$i]['short'] = $result['short'];
                $PaymentDetailList[$i]['interest'] = $result['interest'];
                $PaymentDetailList[$i]['rate_difference'] = $result['rate_difference'];
                $PaymentDetailList[$i]['remark'] = $result['remark'];
                $PaymentDetailList[$i]['flag_sale_bill_sr_no'] = $result['flag_sale_bill_sr_no'];
                $PaymentDetailList[$i]['is_deleted'] = $result['is_deleted'];
                $i++;
            }
        }

        if (!empty($PaymentDetailList)) {
            foreach ($PaymentDetailList as $pddata) {
                $paymentdetail = new PaymentDetail();
                $paymentdetail->id = $pddata['id'];
                $paymentdetail->payment_details_id = $pddata['payment_details_id'];
                $paymentdetail->payment_id = $pddata['payment_id'];
                $paymentdetail->p_increment_id = $pddata['p_increment_id'];
                $paymentdetail->financial_year_id = $pddata['financial_year_id'];
                // $paymentdetail->payment_followup_id = $pddata['payment_followup_id'];
                $paymentdetail->sr_no = $pddata['sr_no'];
                $paymentdetail->supplier_invoice_no = $pddata['supplier_invoice_no'];
                $paymentdetail->amount = $pddata['amount'];
                $paymentdetail->adjust_amount = $pddata['adjust_amount'];
                $paymentdetail->status = $pddata['status'];
                $paymentdetail->discount = $pddata['discount'];
                $paymentdetail->discount_amount = $pddata['discount_amount'];
                $paymentdetail->vatav = $pddata['vatav'];
                $paymentdetail->agent_commission = $pddata['agent_commission'];
                $paymentdetail->bank_commission = $pddata['bank_commission'];
                $paymentdetail->claim = $pddata['claim'];
                $paymentdetail->goods_return = $pddata['goods_return'];
                $paymentdetail->short = $pddata['short'];
                $paymentdetail->interest = $pddata['interest'];
                $paymentdetail->rate_difference = $pddata['rate_difference'];
                $paymentdetail->remark = $pddata['remark'];
                $paymentdetail->flag_sale_bill_sr_no = $pddata['flag_sale_bill_sr_no'];
                $paymentdetail->is_deleted = $pddata['is_deleted'];
                $paymentdetail->save();
            }
        }

        // sleep(3);

        $payment = "SELECT * FROM payment limit $limit offset $offset";
        $paymentquery = mysqli_query($this->conn, $payment);
        if (mysqli_num_rows($paymentquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($paymentquery)) {
                $PaymentList[$i]['id'] = $result['id'];
                $PaymentList[$i]['payment_id'] = $result['payment_id'];
                $PaymentList[$i]['iuid'] = $result['iuid'];
                $PaymentList[$i]['reference_id'] = $result['reference_id'];
                $array = @unserialize(trim($result['attachments']));
                $array = @unserialize(($result['attachments']));
                if (is_array($array) && count($array)) {
                    $PaymentList[$i]['attachments'] = json_encode($array);
                } else {
                    // woops, that didn't appear to be anything serialized
                    $PaymentList[$i]['attachments'] = '[]';
                }
                $PaymentList[$i]['letter_attachment'] = $result['letter_attachment'];
                $PaymentList[$i]['financial_year_id'] = $result['financial_year_id'];
                $PaymentList[$i]['reciept_mode'] = $result['reciept_mode'];
                $PaymentList[$i]['slip_no'] = $result['slip_no'];
                $PaymentList[$i]['date'] = $result['date'] == '0000-00-00' ? null : $result['date'];
                $PaymentList[$i]['deposite_bank'] = $result['deposite_bank'];
                $PaymentList[$i]['cheque_date'] = $result['cheque_date'] == '0000-00-00' ? null : $result['cheque_date'];
                $PaymentList[$i]['cheque_dd_no'] = $result['cheque_dd_no'];
                $PaymentList[$i]['cheque_dd_bank'] = $result['cheque_dd_bank'];
                $PaymentList[$i]['receipt_from'] = $result['receipt_from'];
                $PaymentList[$i]['trns'] = $result['trns'];
                $PaymentList[$i]['supplier_id'] = $result['supplier_id'];
                $PaymentList[$i]['customer_id'] = $result['customer_id'];
                $PaymentList[$i]['receipt_amount'] = $result['receipt_amount'];
                $PaymentList[$i]['total_amount'] = $result['total_amount'];
                $PaymentList[$i]['tot_adjust_amount'] = $result['tot_adjust_amount'];
                $PaymentList[$i]['tot_discount'] = $result['tot_discount'];
                $PaymentList[$i]['tot_vatav'] = $result['tot_vatav'];
                $PaymentList[$i]['tot_agent_commission'] = $result['tot_agent_commission'];
                $PaymentList[$i]['tot_bank_cpmmission'] = $result['tot_bank_cpmmission'];
                $PaymentList[$i]['tot_claim'] = $result['tot_claim'];
                $PaymentList[$i]['tot_good_returns'] = $result['tot_good_returns'];
                $PaymentList[$i]['tot_short'] = $result['tot_short'];
                $PaymentList[$i]['tot_interest'] = $result['tot_interest'];
                $PaymentList[$i]['tot_rate_difference'] = $result['tot_rate_difference'];
                $PaymentList[$i]['payment_ok_or_not'] = $result['payment_ok_or_not'];
                $PaymentList[$i]['old_commission_status'] = $result['old_commission_status'];
                $PaymentList[$i]['customer_commission_status'] = $result['customer_commission_status'];
                $PaymentList[$i]['right_of_amount'] = $result['right_of_amount'];
                $PaymentList[$i]['right_of_remark'] = $result['right_of_remark'];
                $PaymentList[$i]['is_deleted'] = $result['is_deleted'];
                $PaymentList[$i]['done_outward'] = $result['done_outward'];
                $PaymentList[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($PaymentList)) {
            foreach ($PaymentList as $paymentdata) {
                $payment = new Payment();
                $payment->id = $paymentdata['id'];
                $payment->payment_id = $paymentdata['payment_id'];
                $payment->iuid = $paymentdata['iuid'];
                $payment->reference_id = $paymentdata['reference_id'];
                $payment->attachments = $paymentdata['attachments'];
                $payment->letter_attachment = $paymentdata['letter_attachment'];
                $payment->financial_year_id = $paymentdata['financial_year_id'];
                $payment->reciept_mode = $paymentdata['reciept_mode'];
                $payment->slip_no = $paymentdata['slip_no'];
                $payment->date = $paymentdata['date'];
                $payment->deposite_bank = $paymentdata['deposite_bank'];
                $payment->cheque_date = $paymentdata['cheque_date'];
                $payment->cheque_dd_no = $paymentdata['cheque_dd_no'];
                $payment->cheque_dd_bank = $paymentdata['cheque_dd_bank'];
                $payment->receipt_from = $paymentdata['receipt_from'];
                $payment->trns = $paymentdata['trns'];
                $payment->supplier_id = $paymentdata['supplier_id'];
                $payment->customer_id = $paymentdata['customer_id'];
                $payment->receipt_amount = $paymentdata['receipt_amount'];
                $payment->total_amount = $paymentdata['total_amount'];
                $payment->tot_adjust_amount = $paymentdata['tot_adjust_amount'];
                $payment->tot_discount = $paymentdata['tot_discount'];
                $payment->tot_vatav = $paymentdata['tot_vatav'];
                $payment->tot_agent_commission = $paymentdata['tot_agent_commission'];
                $payment->tot_bank_cpmmission = $paymentdata['tot_bank_cpmmission'];
                $payment->tot_claim = $paymentdata['tot_claim'];
                $payment->tot_good_returns = $paymentdata['tot_good_returns'];
                $payment->tot_short = $paymentdata['tot_short'];
                $payment->tot_interest = $paymentdata['tot_interest'];
                $payment->tot_rate_difference = $paymentdata['tot_rate_difference'];
                $payment->payment_ok_or_not = $paymentdata['payment_ok_or_not'];
                $payment->old_commission_status = $paymentdata['old_commission_status'];
                $payment->customer_commission_status = $paymentdata['customer_commission_status'];
                $payment->right_of_amount = $paymentdata['right_of_amount'];
                $payment->right_of_remark = $paymentdata['right_of_remark'];
                $payment->is_deleted = $paymentdata['is_deleted'];
                $payment->done_outward = $paymentdata['done_outward'];
                $payment->created_at = $paymentdata['date_added'];
                $payment->save();
            }
        }
    }

    public function reference($limit, $offset)
    {
        $ReferenceList = [];
        $reference = "SELECT * FROM reference limit $limit offset $offset";
        $referencequery = mysqli_query($this->conn, $reference);
        if (mysqli_num_rows($referencequery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($referencequery)) {
                $ReferenceList[$i]['id'] = $result['id'];
                $ReferenceList[$i]['reference_id'] = $result['reference_id'];
                $ReferenceList[$i]['employee_id'] = $result['employe_id'];
                $ReferenceList[$i]['financial_year_id'] = $result['financial_year_id'];
                $ReferenceList[$i]['inward_or_outward'] = $result['inward_or_outward'];
                $ReferenceList[$i]['type_of_inward'] = $result['type_of_inward'];
                $ReferenceList[$i]['company_id'] = $result['company_id'];
                $ReferenceList[$i]['selection_date'] = $result['selection_date'] == '0000-00-00' ? null : $result['selection_date'];
                $ReferenceList[$i]['from_name'] = $result['from_name'];
                $ReferenceList[$i]['from_number'] = $result['from_number'];
                $ReferenceList[$i]['receiver_number'] = $result['receiver_number'];
                $ReferenceList[$i]['from_email_id'] = $result['from_email_id'];
                $ReferenceList[$i]['receiver_email_id'] = $result['receiver_email_id'];
                $ReferenceList[$i]['latter_by_id'] = $result['latter_by_id'];
                $ReferenceList[$i]['courier_name'] = $result['courier_name'];
                $ReferenceList[$i]['weight_of_parcel'] = $result['weight_of_parcel'];
                $ReferenceList[$i]['courier_receipt_no'] = $result['courier_receipt_no'];
                $ReferenceList[$i]['courier_received_time'] = $result['courier_received_time'] == '0000-00-00 00:00:00' ? null : $result['courier_received_time'];
                $ReferenceList[$i]['delivery_by'] = $result['delivery_by'];
                $ReferenceList[$i]['mark_as_sample'] = $result['mark_as_sample'];
                $ReferenceList[$i]['gmail_mail_id'] = $result['gmail_mail_id'];
                $ReferenceList[$i]['gmail_folder_name'] = $result['gmail_folder_name'];
                $ReferenceList[$i]['is_deleted'] = $result['is_deleted'];
                $ReferenceList[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($ReferenceList)) {
            foreach ($ReferenceList as $referenceData) {
                $referenceid = new ReferenceId();
                $referenceid->id = $referenceData['id'];
                $referenceid->reference_id = $referenceData['reference_id'];
                $referenceid->employee_id = $referenceData['employee_id'];
                $referenceid->financial_year_id = $referenceData['financial_year_id'];
                $referenceid->inward_or_outward = $referenceData['inward_or_outward'];
                $referenceid->type_of_inward = $referenceData['type_of_inward'];
                $referenceid->company_id = $referenceData['company_id'];
                $referenceid->selection_date = $referenceData['selection_date'];
                $referenceid->from_name = $referenceData['from_name'];
                $referenceid->from_number = $referenceData['from_number'];
                $referenceid->receiver_number = $referenceData['receiver_number'];
                $referenceid->from_email_id = $referenceData['from_email_id'];
                $referenceid->receiver_email_id = $referenceData['receiver_email_id'];
                $referenceid->latter_by_id = $referenceData['latter_by_id'];
                $referenceid->courier_name = $referenceData['courier_name'];
                $referenceid->weight_of_parcel = $referenceData['weight_of_parcel'];
                $referenceid->courier_receipt_no = $referenceData['courier_receipt_no'];
                $referenceid->courier_received_time = $referenceData['courier_received_time'];
                $referenceid->delivery_by = $referenceData['delivery_by'];
                $referenceid->mark_as_sample = $referenceData['mark_as_sample'];
                $referenceid->gmail_mail_id = $referenceData['gmail_mail_id'];
                $referenceid->gmail_folder_name = $referenceData['gmail_folder_name'];
                $referenceid->is_deleted = $referenceData['is_deleted'];
                $referenceid->created_at = $referenceData['date_added'];
                $referenceid->save();
            }
        }
    }

    public function salebills($limit, $offset)
    {
        $SalebillItemList = [];
        $SalebillTransportList = [];
        $SalebillList = [];
        $salebill = "SELECT * FROM sale_bill limit $limit offset $offset";
        $salebillquery = mysqli_query($this->conn, $salebill);
        if (mysqli_num_rows($salebillquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($salebillquery)) {
                if (!empty($result['product_sub_category_id'])) {
                    $prodSubCat = unserialize($result['product_sub_category_id']);
                    if ($prodSubCat) {
                        $result['product_sub_category_id'] = json_encode($prodSubCat);
                    } else {
                        $result['product_sub_category_id'] = json_encode([]);
                    }
                } else {
                    $result['product_sub_category_id'] = json_encode([]);
                }
                $SalebillList[$i]['id'] = $result['id'];
                $SalebillList[$i]['sale_bill_id'] = $result['sale_bill_id'];
                $SalebillList[$i]['iuid'] = $result['iuid'];
                $SalebillList[$i]['sale_bill_via'] = $result['sale_bill_via'];
                $SalebillList[$i]['attachment'] = $result['attachments'];
                $SalebillList[$i]['financial_year_id'] = $result['financial_year_id'];
                $SalebillList[$i]['general_ref_id'] = $result['general_ref_id'];
                $SalebillList[$i]['new_or_old_reference'] = $result['new_or_old_reference'];
                $SalebillList[$i]['sale_bill_for'] = $result['sale_bill_for'];
                $SalebillList[$i]['product_default_category_id'] = $result['product_main_category_id'];
                $SalebillList[$i]['product_category_id'] = $result['product_sub_category_id'];
                $SalebillList[$i]['inward_id'] = $result['inward_id'];
                $SalebillList[$i]['company_id'] = $result['company_id'];
                $SalebillList[$i]['address'] = $result['address'];
                $SalebillList[$i]['supplier_id'] = $result['supplier_id'];
                $SalebillList[$i]['agent_id'] = $result['agent_id'];
                $SalebillList[$i]['supplier_invoice_no'] = $result['supplier_invoice_no'];
                $SalebillList[$i]['select_date'] = $result['select_date'] == '0000-00-00' ? null : $result['select_date'];
                $SalebillList[$i]['change_in_amount'] = $result['change_in_amount'];
                $SalebillList[$i]['sign_change'] = $result['sign_change'];
                $SalebillList[$i]['total'] = $result['total'];
                $SalebillList[$i]['total_peices'] = $result['total_peices'] ? $result['total_peices'] : 0;
                $SalebillList[$i]['total_meters'] = $result['total_meters'] ? $result['total_meters'] : 0;
                $SalebillList[$i]['remark'] = $result['remark'];
                $SalebillList[$i]['sale_bill_flag'] = $result['sale_bill_flag'];
                $SalebillList[$i]['done_outward'] = $result['done_outward'];
                $SalebillList[$i]['is_copied'] = $result['is_copied'];
                $SalebillList[$i]['is_moved'] = $result['is_moved'];
                $SalebillList[$i]['inward_main_or_sub_id'] = $result['inward_main_or_sub_id'];
                $SalebillList[$i]['inward_action_id'] = $result['inward_action_id'];
                $SalebillList[$i]['payment_status'] = $result['payment_status'];
                $SalebillList[$i]['received_payment'] = $result['received_payment'];
                $SalebillList[$i]['pending_payment'] = $result['pending_payment'];
                $SalebillList[$i]['is_deleted'] = $result['is_deleted'];
                $SalebillList[$i]['date_added'] = $result['date_added'];
                $SalebillList[$i]['date_updated'] = $result['date_updated'];
                $i++;
            }
        }

        if (!empty($SalebillList)) {
            foreach ($SalebillList as $salebill) {
                $salebilldata = new SaleBill();
                $salebilldata->id = $salebill['id'];
                $salebilldata->sale_bill_id = $salebill['sale_bill_id'];
                $salebilldata->iuid = $salebill['iuid'];
                $salebilldata->sale_bill_via = $salebill['sale_bill_via'];
                $salebilldata->attachment = $salebill['attachment'];
                $salebilldata->financial_year_id = $salebill['financial_year_id'];
                $salebilldata->general_ref_id = $salebill['general_ref_id'];
                $salebilldata->new_or_old_reference = $salebill['new_or_old_reference'];
                $salebilldata->sale_bill_for = $salebill['sale_bill_for'];
                $salebilldata->product_default_category_id = $salebill['product_default_category_id'];
                $salebilldata->product_category_id = $salebill['product_category_id'];
                $salebilldata->inward_id = $salebill['inward_id'];
                $salebilldata->company_id = $salebill['company_id'];
                $salebilldata->address = $salebill['address'];
                $salebilldata->supplier_id = $salebill['supplier_id'];
                $salebilldata->agent_id = $salebill['agent_id'];
                $salebilldata->supplier_invoice_no = $salebill['supplier_invoice_no'];
                $salebilldata->select_date = $salebill['select_date'];
                $salebilldata->change_in_amount = $salebill['change_in_amount'];
                $salebilldata->sign_change = $salebill['sign_change'];
                $salebilldata->total = $salebill['total'];
                $salebilldata->total_peices = $salebill['total_peices'];
                $salebilldata->total_meters = $salebill['total_meters'];
                $salebilldata->remark = $salebill['remark'];
                $salebilldata->sale_bill_flag = $salebill['sale_bill_flag'];
                $salebilldata->done_outward = $salebill['done_outward'];
                $salebilldata->is_copied = $salebill['is_copied'];
                $salebilldata->is_moved = $salebill['is_moved'];
                $salebilldata->inward_main_or_sub_id = $salebill['inward_main_or_sub_id'];
                $salebilldata->inward_action_id = $salebill['inward_action_id'];
                $salebilldata->payment_status = $salebill['payment_status'];
                $salebilldata->received_payment = $salebill['received_payment'];
                $salebilldata->pending_payment = $salebill['pending_payment'];
                $salebilldata->is_deleted = $salebill['is_deleted'];
                $salebilldata->created_at = $salebill['date_added'];
                $salebilldata->updated_at = $salebill['date_updated'];
                $salebilldata->save();
            }
        }

        // sleep(3);

        $salebillitem = "SELECT * FROM sale_bill_item limit $limit offset $offset";
        $salebillitemquery = mysqli_query($this->conn, $salebillitem);
        if (mysqli_num_rows($salebillitemquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($salebillitemquery)) {
                $SalebillItemList[$i]['id'] = $result['sale_bill_item_id'];
                $SalebillItemList[$i]['sale_bill_id'] = $result['sale_bill_id'];
                $SalebillItemList[$i]['financial_year_id'] = $result['financial_year_id'];
                $SalebillItemList[$i]['product_or_fabric_id'] = $result['product_or_fabric_id'];
                $SalebillItemList[$i]['sub_product_id'] = $result['sub_product_id'];
                $SalebillItemList[$i]['pieces'] = $result['peices'] ? $result['peices'] : 0;
                $SalebillItemList[$i]['cut'] = $result['cut'] ? $result['cut'] : 0;
                $SalebillItemList[$i]['meters'] = $result['meters'] ? $result['meters'] : 0;
                $SalebillItemList[$i]['pieces_meters'] = $result['peices_meters'] ? $result['peices_meters'] : 0;
                $SalebillItemList[$i]['rate'] = $result['rate'] ? $result['rate'] : 0;
                $SalebillItemList[$i]['hsn_code'] = $result['hsn_code'];
                $SalebillItemList[$i]['discount'] = $result['discount'] ? $result['discount'] : 0;
                $SalebillItemList[$i]['discount_amount'] = $result['discount_amount'] ? $result['discount_amount'] : 0;
                $SalebillItemList[$i]['cgst'] = $result['cgst'] ? $result['cgst'] : 0;
                $SalebillItemList[$i]['cgst_amount'] = $result['cgst_amount'] ? $result['cgst_amount'] : 0;
                $SalebillItemList[$i]['sgst'] = $result['sgst'] ? $result['sgst'] : 0;
                $SalebillItemList[$i]['sgst_amount'] = $result['sgst_amount'] ? $result['sgst_amount'] : 0;
                $SalebillItemList[$i]['igst'] = $result['igst'] ? $result['igst'] : 0;
                $SalebillItemList[$i]['igst_amount'] = $result['igst_amount'] ? $result['igst_amount'] : 0;
                $SalebillItemList[$i]['amount'] = $result['amount'] ? $result['amount'] : 0;
                $SalebillItemList[$i]['main_or_sub'] = $result['main_or_sub'];
                $SalebillItemList[$i]['inward_order_action_id'] = $result['inward_order_action_id'];
                $SalebillItemList[$i]['is_deleted'] = $result['is_deleted'];

                $i++;
            }
        }

        if (!empty($SalebillItemList)) {
            foreach ($SalebillItemList as $sbitem) {
                $salebillitemdata = new SaleBillItem();
                $salebillitemdata->id = $sbitem['id'];
                $salebillitemdata->sale_bill_id = $sbitem['sale_bill_id'];
                $salebillitemdata->financial_year_id = $sbitem['financial_year_id'];
                $salebillitemdata->product_or_fabric_id = $sbitem['product_or_fabric_id'];
                $salebillitemdata->sub_product_id = $sbitem['sub_product_id'];
                $salebillitemdata->pieces = $sbitem['pieces'];
                $salebillitemdata->cut = $sbitem['cut'];
                $salebillitemdata->meters = $sbitem['meters'];
                $salebillitemdata->pieces_meters = $sbitem['pieces_meters'];
                $salebillitemdata->rate = $sbitem['rate'];
                $salebillitemdata->hsn_code = $sbitem['hsn_code'];
                $salebillitemdata->discount = $sbitem['discount'];
                $salebillitemdata->discount_amount = $sbitem['discount_amount'];
                $salebillitemdata->cgst = $sbitem['cgst'];
                $salebillitemdata->cgst_amount = $sbitem['cgst_amount'];
                $salebillitemdata->sgst = $sbitem['sgst'];
                $salebillitemdata->sgst_amount = $sbitem['sgst_amount'];
                $salebillitemdata->igst = $sbitem['igst'];
                $salebillitemdata->igst_amount = $sbitem['igst_amount'];
                $salebillitemdata->amount = $sbitem['amount'];
                $salebillitemdata->main_or_sub = $sbitem['main_or_sub'];
                $salebillitemdata->inward_order_action_id = $sbitem['inward_order_action_id'];
                $salebillitemdata->is_deleted = $sbitem['is_deleted'];
                $salebillitemdata->save();
            }
        }

        // sleep(3);

        $salebilltransport = "SELECT * FROM sale_bill_transport limit $limit offset $offset";
        $salebilltransportquery = mysqli_query($this->conn, $salebilltransport);
        if (mysqli_num_rows($salebilltransportquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($salebilltransportquery)) {
                $SalebillTransportList[$i]['id'] = $result['sale_bill_transport_id'];
                $SalebillTransportList[$i]['sale_bill_id'] = $result['sale_bill_id'];
                $SalebillTransportList[$i]['financial_year_id'] = $result['financial_year_id'];
                $SalebillTransportList[$i]['transport_id'] = $result['transport_id'];
                $SalebillTransportList[$i]['station'] = $result['station'];
                $SalebillTransportList[$i]['lr_mr_no'] = $result['lr_mr_no'];
                $SalebillTransportList[$i]['date'] = $result['date'] == '0000-00-00' ? null : $result['date'];
                $SalebillTransportList[$i]['cases'] = $result['cases'];
                $SalebillTransportList[$i]['weight'] = empty($result['weight']) ? 0 : $result['weight'];
                $SalebillTransportList[$i]['freight'] = empty($result['freight']) ? 0 : $result['freight'];
                $SalebillTransportList[$i]['is_deleted'] = $result['is_deleted'];
                $i++;
            }
        }

        if (!empty($SalebillTransportList)) {
            foreach ($SalebillTransportList as $sbtransport) {
                $salebilltransportData = new SaleBillTransport();
                $salebilltransportData->id = $sbtransport['id'];
                $salebilltransportData->sale_bill_id = $sbtransport['sale_bill_id'];
                $salebilltransportData->financial_year_id = $sbtransport['financial_year_id'];
                $salebilltransportData->transport_id = $sbtransport['transport_id'];
                $salebilltransportData->station = $sbtransport['station'];
                $salebilltransportData->lr_mr_no = $sbtransport['lr_mr_no'];
                $salebilltransportData->date = $sbtransport['date'];
                $salebilltransportData->cases = $sbtransport['cases'];
                $salebilltransportData->weight = $sbtransport['weight'];
                $salebilltransportData->freight = $sbtransport['freight'];
                $salebilltransportData->is_deleted = $sbtransport['is_deleted'];
                $salebilltransportData->save();
            }
        }
    }

    public function transport()
    {
        $transports = [];
        $typeOfAddressData = [];
        $transport = "SELECT * FROM transport";
        $transportquery = mysqli_query($this->conn, $transport);
        if (mysqli_num_rows($transportquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($transportquery)) {
                $TransportList[$i]['id'] = $result['transport_id'];
                $TransportList[$i]['name'] = $result['transport_name'];
                $TransportList[$i]['gstin'] = $result['gstin'];
                $i++;
            }
        }

        if (!empty($TransportList)) {
            DB::table('transport_details')->truncate();
            DB::table('transport_multiple_address_details')->truncate();
            foreach ($TransportList as $transport) {
                $transportDetails = new TransportDetails;
                $transportDetails->id = $transport['id'];
                $transportDetails->name = $transport['name'];
                $transportDetails->gstin = $transport['gstin'];
                $transportDetails->save();
            }
            $transportMultipleAddressDetails = new TransportMultipleAddressDetails;
            $transportMultipleAddressDetails->id = 1;
            $transportMultipleAddressDetails->transport_details = 248;
            $transportMultipleAddressDetails->contact_person_name = '';
            $transportMultipleAddressDetails->contact_person_address = 'kalbadevi mumbai';
            $transportMultipleAddressDetails->contact_person_office_no = '02222073928';
            $transportMultipleAddressDetails->contact_person_email = '';
            $transportMultipleAddressDetails->save();
        }

        $toasql = "SELECT * FROM type_of_address";
        $toaquery = mysqli_query($this->conn, $toasql);
        if (mysqli_num_rows($toaquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($toaquery)) {
                $typeOfAddressData[$i]['id'] = $result['type_of_address_id'];
                $typeOfAddressData[$i]['name'] = $result['name'];
                $typeOfAddressData[$i]['sort_order'] = $result['sort_order'];
                $i++;
            }
        }

        if (!empty($typeOfAddressData)) {
            DB::table('type_of_addresses')->truncate();
            foreach ($typeOfAddressData as $typeOfAddresses) {
                $typeOfAddress = new TypeOfAddress;
                $typeOfAddress->id = $typeOfAddresses['id'];
                $typeOfAddress->name = $typeOfAddresses['name'];
                $typeOfAddress->sort_order = $typeOfAddresses['sort_order'];
                $typeOfAddress->save();
            }
        }
    }

    public function userLog($limit, $offset)
    {
        $toasql = "SELECT * FROM user_log limit $limit offset $offset";
        $toaquery = mysqli_query($this->conn, $toasql);
        if (mysqli_num_rows($toaquery) != 0) {
            $i = 0;
            while ($result = mysqli_fetch_assoc($toaquery)) {
                $log[$i]['user_log_id'] = $result['user_log_id'];
                $log[$i]['log_path'] = $result['log_path'];
                $log[$i]['log_subject'] = $result['log_subject'];
                $log[$i]['employe_id'] = $result['employe_id'];
                $log[$i]['log_url'] = $result['log_url'];
                $log[$i]['date_added'] = $result['date_added'];
                $i++;
            }
        }

        if (!empty($log)) {
            DB::table('type_of_addresses')->truncate();
            foreach ($log as $l) {
                $lg = new Logs;
                $lg->id = $l['user_log_id'];
                $lg->log_path = $l['log_path'];
                $lg->log_subject = $l['log_subject'];
                $lg->employee_id = $l['employe_id'];
                $lg->log_url = $l['log_url'];
                $lg->created_at = $l['date_added'];
                $lg->save();
            }
        }
    }

    public function index(Request $request) {
        $employeeData = [];
        $cityList = [];
        $CountryList = [];
        $agentData = [];
        $designationData = [];
        $ECRList = [];
        $FinancialYearList = [];
        $ProductDefaultCatagoriesList = [];
        $SalebillAgentList = [];
        $Statelist = [];

        $servername = "localhost";
        $username = "root";
        $password = "";
        // $servername = "139.59.23.41";
        // $username = "new_la";
        // $password = "LA@remote2022";
        $database = "laoffline";
        // $username = "akashs_laoffline123";
        // $password = "laoffline123";
        // $database = "akashs_laoffline";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $database);
        mysqli_set_charset($conn,'utf8');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            echo "Connected successfully";
            die;
            echo "<br><pre>";

            /* $salebillagent = "SELECT * FROM sale_bill_agent";
            $salebillagentquery = mysqli_query($conn, $salebillagent);
            if(mysqli_num_rows($salebillagentquery) != 0) {
                $i = 0;
                while($result = mysqli_fetch_assoc($salebillagentquery)) {
                    $SalebillAgentList[$i]['id'] = $result['agent_id'];
                    $SalebillAgentList[$i]['name'] = $result['name'];
                    $SalebillAgentList[$i]['is_defult'] = $result['is_default'];
                    $i++;
                }
            }

            if(!empty($SalebillAgentList)) {
                DB::table('sale_bill_agents')->truncate();
                foreach($SalebillAgentList as $sba) {
                    $SaleBillAgent = new SaleBillAgent();
                    $SaleBillAgent->id = $sba['id'];
                    $SaleBillAgent->name = $sba['name'];
                    $SaleBillAgent->default = 0;
                    $SaleBillAgent->save();
                }
            } */

            // NOT NEEDED
            // $linkcompanylog = "SELECT * FROM link_company_log";
            // $linkcompanylogquery = mysqli_query($conn, $linkcompanylog);
            // if(mysqli_num_rows($linkcompanylogquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($linkcompanylogquery)) {
            //         $LinkCompanyLogList[$i]['id'] = $result['id'];
            //         $LinkCompanyLogList[$i]['company_id'] = $result['company_id'];
            //         $LinkCompanyLogList[$i]['subject'] = $result['subject'];
            //         $i++;
            //     }
            // }

            /* $productdefaultcatagories = "SELECT * FROM product_default_category";
            $productdefaultcatagoriesquery = mysqli_query($conn, $productdefaultcatagories);
            if(mysqli_num_rows($productdefaultcatagoriesquery) != 0) {
                $i = 0;
                while($result = mysqli_fetch_assoc($productdefaultcatagoriesquery)) {
                    $ProductDefaultCatagoriesList[$i]['id'] = $result['product_default_category_id'];
                    $ProductDefaultCatagoriesList[$i]['name'] = $result['name'];
                    $i++;
                }
            }

            if(!empty($ProductDefaultCatagoriesList)) {
                foreach($ProductDefaultCatagoriesList as $pdc) {
                    $productdefaultcatagoriesdata = new ProductDefaultCategory();
                    $productdefaultcatagoriesdata->id = $pdc['id'];
                    $productdefaultcatagoriesdata->name = $pdc['name'];
                    $productdefaultcatagoriesdata->save();
                }
            } */

            // $state = "SELECT * FROM state";
            // $statequery = mysqli_query($conn, $state);
            // if(mysqli_num_rows($statequery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($statequery)) {
            //         $Statelist[$i]['id'] = $result['state_id'];
            //         $Statelist[$i]['country_id'] = $result['country_id'];
            //         $Statelist[$i]['name'] = $result['name'];
            //         $i++;
            //     }
            // }

            // $country = "SELECT * FROM countries";
            // $countryquery = mysqli_query($conn, $country);
            // if(mysqli_num_rows($countryquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($countryquery)) {
            //         $CountryList[$i]['id'] = $result['country_id'];
            //         $CountryList[$i]['country_code'] = $result['country_code'];
            //         $CountryList[$i]['name'] = $result['name'];
            //         $i++;
            //     }
            // }

            // $financialyear = "SELECT * FROM financial_year";
            // $fyquery = mysqli_query($conn, $financialyear);
            // if(mysqli_num_rows($fyquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($fyquery)) {
            //         $FinancialYearList[$i]['id'] = $result['financial_year_id'];
            //         $FinancialYearList[$i]['name'] = $result['name'];
            //         $FinancialYearList[$i]['start_date'] = $result['start_date'];
            //         $FinancialYearList[$i]['end_date'] = $result['end_date'];
            //         $FinancialYearList[$i]['current_year_flag'] = $result['current_year_flag'];
            //         $FinancialYearList[$i]['inv_prefix'] = $result['inv_prefix'];
            //         $i++;
            //     }
            // }

            // $ecr = "SELECT * FROM enjay_call_records";
            // $ecrquery = mysqli_query($conn, $ecr);
            // if(mysqli_num_rows($ecrquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($ecrquery)) {
            //         $ECRList[$i]['id'] = $result['enjay_call_records_id'];
            //         $ECRList[$i]['reference_id'] = $result['reference_id'];
            //         $ECRList[$i]['asteriskhost'] = $result['asteriskhost'];
            //         $ECRList[$i]['event'] = $result['event'];
            //         $ECRList[$i]['direction'] = $result['direction'];
            //         $ECRList[$i]['number'] = $result['number'];
            //         $ECRList[$i]['extension'] = $result['extension'];
            //         $ECRList[$i]['redirectchannel'] = $result['redirectchannel'];
            //         $ECRList[$i]['uniqueid'] = $result['uniqueid'];
            //         $ECRList[$i]['starttime'] = $result['starttime'];
            //         $ECRList[$i]['answertime'] = $result['answertime'];
            //         $ECRList[$i]['endtime'] = $result['endtime'];
            //         $ECRList[$i]['duration'] = $result['duration'];
            //         $ECRList[$i]['billableseconds'] = $result['billableseconds'];
            //         $ECRList[$i]['disposition'] = $result['disposition'];
            //         $ECRList[$i]['recordlink'] = $result['recordlink'];
            //         $ECRList[$i]['enjay_flag'] = $result['enjay_flag'];
            //         $i++;
            //     }
            // }

            /* $companytype = "SELECT * FROM company_type";
            $companytypequery = mysqli_query($conn, $companytype);
            if(mysqli_num_rows($companytypequery) != 0) {
                $i = 0;
                while($result = mysqli_fetch_assoc($companytypequery)) {
                    $companyTypeList[$i]['id'] = $result['company_type_id'];
                    $companyTypeList[$i]['name'] = $result['name'];
                    $i++;
                }
            }

            if(!empty($companyTypeList)) {
                foreach($companyTypeList as $ct) {
                    $companytypedata = new CompanyType();
                    $companytypedata->id = $ct['id'];
                    $companytypedata->name = $ct['name'];
                    $companytypedata->save();
                }
            } */

            // $emp = "SELECT * FROM employe";
            // $equery = mysqli_query($conn, $emp);
            // if(mysqli_num_rows($equery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($equery)) {
            //         // print_r($result);
            //         $employeeData[$i]['id'] = $result['employe_id'];
            //         $employeeData[$i]['firstname'] = $result['firstname'];
            //         $employeeData[$i]['middlename'] = $result['middlename'];
            //         $employeeData[$i]['lastname'] = $result['lastname'];
            //         $employeeData[$i]['profile_pic'] = $result['profile_pic'];
            //         $employeeData[$i]['email_id'] = $result['email_id'];
            //         $employeeData[$i]['username'] = $result['username'];
            //         $employeeData[$i]['password'] = $result['password'];
            //         $employeeData[$i]['mobile'] = $result['mobile'];
            //         $employeeData[$i]['address'] = $result['address'];
            //         $employeeData[$i]['user_group'] = $result['user_group_id'];
            //         $employeeData[$i]['excel_access'] = $result['excel_access'];
            //         $employeeData[$i]['id_proof'] = $result['id_proof'];
            //         $employeeData[$i]['ref_full_name'] = $result['ref_name'];
            //         $employeeData[$i]['ref_pass_pic'] = $result['ref_profile_pic'];
            //         $employeeData[$i]['ref_mobile'] = $result['ref_mobile'];
            //         $employeeData[$i]['ref_address'] = $result['ref_address'];
            //         $employeeData[$i]['is_active'] = $result['is_active'];
            //         $i++;
            //     }
            // }

            // $csql = "SELECT * FROM cities";
            // $cquery = mysqli_query($conn, $csql);

            // if(mysqli_num_rows($cquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($cquery)) {
            //         $cityList[$i]['id'] = $result['city_id'];
            //         $cityList[$i]['name'] = $result['city_name'];
            //         $cityList[$i]['std_code'] = $result['city_code'];
            //         $cityList[$i]['country'] = $result['country_id'];
            //         if($result['city_state'] == NULL) {
            //             $cityList[$i]['state'] = 0;
            //         } else {
            //             $cityList[$i]['state'] = $result['city_state'];
            //         }
            //         $i++;
            //     }
            // }

            /* $asql = "SELECT * FROM agent";
            $aquery = mysqli_query($conn, $asql);
            if(mysqli_num_rows($aquery) != 0) {
                $i = 0;
                while($result = mysqli_fetch_assoc($aquery)) {
                    $agentData[$i]['id'] = $result['agent_id'];
                    $agentData[$i]['name'] = $result['name'];
                    $agentData[$i]['pan_no'] = $result['pan_no'];
                    $agentData[$i]['gst_no'] = $result['gst_no'];
                    $agentData[$i]['include_tax'] = $result['include_tax'];
                    $agentData[$i]['default'] = $result['is_default'];
                    $agentData[$i]['inv_prefix'] = $result['inv_prefix'];
                    $i++;
                }
            }

            if(!empty($agentData)) {
                foreach($agentData as $agents) {
                    $agent = new Agent;
                    $agent->id = $agents['id'];
                    $agent->name = $agents['name'];
                    $agent->pan_no = $agents['pan_no'];
                    $agent->gst_no = $agents['gst_no'];
                    $agent->include_tax = $agents['include_tax'];
                    $agent->default = $agents['default'];
                    $agent->inv_prefix = $agents['inv_prefix'];
                    $agent->save();
                }
            } */

            /* $dsql = "SELECT * FROM designation";
            $dquery = mysqli_query($conn, $dsql);
            if(mysqli_num_rows($dquery) != 0) {
                $i = 0;
                while($result = mysqli_fetch_assoc($dquery)) {
                    $designationData[$i]['id'] = $result['designation_id'];
                    $designationData[$i]['name'] = $result['designation_name'];
                    $i++;
                }
            }

            if(!empty($designationData)) {
                foreach($designationData as $designations) {
                    $designation = new Designation;
                    $designation->id = $designations['id'];
                    $designation->name = $designations['name'];
                    $designation->save();
                }
            } */

        }

        // if(!empty($employeeData)) {
        //     foreach($employeeData as $employees) {
        //         $employee = new Employee;
        //         $employee->id = $employees['id'];
        //         $employee->firstname = $employees['firstname'];
        //         $employee->middlename = $employees['middlename'];
        //         $employee->lastname = $employees['lastname'];
        //         $employee->profile_pic = $employees['profile_pic'];
        //         $employee->email_id = $employees['email_id'];
        //         $employee->mobile = $employees['mobile'];
        //         $employee->address = $employees['address'];
        //         $employee->user_group = $employees['user_group'];
        //         $employee->excel_access = $employees['excel_access'];
        //         $employee->id_proof = $employees['id_proof'];
        //         $employee->ref_full_name = $employees['ref_full_name'];
        //         $employee->ref_pass_pic = $employees['ref_pass_pic'];
        //         $employee->ref_mobile = $employees['ref_mobile'];
        //         $employee->ref_address = $employees['ref_address'];
        //         $employee->web_login = '';
        //         $employee->save();

        //         $userId = User::orderBy('id', 'DESC')->first('id');
        //         $usrId = !empty($userId) ? $userId->id + 1 : 1;

        //         $user = new User;
        //         $user->id = $usrId;
        //         $user->employee_id = $employee['id'];
        //         $user->username = $employees['username'];
        //         $user->password = $employees['password'];
        //         $user->is_active = $employees['is_active'];
        //         $user->save();

        //         $userGroupData = UserGroup::where('id', $employees['user_group'])->first();

        //         $role = Role::where('id', $userGroupData['roles_id'])->first();

        //         $user->assignRole($role);
        //     }
        // }

        // if(!empty($cityList)) {
        //     foreach($cityList as $city) {
        //         $cities = new Cities;
        //         $cities->id = $city['id'];
        //         $cities->name = $city['name'];
        //         $cities->std_code = $city['std_code'];
        //         $cities->country = $city['country'];
        //         $cities->state = $city['state'];
        //         $cities->save();
        //     }
        // }

        // if(!empty($Statelist)) {
        //     foreach($Statelist as $stateData) {
        //         $states = new State();
        //         $states->id = $stateData['id'];
        //         $states->country_id = $stateData['country_id'];
        //         $states->name = $stateData['name'];
        //         $states->save();
        //     }
        // }

        // if(!empty($LinkCompanyLogList)) {
        //     foreach($LinkCompanyLogList as $lcl) {
        //         $linkcompanylog = new linkCompaniesLog();
        //         $linkcompanylog->id = $lcl['id'];
        //         $linkcompanylog->company_id = $lcl['company_id'];
        //         $linkcompanylog->subject = $lcl['subject'];
        //         $linkcompanylog->save();
        //     }
        // }

        // if(!empty($FinancialYearList)) {
        //     foreach($FinancialYearList as $fyl) {
        //         $fy = new FinancialYear();
        //         $fy->id = $fyl['id'];
        //         $fy->name = $fyl['name'];
        //         $fy->start_date = $fyl['start_date'];
        //         $fy->end_date = $fyl['end_date'];
        //         $fy->current_year_flag = $fyl['current_year_flag'];
        //         $fy->inv_prefix = $fyl['inv_prefix'];
        //         $fy->save();
        //     }
        // }

        // if(!empty($ECRList)) {
        //     foreach($ECRList as $ecrdata) {
        //         $ecr = new EnjayCallRecordsId();
        //         $ecr->id = $ecrdata['id'];
        //         $ecr->reference_id = $ecrdata['reference_id'];
        //         $ecr->asteriskhost = $ecrdata['asteriskhost'];
        //         $ecr->event = $ecrdata['event'];
        //         $ecr->direction = $ecrdata['direction'];
        //         $ecr->number = $ecrdata['number'];
        //         $ecr->extension = $ecrdata['extension'];
        //         $ecr->redirectchannel = $ecrdata['redirectchannel'];
        //         $ecr->uniqueid = $ecrdata['uniqueid'];
        //         $ecr->starttime = $ecrdata['starttime'];
        //         $ecr->answertime = $ecrdata['answertime'];
        //         $ecr->endtime = $ecrdata['endtime'];
        //         $ecr->duration = $ecrdata['duration'];
        //         $ecr->billableseconds = $ecrdata['billableseconds'];
        //         $ecr->disposition = $ecrdata['disposition'];
        //         $ecr->recordlink = $ecrdata['recordlink'];
        //         $ecr->enjay_flag = $ecrdata['enjay_flag'];
        //         $ecr->save();
        //     }
        // }

        // if(!empty($CountryList)) {
        //     foreach($CountryList as $c) {
        //         $country = new Country();
        //         $country->id = $c['id'];
        //         $country->name = $c['name'];
        //         $country->country_code = $c['country_code'];
        //         $country->save();
        //     }
        // }

        print_r("SUCCESsssS");
    }
}
