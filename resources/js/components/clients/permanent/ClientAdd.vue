<template>

    <div class="mt-3">

        <div class="page-header">

            <div class="row w-100">
                <div class="col-10">
                    <h3 class="page-title">
                        Ajouter un Nouveau Client 
                    </h3>
                </div>
            </div>

        </div>

        <div>

            <div class="container"  style="height : 58vh; overflow : auto;">

                <form>

                    <div class="slideshow-container">

                        <div class="mb-3 mySlides slide_1">

                            <hr />

                            <h5>Nearby Clients</h5>

                            <hr />

                            <table class="table table-bordered mt-1">
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
                            </table>
                        </div>

                        <div class="mb-3 mySlides slide_2">
                            <div v-show="client.CustomerCode   ==  ''"     class="mt-1 p-0">
                                <div    id="reader" class="scanner_reader w-100"></div>
                            </div>

                            <div v-show="client.CustomerCode   !=  ''"     class="mt-1 p-0">
                                <div    id="result"></div>
                            </div>

                            <div v-show="client.CustomerCode   !=  ''"     class="mt-1 p-0">
                                <div    id="customerCode_value"              class="text-center">
                                    <span class="">CustomerCode : {{ client.CustomerCode }}</span>
                                </div>
                            </div>

                            <!--  -->

                            <div class="mt-1 mb-1 w-100">
                                <div class="w-100" id="refresh_client_barcode_button">
                                    <button type="button" class="btn btn-primary w-100"     @click="setBarCodeReader()">Capture Bar Code</button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 mySlides slide_3">
                            <label for="CustomerCode"       class="form-label">CustomerCode (CustomerCode)</label>
                            <input type="text"              class="form-control"        id="CustomerCode"           v-model="client.CustomerCode">
                        </div>

                        <div class="mb-3 mySlides slide_4">
                            <label for="CustomerBarCode_image"    class="form-label">CustomerBarCode Image (CustomerBarCode Image)</label>
                            <input type="file"              class="form-control"        id="CustomerBarCode_image"              accept="image/*"    capture     @change="customerBarCodeImage()">
                            <img                                                        id="CustomerBarCode_image_display"      src=""              class="w-100">
                        </div>

                        <div class="mb-3 mySlides slide_5">
                            <label for="CustomerNameE"      class="form-label">CustomerNameE (CustomerNameE)</label>
                            <input type="text"              class="form-control"        id="CustomerNameE"          v-model="client.CustomerNameE">
                        </div>

                        <div class="mb-3 mySlides slide_6">
                            <label for="CustomerNameA"      class="form-label">CustomerNameA (CustomerNameA)</label>
                            <input type="text"              class="form-control"        id="CustomerNameA"          v-model="client.CustomerNameA">
                        </div>

                        <div class="mb-3 mySlides slide_7">
                            <label for="Tel"                class="form-label">Phone Number (Tel)</label>
                            <input type="text"              class="form-control"        id="Tel"                    v-model="client.Tel">
                        </div>

                        <div class="mb-3 mySlides slide_8">
                            <label for="Address"            class="form-label">Address (Address)</label>
                            <input type="text"              class="form-control"        id="Address"                v-model="client.Address">
                        </div>

                        <div class="mb-3 mySlides slide_9">
                            <label for="Neighborhood"       class="form-label">Neighborhood (Neighborhood)</label>
                            <input type="text"              class="form-control"        id="Neighborhood"           v-model="client.Neighborhood">
                        </div>

                        <div class="mb-3 mySlides slide_10">
                            <label for="Landmark"           class="form-label">Landmark (Landmark)</label>
                            <textarea                       class="form-control"        id="Landmark"   rows="3"    v-model="client.Landmark"></textarea>
                        </div>

                        <div class="mb-3 mySlides slide_11">
                            <label for="DistrictNo"         class="form-label">DistrictNo (DistrictNo)</label>
                            <select                         class="form-select"         id="DistrictNo"             v-model="client.DistrictNo"     @change="getCites()">
                                <option v-for="willaya in willayas" :key="willaya.DistrictNo" :value="willaya.DistrictNo">{{willaya.DistrictNo}}- {{willaya.DistrictNameE}}</option>
                            </select>
                        </div>

                        <div class="mb-3 mySlides slide_12">
                            <label for="CityNo"             class="form-label">CityNo (CityNo)</label>
                            <select                         class="form-select"         id="CityNo"                 v-model="client.CityNo">
                                <option v-for="cite in cites" :key="cite.CITYNO" :value="cite.CITYNO">{{cite.CITYNO}}- {{cite.CityNameE}}</option>
                            </select>
                        </div>

                        <div class="mb-3 mySlides slide_13">
                            <label for="text"               class="form-label">CustomerType (CustomerType)</label>
                            <select                         class="form-select"         id="CustomerType"                 v-model="client.CustomerType">
                                <option     :value="'Superette'">Superette</option>
                                <option     :value="'Alimentation General'">Alimentation General</option>
                                <option     :value="'Grossiste'">Grossiste</option>
                            </select>
                        </div>

                        <div class="mb-3 mySlides slide_14">
                            <label for="text"               class="form-label">BrandAvailability (BrandAvailability)</label>
                            <select                         class="form-select"         id="BrandAvailability"                 v-model="client.BrandAvailability">
                                <option     value="0">No</option>
                                <option     value="1">Yes</option>
                            </select>
                        </div>

                        <div class="mb-3 mySlides slide_15">
                            <label for="text"               class="form-label">BrandSourcePurchase (BrandSourcePurchase)</label>
                            <select                         class="form-select"         id="BrandSourcePurchase"                 v-model="client.BrandSourcePurchase">
                                <option     value="Distribution Direct">Distribution Direct</option>
                                <option     value="Grossiste Fixe">Grossiste Fixe</option>
                                <option     value="Grossiste Mobile">Grossiste Mobile</option>
                                <option     value="Multi Source">Multi Source</option>
                                <option     value="Pas d'achat">Pas d'achat</option>
                            </select>
                        </div>

                        <div class="mb-3 mySlides slide_16">
                            <label for="JPlan"              class="form-label">JPlan (JPlan)</label>
                            <input type="text"              class="form-control"        id="JPlan"                  v-model="client.JPlan">
                        </div>
                        
                        <div class="mb-3 mySlides slide_17">
                            <label for="Journee"            class="form-label">WorkDay (Journee)</label>
                            <input type="text"              class="form-control"        id="Journee"                v-model="client.Journee">
                        </div>

                        <div class="mb-3 mySlides slide_18">
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

                        <div class="mb-3 mySlides slide_19">
                            <label for="facade_image"       class="form-label">Facade Image (Facade Image)</label>
                            <input type="file"              class="form-control"        id="facade_image"               accept="image/*"    capture     @change="facadeImage()">
                            <img                                                        id="facade_image_display"       src=""              class="w-100">
                        </div>

                        <div class="mb-3 mySlides slide_20">
                            <label for="in_store_image"     class="form-label">In-Store Image (In-Store Image)</label>
                            <input type="file"              class="form-control"        id="in_store_image"             accept="image/*"    capture     @change="inStoreImage()">
                            <img                                                        id="in_store_image_display"     src=""              class="w-100">
                        </div>

                        <div class="mb-3 mySlides slide_21">
                            <label      for="comment">Comment</label>
                            <textarea   class="form-control"    id="comment"    rows="3"    v-model="client.comment"></textarea>
                        </div>

                    </div>

                </form>

            </div>

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-6 mt-3">
                        <button v-if="slideIndex    >   1"      type="button" class="btn btn-secondary w-100"   @click="plusSlides(-1)">Precedent</button>
                    </div>

                    <div class="col-6 mt-3">
                        <button v-if="slideIndex    <   total_questions"     type="button" class="btn btn-primary w-100"     @click="plusSlides(1)">Suivant</button>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col mt-3">
                        <button v-if="slideIndex  ==  total_questions"       type="button" class="btn btn-primary w-100"     @click="sendData()">Confirmer</button>
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
                CustomerBarCode_image                   :   '',
                facade_image                            :   '',
                in_store_image                          :   '',

                CustomerBarCode_image_original_name     :   '',
                facade_image_original_name              :   '',
                in_store_image_original_name            :   '',

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
                CustomerType            :   '',
                BrandAvailability       :   '',
                BrandSourcePurchase     :   '',

                // Journey Plan
                JPlan                   :   '',

                // Journee
                Journee                 :   '',

                // Status
                status                  :   '',
                nonvalidated_details    :   '', 

                // Comment
                comment                 :   ''
            },

            willayas                        :   [],
            cites                           :   [],

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

            total_questions                 :   21      ,

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

            getUser                         :   'authentification/getUser'              
        }),
    },

    async mounted() {

        this.slideIndex     =   this.$showSlides(this.slideIndex, this.slideIndex);

        //
        this.client.status  =   "pending"

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

            if(this.$connectedToInternet) {

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/store",   formData)

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

            //

            let client          =   { lat : 0, lng : 0 }

            client.lat          =   this.$route.params.latitude
            client.lng          =   this.$route.params.longitude

            //

            if(this.$connectedToInternet) {

                const res           =   await this.$callApi("post"  ,   "/route/obs/route_import/"+this.$route.params.id_route_import+"/details",   null)
                this.all_clients    =   res.data.route_import.data
            }

            else {

                this.route_import   =   await this.$indexedDB.$getRouteImport(this.$route.params.id_route_import)
                this.all_clients    =   this.route_import.clients
            }

            //

            this.setCoords(client)
            this.getComboData()  

            if(this.$connectedToInternet) {

                this.checkClients()
            }
        },

        setCoords(client) {

            if(client) {

                this.client.Latitude    =   client.lat
                this.client.Longitude   =   client.lng
            }

            //
            this.point_is_inside_user_polygons  =   this.$map.$checkPointInsideUserPolygons(this.client.Latitude, this.client.Longitude)
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

                let willaya                     =   await this.$indexedDB.$getWillaya(this.client.DistrictNo)
                console.log(willaya)

                this.cites                      =   willaya.cites

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

            if(CustomerBarCode_image) {

                if(this.$connectedToInternet) {

                    this.client.CustomerBarCode_image_original_name      =   CustomerBarCode_image.name
                    this.client.CustomerBarCode_image                    =   await this.$compressImage(CustomerBarCode_image)

                    //

                    let CustomerBarCode_image_base64                     =   await this.$imageToBase64(this.client.CustomerBarCode_image)

                    let CustomerBarCode_image_display                    =   document.getElementById("CustomerBarCode_image_display")
                    this.base64ToImage(CustomerBarCode_image_base64, CustomerBarCode_image_display)
                }

                else {

                    this.client.CustomerBarCode_image_original_name      =   CustomerBarCode_image.name
                    this.client.CustomerBarCode_image                    =   await this.$compressImage(CustomerBarCode_image)

                    //

                    this.client.CustomerBarCode_image                    =   await this.$imageToBase64(this.client.CustomerBarCode_image)

                    let CustomerBarCode_image_display                    =   document.getElementById("CustomerBarCode_image_display")
                    this.base64ToImage(this.client.CustomerBarCode_image, CustomerBarCode_image_display)
                }
            }
        },

        //

        async facadeImage() {

            const facade_image  =   document.getElementById("facade_image").files[0];

            if(facade_image) {

                if(this.$connectedToInternet) {

                    this.client.facade_image_original_name      =   facade_image.name
                    this.client.facade_image                    =   await this.$compressImage(facade_image)

                    //

                    let facade_image_base64                     =   await this.$imageToBase64(this.client.facade_image)

                    let facade_image_display                    =   document.getElementById("facade_image_display")
                    this.base64ToImage(facade_image_base64, facade_image_display)
                }

                else {

                    this.client.facade_image_original_name      =   facade_image.name
                    this.client.facade_image                    =   await this.$compressImage(facade_image)

                    //

                    this.client.facade_image                    =   await this.$imageToBase64(this.client.facade_image)

                    let facade_image_display                    =   document.getElementById("facade_image_display")
                    this.base64ToImage(this.client.facade_image, facade_image_display)
                }
            }
        },

        //

        async inStoreImage() {

            const in_store_image  =   document.getElementById("in_store_image").files[0];

            if(in_store_image) {

                if(this.$connectedToInternet) {

                    this.client.in_store_image_original_name    =   in_store_image.name
                    this.client.in_store_image                  =   await this.$compressImage(in_store_image)
                    
                    //

                    let in_store_image_base64                   =   await this.$imageToBase64(this.client.in_store_image)

                    let in_store_image_display                  =   document.getElementById("in_store_image_display")
                    this.base64ToImage(in_store_image_base64, in_store_image_display)
                }

                else {

                    this.client.in_store_image_original_name    =   in_store_image.name
                    this.client.in_store_image                  =   await this.$compressImage(in_store_image)
                    
                    //

                    this.client.in_store_image                  =   await this.$imageToBase64(this.client.in_store_image)

                    let in_store_image_display                  =   document.getElementById("in_store_image_display")
                    this.base64ToImage(this.client.in_store_image, in_store_image_display)
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

                        disableFileInput: true // Disable file input (gallery selection)
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