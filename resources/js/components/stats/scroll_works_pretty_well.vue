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
        <div class="chartCard">
            <div class="chartBox">
                <div class="chartContainer">
                    <div class="containerBody">
                        <canvas id="myChart"></canvas>
                    </div>
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

            config  :   {
                type    :   'bar',
                data    :   {
                    labels: [   'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun',
                                'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun',
                                'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun',
                                'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun',
                                'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'   ],
                    datasets: [{
                        label: 'Weekly Sales',
                        data: [18, 12, 6, 9, 12, 3, 9, 18, 12, 6, 9, 12, 3, 9, 18, 12, 6, 9, 12, 3, 9],
                        backgroundColor: [
                            'rgba(255, 26, 104, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(0, 0, 0, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 26, 104, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(0, 0, 0, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio :   false,
                    scales: {
                    y: {
                        beginAtZero: true
                    }
                    }
                },
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

            const myChart   =   new Chart(
                document.getElementById('myChart'),
                this.config
            );

            const containerBody =   document.querySelector(".containerBody")
            const totalLabels   =   myChart.data.labels.length

            if(totalLabels  >  5) {

                const newWidth                  =   700 + ((totalLabels - 7) * 90)
                containerBody.style.width       =   newWidth+"px"
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

.chartMenu {
    width: 100vw;
    height: 40px;
    background: #1A1A1A;
    color: rgba(54, 162, 235, 1);
}
.chartMenu p {
    padding: 10px;
    font-size: 20px;
}
.chartCard {
    width: 100vw;
    height: calc(100vh - 40px);
    background: rgba(54, 162, 235, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
}
.chartBox {
    width: 700px;
    padding: 20px;
    border-radius: 20px;
    border: solid 3px rgba(54, 162, 235, 1);
    background: white;
}

.chartContainer {
    width       :   700px;
    max-width   :   700px;
    overflow-x  :   scroll;
}

.containerBody {
    height      :   500px;
}

</style>