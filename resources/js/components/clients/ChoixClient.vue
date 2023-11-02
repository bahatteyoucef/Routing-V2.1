<template>
    
    <div class="container mt-5">

        <div class="row mb-3">

            <div class="col-6 p-0" @click="updateInformations()">

                <div class="card bg-gradient-danger card-img-holder text-white m-1 h-100">
                    <div class="card-body d-flex align-items-center p-0">
                        <img :src="'/template/images/dashboard/circle.svg'" class="card-img-absolute"   alt="circle-image" />
                        <h5 class="text-center font-weight-normal m-1 w-100">Update Informations</h5>
                    </div>
                </div>

            </div>

            <div class="col-6 p-0" @click="orders()">

                <div class="card bg-gradient-danger card-img-holder text-white m-1 h-100">
                    <div class="card-body d-flex align-items-center p-0">
                        <img :src="'/template/images/dashboard/circle.svg'" class="card-img-absolute"   alt="circle-image" />
                        <h5 class="text-center -weight-normal m-1 w-100">Orders</h5>
                    </div>
                </div>

            </div>
        
        </div>

        <!--  -->

        <div class="row mb-3">

            <div class="col-6 p-0" @click="stockCheck()">

                <div class="card bg-gradient-danger card-img-holder text-white m-1 h-100">
                    <div class="card-body d-flex align-items-center p-0">
                        <img :src="'/template/images/dashboard/circle.svg'" class="card-img-absolute"   alt="circle-image" />
                        <h5 class="text-center font-weight-normal m-1 w-100">Stock Check</h5>
                    </div>
                </div>

            </div>

            <div class="col-6 p-0" @click="competitorsAnalysis()">

                <div class="card bg-gradient-danger card-img-holder text-white m-1 h-100">
                    <div class="card-body d-flex align-items-center p-0">
                        <img :src="'/template/images/dashboard/circle.svg'" class="card-img-absolute"   alt="circle-image" />
                        <h5 class="text-center -weight-normal m-1 w-100">Competitors Analysis</h5>
                    </div>
                </div>

            </div>
        
        </div>
       
        <!--  -->

        <div class="row mb-1 mt-5">

            <div class="col-12 p-0" @click="complaint()">

                <div class="card bg-gradient-danger card-img-holder text-white m-1 h-100">
                    <div class="card-body d-flex align-items-center p-0">
                        <img :src="'/template/images/dashboard/circle.svg'" class="card-img-absolute"   alt="circle-image" />
                        <h5 class="text-center font-weight-normal m-1 w-100">Complaint</h5>
                    </div>
                </div>

            </div>
        
        </div>

    </div>

</template>

<script>

export default {
 
    methods : {

        updateInformations() {

            this.$router.push('/route_import/'+this.$route.params.id_route_import+'/clients/'+this.$route.params.id_client+'/update')
        },

        orders() {

            this.$router.push('/route_import/'+this.$route.params.id_route_import+'/clients/'+this.$route.params.id_client+'/orders/add')
        },

        stockCheck() {

            this.$router.push('/route_import/'+this.$route.params.id_route_import+'/clients/'+this.$route.params.id_client+'/products/stock/add')
        },

        competitorsAnalysis() {

            this.$router.push('/route_import/'+this.$route.params.id_route_import+'/clients/'+this.$route.params.id_client+'/products/competitors/add')
        },

        complaint() {

            this.$router.push('/route_import/'+this.$route.params.id_route_import+'/clients/'+this.$route.params.id_client+'/complaints/add')
        }
    }
}

</script>
