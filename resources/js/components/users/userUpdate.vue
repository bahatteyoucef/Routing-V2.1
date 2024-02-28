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

                        <div class="mb-3">
                            <label for="nom"                class="form-label">Name : </label>
                            <input type="text"              class="form-control"        id="nom"           v-model="user.nom">
                        </div>

                        <div class="mb-3">
                            <label for="email"              class="form-label">Email : </label>
                            <input type="text"              class="form-control"        id="email"           v-model="user.email">
                        </div>

                        <div class="mb-3">
                            <label for="tel"                class="form-label">Tel : </label>
                            <input type="text"              class="form-control"        id="tel"           v-model="user.tel">
                        </div>

                        <div class="mb-3">
                            <label for="company"            class="form-label">Company : </label>
                            <input type="text"              class="form-control"        id="company"           v-model="user.company">
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

import Multiselect  from    '@vueform/multiselect'

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

                liste_route_import      :   null,
            },

            liste_route_import          :   []
        }
    },

    async mounted() {

        console.log(1111)

        await this.getData()
    },  

    components : {

        Multiselect
    },

    methods : {

        async getData(user) {

            await this.getComboData()  
            await this.getUserData()  

            this.setListeRouteImport()
        },

        //

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("nom"                       , this.user.nom)
            formData.append("email"                     , this.user.email)
            formData.append("tel"                       , this.user.tel)
            formData.append("company"                   , this.user.company)
            formData.append("type_user"                 , this.user.type_user)

            formData.append("max_route_import"          , this.user.max_route_import)

            formData.append("liste_route_import"        , JSON.stringify(this.user.liste_route_import))

            console.log(this.user.liste_route_import)

            const res   =   await this.$callApi('post' ,   '/users/'+this.user.id+'/update'    ,   formData)         

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

        async getUserData() {

            const res                   =   await this.$callApi("post"  ,   "/users/"+this.$route.params.id_user+"/show"    ,   null)
            console.log(res.data)

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

            const res               =   await this.$callApi("post",       "/route_import",        null)
            console.log(res.data)

            let liste_route_import  =   res.data

            for (let i = 0; i < liste_route_import.length; i++) {

                this.liste_route_import.push({ value : liste_route_import[i].id , label : liste_route_import[i].libelle})
            }
        },

        //

        setListeRouteImport() {   

            console.log(this.user.liste_route_import)
        }
    }

};
</script>