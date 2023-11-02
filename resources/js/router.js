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
import ShowUsers                from    "./components/users/show.vue"
import UserUpdate               from    "./components/users/UserUpdate.vue"
import changePassword           from    "./components/users/changePassword.vue"

// Produits
import Produits                 from    "./components/produits/index.vue"
import AddProduits              from    "./components/produits/AddProduits.vue"
import UpdateProduits           from    "./components/produits/UpdateProduits.vue"

// Marques
import Marques                  from    "./components/marques/index.vue"
import AddMarques               from    "./components/marques/AddMarques.vue"
import UpdateMarques            from    "./components/marques/UpdateMarques.vue"

// Categories
import Categories               from    "./components/categories/index.vue"
import AddCategories            from    "./components/categories/AddCategories.vue"
import UpdateCategories         from    "./components/categories/UpdateCategories.vue"

// Types
import Types                    from    "./components/types/index.vue"
import AddTypes                 from    "./components/types/AddTypes.vue"
import UpdateTypes              from    "./components/types/UpdateTypes.vue"

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

import ChoixClient                          from    "./components/clients/ChoixClient.vue"

import AddComplaint                         from    "./components/clients/AddComplaint.vue"

import AddOrder                             from    "./components/produits/AddOrder.vue"

import Prices                               from    "./components/produits/Prices.vue"

import ClientCompetitorsAdd                 from    "./components/produits/ClientCompetitorsAdd.vue"
import ClientStockAdd                       from    "./components/produits/ClientStockAdd.vue"

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

    // ShowUsers
    {
        path        : "/users/:id_user/show",
        component   : ShowUsers
    },

    // UserUpdate
    {
        path        : "/users/:id_user/update",
        component   : UserUpdate        
    },

    // UserUpdate Password
    {
        path        : "/users/:id_user/update/password",
        component   : changePassword        
    },

    //

    // Produits
    {
        path        : "/produits",
        component   : Produits        
    },

    // 
    {
        path        : "/produits/add",
        component   : AddProduits        
    },

    //
    {
        path        : "/produits/:id_produit/update",
        component   : UpdateProduits        
    },

    //
    {
        path        : "/route_import/:id_route_import/produits/:id_produit/prices",
        component   : Prices
    },

    // Categories
    {
        path        : "/categories",
        component   : Categories        
    },

    //
    {
        path        : "/categories/add",
        component   : AddCategories        
    },

    //
    {
        path        : "/categories/:id_categorie/update",
        component   : UpdateCategories        
    },

    // Types
    {
        path        : "/types",
        component   : Types        
    },

    //
    {
        path        : "/types/add",
        component   : AddTypes        
    },

    //
    {
        path        : "/types/:id_type/update",
        component   : UpdateTypes        
    },

    // Marques
    {
        path        : "/marques",
        component   : Marques        
    },

    //
    {
        path        : "/marques/add",
        component   : AddMarques        
    },

    //
    {
        path        : "/marques/:id_marque/update",
        component   : UpdateMarques        
    },

    //

    // Route Import Clients
    {
        path        : "/route_import/:id_route_import/clients",
        component   : RouteImportClients
    },

    // 

    //

    //

    //

    //

    // Choix Client
    {
        path        : "/route_import/:id_route_import/clients/:id_client/choix",
        component   : ChoixClient
    },

    // Stock 
    {
        path        : "/route_import/:id_route_import/clients/:id_client/products/stock/add",
        component   : ClientStockAdd
    },

    // Competitor 
    {
        path        : "/route_import/:id_route_import/clients/:id_client/products/competitors/add",
        component   : ClientCompetitorsAdd
    },

    // Complaint 
    {
        path        : "/route_import/:id_route_import/clients/:id_client/complaints/add",
        component   : AddComplaint
    },

    // Order
    {
        path        : "/route_import/:id_route_import/clients/:id_client/orders/add",
        component   : AddOrder
    },

    //

    //

    //

    //

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
