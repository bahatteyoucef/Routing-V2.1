import { createRouter, createWebHistory } from "vue-router";

import Authentification from "./mixin/authentification"; // Adjust path if needed
import store            from "./store/store"; // Adjust path if needed

// ==========================================================
// 1. LAYOUTS & CORE PAGES (Eager Loaded)
// ==========================================================
import DashboardLayout          from "./template/layouts/DashboardLayout.vue"; // The wrapper you created
import LoginShared              from "./components/login/Login.vue";
import IndexShared              from "./components/index/shared/Index.vue"; // Dashboard Home

// ==========================================================
// 2. FEATURE PAGES (Lazy Loaded)
// ==========================================================
const UsersShared               = () => import(/* webpackChunkName: "users" */ "./components/users/Index.vue");
const ShowUsers                 = () => import(/* webpackChunkName: "users" */ "./components/users/Show.vue");
const Pointings                 = () => import(/* webpackChunkName: "users" */ "./components/users/Pointings.vue");

const StatsStandard             = () => import(/* webpackChunkName: "stats" */ "./components/stats/shared/standard/Index.vue");
const StatsSelf                 = () => import(/* webpackChunkName: "stats" */ "./components/stats/shared/self-service/Index.vue");

// Route Imports Chunk
const RouteImportClients        = () => import(/* webpackChunkName: "route-imports" */ "./components/routes/shared/crud/RouteImportClients.vue");
const RouteImportAdd            = () => import(/* webpackChunkName: "route-imports" */ "./components/routes/shared/crud/RouteImportAdd.vue");
const RouteImportTempo          = () => import(/* webpackChunkName: "route-imports" */ "./components/routes/shared/crud/RouteImportTempo.vue");
const ParRouteImportDetails     = () => import(/* webpackChunkName: "route-imports" */ "./components/routes/shared/obs/ParRouteImportDetails.vue");
const RouteImportConfirmation   = () => import(/* webpackChunkName: "route-imports" */ "./components/routes/shared/crud/RouteImportClientsConfirmation.vue");
const RouteImportValidation     = () => import(/* webpackChunkName: "route-imports" */ "./components/routes/shared/crud/RouteImportClientsValidation.vue");

// FrontOffice Chunk
const IndexFrontOffice          = () => import(/* webpackChunkName: "frontoffice" */ "./components/index/front_office/Index.vue");
const ClientDetailsFrontOffice  = () => import(/* webpackChunkName: "frontoffice" */ "./components/clients/front_office/ClientDetails.vue");
const ClientAddCurrentPositionFrontOffice = () => import(/* webpackChunkName: "frontoffice" */ "./components/clients/front_office/ClientAddCurrentPosition.vue");
const ClientUpdateFrontOffice   = () => import(/* webpackChunkName: "frontoffice" */ "./components/clients/front_office/ClientUpdate.vue");
const RouteImportClientsByStatus= () => import(/* webpackChunkName: "frontoffice" */ "./components/routes/front_office/RouteImportClientsByStatus.vue");
const ParRouteImportFrontOfficeDetails = () => import(/* webpackChunkName: "frontoffice" */ "./components/routes/front_office/ParRouteImportFrontOfficeDetails.vue");

const ExpectedClients           = () => import(/* webpackChunkName: "districts" */ "./components/districts/ExpectedClients.vue");

// ==========================================================
// 3. ROUTE DEFINITIONS
// ==========================================================
const routes = [
  
  // --- A. PUBLIC ROUTE ---
  { 
    path: "/login", 
    name: "Login",
    component: LoginShared,
    meta: { requiresAuth: false } 
  },

  // --- B. PROTECTED ROUTES (Wrapped in DashboardLayout) ---
  {
    path: "/",
    component: DashboardLayout, // THIS IS THE PARENT
    meta: { requiresAuth: true },
    children: [
        // 1. Dashboard (The default view for "/")
        { path: "", component: IndexShared, name: "Dashboard" },

        // 2. Users
        { path: "users", component: UsersShared },
        { path: "users/:id_user/show", component: ShowUsers },
        { path: "users/pointings", component: Pointings },

        // 3. Stats
        { path: "stats/standard", component: StatsStandard },
        { path: "stats/self-service", component: StatsSelf },

        // 4. Route Imports
        { path: "route-imports/:id_route_import/clients", component: RouteImportClients },
        { path: "route-imports/add", component: RouteImportAdd },
        { path: "route-imports-tempo/last-imported", component: RouteImportTempo },
        { path: "route/obs/route-imports/:id_route_import/details", component: ParRouteImportDetails },

        // 6. Districts
        { path: "districts/expected-clients", component: ExpectedClients },

        //  //  //  //  //
        //  //  //  //  //
        //  //  //  //  //

        // 5. Front Office Specifics
        { path: "front-office", component: IndexFrontOffice },
        { path: "route/frontoffice/obs/route-imports/:id_route_import/details", component: ParRouteImportFrontOfficeDetails },
        { path: "route/frontoffice/obs/route-imports/:id_route_import/clients/selected", component: ParRouteImportFrontOfficeDetails }, // Same component?
        
        { path: "route-imports/:id_route_import/clients/:id_client/details", component: ClientDetailsFrontOffice },
        { path: "route-imports/:id_route_import/clients/add", component: ClientAddCurrentPositionFrontOffice },
        { path: "route-imports/:id_route_import/clients/:id_client/update", component: ClientUpdateFrontOffice },
        { path: "route-imports/:id_route_import/clients/confirmation", component: RouteImportConfirmation },
        { path: "route-imports/:id_route_import/clients/validation", component: RouteImportValidation },
        { path: "route-imports/:id_route_import/clients/by-status", component: RouteImportClientsByStatus },
    ]
  },

  // --- C. 404 CATCH-ALL ---
  // If user types random URL, redirect to home
  { path: '/:pathMatch(.*)*', redirect: '/' }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// ==========================================================
// 4. NAVIGATION GUARD (The Gatekeeper)
// ==========================================================
router.beforeEach(async (to, from, next) => {
  // Ensure store is populated
  Authentification.methods.$fillStore(store);

  const isAuthentificated = store.getters["authentification/getIsAuthentificated"];
  
  // Check if the route requires auth (it does if it's a child of "/")
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);

  // 1. Not Logged In -> Trying to access Protected Route
  if (requiresAuth && !isAuthentificated) {
    return next("/login");
  }

  // 2. Logged In -> Trying to access Login Page
  if (to.path === "/login" && isAuthentificated) {
    return next("/");
  }

  // 3. Logged In -> Logic for Role Redirection
  if (isAuthentificated && requiresAuth) {
    
    // FrontOffice Logic
    if (Authentification.methods.$isRole("FrontOffice")) {
      const pattern = /^\/route\/obs\/route_import\/\d+\/details$/;
      let route_obs_frontOffice = pattern.test(to.path);

      if (route_obs_frontOffice) {
        // Allow access to specific OBS route
        return next();
      } 
      
      // If trying to access root "/" or standard dashboard, force to /front-office
      if (to.path === "/") {
        return next("/front-office");
      }
    } 
    // BackOffice Logic
    else {
      // If BackOffice user hits root, just allow them to proceed to IndexShared
    }
  }

  // 4. Proceed as normal
  next();
});

export default router;