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
    }
}).use(router).mount('#app');
