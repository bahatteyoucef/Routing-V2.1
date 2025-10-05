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

                    <div class="col-2 float-right mt-1 p-0" id="show_position_button">
                        <button class="btn btn-primary w-100 m-0 mt-1 pr-0 pl-0"    @click="showCurrentPosition()"><i class="mdi mdi-crosshairs-gps"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!--  -->

        <!-- Buttons and Filter -->
        <!-- <div id="tableau_data">
            <div class="map_top_buttons_parent_div animate__animated" id="map_top_buttons_parent_div">
                <div class="map_top_buttons_div" id="map_top_buttons_div">
                    <div class="row">
                        <div class="col-2 float-right mt-1 p-0" id="show_position_button">
                            <button class="btn btn-primary w-100 m-0 mt-1 pr-0 pl-0"    @click="showCurrentPosition()"><i class="mdi mdi-crosshairs-gps"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
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
                console.log(res)

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

        //

        setRouteMarkers() {

            // Clear Route Data
            this.clearRouteMarkers()

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