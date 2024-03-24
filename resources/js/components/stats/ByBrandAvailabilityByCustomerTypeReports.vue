<template>

    <div class="m-3 p-3">

        <!-- Chart Filters -->
        <div class="row chart_filters" id="by_brand_availability_by_customer_type_chart_filters">

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
            <div class="col-8 d-flex align-items-end">
                <div v-if="by_brand_availability_by_customer_type_table"    class="table_scroll table_scroll_x table_scroll_y table_container mt-5 w-100">
                    <table class="table table-bordered w-100" id="by_brand_availability_by_customer_type_reports_table">
                        <thead>
                            <tr>
                                <th>CustomerType</th>
                                <th>Clients</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="row, index_1 in by_brand_availability_by_customer_type_table.rows" :key="index_1">
                                <th>{{ row.label }}</th>
                                <td>{{ row.count_clients }}</td>
                                <th>{{ row.percentage_clients * 100 }} %</th>
                            </tr>

                            <tr>
                                <th>{{ by_brand_availability_by_customer_type_table.total_row.label }}</th>
                                <th>{{ by_brand_availability_by_customer_type_table.total_row.count_clients }}</th>
                                <th>{{ by_brand_availability_by_customer_type_table.total_row.percentage_clients * 100 }} %</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Show Chart         -->
            <div class="col-4">
                <div v-if="show_by_brand_availability_by_customer_type_chart"        id="by_brand_availability_by_customer_type_reports_container"    class="pb-1">
                    <div class="pie_chart_container">
                        <canvas id="by_brand_availability_by_customer_type_chart"    ref="by_brand_availability_by_customer_type_chart"></canvas>
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

            by_brand_availability_by_customer_type_chart              :   null,

            by_brand_availability_by_customer_type_reports_data       :   {
                labels                              :   [],
                datasets                            :   []
            },

            //

            by_brand_availability_by_customer_type_table              :   null,

            //

            show_by_brand_availability_by_customer_type_chart         :   false
        }
    },

    async mounted() {

        //
        this.fetchMaps()
    },

    methods : {

        async getData() {

            console.log("asdasdasd")

            try {

                if((this.start_date  !=  "")&&(this.end_date  !=  "")) {

                    this.$showLoadingPage()

                    let formData    =   new FormData()

                    formData.append("route_links"   , JSON.stringify(this.route_links))
                    formData.append("start_date"    , this.start_date)
                    formData.append("end_date"      , this.end_date)

                    await this.$callApi("post",     "/statistics/by_brand_availability_by_customer_type_reports",   formData)
                    .then(async (res)=> {

                        console.log(res)
                        console.log(res.data.by_brand_availability_by_customer_type_reports.by_brand_availability_by_customer_type_table)

                        //
                        // this.by_brand_availability_by_customer_type_reports_data.labels       =   res.data.by_brand_availability_by_customer_type_reports.labels;
                        // this.by_brand_availability_by_customer_type_reports_data.datasets     =   res.data.by_brand_availability_by_customer_type_reports.datasets;
                        this.by_brand_availability_by_customer_type_table                     =   res.data.by_brand_availability_by_customer_type_reports.by_brand_availability_by_customer_type_table;

                        //
                        this.show_by_brand_availability_by_customer_type_chart                =   true

                        //
                        // await this.$nextTick()

                        //
                        // this.setChart();

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

            if (this.by_brand_availability_by_customer_type_chart) {
                this.by_brand_availability_by_customer_type_chart.destroy();   // Destroy existing chart for proper updates
            }

            const by_brand_availability_by_customer_type_chart    =   this.$refs.by_brand_availability_by_customer_type_chart.getContext('2d');

            this.by_brand_availability_by_customer_type_chart     =   new Chart(by_brand_availability_by_customer_type_chart, {
                type                        :   "doughnut"                              ,
                data                        :   this.by_brand_availability_by_customer_type_reports_data      ,
                options                     :   {}
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
