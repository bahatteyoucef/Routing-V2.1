<template>

    <div>

        <!-- Title -->
        <div class="row">
            <div class="col-9 d-flex align-items-center">
                <h5 class="mb-0 ml-2">By Customer Type Report</h5>
            </div>

            <div class="col-3 p-0 text-end">
                <img v-if="true"    src="/images/slide_down.png" @click="setChart()"    role="button"/>

                <img v-if="false"   src="/images/slide_up.png" @click="setChart()"      role="button"/>
            </div>
        </div>

        <!-- Show Chart         -->
        <div class="row">

            <!-- By CustomerType  -->
            <div class="col-12">

                <div        id="by_customer_type_report_chart_scroll"       class="chart_scroll pt-5 pb-1">
                    <div    id="by_customer_type_report_chart_container"    class="pie_chart_container">
                        <canvas id="by_customer_type_report_chart"          ref="by_customer_type_report_chart"></canvas>
                    </div>
                </div>
            
            </div>

        </div>
        <!--  -->

    </div>

</template>

<script>

export default {

    data() {
        return {

            by_customer_type_report_chart              :   null,
        }
    },

    props : ["by_customer_type_report_chart_data"],

    async mounted() {

        //
        await this.$nextTick()

        //
        this.setChart();
    },

    methods : {

        setChart() {

            if (this.by_customer_type_report_chart) {
                this.by_customer_type_report_chart.destroy();   // Destroy existing chart for proper updates
            }

            const by_customer_type_report_chart     =   this.$refs.by_customer_type_report_chart.getContext('2d');

            this.by_customer_type_report_chart      =   new Chart(by_customer_type_report_chart, {
                type                                    :   "doughnut"                                  ,
                data                                    :   this.by_customer_type_report_chart_data     ,
                options                                 :   {}
            });
        }
    }
}

</script>
