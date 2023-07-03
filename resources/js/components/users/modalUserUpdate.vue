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
                            <label for="nom"                    class="form-label">Nom</label>
                            <input type="text"                  class="form-control"        id="nom"                        v-model="user.nom">
                        </div>

                        <div class="mb-3">
                            <label for="email"                  class="form-label">Email</label>
                            <input type="text"                  class="form-control"        id="email"                      v-model="user.email">
                        </div>

                        <!--  

                        <div class="mb-3">
                            <label for="type_user"              class="form-label">Type User</label>
                            <select                             class="form-select"         id="type_user"                  v-model="user.type_user">
                                <option value="Administrateur">Administrateur</option>
                                <option value="RTM Manager">RTM Manager</option>
                                <option value="BU Manager">BU Manager</option>
                            </select>
                        </div>

                        -->

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

export default {

    data() {
        return {

            user            :   {
                nom_original        :   '',

                id                  :   '',
                nom                 :   '',
                email               :   '',

                type_user           :   ''
            },

            organisations   :   []
        }
    },

    mounted() {

        this.clearData("#updateUserModal")
    },  

    methods : {

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("nom"                       , this.user.nom)
            formData.append("email"                     , this.user.email)
            formData.append("type_user"                 , this.user.type_user)

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

                this.user.nom_original              =   ''

                this.user.nom                       =   ''
                this.user.email                     =   ''

                this.user.type_user                 =   ''
            });
        },

        getData(user) {

            this.getUserData(user)  
            this.getComboData()  
        },

        async getUserData(user) {

            const res                   =   await this.$callApi("post"  ,   "/users/"+user.id+"/show"    ,   null)

            this.user.nom_original      =   res.data.nom

            this.user.id                =   res.data.id                 
            this.user.nom               =   res.data.nom                 
            this.user.email             =   res.data.email   

            this.user.type_user         =   res.data.role
        },

        async getComboData() {

        }
    }

};
</script>