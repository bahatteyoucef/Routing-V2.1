<template>

    <div class="mt-5">

        <div class="row">
            <!-- Header -->
            <div class="col-2 d-flex align-items-center">
                <h5 class="ml-1 card-title title-with-line">Set Route Import Cities</h5>
            </div>

            <div class="col-2">
                <button class="btn primary w-100" @click="sendData()">Submit</button>
            </div>
        </div>

        <form class="mt-3">

            <div class="mb-2">

                <div class="row">
                    <div class="col-3 p-0" v-for="(city,index) in route_import_cities" :key="index">
                        <div class="p-1 m-1">
                            <div class="p-3">
                                <div class="row">
                                    <div class="col-4 d-flex align-items-center">
                                        {{ city.CityNameE }}
                                    </div>

                                    <div class="col-8">
                                        <input :class="'form-control expected_clients_'+id_route_import+'_'+city.CityNo" v-model="city.expected_clients"/>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </form>
    </div>

</template>

<script>

export default {

    data() {
        return {

        }
    },

    props : ["id_route_import", "DistrictNo", "route_import_cities"],

    mounted() {

    },

    methods : {

        async sendData() {

            this.$showLoadingPage()

            //
            this.validateRouteImportCities()

            let formData = new FormData();

            formData.append("route_import_cities"   ,   JSON.stringify(this.route_import_cities))

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.id_route_import+"/districts/"+this.DistrictNo+"/cities/set",   formData)
            console.log(res.data)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
            }      
        },

        //

        validateRouteImportCities() {

            for (let index = 0; index < this.route_import_cities.length; index++) {

                // Try to parse the input value as a number
                var expected_clients    =   parseFloat(this.route_import_cities[index].expected_clients);

                // Check if the parsed value is a valid number
                if (!isNaN(expected_clients)) {

                } 

                else {

                    this.route_import_cities[index].expected_clients    =   0
                }
            }
        }
    }
};

</script>