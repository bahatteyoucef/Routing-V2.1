<template>

    <!-- Modal -->
    <div class="modal fade" id="modalResumeValidate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" v-if="clients.length">Validate Data : </h5>
                    <button type="button"   class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <!-- DOUBLE PAR TEL -->
                    <div class="mt-3 table-responsive">

                        <div>
                            <h5>Clients double (Tel)</h5>
                        </div>

                        <table class="table table-striped datatable_client_double_tel" id="datatable_client_double_tel">
                            <thead>
                                <tr>
                                    <th class="col-sm-1">CustomerCode</th>
                                    <th class="col-sm-1">CustomerNameE</th>
                                    <th class="col-sm-1">CustomerNameA</th>

                                    <th class="col-sm-2">Latitude</th>
                                    <th class="col-sm-2">Longitude</th>

                                    <th class="col-sm-2">Address</th>

                                    <th class="col-sm-1">DistrictNo</th>
                                    <th class="col-sm-2">DistrictNameE</th>

                                    <th class="col-sm-1">CityNo</th>
                                    <th class="col-sm-2">CityNameE</th>

                                    <th class="col-sm-2">Tel</th>

                                    <th class="col-sm-1">CustomerType</th>

                                    <th class="col-sm-2">JPlan</th>

                                    <th class="col-sm-1">Journee</th>
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
                                <tr v-for="client in getDoublantTel" :key="client.CustomerNo">
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
                            <h5>Clients double (Latitude/Longitude)</h5>
                        </div>

                        <table class="table table-striped datatable_client_double_latitude_longitude" id="datatable_client_double_latitude_longitude">
                            <thead>
                                <tr>
                                    <th class="col-sm-1">CustomerCode</th>
                                    <th class="col-sm-1">CustomerNameE</th>
                                    <th class="col-sm-1">CustomerNameA</th>

                                    <th class="col-sm-2">Latitude</th>
                                    <th class="col-sm-2">Longitude</th>

                                    <th class="col-sm-2">Address</th>

                                    <th class="col-sm-1">DistrictNo</th>
                                    <th class="col-sm-2">DistrictNameE</th>

                                    <th class="col-sm-1">CityNo</th>
                                    <th class="col-sm-2">CityNameE</th>

                                    <th class="col-sm-2">Tel</th>

                                    <th class="col-sm-1">CustomerType</th>

                                    <th class="col-sm-2">JPlan</th>

                                    <th class="col-sm-1">Journee</th>
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
                                <tr v-for="client in getDoublantLatitudeLongitude" :key="client.CustomerNo">
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
                            <h5>Clients double (CustomerNameE)</h5>
                        </div>

                        <table class="table table-striped datatable_client_double_namee" id="datatable_client_double_namee">
                            <thead>
                                <tr>
                                    <th class="col-sm-1">CustomerCode</th>
                                    <th class="col-sm-1">CustomerNameE</th>
                                    <th class="col-sm-1">CustomerNameA</th>

                                    <th class="col-sm-2">Latitude</th>
                                    <th class="col-sm-2">Longitude</th>

                                    <th class="col-sm-2">Address</th>

                                    <th class="col-sm-1">DistrictNo</th>
                                    <th class="col-sm-2">DistrictNameE</th>

                                    <th class="col-sm-1">CityNo</th>
                                    <th class="col-sm-2">CityNameE</th>

                                    <th class="col-sm-2">Tel</th>

                                    <th class="col-sm-1">CustomerType</th>

                                    <th class="col-sm-2">JPlan</th>

                                    <th class="col-sm-1">Journee</th>
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
                                <tr v-for="client in getDoublantCustomerNameE" :key="client.CustomerNo">
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
                            <h5>Clients double (CustomerCode)</h5>
                        </div>

                        <table class="table table-striped datatable_client_double_customercode" id="datatable_client_double_customercode">
                            <thead>
                                <tr>
                                    <th class="col-sm-1">CustomerCode</th>
                                    <th class="col-sm-1">CustomerNameE</th>
                                    <th class="col-sm-1">CustomerNameA</th>

                                    <th class="col-sm-2">Latitude</th>
                                    <th class="col-sm-2">Longitude</th>

                                    <th class="col-sm-2">Address</th>

                                    <th class="col-sm-1">DistrictNo</th>
                                    <th class="col-sm-2">DistrictNameE</th>

                                    <th class="col-sm-1">CityNo</th>
                                    <th class="col-sm-2">CityNameE</th>

                                    <th class="col-sm-2">Tel</th>

                                    <th class="col-sm-1">CustomerType</th>

                                    <th class="col-sm-2">JPlan</th>

                                    <th class="col-sm-1">Journee</th>
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
                                <tr v-for="client in getDoublantCustomerCode" :key="client.CustomerNo">
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
    </div>

</template>

<script>

export default {

    data() {
        return {

            getDoublantTel                  :   null,
            getDoublantLatitudeLongitude    :   null,
            getDoublantCustomerNameE        :   null,
            getDoublantCustomerCode         :   null,

            datatable_client_double_tel                     :   null,
            datatable_client_double_latitude_longitude      :   null,
            datatable_client_double_namee                   :   null,
            datatable_client_double_customercode            :   null

        }
    },

    props : ["clients", "route_import"],

    methods : {

        setResumeValidate() {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(async () => {
                
                this.getDoublantTel                  =   this.$getDoublantTel(this.clients)
                this.getDoublantLatitudeLongitude    =   this.$getDoublantLatitudeLongitude(this.clients)
                this.getDoublantCustomerNameE        =   this.$getDoublantCustomerNameE(this.clients)
                this.getDoublantCustomerCode         =   this.$getDoublantCustomerCode(this.clients)

                await this.setDataTableTel()
                await this.setDataTableLatitudeLongitude()
                await this.setDataTableNameE()
                await this.setDataTableCustomerCode()

                // Hide Loading Page
                this.$hideLoadingPage()
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
        }

    }
}
</script>
