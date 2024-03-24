<template>

  <div class="p-3 m-2" style="background-color: #f2edf3; padding : 15px;">

    <!-- Stats Filters  -->
    <div class="col-12 p-0 mb-3" id="stats_filters">
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
    <!--                -->

    <!-- Small Stats -->
    <div class="col-12 p-0 mb-3" id="small_stats">

      <!-- Small Report  -->
      <div class="row h-equal p-0 m-0">
        <div class="col-4 p-2">
          <div class="row">
            <div class="col p-1">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Weekly Orders <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5">45,6334</h2>
                </div>
              </div>
            </div>

            <div class="col p-1">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Weekly Orders <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5">45,6334</h2>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col p-1">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Weekly Orders <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5">45,6334</h2>
                </div>
              </div>
            </div>

            <div class="col p-1">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Weekly Orders <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5">45,6334</h2>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- v-if="show_by_brand_availability_report_content" -->
        <div class="col-8 p-2">
          <div class="card h-100">
            <div class="card-body p-0">
              <ByBrandAvailabilityReport></ByBrandAvailabilityReport>
            </div>
          </div>
        </div>

      </div>
      <!--                -->

    </div>
    <!--                -->

    <!-- Report        -->
    <div class="col-12 p-0 mb-2" id="reports">

      <!-- Small Report  -->
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body p-0">
              <div class="row stats_filters" id="stats_filters">
                
                <div class="report_div" id="by_customer_type_reports">
                    <ByCustomerTypeReport></ByCustomerTypeReport>
                </div>

              </div>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card">
            <div class="card-body p-0">
              <div class="row stats_filters" id="stats_filters">

                <div class="report_div by_customer_type_reports" id="by_customer_type_reports">

                </div>

              </div>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card">
            <div class="card-body p-0">
              <div class="row stats_filters" id="stats_filters">

                <div class="report_div by_customer_type_reports" id="by_customer_type_reports">

                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <!--                -->

    </div>
    <!--                -->

  </div>

</template>

<script>

import ByCustomerTypeReport                      from  "./parts/ByCustomerTypeReport.vue"
import ByBrandAvailabilityReport                 from  "./parts/ByBrandAvailabilityReport.vue"
import BySourceAchatReport                       from  "./parts/BySourceAchatReport.vue"

//

import Multiselect                                from  "@vueform/multiselect"

export default {

  data() {

    return {

      show_data_census_report_content                            : false  ,
      show_daily_report_content                                  : false  ,
      show_by_tel_availability_report_content                    : false  ,
      show_by_city_report_content                                : false  ,
      show_by_customer_type_report_content                       : false  ,
      show_by_source_achat_report_content                        : false  ,
      show_by_brand_availability_report_content                  : false  ,

      //

      liste_route_link    :   [],

      route_links         :   [],
      start_date          :   "",
      end_date            :   ""
    }
  },

  components : {

    BySourceAchatReport           :   BySourceAchatReport                      ,
    ByBrandAvailabilityReport     :   ByBrandAvailabilityReport                ,
    ByCustomerTypeReport          :   ByCustomerTypeReport                     ,

    //

    Multiselect                   :   Multiselect
  },

  methods : {

    showHideDataCensusReport() {

      this.show_data_census_report_content              =   !this.show_data_census_report_content
    },

    showHideDailyReport() {

      this.show_daily_report_content                    =   !this.show_daily_report_content
    },

    showHideByTelAvailabilityReport() {

      this.show_by_tel_availability_report_content      =   !this.show_by_tel_availability_report_content
    },

    showHideByCityReport() {

      this.show_by_city_report_content                  =   !this.show_by_city_report_content
    },

    showHideByCustomerTypeReport() {

      this.show_by_customer_types_report_content        =   !this.show_by_customer_types_report_content
    },

    showHideByCustomerTypeReport() {

      this.show_by_customer_type_report_content         =   !this.show_by_customer_type_report_content
    },

    showHideBySourceAchatReport() {

      this.show_by_source_achat_report_content          =   !this.show_by_source_achat_report_content
    },

    showHideByBrandAvailabilityReport() {

      this.show_by_brand_availability_report_content    =   !this.show_by_brand_availability_report_content
    }
  }
}

</script>

<style scoped>

.report_div {

  min-height : 225px
}

</style>