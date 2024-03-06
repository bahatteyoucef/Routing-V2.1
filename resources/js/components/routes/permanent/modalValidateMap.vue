<template>

    <!-- Modal -->
    <div class="modal fade" id="modalValidateMap" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Validate Uploaded Clients :</h5>
                    <button type="button"   class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="validations_div mt-5">

                        <!--  CustomerCode      -->

                        <div class="row mt-3 mb-3">
                            <div class="col-3">
                                <h5 class="modal-title">Validate CustomerCode : </h5>
                            </div>

                            <div class="col-1 mt-1">
                                <span v-if="clients_doubles_CustomerCode   !=  null" :class="{ 'text-warning': clients_doubles_CustomerCode.length    >   0 , 'text-success': clients_doubles_CustomerCode.length  ==  0   }">{{ clients_doubles_CustomerCode.length }} clients</span>
                            </div>

                            <div class="col-1">
                                <button class="btn btn-primary btn-block"                                                                               @click="validateCustomerCode()">Validate</button>
                            </div>

                            <div class="col-1">
                                <button class="btn btn-secondary btn-block"                                                                             @click="getDoubleCustomerCode()">Refresh</button>
                            </div>
                        </div>

                        <!--                    -->

                        <!--    CustomerNameE   -->

                        <div class="row mt-3 mb-3">
                            <div class="col-3">
                                <h5 class="modal-title">Validate CustomerNameE : </h5>
                            </div>

                            <div class="col-1 mt-1">
                                <span v-if="clients_doubles_CustomerNameE   !=  null" :class="{ 'text-warning': clients_doubles_CustomerNameE.length    >   0 , 'text-success': clients_doubles_CustomerNameE.length  ==  0   }">{{ clients_doubles_CustomerNameE.length }} clients</span>
                            </div>

                            <div class="col-1">
                                <button class="btn btn-primary btn-block"                                                                           @click="validateCustomerNameE()">Validate</button>
                            </div>

                            <div class="col-1">
                                <button class="btn btn-secondary btn-block"                                                                         @click="getDoubleCustomerNameE()">Refresh</button>
                            </div>
                        </div>

                        <!--                    -->

                        <!--    Tel             -->

                        <div class="row mt-3 mb-3">
                            <div class="col-3">
                                <h5 class="modal-title">Validate Tel : </h5>
                            </div>

                            <div class="col-1 mt-1">
                                <span v-if="clients_doubles_Tel   !=  null" :class="{ 'text-warning': clients_doubles_Tel.length    >   0 , 'text-success': clients_doubles_Tel.length  ==  0   }" >{{ clients_doubles_Tel.length }} clients</span>
                            </div>

                            <div class="col-1">
                                <button class="btn btn-primary btn-block"                                                                           @click="validateTel()">Validate</button>
                            </div>

                            <div class="col-1">
                                <button class="btn btn-secondary btn-block"                                                                         @click="getDoubleTel()">Refresh</button>
                            </div>
                        </div>

                        <!--                    -->

                        <!--    GPS             -->

                        <div class="row mt-3 mb-3">
                            <div class="col-3">
                                <h5 class="modal-title">Validate GPS : </h5>
                            </div>

                            <div class="col-1 mt-1">
                                <span v-if="clients_doubles_GPS   !=  null" :class="{ 'text-warning': clients_doubles_GPS.length    >   0 , 'text-success': clients_doubles_GPS.length  ==  0   }">{{ clients_doubles_GPS.length }} clients</span>
                            </div>

                            <div class="col-1">
                                <button class="btn btn-primary btn-block"                                                                               @click="validateGPS()">Validate</button>
                            </div>

                            <div class="col-1">
                                <button class="btn btn-secondary btn-block"                                                                             @click="getDoubleGPS()">Refresh</button>
                            </div>
                        </div>

                        <!--                    -->

                    </div>

                    <!--  -->

                    <modalValidateCustomerCodeMap       ref="modalValidateCustomerCodeMap"                                 :clients_doubles_CustomerCode="clients_doubles_CustomerCode"         :id_route_import="route_import.id_route_import"                 ></modalValidateCustomerCodeMap>
                    <modalValidateCustomerNameEMap      ref="modalValidateCustomerNameEMap"                                :clients_doubles_CustomerNameE="clients_doubles_CustomerNameE"       :id_route_import="route_import.id_route_import"                 ></modalValidateCustomerNameEMap>
                    <modalValidateTelMap                ref="modalValidateTelMap"                                          :clients_doubles_Tel="clients_doubles_Tel"                           :id_route_import="route_import.id_route_import"                 ></modalValidateTelMap>
                    <modalValidateGPSMap                ref="modalValidateGPSMap"                                          :clients_doubles_GPS="clients_doubles_GPS"                           :id_route_import="route_import.id_route_import"                 ></modalValidateGPSMap>

                    <!--  -->

                    <modalClientUpdateMap               ref="modalClientUpdateMap"                                         :modal_source="modal_source"                                         :id_route_import="route_import.id_route_import"                 ></modalClientUpdateMap>
                
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

</template>

<script>

import modalValidateCustomerCodeMap     from "../permanent/validateData/modalValidateCustomerCodeMap.vue"
import modalValidateCustomerNameEMap    from "../permanent/validateData/modalValidateCustomerNameEMap.vue"
import modalValidateTelMap              from "../permanent/validateData/modalValidateTelMap.vue"
import modalValidateGPSMap              from "../permanent/validateData/modalValidateGPSMap.vue"

import modalClientUpdateMap             from "../../clients/permanent/modalClientUpdateMap.vue"

export default {

    data() {

        return {

            route_import    : {

                libelle                     :   ""      ,
                id_route_import             :   null    
            },

            modal_source                    :   ""      ,
            clients                         :   ""      ,

            clients_doubles_CustomerCode    :   null    ,
            clients_doubles_CustomerNameE   :   null    ,
            clients_doubles_Tel             :   null    ,
            clients_doubles_GPS             :   null    
        }
    },

    props : ["id_route_import"],

    components : {

        modalValidateCustomerCodeMap    :   modalValidateCustomerCodeMap    ,
        modalValidateCustomerNameEMap   :   modalValidateCustomerNameEMap   ,
        modalValidateTelMap             :   modalValidateTelMap             ,
        modalValidateGPSMap             :   modalValidateGPSMap             ,

        modalClientUpdateMap            :   modalClientUpdateMap
    },

    async mounted() {

        this.route_import.id_route_import   =   this.id_route_import

        this.clearData("#modalValidateMap")
    },

    methods : {

        //

        async getDoubles() {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(async () => {
                
                this.$showLoadingPage()

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.route_import.id_route_import+"/clients/doubles", null)
                console.log(res)

                if(res.status===200){

                    this.clients_doubles_Tel                =   res.data.getDoublantTel
                    this.clients_doubles_GPS                =   res.data.getDoublantLatitudeLongitude
                    this.clients_doubles_CustomerNameE      =   res.data.getDoublantCustomerNameE
                    this.clients_doubles_CustomerCode       =   res.data.getDoublantCustomerCode

                    // Hide Loading Page
                    this.$hideLoadingPage()
                }
                
                else{

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)
                }            

            }, 55);
        },

        async getDoubleCustomerCode() {

            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.route_import.id_route_import+"/clients/doubles/CustomerCode"    , null)

            if(res.status===200){

                this.clients_doubles_CustomerCode       =   res.data
            }

            this.$hideLoadingPage()
        },

        async getDoubleCustomerNameE() {

            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.route_import.id_route_import+"/clients/doubles/CustomerNameE"   , null)

            if(res.status===200){

                this.clients_doubles_CustomerNameE      =   res.data
            }

            this.$hideLoadingPage()
        },

        async getDoubleTel() {
  
            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.route_import.id_route_import+"/clients/doubles/Tel"             , null)

            if(res.status===200){

                this.clients_doubles_Tel                =   res.data
            }

            this.$hideLoadingPage()
        },

        async getDoubleGPS() {
  
            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.route_import.id_route_import+"/clients/doubles/GPS"             , null)

            if(res.status===200){

                this.clients_doubles_GPS                =   res.data
            }

            this.$hideLoadingPage()
        },

        //

        async validateCustomerCode() {

            // ShowModal
            var modalValidateCustomerCodeMap        =   new Modal(document.getElementById("modalValidateCustomerCodeMap"));
            modalValidateCustomerCodeMap.show();

            await this.$refs.modalValidateCustomerCodeMap.setResumeValidateMap()
        },

        async validateCustomerNameE() {

            // ShowModal
            var modalValidateCustomerNameEMap        =   new Modal(document.getElementById("modalValidateCustomerNameEMap"));
            modalValidateCustomerNameEMap.show();

            await this.$refs.modalValidateCustomerNameEMap.setResumeValidateMap()
        },

        async validateGPS() {

            // ShowModal
            var modalValidateGPSMap        =   new Modal(document.getElementById("modalValidateGPSMap"));
            modalValidateGPSMap.show();

            await this.$refs.modalValidateGPSMap.setResumeValidateMap()
        },

        async validateTel() {

            // ShowModal
            var modalValidateTelMap        =   new Modal(document.getElementById("modalValidateTelMap"));
            modalValidateTelMap.show();

            await this.$refs.modalValidateTelMap.setResumeValidateMap()
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                this.emitter.emit("reSetClientsUpdateMap")
            });
        },

        //

        setModalSourceMap(client, modal_source) {

            this.modal_source   =   modal_source

            this.$refs.modalClientUpdateMap.getData(client)
        }
    }
}

</script>