<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">

                        <div>
                            <h5 class="modal-title">Importer un nouveau routing</h5>
                        </div>

                        <hr />

                        <form class="row mt-3 mb-3">
                            <div class="col-5">
                                <label for="libelle"        class="form-label">Libelle</label>
                                <input type="text"          class="form-control"        id="libelle"        v-model="route_import.libelle">
                            </div>

                            <div class="col-5">
                                <label for="fiile"          class="form-label">File</label>
                                <input  type="file"         class="form-control"        
                                                            id="file"
                                                            @input="getFile($event)"   
                                                            accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, 
                                                                    application/vnd.ms-excel">
                            </div>

                            <div class="col-2 mt-auto">
                                <button type="button" class="btn btn-primary"   @click="sendData()">Valider</button>
                            </div>
                        </form>

                        <div v-if="resume_liste_journey_plan    !=  null" class="mt-5">

                            <div v-for="journey_plan in resume_liste_journey_plan" :key="journey_plan.JPlan" class="mt-5">

                                <div>
                                    <h5 class="modal-title">{{journey_plan.JPlan}} : {{journey_plan.clients.length}}</h5>
                                </div>

                                <table class="table table-striped mt-3">
                                    <tr>
                                        <th>District</th>
                                        <th>Clients</th>
                                        <th>City</th>
                                        <!-- <th>Clients</th> -->
                                    </tr>

                                    <tr v-for="district in journey_plan.districts" :key="district.DistrictNo">
                                        <td :rowspan="district.cites.length">{{district.DistrictNameE}}</td>
                                        <td :rowspan="district.cites.length">{{district.clients.length}}</td>
                                        <td>
                                            <tr v-for="cite in district.cites" :key="cite.CityNo" class="p-0">
                                                <td>{{cite.CityNameE}}</td>
                                                <td>{{cite.clients.length}}</td>
                                            </tr>
                                        </td>
                                        <!-- <td>Clients</td> -->
                                    </tr>
                                </table>
                            </div>

                        </div>

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

                libelle         :   "",
                file            :   "",
                data            :   ""
            },

            clients         :   ""  ,

            //

            resume_liste_journey_plan   :   null

            //
        }
    },

    mounted() {

        console.log(1111)
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

            // Hide Loading Page
            this.$hideLoadingPage()

            if(res.status===200){

                this.$router.push("/route/obs/route_import/"+res.data.route_import.id+"/details")

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])
			}
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}

        },

        getFile(event) {

            try {

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

                        // this.resume_liste_journey_plan  =   this.$getResumeFileRouting(this.clients)

                        // for (const [key, value] of Object.entries(this.resume_liste_journey_plan)) {

                        //     console.log(this.resume_liste_journey_plan[key])
                        // }

                        //
                        
                    };
                              
                }

                else {

                }

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

                    this.clients[i].DistrictNo      =   ""
                }

                if(!this.clients[i].hasOwnProperty("CityNo")) {

                    this.clients[i].CityNo          =   ""
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

                //

                if(!this.clients[i].hasOwnProperty("Frequency")) {

                    this.clients[i].Frequency       =   ""
                }

                if(!this.clients[i].hasOwnProperty("StartWeek")) {

                    this.clients[i].StartWeek       =   ""
                }

                if(!this.clients[i].hasOwnProperty("sat")) {

                    this.clients[i].sat             =   ""
                }

                if(!this.clients[i].hasOwnProperty("sun")) {

                    this.clients[i].sun             =   0
                }

                if(!this.clients[i].hasOwnProperty("mon")) {

                    this.clients[i].mon             =   0
                }

                if(!this.clients[i].hasOwnProperty("tue")) {

                    this.clients[i].tue             =   ""
                }

                if(!this.clients[i].hasOwnProperty("wed")) {

                    this.clients[i].wed             =   ""
                }

                if(!this.clients[i].hasOwnProperty("thu")) {

                    this.clients[i].thu             =   ""
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

        //

    }
}

</script>