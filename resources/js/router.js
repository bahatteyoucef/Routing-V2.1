import { createRouter, createWebHistory }   from    "vue-router";

// mixin
import Authentification                     from    "./mixin/authentification"

// store
import store                                from    "./store/store"

// Declare a variable at the top of your router file
let initialRoute  =   true

//  //  //  //  //  //  //  //  //  //  //  //

// Routes that can be used offline i should remove dynamique import from them

import ClientDetails                        from    "./components/clients/permanent/ClientDetails.vue"

import RouteImportClientsByStatus           from    "./components/routes/routeImportClientsByStatus.vue"

//  //  //  //  //  //  //  //  //  //  //  //

const routes = [

    // Index
    {
        path        : "/",
        component   : () => import('./components/index/index.vue')
    },

    // Login
    {
        path        : "/login",
        component   : () => import('./components/login/login.vue')
    },

    // Users
    {
        path        : "/users",
        component   : () => import('./components/users/index.vue')
    },

    // ShowUsers
    {
        path        : "/users/:id_user/show",
        component   : () => import('./components/users/show.vue')
    },

    // UserUpdate
    {
        path        : "/users/:id_user/update",
        component   : () => import('./components/users/UserUpdate.vue')
    },

    // UserUpdate Password
    {
        path        : "/users/:id_user/update/password",
        component   : () => import('./components/users/changePassword.vue')
    },

    // 

    // ClientsAdd
    {
        path        : "/route_import/:id_route_import/clients/add",
        component   : () => import('./components/clients/permanent/ClientAddCurrentPosition.vue')
    },

    // ClientsAdd
    {
        path        : "/route_import/:id_route_import/clients/add/:latitude/:longitude",
        component   : () => import('./components/clients/permanent/ClientAdd.vue')
    },

    // ClientsUpdate
    {
        path        : "/route_import/:id_route_import/clients/:id_client/update",
        component   : () => import('./components/clients/permanent/ClientUpdate.vue')
    },

    // Stats
    {
        path        : "/stats",
        component   : () => import('./components/stats/index.vue')
    },

    // Imports Tempo
    {
        path        : "/route/obs/route_import_tempo",
        component   : () => import('./components/routes/temporary/routeImportTempo.vue')
    },

    // Route Import Clients
    {
        path        : "/route_import/:id_route_import/clients",
        component   : () => import('./components/routes/routeImportClients.vue')
    },

    // Imports Add
    {
        path        : "/route/obs/route_import/add",
        component   : () => import('./components/routes/RouteImportAdd.vue')
    },

    // Imports Details
    {
        path        : "/route/obs/route_import/:id_route_import/details",
        component   : () => import('./components/routes/permanent/obs/ParRouteImportDetails.vue')
    },

    //  //  //  //  //  FrontOffice //  //  //  //  //

    // ClientDetails
    {
        path        : "/route_import/:id_route_import/clients/:id_client/details",
        component   : ClientDetails
    },

    // Route Import Clients
    {
        path        : "/route_import/:id_route_import/clients/by_status",
        component   : RouteImportClientsByStatus
    },

    // Imports ParRouteImportFrontOfficeDetails Details
    {
        path        : "/route/frontoffice/obs/route_import/:id_route_import/details",
        component   : () => import('./components/routes/permanent/obs/ParRouteImportFrontOfficeDetails.vue')
    },

    // Route Import Selected Clients
    {
        path        : "/route/frontoffice/obs/route_import/:id_route_import/clients/selected",
        component   : () => import('./components/routes/permanent/obs/showCustomersMap.vue')
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
