<template>
    <div class="content-wrapper p-3">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-10">
                        <h5 class="modal-title">Validate the Routing</h5>
                    </div>

                    <div class="col-sm-1 text-right">
                        <button class="btn btn-primary btn-block"   data-bs-toggle="modal" :data-bs-target="'#ModalSubmit'"     @click="submitRouteImport()">Submit</button>
                    </div>

                    <div class="col-sm-1 text-right">
                        <button class="btn btn-primary btn-block"   data-bs-toggle="modal" :data-bs-target="'#ModalResume'"     @click="showResume()">Resume</button>
                    </div>
                </div>

                <hr />

                <form class="row mt-3 mb-5">
                    <div class="col-sm-4">
                        <label for="libelle"                    class="form-label">Label</label>
                        <input type="text"                      class="form-control"        id="libelle"        v-model="route_import.libelle"      disabled="disabled">
                    </div>

                    <div class="col-sm-4">
                        <label for="route_import_tempo_file"    class="form-label">File</label>
                        <input  type="file"                     class="form-control"        
                                                                id="route_import_tempo_file"
                                                                disabled="disabled"
                                                                accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, 
                                                                        application/vnd.ms-excel">
                    </div>

                    <div class="col-sm-4">
                        <label for="districts"       class="form-label">districts</label>
                        <ul>
                            <li class="list-item" v-for="district in route_import.districts" :key="district">{{ district.DistrictNameE }} ({{ district.DistrictNo }})</li>
                        </ul>
                    </div>
                </form>

                <hr />

                <div class="validations_div mt-5">

                    <!-- Card Doublant  -->
                    <CardDoublants  v-if="show_card_doublants"  :getDoublant="getDoublant"  :total_clients="total_clients"    :mode="'temporary'"     :id_route_import_tempo="route_import.id_route_import_tempo"></CardDoublants>
                    <!--                -->

                </div>
            </div>
        </div>
    </div>

    <!--  -->

    <ModalClientUpdate  ref="ModalClientUpdate"     :id_route_import_tempo="route_import.id_route_import_tempo"     :mode="'temporary'"     :update_type="'validation'"     :validation_type="validation_type"  :users_all="users_all"      :districts_all="districts_all"  >   </ModalClientUpdate>
    <ModalResume        ref="ModalResume"           :id_route_import_tempo="route_import.id_route_import_tempo"     :mode="'temporary'"                                                                                                                                     >   </ModalResume>

    <ModalSubmit        ref="ModalSubmit"           :id_route_import_tempo="route_import.id_route_import_tempo"     :submit_clients="total_clients"                                                                                                                         >   </ModalSubmit>

</template>

<script>

import ModalClientUpdate    from    "@/components/clients/shared/ModalClientUpdate.vue"
import ModalResume          from    "../operations/ModalResume.vue"
import ModalSubmit          from    "./ModalSubmit.vue"

import CardDoublants        from    "@/components/routes/shared/operations/validations/CardDoublants.vue"

export default {

    data() {

        return {

            route_import    : {

                libelle                     :   "",
                file                        :   "",
                file_original_name          :   "",

                districts                   :   [],

                id_route_import_tempo       :   null,
                file_route_import_tempo     :   null
            },

            getDoublant                     :   {},

            show_card_doublants             :   false,

            //

            total_clients                   :   [],

            //

            users_all                       :   [],
            districts_all                   :   [],

            //

            validation_type                 :   null
        }
    },

    components : {

        ModalClientUpdate   :   ModalClientUpdate   , 
        ModalResume         :   ModalResume         ,
        ModalSubmit         :   ModalSubmit         ,

        CardDoublants       :   CardDoublants
    },

    async mounted() {

        await this.getDataTempo()
        await this.getDoubles()
        await this.getComboData()

        this.emitter.on('reSetClientsDevide' , async (clients)  =>  {

            for (let i = 0; i < this.total_clients.length; i++) {
                const client    =   this.total_clients[i]
                const upd       =   clients[client.id] // undefined if not present

                if (upd) {
                    client.JPlan    =   clients[client.id].JPlan
                    client.Journee  =   clients[client.id].Journee
                }
            }

            await this.getDoubles()
        })

        //

        this.emitter.on("reSetValidationClientUpdate"     , (validation_type) => {
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

        this.emitter.off('reSetClientsDecoupeByJourneeAdd')
        this.emitter.off('reSetUpdate')
        this.emitter.off('reSetDelete')

        this.emitter.off('reSetClientsDevide')
        this.emitter.off('reSetValidationClientUpdate')
        this.emitter.off('refreshDoublantCustomerCode')
        this.emitter.off('refreshDoublantCustomerNameE')
        this.emitter.off('refreshDoublantTel')
        this.emitter.off('refreshDoublantGPS')
    },

    methods : {

        async showResume() {
            await this.$refs.ModalResume.getClients(this.total_clients)
        },

        async submitRouteImport() {
            await this.$refs.ModalSubmit.setDatatable()
        },

        //

        async getDataTempo() {

            this.$showLoadingPage()

            // Set Data
            const res   = await this.$callApi('post' ,   '/route_import_tempo/last'    ,   null)         
            console.log(res)

            if(res.status===200){

                this.total_clients                          =   res.data.clients

                this.route_import.id_route_import_tempo     =   res.data.id
                this.route_import.libelle                   =   res.data.libelle
                this.route_import.districts                 =   res.data.districts
                this.route_import.file_route_import_tempo   =   res.data.file
                this.route_import.file_original_name        =   res.data.file_original_name

                //
                this.$createFile(res.data.file_original_name, "route_import_tempo_file")

                this.$hideLoadingPage()
            }
            
            else{

                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
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

            const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.route_import.id_route_import_tempo+"/clients_tempo/doubles", null)
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
                "RvrsGeoAddress"            ,
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