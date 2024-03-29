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
                        </div>

                        <hr />

                        <form class="row mt-3 mb-3">
                            <div class="col-3">
                                <label for="libelle"        class="form-label">Label</label>
                                <input type="text"          class="form-control"    id="libelle"    v-model="route_import.libelle">
                            </div>

                            <div class="col-3">
                                <label for="file"           class="form-label">File</label>
                                <input  type="file"         class="form-control"        
                                                            id="file"
                                                            @input="getFile($event)"
                                                            accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, 
                                                                    application/vnd.ms-excel">
                            </div>

                            <div class="col-3">
                                <label for="District"       class="form-label">District</label>
                                <select                     class="form-select"     id="District"   v-model="route_import.District">
                                    <option v-for="district in districts" :key="district.DistrictNo" :value="district.DistrictNo">{{district.DistrictNo}}- {{district.DistrictNameE}}</option>
                                </select>
                            </div>

                            <div class="col-2 mt-auto">
                                <button type="button" class="btn btn-primary"   @click="sendDataTempo()"    >Import     </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import * as XLSX from "xlsx";

export default {

    data() {

        return {

            route_import    : {

                libelle                     :   "",
                file                        :   "",
                file_original_name          :   "",
                District                    :   "",

                id_route_import_tempo       :   null,
                file_route_import_tempo     :   null,

                new_upload                  :   true,
                sent_tempo                  :   false
            },

            rdy_send                            :   false   ,

            clients                             :   ""      ,

            districts                           :   []
        }

    },

    async mounted() {

        await this.getComboData()
    },

    methods : {

        getFile(event) {

            try {

                // Show Loading Page
                this.$showLoadingPage()

                this.rdy_send   =   false

                setTimeout(() => {
                    
                    const target    =   event.target

                    if (target && target.files) {

                        this.route_import.file      =   target.files[0]

                        if(typeof target.files[0] != "undefined") {

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

                            this.checkFileFormat()

                            if(this.rdy_send) {

                                this.setLatitudeLongitudeStandard()
                                this.setNecessaryAttributs()
                                // this.setCustomerNo()

                                // 

                                // await this.setDistrictNoCityNo()

                                this.route_import.new_upload    =   true
                                this.route_import.sent_tempo    =   false
                            }

                            // Hide Loading Page
                            this.$hideLoadingPage()
                        };   
                        }  

                        else {

                            // Hide Loading Page
                            this.$hideLoadingPage()
                        }
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

        async getComboData() {

            const res       =   await this.$callApi("post",     "/rtm_willayas",    null)
            console.log(res)

            this.districts  =   res.data
        },

        //

        checkFileFormat() {

            let errors  =   []

            if(this.clients.length  >   0) {

                let columns =   Object.keys(this.clients[0])

                let CustomerCode_existe     =   columns.includes("CustomerCode")
                let CustomerNameE_existe    =   columns.includes("CustomerNameE")
                let CustomerNameA_existe    =   columns.includes("CustomerNameA")
                let Latitude_existe         =   columns.includes("Latitude")
                let Longitude_existe        =   columns.includes("Longitude")
                let Address_existe          =   columns.includes("Address")
                let Neighborhood_existe     =   columns.includes("Neighborhood")
                let Landmark_existe         =   columns.includes("Landmark")

                let DistrictNo_existe       =   columns.includes("DistrictNo")
                let DistrictNameE_existe    =   columns.includes("DistrictNameE")
                let CityNo_existe           =   columns.includes("CityNo")
                let CityNameE_existe        =   columns.includes("CityNameE")
                let Tel_existe              =   columns.includes("Tel")
                let CustomerType_existe     =   columns.includes("CustomerType")

                if(!CustomerCode_existe) {

                    errors.push("Your file doesn't contain the column 'CustomerCode'")
                }

                if(!CustomerNameE_existe) {

                    errors.push("Your file doesn't contain the column 'CustomerNameE'")
                }

                if(!CustomerNameA_existe) {

                    errors.push("Your file doesn't contain the column 'CustomerNameA'")
                }

                if(!Latitude_existe) {

                    errors.push("Your file doesn't contain the column 'Latitude'")
                }

                if(!Longitude_existe) {

                    errors.push("Your file doesn't contain the column 'Longitude'")
                }

                if(!Address_existe) {

                    errors.push("Your file doesn't contain the column 'Address'")
                }

                if(!Neighborhood_existe) {

                    errors.push("Your file doesn't contain the column 'Neighborhood'")
                }

                if(!Landmark_existe) {

                    errors.push("Your file doesn't contain the column 'Landmark'")
                }

                if(!DistrictNo_existe) {

                    errors.push("Your file doesn't contain the column 'DistrictNo'")
                }

                if(!DistrictNameE_existe) {

                    errors.push("Your file doesn't contain the column 'DistrictNameE'")
                }

                if(!CityNo_existe) {

                    errors.push("Your file doesn't contain the column 'CityNo'")
                }

                if(!CityNameE_existe) {

                    errors.push("Your file doesn't contain the column 'CityNameE'")
                }

                if(!Tel_existe) {

                    errors.push("Your file doesn't contain the column 'Tel'")
                }

                if(!CustomerType_existe) {

                    errors.push("Your file doesn't contain the column 'CustomerType'")
                }

                //

                if(errors.length    >   0) {

                    this.$showErrors("Error !", errors)
                }

                else {

                    this.rdy_send   =   true
                }
            }

            else {

                this.$feedbackWarning("Error !", "Your file is empty !")

                this.rdy_send   =   true
            }
        },

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

                if(!this.clients[i].hasOwnProperty("Neighborhood")) {

                    this.clients[i].Neighborhood    =   ""
                }

                if(!this.clients[i].hasOwnProperty("Landmark")) {

                    this.clients[i].Landmark        =   ""
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

            if(this.rdy_send) {

                this.$showLoadingPage()

                let formData = new FormData();

                formData.append("libelle"   ,   this.route_import.libelle)
                formData.append("District"  ,   this.route_import.District)
                formData.append("file"      ,   this.route_import.file)
                formData.append("data"      ,   JSON.stringify(this.clients))

                const res   = await this.$callApi('post' ,   '/route_import_tempo/store'    ,   formData)         
                console.log(res.data)

                if(res.status===200){

                    //

                    this.$goTo('/route/obs/route_import_tempo')

                    //

                    this.$hideLoadingPage()

                    // Send Feedback
                    this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])
                }
                
                else{

                    this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)
                }
            }
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