<template>
    <div class="content-wrapper p-3">
        <div class="card">
            <div class="card-body">

                <div>
                    <!-- Header -->
                    <h3 class="fw-bold"><u>Districts</u></h3>

                    <!-- District -->
                    <div class="row mt-5">
                        <div class="col-sm-2 mt-1">
                            <label for="district"                    class="form-label font-weight-bold mt-2">District : </label>
                        </div>

                        <div class="col-sm-8 mt-1">
                            <Multiselect
                                v-model             =   "selected_district"
                                :options            =   "districts"
                                mode                =   "single"
                                placeholder         =   "Select District"

                                :close-on-select    =   "true"
                                :searchable         =   "true"
                                :create-option      =   "false"

                                @select             =   "getDistrictCities()"
                                @deselect           =   "clearDistrictCities()"
                                @clear              =   "clearDistrictCities()"
                            />
                        </div>

                        <div class="col-sm-2 mt-1">
                            <button type="button" class="btn btn-primary w-100"   @click="sendData()">Submit</button>
                        </div>
                    </div>
                </div>

                <!--  -->
                <hr class="mt-5 mb-5" />
                <!--  -->

                <div v-if="selected_district">
                    <!-- Header -->
                    <h5 class="fw-bold"><u>District ({{ selected_district.DistrictNameE }}) Cities</u></h5>

                    <!-- Cities -->
                    <div class="row">
                        <div v-for="city, index in district_cities" :key="index" class="col-sm-2 mt-3">
                            <label class="form-label">{{ city.CityNameE }}</label>
                            <input class="form-control form-control-sm" v-model="city.expected_clients"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
 
<script>

import Multiselect from "@vueform/multiselect"

export default {

    data() {
        return {

            selected_district       :   null,
            district_cities         :   []  ,

            districts_full          :   []  ,
            districts               :   []
        }
    },

    components : {
        Multiselect
    },

    async mounted() {
        await this.getData()
    },  

    methods : {

        async sendData() {

            this.$showLoadingPage()

            let formData    =   new FormData();
            formData.append("district_cities" , JSON.stringify(this.district_cities))

            const res   = await this.$callApi('post' ,  '/rtm_willayas/'+this.selected_district.DistrictNo+'/rtm_cites/expected_clients/update' ,   formData)         
            console.log(res)

            if(res.status===200){

                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])
            }
            
            else{

                this.$hideLoadingPage()

                // Send Errors
                this.$showWarnings("Error !", res.data.errors)
            }
        },

        //

        async getData() {

            await this.getComboData()  
        },

        async getComboData() {

            const res           =   await this.$callApi("post",     "/rtm_willayas",    null)
            console.log(res)

            this.districts_full =   res.data

            for (let index = 0; index < this.districts_full.length; index++) {

                this.districts.push({ value : this.districts_full[index], label : this.districts_full[index].DistrictNameE   +   " ("+ this.districts_full[index].DistrictNo +")" })      
            }
        },

        //

        async getDistrictCities() {

            const res               =   await this.$callApi("post",     "/rtm_willayas/"+this.selected_district.DistrictNo+"/rtm_cites",    null)
            console.log(res)

            this.district_cities    =   res.data
        },

        async clearDistrictCities() {

            this.district_cities    =   []
        }
    }
};

</script>