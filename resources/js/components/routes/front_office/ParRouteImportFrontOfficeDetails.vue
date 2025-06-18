<template>

    <div>
        <div id="content_route">
            <div id="map"></div>
            <div id="map_top_middle_options_div">
                <div class="row">
                    <!-- Toggle -->
                    <div class="col p-0 ml-4">
                        <div id="toggle_div">
                            <div class="btn-container" id="marker_cluster_mode_div">
                                <label class="switch btn-color-mode-switch">
                                    <input type="checkbox" name="marker_cluster_mode" id="marker_cluster_mode" @change="switchMarkerClusterMode()">
                                    <label for="marker_cluster_mode" data-on="Marker" data-off="Cluster" class="btn-color-mode-switch-inner"></label>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--  -->

        <!-- Buttons and Filter -->
        <div id="tableau_data">
            <div class="map_top_buttons_parent_div animate__animated" id="map_top_buttons_parent_div">
                <div class="map_top_buttons_div" id="map_top_buttons_div">
                    <div class="row">
                        <div class="col-2 float-right mt-1 p-0" id="show_position_button">
                            <button class="btn btn-primary w-100 m-0 mt-1 pr-0 pl-0"    @click="showCurrentPosition()"><i class="mdi mdi-crosshairs-gps"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import {mapGetters, mapActions} from "vuex"

export default {
    
    data() {
        return {

            column_group                :   1                           ,

            id_route_import             :   ''                          ,
            route_import                :   null                        ,

            // Journey Plan

            journey_plan_filter_value   :   []                          ,
            liste_journey_plan          :   {}                          ,

            // District

            district_filter_value       :   []                          ,
            districts                   :   {}                          ,

            // City

            city_filter_value           :   []                          ,
            cites                       :   {}                          ,

            // Type Client

            type_client_filter_value    :   []                          ,
            liste_type_client           :   {}                          ,

            // Journee

            journee_filter_value        :   []                          ,
            liste_journee               :   {}                          ,

            // Ownwer

            owner_filter_value          :   []                          ,
            owners                      :   {}                          ,

            // Status

            status_filter_value         :   []                          ,
            liste_status                :   {}                          ,

            // Clients
            clients_group               :   {}                          ,
            clients_markers_affiche     :   {}                          ,
            clients_table_affiche       :   []                          ,

            // latlngs selected Polygon
            latlngs                             :   null                ,
            journey_plan                        :   null                ,

            client                      :   {
                lat                         :   0   ,
                lng                         :   0   
            },

            // clients_non_owner               :   [],
            clients_owner_validated         :   [],
            clients_owner_pending           :   [],
            clients_owner_non_validated     :   [],
            clients_owner_visible           :   [],

            //
            is_database_clients_map         :   false
        }
    },  

    computed: {
        ...mapGetters({
            getUpdateClient         :   'client/getUpdateClient'                ,
            getSelectedClients      :   'client/getSelectedClients'             ,

            //

            getUser                 :   'authentification/getUser'              ,
        })
    },

    async mounted() {

        const pattern                   =   /^\/route\/frontoffice\/obs\/route_import\/\d+\/details$/;
        this.is_database_clients_map    =   pattern.test(this.$route.path);

        // add Map
        this.addMap()

        //
        this.showUserBDTerritoriesFront()

        // 
        this.setValues()

        // 
        await this.getData()
    },

    methods : {

        ...mapActions("client" ,  [
            "setAllClientsAction"     ,
        ]),

        //

        setValues() {
            this.id_route_import    =   this.$route.params.id_route_import
        },

        async getData() {

            // Show Loading Page
            this.$showLoadingPage()

            if(this.is_database_clients_map) {

                const res                   =   await this.$callApi("post"  ,   "/route/obs/route_import/"+this.id_route_import+"/details/for_front_office",   null)

                this.route_import           =   res.data.route_import

                // Set Clients
                this.route_import.clients   =   this.route_import.data
            }

            else {

                this.route_import           =   {}

                // Set Clients
                this.route_import.clients   =   this.getSelectedClients
            }

            // Set Data in Vuex
            this.setAllClientsAction(this.route_import.clients)

            // Set Markers
            this.setRouteMarkers()

            // Hide Loading Page
            this.$hideLoadingPage()
        }, 

        //

        extractMetaData() {

            let journey_plan_count                  =   0
            let district_count                      =   0
            let cite_count                          =   0
            let type_client_count                   =   0
            let journee_count                       =   0
            let owner_count                         =   0
            let status_count                        =   0

            let journey_plan_existe                 =   false
            let district_existe                     =   false
            let cite_existe                         =   false
            let type_client_existe                  =   false
            let journee_existe                      =   false
            let owner_existe                        =   false
            let status_existe                       =   false

            let journey_plan_util                   =   {}
            let district_util                       =   {}
            let cite_util                           =   {}
            let type_client_util                    =   {}
            let journee_util                        =   {}
            let owner_util                          =   {}
            let status_util                          =   {}

            let liste_journey_plan_colors           =   {}
            let districts_colors                    =   {}
            let cites_colors                        =   {}
            let liste_type_client_colors            =   {}
            let liste_journee_colors                =   {}
            let owners_colors                       =   {}
            let liste_status_colors                 =   {}

            let sortedArray                         =   null
            let sortedObject                        =   null

            // Colors
            for (const [key, value] of Object.entries(this.liste_journey_plan)) {

                if(typeof liste_journey_plan_colors[this.liste_journey_plan[key].color] ==  "undefined") {

                    liste_journey_plan_colors[this.liste_journey_plan[key].color]           =   this.liste_journey_plan[key].color
                }
            }

            for (const [key, value] of Object.entries(this.districts)) {

                if(typeof districts_colors[this.districts[key].color]                   ==  "undefined") {

                    districts_colors[this.districts[key].color]                             =   this.districts[key].color
                }
            }

            for (const [key, value] of Object.entries(this.cites)) {

                if(typeof cites_colors[this.cites[key].color]                           ==  "undefined") {

                    cites_colors[this.cites[key].color]                                     =   this.cites[key].color
                }
            }

            for (const [key, value] of Object.entries(this.liste_type_client)) {

                if(typeof type_client_util[this.liste_type_client[key].color]           ==  "undefined") {

                    liste_type_client_colors[this.liste_type_client[key].color]             =   this.liste_type_client[key].color
                }
            }

            for (const [key, value] of Object.entries(this.liste_journee)) {

                if(typeof journee_util[this.liste_journee[key].color]                   ==  "undefined") {

                    liste_journee_colors[this.liste_journee[key].color]                     =   this.liste_journee[key].color
                }
            }

            for (const [key, value] of Object.entries(this.owners)) {

                if(typeof owner_util[this.owners[key].color]  ==  "undefined") {

                    owners_colors[this.owners[key].color]           =   this.owners[key].color
                }
            }

            for (const [key, value] of Object.entries(this.liste_status)) {

                if(typeof status_util[this.liste_status[key].color]  ==  "undefined") {

                    liste_status_colors[this.liste_status[key].color]           =   this.liste_status[key].color
                }
            }

            // Make Groupe
            for (let i = 0; i < this.route_import.clients.length; i++) {

                if(typeof journey_plan_util[this.route_import.clients[i].JPlan]         ==  "undefined") {

                    journey_plan_util[this.route_import.clients[i].JPlan]                   =   this.route_import.clients[i].JPlan
                }    

                if(typeof district_util[this.route_import.clients[i].DistrictNo]        ==  "undefined") {

                    district_util[this.route_import.clients[i].DistrictNo]                  =   this.route_import.clients[i].DistrictNameE
                }

                if(typeof cite_util[this.route_import.clients[i].CityNo]                ==  "undefined") {

                    cite_util[this.route_import.clients[i].CityNo]                          =   this.route_import.clients[i].CityNameE
                }

                if(typeof type_client_util[this.route_import.clients[i].CustomerType]   ==  "undefined") {

                    type_client_util[this.route_import.clients[i].CustomerType]             =   this.route_import.clients[i].CustomerType
                }

                if(typeof journee_util[this.route_import.clients[i].Journee]            ==  "undefined") {

                    journee_util[this.route_import.clients[i].Journee]                      =   this.route_import.clients[i].Journee
                }

                if(typeof owner_util[this.route_import.clients[i].owner_name]                ==  "undefined") {

                    owner_util[this.route_import.clients[i].owner_name]                     =   this.route_import.clients[i].owner_name
                }

                if(typeof status_util[this.route_import.clients[i].status]              ==  "undefined") {

                    status_util[this.route_import.clients[i].status]                        =   this.route_import.clients[i].status
                }

                // JPlan
                journey_plan_existe         =   this.checkExistJPlan(this.liste_journey_plan, this.route_import.clients[i].JPlan) 

                if(!journey_plan_existe) {

                    this.liste_journey_plan[this.route_import.clients[i].JPlan]                         =   {JPlan  :   ""}
                    this.liste_journey_plan[this.route_import.clients[i].JPlan].JPlan                   =   this.route_import.clients[i].JPlan

                    if(Object.keys(liste_journey_plan_colors).length    >   0) {

                        while(typeof liste_journey_plan_colors[this.$colors[journey_plan_count % this.$colors.length]]      !=  "undefined") {

                            journey_plan_count                                                          =   journey_plan_count +   1
                        }
                    }

                    this.liste_journey_plan[this.route_import.clients[i].JPlan].color                   =   this.$colors[journey_plan_count % this.$colors.length]
                    journey_plan_count                                                                  =   journey_plan_count +   1
                }

                //

                // District
                district_existe             =   this.checkExistDistrictNo(this.districts, this.route_import.clients[i].DistrictNo) 

                if(!district_existe) {

                    this.districts[this.route_import.clients[i].DistrictNo]                                 =   {DistrictNameComplete : "", DistrictNameE : "", DistrictNo : ""}
                    this.districts[this.route_import.clients[i].DistrictNo].DistrictNo                      =   this.route_import.clients[i].DistrictNo 
                    this.districts[this.route_import.clients[i].DistrictNo].DistrictNameE                   =   this.route_import.clients[i].DistrictNameE 
                    this.districts[this.route_import.clients[i].DistrictNo].DistrictNameComplete            =   this.route_import.clients[i].DistrictNo +   "- "    +   this.route_import.clients[i].DistrictNameE 

                    if(Object.keys(districts_colors).length    >   0) {

                        while(typeof districts_colors[this.$colors[district_count % this.$colors.length]]                   !=  "undefined") {

                            district_count                                                                  =   district_count +   1
                        }
                    }

                    this.districts[this.route_import.clients[i].DistrictNo].color                           =   this.$colors[district_count % this.$colors.length]
                    district_count                                                                          =   district_count +   1
                }

                //

                // Cite
                cite_existe                 =   this.checkExistCityNo(this.cites, this.route_import.clients[i].CityNo) 

                if(!cite_existe) {

                    this.cites[this.route_import.clients[i].CityNo]                                         =   {CityNameComplete : "", CityNameE : "", CityNo : ""}
                    this.cites[this.route_import.clients[i].CityNo].CityNo                                  =   this.route_import.clients[i].CityNo 
                    this.cites[this.route_import.clients[i].CityNo].CityNameE                               =   this.route_import.clients[i].CityNameE 
                    this.cites[this.route_import.clients[i].CityNo].CityNameComplete                        =   this.route_import.clients[i].CityNo +   "- " +   this.route_import.clients[i].CityNameE 

                    if(Object.keys(cites_colors).length    >   0) {

                        while(typeof cites_colors[this.$colors[cite_count % this.$colors.length]]                           !=  "undefined") {

                            cite_count                                                                      =   cite_count +   1
                        }
                    }

                    this.cites[this.route_import.clients[i].CityNo].color                                   =   this.$colors[cite_count % this.$colors.length]
                    cite_count                                                                              =   cite_count +   1

                }

                //

                // CustomerType
                type_client_existe          =   this.checkExistCustomerType(this.liste_type_client, this.route_import.clients[i].CustomerType) 

                if(!type_client_existe) {

                    this.liste_type_client[this.route_import.clients[i].CustomerType]                   =   {CustomerType :   ""}
                    this.liste_type_client[this.route_import.clients[i].CustomerType].CustomerType      =   this.route_import.clients[i].CustomerType 

                    if(Object.keys(liste_type_client_colors).length    >   0) {

                        while(typeof liste_type_client_colors[this.$colors[type_client_count % this.$colors.length]]        !=  "undefined") {

                            type_client_count                                                               =   type_client_count +   1
                        }
                    }

                    this.liste_type_client[this.route_import.clients[i].CustomerType].color             =   this.$colors[type_client_count % this.$colors.length]
                    type_client_count                                                                   =   type_client_count +   1
                }

                // Journee
                journee_existe              =   this.checkExistJournee(this.liste_journee, this.route_import.clients[i].Journee) 

                if(!journee_existe) {

                    this.liste_journee[this.route_import.clients[i].Journee]                            =   {Journee :   ""}
                    this.liste_journee[this.route_import.clients[i].Journee].Journee                    =   this.route_import.clients[i].Journee 

                    if(Object.keys(liste_journee_colors).length    >   0) {

                        while(typeof liste_journee_colors[this.$colors[journee_count % this.$colors.length]]                !=  "undefined") {

                            journee_count                                                                   =   journee_count +   1
                        }
                    }

                    this.liste_journee[this.route_import.clients[i].Journee].color                      =   this.$colors[journee_count % this.$colors.length]
                    journee_count                                                                       =   journee_count +   1
                }

                // Owner
                owner_existe              =   this.checkExistOwner(this.owners, this.route_import.clients[i].owner_name) 

                if(!owner_existe) {

                    this.owners[this.route_import.clients[i].owner_name]             =   {owner_name :   ""}
                    this.owners[this.route_import.clients[i].owner_name].owner_name  =   this.route_import.clients[i].owner_name 

                    if(Object.keys(owners_colors).length    >   0) {

                        while(typeof owners_colors[this.$colors[owner_count % this.$colors.length]] !=  "undefined") {

                            owner_count                                                                 =   owner_count +   1
                        }
                    }

                    this.owners[this.route_import.clients[i].owner_name].color      =   this.$colors[owner_count % this.$colors.length]
                    owner_count                                                     =   owner_count +   1
                }

                // Status
                status_existe              =   this.checkExistStatus(this.liste_status, this.route_import.clients[i].status) 

                if(!status_existe) {

                    this.liste_status[this.route_import.clients[i].status]             =   {status :   ""}
                    this.liste_status[this.route_import.clients[i].status].status  =   this.route_import.clients[i].status 

                    if(Object.keys(liste_status_colors).length    >   0) {

                        while(typeof liste_status_colors[this.$colors[status_count % this.$colors.length]] !=  "undefined") {

                            status_count                                                                        =   status_count +   1
                        }
                    }

                    this.liste_status[this.route_import.clients[i].status].color        =   this.$colors[status_count % this.$colors.length]
                    status_count                                                        =   status_count +   1
                }
            }

            // Remove Elements
            for (const [key, value] of Object.entries(this.liste_journey_plan)) {

                if(typeof journey_plan_util[key]   ==  "undefined") {

                    delete this.liste_journey_plan[key]
                }
            }

            for (const [key, value] of Object.entries(this.districts)) {

                if(typeof district_util[key]   ==  "undefined") {

                    delete this.districts[key]
                }
            }

            for (const [key, value] of Object.entries(this.cites)) {

                if(typeof cite_util[key]   ==  "undefined") {

                    delete this.cites[key]
                }
            }

            for (const [key, value] of Object.entries(this.liste_type_client)) {

                if(typeof type_client_util[key]   ==  "undefined") {

                    delete this.liste_type_client[key]
                }
            }

            for (const [key, value] of Object.entries(this.liste_journee)) {

                if(typeof journee_util[key]   ==  "undefined") {

                    delete this.liste_journee[key]
                }
            }

            for (const [key, value] of Object.entries(this.owners)) {

                if(typeof owner_util[key]   ==  "undefined") {

                    delete this.owners[key]
                }
            }

            for (const [key, value] of Object.entries(this.liste_status)) {

                if(typeof status_util[key]   ==  "undefined") {

                    delete this.liste_status[key]
                }
            }

            //

            sortedArray                 =   Object.values(this.liste_journey_plan);
            sortedArray.sort((a, b)     =>  a.JPlan.localeCompare(b.JPlan));
            sortedObject                =   sortedArray.reduce((acc, journey_plan, index) => {
                
                acc[journey_plan.JPlan]             =   journey_plan;
                return acc;
            }, {});

            this.liste_journey_plan                 =   sortedObject
            this.route_import.liste_journey_plan    =   this.liste_journey_plan

            //

            sortedArray                 =   Object.values(this.districts);
            sortedArray.sort((a, b)     =>  a.DistrictNameComplete.localeCompare(b.DistrictNameComplete));

            sortedObject                =   sortedArray.reduce((acc, district, index) => {
                
                acc[district.DistrictNo]            =   district;
                return acc;
            }, {});

            this.districts                          =   sortedObject
            this.route_import.districts             =   this.districts

            //

            sortedArray                 =   Object.values(this.cites);
            sortedArray.sort((a, b)     =>  a.CityNameComplete.localeCompare(b.CityNameComplete));

            sortedObject                =   sortedArray.reduce((acc, cite, index) => {

                acc[cite.CityNo]                    =   cite;
                return acc;
            }, {});

            this.cites                              =   sortedObject
            this.route_import.cites                 =   this.cites

            //

            sortedArray                 =   Object.values(this.liste_type_client);
            sortedArray.sort((a, b)     =>  a.CustomerType.localeCompare(b.CustomerType));
            sortedObject                =   sortedArray.reduce((acc, type_client, index) => {

                acc[type_client.CustomerType]       =   type_client;
                return acc;
            }, {});

            this.liste_type_client                  =   sortedObject
            this.route_import.liste_type_client     =   this.liste_type_client

            //

            sortedArray                 =   Object.values(this.liste_journee);
            sortedArray.sort((a, b)     =>  a.Journee.localeCompare(b.Journee));
            sortedObject                =   sortedArray.reduce((acc, journee, index) => {

                acc[journee.Journee]                =   journee;
                return acc;
            }, {});

            this.liste_journee                      =   sortedObject
            this.route_import.liste_journee         =   this.liste_journee

            //

            sortedArray                 =   Object.values(this.owners);
            sortedArray.sort((a, b)     =>  a.owner_name.localeCompare(b.owner_name));
            sortedObject                =   sortedArray.reduce((acc, owner, index) => {

                acc[owner.owner_name]        =   owner;
                return acc;
            }, {});

            this.owners                      =   sortedObject
            this.route_import.owners         =   this.owners

            // 

            sortedArray                 =   Object.values(this.liste_status);
            sortedArray.sort((a, b)     =>  a.status.localeCompare(b.status));
            sortedObject                =   sortedArray.reduce((acc, status, index) => {

                acc[status.status]   =   status;
                return acc;
            }, {});

            this.liste_status                      =   sortedObject
            this.route_import.liste_status         =   this.liste_status
        },

        // 

        checkExistJPlan(liste_journey_plan, JPlan) {

            for (const [key, value] of Object.entries(liste_journey_plan)) {

                if(key  ==  JPlan) {

                    return true
                }
            }

            return false
        },

        checkExistDistrictNo(districts, DistrictNo) {

            for (const [key, value] of Object.entries(districts)) {
            
                if(key  ==  DistrictNo) {

                    return true
                }
            }

            return false
        },

        checkExistCityNo(cites, CityNo) {

            for (const [key, value] of Object.entries(cites)) {
            
                if(key  ==  CityNo) {

                    return true
                }
            }

            return false
        },

        checkExistCustomerType(liste_type_client, CustomerType) {

            for (const [key, value] of Object.entries(liste_type_client)) {
            
                if(key  ==  CustomerType) {

                    return true
                }
            }

            return false
        },

        checkExistJournee(liste_journee, Journee) {

            for (const [key, value] of Object.entries(liste_journee)) {
                
                if(key  ==  Journee) {

                    return true
                }
            }

            return false
        },

        checkExistOwner(owners, owner_name) {

            for (const [key, value] of Object.entries(owners)) {
                
                if(key  ==  owner_name) {

                    return true
                }
            }

            return false
        },

        checkExistStatus(liste_status, status) {

            for (const [key, value] of Object.entries(liste_status)) {
                
                if(key  ==  status) {

                    return true
                }
            }

            return false
        },

        //

        reAfficherClientsAndMarkers() {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(async () => {

                // reAffiche Markers
                this.setRouteMarkers()

                // Hide Loading Page
                this.$hideLoadingPage()

            }, 0);
        },

        reAfficherClientsAndMarkersByColor(column_name) {

            // JPlan
            if(this.column_group    ==  1) {

                this.journey_plan_filter_value      =   [column_name]
            }

            // District
            if(this.column_group    ==  2) {

                let DistrictNo                      =   this.getDistrictNo(column_name)
                this.district_filter_value          =   [DistrictNo]
            }

            // City
            if(this.column_group    ==  3) {

                let CityNo                          =   this.getCityNo(column_name)
                this.city_filter_value              =   [CityNo]
            }

            // CustomerType
            if(this.column_group    ==  4) {

                this.type_client_filter_value       =   [column_name]
            }

            // Journee
            if(this.column_group    ==  5) {

                this.journee_filter_value           =   [column_name]
            }

            // Owner
            if(this.column_group    ==  6) {

                this.owner_filter_value             =   [column_name]
            }

            // Staus
            if(this.column_group    ==  7) {

                this.status_filter_value            =   [column_name]
            }
        },

        //

        getDistrictNo(DistrictNameE) {

            for (const [key, value] of Object.entries(this.districts)) {

                if(this.districts[key].DistrictNameE    ==  DistrictNameE) {

                    return key
                }
            }
        },

        getCityNo(CityNameE) {

            for (const [key, value] of Object.entries(this.cites)) {

                if(this.cites[key].CityNameE            ==  CityNameE) {

                    return key
                }
            }
        },

        getOwner(owner_name) {

            for (const [key, value] of Object.entries(this.owners)) {

                if(this.owners[key].owner_name            ==  owner_name) {

                    return key
                }
            }
        },

        //

        prepareClients() {

            this.clients_group      =   {}

            if(this.column_group    ==  1) {

                for (const [i, value] of Object.entries(this.route_import.liste_journey_plan)) {

                    this.clients_group[i]                                                       =   {column_name : i, clients : [], color : this.route_import.liste_journey_plan[i].color}

                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].JPlan   ==  i) {

                            this.clients_group[i].clients.push(this.route_import.clients[j])
                        }
                    }
                }
            }

            if(this.column_group    ==  2) {

                for (const [i, value] of Object.entries(this.route_import.districts)) {

                    this.clients_group[i]                                                       =   {column_name : this.route_import.districts[i].DistrictNameE, clients : [], color : this.route_import.districts[i].color}
                
                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].DistrictNo  ==  i) {

                            this.clients_group[i].clients.push(this.route_import.clients[j])
                        }
                    }
                }
            }

            if(this.column_group    ==  3) {

                for (const [i, value] of Object.entries(this.route_import.cites)) {

                    this.clients_group[i]                                                       =   {column_name : this.route_import.cites[i].CityNameE, clients : [], color : this.route_import.cites[i].color}

                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].CityNo  ==  i) {

                            this.clients_group[i].clients.push(this.route_import.clients[j])
                        }
                    }
                }           
            }

            if(this.column_group    ==  4) {

                for (const [i, value] of Object.entries(this.route_import.liste_type_client)) {

                    this.clients_group[i]                                                       =   {column_name : i, clients : [], color : this.route_import.liste_type_client[i].color}
                
                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].CustomerType    ==  i) {

                            this.clients_group[i].clients.push(this.route_import.clients[j])
                        }
                    }
                }           
            }

            if(this.column_group    ==  5) {

                for (const [i, value] of Object.entries(this.route_import.liste_journee)) {

                    this.clients_group[i]                                                       =   {column_name : i, clients : [], color : this.route_import.liste_journee[i].color}

                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].Journee    ==  i) {

                            this.clients_group[i].clients.push(this.route_import.clients[j])
                        }
                    }
                }           
            }

            if(this.column_group    ==  6) {

                for (const [i, value] of Object.entries(this.route_import.owners)) {

                    this.clients_group[i]                                                       =   {column_name : i, clients : [], color : this.route_import.owners[i].color}

                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].owner_name  ==  i) {

                            this.clients_group[i].clients.push(this.route_import.clients[j])
                        }
                    }
                }           
            }

            if(this.column_group    ==  7) {

                for (const [i, value] of Object.entries(this.route_import.liste_status)) {

                    this.clients_group[i]                                                       =   {column_name : i, clients : [], color : this.route_import.liste_status[i].color}

                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].status    ==  i) {

                            this.clients_group[i].clients.push(this.route_import.clients[j])
                        }
                    }
                }           
            }

            // 

            // Step 1: Convert the object into an array of values
            const clientsGroupsArray = Object.values(this.clients_group);

            // Step 2: Sort the array based on the 'year' property of the sub-objects
            clientsGroupsArray.sort((a, b) => b.clients.length - a.clients.length);

            // Step 3: Convert the sorted array back into an object
            const sortedClientsGroups   =   clientsGroupsArray.reduce((acc, client_group, index) => {
                
                acc[client_group.column_name] = client_group;
                return acc;
            }, {});

            //

            this.clients_group  =   sortedClientsGroups
        },

        async reAfficheClients() {

            // Markers Data
            this.reAfficheClientsMarkers()

            // Table Data
            this.reAfficheClientsTable()
        },

        reAfficheClientsMarkers() {

            this.clients_markers_affiche        =   this.clients_group

            let splice                          =   false

            for (const [key, value] of Object.entries(this.clients_markers_affiche)) {
 
                for (let i = this.clients_markers_affiche[key].clients.length - 1;    i >= 0; --i) {

                    splice          =   false  

                    if(this.journey_plan_filter_value.length    >   0) {

                        if (this.journey_plan_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].JPlan)         ==  -1) {

                            // splice
                            this.clients_markers_affiche[key].clients.splice(i, 1)
                            splice  =   true
                        }
                    }

                    if(splice   ==  false) {

                        if(this.district_filter_value.length    >   0) {

                            if (this.district_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].DistrictNo.toString())  ==  -1) {

                                // splice
                                this.clients_markers_affiche[key].clients.splice(i, 1)
                                splice  =   true
                            }
                        }

                        if(splice   ==  false) {

                            if(this.city_filter_value.length    >   0) {

                                if (this.city_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].CityNo.toString())      ==  -1) {

                                    // splice
                                    this.clients_markers_affiche[key].clients.splice(i, 1)
                                    splice  =   true
                                }
                            }

                            if(splice   ==  false) {

                                if(this.type_client_filter_value.length    >   0) {

                                    if (this.type_client_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].CustomerType.toString())     ==  -1) {

                                        // splice
                                        this.clients_markers_affiche[key].clients.splice(i, 1)
                                        splice  =   true
                                    }
                                }                            

                                if(splice   ==  false) {

                                    if(this.journee_filter_value.length    >   0) {

                                        if (this.journee_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].Journee.toString())          ==  -1) {

                                            // splice
                                            this.clients_markers_affiche[key].clients.splice(i, 1)
                                            splice  =   true
                                        }
                                    }

                                    if(splice   ==  false) {

                                        if(this.owner_filter_value.length    >   0) {

                                            if (this.owner_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].owner_name.toString())          ==  -1) {

                                                // splice
                                                this.clients_markers_affiche[key].clients.splice(i, 1)
                                                splice  =   true
                                            }
                                        }

                                        if(splice   ==  false) {

                                            if(this.status_filter_value.length    >   0) {

                                                if (this.status_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].status.toString())          ==  -1) {

                                                    // splice
                                                    this.clients_markers_affiche[key].clients.splice(i, 1)
                                                    splice  =   true
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if(this.clients_markers_affiche[key].clients.length <   0) {

                    this.clients_markers_affiche[key]
                }
            }

            // Step 1: Convert the object into an array of values
            const clientsMarkersAfficheArray    =   Object.values(this.clients_markers_affiche);

            // Step 2: Sort the array based on the 'year' property of the sub-objects
            clientsMarkersAfficheArray.sort((a, b) => b.clients.length - a.clients.length);

            // Step 3: Convert the sorted array back into an object
            const sortedClientsMarkersAffiche   =   clientsMarkersAfficheArray.reduce((acc, client_marker, index) => {
                
                acc[client_marker.column_name]   =   client_marker;
                return acc;
            }, {});

            //

            this.clients_markers_affiche        =   sortedClientsMarkersAffiche
        },

        reAfficheClientsTable() {

            this.clients_table_affiche  =   []

            for (const [key, value] of Object.entries(this.clients_group)) {
 
                for (let i = this.clients_markers_affiche[key].clients.length - 1;    i >= 0; --i) {

                    this.clients_table_affiche.push(this.clients_markers_affiche[key].clients[i])
                }
            }
        },

        //

        setRouteMarkers() {

            // Clear Drwaings
            this.removeDrawings()

            // Clear Route Data
            this.clearRouteMarkers()

            // Clear Path
            this.clearPath()

            // Set Markers
            this.setMarkers()

            // Focus
            this.focuseMarkers()
        },

        //

        clearRouteMarkers() {

            // Clear Route Data
            this.$map.$clearRouteMarkers()
        },

        async showUserBDTerritoriesFront() {

            // Show BD Territories
            this.$map.$showUserBDTerritoriesFront(this.getUser.user_territories)
        },

        //

        showCurrentPosition() {

            // Clear Route Data
            this.$map.$showCurrentPosition()
        },

        //

        clearPath() {
            // Clear Path
            this.$map.$clearPath()
        },

        setMarkers() {

            this.setClientsArrays()

            // this.$map.$setRouteMarkers(this.clients_non_owner               , 1, "#000000")
            this.$map.$setRouteMarkers(this.clients_owner_validated         , 2, "#0F9D58")
            this.$map.$setRouteMarkers(this.clients_owner_pending           , 3, "#F57C00")
            this.$map.$setRouteMarkers(this.clients_owner_non_validated     , 4, "#F70000")
            this.$map.$setRouteMarkers(this.clients_owner_visible           , 5, "#3949AB")
            this.$map.$setRouteMarkers(this.clients_owner_ferme             , 6, "#000000")
        },

        setClientsArrays() {

            this.clients_non_owner              =   []
            this.clients_owner_validated        =   []
            this.clients_owner_pending          =   []
            this.clients_owner_non_validated    =   []
            this.clients_owner_visible          =   []
            this.clients_owner_ferme            =   []

            for (let i = 0; i < this.route_import.clients.length; i++) {

                // Black
                // if(this.route_import.clients[i].owner   !=  this.getUser.id) {

                //     this.clients_non_owner.push(this.route_import.clients[i])
                // }

                // else {

                    if(this.route_import.clients[i].status == "validated") {

                        this.clients_owner_validated.push(this.route_import.clients[i])
                    }

                    else {

                        if(this.route_import.clients[i].status == "pending") {

                            this.clients_owner_pending.push(this.route_import.clients[i])
                        }

                        else {

                            if(this.route_import.clients[i].status == "nonvalidated") {

                                this.clients_owner_non_validated.push(this.route_import.clients[i])
                            }

                            else {

                                if(this.route_import.clients[i].status == "visible") {

                                    this.clients_owner_visible.push(this.route_import.clients[i])
                                }

                                else {

                                    if(this.route_import.clients[i].status == "ferme") {

                                        this.clients_owner_ferme.push(this.route_import.clients[i])
                                    }
                                }
                            }
                        }
                    }
                // }
            }
        },        

        //

        removeDrawings() {
            this.$map.$removeDrawings()
        },

        //

        focuseMarkers() {
            this.$map.$focuseMarkers()
        },

        //

        // Map

        addMap() {
            this.$map.$createMap(this.$role(), "map", false, false)
        },

        //

        switchMarkerClusterMode() {

            const marker_cluster_mode   =   document.getElementById("marker_cluster_mode")

            if(marker_cluster_mode.checked) {

                // Show Markers
                this.$map.$switchMarkerClusterMode("marker")

                // Show Markers
                this.reAfficherClientsAndMarkers()
            }

            else {

                // Show Markers
                this.$map.$switchMarkerClusterMode("cluster")

                // Show Markers
                this.reAfficherClientsAndMarkers()
            }
        }
    },

    watch: {

        async getUpdateClient(newValue, oldValue) {

            if(newValue != null) {
                
                this.$router.push('/route_import/'+this.$route.params.id_route_import+'/clients/'+newValue.id+'/details')
            }
        },
    },
}

</script>