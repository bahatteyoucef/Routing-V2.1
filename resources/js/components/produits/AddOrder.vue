<template>

    <div>

        <div
            class="container"
        >
            <div class="col-sm-10 mt-3 mb-3 pl-0">
                <h4 class="ml-0"> Add Order :</h4>
            </div>

            <div class="mt-5 mb-3">

                <!-- Products labels -->
                <div class="row mt-1">
                    <div class="col-2 p-1">
                    </div>

                    <div class="col-3 p-1">
                        <label class="form-label w-100 text-center">Qte</label>
                    </div>

                    <div class="col-3 p-1">
                        <label class="form-label w-100 text-center">Qte Price</label>
                    </div>

                    <div class="col-2 p-1">
                        <label class="form-label w-100 text-center">Price</label>
                    </div>

                    <div class="col-2 p-1">
                        <label class="form-label w-100 text-center">Disp. Qte</label>
                    </div>      
                </div>

                <!-- Products -->
                <div v-for="product in products" :key="product.id" class="row mt-1">
                    <div class="col-2 p-1">
                        <label class="form-label">{{ product.nom }}</label>
                    </div>

                    <div class="col-3 p-1">
                        <input placeholder="Qte"           type="text" class="form-control w-100"      :id="'product_quantity_'+product.id"                 @change="setQuantitePrice(product.id)"/>
                    </div>

                    <div class="col-3 p-1">
                        <input placeholder="Qte Price"     type="text" class="form-control w-100"      :id="'product_quantity_price_'+product.id"                                           disabled/>
                    </div>

                    <div class="col-2 p-1">
                        <input placeholder="Price"          type="text" class="form-control w-100 pr-1" :id="'product_price_'+product.id"                   :value="product.prix"           disabled/>
                    </div>

                    <div class="col-2 p-1">
                        <input placeholder="Disp Qte"       type="text" class="form-control w-100"      :id="'product_disponible_quantity_'+product.id"     :value="product.quantite"       disabled/>
                    </div>      
                </div>
            </div>

            
            <div        style="display: flex; justify-content: space-between;">
                <div    class="right-buttons"  style="display: flex; margin-left: auto;">
                    <label class="form-label">Total Price : {{ total_price }}</label>
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

            orders_result   :   [],

            orders          :   [],

            //

            total_price     :   0
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

            formData.append("orders_result"     ,   JSON.stringify(this.orders_result))

            const res   =   await this.$callApi("post"  ,   "/orders/all/store",   formData)
            console.log(res)
            console.log(this.orders_result)

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

            let product_quantity_result     =   null
            let product_result              =   { quantity : ""}

            this.orders_result              =   []

            for (let i = 0; i < this.products.length; i++) {

                // 
                product_result                  =   { price : "", quantity : ""}

                product_quantity_result         =   document.getElementById("product_quantity_"+this.products[i].id)
                console.log(product_quantity_result.value)

                if(product_quantity_result.value    >=  0) {

                    product_result.quantity         =   product_quantity_result.value
                    product_result.id_product       =   this.products[i].id

                    this.orders_result.push(product_result)
                }
            }
        },

        setQuantitePrice(id) {

            for (let i = 0; i < this.products.length; i++) {

                if(this.products[i].id  ==  id) {

                    let product_quantity            =   document.getElementById("product_quantity_"+this.products[i].id)

                    let product_quantity_price      =   document.getElementById("product_quantity_price_"+this.products[i].id)


                    product_quantity_price.value    =   parseFloat(product_quantity.value) * parseFloat(this.products[i].prix)
                }                
            }

            this.setTotalPrice()
        },
        
        setTotalPrice() {

            this.total_price    =   0

            for (let i = 0; i < this.products.length; i++) {

                let product_quantity_price      =   document.getElementById("product_quantity_price_"+this.products[i].id)

                if(!isNaN(parseFloat(product_quantity_price.value))) {

                    this.total_price                =   this.total_price    +   parseFloat(product_quantity_price.value)
                }
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
