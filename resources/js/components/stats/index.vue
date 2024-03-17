<template>

  <div class="content-wrapper" style="padding : 15px;">

    <ul class="list-group" id="reports">

      <!-- Daily Reports            -->
      <li class="list-group-item p-0" id="daily_reports_div">

        <div id="daily_reports_header" class="p-3" :class="{ 'primary': show_daily_reports_content }" @click="showHideDailyReports()" role="button">
          <h4 class="mb-0">Daily Reports</h4>
        </div>

        <div v-show="show_daily_reports_content" id="daily_reports_content" class="reports_li animate__animated animate__fadeInDown">
          <DailyReports></DailyReports>
        </div>

      </li>
      <!--  -->

      <!-- BY Tel Availability      -->
      <li class="list-group-item p-0" id="by_tel_availability_reports_div">

        <div id="by_tel_availability_reports_header" class="p-3" :class="{ 'primary': show_by_tel_availability_reports_content }" @click="showHideByTelAvailabilityReports()" role="button">
          <h4 class="mb-0">By Tel Availability Reports</h4>
        </div>

        <div v-show="show_by_tel_availability_reports_content" id="by_tel_availability_reports_content" class="reports_li animate__animated animate__fadeInDown">
          <ByTelAvailabilityReports></ByTelAvailabilityReports>
        </div>

      </li>
      <!--  -->

      <!-- By CustomerType Reports  -->
      <li class="list-group-item p-0" id="by_customer_type_reports_div">

        <div id="by_customer_type_reports_header" class="p-3" :class="{ 'primary': show_by_customer_type_reports_content }" @click="showHideByCustomerTypeReports()" role="button">
          <h4 class="mb-0">By Customer Type Reports</h4>
        </div>

        <div v-show="show_by_customer_type_reports_content" id="by_customer_type_reports_content" class="reports_li animate__animated animate__fadeInDown">
          <ByCustomerTypeReports></ByCustomerTypeReports>
        </div>

      </li>
      <!--  -->

      <!-- By City Reports          -->
      <li class="list-group-item p-0" id="by_city_reports_div">

        <div id="by_city_reports_header" class="p-3" :class="{ 'primary': show_by_city_reports_content }" @click="showHideByCityReports()" role="button">
          <h4 class="mb-0">By City Reports</h4>
        </div>

        <div v-show="show_by_city_reports_content" id="by_city_reports_content" class="reports_li animate__animated animate__fadeInDown">
          <ByCityReports></ByCityReports>
        </div>

      </li>
      <!--  -->

      <!-- By Source Achat Reports  -->
      <li class="list-group-item p-0" id="by_source_achat_reports_div">

        <div id="by_source_achat_reports_header" class="p-3" :class="{ 'primary': show_by_source_achat_reports_content }" @click="showHideBySourceAchatReports()" role="button">
          <h4 class="mb-0">By Source Achat Reports</h4>
        </div>

        <div v-show="show_by_source_achat_reports_content" id="by_source_achat_reports_content" class="reports_li animate__animated animate__fadeInDown">
          <BySourceAchatReports></BySourceAchatReports>
        </div>

      </li>
      <!--  -->

      <!-- By Brand Availability Reports  -->
      <li class="list-group-item p-0" id="by_brand_availability_reports_div">

        <div id="by_brand_availability_reports_header" class="p-3" :class="{ 'primary': show_by_brand_availability_reports_content }" @click="showHideByBrandAvailabilityReports()" role="button">
          <h4 class="mb-0">By Brand Availability Reports</h4>
        </div>

        <div v-show="show_by_brand_availability_reports_content" id="by_brand_availability_reports_content" class="reports_li animate__animated animate__fadeInDown">
          <ByBrandAvailabilityReports></ByBrandAvailabilityReports>
        </div>

      </li>
      <!--  -->

    </ul>

  </div>
</template>

<script>

import DailyReports                   from  "./DailyReports.vue"
import ByTelAvailabilityReports       from  "./ByTelAvailabilityReports.vue"
import ByCityReports                  from  "./ByCityReports.vue"
import ByCustomerTypeReports          from  "./ByCustomerTypeReports.vue"
import BySourceAchatReports           from  "./BySourceAchatReports.vue"
import ByBrandAvailabilityReports     from  "./ByBrandAvailabilityReports.vue"

export default {

  data() {

    return {

      show_daily_reports_content                  : false ,
      show_by_tel_availability_reports_content    : false ,
      show_by_city_reports_content                : false ,
      show_by_customer_type_reports_content       : false ,
      show_by_source_achat_reports_content        : false ,
      show_by_brand_availability_reports_content  : false
    }
  },

  components : {

    DailyReports                : DailyReports                ,
    ByTelAvailabilityReports    : ByTelAvailabilityReports    ,
    ByCityReports               : ByCityReports               ,
    ByCustomerTypeReports       : ByCustomerTypeReports       ,
    BySourceAchatReports        : BySourceAchatReports        ,
    ByBrandAvailabilityReports  : ByBrandAvailabilityReports
  },

  methods : {

    showHideDailyReports() {

      this.show_daily_reports_content             = !this.show_daily_reports_content
    },

    showHideByTelAvailabilityReports() {

      this.show_by_tel_availability_reports_content  = !this.show_by_tel_availability_reports_content
    },

    showHideByCityReports() {

      this.show_by_city_reports_content = !this.show_by_city_reports_content
    },

    showHideByCustomerTypeReports() {

      this.show_by_customer_types_report_content = !this.show_by_customer_types_report_content
    },

    showHideByCustomerTypeReports() {

      this.show_by_customer_type_reports_content  = !this.show_by_customer_type_reports_content
    },

    showHideBySourceAchatReports() {

      this.show_by_source_achat_reports_content   =   !this.show_by_source_achat_reports_content
    },

    showHideByBrandAvailabilityReports() {

      this.show_by_brand_availability_reports_content   =   !this.show_by_brand_availability_reports_content
    }
  }
}

</script>

<style scoped>

.reports_li {

  min-height : 225px
}

</style>