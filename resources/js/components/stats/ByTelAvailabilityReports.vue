<template>

    <div class="m-3 p-3">

        <!-- Chart Filters -->
        <div class="row chart_filters" id="by_tel_availability_chart_filters">

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

            <!-- Show Table         -->
            <div class="col-6 d-flex align-items-end">
                <div v-if="by_tel_availability_table"    class="table_scroll table_scroll_x table_scroll_y table_container table_container mt-5 w-100">
                    <table class="table table-bordered w-100" id="by_tel_availability_reports_table">
                        <tr>
                            <th>User</th>
                            <th>Yes</th>
                            <th>No</th>
                            <th>Total</th>
                        </tr>

                        <tr v-for="row, index_1 in by_tel_availability_table.rows" :key="index_1">
                            <th>{{ row.label }}</th>
                            <td>{{ row.count_yes }}</td>
                            <td>{{ row.count_no }}</td>
                            <th>{{ row.count_total }}</th>
                        </tr>

                        <tr>
                            <th>{{ by_tel_availability_table.total_row.label }}</th>
                            <th>{{ by_tel_availability_table.total_row.count_yes }}</th>
                            <th>{{ by_tel_availability_table.total_row.count_no }}</th>
                            <th>{{ by_tel_availability_table.total_row.count_total }}</th>
                        </tr>
                    </table>
                </div>
            </div>
            <!--  -->

            <!-- Show Chart         -->
            <div class="col-6">
                <div v-if="show_by_tel_availability_chart"        id="by_tel_availability_reports_container"    class="chart_scroll pt-5 pb-1">
                    <div class="bar_chart_container">
                        <canvas id="by_tel_availability_chart"    ref="by_tel_availability_chart"></canvas>
                    </div>
                </div>
            </div>
            <!--  -->

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

            by_tel_availability_chart     :   null,

            by_tel_availability_reports_options    :   {
                maintainAspectRatio                 :   false,
                scales                              :   {
                    y                                   :   {
                        beginAtZero: true
                    }
                }
            },

            by_tel_availability_reports_data       :   {
                labels                              :   [],
                datasets                            :   []
            },

            //

            by_tel_availability_table              :   null,

            //

            show_by_tel_availability_chart         :   false
        }
    },

    async mounted() {

        //
        this.fetchMaps()
    },

    methods : {

        async getData() {

            try {

                if((this.start_date  !=  "")&&(this.end_date  !=  "")) {

                    this.$showLoadingPage()

                    let formData    =   new FormData()

                    formData.append("route_links"   , JSON.stringify(this.route_links))
                    formData.append("start_date"    , this.start_date)
                    formData.append("end_date"      , this.end_date)

                    await this.$callApi("post",     "/statistics/by_tel_availability_reports",   formData)
                    .then(async (res)=> {

                        console.log(res)

                        //
                        this.by_tel_availability_reports_data.labels       =   res.data.by_tel_availability_reports.labels;
                        this.by_tel_availability_reports_data.datasets     =   res.data.by_tel_availability_reports.datasets;
                        this.by_tel_availability_table                     =   res.data.by_tel_availability_reports.by_tel_availability_table;

                        //
                        this.show_by_tel_availability_chart                =   true

                        //
                        await this.$nextTick()

                        //
                        this.setChart();

                        //
                        this.$hideLoadingPage()
                    })
                }
            }

            catch(e) {

                console.log(e)
            }
        },

        setChart() {

            if (this.by_tel_availability_chart) {
                this.by_tel_availability_chart.destroy();   // Destroy existing chart for proper updates
            }

            const by_tel_availability_chart    =   this.$refs.by_tel_availability_chart.getContext('2d');

            this.by_tel_availability_chart     =   new Chart(by_tel_availability_chart, {
                type                        :   "bar"                                   ,
                data                        :   this.by_tel_availability_reports_data      ,
                options                     :   this.by_tel_availability_reports_options
            });

            //

            const bar_chart_container       =   document.querySelector(".bar_chart_container")
            const totalLabels               =   this.by_tel_availability_chart.data.labels.length

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
