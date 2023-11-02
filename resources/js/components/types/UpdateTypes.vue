<template>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <div class="row">
                        <h4>Type Details</h4>
                    </div>

                    <!-- Details -->
                    <form class="mt-5">

                        <div class="mb-3">
                            <label for="nom"                class="form-label">Name : </label>
                            <input type="text"              class="form-control"        id="nom"            v-model="type.nom">
                        </div>

                        <div class="mb-3">
                            <label for="description"        class="form-label">Description : </label>
                            <input type="text"              class="form-control"        id="description"    v-model="type.description">
                        </div>

                        <!-- Route Import -->
                        <div class="mb-3">
                            <label for="id_route_import"    class="form-label">Route Import</label>
                            <select                         class="form-select"         id="id_route_import"        v-model="type.id_route_import"   @change="getCategories()">
                                <option v-for="route_import in liste_route_import"      :key="route_import.id"      :value="route_import.id">{{route_import.libelle}}</option>
                            </select>
                        </div>

                        <!-- Categorie -->
                        <div class="mb-3">
                            <label for="id_categorie"       class="form-label">Categories</label>
                            <select                         class="form-select"         id="id_categorie"           v-model="type.id_categorie">
                                <option v-for="categorie in categories"                 :key="categorie.id"         :value="categorie.id">{{categorie.nom}}</option>
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

            type            :   {

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
            await this.getTypeData()

            this.$hideLoadingPage()
        },

        //

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("nom"                       , this.type.nom)
            formData.append("description"               , this.type.description)

            formData.append("id_route_import"           , this.type.id_route_import)
            formData.append("id_categorie"              , this.type.id_categorie)

            const res   =   await this.$callApi('post' ,   '/types/'+this.type.id+'/update'    ,   formData)         
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

        //

        async getTypeData() {

            console.log(this.$route.params.id_type)

            const res                   =   await this.$callApi("post"  ,   "/types/"+this.$route.params.id_type+"/show"    ,   null)
            console.log(res)

            this.type.nom_original           =   res.data.nom

            this.type.id                     =   res.data.id                 
            this.type.nom                    =   res.data.nom                 
            this.type.description            =   res.data.description  

            this.type.id_route_import        =   res.data.id_route_import   

            await this.getCategories()

            this.type.id_categorie           =   res.data.id_categorie   
        },

        //

        async getCategories() {

            this.$showLoadingPage()

            const res               =   await this.$callApi("post",       "/route_import/"+this.type.id_route_import+"/categories"   ,        null)
            console.log(res.data)

            this.categories         =   res.data

            this.$hideLoadingPage()
        }
    }
};
</script>