import { createRouter, createWebHistory }   from    "vue-router";

// mixin
import Authentification                     from    "./mixin/authentification"

// store
import store                                from    "./store/store"

// Declare a variable at the top of your router file
let initialRoute  =   true

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

    //  //  //  //  //  OBS     //  //  //

    //

    // Imports Tempo
    {
        path        : "/route/obs/route_import_tempo",
        component   : () => import('./components/routes/Temporary/routeImportTempo.vue')
    },

    // Imports Add
    {
        path        : "/route/obs/route_import/add",
        component   : () => import('./components/routes/RouteImportAdd.vue')
    },

    // Imports Details
    {
        path        : "/route/obs/route_import/:id_route_import/details",
        component   : () => import('./components/routes/Permanent/ParRouteImportDetails.vue')
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

            if(initialRoute) {

                initialRoute    =   false
                next("/")
            }

            else {

                next()    
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
