<template>

    <div>

        <!-- Show Chart     -->
        <div class="row" id="by_brand_availability_report">

            <!-- Show Chart     -->
            <div class="col-6">
                <div        id="by_brand_availability_reports_container_yes"    class="pb-1">
                    <div    class="pie_chart_container">
                        <canvas id="by_brand_availability_chart_yes"    ref="by_brand_availability_chart_yes"></canvas>
                    </div>
                </div>
            </div>

            <!-- Show Chart     -->
            <div class="col-6">
                <div        id="by_brand_availability_reports_container_no"    class="pb-1">
                    <div    class="pie_chart_container">
                        <canvas id="by_brand_availability_chart_no"     ref="by_brand_availability_chart_no"></canvas>
                    </div>
                </div>
            </div>

        </div>
        <!--                -->

        <!-- Show Table     -->
        <div class="row">
            <div class="col-12">
                <div        class="table_scroll table_scroll_x table_scroll_y table_container mt-5 w-100">
                    <table  class="table table-bordered w-100"   id="by_brand_availability_table">
                        <thead>
                            <tr>
                                <th>Brand Availability</th>
                                <th>Clients</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="row, index_1 in by_brand_availability_table.rows" :key="index_1">
                                <th>{{ row.label }}</th>
                                <td>{{ row.count_clients }}</td>
                                <th>{{ row.percentage_clients * 100 }} %</th>
                            </tr>

                            <tr>
                                <th>{{ by_brand_availability_table.total_row.label }}</th>
                                <th>{{ by_brand_availability_table.total_row.count_clients }}</th>
                                <th>{{ by_brand_availability_table.total_row.percentage_clients * 100 }} %</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--                -->

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

            by_customer_type_chart              :   null,

            by_customer_type_reports_data       :   {
                labels                              :   [],
                datasets                            :   []
            },
        }
    },

    async mounted() {

        //
        this.setChart();
    },

    methods : {

        setChart() {

            if (this.by_customer_type_chart) {
                this.by_customer_type_chart.destroy();   // Destroy existing chart for proper updates
            }

            const by_customer_type_chart    =   this.$refs.by_customer_type_chart.getContext('2d');

            this.by_customer_type_chart     =   new Chart(by_customer_type_chart, {
                type                        :   "doughnut"                              ,
                data                        :   this.by_customer_type_reports_data      ,
                options                     :   {}
            });
        }
    }
}

</script>
