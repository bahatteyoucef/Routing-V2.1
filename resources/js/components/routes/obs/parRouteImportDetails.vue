<template v-if="$isRole('Administrateur')||$isRole('RTM Manager')||$isRole('BU Manager')||$isRole('Salesman Cashvan')||$isRole('Salesman Prevente')||$isRole('Salesman Livreur')">

    <div>
        <div class="map_top_buttons_div">
            <button class="btn primary w-100 m-0 mt-1"  @click="focuseMarkers()">Focus</button>
            <button class="btn primary w-100 m-0 mt-1"  @click="validerData()">Valider</button>

            <select class="form-select w-100 m-0 mt-1"  @change="reAfficherClientsAndMarkers()"  v-model="column_group">
                <option value="1">JPlan</option>
                <option value="2">DistrictNo</option>
                <option value="3">CityNo</option>
                <option value="4">CustomerType</option>
            </select>

            <!-- Journey Plan   -->
            <Multiselect
                v-model     =   "journey_plan_filter_value"
                :options    =   "liste_journey_plan"
                mode        =   "tags"
                placeholder =   "Filter By JPlan"
                class       =   "mt-1"

                :close-on-select    =   "false"
                :searchable         =   "true"
                :create-option      =   "true"

                @change             =   "reAfficherClientsAndMarkers()"
            />
            <!--                -->

            <!-- District       -->
            <Multiselect
                v-model     =   "district_filter_value"
                :options    =   "districts"
                mode        =   "tags"
                placeholder =   "Filter By DistrictNameE"
                class       =   "mt-1"

                :close-on-select    =   "false"
                :searchable         =   "true"
                :create-option      =   "true"

                @change             =   "reAfficherClientsAndMarkers()"
            />
            <!--                -->

            <!-- City           -->
            <Multiselect
                v-model     =   "city_filter_value"
                :options    =   "cites"
                mode        =   "tags"
                placeholder =   "Filter By CityNameE"
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
            <!--                -->

            <!-- Journee        -->
            <Multiselect
                v-model     =   "journee_filter_value"
                :options    =   "journee_filter_liste"
                mode        =   "tags"
                placeholder =   "Filter By Journee"
                class       =   "mt-1"

                :close-on-select    =   "false"
                :searchable         =   "true"
                :create-option      =   "true"

                @change             =   "reAfficherClientsAndMarkers()"
            />
            <!--                -->

        </div>

        <table class="table table-striped scrollbar scrollbar-deep-blue" id="informations_route_div">
            <thead>
                <tr>
                    <th class="col-sm-1">CustomerNo</th>
                    <th class="col-sm-1">CustomerCode</th>
                    <th class="col-sm-1">CustomerNameE</th>
                    <th class="col-sm-1">CustomerNameA</th>

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

                    <th class="col-sm-1">Frequency</th>
                    <th class="col-sm-1">StartWeek</th>

                    <th class="col-sm-1">sat</th>
                    <th class="col-sm-1">sun</th>
                    <th class="col-sm-1">mon</th>
                    <th class="col-sm-1">tue</th>
                    <th class="col-sm-1">wed</th>
                    <th class="col-sm-1">thu</th>
                </tr>
            </thead>
            
            <tbody>
                <tr v-for="client in clients_table_affiche" :key="client">
                    <td>{{client.CustomerNo}}</td>
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

                    <td>{{client.Frequency}}</td>
                    <td>{{client.StartWeek}}</td>

                    <td>{{client.sat}}</td>
                    <td>{{client.sun}}</td>
                    <td>{{client.mon}}</td>
                    <td>{{client.tue}}</td>
                    <td>{{client.wed}}</td>
                    <td>{{client.thu}}</td>
                </tr>
            </tbody>
        </table>
    </div>

</template>

<script>

import Multiselect              from '@vueform/multiselect'

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

            journee_filter_value        :   []                                                          ,
            journee_filter_liste        :   ['Samedi','Dimanche','Lundi', 'Mardi', 'Mercredi', 'Jeudi'] ,

            // Clients
            clients_group               :   {}                                                          ,
            clients_markers_affiche     :   {}                                                          ,
            clients_table_affiche       :   []                                                          ,

            // Event
            event_on_process            :   false
        }
    },  

    components: {
        Multiselect
    },

    async mounted() {

        this.setValues()
        await this.getData()

        // CRUD Events

        this.emitter.on('reSetAdd'          , async (client)    =>  {

            if(this.event_on_process ==  false) {

                this.event_on_process   =   true

                this.addClient(client)

                await this.removeDrawings()

                this.event_on_process   =   false
            }
        })

        this.emitter.on('reSetUpdate'       , async (client)    =>  {

            if(this.event_on_process ==  false) {

                this.event_on_process   =   true

                this.updateClient(client)

                await this.removeDrawings()

                this.event_on_process   =   false
            }
        })

        this.emitter.on('reSetDelete'       , async (client)    =>  {

            if(this.event_on_process ==  false) {

                this.event_on_process   =   true

                this.deleteClient(client)

                await this.removeDrawings()

                this.event_on_process   =   false
            }
        })

        this.emitter.on('reSetChangeRoute'  , async (clients)    =>  {

            if(this.event_on_process ==  false) {

                this.event_on_process   =   true

                this.changeRouteClients(clients)

                await this.removeDrawings()

                this.event_on_process   =   false
            }
        })
    },

    methods : {

        ...mapActions("journey_plan" ,  [
            "setListeJourneyPlanAction"   ,
        ]),

        setValues() {

            this.id_route_import    =   this.$route.params.id_route_import
        },

        async getData() {

            // Show Loading Page
            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route/obs/route_import/"+this.id_route_import+"/details",   null)
            this.route_import           =   res.data

            // Set Clients
            this.route_import.clients   =   JSON.parse(this.route_import.data)

            // Extract JPlan, Cites, District
            this.extractMetaData()

            // Prepare Clients
            this.prepareClients()

            // Prepare Affiche Clients
            this.reAfficheClients()

            // Set Markers
            this.setRouteMarkers()

            // Hide Loading Page
            this.$hideLoadingPage()
        }, 

        //

        extractMetaData() {

            let journey_plan_existe     =   false
            let district_existe         =   false
            let cite_existe             =   false
            let type_client_existe      =   false

            for (let i = 0; i < this.route_import.clients.length; i++) {

                journey_plan_existe         =   this.checkExistJPlan(this.liste_journey_plan, this.route_import.clients[i].JPlan) 

                if(!journey_plan_existe) {

                    this.liste_journey_plan[this.route_import.clients[i].JPlan]         =   {JPlan  :   ""}
                    this.liste_journey_plan[this.route_import.clients[i].JPlan].JPlan   =   this.route_import.clients[i].JPlan   
                }

                //

                district_existe             =   this.checkExistDistrictNameE(this.districts, this.route_import.clients[i].DistrictNameE) 

                if(!district_existe) {

                    this.districts[this.route_import.clients[i].DistrictNameE]                  =   {DistrictNameE :   ""}
                    this.districts[this.route_import.clients[i].DistrictNameE].DistrictNameE    =   this.route_import.clients[i].DistrictNameE 
                }

                //

                cite_existe                 =   this.checkExistCityNameE(this.cites, this.route_import.clients[i].CityNameE) 

                if(!cite_existe) {

                    this.cites[this.route_import.clients[i].CityNameE]                          =   {CityNameE :   ""}
                    this.cites[this.route_import.clients[i].CityNameE].CityNameE                =   this.route_import.clients[i].CityNameE 
                }

                //

                type_client_existe          =   this.checkExistCustomerType(this.liste_type_client, this.route_import.clients[i].CustomerType) 

                if(!type_client_existe) {

                    this.liste_type_client[this.route_import.clients[i].CustomerType]                   =   {CustomerType :   ""}
                    this.liste_type_client[this.route_import.clients[i].CustomerType].CustomerType      =   this.route_import.clients[i].CustomerType 
                }
            }

            this.route_import.liste_journey_plan    =   this.liste_journey_plan
            this.route_import.districts             =   this.districts
            this.route_import.cites                 =   this.cites
            this.route_import.liste_type_client     =   this.liste_type_client

            // 
            this.setListeJourneyPlanAction(this.liste_journey_plan)
        },

        // 

        checkExistJPlan(liste_journey_plan, JPlan) {

            for (const [key, value] of Object.entries(liste_journey_plan)) {

                if(liste_journey_plan[key].JPlan    ==  JPlan) {

                    return true
                }
            }

            return false
        },

        checkExistDistrictNameE(districts, DistrictNameE) {

            for (const [key, value] of Object.entries(districts)) {
            
                if(districts[key].DistrictNameE    ==  DistrictNameE) {

                    return true
                }
            }

            return false
        },

        checkExistCityNameE(cites, CityNameE) {

            for (const [key, value] of Object.entries(cites)) {
            
                if(cites[key].CityNameE    ==  CityNameE) {

                    return true
                }
            }

            return false
        },

        checkExistCustomerType(liste_type_client, CustomerType) {

            for (const [key, value] of Object.entries(liste_type_client)) {
            
                if(liste_type_client[key].CustomerType    ==  CustomerType) {

                    return true
                }
            }

            return false
        },

        //

        reAfficherClientsAndMarkers() {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(() => {

                // Extract JPlan, Cites, District
                this.extractMetaData()

                // Prepare Clients
                this.prepareClients()

                // reAffiche Clients
                this.reAfficheClients()

                // reAffiche Markers
                this.setRouteMarkers()

                // Hide Loading Page
                this.$hideLoadingPage()

            }, 55);
        },

        prepareClients() {

            this.clients_group      =   {}

            if(this.column_group    ==  1) {

                for (const [i, value] of Object.entries(this.route_import.liste_journey_plan)) {

                    this.clients_group[this.route_import.liste_journey_plan[i].JPlan]   =   {column_name : this.route_import.liste_journey_plan[i].JPlan, clients : []}

                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].JPlan   ==  this.route_import.liste_journey_plan[i].JPlan) {

                            this.clients_group[this.route_import.liste_journey_plan[i].JPlan].clients.push(this.route_import.clients[j])
                        }
                    }
                }
            }

            if(this.column_group    ==  2) {

                for (const [i, value] of Object.entries(this.route_import.districts)) {

                    this.clients_group[this.route_import.districts[i].DistrictNameE]       =   {column_name : this.route_import.districts[i].DistrictNameE, clients : []}
                
                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].DistrictNameE  ==  this.route_import.districts[i].DistrictNameE) {

                            this.clients_group[this.route_import.districts[i].DistrictNameE].clients.push(this.route_import.clients[j])
                        }
                    }
                }
            }

            if(this.column_group    ==  3) {

                for (const [i, value] of Object.entries(this.route_import.cites)) {

                    this.clients_group[this.route_import.cites[i].CityNameE]               =   {column_name : this.route_import.cites[i].CityNameE, clients : []}
                
                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].CityNameE  ==  this.route_import.cites[i].CityNameE) {

                            this.clients_group[this.route_import.cites[i].CityNameE].clients.push(this.route_import.clients[j])
                        }
                    }
                }           
            }

            if(this.column_group    ==  4) {

                for (const [i, value] of Object.entries(this.route_import.liste_type_client)) {

                    this.clients_group[this.route_import.liste_type_client[i].CustomerType]     =   {column_name : this.route_import.liste_type_client[i].CustomerType, clients : []}
                
                    for (let j = 0; j < this.route_import.clients.length; j++) {

                        if(this.route_import.clients[j].CustomerType    ==  this.route_import.liste_type_client[i].CustomerType) {

                            this.clients_group[this.route_import.liste_type_client[i].CustomerType].clients.push(this.route_import.clients[j])
                        }
                    }
                }           
            }
        },

        reAfficheClients() {

            // 
            this.reAfficheClientsMarkers()

            // 
            this.reAfficheClientsTable()
        },

        reAfficheClientsMarkers() {

            this.clients_markers_affiche    =   this.clients_group

            let splice                      =   false

            let journee_array               =   []
            let journee_existe              =   false

            for (const [key, value] of Object.entries(this.clients_markers_affiche)) {
 
                for (let i = this.clients_markers_affiche[key].clients.length - 1;    i >= 0; --i) {

                    splice          =   false  

                    journee_array   =   []
                    journee_existe  =   false

                    if(this.journey_plan_filter_value.length    >   0) {

                        if (this.journey_plan_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].JPlan)         ==  -1) {

                            // splice
                            this.clients_markers_affiche[key].clients.splice(i, 1)
                            splice  =   true
                        }
                    }

                    if(splice   ==  false) {

                        if(this.district_filter_value.length    >   0) {

                            if (this.district_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].DistrictNameE.toString())  ==  -1) {

                                // splice
                                this.clients_markers_affiche[key].clients.splice(i, 1)
                                splice  =   true
                            }
                        }

                        if(splice   ==  false) {

                            if(this.city_filter_value.length    >   0) {

                                if (this.city_filter_value.indexOf(this.clients_markers_affiche[key].clients[i].CityNameE.toString())      ==  -1) {

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

                                        if(this.clients_markers_affiche[key].clients[i].sat ==  1) {

                                            journee_array.push("Samedi")
                                        }

                                        if(this.clients_markers_affiche[key].clients[i].sun ==  1) {

                                            journee_array.push("Dimanche")
                                        }

                                        if(this.clients_markers_affiche[key].clients[i].mon ==  1) {

                                            journee_array.push("Lundi")
                                        }

                                        if(this.clients_markers_affiche[key].clients[i].tue ==  1) {

                                            journee_array.push("Mardi")
                                        }

                                        if(this.clients_markers_affiche[key].clients[i].wed ==  1) {

                                            journee_array.push("Mercredi")
                                        }

                                        if(this.clients_markers_affiche[key].clients[i].thu ==  1) {

                                            journee_array.push("Jeudi")
                                        }

                                        for (let k = 0; k < journee_array.length; k++) {

                                            if (this.journee_filter_value.indexOf(journee_array[k].toString())   !=  -1) {

                                                journee_existe  =   true
                                                break;
                                            }                  
                                        }

                                        if (journee_existe  ==  false) {

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

                if(this.clients_markers_affiche[key].clients.length <   0) {

                    delete this.clients_markers_affiche[key]
                }
            }
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

        clearPath() {

            // Clear Path
            this.$map.$clearPath()
        },

        setMarkers() {

            let i = 0

            // Set Markers
            for (const [key, value] of Object.entries(this.clients_markers_affiche)) {

                this.$map.$setRouteMarkers(this.clients_markers_affiche[key].clients, i)

                i   =   i + 1
            }
        },

        async removeDrawings() {

            this.$map.$removeDrawings()
        },

        //

        focuseMarkers() {

            this.$map.$focuseMarkers()
        },

        //

        addClient(client) {

            let new_client      =   {}

            // Add Client
            new_client.CustomerNo       =   this.getMaxCustomerNo() +   1

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
            new_client.Frequency        =   client.Frequency        
            new_client.StartWeek        =   client.StartWeek        

            new_client.sat              =   client.sat        
            new_client.sun              =   client.sun        
            new_client.mon              =   client.mon        
            new_client.tue              =   client.tue        
            new_client.wed              =   client.wed        
            new_client.thu              =   client.thu        

            this.route_import.clients.push(new_client)

            // ReAffiche
            this.reAfficherClientsAndMarkers()
        },

        updateClient(client) {

            for (let i = 0; i < this.route_import.clients.length; i++) {
                
                if(this.route_import.clients[i].CustomerNo  ==  client.CustomerNo) {

                    // Update Client

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

                    this.route_import.clients[i].JPlan          =   client.JPlan            
                    this.route_import.clients[i].Frequency      =   client.Frequency        
                    this.route_import.clients[i].StartWeek      = client.StartWeek        

                    this.route_import.clients[i].sat            =   client.sat        
                    this.route_import.clients[i].sun            =   client.sun        
                    this.route_import.clients[i].mon            =   client.mon        
                    this.route_import.clients[i].tue            =   client.tue        
                    this.route_import.clients[i].wed            =   client.wed        
                    this.route_import.clients[i].thu            =   client.thu      

                    break
                }
            }

            // ReAffiche
            this.reAfficherClientsAndMarkers()
        },

        deleteClient(client) {

            for (let i = 0; i < this.route_import.clients.length; i++) {
                
                if(this.route_import.clients[i].CustomerNo  ==  client.CustomerNo) {

                    this.route_import.clients.splice(i, 1)

                    break
                }
            }

            // ReAffiche
            this.reAfficherClientsAndMarkers()

        },

        changeRouteClients(clients) {

            for(let i = 0; i < clients.length; i++) {
                for (let j = 0; j < this.route_import.clients.length; j++) {
                    
                    if(this.route_import.clients[j].CustomerNo  ==  clients[i].CustomerNo) {

                        // Update Client
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

                        if(clients[i].Frequency     !=  "") {

                            this.route_import.clients[j].Frequency      =   clients[i].Frequency  
                        }

                        if(clients[i].StartWeek     !=  "") {

                            this.route_import.clients[j].StartWeek      =   clients[i].StartWeek        
                        }

                        if(clients[i].jour_changed  ==  true) {

                            this.route_import.clients[j].sat            =   clients[i].sat        
                            this.route_import.clients[j].sun            =   clients[i].sun        
                            this.route_import.clients[j].mon            =   clients[i].mon        
                            this.route_import.clients[j].tue            =   clients[i].tue        
                            this.route_import.clients[j].wed            =   clients[i].wed        
                            this.route_import.clients[j].thu            =   clients[i].thu      
                        }

                        break
                    }
                }
            }

            // ReAffiche
            this.reAfficherClientsAndMarkers()
        },

        getMaxCustomerNo() {

            let maxCustomerNo   =   0

            for (let i = 0; i < this.route_import.clients.length; i++) {

                if(maxCustomerNo    <   this.route_import.clients[i].CustomerNo) {

                    maxCustomerNo   =   this.route_import.clients[i].CustomerNo
                }
            }

            return maxCustomerNo
        },

        //

        async validerData() {

            let formData = new FormData();

            formData.append("data", JSON.stringify(this.route_import.clients))

            const res   = await this.$callApi('post'    ,   '/route_import/'+this.route_import.id+'/update'    ,   formData)      

            if(res.status===200){

                // Send Feedback
                this.$feedbackSuccess("Modifications Validés"     ,   "les changements effectués ont été valides avec succès !")
            }
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}

        },

        //

    }
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>