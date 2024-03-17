<template>

    <div class="mt-3">
        <!-- Header -->
        <div class="col-sm-3">
            <h5 class="ml-1 card-title title-with-line">Set Cities</h5>
        </div>

        <form class="mt-3">

            Youcef

            <div class="m-3" v-for="(city,index) in cities" :key="index">

                <div class="form-row mb-2">
                    <div class="form-group col-2 m-0">
                        <label class="form-label ml-1">{{city.CityNameE}}</label> 
                    </div>

                    <div class="form-group col-2 m-0">
                        <input type="button" class="form-control"/>
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

    props : ["cities"],

    methods : {

        async sendData() {

            this.$showLoadingPage()

            this.setCitiesJSON()
            this.setareasJSON()

            let formData = new FormData();

            formData.append("buid"              , this.organisation.BUID)
            formData.append("cities"            , JSON.stringify(this.cities))
            formData.append("areas"             , JSON.stringify(this.areas))

            console.log(this.cities)
            console.log(this.areas)

            const res                   =   await this.$callApi("post"  ,   "/cities/store",  formData)
            console.log(res.data)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                // 
                this.$goBack()
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
            }      
        },

        //

        setCitiesJSON() {

            let city             =   {}

            let cities            =   []
            let city_checkbox    =   null

            for (let i = 0; i < this.tout_cities.length; i++) {

                city_checkbox    =   document.getElementById("checkbox_city_"+this.tout_cities[i].CityNo)
                
                if((city_checkbox)&&(city_checkbox.checked)) {

                    city                =   {}

                    city.CityNo         =   this.tout_cities[i].CityNo
                    city.RegionNo       =   this.tout_cities[i].RegionNo
                    city.CityNameE      =   this.tout_cities[i].CityNameE
                    city.CityNoNameA    =   this.tout_cities[i].CityNoNameA
                    city.areas          =   this.tout_cities[i].areas

                    cities.push(city)
                }
            }

            this.cities   =   cities
        }
    }
};

</script>