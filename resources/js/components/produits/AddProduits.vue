<template>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <div class="row">
                        <h4>Product Details</h4>
                    </div>

                    <!-- Details -->
                    <form class="mt-5">

                        <div class="mb-3">
                            <label for="nom"                class="form-label">Name : </label>
                            <input type="text"              class="form-control"        id="nom"            v-model="produit.nom">
                        </div>

                        <div class="mb-3">
                            <label for="description"        class="form-label">Description : </label>
                            <input type="text"              class="form-control"        id="description"    v-model="produit.description">
                        </div>

                        <!-- Class -->
                        <div class="mb-3">
                            <label for="id_route_import"    class="form-label">Class : </label>
                            <select                         class="form-select"         id="id_route_import"        v-model="produit.class">
                                <option :value="'stock'">Stock</option>
                                <option :value="'competitor'">Competitor</option>
                            </select>
                        </div>

                        <div v-if="produit.class    ==  'stock'"    class="mb-3">
                            <label for="quantite"           class="form-label">Quantite : </label>
                            <input type="text"              class="form-control"        id="quantite"       v-model="produit.quantite">
                        </div>

                        <div v-if="produit.class    ==  'stock'"    class="mb-3">
                            <label for="prix"               class="form-label">Price : </label>
                            <input type="text"              class="form-control"        id="prix"           v-model="produit.prix">
                        </div>

                        <!-- Route Import -->
                        <div class="mb-3">
                            <label for="id_route_import"    class="form-label">Route Import</label>
                            <select                         class="form-select"         id="id_route_import"        v-model="produit.id_route_import"   @change="getCategories()">
                                <option v-for="route_import in liste_route_import"      :key="route_import.id"      :value="route_import.id">{{route_import.libelle}}</option>
                            </select>
                        </div>

                        <!-- Categorie -->
                        <div class="mb-3">
                            <label for="id_categorie"       class="form-label">Categories</label>
                            <select                         class="form-select"         id="id_categorie"           v-model="produit.id_categorie"      @change="getTypes()">
                                <option v-for="categorie in categories"                 :key="categorie.id"         :value="categorie.id">{{categorie.nom}}</option>
                            </select>
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <label for="id_type"         class="form-label">Types</label>
                            <select                         class="form-select"             id="id_type"             v-model="produit.id_type">
                                <option v-for="type_elem in types"                          :key="type_elem.id"     :value="type_elem.id">{{type_elem.nom}}</option>
                            </select>
                        </div>

                        <!--  -->

                        <div class="container mt-5">
                            <div class="row justify-content-center">
                                <div class="col-sm-3 mt-3">
                                    <button type="button" class="btn btn-secondary w-100"   @click="$goBack()">Close</button>
                                </div>

                                <div class="col-sm-3 mt-3">
                                    <button type="button" class="btn btn-primary w-100"   @click="sendData()">Confirm</button>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

</template>

<script>

export default {

    data() {
        return {

            produit            :   {

                nom                     :   '',
                description             :   '',
                quantite                :   '',
                prix                    :   '',

                class                   :   '',

                id_route_import         :   [],
                id_categorie            :   '',
                id_type                 :   '',
            },

            liste_route_import          :   [],
            categories                  :   [],
            types                       :   []
        }
    },

    async mounted() {

        await this.getData()
    },  

    methods : {

        async getData() {

            await this.getListeRouteImport()  
        },

        //

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("nom"                   , this.produit.nom)
            formData.append("description"           , this.produit.description)
            formData.append("quantite"              , this.produit.quantite)
            formData.append("prix"                  , this.produit.prix)

            formData.append("class"                 , this.produit.class)

            formData.append("id_route_import"       , this.produit.id_route_import)
            formData.append("id_categorie"          , this.produit.id_categorie)
            formData.append("id_type"               , this.produit.id_type)

            const res   =   await this.$callApi('post' ,   '/produits/store'    ,   formData)         
            console.log(res)

            // Hide Loading Page
            this.$hideLoadingPage()

            if(res.status===200){
                
                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])

                // Close Modal
                this.$goBack()
			}
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },

        //

        async getListeRouteImport() {

            this.$showLoadingPage()

            const res               =   await this.$callApi("post",       "/route_import"   ,       null)
            console.log(res.data)

            this.liste_route_import =   res.data

            this.$hideLoadingPage()
        },

        async getCategories() {

            this.$showLoadingPage()

            const res               =   await this.$callApi("post",       "/route_import/"+this.produit.id_route_import+"/categories"   ,        null)
            console.log(res.data)

            this.categories         =   res.data

            this.$hideLoadingPage()
        },

        async getTypes() {

            this.$showLoadingPage()

            console.log(this.produit.id_categorie)

            const res               =   await this.$callApi("post",       "/categories/"+this.produit.id_categorie+"/types"             ,        null)
            console.log(res.data)

            this.types              =   res.data

            this.$hideLoadingPage()
        },
    }
};
</script>