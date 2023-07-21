<template>

    <!-- Modal -->
    <div class="modal fade" id="modalUpdateMap" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Update Routing : </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3">
                        
                    <form class="row mt-3 mb-3 d-flex justify-content-center">

                        <div class="col-11">
                            <label for="file"           class="form-label">File</label>
                            <input  type="file"         class="form-control"        
                                                        id="file"
                                                        @input="getFile($event)"
                                                        accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, 
                                                                application/vnd.ms-excel">
                        </div>

                    </form>

                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"   @click="sendData()">Confirm</button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

export default {

    data() {
        return { 

            route_import    : {

                file            :   "",
            },

            clients         :   ""   
        }
    },

    props : ["id_route_import"],

    mounted() {

    },  

    methods : {

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("data"                      ,   JSON.stringify(this.clients))
            formData.append("file"                      ,   this.route_import.file)

            const res   =   await this.$callApi('post'    ,   '/route_import/'+this.id_route_import+'/update' ,   formData)         
            console.log(res.data)

            if(res.status===200){

                // Pass Clients to Map
                this.emitter.emit('reSetClientsUpdateMap'   ,   res.data.clients)

                // Close Modal
                this.$hideModal("modalUpdateMap")

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])
			}
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}

        },

        //

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

                            this.route_import.new_upload    =   true
                            this.route_import.sent_tempo    =   false

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

    }
}
</script>
