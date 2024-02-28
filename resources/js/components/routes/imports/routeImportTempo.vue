<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-10">
                                <h5 class="modal-title">Validate the Routing</h5>
                            </div>

                            <div class="col-1 text-right">
                                <button class="btn btn-primary btn-block"   data-bs-toggle="modal" :data-bs-target="'#modalValidate'"   @click="validateRouteImport()">Validate</button>
                            </div>

                            <div class="col-1 text-right">
                                <button class="btn btn-primary btn-block"   data-bs-toggle="modal" :data-bs-target="'#modalResume'"     @click="showResume()">Resume</button>
                            </div>
                        </div>

                        <hr />

                        <form class="row mt-3 mb-5">
                            <div class="col-4">
                                <label for="libelle"        class="form-label">Label</label>
                                <input type="text"          class="form-control"        id="libelle"        v-model="route_import.libelle"  disabled="disabled">
                            </div>

                            <div class="col-4">
                                <label for="file"           class="form-label">File</label>
                                <input  type="file"         class="form-control"        
                                                            id="file"
                                                            disabled="disabled"
                                                            accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, 
                                                                    application/vnd.ms-excel">
                            </div>
                        </form>

                        <hr />

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
                                    <button class="btn btn-primary btn-block"   data-bs-toggle="modal" :data-bs-target="'#modalValidateCustomerCode'"   @click="validateCustomerCode()">Validate</button>
                                </div>

                                <div class="col-1">
                                    <button class="btn btn-secondary btn-block"                                                                         @click="getDoubleCustomerCode()">Refresh</button>
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
                                    <button class="btn btn-primary btn-block"   data-bs-toggle="modal" :data-bs-target="'#modalValidateCustomerNameE'"  @click="validateCustomerNameE()">Validate</button>
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
                                    <button class="btn btn-primary btn-block"   data-bs-toggle="modal" :data-bs-target="'#modalValidateTel'"            @click="validateTel()">Validate</button>
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
                                    <button class="btn btn-primary btn-block"   data-bs-toggle="modal" :data-bs-target="'#modalValidateGPS'"            @click="validateGPS()">Validate</button>
                                </div>

                                <div class="col-1">
                                    <button class="btn btn-secondary btn-block"                                                                         @click="getDoubleGPS()">Refresh</button>
                                </div>
                            </div>

                            <!--                    -->

                        </div>

                        <!--  -->

                        <modalResume                    ref="modalResume"                       :key="Date.now()"       :type="'temporary'"                                                 :id_route_import_tempo="route_import.id_route_import_tempo"     ></modalResume>
                        <modalValidate                  ref="modalValidate"                                                                                                                 :id_route_import_tempo="route_import.id_route_import_tempo"     ></modalValidate>

                        <!--  -->

                        <modalValidateCustomerCode      ref="modalValidateCustomerCode"                                 :clients_doubles_CustomerCode="clients_doubles_CustomerCode"        :id_route_import_tempo="route_import.id_route_import_tempo"     ></modalValidateCustomerCode>
                        <modalValidateCustomerNameE     ref="modalValidateCustomerNameE"                                :clients_doubles_CustomerNameE="clients_doubles_CustomerNameE"      :id_route_import_tempo="route_import.id_route_import_tempo"     ></modalValidateCustomerNameE>
                        <modalValidateTel               ref="modalValidateTel"                                          :clients_doubles_Tel="clients_doubles_Tel"                          :id_route_import_tempo="route_import.id_route_import_tempo"     ></modalValidateTel>
                        <modalValidateGPS               ref="modalValidateGPS"                                          :clients_doubles_GPS="clients_doubles_GPS"                          :id_route_import_tempo="route_import.id_route_import_tempo"     ></modalValidateGPS>

                        <!--  -->

                        <modalClientUpdateTempo         ref="modalClientUpdateTempo"                                    :modal_source="modal_source"                                        :id_route_import_tempo="route_import.id_route_import_tempo"     ></modalClientUpdateTempo>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import modalResume                  from "./modalResume.vue"
import modalValidate                from "./modalValidate.vue"

import modalValidateCustomerCode    from "./modalValidateCustomerCode.vue"
import modalValidateCustomerNameE   from "./modalValidateCustomerNameE.vue"
import modalValidateTel             from "./modalValidateTel.vue"
import modalValidateGPS             from "./modalValidateGPS.vue"

import modalClientUpdateTempo       from "../../clients/modalClientUpdateTempo.vue"

import * as XLSX from "xlsx";

export default {

    data() {

        return {

            route_import    : {

                libelle                     :   "",
                file                        :   "",
                file_original_name          :   "",

                id_route_import_tempo       :   null,
                file_route_import_tempo     :   null,

                new_upload                  :   true,
                sent_tempo                  :   false,

                //
            },

            clients                         :   ""      ,

            clients_doubles_CustomerCode    :   null    ,
            clients_doubles_CustomerNameE   :   null    ,
            clients_doubles_Tel             :   null    ,
            clients_doubles_GPS             :   null    ,

            modal_source                    :   ""
        }
    },

    components : {

        modalResume                 :   modalResume                 ,
        modalValidate               :   modalValidate               ,

        modalValidateCustomerCode   :   modalValidateCustomerCode   ,
        modalValidateCustomerNameE  :   modalValidateCustomerNameE  ,
        modalValidateTel            :   modalValidateTel            ,
        modalValidateGPS            :   modalValidateGPS            ,

        modalClientUpdateTempo      :   modalClientUpdateTempo
    },

    async mounted() {

        await this.getDataTempo()
        await this.getDoubles()

        this.emitter.on('reSetClientsDecoupeByJourneeAdd' , (clients)  =>  {

            this.clients    =   clients
        })
    },

    methods : {

        async showResume() {

            await this.$refs.modalResume.getClients()
        },

        async validateRouteImport() {

            await this.$refs.modalValidate.setResumeValidate()
        },

        //

        async getDataTempo() {

            this.$showLoadingPage()

            // Set Data

            const res   = await this.$callApi('post' ,   '/route_import_tempo/last'    ,   null)         

            if(res.status===200){

                if(res.data    ==  "")  {

                }

                else {

                    this.route_import.sent_tempo     =   true

                    if(typeof res.data.clients              !=  "undefined") {

                        this.clients                                                =   res.data.clients
                    }

                    if(typeof res.data.id                   !=  "undefined") {

                        this.route_import.id_route_import_tempo                     =   res.data.id
                    }

                    if(typeof res.data.libelle              !=  "undefined") {

                        this.route_import.libelle                                   =   res.data.libelle
                    }

                    if(typeof res.data.file                 !=  "undefined") {

                        this.route_import.file_route_import_tempo                   =   res.data.file
                        this.route_import.new_upload                                =   false
                    }

                    if(typeof res.data.file_original_name   !=  "undefined") {

                        this.route_import.file_original_name                        =   res.data.file_original_name
                        this.createFile(res.data.file_original_name)
                    }
                }

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

            setTimeout(async () => {
                
                this.$showLoadingPage()

                const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.route_import.id_route_import_tempo+"/clients_tempo/doubles", null)
                console.log(res.data)

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

            const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.route_import.id_route_import_tempo+"/clients_tempo/doubles/CustomerCode"    , null)
            console.log(res.data)

            if(res.status===200){

                this.clients_doubles_CustomerCode       =   res.data
            }

            this.$hideLoadingPage()
        },

        async getDoubleCustomerNameE() {

            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.route_import.id_route_import_tempo+"/clients_tempo/doubles/CustomerNameE"   , null)
            console.log(res.data)

            if(res.status===200){

                this.clients_doubles_CustomerNameE      =   res.data
            }

            this.$hideLoadingPage()
        },

        async getDoubleTel() {
  
            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.route_import.id_route_import_tempo+"/clients_tempo/doubles/Tel"             , null)
            console.log(res.data)

            if(res.status===200){

                this.clients_doubles_Tel                =   res.data
            }

            this.$hideLoadingPage()
        },

        async getDoubleGPS() {
  
            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.route_import.id_route_import_tempo+"/clients_tempo/doubles/GPS"             , null)
            console.log(res.data)

            if(res.status===200){

                this.clients_doubles_GPS                =   res.data
            }

            this.$hideLoadingPage()
        },

        //

        async validateCustomerCode() {

            await this.$refs.modalValidateCustomerCode.setResumeValidate()
        },

        async validateCustomerNameE() {

            await this.$refs.modalValidateCustomerNameE.setResumeValidate()
        },

        async validateGPS() {

            await this.$refs.modalValidateGPS.setResumeValidate()
        },

        async validateTel() {

            await this.$refs.modalValidateTel.setResumeValidate()
        },

        //

        setModalSource(client_tempo, modal_source) {

            this.modal_source   =   modal_source

            this.$refs.modalClientUpdateTempo.getData(client_tempo)
        },

        //

        createFile(original_name) {

            // Create a new workbook
            const workbook = XLSX.utils.book_new();

            // Create a new worksheet
            const worksheet = XLSX.utils.aoa_to_sheet([["Hello", "World"]]);

            // Add the worksheet to the workbook
            XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");

            // Generate the Excel file binary data
            const excelBinaryData = XLSX.write(workbook, { type: "binary", bookType: "xlsx" });

            // Convert the binary data to a Blob
            const fileData = new Blob([this.s2ab(excelBinaryData)], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });

            // Create a new file object
            const file = new File([fileData], original_name, { type: fileData.type });

            // Get the file input element
            const fileInput = document.getElementById("file");

            // Create a new file list
            const fileList = new DataTransfer();

            // Add the file to the file list
            fileList.items.add(file);

            // Set the file list to the file input
            fileInput.files = fileList.files;
        },

        // Utility function to convert a string to ArrayBuffer
        s2ab(s) {

            const buf = new ArrayBuffer(s.length);
            const view = new Uint8Array(buf);

            for (let i = 0; i < s.length; i++) {
                view[i] = s.charCodeAt(i) & 0xFF;
            }

            return buf;
        }
    }
}

</script>