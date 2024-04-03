<template>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <div class="row">
                        <h4>Profile Details</h4>
                    </div>

                    <!-- Details -->
                    <form class="mt-5">

                        <div class="mb-3">
                            <label for="old_password"               class="form-label">Old Password : </label>
                            <input type="password"                  class="form-control"        id="old_password"                   v-model="user.old_password">
                        </div>

                        <div class="mb-3">
                            <label for="new_password"               class="form-label">New Password : </label>
                            <input type="password"                  class="form-control"        id="new_password"                   v-model="user.new_password">
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation"  class="form-label">Confirm Password : </label>
                            <input type="password"                  class="form-control"        id="new_password_confirmation"      v-model="user.new_password_confirmation">
                        </div>

                        <!--  -->

                        <div class="container mt-5">
                            <div class="row justify-content-center">
                                <div class="col-sm-3 mt-3">
                                    <button type="button" class="btn btn-secondary w-100"   @click="$goBack()">Back</button>
                                </div>
    
                                <div class="col-sm-3 mt-3">
                                    <button type="button" class="btn btn-primary w-100"   @click="sendData()">Confirm</button>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

</template>

<script>

import {mapGetters, mapActions}     from    "vuex"

export default {

    data() {
        return {

            user            :   {
                old_password                :   '',

                new_password                :   '',
                new_password_confirmation   :   '',
            },
        }
    },

    computed : {

        ...mapGetters({
            getUser                     :   'authentification/getUser'              ,
            getAccessToken              :   'authentification/getAccessToken'       ,
            getIsAuthentificated        :   'authentification/getIsAuthentificated' ,

            getAddClient                :   'client/getAddClient'                   ,
            getUpdateClient             :   'client/getUpdateClient'                
        }),
    },

    methods : {

        ...mapActions("authentification" ,  [
          "setUserAction"                 ,
          "setAccessTokenAction"          ,
          "setIsAuthentificatedAction"    
        ]),

        //

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("old_password"                  , this.user.old_password)

            formData.append("new_password"                  , this.user.new_password)
            formData.append("new_password_confirmation"     , this.user.new_password_confirmation)

            const res   =   await this.$callApi('post' ,   '/users/'+this.$route.params.id_user+'/update/password'    ,   formData)         

            // Hide Loading Page
            this.$hideLoadingPage()

            if(res.status===200){
                
                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])

                // Close Modal
                this.logOut()
			}
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },

        //

        async logOut() {

            const res = await this.$callApi("post", "/logout" , null)

            if(res.status ==  200) {

                // Success
                this.$feedbackSuccess("Logged Out !" , "")

                // Update State
                this.setUserAction({})
                this.setAccessTokenAction("")
                this.setIsAuthentificatedAction(false)

                // Router
                this.$goTo("/login")
            }

            else {

                this.$showErrors("Error !" , res.errors)
            }
        },
    }

};
</script>