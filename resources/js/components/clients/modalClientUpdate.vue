<template>

    <!-- Modal -->
    <div class="modal fade" id="updateClientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Modifier le Client : </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3">

                    <form>

                        <div class="mb-3">
                            <label for="CustomerCode"       class="form-label">CustomerCode</label>
                            <input type="text"              class="form-control"        id="CustomerCode"           v-model="client.CustomerCode">
                        </div>

                        <div class="mb-3">
                            <label for="CustomerNameE"      class="form-label">CustomerNameE</label>
                            <input type="text"              class="form-control"        id="CustomerNameE"          v-model="client.CustomerNameE">
                        </div>

                        <div class="mb-3">
                            <label for="CustomerNameA"      class="form-label">CustomerNameA</label>
                            <input type="text"              class="form-control"        id="CustomerNameA"          v-model="client.CustomerNameA">
                        </div>

                        <div class="mb-3">
                            <label for="Tel"                class="form-label">Tel</label>
                            <input type="text"              class="form-control"        id="Tel"                    v-model="client.Tel">
                        </div>

                        <div class="mb-3">
                            <label for="Address"            class="form-label">Address</label>
                            <input type="text"              class="form-control"        id="Address"                v-model="client.Address">
                        </div>

                        <div class="mb-3">
                            <label for="DistrictNo"         class="form-label">DistrictNo</label>
                            <select                         class="form-select"         id="DistrictNo"             v-model="client.DistrictNo"     @change="getCites()">
                                <option v-for="willaya in willayas" :key="willaya.DistrictNo" :value="willaya.DistrictNo">{{willaya.DistrictNameE}}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="CityNo"             class="form-label">CityNo</label>
                            <select                         class="form-select"         id="CityNo"                 v-model="client.CityNo">
                                <option v-for="cite in cites" :key="cite.CITYNO" :value="cite.CITYNO">{{cite.CityNameE}}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="Latitude"           class="form-label">Latitude</label>
                            <input type="text"              class="form-control"        id="Latitude"               v-model="client.Latitude">
                        </div>

                        <div class="mb-3">
                            <label for="Longitude"          class="form-label">Longitude</label>
                            <input type="text"              class="form-control"        id="Longitude"              v-model="client.Longitude">
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="text"               class="form-label">CustomerType</label>
                            <input type="text"              class="form-control"        id="CustomerType"           v-model="client.CustomerType">
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="JPlan"              class="form-label">JPlan</label>
                            <input type="text"              class="form-control"        id="JPlan"           v-model="client.JPlan">
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="Journee"            class="form-label">Journee</label>
                            <input type="text"              class="form-control"        id="Journee"           v-model="client.Journee">
                        </div>

                        <!--  -->

                    </form>

                </div>

                <div class="modal-footer"       style="display: flex; justify-content: space-between;">
                    <div class="left-buttons"   style="display: flex;">
                        <button type="button"   class="btn btn-danger float-left" @click="deleteData()">Supprimer</button>
                    </div>

                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button"   class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button"   class="btn btn-primary"   @click="sendData()">Valider</button>
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
                CustomerNo      :   '',

                CustomerCode    :   '',

                CustomerNameE   :   '',
                CustomerNameA   :   '',
                Tel             :   '',

                Address         :   '',

                DistrictNo      :   '',
                DistrictNameE   :   '',

                CityNo          :   '',
                CityNameE       :   '',

                Latitude        :   '',
                Longitude       :   '',

                // Type
                CustomerType    :   '',

                // Journey Plan
                JPlan           :   '',
                Journee         :   ''
            },

            willayas                :   [],
            cites                   :   [],

            // 
            liste_journey_plan              :   []  ,
            liste_journee                   :   []  ,
            liste_type_client               :   []  ,

        }
    },

    computed : {

        ...mapGetters({
            getListeJourneyPlan             :   'journey_plan/getListeJourneyPlan'      ,
            getListeTypeClient              :   'type_client/getListeTypeClient'        ,  
            getListeJournee                 :   'journee/getListeJournee'    
        }),
    },

    mounted() {

        this.clearData("#updateClientModal")
    },  

    methods : {

        ...mapActions("client" ,  [
            "setUpdateClientAction"   ,
        ]),

        //

        async sendData() {

            // Set Client
            this.client.DistrictNameE   =   this.getDistrictNameE(this.client.DistrictNo)
            this.client.CityNameE       =   this.getCityNameE(this.client.CityNo)

            // Send Client
            this.emitter.emit('reSetUpdate' , this.client)

            // Close Modal
            this.$hideModal("updateClientModal")
        },

        async deleteData() {

            // Send Client
            this.emitter.emit('reSetDelete' , this.client)

            // Close Modal
            this.$hideModal("updateClientModal")
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                // 
                this.unsetJoursGetData()

                // 
                this.setUpdateClientAction(null)

                // Client
                this.client.CustomerCode    =   '',

                this.client.CustomerNameE   =   '',
                this.client.CustomerNameA   =   '',
                this.client.Tel             =   '',

                this.client.Address         =   '',

                this.client.DistrictNo      =   '',
                this.client.DistrictNameE    =   '',

                this.client.CityNo          =   '',
                this.client.CityNameE        =   '',

                this.client.Latitude        =   '',
                this.client.Longitude       =   '',

                // Type
                this.client.CustomerType    =   '',

                // Journey Plan
                this.client.JPlan           =   '',

                this.willayas               =   []
                this.cites                  =   []

                // Remove Drawings
                this.removeDrawings()

            });
        },

        removeDrawings() {

            // Not Map
            if(!this.$route.path.startsWith("/route/obs/")) {

                // Do Nothing 
            }

            // Map
            else {

                // Remove Drawings
                this.$map.$removeDrawings()
            }   
        },

        getData(client) {

            this.getClientData(client)  
            this.getComboData()  
        },

        async getClientData(client) {

            this.client.CustomerNo      =   client.CustomerNo

            this.client.CustomerCode    =   client.CustomerCode

            this.client.CustomerNameE   =   client.CustomerNameE
            this.client.CustomerNameA   =   client.CustomerNameA
            this.client.Latitude        =   client.Latitude
            this.client.Longitude       =   client.Longitude

            this.client.Address         =   client.Address
            this.client.DistrictNo      =   client.DistrictNo

            this.client.CityNo          =   client.CityNo

            this.client.Tel             =   client.Tel

            this.client.CustomerType    =   client.CustomerType

            this.client.JPlan           =   client.JPlan

            this.client.Journee         =   client.Journee

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

        checkUncheck(id) {

            const jour  =   document.getElementById(id)
            console.log(jour)

            if(jour.checked) {

                jour.removeAttribute('checked')
            }
            else {

                jour.setAttribute('checked', 'true')
            }
        },

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
        }
    },

    watch : {

        getListeJourneyPlan(new_liste_journey_plan, old_liste_journey_plan) {

            this.liste_journey_plan     =   new_liste_journey_plan
        },

        getListeJournee(new_liste_journee, old_liste_journee) {

            this.liste_journee          =   new_liste_journee
        },

        getListeTypeClient(new_liste_type_client, old_liste_type_client) {

            this.liste_type_client      =   new_liste_type_client
        }
    }

};

</script>