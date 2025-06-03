<template>

    <!-- Modal -->
    <div class="modal fade" id="ModalValidateClients" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Validate Data : </h5>
                    <button type="button"   class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <!-- DOUBLE PAR -->
                    <div class="mt-3 table-responsive">

                        <div>
                            <h5>Double Clients</h5>
                        </div>

                        <div id="validation_clients_container" class="table-container mt-5">
                            <table class="table table-hover table-bordered validation_clients mt-0 mb-0 w-100" id="validation_clients">
                                <thead>
                                    <tr>
                                        <th role="button">#</th>
                                        <th v-for="validation_client_column in validation_clients_columns" :key="validation_client_column" role="button">{{ validation_client_column.title }}</th>
                                    </tr>

                                    <tr id="validation_clients_filters" class="validation_clients_filters">
                                        <th></th>

                                        <th v-for="validation_client_column in validation_clients_columns" :key="validation_client_column">
                                            <div class="filter_group" :data-column="validation_client_column.title">
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
                                                    :placeholder="validation_client_column.title"
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

            </div>
        </div>

    </div>

</template>

<script>

import DatatableHelper              from    "@/services/DatatableHelper"

export default {

    data() {
        return {

            selected_row                    :   null    ,

            validation_clients_data         :   []      ,
            validation_clients_columns      :   [
                                                    { data: "id"                    , title: "Id"                   },
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

            datatable_validation_clients            :   null    ,
            datatable_validation_clients_instance   :   null    ,

            //

            mode                                    :   ''
        }
    },

    mounted(){

        //
        this.datatable_validation_clients_instance    =   new DatatableHelper()

        //
        this.emitter.on("updateDoubleClients", async (client)    =>  {

            await this.deleteDoubleClient(client)
        })

        this.emitter.on("deleteDoubleClients", async (client)    =>  {

            await this.deleteDoubleClient(client)
        })
    },

    unmounted() {

        this.emitter.off('updateDoubleClients')
        this.emitter.off('deleteDoubleClients')
    },

    methods : {

        async setResumeValidate(selected_double_clients, mode) {

            this.validation_clients_data    =   selected_double_clients
            this.mode                       =   mode

            await this.setDataTableDoubleClients()
        },

        async setDataTableDoubleClients() {

            // Create DataTable
            this.datatable_validation_clients   =   this.datatable_validation_clients.$DataTableCreate("validation_clients", this.validation_clients_data, this.validation_clients_columns, null, null, this.updateElement, null, null, "Validation Clients")   
        },

        //

        updateElement(client_tempo) {

            console.log(client_tempo)

            // ShowModal
            var updateModal     =   new Modal(document.getElementById("ModalClientUpdate"));
            updateModal.show();

            // Send DATA To Modal
            this.$parent.setModalSource(client_tempo, "CustomerCode")
        },

        //

        async deleteDoubleClient(client) {

            for (let i = 0; i < this.validation_clients_data.length; i++) {

                if(this.validation_clients_data[i].id    ==  client.id) {

                    this.validation_clients_data.splice(i, 1)
                }                
            }

            //

            await this.setDataTableDoubleClients()
        }
    }
}

</script>