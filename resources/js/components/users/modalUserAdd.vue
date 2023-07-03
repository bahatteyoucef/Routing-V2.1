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
                            <label for="nom"                    class="form-label">Nom</label>
                            <input type="text"                  class="form-control"        id="nom"                        v-model="user.nom">
                        </div>

                        <div class="mb-3">
                            <label for="email"                  class="form-label">Email</label>
                            <input type="text"                  class="form-control"        id="email"                      v-model="user.email">
                        </div>

                        <div class="mb-3">
                            <label for="password"               class="form-label">password</label>
                            <input type="password"              class="form-control"        id="password"                   v-model="user.password">
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation"  class="form-label">Password Confirmation</label>
                            <input type="password"              class="form-control"        id="password_confirmation"      v-model="user.password_confirmation">
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
                nom                     :   '',
                email                   :   '',

                password                :   '',
                password_confirmation   :   '',

                type_user               :   ''
            },
        }
    },

    mounted() {

        this.clearData("#addUserModal")
    },  

    methods : {

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("nom"                       , this.user.nom)
            formData.append("email"                     , this.user.email)
            formData.append("password"                  , this.user.password)
            formData.append("password_confirmation"     , this.user.password_confirmation)
            // formData.append("type_user"                 , this.user.type_user)

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
                this.user.type_user                 =   ''
                this.user.email                     =   ''
                this.user.password                  =   ''
                this.user.password_confirmation     =   ''
            });
        },

        getData() {

            this.getComboData()  
        },

        async getComboData() {

        }
    }

};
</script>