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
                                                                    { data: "id"                                , title: "Id"                               },
                                                                    { data: "OpenCustomer"                      , title: "OpenCustomer"                     },
                                                                    { data: "CustomerIdentifier"                , title: "CustomerIdentifier"               },
                                                                    { data: "CustomerCode"                      , title: "CustomerCode"                     },
                                                                    { data: "CustomerNameE"                     , title: "CustomerNameE"                    },
                                                                    { data: "CustomerNameA"                     , title: "CustomerNameA"                    },

                                                                    { data: "DistrictNo"                        , title: "DistrictNo"                       },
                                                                    { data: "DistrictNameE"                     , title: "DistrictNameE"                    },

                                                                    { data: "CityNo"                            , title: "CityNo"                           },
                                                                    { data: "CityNameE"                         , title: "CityNameE"                        },

                                                                    { data: "Address"                           , title: "Address"                          },
                                                                    { data: "Neighborhood"                      , title: "Neighborhood"                     },
                                                                    { data: "Landmark"                          , title: "Landmark"                         },

                                                                    { data: "Latitude"                          , title: "Latitude"                         },
                                                                    { data: "Longitude"                         , title: "Longitude"                        },

                                                                    { data: "Tel"                               , title: "Tel"                              },
                                                                    { data: "CustomerType"                      , title: "CustomerType"                     },

                                                                    { data: "JPlan"                             , title: "JPlan"                            },
                                                                    { data: "Journee"                           , title: "Journee"                          },
                                                                    { data: "Frequency"                         , title: "Frequency"                        },

                                                                    { data: "SuperficieMagasin"                 , title: "SuperficieMagasin"                },
                                                                    { data: "NbrAutomaticCheckouts"             , title: "NbrAutomaticCheckouts"            },
                                                                    // { data: "AvailableBrands_string_formatted"  , title: "AvailableBrands_string_formatted" },
                                                                    { data: "comment"                           , title: "comment"                          },
                                                                    { data: "BrandAvailability"                 , title: "BrandAvailability"                },
                                                                    { data: "BrandSourcePurchase"               , title: "BrandSourcePurchase"              },
                                                                    { data: "start_adding_time"                 , title: "start_adding_time"                },
                                                                    { data: "adding_duration"                   , title: "adding_duration"                  },
                                                                    { data: "created_at"                        , title: "created_at"                       },
                                                                    { data: "status"                            , title: "status"                           },
                                                                    // { data: "owner_username"                        , title: "owner_username"                       },
                                                            ],

            datatable_submit_clients                        :   null    ,
            datatable_submit_clients_instance               :   null    ,
        }
    },

    props : ["id_route_import_tempo", "submit_clients"],

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
            
            //
            const res   = await this.$callApi('post'    ,   '/route_import/store'   ,   formData)         

            if(res.status===200){

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])

                // 5) Now hide the spinner
                this.$hideLoadingPage();

                // Send Event
                this.emitter.emit("reSetRouteImport")

                // Close Modal
                await this.$hideModal("ModalSubmit")

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

        async setDatatable() {

            // Create DataTable
            this.datatable_submit_clients   =    this.datatable_submit_clients_instance.$DataTableCreate("submit_clients", this.submit_clients, this.submit_clients_columns, this.setDataTable, null, null, null, () => {}, "Map Clients")
        }
    }
}

</script>