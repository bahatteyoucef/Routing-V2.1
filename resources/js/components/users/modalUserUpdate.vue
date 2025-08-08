<template>

    <!-- Modal -->
    <div class="modal fade" id="ModalUserUpdate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update The User : {{ user.nom_original }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <form>

                        <div class="mb-3">
                            <label for="nom"                    class="form-label">Name</label>
                            <input type="text"                  class="form-control"        id="nom"                       v-model="user.nom">
                        </div>

                        <div class="mb-3">
                            <label for="email"                  class="form-label">Email</label>
                            <input type="text"                  class="form-control"        id="email"                      v-model="user.email">
                        </div>

                        <div class="mb-3">
                            <label for="tel"                    class="form-label">Tel</label>
                            <input type="text"                  class="form-control"        id="tel"                        v-model="user.tel">
                        </div>

                        <div class="mb-3">
                            <label for="company"                class="form-label">Company</label>
                            <input type="text"                  class="form-control"        id="company"                    v-model="user.company">
                        </div>

                        <div class="mb-3">
                            <label for="type_user"              class="form-label">Type User</label>
                            <select                             class="form-select"         id="type_user"                  v-model="user.type_user">
                                <option v-if="$isRole('Super Admin')" value="BU Manager">BU Manager</option>
                                <option value="BackOffice">BackOffice</option>
                                <option value="FrontOffice">FrontOffice</option>
                                <option value="Viewer">Viewer</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status"              class="form-label">Status</label>
                            <select                             class="form-select"         id="status"                  v-model="user.status">
                                <option value="enabled">enabled</option>
                                <option value="disabled">disabled</option>
                            </select>
                        </div>

                        <div class="mb-3" v-if="(user.type_user  ==  'BU Manager')">
                            <label for="max_route_import"       class="form-label">Max Route Imports</label>
                            <input type="number"                class="form-control"        id="max_route_import"           v-model="user.max_route_import">
                        </div>

                        <!--  -->

                        <div class="mb-3"   v-if="(user.type_user  ==  'Viewer')||(user.type_user  ==  'BackOffice')||(user.type_user  ==  'BU Manager')">

                            <div class="mb-3">
                                <label for="Route Imports"               class="form-label">Route Imports</label>

                                <Multiselect
                                    v-model             =   "user.liste_route_import"
                                    :options            =   "liste_route_import"
                                    mode                =   "tags" 
                                    placeholder         =   "Select Maps"
                                    class               =   "mt-1"

                                    :close-on-select    =   "false"
                                    :searchable         =   "true"
                                    :create-option      =   "true"

                                    :canDeselect        =   "true"
                                    :canClear           =   "true"
                                    :allowAbsent        =   "false"
                                />
                            </div>

                        </div>

                        <!--  -->

                        <div class="mb-3"   v-if="user.type_user    ==  'FrontOffice'">

                            <div class="mb-3">
                                <label for="accuracy"       class="form-label">Accuracy</label>
                                <input type="number"        class="form-control"        id="accuracy"                       v-model="user.accuracy">
                            </div>

                            <div class="mb-3">
                                <label for="Route Imports"               class="form-label">Route Imports</label>

                                <Multiselect
                                    @select             =   "getDistricts()"
                                    @deselect           =   "getDistricts()"
                                    @clear              =   "getDistricts()"

                                    v-model             =   "user.selected_route_import"
                                    :options            =   "liste_route_import"
                                    mode                =   "single" 
                                    placeholder         =   "Select Map"
                                    class               =   "mt-1"

                                    :close-on-select    =   "false"
                                    :searchable         =   "true"
                                    :create-option      =   "true"

                                    :canDeselect        =   "true"
                                    :canClear           =   "true"
                                    :allowAbsent        =   "false"
                                />
                            </div>

                            <div class="mb-3">
                                <label for="District"               class="form-label">District</label>

                                <Multiselect
                                    @select             =   "getCities()"
                                    @deselect           =   "getCities()"
                                    @clear              =   "getCities()"

                                    v-model             =   "user.selected_district"
                                    :options            =   "districts"
                                    mode                =   "single"
                                    placeholder         =   "Select District"
                                    class               =   "mt-1"

                                    :close-on-select    =   "false"
                                    :searchable         =   "true"
                                    :create-option      =   "false"

                                    :canDeselect        =   "true"
                                    :canClear           =   "true"
                                    :allowAbsent        =   "true"
                                />
                            </div>

                            <div class="mb-3">
                                <label for="Cities"               class="form-label">Cities</label>

                                <Multiselect
                                    v-model             =   "user.selected_cities"
                                    :options            =   "cities"
                                    mode                =   "tags"
                                    placeholder         =   "Select Cities"
                                    class               =   "mt-1"

                                    :close-on-select    =   "false"
                                    :searchable         =   "true"
                                    :create-option      =   "false"

                                    :canDeselect        =   "true"
                                    :canClear           =   "true"
                                    :allowAbsent        =   "true"
                                />
                            </div>

                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <label for="password"               class="form-label">password</label>
                            <input type="password"              class="form-control"        id="password"                   v-model="user.password">
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation"  class="form-label">Password Confirmation</label>
                            <input type="password"              class="form-control"        id="password_confirmation"      v-model="user.password_confirmation">
                        </div>                        

                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"   @click="sendData()">Confirm</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import Multiselect  from    '@vueform/multiselect'

export default {

    data() {
        return {

            user            :   {
                nom_original            :   ''      ,

                id                      :   ''      ,
                nom                     :   ''      ,
                email                   :   ''      ,
                tel                     :   ''      ,
                company                 :   ''      ,
                type_user               :   ''      ,
                status                  :   ''      ,

                accuracy                :   0       ,
                max_route_import        :   0       ,

                selected_route_import   :   null    ,
                liste_route_import      :   null    ,

                selected_district       :   null    ,
                selected_cities         :   []      ,

                password                :   ''      ,
                password_confirmation   :   ''
            },

            liste_route_import  :   [],
            districts           :   [],
            cities              :   []
        }
    },

    mounted() {
        this.clearData("#ModalUserUpdate")
    },  

    components : {
        Multiselect
    },

    methods : {

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("nom"                       , this.user.nom)
            formData.append("email"                     , this.user.email)
            formData.append("tel"                       , this.user.tel)
            formData.append("company"                   , this.user.company)
            formData.append("type_user"                 , this.user.type_user)
            formData.append("status"                    , this.user.status)            

            formData.append("accuracy"                  , this.user.accuracy)
            formData.append("max_route_import"          , this.user.max_route_import)

            formData.append("selected_route_import"     , this.user.selected_route_import)
            formData.append("liste_route_import"        , JSON.stringify(this.user.liste_route_import))

            formData.append("selected_district"         , this.user.selected_district)
            formData.append("selected_cities"           , JSON.stringify(this.user.selected_cities))

            if(this.user.password) {
                formData.append("password"                  , this.user.password)
                formData.append("password_confirmation"     , this.user.password_confirmation)
            }

            const res   =   await this.$callApi('post' ,   '/users/'+this.user.id+'/update'    ,   formData)         

            if(res.status===200){

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])

                // Hide Loading Page
                this.$hideLoadingPage()

                // Reload DataTable
                await this.$parent.setDataTable()

                // Close Modal
                await this.$hideModal("ModalUserUpdate")
			}
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)

                // Hide Loading Page
                this.$hideLoadingPage()
			}
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                this.user.nom_original              =   ''
                this.user.nom                       =   ''
                this.user.email                     =   ''
                this.user.tel                       =   ''
                this.user.company                   =   ''
                this.user.type_user                 =   ''
                this.user.status                    =   ''

                this.user.accuracy                  =   0
                this.user.max_route_import          =   0

                this.user.selected_route_import     =   null
                this.user.liste_route_import        =   null

                this.user.selected_district         =   null
                this.user.selected_cities           =   []
                this.user.password                  =   ''
                this.user.password_confirmation     =   ''

                this.liste_route_import             =   []
                this.districts                      =   []
                this.cities                         =   []
            });
        },

        async getData(user) {
            await this.getComboData()  
            await this.getUserData(user)  
        },

        async getUserData(user) {

            const res                   =   await this.$callApi("post"  ,   "/users/"+user.id+"/show"    ,   null)

            this.user.nom_original          =   res.data.nom

            this.user.id                    =   res.data.id                 
            this.user.nom                   =   res.data.nom                 
            this.user.email                 =   res.data.email   
            this.user.tel                   =   res.data.tel                 
            this.user.company               =   res.data.company    
            this.user.type_user             =   res.data.type_user        
            this.user.status                =   res.data.status

            this.user.accuracy              =   res.data.accuracy
            this.user.max_route_import      =   res.data.max_route_import        

            if((this.user.type_user  ==  "Viewer")||(this.user.type_user  ==  "BackOffice")||(this.user.type_user  ==  "BU Manager")) {

                this.user.liste_route_import        =   res.data.liste_route_import
            }

            if(this.user.type_user  ==  "FrontOffice") {

                this.user.selected_route_import     =   res.data.liste_route_import

                await this.getDistricts()

                this.user.selected_district         =   res.data.DistrictNo

                await this.getCities()

                this.user.selected_cities           =   res.data.cities
            }
        },

        async getComboData() {

            const res               =   await this.$callApi("post",       "/route_import/combo",        null)

            let liste_route_import  =   res.data

            for (let i = 0; i < liste_route_import.length; i++) {

                this.liste_route_import.push({ value : liste_route_import[i].id , label : liste_route_import[i].libelle})
            }
        },

        //

        async getDistricts() {   

            this.$showLoadingPage()

            setTimeout(async () => {
                
                this.districts              =   []
                this.user.selected_district =   null

                this.user.cities            =   []
                this.user.selected_cities   =   []

                if(this.user.selected_route_import) {

                    const res_3         =   await this.$callApi("post"  ,   "/route_import/"+this.user.selected_route_import+"/districts"   ,   null)
                    let districts       =   res_3.data

                    for (let i = 0; i < districts.length; i++) {

                        this.districts.push({ value : districts[i].DistrictNo , label : districts[i].DistrictNameE  +   " (" + districts[i].DistrictNo + ")"})
                    }
                }

            }, 222);

            this.$hideLoadingPage()
        },

        async getCities() {

            this.$showLoadingPage()

            const res_3         =   await this.$callApi("post"  ,   "/rtm_willayas/"+this.user.selected_district+"/rtm_cites"       ,   null)
            let cities          =   res_3.data

            for (let i = 0; i < cities.length; i++) {

                this.cities.push({ value : cities[i].CITYNO , label : cities[i].CITYNO +   "- "    +   cities[i].CityNameE})
            }

            this.user.cities            =   []
            this.user.selected_cities   =   []

            this.$hideLoadingPage()
        }
    }
};
</script>