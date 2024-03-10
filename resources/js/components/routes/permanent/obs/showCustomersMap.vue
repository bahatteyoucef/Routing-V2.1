<template>

    <div>

        <div id="content_route">
            <div id="map"></div>

            <!--  -->

            <div id="hide_route_datatable_icon_div" class="animate__animated"   style="display : none">
                <img  :src="'/images/hide_route_datatable.png'" role="button"   @click="hideRouteDatatable()"/>
            </div>

            <div id="map_top_middle_options_div">

                <div class="row">
                    <!-- Map Info -->
                    <div v-if="(($isRole('Super Admin'))||($isRole('BackOffice')))" class="col p-0 ml-1">
                        <div class="map_top_infos_div">
                            <table class="table table-borderless">

                                <Popper click placement="bottom">
                                    <img  :src="'/images/map_infos_2.png'" role="button" style="width : 35px; padding : 0;"/>

                                    <template #content>
                                        <table class="table table-borderless scrollbar scrollbar-deep-blue">
                                            <tr v-for="groupe in clients_markers_affiche" :key="groupe">
                                                <th class="p-1 col-7"><span @click="reAfficherClientsAndMarkersByColor(groupe.column_name)" role="button">{{ groupe.column_name }} : </span></th>
                                                <td class="p-1 col-3"><span>{{ groupe.clients.length }} Clients </span></td>
                                                <td class="p-1 col-1">
                                                    <span   :style="    'display: inline-block;'+
                                                                        'width: 15px;          '+
                                                                        'height: 15px;         '+
                                                                        'float: right;         '+
                                                                        'background-color: '    +groupe.color+';'">
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>                            
                                    </template>
                                </Popper>

                            </table>
                        </div>
                    </div>

                    <!-- Toggle -->
                    <div v-if="(($isRole('Super Admin'))||($isRole('BackOffice')))" class="col p-0 ml-1">
                        <div id="toggle_div">

                            <div class="btn-container" id="marker_cluster_mode_div">
                                <label class="switch btn-color-mode-switch">
                                    <input type="checkbox" name="marker_cluster_mode" id="marker_cluster_mode" @change="switchMarkerClusterMode()">
                                    <label for="marker_cluster_mode" data-on="Marker" data-off="Cluster" class="btn-color-mode-switch-inner"></label>
                                </label>
                            </div>

                        </div>
                    </div>

                    <div v-if="$isRole('FrontOffice')" class="col p-0 ml-1">
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

            <div class="leaflet-top leaflet-left" style="margin-top : 150px;margin-left: 15px;">  
                <div class="leaflet-control-zoom leaflet-bar leaflet-control ml-0">
                    <div id="refresh_button_map_data_div"       class="col p-0 h-100" >   
                        <button id="refresh_button_map_data"    class="btn btn-sm p-1"  style="background-color : #3498DB;"    @click="getData()">
                            <i class="mdi mdi-refresh"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="leaflet-bottom leaflet-right"               style="margin-bottom: 135px; width: 120px;">  
                <div class="leaflet-control-zoom leaflet-control">
                    <div id="map_buttons_div"   class="col p-0">   
                        
                        <div v-if="(($isRole('Super Admin'))||($isRole('BackOffice')))">
                            <button class="btn secondary w-100 m-0 mt-1"    style="background-color : #FFFFFF; color : #424242; font-size : 13px; border-radius : 26px; box-shadow : 0px 0px 6px -2px #111"      @click="focuseMarkers()">Focus</button>
                            <button class="btn secondary w-100 m-0 mt-1"    style="background-color : #FFFFFF; color : #424242; font-size : 13px; border-radius : 26px; box-shadow : 0px 0px 6px -2px #111"      @click="showTerritories()">Auto</button>
                            <button class="btn secondary w-100 m-0 mt-1"    style="background-color : #FFFFFF; color : #424242; font-size : 13px; border-radius : 26px; box-shadow : 0px 0px 6px -2px #111"      @click="showJPIDBDTerritories()">JPID</button>
                            <button class="btn secondary w-100 m-0 mt-1"    style="background-color : #FFFFFF; color : #424242; font-size : 13px; border-radius : 26px; box-shadow : 0px 0px 6px -2px #111"      @click="showJourneeBDTerritories()">Journee</button>
                        </div>

                        <div v-if="$isRole('FrontOffice')">
                            <button class="btn secondary w-100 m-0 mt-1"    style="background-color : #FFFFFF; color : #424242; font-size : 13px; border-radius : 26px; box-shadow : 0px 0px 6px -2px #111"      @click="showCurrentPosition()">Position</button>
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

        <!-- Modal Add New Journey Plan     -->
        <modalAddJourneyPlan                                            ref="modalAddJourneyPlan"                                                                                   >   </modalAddJourneyPlan>

        <!-- Modal Add New Journey Plan     -->
        <modalUpdateJourneyPlan                                         ref="modalUpdateJourneyPlan"                                                                                >   </modalUpdateJourneyPlan>

        <!--  -->

        <div id="tableau_data">

            <!-- Buttons and Filter -->
            <div v-if="$isRole('BackOffice')||$isRole('Super Admin')" class="map_top_buttons_parent_div animate__animated" style="width : 10%" id="map_top_buttons_parent_div">
                <div class="map_top_buttons_div float-right" id="map_top_buttons_div">
                    <div class="row d-flex justify-content-end">

                        <div class="col-12">

                            <select class="form-select w-100 m-0 mt-1"                                                          @change="reAfficherClientsAndMarkers()"  v-model="column_group">
                                <option value="1">JPID</option>
                                <option value="2">CityNameE</option>
                                <option value="3">AreaNameE</option>
                                <option value="4">CustomerType</option>
                                <option value="5">Journee</option>
                                <option value="6">Owner</option>
                                <option value="7">Status</option>
                            </select>

                            <!-- Journey Plan   -->
                            <Multiselect
                                v-model     =   "journey_plan_filter_value"
                                :options    =   "liste_journey_plan"
                                mode        =   "tags"
                                placeholder =   "Filter By JPID"
                                class       =   "mt-1"

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "true"

                                @change             =   "reAfficherClientsAndMarkers()"
                            />
                            <!--                -->

                            <!-- City       -->
                            <Multiselect
                                v-model     =   "city_filter_value"
                                :options    =   "cities"
                                mode        =   "tags"
                                placeholder =   "Filter By CityNameE"
                                class       =   "mt-1"

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "true"

                                @change             =   "reAfficherClientsAndMarkers()"
                            />
                            <!--                -->

                            <!-- Area           -->
                            <Multiselect
                                v-model     =   "area_filter_value"
                                :options    =   "areas"
                                mode        =   "tags"
                                placeholder =   "Filter By AreaNameE"
                                class       =   "mt-1"

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "true"

                                @change             =   "reAfficherClientsAndMarkers()"
                            />
                            <!--                -->

                            <!-- CustomerType   -->
                            <Multiselect
                                v-model     =   "type_client_filter_value"
                                :options    =   "liste_type_client"
                                mode        =   "tags"
                                placeholder =   "Filter By CustomerType"
                                class       =   "mt-1"

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "true"

                                @change             =   "reAfficherClientsAndMarkers()"
                            />

                            <!-- Journee        -->
                            <Multiselect
                                v-model     =   "journee_filter_value"
                                :options    =   "liste_journee"
                                mode        =   "tags"
                                placeholder =   "Filter By Journee"
                                class       =   "mt-1"

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "true"

                                @change             =   "reAfficherClientsAndMarkers()"
                            />
                            <!--                -->

                            <!-- Owners        -->
                            <Multiselect
                                v-model     =   "owner_filter_value"
                                :options    =   "owners"
                                mode        =   "tags"
                                placeholder =   "Filter By Owner"
                                class       =   "mt-1"

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "true"

                                @change             =   "reAfficherClientsAndMarkers()"
                            />
                            <!--                -->

                            <!-- Status        -->
                            <Multiselect
                                v-model     =   "status_filter_value"
                                :options    =   "liste_status"
                                mode        =   "tags"
                                placeholder =   "Filter By Status"
                                class       =   "mt-1"

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "true"

                                @change             =   "reAfficherClientsAndMarkers()"
                            />
                            <!--                -->

                            <!-- Status        -->
                            <Multiselect
                                v-model     =   "kml_layers"
                                :options    =   "kml_cities"
                                mode        =   "tags"
                                placeholder =   "Select City KML"
                                class       =   "mt-1"

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "true"

                                @select             =   "setKMLLayers()"
                                @deselect           =   "setKMLLayers()"
                                @clear              =   "setKMLLayers()"
                            />
                            <!--                -->
                        </div>
                    </div>
                </div>
            </div>

            <!--  -->

            <div id="datatable_par_route_import_parent" class="scrollbar scrollbar-deep-blue bg-white p-1 animate__animated" style="display : none">
                <table class="table datatable_par_route_import_details" id="datatable_par_route_import_details" style="margin-top : 105px">
                    <thead>
                        <tr>
                            <th class="col-sm-1">Index</th>

                            <th class="col-sm-2">CustomerNo</th>

                            <th class="col-sm-2">CustomerNameE</th>
                            <th class="col-sm-2">CustomerNameA</th>

                            <th class="col-sm-2">Latitude</th>
                            <th class="col-sm-2">Longitude</th>

                            <th class="col-sm-2">Address</th>

                            <th class="col-sm-1">AreaNo</th>
                            <th class="col-sm-2">AreaNameE</th>

                            <th class="col-sm-1">CityNo</th>
                            <th class="col-sm-2">CityNameE</th>

                            <th class="col-sm-2">Tel</th>

                            <th class="col-sm-1">CustomerType</th>
                            <th class="col-sm-1">CategoryNo</th>

                            <th class="col-sm-2">JPID</th>
                            <th class="col-sm-2">Journee</th>

                            <!--  -->

                            <th class="col-sm-2">Owner</th>
                            <th class="col-sm-2">Status</th>
                        </tr>
                    </thead>

                    <thead>
                        <tr class="datatable_par_route_import_details_filters">

                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Index"            /></th>

                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerNo"       /></th>

                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameE"    /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameA"    /></th>

                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Latitude"         /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Longitude"        /></th>

                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Address"          /></th>

                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="AreaNo"           /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="AreaNameE"        /></th>

                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CityNo"       /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CityNameE"    /></th>

                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Tel"              /></th>

                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerType"     /></th>
                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CategoryNo"       /></th>

                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="JPID"             /></th>
                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Journee"          /></th>

                            <!--  -->

                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Owner"            /></th>
                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Status"           /></th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr v-for="(client, index) in clients_table_affiche" :key="client" role="button" @click="showModalupdateClient(client)">
                            <td>{{index +   1}}</td>

                            <td>{{client.CustomerNo}}</td>
                            <td>{{client.CustomerNameE}}</td>
                            <td>{{client.CustomerNameA}}</td>

                            <td>{{client.Latitude}}</td>
                            <td>{{client.Longitude}}</td>

                            <td>{{client.Address}}</td>

                            <td>{{client.AreaNo}}</td>
                            <td>{{client.AreaNameE}}</td>

                            <td>{{client.CityNo}}</td>
                            <td>{{client.CityNameE}}</td>

                            <td>{{client.Tel}}</td>

                            <td>{{client.CustomerType}}</td>
                            <td>{{client.CategoryNo}}</td>

                            <td>{{client.JPID}}</td>
                            <td>{{client.Journee}}</td>

                            <!--  -->

                            <td>{{client.owner_name}}</td>

                            <td>
                                <span v-if="client.CustomerStatus=='nonvalidated'"  href="#" class="badge badge-danger">{{client.CustomerStatus}}</span>
                                <span v-if="client.CustomerStatus=='pending'"       href="#" class="badge badge-warning">{{client.CustomerStatus}}</span>
                                <span v-if="client.CustomerStatus=='validated'"     href="#" class="badge badge-success">{{client.CustomerStatus}}</span>
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

import modalClientAdd           from "../../../back_office/clients/modalClientAdd.vue"
import modalClientUpdate        from "../../../back_office/clients/modalClientUpdate.vue"
import modalClientsChangeRoute  from "../../../back_office/clients/modalClientsChangeRoute.vue"
import modalResume              from "../../../back_office/routes/imports/modalResume.vue"
import modalDevide              from "../../../back_office/routes/imports/modalDevide.vue"

import modalAddJourneyPlan      from "../../../back_office/territoires/modalAddJourneyPlan.vue"
import modalUpdateJourneyPlan   from "../../../back_office/territoires/modalUpdateJourneyPlan.vue"

import modalUpdateMap           from "../../../back_office/routes/imports/Map/modalUpdateMap.vue"
import modalValidateMap         from "../../../back_office/routes/imports/Map/modalValidateMap.vue"

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

            // City

            city_filter_value           :   []                          ,
            cities                      :   {}                          ,

            // Area

            area_filter_value           :   []                          ,
            areas                       :   {}                          ,

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

            //

            kml_cities                :   [],
            kml_layers                  :   null,
        }
    },  

    components: {

        Multiselect,

        modalClientAdd              ,
        modalClientUpdate           ,
        modalClientsChangeRoute     ,
        modalResume                 ,
        modalDevide                 ,

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
            getIsAuthentificated    :   'authentification/getIsAuthentificated' ,

            //

            getSelectedClients      :   'client/getSelectedClients' 
        })
    },

    async mounted() {

        // add Map
        this.addMap()

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

        this.emitter.on('reSetJPIDBDTerritory' , () =>  {

            this.showJPIDBDTerritories()
        })

        this.emitter.on('reSetJourneeBDTerritory' , () =>  {

            this.showJourneeBDTerritories()
        })
    },

    methods : {

        ...mapActions("journey_plan"    ,  [
            "setListeJourneyPlanAction"     ,
        ]),

        ...mapActions("type_client"     ,  [
            "setListeTypeClientAction"      ,
        ]),

        ...mapActions("journee"         ,  [
            "setListeJourneeAction"         ,
        ]),

        ...mapActions("client"          ,  [
            "setSelectedClientsAction"
        ]),

        //

        async getData() {

            // Show Loading Page
            this.$showLoadingPage()

            // const res                   =   await this.$callApi("post"  ,   "/front_office/presellers/rtm_cities",   null)

            this.route_import           =   {}

            // Set Clients
            this.route_import.clients   =   this.getSelectedClients

            // 
            // this.setKMLCities(res.data.cities)

            // Extract JPID, Areas, City
            this.extractMetaData()

            // Prepare Clients
            this.prepareClients()

            // Prepare Affiche Clients
            await this.reAfficheClients()

            // Set Markers
            this.setRouteMarkers()

            // Hide Loading Page
            this.$hideLoadingPage()
        }, 

        //

        extractMetaData() {

            let journey_plan_count                  =   0
            let city_count                          =   0
            let area_count                          =   0
            let type_client_count                   =   0
            let journee_count                       =   0
            let owner_count                         =   0
            let status_count                        =   0

            let journey_plan_existe                 =   false
            let city_existe                         =   false
            let area_existe                         =   false
            let type_client_existe                  =   false
            let journee_existe                      =   false
            let owner_existe                        =   false
            let status_existe                       =   false

            let journey_plan_util                   =   {}
            let city_util                           =   {}
            let area_util                           =   {}
            let type_client_util                    =   {}
            let journee_util                        =   {}
            let owner_util                          =   {}
            let status_util                         =   {}

            let liste_journey_plan_colors           =   {}
            let cities_colors                       =   {}
            let areas_colors                        =   {}
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

            for (const [key, value] of Object.entries(this.cities)) {

                if(typeof cities_colors[this.cities[key].color]                         ==  "undefined") {

                    cities_colors[this.cities[key].color]                                   =   this.cities[key].color
                }
            }

            for (const [key, value] of Object.entries(this.areas)) {

                if(typeof areas_colors[this.areas[key].color]                           ==  "undefined") {

                    areas_colors[this.areas[key].color]                                     =   this.areas[key].color
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

                if(typeof owner_util[this.owners[key].color]                            ==  "undefined") {

                    owners_colors[this.owners[key].color]                                   =   this.owners[key].color
                }
            }

            for (const [key, value] of Object.entries(this.liste_status)) {

                if(typeof status_util[this.liste_status[key].color]                     ==  "undefined") {

                    liste_status_colors[this.liste_status[key].color]                       =   this.liste_status[key].color
                }
            }

            // Make Groupe
            for (let i = 0; i < this.route_import.clients.length; i++) {

                if(typeof journey_plan_util[this.route_import.clients[i].JPID]          ==  "undefined") {

                    journey_plan_util[this.route_import.clients[i].JPID]                    =   this.route_import.clients[i].JPID
                }    

                if(typeof city_util[this.route_import.clients[i].CityNo]                ==  "undefined") {

                    city_util[this.route_import.clients[i].CityNo]                          =   this.route_import.clients[i].CityNameE
                }

                if(typeof area_util[this.route_import.clients[i].AreaNo]                ==  "undefined") {

                    area_util[this.route_import.clients[i].AreaNo]                          =   this.route_import.clients[i].AreaNameE
                }

                if(typeof type_client_util[this.route_import.clients[i].CustomerType]   ==  "undefined") {

                    type_client_util[this.route_import.clients[i].CustomerType]             =   this.route_import.clients[i].CustomerType
                }

                if(typeof journee_util[this.route_import.clients[i].Journee]            ==  "undefined") {

                    journee_util[this.route_import.clients[i].Journee]                      =   this.route_import.clients[i].Journee
                }

                if(typeof owner_util[this.route_import.clients[i].owner_name]           ==  "undefined") {

                    owner_util[this.route_import.clients[i].owner_name]                     =   this.route_import.clients[i].owner_name
                }

                if(typeof status_util[this.route_import.clients[i].status]              ==  "undefined") {

                    status_util[this.route_import.clients[i].status]                        =   this.route_import.clients[i].status
                }

                // JPID
                journey_plan_existe         =   this.checkExistJPID(this.liste_journey_plan, this.route_import.clients[i].JPID) 

                if(!journey_plan_existe) {

                    this.liste_journey_plan[this.route_import.clients[i].JPID]                         =   {JPID  :   ""}
                    this.liste_journey_plan[this.route_import.clients[i].JPID].JPID                   =   this.route_import.clients[i].JPID

                    if(Object.keys(liste_journey_plan_colors).length    >   0) {

                        while(typeof liste_journey_plan_colors[this.$colors[journey_plan_count % this.$colors.length]]      !=  "undefined") {

                            journey_plan_count                                                          =   journey_plan_count +   1
                        }
                    }

                    this.liste_journey_plan[this.route_import.clients[i].JPID].color                   =   this.$colors[journey_plan_count % this.$colors.length]
                    journey_plan_count                                                                  =   journey_plan_count +   1
                }

                //

                // City
                city_existe             =   this.checkExistCityNo(this.cities, this.route_import.clients[i].CityNo) 

                if(!city_existe) {

                    this.cities[this.route_import.clients[i].CityNo]                            =   {CityNameComplete : "", CityNameE : "", CityNo : ""}
                    this.cities[this.route_import.clients[i].CityNo].CityNo                     =   this.route_import.clients[i].CityNo 
                    this.cities[this.route_import.clients[i].CityNo].CityNameE                  =   this.route_import.clients[i].CityNameE 
                    this.cities[this.route_import.clients[i].CityNo].CityNameComplete           =   this.route_import.clients[i].CityNo +   "- "    +   this.route_import.clients[i].CityNameE 

                    if(Object.keys(cities_colors).length    >   0) {

                        while(typeof cities_colors[this.$colors[city_count % this.$colors.length]]                   !=  "undefined") {

                            city_count                                                                  =   city_count +   1
                        }
                    }

                    this.cities[this.route_import.clients[i].CityNo].color                              =   this.$colors[city_count % this.$colors.length]
                    city_count                                                                          =   city_count +   1
                }

                //

                // Area
                area_existe                 =   this.checkExistAreaNo(this.areas, this.route_import.clients[i].AreaNo) 

                if(!area_existe) {

                    this.areas[this.route_import.clients[i].AreaNo]                                         =   {AreaNameComplete : "", AreaNameE : "", AreaNo : ""}
                    this.areas[this.route_import.clients[i].AreaNo].AreaNo                                  =   this.route_import.clients[i].AreaNo 
                    this.areas[this.route_import.clients[i].AreaNo].AreaNameE                               =   this.route_import.clients[i].AreaNameE 
                    this.areas[this.route_import.clients[i].AreaNo].AreaNameComplete                        =   this.route_import.clients[i].AreaNo +   "- " +   this.route_import.clients[i].AreaNameE 

                    if(Object.keys(areas_colors).length    >   0) {

                        while(typeof areas_colors[this.$colors[area_count % this.$colors.length]]                           !=  "undefined") {

                            area_count                                                                      =   area_count +   1
                        }
                    }

                    this.areas[this.route_import.clients[i].AreaNo].color                                   =   this.$colors[area_count % this.$colors.length]
                    area_count                                                                              =   area_count +   1
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

            for (const [key, value] of Object.entries(this.cities)) {

                if(typeof city_util[key]   ==  "undefined") {

                    delete this.cities[key]
                }
            }

            for (const [key, value] of Object.entries(this.areas)) {

                if(typeof area_util[key]   ==  "undefined") {

                    delete this.areas[key]
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

            // Sort JPID
            this.liste_journey_plan                 =   this.sortFilterJPID()
            this.route_import.liste_journey_plan    =   this.liste_journey_plan

            // Sort CityNo
            this.cities                             =   this.sortFilterCityNo()
            this.route_import.cities                =   this.cities

            // Sort AreaNo
            this.areas                              =   this.sortFilterAreaNo()
            this.route_import.areas                 =   this.areas

            // Sort CustomerType
            this.liste_type_client                  =   this.sortFilterCustomerType()
            this.route_import.liste_type_client     =   this.liste_type_client

            // Sort Journee
            this.liste_journee                      =   this.sortFilterJournee()
            this.route_import.liste_journee         =   this.liste_journee

            // Sort Owner
            this.owners                             =   this.sortFilterOwner()
            this.route_import.owners                =   this.owners

            // Sort Status
            this.liste_status                       =   this.sortFilterStatus()
            this.route_import.liste_status          =   this.liste_status

            //

            this.setListeJourneyPlanAction(this.liste_journey_plan)
            this.setListeTypeClientAction(this.liste_type_client)
            this.setListeJourneeAction(this.liste_journee)
            // this.setOwnersAction(this.owners)
        },

        // 

        checkExistJPID(liste_journey_plan, JPID) {

            for (const [key, value] of Object.entries(liste_journey_plan)) {

                if(key  ==  JPID) {

                    return true
                }
            }

            return false
        },

        checkExistCityNo(cities, CityNo) {

            for (const [key, value] of Object.entries(cities)) {
            
                if(key  ==  CityNo) {

                    return true
                }
            }

            return false
        },

        checkExistAreaNo(areas, AreaNo) {

            for (const [key, value] of Object.entries(areas)) {
            
                if(key  ==  AreaNo) {

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

                // Prepare Clients
                this.prepareClients()

                // reAffiche Clients
                await this.reAfficheClients()

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

                // Extract JPID, Areas, City
                this.extractMetaData()

                // Prepare Clients
                this.prepareClients()

                // reAffiche Clients
                await this.reAfficheClients()

                // reAffiche Markers
                this.setRouteMarkers()

                // Hide Loading Page
                this.$hideLoadingPage()

            }, 55);
        },

        reAfficherClientsAndMarkersByColor(column_name) {

            // JPID
            if(this.column_group    ==  1) {

                this.journey_plan_filter_value      =   [column_name]
            }

            // City
            if(this.column_group    ==  2) {

                let CityNo                      =   this.getCityNo(column_name)
                this.city_filter_value          =   [CityNo]
            }

            // Area
            if(this.column_group    ==  3) {

                let AreaNo                          =   this.getAreaNo(column_name)
                this.area_filter_value              =   [AreaNo]
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

        getCityNo(CityNameE) {

            for (const [key, value] of Object.entries(this.cities)) {

                if(this.cities[key].CityNameE    ==  CityNameE) {

                    return key
                }
            }
        },

        getAreaNo(AreaNameE) {

            for (const [key, value] of Object.entries(this.areas)) {

                if(this.areas[key].AreaNameE            ==  AreaNameE) {

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

                        if(this.route_import.clients[j].JPID   ==  i) {

                            this.clients_group[i].clients.push(this.route_import.clients[j])
                        }
                    }
                }
            }

            if(this.column_group    ==  2) {

                for (const [i, value] of Object.entries(this.route_import.cities)) {

                    this.clients_group[i]                                                       =   {column_name : this.route_import.cities[i].CityNameE, clients : [], color : this.route_import.cities[i].color}
                
                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].CityNo  ==  i) {

                            this.clients_group[i].clients.push(this.route_import.clients[j])
                        }
                    }
                }
            }

            if(this.column_group    ==  3) {

                for (const [i, value] of Object.entries(this.route_import.areas)) {

                    this.clients_group[i]                                                       =   {column_name : this.route_import.areas[i].AreaNameE, clients : [], color : this.route_import.areas[i].color}

                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].AreaNo  ==  i) {

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

                        if (this.journey_plan_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].JPID)         ==  -1) {

                            // splice
                            this.clients_markers_affiche[key].clients.splice(i, 1)
                            splice  =   true
                        }
                    }

                    if(splice   ==  false) {

                        if(this.city_filter_value.length    >   0) {

                            if (this.city_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].CityNo.toString())  ==  -1) {

                                // splice
                                this.clients_markers_affiche[key].clients.splice(i, 1)
                                splice  =   true
                            }
                        }

                        if(splice   ==  false) {

                            if(this.area_filter_value.length    >   0) {

                                if (this.area_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].AreaNo.toString())      ==  -1) {

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

        async showJPIDBDTerritories() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("liste_journey_plan", JSON.stringify(this.journey_plan_filter_value)) 

            const res   = await this.$callApi('post'    ,   '/route_import/'+this.route_import.id+'/journey_plan/util'   ,   formData)      
            console.log(res)

            if(res.status===200){

                // Show BD Territories
                this.$map.$showJPIDBDTerritories(res.data)

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

            let i = 0

            // Set Markers
            for (const [key, value] of Object.entries(this.clients_markers_affiche)) {

                this.$map.$setRouteMarkers(this.clients_markers_affiche[key].clients, i, this.clients_markers_affiche[key].color)

                i   =   i + 1
            }
        },

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
            new_client.CustomerNo       =   client.CustomerNo

            new_client.CustomerNameE    =   client.CustomerNameE
            new_client.CustomerNameA    =   client.CustomerNameA
            new_client.Tel              =   client.Tel

            new_client.Address          =   client.Address
            new_client.CityNo           =   client.CityNo      
            new_client.CityNameE        =   client.CityNameE   
            new_client.AreaNo           =   client.AreaNo           
            new_client.AreaNameE        =   client.AreaNameE       

            new_client.Latitude         =   client.Latitude         
            new_client.Longitude        =   client.Longitude        

            new_client.CustomerType     =   client.CustomerType     

            new_client.JPID             =   client.JPID            
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
                
                if(this.route_import.clients[i].CustomerNo  ==  client.CustomerNo) {

                    // Update Client

                    this.route_import.clients[i].CustomerNo     =   client.CustomerNo

                    this.route_import.clients[i].CustomerNameE  =   client.CustomerNameE
                    this.route_import.clients[i].CustomerNameA  =   client.CustomerNameA
                    this.route_import.clients[i].Tel            =   client.Tel

                    this.route_import.clients[i].Address        =   client.Address
                    this.route_import.clients[i].CityNo         =   client.CityNo      
                    this.route_import.clients[i].CityNameE      =   client.CityNameE   
                    this.route_import.clients[i].AreaNo         =   client.AreaNo           
                    this.route_import.clients[i].AreaNameE      =   client.AreaNameE       

                    this.route_import.clients[i].Latitude       =   client.Latitude         
                    this.route_import.clients[i].Longitude      =   client.Longitude        

                    this.route_import.clients[i].CustomerType   =   client.CustomerType     

                    this.route_import.clients[i].JPID           =   client.JPID            
                    this.route_import.clients[i].Journee        =   client.Journee        

                    this.route_import.clients[i].status                         =   client.status            
                    this.route_import.clients[i].nonvalidated_details           =   client.nonvalidated_details        

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
                
                if(this.route_import.clients[i].CustomerNo  ==  client.CustomerNo) {

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
                
                if(this.route_import.clients[i].CustomerNo  ==  client.CustomerNo) {

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
                    
                    if(this.route_import.clients[j].CustomerNo  ==  clients[i].CustomerNo) {

                        // Update Client
                        if(clients[i].CityNo    !=  "") {

                            this.route_import.clients[j].CityNo     =   clients[i].CityNo      
                            this.route_import.clients[j].CityNameE  =   clients[i].CityNameE   
                        }

                        if(clients[i].AreaNo        !=  "") {

                            this.route_import.clients[j].AreaNo         =   clients[i].AreaNo           
                            this.route_import.clients[j].AreaNameE      =   clients[i].AreaNameE       
                        }

                        if(clients[i].JPID         !=  "") {

                            this.route_import.clients[j].JPID          =   clients[i].JPID     
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

        async showDevide() {

            await this.$refs.modalDevide.getClients()
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
            console.log(232323)

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
        },

        //

        setKMLCities(kml_cities) {

            this.kml_cities   =   []

            for (let i = 0; i < kml_cities.length; i++) {

                this.kml_cities.push({ value : kml_cities[i].CityNo , label : kml_cities[i].CityNo + "- " + kml_cities[i].CityNameE})
            }
        },

        setKMLLayers() {

            setTimeout(() => {

                this.$map.$setKMLLayers(this.kml_layers)
            }, 555);
        },

        // SORT FILTERS

        sortFilterJPID() {

            let sortedArray                 =   Object.values(this.liste_journey_plan);

            /*

            sortedArray.sort((a, b)             =>  {

                // Use a regular expression to match numbers at the end of the string
                let regex = /\d+$/;
                
                // Use the match method to find the matched numbers
                let a_last_number   =   a.JPID.match(regex);
                let b_last_number   =   b.JPID.match(regex);

                // Check if there is a match and return the result, or return null if there's no match
                if(a_last_number    ==  null) {

                    if(b_last_number    ==  null) {

                        return a.JPID.localeCompare(b.JPID)
                    }

                    else {

                        return -1
                    }
                }

                else {

                    if(b_last_number    ==  null) {

                        return 1
                    }

                    else {

                        return a_last_number - b_last_number
                    }
                }
            })

            let sortedObject                =   sortedArray.reduce((acc, journey_plan, index) => {

            */

            sortedArray.sort((a, b)         =>  b.JPID - a.JPID);
                
            let sortedObject                    =   sortedArray.reduce((acc, journey_plan, index) => {
                
                acc[journey_plan.JPID]         =   journey_plan;
                return acc;
            }, {});

            return sortedObject
        },

        sortFilterCityNo() {

            let sortedArray                 =   Object.values(this.cities);

            sortedArray.sort((a, b)             =>  {

                // Use a regular expression to match numbers at the end of the string
                let regex = /\d+$/;
                
                // Use the match method to find the matched numbers
                let a_last_number   =   a.CityNo.match(regex);
                let b_last_number   =   b.CityNo.match(regex);

                // Check if there is a match and return the result, or return null if there's no match
                if(a_last_number    ==  null) {

                    return -1
                }

                else {

                    if(b_last_number    ==  null) {

                        return 1
                    }

                    else {

                        return a_last_number - b_last_number
                    }
                }
            })

            let sortedObject                =   sortedArray.reduce((acc, city, index) => {
                
                acc[city.CityNo]            =   city;
                return acc;
            }, {});

            return sortedObject
        },

        sortFilterAreaNo() {

            let sortedArray                 =   Object.values(this.areas);

            /*

            sortedArray.sort((a, b)             =>  {

                // Use a regular expression to match numbers at the end of the string
                let regex = /\d+$/;
                
                // Use the match method to find the matched numbers
                let a_last_number   =   a.AreaNo.match(regex);
                let b_last_number   =   b.AreaNo.match(regex);

                // Check if there is a match and return the result, or return null if there's no match
                if(a_last_number    ==  null) {

                    return -1
                }

                else {

                    if(b_last_number    ==  null) {

                        return 1
                    }

                    else {

                        return a_last_number - b_last_number
                    }
                }
            })

            */

            let sortedObject                =   sortedArray.reduce((acc, area, index) => {

                acc[area.AreaNo]                    =   area;
                return acc;
            }, {});

            return sortedObject
        },

        sortFilterCustomerType() {

            let sortedArray                 =   Object.values(this.liste_type_client);
            
            sortedArray.sort((a, b)     =>  a.CustomerType.localeCompare(b.CustomerType));
            
            let sortedObject                =   sortedArray.reduce((acc, type_client, index) => {

                acc[type_client.CustomerType]       =   type_client;
                return acc;
            }, {});

            return sortedObject
        },

        sortFilterJournee() {

            let sortedArray                 =   Object.values(this.liste_journee);

            sortedArray.sort((a, b)             =>  {

                // Use a regular expression to match numbers at the end of the string
                let regex = /\d+$/;
                
                // Use the match method to find the matched numbers
                let a_last_number   =   a.Journee.match(regex);
                let b_last_number   =   b.Journee.match(regex);

                // Check if there is a match and return the result, or return null if there's no match
                if(a_last_number    ==  null) {

                    if(b_last_number    ==  null) {

                        return a.Journee.localeCompare(b.Journee)
                    }

                    else {

                        return -1
                    }
                }

                else {

                    if(b_last_number    ==  null) {

                        return 1
                    }

                    else {

                        return a_last_number - b_last_number
                    }
                }
            })

            let sortedObject                =   sortedArray.reduce((acc, journee, index) => {

                acc[journee.Journee]                =   journee;
                return acc;
            }, {});

            return sortedObject
        },

        sortFilterOwner() {

            let sortedArray                 =   Object.values(this.owners);
            
            sortedArray.sort((a, b)         =>  {

                a.owner_name.localeCompare(b.owner_name)
            });
            
            let sortedObject                =   sortedArray.reduce((acc, owner, index) => {

                acc[owner.owner_name]       =   owner;
                return acc;
            }, {});

            return sortedObject
        },

        sortFilterStatus() {

            let sortedArray                 =   Object.values(this.liste_status);
            
            sortedArray.sort((a, b)     =>  a.status.localeCompare(b.status));
            
            let sortedObject                =   sortedArray.reduce((acc, status, index) => {

                acc[status.status]   =   status;
                return acc;
            }, {});

            return sortedObject
        }
    },

    watch: {

        getAddClient(newValue, oldValue) {

            if(newValue != null) {
                
                // Version Customer
                this.$router.push('/route_import/'+this.$route.params.id_route_import+'/customers/add/'+newValue.lat+'/'+newValue.lng)

                // Version Client
                // this.$router.push('/route_import/'+this.$route.params.id_route_import+'/clients/add/'+newValue.lat+'/'+newValue.lng)
            }
        },

        getUpdateClient(newValue, oldValue) {

            if(newValue != null) {
                
                // Version Customer
                // this.$router.push('/route_import/'+this.$route.params.id_route_import+'/customers/'+newValue.CustomerNo+'/edit')

                // Version Client
                this.$router.push('/route_import/'+this.$route.params.id_route_import+'/customers/'+newValue.CustomerNo+'/choice')
            }
        },

        //

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
