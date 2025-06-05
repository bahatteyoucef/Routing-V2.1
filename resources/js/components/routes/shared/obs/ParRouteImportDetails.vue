<template>

    <div id="route_import_clients_component">
        <div id="content_route">
            <div id="map_parent_div" class="d-inline-block">
                <div id="show_hide_map_filters_button_div" class="text-right">
                    <i v-show="show_map_filters_button" class="mdi mdi-arrow-left-bold-circle primary_bg map_primary_icon animate__animated"   id="show_map_filters_button"    role="button"   @click="showMapFilters()"></i>
                    <i v-show="hide_map_filters_button" class="mdi mdi-arrow-right-bold-circle primary_bg map_primary_icon animate__animated"  id="hide_map_filters_button"    role="button"   @click="hideMapFilters()"></i>
                </div>

                <div id="show_datatable_route_import_obs_clients_div">
                    <i v-show="show_datatable_route_import_obs_clients_button"  class="mdi mdi-arrow-up-bold-circle primary_bg map_primary_icon animate__animated"      id="show_datatable_route_import_obs_clients_button"    role="button"   @click="showDatatableClients()"></i>
                </div>

                <div id="hide_datatable_route_import_obs_clients_div">
                    <i v-show="hide_datatable_route_import_obs_clients_button"  class="mdi mdi-arrow-down-bold-circle primary_bg map_primary_icon animate__animated"    id="hide_datatable_route_import_obs_clients_button"    role="button"   @click="hideDatatableClients()"></i>
                </div>

                <div id="map_top_middle_options_div">

                    <div class="row">

                        <!-- Map Info -->
                        <div v-if="(($isRole('Super Admin'))||($isRole('BU Manager'))||($isRole('BackOffice'))||($isRole('Viewer')))" class="col p-0">
                            <div class="map_top_infos_div">
                                <table class="table table-borderless">

                                    <Popper click placement="bottom">
                                        <i role="button"    class="mdi mdi-information primary_bg map_primary_small_icon p-0" id="show_hide_route_import_info_button"></i>

                                        <template #content>
                                            <table class="table table-borderless scrollbar scrollbar-deep-blue mb-0">
                                                <tr v-for="groupe in clients_markers_affiche" :key="groupe">
                                                    <th class="p-1 col-sm-7"><span @click="filterFromMapInfo(groupe.column_name)" role="button">{{ groupe.column_name }} : </span></th>
                                                    <td class="p-1 col-sm-3"><span>{{ groupe.clients.length }} Clients </span></td>
                                                    <td class="p-1 col-sm-1">
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
                        <div v-if="(($isRole('Super Admin'))||($isRole('BU Manager'))||($isRole('BackOffice'))||($isRole('Viewer')))" class="col p-0 ml-1 d-flex align-items-center">
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

                <div id="tableau_data">
                    <div v-show="show_datatable_route_import_obs_clients_parent" id="datatable_route_import_obs_clients_parent" class="scrollbar scrollbar-deep-blue bg-white p-1 animate__animated mt-0 mb-0">
                        <div id="route_import_obs_clients_container" class="table-container mt-1 mb-1">
                            <table class="table table-hover table-bordered route_import_obs_clients mt-0 mb-0 w-100" id="route_import_obs_clients">
                                <thead>
                                    <tr>
                                        <th role="button">#</th>
                                        <th v-for="route_import_obs_client_column in route_import_obs_clients_columns" :key="route_import_obs_client_column" role="button">{{ route_import_obs_client_column.title }}</th>
                                    </tr>

                                    <tr id="route_import_obs_clients_filters" class="route_import_obs_clients_filters">
                                        <th></th>

                                        <th v-for="route_import_obs_client_column in route_import_obs_clients_columns" :key="route_import_obs_client_column">
                                            <div class="filter_group" :data-column="route_import_obs_client_column.title">
                                                <select class="filter_type form-select-sm w-100 mb-2">
                                                    <option value="contains">contains</option>
                                                    <option value="not_contains">not contains</option>
                                                    <option value="exact">exact</option>
                                                    <option value="starts_with">starts with</option>
                                                    <option value="ends_with">ends with</option>
                                                </select>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm filter_input"
                                                    :placeholder="route_import_obs_client_column.title"
                                                />
                                            </div>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!--  -->

                <div id="map"></div>
            </div>

            <!--  -->

            <div v-show="show_map_operations_filters_div" id="map_operations_filters_div" class="d-inline-block animate__animated p-1 pt-3 pb-5">
                <div id="map_operation_buttons" v-if="(($isRole('Super Admin'))||($isRole('BU Manager'))||($isRole('BackOffice')))">
                    <h5 class="fw-bold text-center primary_color">Operations</h5>

                    <button class="btn btn-primary w-100 m-0 mt-1"                                                                  @click="getData()">Refresh</button>
                    <button class="btn btn-primary w-100 m-0 mt-1"                                                                  @click="focuseMarkers()">Focus</button>
                    <button class="btn btn-primary w-100 m-0 mt-1"  data-bs-toggle="modal" :data-bs-target="'#ModalResume'"         @click="showResume()">Resume</button>
                    <button class="btn btn-primary w-100 m-0 mt-1"  data-bs-toggle="modal" :data-bs-target="'#ModalUpdateMap'"      >Update</button>
                    <button class="btn btn-primary w-100 m-0 mt-1"  data-bs-toggle="modal" :data-bs-target="'#ModalValidateMap'"    @click="getDoubles()">Validate</button>
                    <button class="btn btn-primary w-100 m-0 mt-1"                                                                  @click="showTerritories()">Auto Territories</button>
                    <button class="btn btn-primary w-100 m-0 mt-1"                                                                  @click="showJPlanBDTerritories()">JPlan Territories</button>
                    <button class="btn btn-primary w-100 m-0 mt-1"                                                                  @click="showJourneeBDTerritories()">Journee Territories</button>
                    <button class="btn btn-primary w-100 m-0 mt-1"                                                                  @click="showUserBDTerritories()">User Territories</button>
                </div>

                <!--  -->

                <div id="map_operation_buttons" v-if="($isRole('Viewer'))">
                    <h5 class="fw-bold text-center primary_color">Operations</h5>
                    <hr />
                    <button class="btn btn-primary w-100 m-0 mt-1"                                                                  @click="focuseMarkers()">Focus</button>
                    <button class="btn btn-primary w-100 m-0 mt-1"  data-bs-toggle="modal" :data-bs-target="'#ModalResume'"         @click="showResume()">Resume</button>
                </div>

                <!--  -->

                <hr />

                <!--  -->

                <div id="map_filter_inputs"> 
                    <h5 class="fw-bold text-center primary_color">Filters</h5>

                    <select class="form-select w-100 m-0 mt-1"                                                          @change="reAfficherClientsAndMarkers('data_ready')"  v-model="column_group">
                        <option value="1">JPlan</option>
                        <option value="2">DistrictNameE</option>
                        <option value="3">CityNameE</option>
                        <option value="4">CustomerType</option>
                        <option value="5">Journee</option>
                        <option value="6">Owner</option>
                        <option value="7">Status</option>
                    </select>

                    <!-- Date Start -->
                    <div class="mt-1">
                        <input type="date"  class="form-control"    v-model="date_start"    @change="reAfficherClientsAndMarkers('data_ready')" />
                    </div>
                    <!--            -->

                    <!-- Date End   -->
                    <div class="mt-1">
                        <input type="date"  class="form-control"    v-model="date_end"      @change="reAfficherClientsAndMarkers('data_ready')" />
                    </div>
                    <!--            -->

                    <!-- Journey Plan   -->
                    <Multiselect
                        v-model     =   "journey_plan_filter_value"
                        :options    =   "journeyPlanOptions"
                        mode        =   "tags"
                        placeholder =   "Filter By JPlan"
                        class       =   "mt-1"

                        :close-on-select    =   "false"
                        :searchable         =   "true"
                        :create-option      =   "true"

                        @change             =   "reAfficherClientsAndMarkers('data_ready')"
                    />
                    <!--                -->

                    <!-- District       -->
                    <Multiselect
                        v-model     =   "district_filter_value"
                        :options    =   "districtOptions"
                        mode        =   "tags"
                        placeholder =   "Filter By DistrictNameE"
                        class       =   "mt-1"

                        :close-on-select    =   "false"
                        :searchable         =   "true"
                        :create-option      =   "true"

                        @change             =   "reAfficherClientsAndMarkers('data_ready')"
                    />
                    <!--                -->

                    <!-- City           -->
                    <Multiselect
                        v-model     =   "city_filter_value"
                        :options    =   "cityOptions"
                        mode        =   "tags"
                        placeholder =   "Filter By CityNameE"
                        class       =   "mt-1"

                        :close-on-select    =   "false"
                        :searchable         =   "true"
                        :create-option      =   "true"

                        @change             =   "reAfficherClientsAndMarkers('data_ready')"
                    />
                    <!--                -->

                    <!-- CustomerType   -->
                    <Multiselect
                        v-model     =   "type_client_filter_value"
                        :options    =   "customerTypeOptions"
                        mode        =   "tags"
                        placeholder =   "Filter By CustomerType"
                        class       =   "mt-1"

                        :close-on-select    =   "false"
                        :searchable         =   "true"
                        :create-option      =   "true"

                        @change             =   "reAfficherClientsAndMarkers('data_ready')"
                    />

                    <!-- Journee        -->
                    <Multiselect
                        v-model     =   "journee_filter_value"
                        :options    =   "journeeOptions"
                        mode        =   "tags"
                        placeholder =   "Filter By Journee"
                        class       =   "mt-1"

                        :close-on-select    =   "false"
                        :searchable         =   "true"
                        :create-option      =   "true"

                        @change             =   "reAfficherClientsAndMarkers('data_ready')"
                    />
                    <!--                -->

                    <!-- Owners        -->
                    <Multiselect
                        v-model     =   "owner_filter_value"
                        :options    =   "ownerOptions"
                        mode        =   "tags"
                        placeholder =   "Filter By Owner"
                        class       =   "mt-1"

                        :close-on-select    =   "false"
                        :searchable         =   "true"
                        :create-option      =   "true"

                        @change             =   "reAfficherClientsAndMarkers('data_ready')"
                    />
                    <!--                -->

                    <!-- Status        -->
                    <Multiselect
                        v-model     =   "status_filter_value"
                        :options    =   "statusOptions"
                        mode        =   "tags"
                        placeholder =   "Filter By Status"
                        class       =   "mt-1"

                        :close-on-select    =   "false"
                        :searchable         =   "true"
                        :create-option      =   "true"

                        @change             =   "reAfficherClientsAndMarkers('data_ready')"
                    />
                    <!--                -->

                    <!-- Status        -->
                    <Multiselect
                        v-model     =   "kml_layers"
                        :options    =   "kml_willayas"
                        mode        =   "tags"
                        placeholder =   "Select Willaya KML"
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

    <!--                                -->

    <!-- Modal Add                      -->
    <ModalClientAdd                                                 ref="ModalClientAdd"            :id_route_import="id_route_import"                                                              >   </ModalClientAdd>

    <!-- Modal Update                   -->
    <ModalClientUpdate                                              ref="ModalClientUpdate"         :id_route_import="id_route_import"     :update_type="'normal_update'"      :mode="'permanent'"  >   </ModalClientUpdate>

    <!-- Modal Change Route             -->
    <ModalClientsChangeRoute                                        ref="ModalClientsChangeRoute"                                                                                           >   </ModalClientsChangeRoute>

    <!-- Modal Decoupe By Journee       -->
    <ModalResume                                                    ref="ModalResume"               :mode="'permanent'"                                                                     >   </ModalResume>

    <!-- Modal Add New Journey Plan     -->
    <ModalAddJourneyPlan                                            ref="ModalAddJourneyPlan"                                                                                               >   </ModalAddJourneyPlan>

    <!-- Modal Add New Journey Plan     -->
    <ModalUpdateJourneyPlan                                         ref="ModalUpdateJourneyPlan"                                                                                            >   </ModalUpdateJourneyPlan>

    <!-- Modal Update Map               -->
    <ModalUpdateMap                                                 ref="ModalUpdateMap"            :id_route_import="id_route_import"                                                      >   </ModalUpdateMap>

    <!-- Modal Update Map               -->
    <ModalValidateMap                                               ref="ModalValidateMap"          :id_route_import="id_route_import"                                                      >   </ModalValidateMap>

</template>

<script>

import Multiselect                  from    '@vueform/multiselect'

import ModalClientAdd               from    "@/components/clients/shared/ModalClientAdd.vue"
import ModalClientUpdate            from    "@/components/clients/shared/ModalClientUpdate.vue"
import ModalClientsChangeRoute      from    "@/components/clients/shared/ModalClientsChangeRoute.vue"

import ModalAddJourneyPlan          from    "@/components/territoires/ModalAddJourneyPlan.vue"
import ModalUpdateJourneyPlan       from    "@/components/territoires/ModalUpdateJourneyPlan.vue"

import ModalUpdateMap               from    "../crud/ModalUpdateMap.vue"

import ModalValidateMap             from    "../operations/ModalValidateMap.vue"
import ModalResume                  from    "../operations/ModalResume.vue"

import {mapGetters, mapActions}     from    "vuex"

import DatatableHelper              from    "@/services/DatatableHelper"

import Map                          from    '@/services/map'

export default {
    
    data() {
        return {

            map_instance                            :   null    ,

            //

            route_import_obs_clients_columns        :   [
                                                            { data: "id"                    , title: "Id"                   },
                                                            { data: "CustomerCode"          , title: "CustomerCode"         },
                                                            { data: "CustomerNameE"         , title: "CustomerNameE"        },
                                                            { data: "CustomerNameA"         , title: "CustomerNameA"        },
                                                            { data: "DistrictNo"            , title: "DistrictNo"           },
                                                            { data: "DistrictNameE"         , title: "DistrictNameE"        },
                                                            { data: "CityNo"                , title: "CityNo"               },
                                                            { data: "CityNameE"             , title: "CityNameE"            },
                                                            { data: "CustomerBarCode_image" , title: "CustomerBarCode Image"},
                                                            { data: "in_store_image"        , title: "In Store Image"       },
                                                            { data: "facade_image"          , title: "Facade Image"         },
                                                            { data: "Address"               , title: "Address"              },
                                                            { data: "Neighborhood"          , title: "Neighborhood"         },
                                                            { data: "Landmark"              , title: "Landmark"             },
                                                            { data: "Latitude"              , title: "Latitude"             },
                                                            { data: "Longitude"             , title: "Longitude"            },
                                                            { data: "Tel"                   , title: "Tel"                  },
                                                            { data: "CustomerType"          , title: "CustomerType"         },
                                                            { data: "JPlan"                 , title: "JPlan"                },
                                                            { data: "Journee"               , title: "Journee"              },
                                                            { data: "comment"               , title: "Comment"              },
                                                            { data: "BrandAvailability"     , title: "BrandAvailability"    },
                                                            { data: "BrandSourcePurchase"   , title: "BrandSourcePurchase"  },
                                                            { data: "start_adding_time"     , title: "Start Adding Time"    },
                                                            { data: "adding_duration"       , title: "Adding Duration"      },
                                                            { data: "created_at"            , title: "Created At"           },
                                                            { data: "status"                , title: "Status"               },
                                                            { data: "owner"                 , title: "Owner"                }
                                                        ],

            datatable_route_import_obs_clients              :   null    ,
            datatable_route_import_obs_clients_instance     :   null    ,

            //  //  //  //  //
            //  //  //  //  //
            //  //  //  //  //

            column_group                :   1                           ,

            id_route_import             :   ''                          ,
            route_import                :   null                        ,

            // Dates

            date_start                  :   ""                          ,
            date_end                    :   ""                          ,

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

            // latlngs selected Polygon
            latlngs                             :   null                ,
            journey_plan                        :   null                ,

            client : {

                lat :   0,
                lng :   0
            },

            //

            kml_willayas                :   [],
            kml_layers                  :   null,

            //

            show_map_filters_button             :   true,
            hide_map_filters_button             :   false,
            show_map_operations_filters_div     :   false,

            //

            show_datatable_route_import_obs_clients_button  :   true,
            hide_datatable_route_import_obs_clients_button  :   false,
            show_datatable_route_import_obs_clients_parent  :   false,

            //

            journeyPlanOptions                              :   [],
            districtOptions                                 :   [],
            cityOptions                                     :   [],
            customerTypeOptions                             :   [],
            journeeOptions                                  :   [],
            ownerOptions                                    :   [],
            statusOptions                                   :   [],

            //

            marker_cluster_mode         :   "cluster"       ,

            //

            selected_row                :   null            ,
            selected_row_id             :   null
        }
    },

    components: {

        Multiselect,

        ModalClientAdd              ,
        ModalClientUpdate           ,
        ModalClientsChangeRoute     ,
        ModalResume                 ,

        ModalAddJourneyPlan         ,
        ModalUpdateJourneyPlan      ,

        ModalUpdateMap              ,
        ModalValidateMap         
    },

    computed: {

        ...mapGetters({

            getAddClient            : 'client/getAddClient'                     ,
            getUpdateClient         : 'client/getUpdateClient'                  ,
            getClientsChangeRoute   : 'client/getClientsChangeRoute'            ,

            getAddJourneyPlan       : 'journey_plan/getAddJourneyPlan'          ,

            //

            getUser                 :   'authentification/getUser'              
        })

    },

    async mounted() {

        //
        this.datatable_route_import_obs_clients_instance    =   new DatatableHelper()
        this.map_instance                                   =   new Map()

        // add Map
        this.addMap()

        // Set Simple Values
        this.setValues()

        // Show Clusters
        await this.map_instance.$switchMarkerClusterMode("cluster")

        // Get Data (clients + filter values)
        await this.getData()

        // CRUD Events

        this.emitter.on('reSetAdd'                          , async (client)    =>  {
            
            await this.addClientJSON(client)

            //
            await this.reAfficherClientsAndMarkers("add", [client]);

            //
            this.removeDrawings()
        })

        this.emitter.on('reSetUpdate'                       , async (client)    =>  {

            this.updateClientJSON(client)

            //
            await this.reAfficherClientsAndMarkers("update", [client]);

            //
            this.removeDrawings()
        })

        this.emitter.on('reSetDelete'                       , async (client)    =>  {

            this.deleteClientJSON(client)

            //
            await this.reAfficherClientsAndMarkers("delete", [client]);

            //
            this.removeDrawings()
        })

        this.emitter.on('reSetChangeRoute'                  , async (clients)   =>  {

            this.changeRouteClientsJSON(clients)

            //
            await this.reAfficherClientsAndMarkers("change_route_update", clients);

            //
            this.removeDrawings()
        })

        this.emitter.on('reSetChangeRouteDelete'            , async (clients)   =>  {

            //
            this.changeRouteClientsJSONDelete(clients)

            //
            await this.reAfficherClientsAndMarkers("change_route_delete", clients);

            //
            this.removeDrawings()
        })

        this.emitter.on('reSetClientsDecoupeByJourneeMap'   , async (clients)   =>  {

            this.route_import.clients   =   clients

            //
            await this.reAfficherClientsAndMarkers("standard");
        })

        this.emitter.on('reSetClientsUpdateMap'             , async ()          =>  {
            await this.getData()
        })

        this.emitter.on('reSetJPlanBDTerritory'             , ()                =>  {
            this.showJPlanBDTerritories()
        })

        this.emitter.on('reSetJourneeBDTerritory'           , ()                =>  {
            this.showJourneeBDTerritories()
        })

        this.emitter.on('reSetUserBDTerritory'              , ()                =>  {
            this.showUserBDTerritories()
        })
    },

    unmounted() {

        this.emitter.off('reSetAdd')
        this.emitter.off('reSetUpdate')
        this.emitter.off('reSetDelete')
        this.emitter.off('reSetChangeRoute')
        this.emitter.off('reSetChangeRouteDelete')
        this.emitter.off('reSetClientsDecoupeByJourneeMap')
        this.emitter.off('reSetClientsUpdateMap')
        this.emitter.off('reSetJPlanBDTerritory')
        this.emitter.off('reSetJourneeBDTerritory')
        this.emitter.off('reSetUserBDTerritory')
    },

    methods : {

        // Set Simple Value

        setValues() {
            this.id_route_import    =   this.$route.params.id_route_import
        },

        // Map

        addMap() {

            this.map_instance.$createMap(this.$role(), "map")
        },

        // Get Data

        async getData() {

            // Show Loading Page
            this.$showLoadingPage()
                
            const res                   =   await this.$callApi("post"  ,   "/route/obs/route_import/"+this.id_route_import+"/details",   null)
            this.route_import           =   res.data.route_import

            // Set Clients
            this.route_import.clients   =   this.route_import.data

            // 
            this.setKMLWillayas(res.data.willayas)

            //
            await this.reAfficherClientsAndMarkers("initial")
        },

        // Extract Filters Data

        extractMetaData() {

            // --- 1) Define categories and palette ---
            const clients       =   this.route_import.clients;
            const palette       =   this.$colors
            const categories    =   [
                                        { listName: 'liste_journey_plan'    , prop: 'JPlan'                                                                                                     },
                                        { listName: 'districts'             , prop: 'DistrictNo', complete: c => `${c.DistrictNo}- ${c.DistrictNameE}`  , completeProp: 'DistrictNameComplete'  },
                                        { listName: 'cites'                 , prop: 'CityNo'    , complete: c => `${c.CityNo}- ${c.CityNameE}`          , completeProp: 'CityNameComplete'      },
                                        { listName: 'liste_type_client'     , prop: 'CustomerType'                                                                                              },
                                        { listName: 'liste_journee'         , prop: 'Journee'                                                                                                   },
                                        { listName: 'owners'                , prop: 'owner_name'                                                                                                },
                                        { listName: 'liste_status'          , prop: 'status'                                                                                                    },
                                    ];

            // Helper: assign next unused color for a category
            const assignColor   =   usedSet => {

                const color         =   palette[usedSet.size % palette.length];

                usedSet.add(color);

                return color;
            };

            // Helper: build/set metadata entry
            const makeEntry     =   (cat, client, usedColors) => {

                const entry         =   { [cat.prop]: client[cat.prop] };

                if (cat.complete) 
                    entry[cat.completeProp] = cat.complete(client);

                entry.color         = assignColor(usedColors);

                return entry;
            };

            // Pre‑populate seenKeys & usedColors if doing an incremental add/delete
            categories.forEach(cat => {

                const seen          =   new Set(), unique = [];

                // collect unique clients
                for (let c of clients) {

                    const key = c[cat.prop];

                    if (!seen.has(key)) {

                        seen.add(key);
                        unique.push(c);
                    }
                }

                // build list
                const used = new Set(), obj = {};

                for (let c of unique) {

                    obj[c[cat.prop]] = makeEntry(cat, c, used);
                }

                this[cat.listName] = obj;
            });

            // Cleanup stale entries (for safety) & sort via existing filters
            categories.forEach(cat => {

                const obj = this[cat.listName];

                Object.keys(obj).forEach(k => {

                    // drop any keys no longer in clients
                    if (!this.route_import.clients.some(c => c[cat.prop] === k)) 
                        delete obj[k];
                });
            });

            // Apply your sortFilter* methods
            this.liste_journey_plan   = this.sortFilterJPlan();
            this.districts            = this.sortFilterDistrictNo();
            this.cites                = this.sortFilterCityNo();
            this.liste_type_client    = this.sortFilterCustomerType();
            this.liste_journee        = this.sortFilterJournee();
            this.owners               = this.sortFilterOwner();
            this.liste_status         = this.sortFilterStatus();

            // Mirror back into route_import
            Object.assign(this.route_import, {
                liste_journey_plan: this.liste_journey_plan,
                districts:          this.districts,
                cites:              this.cites,
                liste_type_client:  this.liste_type_client,
                liste_journee:      this.liste_journee,
                owners:             this.owners,
                liste_status:       this.liste_status,
            });

            // 6) Build options arrays
            const buildOptions = (obj, valueKey, labelKey) =>
            Object.values(obj).map(item => ({
                value: item[valueKey],
                label: item[labelKey],
            }));

            //
            this.journeyPlanOptions   = buildOptions(this.liste_journey_plan,   'JPlan',           'JPlan');
            this.districtOptions      = buildOptions(this.districts,            'DistrictNo',      'DistrictNameComplete');
            this.cityOptions          = buildOptions(this.cites,                'CityNo',          'CityNameComplete');
            this.customerTypeOptions  = buildOptions(this.liste_type_client,    'CustomerType',    'CustomerType');
            this.journeeOptions       = buildOptions(this.liste_journee,        'Journee',         'Journee');
            this.ownerOptions         = buildOptions(this.owners,               'owner_name',      'owner_name');
            this.statusOptions        = buildOptions(this.liste_status,         'status',          'status');
        },

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

        sortFilterJPlan() {

            let sortedArray                 =   Object.values(this.liste_journey_plan);

            sortedArray.sort((a, b)             =>  {

                // Use a regular expression to match numbers at the end of the string
                let regex = /\d+$/;
                
                // Use the match method to find the matched numbers
                let a_last_number   =   a.JPlan.match(regex);
                let b_last_number   =   b.JPlan.match(regex);

                // Check if there is a match and return the result, or return null if there's no match
                if(a_last_number    ==  null) {

                    if(b_last_number    ==  null) {

                        return a.JPlan.localeCompare(b.JPlan)
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
                
                acc[journey_plan.JPlan]     =   journey_plan;
                return acc;
            }, {});

            return sortedObject
        },

        sortFilterDistrictNo() {

            let sortedArray                 =   Object.values(this.districts);

            sortedArray.sort((a, b)             =>  {

                // Use a regular expression to match numbers at the end of the string
                let regex = /\d+$/;
                
                // Use the match method to find the matched numbers
                let a_last_number   =   a.DistrictNo.match(regex);
                let b_last_number   =   b.DistrictNo.match(regex);

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

            let sortedObject                =   sortedArray.reduce((acc, district, index) => {
                
                acc[district.DistrictNo]            =   district;
                return acc;
            }, {});

            return sortedObject
        },

        sortFilterCityNo() {

            let sortedArray                 =   Object.values(this.cites);

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

            let sortedObject                =   sortedArray.reduce((acc, cite, index) => {

                acc[cite.CityNo]                    =   cite;
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
            
            sortedArray.sort((a, b)         =>  a.owner_name.localeCompare(b.owner_name));
            
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
        },

        // Prepare Markers and Table Data

        async reAfficherClientsAndMarkers(mode, clients = []) {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(async () => {

                // data_ready                   => wher filters 
                // switch_marker_cluster_mode   => switch cluster/marker
                // initial                      => wher getData()
                // add                          => wher add clients         (clients passed)
                // update                       => when update clients      (clients passed)
                // delete                       => when delete clients      (clients passed)
                // standard                     => when resume

                // Extract JPlan, Cites, District
                if(mode != "data_ready") this.extractMetaData()

                // reAffiche Clients
                this.clients_markers_affiche    =   this.reAfficheClientsMarkers(this.route_import.clients)

                // Table Data
                this.reAfficheClientsTable()

                // Datatable
                await this.setDataTable()

                // reAffiche Markers
                if((mode == "data_ready")||(mode == "initial")||(mode == "standard")||(mode == "switch_marker_cluster_mode")) {

                    this.setRouteMarkers(mode, this.clients_markers_affiche)
                }

                //
                else {

                    if((mode == "add")||(mode == "update")||(mode == "change_route_update")||(mode == "delete")||(mode == "change_route_delete")) {

                        if(mode == "add")       {

                            // reAffiche Clients
                            let clients_markers_affiche    =   this.reAfficheClientsMarkers(clients)
                            this.addMarkers(clients_markers_affiche)
                        }

                        if(mode == "update")       {

                            // reAffiche Clients
                            let clients_markers_affiche    =   this.reAfficheClientsMarkers(clients)
                            await this.updateMarkers(clients_markers_affiche, clients)
                        }

                        if(mode == "change_route_update")   {

                            // reAffiche Clients
                            let clients_markers_affiche    =   this.reAfficheClientsMarkers(clients)
                            await this.updateMarkers(clients_markers_affiche, clients)
                        }

                        if(mode == "delete")       {

                            // reAffiche Clients
                            await this.deleteMarkers(clients)
                        }

                        if(mode == "change_route_delete")   {

                            // reAffiche Clients
                            await this.deleteMarkers(clients)
                        }

                        //
                        this.focuseMarkers()
                    }
                }

                // Hide Loading Page
                this.$hideLoadingPage()
            }, 0)
        },

        // Get Map Info Key ("Map Info")

        filterFromMapInfo(column_name) {

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

        // Prepare Markers and Table Data (Sub Functions)
        reAfficheClientsMarkers(param_clients) {

            // 1) Pre‑compute filter sets & date bounds
            const startDate     =   this.date_start, endDate = this.date_end;

            const jpFilter      =   new Set(this.journey_plan_filter_value);
            const distFilter    =   new Set(this.district_filter_value);
            const cityFilter    =   new Set(this.city_filter_value);
            const typeFilter    =   new Set(this.type_client_filter_value);
            const jourFilter    =   new Set(this.journee_filter_value);
            const ownerFilter   =   new Set(this.owner_filter_value);
            const statusFilter  =   new Set(this.status_filter_value);

            // 2) One pass: filter route_import.clients
            const filtered      =   param_clients.filter(client => {

                const cd            =   this.$humanToISO(client.created_at);

                if (startDate           && cd < startDate) return false;
                if (endDate             && cd > endDate)   return false;

                if (jpFilter.size       && !jpFilter.has(client.JPlan))                         return false;
                if (distFilter.size     && !distFilter.has(client.DistrictNo.toString()))       return false;
                if (cityFilter.size     && !cityFilter.has(client.CityNo.toString()))           return false;
                if (typeFilter.size     && !typeFilter.has(client.CustomerType.toString()))     return false;
                if (jourFilter.size     && !jourFilter.has(client.Journee.toString()))          return false;
                if (ownerFilter.size    && !ownerFilter.has(client.owner_name.toString()))      return false;
                if (statusFilter.size   && !statusFilter.has(client.status.toString()))         return false;

                return true;
            });

            // 3) Group filtered clients by your column_group
            const configs = {
                1: { list: this.route_import.liste_journey_plan     , prop: 'JPlan'         , labelKey: 'JPlan'         },
                2: { list: this.route_import.districts              , prop: 'DistrictNo'    , labelKey: 'DistrictNameE' },
                3: { list: this.route_import.cites                  , prop: 'CityNo'        , labelKey: 'CityNameE'     },
                4: { list: this.route_import.liste_type_client      , prop: 'CustomerType'  , labelKey: 'CustomerType'  },
                5: { list: this.route_import.liste_journee          , prop: 'Journee'       , labelKey: 'Journee'       },
                6: { list: this.route_import.owners                 , prop: 'owner_name'    , labelKey: 'owner_name'    },
                7: { list: this.route_import.liste_status           , prop: 'status'        , labelKey: 'status'        }
            };

            const cfg = configs[this.column_group];

            // init empty buckets
            const groups = {};
            for (const [key, md] of Object.entries(cfg.list)) {
                groups[key] = {
                    column_name: typeof md === 'object' && md[cfg.labelKey] ? md[cfg.labelKey] : key,
                    color: md.color,
                    clients: []
                };
            }

            // assign clients
            for (let i = 0, L = filtered.length; i < L; i++) {
                const c = filtered[i], k = c[cfg.prop];
                if (groups[k]) groups[k].clients.push(c);
            }

            // 4) sort by descending size & rebuild object
            let clients_markers_affiche     =   Object
                                                    .values(groups)
                                                    .sort((a, b) => b.clients.length - a.clients.length)
                                                    .reduce((acc, g) => {
                                                        acc[g.column_name] = g;
                                                        return acc;
                                                    }, {});

            //
            return clients_markers_affiche
        },

        reAfficheClientsTable() {

            // now your table is literally the same filtered list—
            // if you need the same date + dropdown filters:
            const startDate = this.date_start, endDate = this.date_end;
            const filtered = this.route_import.clients.filter(c => {
            const cd = this.$humanToISO(c.created_at);
            if (startDate && cd < startDate) return false;
            if (endDate   && cd > endDate)   return false;
            // no need to re‐apply grouping filters if markers already did it
            return true;
            });
            this.clients_table_affiche = filtered;
        },

        async setDataTable() {

            // Create DataTable
            this.datatable_route_import_obs_clients     =   this.datatable_route_import_obs_clients_instance.$DataTableCreate("route_import_obs_clients", this.clients_table_affiche, this.route_import_obs_clients_columns, this.setDataTable, null, this.updateClient, null, this.selectRow, "Route Import Clients")      
        },

        selectRow(selected_row, selected_row_id) {

            this.selected_row       =   selected_row
            this.selected_row_id    =   selected_row_id
        },

        // Prepare Markers

        setRouteMarkers(mode, clients_markers_affiche) {

            // Clear Route Data
            this.clearRouteMarkers()

            // Set Markers
            this.addMarkers(clients_markers_affiche)

            // Focus
            this.focuseMarkers()
        },

        removeDrawings() {
            this.map_instance.$removeDrawings()
        },

        clearRouteMarkers() {
            this.map_instance.$clearRouteMarkers()
        },

        addMarkers(clients_markers_affiche) {

            // Set Markers
            for (const [key, value] of Object.entries(clients_markers_affiche))  {

                this.map_instance.$setRouteMarkers(clients_markers_affiche[key].clients, key, clients_markers_affiche[key].color)
            }
        },

        async updateMarkers(clients_markers_affiche, param_clients) {

            // Set Markers
            this.map_instance.$deleteRouteMarkers(param_clients)            

            await this.$nextTick()

            setTimeout(() => {
                
                // Set Markers
                for (const [key, value] of Object.entries(clients_markers_affiche))  {

                    this.map_instance.$setRouteMarkers(clients_markers_affiche[key].clients, key, clients_markers_affiche[key].color)
                }
            }, 0);
        },

        async deleteMarkers(param_clients) {

            // Delete Markers
            this.map_instance.$deleteRouteMarkers(param_clients)
        },

        focuseMarkers() {
            setTimeout(() => {
                this.map_instance.$focuseMarkers()
            }, 0);
        },

        // Set Map Operations

        showTerritories() {
            this.map_instance.$showTerritories()
        },

        async showResume() {
            await this.$refs.ModalResume.getClients()
        },

        async getDoubles() {
            await this.$refs.ModalValidateMap.getDoubles()
        },

        async showJPlanBDTerritories() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("liste_journey_plan", JSON.stringify(this.journey_plan_filter_value)) 

            const res   = await this.$callApi('post'    ,   '/route_import/'+this.route_import.id+'/journey_plan/util'   ,   formData)      

            if(res.status===200){

                // Show BD Territories
                this.map_instance.$showJPlanBDTerritories(res.data)

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
                this.map_instance.$showJourneeBDTerritories(res.data)

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

        async showUserBDTerritories() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("liste_user_territory"  ,   JSON.stringify(this.owner_filter_value)) 

            const res   = await this.$callApi('post'    ,   '/route_import/'+this.route_import.id+'/user_territories/util'   ,   formData)      

            if(res.status===200){

                // Show BD Territories
                this.map_instance.$showUserBDTerritories(res.data)

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

        // Set KML Filters

        setKMLWillayas(kml_willayas) {

            this.kml_willayas   =   []

            for (let i = 0; i < kml_willayas.length; i++) {

                this.kml_willayas.push({ value : kml_willayas[i].DistrictNo , label : kml_willayas[i].DistrictNo + "- " + kml_willayas[i].DistrictNameE})
            }
        },

        setKMLLayers() {
            this.map_instance.$setKMLLayers(this.kml_layers)
        },

        // Show/Hide Filters

        showMapFilters() {

            let map_parent_div_element              =   document.getElementById("map_parent_div")
            map_parent_div_element.classList.add("map_shrink")

            // Datatable
            let map_operations_filters_div_element             =   document.getElementById("map_operations_filters_div")
            map_operations_filters_div_element.classList.remove("animate__fadeOutRight")
            map_operations_filters_div_element.classList.add("animate__fadeInRight")

            this.show_map_operations_filters_div               =   true 

            // Show Button
            let show_map_filters_button_element     =   document.getElementById("show_map_filters_button")
            show_map_filters_button_element.classList.remove("animate__fadeInLeft")
            show_map_filters_button_element.classList.add("animate__fadeOutRight")

            setTimeout(() => {

                this.show_map_filters_button        =   false

                // Hide Button
                let hide_map_filters_button_element     =   document.getElementById("hide_map_filters_button")
                hide_map_filters_button_element.classList.remove("animate__fadeOutRight")
                hide_map_filters_button_element.classList.add("animate__fadeInLeft")

                this.hide_map_filters_button            =   true
            }, 555)
        },

        hideMapFilters() {

            let map_parent_div_element                  =   document.getElementById("map_parent_div")
            map_parent_div_element.classList.remove("map_shrink")

            // Datatable
            let map_operations_filters_div_element                      =   document.getElementById("map_operations_filters_div")
            map_operations_filters_div_element.classList.remove("animate__fadeInRight")
            map_operations_filters_div_element.classList.add("animate__fadeOutRight")

            setTimeout(() => {

                this.show_map_operations_filters_div                    =   false 

                // Hide Button
                let hide_map_filters_button_element                     =   document.getElementById("hide_map_filters_button")
                hide_map_filters_button_element.classList.remove("animate__fadeInLeft")
                hide_map_filters_button_element.classList.add("animate__fadeOutRight")

                setTimeout(() => {

                    this.hide_map_filters_button                =   false 

                    // Show Button
                    let show_map_filters_button_element             =   document.getElementById("show_map_filters_button")
                    show_map_filters_button_element.classList.remove("animate__fadeOutRight")
                    show_map_filters_button_element.classList.add("animate__fadeInLeft")

                    this.show_map_filters_button                    =   true 
                }, 555);
            }, 555)
        },

        // Show/Hide Datatable

        showDatatableClients() {

            // Datatable
            let datatable_route_import_obs_clients_parent_element   =   document.getElementById("datatable_route_import_obs_clients_parent")
            datatable_route_import_obs_clients_parent_element.classList.remove("animate__fadeOutDown")
            datatable_route_import_obs_clients_parent_element.classList.add("animate__fadeInUp")

            this.show_datatable_route_import_obs_clients_parent     =   true 

            // Show Button
            let show_datatable_route_import_obs_clients_button_element      =   document.getElementById("show_datatable_route_import_obs_clients_button")
            show_datatable_route_import_obs_clients_button_element.classList.remove("animate__fadeInUp")
            show_datatable_route_import_obs_clients_button_element.classList.add("animate__fadeOutDown")

            setTimeout(() => {

                this.show_datatable_route_import_obs_clients_button             =   false

                // Hide Button
                let hide_datatable_route_import_obs_clients_button_element      =   document.getElementById("hide_datatable_route_import_obs_clients_button")
                hide_datatable_route_import_obs_clients_button_element.classList.remove("animate__fadeOutDown")
                hide_datatable_route_import_obs_clients_button_element.classList.add("animate__fadeInUp")

                this.hide_datatable_route_import_obs_clients_button             =   true

                // Map
                setTimeout(() => {

                    let map_element                                                 =   document.getElementById("map")
                    map_element.style.height                                        =   "45%"
                }, 555)

            }, 555)
        },

        hideDatatableClients() {

            // Map
            let map_element                                                 =   document.getElementById("map")
            map_element.style.height                                        =   "100%"

            // Datatable
            let datatable_route_import_obs_clients_parent_element           =   document.getElementById("datatable_route_import_obs_clients_parent")
            datatable_route_import_obs_clients_parent_element.classList.remove("animate__fadeInUp")
            datatable_route_import_obs_clients_parent_element.classList.add("animate__fadeOutDown")

            setTimeout(() => {

                this.show_datatable_route_import_obs_clients_parent             =   false 

                // Hide Button
                let hide_datatable_route_import_obs_clients_button_element      =   document.getElementById("hide_datatable_route_import_obs_clients_button")
                hide_datatable_route_import_obs_clients_button_element.classList.remove("animate__fadeInUp")
                hide_datatable_route_import_obs_clients_button_element.classList.add("animate__fadeOutDown")

                setTimeout(() => {

                    this.hide_datatable_route_import_obs_clients_button_element     =   false 

                    // Show Button
                    let show_datatable_route_import_obs_clients_button_element      =   document.getElementById("show_datatable_route_import_obs_clients_button")
                    show_datatable_route_import_obs_clients_button_element.classList.remove("animate__fadeOutDown")
                    show_datatable_route_import_obs_clients_button_element.classList.add("animate__fadeInUp")

                    this.show_datatable_route_import_obs_clients_button             =   true 

                }, 255);
            }, 255)
        },

        // Show Markers in Cluster/Marker Mode

        async switchMarkerClusterMode() {

            if(this.marker_cluster_mode ==  "marker") {

                // Show Markers
                this.map_instance.$switchMarkerClusterMode("marker")

                // Show Markers
                await this.reAfficherClientsAndMarkers("switch_marker_cluster_mode")
            }

            else {

                if(this.marker_cluster_mode ==  "cluster") {

                    // Show Markers
                    this.map_instance.$switchMarkerClusterMode("cluster")

                    // Show Markers
                    await this.reAfficherClientsAndMarkers("switch_marker_cluster_mode")
                }
            }
        },

        // Show CRUD Modals

        addClient(client) {

            // ShowModal
            var addModal    =   new Modal(document.getElementById("ModalClientAdd"));
            addModal.show();

            //
            this.$refs.ModalClientAdd.getData(client, this.clients_table_affiche)
        },

        updateClient(client) {

            // ShowModal
            var updateModal     =   new Modal(document.getElementById("ModalClientUpdate"));
            updateModal.show();

            //
            this.$refs.ModalClientUpdate.getData(client, this.clients_table_affiche)
        },

        async updateClientsRoute(clients) {
            await this.$refs.ModalClientsChangeRoute.getData(clients)
        },

        addJourneyPlan(LatLngs) {
            this.$refs.ModalAddJourneyPlan.getData(LatLngs)
        },

        updateJourneyPlan(journey_plan) {
            this.$refs.ModalUpdateJourneyPlan.getData(journey_plan)
        },

        // Handle Events

        async addClientJSON(client) {

            const new_client = {
                ...client,
                owner: this.getUser.id,
                owner_name: this.getUser.nom,
            };

            //
            this.route_import.clients.push(new_client);
        },

        async updateClientJSON(client) {

            const existing  =   this.route_import.clients.find(c => c.id === client.id);

            //
            if (existing) Object.assign(existing, client);
        },

        async deleteClientJSON(client) {

            const idx = this.route_import.clients.findIndex(c => c.id === client.id);

            //
            if (idx !== -1) this.route_import.clients.splice(idx, 1);
        },

        async changeRouteClientsJSON(clients) {

            const lookup = this.route_import.clients.reduce((map, c) => {
                map[c.id] = c;
                return map;
            }, {});

            //
            clients.forEach(src => {
                const dest  =   lookup[src.id];
                if (!dest) return;

                // Only overwrite when src field is non-empty
                if (src.owner)          { dest.owner        = src.owner; dest.owner_name = src.owner_name; }
                if (src.DistrictNo)     { dest.DistrictNo   = src.DistrictNo; dest.DistrictNameE = src.DistrictNameE; }
                if (src.CityNo)         { dest.CityNo       = src.CityNo; dest.CityNameE = src.CityNameE; }
                if (src.JPlan)          { dest.JPlan        = src.JPlan; }
                if (src.Journee)        { dest.Journee      = src.Journee; }
                if (src.status)         { dest.status       = src.status; }
            });
        },

        async changeRouteClientsJSONDelete(clients) {

            const toRemove              =   new Set(clients.map(c => c.id));

            //
            this.route_import.clients   =   this.route_import.clients.filter(c => !toRemove.has(c.id));
        },
    },

    watch: {

        getAddClient(newValue, oldValue) {

            if(newValue != null) {
                
                // Send DATA To Modal
                this.addClient(newValue)
            }
        },

        getUpdateClient(newValue, oldValue) {

            if(newValue != null) {
                
                // Send DATA To Modal
                this.updateClient(newValue)
            }
        },

        async getClientsChangeRoute(newValue, oldValue) {

            if((newValue != null)&&(newValue != {})) {
                
                // ShowModal
                var ModalClientsChangeRoute     =   new Modal(document.getElementById("ModalClientsChangeRoute"));
                ModalClientsChangeRoute.show();

                // Send DATA To Modal
                await this.updateClientsRoute(newValue)
            }
        },

        //

        getAddJourneyPlan(newValue, oldValue) {

            if(newValue != null) {
                
                if((typeof newValue.journey_plan    ==  "undefined")&&(typeof newValue.journee  ==  "undefined")&&(typeof newValue.user     ==  "undefined")) {

                    // ShowModal
                    var addJourneyPlanModal        =   new Modal(document.getElementById("addJourneyPlanModal"));
                    addJourneyPlanModal.show();

                    // Send DATA To Modal
                    this.addJourneyPlan(newValue.latlngs)
                }

                if(typeof newValue.journey_plan     !=  "undefined") {

                    // ShowModal
                    var updateJourneyPlanModal      =   new Modal(document.getElementById("updateJourneyPlanModal"));
                    updateJourneyPlanModal.show();

                    // Send DATA To Modal
                    this.updateJourneyPlan(newValue.journey_plan)
                }

                if(typeof newValue.journee          !=  "undefined") {

                    // ShowModal
                    var updateJourneyPlanModal      =   new Modal(document.getElementById("updateJourneyPlanModal"));
                    updateJourneyPlanModal.show();

                    // Send DATA To Modal
                    this.updateJourneyPlan(newValue.journee)
                }

                if(typeof newValue.user             !=  "undefined") {

                    // ShowModal
                    var updateJourneyPlanModal      =   new Modal(document.getElementById("updateJourneyPlanModal"));
                    updateJourneyPlanModal.show();

                    // Send DATA To Modal
                    this.updateJourneyPlan(newValue.user)
                }
            }
        }
    },
}
</script>

<style>

#route_import_clients_component .dataTables_scrollBody {
    max-height  : 185px !important;
}

</style>