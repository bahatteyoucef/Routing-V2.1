<template>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <headerComponent    :title="'List of Users'"            :add_modal="'addUserModal'" :update_modal="'updateUserModal'"   :add_button="'New User'"    
                                        :update_button="'Update User'"      :role="'RTM Manager'"/>

                    <!-- Table -->
                    <table class="table table-bordered clickable_table" id="user_index">
                        <thead>
                            <tr>
                                <th class="col-sm-1">ID</th>
                                <th class="col-sm-3">Nom</th>
                                <th class="col-sm-3">Email</th>
                            </tr>
                        </thead>

                        <thead>
                            <tr class="user_index_filters">
                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="ID"       /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Nom"      /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Email"    /></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="user, index in users" :key="index" @click="selectRow(user)"   role="button"   :id="'user_index_'+user.id">
                                <td>{{index + 1}}</td>
                                <td>{{user.nom}}</td>
                                <td>{{user.email}}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!-- Modal Add  -->
        <modalUserAdd     ref="modalUserAdd">   </modalUserAdd>

        <!-- Modal Update  -->
        <modalUserUpdate  ref="modalUserUpdate"></modalUserUpdate>

    </div>
</template>
 
<script>

import headerComponent      from "../../template/components/headerComponent.vue"

import modalUserAdd       from "./modalUserAdd.vue"
import modalUserUpdate    from "./modalUserUpdate.vue"

export default {

    data() {
        return {
            users                   :   [],
            datatable_user_index    :   null,

            selected_user           :   null,
            display_modal           :   false,

            selected_row            :   null
        }
    },

    components : {
        headerComponent ,
        modalUserAdd,
        modalUserUpdate
    },

    async mounted() {

        await this.setDataTable()
    },

    methods : {

        async setDataTable() {

            try {

                // Destroy DataTable
                if(this.datatable_user_index)  {

                    this.datatable_user_index.destroy()
                }

                // Initialisation 
                this.users   =   [];

                this.$callApi("post",    "/users", null)
                .then(async (res)=> {

                    console.log(res.data)

                    this.users = res.data;

                    this.datatable_user_index   =   await this.$DataTableCreate("user_index")
                })
            }

            catch(e) {

                console.log(e)
            }
        },

        addElement() {

            try {

                this.$refs.modalUserAdd.getData()
            }
            catch(e) {

                console.log(e)
            }
        },

        updateElement() {

            try {

                this.$refs.modalUserUpdate.getData(this.selected_row)
            }
            catch(e) {

                console.log(e)
            }
        },

        //

        selectRow(user) {

            // Get Element
            const row           =   document.getElementById("user_index_"+user.id)

            if(!row.classList.contains("active_row")) {

                // remove Active Class
                const active_row    =   document.getElementsByClassName("active_row")[0]

                if(active_row) {

                    active_row.classList.remove("active_row")
                }

                // add Active Class
                row.classList.add("active_row")

                // Selected Row
                this.selected_row   =   user
            }

            else {

                // remove Active Class
                row.classList.remove("active_row")

                // Selected Row
                this.selected_row   =   null
            }
        },
    }

};
</script>
