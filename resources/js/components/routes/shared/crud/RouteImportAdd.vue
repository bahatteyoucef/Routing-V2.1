<template>
    <div class="content-wrapper p-3">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-10">
                        <h5 class="modal-title">Import a new Routing</h5>
                    </div>
                </div>

                <hr />

                <form class="row mt-3 mb-3">
                    <div class="col-sm-3">
                        <label for="libelle"        class="form-label">Label</label>
                        <input type="text"          class="form-control"    id="libelle"    v-model="route_import.libelle">
                    </div>

                    <div class="col-sm-3">
                        <label for="file"           class="form-label">File</label>
                        <input  type="file"         class="form-control"        
                                                    id="file"
                                                    @input="getFile($event)"
                                                    accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, 
                                                            application/vnd.ms-excel">
                    </div>

                    <div class="col-sm-3">
                        <label for="Districts"      class="form-label">Districts</label>

                        <!-- Journey Plan   -->
                        <Multiselect
                            v-model             =   "route_import.districts"
                            :options            =   "districts"
                            mode                =   "tags"
                            placeholder         =   "Select Districts"

                            :close-on-select    =   "false"
                            :searchable         =   "true"
                            :create-option      =   "false"
                        />
                        <!--                -->
                    </div>

                    <div class="col-sm-2 mt-auto">
                        <button type="button" class="btn btn-primary"   @click="sendData()">Import</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>

import * as XLSX    from "xlsx";

import Multiselect  from "@vueform/multiselect"

export default {

    data() {

        return {

            route_import    : {

                libelle                     :   "",
                file                        :   "",
                file_original_name          :   "",
                districts                   :   [],

                id_route_import_tempo       :   null,
                file_route_import_tempo     :   null,

                new_upload                  :   true,
                sent_tempo                  :   false
            },

            rdy_send                            :   false   ,

            clients                             :   ""      ,

            districts_full                      :   []      ,
            districts                           :   []
        }
    },

    components : {
        Multiselect
    },

    async mounted() {
        await this.getComboData()
    },

    methods : {

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

        async getComboData() {

            const res           =   await this.$callApi("post",     "/rtm-willayas",    null)

            this.districts_full =   res.data.willayas

            for (let index = 0; index < this.districts_full.length; index++) {

                this.districts.push({ value : this.districts_full[index].DistrictNo, label : this.districts_full[index].DistrictNameE   +   " ("+ this.districts_full[index].DistrictNo +")" })      
            }
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

        //

        async sendData() {

            if(this.rdy_send) {

                await this.$showLoadingPage()

                let formData = new FormData();

                formData.append("libelle"   ,   this.route_import.libelle)
                formData.append("districts" ,   JSON.stringify(this.route_import.districts))
                formData.append("data"      ,   JSON.stringify(this.clients))

                // formData.append("file"      ,   this.route_import.file)

                const res   = await this.$callApi('post' ,   '/route-imports-tempo/store'    ,   formData)         

                if(res.status===200){

                    //

                    this.$goTo('/route-imports-tempo/last-imported')

                    //

                    await this.$hideLoadingPage()

                    // Send Feedback
                    this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])
                }
                
                else{

                    await this.$hideLoadingPage()

                    // Send Errors
                    this.$showWarnings("Error !", res.data.errors)
                }
            }
        },
    }
}

</script>