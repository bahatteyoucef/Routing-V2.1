<template>

    <div class="m-1">

        <!-- Title -->
        
        <div class="row">
            <div class="col-9 d-flex align-items-center">
                <h4 class="mb-0 ml-2">By City Report</h4>
            </div>

            <!-- <div class="col-3 text-end">
                <img    src="/images/switch_arrows.png" @click="toggleChartTable()"    role="button"    class="mb-0 mr-2"/>
            </div> -->
        </div> 
       

        <!-- Show Chart         -->
        <div v-show="show_by_city_report_chart_data"    class="row animate__animated animate__pulse">

            <!-- By City Reports  -->
            <div class="col-12">
                <div        id="by_city_report_chart_scroll"        class="chart_scroll pt-5 pb-1">
                    <div    id="by_city_report_chart_container"     class="bar_chart_container">
                        <canvas id="by_city_report_chart"           ref="by_city_report_chart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <!-- Show Table             -->
        <div v-show="show_by_city_report_table_data"    class="row animate__animated animate__pulse">
            <div        class="table_scroll table_scroll_x table_scroll_y table_container table_container mt-5">
                <table  class="table table-bordered w-100" id="by_city_report_table_data">
                    <thead>
                        <tr>
                            <th>Index</th>
                            <th>City</th>
                            <th>Expected</th>
                            <th>Added</th>
                            <th>Gap</th>
                            <th>Percentage</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="row, index_1 in by_city_report_table_data.rows" :key="index_1">
                            <td>{{ index_1 + 1 }}</td>
                            <td>{{ row.CityNameE }}</td>
                            <td>{{ row.expected_clients }}</td>
                            <td>{{ row.added_clients }}</td>
                            <td>{{ row.gap }}</td>
                            <td>{{ parseInt(row.percentage_clients * 100) }} %</td>
                            <td>{{ row.status_clients }}</td>
                        </tr>

                        <tr>
                            <th>{{ by_city_report_table_data.total_row.label }}</th>
                            <th></th>
                            <th>{{ by_city_report_table_data.total_row.expected_clients }}</th>
                            <th>{{ by_city_report_table_data.total_row.added_clients }}</th>
                            <th>{{ by_city_report_table_data.total_row.gap }}</th>
                            <th>{{ parseInt(by_city_report_table_data.total_row.percentage_clients * 100) }} %</th>
                            <th>{{ by_city_report_table_data.total_row.status_clients }}</th>
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

            by_city_chart               :   null,

            by_city_report_chart_options    :   {
                maintainAspectRatio             :   false,
                scales                          :   {
                    y                               :   {
                        beginAtZero: true
                    }
                }
            },

            //

            show_by_city_report_chart_data      :   false    ,
            show_by_city_report_table_data      :   true   
        }
    },

    props : ["by_city_report_chart_data", "by_city_report_table_data"],

    async mounted() {

        //
        // this.setChart();

        //
        await this.$nextTick()

        //
        this.emitter.emit('show_by_city_report_content_ready')
    },

    methods : {

        setChart() {

            if (this.by_city_report_chart) {
                this.by_city_report_chart.destroy();   // Destroy existing chart for proper updates
            }

            const by_city_report_chart      =   this.$refs.by_city_report_chart.getContext('2d');

            this.by_city_report_chart       =   new Chart(by_city_report_chart      ,   {
                type                        :   "bar"                               ,
                data                        :   this.by_city_report_chart_data      ,
                options                     :   this.by_city_report_chart_options
            });

            //Get Width
            const by_city_report_chart_container           =   document.querySelector("#by_city_report_chart_container")
            const totalLabels                       =   this.by_city_report_chart.data.labels.length
            const newWidth                          =   700 + ((totalLabels - 7) * 90)
            by_city_report_chart_container.style.width     =   newWidth+"px"

            //Get Chart Container Width
            let by_city_report_chart_scroll                =   document.getElementById("by_city_report_chart_scroll")
            let by_city_report_chart_scroll_width          =   window.getComputedStyle(by_city_report_chart_scroll).width

            if(parseFloat(by_city_report_chart_scroll_width)   >   parseFloat(by_city_report_chart_container.style.width)) {

                by_city_report_chart_container.style.width         =   by_city_report_chart_scroll_width
            }
        },

        //

        toggleChartTable() {

            if(this.show_by_city_report_chart_data      ==  false) {

                this.show_by_city_report_table_data     =   false
                this.show_by_city_report_chart_data     =   true
            }

            else {

                this.show_by_city_report_chart_data     =   false
                this.show_by_city_report_table_data     =   true
            }
        }
    }
}
</script>
