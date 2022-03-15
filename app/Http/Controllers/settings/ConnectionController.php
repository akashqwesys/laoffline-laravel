<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserGroup;
use App\Models\Employee;
use App\Models\User;
use App\Models\Settings\Cities;
use App\Models\ProductCategory;
use App\Models\Settings\TransportDetails;
use App\Models\Settings\TransportMultipleAddressDetails;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\ProductsImages;
use App\Models\ProductFabricDetails;
use App\Models\Company\Company;
use App\Models\Company\CompanyAddress;
use App\Models\Company\CompanyAddressOwner;
use App\Models\Company\CompanyBankDetails;
use App\Models\Company\CompanyContactDetails;
use App\Models\Company\CompanyEmails;
use App\Models\Company\CompanyPackagingDetails;
use App\Models\Company\CompanyReferences;
use App\Models\Company\CompanySwotDetails;
use App\Models\CompanyCategory;
use App\Models\Settings\Agent;
use App\Models\Settings\BankDetails;
use App\Models\Settings\Designation;
use App\Models\Settings\TypeOfAddress;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ConnectionController extends Controller
{
    public function index(Request $request) {
        $employeeData = [];
        $productCategoryData = [];
        $products = [];
        $productImages = [];
        $cityList = [];
        $transports = [];
        $transportDetails = [];
        $companyData = [];
        $companyAddressData = [];
        $companyAddressOwnerData = [];
        $companyEmailData = [];
        $companyOwnerData = [];
        $companyCategoryData = [];
        $agentData = [];
        $designationData = [];
        $typeOfAddressData = [];
        $bankDetailData = [];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "laoffline-original";
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

            echo "<br><pre>";
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
            // echo "<pre>"; print_r($employeeData); die;
            // $pcat = "SELECT * FROM product_category";
            // $pquery = mysqli_query($conn, $pcat);

            // if(mysqli_num_rows($pquery) != 0) { 
            //     $i = 0;
            //     $companyId = [];
            //     while($result = mysqli_fetch_assoc($pquery)) {
            //         if(!empty($result['company_id'])) {
            //             $companyId = unserialize($result['company_id']);
            //             if($companyId) {
            //                 $countCompanyId = array_key_last($companyId) + 1;
            //                 if ($countCompanyId == 1) {
            //                     $result['company_id'] = json_encode($companyId[0]);
            //                 } else {
            //                     $result['company_id'] = json_encode($companyId);
            //                 }
            //             } else {
            //                 $result['company_id'] = 0;                            
            //             }
            //         }

            //         $productCategoryData[$i]['id'] = $result['product_category_id'];
            //         $productCategoryData[$i]['product_default_category_id'] = $result['product_default_category_id'];
            //         $productCategoryData[$i]['name'] = $result['name'];
            //         $productCategoryData[$i]['main_category_id'] = $result['main_category_id'];
            //         $productCategoryData[$i]['company_id'] = $result['company_id'];
            //         $productCategoryData[$i]['product_fabric_id'] = $result['product_fabric_id'];
            //         $productCategoryData[$i]['sort_order'] = $result['sort_order'];
            //         $productCategoryData[$i]['multiple_company'] = $result['multiple_company'];
            //         $productCategoryData[$i]['rate'] = $result['rate'];
            //         $i++;
            //     }
            // }

                         
            // $prod = "SELECT * FROM product_main";
            // $pdquery = mysqli_query($conn, $prod);

            // if(mysqli_num_rows($pdquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($pdquery)) {
                    
            //         if(!empty($result['sub_category_id'])) {
            //             $subCatId = unserialize($result['sub_category_id']);
            //             if($subCatId) {
            //                 $countsubCatId = array_key_last($subCatId) + 1;
            //                 if ($countsubCatId == 1) {
            //                     $result['sub_category_id'] = json_encode($subCatId[0]);
            //                 } else {
            //                     $result['sub_category_id'] = json_encode($subCatId);
            //                 }
            //             } else {
            //                 $result['sub_category_id'] = 0;                            
            //             }
            //         }

            //         if($result['launch_date'] == '0000-00-00') {
            //             $result['launch_date'] = NULL;
            //         } else {
            //             $result['launch_date'] = $result['launch_date'];
            //         }

            //         $products[$i]['productData']['id'] = $result['product_main_id'];
            //         $products[$i]['productData']['product_name'] = $result['name'];
            //         $products[$i]['productData']['catalogue_name'] = $result['catalogue_name'];
            //         $products[$i]['productData']['brand_name'] = $result['brand_name'];
            //         $products[$i]['productData']['model'] = $result['model_name'];
            //         $products[$i]['productData']['launch_date'] = $result['launch_date'];
            //         $products[$i]['productData']['company'] = $result['company_id'];
            //         $products[$i]['productData']['category'] = $result['category_id'];
            //         $products[$i]['productData']['sub_category'] = $result['sub_category_id'];                    
            //         $products[$i]['productData']['main_image'] = $result['main_image'];
            //         $products[$i]['productData']['price_list_image'] = $result['price_list_image'];
            //         $products[$i]['productData']['description'] = $result['description'];
            //         $products[$i]['productData']['complete_flag'] = $result['complete_flag'];
            //         $products[$i]['productData']['generated_by'] = 1;
            //         $products[$i]['productData']['updated_by'] = 0;

            //         $products[$i]['productDetails']['product_id'] = $result['product_main_id'];
            //         $products[$i]['productDetails']['catalogue_price'] = $result['price'];
            //         $products[$i]['productDetails']['average_price'] = $result['average_price'];
            //         $products[$i]['productDetails']['wholesale_discount'] = $result['wh_discount'];
            //         $products[$i]['productDetails']['wholesale_brokerage'] = $result['wh_brokerage'];
            //         $products[$i]['productDetails']['retail_discount'] = $result['re_discount'];
            //         $products[$i]['productDetails']['retail_brokerage'] = $result['re_brokerage'];

            //         $products[$i]['fabrics']['product_id'] = $result['product_main_id'];
            //         $products[$i]['fabrics']['saree_fabric'] = $result['saree_fabric'];
            //         $products[$i]['fabrics']['saree_cut'] = $result['saree_cut'];
            //         $products[$i]['fabrics']['blouse_fabric'] = $result['blouse_fabric'];
            //         $products[$i]['fabrics']['blouse_cut'] = $result['blouse_cut'];
            //         $products[$i]['fabrics']['top_fabric'] = $result['top_fabric'];
            //         $products[$i]['fabrics']['top_cut'] = $result['top_cut'];
            //         $products[$i]['fabrics']['bottom_fabric'] = $result['bottom_fabric'];
            //         $products[$i]['fabrics']['bottom_cut'] = $result['bottom_cut'];
            //         $products[$i]['fabrics']['dupatta_fabric'] = $result['dupatta_fabric'];
            //         $products[$i]['fabrics']['dupatta_cut'] = $result['dupatta_cut'];
            //         $products[$i]['fabrics']['inner_fabric'] = $result['inner_fabric'];
            //         $products[$i]['fabrics']['inner_cut'] = $result['inner_cut'];
            //         $products[$i]['fabrics']['sleeves_fabric'] = $result['sleeves_fabric'];
            //         $products[$i]['fabrics']['sleeves_cut'] = $result['sleeves_cut'];
            //         $products[$i]['fabrics']['choli_fabric'] = $result['choli_fabric'];
            //         $products[$i]['fabrics']['choli_cut'] = $result['choli_cut'];
            //         $products[$i]['fabrics']['lehenga_fabric'] = $result['lehenga_fabric'];
            //         $products[$i]['fabrics']['lehenga_cut'] = $result['lehenga_cut'];
            //         $products[$i]['fabrics']['lining_fabric'] = $result['lining_fabric'];
            //         $products[$i]['fabrics']['lining_cut'] = $result['lining_cut'];
            //         $products[$i]['fabrics']['gown_fabric'] = $result['gown_fabric'];
            //         $products[$i]['fabrics']['gown_cut'] = $result['gown_cut'];
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
            
                                     
            // $tsql = "SELECT * FROM transport";
            // $tquery = mysqli_query($conn, $tsql);

            // if(mysqli_num_rows($tquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($tquery)) {
            //         $transports[$i]['id'] = $result['transport_id'];
            //         $transports[$i]['name'] = $result['transport_name'];
            //         $transports[$i]['gstin'] = !empty($result['gstin']) ? $result['gstin'] : 0;
            //         $i++;
            //     }
            // }
            
            
                                     
            // $pisql = "SELECT * FROM product_image";
            // $piquery = mysqli_query($conn, $pisql);

            // if(mysqli_num_rows($piquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($piquery)) {
            //         $productImages[$i]['id'] = $result['product_image_id'];
            //         $productImages[$i]['product_id'] = $result['product_main_id'];
            //         $productImages[$i]['supplier_code'] = $result['name'];
            //         $productImages[$i]['product_code'] = !empty($result['product_code']) ? $result['product_code'] : 0;
            //         $productImages[$i]['image'] = $result['image_name'];
            //         $productImages[$i]['price'] = $result['price'];
            //         $productImages[$i]['sort_order'] = $result['sort_order'];
            //         $i++;
            //     }
            // }
            
                        
                                     
            // $comsql = "SELECT * FROM company";
            // $comquery = mysqli_query($conn, $comsql);

            // if(mysqli_num_rows($comquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($comquery)) {
            //         if(!empty($result['company_category_id'])) {
            //             $subCatId = unserialize($result['company_category_id']);
            //             if($subCatId) {
            //                 $countsubCatId = array_key_last($subCatId['company_category']) + 1;
            //                 if ($countsubCatId == 1) {
            //                     $result['company_category_id'] = json_encode($subCatId['company_category'][0]);
            //                 } else {
            //                     $result['company_category_id'] = json_encode($subCatId['company_category']);
            //                 }
            //             } else {
            //                 $result['company_category_id'] = 0;                            
            //             }
            //         } else {
            //             $result['company_category_id'] = 0;
            //         }
                    
            //         if(!empty($result['landline_no'])) {
            //             $lno = @unserialize($result['landline_no']);
            //             if($lno) {
            //                 $countlno = array_key_last($lno) + 1;
            //                 if ($countlno == 1) {
            //                     $result['landline_no'] = json_encode($lno[0]);
            //                 } else {
            //                     $result['landline_no'] = json_encode($lno);
            //                 }
            //             } else {
            //                 $result['landline_no'] = 0;
            //             }
            //         } else {
            //             $result['landline_no'] = 0;
            //         }
                    
            //         if(!empty($result['mobile_no'])) {
            //             $lno = @unserialize($result['mobile_no']);
            //             if($lno) {
            //                 $countlno = array_key_last($lno) + 1;
            //                 if ($countlno == 1) {
            //                     $result['mobile_no'] = json_encode($lno[0]);
            //                 } else {
            //                     $result['mobile_no'] = json_encode($lno);
            //                 }
            //             } else {
            //                 $result['mobile_no'] = 0;
            //             }
            //         } else {
            //             $result['mobile_no'] = 0;
            //         }

            //         if($result['verified_date'] == '0000-00-00 00:00:00') {
            //             $result['verified_date'] = NULL;
            //         } else {
            //             $result['verified_date'] = $result['verified_date'];
            //         }

            //         $companyData[$i]['companyData']['id'] = $result['company_id'];
            //         $companyData[$i]['companyData']['company_name'] = $result['name'];
            //         $companyData[$i]['companyData']['company_type'] = $result['company_type_id'];
            //         $companyData[$i]['companyData']['company_country'] = !empty($result['country_name']) ? $result['country_name'] : 0;
            //         $companyData[$i]['companyData']['company_state'] = !empty($result['state_id']) ? $result['state_id'] : 0;
            //         $companyData[$i]['companyData']['company_city'] = !empty($result['city_name']) ? $result['city_name'] : 0;
            //         $companyData[$i]['companyData']['company_website'] = $result['website'];
            //         $companyData[$i]['companyData']['company_landline'] = $result['landline_no'];
            //         $companyData[$i]['companyData']['company_mobile'] = $result['mobile_no'];
            //         $companyData[$i]['companyData']['company_watchout'] = $result['watchout'];
            //         $companyData[$i]['companyData']['company_remark_watchout'] = $result['remark_watchout'];
            //         $companyData[$i]['companyData']['company_about'] = $result['about_cmp'];
            //         $companyData[$i]['companyData']['company_category'] = $result['company_category_id'];
            //         $companyData[$i]['companyData']['company_transport'] = $result['transport_id'];
            //         $companyData[$i]['companyData']['company_discount'] = $result['discount'];
            //         $companyData[$i]['companyData']['company_payment_terms_in_days'] = $result['pay_term_days'];
            //         $companyData[$i]['companyData']['company_opening_balance'] = $result['opening_balance'];
            //         $companyData[$i]['companyData']['favorite_flag'] = $result['favorite_flag'];
            //         $companyData[$i]['companyData']['is_verified'] = $result['is_verified'];
            //         $companyData[$i]['companyData']['is_active'] = $result['is_active'];
            //         $companyData[$i]['companyData']['is_linked'] = $result['is_linked'];
            //         $companyData[$i]['companyData']['verified_by'] = $result['verified_by'];
            //         $companyData[$i]['companyData']['generated_by'] = $result['generated_by'];
            //         $companyData[$i]['companyData']['updated_by'] = $result['updated_by'];
            //         $companyData[$i]['companyData']['verified_date'] = $result['verified_date'];

            //         $companyData[$i]['swotData']['company_id'] = $result['company_id'];
            //         $companyData[$i]['swotData']['strength'] = !empty($result['strength']) ? $result['strength'] : 0;
            //         $companyData[$i]['swotData']['weakness'] = !empty($result['weakness']) ? $result['weakness'] : 0;
            //         $companyData[$i]['swotData']['opportunity'] = !empty($result['opportunity']) ? $result['opportunity'] : 0;
            //         $companyData[$i]['swotData']['threat'] = !empty($result['threat']) ? $result['threat'] : 0;

            //         $companyData[$i]['bankData']['company_id'] = $result['company_id'];
            //         $companyData[$i]['bankData']['bank_name'] = $result['bank_name'];
            //         $companyData[$i]['bankData']['account_holder_name'] = $result['acc_name'];
            //         $companyData[$i]['bankData']['account_no'] = $result['acc_num'];
            //         $companyData[$i]['bankData']['branch_name'] = $result['branch_name'];
            //         $companyData[$i]['bankData']['ifsc_code'] = $result['ifsc_code'];

            //         $companyData[$i]['packagingData']['company_id'] = $result['company_id'];
            //         $companyData[$i]['packagingData']['gst_no'] = !empty($result['gst_no']) ? $result['gst_no'] : 0;
            //         $companyData[$i]['packagingData']['cst_no'] = !empty($result['cst_no']) ? $result['cst_no'] : 0;
            //         $companyData[$i]['packagingData']['tin_no'] = !empty($result['tin_no']) ? $result['tin_no'] : 0;
            //         $companyData[$i]['packagingData']['vat_no'] = !empty($result['vat_no']) ? $result['vat_no'] : 0;

            //         $companyData[$i]['referencesData']['company_id'] = $result['company_id'];
            //         $companyData[$i]['referencesData']['ref_person_name'] = $result['ref_person'];
            //         $companyData[$i]['referencesData']['ref_person_mobile'] = $result['ref_mobile'];
            //         $companyData[$i]['referencesData']['ref_person_company'] = $result['ref_company'];
            //         $companyData[$i]['referencesData']['ref_person_address'] = $result['ref_address'];
            //         $i++;
            //     }
            // }
            
                                     
            // $casql = "SELECT * FROM company_address";
            // $caquery = mysqli_query($conn, $casql);

            // if(mysqli_num_rows($caquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($caquery)) {
            //         $companyAddressData[$i]['id'] = $result['company_address_id'];
            //         $companyAddressData[$i]['company_id'] = $result['company_id'];
            //         $companyAddressData[$i]['address_type'] = $result['type_of_address_id'];
            //         $companyAddressData[$i]['address'] = $result['address'];
            //         $companyAddressData[$i]['country_code'] = $result['country_code'];
            //         $companyAddressData[$i]['mobile'] = $result['landline_no'];
            //         $i++;
            //     }
            // }
            
            

            // $caosql = "SELECT * FROM company_address_owner";
            // $caoquery = mysqli_query($conn, $caosql);

            // if(mysqli_num_rows($caoquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($caoquery)) {
            //         $des = $result['designation'];

            //         if (is_numeric($des)) {
            //             $result['designation'] = json_encode($des);

            //         } else {
            //             $desg = @unserialize($result['designation']);
            //             if (is_array($desg)) {
            //                 if($desg) {
            //                     $result['designation'] = json_encode($desg);
            //                 } else {
            //                     $result['designation'] = 0;                            
            //                 }
            //             } else {
            //                 $designation = "SELECT * FROM designation WHERE designation_name = '$des'";
            //                 $designationData = mysqli_query($conn, $designation);
                            
            //                 $dResult = mysqli_fetch_row($designationData);
                            
            //                 if ($dResult != NULL) {
            //                     $result['designation'] = json_encode($dResult[0]);
            //                 } else {
            //                     $result['designation'] = 0;
            //                 }
            //             }                        
            //         }

            //         $companyAddressOwnerData[$i]['id'] = $result['company_address_owner_id'];
            //         $companyAddressOwnerData[$i]['company_address_id'] = $result['company_address_id'];
            //         $companyAddressOwnerData[$i]['name'] = $result['name'];
            //         $companyAddressOwnerData[$i]['designation'] = $result['designation'];
            //         $companyAddressOwnerData[$i]['profile_pic'] = $result['profile_pic'];
            //         $companyAddressOwnerData[$i]['mobile'] = $result['mobile_no'];
            //         $companyAddressOwnerData[$i]['email'] = $result['email'];
            //         $i++;
            //     }
            // }          
                                     
            // $ccsql = "SELECT * FROM company_category";
            // $ccquery = mysqli_query($conn, $ccsql);

            // if(mysqli_num_rows($ccquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($ccquery)) {
            //         $companyCategoryData[$i]['id'] = $result['company_category_id'];
            //         $companyCategoryData[$i]['category_name'] = $result['name'];
            //         $companyCategoryData[$i]['sort_order'] = $result['sort_order'];
            //         $i++;                        
            //     }
            // }
            
            
                        
                                     
            // $cesql = "SELECT * FROM company_email";
            // $cequery = mysqli_query($conn, $cesql);

            // if(mysqli_num_rows($cequery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($cequery)) {
            //         $companyEmailData[$i]['id'] = $result['company_email_id'];
            //         $companyEmailData[$i]['company_id'] = $result['company_id'];
            //         $companyEmailData[$i]['email_id'] = $result['email_id'];
            //         $i++;
            //     }
            // }
            
            
                             
            $cosql = "SELECT * FROM company_owner";
            $coquery = mysqli_query($conn, $cosql);
            
            if(mysqli_num_rows($coquery) != 0) {
                $i = 0;
                while($result = mysqli_fetch_assoc($coquery)) {
                    $des = $result['designation'];

                    if (is_numeric($des)) {
                        $result['designation'] = json_encode($des);
                    } else {
                        $desg = @unserialize($result['designation']);
                        if (is_array($desg)) {
                            if($desg) {
                                $result['designation'] = json_encode($desg);
                            } else {
                                $result['designation'] = 0;                            
                            }
                        } else {
                            $designation = "SELECT * FROM designation WHERE designation_name = '$des'";
                            $designationData = mysqli_query($conn, $designation);
                            
                            $dResult = mysqli_fetch_row($designationData);
                            
                            if ($dResult != NULL) {
                                $result['designation'] = json_encode($dResult[0]);
                            } else {
                                $result['designation'] = 0;
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

            // $asql = "SELECT * FROM agent";
            // $aquery = mysqli_query($conn, $asql);

            // if(mysqli_num_rows($aquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($aquery)) {
            //         $agentData[$i]['id'] = $result['agent_id'];
            //         $agentData[$i]['name'] = $result['name'];
            //         $agentData[$i]['pan_no'] = $result['pan_no'];
            //         $agentData[$i]['gst_no'] = $result['gst_no'];
            //         $agentData[$i]['include_tax'] = $result['include_tax'];
            //         $agentData[$i]['default'] = $result['is_default'];
            //         $agentData[$i]['inv_prefix'] = $result['inv_prefix'];
            //         $i++;
            //     }
            // }
            
            
                        
            // $dsql = "SELECT * FROM designation";
            // $dquery = mysqli_query($conn, $dsql);

            // if(mysqli_num_rows($dquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($dquery)) {
            //         $designationData[$i]['id'] = $result['designation_id'];
            //         $designationData[$i]['name'] = $result['designation_name'];
            //         $i++;
            //     }
            // }
            
            
            
                        
            // $bdsql = "SELECT * FROM bank_details";
            // $bdquery = mysqli_query($conn, $bdsql);

            // if(mysqli_num_rows($bdquery) != 0) {
            //     $i = 0;
            //     while($result = mysqli_fetch_assoc($bdquery)) {
            //         $bankDetailData[$i]['id'] = $result['bank_details_id'];
            //         $bankDetailData[$i]['name'] = $result['name'];
            //         $bankDetailData[$i]['sort_order'] = $result['sort_order'];
            //         $i++;
            //     }
            // }
            
            
            
            
                        
            $toasql = "SELECT * FROM type_of_address";
            $toaquery = mysqli_query($conn, $toasql);

            if(mysqli_num_rows($toaquery) != 0) {
                $i = 0;
                while($result = mysqli_fetch_assoc($toaquery)) {
                    $typeOfAddressData[$i]['id'] = $result['type_of_address_id'];
                    $typeOfAddressData[$i]['name'] = $result['name'];
                    $typeOfAddressData[$i]['sort_order'] = $result['sort_order'];
                    $i++;
                }
            }
            
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

        // if(!empty($productCategoryData)) {
        //     foreach($productCategoryData as $productCategory) {
        //         $pCategory = new ProductCategory;
        //         $pCategory->id = $productCategory['id'];
        //         $pCategory->product_default_category_id = $productCategory['product_default_category_id'];
        //         $pCategory->name = $productCategory['name'];
        //         $pCategory->main_category_id = $productCategory['main_category_id'];
        //         $pCategory->company_id = $productCategory['company_id'];
        //         $pCategory->product_fabric_id = $productCategory['product_fabric_id'];
        //         $pCategory->multiple_company = $productCategory['multiple_company'];
        //         $pCategory->rate = $productCategory['rate'];
        //         $pCategory->sort_order = $productCategory['sort_order'];
        //         $pCategory->save();
        //     }
        // }

        // if(!empty($products)) {
        //     foreach($products as $product) {
        //         $productsData = new Product;
        //         $productsData->id = $product['productData']['id'];
        //         $productsData->product_name = $product['productData']['product_name'];
        //         $productsData->catalogue_name = $product['productData']['catalogue_name'];
        //         $productsData->brand_name = $product['productData']['brand_name'];
        //         $productsData->model = $product['productData']['model'];
        //         $productsData->launch_date = $product['productData']['launch_date'];
        //         $productsData->company = $product['productData']['company'];
        //         $productsData->category = $product['productData']['category'];
        //         $productsData->sub_category = $product['productData']['sub_category'];        
        //         $productsData->main_image = $product['productData']['main_image'];
        //         $productsData->price_list_image = $product['productData']['price_list_image'];
        //         $productsData->description = $product['productData']['description'];
        //         $productsData->complete_flag = $product['productData']['complete_flag'];
        //         $productsData->generated_by = $product['productData']['generated_by'];
        //         $productsData->updated_by = $product['productData']['updated_by'];
        //         $productsData->save();

        //         $pdId = ProductDetails::orderBy('id', 'DESC')->first('id');
        //         $podId = !empty($pdId) ? $pdId->id + 1 : 1;
                
        //         $productsDetail = new ProductDetails;
        //         $productsDetail->id = $podId;
        //         $productsDetail->product_id = $product['productDetails']['product_id'];
        //         $productsDetail->catalogue_price = $product['productDetails']['catalogue_price'];
        //         $productsDetail->average_price = $product['productDetails']['average_price'];
        //         $productsDetail->wholesale_discount = $product['productDetails']['wholesale_discount'];
        //         $productsDetail->wholesale_brokerage = $product['productDetails']['wholesale_brokerage'];
        //         $productsDetail->retail_discount = $product['productDetails']['retail_discount'];
        //         $productsDetail->retail_brokerage = $product['productDetails']['retail_brokerage'];
        //         $productsDetail->save();

        //         $pfdId = ProductFabricDetails::orderBy('id', 'DESC')->first('id');
        //         $fdId = !empty($pfdId) ? $pfdId->id + 1 : 1;
                
        //         $productFabrics = new ProductFabricDetails;
        //         $productFabrics->id = $fdId;
        //         $productFabrics->product_id = $product['fabrics']['product_id'];
        //         $productFabrics->saree_fabric = $product['fabrics']['saree_fabric'];
        //         $productFabrics->saree_cut = $product['fabrics']['saree_cut'];
        //         $productFabrics->blouse_fabric = $product['fabrics']['blouse_fabric'];
        //         $productFabrics->blouse_cut = $product['fabrics']['blouse_cut'];
        //         $productFabrics->top_fabric = $product['fabrics']['top_fabric'];
        //         $productFabrics->top_cut = $product['fabrics']['top_cut'];
        //         $productFabrics->bottom_fabric = $product['fabrics']['bottom_fabric'];
        //         $productFabrics->bottom_cut = $product['fabrics']['bottom_cut'];
        //         $productFabrics->dupatta_fabric = $product['fabrics']['dupatta_fabric'];
        //         $productFabrics->dupatta_cut = $product['fabrics']['dupatta_cut'];
        //         $productFabrics->inner_fabric = $product['fabrics']['inner_fabric'];
        //         $productFabrics->inner_cut = $product['fabrics']['inner_cut'];
        //         $productFabrics->sleeves_fabric = $product['fabrics']['sleeves_fabric'];
        //         $productFabrics->sleeves_cut = $product['fabrics']['sleeves_cut'];
        //         $productFabrics->choli_fabric = $product['fabrics']['choli_fabric'];
        //         $productFabrics->choli_cut = $product['fabrics']['choli_cut'];
        //         $productFabrics->lehenga_fabric = $product['fabrics']['lehenga_fabric'];
        //         $productFabrics->lehenga_cut = $product['fabrics']['lehenga_cut'];
        //         $productFabrics->lining_fabric = $product['fabrics']['lining_fabric'];
        //         $productFabrics->lining_cut = $product['fabrics']['lining_cut'];
        //         $productFabrics->gown_fabric = $product['fabrics']['gown_fabric'];
        //         $productFabrics->gown_cut = $product['fabrics']['gown_cut'];
        //         $productFabrics->save();
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
            
        // if(!empty($transports)) {
        //     foreach($transports as $transport) {                
        //         $transportDetails = new TransportDetails;
        //         $transportDetails->id = $transport['id'];
        //         $transportDetails->name = $transport['name'];
        //         $transportDetails->gstin = $transport['gstin'];
        //         $transportDetails->save();
        //     }
        //     $transportMultipleAddressDetails = new TransportMultipleAddressDetails;
        //     $transportMultipleAddressDetails->id = '2';
        //     $transportMultipleAddressDetails->transport_details = '248';
        //     $transportMultipleAddressDetails->contact_person_name = '';
        //     $transportMultipleAddressDetails->contact_person_address = 'kalbadevi mumbai';
        //     $transportMultipleAddressDetails->contact_person_office_no = '02222073928';
        //     $transportMultipleAddressDetails->contact_person_email = '';
        //     $transportMultipleAddressDetails->save();
        // }

        // if(!empty($productImages)) {
        //     foreach($productImages as $productImage) {
        //         $pImages = new ProductsImages;                
        //         $pImages->id = $productImage['id'];
        //         $pImages->product_id = $productImage['product_id'];
        //         $pImages->supplier_code = $productImage['supplier_code'];
        //         $pImages->product_code = $productImage['product_code'];
        //         $pImages->image = $productImage['image'];
        //         $pImages->price = $productImage['price'];
        //         $pImages->sort_order = $productImage['sort_order'];
        //         $pImages->save();
        //     }
        // }
        // dd($companyData[0]['companyData']);
        // if(!empty($companyData)) {
        //     foreach($companyData as $cData) {
        //         $company = new Company;
        //         $company->id = $cData['companyData']['id'];
        //         $company->company_name = $cData['companyData']['company_name'];
        //         $company->company_type = $cData['companyData']['company_type'];
        //         $company->company_country = $cData['companyData']['company_country'];
        //         $company->company_state = $cData['companyData']['company_state'];
        //         $company->company_city = $cData['companyData']['company_city'];
        //         $company->company_website = $cData['companyData']['company_website'];
        //         $company->company_landline = $cData['companyData']['company_landline'];
        //         $company->company_mobile = $cData['companyData']['company_mobile'];
        //         $company->company_watchout = $cData['companyData']['company_watchout'];
        //         $company->company_remark_watchout = $cData['companyData']['company_remark_watchout'];
        //         $company->company_about = $cData['companyData']['company_about'];
        //         $company->company_category = $cData['companyData']['company_category'];
        //         $company->company_transport = $cData['companyData']['company_transport'];
        //         $company->company_discount = $cData['companyData']['company_discount'];
        //         $company->company_payment_terms_in_days = $cData['companyData']['company_payment_terms_in_days'];
        //         $company->company_opening_balance = $cData['companyData']['company_opening_balance'];
        //         $company->favorite_flag = $cData['companyData']['favorite_flag'];
        //         $company->is_verified = $cData['companyData']['is_verified'];
        //         $company->is_active = $cData['companyData']['is_active'];
        //         $company->is_linked = $cData['companyData']['is_linked'];
        //         $company->verified_by = $cData['companyData']['verified_by'];
        //         $company->generated_by = $cData['companyData']['generated_by'];
        //         $company->updated_by = $cData['companyData']['updated_by'];
        //         $company->verified_date = $cData['companyData']['verified_date'];
        //         $company->save();

        //         $csdId = CompanySwotDetails::orderBy('id', 'DESC')->first('id');
        //         $sdId = !empty($csdId) ? $csdId->id + 1 : 1;
                
        //         $swotData = new CompanySwotDetails;
        //         $swotData->id = $sdId;
        //         $swotData->company_id = $cData['swotData']['company_id'];
        //         $swotData->strength = $cData['swotData']['strength'];
        //         $swotData->weakness = $cData['swotData']['weakness'];
        //         $swotData->opportunity = $cData['swotData']['opportunity'];
        //         $swotData->threat = $cData['swotData']['threat'];
        //         $swotData->save();

        //         $cbdId = CompanyBankDetails::orderBy('id', 'DESC')->first('id');
        //         $bdId = !empty($cbdId) ? $cbdId->id + 1 : 1;
                
        //         $bankDetail = new CompanyBankDetails;
        //         $bankDetail->id = $bdId;
        //         $bankDetail->company_id = $cData['bankData']['company_id'];
        //         $bankDetail->bank_name = $cData['bankData']['bank_name'];
        //         $bankDetail->account_holder_name = $cData['bankData']['account_holder_name'];
        //         $bankDetail->account_no = $cData['bankData']['account_no'];
        //         $bankDetail->branch_name = $cData['bankData']['branch_name'];
        //         $bankDetail->ifsc_code = $cData['bankData']['ifsc_code'];
        //         $bankDetail->save();

        //         $cpdId = CompanyPackagingDetails::orderBy('id', 'DESC')->first('id');
        //         $pdId = !empty($cpdId) ? $cpdId->id + 1 : 1;
                
        //         $package = new CompanyPackagingDetails;
        //         $package->id = $pdId;
        //         $package->company_id = $cData['packagingData']['company_id'];
        //         $package->gst_no = $cData['packagingData']['gst_no'];
        //         $package->cst_no = $cData['packagingData']['cst_no'];
        //         $package->tin_no = $cData['packagingData']['tin_no'];
        //         $package->vat_no = $cData['packagingData']['vat_no'];
        //         $package->save();

        //         $crId = CompanyReferences::orderBy('id', 'DESC')->first('id');
        //         $crefId = !empty($crId) ? $crId->id + 1 : 1;
                
        //         $reference = new CompanyReferences;
        //         $reference->id = $crefId;
        //         $reference->company_id = $cData['referencesData']['company_id'];
        //         $reference->ref_person_name = $cData['referencesData']['ref_person_name'];
        //         $reference->ref_person_mobile = $cData['referencesData']['ref_person_mobile'];
        //         $reference->ref_person_company = $cData['referencesData']['ref_person_company'];
        //         $reference->ref_person_address = $cData['referencesData']['ref_person_address'];
        //         $reference->save();
        //     }
        // }

        // if(!empty($companyAddressData)) {
        //     foreach($companyAddressData as $caData) {
        //         $companyAddress = new CompanyAddress;
        //         $companyAddress->id = $caData['id'];
        //         $companyAddress->company_id = $caData['company_id'];
        //         $companyAddress->address_type = $caData['address_type'];
        //         $companyAddress->address = $caData['address'];
        //         $companyAddress->country_code = $caData['country_code'];
        //         $companyAddress->mobile = $caData['mobile'];
        //         $companyAddress->save();
        //     }
        // }

        // if(!empty($companyAddressOwnerData)) {
        //     foreach($companyAddressOwnerData as $caoData) {
        //         $companyAddressOwner = new CompanyAddressOwner;
        //         $companyAddressOwner->id = $caoData['id'];
        //         $companyAddressOwner->company_address_id = $caoData['company_address_id'];
        //         $companyAddressOwner->name = $caoData['name'];
        //         $companyAddressOwner->designation = $caoData['designation'];
        //         $companyAddressOwner->profile_pic = $caoData['profile_pic'];
        //         $companyAddressOwner->mobile = $caoData['mobile'];
        //         $companyAddressOwner->email = $caoData['email'];
        //         $companyAddressOwner->save();
        //     }
        // }

        // if(!empty($companyCategoryData)) {
        //     foreach($companyCategoryData as $ccData) {
        //         $companyCategory = new CompanyCategory;
        //         $companyCategory->id = $ccData['id'];
        //         $companyCategory->category_name = $ccData['category_name'];
        //         $companyCategory->sort_order = $ccData['sort_order'];
        //         $companyCategory->save();
        //     }
        // }

        // if(!empty($companyEmailData)) {
        //     foreach($companyEmailData as $ceData) {
        //         $companyEmail = new CompanyEmails;
        //         $companyEmail->id = $ceData['id'];
        //         $companyEmail->company_id = $ceData['company_id'];
        //         $companyEmail->email_id = $ceData['email_id'];
        //         $companyEmail->save();
        //     }
        // }

        if(!empty($companyOwnerData)) {
            foreach($companyOwnerData as $coData) {
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

        // if(!empty($agentData)) {
        //     foreach($agentData as $agents) {
        //         $agent = new Agent;
        //         $agent->id = $agents['id'];
        //         $agent->name = $agents['name'];
        //         $agent->pan_no = $agents['pan_no'];
        //         $agent->gst_no = $agents['gst_no'];
        //         $agent->include_tax = $agents['include_tax'];
        //         $agent->default = $agents['default'];
        //         $agent->inv_prefix = $agents['inv_prefix'];
        //         $agent->save();
        //     }
        // }

        // if(!empty($designationData)) {
        //     foreach($designationData as $designations) {
        //         $designation = new Designation;
        //         $designation->id = $designations['id'];
        //         $designation->name = $designations['name'];
        //         $designation->save();
        //     }
        // }

        // if(!empty($bankDetailData)) {
        //     foreach($bankDetailData as $bankDetail) {
        //         $bankDetails = new BankDetails;
        //         $bankDetails->id = $bankDetail['id'];
        //         $bankDetails->name = $bankDetail['name'];
        //         $bankDetails->sort_order = $bankDetail['sort_order'];
        //         $bankDetails->save();
        //     }
        // }

        // if(!empty($typeOfAddressData)) {
        //     foreach($typeOfAddressData as $typeOfAddresses) {
        //         $typeOfAddress = new TypeOfAddress;
        //         $typeOfAddress->id = $typeOfAddresses['id'];
        //         $typeOfAddress->name = $typeOfAddresses['name'];
        //         $typeOfAddress->sort_order = $typeOfAddresses['sort_order'];
        //         $typeOfAddress->save();
        //     }
        // }

        print_r("SUCCESsssS");
    }
}
