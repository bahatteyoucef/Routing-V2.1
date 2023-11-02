<template>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <headerComponent    :title="'List of Types'"     :add_button_page="'New Type'" :update_button_page="'Update Type'"/>

                    <!-- HeaderPaging -->
                    <headerRouterComponent  :produits_button="'show'"           :marques_button="'show'"            :categories_button="'show'"     :types_button="'show'" />

                    <!-- Table -->
                    <table class="table table-bordered clickable_table" id="type_index">
                        <thead>
                            <tr>
                                <th class="col-sm-1">ID</th>
                                <th class="col-sm-3">Name</th>
                                <th class="col-sm-3">Description</th>
                                <th class="col-sm-3">Categorie</th>
                                <th class="col-sm-3">Route Import</th>
                            </tr>
                        </thead>

                        <thead>
                            <tr class="type_index_filters">
                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="ID"               /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Name"             /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Description"      /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Categorie"        /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Route Import"     /></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="type, index in types" :key="index" @click="selectRow(type)"   role="button"   :id="'type_index_'+type.id">
                                <td>{{index + 1}}</td>
                                <td>{{type.nom}}</td>
                                <td>{{type.description}}</td>
                                <td>{{type.libelle_categorie}}</td>
                                <td>{{type.libelle_route_import}}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>
</template>
 
<script>

import headerComponent              from "../../template/components/headerComponent.vue"
import headerRouterComponent        from "../../template/components/headerRouterComponent.vue"

export default {

    data() {
        return {
            types                   :   [],
            datatable_type_index    :   null,

            selected_type           :   null,
            display_modal           :   false,

            selected_row            :   null
        }
    },

    components : {
        headerComponent         ,
        headerRouterComponent
    },

    async mounted() {

        await this.setDataTable()
    },

    methods : {

        async setDataTable() {

            try {

                // Destroy DataTable
                if(this.datatable_type_index)  {

                    this.datatable_type_index.destroy()
                }

                // Initialisation 
                this.types   =   [];

                this.$callApi("post",    "/types", null)
                .then(async (res)=> {

                    console.log(res.data)

                    this.types = res.data;

                    this.datatable_type_index   =   await this.$DataTableCreate("type_index")
                })
            }

            catch(e) {

                console.log(e)
            }
        },

        async addElement() {

            try {

                await this.$refs.modalUserAdd.getData()
            }
            catch(e) {

                console.log(e)
            }
        },

        async updateElement() {

            try {

                await this.$refs.modalUserUpdate.getData(this.selected_row)
            }
            catch(e) {

                console.log(e)
            }
        },

        //

        async addElementPage() {

            this.$router.push("/types/add")
        },

        async updateElementPage() {

            this.$router.push("/types/"+this.selected_row.id+"/update")
        },

        //

        selectRow(type) {

            // Get Element
            const row           =   document.getElementById("type_index_"+type.id)

            if(!row.classList.contains("active_row")) {

                // remove Active Class
                const active_row    =   document.getElementsByClassName("active_row")[0]

                if(active_row) {

                    active_row.classList.remove("active_row")
                }

                // add Active Class
                row.classList.add("active_row")

                // Selected Row
                this.selected_row   =   type
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
