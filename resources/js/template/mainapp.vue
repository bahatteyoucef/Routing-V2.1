<template>

    <!-- Loading -->
    <loading-page></loading-page>

    <!-- Login -->
    <section v-if="component_login">
        <login-page></login-page>
    </section>

    <div v-if="isAuthentificated">
        <!-- Dashboard + Route -->
        <section v-if="component_dashboard">

            <div class="container-scroller" id="page_route">

                <header-part></header-part>

                <div class="container-fluid page-body-wrapper">

                    <div class="main-panel w-100" id="main_content">

                        <router-view :key="$route.path"></router-view>

                    </div>

                </div>
            </div>
        </section>
    </div>

</template>

<script>

    import {mapGetters, mapActions}     from "vuex"

    export default {

        data() {

            return {

                user                    :   {}      ,

                isAuthentificated       :   false   ,

                component_login         :   false   ,
                component_dashboard     :   false
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

            this.isAuthentificated  =   false
        },

        async mounted() {

            this.isAuthentificated      =   await this.checkIfUserIsAuthentificated()

            if(this.isAuthentificated) {

                this.user   =   this.getUser

                if(this.user.nom) {

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
        },

        methods : {

            ...mapActions("authentification" ,  [
                "setUserAction"                 ,
                "setAccessTokenAction"          ,
                "setIsAuthentificatedAction"    
            ]),

            async checkIfUserIsAuthentificated() {

                const res                   =   await this.$callApi("post"  ,   "/user/isAuthentificated",   null)
                
                if(res.data    ==  "") {

                    localStorage.removeItem("vuex")
                    return false
                }

                return true
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
            }
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