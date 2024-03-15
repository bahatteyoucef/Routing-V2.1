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
        <div class="chart-box">
            <div class="chart_container mt-5">
                <div class="y_axes_chart_column">
                    <canvas id="y_axes_chart" ref="y_axes_chart"></canvas>
                </div>

                <div class="x_axes_chart_column">
                    <div class="main_chart_container">
                        <canvas id="main_chart" ref="main_chart"></canvas>
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

            y_axes_chart        :   null,
            main_chart          :   null,

            //

            main_chart_options  :   {
                maintainAspectRatio : false,
                layout              :   {
                    padding             :   {
                        top                 :   11
                    }
                },
                plugins             :   {
                    legend              :   {
                        display             :   false
                    }
                },
                scales: {
                    y               :   {
                        beginAtZero     :   true,

                        ticks       :   {
                            display     :   false
                        },
                        border      :   {
                            display     :   false
                        }
                    }
                }
            },

            //

            y_axes_chart_options    :   {
                maintainAspectRatio     :   false,
                layout                  :   {
                    padding                 :   {
                        bottom                  :   36
                    }
                },
                plugins                 :   {
                    legend                  :   {
                        display                 :   false
                    }
                },
                scales                  :   {
                    x                       :   {
                        ticks                   :   {
                            display                 :   false
                        },
                        gridLines               :   {
                            display                 :   false
                        }
                    },
                    y                       :   {
                        beginAtZero             :   true,

                        afterFit    : (c) => {
                            c.width = 40;
                        }
                    }
                }
            },

            //

            main_chart_data         :   {
                labels                  :   [],
                datasets                :   []
            },

            //

            y_axes_chart_data       :   {
                labels                  :   [],
                datasets                :   []
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
                    this.main_chart_data.labels         =   res.data.daily_reports.labels;
                    this.main_chart_data.datasets       =   res.data.daily_reports.datasets;

                    //
                    this.y_axes_chart_data.labels       =   res.data.daily_reports.labels;
                    this.y_axes_chart_data.datasets     =   res.data.daily_reports.datasets;

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

        updateChart() {

            //

            if (this.y_axes_chart) {
                this.y_axes_chart.destroy(); // Destroy existing chart for proper updates
            }

            if (this.main_chart) {
                this.main_chart.destroy(); // Destroy existing chart for proper updates
            }

            //

            const y_axes_chart  =   this.$refs.y_axes_chart.getContext('2d');

            this.chart      =   new Chart(y_axes_chart, {
                type        :   "bar",
                data        :   this.y_axes_chart_data,
                options     :   this.y_axes_chart_options,
            });

            const main_chart    =   this.$refs.main_chart.getContext('2d');

            this.chart      =   new Chart(main_chart, {
                type        :   "bar",
                data        :   this.main_chart_data,
                options     :   this.main_chart_options,
            });

            //
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

.chart_container {
    width       : 700px;
    display     : flex;
}

.y_axes_chart_column {
    width       : 40px;
}

.x_axes_chart_column {
    max-width   : 100%;
    overflow-x  : scroll;
}

.main_chart_container {
    width   : calc(1000px - 40px);
    height  : 500px;
}

</style>