<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-10">
                                <h5 class="modal-title">Import a new Routing</h5>
                            </div>

                            <div class="col-1 text-right">
                                <button class="btn btn-primary btn-block" @click="sendDataTempo()">Send Data</button>
                            </div>

                            <div class="col-1 text-right">
                                <button class="btn btn-primary btn-block" @click="getDataTempo()">Get Data</button>
                            </div>
                        </div>

                        <hr />

                        <form class="row mt-3 mb-3">
                            <div class="col-4">
                                <label for="libelle"        class="form-label">Label</label>
                                <input type="text"          class="form-control"        id="libelle"        v-model="route_import.libelle">
                            </div>

                            <div class="col-4">
                                <label for="fiile"          class="form-label">File</label>
                                <input  type="file"         class="form-control"        
                                                            id="file"
                                                            @input="getFile($event)"   
                                                            accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, 
                                                                    application/vnd.ms-excel">
                            </div>

                            <div class="col-4 mt-auto">
                                <button type="button" class="btn btn-primary"   @click="sendData()"                                                                                                         >Import     </button>
                                <button type="button" class="btn btn-primary"   @click="showResumeValidate()"   data-bs-toggle="modal" :data-bs-target="'#modalResumeValidate'"     v-if="route_import.file">Validate   </button>
                                <button type="button" class="btn btn-primary"   @click="showResume()"           data-bs-toggle="modal" :data-bs-target="'#modalResume'"             v-if="route_import.file">Resume     </button>
                            </div>
                        </form>

                        <!-- <modalResume            ref="modalResume"           :key="clients"                                      :clients="clients"></modalResume> -->
                        <!-- <modalResumeValidate    ref="modalResumeValidate"   :key="clients"      :route_import="route_import"    :clients="clients"></modalResumeValidate> -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import modalResume       from "./modalResume.vue"
import modalResumeValidate  from "./modalResumeValidate.vue"

import * as XLSX from "xlsx";

export default {

    data() {

        return {

            route_import    : {

                libelle             :   "",
                file                :   "",
                file_original_name  :   ""
            },

            clients         :   ""  ,
        }
    },

    components : {

        modalResume      :   modalResume      ,
        modalResumeValidate :   modalResumeValidate
    },

    mounted() {

        this.emitter.on('reSetClientsDecoupeByJournee' , (clients)  =>  {

            this.emitter.off('reSetClientsDecoupeByJournee')
            this.clients    =   clients
        })
    },

    methods : {

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("libelle"   ,   this.route_import.libelle)
            formData.append("file"      ,   this.route_import.file)
            formData.append("data"      ,   JSON.stringify(this.clients))

            const res   = await this.$callApi('post' ,   '/route_import/store'    ,   formData)         
            console.log(res.data)

            if(res.status===200){

                // Send Event
                this.emitter.emit("reSetRouteImport")

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])

                // Add Route Import
                this.$router.push("/route/obs/route_import/"+res.data.route_import.id+"/details")
			}
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },

        getFile(event) {

            try {

                // Show Loading Page
                this.$showLoadingPage()

                setTimeout(() => {
                    
                    const target    =   event.target

                    if (target && target.files) {

                        this.route_import.file      =   target.files[0]

                        let fileReader              =   new FileReader();

                        fileReader.readAsArrayBuffer(this.route_import.file)

                        fileReader.onload = async (e) => {

                            let arrayBuffer                         =   fileReader.result

                            var data                                =   new Uint8Array(arrayBuffer)
                            var arr                                 =   new Array()

                            for (var i = 0; i != data.length; ++i) {
                                
                                arr[i] = String.fromCharCode(data[i])
                            }

                            var bstr                    =   arr.join("")
                            var workbook                =   XLSX.read(bstr, { type: "binary" })

                            var first_sheet_name        =   workbook.SheetNames[0]
                            var worksheet               =   workbook.Sheets[first_sheet_name]

                            this.clients                =   [...XLSX.utils.sheet_to_json(worksheet, { raw: true })]

                            //

                            this.setLatitudeLongitudeStandard()
                            this.setNecessaryAttributs()
                            this.setCustomerNo()

                            // 

                            await this.setDistrictNoCityNo()

                            // Hide Loading Page
                            this.$hideLoadingPage()

                        };             
                    }

                    else {

                        // Hide Loading Page
                        this.$hideLoadingPage()
                    }

                }, 55);

            }catch(error)
            {
   
            }
        },

        showResume() {

            this.$refs.modalResume.setResume(this.clients)
        },

        showResumeValidate() {

            this.$refs.modalResumeValidate.setResumeValidate()
        },

        //

        setLatitudeLongitudeStandard() {

            for (let i = 0; i < this.clients.length; i++) {

                if((this.clients[i].Latitude)&&(this.clients[i].Longitude)) {

                    if((isNaN(this.clients[i].Latitude))||(isNaN(this.clients[i].Longitude))) {

                        this.clients[i].Latitude   =   0
                        this.clients[i].Longitude  =   0
                    }

                    else {

                        // if latitude longitude has ,
                        this.clients[i].Latitude   =   this.clients[i].Latitude.toString().replace(",", ".")
                        this.clients[i].Longitude  =   this.clients[i].Longitude.toString().replace(",", ".")
                    }                    
                }

                else {
                    // if latitude longitude has ,
                    this.clients[i].Latitude   =   0
                    this.clients[i].Longitude  =   0
                }
            }
        },

        setNecessaryAttributs() {

            for (let i = 0; i < this.clients.length; i++) {

                if(!this.clients[i].hasOwnProperty("CustomerCode")) {

                    this.clients[i].CustomerCode    =   ""
                }

                if(!this.clients[i].hasOwnProperty("CustomerNameE")) {

                    this.clients[i].CustomerNameE   =   ""
                }

                if(!this.clients[i].hasOwnProperty("CustomerNameA")) {

                    this.clients[i].CustomerNameA   =   ""
                }

                if(!this.clients[i].hasOwnProperty("Address")) {

                    this.clients[i].Address         =   ""
                }

                if(!this.clients[i].hasOwnProperty("DistrictNo")) {

                    this.clients[i].DistrictNo      =   "UND"
                }

                if(!this.clients[i].hasOwnProperty("CityNo")) {

                    this.clients[i].CityNo          =   "UND"
                }

                if(!this.clients[i].hasOwnProperty("Tel")) {

                    this.clients[i].Tel             =   ""
                }

                if(!this.clients[i].hasOwnProperty("Latitude")) {

                    this.clients[i].Latitude        =   0
                }

                if(!this.clients[i].hasOwnProperty("Longitude")) {

                    this.clients[i].Longitude       =   0
                }

                if(!this.clients[i].hasOwnProperty("CustomerType")) {

                    this.clients[i].CustomerType    =   ""
                }

                if(!this.clients[i].hasOwnProperty("JPlan")) {

                    this.clients[i].JPlan           =   ""
                }

                if(!this.clients[i].hasOwnProperty("Journee")) {

                    this.clients[i].Journee         =   ""
                }
            }
        },

        setCustomerNo() {

            let count   =   1

            for (let i = 0; i < this.clients.length; i++) {

                this.clients[i].CustomerNo  =   count
                count                       =   count   +   1
            }
        },

        async setDistrictNoCityNo() {

            let formData = new FormData();

            formData.append("clients"       ,   JSON.stringify(this.clients))

            const res   = await this.$callApi('post' ,   '/route_import/set_willayas_cites'    ,   formData)         
            console.log(res.data)

            if(res.status===200){

                this.clients    =   res.data
			}
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },

        //

        async sendDataTempo() {

            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("libelle"   ,   this.route_import.libelle)
            formData.append("file"      ,   this.route_import.file)
            formData.append("data"      ,   JSON.stringify(this.clients))

            const res   = await this.$callApi('post' ,   '/route_import_tempo/store'    ,   formData)         
            console.log(res.data)

            if(res.status===200){

                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])
			}
            
            else{

                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },                    

        async getDataTempo() {

            this.$showLoadingPage()

            const res   = await this.$callApi('post' ,   '/route_import_tempo/last'    ,   null)         
            console.log(res.data)

            if(res.status===200){

                this.$hideLoadingPage()

                this.clients                            =   res.data.clients

                // Route Import
                this.route_import.libelle               =   res.data.libelle
                this.route_import.file                  =   res.data.file
                this.route_import.file_original_name    =   res.data.file_original_name
            }
            
            else{

                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        }
    }
}

</script>