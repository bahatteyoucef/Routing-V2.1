<template>

    <!-- Modal -->
    <div class="modal fade" id="modalValidateTel" tabindex="-1" aria-hidden="true">
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
                                <tr v-for="client in getDoublantTel" :key="client.id" @click="updateElementTempo(client)" role="button">
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

    props : ["id_route_import_tempo", "clients_doubles_Tel"],

    mounted(){

        this.emitter.on("updateDoublesTel", async (client)    =>  {

            await this.deleteClientDoubleTel(client)
        })

        this.emitter.on("deleteDoublesTel", async (client)    =>  {

            await this.deleteClientDoubleTel(client)
        })
    },

    methods : {

        async setResumeValidate() {

            this.getDoublantTel                     =   this.clients_doubles_Tel

            await this.setDataTableTel()
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

        //

        updateElementTempo(client_tempo) {

            try {

                // ShowModal
                var updateModal     =   new Modal(document.getElementById("modalClientUpdateTempo"));
                updateModal.show();

                // Send DATA To Modal
                this.$parent.setModalSource(client_tempo, "Tel")
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

        //
    }
}
</script>
