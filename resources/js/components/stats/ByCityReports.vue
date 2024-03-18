<template>

    <div class="m-3 p-3">
        <div>
            <!-- Chart Filters -->
            <div class="row chart_filters" id="by_city_chart_filters">

                <!-- Select Map         -->
                <div class="col-2 map_filter">
                    <Multiselect
                        v-model             =   "route_link"
                        :options            =   "liste_route_link"
                        mode                =   "single" 
                        placeholder         =   "Select Map"
                        class               =   "mt-1"

                        :close-on-select    =   "false"
                        :searchable         =   "true"
                        :create-option      =   "false"

                        :canDeselect        =   "true"
                        :canClear           =   "true"
                        :allowAbsent        =   "true"
                    />
                </div>
                <!--  -->

                <!-- Select District    -->
                <div class="col-2 district_filter">
                    <Multiselect
                        v-model             =   "DistrictNo"
                        :options            =   "liste_districts"
                        mode                =   "single" 
                        placeholder         =   "Select District"
                        class               =   "mt-1"

                        :close-on-select    =   "false"
                        :searchable         =   "true"
                        :create-option      =   "false"

                        :canDeselect        =   "true"
                        :canClear           =   "true"
                        :allowAbsent        =   "true"
                    />
                </div>
                <!--  -->

                <!-- Select Date Range  -->
                <div class="col-2 mt-1">
                    <input type="date" class="form-control" v-model="start_date"/>
                </div>
                <!--  -->

                <!-- Select Date Range  -->
                <div class="col-2 mt-1">
                    <input type="date" class="form-control" v-model="end_date"/>
                </div>
                <!--  -->

                <!-- Get Data           -->
                <div class="col-2 mt-1">
                    <button class="btn primary w-100"   @click="getData()">Get Data</button>
                </div>
                <!--  -->

                <!-- Set Cities         -->
                <div class="col-2 mt-1 pl-3">
                    <button class="btn primary w-100" @click="showCities()">Show Cities</button>
                </div>
                <!--  -->
                
            </div>
            <!--  -->

            <!-- Show Route Cities -->
            <div v-show="show_set_cities_content"   style="min-height : 255px"  id="set_cities_div" class="p-0 animate__animated animate__fadeInDown">
                <SetCityMapExpected :key="route_import_cities"  :id_route_import="route_link"   :DistrictNo="DistrictNo"    :route_import_cities="route_import_cities"></SetCityMapExpected>
            </div>
            <!--  -->

            <!-- Show Chart             -->
            <div class="row">

                <div class="col-6">
                    <div v-if="show_by_city_chart"        id="by_city_reports_container"    class="pt-5 pb-1">
                        <div class="pie_chart_container">
                            <canvas id="by_city_chart"    ref="by_city_chart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
            <!--  -->

            <!-- Show Table             -->
            <div v-if="by_city_table"    class="table_scroll table_container mt-5">
                <table class="table w-100" id="by_city_reports_table">
                    <tr>
                        <th>CustomerType</th>
                        <th>Clients</th>
                        <th>Total</th>
                    </tr>

                    <tr v-for="row, index_1 in by_city_table.rows" :key="index_1">
                        <th>{{ row.label }}</th>
                        <td>{{ row.count_clients }}</td>
                        <th>{{ row.percentage_clients * 100 }} %</th>
                    </tr>

                    <tr>
                        <th>{{ by_city_table.total_row.label }}</th>
                        <th>{{ by_city_table.total_row.count_clients }}</th>
                        <th>{{ by_city_table.total_row.percentage_clients * 100 }} %</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</template>

<script>

import Multiselect          from    '@vueform/multiselect'

import SetCityMapExpected   from    '../city/SetCityMapExpected.vue'

export default {

    data() {
        return {

            liste_route_link    :   [],
            liste_districts     :   [],
            route_import_cities :   [],

            //

            route_link          :   "",
            DistrictNo          :   "",

            start_date          :   "",
            end_date            :   "",

            //

            by_city_chart               :   null,

            by_city_reports_data        :   {
                labels                      :   [],
                datasets                    :   []
            },

            //

            by_city_table               :   null,

            //

            show_by_city_chart          :   false,

            //

            show_set_cities_content     :   false
        }
    },

    components  : { 

        Multiselect         :   Multiselect         ,
        SetCityMapExpected  :   SetCityMapExpected
    },

    async mounted() {

        //
        this.fetchMaps()

        //
        this.fetchDistricts()
    },

    methods : {

        async getData() {

            try {

                this.$showLoadingPage()

                let formData    =   new FormData()

                formData.append("route_links"   , JSON.stringify(this.route_links))
                formData.append("start_date"    , this.start_date)
                formData.append("end_date"      , this.end_date)

                await this.$callApi("post",     "/statistics/by_city_reports",   formData)
                .then(async (res)=> {

                    console.log(res)

                    //
                    this.by_city_reports_data.labels       =   res.data.by_city_reports.labels;
                    this.by_city_reports_data.datasets     =   res.data.by_city_reports.datasets;
                    this.by_city_table                     =   res.data.by_city_reports.by_city_table;

                    //
                    this.show_by_city_chart                =   true

                    //
                    await this.$nextTick()

                    //
                    this.setChart();

                    //
                    this.$hideLoadingPage()
                })
            }

            catch(e) {

                console.log(e)
            }
        },

        setChart() {

            if (this.by_city_chart) {
                this.by_city_chart.destroy();   // Destroy existing chart for proper updates
            }

            const by_city_chart    =   this.$refs.by_city_chart.getContext('2d');

            this.by_city_chart     =   new Chart(by_city_chart, {
                type                        :   "pie"                                   ,
                data                        :   this.by_city_reports_data      ,
                options                     :   {}
            });
        },

        //  //  //  //  //

        async fetchMaps() {

            try {

                this.$callApi("post",    "/route_import/stats", null)
                .then((res)=> {

                    let liste_route_link        =   res.data

                    //
                    this.liste_route_link       =   []

                    for (let i = 0; i < liste_route_link.length; i++) {

                        this.liste_route_link.push({ value : liste_route_link[i].id , label : liste_route_link[i].libelle})
                    }
                })
            }
            catch(e) {

                console.log(e)
            }
        },

        //  //  //  //  //

        async fetchDistricts() {

            try {

                this.$callApi("post",    "/rtm_willayas",   null)
                .then((res)=> {

                    let liste_districts     =   res.data

                    //
                    this.liste_districts   =   []

                    for (let i = 0; i < liste_districts.length; i++) {

                        this.liste_districts.push({ value : liste_districts[i].DistrictNo , label : liste_districts[i].DistrictNameE})
                    }
                })
            }
            catch(e) {

                console.log(e)
            }
        },

        //  //  //  //  //

        async showCities() {

            if(this.show_set_cities_content ==  true) {

                //
                this.show_set_cities_content    =   false
            }

            else {

                if((this.route_link  !=  "")&&(this.DistrictNo  !=  "")) {

                    //
                    await this.$callApi("post",     "/route_import/"+this.route_link+"/districts/"+this.DistrictNo+"/cities",      null)
                    .then(async (res)=> {

                        //
                        this.route_import_cities    =   res.data.route_import_cities

                        //
                        this.$hideLoadingPage()

                        //
                        this.show_set_cities_content    =   true
                    })
                }
            }
        }
    }
}
</script>
