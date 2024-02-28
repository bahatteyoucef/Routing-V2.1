<template>

    <!-- Modal -->
    <div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update The User : {{ user.nom_original }}</h5>
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

                                :close-on-select    =   "true"
                                :searchable         =   "true"

                                :canDeselect        =   "true"
                                :canClear           =   "false"
                                :allowAbsent        =   "true"
                            />

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
                nom_original            :   '',

                id                      :   '',
                nom                     :   '',
                email                   :   '',
                tel                     :   '',
                company                 :   '',
                type_user               :   'BackOffice',

                max_route_import        :   0 ,

                liste_route_import      :   null,
            },

            liste_route_import          :   []
        }
    },

    mounted() {

        this.clearData("#updateUserModal")
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

            console.log(this.user.liste_route_import)

            const res   =   await this.$callApi('post' ,   '/users/'+this.user.id+'/update'    ,   formData)         

            // Hide Loading Page
            this.$hideLoadingPage()

            if(res.status===200){
                
                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])

                // Close Modal
                this.$hideModal("updateUserModal")

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

                this.liste_route_import             =   []
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

            this.user.max_route_import      =   res.data.max_route_import        

            this.user.liste_route_import    =   res.data.liste_route_import   
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