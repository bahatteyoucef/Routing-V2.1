<template>

    <!-- Modal -->
    <div class="modal fade" id="modalClientUpdateTempo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Update the Client : {{client.old_CustomerNameE}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3">

                    <form>

                        <div class="mb-3">
                            <label for="CustomerCode"       class="form-label">CustomerCode (CustomerCode)</label>
                            <input type="text"              class="form-control"        id="CustomerCode"           v-model="client.CustomerCode">
                        </div>

                        <div class="mb-3">
                            <label for="CustomerNameE"      class="form-label">CustomerNameE (CustomerNameE)</label>
                            <input type="text"              class="form-control"        id="CustomerNameE"          v-model="client.CustomerNameE">
                        </div>

                        <div class="mb-3">
                            <label for="CustomerNameA"      class="form-label">CustomerNameA (CustomerNameA)</label>
                            <input type="text"              class="form-control"        id="CustomerNameA"          v-model="client.CustomerNameA">
                        </div>

                        <div class="mb-3">
                            <label for="Tel"                class="form-label">Phone Number (Tel)</label>
                            <input type="text"              class="form-control"        id="Tel"                    v-model="client.Tel">
                        </div>

                        <div class="mb-3">
                            <label for="Address"            class="form-label">Address (Address)</label>
                            <input type="text"              class="form-control"        id="Address"                v-model="client.Address">
                        </div>

                        <div class="mb-3">
                            <label for="DistrictNo"         class="form-label">DistrictNo (DistrictNo)</label>
                            <select                         class="form-select"         id="DistrictNo"             v-model="client.DistrictNo"     @change="getCites()">
                                <option v-for="willaya in willayas" :key="willaya.DistrictNo" :value="willaya.DistrictNo">{{willaya.DistrictNo}}- {{willaya.DistrictNameE}}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="CityNo"             class="form-label">CityNo (CityNo)</label>
                            <select                         class="form-select"         id="CityNo"                 v-model="client.CityNo">
                                <option v-for="cite in cites" :key="cite.CITYNO" :value="cite.CITYNO">{{cite.CITYNO}}- {{cite.CityNameE}}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="Latitude"           class="form-label">Latitude (Latitude)</label>
                            <input type="text"              class="form-control"        id="Latitude"               v-model="client.Latitude">
                        </div>

                        <div class="mb-3">
                            <label for="Longitude"          class="form-label">Longitude (Longitude)</label>
                            <input type="text"              class="form-control"        id="Longitude"              v-model="client.Longitude">
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="text"               class="form-label">CustomerType (CustomerType)</label>
                            <select                         class="form-select"         id="CustomerType"                 v-model="client.CustomerType">
                                <option     :value="'Superette'">Superette</option>
                                <option     :value="'Alimentation General'">Alimentation General</option>
                                <option     :value="'Grossiste'">Grossiste</option>
                            </select>
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="JPlan"              class="form-label">JPlan (JPlan)</label>
                            <input type="text"              class="form-control"        id="JPlan"           v-model="client.JPlan">
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="Journee"            class="form-label">WorkDay (Journee)</label>
                            <input type="text"              class="form-control"        id="Journee"           v-model="client.Journee">
                        </div>

                        <!--  -->

                        <!-- <hr />

                        <h5>Nearby Clients</h5>

                        <hr />

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="col-sm-1">CustomerNameE</th>
                                    <th class="col-sm-2">Tel</th>
                                    <th class="col-sm-1">CustomerType</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <tr v-for="client in close_clients" :key="client">
                                    <td>{{client.CustomerNameE}}</td>
                                    <td>{{client.Tel}}</td>
                                    <td>{{client.CustomerType}}</td>
                                </tr>
                            </tbody>
                        </table> -->

                    </form>

                </div>

                <div class="modal-footer"       style="display: flex; justify-content: space-between;">
                    <div class="left-buttons"   style="display: flex;">
                        <button type="button"   class="btn btn-danger float-left" @click="deleteData()">Delete</button>
                    </div>

                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button"   class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button"   class="btn btn-primary"   @click="sendData()">Confirm</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

import {mapGetters, mapActions} from    "vuex"

export default {

    data() {
        return {


            client      :   {

                // Client
                id                  :   '',

                CustomerCode        :   '',

                old_CustomerNameE   :   '',

                CustomerNameE       :   '',
                CustomerNameA       :   '',
                Tel                 :   '',

                Address             :   '',

                DistrictNo          :   '',
                DistrictNameE       :   '',

                CityNo              :   '',
                CityNameE           :   '',

                Latitude            :   '',
                Longitude           :   '',

                // Type
                CustomerType        :   '',

                // Journey Plan
                JPlan               :   '',
                Journee             :   ''
            },

            willayas                        :   []  ,
            cites                           :   []  ,

            // 
            liste_journey_plan              :   []  ,
            liste_journee                   :   []  ,
            liste_type_client               :   []  ,

            //
            all_clients                     :   []  ,
            close_clients                   :   []  ,
            min_distance                    :   0.03
        }
    },

    props : ["id_route_import_tempo", "modal_source"],

    mounted() {

        this.clearData("#modalClientUpdateTempo")
    },  

    methods : {

        //

        async sendData() {

            this.$showLoadingPage()

            // Set Client
            this.client.DistrictNameE   =   this.getDistrictNameE(this.client.DistrictNo)
            this.client.CityNameE       =   this.getCityNameE(this.client.CityNo)

            let formData = new FormData();

            formData.append("CustomerCode"  ,   this.client.CustomerCode)
            formData.append("CustomerNameE" ,   this.client.CustomerNameE)
            formData.append("CustomerNameA" ,   this.client.CustomerNameA)
            formData.append("Latitude"      ,   this.client.Latitude)
            formData.append("Longitude"     ,   this.client.Longitude)
            formData.append("Address"       ,   this.client.Address)
            formData.append("DistrictNo"    ,   this.client.DistrictNo)
            formData.append("DistrictNameE" ,   this.client.DistrictNameE)
            formData.append("CityNo"        ,   this.client.CityNo)
            formData.append("CityNameE"     ,   this.client.CityNameE)
            formData.append("Tel"           ,   this.client.Tel)
            formData.append("CustomerType"  ,   this.client.CustomerType)
            formData.append("JPlan"         ,   this.client.JPlan)
            formData.append("Journee"       ,   this.client.Journee)

            const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo/"+this.client.id+"/update",   formData)
            console.log(res.data)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                if(this.modal_source    ==  "CustomerCode") {

                    this.emitter.emit("updateDoublesCustomerCode"   , this.client)
                }

                if(this.modal_source    ==  "CustomerNameE") {

                    this.emitter.emit("updateDoublesCustomerNameE"  , this.client)
                }

                if(this.modal_source    ==  "Tel") {

                    this.emitter.emit("updateDoublesTel"            , this.client)
                }

                if(this.modal_source    ==  "GPS") {

                    this.emitter.emit("updateDoublesGPS"            , this.client)
                }
            
                // Close Modal
                this.$hideModal("modalClientUpdateTempo")
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}            

            //
        },

        async deleteData() {

            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo/"+this.client.id+"/delete",   null)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                if(this.modal_source    ==  "CustomerCode") {

                    this.emitter.emit("deleteDoublesCustomerCode"   , this.client)
                }

                if(this.modal_source    ==  "CustomerNameE") {

                    this.emitter.emit("deleteDoublesCustomerNameE"  , this.client)
                }

                if(this.modal_source    ==  "Tel") {

                    this.emitter.emit("deleteDoublesTel"            , this.client)
                }

                if(this.modal_source    ==  "GPS") {

                    this.emitter.emit("deleteDoublesGPS"            , this.client)
                }

                // Close Modal
                this.$hideModal("modalClientUpdateTempo")
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}      
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                // Client
                this.client.CustomerCode        =   '',

                this.client.old_CustomerNameE   =   '',

                this.client.CustomerNameE       =   '',
                this.client.CustomerNameA       =   '',
                this.client.Tel                 =   '',

                this.client.Address             =   '',

                this.client.DistrictNo          =   '',
                this.client.DistrictNameE       =   '',

                this.client.CityNo              =   '',
                this.client.CityNameE           =   '',

                this.client.Latitude            =   '',
                this.client.Longitude           =   '',

                // Type
                this.client.CustomerType        =   '',

                // Journey Plan
                this.client.JPlan               =   '',

                this.willayas                   =   []
                this.cites                      =   []
            });
        },

        getData(client, all_clients) {

            this.getClientData(client)  
            this.getComboData()  

            // this.checkClients()
        },

        async getClientData(client) {

            this.client.id                  =   client.id

            this.client.CustomerCode        =   client.CustomerCode

            this.client.old_CustomerNameE   =   client.CustomerNameE

            this.client.CustomerNameE       =   client.CustomerNameE
            this.client.CustomerNameA       =   client.CustomerNameA
            this.client.Latitude            =   client.Latitude
            this.client.Longitude           =   client.Longitude

            this.client.Address             =   client.Address
            this.client.DistrictNo          =   client.DistrictNo

            this.client.CityNo              =   client.CityNo

            this.client.Tel                 =   client.Tel

            this.client.CustomerType        =   client.CustomerType

            this.client.JPlan               =   client.JPlan

            this.client.Journee             =   client.Journee

            this.setJoursGetData(client)

            await this.getCites()
        },

        async getComboData() {

            const res_3                     =   await this.$callApi("post"  ,   "/rtm_willayas"         ,   null)
            this.willayas                   =   res_3.data
        },

        async getCites() {

            // Show Loading Page
            this.$showLoadingPage()

            const res_3                     =   await this.$callApi("post"  ,   "/rtm_willayas/"+this.client.DistrictNo+"/rtm_cites"         ,   null)
            this.cites                      =   res_3.data

            // Hide Loading Page
            this.$hideLoadingPage()
        },

        //

        setJoursGetData(client) {

            const jours         =   document.querySelectorAll(".jours")

            jours.forEach(jour => {

                // Samedi
                if(jour.id ==   "samedi_checkbox") {

                    if(client.sat   ==  1) {

                        jour.setAttribute('checked', 'true')
                    }
                }

                // Dimanche
                if(jour.id ==   "dimanche_checkbox") {

                    if(client.sun   ==  1) {

                        jour.setAttribute('checked', 'true')
                    }
                }

                // Lundi
                if(jour.id ==   "lundi_checkbox") {

                    if(client.mon   ==  1) {

                        jour.setAttribute('checked', 'true')
                    }
                }

                // Mardi
                if(jour.id ==   "mardi_checkbox") {

                    if(client.tue   ==  1) {

                        jour.setAttribute('checked', 'true')
                    }
                }

                // Mercredi
                if(jour.id ==   "mercredi_checkbox") {

                    if(client.wed   ==  1) {

                        jour.setAttribute('checked', 'true')
                    }
                }

                // Jeudi
                if(jour.id ==   "jeudi_checkbox") {

                    if(client.thu   ==  1) {

                        jour.setAttribute('checked', 'true')
                    }
                }
            });
        },

        //

        unsetJoursGetData() {

            const jours             =   document.querySelectorAll(".jours")

            jours.forEach(jour => {

                jour.removeAttribute('checked')
            });
        
        },

        //

        setJours() {

            // Samedi

            const samedi_checkbox   =   document.querySelector("#samedi_checkbox")
            
            if(samedi_checkbox.checked) {

                this.client.sat         =   1
            }
            else {

                this.client.sat         =   0
            }

            //

            // Dimanche

            const dimanche_checkbox =   document.querySelector("#dimanche_checkbox")
            
            if(dimanche_checkbox.checked) {

                this.client.sun         =   1
            }
            else {

                this.client.sun         =   1
            }

            //

            // Lundi

            const lundi_checkbox    =   document.querySelector("#lundi_checkbox")
            
            if(lundi_checkbox.checked) {

                this.client.mon         =   1
            }
            else {

                this.client.mon         =   1
            }

            //

            // Mardi

            const mardi_checkbox    =   document.querySelector("#mardi_checkbox")
            
            if(mardi_checkbox.checked) {

                this.client.tue         =   1
            }
            else {

                this.client.tue         =   0
            }

            //

            // Mercredi

            const mercredi_checkbox =   document.querySelector("#mercredi_checkbox")
            
            if(mercredi_checkbox.checked) {

                this.client.wed         =   1
            }
            else {

                this.client.wed         =   0
            }

            //

            // Jeudi

            const jeudi_checkbox    =   document.querySelector("#jeudi_checkbox")
            
            if(jeudi_checkbox.checked) {

                this.client.thu         =   1
            }
            else {

                this.client.thu         =   0
            }
        },

        //

        getDistrictNameE(DistrictNo) {

            for (let i = 0; i < this.willayas.length; i++) {

                if(this.willayas[i].DistrictNo  ==  DistrictNo) {

                    return this.willayas[i].DistrictNameE
                }                
            }
        },

        getCityNameE(CityNo) {

            for (let i = 0; i < this.cites.length; i++) {

                if(this.cites[i].CITYNO  ==  CityNo) {

                    return this.cites[i].CityNameE
                }                
            }
        },

        //

        checkClients() {

            this.close_clients  =   []

            let distance        =   0

            for (let i = 0; i < this.all_clients.length; i++) {

                if(this.all_clients[i].id   !=  this.client.id) {

                    distance        =   this.getDistance(this.client.Latitude, this.client.Longitude, this.all_clients[i].Latitude, this.all_clients[i].Longitude)

                    if(distance <=  this.min_distance) {
                    
                        this.close_clients.push(this.all_clients[i])
                    }
                }
            }
        },

        getDistance(latitude_1, longitude_1, latitude_2, longitude_2) {

            return this.$map.$setDistanceStraight(latitude_1, longitude_1, latitude_2, longitude_2)
        }
    },
};

</script>