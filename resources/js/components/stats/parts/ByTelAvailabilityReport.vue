<template>

    <div class="m-1">

        <!-- Title -->
        <div class="row">
            <div class="col-9 d-flex align-items-center">
                <h4 class="mb-0 ml-2">By Tel Availability</h4>
            </div>

            <div class="col-3 text-end">
                <img    :src="'/images/switch_arrows.png'" @click="toggleChartTable()"    role="button"    class="mb-0 mr-2"/>
            </div>
        </div>

        <!-- Show Chart         -->
        <div v-show="show_by_tel_availability_report_chart_data"    class="row animate__animated animate__pulse">

            <!-- Daily Reports  -->
            <div class="col-12">
                <div        id="by_tel_availability_report_chart_scroll"        class="chart_scroll pt-5 pb-1">
                    <div    id="by_tel_availability_report_chart_container"     class="bar_chart_container">
                        <canvas id="by_tel_availability_report_chart"           ref="by_tel_availability_report_chart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <!-- Show Table -->
        <div v-show="show_by_tel_availability_report_table_data"    class="row animate__animated animate__pulse">
            <div        class="table_scroll table_scroll_x table_scroll_y table_container mt-5">
                <table  class="table table-bordered w-100" id="by_tel_availability_report_table_data">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Yes</th>
                            <th>No</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="row, index_1 in by_tel_availability_report_table_data.rows" :key="index_1">
                            <th>{{ row.label }}</th>
                            <td>{{ row.count_yes }}</td>
                            <td>{{ row.count_no }}</td>
                            <th>{{ row.count_total }}</th>
                        </tr>

                        <tr>
                            <th>{{ by_tel_availability_report_table_data.total_row.label }}</th>
                            <th>{{ by_tel_availability_report_table_data.total_row.count_yes }}</th>
                            <th>{{ by_tel_availability_report_table_data.total_row.count_no }}</th>
                            <th>{{ by_tel_availability_report_table_data.total_row.count_total }}</th>
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

            by_tel_availability_report_chart        :   null,

            by_tel_availability_report_chart_options    :   {
                maintainAspectRatio                         :   false,
                scales                                      :   {
                    y                                           :   {
                        beginAtZero: true
                    }
                }
            },

            //

            show_by_tel_availability_report_chart_data  :   true    ,
            show_by_tel_availability_report_table_data  :   false   
        }
    },

    props : ["by_tel_availability_report_chart_data", "by_tel_availability_report_table_data"],

    async mounted() {

        //
        this.setChart();

        //
        await this.$nextTick()

        //
        this.emitter.emit('show_by_tel_availability_report_content_ready')
    },

    methods : {

        setChart() {

            if (this.by_tel_availability_report_chart) {
                this.by_tel_availability_report_chart.destroy();   // Destroy existing chart for proper updates
            }

            const by_tel_availability_report_chart      =   this.$refs.by_tel_availability_report_chart.getContext('2d');

            this.by_tel_availability_report_chart       =   new Chart(by_tel_availability_report_chart, {
                type                                        :   "bar"                                           ,
                data                                        :   this.by_tel_availability_report_chart_data      ,
                options                                     :   this.by_tel_availability_report_chart_options
            });

            //
            const by_tel_availability_report_chart_container            =   document.querySelector("#by_tel_availability_report_chart_container")
            const totalLabels                                           =   this.by_tel_availability_report_chart.data.labels.length
            const newWidth                                              =   700 + ((totalLabels - 7) * 90)
            by_tel_availability_report_chart_container.style.width      =   newWidth+"px"

            //Get Chart Container Width
            let by_tel_availability_report_chart_scroll                 =   document.getElementById("by_tel_availability_report_chart_scroll")
            let by_tel_availability_report_chart_scroll_width           =   window.getComputedStyle(by_tel_availability_report_chart_scroll).width

            if(parseFloat(by_tel_availability_report_chart_scroll_width)    >   parseFloat(by_tel_availability_report_chart_container.style.width)) {

                by_tel_availability_report_chart_container.style.width          =   by_tel_availability_report_chart_scroll_width
            }
        },

        //

        toggleChartTable() {

            if(this.show_by_tel_availability_report_chart_data ==  false) {

                this.show_by_tel_availability_report_table_data     =   false
                this.show_by_tel_availability_report_chart_data     =   true
            }

            else {

                this.show_by_tel_availability_report_chart_data     =   false
                this.show_by_tel_availability_report_table_data     =   true
            }
        }
    }
}

</script>