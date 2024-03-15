<template>

    <div class="container p-3">

        <!-- Chart Filters -->
        <div class="row chart_filters" id="daily_reports_chart_filters">

            <!-- Select Map         -->
            <div class="col-4 map_filter">
                <Multiselect
                    v-model             =   "route_links"
                    :options            =   "liste_route_link"
                    mode                =   "multiple" 
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
        <div v-show="show_daily_reports_chart" class="chart_container">
            <div class="chart_container_body">
                <Bar
                    ref="daily_reports_chart"
                    id="daily_reports_chart"

                    class="chart_content"

                    :options="chartOptions"
                    :data="updatedChartData"
                />
            </div>
        </div>
        <!--  -->

    </div>

</template>

<script>

//
import { Bar }                                                                                              from    'vue-chartjs'
import { Chart          as      ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale }   from    'chart.js'

import Multiselect                                                                                          from    '@vueform/multiselect'
//

//
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
//

export default {

    components  : { 

        Bar         :   Bar         ,
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

            chartData: {
                labels          :   [],
                datasets        :   []
            },

            chartOptions        :   {
                scales              :   {
                    x                   :   {
                        ticks               :   {
                            autoSkip            :   false   , // Setting autoSkip to true will enable auto skipping of labels
                            maxRotation         :   0       ,
                            minRotation         :   0
                        },

                        // Add the following option to enable a horizontal scrollbar
                        // type                    :   'linear',
                        // position                :   'bottom'
                    }
                },

                plugins         :   {
                    legend          :   {
                        display         :   true    ,
                        position        :   'top'
                    },
                }
            },

            //

            rows                :   [],

            //

            show_daily_reports_chart    :   false
        }
    },

    async mounted() {

        //
        this.fetchMaps()
    },

    computed: {
        
        updatedChartData() {

            return {

                labels      : this.chartData.labels,
                datasets    : this.chartData.datasets,
            };
        },

        chartBackgroundColor() {

            return (context) => {

                let colors  =   ["#FF0000", "#00FF00", "#0000FF"]

                const index = context.dataIndex;
                return colors[index % this.$colors.length];
            };
        }

    },

    methods : {

        async getData() {

            try {

                this.$showLoadingPage()

                let formData    =   new FormData()

                formData.append("route_links"   , JSON.stringify(this.route_links))
                formData.append("start_date"    , this.start_date)
                formData.append("end_date"      , this.end_date)

                await this.$callApi("post",   "/daily_reports",   formData)
                .then(async (res)=> {

                    //
                    this.show_daily_reports_chart   =   true

                    //
                    this.chartData.labels           =   res.data.daily_reports.labels;
                    this.chartData.datasets         =   res.data.daily_reports.datasets;

                    // Set colors before initial render
                    this.chartData.datasets.forEach((dataset, index) => {
                        dataset.backgroundColor     =   this.$colors[index % this.$colors.length];
                    });

                    //
                    this.$hideLoadingPage()
                })
            }

            catch(e) {

                console.log(e)
            }
        },

        //

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

<style scoped>

</style>