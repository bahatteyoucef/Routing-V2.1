<template>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <div class="row">
                        <h4>Profile Details</h4>
                    </div>

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

                        <!--  -->

                        <div class="container mt-5">
                            <div class="row justify-content-center">
                                <div class="col-sm-3 mt-3">
                                    <button type="button" class="btn btn-secondary w-100"       @click="changePassword()">Change Password</button>
                                </div>
    
                                <div class="col-sm-3 mt-3">
                                    <button type="button" class="btn btn-primary w-100"         @click="updateInformations()">Update Informations</button>
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

import headerComponent      from "../../template/components/headerComponent.vue"

export default {

    data() {
        return {
            user            :   {
                nom_original            :   '',

                id                      :   '',
                nom                     :   '',
                email                   :   '',
                tel                     :   '',
                company                 :   '',
                type_user               :   '',

                max_route_import        :   0 ,

                liste_route_import          :   [],
                liste_route_import_string   :   ""
            },

            liste_route_import          :   []
        }
    },

    components : {
        headerComponent 
    },

    async mounted() {

        await this.getData()
    },  

    methods : {

        async getData(user) {

            await this.getComboData()  
            await this.getUserData()  

            this.setListeRouteImport()
        },

        async getUserData() {

            console.log(111)

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

            console.log(222)

            const res               =   await this.$callApi("post",     "/route_import/combo",      null)
            console.log(res)

            let liste_route_import  =   res.data

            for (let i = 0; i < liste_route_import.length; i++) {

                this.liste_route_import.push({ value : liste_route_import[i].id , label : liste_route_import[i].libelle})
            }
        },

        //

        setListeRouteImport() {   

            for (let i = 0; i < this.user.liste_route_import.length; i++) {

                this.user.liste_route_import_string     =   this.user.liste_route_import_string     +   " - "   +   this.liste_route_import[i].label
            }
        },

        //

        updateInformations() {

            this.$router.push('/users/'+this.$route.params.id_user+'/update')
        },

        changePassword() {

            this.$router.push('/users/'+this.$route.params.id_user+'/update/password')
        }
    }
};
</script>
