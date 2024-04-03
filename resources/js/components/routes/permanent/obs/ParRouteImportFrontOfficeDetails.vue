<template>

    <div>

        <div id="content_route">
            <div id="map"></div>

            <!--  -->

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

            <!--  -->
        </div>

        <!-- Modal Add                      -->
        <modalClientAdd                                                 ref="modalClientAdd"                                                                                        >   </modalClientAdd>

        <!-- Modal Update                   -->
        <modalClientUpdate                                              ref="modalClientUpdate"                                                                                     >   </modalClientUpdate>

        <!-- Modal Change Route             -->
        <modalClientsChangeRoute                                        ref="modalClientsChangeRoute"                                                                               >   </modalClientsChangeRoute>

        <!-- Modal Decoupe By Journee       -->
        <modalResume                                                    ref="modalResume"               :key="id_route_import"      :type="'permanent'"                             >   </modalResume>

        <!-- Modal Add New Journey Plan     -->
        <modalAddJourneyPlan                                            ref="modalAddJourneyPlan"                                                                                   >   </modalAddJourneyPlan>

        <!-- Modal Add New Journey Plan     -->
        <modalUpdateJourneyPlan                                         ref="modalUpdateJourneyPlan"                                                                                >   </modalUpdateJourneyPlan>

        <!-- Modal Update Map               -->
        <modalUpdateMap                                                 ref="modalUpdateMap"           :key="id_route_import"       :id_route_import="id_route_import"              >   </modalUpdateMap>

        <!-- Modal Update Map               -->
        <modalValidateMap                                               ref="modalValidateMap"         :key="id_route_import"       :id_route_import="id_route_import"              >   </modalValidateMap>

        <!--                                -->   

        <div id="tableau_data">

            <div v-if="$isRole('FrontOffice')" class="col-2 float-right mt-1 p-0" id="show_position_button">
                <button class="btn primary w-100 m-0 mt-1 pr-0 pl-0"                                                        @click="showCurrentPosition()"><i class="mdi mdi-crosshairs-gps"></i></button>
            </div>

            <!--  -->

            <div id="datatable_par_route_import_parent" class="scrollbar scrollbar-deep-blue bg-white p-1 animate__animated" style="display : none">
                <table class="table datatable_par_route_import_details" id="datatable_par_route_import_details">
                    <thead>
                        <tr>
                            <th class="col-sm-1">Index</th>

                            <th class="col-sm-2">CustomerCode</th>
                            <th class="col-sm-2">CustomerNameE</th>
                            <th class="col-sm-2">CustomerNameA</th>

                            <th class="col-sm-2">Latitude</th>
                            <th class="col-sm-2">Longitude</th>

                            <th class="col-sm-2">Address</th>

                            <th class="col-sm-1">DistrictNo</th>
                            <th class="col-sm-2">DistrictNameE</th>

                            <th class="col-sm-1">CityNo</th>
                            <th class="col-sm-2">CityNameE</th>

                            <th class="col-sm-2">Tel</th>

                            <th class="col-sm-1">CustomerType</th>

                            <th class="col-sm-2">JPlan</th>

                            <th class="col-sm-2">Journee</th>

                            <!--  -->

                            <th class="col-sm-2">Owner</th>
                            <th class="col-sm-2">Created At</th>
                            <th class="col-sm-2">Status</th>

                        </tr>
                    </thead>

                    <thead>
                        <tr class="datatable_par_route_import_details_filters">

                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Index"            /></th>

                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerCode"     /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameE"    /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameA"    /></th>

                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Latitude"         /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Longitude"        /></th>

                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Address"          /></th>

                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="DistrictNo"       /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="DistrictNameE"    /></th>

                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CityNo"           /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CityNameE"        /></th>

                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Tel"              /></th>

                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerType"     /></th>

                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="JPlan"            /></th>

                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Journee"          /></th>

                            <!--  -->

                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Owner"            /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Created_At"       /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Status"           /></th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr v-for="(client, index) in clients_table_affiche" :key="client" role="button" @click="showModalupdateClient(client)">
                            <td>{{index +   1}}</td>

                            <td>{{client.CustomerCode}}</td>
                            <td>{{client.CustomerNameE}}</td>
                            <td>{{client.CustomerNameA}}</td>

                            <td>{{client.Latitude}}</td>
                            <td>{{client.Longitude}}</td>

                            <td>{{client.Address}}</td>

                            <td>{{client.DistrictNo}}</td>
                            <td>{{client.DistrictNameE}}</td>

                            <td>{{client.CityNo}}</td>
                            <td>{{client.CityNameE}}</td>

                            <td>{{client.Tel}}</td>

                            <td>{{client.CustomerType}}</td>

                            <td>{{client.JPlan}}</td>

                            <td>{{client.Journee}}</td>

                            <!--  -->

                            <td>{{client.owner_name}}</td>
                            <td>{{client.created_at}}</td>

                            <td>
                                <span v-if="client.status=='nonvalidated'"  href="#" class="badge badge-danger">{{client.status}}</span>
                                <span v-if="client.status=='pending'"       href="#" class="badge badge-warning">{{client.status}}</span>
                                <span v-if="client.status=='validated'"     href="#" class="badge badge-success">{{client.status}}</span>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
 
        <!--                                -->

    </div>

</template>

<script>

import Multiselect              from '@vueform/multiselect'

import modalClientAdd           from "../../../clients/permanent/modalClientAdd.vue"
import modalClientUpdate        from "../../../clients/permanent/modalClientUpdate.vue"
import modalClientsChangeRoute  from "../../../clients/permanent/modalClientsChangeRoute.vue"
import modalResume              from "../../modalResume.vue"

import modalAddJourneyPlan      from "../../../territoires/modalAddJourneyPlan.vue"
import modalUpdateJourneyPlan   from "../../../territoires/modalUpdateJourneyPlan.vue"

import modalUpdateMap           from "../../../routes/permanent/modalUpdateMap.vue"
import modalValidateMap         from "../../../routes/permanent/modalValidateMap.vue"

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

            // Event
            event_on_process            :   false                       ,

            // Datatable                
            datatable_par_route_import_details  :   null                ,

            // latlngs selected Polygon
            latlngs                             :   null                ,
            journey_plan                        :   null                ,

            client : {

                lat :   0,
                lng :   0
            },

            clients_non_owner               :   [],
            clients_owner_validated         :   [],
            clients_owner_pending           :   [],
            clients_owner_non_validated     :   []
        }
    },  

    components: {

        Multiselect,

        modalClientAdd              ,
        modalClientUpdate           ,
        modalClientsChangeRoute     ,
        modalResume                 ,

        modalAddJourneyPlan         ,
        modalUpdateJourneyPlan      ,

        modalUpdateMap              ,
        modalValidateMap         
    },

    computed: {

        ...mapGetters({

            getAddClient            : 'client/getAddClient'                 ,
            getUpdateClient         : 'client/getUpdateClient'              ,
            getClientsChangeRoute   : 'client/getClientsChangeRoute'        ,

            getAddJourneyPlan       : 'journey_plan/getAddJourneyPlan'      ,

            //

            getUser                 :   'authentification/getUser'              ,
            getAccessToken          :   'authentification/getAccessToken'       ,
            getIsAuthentificated    :   'authentification/getIsAuthentificated'
        })

    },

    async mounted() {

        // add Map
        this.addMap()

        //
        this.showUserBDTerritoriesFront()

        // 
        this.setValues()

        // 
        await this.getData()

        // CRUD Events

        this.emitter.on('reSetAdd'          , async (client)    =>  {

            this.addClientJSON(client)

            this.removeDrawings()
        })

        this.emitter.on('reSetUpdate'       , async (client)    =>  {

            this.updateClientJSON(client)

            this.removeDrawings()
        })

        this.emitter.on('reSetDelete'       , async (client)    =>  {

            this.deleteClientJSON(client)

            this.removeDrawings()
        })

        this.emitter.on('reSetValidate'     , async (client)    =>  {

            this.validateClientJSON(client)

            this.removeDrawings()
        })

        this.emitter.on('reSetChangeRoute'  , async (clients)    =>  {

            this.changeRouteClientsJSON(clients)

            this.removeDrawings()
        })

        this.emitter.on('reSetClientsDecoupeByJourneeMap' , (clients)  =>  {

            this.route_import.clients   =   clients

            this.reAfficherClientsAndMarkers()
        })

        this.emitter.on('reSetClientsUpdateMap'             , async ()  =>  {

            await this.getData()
        })

        //

        this.emitter.on('reSetJPlanBDTerritory' , () =>  {

            this.showJPlanBDTerritories()
        })

        this.emitter.on('reSetJourneeBDTerritory' , () =>  {

            this.showJourneeBDTerritories()
        })
    },

    methods : {

        ...mapActions("journey_plan" ,  [
            "setListeJourneyPlanAction"     ,
        ]),

        ...mapActions("type_client" ,  [
            "setListeTypeClientAction"     ,
        ]),

        ...mapActions("journee" ,  [
            "setListeJourneeAction"     ,
        ]),

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

            const res                   =   await this.$callApi("post"  ,   "route/obs/route_import/"+this.id_route_import+"/details/by_owner",   null)

            this.route_import           =   res.data.route_import

            // Set Clients
            this.route_import.clients   =   this.route_import.data

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

            //

            this.setListeJourneyPlanAction(this.liste_journey_plan)
            this.setListeTypeClientAction(this.liste_type_client)
            this.setListeJourneeAction(this.liste_journee)
            // this.setOwnersAction(this.owners)
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

        reAfficherClientsAndMarkersSelect() {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(async () => {

                // reAffiche Markers
                this.setRouteMarkers()

                // Hide Loading Page
                this.$hideLoadingPage()

            }, 55);

        },

        reAfficherClientsAndMarkers() {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(async () => {

                // reAffiche Markers
                this.setRouteMarkers()

                // Hide Loading Page
                this.$hideLoadingPage()

            }, 55);
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

                this.status_filter_value           =   [column_name]
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

            // Datatable
            await this.setDataTable()
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

        async setDataTable() {

            try {

                // Destroy DataTable
                if(this.datatable_par_route_import_details)  {

                    this.datatable_par_route_import_details.destroy()
                }

                this.datatable_par_route_import_details     =   await this.$DataTableCreate("datatable_par_route_import_details")
            }

            catch(e) {

                console.log(e)
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

        //

        async showJPlanBDTerritories() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("liste_journey_plan", JSON.stringify(this.journey_plan_filter_value)) 

            const res   = await this.$callApi('post'    ,   '/route_import/'+this.route_import.id+'/journey_plan/util'   ,   formData)      

            if(res.status===200){

                // Show BD Territories
                this.$map.$showJPlanBDTerritories(res.data)

                // Hide Loading Page
                this.$hideLoadingPage()
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },

        async showJourneeBDTerritories() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("liste_journey_plan"    , JSON.stringify(this.journey_plan_filter_value)) 
            formData.append("journees"              , JSON.stringify(this.journee_filter_value)) 

            const res   = await this.$callApi('post'    ,   '/route_import/'+this.route_import.id+'/journees/util'   ,   formData)      

            if(res.status===200){

                // Show BD Territories
                this.$map.$showJourneeBDTerritories(res.data)

                // Hide Loading Page
                this.$hideLoadingPage()
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
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

            this.$map.$setRouteMarkers(this.clients_non_owner               , 1, "#000000")

            this.$map.$setRouteMarkers(this.clients_owner_validated         , 2, "#0F9D58")

            this.$map.$setRouteMarkers(this.clients_owner_pending           , 3, "#F57C00")

            this.$map.$setRouteMarkers(this.clients_owner_non_validated     , 4, "#F70000")
        },

        setClientsArrays() {

            this.clients_non_owner               =   []
            this.clients_owner_validated         =   []
            this.clients_owner_pending           =   []
            this.clients_owner_non_validated     =   []

            for (let i = 0; i < this.route_import.clients.length; i++) {

                // Black
                if(this.route_import.clients[i].owner   !=  this.getUser.id) {

                    this.clients_non_owner.push(this.route_import.clients[i])
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
                        }
                    }
                }
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

        showTerritories() {

            this.$map.$showTerritories()
        },

        //

        addClientJSON(client) {

            let new_client      =   {}

            // Add Client
            new_client.id               =   client.id
            new_client.CustomerCode     =   client.CustomerCode

            new_client.CustomerNameE    =   client.CustomerNameE
            new_client.CustomerNameA    =   client.CustomerNameA
            new_client.Tel              =   client.Tel

            new_client.Address          =   client.Address
            new_client.DistrictNo       =   client.DistrictNo      
            new_client.DistrictNameE    =   client.DistrictNameE   
            new_client.CityNo           =   client.CityNo           
            new_client.CityNameE        =   client.CityNameE       

            new_client.Latitude         =   client.Latitude         
            new_client.Longitude        =   client.Longitude        

            new_client.CustomerType     =   client.CustomerType     

            new_client.JPlan            =   client.JPlan            
            new_client.Journee          =   client.Journee        

            new_client.owner_name       =   this.getUser.nom
            new_client.created_at       =   this.$formatDate(new Date())

            new_client.status                  =   client.status            
            new_client.nonvalidated_details    =   client.nonvalidated_details        

            new_client.facade_image                     =   client.facade_image            
            new_client.in_store_image                   =   client.in_store_image        
            new_client.facade_image_original_name       =   client.facade_image_original_name            
            new_client.in_store_image_original_name     =   client.in_store_image_original_name        

            this.route_import.clients.push(new_client)

            // ReAffiche
            this.reAfficherClientsAndMarkers()
        },

        updateClientJSON(client) {

            for (let i = 0; i < this.route_import.clients.length; i++) {
                
                if(this.route_import.clients[i].id  ==  client.id) {

                    // Update Client

                    this.route_import.clients[i].CustomerCode   =   client.CustomerCode

                    this.route_import.clients[i].CustomerNameE  =   client.CustomerNameE
                    this.route_import.clients[i].CustomerNameA  =   client.CustomerNameA
                    this.route_import.clients[i].Tel            =   client.Tel

                    this.route_import.clients[i].Address        =   client.Address
                    this.route_import.clients[i].DistrictNo     =   client.DistrictNo      
                    this.route_import.clients[i].DistrictNameE  =   client.DistrictNameE   
                    this.route_import.clients[i].CityNo         =   client.CityNo           
                    this.route_import.clients[i].CityNameE      =   client.CityNameE       

                    this.route_import.clients[i].Latitude       =   client.Latitude         
                    this.route_import.clients[i].Longitude      =   client.Longitude        

                    this.route_import.clients[i].CustomerType   =   client.CustomerType     

                    this.route_import.clients[i].JPlan              =   client.JPlan            
                    this.route_import.clients[i].Journee            =   client.Journee        

                    this.route_import.clients[i].status                 =   client.status            
                    this.route_import.clients[i].nonvalidated_details   =   client.nonvalidated_details        

                    this.route_import.clients[i].facade_image                   =   client.facade_image            
                    this.route_import.clients[i].in_store_image                 =   client.in_store_image        
                    this.route_import.clients[i].facade_image_original_name     =   client.facade_image_original_name            
                    this.route_import.clients[i].in_store_image_original_name   =   client.in_store_image_original_name        

                    break
                }
            }

            // ReAffiche
            this.reAfficherClientsAndMarkers()
        },

        validateClientJSON(client) {

            for (let i = 0; i < this.route_import.clients.length; i++) {
                
                if(this.route_import.clients[i].id  ==  client.id) {

                    // Update Client

                    this.route_import.clients[i].status                 =   client.status            
                    this.route_import.clients[i].nonvalidated_details   =   client.nonvalidated_details        

                    break
                }
            }

            // ReAffiche
            this.reAfficherClientsAndMarkers()
        },

        deleteClientJSON(client) {

            for (let i = 0; i < this.route_import.clients.length; i++) {
                
                if(this.route_import.clients[i].id  ==  client.id) {

                    this.route_import.clients.splice(i, 1)

                    break
                }
            }

            // ReAffiche
            this.reAfficherClientsAndMarkers()

        },

        changeRouteClientsJSON(clients) {

            for(let i = 0; i < clients.length; i++) {
                for (let j = 0; j < this.route_import.clients.length; j++) {
                    
                    if(this.route_import.clients[j].id  ==  clients[i].id) {

                        // Update Client
                        if(clients[i].owner         !=  "") {

                            this.route_import.clients[j].owner          =   clients[i].owner      
                            this.route_import.clients[j].owner_name     =   clients[i].owner_name      
                        }

                        if(clients[i].DistrictNo    !=  "") {

                            this.route_import.clients[j].DistrictNo     =   clients[i].DistrictNo      
                            this.route_import.clients[j].DistrictNameE  =   clients[i].DistrictNameE   
                        }

                        if(clients[i].CityNo        !=  "") {

                            this.route_import.clients[j].CityNo         =   clients[i].CityNo           
                            this.route_import.clients[j].CityNameE      =   clients[i].CityNameE       
                        }

                        if(clients[i].JPlan         !=  "") {

                            this.route_import.clients[j].JPlan          =   clients[i].JPlan     
                        }

                        if(clients[i].Journee       !=  "") {

                            this.route_import.clients[j].Journee        =   clients[i].Journee        
                        }

                        break
                    }
                }
            }

            // ReAffiche
            this.reAfficherClientsAndMarkers()
        },

        //

        async validerData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("data", JSON.stringify(this.route_import.clients))

            const res   = await this.$callApi('post'    ,   '/route_import/'+this.route_import.id+'/update'    ,   formData)      

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess("Modifications Validés"     ,   "les changements effectués ont été valides avec succès !")
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },

        async showResume() {

            await this.$refs.modalResume.getClients()
        },

        async getDoubles() {

            await this.$refs.modalValidateMap.getDoubles()
        },

        // Map

        addMap() {

            this.$map.$createMap(this.$role())
        },

        addClient(client) {

            try {
            
                this.$refs.modalClientAdd.getData(client, this.clients_table_affiche)
            }
            catch(e) {

                console.log(e)
            }
        },

        updateClient(client) {

            try {
                this.$refs.modalClientUpdate.getData(client, this.clients_table_affiche)
            }
            catch(e) {

                console.log(e)
            }
        },

        showModalupdateClient(client) {

            // ShowModal
            var updateModal     =   new Modal(document.getElementById("updateClientModal"));
            updateModal.show();

            // Send DATA To Modal
            this.updateClient(client)
        },

        async updateClientsRoute(clients) {

            try {

                await this.$refs.modalClientsChangeRoute.getData(clients)
            }
            catch(e) {

                console.log(e)
            }
        },

        //

        async addClientFront() {

            try {
            
                let position     =   await this.$currentPosition()

                let client      =   { lat : 0, lng : 0 }

                client.lat      =   position.coords.latitude
                client.lng      =   position.coords.longitude

                // ShowModal
                var addModal    =   new Modal(document.getElementById("addClientModal"));
                addModal.show();

                this.addClient(client)
            }

            catch(e) {

                console.log(e)
            }
        },

        //

        AddJourneyPlan(LatLngs) {

            try {

                this.$refs.modalAddJourneyPlan.getData(LatLngs)
            }

            catch(e) {

                console.log(e)
            }
        },

        UpdateJourneyPlan(journey_plan) {

            try {

                this.$refs.modalUpdateJourneyPlan.getData(journey_plan)
            }

            catch(e) {

                console.log(e)
            }
        },

        //

        showRouteDatatable() {

            // Datatable

            let datatable_par_route_import_parent_element               =   document.getElementById("datatable_par_route_import_parent")
            datatable_par_route_import_parent_element.classList.remove("animate__fadeOutDown")
            datatable_par_route_import_parent_element.classList.add("animate__fadeInUp")

            datatable_par_route_import_parent_element.style.display     =   "block" 

            // Show Button

            let show_route_datatable_icon_div_element                   =   document.getElementById("show_route_datatable_icon_div")
            show_route_datatable_icon_div_element.classList.remove("animate__fadeIn")
            show_route_datatable_icon_div_element.classList.add("animate__fadeOut")

            setTimeout(() => {

                show_route_datatable_icon_div_element.style.display     =   "none" 
            }, 555)

            // Hide Button

            let hide_route_datatable_icon_div_element                   =   document.getElementById("hide_route_datatable_icon_div")
            hide_route_datatable_icon_div_element.classList.remove("animate__fadeOut")
            hide_route_datatable_icon_div_element.classList.add("animate__fadeIn")

            hide_route_datatable_icon_div_element.style.display         =   "block" 

            // Map

            let map_element                                             =   document.getElementById("map")
            map_element.style.height                                    =   "50%"
        },

        hideRouteDatatable() {

            // Datatable

            let datatable_par_route_import_parent_element               =   document.getElementById("datatable_par_route_import_parent")
            datatable_par_route_import_parent_element.classList.remove("animate__fadeInUp")
            datatable_par_route_import_parent_element.classList.add("animate__fadeOutDown")

            setTimeout(() => {

                datatable_par_route_import_parent_element.style.display     =   "none" 
            }, 555)

            // Hide Button

            let hide_route_datatable_icon_div_element                   =   document.getElementById("hide_route_datatable_icon_div")
            hide_route_datatable_icon_div_element.classList.remove("animate__fadeIn")
            hide_route_datatable_icon_div_element.classList.add("animate__fadeOut")

            setTimeout(() => {

                hide_route_datatable_icon_div_element.style.display         =   "none" 
            }, 555);

            // Show Button

            let show_route_datatable_icon_div_element                   =   document.getElementById("show_route_datatable_icon_div")
            show_route_datatable_icon_div_element.classList.remove("animate__fadeOut")
            show_route_datatable_icon_div_element.classList.add("animate__fadeIn")

            show_route_datatable_icon_div_element.style.display         =   "block" 

            // Map

            let map_element                                             =   document.getElementById("map")
            map_element.style.height                                    =   "100%"
        },

        //

        switchMarkerClusterMode() {

            const marker_cluster_mode   =   document.getElementById("marker_cluster_mode")

            if(marker_cluster_mode.checked) {

                // Show Markers
                this.$map.$switchMarkerClusterMode("marker")

                // Show Markers
                this.reAfficherClientsAndMarkersSelect()
            }

            else {

                // Show Markers
                this.$map.$switchMarkerClusterMode("cluster")

                // Show Markers
                this.reAfficherClientsAndMarkersSelect()
            }
        }
    },

    watch: {

        async getAddClient(newValue, oldValue) {

            if(newValue != null) {
                
                this.$router.push('/route_import/'+this.$route.params.id_route_import+'/clients/add/'+newValue.lat+'/'+newValue.lng)

                // Send DATA To Modal
                // this.addClient(newValue)
            }
        },

        async getUpdateClient(newValue, oldValue) {

            if(newValue != null) {
                
                this.$router.push('/route_import/'+this.$route.params.id_route_import+'/clients/'+newValue.id+'/update')

                // Send DATA To Modal
                // this.updateClient(newValue)
            }
        },

        async getClientsChangeRoute(newValue, oldValue) {

            if((newValue != null)&&(newValue != {})) {
                
                // ShowModal
                var clientsChangeRouteModal     =   new Modal(document.getElementById("clientsChangeRouteModal"));
                clientsChangeRouteModal.show();

                // Send DATA To Modal
                await this.updateClientsRoute(newValue)
            }
        },

        //

        getAddJourneyPlan(newValue, oldValue) {

            if(newValue != null) {
                
                if((typeof newValue.journey_plan    ==  "undefined")&&(typeof newValue.journee  ==  "undefined")) {

                    // ShowModal
                    var addJourneyPlanModal        =   new Modal(document.getElementById("addJourneyPlanModal"));
                    addJourneyPlanModal.show();

                    // Send DATA To Modal
                    this.AddJourneyPlan(newValue.latlngs)
                }

                if(typeof newValue.journey_plan    !=  "undefined") {

                    // ShowModal
                    var updateJourneyPlanModal      =   new Modal(document.getElementById("updateJourneyPlanModal"));
                    updateJourneyPlanModal.show();

                    // Send DATA To Modal
                    this.UpdateJourneyPlan(newValue.journey_plan)
                }

                if(typeof newValue.journee          !=  "undefined") {

                    // ShowModal
                    var updateJourneyPlanModal      =   new Modal(document.getElementById("updateJourneyPlanModal"));
                    updateJourneyPlanModal.show();

                    // Send DATA To Modal
                    this.UpdateJourneyPlan(newValue.journee)
                }
            }
        }

        //
    },
}
</script>
