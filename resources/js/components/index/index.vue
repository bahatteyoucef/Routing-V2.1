<template>
    <div class="content-wrapper">
        <section class="dashboard">
            <div class="page-header">

                <div class="row w-100">
                    <div class="col-10">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                                <i class="mdi mdi-home"></i>
                            </span> Dashboard 
                        </h3>
                    </div>

                    <div class="col-1"></div>

                    <div class="col-1 mt-auto">
                        <button class="float-right btn bg-gradient-primary text-white w-100" @click="AddRouteImport()">+</button>
                    </div>
                </div>

            </div>

            <div class="row">

                <div    class="col-md-3 stretch-card grid-margin" 
                        v-for="route_import in liste_route_import" :key="route_import.id">

                    <div class="card bg-gradient-danger card-img-holder text-white">
                        <div class="card-body p-3">
                            <img :src="'/template/images/dashboard/circle.svg'"    class="card-img-absolute"   alt="circle-image" />

                            <h4 class="font-weight-normal mb-3">{{route_import.libelle}}

                                <span role="button" class="p-1" @click="deleteMap(route_import.id)">
                                    <i class="mdi mdi-delete mdi-24px float-right">
                                    </i>                                    
                                </span>

                                <span role="button" class="p-1" @click="navToMap(route_import.id)">
                                    <i class="mdi mdi-google-maps mdi-24px float-right">
                                    </i>
                                </span>

                            </h4>
 
                            <h6 class="card-text">ID : {{route_import.id}}</h6>
                            <h6 class="card-text">libelle : {{route_import.libelle}}</h6>

                        </div>
                    </div>

                </div>
            
            </div>

        </section>

    </div>
</template>

<script>

export default {

    data() {
        return {
            liste_route_import  :   [],
        }
    },

    async mounted() {

        await this.getRouteImport()
    },

    methods : {

        async getRouteImport() {

            try {

                this.$callApi("post",    "/route_import", null)
                .then((res)=> {

                    console.log(res.data)

                    this.liste_route_import = res.data
                })
            }

            catch(e) {

                console.log(e)
            }
        },

        AddRouteImport() {
    
            // Go To Route
            this.$router.push('/route/obs/route_import/add')
        },

        navToMap(id_route_import) {
            
            this.$router.push('/route/obs/route_import/'+id_route_import+'/details')
        },

        async deleteMap(id_route_import) {

            // Show Loading Page
            this.$showLoadingPage()

            const res   = await this.$callApi('post'    ,   '/route_import/'+id_route_import+'/delete'    ,   null)      

            if(res.status===200){

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])

                // Get Route Import
                await this.getRouteImport()

                // Hide Loading Page
                this.$hideLoadingPage()
            }
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)

                // Hide Loading Page
                this.$hideLoadingPage()
			}
        }
    }
}

</script>
