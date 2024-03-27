<template>

  <div class="p-3 m-2 h-100" style="background-color: #f2edf3; padding : 15px;">

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

                <!-- Get Range      -->
                <div class="col-2 mt-1">
                    <button v-if="show_get_data_button"     class="btn primary w-100"   @click="getData()">Get Data</button>
                </div>
                <!--  -->

                <!-- Export Range   -->
                <div class="col-2 mt-1">
                    <button v-if="show_export_data_button"  class="btn primary w-100"   @click="exportData()">Export Data</button>
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

        <!-- Card : Validated + Pending + Not Validated -->
        <div class="col p-1">
          <div class="card h-100 bg-gradient-success card-img-holder text-white p-3" v-if="number_clients_validated">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_validated}}</h2>
            </div>
          </div>
        </div>

        <div class="col p-1">
          <div class="card h-100 bg-gradient-warning card-img-holder text-white p-3" v-if="number_clients_pending">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Pending <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_pending}}</h2>
            </div>
          </div>
        </div>

        <div class="col p-1">
          <div class="card h-100 bg-gradient-danger card-img-holder text-white p-3" v-if="number_clients_nonvalidated">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Non Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_nonvalidated}}</h2>
            </div>
          </div>
        </div>

        <div class="col p-1">
          <div class="card h-100 bg-gradient-info card-img-holder text-white p-3" v-if="number_clients_total">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Total <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_total}}</h2>
            </div>
          </div>
        </div>

        <div class="col p-1 h-100">
          <div class="card h-100 bg-gradient-info card-img-holder text-white p-3" v-if="number_clients_expected">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Expected <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_expected}}</h2>
            </div>
          </div>
        </div>

      </div>
      <!--                -->

      <!-- ByCustomerTypeReport + ByBrandSourcePurchaseReport + ByBrandAvailabilityReport -->
      <div class="row h-equal p-0 m-0">

        <!--  ByCustomerTypeReport  -->
        <div class="col-4 p-2">
          <div class="card h-100" v-if="show_by_customer_type_report_content">
            <div class="card-body p-0">                
              <div class="report_div" id="by_customer_type_report">
                  <ByCustomerTypeReport refs="ByCustomerTypeReport" :key="by_customer_type_report_chart_data"   :by_customer_type_report_chart_data="by_customer_type_report_chart_data"  :by_customer_type_report_table_data="by_customer_type_report_table_data"></ByCustomerTypeReport>
              </div>
            </div>
          </div>
        </div>

        <!--  ByBrandSourcePurchaseReport -->
        <div class="col-4 p-2">
          <div class="card h-100" v-if="show_by_brand_source_purchase_report_content">
            <div class="card-body p-0">
              <div class="row">

                <div class="report_div by_source_achat_report" id="by_source_achat_report">
                    <ByBrandSourcePurchaseReport :key="by_brand_source_purchase_report_chart_data" :by_brand_source_purchase_report_chart_data="by_brand_source_purchase_report_chart_data"  :by_brand_source_purchase_report_table_data="by_brand_source_purchase_report_table_data"></ByBrandSourcePurchaseReport>
                </div>

              </div>
            </div>
          </div>
        </div>

        <!--  ByBrandAvailabilityReport -->
        <div class="col-4 p-2">
          <div class="card h-100" v-if="show_by_brand_availability_report_content">
            <div class="card-body p-0">
              <div class="row">

                <div class="report_div by_brand_availability_report" id="by_brand_availability_report">
                    <ByBrandAvailabilityReport  :key="by_brand_availability_report_chart_data"  :by_brand_availability_report_chart_data="by_brand_availability_report_chart_data"  :by_brand_availability_report_table_data="by_brand_availability_report_table_data"></ByBrandAvailabilityReport>
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
          <div class="card h-100" v-if="show_daily_report_content">
            <div class="card-body p-0">                
              <div class="report_div" id="daily_report">
                <DailyReport  :key="daily_report_chart_data"  :daily_report_chart_data="daily_report_chart_data"  :daily_report_table_data="daily_report_table_data"></DailyReport>
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
          <div class="card h-100" v-if="show_by_tel_availability_report_content">
            <div class="card-body p-0">
              <div class="report_div" id="by_tel_availability_report">
                  <ByTelAvailabilityReport  :key="by_tel_availability_report_chart_data"    :by_tel_availability_report_chart_data="by_tel_availability_report_chart_data"  :by_tel_availability_report_table_data="by_tel_availability_report_table_data"></ByTelAvailabilityReport>
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
          <div class="card h-100" v-if="show_by_city_report_content">
            <div class="card-body p-0">                
              <div class="report_div" id="by_city_report">
                  <ByCityReport :key="by_city_report_chart_data"  :by_city_report_table_data="by_city_report_table_data"></ByCityReport>
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
          <div class="card h-100" v-if="show_data_census_report_content">
            <div class="card-body p-0">                
              <div class="report_div" id="data_census_report">
                  <DataCensusReport :key="data_census_report_table_data"  :data_census_report_table_data="data_census_report_table_data"></DataCensusReport>
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
import ByBrandAvailabilityReport          from  "./parts/ByBrandAvailabilityReport.vue"

import DailyReport                        from  "./parts/DailyReport.vue"
import ByTelAvailabilityReport            from  "./parts/ByTelAvailabilityReport.vue"
import ByCityReport                       from  "./parts/ByCityReport.vue"
import DataCensusReport                   from  "./parts/DataCensusReport.vue"

//

import Multiselect                        from  "@vueform/multiselect"

export default {

  data() {

    return {

      show_get_data_button                                      : true    ,
      show_export_data_button                                   : false   ,

      //

      number_clients_validated                                  : null    ,
      number_clients_pending                                    : null    ,
      number_clients_nonvalidated                               : null    ,
      number_clients_total                                      : null    ,
      number_clients_expected                                   : null    ,

      //

      show_by_customer_type_report_content                      : false   ,
      by_customer_type_report_chart_data                        : null    ,
      by_customer_type_report_table_data                        : null    ,

      by_customer_type_report_chart                             : null    ,

      //

      show_by_brand_source_purchase_report_content              : false   ,
      by_brand_source_purchase_report_chart_data                : null    ,
      by_brand_source_purchase_report_table_data                : null    ,

      //

      show_by_brand_availability_report_content                 : false   ,
      by_brand_availability_report_chart_data                   : null    ,
      by_brand_availability_report_table_data                   : null    ,

      //

      show_daily_report_content                                 : false   ,
      daily_report_chart_data                                   : null    ,
      daily_report_table_data                                   : null    ,

      //

      show_by_tel_availability_report_content                   : false   ,
      by_tel_availability_report_chart_data                     : null    ,
      by_tel_availability_report_table_data                     : null    ,

      //

      show_by_city_report_content                               : false   ,
      by_city_report_chart_data                                 : null    ,
      by_city_report_table_data                                 : null    ,

      //

      show_data_census_report_content                           : false   ,
      data_census_report_table_data                             : null    ,

      //

      liste_route_link    :   [],

      route_links         :   [],
      start_date          :   "",
      end_date            :   "",

      //

      workbook            :   null
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

            this.show_get_data_button     =   false
            this.show_export_data_button  =   false

            //

            let formData    =   new FormData()

            formData.append("route_links"   , JSON.stringify(this.route_links))
            formData.append("start_date"    , this.start_date)
            formData.append("end_date"      , this.end_date)

            await this.$callApi("post",   "/statistics/details",    formData)
            .then(async (res)=> {

                console.log(res)

                //
                this.$hideLoadingPage()

                //
                this.number_clients_validated                     =   res.data.stats_details.number_clients_validated
                this.number_clients_pending                       =   res.data.stats_details.number_clients_pending  
                this.number_clients_nonvalidated                  =   res.data.stats_details.number_clients_nonvalidated
                this.number_clients_total                         =   res.data.stats_details.number_clients_total
                this.number_clients_expected                      =   res.data.stats_details.number_clients_expected

                //  //  //  //  //  //  //  //  //  //  //  //  //

                //
                this.by_customer_type_report_chart_data           =   res.data.stats_details.by_customer_type_report_chart_data
                this.by_customer_type_report_table_data           =   res.data.stats_details.by_customer_type_report_table_data

                this.show_by_customer_type_report_content         =   true

                this.emitter.on('show_by_customer_type_report_content_ready' , (by_customer_type_report_chart) =>  {

                    //
                    this.by_brand_source_purchase_report_chart_data   =   res.data.stats_details.by_brand_source_purchase_report_chart_data
                    this.by_brand_source_purchase_report_table_data   =   res.data.stats_details.by_brand_source_purchase_report_table_data

                    this.show_by_brand_source_purchase_report_content =   true

                    this.emitter.on('show_by_brand_source_purchase_report_content_ready' , () =>  {

                        //
                        this.by_brand_availability_report_chart_data      =   res.data.stats_details.by_brand_availability_report_chart_data
                        this.by_brand_availability_report_table_data      =   res.data.stats_details.by_brand_availability_report_table_data

                        this.show_by_brand_availability_report_content    =   true

                        this.emitter.on('show_by_brand_availability_report_content_ready' , () =>  {

                            //
                            this.daily_report_chart_data                      =   res.data.stats_details.daily_report_chart_data
                            this.daily_report_table_data                      =   res.data.stats_details.daily_report_table_data

                            this.show_daily_report_content                    =   true

                            this.emitter.on('show_daily_report_content_ready' , () =>  {

                                //
                                this.by_tel_availability_report_chart_data        =   res.data.stats_details.by_tel_availability_report_chart_data
                                this.by_tel_availability_report_table_data        =   res.data.stats_details.by_tel_availability_report_table_data

                                this.show_by_tel_availability_report_content      =   true

                                this.emitter.on('show_by_tel_availability_report_content_ready' , () =>  {

                                    //
                                    // this.by_city_report_chart_data                    =   res.data.stats_details.by_city_report_chart_data
                                    this.by_city_report_table_data                    =   res.data.stats_details.by_city_report_table_data

                                    this.show_by_city_report_content                  =   true

                                    this.emitter.on('show_by_city_report_content_ready' , () =>  {

                                        //
                                        this.data_census_report_table_data                =   res.data.stats_details.data_census_report_table_data

                                        this.show_data_census_report_content              =   true

                                        this.emitter.on('show_data_census_report_content_ready' , () =>  {

                                            this.show_get_data_button     =   true
                                            this.show_export_data_button  =   true
                                        })
                                    })
                                })
                            })
                        })
                    })
                })
            })
        }
      }

      catch(e) {

        console.log(e)
      }
    },

    //

    async exportData() {

        // Create a workbook
        this.workbook = new ExcelJS.Workbook();

        // File name
        let filename = "Student_Report.xlsx";

        //
        // this.exportByCustomerTypeReport()
        // this.exportByBrandSourcePurchaseReport()
        // this.exportByBrandAvailabilityReport()
        // this.exportDailyReport()
        this.exportByTelAvailabilityReport()
        // this.exportByCityReport()
        // this.exportDataCensusReport()

        // Write workbook to buffer then convert to Excel file and download
        this.workbook.xlsx.writeBuffer().then(data => {
          const file = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8' });
          saveAs(file, filename);
        });
    },

    //

    exportByCustomerTypeReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('By Customer Type Report');

        console.log(this.by_customer_type_report_table_data)

        //

        worksheet.columns =   this.getByCustomerTypeReportColumns()

        //

        let rows          =   this.getByCustomerTypeReportRows()

        worksheet.addRows(rows)

        //

        // Determine the last row of the table
        const lastRow = worksheet.lastRow.number;

        //
        const canvas = document.getElementById("by_customer_type_report_chart")
        const ctx = canvas.getContext('2d');
        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        //
        const imgId = this.workbook.addImage({
          base64    : this.imageDataToBase64(imgData),
          extension : 'png',
        });

        // Add the image to the worksheet
        worksheet.addImage(imgId, {
          tl  : { col: 1, row: lastRow + 2 },
          ext : { width: canvas.width, height: canvas.height },
        });
    },

    getByCustomerTypeReportRows() {

      return this.data_census_report_table_data.rows
    },

    getByCustomerTypeReportColumns() {

        let columns =   []

        if(array.length > 0) {

            const keys  =   Object.keys(array[0])
            console.log(array)
            console.log(keys)

            for (let index = 0; index < keys.length; index++) {

                columns.push({header: keys[index], key: keys[index], width: 10})
            }
        }

        return columns
    },

    //

    exportByBrandSourcePurchaseReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('By Brand Source Purchase Report');

        console.log(this.by_brand_source_purchase_report_table_data)

        //

        worksheet.columns =   this.getByBrandSourcePurchaseReportColumns()

        //

        let rows          =   this.getByBrandSourcePurchaseReportRows()

        worksheet.addRows(rows)

        //

        // Determine the last row of the table
        const lastRow = worksheet.lastRow.number;

        //
        const canvas = document.getElementById("by_brand_source_purchase_report_chart")
        const ctx = canvas.getContext('2d');
        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        //
        const imgId = this.workbook.addImage({
          base64    : this.imageDataToBase64(imgData),
          extension : 'png',
        });

        // Add the image to the worksheet
        worksheet.addImage(imgId, {
          tl  : { col: 1, row: lastRow + 2 },
          ext : { width: canvas.width, height: canvas.height },
        });
    },

    getByBrandSourcePurchaseReportRows() {

      return this.data_census_report_table_data.rows
    },

    getByBrandSourcePurchaseReportColumns() {

        let columns =   []

        if(array.length > 0) {

            const keys  =   Object.keys(array[0])
            console.log(array)
            console.log(keys)

            for (let index = 0; index < keys.length; index++) {

                columns.push({header: keys[index], key: keys[index], width: 10})
            }
        }

        return columns
    },

    //

    exportByBrandAvailabilityReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('By Brand Availability Report');

        console.log(this.by_brand_availability_report_table_data)

        //

        worksheet.columns =   this.getByBrandAvailabilityReportColumns()

        //

        let rows          =   this.getByBrandAvailabilityReportRows()

        worksheet.addRows(rows)

        //

        // Determine the last row of the table
        const lastRow = worksheet.lastRow.number;

        //
        const canvas = document.getElementById("by_brand_availability_report_chart")
        const ctx = canvas.getContext('2d');
        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        //
        const imgId = this.workbook.addImage({
          base64    : this.imageDataToBase64(imgData),
          extension : 'png',
        });

        // Add the image to the worksheet
        worksheet.addImage(imgId, {
          tl  : { col: 1, row: lastRow + 2 },
          ext : { width: canvas.width, height: canvas.height },
        });
    },

    getByBrandAvailabilityReportRows() {

      return this.data_census_report_table_data.rows
    },

    getByBrandAvailabilityReportColumns() {

        let columns =   []

        if(array.length > 0) {

            const keys  =   Object.keys(array[0])
            console.log(array)
            console.log(keys)

            for (let index = 0; index < keys.length; index++) {

                columns.push({header: keys[index], key: keys[index], width: 10})
            }
        }

        return columns
    },

    //

    exportDailyReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('Daily Report');

        console.log(this.daily_report_table_data)

        //

        worksheet.columns =   this.getDailyReportColumns()

        //

        let rows          =   this.getDailyReportRows()

        worksheet.addRows(rows)

        //

        // Determine the last row of the table
        const lastRow = worksheet.lastRow.number;

        //
        const canvas  = document.getElementById("daily_report_chart")
        const ctx     = canvas.getContext('2d');
        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        //
        const imgId   = this.workbook.addImage({
          base64      : this.imageDataToBase64(imgData),
          extension   : 'png',
        });

        // Add the image to the worksheet
        worksheet.addImage(imgId, {
          tl  : { col: 1, row: lastRow + 2 },
          ext : { width: canvas.width, height: canvas.height },
        });
    },

    getDailyReportRows() {

      return this.data_census_report_table_data.rows
    },

    getDailyReportColumns() {

        let columns =   []

        if(array.length > 0) {

            const keys  =   Object.keys(array[0])
            console.log(array)
            console.log(keys)

            for (let index = 0; index < keys.length; index++) {

                columns.push({header: keys[index], key: keys[index], width: 10})
            }
        }

        return columns
    },

    //

    exportByTelAvailabilityReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('By Tel Availability Report');

        //

        worksheet.columns =   this.getByTelAvailabilityReportColumns()

        //

        let rows          =   this.getByTelAvailabilityReportRows()

        worksheet.addRows(rows)

        //

        // Determine the last row of the table
        const lastRow = worksheet.lastRow.number;

        //
        const canvas = document.getElementById("by_tel_availability_report_chart")
        const ctx = canvas.getContext('2d');
        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        //
        const imgId = this.workbook.addImage({
          base64    : this.imageDataToBase64(imgData),
          extension : 'png',
        });

        // Add the image to the worksheet
        worksheet.addImage(imgId, {
          tl  : { col: 1, row: lastRow + 2 },
          ext : { width: canvas.width, height: canvas.height },
        });
    },

    getByTelAvailabilityReportRows() {

      let obj = { "label"           : this.by_tel_availability_report_table_data.total_row.label        , 
                  "count_yes"       : this.by_tel_availability_report_table_data.total_row.count_yes    ,
                  "count_no"        : this.by_tel_availability_report_table_data.total_row.count_no     ,
                  "count_total"     : this.by_tel_availability_report_table_data.total_row.count_total
                }

      this.by_tel_availability_report_table_data.rows.push(obj)

      console.log(this.by_tel_availability_report_table_data.rows)

      return this.by_tel_availability_report_table_data.rows
    },

    getByTelAvailabilityReportColumns() {

        let columns =   []

        if(this.by_tel_availability_report_table_data.rows.length > 0) {

            const keys  =   Object.keys(this.by_tel_availability_report_table_data.rows[0])
            console.log(this.by_tel_availability_report_table_data)
            console.log(keys)

            for (let index = 0; index < keys.length; index++) {

                columns.push({header: keys[index], key: keys[index], width: 10})
            }
        }

        return columns
    },

    //

    exportByCityReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('By City Report');

        console.log(this.by_city_report_table_data)

        //

        worksheet.columns =   this.getByCityReportColumns()

        //

        let rows          =   this.getByCityReportRows()

        worksheet.addRows(rows)
    },

    getByCityReportRows() {

      let obj = { "label"               : this.by_city_report_table_data.total_row.label                                              , 
                  "empty"               : ""                                                                                          ,
                  "expected_clients"    : this.by_city_report_table_data.total_row.expected_clients                                   , 
                  "added_clients"       : this.by_city_report_table_data.total_row.added_clients                                      ,
                  "gap"                 : this.by_city_report_table_data.total_row.gap                                                ,
                  "percentage_clients"  : parseInt(this.by_city_report_table_data.total_row.percentage_clients * 100) + "%" ,
                  "status_clients"      : this.by_city_report_table_data.total_row.status_clients
                }

      this.by_city_report_table_data.rows.push(obj)

      return this.by_city_report_table_data.rows
    },

    getByCityReportColumns() {

        let columns =   []

        if(this.by_city_report_table_data.rows.length > 0) {

            const keys  =   Object.keys(this.by_city_report_table_data.rows[0])
            console.log(this.by_city_report_table_data)
            console.log(keys)

            for (let index = 0; index < keys.length; index++) {

                columns.push({header: keys[index], key: keys[index], width: 10})
            }
        }

        return columns
    },

    //

    exportDataCensusReport() {

        // Add a new worksheet to workbook variable
        const worksheet   =   this.workbook.addWorksheet('Data Census Report');

        //

        worksheet.columns =   this.getDataCensusReportColumns()

        //

        let rows          =   this.getDataCensusReportRows()

        worksheet.addRows(rows)
    },

    getDataCensusReportRows() {

      return this.data_census_report_table_data.rows
    },

    getDataCensusReportColumns() {

        let columns =   []

        if(this.data_census_report_table_data.rows.length > 0) {

            const keys  =   Object.keys(this.data_census_report_table_data.rows[0])
            console.log(this.data_census_report_table_data.rows)
            console.log(keys)

            for (let index = 0; index < keys.length; index++) {

                columns.push({header: keys[index], key: keys[index], width: 10})
            }
        }

        return columns
    },

    //

    imageDataToBase64(imgData) {

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = imgData.width;
        canvas.height = imgData.height;
        ctx.putImageData(imgData, 0, 0);
        return canvas.toDataURL('image/png').replace(/^data:image\/png;base64,/, '');
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