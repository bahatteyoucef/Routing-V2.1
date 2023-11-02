<template>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <div class="row">
                        <h4>Categorie Details</h4>
                    </div>

                    <!-- Details -->
                    <form class="mt-5">

                        <div class="mb-3">
                            <label for="nom"                class="form-label">Name : </label>
                            <input type="text"              class="form-control"        id="nom"            v-model="categorie.nom">
                        </div>

                        <div class="mb-3">
                            <label for="description"        class="form-label">Description : </label>
                            <input type="text"              class="form-control"        id="description"    v-model="categorie.description">
                        </div>

                        <!-- Route Import -->
                        <div class="mb-3">
                            <label for="id_route_import"    class="form-label">Route Import</label>
                            <select                         class="form-select"         id="id_route_import"        v-model="categorie.id_route_import">
                                <option v-for="route_import in liste_route_import"      :key="route_import.id"      :value="route_import.id">{{route_import.libelle}}</option>
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

            categorie            :   {

                nom                     :   '',
                description             :   '',
                quantite                :   '',
                prix                    :   '',

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

            this.$showLoadingPage()

            await this.getListeRouteImport()  
            await this.getCategorieData()

            this.$hideLoadingPage()
        },

        //

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("nom"                       , this.categorie.nom)
            formData.append("description"               , this.categorie.description)
            formData.append("id_route_import"           , this.categorie.id_route_import)

            const res   =   await this.$callApi('post' ,   '/categories/'+this.categorie.id+'/update'    ,   formData)         
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

            const res               =   await this.$callApi("post",       "/route_import/"+this.categorie.id_route_import+"/categories"   ,        null)
            console.log(res.data)

            this.categories         =   res.data

            this.$hideLoadingPage()
        },

        async getTypes() {

            this.$showLoadingPage()

            const res               =   await this.$callApi("post",       "/categories/"+this.categorie.id_categorie+"/types"             ,        null)
            console.log(res.data)

            this.types              =   res.data

            this.$hideLoadingPage()
        },

        //

        async getCategorieData() {

            const res                   =   await this.$callApi("post"  ,   "/categories/"+this.$route.params.id_categorie+"/show"    ,   null)
            console.log(res)

            this.categorie.nom_original           =   res.data.nom

            this.categorie.id                     =   res.data.id                 
            this.categorie.nom                    =   res.data.nom                 
            this.categorie.description            =   res.data.description  

            this.categorie.id_route_import        =   res.data.id_route_import   
        }
    }
};
</script>