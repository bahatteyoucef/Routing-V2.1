<template>
    <div class="content-wrapper h-100" style="padding : 5px;">
        <section class="dashboard mt-4">
            <!-- Header Options -->
            <div class="container">
                <div class="row justify-content-start h-100 mt-2">
                    <div class="col-sm-10 d-flex align-items-center">
                        <i
                            class="mdi mdi-dice-1 mr-1 ml-1 fw-bold"
                            style="color: #2c78bd"
                        ></i
                        ><span class="fw-bold mb-1">{{ getUser.first_name }} {{ getUser.last_name }}</span>
                    </div>
                </div>

                <!-- Index Options -->
                <div class="row justify-content-center h-100 mt-4 align-items-stretch">
                    <div
                        class="card col-5 m-1 shadow-sm rounded min_card_height text-center h-100"
                        @click="addClient()"
                    >
                        <div class="text-center" style="height: 50px">
                            <img
                                class="card-img-top"
                                :src="'/images/front_office_images/store.png'"
                                style="height: 100%; width: auto"
                            />
                        </div>
                        <div class="card-body p-0 mt-3">
                            <p class="card-text font-weight-bold">New Client</p>
                        </div>
                    </div>

                    <div
                        class="card col-5 m-1 shadow-sm rounded min_card_height text-center h-100"
                        @click="showProfile()"
                    >
                        <div class="text-center" style="height: 50px">
                            <img
                                class="card-img-top"
                                :src="'/images/front_office_images/profile.png'"
                                style="height: 100%; width: auto"
                            />
                        </div>
                        <div class="card-body p-0 mt-3">
                            <p class="card-text font-weight-bold">Profile</p>
                        </div>
                    </div>

                    <div
                        class="card col-5 m-1 shadow-sm rounded min_card_height text-center h-100"
                        @click="goToMap()"
                    >
                        <div class="text-center" style="height: 50px">
                            <img
                                class="card-img-top"
                                :src="'/images/front_office_images/map_marker.png'"
                                style="height: 100%; width: auto"
                            />
                        </div>
                        <div class="card-body p-0 mt-3">
                            <p class="card-text font-weight-bold">Map</p>
                        </div>
                    </div>

                    <div
                        class="card col-5 m-1 shadow-sm rounded min_card_height text-center h-100"
                        @click="showClientsByStatus()"
                    >
                        <div class="text-center" style="height: 50px">
                            <img
                                class="card-img-top"
                                :src="'/images/front_office_images/group_clients.png'"
                                style="height: 100%; width: auto"
                            />
                        </div>
                        <div class="card-body p-0 mt-3">
                            <p class="card-text font-weight-bold">Clients</p>
                        </div>
                    </div>

                    <div
                        class="card col-10 m-1 shadow-sm rounded min_card_height text-center h-100"
                        @click="logOut()"
                    >
                        <div class="text-center" style="height: 50px">
                            <img
                                class="card-img-top"
                                :src="'/images/front_office_images/logout.png'"
                                style="height: 100%; width: auto"
                            />
                        </div>
                        <div class="card-body p-0 mt-3">
                            <p class="card-text font-weight-bold">Log Out</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="row mt-3" style="position: fixed; bottom: 10px;">
            <span class="text-small" style="color: rgb(215, 72, 31);">Software Version : V2.1</span>
        </div>
    </div>
</template>

<script>

import {mapGetters, mapActions} from    "vuex"

export default {

    data() {
        return {
            liste_route_import  :   [],
        }
    },

    computed : {
        ...mapGetters({
            getUser         :   'authentification/getUser'  ,
            getIsOnline     :   'internet/getIsOnline'
        }),
    },

    methods : {

        ...mapActions("authentification" ,  [
            "setUserAction"                 ,
            "setAccessTokenAction"          ,
            "setIsAuthentificatedAction"    
        ]),

        async addClient() {
            this.$router.push('/route_import/'+this.getUser.id_route_import+'/clients/add')
        },

        goToMap() {
            this.$router.push('/route/frontoffice/obs/route_import/'+this.getUser.id_route_import+'/details')
        },

        showClientsByStatus() {
            this.$router.push('/route_import/'+this.getUser.id_route_import+'/clients/by_status')
        },

        async showProfile() {
            this.$router.push('/users/'+this.getUser.id+'/show')
        },

        async logOut() {

            const res   =   await this.$callApi("post", "/logout" , null)

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
}

</script>

<style scoped>

.min_card_height {

    min-height: 145px;
}

</style>