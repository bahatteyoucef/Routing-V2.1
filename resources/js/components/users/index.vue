<template>
    <div class="content-wrapper p-3">
        <div class="card">
            <div class="card-body">

                <!-- Header -->
                <h4 class="fw-bold"><u>Users</u></h4>

                <!-- Table -->
                <div id="index_users_container" class="table-container mt-5">
                    <table class="table table-hover table-bordered index_users mt-0 mb-0 w-100" id="index_users">
                        <thead>
                            <tr>
                                <th role="button">#</th>
                                <th v-for="index_user_column in index_users_columns" :key="index_user_column" role="button">{{ index_user_column.title }}</th>
                            </tr>

                            <tr id="index_users_filters" class="index_users_filters">
                                <th></th>

                                <th v-for="index_user_column in index_users_columns" :key="index_user_column">
                                    <div class="filter_group" :data-column="index_user_column.title">
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
                                            :placeholder="index_user_column.title"
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

    <!-- Modal Add  -->
    <ModalUserAdd     ref="ModalUserAdd">   </ModalUserAdd>

    <!-- Modal Update  -->
    <ModalUserUpdate  ref="ModalUserUpdate"></ModalUserUpdate>

</template>
 
<script>

import HeaderComponent      from    "@/template/components/HeaderComponent.vue"

import ModalUserAdd         from    "./ModalUserAdd.vue"
import ModalUserUpdate      from    "./ModalUserUpdate.vue"

import DatatableHelper      from    "@/services/DatatableHelper"

export default {

    data() {
        return {
            selected_row                    :   null    ,

            index_users_data                :   []      ,
            index_users_columns             :   [
                                                    { data: "id"                    , title: "ID"           },
                                                    { data: "username"              , title: "Username"     },
                                                    { data: "first_name"            , title: "First Name"   },
                                                    { data: "last_name"             , title: "Last Name"    },
                                                    { data: "email"                 , title: "Email"        },
                                                    { data: "tel"                   , title: "Tel"          },
                                                    { data: "company"               , title: "Company"      },
                                                    { data: "type_user"             , title: "Type User"    },
                                                    { data: "password_non_hashed"   , title: "Password"     },
                                                ],

            datatable_index_users           :   null,
            datatable_index_users_instance  :   null,

            //

            selected_row                    :   null,
            selected_row_id                 :   null
        }
    },

    components : {
        HeaderComponent ,
        ModalUserAdd,
        ModalUserUpdate
    },

    async mounted() {

        this.datatable_index_users_instance     =   new DatatableHelper()   

        //
        await this.setDataTable()
    },

    methods : {

        async setDataTable() {

            this.$showLoadingPage()

            // Initialisation 
            this.index_users_data   =   [];

            this.$callApi("post",    "/users", null)
            .then(async (res)=> {

                console.log(res)

                this.index_users_data       =   res.data;

                // Create DataTable
                this.datatable_index_users  =   this.datatable_index_users_instance.$DataTableCreate("index_users", this.index_users_data, this.index_users_columns, this.setDataTable, this.addElement, this.updateElement, null, this.selectRow, "Users")      

                //
                this.$hideLoadingPage()
            })
        },

        async addElement() {

            // ShowModal
            var addModal        =   new Modal(document.getElementById("ModalUserAdd"));
            addModal.show();

            await this.$refs.ModalUserAdd.getData()
        },

        async updateElement(selected_row) {

            // ShowModal
            if(selected_row) {
                var updateModal     =   new Modal(document.getElementById("ModalUserUpdate"));
                updateModal.show();

                await this.$refs.ModalUserUpdate.getData(selected_row)
            }
        },

        //

        selectRow(selected_row, selected_row_id) {

            this.selected_row       =   selected_row
            this.selected_row_id    =   selected_row_id
        },
    }
};

</script>
