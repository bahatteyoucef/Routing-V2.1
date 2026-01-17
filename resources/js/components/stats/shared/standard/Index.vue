<template>

  <div class="p-3 m-2 h-100" style="background-color: #f2edf3; padding : 15px;">

    <!-- Stats Filters    -->
    <div class="col-sm-12 p-2" id="stats_filters">
        <div class="card">
            <div class="card-body p-0">
              <div class="row">

                <!-- Select Map         -->
                <div class="col-sm-2 map_filter">
                    <Multiselect
                        v-model             =   "route_link"
                        :options            =   "liste_route_link"
                        mode                =   "single" 
                        placeholder         =   "Select Map"
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
                <div class="col-sm-2 mt-1">
                    <input type="date"                        class="form-control" v-model="start_date"/>
                </div>
                <!--  -->

                <!-- Select Date Range  -->
                <div class="col-sm-2 mt-1">
                    <input type="date"                        class="form-control" v-model="end_date"/>
                </div>
                <!--  -->

                <!-- Get Range      -->
                <div class="col-sm-2 mt-1">
                    <button v-if="show_get_data_button"       class="btn primary w-100"   @click="getData()">Get Data</button>
                </div>
                <!--  -->

                <!-- Resume         -->
                <div class="col-sm-2 mt-1">
                    <button v-if="show_export_data_button"    class="btn primary w-100"   @click="showResume()">Resume</button>
                </div>
                <!--  -->

                <!-- Export Range   -->
                <div class="col-sm-2 mt-1">
                    <button v-if="show_export_data_button"    class="btn primary w-100"   @click="exportData()">Export Data</button>
                </div>
                <!--  -->

              </div>
            </div>
        </div>
    </div>
    <!--                  -->

    <!-- Reports          -->
    <div class="col-sm-12 p-0 mb-2" id="reports">

      <!-- Card Stats     -->
      <CardStats  v-if="show_card_stats"
                  :number_clients_tel_status_validated="number_clients_tel_status_validated"
                  :number_clients_confirmed="number_clients_confirmed"        :number_clients_validated="number_clients_validated"    :number_clients_ferme="number_clients_ferme"                :number_clients_refus="number_clients_refus"                :number_clients_pending="number_clients_pending"
                  :number_clients_nonvalidated="number_clients_nonvalidated"  :number_clients_visible="number_clients_visible"        :number_clients_introuvable="number_clients_introuvable"
                  :number_clients_non_visible="number_clients_non_visible"    :number_clients_total="number_clients_total"            :number_clients_expected="number_clients_expected">
      </CardStats>
      <!--                -->

      <!-- Card Doublant  -->
      <div v-if="show_card_doublants" class="row h-equal p-0 m-0">
        <div class="col-sm-12 p-2">
          <div class="card h-100">
            <div class="card-body p-0">                
              <div class="report_div" id="validation_report">
                <div class="validations_div mt-5">
                    <CardDoublants  :getDoublant="getDoublant"    :total_clients="total_clients"    :mode="'permanent'"   :id_route_import="route_link"></CardDoublants>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--                -->

      <!-- ByCustomerTypeReport + ByBrandSourcePurchaseReport + ByBrandAvailabilityReport -->
      <div class="row h-equal p-0 m-0">

        <!--  ByCustomerTypeReport  -->
        <div class="col-sm-4 p-2">
          <div class="card h-100" v-if="show_by_customer_type_report_content">
            <div class="card-body p-0">                
              <div class="report_div" id="by_customer_type_report">
                  <ByCustomerTypeReport refs="ByCustomerTypeReport" :key="by_customer_type_report_chart_data"   :by_customer_type_report_chart_data="by_customer_type_report_chart_data"  :by_customer_type_report_table_data="by_customer_type_report_table_data"></ByCustomerTypeReport>
              </div>
            </div>
          </div>
        </div>

        <!--  -->

        <!--  ByNewCustomerReport  -->
        <div class="col-sm-4 p-2">
          <div class="card h-100" v-if="show_by_new_customer_report_content">
            <div class="card-body p-0">
              <div class="row">
                <div class="report_div by_new_customer_report" id="by_new_customer_report">
                    <ByNewCustomerReport :key="by_new_customer_report_chart_data" :by_new_customer_report_chart_data="by_new_customer_report_chart_data"  :by_new_customer_report_table_data="by_new_customer_report_table_data"></ByNewCustomerReport>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!--  -->

        <!--  ByOpenCustomerReport  -->
        <div class="col-sm-4 p-2">
          <div class="card h-100" v-if="show_by_open_customer_report_content">
            <div class="card-body p-0">
              <div class="row">
                <div class="report_div by_open_customer_report" id="by_open_customer_report">
                    <ByOpenCustomerReport :key="by_open_customer_report_chart_data" :by_open_customer_report_chart_data="by_open_customer_report_chart_data"  :by_open_customer_report_table_data="by_open_customer_report_table_data"></ByOpenCustomerReport>
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
        <div class="col-sm-12 p-2">
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

      <!-- ByCityReport         -->
      <div class="row h-equal p-0 m-0">

        <!--  ByCityReport  -->
        <div class="col-sm-12 p-2">
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

      <!-- DataMapReport        -->
      <div class="row h-equal p-0 m-0">

        <!--  DataMapReport  -->
        <div class="col-sm-12 p-2">
          <div class="card h-100" v-if="show_data_map_report_content">
            <div class="card-body p-0">                
              <div class="report_div" id="data_map_report">
                  <DataMapReport :key="map_report_data"  :map_report_data="map_report_data"  :id_route_import="route_link"></DataMapReport>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!--                      -->

      <!-- DataCensusReport -->
      <div class="row h-equal p-0 m-0">

        <!--  DataCensusReport  -->
        <div class="col-sm-12 p-2">
          <div class="card h-100" v-if="show_data_census_report_content">
            <div class="card-body p-0">                
              <div class="report_div" id="data_census_report">
                  <DataCensusReport ref="DataCensusReport" :key="data_census_report_table_data"  :data_census_report_table_data="data_census_report_table_data"></DataCensusReport>
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

import CardDoublants                      from  "@/components/routes/shared/operations/validations/CardDoublants.vue"

import CardStats                          from  "./parts/CardStats.vue"

import ByCustomerTypeReport               from  "./parts/ByCustomerTypeReport.vue"
import ByNewCustomerReport                from  "./parts/ByNewCustomerReport.vue"
import ByOpenCustomerReport               from  "./parts/ByOpenCustomerReport.vue"

import ByCityReport                       from  "./parts/ByCityReport.vue"

import DailyReport                        from  "./parts/DailyReport.vue"
import DataMapReport                      from  "./parts/DataMapReport.vue"
import DataCensusReport                   from  "./parts/DataCensusReport.vue"

//

import ModalResume                        from  "../../../routes/shared/operations/ModalResume.vue"

//

import ModalClientUpdate                  from  "../../../clients/shared/ModalClientUpdate.vue"

//

import Multiselect                        from  "@vueform/multiselect"

//

import emitter                  from    "@/utils/emitter"

export default {

  data() {

    return {

      show_get_data_button                                      : true    ,
      show_export_data_button                                   : false   ,
      show_validate_data_button                                 : false   ,

      //

      show_card_stats                                           : false   ,
      number_clients_tel_status_validated                       : null    ,
      number_clients_confirmed                                  : null    ,
      number_clients_validated                                  : null    ,
      number_clients_pending                                    : null    ,
      number_clients_nonvalidated                               : null    ,
      number_clients_visible                                    : null    ,
      number_clients_ferme                                      : null    ,
      number_clients_refus                                      : null    ,
      number_clients_introuvable                                : null    ,
      number_clients_non_visible                                : null    ,
      number_clients_total                                      : null    ,
      number_clients_expected                                   : null    ,

      //

      total_clients                                             : []      ,

      //

      show_card_doublants                                       : false   ,
      getDoublant                                               : null    ,

      //

      show_by_customer_type_report_content                      : false   ,
      by_customer_type_report_chart_data                        : null    ,
      by_customer_type_report_table_data                        : null    ,

      by_customer_type_report_chart                             : null    ,

      //

      show_by_new_customer_report_content                       : false   ,
      by_new_customer_report_chart_data                         : null    ,
      by_new_customer_report_table_data                         : null    ,

      show_by_open_customer_report_content                      : false   ,
      by_open_customer_report_chart_data                        : null    ,
      by_open_customer_report_table_data                        : null    ,

      //

      show_daily_report_content                                 : false   ,
      daily_report_chart_data                                   : null    ,
      daily_report_table_data                                   : null    ,

      //

      show_by_city_report_content                               : false   ,
      by_city_report_chart_data                                 : null    ,
      by_city_report_table_data                                 : null    ,

      //

      show_data_map_report_content                              : false   ,
      map_report_data                                           : null    ,

      //

      show_data_census_report_content                           : false   ,
      data_census_report_table_data                             : null    ,

      //

      liste_route_link    :   [],

      route_link          :   null,
      start_date          :   "",
      end_date            :   "",

      update_type         :   null,
      mode                :   null,
      validation_type     :   null,

      //

      users_all           :   [],
      districts_all       :   [],

      //

      workbook            :   null,
    }
  },

  async mounted() {

    emitter.on("reSetValidationClientUpdate" , (validation_type) =>  {
      this.update_type        =   'validation'
      this.mode               =   'permanent'
      this.validation_type    =   validation_type
    })

    emitter.on("reSetNormalClientUpdate"     , ()                =>  {
      this.update_type        =   'normal_update'
      this.mode               =   'permanent'
      this.validation_type    =   null
    })

    //

    emitter.on("reSetUpdate"                 , async (client)    =>  {
      await this.updateClientJSON(client)
    })

    emitter.on("updateDoublesCustomerCode"   , async (client)    =>  {
      await this.updateClientJSONDoublant(client)
    })

    emitter.on("updateDoublesCustomerNameE"  , async (client)    =>  {
      await this.updateClientJSONDoublant(client)
    })

    emitter.on("updateDoublesTel"            , async (client)    =>  {
      await this.updateClientJSONDoublant(client)
    })

    emitter.on("updateDoublesGPS"            , async (client)    =>  {
      await this.updateClientJSONDoublant(client)
    })

    //

    emitter.on("reSetDelete"                 , async (client)    =>  {
      await this.deleteClientJSON(client)
    })

    emitter.on("deleteDoublesCustomerCode"   , async (client)    =>  {
        await this.deleteClientJSONDoublant(client)
    })

    emitter.on("deleteDoublesCustomerNameE"  , async (client)    =>  {
        await this.deleteClientJSONDoublant(client)
    })

    emitter.on("deleteDoublesTel"            , async (client)    =>  {
        await this.deleteClientJSONDoublant(client)
    })

    emitter.on("deleteDoublesGPS"            , async (client)    =>  {
        await this.deleteClientJSONDoublant(client)
    })

    //

    emitter.on('reSetClientsDecoupeByJourneeMap'   , async (clients)   =>  {
        await this.getData();
    })

    //

    await this.fetchMaps()
  },

  components : {

    CardStats                     :   CardStats                   ,
    CardDoublants                 :   CardDoublants               ,

    //

    ByCityReport                  :   ByCityReport                ,

    ByCustomerTypeReport          :   ByCustomerTypeReport        ,
    ByNewCustomerReport           :   ByNewCustomerReport         ,
    ByOpenCustomerReport          :   ByOpenCustomerReport        ,

    DailyReport                   :   DailyReport                 ,
    DataMapReport                 :   DataMapReport               ,
    DataCensusReport              :   DataCensusReport            ,

    //

    Multiselect                   :   Multiselect
  },

  methods : {

    async getData() {

        if((this.start_date  !=  "")&&(this.end_date  !=  "")) {

            await this.$showLoadingPage()

            //

            this.show_get_data_button                           =   false
            this.show_export_data_button                        =   false
            this.show_validate_data_button                      =   false

            this.show_card_stats                                =   false
            this.show_card_doublants                            =   false

            this.show_by_customer_type_report_content           =   false
            this.show_by_new_customer_report_content            =   false
            this.show_by_open_customer_report_content           =   false

            this.show_daily_report_content                      =   false
            this.show_by_city_report_content                    =   false
            this.show_data_census_report_content                =   false
            this.show_data_map_report_content                   =   false

            //

            let formData    =   new FormData()

            formData.append("route_links"   , JSON.stringify([this.route_link]))
            formData.append("start_date"    , this.start_date)
            formData.append("end_date"      , this.end_date)

            await this.$callApi("post",   "/statistics/standard",    formData)
            .then(async (res)=> {

                //
                await this.$hideLoadingPage()

                //
                this.number_clients_tel_status_validated          =   res.data.stats_details.number_clients_tel_status_validated
                this.number_clients_confirmed                     =   res.data.stats_details.number_clients_confirmed
                this.number_clients_validated                     =   res.data.stats_details.number_clients_validated
                this.number_clients_pending                       =   res.data.stats_details.number_clients_pending  
                this.number_clients_nonvalidated                  =   res.data.stats_details.number_clients_nonvalidated
                this.number_clients_visible                       =   res.data.stats_details.number_clients_visible 
                this.number_clients_ferme                         =   res.data.stats_details.number_clients_ferme 
                this.number_clients_refus                         =   res.data.stats_details.number_clients_refus 
                this.number_clients_introuvable                   =   res.data.stats_details.number_clients_introuvable
                this.number_clients_non_visible                   =   res.data.stats_details.number_clients_non_visible
                this.number_clients_total                         =   res.data.stats_details.number_clients_total
                this.number_clients_expected                      =   res.data.stats_details.number_clients_expected

                this.show_card_stats                              =   true

                //

                this.total_clients                                =   res.data.stats_details.total_clients

                //

                this.getDoublant                                  =   res.data.stats_details.getDoublant

                this.show_card_doublants                          =   true

                //

                //
                this.by_customer_type_report_chart_data           =   res.data.stats_details.by_customer_type_report_chart_data
                this.by_customer_type_report_table_data           =   res.data.stats_details.by_customer_type_report_table_data

                this.show_by_customer_type_report_content         =   true

                emitter.on('show_by_customer_type_report_content_ready' , () =>  {

                    this.by_new_customer_report_chart_data   =   res.data.stats_details.by_new_customer_report_chart_data
                    this.by_new_customer_report_table_data   =   res.data.stats_details.by_new_customer_report_table_data

                    this.show_by_new_customer_report_content =   true

                    emitter.on('show_by_new_customer_report_content_ready' , () =>  {
                            
                        this.by_open_customer_report_chart_data           =   res.data.stats_details.by_open_customer_report_chart_data
                        this.by_open_customer_report_table_data           =   res.data.stats_details.by_open_customer_report_table_data

                        this.show_by_open_customer_report_content         =   true

                        emitter.on('show_by_open_customer_report_content_ready' , () =>  {
                                
                            //
                            this.daily_report_chart_data                      =   res.data.stats_details.daily_report_chart_data
                            this.daily_report_table_data                      =   res.data.stats_details.daily_report_table_data

                            this.show_daily_report_content                    =   true

                            emitter.on('show_daily_report_content_ready' , () =>  {

                                //
                                // this.by_city_report_chart_data                    =   res.data.stats_details.by_city_report_chart_data
                                this.by_city_report_table_data                    =   res.data.stats_details.by_city_report_table_data

                                this.show_by_city_report_content                  =   true

                                emitter.on('show_by_city_report_content_ready' , () =>  {

                                    //
                                    this.map_report_data                              =   res.data.stats_details.data_census_report_table_data

                                    this.show_data_map_report_content                 =   true

                                    emitter.on('show_data_map_report_content_ready', () => {

                                        //
                                        this.data_census_report_table_data                =   res.data.stats_details.data_census_report_table_data

                                        this.show_data_census_report_content              =   true

                                        emitter.on('show_data_census_report_content_ready' , () =>  {

                                            this.show_get_data_button       =   true
                                            this.show_export_data_button    =   true
                                            this.show_validate_data_button  =   true
                                        })
                                    })
                                })
                            })
                        })
                    })
                    // })
                })
            })
      }

      else {
        this.$feedbackWarning("Warning !", "Please select a map, start and an end date !")
      }
    },

    //

    async showResume() {

      // ShowModal
      var ModalResume    =   new Modal(document.getElementById("ModalResume"));
      ModalResume.show();

      //
      await this.$refs.ModalResume.getClients(this.total_clients)
    },

    //

    async exportData() {

        //
        await this.$showLoadingPage()

        // Create a workbook
        this.workbook = new ExcelJS.Workbook();

        // File name
        let filename = "Reports ("+this.start_date+" __ "+this.end_date+").xlsx";

        //
        this.exportByCustomerTypeReport()
        this.exportByNewCustomerReport()
        this.exportByOpenCustomerReport()
        this.exportDailyReport()
        this.exportDailyReportTotal()
        this.exportByCityReport()
        this.exportDataCensusReport()

        // Write workbook to buffer then convert to Excel file and download
        this.workbook.xlsx.writeBuffer().then(data => {
          const file = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8' });
          saveAs(file, filename);
        });

        //
        await this.$hideLoadingPage()
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

    exportByNewCustomerReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('By New Customer Report');

        //

        worksheet.columns =   this.getByNewCustomerReportColumns()

        //

        let rows          =   this.getByNewCustomerReportRows()

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

    getByNewCustomerReportRows() {

      let rows  =   []

      let row   =   {}

      for (let index = 0; index < this.by_customer_type_report_table_data.rows.length; index++) {

        row                         =   {}

        row["NewCustomer"]         =   this.by_customer_type_report_table_data.rows[index].label 
        row["Clients"]              =   this.by_customer_type_report_table_data.rows[index].count_clients 
        row["Total"]                =   parseInt(this.by_customer_type_report_table_data.rows[index].percentage_clients * 100) + "%"

        rows.push(row)
      }

      // Set Total
      row                         =   {}

      row["NewCustomer"]         =   this.by_customer_type_report_table_data.total_row.label
      row["Clients"]              =   this.by_customer_type_report_table_data.total_row.count_clients
      row["Total"]                =   parseInt(this.by_customer_type_report_table_data.total_row.percentage_clients * 100) + "%"

      rows.push(row)

      return rows
    },

    getByNewCustomerReportColumns() {

        let columns =   [
                          {header: "NewCustomer"        , key: "NewCustomer"        , width: 10},
                          {header: "Clients"            , key: "Clients"            , width: 10},
                          {header: "Total"              , key: "Total"              , width: 10}
                        ]

        //
        return columns
    },

    //

    exportByOpenCustomerReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('By Open Customer Report');

        //

        worksheet.columns =   this.getByOpenCustomerReportColumns()

        //

        let rows          =   this.getByOpenCustomerReportRows()

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

    getByOpenCustomerReportRows() {

      let rows  =   []

      let row   =   {}

      for (let index = 0; index < this.by_customer_type_report_table_data.rows.length; index++) {

        row                         =   {}

        row["OpenCustomer"]         =   this.by_customer_type_report_table_data.rows[index].label 
        row["Clients"]              =   this.by_customer_type_report_table_data.rows[index].count_clients 
        row["Total"]                =   parseInt(this.by_customer_type_report_table_data.rows[index].percentage_clients * 100) + "%"

        rows.push(row)
      }

      // Set Total
      row                         =   {}

      row["OpenCustomer"]         =   this.by_customer_type_report_table_data.total_row.label
      row["Clients"]              =   this.by_customer_type_report_table_data.total_row.count_clients
      row["Total"]                =   parseInt(this.by_customer_type_report_table_data.total_row.percentage_clients * 100) + "%"

      rows.push(row)

      return rows
    },

    getByOpenCustomerReportColumns() {

        let columns =   [
                          {header: "OpenCustomer"       , key: "OpenCustomer"       , width: 10},
                          {header: "Clients"            , key: "Clients"            , width: 10},
                          {header: "Total"              , key: "Total"              , width: 10}
                        ]

        //
        return columns
    },

    //

    exportDailyReport() {
        const worksheet = this.workbook.addWorksheet('Daily Report');
        const allDays = this.daily_report_table_data.allDays;
        const N = allDays.length;
        // The 4 sub-headers used for each day and the total
        const subHeaders = ['Ouvert', 'Ferme', 'Refus', 'Total'];

        // 1. Define Column Keys & Widths
        // This MUST be done before addRows() to map the data keys correctly.
        worksheet.columns = this.getDailyReportColumns();

        // 2. Build Header Row 1 (e.g., [ ], [Day 1], [Day 2], [Total])
        let headerRow1Values = ['']; // A1 is empty, will be merged
        
        // Add Day headers
        for (const day of allDays) {
            headerRow1Values.push(day); // This will be the top-left cell of the merge
            // Add 3 empty placeholders for the 4-column merge
            headerRow1Values.push('', '', ''); 
        }
        
        // Add the "Total" header
        headerRow1Values.push('Total');
        // Add 3 empty placeholders for the 'Total' merge
        headerRow1Values.push('', '', '');
        
        const headerRow1 = worksheet.addRow(headerRow1Values);
        headerRow1.font = { bold: true };
        headerRow1.alignment = { horizontal: 'center' };

        // 3. Build Header Row 2 (e.g., [User], [Ouvert, Ferme, ...], [Ouvert, ...])
        let headerRow2Values = ['User']; // A2
        
        // Add sub-headers for each day
        for (let i = 0; i < N; i++) {
            headerRow2Values.push(...subHeaders); // Adds ['Ouvert', 'Ferme', 'Refus', 'Total']
        }
        
        // Add sub-headers for the Grand Total
        headerRow2Values.push(...subHeaders); 

        const headerRow2 = worksheet.addRow(headerRow2Values);
        headerRow2.font = { bold: true };
        headerRow2.alignment = { horizontal: 'center' };

        // 4. Apply Header Merges
        // Merge A1:A2 and set shared value
        worksheet.mergeCells('A1:A2');
        worksheet.getCell('A1').value = 'User';
        worksheet.getCell('A1').alignment = { vertical: 'middle', horizontal: 'center' };

        // Merge Day headers (Row 1)
        for (let i = 0; i < N; i++) {
            // Calculate column range for each day
            let startCol = 2 + (i * 4); // Col B, F, J, etc.
            let endCol = startCol + 3;   // Col E, I, M, etc.
            worksheet.mergeCells(1, startCol, 1, endCol); // (row, col, row, col)
        }

        // Merge Grand Total header (Row 1)
        let totalStartCol = 2 + (N * 4); // Column after the last day
        let totalEndCol = totalStartCol + 3;
        worksheet.mergeCells(1, totalStartCol, 1, totalEndCol);
        
        // 5. Add Data Rows (This function is also modified)
        let rows = this.getDailyReportRows();
        worksheet.addRows(rows);

        // --- Your Image Code (Unchanged) ---

        // Determine the last row of the table
        const lastRow = worksheet.lastRow.number;

        //
        const canvas = document.getElementById("daily_report_chart"); // Make sure this ID is correct
        const ctx = canvas.getContext('2d');
        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        //
        const imgId = this.workbook.addImage({
            base64: this.imageDataToBase64(imgData),
            extension: 'png',
        });

        // Add the image to the worksheet
        worksheet.addImage(imgId, {
            tl: { col: 1, row: lastRow + 2 },
            ext: { width: canvas.width, height: canvas.height },
        });
    },

    getDailyReportRows() {
        let rows = [];
        const allDays = this.daily_report_table_data.allDays;
        const totalRowData = this.daily_report_table_data.total_by_day_object;
        const userRowsData = this.daily_report_table_data.rows;

        // 1. Build the "Total" Row (as seen first in your tbody)
        let totalRow = { User: "Total" }; // First column

        // Add data for each day
        for (let i = 0; i < allDays.length; i++) {
            const day = allDays[i];
            const dayData = totalRowData.data[i];
            
            totalRow[`${day}_ouvert`] = dayData.count_ouvert;
            totalRow[`${day}_ferme`]  = dayData.count_ferme;
            totalRow[`${day}_refus`]  = dayData.count_refus;
            totalRow[`${day}_total`]  = dayData.count_total;
        }

        // Add data for the final "Grand Total" block
        totalRow["GrandTotal_ouvert"] = totalRowData.total_obj.count_ouvert;
        totalRow["GrandTotal_ferme"]  = totalRowData.total_obj.count_ferme;
        totalRow["GrandTotal_refus"]  = totalRowData.total_obj.count_refus;
        totalRow["GrandTotal_total"]  = totalRowData.total_obj.count_total;
        
        rows.push(totalRow);

        // 2. Build the User Rows
        for (const userRow of userRowsData) {
            let row = { User: userRow.label }; // First column

            // Add data for each day
            for (let i = 0; i < allDays.length; i++) {
                const day = allDays[i];
                const dayData = userRow.data[i];

                row[`${day}_ouvert`] = dayData.count_ouvert;
                row[`${day}_ferme`]  = dayData.count_ferme;
                row[`${day}_refus`]  = dayData.count_refus;
                row[`${day}_total`]  = dayData.count_total;
            }

            // Add data for the user's total ("Grand Total" block for that row)
            row["GrandTotal_ouvert"] = userRow.total_obj.count_ouvert;
            row["GrandTotal_ferme"]  = userRow.total_obj.count_ferme;
            row["GrandTotal_refus"]  = userRow.total_obj.count_refus;
            row["GrandTotal_total"]  = userRow.total_obj.count_total;

            rows.push(row);
        }

        return rows;
    },

    getDailyReportColumns() {
        let columns = [];
        const allDays = this.daily_report_table_data.allDays;

        if (allDays.length > 0) {
            // 1. User Column
            columns.push({ key: "User", width: 15 });

            // 2. Columns for each day
            for (const day of allDays) {
                // We need unique keys for each sub-column
                columns.push({ key: `${day}_ouvert`, width: 10 });
                columns.push({ key: `${day}_ferme`, width: 10 });
                columns.push({ key: `${day}_refus`, width: 10 });
                columns.push({ key: `${day}_total`, width: 10, style: { font: { bold: true } } });
            }

            // 3. Columns for the Grand Total block
            columns.push({ key: "GrandTotal_ouvert", width: 10 });
            columns.push({ key: "GrandTotal_ferme", width: 10 });
            columns.push({ key: "GrandTotal_refus", width: 10 });
            columns.push({ key: "GrandTotal_total", width: 12, style: { font: { bold: true } } });
        }
        
        return columns;
    },

    //

    exportDailyReportTotal() {
        const worksheet = this.workbook.addWorksheet('Daily Report (Total)');
        const allDays = this.daily_report_table_data.allDays;
        const N = allDays.length;
        // The sub-header is now just 'Total'
        const subHeaders = ['Total'];

        // 1. Define Column Keys & Widths
        // This calls the new getDailyReportTotalColumns() function
        worksheet.columns = this.getDailyReportTotalColumns();

        // 2. Build Header Row 1
        // [EMPTY] | [Day 1] | [Day 2] | ... | [Total]
        let headerRow1Values = ['']; // A1 is empty, will be merged
        
        // Add Day headers (now only 1 column each)
        for (const day of allDays) {
            headerRow1Values.push(day); 
        }
        
        // Add the "Total" header
        headerRow1Values.push('Total');
        
        const headerRow1 = worksheet.addRow(headerRow1Values);
        headerRow1.font = { bold: true };
        headerRow1.alignment = { horizontal: 'center' };

        // 3. Build Header Row 2
        // [User] | [Total] | [Total] | ... | [Total]
        let headerRow2Values = ['User']; // A2
        
        // Add sub-headers ('Total') for each day
        for (let i = 0; i < N; i++) {
            headerRow2Values.push(...subHeaders); // Adds ['Total']
        }
        
        // Add sub-header for the Grand Total
        headerRow2Values.push(...subHeaders); // Adds ['Total']

        const headerRow2 = worksheet.addRow(headerRow2Values);
        headerRow2.font = { bold: true };
        headerRow2.alignment = { horizontal: 'center' };

        // 4. Apply Header Merges
        // Merge A1:A2 for "User"
        worksheet.mergeCells('A1:A2');
        worksheet.getCell('A1').value = 'User';
        worksheet.getCell('A1').alignment = { vertical: 'middle', horizontal: 'center' };

        // Merge the final "Total" column
        // It's the column at index N + 2 (1 for User, N for days, 1 for Total)
        const totalColLetter = worksheet.getColumn(N + 2).letter; 
        worksheet.mergeCells(`${totalColLetter}1:${totalColLetter}2`);
        worksheet.getCell(`${totalColLetter}1`).value = 'Total';
        worksheet.getCell(`${totalColLetter}1`).alignment = { vertical: 'middle', horizontal: 'center' };
        
        // 5. Add Data Rows
        // This calls the new getDailyReportTotalRows() function
        let rows = this.getDailyReportTotalRows();
        worksheet.addRows(rows);

        // --- Your Image Code (Unchanged) ---
        const lastRow = worksheet.lastRow.number;
        const canvas = document.getElementById("daily_report_chart");
        const ctx = canvas.getContext('2d');
        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        const imgId = this.workbook.addImage({
            base64: this.imageDataToBase64(imgData),
            extension: 'png',
        });

        worksheet.addImage(imgId, {
            tl: { col: 1, row: lastRow + 2 },
            ext: { width: canvas.width, height: canvas.height },
        });
    },

    getDailyReportTotalRows() {
        let rows = [];
        const allDays = this.daily_report_table_data.allDays;
        const totalRowData = this.daily_report_table_data.total_by_day_object;
        const userRowsData = this.daily_report_table_data.rows;

        // 1. Build the "Total" Row
        let totalRow = { User: "Total" }; // First column

        // Add data for each day's total
        for (let i = 0; i < allDays.length; i++) {
            const day = allDays[i]; // The key, e.g., "Day 1"
            const dayData = totalRowData.data[i];
            
            // Use the day name as the key
            totalRow[day] = dayData.count_total;
        }

        // Add data for the final "Grand Total"
        totalRow["GrandTotal"] = totalRowData.total_obj.count_total;
        
        rows.push(totalRow);

        // 2. Build the User Rows
        for (const userRow of userRowsData) {
            let row = { User: userRow.label }; // First column

            // Add data for each day's total
            for (let i = 0; i < allDays.length; i++) {
                const day = allDays[i]; // The key
                const dayData = userRow.data[i];

                // Use the day name as the key
                row[day] = dayData.count_total;
            }

            // Add data for the user's total ("Grand Total" column)
            row["GrandTotal"] = userRow.total_obj.count_total;

            rows.push(row);
        }

        return rows;
    },

    getDailyReportTotalColumns() {
        let columns = [];
        const allDays = this.daily_report_table_data.allDays;

        if (allDays.length > 0) {
            // 1. User Column
            columns.push({ key: "User", width: 15 });

            // 2. Columns for each day's TOTAL
            for (const day of allDays) {
                // The key is the day's name (e.g., "Day 1", "Day 2")
                // This key MUST match the keys created in getDailyReportTotalRows
                columns.push({ key: day, width: 10, style: { font: { bold: true } } });
            }

            // 3. Column for the Grand Total
            columns.push({ key: "GrandTotal", width: 12, style: { font: { bold: true } } });
        }
        
        return columns;
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
        row["CustomerNameE"]        =   this.data_census_report_table_data.rows[index].CustomerNameE
        row["TelValidity"]          =   this.data_census_report_table_data.rows[index].TelValidityText
        row["Tel"]                  =   this.data_census_report_table_data.rows[index].Tel

        row["RvrsGeoAddress"]       =   this.data_census_report_table_data.rows[index].RvrsGeoAddress
        row["Address"]              =   this.data_census_report_table_data.rows[index].Address
        row["Neighborhood"]         =   this.data_census_report_table_data.rows[index].Neighborhood
        row["Landmark"]             =   this.data_census_report_table_data.rows[index].Landmark

        row["JPlan"]                =   this.data_census_report_table_data.rows[index].JPlan
        row["Journee"]              =   this.data_census_report_table_data.rows[index].Journee
        row["BrandAvailability"]    =   this.data_census_report_table_data.rows[index].BrandAvailabilityText
        row["BrandSourcePurchase"]  =   this.data_census_report_table_data.rows[index].BrandSourcePurchase

        row["CustomerBarCodeExiste"]=   this.data_census_report_table_data.rows[index].CustomerBarCodeExiste
        row["CustomerCode"]         =   this.data_census_report_table_data.rows[index].CustomerCode
        row["GPS"]                  =   this.data_census_report_table_data.rows[index].Latitude + ", " + this.data_census_report_table_data.rows[index].Longitude
        row["Comment"]              =   this.data_census_report_table_data.rows[index].Comment
        row["owner_username"]            =   this.data_census_report_table_data.rows[index].owner_username
        row["JPlan"]                =   this.data_census_report_table_data.rows[index].JPlan
        row["Journee"]              =   this.data_census_report_table_data.rows[index].Journee
        row["OpenCustomer"]         =   this.data_census_report_table_data.rows[index].OpenCustomer
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
                          {header: "CustomerNameE"          , key: "CustomerNameE"        , width: 10},
                          {header: "TelValidity"            , key: "TelValidity"          , width: 10},
                          {header: "Tel"                    , key: "Tel"                  , width: 10},

                          {header: "RvrsGeoAddress"         , key: "RvrsGeoAddress"       , width: 10},
                          {header: "Address"                , key: "Address"              , width: 10},
                          {header: "Neighborhood"           , key: "Neighborhood"         , width: 10},
                          {header: "Landmark"               , key: "Landmark"             , width: 10},

                          {header: "JPlan"                  , key: "JPlan"                , width: 10},
                          {header: "Journee"                , key: "Journee"              , width: 10},
                          {header: "BrandAvailability"      , key: "BrandAvailability"    , width: 10},
                          {header: "BrandSourcePurchase"    , key: "BrandSourcePurchase"  , width: 10},

                          {header: "CustomerBarCodeExiste"  , key: "CustomerBarCodeExiste", width: 10},
                          {header: "CustomerCode"           , key: "CustomerCode"         , width: 10},
                          {header: "GPS"                    , key: "GPS"                  , width: 10},
                          {header: "Comment"                , key: "Comment"              , width: 10},
                          {header: "owner_username"              , key: "owner_username"            , width: 10},
                          {header: "JPlan"                  , key: "JPlan"                , width: 10},
                          {header: "Journee"                , key: "Journee"              , width: 10},
                          {header: "OpenCustomer"           , key: "OpenCustomer"         , width: 10},
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

      this.$callApi("post",    "/route-imports", null)
      .then((res)=> {

          this.liste_route_import = res.data.liste_route_import

          this.prepareRouteLink()
      })
    },

    prepareRouteLink() {

      this.liste_route_link     =   []

      for (let i = 0; i < this.liste_route_import.length; i++) {

          this.liste_route_link.push({ value : this.liste_route_import[i].id , label : this.liste_route_import[i].libelle})
      }
    },

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    async updateClientJSON(client) {

      // Collections you need to update
      const collections = [
        this.total_clients,
        this.map_report_data.rows,
        this.data_census_report_table_data.rows,
        this.getDoublant.getDoublantCustomerCode,
        this.getDoublant.getDoublantCustomerNameE,
        this.getDoublant.getDoublantTel,
        this.getDoublant.getDoublantGPS,
      ];

      // List of fields to sync from the incoming client object
      const fields = [
        'NewCustomer', 'OpenCustomer', 'CustomerIdentifier', 'CustomerCode',
        'CustomerNameE', 'CustomerNameA',
        'Tel', 'tel_status', 'tel_comment',
        'Latitude', 'Longitude',
        'Address', 'RvrsGeoAddress', 'Neighborhood', 'Landmark',
        'DistrictNo', 'DistrictNameE',
        'CityNo', 'CityNameE',
        'CustomerType',
        'BrandAvailability', 'BrandSourcePurchase',
        'JPlan', 'Journee',
        'Frequency', 'SuperficieMagasin', 'NbrAutomaticCheckouts',
        'AvailableBrands', 'AvailableBrands_array_formatted', 'AvailableBrands_string_formatted',
        'status', 'nonvalidated_details',
        'owner', 
        // 'owner_username',
        'comment',
        'facade_image', 'in_store_image',
        'facade_image_original_name', 'in_store_image_original_name',
        'CustomerBarCode_image', 'CustomerBarCode_image_original_name',
      ];

      for (const collection of collections) {
        const item = collection.find(c => c.id === client.id);
        if (item) {
          // Copy only the listed fields
          for (const key of fields) {
            // Only copy if the property exists in client
            if (client.hasOwnProperty(key)) {
              item[key] = client[key];
            }
          }
        }
      }

      //

      await this.$refs.DataCensusReport.setDataTable()
    },

    async updateClientJSONDoublant(client) {

      // Collections you need to update
      const collections = [
        this.map_report_data.rows,
        this.data_census_report_table_data.rows,
      ];

      // List of fields to sync from the incoming client object
      const fields = [
        'NewCustomer', 'OpenCustomer', 'CustomerIdentifier', 'CustomerCode',
        'CustomerNameE', 'CustomerNameA',
        'Tel', 'tel_status', 'tel_comment',
        'Latitude', 'Longitude',
        'Address', 'RvrsGeoAddress', 'Neighborhood', 'Landmark',
        'DistrictNo', 'DistrictNameE',
        'CityNo', 'CityNameE',
        'CustomerType',
        'BrandAvailability', 'BrandSourcePurchase',
        'JPlan', 'Journee',
        'Frequency', 'SuperficieMagasin', 'NbrAutomaticCheckouts',
        'AvailableBrands', 'AvailableBrands_array_formatted', 'AvailableBrands_string_formatted',
        'status', 'nonvalidated_details',
        'owner', 
        // 'owner_username',
        'comment',
        'facade_image', 'in_store_image',
        'facade_image_original_name', 'in_store_image_original_name',
        'CustomerBarCode_image', 'CustomerBarCode_image_original_name',
      ];

      for (const collection of collections) {
        const item = collection.find(c => c.id === client.id);
        if (item) {
          // Copy only the listed fields
          for (const key of fields) {
            // Only copy if the property exists in client
            if (client.hasOwnProperty(key)) {
              item[key] = client[key];
            }
          }
        }
      }

      //

      await this.$refs.DataCensusReport.setDataTable()
    },

    //

    async deleteClientJSON(client) {

      // Collections you need to delete from
      const collections = [
        this.total_clients,
        this.map_report_data.rows,
        this.data_census_report_table_data.rows,
        this.getDoublant.getDoublantCustomerCode,
        this.getDoublant.getDoublantCustomerNameE,
        this.getDoublant.getDoublantTel,
        this.getDoublant.getDoublantGPS,
      ];

      for (const collection of collections) {
        const idx = collection.findIndex(c => c.id === client.id);
        if (idx !== -1) {
          collection.splice(idx, 1);
        }
      }

      //

      await this.$refs.DataCensusReport.setDataTable()
    },

    async deleteClientJSONDoublant(client) {

      // Collections you need to delete from
      const collections = [
        this.map_report_data.rows,
        this.data_census_report_table_data.rows,
      ];

      for (const collection of collections) {
        const idx = collection.findIndex(c => c.id === client.id);
        if (idx !== -1) {
          collection.splice(idx, 1);
        }
      }

      //

      await this.$refs.DataCensusReport.setDataTable()
    },
  },
}

</script>