<template>

    <!-- Modal -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add a new Client</h5>
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

                        <!--  -->
                         
                        <div v-if="$isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice')"   class="mb-3">
                            <label for="Latitude"           class="form-label">Latitude (Latitude)</label>
                            <input type="text"              class="form-control"        id="Latitude"               v-model="client.Latitude"   @change="checkClients()">
                        </div>

                        <div v-if="$isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice')"   class="mb-3">
                            <label for="Longitude"          class="form-label">Longitude (Longitude)</label>
                            <input type="text"              class="form-control"        id="Longitude"              v-model="client.Longitude"  @change="checkClients()">
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
                            <label for="text"               class="form-label">BrandAvailability (BrandAvailability)</label>
                            <select                         class="form-select"         id="BrandAvailability"                 v-model="client.BrandAvailability">
                                <option     value="0">No</option>
                                <option     value="1">Yes</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="text"               class="form-label">BrandSourcePurchase (BrandSourcePurchase)</label>
                            <select                         class="form-select"         id="BrandSourcePurchase"                 v-model="client.BrandSourcePurchase">
                                <option     value="Distribution Direct">Distribution Direct</option>
                                <option     value="Grossiste Fixe">Grossiste Fixe</option>
                                <option     value="Grossiste Mobile">Grossiste Mobile</option>
                                <option     value="Multi Source">Multi Source</option>
                                <option     value="Pas d'achat">Pas d'achat</option>
                            </select>
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="JPlan"              class="form-label">JPlan (JPlan)</label>
                            <input type="text"              class="form-control"        id="JPlan"                  v-model="client.JPlan">
                        </div>

                        <!--  -->
                        
                        <div class="mb-3">
                            <label for="Journee"            class="form-label">WorkDay (Journee)</label>
                            <input type="text"              class="form-control"        id="Journee"                v-model="client.Journee">
                        </div>

                        <!--  -->

                        <div v-if="$isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice')" class="mb-3">
                            <label for="status"             class="form-label">Status</label>
                            <select                         class="form-select"         id="status"                 v-model="client.status">
                                <option value="validated" selected>validated</option>
                                <option value="pending">pending</option>
                                <option value="nonvalidated">nonvalidated</option>
                            </select>

                            <div v-if="client.status    ==  'nonvalidated'" class="mt-3">
                                <div class="form-group">
                                    <label      for="nonvalidated_details" class="form-label">NonValidated Details</label>
                                    <textarea   class="form-control" id="nonvalidated_details" rows="3"             v-model="client.nonvalidated_details"></textarea>
                                </div>
                            </div>
                        </div>

                        <div v-if="$isRole('FrontOffice')" class="mb-3">
                            <label for="status"             class="form-label">Status</label>
                            <select                         class="form-select"         id="status"                 v-model="client.status">
                                <option value="pending" selected>pending</option>
                                <option value="nonvalidated">nonvalidated</option>
                            </select>

                            <div v-if="client.status    ==  'nonvalidated'" class="mt-3">
                                <div class="form-group">
                                    <label      for="nonvalidated_details" class="form-label">NonValidated Details</label>
                                    <textarea   class="form-control" id="nonvalidated_details" rows="3"             v-model="client.nonvalidated_details"></textarea>
                                </div>
                            </div>
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="facade_image_add"   class="form-label">Facade Image (Facade Image)</label>
                            <input type="file"              class="form-control"        id="facade_image_add"           accept="image/*"    @change="facadeImage()">
                            <img                                                        id="facade_image_display_add"       src=""              class="w-100">
                        </div>

                        <div class="mb-3">
                            <label for="in_store_image_add" class="form-label">In-Store Image (In-Store Image)</label>
                            <input type="file"              class="form-control"        id="in_store_image_add"             accept="image/*"    @change="inStoreImage()">
                            <img                                                        id="in_store_image_display_add"     src=""              class="w-100">
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label      for="comment">Comment</label>
                            <textarea   class="form-control"    id="comment"    rows="3"    v-model="client.comment"></textarea>
                        </div>

                        <!--  -->

                        <hr />

                        <h5>Nearby Clients</h5>

                        <hr />

                        <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-wrap">CustomerNameE</th>
                                        <th class="text-wrap">CustomerNameA</th>
                                        <!-- <th class="col-sm-2">Tel</th> -->
                                        <th class="text-wrap">CustomerType</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="client in close_clients" :key="client">
                                        <td class="text-wrap">{{client.CustomerNameE}}</td>
                                        <td class="text-wrap">{{client.CustomerNameA}}</td>
                                        <!-- <td class="text-wrap">{{client.Tel}}</td> -->
                                        <td class="text-wrap">{{client.CustomerType}}</td>
                                    </tr>
                                </tbody>
                        </table>

                    </form>

                </div>

                <div class="modal-footer">
                </div>

                <div class="modal-footer"       style="display: flex; justify-content: space-between;">
                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"   @click="sendData()">Confirm</button>
                    </div>
                </div>


            </div>
        </div>
    </div>

</template>

<script>

import {mapGetters, mapActions} from    "vuex"

import moment                   from    "moment"
import "moment-timezone"

export default {

    data() {
        return {

            client      :   {

                // Images   
                facade_image                    :   '',
                in_store_image                  :   '',
                facade_image_original_name      :   '',
                in_store_image_original_name    :   '',

                // Client
                id              :   '',

                CustomerCode    :   '',

                CustomerNameE   :   '',
                CustomerNameA   :   '',
                Tel             :   '',

                Address         :   '',
                Neighborhood    :   '',
                Landmark        :   '',

                DistrictNo      :   '',
                DistrictNameE   :   '',

                CityNo          :   '',
                CityNameE       :   '',

                Latitude        :   0,
                Longitude       :   0,

                // Type
                CustomerType        :   '',
                BrandAvailability   :   '',
                BrandSourcePurchase :   '',

                // Journey Plan
                JPlan           :   '',

                // Journee
                Journee         :   '',

                // Status
                status                  :   '',
                nonvalidated_details    :   '', 

                // Comment
                comment                 :   ''
            },

            willayas                :   [],
            cites                   :   [],

            //

            liste_journey_plan              :   []  ,
            liste_journee                   :   []  ,
            liste_type_client               :   []  ,

            //

            all_clients                     :   []  ,
            close_clients                   :   []  ,
            min_distance                    :   0.03,

            //

            start_adding_date               :   ""
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

        if(this.$isRole("Super Admin")||this.$isRole("BU Manager")||this.$isRole("BackOffice")) {

            this.client.status  =   "validated"
        }

        if(this.$isRole("FrontOffice")) {

            this.client.status  =   "pending"
        }

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

            formData.append("CustomerCode"                          ,   this.client.CustomerCode)
            formData.append("CustomerNameE"                         ,   this.client.CustomerNameE)
            formData.append("CustomerNameA"                         ,   this.client.CustomerNameA)
            formData.append("Latitude"                              ,   this.client.Latitude)
            formData.append("Longitude"                             ,   this.client.Longitude)
            formData.append("Address"                               ,   this.client.Address)
            formData.append("Neighborhood"                          ,   this.client.Neighborhood)
            formData.append("Landmark"                              ,   this.client.Landmark)

            formData.append("DistrictNo"                            ,   this.client.DistrictNo)
            formData.append("DistrictNameE"                         ,   this.client.DistrictNameE)
            formData.append("CityNo"                                ,   this.client.CityNo)
            formData.append("CityNameE"                             ,   this.client.CityNameE)
            formData.append("Tel"                                   ,   this.client.Tel)
            formData.append("CustomerType"                          ,   this.client.CustomerType)

            formData.append("BrandAvailability"                     ,   this.client.BrandAvailability)
            formData.append("BrandSourcePurchase"                   ,   this.client.BrandSourcePurchase)

            formData.append("JPlan"                                 ,   this.client.JPlan)
            formData.append("Journee"                               ,   this.client.Journee)

            formData.append("facade_image"                          ,   this.client.facade_image)
            formData.append("in_store_image"                        ,   this.client.in_store_image)
            formData.append("facade_image_original_name"            ,   this.client.facade_image_original_name)
            formData.append("in_store_image_original_name"          ,   this.client.in_store_image_original_name)

            formData.append("status"                                ,   this.client.status)
            formData.append("nonvalidated_details"                  ,   this.client.nonvalidated_details)

            formData.append("comment"                               ,   this.client.comment)

            formData.append("start_adding_date"                     ,   this.start_adding_date)
            formData.append("finish_adding_date"                    ,   moment(new Date()).format())

            //

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/store",   formData)
            console.log(res)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Client

                this.client.id                      =   res.data.client.id
                this.client.status                  =   this.client.status
                this.client.nonvalidated_details    =   this.client.nonvalidated_details

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                this.emitter.emit('reSetAdd' , res.data.client)

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

                let facade_image_add             =   document.getElementById("facade_image_add")
                facade_image_add.value           =   ""

                let facade_image_display_add     =   document.getElementById("facade_image_display_add")
                facade_image_display_add.src     =   ""

                //

                let in_store_image_add           =   document.getElementById("in_store_image_add")
                in_store_image_add.value         =   ""

                let in_store_image_display_add   =   document.getElementById("in_store_image_display_add")
                in_store_image_display_add.src   =   ""

                //

                this.client.facade_image                    =   '',
                this.client.in_store_image                  =   '',
                this.client.facade_image_original_name      =   '',
                this.client.in_store_image_original_name    =   '',

                // 
                this.setAddClientAction(null)

                // Client
                this.client.CustomerCode    =   '',

                this.client.CustomerNameE   =   '',
                this.client.CustomerNameA   =   '',
                this.client.Tel             =   '',

                this.client.Address         =   '',
                this.client.Neighborhood    =   '',
                this.client.Landmark        =   '',

                this.client.DistrictNo      =   '',
                this.client.DistrictNameE   =   '',

                this.client.CityNo          =   '',
                this.client.CityNameE       =   '',

                this.client.Latitude        =   '',
                this.client.Longitude       =   '',

                // Type
                this.client.CustomerType            =   '',
                this.client.BrandAvailability       =   '',
                this.client.BrandSourcePurchase     =   '',

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

        getData(client, all_clients) {

            // Set Start Added
            this.start_adding_date  =   moment(new Date()).format()

            //
            this.all_clients    =   all_clients

            this.setCoords(client)
            this.getComboData()  

            // if(this.$isRole("FrontOffice")) {

                this.checkClients()
            // }
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

        async facadeImage() {

            const facade_image  =   document.getElementById("facade_image_add").files[0];

            if(facade_image) {

                this.client.facade_image_original_name      =   facade_image.name
                this.client.facade_image                    =   await this.$compressImage(facade_image)

                //

                let facade_image_base64                     =   await this.$imageToBase64(this.client.facade_image)

                let facade_image_display                    =   document.getElementById("facade_image_display_add")
                this.base64ToImage(facade_image_base64, facade_image_display)
            }
        },

        //

        async inStoreImage() {

            const in_store_image  =   document.getElementById("in_store_image_add").files[0];

            if(in_store_image) {

                this.client.in_store_image_original_name    =   in_store_image.name
                this.client.in_store_image                  =   await this.$compressImage(in_store_image)
                
                //

                let in_store_image_base64                   =   await this.$imageToBase64(this.client.in_store_image)

                let in_store_image_display                  =   document.getElementById("in_store_image_display_add")
                this.base64ToImage(in_store_image_base64, in_store_image_display)
            }
        },

        //     

        base64ToImage(image_base64, image_display_div) {

            this.$base64ToImage(image_base64, image_display_div)
        },

        //

        checkClients() {

            this.close_clients  =   []

            let distance        =   0

            for (let i = 0; i < this.all_clients.length; i++) {

                distance        =   this.getDistance(this.client.Latitude, this.client.Longitude, this.all_clients[i].Latitude, this.all_clients[i].Longitude)

                if(distance <=  this.min_distance) {
                
                    this.close_clients.push(this.all_clients[i])
                }
            }
        },

        getDistance(latitude_1, longitude_1, latitude_2, longitude_2) {

            return this.$map.$setDistanceStraight(latitude_1, longitude_1, latitude_2, longitude_2)
        }

        //
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