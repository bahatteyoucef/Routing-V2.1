<template>

    <!-- Modal -->
    <div class="modal fade" id="modalResumeValidate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Validate Data : </h5>
                    <button type="button"   class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <!-- DOUBLE PAR TEL -->
                    <div class="mt-3 table-responsive">

                        <div>
                            <h5>Double Clients (Tel)</h5>
                        </div>

                        <table class="table table-striped datatable_client_double_tel" id="datatable_client_double_tel">
                            <thead>
                                <tr>
                                    <th class="col-sm-1">CustomerCode (CustomerCode)</th>
                                    <th class="col-sm-1">CustomerNameE (CustomerNameE)</th>
                                    <th class="col-sm-1">CustomerNameA (CustomerNameA)</th>

                                    <th class="col-sm-2">Latitude (Latitude)</th>
                                    <th class="col-sm-2">Longitude (Longitude)</th>

                                    <th class="col-sm-2">Address (Address)</th>

                                    <th class="col-sm-1">DistrictNo (DistrictNo)</th>
                                    <th class="col-sm-2">DistrictNameE (DistrictNameE)</th>

                                    <th class="col-sm-1">CityNo (CityNo)</th>
                                    <th class="col-sm-2">CityNameE (CityNameE)</th>

                                    <th class="col-sm-2">Phone Number (Tel)</th>

                                    <th class="col-sm-1">CustomerType (CustomerType)</th>

                                    <th class="col-sm-2">JPlan (JPlan)</th>

                                    <th class="col-sm-1">WorkDay (Journee)</th>
                                </tr>
                            </thead>

                            <thead>
                                <tr class="datatable_client_double_tel_filters">

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerCode"     /></th>
                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameE"    /></th>
                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameA"    /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Latitude"         /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Longitude"        /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Address"          /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="DistrictNo"       /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="DistrictNameE"    /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CityNo"           /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CityNameE"        /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Tel"              /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerType"     /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="JPlan"            /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Journee"          /></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="client in getDoublantTel" :key="client.id" @click="updateElementTempo(client)" >
                                    <td>{{client.CustomerCode}}</td>
                                    <td>{{client.CustomerNameE}}</td>
                                    <td>{{client.CustomerNameA}}</td>

                                    <td>{{client.Latitude}}</td>
                                    <td>{{client.Longitude}}</td>

                                    <td>{{client.Address}}</td>

                                    <td>{{client.DistrictNo}}</td>
                                    <td>{{client.DistrictNameE}}</td>

                                    <td>{{client.CityNo}}</td>
                                    <td>{{client.CityNameE}}</td>

                                    <td>{{client.Tel}}</td>

                                    <td>{{client.CustomerType}}</td>

                                    <td>{{client.JPlan}}</td>

                                    <td>{{client.Journee}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- DOUBLE PAR Latitude Longitude -->
                    <div class="mt-3 table-responsive">

                        <div>
                            <h5>Double Clients (Latitude/Longitude)</h5>
                        </div>

                        <table class="table table-striped datatable_client_double_latitude_longitude" id="datatable_client_double_latitude_longitude">
                            <thead>
                                <tr>
                                    <th class="col-sm-1">CustomerCode (CustomerCode)</th>
                                    <th class="col-sm-1">CustomerNameE (CustomerNameE)</th>
                                    <th class="col-sm-1">CustomerNameA (CustomerNameA)</th>

                                    <th class="col-sm-2">Latitude (Latitude)</th>
                                    <th class="col-sm-2">Longitude (Longitude)</th>

                                    <th class="col-sm-2">Address (Address)</th>

                                    <th class="col-sm-1">DistrictNo (DistrictNo)</th>
                                    <th class="col-sm-2">DistrictNameE (DistrictNameE)</th>

                                    <th class="col-sm-1">CityNo (CityNo)</th>
                                    <th class="col-sm-2">CityNameE (CityNameE)</th>

                                    <th class="col-sm-2">Phone Number (Tel)</th>

                                    <th class="col-sm-1">CustomerType (CustomerType)</th>

                                    <th class="col-sm-2">JPlan (JPlan)</th>

                                    <th class="col-sm-1">WorkDay (Journee)</th>
                                </tr>
                            </thead>

                            <thead>
                                <tr class="datatable_client_double_latitude_longitude_filters">

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerCode"     /></th>
                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameE"    /></th>
                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameA"    /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Latitude"         /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Longitude"        /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Address"          /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="DistrictNo"       /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="DistrictNameE"    /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CityNo"           /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CityNameE"        /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Tel"              /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerType"     /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="JPlan"            /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Journee"          /></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="client in getDoublantLatitudeLongitude" :key="client.id" @click="updateElementTempo(client)" >
                                    <td>{{client.CustomerCode}}</td>
                                    <td>{{client.CustomerNameE}}</td>
                                    <td>{{client.CustomerNameA}}</td>

                                    <td>{{client.Latitude}}</td>
                                    <td>{{client.Longitude}}</td>

                                    <td>{{client.Address}}</td>

                                    <td>{{client.DistrictNo}}</td>
                                    <td>{{client.DistrictNameE}}</td>

                                    <td>{{client.CityNo}}</td>
                                    <td>{{client.CityNameE}}</td>

                                    <td>{{client.Tel}}</td>

                                    <td>{{client.CustomerType}}</td>

                                    <td>{{client.JPlan}}</td>

                                    <td>{{client.Journee}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- DOUBLE PAR CustomerNameE -->
                    <div class="mt-3 table-responsive">

                        <div>
                            <h5>Double Clients (CustomerNameE)</h5>
                        </div>

                        <table class="table table-striped datatable_client_double_namee" id="datatable_client_double_namee">
                            <thead>
                                <tr>
                                    <th class="col-sm-1">CustomerCode (CustomerCode)</th>
                                    <th class="col-sm-1">CustomerNameE (CustomerNameE)</th>
                                    <th class="col-sm-1">CustomerNameA (CustomerNameA)</th>

                                    <th class="col-sm-2">Latitude (Latitude)</th>
                                    <th class="col-sm-2">Longitude (Longitude)</th>

                                    <th class="col-sm-2">Address (Address)</th>

                                    <th class="col-sm-1">DistrictNo (DistrictNo)</th>
                                    <th class="col-sm-2">DistrictNameE (DistrictNameE)</th>

                                    <th class="col-sm-1">CityNo (CityNo)</th>
                                    <th class="col-sm-2">CityNameE (CityNameE)</th>

                                    <th class="col-sm-2">Phone Number (Tel)</th>

                                    <th class="col-sm-1">CustomerType (CustomerType)</th>

                                    <th class="col-sm-2">JPlan (JPlan)</th>

                                    <th class="col-sm-1">WorkDay (Journee)</th>
                                </tr>
                            </thead>

                            <thead>
                                <tr class="datatable_client_double_namee_filters">

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerCode"     /></th>
                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameE"    /></th>
                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameA"    /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Latitude"         /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Longitude"        /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Address"          /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="DistrictNo"       /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="DistrictNameE"    /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CityNo"           /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CityNameE"        /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Tel"              /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerType"     /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="JPlan"            /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Journee"          /></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="client in getDoublantCustomerNameE" :key="client.id" @click="updateElementTempo(client)" >
                                    <td>{{client.CustomerCode}}</td>
                                    <td>{{client.CustomerNameE}}</td>
                                    <td>{{client.CustomerNameA}}</td>

                                    <td>{{client.Latitude}}</td>
                                    <td>{{client.Longitude}}</td>

                                    <td>{{client.Address}}</td>

                                    <td>{{client.DistrictNo}}</td>
                                    <td>{{client.DistrictNameE}}</td>

                                    <td>{{client.CityNo}}</td>
                                    <td>{{client.CityNameE}}</td>

                                    <td>{{client.Tel}}</td>

                                    <td>{{client.CustomerType}}</td>

                                    <td>{{client.JPlan}}</td>

                                    <td>{{client.Journee}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- DOUBLE PAR CustomerCode -->
                    <div class="mt-3 table-responsive">

                        <div>
                            <h5>Double Clients (CustomerCode)</h5>
                        </div>

                        <table class="table table-striped datatable_client_double_customercode" id="datatable_client_double_customercode">
                            <thead>
                                <tr>
                                    <th class="col-sm-1">CustomerCode (CustomerCode)</th>
                                    <th class="col-sm-1">CustomerNameE (CustomerNameE)</th>
                                    <th class="col-sm-1">CustomerNameA (CustomerNameA)</th>

                                    <th class="col-sm-2">Latitude (Latitude)</th>
                                    <th class="col-sm-2">Longitude (Longitude)</th>

                                    <th class="col-sm-2">Address (Address)</th>

                                    <th class="col-sm-1">DistrictNo (DistrictNo)</th>
                                    <th class="col-sm-2">DistrictNameE (DistrictNameE)</th>

                                    <th class="col-sm-1">CityNo (CityNo)</th>
                                    <th class="col-sm-2">CityNameE (CityNameE)</th>

                                    <th class="col-sm-2">Phone Number (Tel)</th>

                                    <th class="col-sm-1">CustomerType (CustomerType)</th>

                                    <th class="col-sm-2">JPlan (JPlan)</th>

                                    <th class="col-sm-1">WorkDay (Journee)</th>
                                </tr>
                            </thead>

                            <thead>
                                <tr class="datatable_client_double_customercode_filters">

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerCode"     /></th>
                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameE"    /></th>
                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameA"    /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Latitude"         /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Longitude"        /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Address"          /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="DistrictNo"       /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="DistrictNameE"    /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CityNo"           /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CityNameE"        /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Tel"              /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerType"     /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="JPlan"            /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Journee"          /></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="client in getDoublantCustomerCode" :key="client.id" @click="updateElementTempo(client)" >
                                    <td>{{client.CustomerCode}}</td>
                                    <td>{{client.CustomerNameE}}</td>
                                    <td>{{client.CustomerNameA}}</td>

                                    <td>{{client.Latitude}}</td>
                                    <td>{{client.Longitude}}</td>

                                    <td>{{client.Address}}</td>

                                    <td>{{client.DistrictNo}}</td>
                                    <td>{{client.DistrictNameE}}</td>

                                    <td>{{client.CityNo}}</td>
                                    <td>{{client.CityNameE}}</td>

                                    <td>{{client.Tel}}</td>

                                    <td>{{client.CustomerType}}</td>

                                    <td>{{client.JPlan}}</td>

                                    <td>{{client.Journee}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>

        <!-- Modal Add  -->
        <modalClientUpdateTempo ref="modalClientUpdateTempo" :id_route_import_tempo="id_route_import_tempo">   </modalClientUpdateTempo>

    </div>

</template>

<script>

import modalClientUpdateTempo from "../../clients/modalClientUpdateTempo.vue"

export default {

    data() {
        return {

            getDoublantTel                                  :   null,
            getDoublantLatitudeLongitude                    :   null,
            getDoublantCustomerNameE                        :   null,
            getDoublantCustomerCode                         :   null,

            datatable_client_double_tel                     :   null,
            datatable_client_double_latitude_longitude      :   null,
            datatable_client_double_namee                   :   null,
            datatable_client_double_customercode            :   null,

            clients                                         :   null
        }
    },

    props : ["id_route_import_tempo"],

    mounted(){

    },

    components : {

        modalClientUpdateTempo  :   modalClientUpdateTempo
    },

    methods : {

        setResumeValidate() {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(async () => {
                
                this.$showLoadingPage()

                const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo/doubles", null)
                console.log(res.data)

                if(res.status===200){

                    this.getDoublantTel                     =   res.data.getDoublantTel
                    this.getDoublantLatitudeLongitude       =   res.data.getDoublantLatitudeLongitude
                    this.getDoublantCustomerNameE           =   res.data.getDoublantCustomerNameE
                    this.getDoublantCustomerCode            =   res.data.getDoublantCustomerCode

                    await this.setDataTableTel()
                    await this.setDataTableLatitudeLongitude()
                    await this.setDataTableNameE()
                    await this.setDataTableCustomerCode()

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

        async setDataTableTel() {

            try {

                // Destroy DataTable
                if(this.datatable_client_double_tel)  {

                    this.datatable_client_double_tel.destroy()
                }

                this.datatable_client_double_tel                    =   await this.$DataTableCreate("datatable_client_double_tel")
            }

            catch(e) {

                console.log(e)
            }
        },

        async setDataTableLatitudeLongitude() {

            try {

                // Destroy DataTable
                if(this.datatable_client_double_latitude_longitude)  {

                    this.datatable_client_double_latitude_longitude.destroy()
                }

                this.datatable_client_double_latitude_longitude     =   await this.$DataTableCreate("datatable_client_double_latitude_longitude")
            }

            catch(e) {

                console.log(e)
            }
        },

        async setDataTableNameE() {

            try {

                // Destroy DataTable
                if(this.datatable_client_double_namee)  {

                    this.datatable_client_double_namee.destroy()
                }

                this.datatable_client_double_namee                  =   await this.$DataTableCreate("datatable_client_double_namee")
            }

            catch(e) {

                console.log(e)
            }
        },

        async setDataTableCustomerCode() {

            try {

                // Destroy DataTable
                if(this.datatable_client_double_customercode)  {

                    this.datatable_client_double_customercode.destroy()
                }

                this.datatable_client_double_customercode           =   await this.$DataTableCreate("datatable_client_double_customercode")
            }

            catch(e) {

                console.log(e)
            }
        },

        //

        updateElementTempo(client_tempo) {

            try {

                // ShowModal
                var updateModal     =   new Modal(document.getElementById("modalClientUpdateTempo"));
                updateModal.show();

                // Send DATA To Modal
                this.$refs.modalClientUpdateTempo.getData(client_tempo)
            }
            catch(e) {

                console.log(e)
            }
        },

        //

        async updateClientDoubleTel(client) {

            for (let i = 0; i < this.getDoublantTel.length; i++) {

                if(this.getDoublantTel[i].id    ==  client.id) {

                    this.getDoublantTel[i].CustomerCode     =   client.CustomerCode
                    this.getDoublantTel[i].CustomerNameE    =   client.CustomerNameE
                    this.getDoublantTel[i].CustomerNameA    =   client.CustomerNameA
                    this.getDoublantTel[i].Latitude         =   client.Latitude
                    this.getDoublantTel[i].Longitude        =   client.Longitude
                    this.getDoublantTel[i].Address          =   client.Address
                    this.getDoublantTel[i].DistrictNo       =   client.DistrictNo
                    this.getDoublantTel[i].DistrictNameE    =   client.DistrictNameE
                    this.getDoublantTel[i].CityNo           =   client.CityNo
                    this.getDoublantTel[i].CityNameE        =   client.CityNameE
                    this.getDoublantTel[i].Tel              =   client.Tel
                    this.getDoublantTel[i].CustomerType     =   client.CustomerType
                    this.getDoublantTel[i].JPlan            =   client.JPlan
                    this.getDoublantTel[i].Journee          =   client.Journee
                }                
            }

            //

            await this.setDataTableTel()
        },

        async updateClientDoubleLatitudeLongitude(client) {

            for (let i = 0; i < this.getDoublantLatitudeLongitude.length; i++) {

                if(this.getDoublantLatitudeLongitude[i].id    ==  client.id) {

                    this.getDoublantLatitudeLongitude[i].CustomerCode       =   client.CustomerCode
                    this.getDoublantLatitudeLongitude[i].CustomerNameE      =   client.CustomerNameE
                    this.getDoublantLatitudeLongitude[i].CustomerNameA      =   client.CustomerNameA
                    this.getDoublantLatitudeLongitude[i].Latitude           =   client.Latitude
                    this.getDoublantLatitudeLongitude[i].Longitude          =   client.Longitude
                    this.getDoublantLatitudeLongitude[i].Address            =   client.Address
                    this.getDoublantLatitudeLongitude[i].DistrictNo         =   client.DistrictNo
                    this.getDoublantLatitudeLongitude[i].DistrictNameE      =   client.DistrictNameE
                    this.getDoublantLatitudeLongitude[i].CityNo             =   client.CityNo
                    this.getDoublantLatitudeLongitude[i].CityNameE          =   client.CityNameE
                    this.getDoublantLatitudeLongitude[i].Tel                =   client.Tel
                    this.getDoublantLatitudeLongitude[i].CustomerType       =   client.CustomerType
                    this.getDoublantLatitudeLongitude[i].JPlan              =   client.JPlan
                    this.getDoublantLatitudeLongitude[i].Journee            =   client.Journee
                }                
            }

            //

            await this.setDataTableLatitudeLongitude()
        },

        async updateClientDoubleCustomerNameE(client) {

            for (let i = 0; i < this.getDoublantCustomerNameE.length; i++) {

                if(this.getDoublantCustomerNameE[i].id    ==  client.id) {

                    this.getDoublantCustomerNameE[i].CustomerCode       =   client.CustomerCode
                    this.getDoublantCustomerNameE[i].CustomerNameE      =   client.CustomerNameE
                    this.getDoublantCustomerNameE[i].CustomerNameA      =   client.CustomerNameA
                    this.getDoublantCustomerNameE[i].Latitude           =   client.Latitude
                    this.getDoublantCustomerNameE[i].Longitude          =   client.Longitude
                    this.getDoublantCustomerNameE[i].Address            =   client.Address
                    this.getDoublantCustomerNameE[i].DistrictNo         =   client.DistrictNo
                    this.getDoublantCustomerNameE[i].DistrictNameE      =   client.DistrictNameE
                    this.getDoublantCustomerNameE[i].CityNo             =   client.CityNo
                    this.getDoublantCustomerNameE[i].CityNameE          =   client.CityNameE
                    this.getDoublantCustomerNameE[i].Tel                =   client.Tel
                    this.getDoublantCustomerNameE[i].CustomerType       =   client.CustomerType
                    this.getDoublantCustomerNameE[i].JPlan              =   client.JPlan
                    this.getDoublantCustomerNameE[i].Journee            =   client.Journee
                }                
            }

            //

            await this.setDataTableNameE()
        },

        async updateClientDoubleCustomerCode(client) {

            for (let i = 0; i < this.getDoublantCustomerCode.length; i++) {

                if(this.getDoublantCustomerCode[i].id    ==  client.id) {

                    this.getDoublantCustomerCode[i].CustomerCode        =   client.CustomerCode
                    this.getDoublantCustomerCode[i].CustomerNameE       =   client.CustomerNameE
                    this.getDoublantCustomerCode[i].CustomerNameA       =   client.CustomerNameA
                    this.getDoublantCustomerCode[i].Latitude            =   client.Latitude
                    this.getDoublantCustomerCode[i].Longitude           =   client.Longitude
                    this.getDoublantCustomerCode[i].Address             =   client.Address
                    this.getDoublantCustomerCode[i].DistrictNo          =   client.DistrictNo
                    this.getDoublantCustomerCode[i].DistrictNameE       =   client.DistrictNameE
                    this.getDoublantCustomerCode[i].CityNo              =   client.CityNo
                    this.getDoublantCustomerCode[i].CityNameE           =   client.CityNameE
                    this.getDoublantCustomerCode[i].Tel                 =   client.Tel
                    this.getDoublantCustomerCode[i].CustomerType        =   client.CustomerType
                    this.getDoublantCustomerCode[i].JPlan               =   client.JPlan
                    this.getDoublantCustomerCode[i].Journee             =   client.Journee
                }                
            }

            //

            await this.setDataTableCustomerCode()
        },

        //

        async deleteClientDoubleTel(client) {

            for (let i = 0; i < this.getDoublantTel.length; i++) {

                if(this.getDoublantTel[i].id    ==  client.id) {

                    this.getDoublantTel.splice(i, 1)
                }                
            }

            //

            await this.setDataTableTel()
        },

        async deleteClientDoubleLatitudeLongitude(client) {

            for (let i = 0; i < this.getDoublantLatitudeLongitude.length; i++) {

                if(this.getDoublantLatitudeLongitude[i].id    ==  client.id) {

                    this.getDoublantLatitudeLongitude.splice(i, 1)
                }                
            }

            //

            await this.setDataTableLatitudeLongitude()
        },

        async deleteClientDoubleCustomerNameE(client) {

            for (let i = 0; i < this.getDoublantCustomerNameE.length; i++) {

                if(this.getDoublantCustomerNameE[i].id    ==  client.id) {

                    this.getDoublantCustomerNameE.splice(i, 1)
                }                
            }

            //

            await this.setDataTableNameE()
        },

        async deleteClientDoubleCustomerCode(client) {

            for (let i = 0; i < this.getDoublantCustomerCode.length; i++) {

                if(this.getDoublantCustomerCode[i].id    ==  client.id) {

                    this.getDoublantCustomerCode.splice(i, 1)
                }                
            }

            //

            await this.setDataTableCustomerCode()
        }

        //
    }
}
</script>
