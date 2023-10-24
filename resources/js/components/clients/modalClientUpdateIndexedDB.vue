<template>

    <!-- Modal -->
    <div class="modal fade" id="modalClientUpdateIndexedDB" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
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

                        <!--  -->

                        <div v-if="$isRole('Super Admin')||$isRole('BackOffice')"   class="mb-3">
                            <label for="Latitude"           class="form-label">Latitude (Latitude)</label>
                            <input type="text"              class="form-control"        id="Latitude"               v-model="client.Latitude"   @input="checkClients()">
                        </div>

                        <div v-if="$isRole('Super Admin')||$isRole('BackOffice')"   class="mb-3">
                            <label for="Longitude"          class="form-label">Longitude (Longitude)</label>
                            <input type="text"              class="form-control"        id="Longitude"              v-model="client.Longitude"  @input="checkClients()">
                        </div>
                    
                        <!--  -->

                        <div class="mb-3">
                            <label for="text"               class="form-label">CustomerType (CustomerType)</label>
                            <input type="text"              class="form-control"        id="CustomerType"           v-model="client.CustomerType">
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

                        <div v-if="client.status_original   ==  'validated'">
                            <div v-if="$isRole('Super Admin')||$isRole('BackOffice')" class="mb-3">
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
                            <div v-if="$isRole('Super Admin')||$isRole('BackOffice')" class="mb-3">
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
                            <label for="facade_image_update"    class="form-label">Facade Image (Facade Image)</label>
                            <input type="file"                  class="form-control"    id="facade_image_update"               accept="image/*"    @change="facadeImage()">
                            <img                                                        id="facade_image_display_update"       src=""              class="w-100">
                        </div>

                        <div class="mb-3">
                            <label for="in_store_image_update"  class="form-label">In-Store Image (In-Store Image)</label>
                            <input type="file"                  class="form-control"    id="in_store_image_update"             accept="image/*"    @change="inStoreImage()">
                            <img                                                        id="in_store_image_display_update"     src=""              class="w-100">
                        </div>

                        <!--  -->

                        <hr />

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
                        </table>

                    </form>

                </div>

                <div class="modal-footer"       style="display: flex; justify-content: space-between;">
                    <div class="left-buttons"   style="display: flex;">
                        <button type="button"   class="btn btn-danger float-left" @click="deleteData()">Delete</button>
                    </div>

                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button"   class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- <button type="button"   class="btn btn-success"                             v-if="client.status   !=  'validated'"  @click="validateData()">Validate</button> -->
                        <button type="button"   class="btn btn-primary"                                                                     @click="sendData()">Confirm</button>
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

                // Images   
                facade_image                     :   '',
                in_store_image                   :   '',
                facade_image_original_name       :   '',
                in_store_image_original_name     :   '',

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
                Journee             :   '',

                // Status
                status                  :   '',
                status_original         :   '',
                nonvalidated_details    :   ''
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

    computed : {

        ...mapGetters({
            getListeJourneyPlan             :   'journey_plan/getListeJourneyPlan'      ,
            getListeTypeClient              :   'type_client/getListeTypeClient'        ,  
            getListeJournee                 :   'journee/getListeJournee'    
        }),
    },

    mounted() {

        this.clearData("#modalClientUpdateIndexedDB")
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
            formData.append("DistrictNo"                    ,   this.client.DistrictNo)
            formData.append("DistrictNameE"                 ,   this.client.DistrictNameE)
            formData.append("CityNo"                        ,   this.client.CityNo)
            formData.append("CityNameE"                     ,   this.client.CityNameE)
            formData.append("Tel"                           ,   this.client.Tel)
            formData.append("CustomerType"                  ,   this.client.CustomerType)
            formData.append("JPlan"                         ,   this.client.JPlan)
            formData.append("Journee"                       ,   this.client.Journee)

            formData.append("facade_image"                  ,   this.client.facade_image)
            formData.append("in_store_image"                ,   this.client.in_store_image)

            formData.append("facade_image_original_name"    ,   this.client.facade_image_original_name)
            formData.append("in_store_image_original_name"  ,   this.client.in_store_image_original_name)

            formData.append("id_route_import"               ,   this.$route.params.id_route_import)

            formData.append("status"                        ,   this.client.status)
            formData.append("nonvalidated_details"          ,   this.client.nonvalidated_details)

            if(this.$connectedToInternet) {

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.client.id+"/update",   formData)
                console.log(res.data)

                if(res.status===200){

                    this.client.id_route_import     =   this.$route.params.id_route_import

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Feedback
                    this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                    // Send Client
                    this.emitter.emit('reSetUpdate' , this.client)

                    // Close Modal
                    this.$hideModal("modalClientUpdateIndexedDB")
                }
                
                else{

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)
                }
            }

            else {

                this.client.id_route_import     =   this.$route.params.id_route_import

                // Add in indexedDB
                await this.$indexedDB.$setUpdatedClients(this.client, this.$route.params.id_route_import)

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Client
                this.emitter.emit('reSetUpdate' , this.client)

                // Close Modal
                this.$hideModal("modalClientUpdateIndexedDB")
            }
        },

        async deleteData() {

            this.$showLoadingPage()

            if(this.$connectedToInternet) {

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.client.id+"/delete",   null)

                if(res.status===200){

                    this.client.id_route_import     =   this.$route.params.id_route_import

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Feedback
                    this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                    // Send Client
                    this.emitter.emit('reSetDelete' , this.client)

                    // Close Modal
                    this.$hideModal("modalClientUpdateIndexedDB")
                }
                
                else{

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)
                }
            }

            else {

                this.client.id_route_import     =   this.$route.params.id_route_import

                // Add in indexedDB
                await this.$indexedDB.$setDeletedClients(this.client, this.$route.params.id_route_import)

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Client
                this.emitter.emit('reSetDelete' , this.client)

                // Close Modal
                this.$hideModal("modalClientUpdateIndexedDB")
            }
        },

        async validateData() {

            this.$showLoadingPage()

            if(this.$connectedToInternet) {

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.client.id+"/validate",   null)

                if(res.status===200){

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    this.client.status                  =   this.client.status
                    this.client.nonvalidated_details    =   this.client.nonvalidated_details
                    this.client.id_route_import         =   this.$route.params.id_route_import

                    // Send Feedback
                    this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                    // Send Client
                    this.emitter.emit('reSetValidate' , this.client)

                    // Close Modal
                    this.$hideModal("modalClientUpdateIndexedDB")
                }
                
                else{

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)
                }
            }

            else {

                this.client.status                  =   this.client.status
                this.client.nonvalidated_details    =   this.client.nonvalidated_details
                this.client.id_route_import         =   this.$route.params.id_route_import

                // Add in indexedDB
                await this.$indexedDB.$setValidatedClients(this.client, this.$route.params.id_route_import)

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Client
                this.emitter.emit('reSetValidate' , this.client)

                // Close Modal
                this.$hideModal("modalClientUpdateIndexedDB")
            }
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                //

                let facade_image_update             =   document.getElementById("facade_image_update")
                facade_image_update.value           =   ""

                let facade_image_display_update     =   document.getElementById("facade_image_display_update")
                facade_image_display_update.src     =   ""

                //

                let in_store_image_update           =   document.getElementById("in_store_image_update")
                in_store_image_update.value         =   ""

                let in_store_image_display_update   =   document.getElementById("in_store_image_display_update")
                in_store_image_display_update.src   =   ""

                //

                this.client.facade_image                    =   '',
                this.client.in_store_image                  =   '',
                this.client.facade_image_original_name      =   '',
                this.client.in_store_image_original_name    =   '',

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

                this.client.status              =   '',

                this.willayas                   =   []  ,
                this.cites                      =   []  ,

                this.all_clients                =   []  ,
                this.close_clients              =   []

            });
        },

        getData(client, all_clients) {

            console.log(client)

            this.all_clients    =   all_clients

            this.getClientData(client)  
            this.getComboData()  

            // if(this.$isRole("FrontOffice")) {

                this.checkClients()
            // }
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

            this.client.status                  =   client.status
            this.client.status_original         =   client.status
            this.client.nonvalidated_details    =   client.nonvalidated_details

            this.client.facade_image                        =   client.facade_image
            this.client.in_store_image                      =   client.in_store_image
            this.client.facade_image_original_name          =   client.facade_image_original_name
            this.client.in_store_image_original_name        =   client.in_store_image_original_name

            // 
            this.$createFile(client.facade_image_original_name      ,   "facade_image_update")
            this.$createFile(client.in_store_image_original_name    ,   "in_store_image_update")

            // 
            let facade_image_display_update     =   document.getElementById("facade_image_display_update")
            let in_store_image_display_update   =   document.getElementById("in_store_image_display_update")

            this.base64ToImage(this.client.facade_image             ,   facade_image_display_update)            
            this.base64ToImage(this.client.in_store_image           ,   in_store_image_display_update)            

            this.setJoursGetData(client)

            await this.getCites()
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

        async facadeImage() {

            const facade_image  =   document.getElementById("facade_image_update").files[0];

            console.log(facade_image)

            if(facade_image) {

                console.log(222)

                this.client.facade_image_original_name      =   facade_image.name
                this.client.facade_image                    =   await this.$imageToBase64(facade_image)

                //

                let facade_image_display                    =   document.getElementById("facade_image_display_update")
                this.base64ToImage(this.client.facade_image, facade_image_display)
            }
        },

        //

        async inStoreImage() {

            const in_store_image  =   document.getElementById("in_store_image_update").files[0];

            if(in_store_image) {

                this.client.in_store_image_original_name    =   in_store_image.name
                this.client.in_store_image                  =   await this.$imageToBase64(in_store_image)
                
                //

                let in_store_image_display                  =   document.getElementById("in_store_image_display_update")
                this.base64ToImage(this.client.in_store_image, in_store_image_display)
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

            console.log(this.all_clients)

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