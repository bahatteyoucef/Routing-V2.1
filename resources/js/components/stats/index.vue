<template>

  <div class="p-3 m-2" style="background-color: #f2edf3; padding : 15px;">

    <!-- Stats Filters    -->
    <div class="col-12 p-2" id="stats_filters">
        <div class="card">
            <div class="card-body p-0">
              <div class="row">

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
                <div class="col-2 mt-1">
                    <input type="date" class="form-control" v-model="start_date"/>
                </div>
                <!--  -->

                <!-- Select Date Range  -->
                <div class="col-2 mt-1">
                    <input type="date" class="form-control" v-model="end_date"/>
                </div>
                <!--  -->

                <!-- Select Date Range  -->
                <div class="col-2 mt-1">
                    <button class="btn primary w-100"   @click="getData()">Get Data</button>
                </div>
                <!--  -->

                <!-- Select Date Range  -->
                <div class="col-2 mt-1">
                    <button class="btn primary w-100"   @click="getData()">Export Data</button>
                </div>
                <!--  -->

              </div>
            </div>
        </div>
    </div>
    <!--                  -->

    <!-- Reports          -->
    <div class="col-12 p-0 mb-2" id="reports">

      <!-- Card Stats     -->
      <div class="row h-equal p-0 pl-2 m-0">

        <!-- Card : Validated + Not Validated -->
        <div class="col-3 p-1">
          <div class="card bg-gradient-info card-img-holder text-white p-3">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1">45,6334</h2>
            </div>
          </div>
        </div>

        <div class="col-3 p-1">
          <div class="card bg-gradient-info card-img-holder text-white p-3">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Non Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1">45,6334</h2>
            </div>
          </div>
        </div>

        <div class="col-3 p-1">
          <div class="card bg-gradient-success card-img-holder text-white p-3">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Total <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1">45,6334</h2>
            </div>
          </div>
        </div>

        <div class="col-3 p-1 h-100">
          <div class="card bg-gradient-success card-img-holder text-white p-3">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Expected <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1">45,6334</h2>
            </div>
          </div>
        </div>

      </div>
      <!--                -->

      <!-- ByCustomerTypeReport + ByBrandSourcePurchaseReport + ByBrandAvailabilityReport -->
      <div class="row h-equal p-0 m-0">

        <!--  ByCustomerTypeReport  -->
        <div class="col-4 p-2">
          <div class="card">
            <div class="card-body p-0">                
              <div class="report_div" id="by_customer_type_report">
                  <ByCustomerTypeReport v-if="show_by_customer_type_report_content"   :key="by_customer_type_report_chart_data"   :by_customer_type_report_chart_data="by_customer_type_report_chart_data"></ByCustomerTypeReport>
              </div>
            </div>
          </div>
        </div>

        <!--  ByBrandSourcePurchaseReport -->
        <div class="col-4 p-2">
          <div class="card">
            <div class="card-body p-0">
              <div class="row">

                <div class="report_div by_source_achat_report" id="by_source_achat_report">
                    <ByBrandSourcePurchaseReport v-if="show_by_brand_source_purchase_report_content"  :key="by_brand_source_purchase_report_chart_data" :by_brand_source_purchase_report_chart_data="by_brand_source_purchase_report_chart_data"></ByBrandSourcePurchaseReport>
                </div>

              </div>
            </div>
          </div>
        </div>

        <!--  ByBrandAvailabilityReport -->
        <div class="col-4 p-2">
          <div class="card">
            <div class="card-body p-0">
              <div class="row">

                <div class="report_div by_brand_availability_report" id="by_brand_availability_report">
                    <ByBrandAvailabilityReport v-if="show_by_brand_availability_report_content"   :key="by_brand_availability_report_chart_data"  :by_brand_availability_report_chart_data="by_brand_availability_report_chart_data"></ByBrandAvailabilityReport>
                </div>

              </div>
            </div>
          </div>
        </div>

      </div>
      <!--                                                                                -->

      <!-- DailyReport    -->
      <div class="row h-equal p-0 m-0">

        <!--  DailyReport  -->
        <div class="col-12 p-2">
          <div class="card">
            <div class="card-body p-0">                
              <div class="report_div" id="daily_report">
                <DailyReport v-if="show_daily_report_content"   :key="daily_report_chart_data"  :daily_report_chart_data="daily_report_chart_data"></DailyReport>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!--                -->

      <!-- ByTelAvailability    -->
      <div class="row h-equal p-0 m-0">

        <!--  ByTelAvailabilityReport -->
        <div class="col-12 p-2">
          <div class="card">
            <div class="card-body p-0">
              <div class="report_div" id="by_tel_availability_report">
                  <ByTelAvailabilityReport v-if="show_by_tel_availability_report_content"   :key="by_tel_availability_report_chart_data"    :by_tel_availability_report_chart_data="by_tel_availability_report_chart_data"></ByTelAvailabilityReport>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!--                      -->

      <!-- ByCityReport         -->
      <div class="row h-equal p-0 m-0">

        <!--  ByCityReport  -->
        <div class="col-12 p-2">
          <div class="card">
            <div class="card-body p-0">                
              <div class="report_div" id="by_city_report">
                  <ByCityReport v-if="show_by_city_report_content"  :key="by_city_report_chart_data"  :by_city_report_chart_data="by_city_report_chart_data"></ByCityReport>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!--                      -->

      <!-- DataCensusReport -->
      <div class="row h-equal p-0 m-0">

        <!--  DataCensusReport  -->
        <div class="col-12 p-2">
          <div class="card">
            <div class="card-body p-0">                
              <div class="report_div" id="data_census_report">
                  <DataCensusReport v-if="show_data_census_report_content"  :key="data_census_report_table_data"  :data_census_report_table_data="data_census_report_table_data"></DataCensusReport>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!--                  -->

    </div>
    <!--                  -->

  </div>

</template>

<script>

import ByCustomerTypeReport               from  "./parts/ByCustomerTypeReport.vue"
import ByBrandSourcePurchaseReport        from  "./parts/ByBrandSourcePurchaseReport.vue"

import DailyReport                        from  "./parts/DailyReport.vue"
import ByTelAvailabilityReport            from  "./parts/ByTelAvailabilityReport.vue"
import ByCityReport                       from  "./parts/ByCityReport.vue"
import ByBrandAvailabilityReport          from  "./parts/ByBrandAvailabilityReport.vue"
import DataCensusReport                   from  "./parts/DataCensusReport.vue"

//
import Multiselect                        from  "@vueform/multiselect"

export default {

  data() {

    return {

      show_by_customer_type_report_content                      : false   ,
      by_customer_type_report_chart_data                        : null    ,

      //

      show_by_brand_source_purchase_report_content              : false   ,
      by_brand_source_purchase_report_chart_data                : null    ,

      //

      show_daily_report_content                                 : false   ,
      daily_report_chart_data                                   : null    ,

      //

      show_by_tel_availability_report_content                   : false   ,
      by_tel_availability_report_chart_data                     : null    ,

      //

      show_by_city_report_content                               : false   ,
      by_city_report_chart_data                                 : null    ,

      //

      show_data_census_report_content                           : false   ,
      data_census_report_table_data                             : null    ,

      //

      show_by_source_achat_report_content                       : false   ,
      show_by_brand_availability_report_content                 : false   ,

      //

      liste_route_link    :   [],

      route_links         :   [],
      start_date          :   "",
      end_date            :   ""
    }
  },

  async mounted() {

    await this.fetchMaps()
  },

  components : {

    ByBrandAvailabilityReport     :   ByBrandAvailabilityReport   ,
    ByCityReport                  :   ByCityReport                ,
    ByCustomerTypeReport          :   ByCustomerTypeReport        ,
    ByBrandSourcePurchaseReport        :   ByBrandSourcePurchaseReport      ,
    ByTelAvailabilityReport       :   ByTelAvailabilityReport     ,
    DailyReport                   :   DailyReport                 ,
    DataCensusReport              :   DataCensusReport            ,

    //

    Multiselect                   :   Multiselect
  },

  methods : {

    async getData() {

      try {

        if((this.start_date  !=  "")&&(this.end_date  !=  "")) {

            this.$showLoadingPage()

            //

            let formData    =   new FormData()

            formData.append("route_links"   , JSON.stringify(this.route_links))
            formData.append("start_date"    , this.start_date)
            formData.append("end_date"      , this.end_date)

            await this.$callApi("post",   "/statistics/details",    formData)
            .then(async (res)=> {

                console.log(res)

                //
                this.by_customer_type_report_chart_data           =   res.data.stats_details.by_customer_type_report_chart_data
                this.show_by_customer_type_report_content         =   true

                //
                this.by_brand_source_purchase_report_chart_data   =   res.data.stats_details.by_brand_source_purchase_report_chart_data
                this.show_by_brand_source_purchase_report_content =   true

                //
                this.by_brand_availability_report_chart_data      =   res.data.stats_details.by_brand_availability_report_chart_data
                this.show_by_brand_availability_report_content    =   true

                //
                this.daily_report_chart_data                      =   res.data.stats_details.daily_report_chart_data
                this.show_daily_report_content                    =   true

                //
                this.by_tel_availability_report_chart_data        =   res.data.stats_details.by_tel_availability_report_chart_data
                this.show_by_tel_availability_report_content      =   true

                //
                this.by_city_report_chart_data                    =   res.data.stats_details.by_city_report_chart_data
                this.show_by_city_report_content                  =   true

                //
                this.data_census_report_table_data                =   res.data.stats_details.data_census_report_table_data
                this.show_data_census_report_content              =   true
                
                //
                this.$hideLoadingPage()
            })
        }
      }

      catch(e) {

        console.log(e)
      }
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

.report_div {

  min-height : 225px
}

</style>