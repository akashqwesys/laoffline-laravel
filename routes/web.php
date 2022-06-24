<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Auth::routes();

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'index'])->name('register');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');
    Route::get('/common/list-customers-and-suppliers', [App\Http\Controllers\CommonController::class, 'getCustomersAndSuppliers']);
    Route::get('/common/list-all-companies', [App\Http\Controllers\CommonController::class, 'getAllCompanies']);
});

Route::group(['prefix' => 'financialyear', 'middleware' => ['auth', 'permission:access-financial-year']], function () {
    Route::get('/', [App\Http\Controllers\FinancialYearController::class, 'index'])->name('financialyear');
    Route::get('/getPermission', [App\Http\Controllers\FinancialYearController::class, 'getPermissions'])->name('getPermissions');
    Route::get('/list', [App\Http\Controllers\FinancialYearController::class, 'listFinancialYear'])->name('list');
    Route::get('/list-data', [App\Http\Controllers\FinancialYearController::class, 'listData'])->name('list-data');
    Route::get('/fetch-year/{id}', [App\Http\Controllers\FinancialYearController::class, 'fetchFinancialYear']);

    Route::get('/update-current-financial-year/{id}', [App\Http\Controllers\FinancialYearController::class, 'updateCurrentFinancialYear']);
});

Route::group(['prefix' => 'financialyear', 'middleware' => ['auth', 'permission:modify-financial-year']], function () {
    Route::get('/create-financialyear', [App\Http\Controllers\FinancialYearController::class, 'createFinancialYear']);
    Route::post('/create', [App\Http\Controllers\FinancialYearController::class, 'insertFinancialYear']);
    Route::post('/update', [App\Http\Controllers\FinancialYearController::class, 'updateFinancialYear']);
    Route::get('/edit-financialyear/{id}', [App\Http\Controllers\FinancialYearController::class, 'editFinancialYear']);
    Route::get('/delete/{id}', [App\Http\Controllers\FinancialYearController::class, 'deleteFinancialYear'])->name('delete');
});

// Routes for Databank / User Group
Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:access-user-group']], function () {
    Route::group(['prefix' => 'users-group'], function () {
        Route::get('/', [App\Http\Controllers\databank\UserGroupController::class, 'index'])->name('users-group');
        Route::get('/getPermission', [App\Http\Controllers\databank\UserGroupController::class, 'getPermissions'])->name('getPermissions');
        Route::get('/list', [App\Http\Controllers\databank\UserGroupController::class, 'listUserGroup'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\databank\UserGroupController::class, 'listData'])->name('list-data');
        Route::get('/fetch-user-group/{id}', [App\Http\Controllers\databank\UserGroupController::class, 'fetchUserGroup']);
    });
});

Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:modify-user-group']], function () {
    Route::group(['prefix' => 'users-group'], function () {
        Route::get('/create-user-group', [App\Http\Controllers\databank\UserGroupController::class, 'createUserGroup']);
        Route::post('/create', [App\Http\Controllers\databank\UserGroupController::class, 'insertUserGroupData']);
        Route::post('/update', [App\Http\Controllers\databank\UserGroupController::class, 'updateUserGroupData']);
        Route::get('/edit-user-group/{id}', [App\Http\Controllers\databank\UserGroupController::class, 'editUserGroup']);
        Route::get('/delete/{id}', [App\Http\Controllers\databank\UserGroupController::class, 'deleteUserGroup'])->name('delete');
    });
});


// Routes for Databank / Employee
Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:access-employee']], function () {
    Route::group(['prefix' => 'employee'], function () {
        Route::get('/', [App\Http\Controllers\databank\EmployeesController::class, 'index'])->name('employee');
        Route::get('/getPermission', [App\Http\Controllers\databank\EmployeesController::class, 'getPermissions'])->name('getPermissions');
        Route::get('/list', [App\Http\Controllers\databank\EmployeesController::class, 'listEmployee'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\databank\EmployeesController::class, 'listData'])->name('list-data');
        Route::get('/fetch-employee/{id}', [App\Http\Controllers\databank\EmployeesController::class, 'fetchEmployee']);
    });
});

Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:modify-employee']], function () {
    Route::group(['prefix' => 'employee'], function () {
        Route::get('/create-employee', [App\Http\Controllers\databank\EmployeesController::class, 'createEmployee']);
        Route::post('/create', [App\Http\Controllers\databank\EmployeesController::class, 'insertEmployeeData']);
        Route::post('/update', [App\Http\Controllers\databank\EmployeesController::class, 'updateEmployeeData']);
        Route::get('/edit-employee/{id}', [App\Http\Controllers\databank\EmployeesController::class, 'editEmployee']);
        Route::get('/delete/{id}', [App\Http\Controllers\databank\EmployeesController::class, 'deleteEmployee'])->name('delete');
    });
});


// Routes for Logs
Route::group(['prefix' => 'logs'], function () {
    Route::get('/', [App\Http\Controllers\LogsController::class, 'index'])->name('logs');
    Route::get('/list', [App\Http\Controllers\LogsController::class, 'listLogs'])->name('list');
});


// Routes for Databank / Product Category
Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:access-product-category']], function () {
    Route::group(['prefix' => 'product-category'], function () {
        Route::get('/', [App\Http\Controllers\databank\ProductCategoryController::class, 'index'])->name('product-category');
        Route::get('/list', [App\Http\Controllers\databank\ProductCategoryController::class, 'listProductCategory'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\databank\ProductCategoryController::class, 'listData'])->name('list-data');
        Route::get('/list-category', [App\Http\Controllers\databank\ProductCategoryController::class, 'listCategory'])->name('list-category');
        Route::get('/list-default-category', [App\Http\Controllers\databank\ProductCategoryController::class, 'listProductDefaultCategoriesCategory'])->name('list-default-category');
        Route::get('/fetch-product-category/{id}', [App\Http\Controllers\databank\ProductCategoryController::class, 'fetchProductCategory']);
    });
});

Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:modify-product-category']], function () {
    Route::group(['prefix' => 'product-category'], function () {
        Route::get('/create-product-category', [App\Http\Controllers\databank\ProductCategoryController::class, 'createProductCategory']);
        Route::post('/create', [App\Http\Controllers\databank\ProductCategoryController::class, 'insertProductCategoryData']);
        Route::post('/update', [App\Http\Controllers\databank\ProductCategoryController::class, 'updateProductCategoryData']);
        Route::get('/edit-product-category/{id}', [App\Http\Controllers\databank\ProductCategoryController::class, 'editProductCategory']);
        Route::get('/delete/{id}', [App\Http\Controllers\databank\ProductCategoryController::class, 'deleteProductCategory'])->name('delete');
    });
});



// Routes for Databank / Product Sub Category
Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:access-product-sub-category']], function () {
    Route::group(['prefix' => 'productsub-category'], function () {
        Route::get('/', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'index'])->name('productsub-category');
        Route::get('/list', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'listProductSubCategory'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'listData'])->name('list-data');
        Route::get('/company-name/{id}', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'getCompanyName'])->name('company-name');
        Route::get('/listCompanies', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'listCompanies'])->name('listCompanies');
        Route::get('/listProductFabricGroup', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'listProductFabricGroup'])->name('listProductFabricGroup');
        Route::get('/fetch-productsub-category/{id}', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'fetchProductSubCategory']);
    });
});

Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:modify-product-sub-category']], function () {
    Route::group(['prefix' => 'productsub-category'], function () {
        Route::get('/create-productsub-category', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'createProductSubCategory']);
        Route::post('/create', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'insertProductSubCategoryData']);
        Route::post('/update', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'updateProductSubCategoryData']);
        Route::get('/edit-productsub-category/{id}', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'editProductSubCategory']);
        Route::get('/delete/{id}', [App\Http\Controllers\databank\ProductSubCategoryController::class, 'deleteProductSubCategory'])->name('delete');
    });
});


// Routes for Databank / Company Category
Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:access-company-category']], function () {
    Route::group(['prefix' => 'companyCategory'], function () {
        Route::get('/', [App\Http\Controllers\databank\CompanyCategoryController::class, 'index'])->name('companyCategory');
        Route::get('/list', [App\Http\Controllers\databank\CompanyCategoryController::class, 'listCompanyCategory'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\databank\CompanyCategoryController::class, 'listData'])->name('list-data');
        Route::get('/fetch-company-category/{id}', [App\Http\Controllers\databank\CompanyCategoryController::class, 'fetchCompanyCategory']);
    });
});

Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:modify-company-category']], function () {
    Route::group(['prefix' => 'companyCategory'], function () {
        Route::get('/create-company-category', [App\Http\Controllers\databank\CompanyCategoryController::class, 'createCompanyCategory']);
        Route::post('/create', [App\Http\Controllers\databank\CompanyCategoryController::class, 'insertCompanyCategoryData']);
        Route::post('/update', [App\Http\Controllers\databank\CompanyCategoryController::class, 'updateCompanyCategoryData']);
        Route::get('/edit-company-category/{id}', [App\Http\Controllers\databank\CompanyCategoryController::class, 'editCompanyCategory']);
        Route::get('/delete/{id}', [App\Http\Controllers\databank\CompanyCategoryController::class, 'deleteCompanyCategory'])->name('delete');
    });
});


// Routes for Databank / Company
Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:access-company']], function () {
    Route::group(['prefix' => 'companies'], function () {
        Route::get('/', [App\Http\Controllers\databank\CompanyController::class, 'index'])->name('companies');
        Route::get('/list', [App\Http\Controllers\databank\CompanyController::class, 'listCompany'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\databank\CompanyController::class, 'listData'])->name('list-data');
        Route::get('/category-name/{id}', [App\Http\Controllers\databank\CompanyController::class, 'getCompanyCategory'])->name('category-name');
        Route::get('/essential', [App\Http\Controllers\databank\CompanyController::class, 'essentialCompany'])->name('essential');
        Route::get('/essential/list-essential', [App\Http\Controllers\databank\CompanyController::class, 'listEssentialCompany'])->name('list-essential');
        Route::get('/fetch-company/{id}', [App\Http\Controllers\databank\CompanyController::class, 'fetchCompany']);
    });
});

Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:modify-company']], function () {
    Route::group(['prefix' => 'companies'], function () {
        Route::get('/create-company', [App\Http\Controllers\databank\CompanyController::class, 'createCompany']);
        Route::post('/create', [App\Http\Controllers\databank\CompanyController::class, 'insertCompanyData']);
        Route::post('/update', [App\Http\Controllers\databank\CompanyController::class, 'updateCompanyData']);
        Route::post('/verify/{id}', [App\Http\Controllers\databank\CompanyController::class, 'isVerify'])->name('verify');
        Route::post('/favorite/{id}', [App\Http\Controllers\databank\CompanyController::class, 'isFavorite'])->name('favorite');
        Route::post('/unFavorite/{id}', [App\Http\Controllers\databank\CompanyController::class, 'isUnFavorite'])->name('unFavorite');
        Route::get('/view-company/{id}', [App\Http\Controllers\databank\CompanyController::class, 'viewCompany']);
        Route::get('/edit-company/{id}', [App\Http\Controllers\databank\CompanyController::class, 'editCompany']);
        Route::get('/delete/{id}', [App\Http\Controllers\databank\CompanyController::class, 'deleteCompany'])->name('delete');
    });
});


// Routes for Databank / Product
Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:access-product']], function () {
    Route::group(['prefix' => 'catalog'], function () {
        Route::get('/', [App\Http\Controllers\databank\ProductsController::class, 'index'])->name('catalog');
        Route::get('/list', [App\Http\Controllers\databank\ProductsController::class, 'listProducts'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\databank\ProductsController::class, 'listData'])->name('list-data');
        Route::get('/main-category/{id}', [App\Http\Controllers\databank\ProductsController::class, 'mainCategory'])->name('mainCategory');
        Route::get('/sub-category/{id}/{companyId}', [App\Http\Controllers\databank\ProductsController::class, 'subCategory'])->name('subCategory');
        Route::get('/fabric-field/{id}', [App\Http\Controllers\databank\ProductsController::class, 'fabricField'])->name('fabricField');
        Route::get('/list-country', [App\Http\Controllers\databank\ProductsController::class, 'listCountries'])->name('list-country');
        Route::get('/list-state', [App\Http\Controllers\databank\ProductsController::class, 'listState'])->name('list-state');
        Route::get('/list-cities', [App\Http\Controllers\databank\ProductsController::class, 'listCities'])->name('list-cities');
        Route::get('/list-companies', [App\Http\Controllers\databank\ProductsController::class, 'listCompanies'])->name('list-companies');
        Route::get('/fetch-product/{id}', [App\Http\Controllers\databank\ProductsController::class, 'fetchProducts']);
    });
});

Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:modify-product']], function () {
    Route::group(['prefix' => 'catalog'], function () {
        Route::get('/create-products', [App\Http\Controllers\databank\ProductsController::class, 'createProducts']);
        Route::post('/create', [App\Http\Controllers\databank\ProductsController::class, 'insertProductsData']);
        Route::post('/create-company', [App\Http\Controllers\databank\ProductsController::class, 'insertCompaniesData']);
        Route::post('/update', [App\Http\Controllers\databank\ProductsController::class, 'updateProductsData']);
        Route::get('/edit-products/{id}', [App\Http\Controllers\databank\ProductsController::class, 'editProducts']);
        Route::get('/delete/{id}', [App\Http\Controllers\databank\ProductsController::class, 'deleteProducts'])->name('delete');
    });
});


// Routes for Databank / Link Companies
Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:access-link-companies']], function () {
    Route::group(['prefix' => 'link-company'], function () {
        Route::get('/', [App\Http\Controllers\databank\LinkCompaniesController::class, 'index'])->name('link-company');
        Route::get('/list', [App\Http\Controllers\databank\LinkCompaniesController::class, 'listLinkCompanies'])->name('list');
        Route::get('/list-js', [App\Http\Controllers\databank\LinkCompaniesController::class, 'listLinkCompanies_dt'])->name('list-js');
        Route::get('/listCompanies', [App\Http\Controllers\databank\LinkCompaniesController::class, 'listCompanies'])->name('listCompanies');
        Route::get('/getCompanyById/{id}', [App\Http\Controllers\databank\LinkCompaniesController::class, 'getCompanyById'])->name('getCompanyById');
        Route::get('/getLinkedCompanyById/{id}', [App\Http\Controllers\databank\LinkCompaniesController::class, 'getLinkedCompanyById'])->name('getLinkedCompanyById');
        Route::get('/fetch-link-company/{id}', [App\Http\Controllers\databank\LinkCompaniesController::class, 'fetchLinkCompanies']);
    });
});

Route::group(['prefix' => 'databank', 'middleware' => ['auth', 'permission:modify-link-companies']], function () {
    Route::group(['prefix' => 'link-company'], function () {
        Route::get('/create-link-company', [App\Http\Controllers\databank\LinkCompaniesController::class, 'createLinkCompanies']);
        Route::post('/create', [App\Http\Controllers\databank\LinkCompaniesController::class, 'insertLinkCompaniesData']);
        Route::post('/merge', [App\Http\Controllers\databank\LinkCompaniesController::class, 'mergeLinkCompaniesData']);
        Route::post('/update', [App\Http\Controllers\databank\LinkCompaniesController::class, 'updateLinkCompaniesData']);
        Route::get('/edit-link-company/{id}', [App\Http\Controllers\databank\LinkCompaniesController::class, 'editLinkCompanies']);
        Route::get('/delete/{id}', [App\Http\Controllers\databank\LinkCompaniesController::class, 'deleteLinkCompanies'])->name('delete');
    });
});


// Routes for Reference ID
Route::group(['middleware' => ['auth', 'permission:access-reference-id']], function () {
    Route::get('/reference', [App\Http\Controllers\referenceId\ReferenceController::class, 'index'])->name('reference');
    Route::get('/reference/receiverDetails', [App\Http\Controllers\referenceId\ReferenceController::class, 'receiverDetails']);
    Route::get('/reference/companysearch', [App\Http\Controllers\referenceId\ReferenceController::class, 'getCompany']);
    Route::get('/reference/list', [App\Http\Controllers\referenceId\ReferenceController::class, 'listReference'])->name('list');
    Route::get('/reference/fetch-reference/{id}', [App\Http\Controllers\referenceId\ReferenceController::class, 'fetchreference'])->name('referenceView');
    Route::get('/reference/companylist', [App\Http\Controllers\referenceId\ReferenceController::class, 'fetchcompany']);
    Route::get('/reference/designation', [App\Http\Controllers\referenceId\ReferenceController::class, 'designation']);
    Route::get('/reference/list-country', [App\Http\Controllers\referenceId\ReferenceController::class, 'listCountries']);
    Route::get('/reference/list-transport', [App\Http\Controllers\referenceId\ReferenceController::class, 'listTransport']);
    Route::get('/reference/from-name/{id}', [App\Http\Controllers\referenceId\ReferenceController::class, 'fromName']);
});

Route::group(['middleware' => ['auth', 'permission:modify-reference-id']], function () {
    Route::get('/reference/create-reference', [App\Http\Controllers\referenceId\ReferenceController::class, 'createReferenceId']);
    Route::post('/reference/create-reference/create', [App\Http\Controllers\referenceId\ReferenceController::class, 'AddReferenceId']);
    Route::post('/reference/create-reference/createCompany', [App\Http\Controllers\referenceId\ReferenceController::class, 'AddCompany']);
    Route::post('/reference/create-reference/createPerson', [App\Http\Controllers\referenceId\ReferenceController::class, 'AddPerson']);
    Route::get('/reference/edit-reference/{id}', [App\Http\Controllers\referenceId\ReferenceController::class, 'editReferenceId']);
    Route::get('/reference/view-reference/{id}', [App\Http\Controllers\referenceId\ReferenceController::class, 'referenceView']);
    Route::get('/reference/referenceId/delete/{id}', [App\Http\Controllers\referenceId\ReferenceController::class, 'deleteReferenceId'])->name('delete');
    Route::post('/reference/update', [App\Http\Controllers\referenceId\ReferenceController::class, 'updateReference']);
});

// Routes for Account / Sale Bill
Route::group(['prefix' => 'account', 'middleware' => ['auth', 'permission:access-sale-bill']], function () {
    Route::group(['prefix' => 'sale-bill'], function () {
        Route::get('/', [App\Http\Controllers\Account\SaleBillController::class, 'index'])->name('sale-bill');
        Route::get('/list-data', [App\Http\Controllers\Account\SaleBillController::class, 'listSaleBill']);
        Route::get('/list-product-main-category/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'listProductMainCategory']);
        Route::get('/list-product-sub-category/{product_id}/{supplier_id}', [App\Http\Controllers\Account\SaleBillController::class, 'listProductSubCategory']);
        Route::get('/getProductsFromSubCategory', [App\Http\Controllers\Account\SaleBillController::class, 'getProductsFromSubCategory']);
        Route::get('/list-inward-customers/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'getCompanyFromInward']);
        Route::get('/list-customers-and-suppliers', [App\Http\Controllers\Account\SaleBillController::class, 'getCustomersAndSuppliers']);
        Route::get('/list-customer-address/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'getCustomerAddress']);
        Route::get('/list-sale-bill-agents', [App\Http\Controllers\Account\SaleBillController::class, 'getAgents']);
        Route::post('/check-supplier-invoice', [App\Http\Controllers\Account\SaleBillController::class, 'checkSupplierInvoiceNo']);
        Route::get('/getReferenceForSaleBill', [App\Http\Controllers\Account\SaleBillController::class, 'getReferenceForSaleBill']);
        Route::get('/getOldReferenceForSaleBill/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'getOldReferenceForSaleBill']);
        Route::get('/getSubProductFromProduct', [App\Http\Controllers\Account\SaleBillController::class, 'getSubProductFromProduct']);
        Route::get('/getReferenceForSaleBillUpdate/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'getReferenceForSaleBillUpdate']);
        Route::get('/getRefForSaleBillUpdate', [App\Http\Controllers\Account\SaleBillController::class, 'getRefForSaleBillUpdate']);
        Route::get('/list-transport', [App\Http\Controllers\Account\SaleBillController::class, 'listTransports']);
        Route::get('/list-stations/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'getStations']);
        Route::get('/fetch-sale-bill/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'fetchSaleBill']);
        Route::get('/updateSupplier/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'updateSupplier']);
        Route::get('/removeAttachment/{file}', [App\Http\Controllers\Account\SaleBillController::class, 'removeAttachment']);
        Route::get('/view-sale-bill/{id}/{fid}', [App\Http\Controllers\Account\SaleBillController::class, 'viewSaleBill']);
        Route::get('/view-sale-bill-details/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'getSaleBillDetails']);
    });
});

Route::group(['prefix' => 'account', 'middleware' => ['auth', 'permission:modify-sale-bill']], function () {
    Route::group(['prefix' => 'sale-bill'], function () {
        Route::get('/create-sale-bill', [App\Http\Controllers\Account\SaleBillController::class, 'createSaleBill']);
        Route::post('/create-sale-bill/create', [App\Http\Controllers\Account\SaleBillController::class, 'addSaleBill']);
        Route::get('/edit-sale-bill/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'editSaleBill']);
        Route::get('/copy/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'copySaleBill']);
        Route::get('/delete/{id}', [App\Http\Controllers\Account\SaleBillController::class, 'deleteSaleBill']);
        Route::post('/update', [App\Http\Controllers\Account\SaleBillController::class, 'updateSaleBill']);
        Route::post('/addFabricsDetails', [App\Http\Controllers\Account\SaleBillController::class, 'addFabricsDetails']);
        Route::post('/add-transport', [App\Http\Controllers\Account\SaleBillController::class, 'addTransport']);
    });
});


// Routes for Account / Commission - Invoice
Route::group(['prefix' => 'account', 'middleware' => ['auth', 'permission:access-commission']], function () {
    Route::group(['prefix' => 'commission/invoice'], function () {
        Route::get('/', [App\Http\Controllers\Account\InvoiceController::class, 'index']);
        Route::get('/list-data', [App\Http\Controllers\Account\InvoiceController::class, 'listInvoice']);
        Route::get('/view-invoice/{id}', [App\Http\Controllers\Account\InvoiceController::class, 'viewInvoiceDetails']);
        Route::get('/print-invoice/{id}', [App\Http\Controllers\Account\InvoiceController::class, 'viewInvoiceDetails']);
        Route::get('/list-company', [App\Http\Controllers\Account\InvoiceController::class, 'listCompany']);
        Route::get('/get-payments', [App\Http\Controllers\Account\InvoiceController::class, 'getPayments']);
        Route::get('/get-data', [App\Http\Controllers\Account\InvoiceController::class, 'getData']);
        Route::get('/generate-invoice', [App\Http\Controllers\Account\InvoiceController::class, 'invoiceView']);
        Route::post('/getInvoiceBillNo/{id}', [App\Http\Controllers\Account\InvoiceController::class, 'getInvoiceBillNo']);
        Route::get('/get-invoice-data/{id}', [App\Http\Controllers\Account\InvoiceController::class, 'getInvoiceData']);
    });
});

Route::group(['prefix' => 'account', 'middleware' => ['auth', 'permission:modify-commission']], function () {
    Route::group(['prefix' => 'commission/invoice'], function () {
        Route::get('/create-invoice', [App\Http\Controllers\Account\InvoiceController::class, 'createInvoice']);
        Route::post('/save-invoice', [App\Http\Controllers\Account\InvoiceController::class, 'saveInvoice']);
        Route::get('/edit-invoice/{id}', [App\Http\Controllers\Account\InvoiceController::class, 'editInvoice']);
        Route::get('/delete/{id}', [App\Http\Controllers\Account\InvoiceController::class, 'deleteInvoice']);
        Route::post('/update-invoice', [App\Http\Controllers\Account\InvoiceController::class, 'updateInvoice']);
        Route::post('/update-payments-remarks', [App\Http\Controllers\Account\InvoiceController::class, 'updatePaymentRemark']);
        Route::post('/setSessionForPaymentDetails', [App\Http\Controllers\Account\InvoiceController::class, 'setSessionForPaymentDetails']);
        Route::post('/delete-invoice-payment-detail', [App\Http\Controllers\Account\InvoiceController::class, 'deleteInvoicePaymentDetail']);
        Route::post('/refresh-invoice-payment-detail', [App\Http\Controllers\Account\InvoiceController::class, 'refreshInvoicePaymentDetail']);
    });
});


// Routes for Register
Route::group(['prefix' => 'register', 'middleware' => ['auth', 'permission:access-register']], function () {
    Route::get('/', [App\Http\Controllers\register\RegisterController::class, 'index'])->name('register');
    Route::get('/outward', [App\Http\Controllers\register\RegisterController::class, 'outward']);
    Route::get('/inward', [App\Http\Controllers\register\RegisterController::class, 'inward']);
    Route::get('/view-outward/{id}', [App\Http\Controllers\register\RegisterController::class, 'viewOutward']);
    Route::get('/fetch-outward/{id}', [App\Http\Controllers\register\RegisterController::class, 'fetchOutward']);
    Route::get('/edit-outward/{id}', [App\Http\Controllers\register\RegisterController::class, 'editOutward']);
    Route::get('/list', [App\Http\Controllers\register\RegisterController::class, 'listRegister'])->name('list');
    Route::get('/list-buyer', [App\Http\Controllers\register\RegisterController::class, 'listBuyer']);
    Route::get('/outwardlist', [App\Http\Controllers\register\RegisterController::class, 'outwardList']);
    Route::get('/inwardlist', [App\Http\Controllers\register\RegisterController::class, 'inwardList']);
    Route::post('/searchsalebill', [App\Http\Controllers\register\RegisterController::class, 'searchSalebill']);
    Route::post('/searchpayments', [App\Http\Controllers\register\RegisterController::class, 'searchPayment']);
    Route::post('/searchcommission', [App\Http\Controllers\register\RegisterController::class, 'searchCommission']);
    Route::post('/searchcommissioninvoice', [App\Http\Controllers\register\RegisterController::class, 'searchCommissionInvoice']);
    Route::get('/list-agentcourier', [App\Http\Controllers\register\RegisterController::class, 'listAgentCourier']);
    Route::get('/list-suppliers', [App\Http\Controllers\register\RegisterController::class, 'listSuppliers'])->name('list-suppliers');
    Route::get('/list-inwardLinkWith', [App\Http\Controllers\register\RegisterController::class, 'listInwardLinkWith'])->name('list-inwardLinkWith');
    Route::get('/get-reference-details/{type}/{flag}/{refrenceVia}', [App\Http\Controllers\register\RegisterController::class, 'getReferenceDetails'])->name('get-reference-details');
    Route::get('/receiverDetails', [App\Http\Controllers\register\RegisterController::class, 'receiverDetails']);
    Route::get('/getProductWithSupplier/{id}', [App\Http\Controllers\register\RegisterController::class, 'getProductWithSupplier']);
    Route::get('/getSubProducts/{value}', [App\Http\Controllers\register\RegisterController::class, 'getSubProducts']);
    Route::get('/list-employees', [App\Http\Controllers\register\RegisterController::class, 'listAllEmployees']);
    Route::get('/main-categories', [App\Http\Controllers\register\RegisterController::class, 'getmainCategories']);
    Route::post('/getReferenceSampleData', [App\Http\Controllers\register\RegisterController::class, 'getReferenceSampleData']);
    Route::get('/getOldReferenceDetails/{inwardRefSearch}/{typeOfInward}/{inwardType}', [App\Http\Controllers\register\RegisterController::class, 'getOldReferenceDetails']);
    Route::get('/from-name/{id}', [App\Http\Controllers\register\RegisterController::class, 'fromName']);
});

Route::group(['prefix' => 'register', 'middleware' => ['auth', 'permission:modify-register']], function () {
    Route::get('/create-inward', [App\Http\Controllers\register\RegisterController::class, 'createInward']);
    Route::get('/create-outward', [App\Http\Controllers\register\RegisterController::class, 'createOutward']);
    Route::get('/inward/{type}', [App\Http\Controllers\register\RegisterController::class, 'addInward']);
    Route::get('/outward/salebill', [App\Http\Controllers\register\RegisterController::class, 'addSalebillOutward']);
    Route::get('/outward/payment', [App\Http\Controllers\register\RegisterController::class, 'addPaymentOutward']);
    Route::get('/outward/commission', [App\Http\Controllers\register\RegisterController::class, 'addCommissionOutward']);
    Route::get('/outward/commissioninvoice', [App\Http\Controllers\register\RegisterController::class, 'addCommissionInvoiceOutward']);
    Route::post('/getalldetail', [App\Http\Controllers\register\RegisterController::class, 'getAllDetails']);
    Route::post('/insertoutward/{type}/', [App\Http\Controllers\register\RegisterController::class, 'insertOutward']);
    Route::post('/insertcourier', [App\Http\Controllers\register\RegisterController::class, 'insertCourier']);
    Route::post('/updateoutward', [App\Http\Controllers\register\RegisterController::class, 'updateOutward']);
    Route::post('/insertpaymentoutward', [App\Http\Controllers\register\RegisterController::class, 'insertPaymentOutward']);
    Route::post('/insertcommissionoutward', [App\Http\Controllers\register\RegisterController::class, 'insertCommissionOutward']);
    Route::post('/insertcommissioninvoiceoutward', [App\Http\Controllers\register\RegisterController::class, 'insertCommissionInvoiceOutward']);
    Route::post('/insertinward/{type}/', [App\Http\Controllers\register\RegisterController::class, 'insertInward']);
    Route::post('/inward/{type}/add-fabrics-details', [App\Http\Controllers\register\RegisterController::class, 'addFabricDetails']);
});

// Routes for Settings / Bank Details
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-bank-details']], function () {
    Route::group(['prefix' => 'bank-details'], function () {
        Route::get('/', [App\Http\Controllers\settings\BankDetailsController::class, 'index'])->name('bank-details');
        Route::get('/list', [App\Http\Controllers\settings\BankDetailsController::class, 'listBankDetails'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\BankDetailsController::class, 'listData'])->name('list-data');
        Route::get('/fetch-bank-details/{id}', [App\Http\Controllers\settings\BankDetailsController::class, 'fetchBankDetails']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-bank-details']], function () {
    Route::group(['prefix' => 'bank-details'], function () {
        Route::get('/create-bank-details', [App\Http\Controllers\settings\BankDetailsController::class, 'createBankDetails']);
        Route::post('/create', [App\Http\Controllers\settings\BankDetailsController::class, 'insertBankDetailsData']);
        Route::post('/update', [App\Http\Controllers\settings\BankDetailsController::class, 'updateBankDetailsData']);
        Route::get('/edit-bank-details/{id}', [App\Http\Controllers\settings\BankDetailsController::class, 'editBankDetails']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\BankDetailsController::class, 'deleteBankDetails'])->name('delete');
    });
});


// Routes for Settings / Countries
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-countries']], function () {
    Route::group(['prefix' => 'countries'], function () {
        Route::get('/', [App\Http\Controllers\settings\CountriesController::class, 'index'])->name('countries');
        Route::get('/list', [App\Http\Controllers\settings\CountriesController::class, 'listCountries'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\CountriesController::class, 'listData'])->name('list-data');
        Route::get('/fetch-countries/{id}', [App\Http\Controllers\settings\CountriesController::class, 'fetchCountries']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-countries']], function () {
    Route::group(['prefix' => 'countries'], function () {
        Route::get('/create-countries', [App\Http\Controllers\settings\CountriesController::class, 'createCountries']);
        Route::post('/create', [App\Http\Controllers\settings\CountriesController::class, 'insertCountriesData']);
        Route::post('/update', [App\Http\Controllers\settings\CountriesController::class, 'updateCountriesData']);
        Route::get('/edit-countries/{id}', [App\Http\Controllers\settings\CountriesController::class, 'editCountries']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\CountriesController::class, 'deleteCountries'])->name('delete');
    });
});


// Routes for Settings / States
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-states']], function () {
    Route::group(['prefix' => 'states'], function () {
        Route::get('/', [App\Http\Controllers\settings\StatesController::class, 'index'])->name('states');
        Route::get('/list', [App\Http\Controllers\settings\StatesController::class, 'listStates'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\StatesController::class, 'listData'])->name('list-data');
        Route::get('/list-country', [App\Http\Controllers\settings\StatesController::class, 'listCountries'])->name('list-country');
        Route::get('/fetch-states/{id}', [App\Http\Controllers\settings\StatesController::class, 'fetchStates']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-states']], function () {
    Route::group(['prefix' => 'states'], function () {
        Route::get('/create-states', [App\Http\Controllers\settings\StatesController::class, 'createStates']);
        Route::post('/create', [App\Http\Controllers\settings\StatesController::class, 'insertStatesData']);
        Route::post('/update', [App\Http\Controllers\settings\StatesController::class, 'updateStatesData']);
        Route::get('/edit-states/{id}', [App\Http\Controllers\settings\StatesController::class, 'editStates']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\StatesController::class, 'deleteStates'])->name('delete');
    });
});



// Routes for Settings / Cities
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-cities']], function () {
    Route::group(['prefix' => 'cities'], function () {
        Route::get('/', [App\Http\Controllers\settings\CitiesController::class, 'index'])->name('cities');
        Route::get('/list', [App\Http\Controllers\settings\CitiesController::class, 'listCities'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\CitiesController::class, 'listData'])->name('list-data');
        Route::get('/list-country', [App\Http\Controllers\settings\CitiesController::class, 'listCountries'])->name('list-country');
        Route::get('/list-state', [App\Http\Controllers\settings\CitiesController::class, 'listState'])->name('list-state');
        Route::get('/list-state-id/{id}', [App\Http\Controllers\settings\CitiesController::class, 'listStateByCountryId'])->name('list-state-id');
        Route::get('/list-city-id/{id}', [App\Http\Controllers\settings\CitiesController::class, 'listCityByStateId'])->name('list-city-id');
        Route::get('/list-city-by-country/{id}', [App\Http\Controllers\settings\CitiesController::class, 'listCityByCountryId'])->name('list-city-by-country');
        Route::get('/fetch-cities/{id}', [App\Http\Controllers\settings\CitiesController::class, 'fetchCities']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-cities']], function () {
    Route::group(['prefix' => 'cities'], function () {
        Route::get('/create-cities', [App\Http\Controllers\settings\CitiesController::class, 'createCities']);
        Route::post('/create', [App\Http\Controllers\settings\CitiesController::class, 'insertCitiesData']);
        Route::post('/update', [App\Http\Controllers\settings\CitiesController::class, 'updateCitiesData']);
        Route::get('/edit-cities/{id}', [App\Http\Controllers\settings\CitiesController::class, 'editCities']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\CitiesController::class, 'deleteCities'])->name('delete');
    });
});


// Routes for Settings / Transport Details
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-transport']], function () {
    Route::group(['prefix' => 'transport-details'], function () {
        Route::get('/', [App\Http\Controllers\settings\TransportDetailsController::class, 'index'])->name('transport-details');
        Route::get('/list', [App\Http\Controllers\settings\TransportDetailsController::class, 'listTransportDetails'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\TransportDetailsController::class, 'listData'])->name('list-data');
        Route::get('/fetch-transport-details/{id}', [App\Http\Controllers\settings\TransportDetailsController::class, 'fetchTransportDetails']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-transport']], function () {
    Route::group(['prefix' => 'transport-details'], function () {
        Route::get('/create-transport-details', [App\Http\Controllers\settings\TransportDetailsController::class, 'createTransportDetails']);
        Route::post('/create', [App\Http\Controllers\settings\TransportDetailsController::class, 'insertTransportDetailsData']);
        Route::post('/update', [App\Http\Controllers\settings\TransportDetailsController::class, 'updateTransportDetailsData']);
        Route::get('/edit-transport-details/{id}', [App\Http\Controllers\settings\TransportDetailsController::class, 'editTransportDetails']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\TransportDetailsController::class, 'deleteTransportDetails'])->name('delete');
    });
});


// Routes for Settings / Type of Address
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-type-of-address']], function () {
    Route::group(['prefix' => 'type-of-address'], function () {
        Route::get('/', [App\Http\Controllers\settings\TypeOfAddressController::class, 'index'])->name('type-of-address');
        Route::get('/list', [App\Http\Controllers\settings\TypeOfAddressController::class, 'listTypeOfAddress'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\TypeOfAddressController::class, 'listData'])->name('list-data');
        Route::get('/fetch-type-of-address/{id}', [App\Http\Controllers\settings\TypeOfAddressController::class, 'fetchTypeOfAddress']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-type-of-address']], function () {
    Route::group(['prefix' => 'type-of-address'], function () {
        Route::get('/create-type-of-address', [App\Http\Controllers\settings\TypeOfAddressController::class, 'createTypeOfAddress']);
        Route::post('/create', [App\Http\Controllers\settings\TypeOfAddressController::class, 'insertTypeOfAddressData']);
        Route::post('/update', [App\Http\Controllers\settings\TypeOfAddressController::class, 'updateTypeOfAddressData']);
        Route::get('/edit-type-of-address/{id}', [App\Http\Controllers\settings\TypeOfAddressController::class, 'editTypeOfAddress']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\TypeOfAddressController::class, 'deleteTypeOfAddress'])->name('delete');
    });
});


// Routes for Settings / Default Settings
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-default-settings']], function () {
    Route::group(['prefix' => 'default-settings'], function () {
        Route::get('/', [App\Http\Controllers\settings\DefaultSettingsController::class, 'index'])->name('default-settings');
        Route::get('/fetch-default-settings/{id}', [App\Http\Controllers\settings\DefaultSettingsController::class, 'fetchDefaultSettings']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-default-settings']], function () {
    Route::group(['prefix' => 'default-settings'], function () {
        Route::post('/update', [App\Http\Controllers\settings\DefaultSettingsController::class, 'updateDefaultSettingsData']);
        Route::get('/edit-default-settings/{id}', [App\Http\Controllers\settings\DefaultSettingsController::class, 'editDefaultSettings']);
    });
});


// Routes for Settings / Designation
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-designation']], function () {
    Route::group(['prefix' => 'designation'], function () {
        Route::get('/', [App\Http\Controllers\settings\DesignationController::class, 'index'])->name('designation');
        Route::get('/list', [App\Http\Controllers\settings\DesignationController::class, 'listDesignation'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\DesignationController::class, 'listData'])->name('list-data');
        Route::get('/fetch-designation/{id}', [App\Http\Controllers\settings\DesignationController::class, 'fetchDesignation']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-designation']], function () {
    Route::group(['prefix' => 'designation'], function () {
        Route::get('/create-designation', [App\Http\Controllers\settings\DesignationController::class, 'createDesignation']);
        Route::post('/create', [App\Http\Controllers\settings\DesignationController::class, 'insertDesignationData']);
        Route::post('/update', [App\Http\Controllers\settings\DesignationController::class, 'updateDesignationData']);
        Route::get('/edit-designation/{id}', [App\Http\Controllers\settings\DesignationController::class, 'editDesignation']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\DesignationController::class, 'deleteDesignation'])->name('delete');
    });
});


// Routes for Settings / SMS Settings
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-sms-settings']], function () {
    Route::group(['prefix' => 'sms-settings'], function () {
        Route::get('/', [App\Http\Controllers\settings\SmsSettingsController::class, 'index'])->name('sms-settings');
        Route::get('/list-designation', [App\Http\Controllers\settings\SmsSettingsController::class, 'listDesignation'])->name('list-designation');
        Route::get('/fetch-sms-settings/{id}', [App\Http\Controllers\settings\SmsSettingsController::class, 'fetchSmsSettings']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-sms-settings']], function () {
    Route::group(['prefix' => 'sms-settings'], function () {
        Route::post('/update', [App\Http\Controllers\settings\SmsSettingsController::class, 'updateSmsSettingsData']);
        Route::get('/edit-sms-settings/{id}', [App\Http\Controllers\settings\SmsSettingsController::class, 'editSmsSettings']);
    });
});


// Routes for Settings / Agent
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-agent']], function () {
    Route::group(['prefix' => 'agent'], function () {
        Route::get('/', [App\Http\Controllers\settings\AgentController::class, 'index'])->name('agent');
        Route::get('/list', [App\Http\Controllers\settings\AgentController::class, 'listAgent'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\AgentController::class, 'listData'])->name('list-data');
        Route::get('/fetch-agent/{id}', [App\Http\Controllers\settings\AgentController::class, 'fetchAgent']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-agent']], function () {
    Route::group(['prefix' => 'agent'], function () {
        Route::get('/create-agent', [App\Http\Controllers\settings\AgentController::class, 'createAgent']);
        Route::post('/create', [App\Http\Controllers\settings\AgentController::class, 'insertAgentData']);
        Route::post('/update', [App\Http\Controllers\settings\AgentController::class, 'updateAgentData']);
        Route::get('/edit-agent/{id}', [App\Http\Controllers\settings\AgentController::class, 'editAgent']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\AgentController::class, 'deleteAgent'])->name('delete');
    });
});


// Routes for Settings / Sale Bill Agent
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-sale-bill-agent']], function () {
    Route::group(['prefix' => 'sale-bill-agent'], function () {
        Route::get('/', [App\Http\Controllers\settings\SaleBillAgentController::class, 'index'])->name('sale-bill-agent');
        Route::get('/list', [App\Http\Controllers\settings\SaleBillAgentController::class, 'listSaleBillAgent'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\SaleBillAgentController::class, 'listData'])->name('list-data');
        Route::get('/fetch-sale-bill-agent/{id}', [App\Http\Controllers\settings\SaleBillAgentController::class, 'fetchSaleBillAgent']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-sale-bill-agent']], function () {
    Route::group(['prefix' => 'sale-bill-agent'], function () {
        Route::get('/create-sale-bill-agent', [App\Http\Controllers\settings\SaleBillAgentController::class, 'createSaleBillAgent']);
        Route::post('/create', [App\Http\Controllers\settings\SaleBillAgentController::class, 'insertSaleBillAgentData']);
        Route::post('/update', [App\Http\Controllers\settings\SaleBillAgentController::class, 'updateSaleBillAgentData']);
        Route::get('/edit-sale-bill-agent/{id}', [App\Http\Controllers\settings\SaleBillAgentController::class, 'editSaleBillAgent']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\SaleBillAgentController::class, 'deleteSaleBillAgent'])->name('delete');
    });
});


// Routes for Settings / Fabric Group
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-fabric-group']], function () {
    Route::group(['prefix' => 'fabricGroup'], function () {
        Route::get('/', [App\Http\Controllers\settings\FabricGroupController::class, 'index'])->name('fabricGroup');
        Route::get('/list', [App\Http\Controllers\settings\FabricGroupController::class, 'listFabricGroup'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\FabricGroupController::class, 'listData'])->name('list-data');
        Route::get('/fetch-fabricGroup/{id}', [App\Http\Controllers\settings\FabricGroupController::class, 'fetchFabricGroup']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-fabric-group']], function () {
    Route::group(['prefix' => 'fabricGroup'], function () {
        Route::get('/create-fabricGroup', [App\Http\Controllers\settings\FabricGroupController::class, 'createFabricGroup']);
        Route::post('/create', [App\Http\Controllers\settings\FabricGroupController::class, 'insertFabricGroupData']);
        Route::post('/update', [App\Http\Controllers\settings\FabricGroupController::class, 'updateFabricGroupData']);
        Route::get('/edit-fabricGroup/{id}', [App\Http\Controllers\settings\FabricGroupController::class, 'editFabricGroup']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\FabricGroupController::class, 'deleteFabricGroup'])->name('delete');
    });
});


// Routes for Settings / Company Type
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-companytype']], function () {
    Route::group(['prefix' => 'companyType'], function () {
        Route::get('/', [App\Http\Controllers\settings\CompanyTypeController::class, 'index'])->name('companyType');
        Route::get('/list', [App\Http\Controllers\settings\CompanyTypeController::class, 'listCompanyType'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\CompanyTypeController::class, 'listData'])->name('list-data');
        Route::get('/fetch-companyType/{id}', [App\Http\Controllers\settings\CompanyTypeController::class, 'fetchCompanyType']);
    });
});


Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-companytype']], function () {
    Route::group(['prefix' => 'companyType'], function () {
        Route::get('/create-companyType', [App\Http\Controllers\settings\CompanyTypeController::class, 'createCompanyType']);
        Route::post('/create', [App\Http\Controllers\settings\CompanyTypeController::class, 'insertCompanyTypeData']);
        Route::post('/update', [App\Http\Controllers\settings\CompanyTypeController::class, 'updateCompanyTypeData']);
        Route::get('/edit-companyType/{id}', [App\Http\Controllers\settings\CompanyTypeController::class, 'editCompanyType']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\CompanyTypeController::class, 'deleteCompanyType'])->name('delete');
    });
});


// Routes for Settings / Permissions
Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:access-permission']], function () {
    Route::group(['prefix' => 'permission'], function () {
        Route::get('/', [App\Http\Controllers\settings\PermissionController::class, 'index'])->name('permission');
        Route::get('/list', [App\Http\Controllers\settings\PermissionController::class, 'listPermission'])->name('list');
        Route::get('/list-data', [App\Http\Controllers\settings\PermissionController::class, 'listData'])->name('list-data');
        Route::get('/fetch-permission/{id}', [App\Http\Controllers\settings\PermissionController::class, 'fetchPermission']);
    });
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'permission:modify-permission']], function () {
    Route::group(['prefix' => 'permission'], function () {
        Route::get('/create-permission', [App\Http\Controllers\settings\PermissionController::class, 'createPermission']);
        Route::post('/create', [App\Http\Controllers\settings\PermissionController::class, 'insertPermissionData']);
        Route::post('/update', [App\Http\Controllers\settings\PermissionController::class, 'updatePermissionData']);
        Route::get('/edit-permission/{id}', [App\Http\Controllers\settings\PermissionController::class, 'editPermission']);
        Route::get('/delete/{id}', [App\Http\Controllers\settings\PermissionController::class, 'deletePermission'])->name('delete');
    });
});

Route::group(['prefix' => 'payments', 'middleware' => ['auth', 'permission:access-payment']], function () {
    Route::get('/list-seller', [App\Http\Controllers\PaymentsController::class, 'listSeller'])->name('list-seller');
    Route::get('/list-customer', [App\Http\Controllers\PaymentsController::class, 'listCustomer'])->name('list-customer');
    Route::get('/list-bank', [App\Http\Controllers\PaymentsController::class, 'listbank'])->name('list-bank');
    Route::post('/searchsalebill', [App\Http\Controllers\PaymentsController::class, 'searchSaleBill']);
    Route::get('/', [App\Http\Controllers\PaymentsController::class, 'index'])->name('payments');
    Route::get('/status/{status}', [App\Http\Controllers\PaymentsController::class, 'paymentStatus'])->name('paymentstatus');
    Route::get('/list', [App\Http\Controllers\PaymentsController::class, 'listpayment'])->name('list');
    Route::get('/incompletelist', [App\Http\Controllers\PaymentsController::class, 'inCompletelistpayment'])->name('incompletelist');
    Route::get('/goods_returns', [App\Http\Controllers\PaymentsController::class, 'goodReturn'])->name('goodreturn');
    Route::get('/goodreturn-list', [App\Http\Controllers\PaymentsController::class, 'goodreturnList'])->name('goodreturnlist');
    Route::get('/completelist', [App\Http\Controllers\PaymentsController::class, 'completelistpayment'])->name('completelist');
    Route::get('/list-data', [App\Http\Controllers\PaymentsController::class, 'listData'])->name('list-data');
    Route::get('/fetch-payment/{id}', [App\Http\Controllers\PaymentsController::class, 'fetchPayment']);
    Route::get('/view-payment/{id}', [App\Http\Controllers\PaymentsController::class, 'viewPayment']);
    Route::get('/view-voucher/{id}', [App\Http\Controllers\PaymentsController::class, 'viewVoucher']);
    Route::get('/view-goodreturn/{id}', [App\Http\Controllers\PaymentsController::class, 'viewGoodReturn']);
    Route::get('/fetch-voucher/{id}', [App\Http\Controllers\PaymentsController::class, 'fetchVoucher']);
    Route::get('/fetch-goodreturn/{id}', [App\Http\Controllers\PaymentsController::class, 'fetchGoodReturn']);
    Route::get('/getgoodreturnview/{id}', [App\Http\Controllers\PaymentsController::class, 'getGoodReturnView']);
    Route::get('/getbasicdata', [App\Http\Controllers\PaymentsController::class, 'getBasicData']);
    Route::get('/getsalbillforadd', [App\Http\Controllers\PaymentsController::class, 'getSalbillforAdd']);
    Route::post('/removesalebill', [App\Http\Controllers\PaymentsController::class, 'removeSalebill']);
    Route::get('/getReferenceForSaleBill', [App\Http\Controllers\PaymentsController::class, 'getReferenceForSaleBill']);
    Route::get('/getOldReferenceForSaleBill/{id}', [App\Http\Controllers\PaymentsController::class, 'getOldReferenceForSaleBill']);
    Route::get('/getsalebillwithproduct', [App\Http\Controllers\PaymentsController::class, 'getSalebillWithProduct']);
});

Route::group(['prefix' => 'payments', 'middleware' => ['auth', 'permission:modify-payment']], function () {
    Route::get('/create-payment', [App\Http\Controllers\PaymentsController::class, 'createPayment']);
    Route::get('/add-goodreturn/{id}', [App\Http\Controllers\PaymentsController::class, 'addGoodRetuen']);
    Route::get('/edit-goodreturn/{id}', [App\Http\Controllers\PaymentsController::class, 'editGoodRetuen']);
    Route::post('/insertgoodreturn', [App\Http\Controllers\PaymentsController::class, 'insertGoodRetuen']);
    Route::post('/updategoodreturn', [App\Http\Controllers\PaymentsController::class, 'updateGoodRetuen']);
    Route::post('/create', [App\Http\Controllers\PaymentsController::class, 'insertPaymentData']);
    Route::post('/generatepayment', [App\Http\Controllers\PaymentsController::class, 'generatePaymentData']);
    Route::post('/selectsalebills', [App\Http\Controllers\PaymentsController::class, 'selectSaleBills']);
    Route::get('/addpayment', [App\Http\Controllers\PaymentsController::class, 'addPayment'])->name('addpayment');
    Route::post('/update', [App\Http\Controllers\PaymentsController::class, 'updatePaymentData']);
    Route::post('/checkpendingpayment', [App\Http\Controllers\PaymentsController::class, 'checkPendingPayment']);
    Route::get('/edit-payment/{id}', [App\Http\Controllers\PaymentsController::class, 'editPayment']);
    Route::get('/delete/{id}', [App\Http\Controllers\PaymentsController::class, 'deletePayment'])->name('delete');
    Route::get('/deletegoodreturn/{id}', [App\Http\Controllers\PaymentsController::class, 'deleteGoodReturn'])->name('deletegoodreturn');
});

Route::group(['prefix' => 'commission', 'middleware' => ['auth', 'permission:access-commission']], function () {
    Route::get('/', [App\Http\Controllers\CommissionController::class, 'index'])->name('commission');
    Route::get('/list-company', [App\Http\Controllers\CommissionController::class, 'listCompany'])->name('listComapany');
    Route::get('/list', [App\Http\Controllers\CommissionController::class, 'listCommission'])->name('list');
    Route::get('/view-commission/{id}', [App\Http\Controllers\CommissionController::class, 'viewCommission']);
    Route::get('/getbasicdata', [App\Http\Controllers\CommissionController::class, 'getBasicData']);
    Route::get('/list-data', [App\Http\Controllers\CommissionController::class, 'listData'])->name('list-data');
    Route::get('/getReferenceForSaleBill', [App\Http\Controllers\CommissionController::class, 'getReferenceForSaleBill']);
    Route::get('/fetch-commission/{id}', [App\Http\Controllers\CommissionController::class, 'fetchCommission']);
});

Route::group(['prefix' => 'commission', 'middleware' => ['auth', 'permission:modify-commission']], function () {
    Route::get('/create-commission', [App\Http\Controllers\CommissionController::class, 'createCommission']);
    Route::get('/add-commission', [App\Http\Controllers\CommissionController::class, 'addCommission']);
    Route::post('/searchcommissioninvoice', [App\Http\Controllers\CommissionController::class, 'searchCommissionInvoice']);
    Route::post('/generate-commission', [App\Http\Controllers\CommissionController::class, 'generateCommissionData']);
    Route::post('/create', [App\Http\Controllers\CommissionController::class, 'insertCommission']);
    Route::post('/update', [App\Http\Controllers\CommissionController::class, 'updateCommission']);
    Route::post('/update-invoice-remarks', [App\Http\Controllers\CommissionController::class, 'updateInvoiceRemarks']);
    Route::get('/edit-commission/{id}', [App\Http\Controllers\CommissionController::class, 'editCommission']);
    Route::get('/delete/{id}', [App\Http\Controllers\CommissionController::class, 'deleteCommission'])->name('delete');
});

Route::group(['prefix' => 'reports', 'middleware' => ['auth', 'permission:access-reports']], function () {
    Route::get('/', [App\Http\Controllers\Reports\SalesReportController::class, 'index']);
    // Dropdown route start
    Route::get('/list-cities', [App\Http\Controllers\Reports\SalesReportController::class, 'listCities']);
    Route::get('/list-agents', [App\Http\Controllers\Reports\CommissionReportController::class, 'listAgents']);
    Route::get('/list-cities', [App\Http\Controllers\Reports\PaymentsReportController::class, 'listCities']);
    // Dropdown route end
    Route::get('/sales-register-report', [App\Http\Controllers\Reports\SalesReportController::class, 'salesRegister']);
    Route::get('/list-sales-register-data', [App\Http\Controllers\Reports\SalesReportController::class, 'listSalesRegisterData']);
    Route::get('/consolidate-monthly-sales-report', [App\Http\Controllers\Reports\SalesReportController::class, 'consolidateMonthlySales']);
    Route::post('/list-consolidate-monthly-sales-data', [App\Http\Controllers\Reports\SalesReportController::class, 'listConsolidateMonthlySales']);

    Route::get('/payment-register-report', [App\Http\Controllers\Reports\PaymentsReportController::class, 'paymentRegister']);
    Route::post('/list-payment-register-data', [App\Http\Controllers\Reports\PaymentsReportController::class, 'listPaymentRegisterData']);
    Route::post('/list-outstanding-payment-data', [App\Http\Controllers\Reports\PaymentsReportController::class, 'listOutstandingPaymentData']);
    
    Route::get('/outstanding-payment-report', [App\Http\Controllers\Reports\PaymentsReportController::class, 'outstandingPaymentReport']);

    Route::get('/commission-register-report', [App\Http\Controllers\Reports\CommissionReportController::class, 'commissionRegister']);
    Route::post('/list-commission-register-data', [App\Http\Controllers\Reports\CommissionReportController::class, 'listCommissionRegisterData']);
});

Route::get('/settings/connections', [App\Http\Controllers\settings\ConnectionController::class, 'index'])->name('connections');
