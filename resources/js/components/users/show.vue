<template>
    <div class="content-wrapper p-3">
        <div class="card">
            <div class="card-body">

                <!-- Header -->
                <h3 class="fw-bold"><u>Profile Details</u></h3>

                <!-- Details -->
                <form class="mt-5">

                    <div class="mb-3 mt-3">
                        <div class="row">
                            <label for="nom"                    class="form-label font-weight-bold col-sm-2">Name : </label>
                            <label                              class="form-label col-sm-4">{{ user.nom }}</label>

                            <label for="email"                  class="form-label font-weight-bold col-sm-2">Email : </label>
                            <label                              class="form-label col-sm-4">{{ user.email }}</label>
                        </div>
                    </div>

                    <!--  -->

                    <div class="mb-3 mt-3">
                        <div class="row">
                            <label for="tel"                    class="form-label font-weight-bold col-sm-2">Tel : </label>
                            <label                              class="form-label col-sm-4">{{ user.tel }}</label>

                            <label for="company"                class="form-label font-weight-bold col-sm-2">Company : </label>
                            <label                              class="form-label col-sm-4">{{ user.company }}</label>
                        </div>
                    </div>

                    <!--  -->

                    <div class="mb-3 mt-3">
                        <div class="row">
                            <label for="type_user"              class="form-label font-weight-bold col-sm-2">Type User : </label>
                            <label                              class="form-label col-sm-4">{{ user.type_user }}</label>

                            <label for="max_route_import"       class="form-label font-weight-bold col-sm-2">Max Route Import : </label>
                            <label                              class="form-label col-sm-4">{{ user.max_route_import }}</label>
                        </div>
                    </div>

                    <!--  -->

                    <div class="mb-3 mt-3">
                        <div class="row">
                            <label for="max_route_import"       class="form-label font-weight-bold col-sm-2">Liste Route Import : </label>
                            <label                              class="form-label col-sm-10">{{ user.liste_route_import_string }}</label>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>
 
<script>

export default {

    data() {
        return {
            user            :   {
                nom_original                :   '',

                id                          :   '',
                nom                         :   '',
                email                       :   '',
                tel                         :   '',
                company                     :   '',
                type_user                   :   '',

                max_route_import            :   0 ,

                liste_route_import          :   [],
                liste_route_import_string   :   ""
            },

            liste_route_import          :   []
        }
    },

    async mounted() {
        await this.getData()
    },  

    methods : {

        async getData(user) {

            // Show Loading Page
            this.$showLoadingPage()

            await this.getUserData()  
            await this.getComboData()  

            this.setListeRouteImport()

            // Hide Loading Page
            this.$hideLoadingPage()
        },

        async getUserData() {

            const res                   =   await this.$callApi("post"  ,   "/users/"+this.$route.params.id_user+"/show"    ,   null)
            console.log(res)

            this.user.nom_original          =   res.data.nom

            this.user.id                    =   res.data.id                 
            this.user.nom                   =   res.data.nom                 
            this.user.email                 =   res.data.email   
            this.user.tel                   =   res.data.tel                 
            this.user.company               =   res.data.company    
            this.user.type_user             =   res.data.type_user        

            this.user.max_route_import      =   res.data.max_route_import        

            this.user.liste_route_import    =   res.data.liste_route_import   
        },

        async getComboData() {

            const res               =   await this.$callApi("post",       "/route_import/combo",        null)

            let liste_route_import  =   res.data

            for (let i = 0; i < liste_route_import.length; i++) {

                this.liste_route_import.push({ value : liste_route_import[i].id , label : liste_route_import[i].libelle})
            }
        },

        //

        setListeRouteImport() {   

            for (let i = 0; i < this.user.liste_route_import.length; i++) {

                this.user.liste_route_import_string     =   this.user.liste_route_import_string     +   " - "   +   this.user.liste_route_import[i].libelle
            }
        },
    }
};
</script>
