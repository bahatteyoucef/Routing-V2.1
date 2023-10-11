import { createRouter, createWebHistory } from "vue-router";

// mixin
import Authentification         from    "./mixin/authentification"

// store
import store                    from    "./store/store"

// index
import Index                    from    "./components/index/index.vue"

// login
import Login                    from    "./components/login/login.vue"

// users
import Users                    from    "./components/users/index.vue"

// RouteImportTempo
import RouteImportTempo         from    "./components/routes/imports/routeImportTempo.vue"

// RouteImportClients
import RouteImportClients       from    "./components/routes/routeImportClients.vue"

//

import RouteImportAdd                       from    "./components/routes/imports/RouteImportAdd.vue"
import ParRouteImportDetails                from    "./components/routes/obs/ParRouteImportDetails.vue"
import ParRouteImportFrontOfficeDetails     from    "./components/routes/obs/ParRouteImportFrontOfficeDetails.vue"

// 

import ClientsAdd                           from    "./components/clients/ClientAdd.vue"
import ClientsUpdate                        from    "./components/clients/ClientUpdate.vue"

//

// Declare a variable at the top of your router file
let initialRoute  =   true

//  //  //  //  //  //  //  //  //  //  //  //

const routes = [

    // Index
    {
        path        : "/",
        component   : Index
    },

    // Login
    {
        path        : "/login",
        component   : Login
    },

    // Users
    {
        path        : "/users",
        component   : Users
    },

    // Route Import Clients
    {
        path        : "/route_import/:id_route_import/clients",
        component   : RouteImportClients
    },

    // 

    // ClientsAdd
    {
        path        : "/route_import/:id_route_import/clients/add/:latitude/:longitude",
        component   : ClientsAdd
    },

    // ClientsUpdate
    {
        path        : "/route_import/:id_route_import/clients/:id_client/update",
        component   : ClientsUpdate
    },

    //  //  //  //  //  OBS     //  //  //

    //

    // Imports Tempo
    {
        path        : "/route/obs/route_import_tempo",
        component   : RouteImportTempo
    },

    // Imports Add
    {
        path        : "/route/obs/route_import/add",
        component   : RouteImportAdd
    },

    // Imports Details
    {
        path        : "/route/obs/route_import/:id_route_import/details",
        component   : ParRouteImportDetails
    },

    // Imports ParRouteImportFrontOfficeDetails Details
    {
        path        : "/route/frontoffice/obs/route_import/:id_route_import/details",
        component   : ParRouteImportFrontOfficeDetails
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {

    // Set Store Values
    Authentification.methods.$fillStore(store)

    if((to.path == "/login")&&(store.getters[`authentification/getIsAuthentificated`]   ==  false)) {

        next()
    }

    else {

        if((to.path != "/login")&&(store.getters[`authentification/getIsAuthentificated`]   ==  true)) {

            if((to.path != "/login")&&(Authentification.methods.$isRole("FrontOffice"))) {

                const pattern = /^\/route\/obs\/route_import\/\d+\/details$/;
                
                let route_obs_frontOffice   =   pattern.test(to.path);

                if(route_obs_frontOffice) {

                    next("/")
                }

                else {

                    if(initialRoute) {

                        initialRoute    =   false
                        next("/")
                    }

                    else {
                    
                        next()
                    }
                }
            }

            else {

                if(initialRoute) {

                    initialRoute    =   false
                    next("/")
                }

                else {

                    next()    
                }
            }
        }

        else {

            if((to.path == "/login")&&(store.getters[`authentification/getIsAuthentificated`]   ==  true)) {

                next('/')
            }

            else {

                if((to.path != "/login")&&(store.getters[`authentification/getIsAuthentificated`]   ==  false)) {

                    next('/login')
                }
            }
        }
    }
})

export default router;
