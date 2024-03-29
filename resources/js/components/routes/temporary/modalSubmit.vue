<template>

    <!-- Modal -->
    <div class="modal fade" id="modalValidate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Submit the Temporary Route :</h5>
                    <button type="button"   class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <!-- Clients -->
                    <div class="mt-3 table-responsive">

                        <div>
                            <h5>List of Clients</h5>
                        </div>

                        <table class="table table-striped datatable_clients_route_import_tempo" id="datatable_clients_route_import_tempo">
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
                                <tr class="datatable_clients_route_import_tempo_filters">

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
                                <tr v-for="client in clients" :key="client.id">
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

                <div class="modal-footer"       style="display: flex; justify-content: space-between;">
                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button"   class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- <button v-if="ready_to_validate" type="button"   class="btn btn-primary"   @click="sendData()">Confirm</button> -->
                        <button type="button"   class="btn btn-primary"   @click="sendData()">Confirm</button>
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

            ready_to_validate                               :   false,

            clients                                         :   null,

            datatable_clients_route_import_tempo            :   null
        }
    },

    props : ["id_route_import_tempo"],

    mounted(){

    },

    methods : {

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("libelle"   ,   this.$parent.route_import.libelle)
            formData.append("District"  ,   this.$parent.route_import.District)

            if(this.$parent.route_import.new_upload ==  true) {

                formData.append("new_upload"                ,   this.$parent.route_import.new_upload)
                formData.append("data"                      ,   JSON.stringify(this.clients))

                formData.append("file"                      ,   this.$parent.route_import.file)
            }

            else {

                formData.append("new_upload"                ,   this.$parent.route_import.new_upload)
                formData.append("data"                      ,   JSON.stringify(this.clients))

                formData.append("id_route_import_tempo"     ,   this.$parent.route_import.id_route_import_tempo)
                formData.append("file_route_import_tempo"   ,   this.$parent.route_import.file_route_import_tempo)
            }

            const res   = await this.$callApi('post'    ,   '/route_import/store'   ,   formData)         
            console.log(res.data)

            if(res.status===200){

                // Send Event
                this.emitter.emit("reSetRouteImport")

                // Close Modal
                this.$hideModal("modalValidate")

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])

                // Add Route Import
                this.$router.push("/route/obs/route_import/"+res.data.route_import.id+"/details")
			}
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}

        },

        //

        setResumeValidate() {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(async () => {
                
                this.$showLoadingPage()

                await this.getDataTempo()
                await this.setDataTableClients()

                const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo/doubles", null)
                console.log(res.data)

                if(res.status===200){

                    this.getDoublantTel                     =   res.data.getDoublantTel
                    this.getDoublantLatitudeLongitude       =   res.data.getDoublantLatitudeLongitude
                    this.getDoublantCustomerNameE           =   res.data.getDoublantCustomerNameE
                    this.getDoublantCustomerCode            =   res.data.getDoublantCustomerCode

                    if((this.getDoublantTel.length  ==  0)&&(this.getDoublantLatitudeLongitude.length  ==  0)&&(this.getDoublantCustomerNameE.length  ==  0)&&(this.getDoublantCustomerCode.length  ==  0)) {

                        this.ready_to_validate  =   true
                    }

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

        //

        async getDataTempo() {

            // Set Data

            const res   = await this.$callApi('post' ,   '/route_import_tempo/last'    ,   null)         

            if(res.status===200){

                if(res.data    ==  "")  {

                }

                else {

                    if(typeof res.data.clients              !=  "undefined") {

                        this.clients                                                =   res.data.clients
                    }
                }
            }
            
            else{

			}
        },

        async setDataTableClients() {

            try {

                // Destroy DataTable
                if(this.datatable_clients_route_import_tempo)  {

                    this.datatable_clients_route_import_tempo.destroy()
                }

                this.datatable_clients_route_import_tempo                    =   await this.$DataTableCreate("datatable_clients_route_import_tempo")
            }

            catch(e) {

                console.log(e)
            }
        }
    }
}
</script>
