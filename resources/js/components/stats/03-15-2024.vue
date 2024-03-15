<template>

    <div class="m-3 p-3">

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
        <div class="chart-container mt-5 w-100">
            <div class="large-column">
                <div class="main_chart_container">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
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

            chart               :   null,
            width               :   400, // Adjust as needed
            height              :   250, // Adjust as needed

            options             :   {

                responsive          :   false, // Disable responsiveness

                scales              :   {
                    x                   :   {
                        ticks               :   {
                            autoSkip            :   false   , // Setting autoSkip to true will enable auto skipping of labels
                            maxRotation         :   45      ,
                            minRotation         :   45
                        },

                        // Add the following option to enable a horizontal scrollbar
                        // type                    :   'linear',
                        // position                :   'bottom'
                    }
                },
            },

            chartData           :   {
                labels              :   [],
                datasets            :   []
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

        //
        this.prepareChart()
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

                    //
                    this.updateChart();

                    //
                    this.$hideLoadingPage()
                })
            }

            catch(e) {

                console.log(e)
            }
        },

        prepareChart() {

            const ctx   =   document.getElementById('myChart');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [   'Python', 'JavaScript', 'C++', 'C', 'Java', 'PHP', 
                                'Python', 'JavaScript', 'C++', 'C', 'Java', 'PHP', 
                                'Python', 'JavaScript', 'C++', 'C', 'Java', 'PHP', 
                                'Python', 'JavaScript', 'C++', 'C', 'Java', 'PHP', 
                                'Python', 'JavaScript', 'C++', 'C', 'Java', 'PHP', 
                                'Python', 'JavaScript', 'C++', 'C', 'Java', 'PHP'   ],
                    datasets: [{
                        label: 'Percentage (%)',
                        data: [49, 63, 22, 19, 30, 19],
                        borderColor: 'green',
                        fill: false,
                    }]
                },

                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 11
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero :   true,

                            ticks: {
                                display: true
                            },
                            border: {
                                display: true
                            }
                        }
                    }
                }
            });
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

<style scoped>

.chart-container {
    width       : 700px;
    display     : flex;
}

.large-column {
    max-width   : 100%;
    overflow-x  : scroll;
}

.main_chart_container {
    width       : calc(1000px - 40px);
    height      : 500px;
}

</style>