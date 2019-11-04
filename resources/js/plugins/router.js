import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);


const Dashboard = () => import('../components/Dashboard.vue');

export default new Router({
    mode: 'history',
    base: //'/tadd2/public/' , 
    process.env.BASE_URL,  // aangepast voor deploy
    routes: [
        {
            path: '/',
            name: 'dashboard',
            component: Dashboard,
            props: true
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
            path: '/admin/settings',
            name: 'settings',
            component: () => import('../components/admin/Instellingen.vue')
        },
        /*{
            path: '/calendar',
            name: 'calendar',
            component: () => import('../components/Calendar.vue')
        },*/
        
    ]
});