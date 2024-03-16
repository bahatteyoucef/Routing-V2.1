<template>

    <div class="m-3 p-3">

        <!-- Chart Filters -->
        <div class="row chart_filters" id="tel_availability_chart_filters">

            <!-- Select Map         -->
            <div class="col-4 map_filter">
                <Multiselect
                    v-model             =   "route_links"
                    :options            =   "liste_route_link"
                    mode                =   "tags" 
                    placeholder         =   "Select Maps"
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
            <div class="col-3 mt-1">
                <input type="date" class="form-control" v-model="start_date"/>
            </div>
            <!--  -->

            <!-- Select Date Range  -->
            <div class="col-3 mt-1">
                <input type="date" class="form-control" v-model="end_date"/>
            </div>
            <!--  -->

            <!-- Select Date Range  -->
            <div class="col-2 mt-1">
                <button class="btn primary w-100"   @click="getData()">Get Data</button>
            </div>
            <!--  -->
            
        </div>
        <!--  -->

        <!-- Show Chart         -->
        <div class="row">

            <!-- Daily Reports  -->
            <!-- <div class="col-4">

                <div v-if="show_tel_availability_chart"        id="tel_availability_ratio_container"    class="chart_scroll pt-5 pb-1">
                    <div class="pie_chart_container">
                        <canvas id="tel_availability_chart"    ref="tel_availability_chart"></canvas>
                    </div>
                </div>

            </div> -->

            <div class="col-12">
                <div v-if="show_tel_availability_chart"        id="tel_availability_reports_container"    class="chart_scroll pt-5 pb-1">
                    <div class="bar_chart_container">
                        <canvas id="tel_availability_chart"    ref="tel_availability_chart"></canvas>
                    </div>
                </div>
            </div>

        </div>
        <!--  -->

        <!-- Show Table         -->
        <!-- <div v-if="show_tel_availability_chart"    class="table_scroll table_container mt-5">
            <table class="table w-100" id="tel_availability_reports_table">
                <tr>
                    <th>User</th>
                    <th v-for="label, index_1 in tel_availability_reports_data.labels" :key="index_1">{{ label }}</th>
                    <th>Total</th>
                </tr>

                <tr v-for="dataset, index_2 in tel_availability_reports_data.datasets" :key="index_2">
                    <th>{{dataset.label}}</th>
                    <td v-for="data_value, index_3 in dataset.data" :key="index_3">{{ data_value }}</td>
                    <th>{{ dataset.total }}</th>
                </tr>

                <tr>
                    <th>Total</th>
                    <th v-for="total_by_day, index_4 in total_by_day_object.data" :key="index_4">{{ total_by_day }}</th>
                    <th>{{ total_by_day_object.total }}</th>
                </tr>
            </table>
        </div> -->
        <!--  -->

    </div>

</template>

<script>

import Multiselect  from    '@vueform/multiselect'

export default {

    components  : { 

        Multiselect :   Multiselect
    },

    data() {
        return {

            liste_route_link    :   [],

            //

            route_links         :   [],
            start_date          :   "",
            end_date            :   "",

            //

            tel_availability_chart     :   null,

            tel_availability_reports_options    :   {
                maintainAspectRatio                 :   false,
                scales                              :   {
                    y                                   :   {
                        beginAtZero: true
                    }
                }
            },

            tel_availability_reports_data       :   {
                labels                              :   [],
                datasets                            :   []
            },

            //

            total_by_day_object                 :   null,

            //

            show_tel_availability_chart         :   false
        }
    },

    async mounted() {

        //
        this.fetchMaps()
    },

    methods : {

        async getData() {

            try {

                this.$showLoadingPage()

                let formData    =   new FormData()

                formData.append("route_links"   , JSON.stringify(this.route_links))
                formData.append("start_date"    , this.start_date)
                formData.append("end_date"      , this.end_date)

                await this.$callApi("post",     "/statistics/tel_availability_reports",   formData)
                .then(async (res)=> {

                    console.log(res)

                    //
                    this.tel_availability_reports_data.labels           =   res.data.tel_availability_reports.labels;
                    this.tel_availability_reports_data.datasets         =   res.data.tel_availability_reports.datasets;
                    // this.total_by_day_object                            =   res.data.tel_availability_reports.total_by_day_object;

                    //
                    this.show_tel_availability_chart           =   true

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

            if (this.tel_availability_chart) {
                this.tel_availability_chart.destroy();   // Destroy existing chart for proper updates
            }

            const tel_availability_chart    =   this.$refs.tel_availability_chart.getContext('2d');

            this.tel_availability_chart     =   new Chart(tel_availability_chart, {
                type                        :   "bar"                                   ,
                data                        :   this.tel_availability_reports_data      ,
                options                     :   this.tel_availability_reports_options
            });

            //

            const bar_chart_container       =   document.querySelector(".bar_chart_container")
            const totalLabels               =   this.tel_availability_chart.data.labels.length

            if(totalLabels  >  5) {

                const newWidth                      =   700 + ((totalLabels - 7) * 90)
                bar_chart_container.style.width     =   newWidth+"px"
            }
        },

        //  //  //  //  //

        async fetchMaps() {

            try {

                this.$callApi("post",    "/route_import/stats", null)
                .then((res)=> {

                    this.liste_route_import = res.data

                    this.prepareRouteLink()
                })
            }
            catch(e) {

                console.log(e)
            }
        },

        prepareRouteLink() {

            this.liste_route_link     =   []

            for (let i = 0; i < this.liste_route_import.length; i++) {

                this.liste_route_link.push({ value : this.liste_route_import[i].id , label : this.liste_route_import[i].libelle})
            }
        },
    }
}
</script>
