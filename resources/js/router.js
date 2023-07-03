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

//

import RouteImportAdd           from    "./components/routes/imports/RouteImportAdd.vue"
import ParRouteImportDetails    from    "./components/routes/obs/ParRouteImportDetails.vue"

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

    //  //  //  //  //  OBS     //  //  //

    //

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

            next()
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
