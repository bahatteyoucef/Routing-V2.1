<template>

    <!-- Modal -->
    <div class="modal fade" id="ModalSelfServiceChartClients" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" v-if="self_service_chart_clients_data">Clients</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3 table-responsive">
                    <div id="self_service_chart_clients_container" class="table-container mt-5">
                        <table class="table table-hover table-bordered self_service_chart_clients mt-0 mb-0 w-100" id="self_service_chart_clients">
                            <thead>
                                <tr>
                                    <th role="button">#</th>
                                    <th v-for="self_service_chart_client_column in self_service_chart_clients_columns" :key="self_service_chart_client_column" role="button">{{ self_service_chart_client_column.title }}</th>
                                </tr>

                                <tr id="self_service_chart_clients_filters" class="self_service_chart_clients_filters">
                                    <th></th>

                                    <th v-for="self_service_chart_client_column in self_service_chart_clients_columns" :key="self_service_chart_client_column">
                                        <div class="filter_group" :data-column="self_service_chart_client_column.title">
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
                                                :placeholder="self_service_chart_client_column.title"
                                            />
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="modal-footer">
                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

import DatatableHelper      from    "@/services/DatatableHelper"

export default {

    data() {
        return {

            selected_row                        :   null,

            self_service_chart_clients_data     :   []  ,
            self_service_chart_clients_columns  :   [
                                                        { data: "CustomerCode"          , title: "CustomerCode"     },
                                                        { data: "CustomerNameE"         , title: "CustomerNameE"    },
                                                        { data: "CustomerNameA"         , title: "CustomerNameA"    },
                                                        { data: "DistrictNo"            , title: "DistrictNo"       },
                                                        { data: "DistrictNameE"         , title: "DistrictNameE"    },
                                                        { data: "CityNo"                , title: "CityNo"           },
                                                        { data: "CityNameE"             , title: "CityNameE"        },
                                                        { data: "Address"               , title: "Address"          },
                                                        { data: "Latitude"              , title: "Latitude"         },
                                                        { data: "Longitude"             , title: "Longitude"        },
                                                        { data: "Tel"                   , title: "Tel"              },
                                                        { data: "CustomerType"          , title: "CustomerType"     },
                                                        { data: "JPlan"                 , title: "JPlan"            },
                                                        { data: "Journee"               , title: "Journee"          },
                                                        { data: "Frequency"             , title: "Frequency"        },
                                                        { data: "created_at"            , title: "Created At"       },
                                                        { data: "status"                , title: "Status"           },
                                                        { data: "owner"                 , title: "Owner"            },
                                                    ],

            datatable_self_service_chart_clients            :   null,
            datatable_self_service_chart_clients_instance   :   null,
        }
    },

    mounted() {

        this.datatable_self_service_chart_clients_instance  =   new DatatableHelper()   

        this.clearData("#ModalSelfServiceChartClients")
    },  

    methods : {

        async setDataTable(self_service_chart_clients_data) {

            try {

                // Set Value
                this.self_service_chart_clients_data        =   [...self_service_chart_clients_data]

                //
                if (this.datatable_self_service_chart_clients) {
                    this.datatable_self_service_chart_clients.destroy();   // Destroy existing chart for proper updates
                }

                // Create DataTable
                this.datatable_self_service_chart_clients   =   this.datatable_self_service_chart_clients_instance.$DataTableCreate("self_service_chart_clients", this.self_service_chart_clients_data, this.self_service_chart_clients_columns, null, null, null, null, null, "Self-service chart clients")      
            }

            catch(e) {

                console.log(e)
            }
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                this.self_service_chart_clients_data            =   []  
            });
        },
    }
};

</script>