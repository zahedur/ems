import Vue from 'vue';
import vueRouter from 'vue-router';
import store from '../store';
Vue.use(vueRouter);

import welcome from "../pages/welcome";
import login from '../pages/auth/login';
import changePassword from "../pages/profile/changePassword";
import forgotPassword from "../pages/auth/forgotPassword";
import resetPassword from "../pages/auth/resetPassword";

import dashboard from '../pages/dashboard';

import employees from '../pages/employees/employees';
import giveSalary from '../pages/salaries/giveSalary';

import reportDate from "../pages/reports/reportDate";
import reportMonthly from "../pages/reports/reportMonthly";
import reportYearly from "../pages/reports/reportYearly";

import departments from "../pages/settings/departments";
import designations from "../pages/settings/designations";
import leaves from "../pages/settings/leaves";
import salaries from "../pages/settings/salaries";
import general from '../pages/settings/general';
import profile from "../pages/profile/profile";


const routes = [
    { path: '/auth/login', name: 'login', component: login, meta: { visitor: true } },
    { path: '/auth/forgot-password', name: 'forgotPassword', component: forgotPassword, meta: { visitor: true } },
    { path: '/auth/reset-password', name: 'resetPassword', component: resetPassword, meta: { visitor: true } },

    //Dashboard
    { path: '/', name: 'dashboard', component: dashboard, meta: { requiresAuth: true } },

    // All Products
    { path: '/employees', name: 'employees', component: employees, meta: { requiresAuth: true } },

    // Employees Salary
    { path: '/give-salary', name: 'giveSalary', component: giveSalary, meta: { requiresAuth: true } },

    //Settings
    { path: '/settings/general', name: 'settingsGeneral', component: general, meta: { requiresAuth: true } },
    { path: '/departments', name: 'departments', component: departments, meta: { requiresAuth: true } },
    { path: '/designations', name: 'designations', component: designations, meta: { requiresAuth: true } },
    { path: '/leaves', name: 'leaves', component: leaves, meta: { requiresAuth: true } },
    { path: '/salaries', name: 'salaries', component: salaries, meta: { requiresAuth: true } },

    //Profile
    { path: '/profile', name: 'profile', component: profile, meta: { requiresAuth: true } },
    { path: '/change-password', name: 'changePassword', component: changePassword, meta: { requiresAuth: true } },

    //Reports
    { path: '/reports/date', name: 'reportDate', component: reportDate, meta: { requiresAuth: true } },
    { path: '/reports/monthly', name: 'reportMonthly', component: reportMonthly, meta: { requiresAuth: true } },
    { path: '/reports/yearly', name: 'reportYearly', component: reportYearly, meta: { requiresAuth: true } },
]

const router = new vueRouter({
    routes,
    mode: 'history',
    base: process.env.BASE_URL
});


//check auth
router.beforeEach((to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) {

        //store.dispatch('auth/isAuthenticated');
        //store.dispatch('auth/refresh');

        if (!store.getters['auth/authenticated']) {
            store.commit('auth/setAuthData');
        }

        if (to.name !== 'login' && !store.getters['auth/authenticated']) {
            next({
                name: 'login',
                //query: { redirect: to.fullPath }
            })
        }
        else {
            next();
        }
    }
    else if (to.matched.some(record => record.meta.visitor)) {

        store.commit('auth/setAuthData');

        if (store.getters['auth/authenticated']) {
            next({
                name: 'dashboard',
                //query: { redirect: to.fullPath }
            })
        }
        else {
            next();
        }
    }
    else {
        next() // make sure to always call next()!
    }
});



export default router;
