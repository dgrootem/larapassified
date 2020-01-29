/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('../sass/fake.scss');

//window.Vue = require('vue');

import Vue from 'vue'
import '@mdi/font/css/materialdesignicons.css'
// import VueRouter from 'vue-router';
import router from './plugins/router';
import vuetify from './plugins/vuetify' // path to vuetify export

// Vue.use(VueRouter);
window.Vue = Vue;
/*
import CompaniesIndex from './components/companies/CompaniesIndex.vue';
import CompaniesCreate from './components/companies/CompaniesCreate.vue';
import CompaniesEdit from './components/companies/CompaniesEdit.vue';*/
/*
const routes = [
    {
        path: '/',
        components: {
            companiesIndex: CompaniesIndex
        }
    },
    {path: '/admin/companies/create', component: CompaniesCreate, name: 'createCompany'},
    {path: '/admin/companies/edit/:id', component: CompaniesEdit, name: 'editCompany'},
]

const router = new VueRouter({ routes })
*/
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

new Vue({
    vuetify, router,
    props: {
        user : {
            id : -1,
            isadmin : false,
            isactive : false,
            readonly : true
        },
    },
    data: () => ({
        drawer: null,
        settings: {
            naarIngaveNaAanmaak: { name: null, value: true, omschrijving: "naarIngaveNaAanmaak" },
            naarIngaveNaUpdate: { name: null, value: false, omschrijving: "naarIngaveNaUpdate" },
            showAddForNewEmployee: { name: null, value: true, omschrijving: "showAddForNewEmployee" },
            aantalRijenPerLijst : { name: null, value: 50, omschrijving: 'aantalRijenPerLijst' },
            mainlogo : { name: null, value: 'logo', omschrijving: 'logo' },
          },
        
      }),
      methods:{
        loadSettings() {
            var app = this;
            axios
            .get("api/v1/settingsByContext/app")
            .then(function(resp) {
                let settingsFromDB = resp.data;
                let s = null;
                for (s in settingsFromDB) {
                    let n = settingsFromDB[s].name;
                    app.settings[n] = settingsFromDB[s];
                    if (!isNaN(app.settings[n].value))
                        app.settings[n].value = parseInt(app.settings[n].value);
                    // else
                        // console.log('cannot convert to number value for '+n+' ('+app.settings[n]+')');
                    
                }
                return true;
            })
            .catch(function(resp) {
                console.error(resp);
                throw "Could not load settings";
            });
          }
      },
      created() {
          try{
                this.loadSettings();
          }catch(e){
            console.log(e);
            alert("Could not load settings");
          };
      }
  }).$mount('#app')
