<template>

    <!-- DOUBLE PAR CustomerCode -->
    <div class="mt-3 table-responsive h-100">
        <div>
            <h4 class="fw-bold"><u>Validation : <span class="text_primary">{{ validation_type }}</span></u></h4>
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

</template>

<script>

import DatatableHelper              from    "@/services/DatatableHelper"

import emitter                  from    "@/utils/emitter"

export default {

    data() {
        return {

            validation_clients_columns              :   [
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
                                                                { data: "comment"                           , title: "comment"                          },
                                                                { data: "BrandAvailability"                 , title: "BrandAvailability"                },
                                                                { data: "BrandSourcePurchase"               , title: "BrandSourcePurchase"              },
                                                                { data: "start_adding_time"                 , title: "start_adding_time"                },
                                                                { data: "adding_duration"                   , title: "adding_duration"                  },
                                                                { data: "created_at"                        , title: "created_at"                       },
                                                                { data: "status"                            , title: "status"                           },
                                                        ],

            //

            datatable_validation_clients            :   null    ,
            datatable_validation_clients_instance   :   null    ,

            //

            selected_row                            :   null    ,
            selected_row_id                         :   null
        }
    },

    props : ["validation_type", "validation_clients", "total_clients"],

    mounted(){

        //
        this.datatable_validation_clients_instance     =   new DatatableHelper()
    },

    unmounted() {

        emitter.off("reSetUpdate")
        emitter.off("reSetDelete")
    },

    methods : {

        async setDatatable() {

            this.datatable_validation_clients     =    this.datatable_validation_clients_instance.$DataTableCreate("validation_clients", this.validation_clients, this.validation_clients_columns, this.setDataTable, null, this.updateElement, null, this.selectRow, "Map Validation Clients")
        },

        //

        async updateElement() {

            // ShowModal
            var updateModal     =   new Modal(document.getElementById("ModalClientUpdate"));
            updateModal.show();

            //
            this.$parent.$parent.$refs.ModalClientUpdate.getData(this.selected_row, this.total_clients)
        },

        //

        selectRow(selected_row, selected_row_id) {

            this.selected_row       =   selected_row
            this.selected_row_id    =   selected_row_id
        },
    }
}

</script>