<template>

    <div class="m-1">

        <!-- Title -->
        <div class="row">
            <div class="col-9 d-flex align-items-center">
                <h4 class="mb-0 ml-2">By City Report</h4>
            </div>

            <div class="col-3 text-end">
                <img    src="/images/switch_arrows.png" @click="setChart()"    role="button"    class="mb-0 mr-2"/>
            </div>
        </div>

        <!-- Show Chart         -->
        <div class="row">

            <!-- By City Reports  -->
            <div class="col-12">
                <div        id="by_city_report_chart_scroll"        class="chart_scroll pt-5 pb-1">
                    <div    id="by_city_report_chart_container"     class="bar_chart_container">
                        <canvas id="by_city_report_chart"           ref="by_city_report_chart"></canvas>
                    </div>
                </div>
            </div>

        </div>

    </div>

</template>

<script>

export default {

    data() {
        return {

            by_city_chart               :   null,

            by_city_report_chart_options     :   {
                maintainAspectRatio         :   false,
                scales                      :   {
                    y                           :   {
                        beginAtZero: true
                    }
                }
            },
        }
    },

    props : ["by_city_report_chart_data"],

    async mounted() {

        //
        await this.$nextTick()

        //
        this.setChart();
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
    }
}
</script>
