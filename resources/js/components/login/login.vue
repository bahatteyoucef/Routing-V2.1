<template>
    <div class="login_page">

        <div class="container mb-3">
            <div class="row">

                <form>
                    
                    <div class="col-lg-4 col-md-6 col-sm-8 mx-auto">
                        <div class="card login shadow p-3 mb-5 bg-body rounded">

                            <div class="w-25 align-self-center">
                                <img :src="'/images/logo.png'" class="card-img-top">
                            </div>

                            <hr class="m-0 mt-3 mb-3" />

                            <div class="card-body text-center pt-0">
                                <div class="mb-3">
                                    <h1 class="card-title">Login</h1>
                                </div>

                                <div>
                                    <form class="form-group mb-3">
                                        <input v-model="email"          type="text"     class="form-control form-control-lg"    placeholder="Email"   >
                                        <input v-model="password"       type="password" class="form-control form-control-lg"    placeholder="Password">

                                        <div class="d-grid gap-2 mb-3 mt-3">
                                            <button type="button" class="btn btn-outline-dark m-0"  @click="login()">Login</button>
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

    import axios                        from "axios"

    import {mapGetters, mapActions}     from "vuex"

    export default {
        
        data() {

            return {

                email     : "",
                password  : ""
            }
        },

        computed : {

            ...mapGetters({
                getUser                     :   'authentification/getUser'              ,
                getAccessToken              :   'authentification/getAccessToken'       ,
                getIsAuthentificated        :   'authentification/getIsAuthentificated'
            }),
        },

        methods : {

            ...mapActions("authentification" ,  [
                "setUserAction"                 ,
                "setAccessTokenAction"          ,
                "setIsAuthentificatedAction"    
            ]),

            // 

            async login() {

                try {

                    // Show Loading Page
                    this.$showLoadingPage()

                    let formData = new FormData()

                    formData.append("email"     ,   this.email)
                    formData.append("password"  ,   this.password)

                    let response = await this.$callApi('post' ,   '/login'    ,   formData)
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

                            // Set Local DB
                            await this.$indexedDB.$indexedDB_intialiazeSetDATA()
                        }

                        // Route To index
                        this.$router.push('/')
                    }

                    else {
                        this.$showErrors("Error !", [response.data.errors])
                    }
                }

                catch(e) {

                }
            },

            // 

            async logout() {

                try {

                    let response = await this.$callApi('post' ,   '/logout'    ,   null)

                    if(response.status === 200) {

                        this.$feedbackSuccess("Logged out !","You have been logged out successfully !")

                        // Delete l'access token
                        window.$cookies.remove('access_token')
                    }
                    
                    else {
                        this.$showErrors("error", response.errors)
                    }
                }
                catch(e) {

                }
            },

            // 

            async getAuthUser() {

                try {

                    let response = await this.$callApi('post' ,   '/user'    ,   null)
                }
                catch(e) {

                }
            }

        },

    };
</script>
