import { createRouter, createWebHistory }   from "vue-router";

// mixin
import Authentification                     from "./mixin/authentification";

// store
import store                                from "./store/store";

// Declare a variable at the top of your router file
let initialRoute = true;

// Static Imports

import IndexShared                              from "./components/index/shared/Index.vue";

import LoginShared                              from "./components/login/Login.vue";

import UsersShared                              from "./components/users/Index.vue";
import ShowUsersShared                          from "./components/users/Show.vue";
import PointingsShared                          from "./components/users/Pointings.vue";

import StatsStandardShared                      from "./components/stats/shared/standard/Index.vue";
import StatsSelfServiceShared                   from "./components/stats/shared/self-service/Index.vue";

import RouteImportClientsShared                 from "./components/routes/shared/crud/RouteImportClients.vue";
import RouteImportAddShared                     from "./components/routes/shared/crud/RouteImportAdd.vue";
import RouteImportTempoShared                   from "./components/routes/shared/crud/RouteImportTempo.vue";
import ParRouteImportDetailsShared              from "./components/routes/shared/obs/ParRouteImportDetails.vue";
import RouteImportClientsConfirmation           from "./components/routes/shared/crud/RouteImportClientsConfirmation.vue";
import RouteImportClientsValidation             from "./components/routes/shared/crud/RouteImportClientsValidation.vue";

//  //  //  //  //

import IndexFrontOffice                         from "./components/index/front_office/index.vue";

import ClientDetailsFrontOffice                 from "./components/clients/front_office/ClientDetails.vue";
import ClientAddCurrentPositionFrontOffice      from "./components/clients/front_office/ClientAddCurrentPosition.vue";
import ClientUpdateFrontOffice                  from "./components/clients/front_office/ClientUpdate.vue";

import RouteImportClientsByStatusFrontOffice    from "./components/routes/front_office/RouteImportClientsByStatus.vue";
import ParRouteImportFrontOfficeDetails         from "./components/routes/front_office/ParRouteImportFrontOfficeDetails.vue";

import ExpectedClients                          from "./components/districts/ExpectedClients.vue";

//  //  //  //  //

const routes = [
    { path: "/"                                                                             , component: IndexShared                                },
    { path: "/login"                                                                        , component: LoginShared                                },
    { path: "/users"                                                                        , component: UsersShared                                },
    { path: "/users/:id_user/show"                                                          , component: ShowUsersShared                            },
    { path: "/users/pointings"                                                              , component: PointingsShared                            },

    { path: "/stats/standard"                                                               , component: StatsStandardShared                        },
    { path: "/stats/self-service"                                                           , component: StatsSelfServiceShared                     },

    { path: "/route-imports/:id_route_import/clients"                                        , component: RouteImportClientsShared                   },

    { path: "/route/obs/route-imports/add"                                                   , component: RouteImportAddShared                       },
    { path: "/route/obs/route_import_tempo"                                                 , component: RouteImportTempoShared                     },
    { path: "/route/obs/route-imports/:id_route_import/details"                              , component: ParRouteImportDetailsShared                },

    //  //  //
    { path: "/route/frontoffice/obs/route-imports/:id_route_import/details"                  , component: ParRouteImportFrontOfficeDetails           },
    { path: "/route/frontoffice/obs/route-imports/:id_route_import/clients/selected"         , component: ParRouteImportFrontOfficeDetails           },

    { path: "/route-imports/:id_route_import/clients/:id_client/details"                     , component: ClientDetailsFrontOffice                   },
    { path: "/route-imports/:id_route_import/clients/add"                                    , component: ClientAddCurrentPositionFrontOffice        },
    { path: "/route-imports/:id_route_import/clients/:id_client/update"                      , component: ClientUpdateFrontOffice                    },
    { path: "/route-imports/:id_route_import/clients/confirmation"                           , component: RouteImportClientsConfirmation             },
    { path: "/route-imports/:id_route_import/clients/validation"                             , component: RouteImportClientsValidation               },

    { path: "/route-imports/:id_route_import/clients/by-status"                              , component: RouteImportClientsByStatusFrontOffice      },

    { path: "/front-office"                                                                 , component: IndexFrontOffice                           },

    { path: "/districts/expected-clients"                                                   , component: ExpectedClients                            }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {

    Authentification.methods.$fillStore(store);

    if (to.path === "/login" && store.getters[`authentification/getIsAuthentificated`] === false) {
        next();
    } 

    else if (to.path !== "/login" && store.getters[`authentification/getIsAuthentificated`] === true) {

        if (Authentification.methods.$isRole("FrontOffice")) {

            const pattern = /^\/route\/obs\/route_import\/\d+\/details$/;
            let route_obs_frontOffice = pattern.test(to.path);

            if (route_obs_frontOffice) {
                next("/front-office");
            } 

            else {
                initialRoute ? (initialRoute = false, next("/front-office")) : next();
            }
        }

        else {
            initialRoute ? (initialRoute = false, next("/")) : next();
        }
    }

    else if (to.path === "/login" && store.getters[`authentification/getIsAuthentificated`] === true) {
        next("/");
    } 

    else if (to.path !== "/login" && store.getters[`authentification/getIsAuthentificated`] === false) {
        next("/login");
    }
});

export default router;