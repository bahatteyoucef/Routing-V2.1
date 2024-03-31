<template>
    <div class="content-wrapper" style="padding : 15px;">

        <!-- Super Admin + BU Manager + BackOffice -->
        <section v-if="$isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice')" class="dashboard">

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

                    <div class="card bg-gradient-danger card-img-holder text-white">
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

                                <div class="col-1 p-0">
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

        <!-- FrontOffice -->
        <section v-if="$isRole('FrontOffice')"  class="dashboard mt-4"> 

            <!-- Header Options -->
            <div class="container">
                <div class="row d-flex justify-content-start h-100 mt-2">
                    <div class="col-10 d-flex align-items-center">
                        <i class="mdi mdi-dice-1 mr-1 ml-1 fw-bold" style="color : #A25CFF"></i><span class="fw-bold mb-1">{{ getUser.nom }}</span>
                    </div>

                    <!-- <div class="col-2 d-flex align-items-center pl-1"   role="button"   @click="logOut()">
                        <i class="mdi mdi-power mr-1 ml-1 fw-bold" style="color : #A25CFF; font-size: 25px;"></i>
                    </div> -->
                </div>
            </div>

            <!-- Index Options -->
            <div class="row d-flex justify-content-center h-100 mt-4">
                <div class="card col-5 m-1 shadow-sm rounded text-center h-25" @click="addClient()">
                    <div class="text-center" style="height : 50px">  
                        <img class="card-img-top" :src="'/images/store.png'" style="height:100%;width:auto">
                    </div>
                    <div class="card-body p-0 mt-3">
                        <p class="card-text font-weight-bold">New</p>
                    </div>
                </div>

                <div class="card col-5 m-1 shadow-sm rounded text-center h-25"  @click="showProfile()">
                    <div class="text-center" style="height : 50px">  
                        <img class="card-img-top" :src="'/images/profile.png'" style="height:100%;width:auto">
                    </div>
                    <div class="card-body p-0 mt-3">
                        <p class="card-text font-weight-bold">Profile</p>
                    </div>
                </div>

                <div class="card col-5 m-1 shadow-sm rounded text-center h-25" @click="goToMap()">
                    <div class="text-center" style="height : 50px">  
                        <img class="card-img-top" :src="'/images/map_marker.png'" style="height:100%;width:auto">
                    </div>
                    <div class="card-body p-0 mt-3">
                        <p class="card-text font-weight-bold">Map</p>
                    </div>
                </div>

                <div class="card col-5 m-1 shadow-sm rounded text-center h-25" @click="showClientsByStatus()">
                    <div class="text-center" style="height : 50px">  
                        <img class="card-img-top" :src="'/images/group_clients.png'" style="height:100%;width:auto">
                    </div>
                    <div class="card-body p-0 mt-3">
                        <p class="card-text font-weight-bold">Clients</p>
                    </div>
                </div>

                <!--  
                <div class="card col-5 m-1 shadow-sm rounded text-center h-25" @click="showNotifications()">
                    <div class="text-center" style="height : 50px">  
                        <img class="card-img-top" :src="'/images/notifications.png'" style="height:100%;width:auto">
                    </div>
                    <div class="card-body p-0 mt-3">
                        <p class="card-text font-weight-bold">Notifications</p>
                    </div>
                </div>
                -->

                <!--  
                <div class="card col-5 m-1 shadow-sm rounded text-center h-25" @click="showRemuneration()">
                    <div class="text-center" style="height : 50px">  
                        <img class="card-img-top" :src="'/images/credit_card.png'" style="height:100%;width:auto">
                    </div>
                    <div class="card-body p-0 mt-3">
                        <p class="card-text font-weight-bold">Remuneration</p>
                    </div>
                </div>
                -->

                <div class="card col-5 m-1 shadow-sm rounded text-center h-25" @click="sync()">
                    <div class="text-center" style="height : 50px">  
                        <img class="card-img-top" :src="'/images/sync.png'" style="height:100%;width:auto">
                    </div>
                    <div class="card-body p-0 mt-3">
                        <p class="card-text font-weight-bold">Sync</p>
                    </div>
                </div>
               
                <div class="card col-5 m-1 shadow-sm rounded text-center h-25"  @click="logOut()">
                    <div class="text-center" style="height : 50px">  
                        <img class="card-img-top" :src="'/images/logout.png'" style="height:100%;width:auto">
                    </div>
                    <div class="card-body p-0 mt-3">
                        <p class="card-text font-weight-bold">Log Out</p>
                    </div>
                </div>
            </div>

        </section>

        <!-- Software Version -->
        <div class="row mt-3" style="position: absolute;bottom : 10px">
            <span class="text-small text-secondary">Software Version : 2.1</span>
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
            getUpdateClient             :   'client/getUpdateClient'                
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

                if(this.$connectedToInternet) {

                    if(this.$isRole("FrontOffice")) {

                        this.$callApi("post",   "/route_import/index",      null)
                        .then((res)=> {

                            console.log(res)

                            this.liste_route_import     =   res.data
                        })
                    }

                    if((this.$isRole("BackOffice"))||(this.$isRole('BU Manager'))||(this.$isRole("Super Admin"))) {

                        this.$callApi("post",    "/route_import/index",     null)
                        .then((res)=> {

                            this.liste_route_import     =   res.data

                            if(this.$isRole("FrontOffice")) {

                                // Add to indexedDB
                                this.$indexedDB.$setListeRouteImport(this.liste_route_import)
                            }
                        })
                    }
                }

                else {

                    setTimeout(async () => {
                        
                        if(this.$isRole("FrontOffice")) {

                            this.liste_route_import         =   await this.$indexedDB.$getListeRouteImport()
                        }
                    }, 555);
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

            this.$router.push('/route_import/'+this.getUser.id_route_import+'/clients/add')
        },

        async sync() {

            this.$showLoadingPage()

            let res = await this.$indexedDB.$sync()

            if(res  ==  200) {

                this.$feedbackSuccess("Synchronisation Perfomed !" , "a synchronisation has been performed successfuly!")
            }

            if(res  ==  400) {

                this.$showErrors("Synchronisation Failed !" , ["all your local data has been failed!"])
            }

            this.$hideLoadingPage()
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

        async showNotifications() {
        
        },

        async showRemuneration() {
        
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

        //  //  //  //  //  //  //  //  //  //
    }
}

</script>../routes/permanent/modalRouteImportDelete.vue
