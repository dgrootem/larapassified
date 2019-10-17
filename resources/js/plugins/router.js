import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);
export default new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        {
            path: '/',
            name: 'dashboard',
            component: () => import('../components/Dashboard.vue')
        },
        {
            path: '/employee',
            name: 'employee',
            component: () => import('../components/Employee.vue')
        },
        {
            path: '/school', 
            name: 'school',
            component: () => import('../components/School.vue')
        },
        {
            path: '/function',
            name: 'function',
            component: () => import('../components/Function.vue')
        },
        {
            path: '/admin/users',
            name: 'users',
            component: () => import('../components/admin/Users.vue')
        },
        {
            path: '/calendar',
            name: 'calendar',
            component: () => import('../components/Calendar.vue')
        },
        
    ]
});