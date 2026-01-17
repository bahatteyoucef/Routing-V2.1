<template>

    <div id="content_route_fo" class="h-100">
        <div id="map_parent_div" class="d-inline-block h-100">
            <div id="show_hide_map_filters_button_div" class="text-right">
                <button class="btn btn-primary"   @click="showCurrentPosition()"><i class="mdi mdi-crosshairs-gps"></i></button>
            </div>

            <div id="map_top_middle_options_div">
                <div class="row">
                    <!-- Toggle -->
                    <div v-if="$isRole('FrontOffice')" class="col p-0 ml-1 d-flex align-items-center">
                        <div id="toggle_div" class="mb-2">
                            <div class="btn-container" id="marker_cluster_mode_div">
                                <label class="switch btn-color-mode-switch">
                                    <input type="checkbox" name="marker_cluster_mode" id="marker_cluster_mode" @change="switchMarkerClusterMode()" true-value="marker" false-value="cluster" v-model="marker_cluster_mode">
                                    <label for="marker_cluster_mode" data-on="Marker" data-off="Cluster" class="btn-color-mode-switch-inner"></label>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="map"></div>
        </div>
    </div>

</template>

<script>

import {mapGetters, mapActions} from "vuex"

import Map                      from '@/services/map'

import emitter                  from    "@/utils/emitter"

export default {
    
    data() {
        return {

            map_instance                :   null                        ,

            //

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
            clients_introuvable             :   [],
            clients_owner_confirmed         :   [],
            clients_owner_validated         :   [],
            clients_owner_pending           :   [],
            clients_owner_non_validated     :   [],
            clients_owner_visible           :   [],
            client_owner_ferme              :   [],
            client_owner_refus              :   [],

            //
            is_database_clients_map         :   false,

            //
            marker_cluster_mode             :   "marker"
        }
    },

    computed: {
        ...mapGetters({
            getSelectedClients      :   'client/getSelectedClients'             ,

            //

            getUser                 :   'authentification/getUser'              ,
        })
    },

    async mounted() {

        this.map_instance               =   new Map()

        //
        const pattern                   =   /^\/route\/frontoffice\/obs\/route-imports\/\d+\/details$/;
        this.is_database_clients_map    =   pattern.test(this.$route.path);

        // add Map
        this.addMap()

        //
        this.showUserBDTerritoriesFront()

        // 
        await this.getData()

        //
        emitter.on('mapUpdateClient'   , async (client)    =>  {
            this.$router.push('/route-imports/'+this.$route.params.id_route_import+'/clients/'+client.id+'/details')
        })
    },

    methods : {

        async getData() {

            // Show Loading Page
            await this.$showLoadingPage()

            if(this.is_database_clients_map) {

                const res                   =   await this.$callApi("post"  ,   "/route/obs/route-imports/"+this.$route.params.id_route_import+"/details/for-front-office",   null)
                this.route_import           =   res.data.route_import

                // Set Clients
                this.route_import.clients   =   this.route_import.data
            }

            else {

                this.route_import           =   {}

                // Set Clients
                this.route_import.clients   =   this.getSelectedClients
            }

            // Set Markers
            this.setRouteMarkers()

            // Hide Loading Page
            await this.$hideLoadingPage()
        }, 

        //  //  //  //  //  //  //  //  //  //

        addMap() {
            this.map_instance.$createMap(this.$role(), "map", false, false, this.marker_cluster_mode)
        },

        async showUserBDTerritoriesFront() {

            // Show BD Territories
            this.map_instance.$showUserBDTerritoriesFront(this.getUser.user_territories)
        },

        async switchMarkerClusterMode() {

            if(this.marker_cluster_mode ==  "marker") {

                // Show Markers
                this.map_instance.$switchMarkerClusterMode("marker")

                // Show Markers
                await this.reAfficherClientsAndMarkers()
            }

            else {

                if(this.marker_cluster_mode ==  "cluster") {

                    // Show Markers
                    this.map_instance.$switchMarkerClusterMode("cluster")

                    // Show Markers
                    await this.reAfficherClientsAndMarkers()
                }
            }
        },

        async reAfficherClientsAndMarkers() {

            // Show Loading Page
            await this.$showLoadingPage()

            //
            this.clearRouteMarkers()

            // Show Markers
            this.setRouteMarkers(this.clients_markers_affiche)

            // Hide Loading Page
            await this.$hideLoadingPage()
        },

        clearRouteMarkers() {
            this.map_instance.$clearRouteMarkers()
        },

        setRouteMarkers() {

            this.setClientsArrays()

            // this.map_instance.$setRouteMarkers(this.clients_non_owner               , 1, "#000000")
            this.map_instance.$setRouteMarkers(this.clients_introuvable             , 1, "#000000") // Black 
            this.map_instance.$setRouteMarkers(this.clients_owner_confirmed         , 2, "#0F9D58") // Green
            this.map_instance.$setRouteMarkers(this.clients_owner_validated         , 3, "#0F9D58") // Green
            this.map_instance.$setRouteMarkers(this.clients_owner_pending           , 4, "#F57C00") // Orange
            this.map_instance.$setRouteMarkers(this.clients_owner_non_validated     , 5, "#F70000") // Red
            this.map_instance.$setRouteMarkers(this.clients_owner_visible           , 6, "#3949AB") // Purple
            this.map_instance.$setRouteMarkers(this.clients_owner_ferme             , 7, "#0288D1") // Blue
            this.map_instance.$setRouteMarkers(this.clients_owner_refus             , 8, "#880E4F") // Blue

            // Focus
            this.focuseMarkers()
        },

        setClientsArrays() {

            this.clients_introuvable            =   []
            this.clients_owner_confirmed        =   []
            this.clients_owner_validated        =   []
            this.clients_owner_pending          =   []
            this.clients_owner_non_validated    =   []
            this.clients_owner_visible          =   []
            this.clients_owner_ferme            =   []
            this.clients_owner_refus            =   []

            for (let i = 0; i < this.route_import.clients.length; i++) {

                // Black
                if(this.route_import.clients[i].status   ==  "introuvable") {

                    this.clients_introuvable.push(this.route_import.clients[i])
                }

                else {

                    if(this.route_import.clients[i].status == "confirmed") {

                        this.clients_owner_confirmed.push(this.route_import.clients[i])
                    }

                    else {

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

                                        else {

                                            if(this.route_import.clients[i].status == "refus") {

                                                this.clients_owner_refus.push(this.route_import.clients[i])
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        },

        focuseMarkers() {
            setTimeout(() => {
                this.map_instance.$focuseMarkers()
            }, 0);
        },

        showCurrentPosition() {

            // Clear Route Data
            this.map_instance.$showCurrentPosition()
        },
    },
}

</script>

<style scoped>

#show_hide_map_filters_button_div {
    margin-top: 15px;
}

</style>