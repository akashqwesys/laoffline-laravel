require('./bootstrap');

import { createApp } from "vue";
import { createWebHistory, createRouter } from "vue-router";
// import router from './router';

import VueLoader from './VueLoader.vue';

import DashboardComponent from './components/DashboardComponent.vue';

// Databank Menu
import UserGroupComponent from './components/databank/userGroupComponents/UserGroupComponent.vue';
import CreateUserGroupComponent from './components/databank/userGroupComponents/CreateUserGroupComponent.vue';

import EmployeeComponent from './components/databank/employeeComponents/EmployeeComponent.vue';
import CreateEmployeeComponent from './components/databank/employeeComponents/CreateEmployeeComponent.vue';

import ProductCategoryComponent from './components/databank/productCategoryComponents/ProductCategoryComponent.vue';
import CreateProductCategoryComponent from './components/databank/productCategoryComponents/CreateProductCategoryComponent.vue';

import ProductSubCategoryComponent from './components/databank/productSubCategoryComponents/ProductSubCategoryComponent.vue';
import CreateProductSubCategoryComponent from './components/databank/productSubCategoryComponents/CreateProductSubCategoryComponent.vue';

import ProductComponent from './components/databank/productsComponents/ProductComponent.vue';
import CreateProductComponent from './components/databank/productsComponents/CreateProductComponent.vue';

import CompanyComponent from './components/databank/companyComponents/CompanyComponent.vue';
import CreateCompanyComponent from './components/databank/companyComponents/CreateCompanyComponent.vue';
import ViewCompanyComponent from './components/databank/companyComponents/ViewCompanyComponent.vue';
import EssentialCompanyComponent from './components/databank/companyComponents/EssentialCompanyComponent.vue';

import CompanyCategoryComponent from './components/databank/companyCategoryComponents/CompanyCategoryComponent.vue';
import CreateCompanyCategoryComponent from './components/databank/companyCategoryComponents/CreateCompanyCategoryComponent.vue';
// import ViewCompanyDetailsModelComponent from './components/databank/companyComponents/modal/ViewCompanyDetailsModelComponent.vue';

import LinkCompaniesComponent from './components/databank/linkCompaniesComponents/LinkCompaniesComponent.vue';
import CreateLinkCompaniesComponent from './components/databank/linkCompaniesComponents/CreateLinkCompaniesComponent.vue';

import FinancialYearComponent from './components/financialyear/FinancialYearComponent.vue';
import CreateFinancialYearComponent from './components/financialyear/CreateFinancialYearComponent.vue';

// Settings Menu
import BankDetailsComponent from "./components/settings/bankDetailsComponents/BankDetailsComponent.vue";
import CreateBankDetailsComponent from "./components/settings/bankDetailsComponents/CreateBankDetailsComponent.vue";

import CountriesComponent from './components/settings/countriesComponents/CountriesComponent.vue';
import CreateCountriesComponent from './components/settings/countriesComponents/CreateCountriesComponent.vue';

import StatesComponent from './components/settings/statesComponents/StatesComponent.vue';
import CreateStatesComponent from './components/settings/statesComponents/CreateStatesComponent.vue';

import CitiesComponent from './components/settings/citiesComponents/CitiesComponent.vue';
import CreateCitiesComponent from './components/settings/citiesComponents/CreateCitiesComponent.vue';

import TransportDetailsComponent from './components/settings/transportDetailsComponents/TransportDetailsComponent.vue';
import CreateTransportDetailsComponent from './components/settings/transportDetailsComponents/CreateTransportDetailsComponent.vue';

import TypeOfAddressComponent from './components/settings/typeOfAddressComponents/TypeOfAddressComponent.vue';
import CreateTypeOfAddressComponent from './components/settings/typeOfAddressComponents/CreateTypeOfAddressComponent.vue';

import CreateDefaultSettingsComponent from './components/settings/defaultSettingsComponents/CreateDefaultSettingsComponent.vue';

import CreateSmsSettingsComponent from './components/settings/smsSettingsComponents/CreateSmsSettingsComponent.vue';

import DesignationComponent from './components/settings/designationComponents/DesignationComponent.vue';
import CreateDesignationComponent from './components/settings/designationComponents/CreateDesignationComponent.vue';

import AgentComponent from './components/settings/agentComponents/AgentComponent.vue';
import CreateAgentComponent from './components/settings/agentComponents/CreateAgentComponent.vue';

import SaleBillAgentComponent from './components/settings/saleBillAgentComponents/SaleBillAgentComponent.vue';
import CreateSaleBillAgentComponent from './components/settings/saleBillAgentComponents/CreateSaleBillAgentComponent.vue';

import FabricGroupComponent from './components/settings/fabricGroupComponents/FabricGroupComponent.vue';
import CreateFabricGroupComponent from './components/settings/fabricGroupComponents/CreateFabricGroupComponent.vue';

import CompanyTypeComponent from './components/settings/companyTypeComponents/CompanyTypeComponent.vue';
import CreateCompanyTypeComponent from './components/settings/companyTypeComponents/CreateCompanyTypeComponent.vue';

import PermissionComponent from './components/settings/permissionsComponents/PermissionComponent.vue';
import CreatePermissionComponent from './components/settings/permissionsComponents/CreatePermissionComponent.vue';

import PaymentComponent from './components/payment/PaymentComponent.vue';
import CreatePaymentComponent from './components/payment/CreatePaymentComponent.vue'
import AddPaymentComponent from './components/payment/AddPaymentComponent.vue'
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
const routes = [
    {
        path: '/home',
        component: DashboardComponent
    },
    {
        path: '/databank/users-group',
        component: UserGroupComponent,
        children: [
            { path: 'create-user-group', component: CreateUserGroupComponent },
            { path: 'edit-user-group/:id', component: CreateUserGroupComponent },
        ]
    },
    {
        path: '/databank/employee',
        component: EmployeeComponent,
        children: [
            { path: 'create-employee', component: CreateEmployeeComponent },
            { path: 'edit-employee/:id', component: CreateEmployeeComponent },
        ]
    },
    {
        path: '/databank/product-category',
        component: ProductCategoryComponent,
        children: [
            { path: 'create-product-category', component: CreateProductCategoryComponent },
            { path: 'edit-product-category/:id', component: CreateProductCategoryComponent },
        ]
    },
    {
        path: '/databank/productsub-category',
        component: ProductSubCategoryComponent,
        children: [
            { path: 'create-productsub-category', component: CreateProductSubCategoryComponent },
            { path: 'edit-productsub-category/:id', component: CreateProductSubCategoryComponent },
        ]
    },
    {
        path: '/databank/catalog',
        component: ProductComponent,
        children: [
            { path: 'create-products', component: CreateProductComponent },
            { path: 'edit-products/:id', component: CreateProductComponent },
        ]
    },
    {
        path: '/databank/companies',
        component: CompanyComponent,
        children: [
            { path: 'create-company', component: CreateCompanyComponent },
            { path: 'edit-company/:id', component: CreateCompanyComponent },
            { path: 'view-company/:id', component: ViewCompanyComponent },
            { path: 'essential', component: EssentialCompanyComponent },
        ]
    },
    {
        path: '/databank/companyCategory',
        component: CompanyCategoryComponent,
        children: [
            { path: 'create-company-category', component: CreateCompanyCategoryComponent },
            { path: 'edit-company-category/:id', component: CreateCompanyCategoryComponent },
        ]
    },
    {
        path: '/databank/link-company',
        component: LinkCompaniesComponent,
        children: [
            { path: 'create-link-company', component: CreateLinkCompaniesComponent },
            { path: 'edit-link-company/:id', component: CreateLinkCompaniesComponent },
        ]
    },
    {
        path: '/financialyear/',
        component: FinancialYearComponent,
        children: [
            { path: 'create-financialyear', component: CreateFinancialYearComponent },
            { path: 'edit-financialyear/:id', component: CreateFinancialYearComponent },
        ]
    },
    {
        path: '/settings/bank-details/',
        component: BankDetailsComponent,
        children: [
            { path: 'create-bank-details', component: CreateBankDetailsComponent },
            { path: 'edit-bank-details/:id', component: CreateBankDetailsComponent },
        ]
    },
    {
        path: '/settings/countries/',
        component: CountriesComponent,
        children: [
            { path: 'create-countries', component: CreateCountriesComponent },
            { path: 'edit-countries/:id', component: CreateCountriesComponent },
        ]
    },
    {
        path: '/settings/states/',
        component: StatesComponent,
        children: [
            { path: 'create-states', component: CreateStatesComponent },
            { path: 'edit-states/:id', component: CreateStatesComponent },
        ]
    },
    {
        path: '/settings/cities/',
        component: CitiesComponent,
        children: [
            { path: 'create-cities', component: CreateCitiesComponent },
            { path: 'edit-cities/:id', component: CreateCitiesComponent },
        ]
    },
    {
        path: '/settings/transport-details/',
        component: TransportDetailsComponent,
        children: [
            { path: 'create-transport-details', component: CreateTransportDetailsComponent },
            { path: 'edit-transport-details/:id', component: CreateTransportDetailsComponent },
        ]
    },
    {
        path: '/settings/type-of-address/',
        component: TypeOfAddressComponent,
        children: [
            { path: 'create-type-of-address', component: CreateTypeOfAddressComponent },
            { path: 'edit-type-of-address/:id', component: CreateTypeOfAddressComponent },
        ]
    },
    {
        path: '/settings/default-settings/',
        component: CreateDefaultSettingsComponent,
    },
    {
        path: '/settings/designation/',
        component: DesignationComponent,
        children: [
            { path: 'create-designation', component: CreateDesignationComponent },
            { path: 'edit-designation/:id', component: CreateDesignationComponent },
        ]
    },
    {
        path: '/settings/sms-settings/',
        component: CreateSmsSettingsComponent,
    },
    {
        path: '/settings/agent/',
        component: AgentComponent,
        children: [
            { path: 'create-agent', component: CreateAgentComponent },
            { path: 'edit-agent/:id', component: CreateAgentComponent },
        ]
    },
    {
        path: '/settings/sale-bill-agent/',
        component: SaleBillAgentComponent,
        children: [
            { path: 'create-sale-bill-agent', component: CreateSaleBillAgentComponent },
            { path: 'edit-sale-bill-agent/:id', component: CreateSaleBillAgentComponent },
        ]
    },
    {
        path: '/settings/fabricGroup/',
        component: FabricGroupComponent,
        children: [
            { path: 'create-fabricGroup', component: CreateFabricGroupComponent },
            { path: 'edit-fabricGroup/:id', component: CreateFabricGroupComponent },
        ]
    },
    {
        path: '/settings/companyType/',
        component: CompanyTypeComponent,
        children: [
            { path: 'create-companyType', component: CreateCompanyTypeComponent },
            { path: 'edit-companyType/:id', component: CreateCompanyTypeComponent },
        ]
    },
    {
        path: '/settings/permission/',
        component: PermissionComponent,
        children: [
            { path: 'create-permission', component: CreatePermissionComponent },
            { path: 'edit-permission/:id', component: CreatePermissionComponent },
        ]
    },
    {
        path: '/payments/',
        component: PaymentComponent,
        children: [
            { path: 'create-payments', component: CreatePaymentComponent },
            { path: 'edit-payments/:id', component: AddPaymentComponent },
            { path: 'addpayment', component: AddPaymentComponent },
        ]
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

createApp({
    components: {
        VueLoader,
        DashboardComponent,
        UserGroupComponent,
        CreateUserGroupComponent,
        EmployeeComponent,
        CreateEmployeeComponent,
        ProductCategoryComponent,
        CreateProductCategoryComponent,
        ProductSubCategoryComponent,
        CreateProductSubCategoryComponent,
        ProductComponent,
        CreateProductComponent,
        CompanyComponent,
        CreateCompanyComponent,
        ViewCompanyComponent,
        EssentialCompanyComponent,
        CompanyCategoryComponent,
        CreateCompanyCategoryComponent,
        // ViewCompanyDetailsModelComponent,
        LinkCompaniesComponent,
        CreateLinkCompaniesComponent,

        FinancialYearComponent,
        CreateFinancialYearComponent,

        BankDetailsComponent,
        CreateBankDetailsComponent,
        CreateDefaultSettingsComponent,
        CreateTypeOfAddressComponent,
        TypeOfAddressComponent,

        CountriesComponent,
        CreateCountriesComponent,
        StatesComponent,
        CreateStatesComponent,
        CitiesComponent,
        CreateCitiesComponent,
        TransportDetailsComponent,
        CreateTransportDetailsComponent,
        DesignationComponent,
        CreateDesignationComponent,
        CreateSmsSettingsComponent,
        AgentComponent,
        CreateAgentComponent,
        SaleBillAgentComponent,
        CreateSaleBillAgentComponent,
        FabricGroupComponent,
        CreateFabricGroupComponent,
        CompanyTypeComponent,
        CreateCompanyTypeComponent,
        PermissionComponent,
        CreatePermissionComponent,
        PaymentComponent,
        CreatePaymentComponent,
        AddPaymentComponent,
    }
}).use(router).mount('#app');
