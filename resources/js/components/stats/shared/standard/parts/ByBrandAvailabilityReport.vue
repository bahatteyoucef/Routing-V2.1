<template>

    <div>

        <!-- Title -->
        <div class="row">
            <div class="col-9 d-flex align-items-center">
                <h5 class="mb-0 ml-2">By Brand Availability Report</h5>
            </div>

            <div class="col-3 text-end">
                <img    :src="'/images/switch_arrows.png'" @click="toggleChartTable()"    role="button"    class="mb-0 mr-2"/>
            </div>
        </div>

        <!-- Show Chart         -->
        <div    v-show="show_by_brand_availability_report_chart_data"   class="row animate__animated animate__pulse">

            <!-- By BrandSourcePurchase  -->
            <div class="col-12">

                <div            id="by_brand_availability_report_chart_scroll"       class="chart_scroll pt-5 pb-1">
                    <div        id="by_brand_availability_report_chart_container"    class="pie_chart_container">
                        <canvas id="by_brand_availability_report_chart"              ref="by_brand_availability_report_chart"></canvas>
                    </div>
                </div>
            
            </div>

        </div>

        <!-- Show Table         -->
        <div    v-show="show_by_brand_availability_report_table_data"   class="row animate__animated animate__pulse mt-5">
            <div class="table_scroll table_scroll_x table_scroll_y table_container mt-5">
                <table  class="table table-bordered w-100"   id="by_brand_availability_report_table_data">
                    <thead>
                        <tr>
                            <th>Brand Availability</th>
                            <th>Clients</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="row, index_1 in by_brand_availability_report_table_data.rows" :key="index_1">
                            <th>{{ row.label }}</th>
                            <td>{{ row.count_clients }}</td>
                            <th>{{ parseInt(row.percentage_clients * 100) }} %</th>
                        </tr>

                        <tr>
                            <th>{{ by_brand_availability_report_table_data.total_row.label }}</th>
                            <th>{{ by_brand_availability_report_table_data.total_row.count_clients }}</th>
                            <th>{{ by_brand_availability_report_table_data.total_row.percentage_clients * 100 }} %</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</template>

<script>

export default {

    data() {
        return {

            by_brand_availability_report_chart              :   null    ,

            //

            show_by_brand_availability_report_chart_data    :   true    ,
            show_by_brand_availability_report_table_data    :   false   
        }
    },

    props : ["by_brand_availability_report_chart_data", "by_brand_availability_report_table_data"],

    async mounted() {

        //
        this.setChart();

        //
        await this.$nextTick()

        //
        this.emitter.emit('show_by_brand_availability_report_content_ready')
    },

    methods : {

        setChart() {

            if (this.by_brand_availability_report_chart) {
                this.by_brand_availability_report_chart.destroy();   // Destroy existing chart for proper updates
            }

            const by_brand_availability_report_chart    =   this.$refs.by_brand_availability_report_chart.getContext('2d');

            this.by_brand_availability_report_chart     =   new Chart(by_brand_availability_report_chart, {
                type                                    :   "doughnut"                                      ,
                data                                    :   this.by_brand_availability_report_chart_data    ,
                options                                 :   {}
            });
        },

        //

        toggleChartTable() {

            if(this.show_by_brand_availability_report_chart_data    ==  false) {

                this.show_by_brand_availability_report_table_data   =   false
                this.show_by_brand_availability_report_chart_data   =   true
            }

            else {

                this.show_by_brand_availability_report_chart_data   =   false
                this.show_by_brand_availability_report_table_data   =   true
            }
        }
    }
}
</script>