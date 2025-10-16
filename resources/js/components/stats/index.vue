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
                    <input type="date"                        class="form-control"        v-model="start_date"/>
                </div>
                <!--  -->

                <!-- Select Date Range  -->
                <div class="col-sm-2 mt-1">
                    <input type="date"                        class="form-control"        v-model="end_date"/>
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
                  :number_clients_validated="number_clients_validated"        :number_clients_ferme="number_clients_ferme"      :number_clients_pending="number_clients_pending"
                  :number_clients_nonvalidated="number_clients_nonvalidated"  :number_clients_visible="number_clients_visible"  :number_clients_total="number_clients_total"
                  :number_clients_expected="number_clients_expected">
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

        <!--  ByBrandSourcePurchaseReport -->
        <div class="col-sm-4 p-2">
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
        <div class="col-sm-4 p-2">
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

      <!-- ByTelValidity        -->
      <div class="row h-equal p-0 m-0">
        <!--  ByTelValidityReport -->
        <div class="col-sm-12 p-2">
          <div class="card h-100" v-if="show_by_tel_validity_report_content">
            <div class="card-body p-0">
              <div class="report_div" id="by_tel_validity_report">
                  <ByTelValidityReport  :key="by_tel_validity_report_chart_data"    :by_tel_validity_report_chart_data="by_tel_validity_report_chart_data"  :by_tel_validity_report_table_data="by_tel_validity_report_table_data"></ByTelValidityReport>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--                      -->

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

    <!-- Modal Decoupe By Journee       -->
    <ModalResume          ref="ModalResume"         :mode="'permanent'"             :id_route_import="route_link"></ModalResume>
    <!--                                -->

    <!-- Update                         -->
    <ModalClientUpdate    ref="ModalClientUpdate"   :update_type="update_type"      :id_route_import="route_link"   :mode="mode"    :validation_type="validation_type"></ModalClientUpdate>
    <!--                                -->

  </div>

</template>

<script>

import CardDoublants                      from  "../routes/shared/operations/validations/CardDoublants.vue"
import CardStats                          from  "./parts/CardStats.vue"

import ByCustomerTypeReport               from  "./parts/ByCustomerTypeReport.vue"
import ByBrandSourcePurchaseReport        from  "./parts/ByBrandSourcePurchaseReport.vue"
import ByBrandAvailabilityReport          from  "./parts/ByBrandAvailabilityReport.vue"
import ByTelValidityReport                from  "./parts/ByTelValidityReport.vue"
import ByCityReport                       from  "./parts/ByCityReport.vue"

import DailyReport                        from  "./parts/DailyReport.vue"
import DataMapReport                      from  "./parts/DataMapReport.vue"
import DataCensusReport                   from  "./parts/DataCensusReport.vue"

//

import ModalResume                        from  "../routes/shared/operations/ModalResume.vue"

//

import ModalClientUpdate                  from  "../clients/shared/ModalClientUpdate.vue"

//

import Multiselect                        from  "@vueform/multiselect"

export default {

  data() {

    return {

      show_get_data_button                                      : true    ,
      show_export_data_button                                   : false   ,
      show_validate_data_button                                 : false   ,

      //

      show_card_stats                                           : false   ,
      number_clients_validated                                  : null    ,
      number_clients_pending                                    : null    ,
      number_clients_nonvalidated                               : null    ,
      number_clients_visible                                    : null    ,
      number_clients_ferme                                      : null    ,
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

      show_by_brand_source_purchase_report_content              : false   ,
      by_brand_source_purchase_report_chart_data                : null    ,
      by_brand_source_purchase_report_table_data                : null    ,

      //

      show_by_brand_availability_report_content                 : false   ,
      by_brand_availability_report_chart_data                   : null    ,
      by_brand_availability_report_table_data                   : null    ,

      //

      // show_by_open_customer_report_content                      : false   ,
      // by_open_customer_report_chart_data                        : null    ,
      // by_open_customer_report_table_data                        : null    ,

      //

      show_daily_report_content                                 : false   ,
      daily_report_chart_data                                   : null    ,
      daily_report_table_data                                   : null    ,

      //

      show_by_tel_validity_report_content                       : false   ,
      by_tel_validity_report_chart_data                         : null    ,
      by_tel_validity_report_table_data                         : null    ,

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

    this.emitter.on("reSetValidationClientUpdate" , (validation_type) =>  {
      this.update_type        =   'validation'
      this.mode               =   'permanent'
      this.validation_type    =   validation_type
    })

    this.emitter.on("reSetNormalClientUpdate"     , ()                =>  {
      this.update_type        =   'normal_update'
      this.mode               =   'permanent'
      this.validation_type    =   null
    })

    //

    this.emitter.on("reSetUpdate"                 , async (client)    =>  {
      await this.updateClientJSON(client)
    })

    this.emitter.on("updateDoublesCustomerCode"   , async (client)    =>  {
      await this.updateClientJSONDoublant(client)
    })

    this.emitter.on("updateDoublesCustomerNameE"  , async (client)    =>  {
      await this.updateClientJSONDoublant(client)
    })

    this.emitter.on("updateDoublesTel"            , async (client)    =>  {
      await this.updateClientJSONDoublant(client)
    })

    this.emitter.on("updateDoublesGPS"            , async (client)    =>  {
      await this.updateClientJSONDoublant(client)
    })

    //

    this.emitter.on("reSetDelete"                 , async (client)    =>  {
      await this.deleteClientJSON(client)
    })

    this.emitter.on("deleteDoublesCustomerCode"   , async (client)    =>  {
        await this.deleteClientJSONDoublant(client)
    })

    this.emitter.on("deleteDoublesCustomerNameE"  , async (client)    =>  {
        await this.deleteClientJSONDoublant(client)
    })

    this.emitter.on("deleteDoublesTel"            , async (client)    =>  {
        await this.deleteClientJSONDoublant(client)
    })

    this.emitter.on("deleteDoublesGPS"            , async (client)    =>  {
        await this.deleteClientJSONDoublant(client)
    })

    //

    this.emitter.on('reSetClientsDecoupeByJourneeMap'   , async (clients)   =>  {
        await this.getData();
    })

    //

    await this.fetchMaps()
  },

  unmounted() {

    this.emitter.off('reSetUpdate')
    this.emitter.off('reSetDelete')

    this.emitter.off('show_by_customer_type_report_content_ready')
    this.emitter.off('show_by_brand_source_purchase_report_content_ready')
    this.emitter.off('show_by_brand_availability_report_content_ready')
    this.emitter.off('show_daily_report_content_ready')
    this.emitter.off('show_by_tel_validity_report_content_ready')
    this.emitter.off('show_by_city_report_content_ready')
    this.emitter.off('show_data_map_report_content_ready')
    this.emitter.off('show_data_census_report_table_content_ready')
  },

  components : {

    CardStats                     :   CardStats                   ,
    CardDoublants                 :   CardDoublants               ,

    //

    ByBrandAvailabilityReport     :   ByBrandAvailabilityReport   ,
    ByCityReport                  :   ByCityReport                ,
    ByCustomerTypeReport          :   ByCustomerTypeReport        ,
    ByBrandSourcePurchaseReport   :   ByBrandSourcePurchaseReport ,
    ByTelValidityReport           :   ByTelValidityReport         ,
    DailyReport                   :   DailyReport                 ,
    DataMapReport                 :   DataMapReport               ,
    DataCensusReport              :   DataCensusReport            ,

    //

    ModalResume                   :   ModalResume                 ,

    //

    ModalClientUpdate             :   ModalClientUpdate           ,

    //

    Multiselect                   :   Multiselect
  },

  methods : {

    async getData() {

      if((this.start_date  !=  "")&&(this.end_date  !=  "")) {

          this.$showLoadingPage()

          //

          this.show_get_data_button                           =   false
          this.show_export_data_button                        =   false
          this.show_validate_data_button                      =   false

          this.show_card_stats                                =   false
          this.show_card_doublants                            =   false
          this.show_by_customer_type_report_content           =   false
          this.show_by_brand_source_purchase_report_content   =   false
          this.show_by_brand_availability_report_content      =   false
          // this.show_by_open_customer_report_content           =   false
          this.show_daily_report_content                      =   false
          this.show_by_tel_validity_report_content            =   false
          this.show_by_city_report_content                    =   false
          this.show_data_census_report_content                =   false
          this.show_data_map_report_content                   =   false

          //

          let formData    =   new FormData()

          formData.append("route_links"   , JSON.stringify([this.route_link]))
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
              this.number_clients_visible                       =   res.data.stats_details.number_clients_visible 
              this.number_clients_ferme                         =   res.data.stats_details.number_clients_ferme 
              this.number_clients_total                         =   res.data.stats_details.number_clients_total
              this.number_clients_expected                      =   res.data.stats_details.number_clients_expected

              this.show_card_stats                              =   true

              //

              this.total_clients                                =   res.data.stats_details.total_clients

              //

              this.getDoublant                                  =   res.data.stats_details.getDoublant

              //

              this.show_card_doublants                          =   true

              //

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
                          // this.by_open_customer_report_chart_data           =   res.data.stats_details.by_open_customer_report_chart_data
                          // this.by_open_customer_report_table_data           =   res.data.stats_details.by_open_customer_report_table_data

                          // this.show_by_open_customer_report_content         =   true

                          // this.emitter.on('show_by_open_customer_report_content_ready' , () =>  {

                              //
                              this.daily_report_chart_data                      =   res.data.stats_details.daily_report_chart_data
                              this.daily_report_table_data                      =   res.data.stats_details.daily_report_table_data

                              this.show_daily_report_content                    =   true

                              this.emitter.on('show_daily_report_content_ready' , () =>  {
                                  //
                                  this.by_tel_validity_report_chart_data            =   res.data.stats_details.by_tel_validity_report_chart_data
                                  this.by_tel_validity_report_table_data            =   res.data.stats_details.by_tel_validity_report_table_data

                                  this.show_by_tel_validity_report_content          =   true

                                  this.emitter.on('show_by_tel_validity_report_content_ready' , () =>  {

                                      //
                                      // this.by_city_report_chart_data                    =   res.data.stats_details.by_city_report_chart_data
                                      this.by_city_report_table_data                    =   res.data.stats_details.by_city_report_table_data

                                      this.show_by_city_report_content                  =   true

                                      this.emitter.on('show_by_city_report_content_ready' , () =>  {

                                          //
                                          this.map_report_data                              =   res.data.stats_details.data_census_report_table_data

                                          this.show_data_map_report_content                 =   true

                                          this.emitter.on('show_data_map_report_content_ready', () => {

                                              //
                                              this.data_census_report_table_data                =   res.data.stats_details.data_census_report_table_data

                                              this.show_data_census_report_content              =   true

                                              this.emitter.on('show_data_census_report_table_content_ready' , () =>  {

                                                  this.show_get_data_button       =   true
                                                  this.show_export_data_button    =   true
                                                  this.show_validate_data_button  =   true
                                              })
                                          })
                                      })
                                  })
                              })
                          // })
                      })
                  })
              })
          })
          .catch((err) => {
            this.show_get_data_button   =   true
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
        this.exportByTelValidityReport()
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

    exportByTelValidityReport() {

        // Add a new worksheet to workbook variable
        const worksheet = this.workbook.addWorksheet('By Tel Availability Report');

        //

        worksheet.columns =   this.getByTelValidityReportColumns()

        //

        let rows          =   this.getByTelValidityReportRows()

        worksheet.addRows(rows)

        //

        // Determine the last row of the table
        const lastRow = worksheet.lastRow.number;

        //
        const canvas = document.getElementById("by_tel_validity_report_chart")
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

    getByTelValidityReportRows() {

      let rows  =   []

      let row   =   {}

      for (let index = 0; index < this.by_tel_validity_report_table_data.rows.length; index++) {

        row                   =   {}

        row["User"]           =   this.by_tel_validity_report_table_data.rows[index].label 
        row["Validated"]      =   this.by_tel_validity_report_table_data.rows[index].count_validated
        row["NonValidated"]   =   this.by_tel_validity_report_table_data.rows[index].count_nonvalidated
        row["Total"]          =   this.by_tel_validity_report_table_data.rows[index].count_total

        rows.push(row)
      }

      // Set Total
      row                     =   {}

      row["User"]             =   this.by_tel_validity_report_table_data.total_row.label 
      row["Validated"]        =   this.by_tel_validity_report_table_data.total_row.count_validated 
      row["NonValidated"]     =   this.by_tel_validity_report_table_data.total_row.count_nonvalidated
      row["Total"]            =   this.by_tel_validity_report_table_data.total_row.count_total

      rows.push(row)

      return rows
    },

    getByTelValidityReportColumns() {

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
        row["TelValidity"]      =   this.data_census_report_table_data.rows[index].TelValidityText
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
                          {header: "BrandAvailability"      , key: "BrandAvailability"    , width: 10},
                          {header: "CustomerNameE"          , key: "CustomerNameE"        , width: 10},
                          {header: "TelValidity"        , key: "TelValidity"      , width: 10},
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

      this.$callApi("post",    "/route_import/stats", null)
      .then((res)=> {

          this.liste_route_import = res.data

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
        'Address', 'Neighborhood', 'Landmark',
        'DistrictNo', 'DistrictNameE',
        'CityNo', 'CityNameE',
        'CustomerType',
        'BrandAvailability', 'BrandSourcePurchase',
        'JPlan', 'Journee',
        'Frequency', 'SuperficieMagasin', 'NbrAutomaticCheckouts',
        'AvailableBrands', 'AvailableBrands_array_formatted', 'AvailableBrands_string_formatted',
        'status', 'nonvalidated_details',
        'owner', 
        // 'owner_name',
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
        'Address', 'Neighborhood', 'Landmark',
        'DistrictNo', 'DistrictNameE',
        'CityNo', 'CityNameE',
        'CustomerType',
        'BrandAvailability', 'BrandSourcePurchase',
        'JPlan', 'Journee',
        'Frequency', 'SuperficieMagasin', 'NbrAutomaticCheckouts',
        'AvailableBrands', 'AvailableBrands_array_formatted', 'AvailableBrands_string_formatted',
        'status', 'nonvalidated_details',
        'owner', 
        // 'owner_name',
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