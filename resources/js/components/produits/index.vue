<template>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <headerComponent    :title="'List of Produits'"     :add_button_page="'New Produit'" :update_button_page="'Update Produit'"     :prices_button_page="'Prices Page'"/>

                    <!-- HeaderPaging -->
                    <headerRouterComponent  :produits_button="'show'"           :marques_button="'show'"            :categories_button="'show'"     :types_button="'show'" />

                    <!-- Table -->
                    <table class="table table-bordered clickable_table" id="produit_index">
                        <thead>
                            <tr>
                                <th class="col-sm-1">ID</th>
                                <th class="col-sm-3">Name</th>
                                <th class="col-sm-3">Description</th>
                                <th class="col-sm-3">Quantity</th>
                                <th class="col-sm-3">Price</th>
                                <th class="col-sm-3">Class</th>
                                <th class="col-sm-3">Brand</th>
                                <th class="col-sm-3">Category</th>
                                <th class="col-sm-3">Type</th>
                                <th class="col-sm-3">Route Import</th>
                            </tr>
                        </thead>

                        <thead>
                            <tr class="produit_index_filters">
                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="ID"               /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Name"             /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Description"      /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Quantity"         /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Price"            /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Class"            /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Brand"            /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Category"         /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Type"             /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Route Import"     /></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="produit, index in produits" :key="index" @click="selectRow(produit)"   role="button"   :id="'produit_index_'+produit.id">
                                <td>{{index + 1}}</td>
                                <td>{{produit.nom}}</td>
                                <td>{{produit.description}}</td>
                                <td>{{produit.quantite}}</td>
                                <td>{{produit.prix}}</td>
                                <td>{{produit.class}}</td>
                                <td>{{produit.nom_marque}}</td>
                                <td>{{produit.nom_categorie}}</td>
                                <td>{{produit.nom_type}}</td>
                                <td>{{produit.libelle_route_import}}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>
</template>
 
<script>

import headerComponent          from "../../template/components/headerComponent.vue"
import headerRouterComponent    from "../../template/components/headerRouterComponent.vue"

export default {

    data() {
        return {
            produits                   :   [],
            datatable_produit_index    :   null,

            selected_produit           :   null,
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
                if(this.datatable_produit_index)  {

                    this.datatable_produit_index.destroy()
                }

                // Initialisation 
                this.produits   =   [];

                this.$callApi("post",    "/produits", null)
                .then(async (res)=> {

                    console.log(res.data)

                    this.produits = res.data;

                    this.datatable_produit_index   =   await this.$DataTableCreate("produit_index")
                })
            }

            catch(e) {

                console.log(e)
            }
        },

        //

        async addElement() {

            try {

                await this.$refs.modalproduitAdd.getData()
            }
            catch(e) {

                console.log(e)
            }
        },

        async updateElement() {

            try {

                await this.$refs.modalproduitUpdate.getData(this.selected_row)
            }
            catch(e) {

                console.log(e)
            }
        },

        //

        async addElementPage() {

            this.$router.push("/produits/add")
        },

        async updateElementPage() {

            this.$router.push("/produits/"+this.selected_row.id+"/update")
        },

        async pricesElementPage() {

            this.$router.push("/route_import/"+this.selected_row.id_route_import+"/produits/"+this.selected_row.id+"/prices")
        },

        //

        selectRow(produit) {

            // Get Element
            const row           =   document.getElementById("produit_index_"+produit.id)

            if(!row.classList.contains("active_row")) {

                // remove Active Class
                const active_row    =   document.getElementsByClassName("active_row")[0]

                if(active_row) {

                    active_row.classList.remove("active_row")
                }

                // add Active Class
                row.classList.add("active_row")

                // Selected Row
                this.selected_row   =   produit
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
