<template>
    <div class="p-3" style="height: 90%;">
        <div class="card w-100 shadow-lg">
            <div class="card-img-container" style="height: 45%; width: 100%; overflow: hidden"> 
                <img id="facade_image_display" :src="'/uploads/clients/'+client.id+'/'+client.facade_image"     class="card-img-top">
            </div>

            <div class="card-body px-1" style="height: 35%; overflow-y: auto;">
                <h5 class="card-title fw-bold text-center text-decoration-underline">{{ client.CustomerCode }} ({{ client.status }})</h5>
                <div>
                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">Raison Social :</span>
                        <span>{{ client.CustomerNameA }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">Adresse :</span>
                        <span>{{ client.Address }} - {{ client.CityNameE }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">Type de Magasin :</span>
                        <span>{{ client.CustomerType }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">Téléphone :</span>
                        <span>{{ client.Tel }}</span>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white" style="height: 20%;">
                <div v-if="getIsOnline" class="text-center m-1 p-0">
                    <button class="rounded btn btn-primary text-white w-100 p-1" @click="updateInformations()">Modifier <i class="mdi mdi-pencil-box-outline"></i></button>
                </div>

                <div v-if="getIsOnline" class="text-center m-1 p-0">
                    <button class="rounded btn btn-secondary text-white w-100 p-1" @click="openDirectionsInGoogleMaps()">Itinéraire <i class="mdi mdi-map-marker-circle"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapGetters }   from    "vuex"

export default {

    data() {
        return {

            client                :   {

                NewCustomer                     : '',

                OpenCustomer                    : '',

                CustomerCode                    : '',

                CustomerNameE                   : '',
                CustomerNameA                   : '',               

                RegionNo                        : '',
                DistrictNo                      : '',
                CityNo                          : '',
                Address                         : '',
                RvrsGeoAddress                  : '',

                DistrictNameE                   : '',
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

            show_delete_button  :   false
        }
    },

    computed : {
        ...mapGetters({
            getUser                         :   'authentification/getUser'  ,
            getIsOnline                     :   'internet/getIsOnline'
        }),
    },

    async mounted() {

        await this.getCustomerData()
    },  

    methods : {

        async getCustomerData() {

            const res                   =   await this.$callApi("post"  ,   "/route-imports/"+this.$route.params.id_route_import+"/clients/"+this.$route.params.id_client+"/show" ,   null)

            let client                  =   res.data.client

            this.client.id                              =   client.id

            this.client.NewCustomer                     =   client.NewCustomer   

            this.client.OpenCustomer                    =   client.OpenCustomer

            this.client.CustomerCode                    =   client.CustomerCode

            this.client.CustomerNameE                   =   client.CustomerNameE
            this.client.CustomerNameA                   =   client.CustomerNameA                              

            this.client.RegionNo                        =   client.RegionNo
            this.client.DistrictNo                      =   client.DistrictNo
            this.client.CityNo                          =   client.CityNo
            this.client.Address                         =   client.Address
            this.client.RvrsGeoAddress                  =   client.RvrsGeoAddress

            this.client.AddressA                        =   client.AddressA                 
            this.client.CityNameE                       =   client.CityNameE
            this.client.DistrictNameE                   =   client.DistrictNameE

            this.client.Tel                             =   client.Tel

            this.client.CustomerType                    =   client.CustomerType
            this.client.status                          =   client.status

            this.client.Latitude                        =   client.Latitude
            this.client.Longitude                       =   client.Longitude

            this.client.JPlan                           =   client.JPlan
            this.client.Journee                         =   client.Journee

            this.client.facade_image_original_name      =   client.facade_image_original_name
            this.client.in_store_image_original_name    =   client.in_store_image_original_name

            this.client.facade_image                    =   client.facade_image
            this.client.in_store_image                  =   client.in_store_image

            //
            if(this.client.status != 'validated') {
                this.show_delete_button                     =   true
            }
        },

        //

        updateInformations() {

            this.$router.push('/route-imports/'+this.$route.params.id_route_import+'/clients/'+this.$route.params.id_client+'/update')
        },

        async openDirectionsInGoogleMaps() {

            const response      =   await this.$currentPosition(this.getUser.accuracy)
            console.log(response)

            if(response.success) {

                const user_latitude         =   response.position.coords.latitude
                const user_longitude        =   response.position.coords.longitude

                const client_latitude       =   this.client.Latitude;
                const client_longitude      =   this.client.Longitude;

                // Construct the URL for Google Maps directions
                const url = `https://www.google.com/maps/dir/?api=1&origin=${user_latitude},${user_longitude}&destination=${client_latitude},${client_longitude}`;

                // Open the URL in a new window or tab to trigger the Google Maps app if available
                window.open(url, '_system');
            }

            else {

                //
                this.$customMessages("GPS Error", "Vérifiez si votre GPS est activée", "error", "OK", "", "", "")
            }
        },

        //

        async deleteClient() {

            let res =   await this.$customMessages("Suppression d'un client", "Voulez-vous supprimer le client "+this.client.CustomerCode+" ?", "warning", "Oui", "Non", "delete_client_details_clients", "")

            if(res) {

                this.$showLoadingPage()

                const res                   =   await this.$callApi("post"  ,   "/route-imports/"+this.$route.params.id_route_import+"/clients/"+this.client.id+"/delete",   null)

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

<style scoped>

/* Ensure the card takes full height of the parent container */
.card {
    height: 100% !important; 
    border: none; /* Optional: removes border to look cleaner */
}

/* 1. Image Section: 35% */
.card-img-container {
    height: 35%;
    overflow: hidden;
}

/* Make sure the image inside fills the container properly */
.card-img-container img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Key property: prevents image distortion */
}

/* 2. Body Section: 35% */
.card-body {
    height: 35%;
    overflow-y: auto; /* Enables scroll ONLY here */
}

/* 3. Footer Section: 30% */
.card-footer {
    height: 30%;
    overflow-y: hidden; /* Prevents scroll in footer */
}

</style>