<template>

  <div class="content-wrapper" style="padding : 15px;">

    <div class="mt-4 mb-4 text-center">
      <h3><b>Reports Index</b></h3>
    </div>

    <ul class="list-group" id="reports">

      <li class="list-group-item p-0" id="daily_reports_div">

        <div id="daily_reports_header" class="p-3" :class="{ 'primary': show_daily_reports_content }" @click="showHideDailyReports()" role="button">
          <h4 class="mb-0">Daily Reports</h4>
        </div>

        <div v-show="show_daily_reports_content" id="daily_reports_content" class="animate__animated animate__fadeInDown">
          <DailyReports></DailyReports>
        </div>

      </li>

    </ul>

  </div>
</template>

<script>

import DailyReports from "./DailyReports.vue"

export default {

  data() {

    return {

      show_daily_reports_content    :   false ,
    }
  },

  components : {

    DailyReports  : DailyReports
  },

  methods : {

    showHideDailyReports() {

      this.show_daily_reports_content = !this.show_daily_reports_content
    }
  }
}

</script>