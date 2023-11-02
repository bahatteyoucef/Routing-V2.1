<template>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <headerComponent    :title="'List of Prices of Product '+product.nom+' of route import '+product.libelle_route_import"/>

                    <!-- HeaderPaging -->
                    <headerRouterComponent  :produits_button="'show'"           :marques_button="'show'"            :categories_button="'show'"     :types_button="'show'" />

                    <!-- Table -->
                    <table class="table table-bordered clickable_table" id="price_index">
                        <thead>
                            <tr>
                                <th class="col-sm-1">ID</th>
                                <th class="col-sm-3">Price</th>
                                <th class="col-sm-3">Date</th>
                            </tr>
                        </thead>

                        <thead>
                            <tr class="price_index_filters">
                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="ID"               /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Price"            /></th>
                                <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Date"     /></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="price, index in prices" :key="index" @click="selectRow(price)"   role="button"   :id="'price_index_'+price.id">
                                <td>{{index + 1}}</td>
                                <td>{{price.price}}</td>
                                <td>{{price.created_at}}</td>
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

            product     :   {

                nom                     :   '',
                libelle_route_import    :   ''
            },

            //

            prices                   :   [],
            datatable_price_index    :   null,

            selected_price           :   null,
            display_modal           :   false,

            selected_row            :   null
        }
    },

    components : {
        headerComponent         ,
        headerRouterComponent
    },

    async mounted() {

        await this.getData()
        await this.setDataTable()
    },

    methods : {

        async setDataTable() {

            try {

                // Destroy DataTable
                if(this.datatable_price_index)  {

                    this.datatable_price_index.destroy()
                }

                // Initialisation 
                this.prices   =   [];

                this.$callApi("post",    "/route_import/"+this.$route.params.id_route_import+"/products/"+this.$route.params.id_produit+"/prices", null)
                .then(async (res)=> {

                    console.log(res.data)

                    this.prices = res.data;

                    this.datatable_price_index   =   await this.$DataTableCreate("price_index")
                })
            }

            catch(e) {

                console.log(e)
            }
        },

        async addElement() {

            try {

                await this.$refs.modalpriceAdd.getData()
            }
            catch(e) {

                console.log(e)
            }
        },

        async updateElement() {

            try {

                await this.$refs.modalpriceUpdate.getData(this.selected_row)
            }
            catch(e) {

                console.log(e)
            }
        },

        async addElementPage() {

            this.$router.push("/route_import/"+this.$route.params.id_route_import+"/produits/"+this.$route.params.id_produit+"/prices/add")
        },

        async updateElementPage() {

            this.$router.push("/route_import/"+this.$route.params.id_route_import+"/produits/"+this.$route.params.id_produit+"/prices/"+this.selected_row.id+"/update")
        },

        //

        selectRow(price) {

            // Get Element
            const row           =   document.getElementById("price_index_"+price.id)

            if(!row.classList.contains("active_row")) {

                // remove Active Class
                const active_row    =   document.getElementsByClassName("active_row")[0]

                if(active_row) {

                    active_row.classList.remove("active_row")
                }

                // add Active Class
                row.classList.add("active_row")

                // Selected Row
                this.selected_row   =   price
            }

            else {

                // remove Active Class
                row.classList.remove("active_row")

                // Selected Row
                this.selected_row   =   null
            }
        },

        //

        async getData() {

            await this.getProduitData()
        },

        async getProduitData() {

            const res                   =   await this.$callApi("post"  ,   "/produits/"+this.$route.params.id_produit+"/show"    ,   null)

            this.product.nom                    =   res.data.nom                 
            this.product.libelle_route_import   =   res.data.libelle_route_import
        }
    }
};

</script>
