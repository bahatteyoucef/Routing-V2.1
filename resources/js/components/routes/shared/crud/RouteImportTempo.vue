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
                            <li class="list-item" v-for="district in route_import.districts" :key="district">{{ district.DistrictNo }}- {{ district.DistrictNameE }}</li>
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

    <ModalResume    ref="ModalResume"   :id_route_import_tempo="route_import.id_route_import_tempo"     :mode="'temporary'">    </ModalResume>
    <ModalSubmit    ref="ModalSubmit"   :id_route_import_tempo="route_import.id_route_import_tempo">                            </ModalSubmit>

</template>

<script>

import ModalResume                  from    "../operations/ModalResume.vue"
import ModalSubmit                  from    "./ModalSubmit.vue"

import CardDoublants                from    "@/components/routes/shared/operations/validations/CardDoublants.vue"

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

            getDoublant                     :   {}          ,

            show_card_doublants             :   false       ,

            //

            total_clients                   :   []
        }
    },

    components : {

        ModalResume                 :   ModalResume                 ,
        ModalSubmit                 :   ModalSubmit                 ,

        CardDoublants               :   CardDoublants
    },

    async mounted() {

        await this.getDataTempo()
        await this.getDoubles()

        this.emitter.on('reSetClientsDecoupeByJourneeAdd' , (clients)  =>  {
            this.total_clients  =   clients
        })

        this.emitter.on("reSetUpdate"  , async (client)    =>  {
            await this.updateClientJSON(client)
        })

        this.emitter.on("reSetDelete", async (client)    =>  {
            await this.deleteClientJSON(client)
        })

        //

        this.emitter.on("refreshDoublantCustomerCode"       , async (clients)    =>  {
            await this.updateDoublantsJSON(clients)
        })

        this.emitter.on("refreshDoublantCustomerNameE"      , async (clients)    =>  {
            await this.updateDoublantsJSON(clients)
        })

        this.emitter.on("refreshDoublantTel"                , async (clients)    =>  {
            await this.updateDoublantsJSON(clients)
        })

        this.emitter.on("refreshDoublantLatitudeLongitude"  , async (clients)    =>  {
            await this.updateDoublantsJSON(clients)
        })
    },

    unmounted() {

        this.emitter.off('reSetClientsDecoupeByJourneeAdd')
        this.emitter.off('reSetUpdate')
        this.emitter.off('reSetDelete')
        this.emitter.off('refreshDoublantCustomerCode')
        this.emitter.off('refreshDoublantCustomerNameE')
        this.emitter.off('refreshDoublantTel')
        this.emitter.off('refreshDoublantLatitudeLongitude')
    },

    methods : {

        async showResume() {
            await this.$refs.ModalResume.getClients()
        },

        async submitRouteImport() {
            await this.$refs.ModalSubmit.setResumeValidate()
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

        async getDoubles() {

            // Show Loading Page
            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.route_import.id_route_import_tempo+"/clients_tempo/doubles", null)
            console.log(res)

            if(res.status===200){

                this.getDoublant.getDoublantTel                 =   res.data.getDoublantTel
                this.getDoublant.getDoublantLatitudeLongitude   =   res.data.getDoublantLatitudeLongitude
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

        async updateDoublantsJSON(clients) {

            for (let index = 0; index < clients.length; index++) {
                
                // total_clients
                for (let i = 0; i < this.total_clients.length; i++) {
                    
                    if(this.total_clients[i].id  ==  clients[index].id) {

                        // Update Client
                        this.total_clients[i].NewCustomer                             =   clients[index].NewCustomer
                        this.total_clients[i].OpenCustomer                            =   clients[index].OpenCustomer
                        this.total_clients[i].CustomerIdentifier                      =   clients[index].CustomerIdentifier
                        this.total_clients[i].CustomerCode                            =   clients[index].CustomerCode

                        this.total_clients[i].CustomerNameE                           =   clients[index].CustomerNameE
                        this.total_clients[i].CustomerNameA                           =   clients[index].CustomerNameA

                        this.total_clients[i].Tel                                     =   clients[index].Tel
                        this.total_clients[i].tel_status                              =   clients[index].tel_status
                        this.total_clients[i].tel_comment                             =   clients[index].tel_comment

                        this.total_clients[i].Latitude                                =   clients[index].Latitude         
                        this.total_clients[i].Longitude                               =   clients[index].Longitude        

                        this.total_clients[i].Address                                 =   clients[index].Address
                        this.total_clients[i].Neighborhood                            =   clients[index].Neighborhood
                        this.total_clients[i].Landmark                                =   clients[index].Landmark

                        this.total_clients[i].DistrictNo                              =   clients[index].DistrictNo      
                        this.total_clients[i].DistrictNameE                           =   clients[index].DistrictNameE  

                        this.total_clients[i].CityNo                                  =   clients[index].CityNo           
                        this.total_clients[i].CityNameE                               =   clients[index].CityNameE       

                        this.total_clients[i].CustomerType                            =   clients[index].CustomerType     

                        this.total_clients[i].BrandAvailability                       =   clients[index].BrandAvailability       
                        this.total_clients[i].BrandSourcePurchase                     =   clients[index].BrandSourcePurchase       

                        this.total_clients[i].JPlan                                   =   clients[index].JPlan            
                        this.total_clients[i].Journee                                 =   clients[index].Journee        

                        this.total_clients[i].Frequency                               =   clients[index].Frequency        
                        this.total_clients[i].SuperficieMagasin                       =   clients[index].SuperficieMagasin        
                        this.total_clients[i].NbrAutomaticCheckouts                   =   clients[index].NbrAutomaticCheckouts        

                        this.total_clients[i].AvailableBrands                         =   clients[index].AvailableBrands
                        this.total_clients[i].AvailableBrands_array_formatted         =   clients[index].AvailableBrands_array_formatted      // should be array
                        this.total_clients[i].AvailableBrands_string_formatted        =   clients[index].AvailableBrands_string_formatted     // should be string

                        this.total_clients[i].status                                  =   clients[index].status            
                        this.total_clients[i].nonvalidated_details                    =   clients[index].nonvalidated_details        

                        this.total_clients[i].owner                                   =   clients[index].owner
                        this.total_clients[i].owner_name                              =   clients[index].owner_name

                        this.total_clients[i].comment                                 =   clients[index].comment        

                        this.total_clients[i].facade_image                            =   clients[index].facade_image            
                        this.total_clients[i].in_store_image                          =   clients[index].in_store_image        
                        this.total_clients[i].facade_image_original_name              =   clients[index].facade_image_original_name            
                        this.total_clients[i].in_store_image_original_name            =   clients[index].in_store_image_original_name        
                        this.total_clients[i].CustomerBarCode_image                   =   clients[index].CustomerBarCode_image            
                        this.total_clients[i].CustomerBarCode_image_original_name     =   clients[index].CustomerBarCode_image_original_name        

                        break
                    }
                }

                // getDoublantCustomerCode
                for (let i = 0; i < this.getDoublant.getDoublantCustomerCode.length; i++) {
                    
                    if(this.getDoublant.getDoublantCustomerCode[i].id  ==  clients[index].id) {

                        // Update Client
                        this.getDoublant.getDoublantCustomerCode[i].NewCustomer                             =   clients[index].NewCustomer
                        this.getDoublant.getDoublantCustomerCode[i].OpenCustomer                            =   clients[index].OpenCustomer
                        this.getDoublant.getDoublantCustomerCode[i].CustomerIdentifier                      =   clients[index].CustomerIdentifier
                        this.getDoublant.getDoublantCustomerCode[i].CustomerCode                            =   clients[index].CustomerCode

                        this.getDoublant.getDoublantCustomerCode[i].CustomerNameE                           =   clients[index].CustomerNameE
                        this.getDoublant.getDoublantCustomerCode[i].CustomerNameA                           =   clients[index].CustomerNameA

                        this.getDoublant.getDoublantCustomerCode[i].Tel                                     =   clients[index].Tel
                        this.getDoublant.getDoublantCustomerCode[i].tel_status                              =   clients[index].tel_status
                        this.getDoublant.getDoublantCustomerCode[i].tel_comment                             =   clients[index].tel_comment

                        this.getDoublant.getDoublantCustomerCode[i].Latitude                                =   clients[index].Latitude         
                        this.getDoublant.getDoublantCustomerCode[i].Longitude                               =   clients[index].Longitude        

                        this.getDoublant.getDoublantCustomerCode[i].Address                                 =   clients[index].Address
                        this.getDoublant.getDoublantCustomerCode[i].Neighborhood                            =   clients[index].Neighborhood
                        this.getDoublant.getDoublantCustomerCode[i].Landmark                                =   clients[index].Landmark

                        this.getDoublant.getDoublantCustomerCode[i].DistrictNo                              =   clients[index].DistrictNo      
                        this.getDoublant.getDoublantCustomerCode[i].DistrictNameE                           =   clients[index].DistrictNameE  

                        this.getDoublant.getDoublantCustomerCode[i].CityNo                                  =   clients[index].CityNo           
                        this.getDoublant.getDoublantCustomerCode[i].CityNameE                               =   clients[index].CityNameE       

                        this.getDoublant.getDoublantCustomerCode[i].CustomerType                            =   clients[index].CustomerType     

                        this.getDoublant.getDoublantCustomerCode[i].BrandAvailability                       =   clients[index].BrandAvailability       
                        this.getDoublant.getDoublantCustomerCode[i].BrandSourcePurchase                     =   clients[index].BrandSourcePurchase       

                        this.getDoublant.getDoublantCustomerCode[i].JPlan                                   =   clients[index].JPlan            
                        this.getDoublant.getDoublantCustomerCode[i].Journee                                 =   clients[index].Journee        

                        this.getDoublant.getDoublantCustomerCode[i].Frequency                               =   clients[index].Frequency        
                        this.getDoublant.getDoublantCustomerCode[i].SuperficieMagasin                       =   clients[index].SuperficieMagasin        
                        this.getDoublant.getDoublantCustomerCode[i].NbrAutomaticCheckouts                   =   clients[index].NbrAutomaticCheckouts        

                        this.getDoublant.getDoublantCustomerCode[i].AvailableBrands                         =   clients[index].AvailableBrands
                        this.getDoublant.getDoublantCustomerCode[i].AvailableBrands_array_formatted         =   clients[index].AvailableBrands_array_formatted      // should be array
                        this.getDoublant.getDoublantCustomerCode[i].AvailableBrands_string_formatted        =   clients[index].AvailableBrands_string_formatted     // should be string

                        this.getDoublant.getDoublantCustomerCode[i].status                                  =   clients[index].status            
                        this.getDoublant.getDoublantCustomerCode[i].nonvalidated_details                    =   clients[index].nonvalidated_details        

                        this.getDoublant.getDoublantCustomerCode[i].owner                                   =   clients[index].owner
                        this.getDoublant.getDoublantCustomerCode[i].owner_name                              =   clients[index].owner_name

                        this.getDoublant.getDoublantCustomerCode[i].comment                                 =   clients[index].comment        

                        this.getDoublant.getDoublantCustomerCode[i].facade_image                            =   clients[index].facade_image            
                        this.getDoublant.getDoublantCustomerCode[i].in_store_image                          =   clients[index].in_store_image        
                        this.getDoublant.getDoublantCustomerCode[i].facade_image_original_name              =   clients[index].facade_image_original_name            
                        this.getDoublant.getDoublantCustomerCode[i].in_store_image_original_name            =   clients[index].in_store_image_original_name        
                        this.getDoublant.getDoublantCustomerCode[i].CustomerBarCode_image                   =   clients[index].CustomerBarCode_image            
                        this.getDoublant.getDoublantCustomerCode[i].CustomerBarCode_image_original_name     =   clients[index].CustomerBarCode_image_original_name        

                        break
                    }
                }

                // getDoublantCustomerNameE
                for (let i = 0; i < this.getDoublant.getDoublantCustomerNameE.length; i++) {
                    
                    if(this.getDoublant.getDoublantCustomerNameE[i].id  ==  clients[index].id) {

                        // Update Client
                        this.getDoublant.getDoublantCustomerNameE[i].NewCustomer                             =   clients[index].NewCustomer
                        this.getDoublant.getDoublantCustomerNameE[i].OpenCustomer                            =   clients[index].OpenCustomer
                        this.getDoublant.getDoublantCustomerNameE[i].CustomerIdentifier                      =   clients[index].CustomerIdentifier
                        this.getDoublant.getDoublantCustomerNameE[i].CustomerCode                            =   clients[index].CustomerCode

                        this.getDoublant.getDoublantCustomerNameE[i].CustomerNameE                           =   clients[index].CustomerNameE
                        this.getDoublant.getDoublantCustomerNameE[i].CustomerNameA                           =   clients[index].CustomerNameA

                        this.getDoublant.getDoublantCustomerNameE[i].Tel                                     =   clients[index].Tel
                        this.getDoublant.getDoublantCustomerNameE[i].tel_status                              =   clients[index].tel_status
                        this.getDoublant.getDoublantCustomerNameE[i].tel_comment                             =   clients[index].tel_comment

                        this.getDoublant.getDoublantCustomerNameE[i].Latitude                                =   clients[index].Latitude         
                        this.getDoublant.getDoublantCustomerNameE[i].Longitude                               =   clients[index].Longitude        

                        this.getDoublant.getDoublantCustomerNameE[i].Address                                 =   clients[index].Address
                        this.getDoublant.getDoublantCustomerNameE[i].Neighborhood                            =   clients[index].Neighborhood
                        this.getDoublant.getDoublantCustomerNameE[i].Landmark                                =   clients[index].Landmark

                        this.getDoublant.getDoublantCustomerNameE[i].DistrictNo                              =   clients[index].DistrictNo      
                        this.getDoublant.getDoublantCustomerNameE[i].DistrictNameE                           =   clients[index].DistrictNameE  

                        this.getDoublant.getDoublantCustomerNameE[i].CityNo                                  =   clients[index].CityNo           
                        this.getDoublant.getDoublantCustomerNameE[i].CityNameE                               =   clients[index].CityNameE       

                        this.getDoublant.getDoublantCustomerNameE[i].CustomerType                            =   clients[index].CustomerType     

                        this.getDoublant.getDoublantCustomerNameE[i].BrandAvailability                       =   clients[index].BrandAvailability       
                        this.getDoublant.getDoublantCustomerNameE[i].BrandSourcePurchase                     =   clients[index].BrandSourcePurchase       

                        this.getDoublant.getDoublantCustomerNameE[i].JPlan                                   =   clients[index].JPlan            
                        this.getDoublant.getDoublantCustomerNameE[i].Journee                                 =   clients[index].Journee        

                        this.getDoublant.getDoublantCustomerNameE[i].Frequency                               =   clients[index].Frequency        
                        this.getDoublant.getDoublantCustomerNameE[i].SuperficieMagasin                       =   clients[index].SuperficieMagasin        
                        this.getDoublant.getDoublantCustomerNameE[i].NbrAutomaticCheckouts                   =   clients[index].NbrAutomaticCheckouts        

                        this.getDoublant.getDoublantCustomerNameE[i].AvailableBrands                         =   clients[index].AvailableBrands
                        this.getDoublant.getDoublantCustomerNameE[i].AvailableBrands_array_formatted         =   clients[index].AvailableBrands_array_formatted      // should be array
                        this.getDoublant.getDoublantCustomerNameE[i].AvailableBrands_string_formatted        =   clients[index].AvailableBrands_string_formatted     // should be string

                        this.getDoublant.getDoublantCustomerNameE[i].status                                  =   clients[index].status            
                        this.getDoublant.getDoublantCustomerNameE[i].nonvalidated_details                    =   clients[index].nonvalidated_details        

                        this.getDoublant.getDoublantCustomerNameE[i].owner                                   =   clients[index].owner
                        this.getDoublant.getDoublantCustomerNameE[i].owner_name                              =   clients[index].owner_name

                        this.getDoublant.getDoublantCustomerNameE[i].comment                                 =   clients[index].comment        

                        this.getDoublant.getDoublantCustomerNameE[i].facade_image                            =   clients[index].facade_image            
                        this.getDoublant.getDoublantCustomerNameE[i].in_store_image                          =   clients[index].in_store_image        
                        this.getDoublant.getDoublantCustomerNameE[i].facade_image_original_name              =   clients[index].facade_image_original_name            
                        this.getDoublant.getDoublantCustomerNameE[i].in_store_image_original_name            =   clients[index].in_store_image_original_name        
                        this.getDoublant.getDoublantCustomerNameE[i].CustomerBarCode_image                   =   clients[index].CustomerBarCode_image            
                        this.getDoublant.getDoublantCustomerNameE[i].CustomerBarCode_image_original_name     =   clients[index].CustomerBarCode_image_original_name        

                        break
                    }
                }

                // getDoublantTel
                for (let i = 0; i < this.getDoublant.getDoublantTel.length; i++) {
                    
                    if(this.getDoublant.getDoublantTel[i].id  ==  clients[index].id) {

                        // Update Client
                        this.getDoublant.getDoublantTel[i].NewCustomer                             =   clients[index].NewCustomer
                        this.getDoublant.getDoublantTel[i].OpenCustomer                            =   clients[index].OpenCustomer
                        this.getDoublant.getDoublantTel[i].CustomerIdentifier                      =   clients[index].CustomerIdentifier
                        this.getDoublant.getDoublantTel[i].CustomerCode                            =   clients[index].CustomerCode

                        this.getDoublant.getDoublantTel[i].CustomerNameE                           =   clients[index].CustomerNameE
                        this.getDoublant.getDoublantTel[i].CustomerNameA                           =   clients[index].CustomerNameA

                        this.getDoublant.getDoublantTel[i].Tel                                     =   clients[index].Tel
                        this.getDoublant.getDoublantTel[i].tel_status                              =   clients[index].tel_status
                        this.getDoublant.getDoublantTel[i].tel_comment                             =   clients[index].tel_comment

                        this.getDoublant.getDoublantTel[i].Latitude                                =   clients[index].Latitude         
                        this.getDoublant.getDoublantTel[i].Longitude                               =   clients[index].Longitude        

                        this.getDoublant.getDoublantTel[i].Address                                 =   clients[index].Address
                        this.getDoublant.getDoublantTel[i].Neighborhood                            =   clients[index].Neighborhood
                        this.getDoublant.getDoublantTel[i].Landmark                                =   clients[index].Landmark

                        this.getDoublant.getDoublantTel[i].DistrictNo                              =   clients[index].DistrictNo      
                        this.getDoublant.getDoublantTel[i].DistrictNameE                           =   clients[index].DistrictNameE  

                        this.getDoublant.getDoublantTel[i].CityNo                                  =   clients[index].CityNo           
                        this.getDoublant.getDoublantTel[i].CityNameE                               =   clients[index].CityNameE       

                        this.getDoublant.getDoublantTel[i].CustomerType                            =   clients[index].CustomerType     

                        this.getDoublant.getDoublantTel[i].BrandAvailability                       =   clients[index].BrandAvailability       
                        this.getDoublant.getDoublantTel[i].BrandSourcePurchase                     =   clients[index].BrandSourcePurchase       

                        this.getDoublant.getDoublantTel[i].JPlan                                   =   clients[index].JPlan            
                        this.getDoublant.getDoublantTel[i].Journee                                 =   clients[index].Journee        

                        this.getDoublant.getDoublantTel[i].Frequency                               =   clients[index].Frequency        
                        this.getDoublant.getDoublantTel[i].SuperficieMagasin                       =   clients[index].SuperficieMagasin        
                        this.getDoublant.getDoublantTel[i].NbrAutomaticCheckouts                   =   clients[index].NbrAutomaticCheckouts        

                        this.getDoublant.getDoublantTel[i].AvailableBrands                         =   clients[index].AvailableBrands
                        this.getDoublant.getDoublantTel[i].AvailableBrands_array_formatted         =   clients[index].AvailableBrands_array_formatted      // should be array
                        this.getDoublant.getDoublantTel[i].AvailableBrands_string_formatted        =   clients[index].AvailableBrands_string_formatted     // should be string

                        this.getDoublant.getDoublantTel[i].status                                  =   clients[index].status            
                        this.getDoublant.getDoublantTel[i].nonvalidated_details                    =   clients[index].nonvalidated_details        

                        this.getDoublant.getDoublantTel[i].owner                                   =   clients[index].owner
                        this.getDoublant.getDoublantTel[i].owner_name                              =   clients[index].owner_name

                        this.getDoublant.getDoublantTel[i].comment                                 =   clients[index].comment        

                        this.getDoublant.getDoublantTel[i].facade_image                            =   clients[index].facade_image            
                        this.getDoublant.getDoublantTel[i].in_store_image                          =   clients[index].in_store_image        
                        this.getDoublant.getDoublantTel[i].facade_image_original_name              =   clients[index].facade_image_original_name            
                        this.getDoublant.getDoublantTel[i].in_store_image_original_name            =   clients[index].in_store_image_original_name        
                        this.getDoublant.getDoublantTel[i].CustomerBarCode_image                   =   clients[index].CustomerBarCode_image            
                        this.getDoublant.getDoublantTel[i].CustomerBarCode_image_original_name     =   clients[index].CustomerBarCode_image_original_name        

                        break
                    }
                }

                // getDoublantLatitudeLongitude
                for (let i = 0; i < this.getDoublant.getDoublantLatitudeLongitude.length; i++) {
                    
                    if(this.getDoublant.getDoublantLatitudeLongitude[i].id  ==  clients[index].id) {

                        // Update Client
                        this.getDoublant.getDoublantLatitudeLongitude[i].NewCustomer                             =   clients[index].NewCustomer
                        this.getDoublant.getDoublantLatitudeLongitude[i].OpenCustomer                            =   clients[index].OpenCustomer
                        this.getDoublant.getDoublantLatitudeLongitude[i].CustomerIdentifier                      =   clients[index].CustomerIdentifier
                        this.getDoublant.getDoublantLatitudeLongitude[i].CustomerCode                            =   clients[index].CustomerCode

                        this.getDoublant.getDoublantLatitudeLongitude[i].CustomerNameE                           =   clients[index].CustomerNameE
                        this.getDoublant.getDoublantLatitudeLongitude[i].CustomerNameA                           =   clients[index].CustomerNameA

                        this.getDoublant.getDoublantLatitudeLongitude[i].Tel                                     =   clients[index].Tel
                        this.getDoublant.getDoublantLatitudeLongitude[i].tel_status                              =   clients[index].tel_status
                        this.getDoublant.getDoublantLatitudeLongitude[i].tel_comment                             =   clients[index].tel_comment

                        this.getDoublant.getDoublantLatitudeLongitude[i].Latitude                                =   clients[index].Latitude         
                        this.getDoublant.getDoublantLatitudeLongitude[i].Longitude                               =   clients[index].Longitude        

                        this.getDoublant.getDoublantLatitudeLongitude[i].Address                                 =   clients[index].Address
                        this.getDoublant.getDoublantLatitudeLongitude[i].Neighborhood                            =   clients[index].Neighborhood
                        this.getDoublant.getDoublantLatitudeLongitude[i].Landmark                                =   clients[index].Landmark

                        this.getDoublant.getDoublantLatitudeLongitude[i].DistrictNo                              =   clients[index].DistrictNo      
                        this.getDoublant.getDoublantLatitudeLongitude[i].DistrictNameE                           =   clients[index].DistrictNameE  

                        this.getDoublant.getDoublantLatitudeLongitude[i].CityNo                                  =   clients[index].CityNo           
                        this.getDoublant.getDoublantLatitudeLongitude[i].CityNameE                               =   clients[index].CityNameE       

                        this.getDoublant.getDoublantLatitudeLongitude[i].CustomerType                            =   clients[index].CustomerType     

                        this.getDoublant.getDoublantLatitudeLongitude[i].BrandAvailability                       =   clients[index].BrandAvailability       
                        this.getDoublant.getDoublantLatitudeLongitude[i].BrandSourcePurchase                     =   clients[index].BrandSourcePurchase       

                        this.getDoublant.getDoublantLatitudeLongitude[i].JPlan                                   =   clients[index].JPlan            
                        this.getDoublant.getDoublantLatitudeLongitude[i].Journee                                 =   clients[index].Journee        

                        this.getDoublant.getDoublantLatitudeLongitude[i].Frequency                               =   clients[index].Frequency        
                        this.getDoublant.getDoublantLatitudeLongitude[i].SuperficieMagasin                       =   clients[index].SuperficieMagasin        
                        this.getDoublant.getDoublantLatitudeLongitude[i].NbrAutomaticCheckouts                   =   clients[index].NbrAutomaticCheckouts        

                        this.getDoublant.getDoublantLatitudeLongitude[i].AvailableBrands                         =   clients[index].AvailableBrands
                        this.getDoublant.getDoublantLatitudeLongitude[i].AvailableBrands_array_formatted         =   clients[index].AvailableBrands_array_formatted      // should be array
                        this.getDoublant.getDoublantLatitudeLongitude[i].AvailableBrands_string_formatted        =   clients[index].AvailableBrands_string_formatted     // should be string

                        this.getDoublant.getDoublantLatitudeLongitude[i].status                                  =   clients[index].status            
                        this.getDoublant.getDoublantLatitudeLongitude[i].nonvalidated_details                    =   clients[index].nonvalidated_details        

                        this.getDoublant.getDoublantLatitudeLongitude[i].owner                                   =   clients[index].owner
                        this.getDoublant.getDoublantLatitudeLongitude[i].owner_name                              =   clients[index].owner_name

                        this.getDoublant.getDoublantLatitudeLongitude[i].comment                                 =   clients[index].comment        

                        this.getDoublant.getDoublantLatitudeLongitude[i].facade_image                            =   clients[index].facade_image            
                        this.getDoublant.getDoublantLatitudeLongitude[i].in_store_image                          =   clients[index].in_store_image        
                        this.getDoublant.getDoublantLatitudeLongitude[i].facade_image_original_name              =   clients[index].facade_image_original_name            
                        this.getDoublant.getDoublantLatitudeLongitude[i].in_store_image_original_name            =   clients[index].in_store_image_original_name        
                        this.getDoublant.getDoublantLatitudeLongitude[i].CustomerBarCode_image                   =   clients[index].CustomerBarCode_image            
                        this.getDoublant.getDoublantLatitudeLongitude[i].CustomerBarCode_image_original_name     =   clients[index].CustomerBarCode_image_original_name        

                        break
                    }
                }
            }
        },

        //

        async updateClientJSON(client) {

            // total_clients
            for (let i = 0; i < this.total_clients.length; i++) {
                
                if(this.total_clients[i].id  ==  client.id) {

                    // Update Client
                    this.total_clients[i].NewCustomer                             =   client.NewCustomer
                    this.total_clients[i].OpenCustomer                            =   client.OpenCustomer
                    this.total_clients[i].CustomerIdentifier                      =   client.CustomerIdentifier
                    this.total_clients[i].CustomerCode                            =   client.CustomerCode

                    this.total_clients[i].CustomerNameE                           =   client.CustomerNameE
                    this.total_clients[i].CustomerNameA                           =   client.CustomerNameA

                    this.total_clients[i].Tel                                     =   client.Tel
                    this.total_clients[i].tel_status                              =   client.tel_status
                    this.total_clients[i].tel_comment                             =   client.tel_comment

                    this.total_clients[i].Latitude                                =   client.Latitude         
                    this.total_clients[i].Longitude                               =   client.Longitude        

                    this.total_clients[i].Address                                 =   client.Address
                    this.total_clients[i].Neighborhood                            =   client.Neighborhood
                    this.total_clients[i].Landmark                                =   client.Landmark

                    this.total_clients[i].DistrictNo                              =   client.DistrictNo      
                    this.total_clients[i].DistrictNameE                           =   client.DistrictNameE  

                    this.total_clients[i].CityNo                                  =   client.CityNo           
                    this.total_clients[i].CityNameE                               =   client.CityNameE       

                    this.total_clients[i].CustomerType                            =   client.CustomerType     

                    this.total_clients[i].BrandAvailability                       =   client.BrandAvailability       
                    this.total_clients[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                    this.total_clients[i].JPlan                                   =   client.JPlan            
                    this.total_clients[i].Journee                                 =   client.Journee        

                    this.total_clients[i].Frequency                               =   client.Frequency        
                    this.total_clients[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                    this.total_clients[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                    this.total_clients[i].AvailableBrands                         =   client.AvailableBrands
                    this.total_clients[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                    this.total_clients[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                    this.total_clients[i].status                                  =   client.status            
                    this.total_clients[i].nonvalidated_details                    =   client.nonvalidated_details        

                    this.total_clients[i].owner                                   =   client.owner
                    this.total_clients[i].owner_name                              =   client.owner_name

                    this.total_clients[i].comment                                 =   client.comment        

                    this.total_clients[i].facade_image                            =   client.facade_image            
                    this.total_clients[i].in_store_image                          =   client.in_store_image        
                    this.total_clients[i].facade_image_original_name              =   client.facade_image_original_name            
                    this.total_clients[i].in_store_image_original_name            =   client.in_store_image_original_name        
                    this.total_clients[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                    this.total_clients[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                    break
                }
            }

            // getDoublantCustomerCode
            for (let i = 0; i < this.getDoublant.getDoublantCustomerCode.length; i++) {
                
                if(this.getDoublant.getDoublantCustomerCode[i].id  ==  client.id) {

                    // Update Client
                    this.getDoublant.getDoublantCustomerCode[i].NewCustomer                             =   client.NewCustomer
                    this.getDoublant.getDoublantCustomerCode[i].OpenCustomer                            =   client.OpenCustomer
                    this.getDoublant.getDoublantCustomerCode[i].CustomerIdentifier                      =   client.CustomerIdentifier
                    this.getDoublant.getDoublantCustomerCode[i].CustomerCode                            =   client.CustomerCode

                    this.getDoublant.getDoublantCustomerCode[i].CustomerNameE                           =   client.CustomerNameE
                    this.getDoublant.getDoublantCustomerCode[i].CustomerNameA                           =   client.CustomerNameA

                    this.getDoublant.getDoublantCustomerCode[i].Tel                                     =   client.Tel
                    this.getDoublant.getDoublantCustomerCode[i].tel_status                              =   client.tel_status
                    this.getDoublant.getDoublantCustomerCode[i].tel_comment                             =   client.tel_comment

                    this.getDoublant.getDoublantCustomerCode[i].Latitude                                =   client.Latitude         
                    this.getDoublant.getDoublantCustomerCode[i].Longitude                               =   client.Longitude        

                    this.getDoublant.getDoublantCustomerCode[i].Address                                 =   client.Address
                    this.getDoublant.getDoublantCustomerCode[i].Neighborhood                            =   client.Neighborhood
                    this.getDoublant.getDoublantCustomerCode[i].Landmark                                =   client.Landmark

                    this.getDoublant.getDoublantCustomerCode[i].DistrictNo                              =   client.DistrictNo      
                    this.getDoublant.getDoublantCustomerCode[i].DistrictNameE                           =   client.DistrictNameE  

                    this.getDoublant.getDoublantCustomerCode[i].CityNo                                  =   client.CityNo           
                    this.getDoublant.getDoublantCustomerCode[i].CityNameE                               =   client.CityNameE       

                    this.getDoublant.getDoublantCustomerCode[i].CustomerType                            =   client.CustomerType     

                    this.getDoublant.getDoublantCustomerCode[i].BrandAvailability                       =   client.BrandAvailability       
                    this.getDoublant.getDoublantCustomerCode[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                    this.getDoublant.getDoublantCustomerCode[i].JPlan                                   =   client.JPlan            
                    this.getDoublant.getDoublantCustomerCode[i].Journee                                 =   client.Journee        

                    this.getDoublant.getDoublantCustomerCode[i].Frequency                               =   client.Frequency        
                    this.getDoublant.getDoublantCustomerCode[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                    this.getDoublant.getDoublantCustomerCode[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                    this.getDoublant.getDoublantCustomerCode[i].AvailableBrands                         =   client.AvailableBrands
                    this.getDoublant.getDoublantCustomerCode[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                    this.getDoublant.getDoublantCustomerCode[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                    this.getDoublant.getDoublantCustomerCode[i].status                                  =   client.status            
                    this.getDoublant.getDoublantCustomerCode[i].nonvalidated_details                    =   client.nonvalidated_details        

                    this.getDoublant.getDoublantCustomerCode[i].owner                                   =   client.owner
                    this.getDoublant.getDoublantCustomerCode[i].owner_name                              =   client.owner_name

                    this.getDoublant.getDoublantCustomerCode[i].comment                                 =   client.comment        

                    this.getDoublant.getDoublantCustomerCode[i].facade_image                            =   client.facade_image            
                    this.getDoublant.getDoublantCustomerCode[i].in_store_image                          =   client.in_store_image        
                    this.getDoublant.getDoublantCustomerCode[i].facade_image_original_name              =   client.facade_image_original_name            
                    this.getDoublant.getDoublantCustomerCode[i].in_store_image_original_name            =   client.in_store_image_original_name        
                    this.getDoublant.getDoublantCustomerCode[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                    this.getDoublant.getDoublantCustomerCode[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                    break
                }
            }

            // getDoublantCustomerNameE
            for (let i = 0; i < this.getDoublant.getDoublantCustomerNameE.length; i++) {
                
                if(this.getDoublant.getDoublantCustomerNameE[i].id  ==  client.id) {

                    // Update Client
                    this.getDoublant.getDoublantCustomerNameE[i].NewCustomer                             =   client.NewCustomer
                    this.getDoublant.getDoublantCustomerNameE[i].OpenCustomer                            =   client.OpenCustomer
                    this.getDoublant.getDoublantCustomerNameE[i].CustomerIdentifier                      =   client.CustomerIdentifier
                    this.getDoublant.getDoublantCustomerNameE[i].CustomerCode                            =   client.CustomerCode

                    this.getDoublant.getDoublantCustomerNameE[i].CustomerNameE                           =   client.CustomerNameE
                    this.getDoublant.getDoublantCustomerNameE[i].CustomerNameA                           =   client.CustomerNameA

                    this.getDoublant.getDoublantCustomerNameE[i].Tel                                     =   client.Tel
                    this.getDoublant.getDoublantCustomerNameE[i].tel_status                              =   client.tel_status
                    this.getDoublant.getDoublantCustomerNameE[i].tel_comment                             =   client.tel_comment

                    this.getDoublant.getDoublantCustomerNameE[i].Latitude                                =   client.Latitude         
                    this.getDoublant.getDoublantCustomerNameE[i].Longitude                               =   client.Longitude        

                    this.getDoublant.getDoublantCustomerNameE[i].Address                                 =   client.Address
                    this.getDoublant.getDoublantCustomerNameE[i].Neighborhood                            =   client.Neighborhood
                    this.getDoublant.getDoublantCustomerNameE[i].Landmark                                =   client.Landmark

                    this.getDoublant.getDoublantCustomerNameE[i].DistrictNo                              =   client.DistrictNo      
                    this.getDoublant.getDoublantCustomerNameE[i].DistrictNameE                           =   client.DistrictNameE  

                    this.getDoublant.getDoublantCustomerNameE[i].CityNo                                  =   client.CityNo           
                    this.getDoublant.getDoublantCustomerNameE[i].CityNameE                               =   client.CityNameE       

                    this.getDoublant.getDoublantCustomerNameE[i].CustomerType                            =   client.CustomerType     

                    this.getDoublant.getDoublantCustomerNameE[i].BrandAvailability                       =   client.BrandAvailability       
                    this.getDoublant.getDoublantCustomerNameE[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                    this.getDoublant.getDoublantCustomerNameE[i].JPlan                                   =   client.JPlan            
                    this.getDoublant.getDoublantCustomerNameE[i].Journee                                 =   client.Journee        

                    this.getDoublant.getDoublantCustomerNameE[i].Frequency                               =   client.Frequency        
                    this.getDoublant.getDoublantCustomerNameE[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                    this.getDoublant.getDoublantCustomerNameE[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                    this.getDoublant.getDoublantCustomerNameE[i].AvailableBrands                         =   client.AvailableBrands
                    this.getDoublant.getDoublantCustomerNameE[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                    this.getDoublant.getDoublantCustomerNameE[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                    this.getDoublant.getDoublantCustomerNameE[i].status                                  =   client.status            
                    this.getDoublant.getDoublantCustomerNameE[i].nonvalidated_details                    =   client.nonvalidated_details        

                    this.getDoublant.getDoublantCustomerNameE[i].owner                                   =   client.owner
                    this.getDoublant.getDoublantCustomerNameE[i].owner_name                              =   client.owner_name

                    this.getDoublant.getDoublantCustomerNameE[i].comment                                 =   client.comment        

                    this.getDoublant.getDoublantCustomerNameE[i].facade_image                            =   client.facade_image            
                    this.getDoublant.getDoublantCustomerNameE[i].in_store_image                          =   client.in_store_image        
                    this.getDoublant.getDoublantCustomerNameE[i].facade_image_original_name              =   client.facade_image_original_name            
                    this.getDoublant.getDoublantCustomerNameE[i].in_store_image_original_name            =   client.in_store_image_original_name        
                    this.getDoublant.getDoublantCustomerNameE[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                    this.getDoublant.getDoublantCustomerNameE[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                    break
                }
            }

            // getDoublantTel
            for (let i = 0; i < this.getDoublant.getDoublantTel.length; i++) {
                
                if(this.getDoublant.getDoublantTel[i].id  ==  client.id) {

                    // Update Client
                    this.getDoublant.getDoublantTel[i].NewCustomer                             =   client.NewCustomer
                    this.getDoublant.getDoublantTel[i].OpenCustomer                            =   client.OpenCustomer
                    this.getDoublant.getDoublantTel[i].CustomerIdentifier                      =   client.CustomerIdentifier
                    this.getDoublant.getDoublantTel[i].CustomerCode                            =   client.CustomerCode

                    this.getDoublant.getDoublantTel[i].CustomerNameE                           =   client.CustomerNameE
                    this.getDoublant.getDoublantTel[i].CustomerNameA                           =   client.CustomerNameA

                    this.getDoublant.getDoublantTel[i].Tel                                     =   client.Tel
                    this.getDoublant.getDoublantTel[i].tel_status                              =   client.tel_status
                    this.getDoublant.getDoublantTel[i].tel_comment                             =   client.tel_comment

                    this.getDoublant.getDoublantTel[i].Latitude                                =   client.Latitude         
                    this.getDoublant.getDoublantTel[i].Longitude                               =   client.Longitude        

                    this.getDoublant.getDoublantTel[i].Address                                 =   client.Address
                    this.getDoublant.getDoublantTel[i].Neighborhood                            =   client.Neighborhood
                    this.getDoublant.getDoublantTel[i].Landmark                                =   client.Landmark

                    this.getDoublant.getDoublantTel[i].DistrictNo                              =   client.DistrictNo      
                    this.getDoublant.getDoublantTel[i].DistrictNameE                           =   client.DistrictNameE  

                    this.getDoublant.getDoublantTel[i].CityNo                                  =   client.CityNo           
                    this.getDoublant.getDoublantTel[i].CityNameE                               =   client.CityNameE       

                    this.getDoublant.getDoublantTel[i].CustomerType                            =   client.CustomerType     

                    this.getDoublant.getDoublantTel[i].BrandAvailability                       =   client.BrandAvailability       
                    this.getDoublant.getDoublantTel[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                    this.getDoublant.getDoublantTel[i].JPlan                                   =   client.JPlan            
                    this.getDoublant.getDoublantTel[i].Journee                                 =   client.Journee        

                    this.getDoublant.getDoublantTel[i].Frequency                               =   client.Frequency        
                    this.getDoublant.getDoublantTel[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                    this.getDoublant.getDoublantTel[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                    this.getDoublant.getDoublantTel[i].AvailableBrands                         =   client.AvailableBrands
                    this.getDoublant.getDoublantTel[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                    this.getDoublant.getDoublantTel[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                    this.getDoublant.getDoublantTel[i].status                                  =   client.status            
                    this.getDoublant.getDoublantTel[i].nonvalidated_details                    =   client.nonvalidated_details        

                    this.getDoublant.getDoublantTel[i].owner                                   =   client.owner
                    this.getDoublant.getDoublantTel[i].owner_name                              =   client.owner_name

                    this.getDoublant.getDoublantTel[i].comment                                 =   client.comment        

                    this.getDoublant.getDoublantTel[i].facade_image                            =   client.facade_image            
                    this.getDoublant.getDoublantTel[i].in_store_image                          =   client.in_store_image        
                    this.getDoublant.getDoublantTel[i].facade_image_original_name              =   client.facade_image_original_name            
                    this.getDoublant.getDoublantTel[i].in_store_image_original_name            =   client.in_store_image_original_name        
                    this.getDoublant.getDoublantTel[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                    this.getDoublant.getDoublantTel[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                    break
                }
            }

            // getDoublantLatitudeLongitude
            for (let i = 0; i < this.getDoublant.getDoublantLatitudeLongitude.length; i++) {
                
                if(this.getDoublant.getDoublantLatitudeLongitude[i].id  ==  client.id) {

                    // Update Client
                    this.getDoublant.getDoublantLatitudeLongitude[i].NewCustomer                             =   client.NewCustomer
                    this.getDoublant.getDoublantLatitudeLongitude[i].OpenCustomer                            =   client.OpenCustomer
                    this.getDoublant.getDoublantLatitudeLongitude[i].CustomerIdentifier                      =   client.CustomerIdentifier
                    this.getDoublant.getDoublantLatitudeLongitude[i].CustomerCode                            =   client.CustomerCode

                    this.getDoublant.getDoublantLatitudeLongitude[i].CustomerNameE                           =   client.CustomerNameE
                    this.getDoublant.getDoublantLatitudeLongitude[i].CustomerNameA                           =   client.CustomerNameA

                    this.getDoublant.getDoublantLatitudeLongitude[i].Tel                                     =   client.Tel
                    this.getDoublant.getDoublantLatitudeLongitude[i].tel_status                              =   client.tel_status
                    this.getDoublant.getDoublantLatitudeLongitude[i].tel_comment                             =   client.tel_comment

                    this.getDoublant.getDoublantLatitudeLongitude[i].Latitude                                =   client.Latitude         
                    this.getDoublant.getDoublantLatitudeLongitude[i].Longitude                               =   client.Longitude        

                    this.getDoublant.getDoublantLatitudeLongitude[i].Address                                 =   client.Address
                    this.getDoublant.getDoublantLatitudeLongitude[i].Neighborhood                            =   client.Neighborhood
                    this.getDoublant.getDoublantLatitudeLongitude[i].Landmark                                =   client.Landmark

                    this.getDoublant.getDoublantLatitudeLongitude[i].DistrictNo                              =   client.DistrictNo      
                    this.getDoublant.getDoublantLatitudeLongitude[i].DistrictNameE                           =   client.DistrictNameE  

                    this.getDoublant.getDoublantLatitudeLongitude[i].CityNo                                  =   client.CityNo           
                    this.getDoublant.getDoublantLatitudeLongitude[i].CityNameE                               =   client.CityNameE       

                    this.getDoublant.getDoublantLatitudeLongitude[i].CustomerType                            =   client.CustomerType     

                    this.getDoublant.getDoublantLatitudeLongitude[i].BrandAvailability                       =   client.BrandAvailability       
                    this.getDoublant.getDoublantLatitudeLongitude[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                    this.getDoublant.getDoublantLatitudeLongitude[i].JPlan                                   =   client.JPlan            
                    this.getDoublant.getDoublantLatitudeLongitude[i].Journee                                 =   client.Journee        

                    this.getDoublant.getDoublantLatitudeLongitude[i].Frequency                               =   client.Frequency        
                    this.getDoublant.getDoublantLatitudeLongitude[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                    this.getDoublant.getDoublantLatitudeLongitude[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                    this.getDoublant.getDoublantLatitudeLongitude[i].AvailableBrands                         =   client.AvailableBrands
                    this.getDoublant.getDoublantLatitudeLongitude[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                    this.getDoublant.getDoublantLatitudeLongitude[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                    this.getDoublant.getDoublantLatitudeLongitude[i].status                                  =   client.status            
                    this.getDoublant.getDoublantLatitudeLongitude[i].nonvalidated_details                    =   client.nonvalidated_details        

                    this.getDoublant.getDoublantLatitudeLongitude[i].owner                                   =   client.owner
                    this.getDoublant.getDoublantLatitudeLongitude[i].owner_name                              =   client.owner_name

                    this.getDoublant.getDoublantLatitudeLongitude[i].comment                                 =   client.comment        

                    this.getDoublant.getDoublantLatitudeLongitude[i].facade_image                            =   client.facade_image            
                    this.getDoublant.getDoublantLatitudeLongitude[i].in_store_image                          =   client.in_store_image        
                    this.getDoublant.getDoublantLatitudeLongitude[i].facade_image_original_name              =   client.facade_image_original_name            
                    this.getDoublant.getDoublantLatitudeLongitude[i].in_store_image_original_name            =   client.in_store_image_original_name        
                    this.getDoublant.getDoublantLatitudeLongitude[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                    this.getDoublant.getDoublantLatitudeLongitude[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                    break
                }
            }
        },

        async deleteClientJSON(client) {

            let idx     =   -1

            //      
            idx     =   this.total_clients.findIndex(c => c.id === client.id);
            if (idx !== -1) this.total_clients.splice(idx, 1);

            //
            idx     =   this.getDoublant.getDoublantCustomerCode.findIndex(c => c.id === client.id);
            if (idx !== -1) this.getDoublant.getDoublantCustomerCode.splice(idx, 1);

            //
            idx     =   this.getDoublant.getDoublantCustomerNameE.findIndex(c => c.id === client.id);
            if (idx !== -1) this.getDoublant.getDoublantCustomerNameE.splice(idx, 1);
            

            //
            idx     =   this.getDoublant.getDoublantTel.findIndex(c => c.id === client.id);
            if (idx !== -1) this.getDoublant.getDoublantTel.splice(idx, 1);
            
            //
            idx     =   this.getDoublant.getDoublantLatitudeLongitude.findIndex(c => c.id === client.id);
            if (idx !== -1) this.getDoublant.getDoublantLatitudeLongitude.splice(idx, 1);
        }
    }
}

</script>