<template>

    <!-- Loading -->
    <loading-page></loading-page>

    <!-- Internet Error Page    -->
    <internet-error-page    v-show="show_internet_error_page  ==  false"></internet-error-page>

    <!-- Login      -->
    <section v-if="component_login">
        <login-page></login-page>
    </section>

    <!-- Dashboard  -->
    <div v-if="isAuthentificated">
        <!-- Dashboard + Route -->
        <section v-if="component_dashboard">

            <div class="container-scroller" id="page_route">

                <!-- BackOffice + Super Admin -->
                <div v-if="(($isRole('Super Admin'))||($isRole('BackOffice')))">

                    <header-part></header-part>

                    <div class="container-fluid page-body-wrapper">
                        <div class="main-panel w-100 animate__animated" id="main_content">
                            <router-view :key="$route.path"></router-view>
                        </div>
                    </div>

                </div>

                <!-- FrontOffice -->
                <div v-if="$isRole('FrontOffice')">

                    <header-store-part></header-store-part>       

                    <div class="container-fluid page-body-wrapper">
                        <div class="main-panel w-100 animate__animated" id="main_content">
                            <router-view :key="$route.path"></router-view>
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </div>

    <!-- Install Banner         -->
    <div v-if="$isRole('FrontOffice')" v-show="show_install_button" id="BlockInstall" class="w-100 p-3">
        <div class="row">
            <div @click="closeInstallBanner()"  class="col-2 p-0 d-flex justify-content-center align-items-center">
                <i class="mdi mdi-close font-weight-bold text-light" style="font-size: 30px;"></i>
            </div>

            <div class="col-7 text-light p-0">
                <div><h5>Routing V2.0</h5></div>
                <div><span>Get our app for better experience.</span></div>
            </div>

            <div class="col-3 p-0 d-flex justify-content-center align-items-center">
                <button id="BlockInstallButton" class="btn btn-light"><b>Install</b></button>
            </div>
        </div>
    </div>

</template>

<script>

    import {mapGetters, mapActions}     from "vuex"

    export default {

        data() {

            return {

                user                        :   {}      ,

                isAuthentificated           :   false   ,

                component_login             :   false   ,
                component_dashboard         :   false   ,

                //

                show_install_button         :   false   ,

                //

                show_internet_error_page    :   window.navigator.onLine
            }
        },

        computed : {

            ...mapGetters({
                getUser                     :   'authentification/getUser'              ,
                getAccessToken              :   'authentification/getAccessToken'       ,
                getIsAuthentificated        :   'authentification/getIsAuthentificated'
            }),
        },

        beforeMount() {

            this.isAuthentificated              =   false
            this.show_internet_error_page       =   window.navigator.onLine
        },

        async mounted() {

            //
            window.addEventListener('online'    , ()    =>  {this.$connectedToInternet  =   true    ,   this.show_internet_error_page   =   true    });
            window.addEventListener('offline'   , ()    =>  {this.$connectedToInternet  =   false   ,   this.show_internet_error_page   =   false   });

            // 
            this.isAuthentificated      =   await this.checkIfUserIsAuthentificated()

            if(this.isAuthentificated) {

                this.user   =   this.getUser

                if(this.user.nom) {

                    console.log(this.user.nom)
                    console.log(this.$isRole("FrontOffice"))

                    if(this.$isRole("FrontOffice")) {

                        // Set Local DB
                        await this.$indexedDB.$indexedDB_intialiazeSetDATA()
                    }

                    //

                    this.component_login        =   false
                    this.component_dashboard    =   true

                    this.isAuthentificated      =   true
                }

                else {

                    this.component_login        =   true
                    this.component_dashboard    =   false

                    this.isAuthentificated      =   false
                }
            }

            else {

                // Update State
                this.setUserAction({})
                this.setAccessTokenAction("")
                this.setIsAuthentificatedAction(false)

                // Router
                this.$goTo("/login")
            }

            this.pwaFunction()
        },

        methods : {

            ...mapActions("authentification" ,  [
                "setUserAction"                 ,
                "setAccessTokenAction"          ,
                "setIsAuthentificatedAction"    
            ]),

            //

            async checkIfUserIsAuthentificated() {

                const res                   =   await this.$callApi("post"  ,   "/user/isAuthentificated",   null)
                
                if(res.data    ==  "") {

                    localStorage.removeItem("vuex")
                    return false
                }

                return true
            },

            pwaFunction() {

                setTimeout(() => {

                    let deferredPrompt;

                    window.addEventListener('beforeinstallprompt', async (e) => {

                        // Prevent the browser's default prompt from showing
                        e.preventDefault();

                        // 
                        deferredPrompt = e;

                        this.show_install_button  = true
                
                        await this.$nextTick()

                        //
                        console.log("test_1")
                    });

                    console.log("test_2")

                    const BlockInstallButton = document.getElementById('BlockInstallButton');
                    console.log(BlockInstallButton)

                    if(BlockInstallButton) {

                        BlockInstallButton.addEventListener('click', async () => {

                            this.$showLoadingPage()

                            console.log(22222)

                            if (deferredPrompt !== null) {

                                deferredPrompt.prompt();

                                const { outcome } = await deferredPrompt.userChoice;

                                if (outcome === 'accepted') {
                                    deferredPrompt = null;
                                }
                            }

                            this.$hideLoadingPage()
                        });
                    }

                }, 555)
            },

            closeInstallBanner() {

                const BlockInstall  =   document.getElementById("BlockInstall")

                BlockInstall.remove()
            }
        },

        watch : {

            getUser(newUser, oldUser) {

                if(newUser.nom) {

                    this.component_login        =   false
                    this.component_dashboard    =   true

                    this.isAuthentificated      =   true

                }

                else {

                    this.component_login        =   true
                    this.component_dashboard    =   false

                    this.isAuthentificated      =   false
                }
            },
        }
    }

</script>

<style>

:root {
    --popper-theme-background-color: #333333;
    --popper-theme-background-color-hover: #333333;
    --popper-theme-text-color: #ffffff;
    --popper-theme-border-width: 0px;
    --popper-theme-border-style: solid;
    --popper-theme-border-radius: 6px;
    --popper-theme-padding: 7px;
    --popper-theme-box-shadow: 0 6px 30px -6px rgba(0, 0, 0, 0.25);
}

</style>

<style src="@vueform/multiselect/themes/default.css"></style>