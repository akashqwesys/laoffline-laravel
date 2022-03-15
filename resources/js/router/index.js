import { createWebHistory, createRouter } from "vue-router";

import DashboardComponent from '../components/DashboardComponent.vue';
import UserGroupComponent from '../components/databank/userGroupComponents/UserGroupComponent.vue';
import CreateUserGroupComponent from '../components/databank/userGroupComponents/CreateUserGroupComponent.vue';
import EmployeeComponent from '../components/databank/employeeComponents/EmployeeComponent.vue';
import CreateEmployeeComponent from '../components/databank/employeeComponents/CreateEmployeeComponent.vue';

const routes = [
    {
        path: '/home',
        component: DashboardComponent
    },
    {
        path: '/databank/users-group',
        component: UserGroupComponent,
        children: [
            { path: 'create-user-group', component: CreateUserGroupComponent }
        ]
    },
    {
        path: '/databank/employee',
        component: EmployeeComponent,
        children: [
            { path: 'create-employee', component: CreateEmployeeComponent },
        ]
    },
];

export default createRouter({
    history: createWebHistory(),
    routes
})
