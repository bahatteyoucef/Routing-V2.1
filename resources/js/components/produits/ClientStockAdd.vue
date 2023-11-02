<template>

    <div>

        <div
            class="row d-sm-flex justify-content-between align-items-start"
        >
            <div class="col-sm-10 mt-3 mb-3">
                <h4 class="ml-1"> Stock Check :</h4>
            </div>

            <div class="mt-3 mb-3">
                <div v-for="product in products" :key="product.id" class="row mt-1">
                    <div class="col-4">
                        {{ product.nom }}
                    </div>

                    <div class="col-4">
                        <input placeholder="Price"      type="text" class="form-control w-100 pr-1" :id="'product_price_'+product.id"/>
                    </div>

                    <div class="col-4">
                        <input placeholder="Quantity"   type="text" class="form-control w-100"      :id="'product_quantity_'+product.id"/>
                    </div>
                </div>
            </div>

            <div        style="display: flex; justify-content: space-between;">
                <div    class="right-buttons"  style="display: flex; margin-left: auto;">
                    <button type="button" class="btn btn-secondary mb-3 mt-3"   @click="$goBack()">Back</button>
                    <button type="button" class="btn btn-primary mb-3 mt-3"     @click="sendData()">Confirm</button>
                </div>
            </div>
        </div>

    </div>

</template>

<script>

export default {

    data() {

        return {

            products        :   [],

            products_result :   []
        }
    },

    async mounted() {

        await this.getData()
    },

    methods : {

        async sendData() {

            this.$showLoadingPage()

            this.getValues()

            let formData = new FormData();

            formData.append("id_route_import"   ,   this.$route.params.id_route_import)
            formData.append("id_client"         ,   this.$route.params.id_client)

            formData.append("products_result"   ,   JSON.stringify(this.products_result))

            const res   =   await this.$callApi("post"  ,   "/stock_check/all/store",   formData)
            console.log(res)
            console.log(this.products_result)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                // 
                this.$goBack()
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
            }      
        },

        getValues() {

            let product_price_result    =   null
            let product_quantity_result =   null

            let product_result          =   { price : "", quantity : ""}

            for (let i = 0; i < this.products.length; i++) {

                // 
                product_result              =   { price : "", quantity : ""}

                product_price_result        =   document.getElementById("product_price_"+this.products[i].id)
                product_quantity_result     =   document.getElementById("product_quantity_"+this.products[i].id)

                product_result.price        =   product_price_result.value
                product_result.quantity     =   product_quantity_result.value

                product_result.id_product   =   this.products[i].id

                this.products_result.push(product_result)
            }
        },

        //

        async getData() {

            this.$showLoadingPage()

            await this.getStockProducts()

            this.$hideLoadingPage()
        },

        async getStockProducts() {

            const res       =   await this.$callApi("post",     "/route_import/"+this.$route.params.id_route_import+"/products/stock",  null)

            this.products   =   res.data
        }
    }
}

</script>
