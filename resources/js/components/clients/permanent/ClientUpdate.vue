<template>

    <div class="mt-3">

        <div class="page-header mb-2">
            <div class="container w-100">
                <h3 class="page-title">
                        Modifier le Client : {{ client.old_CustomerNameE }} 
                </h3>
            </div>
        </div>

        <div class="container"  style="height : 58vh; overflow : auto;">

            <form>

                <div class="slideshow-container">

                    <div class="mySlides slide_1">
                        <label for="CustomerCode"       class="form-label">Detecter la Position Actuel <button class="btn btn-sm" @click.prevent="showPositionOnMap('show_map')"><i class="mdi mdi-reload"></i></button></label>
                        <p class="text-secondary text-small mb-1">Latitude : {{ client.Latitude }}</p>
                        <p class="text-secondary text-small mb-1">Longitude : {{ client.Longitude }}</p>

                        <div id="show_map" style="width: 100%; height: 200px;"></div>

                        <hr />

                        <h5>Clients a Proximité</h5>

                        <hr />

                        <table class="table table-bordered mt-1">
                            <thead>
                                <tr>
                                    <th class="text-wrap">Acheteur</th>
                                    <th class="text-wrap">Raison Social</th>
                                    <!-- <th class="text-wrap">Tél</th> -->
                                    <th class="text-wrap">Type</th>
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

                    </div>

                    <div class="mySlides slide_2">
                        <div v-show="client.CustomerCode   ==  ''"     class="mt-1 p-0">
                            <div    id="reader" class="scanner_reader w-100"></div>
                        </div>

                        <div v-show="client.CustomerCode   !=  ''"     class="mt-1 p-0">
                            <div    id="result"></div>
                        </div>

                        <div v-show="client.CustomerCode   !=  ''"     class="mt-1 p-0">
                            <div    id="customerCode_value"              class="text-center">
                                <span class="">QR Code : {{ client.CustomerCode }}</span>
                            </div>
                        </div>

                        <!--  -->

                        <div class="mt-1 mb-1 w-100">
                            <div class="w-100" id="refresh_client_barcode_button">
                                <button type="button" class="btn btn-primary w-100"     @click="setBarCodeReader()">Capturer QR Code</button>
                            </div>
                        </div>
                    </div>

                    <div class="mySlides slide_3">
                        <label for="CustomerBarCode_image_update"   class="form-label">QR Code Image</label>
                        <input type="file"                          class="form-control"    id="CustomerBarCode_image_update"                   accept="image/*"    capture     @change="customerBarCodeImage()">
                        <img                                                                id="CustomerBarCode_image_display_update"           src=""              class="w-100">
                    </div>

                    <div class="mySlides slide_4">
                        <label for="CustomerNameE"      class="form-label">Nom et Prénom de l'Acheteur</label>
                        <input type="text"              class="form-control"        id="CustomerNameE"          v-model="client.CustomerNameE">
                    </div>

                    <div class="mySlides slide_5">
                        <label for="CustomerNameA"      class="form-label">Raison Sociale</label>
                        <input type="text"              class="form-control"        id="CustomerNameA"          v-model="client.CustomerNameA">
                    </div>

                    <div class="mySlides slide_6">
                        <label for="Tel"                class="form-label">Téléphone</label>
                        <input type="text"              class="form-control"        id="Tel"                    v-model="client.Tel">
                    </div>

                    <div class="mySlides slide_7">
                        <label for="Address"            class="form-label">Adresse</label>
                        <input type="text"              class="form-control"        id="Address"                v-model="client.Address">
                    </div>

                    <div class="mySlides slide_8">
                        <label for="Neighborhood"       class="form-label">Quartier</label>
                        <input type="text"              class="form-control"        id="Neighborhood"           v-model="client.Neighborhood">
                    </div>

                    <div class="mySlides slide_9">
                        <label for="Landmark"           class="form-label">Point de Repere</label>
                        <textarea                       class="form-control"        id="Landmark"   rows="3"    v-model="client.Landmark"></textarea>
                    </div>

                    <div class="mySlides slide_10">
                        <label for="DistrictNo"         class="form-label">Willaya</label>
                        <select                         class="form-select"         id="DistrictNo"             v-model="client.DistrictNo"     @change="getCites()">
                            <option v-for="willaya in willayas" :key="willaya.DistrictNo" :value="willaya.DistrictNo">{{willaya.DistrictNo}}- {{willaya.DistrictNameE}}</option>
                        </select>
                    </div>

                    <div class="mySlides slide_11">
                        <label for="CityNo"             class="form-label">Commune</label>
                        <select                         class="form-select"         id="CityNo"                 v-model="client.CityNo">
                            <option v-for="cite in cites" :key="cite.CITYNO" :value="cite.CITYNO">{{cite.CITYNO}}- {{cite.CityNameE}}</option>
                        </select>
                    </div>

                    <div class="mySlides slide_12">
                        <label for="text"               class="form-label">Type de Magasin</label>
                        <select                         class="form-select"         id="CustomerType"                 v-model="client.CustomerType">
                            <option     :value="'Superette'">Superette</option>
                            <option     :value="'Alimentation General'">Alimentation General</option>
                            <option     :value="'Grossiste'">Grossiste</option>
                        </select>
                    </div>

                    <div class="mySlides slide_13">
                        <label for="text"               class="form-label">Disponibilité Produits</label>
                        <select                         class="form-select"         id="BrandAvailability"                 v-model="client.BrandAvailability">
                            <option     value="0">No</option>
                            <option     value="1">Yes</option>
                        </select>
                    </div>

                    <div class="mySlides slide_14">
                        <label for="text"               class="form-label">Source d'Achat</label>
                        <select                         class="form-select"         id="BrandSourcePurchase"                 v-model="client.BrandSourcePurchase">
                            <option     value="Distribution Direct">Distribution Direct</option>
                            <option     value="Grossiste Fixe">Grossiste Fixe</option>
                            <option     value="Grossiste Mobile">Grossiste Mobile</option>
                            <option     value="Multi Source">Multi Source</option>
                            <option     value="Pas d'achat">Pas d'achat</option>
                        </select>
                    </div>

                    <div class="mySlides slide_15">
                        <label for="JPlan"              class="form-label">Nom de Vendeur</label>
                        <input type="text"              class="form-control"        id="JPlan"           v-model="client.JPlan">
                    </div>

                    <div class="mySlides slide_16">
                        <label for="Journee"            class="form-label">Journee</label>
                        <input type="text"              class="form-control"        id="Journee"           v-model="client.Journee">
                    </div>

                    <div class="mySlides slide_17">
                        <div v-if="client.status_original   ==  'validated'">
                            <!--
                            <label for="status"             class="form-label">Status Client</label>
                            <select                         class="form-select"         id="status"                 v-model="client.status">
                                <option value="pending" selected>en Attente</option>
                                <option value="nonvalidated">Refusé</option>
                            </select>

                            <div v-if="client.status    ==  'nonvalidated'" class="mt-3">
                                <div class="form-group">
                                    <label      for="nonvalidated_details" class="form-label">Details Refus</label>
                                    <textarea   class="form-control" id="nonvalidated_details" rows="3"             v-model="client.nonvalidated_details"></textarea>
                                </div>
                            </div>
                            -->

                            <label for="status"             class="form-label">Status Client</label>
                            <select                         class="form-select"         id="status"                 v-model="client.status">
                                <option value="validated" selected>validé</option>
                            </select>
                        </div>

                        <!--  -->

                        <div v-if="((client.status_original   ==  'nonvalidated') || (client.status_original   ==  'pending'))">
                            <label for="status"             class="form-label">Status Client</label>
                            <select                         class="form-select"         id="status"                 v-model="client.status">
                                <option value="pending" selected>en Attente</option>
                                <option value="nonvalidated">Refusé</option>
                            </select>

                            <div v-if="client.status    ==  'nonvalidated'" class="mt-3">
                                <div class="form-group">
                                    <label      for="nonvalidated_details" class="form-label">Details Refus</label>
                                    <textarea   class="form-control" id="nonvalidated_details" rows="3"             v-model="client.nonvalidated_details"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mySlides slide_18">
                        <label for="facade_image_update"    class="form-label">Image Facade</label>
                        <input type="file"                  class="form-control"    id="facade_image_update"               accept="image/*"    capture  @change="facadeImage()">
                        <img                                                        id="facade_image_display_update"       src=""                       class="w-100">
                    </div>

                    <div class="mySlides slide_19">
                        <label for="in_store_image_update"  class="form-label">Image In-Store</label>
                        <input type="file"                  class="form-control"    id="in_store_image_update"             accept="image/*"    capture  @change="inStoreImage()">
                        <img                                                        id="in_store_image_display_update"     src=""                       class="w-100">
                    </div>

                    <div class="mySlides slide_20">
                        <label      for="comment">Commentaire</label>
                        <textarea   class="form-control"    id="comment"    rows="3"    v-model="client.comment"></textarea>
                    </div>

                </div>

            </form>

        </div>

        <!--  -->

        <div class="container position-absolute start-0 w-100"          style="bottom: 65px;">
            <div class="row justify-content-center">
                <div class="col-6 mt-3">
                    <button v-if="slideIndex    >   1"                  type="button" class="btn btn-secondary w-100"   @click="plusSlides(-1)">Precedent</button>
                </div>

                <div class="col-6 mt-3">
                    <button v-if="slideIndex    <   total_questions"     type="button" class="btn btn-primary w-100"     @click="plusSlides(1)">Suivant</button>
                </div>
            </div>
        </div>

        <div class="container position-absolute start-0 w-100 mb-3"     style="bottom: 0px;">
            <div class="row justify-content-center">
                <div class="col mt-3">
                    <button v-if="slideIndex  ==  total_questions"      type="button" class="btn btn-primary w-100"     @click="sendData()">Confirmer</button>
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

                // Images   
                CustomerBarCode_image                   :   '',
                facade_image                            :   '',
                in_store_image                          :   '',

                CustomerBarCode_image_original_name     :   '',
                facade_image_original_name              :   '',
                in_store_image_original_name            :   '',

                //  
                CustomerBarCode_image_updated           :   false,
                facade_image_updated                    :   false,
                in_store_image_updated                  :   false,

                // Client
                id                  :   '',

                CustomerCode        :   '',

                old_CustomerNameE   :   '',

                CustomerNameE       :   '',
                CustomerNameA       :   '',
                Tel                 :   '',

                Address             :   '',
                Neighborhood        :   '',
                Landmark            :   '',

                DistrictNo          :   '',
                DistrictNameE       :   '',

                CityNo              :   '',
                CityNameE           :   '',

                Latitude            :   '',
                Longitude           :   '',

                // Type
                CustomerType        :   '',
                BrandAvailability   :   '',
                BrandSourcePurchase :   '',

                // Journey Plan
                JPlan               :   '',
                Journee             :   '',

                // 
                status                  :   '',
                status_original         :   '',
                nonvalidated_details    :   '', 

                // Comment
                comment                 :   ''
            },

            willayas                        :   []      ,
            cites                           :   []      ,

            // 
            liste_journey_plan              :   []      ,
            liste_journee                   :   []      ,
            liste_type_client               :   []      ,

            //

            all_clients                     :   []      ,
            close_clients                   :   []      ,
            min_distance                    :   0.03    ,

            //

            scanner                         :   null    ,

            //

            point_is_inside_user_polygons   :   false   ,

            //

            slideIndex                      :   1       ,

            //

            total_questions                 :   20
        }
    },

    computed : {

        ...mapGetters({
            getListeJourneyPlan             :   'journey_plan/getListeJourneyPlan'      ,
            getListeTypeClient              :   'type_client/getListeTypeClient'        ,  
            getListeJournee                 :   'journee/getListeJournee'               ,

            getAddClient                    :   'client/getAddClient'                   ,
            getUpdateClient                 :   'client/getUpdateClient'                ,

            //

            getUser                         :   'authentification/getUser'              
        }),
    },

    beforeUnmount() {

        if(this.scanner) {

            this.scanner.clear().then(_ => {

            }).catch(error => {

            });
        }
    },

    async mounted() {

        //
        this.slideIndex     =   this.$showSlides(this.slideIndex, this.slideIndex);

        //
        await this.getData()
    },  

    methods : {

        ...mapActions("client" ,  [
            "setUpdateClientAction"   ,
        ]),

        //

        async sendData() {

            this.$showLoadingPage()

            if(typeof this.willayas != "undefined") {

                // Set Client
                this.client.DistrictNameE   =   this.getDistrictNameE(this.client.DistrictNo)
            }

            if(typeof this.cites != "undefined") {

                // Set 
                this.client.CityNameE       =   this.getCityNameE(this.client.CityNo)
            }

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

            formData.append("CustomerBarCode_image_updated"         ,   this.client.CustomerBarCode_image_updated)
            formData.append("facade_image_updated"                  ,   this.client.facade_image_updated)
            formData.append("in_store_image_updated"                ,   this.client.in_store_image_updated)

            formData.append("CustomerBarCode_image"                 ,   this.client.CustomerBarCode_image)
            formData.append("facade_image"                          ,   this.client.facade_image)
            formData.append("in_store_image"                        ,   this.client.in_store_image)

            formData.append("CustomerBarCode_image_original_name"   ,   this.client.CustomerBarCode_image_original_name)
            formData.append("facade_image_original_name"            ,   this.client.facade_image_original_name)
            formData.append("in_store_image_original_name"          ,   this.client.in_store_image_original_name)

            formData.append("status"                                ,   this.client.status)
            formData.append("nonvalidated_details"                  ,   this.client.nonvalidated_details)

            formData.append("comment"                               ,   this.client.comment)

            if(this.$connectedToInternet) {

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.client.id+"/update",   formData)

                if(res.status===200){

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Feedback
                    this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                    // Send Client
                    // this.emitter.emit('reSetUpdate' , this.client)

                    // Go Back
                    this.$goBack()
                }
                
                else{

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)
                }
            }

            else {

                // Add in indexedDB
                await this.$indexedDB.$setUpdatedClients(this.client, this.client.id_route_import)

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Client
                // this.emitter.emit('reSetUpdate' , this.client)

                // Go Back
                this.$goBack()
            }
        },

        async deleteData() {

            this.$showLoadingPage()

            if(this.$connectedToInternet) {

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.client.id+"/delete",   null)

                if(res.status===200){

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Feedback
                    this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                    // Send Client
                    // this.emitter.emit('reSetDelete' , this.client)

                    // Go Back
                    this.$goBack()
                }
                
                else{

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)
                }
            }

            else {

                // Add in indexedDB
                await this.$indexedDB.$setDeletedClients(this.client, this.client.id_route_import)

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Client
                // this.emitter.emit('reSetDelete' , this.client)

                // Go Back
                this.$goBack()
            }
        },

        async validateData() {

            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.client.id+"/validate",   null)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                // Send Client
                // this.emitter.emit('reSetValidate' , this.client)

                // Go Back
                this.$goBack()
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
                this.unsetJoursGetData()

                // 
                this.setUpdateClientAction(null)

                // Client
                this.client.CustomerCode        =   '',

                this.client.old_CustomerNameE   =   '',

                this.client.CustomerNameE       =   '',
                this.client.CustomerNameA       =   '',
                this.client.Tel                 =   '',

                this.client.Address             =   '',
                this.client.Neighborhood        =   '',
                this.client.Landmark            =   '',

                this.client.DistrictNo          =   '',
                this.client.DistrictNameE       =   '',

                this.client.CityNo              =   '',
                this.client.CityNameE           =   '',

                this.client.Latitude            =   '',
                this.client.Longitude           =   '',

                // Type
                this.client.CustomerType        =   '',
                this.client.BrandAvailability   =   '',
                this.client.BrandSourcePurchase =   '',

                // Journey Plan
                this.client.JPlan               =   '',

                this.client.status              =   '',

                this.willayas                   =   []  ,
                this.cites                      =   []  ,

                this.all_clients                =   []  ,
                this.close_clients              =   []

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

        async getData() {

            if(this.$connectedToInternet) {

                const res           =   await this.$callApi("post"  ,   "/route/obs/route_import/"+this.$route.params.id_route_import+"/details",   null)
                this.all_clients    =   res.data.route_import.data

                //

                // await this.showPositionOnMap('show_map')
            }

            else {

                this.route_import   =   await this.$indexedDB.$getRouteImport(this.$route.params.id_route_import)
                this.all_clients    =   this.route_import.clients
            }

            //

            await this.getClientData()  
            await this.getComboData()  

            if(this.$connectedToInternet) {

                this.checkClients()
            }
        },

        async getClientData() {

            if(this.$connectedToInternet) {

                const res                                           =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.$route.params.id_client+"/show",   null)
                let client                                          =   res.data

                console.log(client)

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

                this.client.CityNo                                  =   client.CityNo

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
                this.$createFile(client.CustomerBarCode_image_original_name     ,   "CustomerBarCode_image_update")
                this.$createFile(client.facade_image_original_name              ,   "facade_image_update")
                this.$createFile(client.in_store_image_original_name            ,   "in_store_image_update")

                // 
                let CustomerBarCode_image_display_update    =   document.getElementById("CustomerBarCode_image_display_update")
                let facade_image_display_update             =   document.getElementById("facade_image_display_update")
                let in_store_image_display_update           =   document.getElementById("in_store_image_display_update")

                CustomerBarCode_image_display_update.src    =   "/uploads/clients/"+client.id+"/"+client.CustomerBarCode_image
                facade_image_display_update.src             =   "/uploads/clients/"+client.id+"/"+client.facade_image
                in_store_image_display_update.src           =   "/uploads/clients/"+client.id+"/"+client.in_store_image

                this.setJoursGetData(client)

                await this.getCites()
            }

            else {

                let client                      =   this.getUpdateClient

                this.client                     =   client
                this.client.status_original     =   client.status

                // 
                this.$createFile(client.CustomerBarCode_image_original_name     ,   "CustomerBarCode_image_update")
                this.$createFile(client.facade_image_original_name              ,   "facade_image_update")
                this.$createFile(client.in_store_image_original_name            ,   "in_store_image_update")

                // 
                let CustomerBarCode_image_display_update    =   document.getElementById("CustomerBarCode_image_display_update")
                let facade_image_display_update             =   document.getElementById("facade_image_display_update")
                let in_store_image_display_update           =   document.getElementById("in_store_image_display_update")

                this.base64ToImage(this.client.CustomerBarCode_image            ,   CustomerBarCode_image_display_update)            
                this.base64ToImage(this.client.facade_image                     ,   facade_image_display_update)            
                this.base64ToImage(this.client.in_store_image                   ,   in_store_image_display_update)            

                // 
                this.setJoursGetData(client)

                // 
                await this.getCites()
            }
        },

        async getComboData() {

            if(this.$connectedToInternet) {

                const res_3                     =   await this.$callApi("post"  ,   "/rtm_willayas"         ,   null)
                this.willayas                   =   res_3.data
            }

            else {

                this.willayas                   =   await this.$indexedDB.$getWillayas()
            }
        },

        async getCites() {

            if(this.$connectedToInternet) {

                // Show Loading Page
                this.$showLoadingPage()

                const res_3                     =   await this.$callApi("post"  ,   "/rtm_willayas/"+this.client.DistrictNo+"/rtm_cites"         ,   null)
                this.cites                      =   res_3.data

                // Hide Loading Page
                this.$hideLoadingPage()
            }

            else {

                // Show Loading Page
                this.$showLoadingPage()

                let willaya                         =   await this.$indexedDB.$getWillaya(this.client.DistrictNo)
                console.log(willaya)

                if(willaya) {

                    this.cites                      =   willaya.cites
                }

                // Hide Loading Page
                this.$hideLoadingPage()
            }
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
        },

        //

        checkClients() {

            this.close_clients  =   []

            let distance        =   0

            for (let i = 0; i < this.all_clients.length; i++) {

                if(this.all_clients[i].id   !=  this.client.id) {

                    distance        =   this.getDistance(this.client.Latitude, this.client.Longitude, this.all_clients[i].Latitude, this.all_clients[i].Longitude)

                    if(distance <=  this.min_distance) {
                    
                        console.log(distance)

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

            const CustomerBarCode_image  =   document.getElementById("CustomerBarCode_image_update").files[0];

            console.log(CustomerBarCode_image)

            if(CustomerBarCode_image) {

                this.client.CustomerBarCode_image_updated            =   true

                if(this.$connectedToInternet) {

                    this.client.CustomerBarCode_image_original_name      =   CustomerBarCode_image.name
                    this.client.CustomerBarCode_image                    =   await this.$compressImage(CustomerBarCode_image)

                    //

                    let CustomerBarCode_image_base64                     =   await this.$imageToBase64(this.client.CustomerBarCode_image)

                    let CustomerBarCode_image_display                    =   document.getElementById("CustomerBarCode_image_display_update")
                    this.base64ToImage(CustomerBarCode_image_base64, CustomerBarCode_image_display)
                }

                else {

                    this.client.CustomerBarCode_image_original_name      =   CustomerBarCode_image.name
                    this.client.CustomerBarCode_image                    =   await this.$compressImage(CustomerBarCode_image)

                    //

                    this.client.CustomerBarCode_image                    =   await this.$imageToBase64(this.client.CustomerBarCode_image)

                    let CustomerBarCode_image_display                    =   document.getElementById("CustomerBarCode_image_display_update")
                    this.base64ToImage(this.client.CustomerBarCode_image, CustomerBarCode_image_display)
                }
            }

            else {

                this.client.CustomerBarCode_image_updated            =   false
            }
        },

        //

        async facadeImage() {

            const facade_image  =   document.getElementById("facade_image_update").files[0];

            console.log(facade_image)

            if(facade_image) {

                this.client.facade_image_updated            =   true

                if(this.$connectedToInternet) {

                    this.client.facade_image_original_name      =   facade_image.name
                    this.client.facade_image                    =   await this.$compressImage(facade_image)

                    //

                    let facade_image_base64                     =   await this.$imageToBase64(this.client.facade_image)

                    let facade_image_display                    =   document.getElementById("facade_image_display_update")
                    this.base64ToImage(facade_image_base64, facade_image_display)
                }

                else {

                    this.client.facade_image_original_name      =   facade_image.name
                    this.client.facade_image                    =   await this.$compressImage(facade_image)

                    //

                    this.client.facade_image                    =   await this.$imageToBase64(this.client.facade_image)

                    let facade_image_display                    =   document.getElementById("facade_image_display_update")
                    this.base64ToImage(this.client.facade_image, facade_image_display)
                }
            }

            else {

                this.client.facade_image_updated            =   false
            }
        },

        //

        async inStoreImage() {

            const in_store_image  =   document.getElementById("in_store_image_update").files[0];

            if(in_store_image) {

                this.client.in_store_image_updated      =   true

                if(this.$connectedToInternet) {

                    this.client.in_store_image_original_name    =   in_store_image.name
                    this.client.in_store_image                  =   await this.$compressImage(in_store_image)
                    
                    //

                    let in_store_image_base64                   =   await this.$imageToBase64(this.client.in_store_image)

                    let in_store_image_display                  =   document.getElementById("in_store_image_display_update")
                    this.base64ToImage(in_store_image_base64, in_store_image_display)
                }

                else {
    
                    this.client.in_store_image_original_name    =   in_store_image.name
                    this.client.in_store_image                  =   await this.$compressImage(in_store_image)
                    
                    //

                    this.client.in_store_image                  =   await this.$imageToBase64(this.client.in_store_image)

                    let in_store_image_display                  =   document.getElementById("in_store_image_display_update")
                    this.base64ToImage(this.client.in_store_image, in_store_image_display)
                }
            }

            else {

                this.client.in_store_image_updated      =   false
            }
        },

        //     

        base64ToImage(image_base64, image_display_div) {

            this.$base64ToImage(image_base64, image_display_div)
        },

        //

        async showPositionOnMap(map_id) {

            if(this.$connectedToInternet) {

                let position                =   await this.$currentPosition()

                console.log(position)

                this.client.Latitude        =   position.coords.latitude
                this.client.Longitude       =   position.coords.longitude

                this.$showPositionOnMap(map_id, this.client.Latitude, this.client.Longitude, this.getUser.user_territories)
            }
        },

        //

        setBarCodeReader() {

            const reader    =   document.getElementById('reader')

            // 
            this.client.CustomerCode    =   ""

            if(reader) {

                reader.style.display        =   "block";

                this.scanner = new Html5QrcodeScanner('reader', { 

                        // Scanner will be initialized in DOM inside element with id of 'reader'
                        qrbox: {
                            width: 250,
                            height: 250,
                        },  // Sets dimensions of scanning box (set relative to reader element width)
                        fps: 20, // Frames per second to attempt a scan
                    });

                // 
                this.scanner.render(this.success, this.error);
            }
        },

        success(result) {
             
            // 
            this.client.CustomerCode    =   result

            this.scanner.clear();

            document.getElementById('reader').style.display =   "none"
            // Removes reader element from DOM since no longer needed 
        },

        error(err) {

            // Prints any errors to the console
            console.error("");
        },

        //

        plusSlides(current_slide) {

            // Go Next
            if(current_slide    ==  1) {

                // let validation  =   this.validationCompetitorAnalysis(this.slideIndex  -   1)

                // if(validation   ==  true)  {

                    this.slideIndex     =   this.$plusSlides(this.slideIndex += current_slide, this.slideIndex)
                // }

                // else {

                //     this.$showErrors("Error !"  ,   ["Quantity must be superieur to 0", "Price must be superieur or equal to 0"])
                //     return false;
                // }
            }

            else {

                this.slideIndex     =   this.$plusSlides(this.slideIndex += current_slide, this.slideIndex)
            }
        },

        currentSlide(current_slide) {

            this.slideIndex     =   this.$currentSlide(this.slideIndex = current_slide, this.slideIndex)
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