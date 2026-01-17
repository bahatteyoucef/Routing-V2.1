<template>

    <div class="m-1">
        
        <!-- Title -->
        <div class="row">
            <div class="col-sm-9 d-flex align-items-center">
                <h4 class="mb-0 ml-2">Data Map Report</h4>
            </div>
        </div>

        <!-- Map -->
        <div class="row mt-3 mb-3">
            <div id="show_data_map_report" style="width: 100%; height: 500px;"></div>
        </div>

    </div>

</template>

<script>

import emitter                  from    "@/utils/emitter"

export default {

    data() {
        return {

            show_data_census_reports_chart      :   false   ,

            //
            datatable_data_census_report_table  :   null    ,

            //
            route_import                        :   {}      ,

            // Dates
            date_start                          :   ""      ,
            date_end                            :   ""      ,

            // Journey Plan
            liste_journey_plan                  :   {}      ,
            districts                           :   {}      ,
            cites                               :   {}      ,
            liste_type_client                   :   {}      ,
            liste_journee                       :   {}      ,
            owners                              :   {}      ,
            liste_status                        :   {}      ,

            // Clients
            clients_group                       :   {}      ,
            clients_markers_affiche             :   {}      
        }
    },

    props : ["map_report_data", "id_route_import"],

    async mounted() {

        // add Map
        await this.prepareMap()

        //
        emitter.emit('show_data_map_report_content_ready')
    },

    methods : {

        async prepareMap() {

            // add Map
            await this.addMap()

            // Extract JPlan, Cites, District
            this.extractMetaData()

            // Prepare Clients
            this.prepareClients()

            // Prepare Affiche Clients
            await this.reAfficheClients()

            // Set Markers
            this.setRouteMarkers()
        },

        //

        async addMap() {

            //
            this.route_import.id        =   this.id_route_import
            this.route_import.clients   =   this.map_report_data.rows

            //
            this.$map.$createMap(this.$role(), "show_data_map_report", false, false)
        },

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
            let status_util                         =   {}

            let liste_journey_plan_colors           =   {}
            let districts_colors                    =   {}
            let cites_colors                        =   {}
            let liste_type_client_colors            =   {}
            let liste_journee_colors                =   {}
            let owners_colors                       =   {}
            let liste_status_colors                 =   {}

            //
            // this.liste_journey_plan                 =   this.route_import.clients.map(client => client.JPlan)       .filter((JPlan, index, self)        => self.indexOf(JPlan)          === index)
            // this.districts                          =   this.route_import.clients.map(client => client.DistrictNo)  .filter((DistrictNo, index, self)   => self.indexOf(DistrictNo)     === index)
            // this.cites                              =   this.route_import.clients.map(client => client.CityNo)      .filter((CityNo, index, self)       => self.indexOf(CityNo)         === index)
            // this.liste_type_client                  =   this.route_import.clients.map(client => client.CustomerType).filter((CustomerType, index, self) => self.indexOf(CustomerType)   === index)
            // this.liste_journee                      =   this.route_import.clients.map(client => client.Journee)     .filter((Journee, index, self)      => self.indexOf(Journee)        === index)
            // this.owners                             =   this.route_import.clients.map(client => client.owner_username)  .filter((owner_username, index, self)   => self.indexOf(owner_username)     === index)
            // this.liste_status                       =   this.route_import.clients.map(client => client.status)      .filter((status, index, self)       => self.indexOf(status)         === index)

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

                if(typeof owner_util[this.route_import.clients[i].owner_username]                ==  "undefined") {

                    owner_util[this.route_import.clients[i].owner_username]                     =   this.route_import.clients[i].owner_username
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
                owner_existe              =   this.checkExistOwner(this.owners, this.route_import.clients[i].owner_username) 

                if(!owner_existe) {

                    this.owners[this.route_import.clients[i].owner_username]             =   {owner_username :   ""}
                    this.owners[this.route_import.clients[i].owner_username].owner_username  =   this.route_import.clients[i].owner_username 

                    if(Object.keys(owners_colors).length    >   0) {

                        while(typeof owners_colors[this.$colors[owner_count % this.$colors.length]] !=  "undefined") {

                            owner_count                                                                 =   owner_count +   1
                        }
                    }

                    this.owners[this.route_import.clients[i].owner_username].color      =   this.$colors[owner_count % this.$colors.length]
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
            this.route_import.liste_journey_plan    =   this.liste_journey_plan
            this.route_import.districts             =   this.districts
            this.route_import.cites                 =   this.cites
            this.route_import.liste_type_client     =   this.liste_type_client
            this.route_import.liste_journee         =   this.liste_journee
            this.route_import.owners                =   this.owners
            this.route_import.liste_status          =   this.liste_status
        },

        prepareClients() {

            this.clients_group      =   {}

            for (const [i, value] of Object.entries(this.route_import.liste_journey_plan))  {

                this.clients_group[i]   =   {column_name : i, clients : [], color : this.route_import.liste_journey_plan[i].color}

                for (let j = 0; j < this.route_import.clients.length; j++) {

                    if(this.route_import.clients[j].JPlan   ==  i) {

                        this.clients_group[i].clients.push(this.route_import.clients[j])
                    }
                }
            }

            // Step 1: Convert the object into an array of values
            const clientsGroupsArray    =   Object.values(this.clients_group);

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
        },

        reAfficheClientsMarkers() {

            this.clients_markers_affiche        =   this.clients_group

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

        setRouteMarkers() {

            // Clear Route Data
            this.clearRouteMarkers()

            // Set Markers
            this.setMarkers()

            // Focus
            this.focuseMarkers()
        },

        clearRouteMarkers() {

            // Clear Route Data
            this.$map.$clearRouteMarkers()
        },

        setMarkers() {

            let i = 0

            // Set Markers
            for (const [key, value] of Object.entries(this.clients_markers_affiche)) {

                this.$map.$setRouteMarkers(this.clients_markers_affiche[key].clients, i, this.clients_markers_affiche[key].color)

                i   =   i + 1
            }
        },

        focuseMarkers() {

            this.$map.$focuseMarkers()
        },

        //  //  //

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

        checkExistOwner(owners, owner_username) {

            for (const [key, value] of Object.entries(owners)) {
                
                if(key  ==  owner_username) {

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
    },

    watch: {
        map_report_data : {
            handler(newVal, oldVal) {

                this.prepareMap()
            },

            deep: true        // watch nested changes inside map_report_data
        },
    },
}

</script>