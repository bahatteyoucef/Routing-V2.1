<template>

    <!-- Modal -->
    <div class="modal fade" id="modalValidateTelMap" tabindex="-1" aria-hidden="true">
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
                                    <th class="col-sm-1">Index</th>

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

                                    <!--  -->

                                    <th class="col-sm-2">Comment</th>
                                    <th class="col-sm-2">BrandAvailability</th>
                                    <th class="col-sm-2">BrandSourcePurchase</th>
                                    <th class="col-sm-2">Start Adding Time</th>
                                    <th class="col-sm-2">Adding Duration</th>

                                    <!--  -->

                                    <th class="col-sm-2">Created At</th>
                                    <th class="col-sm-2">Status</th>
                                    <th class="col-sm-2">Owner</th>
                                </tr>
                            </thead>

                            <thead>
                                <tr class="datatable_client_double_tel_filters">

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Index"            /></th>

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

                                <!--  -->

                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Comment"              /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="BrandAvailability"    /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="BrandSourcePurchase"  /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Start Adding Time"    /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Adding Duration"      /></th>

                                <!--  -->

                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Created_At"       /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Status"           /></th>
                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Owner"            /></th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <tr v-for="(client, index) in getDoublantTel" :key="client" role="button" @click="updateElementMap(client)">
                                    <td>{{index +   1}}</td>

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

                                    <!--  -->

                                    <td>{{client.comment}}</td>
                                    <td>{{client.BrandAvailability}}</td>
                                    <td>{{client.BrandSourcePurchase}}</td>
                                    <td>{{client.start_adding_time}}</td>
                                    <td>{{client.adding_duration}}</td>

                                    <!--  -->
                                    <td>{{client.created_at}}</td>

                                    <td>
                                        <span v-if="client.status=='nonvalidated'"  href="#" class="badge badge-danger">{{client.status}}</span>
                                        <span v-if="client.status=='pending'"       href="#" class="badge badge-warning">{{client.status}}</span>
                                        <span v-if="client.status=='validated'"     href="#" class="badge badge-success">{{client.status}}</span>
                                    </td>

                                    <td>{{client.owner_name}}</td>
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

    props : ["id_route_import", "clients_doubles_Tel"],

    mounted(){

        this.emitter.on("updateDoublesTelMap", async (client)    =>  {

            await this.deleteClientDoubleTelMap(client)
        })

        this.emitter.on("deleteDoublesTelMap", async (client)    =>  {

            await this.deleteClientDoubleTelMap(client)
        })
    },

    methods : {

        async setResumeValidateMap() {

            this.getDoublantTel                     =   this.clients_doubles_Tel

            await this.setDataTableTelMap()
        },

        async setDataTableTelMap() {

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

        updateElementMap(client) {

            try {

                // ShowModal
                var updateModal     =   new Modal(document.getElementById("modalClientUpdateMap"));
                updateModal.show();

                // Send DATA To Modal
                this.$parent.setModalSourceMap(client, "Tel")
            }
            catch(e) {

                console.log(e)
            }
        },

        //

        async updateClientDoubleTelMap(client) {

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

            await this.setDataTableTelMap()
        },

        //

        async deleteClientDoubleTelMap(client) {

            for (let i = 0; i < this.getDoublantTel.length; i++) {

                if(this.getDoublantTel[i].id    ==  client.id) {

                    this.getDoublantTel.splice(i, 1)
                }                
            }

            //

            await this.setDataTableTelMap()
        },

        //
    }
}
</script>
