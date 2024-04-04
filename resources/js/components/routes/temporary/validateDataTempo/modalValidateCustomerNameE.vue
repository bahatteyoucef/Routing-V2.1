<template>

    <!-- Modal -->
    <div class="modal fade" id="modalValidateCustomerNameE" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Validate Data : </h5>
                    <button type="button"   class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <!-- DOUBLE PAR CustomerNameE -->
                    <div class="mt-3 table-responsive">

                        <div>
                            <h5>Double Clients (CustomerNameE)</h5>
                        </div>

                        <table class="table table-striped datatable_client_double_namee" id="datatable_client_double_namee">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">CustomerCode</th>

                                    <th class="col-sm-2">CustomerNameE</th>
                                    <th class="col-sm-2">CustomerNameA</th>

                                    <th class="col-sm-1">DistrictNo</th>
                                    <th class="col-sm-2">DistrictNameE</th>

                                    <th class="col-sm-1">CityNo</th>
                                    <th class="col-sm-2">CityNameE</th>

                                    <th class="col-sm-2">Address</th>
                                    <th class="col-sm-2">Neighborhood</th>
                                    <th class="col-sm-2">Landmark</th>

                                    <th class="col-sm-2">Latitude</th>
                                    <th class="col-sm-2">Longitude</th>

                                    <th class="col-sm-2">Tel</th>

                                    <th class="col-sm-1">CustomerType</th>

                                    <th class="col-sm-2">JPlan</th>

                                    <th class="col-sm-2">Journee</th>
                                </tr>
                            </thead>

                            <thead>
                                <tr class="datatable_client_double_namee_filters">

                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerCode"     /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameE"    /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameA"    /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="DistrictNo"       /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="DistrictNameE"    /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CityNo"           /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CityNameE"        /></th>

                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Address"          /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Neighborhood"     /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Landmark"         /></th>

                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Latitude"         /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Longitude"        /></th>

                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Tel"              /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerType"     /></th>

                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="JPlan"            /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Journee"          /></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="client in getDoublantCustomerNameE" :key="client.id" @click="updateElementTempo(client)" role="button">
                                    <td>{{client.CustomerCode}}</td>
                                    <td>{{client.CustomerNameE}}</td>
                                    <td>{{client.CustomerNameA}}</td>

                                    <td>{{client.DistrictNo}}</td>
                                    <td>{{client.DistrictNameE}}</td>

                                    <td>{{client.CityNo}}</td>
                                    <td>{{client.CityNameE}}</td>

                                    <td>{{client.Address}}</td>
                                    <td>{{client.Neighborhood}}</td>
                                    <td>{{client.Landmark}}</td>

                                    <td>{{client.Latitude}}</td>
                                    <td>{{client.Longitude}}</td>

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

    props : ["id_route_import_tempo", "clients_doubles_CustomerNameE"],

    mounted(){

        this.emitter.on("updateDoublesCustomerNameE", async (client)    =>  {

            await this.deleteClientDoubleCustomerNameE(client)
        })

        this.emitter.on("deleteDoublesCustomerNameE", async (client)    =>  {

            await this.deleteClientDoubleCustomerNameE(client)
        })
    },

    methods : {

        async setResumeValidate() {

            this.getDoublantCustomerNameE           =   this.clients_doubles_CustomerNameE

            await this.setDataTableNameE()
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

        //

        updateElementTempo(client_tempo) {

            try {

                // ShowModal
                var updateModal     =   new Modal(document.getElementById("modalClientUpdateTempo"));
                updateModal.show();

                // Send DATA To Modal
                this.$parent.setModalSource(client_tempo, "CustomerNameE")
            }
            catch(e) {

                console.log(e)
            }
        },

        //

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

        //

        async deleteClientDoubleCustomerNameE(client) {

            for (let i = 0; i < this.getDoublantCustomerNameE.length; i++) {

                if(this.getDoublantCustomerNameE[i].id    ==  client.id) {

                    this.getDoublantCustomerNameE.splice(i, 1)
                }                
            }

            //

            await this.setDataTableNameE()
        },

        //
    }
}
</script>
