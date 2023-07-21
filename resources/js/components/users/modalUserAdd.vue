<template>

    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add a New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <form>

                        <div class="mb-3">
                            <label for="nom"                   class="form-label">Name</label>
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
                                <option value="BackOffice">BackOffice</option>
                                <option value="FrontOffice">FrontOffice</option>
                            </select>
                        </div>

                        <div class="mb-3" v-if="user.type_user  ==  'BackOffice'">
                            <label for="max_route_import"       class="form-label">Max Route Imports</label>
                            <input type="number"                class="form-control"        id="max_route_import"           v-model="user.max_route_import">
                        </div>

                        <!--  -->

                        <div class="mb-3">

                            <label for="Route Imports"               class="form-label">Route Imports</label>

                            <Multiselect
                                @select             =   "setListeRouteImport()"
                                @deselect           =   "setListeRouteImport()"
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
                nom                     :   '',
                email                   :   '',
                tel                     :   '',
                company                 :   '',

                type_user               :   '',

                max_route_import        :   0 ,

                liste_route_import      :   null,

                password                :   '',
                password_confirmation   :   ''
            },

            liste_route_import          :   []
        }
    },

    mounted() {

        this.clearData("#addUserModal")
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

            formData.append("max_route_import"          , this.user.max_route_import)

            formData.append("liste_route_import"        , JSON.stringify(this.user.liste_route_import))

            formData.append("password"                  , this.user.password)
            formData.append("password_confirmation"     , this.user.password_confirmation)

            const res   = await this.$callApi('post' ,   '/users/store'    ,   formData)         
            console.log(res.data)

            // Hide Loading Page
            this.$hideLoadingPage()

            if(res.status===200){
                
                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])
                
                // Close Modal
                this.$hideModal("addUserModal")

                // Reload DataTable
                await this.$parent.setDataTable()
			}
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                this.user.nom                       =   ''
                this.user.email                     =   ''
                this.user.tel                       =   ''
                this.user.company                   =   ''
                this.user.type_user                 =   ''

                this.user.max_route_import          =   0

                this.user.liste_route_import        =   null

                this.user.password                  =   ''
                this.user.password_confirmation     =   ''

                this.liste_route_import             =   []
            });
        },

        getData() {

            this.getComboData()  
        },

        async getComboData() {

            const res               =   await this.$callApi("post",       "/route_import",        null)

            let liste_route_import  =   res.data

            for (let i = 0; i < liste_route_import.length; i++) {

                this.liste_route_import.push({ value : liste_route_import[i].id , label : liste_route_import[i].libelle})
            }
        },

        //

        setListeRouteImport() {   
        }
    }
};
</script>