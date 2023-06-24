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
                            <input type="text"              class="form-control"        id="JPlan"                  v-model="client.JPlan">
                        </div>

                        <!--  -->
                        
                        <div class="mb-3">
                            <label for="Journee"            class="form-label">Journee</label>
                            <input type="text"              class="form-control"        id="Journee"                v-model="client.Journee">
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

import {mapGetters, mapActions} from    "vuex"

export default {

    data() {
        return {

            client      :   {

                // Client
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

                // Journee
                Journee         :   '' 
            },

            willayas                :   [],
            cites                   :   [],

            // 
            liste_journey_plan              :   []  ,
            liste_journee                   :   []  ,
            liste_type_client               :   []  
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

        this.clearData("#addClientModal")
    },  

    methods : {

        ...mapActions("client" ,  [
            "setAddClientAction"   ,
        ]),

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

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/store",   formData)
            console.log(res.data)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Client
                this.emitter.emit('reSetAdd' , this.client)

                // Close Modal
                this.$hideModal("addClientModal")

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

                // 
                this.setAddClientAction(null)

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

                // Journee Plan
                this.client.JPlan           =   '',

                // Journee
                this.client.Journee         =   '',

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

            console.log(this.willayas)
        },

        async getCites() {

            // Show Loading Page
            this.$showLoadingPage()

            const res_3                     =   await this.$callApi("post"  ,   "/rtm_willayas/"+this.client.DistrictNo+"/rtm_cites"         ,   null)
            this.cites                      =   res_3.data

            console.log(this.cites)

            // Hide Loading Page
            this.$hideLoadingPage()
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