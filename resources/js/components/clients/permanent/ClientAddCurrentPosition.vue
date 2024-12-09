<template>

    <div class="mt-3">

        <div class="page-header mb-2">

            <div class="container w-100">
                <h3 class="page-title">
                    Ajouter un Nouveau Client
                </h3>
            </div>

        </div>

        <div class="container"  style="height : 60vh; overflow : auto;">

            <form>

                <div class="slideshow-container">

                    <!-- CustomerCode -->
                    <div class="mySlides slide_1">
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

                    <!-- CustomerBarCode_image -->
                    <div class="mySlides slide_2">
                        <label for="CustomerBarCode_image"  class="form-label">QR Code Image</label>
                        <button type="button"               class="btn btn-secondary w-100 mb-1" @click="$clickFile('CustomerBarCode_image')"><i class="mdi mdi-camera"></i></button>

                        <input type='file'                  id="CustomerBarCode_image"              style="display:none"    accept="image/*"    capture     @change="customerBarCodeImage()">
                        <img                                id="CustomerBarCode_image_display"      src=""                  style="width : 100%; height : auto; display: block; margin : auto">
                    </div>

                    <!-- CustomerNameE -->
                    <div class="mySlides slide_3">
                        <label for="CustomerNameE"      class="form-label">Nom et Prénom de l'Acheteur</label>
                        <input type="text"              class="form-control"        id="CustomerNameE"          v-model="client.CustomerNameE">
                    </div>

                    <!-- CustomerNameA -->
                    <div class="mySlides slide_4">
                        <label for="CustomerNameA"      class="form-label">Raison Sociale</label>
                        <input type="text"              class="form-control"        id="CustomerNameA"          v-model="client.CustomerNameA">
                    </div>

                    <!-- Tel -->
                    <div class="mySlides slide_5">
                        <label for="Tel"                class="form-label">Téléphone</label>
                        <input type="text"              class="form-control"        id="Tel"                    v-model="client.Tel">
                    </div>

                    <!-- GPS -->
                    <div class="mySlides slide_6">
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
                                    <th class="text-wrap">Type</th>
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
                    </div>

                    <!-- Adresse -->
                    <div class="mySlides slide_7">
                        <label for="Address"            class="form-label">Adresse</label>
                        <input type="text"              class="form-control"        id="Address"                v-model="client.Address">
                    </div>

                    <!-- Neighborhood -->
                    <div class="mySlides slide_8">
                        <label for="Neighborhood"       class="form-label">Quartier</label>
                        <input type="text"              class="form-control"        id="Neighborhood"           v-model="client.Neighborhood">
                    </div>

                    <!-- Landmark -->
                    <div class="mySlides slide_9">
                        <label for="Landmark"           class="form-label">Point de Repere</label>
                        <textarea                       class="form-control"        id="Landmark"   rows="3"    v-model="client.Landmark"></textarea>
                    </div>

                    <!-- DistrictNo --> 
                    <div class="mySlides slide_10">
                        <label for="DistrictNo"         class="form-label">Willaya</label>
                        <select                         class="form-select"         id="DistrictNo"             v-model="client.DistrictNo"     @change="getCites()"    disabled>
                            <option v-for="willaya in willayas" :key="willaya.DistrictNo" :value="willaya.DistrictNo">{{willaya.DistrictNo}}- {{willaya.DistrictNameE}}</option>
                        </select>
                    </div> 
                   
                    <!-- CityNo -->
                    <div class="mySlides slide_11">
                        <label for="CityNo"             class="form-label">Commune</label>
                        <select                         class="form-select"         id="CityNo"                 v-model="client.CityNo">
                            <option v-for="cite in cites" :key="cite.CITYNO" :value="cite.CITYNO">{{cite.CITYNO}}- {{cite.CityNameE}}</option>
                        </select>
                    </div>

                    <!-- CustomerType -->
                    <div class="mySlides slide_12">
                        <label for="text"               class="form-label">Type de Magasin</label>
                        <select                         class="form-select"         id="CustomerType"                 v-model="client.CustomerType">
                                <option     :value="'Alimentation Generale'">Alimentation Generale</option>
                                <option     :value="'Fast food'">Fast food</option>
                                <option     :value="'Restaurant'">Restaurant</option>
                                <option     :value="'Cafétéria'">Cafétéria</option>
                                <option     :value="'Grossiste'">Grossiste</option>
                                <option     :value="'Supérette'">Supérette</option>
                                <option     :value="'Hypermarché'">Hypermarché</option>

                            <!-- <option     :value="'Bureau Tabac'">Bureau Tabac</option>
                            <option     :value="'Cosmetique'">Cosmetique</option> -->
                        </select>
                    </div>

                    <!-- BrandSourcePurchase -->
                    <div class="mySlides slide_13">
                        <label for="text"               class="form-label">Source d'Achat</label>
                        <select                         class="form-select"         id="BrandSourcePurchase"                 v-model="client.BrandSourcePurchase">
                            <option     value="Distribution Direct">Distribution Direct</option>
                            <option     value="Grossiste Fixe">Grossiste Fixe</option>
                            <option     value="Grossiste Mobile">Grossiste Mobile</option>
                            <option     value="Multi Source">Multi Source</option>
                            <option     value="Pas d'achat">Pas d'achat</option>
                        </select>
                    </div>

                    <!-- JPlan -->
                    <div class="mySlides slide_14">
                        <label for="JPlan"              class="form-label">Nom de Vendeur</label>
                        <input type="text"              class="form-control"        id="JPlan"                  v-model="client.JPlan">
                    </div>
                    
                    <!-- Journee -->
                    <div class="mySlides slide_15">
                        <label for="Journee"            class="form-label">Journee</label>
                        <input type="text"              class="form-control"        id="Journee"                v-model="client.Journee">
                    </div>

                    <!-- BrandAvailability + In-Store Image -->
                    <div class="mySlides slide_16">

                        <div class="mb-1">
                            <label for="text"               class="form-label">Disponibilité Produits</label>
                            <select                         class="form-select"         id="BrandAvailability"                 v-model="client.BrandAvailability"   @change="brandAvailabilityChanged()">
                                <option     value=0>No</option>
                                <option     value=1>Yes</option>
                            </select>
                        </div>

                        <div class="mt-1"   v-show="client.BrandAvailability  ==  1">
                            <label for="in_store_image" class="form-label">Image In-Store</label>
                            <button type="button"       class="btn btn-secondary w-100 mb-1" @click="$clickFile('in_store_image')"><i class="mdi mdi-camera"></i></button>

                            <input type='file'          id="in_store_image"             style="display:none"    accept="image/*"    capture     @change="inStoreImage()">
                            <img                        id="in_store_image_display"     src=""                  style="width : 100%; height : auto; display: block; margin : auto">
                        </div>
                    </div>

                    <!-- facade_image -->
                    <div class="mySlides slide_17">
                        <label for="facade_image"   class="form-label">Image Facade</label>
                        <button type="button"       class="btn btn-secondary w-100 mb-1" @click="$clickFile('facade_image')"><i class="mdi mdi-camera"></i></button>

                        <input type='file'          id="facade_image"           style="display:none"    accept="image/*"    capture     @change="facadeImage()">
                        <img                        id="facade_image_display"   src=""                  style="width : 100%; height : auto; display: block; margin : auto">
                    </div>


                    <!-- Comment -->
                    <div class="mySlides slide_18">
                        <label      for="comment">Commentaire</label>
                        <textarea   class="form-control"    id="comment"    rows="3"    v-model="client.comment"></textarea>
                    </div>

                </div>

            </form>

        </div>

        <!--  -->

        <div v-if="point_is_inside_user_polygons">

            <div class="container start-0 w-100"          style="bottom: 65px;">
                <div class="row justify-content-center">
                    <div class="col-6 mt-3">
                        <button v-if="((slideIndex    >   1)&&(getIsOnline))"                   type="button" class="btn btn-secondary w-100"   @click="plusSlides(-1)">Precedent</button>
                    </div>

                    <div class="col-6 mt-3">
                        <button v-if="((slideIndex    <   total_questions)&&(getIsOnline))"     type="button" class="btn btn-primary w-100"     @click="plusSlides(1)">Suivant</button>
                    </div>
                </div>
            </div>

            <div class="container start-0 w-100 mb-3"    style="bottom: 0px;">
                <div class="row justify-content-center">
                    <div class="col mt-3">
                        <button v-if="((slideIndex  ==  total_questions)&&(getIsOnline))"       type="button" class="btn btn-primary w-100"     @click="sendData()">Confirmer</button>
                    </div>
                </div>
            </div>            

        </div>

    </div>

</template>

<script>

import {mapGetters, mapActions} from    "vuex"

//

import moment                   from    "moment"
import "moment-timezone"

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
                nonvalidated_details                    :   '', 

                // Slide 18
                facade_image                            :   '',
                facade_image_original_name              :   '',

                // Slide 19   
                in_store_image                          :   '',
                in_store_image_original_name            :   '',

                // Slide 20
                comment                                 :   ''
            },

            willayas                :   [],
            cites                   :   [],

            //

            liste_journey_plan              :   []  ,
            liste_journee                   :   []  ,
            liste_type_client               :   []  ,

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

            total_questions                 :   18      ,

            //

            start_adding_date               :   ""
        }
    },

    computed : {

        ...mapGetters({
            getListeJourneyPlan             :   'journey_plan/getListeJourneyPlan'      ,
            getListeTypeClient              :   'type_client/getListeTypeClient'        ,  
            getListeJournee                 :   'journee/getListeJournee'               ,

            //

            getAllClients                   :   'client/getAllClients'                  ,

            //

            getUser                         :   'authentification/getUser'              ,

            //

            getIsOnline                     :   'internet/getIsOnline'
        }),
    },

    async mounted() {

        //
        this.slideIndex     =   this.$showSlides(this.slideIndex, this.slideIndex);

        //
        this.client.status      =   "pending"

        //
        this.client.DistrictNo  =   this.getUser.DistrictNo
        await this.getCites()

        //
        await this.getData()
    }, 

    beforeUnmount() {

        if(this.scanner) {

            this.scanner.clear().then(_ => {

            }).catch(error => {

            });
        }
    },

    methods : {

        ...mapActions("client" ,  [
            "setAddClientAction"   ,
        ]),

        async sendData() {
            if((this.client.facade_image !==  "")&&(this.client.facade_image_original_name   !==  "")) {

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

                formData.append("CustomerBarCode_image"                 ,   this.client.CustomerBarCode_image)
                formData.append("facade_image"                          ,   this.client.facade_image)
                formData.append("in_store_image"                        ,   this.client.in_store_image)

                formData.append("CustomerBarCode_image_original_name"   ,   this.client.CustomerBarCode_image_original_name)
                formData.append("facade_image_original_name"            ,   this.client.facade_image_original_name)
                formData.append("in_store_image_original_name"          ,   this.client.in_store_image_original_name)

                formData.append("status"                                ,   this.client.status)
                formData.append("nonvalidated_details"                  ,   this.client.nonvalidated_details)

                formData.append("comment"                               ,   this.client.comment)

                formData.append("start_adding_date"                     ,   this.start_adding_date)
                formData.append("finish_adding_date"                    ,   moment(new Date()).format())

                if(this.getIsOnline) {

                    const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/store",   formData)
                    console.log(res.data)

                    if(res.status===200){

                        // Hide Loading Page
                        this.$hideLoadingPage()

                        // Send Client
                        this.client.id                      =   res.data.client.id
                        this.client.status                  =   this.client.status
                        this.client.nonvalidated_details    =   this.client.nonvalidated_details

                        // Send Feedback
                        this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                        // this.emitter.emit('reSetAdd' , this.client)

                        // 
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

                    let clients    =   await this.$indexedDB.$getAddedClients()

                    console.log(clients)

                    let max_local_id    =   0

                    if(clients.length > 0) {

                        max_local_id    =   parseInt(clients[clients.length - 1].id.match(/(\d+)$/)[1]) + 1

                        console.log(max_local_id)
                    }

                    this.client.id                      =   "local_id_"+max_local_id
                    this.client.status                  =   this.client.status
                    this.client.nonvalidated_details    =   this.client.nonvalidated_details
                    this.client.id_route_import         =   this.$route.params.id_route_import

                    // Add in indexedDB
                    await this.$indexedDB.$setAddedClients(this.client, this.$route.params.id_route_import)

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Go Back
                    this.$goBack()
                }
            }

            else {

                this.$showErrors("Error !"  ,   ["Veuillez répondre avant de confirmer !"])
            }
        },

        //

        removeDrawings() {

            // Remove Drawings
            this.$map.$removeDrawings()
        },

        //

        async getData() {

            // Set Start Added
            this.start_adding_date  =   moment(new Date()).format()

            if(this.getIsOnline) {

                let position                        =   await this.$currentPosition()

                this.client.Latitude                =   position.coords.latitude
                this.client.Longitude               =   position.coords.longitude

                this.point_is_inside_user_polygons  =   this.$checkMarkerInsideUserPolygonsWithoutMap(this.client.Latitude, this.client.Longitude, this.getUser.user_territories)

                console.log(this.client.Latitude)
                console.log(this.client.Longitude)
                console.log(this.point_is_inside_user_polygons)

                //

                const res                           =   await this.$callApi("post"  ,   "/route/obs/route_import/"+this.$route.params.id_route_import+"/details",   null)
                this.all_clients                    =   res.data.route_import.data
            }

            else {

                this.route_import   =   await this.$indexedDB.$getRouteImport(this.$route.params.id_route_import)
                this.all_clients    =   this.route_import.clients
            }

            //

            this.getComboData()  

            if(this.getIsOnline) {

                this.checkClients()
            }
        },

        async getComboData() {

            if(this.getIsOnline) {

                const res_3                     =   await this.$callApi("post"  ,   "/rtm_willayas"         ,   null)
                this.willayas                   =   res_3.data
            }

            else {

                this.willayas                   =   await this.$indexedDB.$getWillayas()
            }
        },

        async getCites() {

            if(this.getIsOnline) {

                // Show Loading Page
                this.$showLoadingPage()

                const res_3                     =   await this.$callApi("post"  ,   "/rtm_willayas/"+this.client.DistrictNo+"/rtm_cites"         ,   null)
                this.cites                      =   res_3.data

                this.client.CityNo              =   ""

                // Hide Loading Page
                this.$hideLoadingPage()
            }

            else {

                // Show Loading Page
                this.$showLoadingPage()

                let willaya                     =   await this.$indexedDB.$getWillaya(this.client.DistrictNo)
                console.log(willaya)

                this.cites                      =   willaya.cites

                this.client.CityNo              =   ""

                // Hide Loading Page
                this.$hideLoadingPage()
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

        async customerBarCodeImage() {

            const CustomerBarCode_image  =   document.getElementById("CustomerBarCode_image").files[0];

            console.log(200)

            if(CustomerBarCode_image) {

                if(this.getIsOnline) {

                    this.client.CustomerBarCode_image_original_name     =   CustomerBarCode_image.name
                    this.client.CustomerBarCode_image                   =   await this.$compressImage(CustomerBarCode_image)

                    //

                    // let CustomerBarCode_image_much_compressed           =   await this.$compressImageMuch(this.client.CustomerBarCode_image) 
                    // let CustomerBarCode_image_base64                    =   await this.$imageToBase64(CustomerBarCode_image_much_compressed)

                    let CustomerBarCode_image_display                   =   document.getElementById("CustomerBarCode_image_display")
                    // this.base64ToImage(CustomerBarCode_image_base64, CustomerBarCode_image_display)

                    //
                    this.$displayImage(this.client.CustomerBarCode_image, CustomerBarCode_image_display)
                }

                else {

                    this.client.CustomerBarCode_image_original_name     =   CustomerBarCode_image.name
                    this.client.CustomerBarCode_image                   =   await this.$compressImage(CustomerBarCode_image)

                    //

                    // let CustomerBarCode_image_much_compressed           =   await this.$compressImageMuch(this.client.CustomerBarCode_image) 
                    this.client.CustomerBarCode_image                   =   await this.$imageToBase64(this.client.CustomerBarCode_image)

                    let CustomerBarCode_image_display                   =   document.getElementById("CustomerBarCode_image_display")
                    // this.base64ToImage(this.client.CustomerBarCode_image, CustomerBarCode_image_display)

                    //
                    this.$displayImage(this.client.CustomerBarCode_image, CustomerBarCode_image_display)
                }
            }

            else {

                    this.client.CustomerBarCode_image_original_name     =   ""
                    this.client.CustomerBarCode_image                   =   ""

                    const CustomerBarCode_image_display                 =   document.getElementById("CustomerBarCode_image_display")

                    if(CustomerBarCode_image_display) {

                        CustomerBarCode_image_display.src                   =   ""
                    }
            }
        },

        //

        async facadeImage() {

            const facade_image  =   document.getElementById("facade_image").files[0];

            if(facade_image) {

                if(this.getIsOnline) {

                    this.client.facade_image_original_name      =   facade_image.name
                    this.client.facade_image                    =   await this.$compressImage(facade_image)

                    //

                    // let facade_image_much_compressed           =   await this.$compressImageMuch(this.client.facade_image) 
                    // let facade_image_base64                     =   await this.$imageToBase64(facade_image_much_compressed)

                    let facade_image_display                    =   document.getElementById("facade_image_display")
                    // this.base64ToImage(facade_image_base64, facade_image_display)

                    //
                    this.$displayImage(this.client.facade_image, facade_image_display)
                }

                else {

                    this.client.facade_image_original_name      =   facade_image.name
                    this.client.facade_image                    =   await this.$compressImage(facade_image)

                    //

                    // let facade_image_much_compressed            =   await this.$compressImageMuch(this.client.facade_image) 
                    this.client.facade_image                    =   await this.$imageToBase64(this.client.facade_image)

                    let facade_image_display                    =   document.getElementById("facade_image_display")
                    // this.base64ToImage(this.client.facade_image, facade_image_display)

                    //
                    this.$displayImage(this.client.facade_image, facade_image_display)
                }
            }

            else {

                    this.client.facade_image_original_name      =   ""
                    this.client.facade_image                    =   ""

                    const facade_image_display                  =   document.getElementById("facade_image_display")

                    if(facade_image_display) {

                        facade_image_display.src                    =   ""
                    }
            }
        },

        //

        brandAvailabilityChanged() {

            if((this.client.BrandAvailability   === 0)||(this.client.BrandAvailability  === "0")) {

                this.client.in_store_image_original_name    =   ""
                this.client.in_store_image                  =   ""

                const in_store_image_display                =   document.getElementById("in_store_image_display")

                if(in_store_image_display) {

                    in_store_image_display.src                  =   ""
                }
            }
        },

        async inStoreImage() {

            const in_store_image  =   document.getElementById("in_store_image").files[0];

            if(in_store_image) {

                if(this.getIsOnline) {

                    this.client.in_store_image_original_name    =   in_store_image.name
                    this.client.in_store_image                  =   await this.$compressImage(in_store_image)
                    
                    //

                    // let in_store_image_much_compressed          =   await this.$compressImageMuch(this.client.in_store_image) 
                    // let in_store_image_base64                   =   await this.$imageToBase64(in_store_image_much_compressed)

                    let in_store_image_display                  =   document.getElementById("in_store_image_display")
                    // this.base64ToImage(in_store_image_base64, in_store_image_display)

                    //
                    this.$displayImage(this.client.in_store_image, in_store_image_display)
                }

                else {

                    this.client.in_store_image_original_name    =   in_store_image.name
                    this.client.in_store_image                  =   await this.$compressImage(in_store_image)
                    
                    //

                    // let in_store_image_much_compressed          =   await this.$compressImageMuch(this.client.in_store_image) 
                    this.client.in_store_image                  =   await this.$imageToBase64(this.client.in_store_image)

                    let in_store_image_display                  =   document.getElementById("in_store_image_display")
                    // this.base64ToImage(this.client.in_store_image, in_store_image_display)

                    //
                    this.$displayImage(this.client.in_store_image, in_store_image_display)
                }
            }

            else {

                    this.client.in_store_image_original_name    =   ""
                    this.client.in_store_image                  =   ""

                    const in_store_image_display                =   document.getElementById("in_store_image_display")

                    if(in_store_image_display) {

                        in_store_image_display.src                  =   ""
                    }
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
        },

        //

        async setBarCodeReader() {

            const reader    =   document.getElementById('reader')

            // 
            this.client.CustomerCode    =   ""

            if(reader) {

                reader.style.display        =   "block";

                //
                const requestCamera = async () => {
                    const devices       = await navigator.mediaDevices.enumerateDevices();
                    const videoDevices  = devices.filter(device => device.kind === 'videoinput');
                    const backCamera    = videoDevices.find(device => device.label.includes('back') || device.label.includes('rear'));
                    
                    console.log(backCamera)

                    if (backCamera) {

                        return { exact: backCamera.deviceId };
                    }
                    
                    return undefined;
                };

                //
                this.scanner = new Html5QrcodeScanner('reader', {

                    qrbox   : window.innerWidth < 500 ? { width: 200, height: 200 } : { width: 250, height: 250 },

                    fps     : navigator.hardwareConcurrency > 4 ? 20 : 10,

                    // supportedScanTypes  : [
                    //     Html5QrcodeScanType.SCAN_TYPE_CAMERA
                    // ],
                });

                try {
                    await this.scanner.render(this.success, this.error, requestCamera);
                } catch (error) {
                    console.error('Error rendering scanner:', error);
                }
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

        async showPositionOnMap(map_id) {

            let position                =   await this.$currentPosition()

            console.log(position)

            this.client.Latitude        =   position.coords.latitude
            this.client.Longitude       =   position.coords.longitude

            //

            let position_marker                 =   this.$showPositionOnMap(map_id, this.client.Latitude, this.client.Longitude, this.getUser.user_territories)

            this.point_is_inside_user_polygons  =   this.$checkMarkerInsideUserPolygons(position_marker)

            console.log(this.point_is_inside_user_polygons)
        },

        //

        plusSlides(current_slide) {

            // Go Next
            if(current_slide    ==  1) {

                // Validation de la question
                let validation          =   this.validationQuestion()

                console.log(this.slideIndex)

                if(validation   ==  true)  {

                    this.slideIndex     =   this.$plusSlides(this.slideIndex += current_slide, this.slideIndex)
                }

                else {

                    this.$showErrors("Error !"  ,   ["Veuillez répondre avant de passer à la question suivante !"])
                    return false;
                }
                //

                // Verifier Si La Question GPS
                if(this.slideIndex  ==  6) {

                    this.showPositionOnMap("show_map")
                }
                //
            }

            else {

                this.slideIndex     =   this.$plusSlides(this.slideIndex += current_slide, this.slideIndex)
            }
        },

        currentSlide(current_slide) {

            this.slideIndex     =   this.$currentSlide(this.slideIndex = current_slide, this.slideIndex)
        },

        //

        validationQuestion() {

            // Slide 1
            if(this.slideIndex  ==  1) {

                if(this.client.CustomerCode !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 2
            if(this.slideIndex  ==  2) {

                if((this.client.CustomerBarCode_image !==  "")&&(this.client.CustomerBarCode_image_original_name  !==  "")) {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 3
            if(this.slideIndex  ==  3) {

                if(this.client.CustomerNameE !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 4
            if(this.slideIndex  ==  4) {

                if(this.client.CustomerNameA !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 5
            if(this.slideIndex  ==  5) {

                if((this.client.Tel !== "")&&((this.client.Tel.startsWith('05'))||(this.client.Tel.startsWith('06'))||(this.client.Tel.startsWith('07')))&&(!isNaN(parseInt(this.client.Tel)))&&(this.client.Tel.length == 10)){
                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 6
            if(this.slideIndex  ==  6) {

                if((this.client.Latitude !==  "")&&(this.client.Longitude !==  "")) {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 7
            if(this.slideIndex  ==  7) {

                if(this.client.Address !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 8
            if(this.slideIndex  ==  8) {

                if(this.client.Neighborhood !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 9
            if(this.slideIndex  ==  9) {

                if(this.client.Landmark !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 10
            if(this.slideIndex  ==  10) {

                if(this.client.DistrictNo !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 11
            if(this.slideIndex  ==  11) {

                if(this.client.CityNo !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 12
            if(this.slideIndex  ==  12) {

                if(this.client.CustomerType !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 13
            if(this.slideIndex  ==  13) {

                if(this.client.BrandSourcePurchase !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 14
            if(this.slideIndex  ==  14) {

                if(this.client.JPlan !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }

            // Slide 15
            if(this.slideIndex  ==  15) {

                if(this.client.Journee !==  "") {

                    return true;
                }

                else {

                    return false
                }
            }
            
            // Slide 16
            if(this.slideIndex  ==  16) {

                if((this.client.BrandAvailability   === 0)||(this.client.BrandAvailability  === "0")) {

                    return true
                }

                else {

                    if((this.client.in_store_image !==  "")&&(this.client.in_store_image_original_name   !==  "")) {

                        return true
                    }

                    else {

                        return false
                    }
                }
            }
            // Slide 17
            if(this.slideIndex  ==  17) {

                if((this.client.facade_image !==  "")&&(this.client.facade_image_original_name   !==  "")) {

                    return true;
                }

                else {

                    return false
                }
            }



            return true
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