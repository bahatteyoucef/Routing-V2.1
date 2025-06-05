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

                <!-- Export Range   -->
                <div class="col-sm-2 mt-1">
                    <button v-if="show_export_data_button"    class="btn primary w-100"   @click="exportData()">Export Data</button>
                </div>
                <!--  -->

                <!--  -->
                <div class="col-sm-2 mt-1">
                  <button   v-if="show_validate_data_button"  class="btn primary w-100"  data-bs-toggle="modal" :data-bs-target="'#modalValidateMap'"    @click="getDoubles()">Validate</button>
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
                  <DataCensusReport :key="data_census_report_table_data"  :data_census_report_table_data="data_census_report_table_data"></DataCensusReport>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!--                  -->

    </div>
    <!--                  -->

    <!-- Validate         -->
    <modalValidateMap     ref="modalValidateMap"    :key="route_link+'_'+start_date+'_'+end_date"   :id_route_import="route_link"></modalValidateMap>
    <!--                  -->

    <!-- Modal Update     -->
    <ModalClientUpdate    ref="ModalClientUpdate"   :id_route_import="route_link"     :update_type="'normal_update'"      :mode="'permanent'"></ModalClientUpdate>
    <!--                  -->

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

import modalValidateMap                   from  "../routes/shared/operations/ModalValidateMap.vue"
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

      //

      workbook            :   null
    }
  },

  async mounted() {

    this.emitter.on("reSetUpdate"  , async (client)    =>  {
      await this.updateClientJSON(client)
    })

    this.emitter.on("reSetDelete", async (client)    =>  {
      await this.deleteClientJSON(client)
    })

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

    modalValidateMap              :   modalValidateMap            ,

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
          this.show_by_tel_validity_report_content        =   false
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

    async getDoubles() {

        await this.$refs.modalValidateMap.getDoubles()
    },

    async updateClientJSON(client) {

        //      
        for (let i = 0; i < this.total_clients.length; i++) {
            
            if(this.total_clients[i].id  ==  client.id) {

                // Update Client
                this.total_clients[i].NewCustomer                             =   client.NewCustomer
                this.total_clients[i].OpenCustomer                            =   client.OpenCustomer
                this.total_clients[i].CustomerIdentifier                      =   client.CustomerIdentifier
                this.total_clients[i].CustomerCode                            =   client.CustomerCode

                this.total_clients[i].CustomerNameE                           =   client.CustomerNameE
                this.total_clients[i].CustomerNameA                           =   client.CustomerNameA

                this.total_clients[i].Tel                                     =   client.Tel
                this.total_clients[i].tel_status                              =   client.tel_status
                this.total_clients[i].tel_comment                             =   client.tel_comment

                this.total_clients[i].Latitude                                =   client.Latitude         
                this.total_clients[i].Longitude                               =   client.Longitude        

                this.total_clients[i].Address                                 =   client.Address
                this.total_clients[i].Neighborhood                            =   client.Neighborhood
                this.total_clients[i].Landmark                                =   client.Landmark

                this.total_clients[i].DistrictNo                              =   client.DistrictNo      
                this.total_clients[i].DistrictNameE                           =   client.DistrictNameE  

                this.total_clients[i].CityNo                                  =   client.CityNo           
                this.total_clients[i].CityNameE                               =   client.CityNameE       

                this.total_clients[i].CustomerType                            =   client.CustomerType     

                this.total_clients[i].BrandAvailability                       =   client.BrandAvailability       
                this.total_clients[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                this.total_clients[i].JPlan                                   =   client.JPlan            
                this.total_clients[i].Journee                                 =   client.Journee        

                this.total_clients[i].Frequency                               =   client.Frequency        
                this.total_clients[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                this.total_clients[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                this.total_clients[i].AvailableBrands                         =   client.AvailableBrands
                this.total_clients[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                this.total_clients[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                this.total_clients[i].status                                  =   client.status            
                this.total_clients[i].nonvalidated_details                    =   client.nonvalidated_details        

                this.total_clients[i].owner                                   =   client.owner
                this.total_clients[i].owner_name                              =   client.owner_name

                this.total_clients[i].comment                                 =   client.comment        

                this.total_clients[i].facade_image                            =   client.facade_image            
                this.total_clients[i].in_store_image                          =   client.in_store_image        
                this.total_clients[i].facade_image_original_name              =   client.facade_image_original_name            
                this.total_clients[i].in_store_image_original_name            =   client.in_store_image_original_name        
                this.total_clients[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                this.total_clients[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                break
            }
        }

        //
        for (let i = 0; i < this.map_report_data.rows.length; i++) {
            
            if(this.map_report_data.rows[i].id  ==  client.id) {

                // Update Client
                this.map_report_data.rows[i].NewCustomer                             =   client.NewCustomer
                this.map_report_data.rows[i].OpenCustomer                            =   client.OpenCustomer
                this.map_report_data.rows[i].CustomerIdentifier                      =   client.CustomerIdentifier
                this.map_report_data.rows[i].CustomerCode                            =   client.CustomerCode

                this.map_report_data.rows[i].CustomerNameE                           =   client.CustomerNameE
                this.map_report_data.rows[i].CustomerNameA                           =   client.CustomerNameA

                this.map_report_data.rows[i].Tel                                     =   client.Tel
                this.map_report_data.rows[i].tel_status                              =   client.tel_status
                this.map_report_data.rows[i].tel_comment                             =   client.tel_comment

                this.map_report_data.rows[i].Latitude                                =   client.Latitude         
                this.map_report_data.rows[i].Longitude                               =   client.Longitude        

                this.map_report_data.rows[i].Address                                 =   client.Address
                this.map_report_data.rows[i].Neighborhood                            =   client.Neighborhood
                this.map_report_data.rows[i].Landmark                                =   client.Landmark

                this.map_report_data.rows[i].DistrictNo                              =   client.DistrictNo      
                this.map_report_data.rows[i].DistrictNameE                           =   client.DistrictNameE  

                this.map_report_data.rows[i].CityNo                                  =   client.CityNo           
                this.map_report_data.rows[i].CityNameE                               =   client.CityNameE       

                this.map_report_data.rows[i].CustomerType                            =   client.CustomerType     

                this.map_report_data.rows[i].BrandAvailability                       =   client.BrandAvailability       
                this.map_report_data.rows[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                this.map_report_data.rows[i].JPlan                                   =   client.JPlan            
                this.map_report_data.rows[i].Journee                                 =   client.Journee        

                this.map_report_data.rows[i].Frequency                               =   client.Frequency        
                this.map_report_data.rows[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                this.map_report_data.rows[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                this.map_report_data.rows[i].AvailableBrands                         =   client.AvailableBrands
                this.map_report_data.rows[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                this.map_report_data.rows[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                this.map_report_data.rows[i].status                                  =   client.status            
                this.map_report_data.rows[i].nonvalidated_details                    =   client.nonvalidated_details        

                this.map_report_data.rows[i].owner                                   =   client.owner
                this.map_report_data.rows[i].owner_name                              =   client.owner_name

                this.map_report_data.rows[i].comment                                 =   client.comment        

                this.map_report_data.rows[i].facade_image                            =   client.facade_image            
                this.map_report_data.rows[i].in_store_image                          =   client.in_store_image        
                this.map_report_data.rows[i].facade_image_original_name              =   client.facade_image_original_name            
                this.map_report_data.rows[i].in_store_image_original_name            =   client.in_store_image_original_name        
                this.map_report_data.rows[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                this.map_report_data.rows[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                break
            }
        }

        //
        for (let i = 0; i < this.data_census_report_table_data.rows.length; i++) {
            
            if(this.data_census_report_table_data.rows[i].id  ==  client.id) {

                // Update Client
                this.data_census_report_table_data.rows[i].NewCustomer                             =   client.NewCustomer
                this.data_census_report_table_data.rows[i].OpenCustomer                            =   client.OpenCustomer
                this.data_census_report_table_data.rows[i].CustomerIdentifier                      =   client.CustomerIdentifier
                this.data_census_report_table_data.rows[i].CustomerCode                            =   client.CustomerCode

                this.data_census_report_table_data.rows[i].CustomerNameE                           =   client.CustomerNameE
                this.data_census_report_table_data.rows[i].CustomerNameA                           =   client.CustomerNameA

                this.data_census_report_table_data.rows[i].Tel                                     =   client.Tel
                this.data_census_report_table_data.rows[i].tel_status                              =   client.tel_status
                this.data_census_report_table_data.rows[i].tel_comment                             =   client.tel_comment

                this.data_census_report_table_data.rows[i].Latitude                                =   client.Latitude         
                this.data_census_report_table_data.rows[i].Longitude                               =   client.Longitude        

                this.data_census_report_table_data.rows[i].Address                                 =   client.Address
                this.data_census_report_table_data.rows[i].Neighborhood                            =   client.Neighborhood
                this.data_census_report_table_data.rows[i].Landmark                                =   client.Landmark

                this.data_census_report_table_data.rows[i].DistrictNo                              =   client.DistrictNo      
                this.data_census_report_table_data.rows[i].DistrictNameE                           =   client.DistrictNameE  

                this.data_census_report_table_data.rows[i].CityNo                                  =   client.CityNo           
                this.data_census_report_table_data.rows[i].CityNameE                               =   client.CityNameE       

                this.data_census_report_table_data.rows[i].CustomerType                            =   client.CustomerType     

                this.data_census_report_table_data.rows[i].BrandAvailability                       =   client.BrandAvailability       
                this.data_census_report_table_data.rows[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                this.data_census_report_table_data.rows[i].JPlan                                   =   client.JPlan            
                this.data_census_report_table_data.rows[i].Journee                                 =   client.Journee        

                this.data_census_report_table_data.rows[i].Frequency                               =   client.Frequency        
                this.data_census_report_table_data.rows[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                this.data_census_report_table_data.rows[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                this.data_census_report_table_data.rows[i].AvailableBrands                         =   client.AvailableBrands
                this.data_census_report_table_data.rows[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                this.data_census_report_table_data.rows[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                this.data_census_report_table_data.rows[i].status                                  =   client.status            
                this.data_census_report_table_data.rows[i].nonvalidated_details                    =   client.nonvalidated_details        

                this.data_census_report_table_data.rows[i].owner                                   =   client.owner
                this.data_census_report_table_data.rows[i].owner_name                              =   client.owner_name

                this.data_census_report_table_data.rows[i].comment                                 =   client.comment        

                this.data_census_report_table_data.rows[i].facade_image                            =   client.facade_image            
                this.data_census_report_table_data.rows[i].in_store_image                          =   client.in_store_image        
                this.data_census_report_table_data.rows[i].facade_image_original_name              =   client.facade_image_original_name            
                this.data_census_report_table_data.rows[i].in_store_image_original_name            =   client.in_store_image_original_name        
                this.data_census_report_table_data.rows[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                this.data_census_report_table_data.rows[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                break
            }
        }

        //
        for (let i = 0; i < this.getDoublant.getDoublantCustomerCode.length; i++) {
            
            if(this.getDoublant.getDoublantCustomerCode[i].id  ==  client.id) {

                // Update Client
                this.getDoublant.getDoublantCustomerCode[i].NewCustomer                             =   client.NewCustomer
                this.getDoublant.getDoublantCustomerCode[i].OpenCustomer                            =   client.OpenCustomer
                this.getDoublant.getDoublantCustomerCode[i].CustomerIdentifier                      =   client.CustomerIdentifier
                this.getDoublant.getDoublantCustomerCode[i].CustomerCode                            =   client.CustomerCode

                this.getDoublant.getDoublantCustomerCode[i].CustomerNameE                           =   client.CustomerNameE
                this.getDoublant.getDoublantCustomerCode[i].CustomerNameA                           =   client.CustomerNameA

                this.getDoublant.getDoublantCustomerCode[i].Tel                                     =   client.Tel
                this.getDoublant.getDoublantCustomerCode[i].tel_status                              =   client.tel_status
                this.getDoublant.getDoublantCustomerCode[i].tel_comment                             =   client.tel_comment

                this.getDoublant.getDoublantCustomerCode[i].Latitude                                =   client.Latitude         
                this.getDoublant.getDoublantCustomerCode[i].Longitude                               =   client.Longitude        

                this.getDoublant.getDoublantCustomerCode[i].Address                                 =   client.Address
                this.getDoublant.getDoublantCustomerCode[i].Neighborhood                            =   client.Neighborhood
                this.getDoublant.getDoublantCustomerCode[i].Landmark                                =   client.Landmark

                this.getDoublant.getDoublantCustomerCode[i].DistrictNo                              =   client.DistrictNo      
                this.getDoublant.getDoublantCustomerCode[i].DistrictNameE                           =   client.DistrictNameE  

                this.getDoublant.getDoublantCustomerCode[i].CityNo                                  =   client.CityNo           
                this.getDoublant.getDoublantCustomerCode[i].CityNameE                               =   client.CityNameE       

                this.getDoublant.getDoublantCustomerCode[i].CustomerType                            =   client.CustomerType     

                this.getDoublant.getDoublantCustomerCode[i].BrandAvailability                       =   client.BrandAvailability       
                this.getDoublant.getDoublantCustomerCode[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                this.getDoublant.getDoublantCustomerCode[i].JPlan                                   =   client.JPlan            
                this.getDoublant.getDoublantCustomerCode[i].Journee                                 =   client.Journee        

                this.getDoublant.getDoublantCustomerCode[i].Frequency                               =   client.Frequency        
                this.getDoublant.getDoublantCustomerCode[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                this.getDoublant.getDoublantCustomerCode[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                this.getDoublant.getDoublantCustomerCode[i].AvailableBrands                         =   client.AvailableBrands
                this.getDoublant.getDoublantCustomerCode[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                this.getDoublant.getDoublantCustomerCode[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                this.getDoublant.getDoublantCustomerCode[i].status                                  =   client.status            
                this.getDoublant.getDoublantCustomerCode[i].nonvalidated_details                    =   client.nonvalidated_details        

                this.getDoublant.getDoublantCustomerCode[i].owner                                   =   client.owner
                this.getDoublant.getDoublantCustomerCode[i].owner_name                              =   client.owner_name

                this.getDoublant.getDoublantCustomerCode[i].comment                                 =   client.comment        

                this.getDoublant.getDoublantCustomerCode[i].facade_image                            =   client.facade_image            
                this.getDoublant.getDoublantCustomerCode[i].in_store_image                          =   client.in_store_image        
                this.getDoublant.getDoublantCustomerCode[i].facade_image_original_name              =   client.facade_image_original_name            
                this.getDoublant.getDoublantCustomerCode[i].in_store_image_original_name            =   client.in_store_image_original_name        
                this.getDoublant.getDoublantCustomerCode[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                this.getDoublant.getDoublantCustomerCode[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                break
            }
        }

        //
        for (let i = 0; i < this.getDoublant.getDoublantCustomerNameE.length; i++) {
            
            if(this.getDoublant.getDoublantCustomerNameE[i].id  ==  client.id) {

                // Update Client
                this.getDoublant.getDoublantCustomerNameE[i].NewCustomer                             =   client.NewCustomer
                this.getDoublant.getDoublantCustomerNameE[i].OpenCustomer                            =   client.OpenCustomer
                this.getDoublant.getDoublantCustomerNameE[i].CustomerIdentifier                      =   client.CustomerIdentifier
                this.getDoublant.getDoublantCustomerNameE[i].CustomerCode                            =   client.CustomerCode

                this.getDoublant.getDoublantCustomerNameE[i].CustomerNameE                           =   client.CustomerNameE
                this.getDoublant.getDoublantCustomerNameE[i].CustomerNameA                           =   client.CustomerNameA

                this.getDoublant.getDoublantCustomerNameE[i].Tel                                     =   client.Tel
                this.getDoublant.getDoublantCustomerNameE[i].tel_status                              =   client.tel_status
                this.getDoublant.getDoublantCustomerNameE[i].tel_comment                             =   client.tel_comment

                this.getDoublant.getDoublantCustomerNameE[i].Latitude                                =   client.Latitude         
                this.getDoublant.getDoublantCustomerNameE[i].Longitude                               =   client.Longitude        

                this.getDoublant.getDoublantCustomerNameE[i].Address                                 =   client.Address
                this.getDoublant.getDoublantCustomerNameE[i].Neighborhood                            =   client.Neighborhood
                this.getDoublant.getDoublantCustomerNameE[i].Landmark                                =   client.Landmark

                this.getDoublant.getDoublantCustomerNameE[i].DistrictNo                              =   client.DistrictNo      
                this.getDoublant.getDoublantCustomerNameE[i].DistrictNameE                           =   client.DistrictNameE  

                this.getDoublant.getDoublantCustomerNameE[i].CityNo                                  =   client.CityNo           
                this.getDoublant.getDoublantCustomerNameE[i].CityNameE                               =   client.CityNameE       

                this.getDoublant.getDoublantCustomerNameE[i].CustomerType                            =   client.CustomerType     

                this.getDoublant.getDoublantCustomerNameE[i].BrandAvailability                       =   client.BrandAvailability       
                this.getDoublant.getDoublantCustomerNameE[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                this.getDoublant.getDoublantCustomerNameE[i].JPlan                                   =   client.JPlan            
                this.getDoublant.getDoublantCustomerNameE[i].Journee                                 =   client.Journee        

                this.getDoublant.getDoublantCustomerNameE[i].Frequency                               =   client.Frequency        
                this.getDoublant.getDoublantCustomerNameE[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                this.getDoublant.getDoublantCustomerNameE[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                this.getDoublant.getDoublantCustomerNameE[i].AvailableBrands                         =   client.AvailableBrands
                this.getDoublant.getDoublantCustomerNameE[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                this.getDoublant.getDoublantCustomerNameE[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                this.getDoublant.getDoublantCustomerNameE[i].status                                  =   client.status            
                this.getDoublant.getDoublantCustomerNameE[i].nonvalidated_details                    =   client.nonvalidated_details        

                this.getDoublant.getDoublantCustomerNameE[i].owner                                   =   client.owner
                this.getDoublant.getDoublantCustomerNameE[i].owner_name                              =   client.owner_name

                this.getDoublant.getDoublantCustomerNameE[i].comment                                 =   client.comment        

                this.getDoublant.getDoublantCustomerNameE[i].facade_image                            =   client.facade_image            
                this.getDoublant.getDoublantCustomerNameE[i].in_store_image                          =   client.in_store_image        
                this.getDoublant.getDoublantCustomerNameE[i].facade_image_original_name              =   client.facade_image_original_name            
                this.getDoublant.getDoublantCustomerNameE[i].in_store_image_original_name            =   client.in_store_image_original_name        
                this.getDoublant.getDoublantCustomerNameE[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                this.getDoublant.getDoublantCustomerNameE[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                break
            }
        }

        //
        for (let i = 0; i < this.getDoublant.getDoublantTel.length; i++) {
            
            if(this.getDoublant.getDoublantTel[i].id  ==  client.id) {

                // Update Client
                this.getDoublant.getDoublantTel[i].NewCustomer                             =   client.NewCustomer
                this.getDoublant.getDoublantTel[i].OpenCustomer                            =   client.OpenCustomer
                this.getDoublant.getDoublantTel[i].CustomerIdentifier                      =   client.CustomerIdentifier
                this.getDoublant.getDoublantTel[i].CustomerCode                            =   client.CustomerCode

                this.getDoublant.getDoublantTel[i].CustomerNameE                           =   client.CustomerNameE
                this.getDoublant.getDoublantTel[i].CustomerNameA                           =   client.CustomerNameA

                this.getDoublant.getDoublantTel[i].Tel                                     =   client.Tel
                this.getDoublant.getDoublantTel[i].tel_status                              =   client.tel_status
                this.getDoublant.getDoublantTel[i].tel_comment                             =   client.tel_comment

                this.getDoublant.getDoublantTel[i].Latitude                                =   client.Latitude         
                this.getDoublant.getDoublantTel[i].Longitude                               =   client.Longitude        

                this.getDoublant.getDoublantTel[i].Address                                 =   client.Address
                this.getDoublant.getDoublantTel[i].Neighborhood                            =   client.Neighborhood
                this.getDoublant.getDoublantTel[i].Landmark                                =   client.Landmark

                this.getDoublant.getDoublantTel[i].DistrictNo                              =   client.DistrictNo      
                this.getDoublant.getDoublantTel[i].DistrictNameE                           =   client.DistrictNameE  

                this.getDoublant.getDoublantTel[i].CityNo                                  =   client.CityNo           
                this.getDoublant.getDoublantTel[i].CityNameE                               =   client.CityNameE       

                this.getDoublant.getDoublantTel[i].CustomerType                            =   client.CustomerType     

                this.getDoublant.getDoublantTel[i].BrandAvailability                       =   client.BrandAvailability       
                this.getDoublant.getDoublantTel[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                this.getDoublant.getDoublantTel[i].JPlan                                   =   client.JPlan            
                this.getDoublant.getDoublantTel[i].Journee                                 =   client.Journee        

                this.getDoublant.getDoublantTel[i].Frequency                               =   client.Frequency        
                this.getDoublant.getDoublantTel[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                this.getDoublant.getDoublantTel[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                this.getDoublant.getDoublantTel[i].AvailableBrands                         =   client.AvailableBrands
                this.getDoublant.getDoublantTel[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                this.getDoublant.getDoublantTel[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                this.getDoublant.getDoublantTel[i].status                                  =   client.status            
                this.getDoublant.getDoublantTel[i].nonvalidated_details                    =   client.nonvalidated_details        

                this.getDoublant.getDoublantTel[i].owner                                   =   client.owner
                this.getDoublant.getDoublantTel[i].owner_name                              =   client.owner_name

                this.getDoublant.getDoublantTel[i].comment                                 =   client.comment        

                this.getDoublant.getDoublantTel[i].facade_image                            =   client.facade_image            
                this.getDoublant.getDoublantTel[i].in_store_image                          =   client.in_store_image        
                this.getDoublant.getDoublantTel[i].facade_image_original_name              =   client.facade_image_original_name            
                this.getDoublant.getDoublantTel[i].in_store_image_original_name            =   client.in_store_image_original_name        
                this.getDoublant.getDoublantTel[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                this.getDoublant.getDoublantTel[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                break
            }
        }

        //
        for (let i = 0; i < this.getDoublant.getDoublantGPS.length; i++) {
            
            if(this.getDoublant.getDoublantGPS[i].id  ==  client.id) {

                // Update Client
                this.getDoublant.getDoublantGPS[i].NewCustomer                             =   client.NewCustomer
                this.getDoublant.getDoublantGPS[i].OpenCustomer                            =   client.OpenCustomer
                this.getDoublant.getDoublantGPS[i].CustomerIdentifier                      =   client.CustomerIdentifier
                this.getDoublant.getDoublantGPS[i].CustomerCode                            =   client.CustomerCode

                this.getDoublant.getDoublantGPS[i].CustomerNameE                           =   client.CustomerNameE
                this.getDoublant.getDoublantGPS[i].CustomerNameA                           =   client.CustomerNameA

                this.getDoublant.getDoublantGPS[i].Tel                                     =   client.Tel
                this.getDoublant.getDoublantGPS[i].tel_status                              =   client.tel_status
                this.getDoublant.getDoublantGPS[i].tel_comment                             =   client.tel_comment

                this.getDoublant.getDoublantGPS[i].Latitude                                =   client.Latitude         
                this.getDoublant.getDoublantGPS[i].Longitude                               =   client.Longitude        

                this.getDoublant.getDoublantGPS[i].Address                                 =   client.Address
                this.getDoublant.getDoublantGPS[i].Neighborhood                            =   client.Neighborhood
                this.getDoublant.getDoublantGPS[i].Landmark                                =   client.Landmark

                this.getDoublant.getDoublantGPS[i].DistrictNo                              =   client.DistrictNo      
                this.getDoublant.getDoublantGPS[i].DistrictNameE                           =   client.DistrictNameE  

                this.getDoublant.getDoublantGPS[i].CityNo                                  =   client.CityNo           
                this.getDoublant.getDoublantGPS[i].CityNameE                               =   client.CityNameE       

                this.getDoublant.getDoublantGPS[i].CustomerType                            =   client.CustomerType     

                this.getDoublant.getDoublantGPS[i].BrandAvailability                       =   client.BrandAvailability       
                this.getDoublant.getDoublantGPS[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

                this.getDoublant.getDoublantGPS[i].JPlan                                   =   client.JPlan            
                this.getDoublant.getDoublantGPS[i].Journee                                 =   client.Journee        

                this.getDoublant.getDoublantGPS[i].Frequency                               =   client.Frequency        
                this.getDoublant.getDoublantGPS[i].SuperficieMagasin                       =   client.SuperficieMagasin        
                this.getDoublant.getDoublantGPS[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

                this.getDoublant.getDoublantGPS[i].AvailableBrands                         =   client.AvailableBrands
                this.getDoublant.getDoublantGPS[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
                this.getDoublant.getDoublantGPS[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

                this.getDoublant.getDoublantGPS[i].status                                  =   client.status            
                this.getDoublant.getDoublantGPS[i].nonvalidated_details                    =   client.nonvalidated_details        

                this.getDoublant.getDoublantGPS[i].owner                                   =   client.owner
                this.getDoublant.getDoublantGPS[i].owner_name                              =   client.owner_name

                this.getDoublant.getDoublantGPS[i].comment                                 =   client.comment        

                this.getDoublant.getDoublantGPS[i].facade_image                            =   client.facade_image            
                this.getDoublant.getDoublantGPS[i].in_store_image                          =   client.in_store_image        
                this.getDoublant.getDoublantGPS[i].facade_image_original_name              =   client.facade_image_original_name            
                this.getDoublant.getDoublantGPS[i].in_store_image_original_name            =   client.in_store_image_original_name        
                this.getDoublant.getDoublantGPS[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
                this.getDoublant.getDoublantGPS[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

                break
            }
        }
    },

    async deleteClientJSON(client) {

        let idx                               = -1

        //      
        idx = this.total_clients.findIndex(c => c.id === client.id);

        if (idx !== -1) {
          this.total_clients.splice(idx, 1);
        }

        //
        idx = this.map_report_data.rows.findIndex(c => c.id === client.id);

        if (idx !== -1) {
          this.map_report_data.rows.splice(idx, 1);
        }

        //
        idx = this.data_census_report_table_data.rows.findIndex(c => c.id === client.id);

        if (idx !== -1) {
          this.data_census_report_table_data.rows.splice(idx, 1);
        }

        //
        idx = this.getDoublant.getDoublantCustomerCode.findIndex(c => c.id === client.id);

        if (idx !== -1) {
          this.getDoublant.getDoublantCustomerCode.splice(idx, 1);
        }

        //
        idx = this.getDoublant.getDoublantCustomerNameE.findIndex(c => c.id === client.id);

        if (idx !== -1) {
          this.getDoublant.getDoublantCustomerNameE.splice(idx, 1);
        }

        //
        idx = this.getDoublant.getDoublantTel.findIndex(c => c.id === client.id);

        if (idx !== -1) {
          this.getDoublant.getDoublantTel.splice(idx, 1);
        }

        //
        idx = this.getDoublant.getDoublantGPS.findIndex(c => c.id === client.id);

        if (idx !== -1) {
          this.getDoublant.getDoublantGPS.splice(idx, 1);
        }
    }
  },
}

</script>