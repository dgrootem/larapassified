import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);


const Dashboard = () => import('../components/Dashboard.vue');
const Ingave = () => import('../components/Ingave.vue');
const Profile = () => import('../components/admin/Profile.vue');

export default new Router({
    mode: 'hash',
    base: 
    //    '/tadd2/public/' ,  //uncomment for deploy!!
    process.env.BASE_URL,  // aangepast voor deploy
    routes: [
        {
            path: '/',
            name: 'dashboard',
            component: Dashboard
        },
        {
            path: '/ingave',
            name: 'ingave',
            component: Ingave,
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
        {
            path: '/admin/archief',
            name: 'archief',
            component: () => import('../components/admin/Archief.vue')
        },
        {
            path: '/admin/myprofile',
            name: 'myprofile',
            component: Profile,
            props: true
        },
        /*{
            path: '/calendar',
            name: 'calendar',
            component: () => import('../components/Calendar.vue')
        },*/
        
    ]
});