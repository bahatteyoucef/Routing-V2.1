<template>

    <!-- Loading -->
    <loading-page></loading-page>

    <!-- Login -->
    <section v-if="component_login">
        <login-page></login-page>
    </section>

    <!-- Dashboard + Route -->
    <section v-if="component_dashboard">

        <div class="container-scroller" id="page_route">

            <header-part></header-part>

            <div class="container-fluid page-body-wrapper">

                <sidebar-part></sidebar-part>

                <!-- Case Not Route -->
                <div class="main-panel" id="main_content">

                    <router-view></router-view>
                    <!-- <footer-part></footer-part> -->

                </div> 

            </div>
        </div>
    </section>

</template>

<script>

    import {mapGetters, mapActions}     from "vuex"

    import axios                        from "axios"

    export default {

        data() {

            return {

                user                    :   {}      ,

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

        mounted() {

            this.user   =   this.getUser

            if(this.user.nom) {

                this.component_login     =   false
                this.component_dashboard =   true
            }

            else {

                this.component_login     =   true
                this.component_dashboard =   false
            }
        },

        watch : {

            getUser(newUser, oldUser) {

                if(newUser.nom) {

                    this.component_login     =   false
                    this.component_dashboard =   true
                }

                else {

                    this.component_login     =   true
                    this.component_dashboard =   false
                }
            }
        }
    }

</script>

