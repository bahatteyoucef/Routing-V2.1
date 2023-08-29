
//  Defaults
import {createApp}      from    'vue'
import App              from    './template/mainapp.vue'

import authentification from    './mixin/authentification'
import feedbacks        from    './mixin/feedbacks'
import globals          from    './mixin/globals'
import datatables       from    './mixin/datatables'
import responsive       from    './mixin/responsive'

import Map              from    './services/map'
import IndexedDB        from    './services/indexedDB'

// 

// Creating the app
const app           =   createApp(App)

// 

// Event Bus

import mitt from 'mitt'

const emitter       =   mitt()
app.config.globalProperties.emitter             =   emitter

app.config.globalProperties.$colors             =   [   '#A52714'       , '#F9A825'     , '#3949AB'     , '#817717'     , '#558B2F'     , 
                                                        '#097138'       , '#006064'     , '#01579B'     , '#1A237E'     , '#673AB7'     ,
                                                        '#4E342E'       , '#C2185B'     , '#FF5252'     , '#F57C00'     , '#000000'     ,
                                                        '#FFEA00'       , '#AFB42B'     , '#7CB342'     , '#0F9D58'     , '#0097A7'     ,
                                                        '#0288D1'       , '#FFD600'     , '#9C27B0'     , '#E65100'     , '#880E4F'     ,
                                                        '#795548'       , '#BDBDBD'     , '#757575'     , '#424243'     , '#FBC02D'     ]

// 

// JQuery
import jquery           from 'jquery'

window.$            =   window.jQuery   =   jquery

// 

// ToolTip

import Popper from "vue3-popper";

app.component("Popper", Popper);

// 

// Datatable

import 'datatables.net-bs5'
import jsZip from 'jszip';

import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.min';
import 'datatables.net-buttons/js/dataTables.buttons.min';
import 'datatables.net-buttons/js/buttons.flash.min';
import 'datatables.net-buttons/js/buttons.html5.min';
import 'datatables.net-buttons/js/buttons.print.min';

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

// Check if Mobile

if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {

    app.config.globalProperties.device  =   "mobile"
}

else {

    app.config.globalProperties.device  =   "laptop"
}

// 

// Mixin
app.mixin(authentification)
app.mixin(feedbacks)
app.mixin(globals)
app.mixin(datatables)
app.mixin(responsive)
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

// Singleton
if (window.navigator.onLine) {
    app.config.globalProperties.$map                    =   new Map()
}

// IndexedDB
app.config.globalProperties.$indexedDB                  =   new IndexedDB()

await app.config.globalProperties.$indexedDB.$indexedDB_intialiazeSetDATA()

//

// Internet
app.config.globalProperties.$connectedToInternet        =   window.navigator.onLine
window.addEventListener('online'    , ()    =>  {app.config.globalProperties.$connectedToInternet   =   true});
window.addEventListener('offline'   , ()    =>  {app.config.globalProperties.$connectedToInternet   =   false});

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
