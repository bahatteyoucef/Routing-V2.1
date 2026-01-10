<template>

    <!-- Modal -->
    <div class="modal fade" id="ModalUpdateMap" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-l modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Update Routing : </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3">
                        
                    <form class="row mt-3 mb-3 d-flex justify-content-center">

                        <div class="col-sm-11">
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

                file            :   ""  ,
            },

            rdy_send        :   false   ,

            clients         :   ""
        }
    },

    props : ["id_route_import"],

    methods : {

        async sendData() {

            // Show Loading Page
            await this.$showLoadingPage()

            let formData = new FormData();

            formData.append("data"                      ,   JSON.stringify(this.clients))
            formData.append("file"                      ,   this.route_import.file)

            const res   =   await this.$callApi('post'    ,   '/route-imports/'+this.id_route_import+'/update' ,   formData)         
            console.log(res)

            if(res.status===200){

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                // Hide Loading Page
                await this.$hideLoadingPage()

                // Pass Clients to Map
                this.emitter.emit('reSetClientsUpdateMap'   ,   res.data.clients)

                // Close Modal
                await this.$hideModal("ModalUpdateMap")
			}
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)

                // Hide Loading Page
                await this.$hideLoadingPage()
			}
        },

        //

        async getFile(event) {

            // Show Loading Page
            await this.$showLoadingPage()

            this.rdy_send   =   false

            setTimeout(async () => {
                
                const target    =   event.target

                if (target && target.files) {

                    if(typeof target.files[0]   !=  "undefined") {

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

                            this.checkFileFormatAndValidateData()

                            if(this.rdy_send) {

                                this.setGPSStandard()
                                this.setNecessaryAttributs()

                                // 

                                this.route_import.new_upload    =   true
                                this.route_import.sent_tempo    =   false
                            }

                            // Hide Loading Page
                            await this.$hideLoadingPage()
                        };      
                    }

                    else {

                        // Hide Loading Page
                        await this.$hideLoadingPage()
                    }
                }

                else {

                    // Hide Loading Page
                    await this.$hideLoadingPage()
                }

            }, 55);
        },

        //

        checkFileFormatAndValidateData() {

            let warnings    =   []

            if(this.clients.length  >   0) {

                let columns         =   Object.keys(this.clients[0])

                //
                const requiredCols  =   [
                    // 'id'                        ,
                    'start_adding_time'         ,
                    'adding_duration'           ,
                    'comment'                   ,
                    'NewCustomer'               ,
                    'OpenCustomer'              ,
                    'CustomerCode'              ,
                    'CustomerNameE'             ,
                    'CustomerNameA'             ,
                    'Latitude'                  ,
                    'Longitude'                 ,
                    'Address'                   ,
                    'RvrsGeoAddress'            ,
                    'Neighborhood'              ,
                    'Landmark'                  ,
                    'DistrictNo'                ,
                    'DistrictNameE'             ,
                    'CityNo'                    ,
                    'CityNameE'                 ,
                    'Tel'                       ,
                    'tel_status'                ,
                    'tel_comment'               ,
                    'CustomerType'              ,
                    'JPlan'                     ,
                    'Journee'                   ,
                    'BrandAvailability'         ,
                    'BrandSourcePurchase'       ,
                    'status'                    ,
                    'nonvalidated_details'      ,
                    'created_at'                ,
                    'owner'                     ,
                    'CustomerIdentifier'        ,
                    'AvailableBrands'           ,
                    'Frequency'                 ,
                    'SuperficieMagasin'         ,
                    'NbrAutomaticCheckouts'     ,
                    'comment'
                ];

                // 2. Build a map of “exists” flags if you still need them later
                const exists    =   Object.fromEntries(
                    requiredCols.map(col => [col, columns.includes(col)])
                );

                // 3. Push warnings for anything missing
                requiredCols.forEach(col => {
                    if (!exists[col]) {
                        warnings.push(`Your file doesn't contain the column '${col}'`);
                    }
                });

                //

                if(warnings.length    >   0) {

                    this.$showWarnings("Warning !", warnings)
                    this.rdy_send   =   true
                }

                //

                this.rdy_send   =   this.validateData()
            }

            else {

                this.$feedbackWarning("Warning !", "Your file is empty !")
                this.rdy_send   =   true
            }
        },

        validateData() {

            for (let index = 0; index < this.clients.length; index++) {

                if(!this.$isValidForFileName(this.clients[index].CustomerCode)) {

                    this.$showErrors("Error !", [this.clients[index].CustomerNameE+" CustomerCode contient des caractères interdits"])
                    return false;
                }
            }

            return true;
        },

        //

        setGPSStandard() {

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

            // 1) List all your default columns in one array:
            const defaultCols       =   [
                // 'id'                    ,
                'start_adding_time'     ,
                'adding_duration'       ,
                'NewCustomer'           ,
                'OpenCustomer'          ,
                'CustomerCode'          ,
                'CustomerNameE'         ,
                'CustomerNameA'         ,
                'Address'               ,
                'RvrsGeoAddress'        ,
                'Neighborhood'          ,
                'Landmark'              ,
                'DistrictNo'            ,
                'DistrictNameE'         ,
                'CityNo'                ,
                'CityNameE'             ,
                'Tel'                   ,
                'tel_comment'           ,
                'tel_status'            ,
                'CustomerType'          ,
                'JPlan'                 ,
                'Journee'               ,
                'BrandAvailability'     ,
                'BrandSourcePurchase'   ,
                'status'                ,
                'nonvalidated_details'  ,
                'created_at'            ,
                'owner'                 ,
                'CustomerIdentifier'    ,
                'AvailableBrands'       ,
                'Frequency'             ,
                'SuperficieMagasin'     ,
                'NbrAutomaticCheckouts' ,
                'comment'               ,

                'Latitude'              ,
                'Longitude'             ,
            ];

            // 2) Declare any “special” non‐empty defaults:
            const specialDefaults   =   {
                Latitude                :   0,
                Longitude               :   0
            };

            // 3) Build your master defaults object in one pass:
            const defaults          =   defaultCols.reduce((acc, col) => {
                acc[col]                =   specialDefaults.hasOwnProperty(col) ?   specialDefaults[col]    :   '';
                return acc;
            }, {});

            // 4) Fill in missing props on each client:
            this.clients.forEach(client => {
                for (const [key, def] of Object.entries(defaults)) {
                    if (!client.hasOwnProperty(key)) {
                        client[key] = def;
                    }
                }
            });
        },
    }
}

</script>