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
          <div class="card h-100 bg-gradient-success card-img-holder text-white p-3" v-if="number_clients_validated   !=  null">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_validated}}</h2>
            </div>
          </div>
        </div>

        <div class="col p-1">
          <div class="card h-100 bg-gradient-warning card-img-holder text-white p-3" v-if="number_clients_pending     !=  null">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Pending <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_pending}}</h2>
            </div>
          </div>
        </div>

        <div class="col p-1">
          <div class="card h-100 bg-gradient-danger card-img-holder text-white p-3" v-if="number_clients_nonvalidated !=  null">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Non Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_nonvalidated}}</h2>
            </div>
          </div>
        </div>

        <div class="col p-1">
          <div class="card h-100 bg-gradient-info card-img-holder text-white p-3" v-if="number_clients_total          !=  null">
            <div class="card-body p-1">
              <h4 class="font-weight-normal mb-3">Total <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_total}}</h2>
            </div>
          </div>
        </div>

        <div class="col p-1 h-100">
          <div class="card h-100 bg-gradient-info card-img-holder text-white p-3" v-if="number_clients_expected       !=  null">
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

            this.show_get_data_button                           =   false
            this.show_export_data_button                        =   false

            this.show_by_customer_type_report_content           =   false
            this.show_by_brand_source_purchase_report_content   =   false
            this.show_by_brand_availability_report_content      =   false
            this.show_daily_report_content                      =   false
            this.show_by_tel_availability_report_content        =   false
            this.show_by_city_report_content                    =   false
            this.show_data_census_report_content                =   false

            //

            let formData    =   new FormData()

            formData.append("route_links"   , JSON.stringify(this.route_links))
            formData.append("start_date"    , this.start_date)
            formData.append("end_date"      , this.end_date)

            await this.$callApi("post",   "/statistics/details",    formData)
            .then(async (res)=> {

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

                this.emitter.on('show_by_customer_type_report_content_ready' , () =>  {

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

        //
        this.$showLoadingPage()

        // Create a workbook
        this.workbook = new ExcelJS.Workbook();

        // File name
        let filename = "Reports ("+this.start_date+" __ "+this.end_date+").xlsx";

        //
        this.exportByCustomerTypeReport()
        this.exportByBrandSourcePurchaseReport()
        this.exportByBrandAvailabilityReport()
        this.exportDailyReport()
        this.exportByTelAvailabilityReport()
        this.exportByCityReport()
        this.exportDataCensusReport()

        // Write workbook to buffer then convert to Excel file and download
        this.workbook.xlsx.writeBuffer().then(data => {
          const file = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8' });
          saveAs(file, filename);
        });

        //
        this.$hideLoadingPage()
    },

    //

    exportByCustomerTypeReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('By Customer Type Report');

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

      let rows  =   []

      let row   =   {}

      for (let index = 0; index < this.by_customer_type_report_table_data.rows.length; index++) {

        row                         =   {}

        row["CustomerType"]         =   this.by_customer_type_report_table_data.rows[index].label 
        row["Clients"]              =   this.by_customer_type_report_table_data.rows[index].count_clients 
        row["Total"]                =   parseInt(this.by_customer_type_report_table_data.rows[index].percentage_clients * 100) + "%"

        rows.push(row)
      }

      // Set Total
      row                         =   {}

      row["CustomerType"]         =   this.by_customer_type_report_table_data.total_row.label
      row["Clients"]              =   this.by_customer_type_report_table_data.total_row.count_clients
      row["Total"]                =   parseInt(this.by_customer_type_report_table_data.total_row.percentage_clients * 100) + "%"

      rows.push(row)

      return rows
    },

    getByCustomerTypeReportColumns() {

        let columns =   [
                          {header: "CustomerType"       , key: "CustomerType"       , width: 10},
                          {header: "Clients"            , key: "Clients"            , width: 10},
                          {header: "Total"              , key: "Total"              , width: 10}
                        ]


        return columns
    },

    //

    exportByBrandSourcePurchaseReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('By Brand Source Purchase Report');

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

      let rows  =   []

      let row   =   {}

      for (let index = 0; index < this.by_brand_source_purchase_report_table_data.rows.length; index++) {

        row                         =   {}

        row["BrandSourcePurchase"]  =   this.by_brand_source_purchase_report_table_data.rows[index].label 
        row["Clients"]              =   this.by_brand_source_purchase_report_table_data.rows[index].count_clients 
        row["Total"]                =   parseInt(this.by_brand_source_purchase_report_table_data.rows[index].percentage_clients * 100) + "%"

        rows.push(row)
      }

      // Set Total
      row                         =   {}

      row["BrandSourcePurchase"]  =   this.by_brand_source_purchase_report_table_data.total_row.label
      row["Clients"]              =   this.by_brand_source_purchase_report_table_data.total_row.count_clients
      row["Total"]                =   parseInt(this.by_brand_source_purchase_report_table_data.total_row.percentage_clients * 100) + "%"

      rows.push(row)

      return rows
    },

    getByBrandSourcePurchaseReportColumns() {

        let columns =   [
                          {header: "BrandSourcePurchase"  , key: "BrandSourcePurchase"  , width: 10},
                          {header: "Clients"              , key: "Clients"              , width: 10},
                          {header: "Total"                , key: "Total"                , width: 10}
                        ]

        return columns
    },

    //

    exportByBrandAvailabilityReport() {

        // Add a new worksheet to workbook variable
        const worksheet   =   this.workbook.addWorksheet('By Brand Availability Report');

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

      let rows  =   []

      let row   =   {}

      for (let index = 0; index < this.by_brand_availability_report_table_data.rows.length; index++) {

        row                         =   {}

        row["Brand Availability"]   =   this.by_brand_availability_report_table_data.rows[index].label 
        row["Clients"]              =   this.by_brand_availability_report_table_data.rows[index].count_clients 
        row["Total"]                =   parseInt(this.by_brand_availability_report_table_data.rows[index].percentage_clients * 100) + "%"

        rows.push(row)
      }

      // Set Total
      row                         =   {}

      row["Brand Availability"]   =   this.by_brand_availability_report_table_data.total_row.label
      row["Clients"]              =   this.by_brand_availability_report_table_data.total_row.count_clients
      row["Total"]                =   parseInt(this.by_brand_availability_report_table_data.total_row.percentage_clients * 100) + "%"

      rows.push(row)

      return rows
    },

    getByBrandAvailabilityReportColumns() {

        let columns =   [
                          {header: "Brand Availability" , key: "Brand Availability" , width: 10},
                          {header: "Clients"            , key: "Clients"            , width: 10},
                          {header: "Total"              , key: "Total"              , width: 10}
                        ]

        return columns
    },

    //

    exportDailyReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('Daily Report');

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

      let rows  = []

      let row   = {}

      // Set Rows
      for (let index_1 = 0; index_1 < this.daily_report_table_data.rows.length; index_1++) {

          row           =   {}

          //
          row["User"]   =   this.daily_report_table_data.rows[index_1].label

          //
          for (let index_2 = 0; index_2 < this.daily_report_table_data.rows[index_1].data.length; index_2++) {

            row[this.daily_report_table_data.allDays[index_2]] = this.daily_report_table_data.rows[index_1].data[index_2]
          }

          //
          row["Total"]  =   this.daily_report_table_data.rows[index_1].total

          rows.push(row)
      }

      //  //  //  //  //  //  //  //  //  //  //  //  //

      // Set Total
      row           =   {}

      //
      row["User"]   =   "Total"

      //
      for (let index_2 = 0; index_2 < this.daily_report_table_data.total_by_day_object.data.length; index_2++) {

        row[this.daily_report_table_data.allDays[index_2]] = this.daily_report_table_data.total_by_day_object.data[index_2]
      }

      //
      row["Total"]   =   this.daily_report_table_data.total_by_day_object.total

      rows.push(row)

      //
      return rows
    },

    getDailyReportColumns() {

        let columns =   []

        if(this.daily_report_table_data.allDays.length > 0) {

            columns.push({header: "User", key: "User", width: 10})

            for (let index = 0; index < this.daily_report_table_data.allDays.length; index++) {

                columns.push({header: this.daily_report_table_data.allDays[index], key: this.daily_report_table_data.allDays[index], width: 10})
            }

            columns.push({header: "Total", key: "Total", width: 10})
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

      let rows  =   []

      let row   =   {}

      for (let index = 0; index < this.by_tel_availability_report_table_data.rows.length; index++) {

        row                         =   {}

        row["User"]   =   this.by_tel_availability_report_table_data.rows[index].label 
        row["Yes"]    =   this.by_tel_availability_report_table_data.rows[index].count_yes 
        row["No"]     =   this.by_tel_availability_report_table_data.rows[index].count_no
        row["Total"]  =   this.by_tel_availability_report_table_data.rows[index].count_total

        rows.push(row)
      }

      // Set Total
      row             =   {}

      row["User"]     =   this.by_tel_availability_report_table_data.total_row.label 
      row["Yes"]      =   this.by_tel_availability_report_table_data.total_row.count_yes 
      row["No"]       =   this.by_tel_availability_report_table_data.total_row.count_no
      row["Total"]    =   this.by_tel_availability_report_table_data.total_row.count_total

      rows.push(row)

      return rows
    },

    getByTelAvailabilityReportColumns() {

        let columns =   [
                          {header: "User"   , key: "User"   , width: 10},
                          {header: "Yes"    , key: "Yes"    , width: 10},
                          {header: "No"     , key: "No"     , width: 10},
                          {header: "Total"  , key: "Total"  , width: 10}
                        ]

        return columns
    },

    //

    exportByCityReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('By City Report');

        //

        worksheet.columns =   this.getByCityReportColumns()

        //

        let rows          =   this.getByCityReportRows()

        worksheet.addRows(rows)
    },

    getByCityReportRows() {

      let rows  =   []

      let row   =   {}

      //
      for (let index = 0; index < this.by_city_report_table_data.rows.length; index++) {

        row                         =   {}

        row["Index"]      =   index + 1 
        row["City"]       =   this.by_city_report_table_data.rows[index].CityNameE 
        row["Expected"]   =   this.by_city_report_table_data.rows[index].expected_clients
        row["Added"]      =   this.by_city_report_table_data.rows[index].added_clients
        row["Gap"]        =   this.by_city_report_table_data.rows[index].gap
        row["Percentage"] =   parseInt(this.by_city_report_table_data.rows[index].percentage_clients * 100) + "%"
        row["Status"]     =   this.by_city_report_table_data.rows[index].status_clients

        rows.push(row)
      }

      // Set Total
      row                 =   {}

      row["Index"]        =   this.by_city_report_table_data.total_row.label 
      row["City"]         =   ""
      row["Expected"]     =   this.by_city_report_table_data.total_row.expected_clients 
      row["Added"]        =   this.by_city_report_table_data.total_row.added_clients
      row["Gap"]          =   this.by_city_report_table_data.total_row.gap
      row["Percentage"]   =   parseInt(this.by_city_report_table_data.total_row.percentage_clients * 100) + "%"
      row["Status"]       =   this.by_city_report_table_data.total_row.status_clients

      rows.push(row)

      return rows
    },

    getByCityReportColumns() {

        let columns =   [
                          {header: "Index"      , key: "Index"        , width: 10},
                          {header: "City"       , key: "City"         , width: 10},
                          {header: "Expected"   , key: "Expected"     , width: 10},
                          {header: "Added"      , key: "Added"        , width: 10},
                          {header: "Gap"        , key: "Gap"          , width: 10},
                          {header: "Percentage" , key: "Percentage"   , width: 10},
                          {header: "Status"     , key: "Status"       , width: 10}
                        ]

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

      let rows  =   []

      let row   =   {}

      //
      for (let index = 0; index < this.data_census_report_table_data.rows.length; index++) {

        row                         =   {}

        row["Index"]                =   index + 1 
        row["Created At"]           =   this.data_census_report_table_data.rows[index].created_at 
        row["Start Adding Time"]    =   this.data_census_report_table_data.rows[index].start_adding_time
        row["Adding Duration"]      =   this.data_census_report_table_data.rows[index].adding_duration
        row["Status"]               =   this.data_census_report_table_data.rows[index].status
        row["CustomerType"]         =   this.data_census_report_table_data.rows[index].CustomerType
        row["DistrictNameE"]        =   this.data_census_report_table_data.rows[index].DistrictNameE
        row["CityNameE"]            =   this.data_census_report_table_data.rows[index].CityNameE
        row["CustomerNameA"]        =   this.data_census_report_table_data.rows[index].CustomerNameA
        row["BrandAvailability"]    =   this.data_census_report_table_data.rows[index].BrandAvailabilityText
        row["CustomerNameE"]        =   this.data_census_report_table_data.rows[index].CustomerNameE
        row["TelAvailability"]      =   this.data_census_report_table_data.rows[index].TelAvailabilityText
        row["Tel"]                  =   this.data_census_report_table_data.rows[index].Tel
        row["Address"]              =   this.data_census_report_table_data.rows[index].Address
        row["Neighborhood"]         =   this.data_census_report_table_data.rows[index].Neighborhood
        row["Landmark"]             =   this.data_census_report_table_data.rows[index].Landmark
        row["BrandSourcePurchase"]  =   this.data_census_report_table_data.rows[index].BrandSourcePurchase
        row["CustomerCode"]         =   this.data_census_report_table_data.rows[index].CustomerCode
        row["GPS"]                  =   this.data_census_report_table_data.rows[index].Latitude + ", " + this.data_census_report_table_data.rows[index].Longitude
        row["Comment"]              =   this.data_census_report_table_data.rows[index].Comment
        row["OwnerName"]            =   this.data_census_report_table_data.rows[index].OwnerName
        row["JPlan"]                =   this.data_census_report_table_data.rows[index].JPlan
        row["Journee"]              =   this.data_census_report_table_data.rows[index].Journee

        rows.push(row)
      }

      return rows
    },

    getDataCensusReportColumns() {

        let columns =   [
                          {header: "Index"                  , key: "Index"                , width: 10},
                          {header: "Created At"             , key: "Created At"           , width: 10},
                          {header: "Start Adding Time"      , key: "Start Adding Time"    , width: 10},
                          {header: "Adding Duration"        , key: "Adding Duration"      , width: 10},
                          {header: "Status"                 , key: "Status"               , width: 10},
                          {header: "CustomerType"           , key: "CustomerType"         , width: 10},
                          {header: "DistrictNameE"          , key: "DistrictNameE"        , width: 10},
                          {header: "CityNameE"              , key: "CityNameE"            , width: 10},
                          {header: "CustomerNameA"          , key: "CustomerNameA"        , width: 10},
                          {header: "BrandAvailability"      , key: "BrandAvailability"    , width: 10},
                          {header: "CustomerNameE"          , key: "CustomerNameE"        , width: 10},
                          {header: "TelAvailability"        , key: "TelAvailability"      , width: 10},
                          {header: "Tel"                    , key: "Tel"                  , width: 10},
                          {header: "Address"                , key: "Address"              , width: 10},
                          {header: "Neighborhood"           , key: "Neighborhood"         , width: 10},
                          {header: "Landmark"               , key: "Landmark"             , width: 10},
                          {header: "BrandSourcePurchase"    , key: "BrandSourcePurchase"  , width: 10},
                          {header: "CustomerCode"           , key: "CustomerCode"         , width: 10},
                          {header: "GPS"                    , key: "GPS"                  , width: 10},
                          {header: "Comment"                , key: "Comment"              , width: 10},
                          {header: "OwnerName"              , key: "OwnerName"            , width: 10},
                          {header: "JPlan"                  , key: "JPlan"                , width: 10},
                          {header: "Journee"                , key: "Journee"              , width: 10},
                        ]

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