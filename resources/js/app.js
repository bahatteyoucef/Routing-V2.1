
//  Defaults
import {createApp}      from    'vue'
import App              from    './template/mainapp.vue'

import authentification from    './mixin/authentification'
import feedbacks        from    './mixin/feedbacks'
import globals          from    './mixin/globals'
import datatables       from    './mixin/datatables'

import Map              from    './services/map'
// 

// Creating the app
const app           =   createApp(App)

// 

// Singleton

app.config.globalProperties.$map        =   new Map()

// 

// Event Bus

import mitt from 'mitt'

const emitter       =   mitt()
app.config.globalProperties.emitter     =   emitter

// 

// JQuery
import jquery           from 'jquery'

window.$            =   window.jQuery   =   jquery

// 

// Datatable

import 'datatables.net-bs5'
import jsZip from 'jszip';

import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.min';
import 'datatables.net-buttons/js/dataTables.buttons.min';
import 'datatables.net-buttons/js/buttons.flash.min';
import 'datatables.net-buttons/js/buttons.html5.min';

import 'datatables.net-select-bs5'

import 'datatables.net-select-bs5/css/select.bootstrap5.min.css'
import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css'

window.JSZip = jsZip;

// 

// Cookies
import VueCookies from 'vue-cookies';
app.use(VueCookies);

// 

// MDI 
import "@mdi/font/css/materialdesignicons.css";

// 

// XLSX (excel)

// 

// Auth Config
import axios from 'axios'

// Base URL
const url = new URL(window.location.href);

axios.defaults.baseURL  = url.protocol+'//'+url.hostname

if(url.port) {

    axios.defaults.baseURL  = axios.defaults.baseURL + ':' + url.port
}

axios.defaults.baseURL  =   axios.defaults.baseURL  +   '/api/'

//

// Update Authorization
if(JSON.parse(localStorage.getItem('vuex')) !=  null) {

    window.$cookies.set('access_token'  , JSON.parse(localStorage.getItem('vuex'))["access_token"])
    axios.defaults.headers['Authorization'] = `Bearer ${window.$cookies.get('access_token')}`
}

// 

// Mixin
app.mixin(authentification)
app.mixin(feedbacks)
app.mixin(globals)
app.mixin(datatables)
// 

//Sweet Alert
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

app.use(VueSweetalert2);

//

// Routing
import router       from    './router'
app.use(router)

// 

// VueX
import store        from    './store/store'
app.use(store)

// 

// Bootstrap
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"

import  {Modal}   from    'bootstrap';
window.Modal = Modal;

// 

// Template
import Header       from    "./template/partials/header.vue";
import Sidebar      from    "./template/partials/sidebar.vue";
import Footer       from    "./template/partials/footer.vue";

app.component('header-part'     ,   Header)
app.component('sidebar-part'    ,   Sidebar)
app.component('footer-part'     ,   Footer)

//

// Route
import HeaderRoute              from    "./template/routePartials/headerRoute.vue";
import SidebarRoute             from    "./template/routePartials/sidebarRoute.vue";
import FooterRoute              from    "./template/routePartials/footerRoute.vue";
import MapRoute                 from    "./template/routePartials/mapRoute.vue";

app.component('header-route-part'               ,   HeaderRoute)
app.component('sidebar-route-part'              ,   SidebarRoute)
app.component('footer-route-part'               ,   FooterRoute)
app.component('map-route-part'                  ,   MapRoute)

//

// Sync Page

import LoadingPage              from    "./template/partials/loadingPage.vue";

import LoginPage                from    "./components/login/login.vue"

app.component('loading-page'                    ,   LoadingPage)
app.component('login-page'                      ,   LoginPage)

// 

app.component('main-app'                        ,   App.default)

// 

// Mounting
app.mount("#app")

// 
