<template>

    <div id="details_page"  class="mt-4 mb-4 d-flex justify-content-center">

        <div class="card p-4">

            <div class="image d-flex flex-column justify-content-center align-items-center">

                <img    id="facade_image_display_update"    src=""  class="w-100">

                <div class="mt-3">
                    <div class="d-flex flex-row justify-content-center align-items-center gap-2">
                        <span class="idd">CustomerCode : {{ customer.CustomerCode }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center align-items-center gap-2">
                        <span class="idd">CustomerNameE : {{ customer.CustomerNameE }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center align-items-center gap-2">
                        <span class="idd">Address : {{ customer.Address }} - {{ customer.AreaNameE }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center align-items-center gap-2">
                        <span class="idd">CustomerType : {{ customer.CustomerType }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center align-items-center gap-2">
                        <span class="idd">Tel : {{ customer.Tel }}</span>
                    </div>
                </div>

                <div class="d-flex mt-3"       style="display: flex; justify-content: space-between;">
                    <div style="display: flex; margin-left: auto;">
                        <button class="rounded btn1 primary m-1" @click="updateInformations()">Update Informations</button>
                        <button class="rounded btn1 btn-secondary m-1 text-white" @click="openDirectionsInGoogleMaps()">Show Itinerary <i class="mdi mdi-map-marker-radius"></i></button>
                    </div>
                </div>

            </div>
        </div>

    </div>

</template>

<script>

export default {

    data() {
        return {

            customer                :   {

                CustomerCode                    : '',

                CustomerNameE                   : '',
                CustomerNameA                   : '',               

                RegionNo                        : '',
                DistrictNo                      : '',
                CityNo                          : '',
                AreaNo                          : '',
                Address                         : '',

                DistrictNameE                   : '',
                CityNameE                       : '',
                AreaNameE                       : '',
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

    async mounted() {

        await this.getCustomerData()
    },  

    methods : {

        async getCustomerData() {

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.$route.params.id_client+"/show" ,   null)
            console.log(res)

            this.customer.id                            =   res.data.id

            this.customer.CustomerCode                  =   res.data.CustomerCode

            this.customer.CustomerNameE                 =   res.data.CustomerNameE
            this.customer.CustomerNameA                 =   res.data.CustomerNameA                              

            this.customer.RegionNo                      =   res.data.RegionNo
            this.customer.DistrictNo                    =   res.data.DistrictNo
            this.customer.CityNo                        =   res.data.CityNo
            this.customer.AreaNo                        =   res.data.AreaNo
            this.customer.Address                       =   res.data.Address

            this.customer.AddressA                      =   res.data.AddressA                 
            this.customer.AreaNameE                     =   res.data.AreaNameE
            this.customer.CityNameE                     =   res.data.CityNameE

            this.customer.Tel                           =   res.data.Tel

            this.customer.CustomerType                  =   res.data.CustomerType
            this.customer.status                        =   res.data.status

            this.customer.Latitude                      =   res.data.Latitude
            this.customer.Longitude                     =   res.data.Longitude

            this.customer.JPlan                         =   res.data.JPlan
            this.customer.Journee                       =   res.data.Journee

            this.customer.facade_image_original_name    =   res.data.facade_image_original_name
            this.customer.in_store_image_original_name  =   res.data.in_store_image_original_name

            this.customer.facade_image                  =   res.data.facade_image
            this.customer.in_store_image                =   res.data.in_store_image
        },

        //

        updateInformations() {

            this.$router.push('/route_import/'+this.$route.params.id_route_import+'/clients/'+this.$route.params.id_client+'/update')
        },

        async openDirectionsInGoogleMaps() {

            const current_position      =   await this.$currentPosition()

            const user_latitude         =   current_position.coords.latitude
            const user_longitude        =   current_position.coords.longitude

            const customer_latitude     =   this.customer.Latitude;
            const customer_longitude    =   this.customer.Longitude;

            // Construct the URL for Google Maps directions
            const url = `https://www.google.com/maps/dir/?api=1&origin=${user_latitude},${user_longitude}&destination=${customer_latitude},${customer_longitude}`;

            // Open the URL in a new window or tab to trigger the Google Maps app if available
            window.open(url, '_system');
        },
    }
};

</script>

<style>

    #details_page .card {
        width: 350px;
        background-color: #efefef;
        border: none;
        cursor: pointer;
    }

    #details_page .facade_image_display_update {
        height: 210px;
        width: 210px;
        border-radius: 50%
    }

    #details_page .name {
        font-size: 22px;
        font-weight: bold
    }

    #details_page .idd {
        font-size: 14px;
        font-weight: 600
    }

    #details_page .idd1 {
        font-size: 12px
    }

    #details_page .number {
        font-size: 22px;
        font-weight: bold
    }

    #details_page .follow {
        font-size: 12px;
        font-weight: 500;
        color: #444444
    }

    #details_page .btn1 {
        height: 40px;
        width: 150px;
        border: none;
        background-color: #000;
        color: #aeaeae;
        font-size: 15px
    }

    #details_page .text span {
        font-size: 13px;
        color: #545454;
        font-weight: 500
    }

    #details_page .icons i {
        font-size: 19px
    }

    #details_page hr .new1 {
        border: 1px solid
    }

    #details_page .join {
        font-size: 14px;
        color: #a0a0a0;
        font-weight: bold
    }

    #details_page .date {
        background-color: #ccc
    }

</style>./DetailsCustomer.vue