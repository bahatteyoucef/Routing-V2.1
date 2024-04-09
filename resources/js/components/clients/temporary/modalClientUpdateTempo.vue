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
                            <label for="Neighborhood"       class="form-label">Neighborhood (Neighborhood)</label>
                            <input type="text"              class="form-control"        id="Neighborhood"           v-model="client.Neighborhood">
                        </div>

                        <div class="mb-3">
                            <label for="Landmark"           class="form-label">Landmark (Landmark)</label>
                            <textarea                       class="form-control"        id="Landmark"   rows="3"    v-model="client.Landmark"></textarea>
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
                                <option     :value="'Hypermarché'">Hypermarché</option>
                                <option     :value="'Supermarché'">Supermarché</option>
                                <option     :value="'Alimentation General'">Alimentation General</option>
                                <option     :value="'Bureau Tabac'">Bureau Tabac</option>
                                <option     :value="'Cafétéria'">Cafétéria</option>
                                <option     :value="'Cosmetique'">Cosmetique</option>
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

                id                                      :   '',

                // Slide 1
                CustomerCode                            :   '',

                // Slide 2
                CustomerBarCode_image                   :   '',
                CustomerBarCode_image_original_name     :   '',

                // Slide 3
                CustomerNameE                           :   '',
                old_CustomerNameE                       :   '',

                // Slide 4
                CustomerNameA                           :   '',

                // Slide 5
                Tel                                     :   '',

                // Slide 6
                Latitude                                :   '',
                Longitude                               :   '',

                // Slide 7
                Address                                 :   '',

                // Slide 8
                Neighborhood                            :   '',

                // Slide 9
                Landmark                                :   '',

                // Slide 10
                DistrictNo                              :   '',
                DistrictNameE                           :   '',

                // Slide 11
                CityNo                                  :   '',
                CityNameE                               :   '',

                // Slide 12
                CustomerType                            :   '',

                // Slide 13
                BrandAvailability                       :   0,

                // Slide 14
                BrandSourcePurchase                     :   '',

                // Slide 15
                JPlan                                   :   '',

                // Slide 16 
                Journee                                 :   '',

                // Slide 17
                status                                  :   '',
                status_original                         :   '',
                nonvalidated_details                    :   '', 

                // Slide 18
                facade_image                            :   '',
                facade_image_original_name              :   '',

                // Slide 19   
                in_store_image                          :   '',
                in_store_image_original_name            :   '',

                // Slide 20
                comment                                 :   '',

                //
                CustomerBarCode_image_updated           :   false,
                facade_image_updated                    :   false,
                in_store_image_updated                  :   false,
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

            formData.append("CustomerCode"                  ,   this.client.CustomerCode)
            formData.append("CustomerNameE"                 ,   this.client.CustomerNameE)
            formData.append("CustomerNameA"                 ,   this.client.CustomerNameA)
            formData.append("Latitude"                      ,   this.client.Latitude)
            formData.append("Longitude"                     ,   this.client.Longitude)
            formData.append("Address"                       ,   this.client.Address)
            formData.append("Neighborhood"                  ,   this.client.Neighborhood)
            formData.append("Landmark"                      ,   this.client.Landmark)

            formData.append("DistrictNo"                    ,   this.client.DistrictNo)
            formData.append("DistrictNameE"                 ,   this.client.DistrictNameE)
            formData.append("CityNo"                        ,   this.client.CityNo)
            formData.append("CityNameE"                     ,   this.client.CityNameE)
            formData.append("Tel"                           ,   this.client.Tel)
            formData.append("CustomerType"                  ,   this.client.CustomerType)
            formData.append("BrandAvailability"             ,   this.client.BrandAvailability)
            formData.append("BrandSourcePurchase"           ,   this.client.BrandSourcePurchase)

            formData.append("JPlan"                         ,   this.client.JPlan)
            formData.append("Journee"                       ,   this.client.Journee)

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
                this.client.id                                      =   '',

                // Slide 1
                this.client.CustomerCode                            =   '',

                // Slide 3
                this.client.old_CustomerNameE                       =   '',
                this.client.CustomerNameE                           =   '',

                // Slide 4
                this.client.CustomerNameA                           =   '',

                // Slide 5
                this.client.Tel                                     =   '',

                // Slide 6
                this.client.Latitude                                =   '',
                this.client.Longitude                               =   '',

                // Slide 7
                this.client.Address                                 =   '',

                // Slide 8
                this.client.Neighborhood                            =   '',

                // Slide 9
                this.client.Landmark                                =   '',

                // Slide 10
                this.client.DistrictNo                              =   '',
                this.client.DistrictNameE                           =   '',

                // Slide 11
                this.client.CityNo                                  =   '',
                this.client.CityNameE                               =   '',

                // Slide 12
                this.client.CustomerType                            =   '',

                // Slide 13
                this.client.BrandAvailability                       =   0,

                // Slide 14
                this.client.BrandSourcePurchase                     =   '',

                // Slide 15
                this.client.JPlan                                   =   '',

                // Slide 16 
                this.client.Journee                                 =   '',

                //

                this.willayas                                       =   []  ,
                this.cites                                          =   []  ,

                this.liste_journey_plan                             =   []  ,
                this.liste_journee                                  =   []  ,
                this.liste_type_client                              =   []  ,

                this.all_clients                                    =   []  ,
                this.close_clients                                  =   []
            });
        },

        getData(client, all_clients) {

            this.getClientData(client)  
            this.getComboData()  
        },

        async getClientData(client) {

            console.log(client)

            this.client.id                  =   client.id

            this.client.CustomerCode        =   client.CustomerCode

            this.client.old_CustomerNameE   =   client.CustomerNameE

            this.client.CustomerNameE       =   client.CustomerNameE
            this.client.CustomerNameA       =   client.CustomerNameA
            this.client.Latitude            =   client.Latitude
            this.client.Longitude           =   client.Longitude

            this.client.Address             =   client.Address
            this.client.DistrictNo          =   client.DistrictNo

            await this.getCites()

            this.client.CityNo              =   client.CityNo

            this.client.Tel                 =   client.Tel

            this.client.CustomerType        =   client.CustomerType

            this.client.JPlan               =   client.JPlan

            this.client.Journee             =   client.Journee
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

            this.client.CityNo              =   ""

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
};

</script>