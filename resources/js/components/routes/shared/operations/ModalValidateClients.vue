<template>
    <!-- Modal -->
    <div class="modal fade" id="ModalValidateClients" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Validate Clients :</h5>
                    <button type="button"   class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="validations_div mt-5">
                        <CardDoublants      v-if="show_card_doublants"      :getDoublant="getDoublant"      :total_clients="total_clients"      :mode="'permanent'"     :id_route_import="id_route_import"></CardDoublants>
                    </div>
                </div>

                <!--  -->

                <div class="modal-footer"       style="display: flex; justify-content: space-between;">
                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button"   class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!--  -->
    <ModalClientUpdate  ref="ModalClientUpdate"     :id_route_import="id_route_import"      :mode="'permanent'"     :update_type="'validation'"     :validation_type="validation_type"      :users_all="users_all"      :districts_all="districts_all"></ModalClientUpdate>
</template>

<script>

import ModalClientUpdate    from    "@/components/clients/shared/ModalClientUpdate.vue"
import CardDoublants        from    "@/components/routes/shared/operations/validations/CardDoublants.vue"

export default {

    data() {

        return {

            getDoublant                     :   {}      ,
            show_card_doublants             :   false   ,

            //

            validation_type                 :   null    ,

            //

            users_all                       :   []      ,
            districts_all                   :   []      ,
        }
    },

    props       : ["id_route_import", "total_clients"],

    components  : {

        ModalClientUpdate   :   ModalClientUpdate   , 
        CardDoublants       :   CardDoublants
    },

    async mounted() {

        await this.getComboData()

        this.emitter.on("reSetValidationClientUpdate"       , (validation_type) => {
            this.validation_type    =   validation_type
        })

        this.emitter.on("refreshDoublantCustomerCode"       , async (validation_clients)    =>  {
            await this.updateDoublantsJSON(validation_clients, "CustomerCode")
        })

        this.emitter.on("refreshDoublantCustomerNameE"      , async (validation_clients)    =>  {
            await this.updateDoublantsJSON(validation_clients, "CustomerNameE")
        })

        this.emitter.on("refreshDoublantTel"                , async (validation_clients)    =>  {
            await this.updateDoublantsJSON(validation_clients, "Tel")
        })

        this.emitter.on("refreshDoublantGPS"                , async (validation_clients)    =>  {
            await this.updateDoublantsJSON(validation_clients, "GPS")
        })
    },

    unmounted() {

        this.emitter.off('reSetUpdate')
        this.emitter.off('reSetDelete')

        this.emitter.off('reSetValidationClientUpdate')
        this.emitter.off('reSetClientsDecoupeByJourneeAdd')
        this.emitter.off('refreshDoublantCustomerCode')
        this.emitter.off('refreshDoublantCustomerNameE')
        this.emitter.off('refreshDoublantTel')
        this.emitter.off('refreshDoublantGPS')
    },

    methods : {

        async showResume() {
            await this.$refs.ModalResume.getClients()
        },

        async submitRouteImport() {
            await this.$refs.ModalSubmit.setDatatable()
        },

        //

        async getComboData() {

            this.$showLoadingPage()

            const res_1         =   await this.$callApi("post"  ,   "/users/combo"      ,   null)
            const res_2         =   await this.$callApi("post"  ,   "/rtm_willayas"     ,   null)

            this.users_all      =   res_1.data
            this.districts_all  =   res_2.data

            this.$hideLoadingPage()
        },

        //

        async getDoubles() {

            // Show Loading Page
            this.$showLoadingPage()

            const res   =   await this.$callApi("post"  ,   "/route_import/"+this.id_route_import+"/clients/doubles", null)
            console.log(res)

            if(res.status===200){

                this.getDoublant.getDoublantTel                 =   res.data.getDoublantTel
                this.getDoublant.getDoublantGPS                 =   res.data.getDoublantGPS
                this.getDoublant.getDoublantCustomerNameE       =   res.data.getDoublantCustomerNameE
                this.getDoublant.getDoublantCustomerCode        =   res.data.getDoublantCustomerCode

                this.show_card_doublants                        =   true

                // Hide Loading Page
                this.$hideLoadingPage()
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
            }
        },

        //

        async updateDoublantsJSON(validation_clients, validation_type) {

            const updatableFields   =   [
                "NewCustomer"               ,
                "OpenCustomer"              ,
                "CustomerIdentifier"        ,
                "CustomerCode"              ,

                "CustomerNameE"             ,
                "CustomerNameA"             ,

                "Tel"                       ,
                "tel_status"                ,
                "tel_comment"               ,

                "Latitude"                  ,
                "Longitude"                 ,

                "Address"                   ,
                "Neighborhood"              ,
                "Landmark"                  ,

                "DistrictNo"                ,
                "DistrictNameE"             ,

                "CityNo"                    ,
                "CityNameE"                 ,

                "CustomerType"              ,

                "BrandAvailability"         ,
                "BrandSourcePurchase"       ,

                "JPlan"                     ,
                "Journee"                   ,

                "Frequency"                 ,
                "SuperficieMagasin"         ,
                "NbrAutomaticCheckouts"     ,

                "AvailableBrands"                   ,
                "AvailableBrands_array_formatted"   ,
                "AvailableBrands_string_formatted"  ,

                "status"                            ,
                "nonvalidated_details"              ,

                "owner"                             ,
                "owner_username"                        ,

                "comment"                           ,

                "facade_image"                          ,
                "in_store_image"                        ,
                "facade_image_original_name"            ,
                "in_store_image_original_name"          ,
                "CustomerBarCode_image"                 ,
                "CustomerBarCode_image_original_name"
            ];

            //
            const typeToArray       =   {
                CustomerCode            :   this.getDoublant.getDoublantCustomerCode,
                CustomerNameE           :   this.getDoublant.getDoublantCustomerNameE,
                Tel                     :   this.getDoublant.getDoublantTel,
                GPS                     :   this.getDoublant.getDoublantGPS,
            };

            //
            const sourceArray = typeToArray[validation_type] || [];

            //
            const allDoublantArrays =   [
                this.getDoublant.getDoublantCustomerCode    ,
                this.getDoublant.getDoublantCustomerNameE   ,
                this.getDoublant.getDoublantTel             ,
                this.getDoublant.getDoublantGPS
            ];

            //
            for (const doublantClient of sourceArray) {

                //
                const existingInTotal = this.total_clients.find(tc => tc.id === doublantClient.id);
                if (existingInTotal) {
                    updatableFields.forEach(field => {
                        existingInTotal[field] = doublantClient[field];
                    });
                }

                //
                for (const arr of allDoublantArrays) {
                    if (arr === sourceArray) continue;

                    const existing = arr.find(item => item.id === doublantClient.id);
                    if (existing) {
                        updatableFields.forEach(field => {
                        existing[field] = doublantClient[field];
                        });
                    }
                }
            }
        }
    }
}

</script>