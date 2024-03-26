<template>

    <div class="m-1">

        <!-- Title -->
        <div class="row">
            <div class="col-9 d-flex align-items-center">
                <h4 class="mb-0 ml-2">Daily Report</h4>
            </div>

            <div class="col-3 text-end">
                <img    src="/images/switch_arrows.png" @click="toggleChartTable()"    role="button"    class="mb-0 mr-2"/>
            </div>
        </div>

        <!-- Show Chart         -->
        <div v-show="show_daily_report_chart_data"      class="row animate__animated animate__pulse">

            <!-- Daily Reports  -->
            <div class="col-12">

                <div        id="daily_report_chart_scroll"      class="chart_scroll pt-5 pb-1">
                    <div    id="daily_report_chart_container"   class="bar_chart_container">
                        <canvas id="daily_report_chart"    ref="daily_report_chart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <!-- Show Table         -->
        <div v-show="show_daily_report_table_data"      class="row animate__animated animate__pulse">
            <div        class="table_scroll table_scroll_x table_scroll_y table_container table_container mt-5">
                <table  class="table w-100" id="daily_report_table_data">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th v-for="label, index_1 in daily_report_table_data.allDays" :key="index_1">{{ label }}</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="row, index_2 in daily_report_table_data.rows" :key="index_2">
                            <th>{{row.label}}</th>
                            <td v-for="data_value, index_3 in row.data" :key="index_3">{{ data_value }}</td>
                            <th>{{ row.total }}</th>
                        </tr>

                        <tr>
                            <th>Total</th>
                            <th v-for="total_by_day, index_4 in daily_report_table_data.total_by_day_object.data" :key="index_4">{{ total_by_day }}</th>
                            <th>{{ daily_report_table_data.total_by_day_object.total }}</th>
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

            daily_report_chart     :   null,

            daily_report_chart_options      :   {
                maintainAspectRatio             :   false,
                scales                          :   {
                    y                               :   {
                        beginAtZero: true
                    }
                }
            },

            //

            show_daily_report_chart_data    :   true    ,
            show_daily_report_table_data    :   false   
        }
    },

    props : ["daily_report_chart_data", "daily_report_table_data"],

    async mounted() {

        //
        await this.$nextTick()

        //
        this.setChart();
    },

    methods : {

        setChart() {

            if (this.daily_report_chart) {
                this.daily_report_chart.destroy();   // Destroy existing chart for proper updates
            }

            const daily_report_chart    =   this.$refs.daily_report_chart.getContext('2d');

            this.daily_report_chart     =   new Chart(daily_report_chart, {
                type                        :   "bar"                           ,
                data                        :   this.daily_report_chart_data    ,
                options                     :   this.daily_report_chart_options
            });

            //
            const daily_report_chart_container          =   document.querySelector("#daily_report_chart_container")
            const totalLabels                           =   this.daily_report_chart.data.labels.length
            const newWidth                              =   700 + ((totalLabels - 7) * 90)
            daily_report_chart_container.style.width    =   newWidth+"px"

            //Get Chart Container Width
            let daily_report_chart_scroll           =   document.getElementById("daily_report_chart_scroll")
            let daily_report_chart_scroll_width     =   window.getComputedStyle(daily_report_chart_scroll).width

            if(parseFloat(daily_report_chart_scroll_width)      >   parseFloat(daily_report_chart_container.style.width)) {

                daily_report_chart_container.style.width    =   daily_report_chart_scroll_width
            }
        },

        //

        toggleChartTable() {

            if(this.show_daily_report_chart_data ==  false) {

                this.show_daily_report_table_data   =   false
                this.show_daily_report_chart_data   =   true
            }

            else {

                this.show_daily_report_chart_data   =   false
                this.show_daily_report_table_data   =   true
            }
        }
    }
}

</script>
