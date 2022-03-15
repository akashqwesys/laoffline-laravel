<?php

namespace App\Http\Controllers\databank;

use App\Http\Controllers\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\FabricField;
use App\Models\Logs;
use App\Models\ProductCategory;
use App\Models\Company\Company;
use App\Models\Company\CompanyContactDetails;
use App\Models\Product;
use App\Models\FinancialYear;
use App\Models\ProductDetails;
use App\Models\ProductsImages;
use App\Models\ProductFabricDetails;
use App\Models\Settings\Cities;
use App\Models\Settings\Country;
use App\Models\Settings\State;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
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
        $logs->log_path = 'Product / View';
        $logs->log_subject = 'Product view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('databank.products.product',compact('financialYear'))->with('employees', $employees);
    }

    public function createProducts() {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        return view('databank.products.createProduct',compact('financialYear'))->with('employees', $employees);
    }

    public function listCountries() {
        $countries = Country::all();

        return $countries;
    }

    public function listState() {
        $state = State::all();

        return $state;
    }

    public function listCities() {
        $countries = Cities::all();

        return $countries;
    }

    public function listData(Request $request) {
        $products = Product::where('is_delete', '0')->get();

        return $products;
    }

    public function listProducts(Request $request) {
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
        $totalRecords = Product::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Product::select('count(*) as allcount')->
                                           where('product_name', 'ILIKE', '%' .$searchValue . '%')->
                                           count();

        // Fetch records
        $products = Product::join('companies','products.company','=','companies.id')->
                             join('product_categories','products.category','=','product_categories.id')->
                             join('product_details','products.id','=','product_details.product_id')->
                             orderBy('products.'.$columnName,$columnSortOrder)->
                             where('products.product_name', 'ILIKE', '%' .$searchValue . '%')->
                             where('products.is_delete', 0)->
                             skip($start)->
                             take($rowperpage)->
                             get(['products.*','products.id as product_id','companies.company_name','product_categories.name as category_name','product_details.catalogue_price']);

        $data_arr = array();
        $sno = $start+1;

        foreach($products as $product) {
            $id = $product->product_id;
            $f = substr($product->product_name, 0, 1);

            if (!empty($product->complete_flag)) {
                $flag = '<em class="icon ni ni-check-thick"></em>';
            } else {
                $flag = '<em class="icon ni ni-alert-fill"></em>';
            }

            if (!empty($product->main_image)) {
                $mainImage = '<img src="/upload/products/'.$product->main_image.'" alt="">';
            } else {
                $mainImage = '<span>'.strtoupper($f).'</span>';
            }

            $name = $product->product_name;
            $catalogue_name = $product->catalogue_name;
            $brand_name = $product->brand_name;
            $model = $product->model;
            $company_name = $product->company_name;
            $category_name = $product->category_name;
            $catalogue_price = $product->catalogue_price;
            // <a href="#" @click="view_data(product.product_id)" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="View"><em class="icon ni ni-eye"></em></a>
            $action = '<a href="./catalog/edit-products/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Update"><em class="icon ni ni-edit-alt"></em></a>
            <a href="./catalog/delete/'.$id.'" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Remove"><em class="icon ni ni-trash"></em></a>';

            $data_arr[] = array(
                "id" => $id,
                "flag" => $flag,
                "image" => $mainImage,
                "name" => $name,
                "catalogue_name" => $catalogue_name,
                "brand_name" => $brand_name,
                "model" => $model,
                "company_name" => $company_name,
                "category_name" => $category_name,
                "catalogue_price" => $catalogue_price,
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

    public function mainCategory($id) {
        $mainCategory = ProductCategory::where('main_category_id', '!=', 0)->get();
        $mainCategoryData = [];
        $category_array = [];
        

        foreach($mainCategory as $key => $category) {
            if (is_array(json_decode($category->company_id))) {
                $companyIds = json_decode($category->company_id);
                foreach($companyIds as $cId) {
                    if($cId == $id) {
                        array_push($category_array, $category->main_category_id);
                    }
                }
            } else {
                $companyIds = json_decode($category->company_id);
                if($companyIds == $id) {
                    array_push($category_array, $category->main_category_id);
                }
            }
        }

        $categories = ProductCategory::where('main_category_id', 0)->where('product_default_category_id', 1)->get();

        foreach ($categories as $key => $row_category) {
			if(in_array($row_category->id, $category_array)) {
				$mainCategoryData[$key]['id'] = $row_category->id;
                $mainCategoryData[$key]['name'] = $row_category->name;
			}
		}

        return $mainCategoryData;
    }

    public function subCategory($id, $companyId) {
        $subCategories = ProductCategory::where('main_category_id', $id)->get(['id','name','company_id','product_fabric_id']);
        $subCategoryData = [];

        foreach($subCategories as $key => $category) {
            if (is_array(json_decode($category->company_id))) {
                $companyIds = json_decode($category->company_id);
                foreach($companyIds as $cId) {
                    if($cId == $companyId) {
                        $subCategoryData[$key]['id'] = $category->id;
                        $subCategoryData[$key]['name'] = $category->name;
                        $subCategoryData[$key]['fabric_id'] = $category->product_fabric_id;
                    }
                }
            } else {
                $companyIds = json_decode($category->company_id);
                if($companyIds == $companyId) {
                    $subCategoryData[$key]['id'] = $category->id;
                    $subCategoryData[$key]['name'] = $category->name;
                    $subCategoryData[$key]['fabric_id'] = $category->product_fabric_id;
                }
            }
        }
        sort($subCategoryData);

        return $subCategoryData;
    }

    public function fabricField($id) {
        $fabricId = json_decode($id);
        $fabricGroup = [];
        $i = 0;

        foreach($fabricId as $key => $fabric) {
            $fabricData = FabricField::where('product_fabric_id', $fabric)->get('name');
            if($fabricData) {
                foreach($fabricData as $in => $data) {
                    $fabricGroup[$i] = strtolower($data->name).'_view';
                    $i++;
                }
            }
        }

        return $fabricGroup;
    }

    public function listCompanies() {
        $company = Company::get(['id', 'company_name']);

        return $company;
    }

    public function editProducts($id) {
        $financialYear = FinancialYear::get();
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')->
                                join('user_groups', 'employees.user_group', '=', 'user_groups.id')->where('employees.id', $user->employee_id)->first();

        $employees['scope'] = 'edit';
        $employees['editedId'] = $id;

        return view('databank.products.editProduct',compact('financialYear'))->with('employees', $employees);
    }

    public function fetchProducts($id) {
        $products = [];
        $productData = Product::join('product_details','products.id','=','product_details.product_id')->where('products.id', $id)->first();

        $company = Company::where('id', $productData->company)->first();
        $comapnyData['id'] = $company->id;
        $comapnyData['company_name'] = $company->company_name;
        
        $category = ProductCategory::where('id', $productData->category)->first();
        $categoryData['id'] = $category->id;
        $categoryData['name'] = $category->name;
        
        $subCategory = ProductCategory::where('id', $productData->sub_category)->first();
        $subCategoryData['id'] = $subCategory->id;
        $subCategoryData['name'] = $subCategory->name;

        $productData['company'] = $comapnyData;
        $productData['category'] = $categoryData;
        $productData['sub_category'] = $subCategoryData;

        $productAdditionalImage = ProductsImages::where('product_id', $id)->get(['supplier_code','product_code','image','price','sort_order']);
        $productFabric = ProductFabricDetails::where('product_id', $id)->get();

        $i = 0; $view = [];
        foreach ($productFabric as $fabrics) {
            $data = json_decode($fabrics);
            foreach($data as $key => $d) {
                if (($d != 0 && is_numeric($d)) && ($key != 'id' && $key != 'product_id')) {
                    $view[$i] = explode('_', $key)[0] . "_view";
                    $i++;
                }
            }
        }
        $view = array_unique($view);
        sort($view);

        $products['productData'] = $productData;
        $products['additionalImage'] = $productAdditionalImage;
        $products['fabricsData'] = $productFabric;
        $products['fabricsView'] = $view;

        return $products;
    }

    public function deleteProducts($id){
        $productData = Product::where('id',$id)->first();
        $productData->is_delete = 1;
        $productData->save();
        
        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Product / Delete';
        $logs->log_subject = 'Product - "'.$productData->product_name.'" was deleted.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return redirect()->route('catalog');
    }

    public function insertCompaniesData(Request $request) {
        $comapnyLastId = Company::orderBy('id', 'DESC')->first('id');
        $companyId = !empty($comapnyLastId) ? $comapnyLastId->id + 1 : 1;

        $companyContactDetailsLastId = CompanyContactDetails::orderBy('id', 'DESC')->first('id');
        $companyContactDetailsId = !empty($companyContactDetailsLastId) ? $companyContactDetailsLastId->id + 1 : 1;

        $company = new Company;
        $company->id = $companyId;
        $company->company_name = $request->name;
        $company->company_type = $request->company_type['id'];
        $company->company_country = $request->country['id'];
        $company->company_state = $request->city['state'];
        $company->company_city = $request->city['id'];
        $company->company_website = '';
        $company->company_landline = 0;
        $company->company_mobile = 0;
        $company->company_watchout = 0;
        $company->company_remark_watchout = '';
        $company->company_about = $request->about_company;
        $company->company_category = 0;
        $company->company_transport = 0;
        $company->company_discount = 0;
        $company->company_payment_terms_in_days = 0;
        $company->company_opening_balance = 0;
        $company->favorite_flag = 0;
        $company->is_verified = 0;
        $company->is_linked = 0;
        $company->is_active = 0;
        $company->verified_by = 0;
        $company->generated_by = Session::get('user')->employee_id;
        $company->updated_by = 0;
        $company->verified_date = NULL;
        $company->save();

        $companyContactDetails = new CompanyContactDetails;
        $companyContactDetails->id = $companyContactDetailsId;
        $companyContactDetails->company_id = $companyId;
        $companyContactDetails->contact_person_name = $request->owner_name;
        $companyContactDetails->contact_person_designation = 0;
        $companyContactDetails->contact_person_profile_pic = '';
        $companyContactDetails->contact_person_mobile = $request->owner_mobile;
        $companyContactDetails->contact_person_email = $request->owner_email;
        $companyContactDetails->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Company / Add';
        $logs->log_subject = 'Company - "'.$request->name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return true;
    }

    public function insertProductsData(Request $request) {
        $total_image = 0; 
        $inserted_image = 0;

        $productData = json_decode($request->product_data);
        $AdditionalImage = json_decode($request->additionalImages);
        $fabricsData = json_decode($request->fabricsData);
        $fabrics = [];
        $i = 0;

        foreach($fabricsData as $key => $data) {
            $fabrics[$key] = $data;
        }

        $subCategoriesId = [];        

        foreach($productData->sub_category as $key => $subCategory) {
            $subCategoriesId[$key] = $subCategory->id;
        }

        if (!file_exists(public_path('upload/products'))) {
            mkdir(public_path('upload/products'), 0777, true);
        }

        if ($image = $request->mainImage) {
            $profileImage = date('YmdHis') . "_mainImage." . $image->getClientOriginalExtension();
            $productData->main_image = $profileImage;
            $image->move(public_path('upload/products/'), $profileImage);
            $inserted_image++;
        }
        $total_image++;
        if ($image = $request->priceListImage) {
            $profileImage = date('YmdHis') . "_priceListImage." . $image->getClientOriginalExtension();
            $productData->price_list_image = $profileImage;
            $image->move(public_path('upload/products/'), $profileImage);
        }

        if(is_array($request->product_additional_images) && !empty($request->product_additional_images)) {
            $length = count($request->product_additional_images);

            for ($i=0; $i<$length; $i++) {                
                if ($image = $request->product_additional_images[$i]) {
                    if(!is_string($image)) {
                        $profileImage = date('YmdHis') . "_additionalImage_" . $i . "." . $image->getClientOriginalExtension();
                        $AdditionalImage[$i]->product_pic = $profileImage;
                        $image->move(public_path('upload/products/'), $profileImage);
                        $inserted_image++;
                    } else {
                        $AdditionalImage[$i]->product_pic = '';
                    }
                    $total_image++;
                }
            }
        }

        $complete_flag = 0;
        if($total_image != $inserted_image) {
            $complete_flag = 1;
        }

        $productsLastId = Product::orderBy('id', 'DESC')->first('id');
        $productsId = !empty($productsLastId) ? $productsLastId->id + 1 : 1;

        $products = new Product;
        $products->id = $productsId;
        $products->product_name = $productData->product_name;
        $products->catalogue_name = $productData->catalogue_name;
        $products->brand_name = $productData->brand_name;
        $products->model = $productData->model;
        $products->launch_date = $productData->launch_date;
        $products->company = $productData->company->id;
        $products->category = $productData->category->id;        
        $products->sub_category = implode(',', $subCategoriesId);        
        $products->main_image = $productData->main_image;
        $products->price_list_image = $productData->price_list_image;
        $products->description = $productData->description;
        $products->complete_flag = $complete_flag;
        $products->generated_by = Session::get('user')->employee_id;
        $products->updated_by = 0;
        $products->save();
        
        $productDetailsLastId = ProductDetails::orderBy('id', 'DESC')->first('id');
        $productDetailsId = !empty($productDetailsLastId) ? $productDetailsLastId->id + 1 : 1;

        $productDetails = new ProductDetails;
        $productDetails->id = $productDetailsId;
        $productDetails->product_id = $productsId;
        $productDetails->catalogue_price = $productData->catalogue_price;
        $productDetails->average_price = $productData->average_price;
        $productDetails->wholesale_discount = $productData->wholesale_discount;
        $productDetails->wholesale_brokerage = $productData->wholesale_brokerage;
        $productDetails->retail_discount = $productData->retail_discount;
        $productDetails->retail_brokerage = $productData->retail_brokerage;
        $productDetails->save();
        
        foreach($AdditionalImage as $image) {
            $productImagesLastId = ProductsImages::orderBy('id', 'DESC')->first('id');
            $productImagesId = !empty($productImagesLastId) ? $productImagesLastId->id + 1 : 1;
    
            $productImages = new ProductsImages;
            $productImages->id = $productImagesId;
            $productImages->product_id = $productsId;
            $productImages->supplier_code = $image->supplier_code;
            $productImages->product_code = $image->product_code;
            $productImages->image = $image->product_pic;
            $productImages->price = $image->price;
            $productImages->sort_order = $image->sort_order;
            $productImages->save();
        }
        
        $productFabricsLastId = ProductFabricDetails::orderBy('id', 'DESC')->first('id');
        $productFabricsId = !empty($productFabricsLastId) ? $productFabricsLastId->id + 1 : 1;

        $productFabrics = new ProductFabricDetails;
        $productFabrics->id = $productFabricsId;
        $productFabrics->product_id = $productsId;
        $productFabrics->saree_fabric = !empty($fabrics['saree_fabric']) ? $fabrics['saree_fabric'] : '';
        $productFabrics->saree_cut = !empty($fabrics['saree_cut']) ? $fabrics['saree_cut'] : 0;
        $productFabrics->blouse_fabric = !empty($fabrics['blouse_fabric']) ? $fabrics['blouse_fabric'] : '';
        $productFabrics->blouse_cut = !empty($fabrics['blouse_cut']) ? $fabrics['blouse_cut'] : 0;
        $productFabrics->top_fabric = !empty($fabrics['top_fabric']) ? $fabrics['top_fabric'] : '';
        $productFabrics->top_cut = !empty($fabrics['top_cut']) ? $fabrics['top_cut'] : 0;
        $productFabrics->bottom_fabric = !empty($fabrics['bottom_fabric']) ? $fabrics['bottom_fabric'] : '';
        $productFabrics->bottom_cut = !empty($fabrics['bottom_cut']) ? $fabrics['bottom_cut'] : 0;
        $productFabrics->dupatta_fabric = !empty($fabrics['dupatta_fabric']) ? $fabrics['dupatta_fabric'] : '';
        $productFabrics->dupatta_cut = !empty($fabrics['dupatta_cut']) ? $fabrics['dupatta_cut'] : 0;
        $productFabrics->inner_fabric = !empty($fabrics['inner_fabric']) ? $fabrics['inner_fabric'] : '';
        $productFabrics->inner_cut = !empty($fabrics['inner_cut']) ? $fabrics['inner_cut'] : 0;
        $productFabrics->sleeves_fabric = !empty($fabrics['sleeves_fabric']) ? $fabrics['sleeves_fabric'] : '';
        $productFabrics->sleeves_cut = !empty($fabrics['sleeves_cut']) ? $fabrics['sleeves_cut'] : 0;
        $productFabrics->choli_fabric = !empty($fabrics['choli_fabric']) ? $fabrics['choli_fabric'] : '';
        $productFabrics->choli_cut = !empty($fabrics['choli_cut']) ? $fabrics['choli_cut'] : 0;
        $productFabrics->lehenga_fabric = !empty($fabrics['lehenga_fabric']) ? $fabrics['lehenga_fabric'] : '';
        $productFabrics->lehenga_cut = !empty($fabrics['lehenga_cut']) ? $fabrics['lehenga_cut'] : 0;
        $productFabrics->lining_fabric = !empty($fabrics['lining_fabric']) ? $fabrics['lining_fabric'] : '';
        $productFabrics->lining_cut = !empty($fabrics['lining_cut']) ? $fabrics['lining_cut'] : 0;
        $productFabrics->gown_fabric = !empty($fabrics['gown_fabric']) ? $fabrics['gown_fabric'] : '';
        $productFabrics->gown_cut = !empty($fabrics['gown_cut']) ? $fabrics['gown_cut'] : 0;
        $productFabrics->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Product / Add';
        $logs->log_subject = 'Product - "'.$productData->product_name.'" was inserted from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }

    public function updateProductsData(Request $request) {
        $total_image = 0; 
        $inserted_image = 0;

        $productData = json_decode($request->product_data);
        $AdditionalImage = json_decode($request->additionalImages);
        $fabricsData = json_decode($request->fabricsData);
        $fabrics = [];

        $id = $productData->id;

        foreach($fabricsData as $key => $data) {
            $fabrics[$key] = $data;
        }

        $subCategoriesId = [];        
        if(is_array($productData->sub_category)) {
            foreach($productData->sub_category as $key => $subCategory) {
                $subCategoriesId[$key] = $subCategory->id;
            }
        } else {
            $subCategoriesId[0] = $productData->sub_category->id;
        }

        if (!file_exists(public_path('upload/products'))) {
            mkdir(public_path('upload/products'), 0777, true);
        }

        if ($image = $request->mainImage) {
            $profileImage = date('YmdHis') . "_mainImage." . $image->getClientOriginalExtension();
            $productData->main_image = $profileImage;
            $image->move(public_path('upload/products/'), $profileImage);
            $inserted_image++;
        } else {
            $productData->main_image = $productData->main_image;
            $inserted_image++;
        }
        $total_image++;
        
        if ($image = $request->priceListImage) {
            $profileImage = date('YmdHis') . "_priceListImage." . $image->getClientOriginalExtension();
            $productData->price_list_image = $profileImage;
            $image->move(public_path('upload/products/'), $profileImage);
        } else {
            $productData->price_list_image = $productData->price_list_image;
        }

        if(is_array($request->product_additional_images) && !empty($request->product_additional_images) ) {
            $length = count($request->product_additional_images);

            for ($i=0; $i<$length; $i++) {                
                if ($image = $request->product_additional_images[$i]) {
                    if(!is_string($image)) {
                        $profileImage = date('YmdHis') . "_additionalImage_" . $i . "." . $image->getClientOriginalExtension();
                        $AdditionalImage[$i]->image = $profileImage;
                        $image->move(public_path('upload/products/'), $profileImage);
                        $inserted_image++;

                    } else {
                        $AdditionalImage[$i]->image = $AdditionalImage[$i]->image;
                        $inserted_image++;
                    }
                    $total_image++;
                }
            }
        }
        
        $complete_flag = 0;
        if($total_image != $inserted_image) {
            $complete_flag = 1;
        }

        $products = Product::where('id', $id)->first();
        $products->product_name = $productData->product_name;
        $products->catalogue_name = $productData->catalogue_name;
        $products->brand_name = $productData->brand_name;
        $products->model = $productData->model;
        $products->launch_date = $productData->launch_date;
        $products->company = $productData->company->id;
        $products->category = $productData->category->id;        
        $products->sub_category = implode(',', $subCategoriesId);
        $products->main_image = $productData->main_image;
        $products->price_list_image = $productData->price_list_image;
        $products->description = $productData->description;
        $products->complete_flag = $complete_flag;
        $products->updated_by = Session::get('user')->employee_id;
        $products->save();
        
        $productDetails = ProductDetails::where('product_id', $id)->first();
        $productDetails->catalogue_price = $productData->catalogue_price;
        $productDetails->average_price = $productData->average_price;
        $productDetails->wholesale_discount = $productData->wholesale_discount;
        $productDetails->wholesale_brokerage = $productData->wholesale_brokerage;
        $productDetails->retail_discount = $productData->retail_discount;
        $productDetails->retail_brokerage = $productData->retail_brokerage;
        $productDetails->save();

        $productImages = ProductsImages::where('product_id', $id)->delete();
        foreach($AdditionalImage as $image) {            
            $productImageId = ProductsImages::orderBy('id', 'DESC')->first('id');
            $piId = !empty($productImageId) ? $productImageId->id + 1 : 1;
                            
            $productImages = new ProductsImages;
            $productImages->id = $piId;
            $productImages->product_id = $products->id;
            $productImages->supplier_code = $image->supplier_code;
            $productImages->product_code = $image->product_code;
            $productImages->image = $image->image;
            $productImages->price = $image->price;
            $productImages->sort_order = $image->sort_order;
            $productImages->save();
        }
        
        $productFabrics = ProductFabricDetails::where('product_id', $id)->first();
        $productFabrics->product_id = $products->id;
        $productFabrics->saree_fabric = isset($fabrics['saree_fabric']) ? $fabrics['saree_fabric'] : 0;
        $productFabrics->saree_cut = isset($fabrics['saree_cut']) ? $fabrics['saree_cut'] : 0;
        $productFabrics->blouse_fabric = isset($fabrics['blouse_fabric']) ? $fabrics['blouse_fabric'] : 0;
        $productFabrics->blouse_cut = isset($fabrics['blouse_cut']) ? $fabrics['blouse_cut'] : 0;
        $productFabrics->top_fabric = isset($fabrics['top_fabric']) ? $fabrics['top_fabric'] : 0;
        $productFabrics->top_cut = isset($fabrics['top_cut']) ? $fabrics['top_cut'] : 0;
        $productFabrics->bottom_fabric = isset($fabrics['bottom_fabric']) ? $fabrics['bottom_fabric'] : 0;
        $productFabrics->bottom_cut = isset($fabrics['bottom_cut']) ? $fabrics['bottom_cut'] : 0;
        $productFabrics->dupatta_fabric = isset($fabrics['dupatta_fabric']) ? $fabrics['dupatta_fabric'] : 0;
        $productFabrics->dupatta_cut = isset($fabrics['dupatta_cut']) ? $fabrics['dupatta_cut'] : 0;
        $productFabrics->inner_fabric = isset($fabrics['inner_fabric']) ? $fabrics['inner_fabric'] : 0;
        $productFabrics->inner_cut = isset($fabrics['inner_cut']) ? $fabrics['inner_cut'] : 0;
        $productFabrics->sleeves_fabric = isset($fabrics['sleeves_fabric']) ? $fabrics['sleeves_fabric'] : 0;
        $productFabrics->sleeves_cut = isset($fabrics['sleeves_cut']) ? $fabrics['sleeves_cut'] : 0;
        $productFabrics->choli_fabric = isset($fabrics['choli_fabric']) ? $fabrics['choli_fabric'] : 0;
        $productFabrics->choli_cut = isset($fabrics['choli_cut']) ? $fabrics['choli_cut'] : 0;
        $productFabrics->lehenga_fabric = isset($fabrics['lehenga_fabric']) ? $fabrics['lehenga_fabric'] : 0;
        $productFabrics->lehenga_cut = isset($fabrics['lehenga_cut']) ? $fabrics['lehenga_cut'] : 0;
        $productFabrics->lining_fabric = isset($fabrics['lining_fabric']) ? $fabrics['lining_fabric'] : 0;
        $productFabrics->lining_cut = isset($fabrics['lining_cut']) ? $fabrics['lining_cut'] : 0;
        $productFabrics->gown_fabric = isset($fabrics['gown_fabric']) ? $fabrics['gown_fabric'] : 0;
        $productFabrics->gown_cut = isset($fabrics['gown_cut']) ? $fabrics['gown_cut'] : 0;
        $productFabrics->save();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;
                        
        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Product / Edit';
        $logs->log_subject = 'Product - "'.$productData->product_name.'" was updated from '.Session::get('user')->username.'.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();
    }
}
