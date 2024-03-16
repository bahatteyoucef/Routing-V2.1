<template>

  <div class="content-wrapper" style="padding : 15px;">

    <ul class="list-group" id="reports">

      <!-- Daily Reports -->
      <li class="list-group-item p-0" id="daily_reports_div">

        <div id="daily_reports_header" class="p-3" :class="{ 'primary': show_daily_reports_content }" @click="showHideDailyReports()" role="button">
          <h4 class="mb-0">Daily Reports</h4>
        </div>

        <div v-show="show_daily_reports_content" id="daily_reports_content" class="animate__animated animate__fadeInDown">
          <DailyReports></DailyReports>
        </div>

      </li>
      <!--  -->

      <!-- Tel Availability -->
      <li class="list-group-item p-0" id="tel_availability_reports_div">

        <div id="daily_reports_header" class="p-3" :class="{ 'primary': show_tel_availability_reports_content }" @click="showHideTelAvailabilityReports()" role="button">
          <h4 class="mb-0">Tel Availability Reports</h4>
        </div>

        <div v-show="show_tel_availability_reports_content" id="tel_availability_reports_content" class="animate__animated animate__fadeInDown">
          <TelAvailabilityReports></TelAvailabilityReports>
        </div>

      </li>
      <!--  -->

    </ul>

  </div>
</template>

<script>

import DailyReports               from  "./DailyReports.vue"
import TelAvailabilityReports     from  "./TelAvailabilityReports.vue"

export default {

  data() {

    return {

      show_daily_reports_content            : false ,
      show_tel_availability_reports_content : false
    }
  },

  components : {

    DailyReports    : DailyReports      ,
    TelAvailabilityReports : TelAvailabilityReports
  },

  methods : {

    showHideDailyReports() {

      this.show_daily_reports_content             = !this.show_daily_reports_content
    },

    showHideTelAvailabilityReports() {

      this.show_tel_availability_reports_content  = !this.show_tel_availability_reports_content
    }
  }
}

</script>