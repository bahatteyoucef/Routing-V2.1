<template>

    <div class="m-1">
        
        <!-- Title -->
        <div class="row">
            <div class="col-sm-9 d-flex align-items-center">
                <h4 class="mb-0 ml-2">Data Census Report</h4>
            </div>
        </div>

        <!--  -->
        <!--  -->
        <!--  -->
        <!--  -->
        <!--  -->

        <div id="data_census_report_table_container" class="table-container mt-5">
            <table class="table table-hover table-bordered data_census_report_table mt-0 mb-0 w-100" id="data_census_report_table">
                <thead>
                    <tr>
                        <th role="button">#</th>
                        <th v-for="submit_client_column in data_census_report_table_columns" :key="submit_client_column" role="button">{{ submit_client_column.title }}</th>
                    </tr>

                    <tr id="data_census_report_table_filters" class="data_census_report_table_filters">
                        <th></th>

                        <th v-for="submit_client_column in data_census_report_table_columns" :key="submit_client_column">
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

</template>

<script>

import {mapGetters, mapActions}     from    "vuex"

import DatatableHelper              from    "@/services/DatatableHelper"

export default {

    data() {
        return {

            selected_row                        :   null    ,

            data_census_report_table_columns    :   [
                                                        { data: "id"                    , title: "Id"                   },
                                                        { data: "created_at"            , title: "Created At"           },
                                                        { data: "status"                , title: "Status"               },
                                                        { data: "owner"                 , title: "Owner"                },
                                                        { data: "CustomerIdentifier"    , title: "CustomerIdentifier"   },
                                                        { data: "CustomerCode"          , title: "CustomerCode"         },

                                                        { data: "CustomerNameE"         , title: "CustomerNameE"        },
                                                        { data: "CustomerNameA"         , title: "CustomerNameA"        },

                                                        { data: "DistrictNameE"         , title: "DistrictNameE"        },
                                                        { data: "CityNameE"             , title: "CityNameE"            },
                                                        { data: "Address"               , title: "Address"              },
                                                        { data: "Neighborhood"          , title: "Neighborhood"         },
                                                        { data: "Landmark"              , title: "Landmark"             },

                                                        { data: "Tel"                   , title: "Tel"                  },
                                                        { data: "CustomerType"          , title: "CustomerType"         },

                                                        { data: "JPlan"                 , title: "JPlan"                },
                                                        { data: "Journee"               , title: "Journee"              },
                                                        { data: "Frequency"             , title: "Frequency"            }
                                                    ],

            datatable_data_census_report_table          :   null,
            datatable_data_census_report_table_instance :   null
        }
    },

    props : ["data_census_report_table_data"],

    async mounted() {

        //
        this.datatable_data_census_report_table_instance    =   new DatatableHelper()

        //
        this.setTable()

        //
        await this.$nextTick()

        //
        this.emitter.emit('show_data_census_report_table_content_ready')
    },

    methods : {

        async setTable() {
            this.datatable_data_census_report_table   =   this.datatable_data_census_report_table_instance.$DataTableCreate("data_census_report_table", this.data_census_report_table_data.rows, this.data_census_report_table_columns, null, this.updateElement, null, "Route Import Clients")  
        },

        //

        async updateElement(selected_row) {

            //
            if(this.$isRole("Super Admin")||this.$isRole('BU Manager')||this.$isRole("BackOffice")||this.$isRole('Viewer')) {

                this.selected_row       =   selected_row

                // ShowModal
                var ModalClientUpdate   =   new Modal(document.getElementById("ModalClientUpdate"));
                ModalClientUpdate.show();

                //
                this.$parent.$refs.ModalClientUpdate.getData(this.selected_row, this.data_census_report_table_data.rows)
            }
        },
    }
}

</script>