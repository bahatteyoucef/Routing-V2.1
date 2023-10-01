<template>
    <div class="content-wrapper">
        <section class="dashboard">

            <div v-if="$isRole('Super Admin')||$isRole('BackOffice')" class="page-header">

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

                                <div class="col-1 p-0"  v-if="$isRole('Super Admin')||$isRole('BackOffice')">
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

    </div>
</template>

<script>

import modalRouteImportDelete from "../routes/imports/modalRouteImportDelete.vue"

export default {

    data() {
        return {
            liste_route_import  :   [],
        }
    },

    components : {

        modalRouteImportDelete
    },

    async mounted() {

        await this.getRouteImport()

        this.emitter.on("reSetRouteImport" , async () =>  {

            await this.getRouteImport()
        })
    },

    methods : {

        async getRouteImport() {

            try {

                if(this.$connectedToInternet) {

                    this.$callApi("post",    "/route_import", null)
                    .then((res)=> {

                        this.liste_route_import     =   res.data

                        // Add to indexedDB
                        this.$indexedDB.$setListeRouteImport(this.liste_route_import)
                    })
                }

                else {

                    this.liste_route_import         =   await this.$indexedDB.$getListeRouteImport()
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

        //

        getClients(id_route_import) {

            this.$router.push('/route_import/'+id_route_import+'/clients')
        }
    }
}

</script>
