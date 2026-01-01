<template>

    <div class="login_page">
        <div class="wallpaper_login"></div>

        <div class="container">
            <div class="row">
                <form>
                    <div class="col-sm-6 mx-auto">
                        <div class="card login shadow p-3 mb-5 bg-body rounded">
                            <div class="row justify-content-center">
                                <div class="col-sm-4 mt-3">
                                    <img :src="'/images/custom/logo_buzz.png'" loading="lazy" class="card-img-top">
                                </div>
                            </div>

                            <hr class="m-0 mt-3 mb-3" />

                            <div class="card-body text-center pt-0">
                                <div class="mb-3">
                                    <h1 class="card-title mb-0 fw-bold">Login</h1>
                                </div>

                                <div>
                                    <form class="form-group mb-3">
                                        <div class="form-row mb-1">
                                            <div class="form-group col-md-12">
                                                <label class="label_above_input text-secondary form-label">Username</label>
                                                <input v-model="username" type="text" class="form-control form-control-sm"/>
                                            </div>
                                        </div>

                                        <div class="form-row mb-1">
                                            <div class="form-group col-md-12">
                                                <label class="label_above_input text-secondary form-label">Password</label>
                                                <input v-model="password"       type="password" class="form-control form-control-sm">
                                            </div>
                                        </div>

                                        <!--  -->
                                        <!--  -->
                                        <!--  -->

                                        <div class="d-grid gap-2 mb-3 mt-3">
                                            <button type="button" class="btn btn-primary m-0"  @click="login()">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</template>

<script>

    import axios            from    "axios"
    import { mapActions }   from    "vuex"

    export default {
        
        data() {
            return {
                username    :   "",
                password    :   ""
            }
        },

        methods : {

            ...mapActions("authentification" ,  [
                "setUserAction"                 ,
                "setAccessTokenAction"          ,
                "setIsAuthentificatedAction"    
            ]),

            // 

            async login() {

                // Show Loading Page
                this.$showLoadingPage()

                let formData = new FormData()

                formData.append("username"  ,   this.username)
                formData.append("password"  ,   this.password)

                let response = await this.$callApi('post',  '/login',   formData)
                console.log(response)

                // Hide Loading Page
                this.$hideLoadingPage()

                if(response.status === 200) {

                    this.$feedbackSuccess("Logged in !","You have been logged in successfully !")

                    // Update l'access token
                    window.$cookies.set('access_token'  , response.data.access_token)

                    // Update Authorization
                    axios.defaults.headers['Authorization'] = `Bearer ${window.$cookies.get('access_token')}`

                    // Update State
                    this.setUserAction(response.data.user)
                    this.setAccessTokenAction(response.data.access_token)
                    this.setIsAuthentificatedAction(true)

                    if(this.$isRole("FrontOffice")) {
                        this.$router.push('/front-office')
                    }

                    else {
                        this.$router.push('/')
                    }
                }

                else {
                    this.$showErrors("Error !", response.data.errors)
                }
            }
        },
    };

</script>

<style scoped>

.label_above_input {
    position    : absolute;
    top         : -10px;
    left        : 13px;
    background  : #fff;
    font-size   : 13px;
}

/* Target the label when the input inside the same .form-group is focused */
.form-group:focus-within > .label_above_input {
    color: #2c78bd !important; /* Use !important to ensure it overrides other styles */
}

</style>