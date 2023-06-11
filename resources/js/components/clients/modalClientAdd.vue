<template>

    <!-- Modal -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3">

                    <form>

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
                            <label for="Frequency"      class="form-label">Frequence de visite</label>
                            <select                     class="form-select"     id="Frequency"       v-model="client.Frequency">
                                <option value="1">journalière</option>
                                <option value="2">3 fois par semaine</option>
                                <option value="3">2 fois par semaine</option>
                                <option value="4">1 fois par semaine</option>
                                <option value="5">1 fois par 15 jours</option>
                                <option value="6">1 fois par mois</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="StartWeek"          class="form-label">StartWeek</label>
                            <select                         class="form-select"     id="StartWeek"      v-model="client.StartWeek">
                                <option value="1">Semaine 1</option>
                                <option value="2">Semaine 2</option>
                                <option value="3">Semaine 3</option>
                                <option value="4">Semaine 4</option>
                            </select>
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="JPlan"              class="form-label">JPlan</label>
                            <input type="text"              class="form-control"        id="JPlan"           v-model="client.JPlan">
                        </div>

                        <!--  -->

                        <div class="mb-3">

                            <label class="form-label">Jours</label>

                            <div class="form-check m-0 p-0">

                                <table class="table table-borderless ml-2">
    
                                    <tr class="row w-75">
                                        <td class="col-sm-1"><input type="checkbox"  class="jours"                              id="samedi_checkbox"        />  </td>
                                        <td class="col-sm-8"><label for="checkbox"   class="ml-1">Samedi</label>                                                </td>
                                    </tr>

                                    <tr class="row w-75">
                                        <td class="col-sm-1"><input type="checkbox"  class="jours"                              id="dimanche_checkbox"      />  </td>
                                        <td class="col-sm-8"><label for="checkbox"   class="ml-1">Dimanche</label>                                              </td>
                                    </tr>

                                    <tr class="row w-75">
                                        <td class="col-sm-1"><input type="checkbox"  class="jours"                              id="lundi_checkbox"         />  </td>
                                        <td class="col-sm-8"><label for="checkbox"   class="ml-1">Lundi</label>                                                 </td>
                                    </tr>

                                    <tr class="row w-75">
                                        <td class="col-sm-1"><input type="checkbox"  class="jours"                              id="mardi_checkbox"         />  </td>
                                        <td class="col-sm-8"><label for="checkbox"   class="ml-1">Mardi</label>                                                 </td>
                                    </tr>

                                    <tr class="row w-75">
                                        <td class="col-sm-1"><input type="checkbox"  class="jours"                              id="mercredi_checkbox"      />  </td>
                                        <td class="col-sm-8"><label for="checkbox"   class="ml-1">Mercredi</label>                                              </td>
                                    </tr>
                                    
                                    <tr class="row w-75">
                                        <td class="col-sm-1"><input type="checkbox"  class="jours"                              id="jeudi_checkbox"         />  </td>
                                        <td class="col-sm-8"><label for="checkbox"   class="ml-1">Jeudi</label>                                                 </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <!--  -->

                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary"   @click="sendData()">Valider</button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

import { mapActions }  from "vuex";

export default {

    data() {
        return {

            client      :   {

                // Client
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

                // JPCustomer
                Frequency       :   '',
                StartWeek       :   '',

                // Journee
                sat             :   0 ,
                sun             :   0 ,
                mon             :   0 ,
                tue             :   0 ,
                wed             :   0 ,
                thu             :   0 
            },

            willayas                :   [],
            cites                   :   []
        }
    },

    mounted() {

        this.clearData("#addClientModal")
    },  

    methods : {

        ...mapActions("client" ,  [
            "setAddClientAction"   ,
        ]),

        async sendData() {

            // Set Frequency
            if((this.client.Frequency    ==  1)||(this.client.Frequency    ==  2)||(this.client.Frequency    ==  3)||(this.client.Frequency    ==  4)) {

                this.client.Frequency   =   4
            }

            if(this.client.Frequency    ==  5) {

                this.client.Frequency   =   2
            }

            if(this.client.Frequency    ==  6) {

                this.client.Frequency   =   1
            }

            // Set Jours
            this.setJours()

            // Set Client
            this.client.DistrictNameE   =   this.getDistrictNameE(this.client.DistrictNo)
            this.client.CityNameE       =   this.getCityNameE(this.client.CityNo)

            // Send Client
            this.emitter.emit('reSetAdd' , this.client)

            // Close Modal
            this.$hideModal("addClientModal")
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                // 
                this.unsetJoursGetData()

                // 
                this.setAddClientAction(null)

                // Client
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

                // JPCustomer
                this.client.Frequency       =   '',
                this.client.StartWeek       =   '',

                this.willayas               =   []
                this.cites                  =   []

                // Remove Drawings
                this.removeDrawings()
            });
        },

        removeDrawings() {

            // Remove Drawings
            this.$map.$removeDrawings()
        },

        //

        getData(client) {

            this.setCoords(client)
            this.getComboData()  
        },

        setCoords(client) {

            if(client) {

                this.client.Latitude    =   client.lat
                this.client.Longitude   =   client.lng
            }
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
    }

};
</script>