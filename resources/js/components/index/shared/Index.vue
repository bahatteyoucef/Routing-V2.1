<template>
    <div class="content-wrapper p-3">
        <section class="dashboard">

            <div class="page-header">
                <div class="row w-100">
                    <div class="col-sm-10">
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

                    <div class="card bg-gradient-primary card-img-holder text-white p-2">
                        <div class="card-body p-3">
                            <img :src="'/template/images/dashboard/circle.svg'"    class="card-img-absolute"   alt="circle-image" />

                            <div class="mb-3">
                                <h4 class="font-weight-normal text-center">{{route_import.libelle}}</h4>
                            </div>

                            <div class="row">
                                <div class="col p-1"  v-if="$isRole('Super Admin')||$isRole('BU Manager')">
                                    <button class="btn btn-danger text-white w-100" data-bs-toggle="modal" :data-bs-target="'#ModalRouteImportDelete'"     @click="setRouteImportDelete(route_import.id)">Delete</button>
                                </div>

                                <div class="col p-1">
                                    <button class="btn bg-white text-black w-100" @click="navToMap(route_import.id)">Map</button>
                                </div>

                                <div class="col p-1"  v-if="$isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice')">
                                    <button class="btn bg-white text-black w-100" @click="getClientsValidation(route_import.id)">Validation</button>
                                </div>

                                <div class="col p-1"  v-if="$isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice')">
                                    <button class="btn bg-white text-black w-100" @click="getClientsConfirmation(route_import.id)">Confirmation</button>
                                </div>

                                <div class="col p-1"  v-if="$isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice')">
                                    <button class="btn bg-white text-black w-100" @click="getClients(route_import.id)">Clients</button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            
            </div>
        </section>
    </div>

    <ModalRouteImportDelete  ref="ModalRouteImportDelete"></ModalRouteImportDelete>

</template>

<script>

import ModalRouteImportDelete   from    "@/components/routes/shared/crud/ModalRouteImportDelete.vue"

import emitter                  from    "@/utils/emitter"

export default {

    data() {
        return {
            liste_route_import  :   []
        }
    },

    components : {
        ModalRouteImportDelete
    },

    async mounted() {

        await this.getRouteImport()

        emitter.on("reSetRouteImport" , async () =>  {
            await this.getRouteImport()
        })
    },

    unounted() {

        emitter.off("reSetRouteImport")
    },

    methods : {

        async getRouteImport() {

            if((this.$isRole("BackOffice"))||(this.$isRole('BU Manager'))||(this.$isRole("Super Admin"))||(this.$isRole('Viewer'))) {

                this.$callApi("post",    "/route-imports",     null)
                .then((res)=> {
                    this.liste_route_import     =   res.data.liste_route_import
                })
            }
        },

        navToMap(id_route_import) {
            
            this.$router.push('/route/obs/route-imports/'+id_route_import+'/details')
        },

        async setRouteImportDelete(id_route_import) {

            await this.$refs.ModalRouteImportDelete.setRouteImportDelete(id_route_import)
        },

        getClientsValidation(id_route_import) {

            this.$router.push('/route-imports/'+id_route_import+'/clients/validation')
        },

        getClientsConfirmation(id_route_import) {

            this.$router.push('/route-imports/'+id_route_import+'/clients/confirmation')
        },

        getClients(id_route_import) {

            this.$router.push('/route-imports/'+id_route_import+'/clients')
        },
    }
}

</script>

<style scoped>

.min_card_height {

    min-height: 145px;
}

</style>