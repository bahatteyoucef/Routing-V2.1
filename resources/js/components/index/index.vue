<template>
    <div v-if="$isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice')||$isRole('Viewer')" class="content-wrapper" style="padding : 15px;">
        <section class="dashboard">

            <div class="page-header">

                <div class="row w-100">
                    <div class="col-10">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                                <i class="mdi mdi-home"></i>
                            </span> Dashboard 
                        </h3>
                    </div>
                </div>

            </div>

            <div class="row">

                <div    class="col-md-3 stretch-card grid-margin" 
                        v-for="route_import in liste_route_import" :key="route_import.id">

                    <div class="card bg-gradient-info card-img-holder text-white">
                        <div class="card-body p-3">
                            <img :src="'/template/images/dashboard/circle.svg'"    class="card-img-absolute"   alt="circle-image" />

                            <div class="row">
                                <div class="col-9">
                                    <h4 class="font-weight-normal mb-3">{{route_import.libelle}}</h4>
                                </div>

                                <div class="col-1 p-0"  v-if="$isRole('Super Admin')||$isRole('BU Manager')">
                                    <i class="mdi mdi-delete mdi-24px p-1" role="button" data-bs-toggle="modal" :data-bs-target="'#modalRouteImportDelete'"     @click="setRouteImportDelete(route_import.id)">
                                    </i> 
                                </div>

                                <div class="col-1 p-0">
                                    <i class="mdi mdi-google-maps mdi-24px p-1" role="button"                                                                   @click="navToMap(route_import.id)">
                                    </i>
                                </div>

                                <div class="col-1 p-0"  v-if="$isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice')">
                                    <i class="mdi mdi-account-multiple mdi-24px p-1" role="button"                                                              @click="getClients(route_import.id)">
                                    </i>
                                </div>
                            </div>                       

                            <h6 class="card-text">ID    : {{route_import.id}}</h6>
                            <h6 class="card-text">label : {{route_import.libelle}}</h6>

                        </div>
                    </div>

                </div>
            
            </div>

            <modalRouteImportDelete  ref="modalRouteImportDelete"></modalRouteImportDelete>

        </section>
    </div>

    <div v-if="$isRole('FrontOffice')" class="content-wrapper" style="padding : 5px;">
        <section class="dashboard mt-4">
            <!-- Header Options -->
            <div class="container">
                <div class="row justify-content-start h-100 mt-2">
                    <div class="col-10 d-flex align-items-center">
                        <i
                            class="mdi mdi-dice-1 mr-1 ml-1 fw-bold"
                            style="color: #D7481F"
                        ></i
                        ><span class="fw-bold mb-1">{{ getUser.nom }}</span>
                    </div>
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
                    class="card col-5 m-1 shadow-sm rounded min_card_height text-center h-100"
                    @click="sync()"
                >
                    <div class="text-center" style="height: 50px">
                        <img
                            class="card-img-top"
                            :src="'/images/front_office_images/sync.png'"
                            style="height: 100%; width: auto"
                        />
                    </div>
                    <div class="card-body p-0 mt-3">
                        <p class="card-text font-weight-bold">Sync</p>
                    </div>
                </div>

                <div
                    class="card col-5 m-1 shadow-sm rounded min_card_height text-center h-100"
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

        </section>

        <div class="row mt-3" style="position: fixed; bottom: 10px;">
            <span class="text-small" style="color: rgb(215, 72, 31);">Software Version : V2.1</span>
        </div>
    </div>
</template>

<script>

import modalRouteImportDelete   from    "../routes/permanent/modalRouteImportDelete.vue"

import {mapGetters, mapActions} from    "vuex"

export default {

    data() {
        return {
            liste_route_import  :   [],
        }
    },

    components : {

        modalRouteImportDelete
    },

    computed : {

        ...mapGetters({
            getUser                     :   'authentification/getUser'              ,
            getAccessToken              :   'authentification/getAccessToken'       ,
            getIsAuthentificated        :   'authentification/getIsAuthentificated' ,

            getAddClient                :   'client/getAddClient'                   ,
            getUpdateClient             :   'client/getUpdateClient'                ,

            getIsOnline                 :   'internet/getIsOnline'
        }),
    },

    async mounted() {

        await this.getRouteImport()

        this.emitter.on("reSetRouteImport" , async () =>  {

            await this.getRouteImport()
        })
    },

    methods : {

        ...mapActions("authentification" ,  [
            "setUserAction"                 ,
            "setAccessTokenAction"          ,
            "setIsAuthentificatedAction"    
        ]),

        //  //  BackOffice + BU Manager + Super Admin //  //

        async getRouteImport() {

            try {

                if((this.$isRole("BackOffice"))||(this.$isRole('BU Manager'))||(this.$isRole("Super Admin"))||(this.$isRole('Viewer'))) {

                    this.$callApi("post",    "/route_import/index",     null)
                    .then((res)=> {

                        console.log(res)

                        this.liste_route_import     =   res.data
                    })
                }
            }

            catch(e) {

                console.log(e)
            }
        },

        navToMap(id_route_import) {
            
            this.$router.push('/route/obs/route_import/'+id_route_import+'/details')
        },

        async setRouteImportDelete(id_route_import) {

            await this.$refs.modalRouteImportDelete.setRouteImportDelete(id_route_import)
        },

        getClients(id_route_import) {

            this.$router.push('/route_import/'+id_route_import+'/clients')
        },

        //  //  //  //  //  //  //  //  //  //

        //  //  //  FrontOffice //  //  //  //

        async addClient() {

            if(this.getIsOnline) {

                this.$router.push('/route_import/'+this.getUser.id_route_import+'/clients/add')
            }
        },

        async sync() {

            if(this.getIsOnline) {

                this.$showLoadingPage()

                let res = await this.$indexedDB.$sync()

                if(res  ==  200) {

                    this.$feedbackSuccess("Synchronisation Perfomed !" , "a synchronisation has been performed successfuly!")
                }

                if(res  ==  400) {

                    this.$showErrors("Synchronisation Failed !" , ["all your local data has been failed!"])
                }

                this.$hideLoadingPage()
            }
        },

        goToMap() {

            if(this.getIsOnline) {

                this.$router.push('/route/frontoffice/obs/route_import/'+this.getUser.id_route_import+'/details')
            }
        },

        showClientsByStatus() {

            this.$router.push('/route_import/'+this.getUser.id_route_import+'/clients/by_status')
        },

        async showProfile() {

            if(this.getIsOnline) {

                this.$router.push('/users/'+this.getUser.id+'/show')
            }
        },

        async logOut() {

            if(this.getIsOnline) {

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
            }
        },

        //  //  //  //  //  //  //  //  //  //
    }
}

</script>

<style scoped>

.min_card_height {

    min-height: 145px;
}

</style>