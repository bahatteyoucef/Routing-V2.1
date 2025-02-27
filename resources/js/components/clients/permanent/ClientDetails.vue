<template>

    <div class="content-wrapper" style="padding : 5px;">
        <section class="mt-4">
            <div class="image d-flex flex-column justify-content-center align-items-center">

                <img    id="facade_image_display_update"    :src="'/uploads/clients/'+client.id+'/'+client.facade_image"  class="w-100">

                <div class="mt-3">
                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="idd">Code-Barre : {{ client.CustomerCode }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="idd">Raison Social : {{ client.CustomerNameA }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="idd">Adresse : {{ client.Address }} - {{ client.CityNameE }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="idd">Type de Magasin : {{ client.CustomerType }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="idd">Téléphone : {{ client.Tel }}</span>
                    </div>
                </div>

                <div class="mt-5 p-0 w-100 text-center">
                    <div v-if="getIsOnline" class="text-center m-1 p-0">
                        <button class="rounded btn primary text-white w-100 p-1" @click="updateInformations()">Modifier <i class="mdi mdi-pencil-box-outline"></i></button>
                    </div>

                    <div v-if="getIsOnline" class="text-center m-1 p-0">
                        <button class="rounded btn btn-secondary text-white w-100 p-1" @click="openDirectionsInGoogleMaps()">Itinéraire <i class="mdi mdi-map-marker-circle"></i></button>
                    </div>

                    <div v-if="((getIsOnline)&&(client.status != 'validated'))" class="text-center m-1 p-0">
                        <button class="rounded btn btn-danger text-white w-100 p-1" @click="deleteClient()">Supprimer <i class="mdi mdi-delete"></i></button>
                    </div>
                </div>

            </div>
        </section>
    </div>

</template>

<script>

import {mapGetters, mapActions} from    "vuex"

export default {

    data() {
        return {

            client                :   {

                CustomerCode                    : '',

                CustomerNameE                   : '',
                CustomerNameA                   : '',               

                RegionNo                        : '',
                DistrictNo                      : '',
                DistrictNo                          : '',
                CityNo                          : '',
                Address                         : '',

                DistrictNameE                   : '',
                DistrictNameE                       : '',
                CityNameE                       : '',
                AddressA                        : '',

                Latitude                        : '',
                Longitude                       : '',

                Tel                             : '',
                CustomerType                    : '',

                status                          : '',

                facade_image_original_name      : '',
                facade_image                    : '',
                in_store_image_original_name    : '',
                in_store_image                  : '',

                JPlan                           : '',
                Journee                         : ''
            },
        }
    },

    computed : {

        ...mapGetters({
            getListeJourneyPlan             :   'journey_plan/getListeJourneyPlan'      ,
            getListeTypeClient              :   'type_client/getListeTypeClient'        ,  
            getListeJournee                 :   'journee/getListeJournee'               ,

            getAddClient                    :   'client/getAddClient'                   ,
            getUpdateClient                 :   'client/getUpdateClient'                ,

            //

            getUser                         :   'authentification/getUser'              ,

            //

            getIsOnline                     :   'internet/getIsOnline'
        }),
    },

    async mounted() {

        await this.getCustomerData()
    },  

    methods : {

        async getCustomerData() {

            if(this.getIsOnline) {

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.$route.params.id_client+"/show" ,   null)

                this.client.id                            =   res.data.id

                this.client.CustomerCode                  =   res.data.CustomerCode

                this.client.CustomerNameE                 =   res.data.CustomerNameE
                this.client.CustomerNameA                 =   res.data.CustomerNameA                              

                this.client.RegionNo                      =   res.data.RegionNo
                this.client.DistrictNo                    =   res.data.DistrictNo
                this.client.DistrictNo                    =   res.data.DistrictNo
                this.client.CityNo                        =   res.data.CityNo
                this.client.Address                       =   res.data.Address

                this.client.AddressA                      =   res.data.AddressA                 
                this.client.CityNameE                     =   res.data.CityNameE
                this.client.DistrictNameE                 =   res.data.DistrictNameE

                this.client.Tel                           =   res.data.Tel

                this.client.CustomerType                  =   res.data.CustomerType
                this.client.status                        =   res.data.status

                this.client.Latitude                      =   res.data.Latitude
                this.client.Longitude                     =   res.data.Longitude

                this.client.JPlan                         =   res.data.JPlan
                this.client.Journee                       =   res.data.Journee

                this.client.facade_image_original_name    =   res.data.facade_image_original_name
                this.client.in_store_image_original_name  =   res.data.in_store_image_original_name

                this.client.facade_image                  =   res.data.facade_image
                this.client.in_store_image                =   res.data.in_store_image
            }

            else {

                let client      =   this.getUpdateClient

                this.client     =   client
            }
        },

        //

        updateInformations() {

            this.$router.push('/route_import/'+this.$route.params.id_route_import+'/clients/'+this.$route.params.id_client+'/update')
        },

        async openDirectionsInGoogleMaps() {

            const current_position      =   await this.$currentPosition()

            const user_latitude         =   current_position.coords.latitude
            const user_longitude        =   current_position.coords.longitude

            const client_latitude       =   this.client.Latitude;
            const client_longitude      =   this.client.Longitude;

            // Construct the URL for Google Maps directions
            const url = `https://www.google.com/maps/dir/?api=1&origin=${user_latitude},${user_longitude}&destination=${client_latitude},${client_longitude}`;

            // Open the URL in a new window or tab to trigger the Google Maps app if available
            window.open(url, '_system');
        },

        //

        async deleteClient() {

            let res =   await this.$customMessages("Suppression d'un client", "Voulez-vous supprimer le client "+this.client.CustomerCode+" ?", "warning", "Oui", "Non", "delete_client_details_clients", "")

            if(res) {

                this.$showLoadingPage()

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.client.id+"/delete",   null)

                if(res.status===200){

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Feedback
                    this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                    //
                    this.$goBack()
                }
                
                else{

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)
                }
            }
        }
    }
};

</script>