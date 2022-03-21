require('./bootstrap');

import { createApp } from "vue";
import { createWebHistory, createRouter } from "vue-router";
// import router from './router';


import DashboardComponent from './components/DashboardComponent.vue';
// Databank
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

import CompanyCategoryComponent from './components/databank/companyCategoryComponents/CompanyCategoryComponent.vue';
import CreateCompanyCategoryComponent from './components/databank/companyCategoryComponents/CreateCompanyCategoryComponent.vue';

import FinancialYearComponent from './components/financialyear/FinancialYearComponent.vue';
import CreateFinancialYearComponent from './components/financialyear/CreateFinancialYearComponent.vue';

import CountriesComponent from './components/settings/countriesComponents/CountriesComponent.vue';
import CreateCountriesComponent from './components/settings/countriesComponents/CreateCountriesComponent.vue';

import StatesComponent from './components/settings/statesComponents/StatesComponent.vue';
import CreateStatesComponent from './components/settings/statesComponents/CreateStatesComponent.vue';

import CitiesComponent from './components/settings/citiesComponents/CitiesComponent.vue';
import CreateCitiesComponent from './components/settings/citiesComponents/CreateCitiesComponent.vue';

import TransportDetailsComponent from './components/settings/transportDetailsComponents/TransportDetailsComponent.vue';
import CreateTransportDetailsComponent from './components/settings/transportDetailsComponents/CreateTransportDetailsComponent.vue';
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
        path: '/financialyear/',
        component: FinancialYearComponent,
        children: [
            { path: 'create-financialyear', component: CreateFinancialYearComponent },
            { path: 'edit-financialyear/:id', component: CreateFinancialYearComponent },
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
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

createApp({
    components: {
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
        CompanyCategoryComponent,
        CreateCompanyCategoryComponent,


        FinancialYearComponent,
        CreateFinancialYearComponent,
        CountriesComponent,
        CreateCountriesComponent,
        StatesComponent,
        CreateStatesComponent,
        CreateCitiesComponent,
        CitiesComponent,
        CreateTransportDetailsComponent,
        TransportDetailsComponent,
    }
}).use(router).mount('#app');
