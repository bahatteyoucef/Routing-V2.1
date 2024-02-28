<template>
    <section class="app-sidebar">
        <nav class="sidebar sidebar-offcanvas scrollbar scrollbar-deep-blue" id="sidebar">
            <ul class="nav">

                <!-- Profile -->
                <li v-if="user" class="nav-item nav-profile">
                    <a href="javascript:void(0);" class="nav-link">
                        <div class="nav-profile-image">
                            <img
                                :src="'/images/profile.jpg'"
                                alt="profile"
                            />
                            <span class="login-status online"></span>
                        </div>
                        <div class="nav-profile-text d-flex flex-column">
                            <span class="font-weight-bold mb-2"
                                >{{ user.nom }} <br /> {{ user.prenom }}</span
                            >
                            <span class="text-secondary text-small"
                                >Admin</span
                            >
                        </div>
                        <i
                            class="mdi mdi-bookmark-check text-success nav-profile-badge"
                        ></i>
                    </a>
                </li>

                <!-- Dashboard -->
                <li class="nav-item">
                    <span class="nav-link w-100" :class="VerifyDashboardClicked" @click="goToRoute('', '')">
                        <span class="menu-title"            :class="VerifyDashboardClicked">Dashboard</span>
                        <i class="mdi mdi-home menu-icon"   :class="VerifyDashboardClicked"></i>
                    </span>
                </li>

                <!-- Organisations -->
                <li class="nav-item">
                    
                    <!-- Organisation -->

                    <span
                        class="nav-link"
                        role="button"
                    >

                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionOrganisationClicked">Organisations</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold arrow organisations_arrow"                                                        @click="$accordion($event, 'organisations')"></i>
                            <i class="mdi mdi-package-variant-closed menu_icon_i"   :class="VerifyOptionOrganisationClicked"></i>
                        </span>
                    </span>

                    <!--  -->

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="organisations"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">

                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1" :class="VerifyOrganisationClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyOrganisationClicked" @click="goToRoute('organisations', 'organisations_arrow')">
                                        Liste des organisations
                                    </span>
                                </span>
                                
                            </li>

                            <li class="nav-item">

                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1" :class="VerifyNiveauOrganisationClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyNiveauOrganisationClicked" @click="goToRoute('niveau_organisation', 'organisations_arrow')">
                                        Niveaux des organisations
                                    </span>
                                </span>

                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Clients -->
                <li class="nav-item">

                    <span
                        class="nav-link"
                        role="button"
                    >

                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionClientClicked">Clients</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold arrow clients_arrow"        @click="$accordion($event, 'clients')"></i>
                            <i class="mdi mdi-account-multiple-outline menu_icon_i" :class="VerifyOptionClientClicked"></i>
                        </span>
                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="clients"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">

                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyClientClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyClientClicked" @click="goToRoute('clients', 'clients_arrow')">
                                        Liste des clients
                                    </span>
                                </span>
                                
                            </li>

                            <li class="nav-item">

                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyTypeClientClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyTypeClientClicked" @click="goToRoute('type_client', 'clients_arrow')">
                                        Types des clients
                                    </span>
                                </span>

                            </li>

                            <li class="nav-item">

                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyCategorieClientClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyCategorieClientClicked" @click="goToRoute('categorie_client', 'clients_arrow')">
                                        Categories des clients
                                    </span>
                                </span>

                            </li>

                        </ul>
                    </div>
                </li>

                <!-- Upload Client -->
                <li class="nav-item">

                    <span
                        class="nav-link"
                        role="button"
                    >

                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionUploadClientClicked">Upload Clients</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold arrow upload_client_arrow"        @click="$accordion($event, 'upload_client')"></i>
                            <i class="mdi mdi-account-multiple-plus-outline menu_icon_i"        :class="VerifyOptionUploadClientClicked"></i>
                        </span>
                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="upload_client"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">

                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyUploadClientClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyUploadClientClicked" @click="goToRoute('upload_client', 'upload_client_arrow')">
                                        Upload liste des clients
                                    </span>
                                </span>
                                
                            </li>
                        </ul>
                    </div>
                    <!--  -->
                </li>

                <!-- Routes -->
                <li class="nav-item">

                    <!-- Organisation -->

                    <span
                        class="nav-link"
                        role="button"
                    >

                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionRouteClicked">Routes</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold arrow routes_arrow"                                              @click="$accordion($event, 'routes')"></i>
                            <i class="mdi mdi-google-maps menu_icon_i"  :class="VerifyOptionRouteClicked"></i>
                        </span> 
                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="routes"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">

                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyRouteClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyRouteClicked" @click="goToRoute('routes', 'routes_arrow')">
                                        Liste des routes
                                    </span>
                                </span>
                                
                            </li>

                            <!--  -->

                            <li class="nav-item">

                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyCategorieRouteClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyCategorieRouteClicked" @click="goToRoute('categorie_route', 'routes_arrow')">
                                        Categorie des routes
                                    </span>
                                </span>
                                
                            </li>

                        </ul>
                    </div>
                </li>

                <!-- Region -->
                <li class="nav-item">
                    <!-- Organisation -->

                    <span
                        class="nav-link"
                        role="button"
                    >

                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionRegionClicked">Regions</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold arrow regions_arrow"                                                      @click="$accordion($event, 'regions')"></i>
                            <i class="mdi mdi-map-marker-outline menu_icon_i"   :class="VerifyOptionRegionClicked"></i>
                        </span>
                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="regions"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyRegionClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyRegionClicked" @click="goToRoute('regions', 'regions_arrow')">
                                        Liste des regions
                                    </span>
                                </span>
                            </li>

                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyWillayaClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyWillayaClicked" @click="goToRoute('willayas', 'regions_arrow')">
                                        Liste des willaya
                                    </span>
                                </span>
                            </li>

                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyCiteClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyCiteClicked" @click="goToRoute('cites', 'regions_arrow')">
                                        Liste des cites
                                    </span>
                                </span>
                            </li>

                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyZoneClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyZoneClicked" @click="goToRoute('zones', 'regions_arrow')">
                                        Liste des zones
                                    </span>
                                </span>
                            </li>

                        </ul>
                    </div>
                </li>

                <!-- Users -->
                <li class="nav-item">
                    <span
                        class="nav-link"
                        role="button"
                    >

                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionUserClicked">Utilisateurs</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold arrow users_arrow"                                                          @click="$accordion($event, 'users')"></i>
                            <i class="mdi mdi-account-box-multiple-outline menu_icon_i" :class="VerifyOptionUserClicked"></i>
                        </span>
                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="users"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyUserClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyUserClicked" @click="goToRoute('users', 'users_arrow')">
                                        Utilisateurs
                                    </span>
                                </span>
                            </li>

                        </ul>
                    </div>
                </li>

                <!-- Personnels -->
                <li class="nav-item">
                    <span
                        class="nav-link"
                        role="button"
                    >

                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionPersonnelClicked">Personnels</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold arrow personnels_arrow"                                                      @click="$accordion($event, 'personnels')"></i>
                            <i class="mdi mdi-account-switch menu_icon_i"   :class="VerifyOptionPersonnelClicked"></i>
                        </span>
                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="personnels"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1" :class="VerifyPersonnelClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyPersonnelClicked" @click="goToRoute('personnels', 'personnels_arrow')">
                                        Personnels
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Branches -->
                <li class="nav-item">
                    <span
                        class="nav-link"
                        role="button"
                    >
                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionBrancheClicked">Branches</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold arrow branches_arrow"                                      @click="$accordion($event, 'branches')"></i>
                            <i class="mdi mdi-city menu_icon_i" :class="VerifyOptionBrancheClicked"></i>
                        </span>
                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="branches"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1" :class="VerifyBrancheClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyBrancheClicked" @click="goToRoute('branches', 'branches_arrow')">
                                        Branches
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Depots -->
                <li class="nav-item">
                    <span
                        class="nav-link"
                        role="button"
                    >
                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionDepotClicked">Depots</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold arrow depots_arrow"                                              @click="$accordion($event, 'depots')"></i>
                            <i class="mdi mdi-cube-outline menu_icon_i" :class="VerifyOptionDepotClicked"></i>
                        </span>
                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="depots"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1" :class="VerifyDepotClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyDepotClicked" @click="goToRoute('depots', 'depots_arrow')">
                                        Depots
                                    </span>

                                </span>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <!-- Ventes -->
                <li class="nav-item">
                    <span
                        class="nav-link"
                        role="button"
                    >
                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionVenteClicked">Ventes</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold arrow ventes_arrow"                                                  @click="$accordion($event, 'ventes')"></i>
                            <i class="mdi mdi-cash-multiple menu_icon_i"    :class="VerifyOptionVenteClicked"></i>
                        </span>
                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="ventes"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifySecteurVenteClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifySecteurVenteClicked" @click="goToRoute('secteur_vente')">
                                        Secteur Ventes
                                    </span>
                                </span>
                            </li>

                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1" :class="VerifyTypeVenteClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyTypeVenteClicked" @click="goToRoute('type_vente')">
                                        Types Ventes
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Vendeurs -->
                <li class="nav-item">
                    <span
                        class="nav-link"
                        role="button"
                    >
                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionVendeurClicked">Vendeurs</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold arrow vendeurs_arrow"                                                  @click="$accordion($event, 'vendeurs')"></i>
                            <i class="mdi mdi-ticket-account menu_icon_i"   :class="VerifyOptionVendeurClicked"></i>
                        </span>
                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="vendeurs"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyVendeurClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyVendeurClicked" @click="goToRoute('vendeurs', 'vendeurs_arrow')">
                                        Vendeurs
                                    </span>
                                </span>
                            </li>

                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifyCategorieVendeurClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyCategorieVendeurClicked" @click="goToRoute('categorie_vendeur', 'vendeurs_arrow')">
                                        Categories des Vendeurs
                                    </span>
                                </span>
                            </li>

                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"   :class="VerifySecteurVendeurClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifySecteurVendeurClicked" @click="goToRoute('secteur_vendeur', 'vendeurs_arrow')">
                                        Secteurs des Vendeurs
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Monnaies -->
                <li class="nav-item">
                    <span
                        class="nav-link"
                        role="button"
                    >
                        <!-- Title -->
                        <span class="menu-title" :class="VerifyOptionMonnaieClicked">Monnaies</span>

                        <!-- Arrow + Icon -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold monnaies_arrow"   @click="$accordion($event, 'monnaie')"></i>
                            <i class="mdi mdi-currency-usd menu_icon_i" :class="VerifyOptionMonnaieClicked"></i>
                        </span>
                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster"
                        id="monnaie"
                    >
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1" :class="VerifyMonnaieClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyMonnaieClicked" @click="goToRoute('monnaies', 'monnaies_arrow')">
                                        Monnaies
                                    </span>
                                </span>
                            </li>

                            <li class="nav-item">
                                <!-- Vendeur -->
                                <span
                                    class="nav-link pr-0"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1" :class="VerifyModalitePaiementClicked"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="nav-link w-100" :class="VerifyModalitePaiementClicked" @click="goToRoute('modalite_paiement', 'monnaies_arrow')">
                                        Modalites des Paiements
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </nav>
    </section>
</template>

<script>
export default {

    data() {

        return {
            collapses   :   [{ show: false }, { show: false }, { show: false }],

            user        :   {
                nom         :   null,
                prenom      :   null
            }
        };
    },

    mounted() {

        // Set User
        if(JSON.parse(localStorage.getItem('vuex')) !=  null) {

            this.user.nom     = JSON.parse(localStorage.getItem('vuex')).user.nom
            this.user.prenom  = JSON.parse(localStorage.getItem('vuex')).user.prenom
        }

        // SomethingSideBar (Template Default Stuff)

        this.$somethingSideBar()

        //

        // Highlight the right arrow
        this.$highlightArrow()
    },

    computed : {

        VerifyDashboardClicked          : function () {
            return {
                active_item : this.$route.path    ==  "/"
            }
        },

        // Organisations

        VerifyOptionOrganisationClicked : function () {
            return {
                active_item : this.$route.path.startsWith("/organisations")||this.$route.path.startsWith("/niveau_organisation")
            }
        },

        VerifyOrganisationClicked       : function () {
            return {
                active_item : this.$route.path.startsWith("/organisations")
            }
        },

        VerifyNiveauOrganisationClicked : function () {
            return {
                active_item : this.$route.path.startsWith("/niveau_organisation")
            }
        },

        //
  
        // Clients

        VerifyOptionClientClicked       : function () {
            return {
                active_item : this.$route.path.startsWith("/clients")||this.$route.path.startsWith("/type_client")||this.$route.path.startsWith("/categorie_client")
            }
        },

        VerifyClientClicked             : function () {
            return {
                active_item : this.$route.path.startsWith("/clients")
            }
        },

        VerifyTypeClientClicked         : function () {
            return {
                active_item : this.$route.path.startsWith("/type_client")
            }
        },

        VerifyCategorieClientClicked    : function () {
            return {
                active_item : this.$route.path.startsWith("/categorie_client")
            }
        },

        //

        // Upload Client

        VerifyOptionUploadClientClicked : function () {
            return {
                active_item : this.$route.path.startsWith("/upload_client")
            }
        },

        VerifyUploadClientClicked             : function () {
            return {
                active_item : this.$route.path.startsWith("/upload_client")
            }
        },

        // 

        // Routes

        VerifyOptionRouteClicked        : function () {
            return {
                active_item : this.$route.path.startsWith("/routes")||this.$route.path.startsWith("/categorie_route")
            }
        },

        VerifyRouteClicked              : function () {
            return {
                active_item : this.$route.path.startsWith("/routes")
            }
        },

        VerifyCategorieRouteClicked     : function () {
            return {
                active_item : this.$route.path.startsWith("/categorie_route")
            }
        },

        // 

        // Regions

        VerifyOptionRegionClicked       : function () {
            return {
                active_item : this.$route.path.startsWith("/regions")||this.$route.path.startsWith("/willayas")||this.$route.path.startsWith("/cites")||this.$route.path.startsWith("/zones")
            }
        },

        VerifyRegionClicked             : function () {
            return {
                active_item : this.$route.path.startsWith("/regions")
            }
        },

        VerifyWillayaClicked            : function () {
            return {
                active_item : this.$route.path.startsWith("/willayas")
            }
        },

        VerifyCiteClicked               : function () {
            return {
                active_item : this.$route.path.startsWith("/cites")
            }
        },

        VerifyZoneClicked               : function () {
            return {
                active_item : this.$route.path.startsWith("/zones")
            }
        },
            
        //

        // Users

        VerifyOptionUserClicked         : function () {
            return {
                active_item : this.$route.path.startsWith("/users")
            }
        },  

        VerifyUserClicked               : function () {
            return {
                active_item : this.$route.path.startsWith("/users")
            }
        },

        //

        // Personnels
        
        VerifyOptionPersonnelClicked    : function () {
            return {
                active_item : this.$route.path.startsWith("/personnels")
            }
        },  

        VerifyPersonnelClicked          : function () {
            return {
                active_item : this.$route.path.startsWith("/personnels")
            }
        },
        
        //

        // Branche

        VerifyOptionBrancheClicked      : function () {
            return {
                active_item : this.$route.path.startsWith("/branches")
            }
        },

        VerifyBrancheClicked            : function () {
            return {
                active_item : this.$route.path.startsWith("/branches")
            }
        },

        // 

        // Depot

        VerifyOptionDepotClicked        : function () {
            return {
                active_item : this.$route.path.startsWith("/depots")
            }
        },

        VerifyDepotClicked              : function () {
            return {
                active_item : this.$route.path.startsWith("/depots")
            }
        },

        //

        // Vente

        VerifyOptionVenteClicked        : function () {
            return {
                active_item : this.$route.path.startsWith("/secteur_vente")||this.$route.path.startsWith("/type_vente")
            }
        },

        VerifySecteurVenteClicked              : function () {
            return {
                active_item : this.$route.path.startsWith("/secteur_vente")
            }
        },

        VerifyTypeVenteClicked              : function () {
            return {
                active_item : this.$route.path.startsWith("/type_vente")
            }
        },

        //

        // Vendeur

        VerifyOptionVendeurClicked        : function () {
            return {
                active_item : this.$route.path.startsWith("/vendeurs")||this.$route.path.startsWith("/categorie_vendeur")||this.$route.path.startsWith("/secteur_vendeur")
            }
        },

        VerifyVendeurClicked              : function () {
            return {
                active_item : this.$route.path.startsWith("/vendeurs")
            }
        },

        VerifyCategorieVendeurClicked              : function () {
            return {
                active_item : this.$route.path.startsWith("/categorie_vendeur")
            }
        },

        VerifySecteurVendeurClicked              : function () {
            return {
                active_item : this.$route.path.startsWith("/secteur_vendeur")
            }
        },

        //

        // Monnaie

        VerifyOptionMonnaieClicked        : function () {
            return {
                active_item : this.$route.path.startsWith("/monnaies")||this.$route.path.startsWith("/modalite_paiement")
            }
        },

        VerifyMonnaieClicked              : function () {
            return {
                active_item : this.$route.path.startsWith("/monnaies")
            }
        },

        VerifyModalitePaiementClicked              : function () {
            return {
                active_item : this.$route.path.startsWith("/modalite_paiement")
            }
        }
  
    },

    methods: {

        collapseAll() {

            var exp_elm = document.getElementsByClassName("show");
            if (exp_elm.length > 0) {
                var elm_id = exp_elm[0].id;
                this.$root.$emit("bv::toggle::collapse", elm_id);
            }
        },

        goToRoute(route, route_arrow) {
    
            if(this.$route.path !=  route) {

                // Remove Active class from arrows
                const arrows            =   document.getElementsByClassName('arrow active_item')

                Array.prototype.forEach.call(arrows, function(arrow) {
                    arrow.classList.remove("active_item")
                });

                // Set Active class to arrow
                const active_arrow      =   document.getElementsByClassName(route_arrow)
                Array.prototype.forEach.call(active_arrow, function(arrow) {
                    arrow.classList.add("active_item")
                });       

                // Go To Route
                this.$router.push('/'+route)
            }
        }
    },

    watch: {

        $route() {

            if (document.querySelector("#sidebar")) {
                document.querySelector("#sidebar").classList.toggle("active");
            }
        },
    },
};
</script>
