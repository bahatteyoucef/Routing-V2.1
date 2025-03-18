<template>

    <!-- Modal -->
    <div class="modal fade" id="modalClientUpdateMap" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Update the Client : {{client.old_CustomerNameE}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3">

                    <form>

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
                                <option     :value="'Hypermarché'">Hypermarché</option>
                                <option     :value="'Supérette'">Supérette</option>
                                <option     :value="'Alimentation Generale'">Alimentation Generale</option>
                                <option     :value="'Grossiste'">Grossiste</option>

                                <option     :value="'LARGE GROCERY'">LARGE GROCERY</option>
                                <option     :value="'SMALL GROCERY'">SMALL GROCERY</option>

                                <option     :value="'Hôtel'">Hôtel</option>
                                <option     :value="'Fast food'">Fast food</option>
                                <option     :value="'Restaurant'">Restaurant</option>

                                <option     :value="'Cafétéria'">Cafétéria</option>

                                <option     :value="'Bureau Tabac'">Bureau Tabac</option>
                                <option     :value="'Cosmetique'">Cosmetique</option>
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
                            <input type="text"              class="form-control"        id="JPlan"           v-model="client.JPlan">
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="Journee"            class="form-label">WorkDay (Journee)</label>

                            <select                         class="form-select"         id="Journee"                 v-model="client.Journee">
                                <option     :value="'Jour 1'">Samedi 1 (Jour 1)</option>
                                <option     :value="'Jour 2'">Dimanche 1 (Jour 2)</option>
                                <option     :value="'Jour 3'">Lundi 1 (Jour 3)</option>
                                <option     :value="'Jour 4'">Mardi 1 (Jour 4)</option>
                                <option     :value="'Jour 5'">Mercredi 1 (Jour 5)</option>
                                <option     :value="'Jour 6'">Jeudi 1 (Jour 6)</option>
                                <option     :value="'Jour 7'">Samedi 2 (Jour 7)</option>
                                <option     :value="'Jour 8'">Dimanche 2 (Jour 8)</option>
                                <option     :value="'Jour 9'">Lundi 2 (Jour 9)</option>
                                <option     :value="'Jour 10'">Mardi 2 (Jour 10)</option>
                                <option     :value="'Jour 11'">Mercredi 2 (Jour 11)</option>
                                <option     :value="'Jour 12'">Jeudi 2 (Jour 12)</option>
                            </select>
                        </div>

                        <!--  -->

                        <div v-if="client.status_original   ==  'validated'">
                            <div v-if="$isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice')" class="mb-3">
                                <label for="status"             class="form-label">Status</label>
                                <select                         class="form-select"         id="status"                 v-model="client.status">
                                    <option value="validated" selected>validated</option>
                                    <option value="nonvalidated" selected>nonvalidated</option>
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
                                    <option value="validated" selected>validated</option>
                                    <option value="nonvalidated">nonvalidated</option>
                                </select>

                                <div v-if="client.status    ==  'nonvalidated'" class="mt-3">
                                    <div class="form-group">
                                        <label      for="nonvalidated_details" class="form-label">NonValidated Details</label>
                                        <textarea   class="form-control" id="nonvalidated_details" rows="3"             v-model="client.nonvalidated_details"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="((client.status_original   ==  'nonvalidated') || (client.status_original   ==  'pending'))">
                            <div v-if="$isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice')" class="mb-3">
                                <label for="status"             class="form-label">Status</label>
                                <select                         class="form-select"         id="status"                 v-model="client.status">
                                    <option value="validated" selected>validated</option>
                                    <option value="pending">pending</option>
                                    <option value="nonvalidated" selected>nonvalidated</option>
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
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <div v-show="client.CustomerCode   ==  ''"     class="mt-1 p-0">
                                <div    id="reader_validate_update" class="scanner_reader w-100"></div>
                            </div>

                            <div v-show="client.CustomerCode   !=  ''"     class="mt-1 p-0">
                                <div    id="result_validate_update"></div>
                            </div>

                            <div v-show="client.CustomerCode   !=  ''"     class="mt-1 p-0">
                                <div    id="customerCode_validate_update_value"     class="text-center">
                                    <span class="">QR Code (CustomerCode) : {{ client.CustomerCode }}</span>
                                </div>
                            </div>

                            <!--  -->

                            <div class="mt-1 mb-1 w-100">
                                <div class="w-100" id="refresh_client_barcode_validate_update_button">
                                    <button type="button" class="btn btn-primary w-100"     @click="setBarCodeReader()">Capture QR Code (CustomerCode)</button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="CustomerBarCode_image_validate_update"   class="form-label">QR Code Image</label>
                            <input type="file"                          class="form-control"        id="CustomerBarCode_image_validate_update"              accept="image/*"    capture     @change="customerBarCodeImage()">
                            <img                                                                    id="CustomerBarCode_image_validate_display_update"      src=""              class="w-100">
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="facade_image_validate_update"    class="form-label">Facade Image (Facade Image)</label>
                            <input type="file"                  class="form-control"    id="facade_image_validate_update"               accept="image/*"    @change="facadeImage()">
                            <img                                                        id="facade_image_validate_display_update"       src=""              class="w-100">
                        </div>

                        <div class="mb-3">
                            <label for="in_store_image_validate_update"  class="form-label">In-Store Image (In-Store Image)</label>
                            <input type="file"                  class="form-control"    id="in_store_image_validate_update"             accept="image/*"    @change="inStoreImage()">
                            <img                                                        id="in_store_image_validate_display_update"     src=""              class="w-100">
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
                                    <th class="text-wrap">CustomerType</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <tr v-for="client in close_clients" :key="client">
                                    <td class="text-wrap">{{client.CustomerNameE}}</td>
                                    <td class="text-wrap">{{client.CustomerNameA}}</td>
                                    <td class="text-wrap">{{client.CustomerType}}</td>
                                </tr>
                            </tbody>
                        </table>

                    </form>

                </div>

                <div class="modal-footer"       style="display: flex; justify-content: space-between;">
                    <div class="left-buttons"   style="display: flex;">
                        <button type="button"   class="btn btn-danger float-left" @click="deleteData()">Delete</button>
                    </div>

                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button"   class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button"   class="btn btn-primary"                             @click="sendData()"                                                 >Confirm</button>
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
                CustomerBarCode_image_validate_updated           :   false,
                facade_image_validate_updated                    :   false,
                in_store_image_validate_updated                  :   false,
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
            min_distance                    :   0.03    ,

            //

            scanner                         :   null
        }
    },

    props : ["id_route_import", "modal_source"],

    computed : {

        ...mapGetters({
            getListeJourneyPlan             :   'journey_plan/getListeJourneyPlan'      ,
            getListeTypeClient              :   'type_client/getListeTypeClient'        ,  
            getListeJournee                 :   'journee/getListeJournee'               ,

            //

            getIsOnline                     :   'internet/getIsOnline'
        }),
    },

    mounted() {

        this.clearData("#modalClientUpdateMap")
    },  

    methods : {

        ...mapActions("client" ,  [
            "setUpdateClientAction"   ,
        ]),

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

            formData.append("CustomerBarCode_image_validate_updated"         ,   this.client.CustomerBarCode_image_validate_updated)
            formData.append("facade_image_validate_updated"                  ,   this.client.facade_image_validate_updated)
            formData.append("in_store_image_validate_updated"                ,   this.client.in_store_image_validate_updated)

            formData.append("CustomerBarCode_image"                 ,   this.client.CustomerBarCode_image)
            formData.append("facade_image"                          ,   this.client.facade_image)
            formData.append("in_store_image"                        ,   this.client.in_store_image)

            formData.append("CustomerBarCode_image_original_name"   ,   this.client.CustomerBarCode_image_original_name)
            formData.append("facade_image_original_name"            ,   this.client.facade_image_original_name)
            formData.append("in_store_image_original_name"          ,   this.client.in_store_image_original_name)

            formData.append("status"                        ,   this.client.status)
            formData.append("nonvalidated_details"          ,   this.client.nonvalidated_details)

            formData.append("comment"                       ,   this.client.comment)

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.client.id+"/update",   formData)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                if(this.modal_source    ==  "CustomerCode") {

                    this.emitter.emit("updateDoublesCustomerCodeMap"    , this.client)
                }

                if(this.modal_source    ==  "CustomerNameE") {

                    this.emitter.emit("updateDoublesCustomerNameEMap"   , this.client)
                }

                if(this.modal_source    ==  "Tel") {

                    this.emitter.emit("updateDoublesTelMap"             , this.client)
                }

                if(this.modal_source    ==  "GPS") {

                    this.emitter.emit("updateDoublesGPSMap"             , this.client)
                }
            
                // Close Modal
                this.$hideModal("modalClientUpdateMap")
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}            
        },

        async deleteData() {

            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.id_route_import+"/clients/"+this.client.id+"/delete",   null)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                if(this.modal_source    ==  "CustomerCode") {

                    this.emitter.emit("deleteDoublesCustomerCodeMap"   , this.client)
                }

                if(this.modal_source    ==  "CustomerNameE") {

                    this.emitter.emit("deleteDoublesCustomerNameEMap"  , this.client)
                }

                if(this.modal_source    ==  "Tel") {

                    this.emitter.emit("deleteDoublesTelMap"            , this.client)
                }

                if(this.modal_source    ==  "GPS") {

                    this.emitter.emit("deleteDoublesGPSMap"            , this.client)
                }

                // Close Modal
                this.$hideModal("modalClientUpdateMap")
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

                if(this.scanner) {

                    this.scanner.clear().then(_ => {

                    }).catch(error => {

                    });
                }

                //

                let CustomerBarCode_image_validate_update                    =   document.getElementById("CustomerBarCode_image_validate_update")
                CustomerBarCode_image_validate_update.value                  =   ""

                let CustomerBarCode_image_validate_display_update            =   document.getElementById("CustomerBarCode_image_validate_display_update")
                CustomerBarCode_image_validate_display_update.src            =   ""

                //

                let facade_image_validate_update                             =   document.getElementById("facade_image_validate_update")
                facade_image_validate_update.value                           =   ""

                let facade_image_validate_display_update                     =   document.getElementById("facade_image_validate_display_update")
                facade_image_validate_display_update.src                     =   ""

                //

                let in_store_image_validate_update                           =   document.getElementById("in_store_image_validate_update")
                in_store_image_validate_update.value                         =   ""

                let in_store_image_validate_display_update                   =   document.getElementById("in_store_image_validate_display_update")
                in_store_image_validate_display_update.src                   =   ""

                //

                this.client.CustomerBarCode_image                   =   '',
                this.client.facade_image                            =   '',
                this.client.in_store_image                          =   '',

                this.client.CustomerBarCode_image_original_name     =   '',
                this.client.facade_image_original_name              =   '',
                this.client.in_store_image_original_name            =   '',

                this.client.facade_image_validate_updated                    =   false,
                this.client.in_store_image_validate_updated                  =   false

                // 
                this.setUpdateClientAction(null)

                // Client
                this.client.id                                      =   '',

                // Slide 1
                this.client.CustomerCode                            =   '',

                // Slide 2
                this.client.CustomerBarCode_image                   =   '',
                this.client.CustomerBarCode_image_original_name     =   '',

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

                // Slide 17
                this.client.status_original                         =   '',
                this.client.status                                  =   '',
                this.client.nonvalidated_details                    =   '', 

                // Slide 18
                this.client.facade_image                            =   '',
                this.client.facade_image_original_name              =   '',

                // Slide 19   
                this.client.in_store_image                          =   '',
                this.client.in_store_image_original_name            =   '',
                this.client.comment                                 =   ''

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

        async getData(client) {

            const res           =   await this.$callApi("post", "/route_import/"+this.id_route_import+"/clients", null)
            this.all_clients    =   res.data.clients            

            this.getClientData(client)  
            this.getComboData()  

            this.checkClients()
        },

        async getClientData(client) {

            //

            this.client.id                                      =   client.id

            this.client.CustomerCode                            =   client.CustomerCode

            this.client.old_CustomerNameE                       =   client.CustomerNameE

            this.client.CustomerNameE                           =   client.CustomerNameE
            this.client.CustomerNameA                           =   client.CustomerNameA
            this.client.Latitude                                =   client.Latitude
            this.client.Longitude                               =   client.Longitude

            this.client.Address                                 =   client.Address
            this.client.Neighborhood                            =   client.Neighborhood
            this.client.Landmark                                =   client.Landmark

            this.client.DistrictNo                              =   client.DistrictNo
            this.client.DistrictNameE                           =   client.DistrictNameE

            await this.getCites()

            this.client.CityNo                                  =   client.CityNo
            this.client.CityNameE                               =   client.CityNameE

            this.client.Tel                                     =   client.Tel

            this.client.CustomerType                            =   client.CustomerType
            this.client.BrandAvailability                       =   client.BrandAvailability
            this.client.BrandSourcePurchase                     =   client.BrandSourcePurchase

            this.client.JPlan                                   =   client.JPlan

            this.client.Journee                                 =   client.Journee

            this.client.status                                  =   client.status
            this.client.status_original                         =   client.status
            this.client.nonvalidated_details                    =   client.nonvalidated_details

            this.client.comment                                 =   client.comment

            this.client.CustomerBarCode_image                   =   client.CustomerBarCode_image
            this.client.facade_image                            =   client.facade_image
            this.client.in_store_image                          =   client.in_store_image

            this.client.CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name
            this.client.facade_image_original_name              =   client.facade_image_original_name
            this.client.in_store_image_original_name            =   client.in_store_image_original_name

            // 
            this.$createFile(client.CustomerBarCode_image_original_name     ,   "CustomerBarCode_image_validate_update")
            this.$createFile(client.facade_image_original_name              ,   "facade_image_validate_update")
            this.$createFile(client.in_store_image_original_name            ,   "in_store_image_validate_update")

            // 
            let CustomerBarCode_image_validate_display_update    =   document.getElementById("CustomerBarCode_image_validate_display_update")
            let facade_image_validate_display_update             =   document.getElementById("facade_image_validate_display_update")
            let in_store_image_validate_display_update           =   document.getElementById("in_store_image_validate_display_update")

            CustomerBarCode_image_validate_display_update.src    =   "/uploads/clients/"+client.id+"/"+client.CustomerBarCode_image
            facade_image_validate_display_update.src             =   "/uploads/clients/"+client.id+"/"+client.facade_image
            in_store_image_validate_display_update.src           =   "/uploads/clients/"+client.id+"/"+client.in_store_image
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
        },

        //

        async customerBarCodeImage() {

            const CustomerBarCode_image  =   document.getElementById("CustomerBarCode_image_validate_update").files[0];

            if(CustomerBarCode_image) {

                this.client.CustomerBarCode_image_validate_updated            =   true

                // if(this.getIsOnline) {

                    this.client.CustomerBarCode_image_original_name      =   CustomerBarCode_image.name
                    this.client.CustomerBarCode_image                    =   await this.$compressImage(CustomerBarCode_image)

                    //

                    let CustomerBarCode_image_base64                     =   await this.$imageToBase64(this.client.CustomerBarCode_image)

                    let CustomerBarCode_image_display                    =   document.getElementById("CustomerBarCode_image_validate_display_update")
                    this.base64ToImage(CustomerBarCode_image_base64, CustomerBarCode_image_display)
                // }

                // else {

                //     this.client.CustomerBarCode_image_original_name      =   CustomerBarCode_image.name
                //     this.client.CustomerBarCode_image                    =   await this.$compressImage(CustomerBarCode_image)

                //     //

                //     this.client.CustomerBarCode_image                    =   await this.$imageToBase64(this.client.CustomerBarCode_image)

                //     let CustomerBarCode_image_display                    =   document.getElementById("CustomerBarCode_image_validate_display_update")
                //     this.base64ToImage(this.client.CustomerBarCode_image, CustomerBarCode_image_display)
                // }
            }

            else {

                this.client.CustomerBarCode_image_original_name     =   ""
                this.client.CustomerBarCode_image                   =   ""

                this.client.CustomerBarCode_image_validate_updated           =   true

                const CustomerBarCode_image_validate_display_update          =   document.getElementById("CustomerBarCode_image_validate_display_update")

                if(CustomerBarCode_image_validate_display_update) {

                    CustomerBarCode_image_validate_display_update.src            =   ""
                }
            }
        },

        //

        async facadeImage() {

            const facade_image  =   document.getElementById("facade_image_validate_update").files[0];

            if(facade_image) {

                this.client.facade_image_validate_updated            =   true

                this.client.facade_image_original_name      =   facade_image.name
                this.client.facade_image                    =   await this.$compressImage(facade_image)

                //

                let facade_image_base64                     =   await this.$imageToBase64(this.client.facade_image)

                let facade_image_display                    =   document.getElementById("facade_image_validate_display_update")
                this.base64ToImage(facade_image_base64, facade_image_display)
            }

            else {

                this.client.facade_image_original_name      =   ""
                this.client.facade_image                    =   ""

                this.client.facade_image_validate_updated            =   true

                const facade_image_validate_display_update          =   document.getElementById("facade_image_validate_display_update")

                if(facade_image_validate_display_update) {

                    facade_image_validate_display_update.src            =   ""
                }
            }
        },

        //

        async inStoreImage() {

            const in_store_image  =   document.getElementById("in_store_image_validate_update").files[0];

            if(in_store_image) {

                this.client.in_store_image_validate_updated          =   true

                this.client.in_store_image_original_name    =   in_store_image.name
                this.client.in_store_image                  =   await this.$compressImage(in_store_image)
                
                //

                let in_store_image_base64                   =   await this.$imageToBase64(this.client.in_store_image)

                let in_store_image_display                  =   document.getElementById("in_store_image_validate_display_update")
                this.base64ToImage(in_store_image_base64, in_store_image_display)
            }

            else {

                this.client.in_store_image_original_name    =   ""
                this.client.in_store_image                  =   ""

                this.client.in_store_image_validate_updated          =   true

                const in_store_image_validate_display_update         =   document.getElementById("in_store_image_validate_display_update")

                if(in_store_image_validate_display_update) {

                    in_store_image_validate_display_update.src           =   ""
                }
            }
        },

        //     

        base64ToImage(image_base64, image_display_div) {

            this.$base64ToImage(image_base64, image_display_div)
        },

        //

        async setBarCodeReader() {

            const reader_validate_update    =   document.getElementById('reader_validate_update')

            // 
            this.client.CustomerCode    =   ""

            if(reader_validate_update) {

                reader_validate_update.style.display        =   "block";

                //
                this.scanner = new Html5QrcodeScanner('reader_validate_update', {

                    qrbox   : {
                        width   : 250,
                        height  : 250,
                    },

                    fps     : 20
                });

                try {
                    await this.scanner.render(this.success, this.error);
                } catch (error) {
                    console.error('Error rendering scanner:', error);
                }
            }
        },

        success(result) {
             
            // 
            if(this.$isValidForFileName(result)) {

                // 
                this.client.CustomerCode    =   result
            }

            else {

                // 
                this.client.CustomerCode    =   ""
                this.$showErrors("Error !"  ,   ["Votre code-barres contient des caractères interdits : / \ : * ? \" < > | &; (espace)"])
            }

            this.scanner.clear();

            document.getElementById('reader_validate_update').style.display =   "none"
            // Removes reader_validate_update element from DOM since no longer needed 
        },

        error(err) {

            // Prints any errors to the console
            console.error("");
        },
    },
};

</script>