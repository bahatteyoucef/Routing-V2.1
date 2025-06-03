<template>

    <!-- Modal -->
    <div class="modal fade" id="ModalSubmit" tabindex="-1" aria-hidden="true">
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

                        <div id="submit_clients_container" class="table-container mt-5">
                            <table class="table table-hover table-bordered submit_clients mt-0 mb-0 w-100" id="submit_clients">
                                <thead>
                                    <tr>
                                        <th role="button">#</th>
                                        <th v-for="submit_client_column in submit_clients_columns" :key="submit_client_column" role="button">{{ submit_client_column.title }}</th>
                                    </tr>

                                    <tr id="submit_clients_filters" class="submit_clients_filters">
                                        <th></th>

                                        <th v-for="submit_client_column in submit_clients_columns" :key="submit_client_column">
                                            <div class="filter_group" :data-column="submit_client_column.title">
                                                <select class="filter_type form-select-sm w-100 mb-2">
                                                    <option value="contains">contains</option>
                                                    <option value="not_contains">not contains</option>
                                                    <option value="exact">exact</option>
                                                    <option value="starts_with">starts with</option>
                                                    <option value="ends_with">ends with</option>
                                                </select>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm filter_input"
                                                    :placeholder="submit_client_column.title"
                                                />
                                            </div>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="modal-footer"       style="display: flex; justify-content: space-between;">
                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button"   class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button"   class="btn btn-primary"   @click="sendData()">Confirm</button>
                    </div>
                </div>

            </div>
        </div>

    </div>

</template>

<script>

import DatatableHelper  from    "@/services/DatatableHelper"

export default {

    data() {
        return {

            selected_row                                    :   null    ,

            submit_clients_data                             :   []      ,
            submit_clients_columns                          :   [
                                                                    { data: "CustomerCode"          , title: "CustomerCode"         },
                                                                    { data: "CustomerNameE"         , title: "CustomerNameE"        },
                                                                    { data: "CustomerNameA"         , title: "CustomerNameA"        },
                                                                    { data: "DistrictNo"            , title: "DistrictNo"           },
                                                                    { data: "DistrictNameE"         , title: "DistrictNameE"        },
                                                                    { data: "CityNo"                , title: "CityNo"               },
                                                                    { data: "CityNameE"             , title: "CityNameE"            },
                                                                    // { data: "CustomerBarCode_image" , title: "CustomerBarCode Image"},
                                                                    // { data: "in_store_image"        , title: "In Store Image"       },
                                                                    // { data: "facade_image"          , title: "Facade Image"         },
                                                                    { data: "Address"               , title: "Address"              },
                                                                    { data: "Neighborhood"          , title: "Neighborhood"         },
                                                                    { data: "Landmark"              , title: "Landmark"             },
                                                                    { data: "Latitude"              , title: "Latitude"             },
                                                                    { data: "Longitude"             , title: "Longitude"            },
                                                                    { data: "Tel"                   , title: "Tel"                  },
                                                                    { data: "CustomerType"          , title: "CustomerType"         },
                                                                    { data: "JPlan"                 , title: "JPlan"                },
                                                                    { data: "Journee"               , title: "Journee"              },
                                                                    { data: "comment"               , title: "Comment"              },
                                                                    { data: "BrandAvailability"     , title: "BrandAvailability"    },
                                                                    { data: "BrandSourcePurchase"   , title: "BrandSourcePurchase"  },
                                                                    // { data: "start_adding_time"     , title: "Start Adding Time"    },
                                                                    // { data: "adding_duration"       , title: "Adding Duration"      },
                                                                    // { data: "created_at"            , title: "Created At"           },
                                                                    { data: "status"                , title: "Status"               },
                                                                    { data: "owner"                 , title: "Owner"                }
                                                                ],

            datatable_submit_clients                        :   null    ,
            datatable_submit_clients_instance               :   null    ,

            //  //  //  //  //
            //  //  //  //  //
            //  //  //  //  //

            getDoublantTel                                  :   null,
            getDoublantLatitudeLongitude                    :   null,
            getDoublantCustomerNameE                        :   null,
            getDoublantCustomerCode                         :   null,

            ready_to_validate                               :   false
        }
    },

    props : ["id_route_import_tempo"],

    mounted() {

        //
        this.datatable_submit_clients_instance  =   new DatatableHelper()
    },

    methods : {

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            let districts               =   this.$parent.route_import.districts.map(district => district.DistrictNo)

            formData.append("id_route_import_tempo" ,   this.id_route_import_tempo)
            formData.append("libelle"               ,   this.$parent.route_import.libelle)
            formData.append("districts"             ,   JSON.stringify(districts))
            formData.append("data"                  ,   JSON.stringify(this.submit_clients_data))
            
            //
            const res   = await this.$callApi('post'    ,   '/route_import/store'   ,   formData)         

            if(res.status===200){

                // Close Modal
                await this.$hideModal("ModalSubmit")

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])

                // 5) Now hide the spinner
                this.$hideLoadingPage();

                // Send Event
                this.emitter.emit("reSetRouteImport")

                // Add Route Import
                this.$router.push("/route/obs/route_import/"+res.data.route_import.id+"/details")
			}
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)

                // Hide Loading Page
                this.$hideLoadingPage()
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

            const res   = await this.$callApi('post' ,   '/route_import_tempo/last'    ,   null)         

            if(res.status===200){

                if(res.data    ==  "")  {

                }

                else {

                    if(typeof res.data.clients  !=  "undefined") {

                        this.submit_clients_data    =   res.data.clients
                    }
                }
            }
        },

        async setDataTableClients() {

            // Create DataTable
            this.datatable_submit_clients     =   this.datatable_submit_clients_instance.$DataTableCreate("submit_clients", this.submit_clients_data, this.submit_clients_columns, this.addElement, this.updateElement, null, "Temporary Clients")      
        }
    }
}

</script>