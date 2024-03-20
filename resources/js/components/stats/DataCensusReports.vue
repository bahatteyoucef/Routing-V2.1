<template>

    <div class="m-3 p-3">
        
        <!-- Chart Filters -->
        <div class="row chart_filters" id="data_census_reports_chart_filters">

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
            <div class="col-3 mt-1">
                <input type="date" class="form-control" v-model="start_date"/>
            </div>
            <!--  -->

            <!-- Select Date Range  -->
            <div class="col-3 mt-1">
                <input type="date" class="form-control" v-model="end_date"/>
            </div>
            <!--  -->

            <!-- Select Date Range  -->
            <div class="col-2 mt-1">
                <button class="btn primary w-100"   @click="getData()">Get Data</button>
            </div>
            <!--  -->
            
        </div>
        <!--  -->

        <!-- Show Table         -->
        <div v-if="show_data_census_reports_chart"    class="table_scroll table_scroll_x table_container table_container mt-5">
            <table class="table table-bordered w-100" id="data_census_table">
                <thead>
                    <tr>
                        <th class="col-sm-1">Index</th>

                        <th class="col-sm-2">Created At</th>
                        <th class="col-sm-2">Start Adding Time</th>
                        <th class="col-sm-2">Adding Duration</th>

                        <th class="col-sm-2">Status</th>

                        <th class="col-sm-2">CustomerType</th>
                        <th class="col-sm-2">DistrictNameE</th>
                        <th class="col-sm-2">CityNameE</th>

                        <th class="col-sm-2">CustomerNameA</th>
                        <th class="col-sm-2">BrandAvailability</th>

                        <th class="col-sm-2">CustomerNameE</th>

                        <th class="col-sm-2">TelAvailability</th>
                        <th class="col-sm-2">Tel</th>

                        <th class="col-sm-2">Address</th>
                        <th class="col-sm-2">Neighborhood</th>
                        <th class="col-sm-2">Landmark</th>

                        <th class="col-sm-2">BrandSourcePurchase</th>
                        <th class="col-sm-2">CustomerCode</th>
                        <th class="col-sm-2">GPS</th>

                        <th class="col-sm-2">Comment</th>

                        <th class="col-sm-2">OwnerName</th>

                        <th class="col-sm-2">JPlan</th>
                        <th class="col-sm-2">Journee</th>
                    </tr>
                </thead>

                <thead>
                    <tr class="data_census_table_filters">
                        <th class="col-sm-1"></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Created At"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Start Adding Time"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Adding Duration"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Status"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="CustomerType"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="DistrictNameE"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="CityNameE"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameA"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="BrandAvailability"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameE"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="TelAvailability"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Tel"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Address"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Neighborhood"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Landmark"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="BrandSourcePurchase"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="CustomerCode"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="GPS"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Comment"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="OwnerName"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="JPlan"/></th>
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="Journee"/></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(row, index) in data_census_table.rows" :key="row" role="button">
                        <td>{{index +   1}}</td>

                        <td>{{ row.created_at }}</td>
                        <td>{{ row.start_adding_time }}</td>
                        <td>{{ row.adding_duration }}</td>

                        <td>
                            <span v-if="row.status=='nonvalidated'"  href="#" class="badge badge-danger">{{row.status}}</span>
                            <span v-if="row.status=='pending'"       href="#" class="badge badge-warning">{{row.status}}</span>
                            <span v-if="row.status=='validated'"     href="#" class="badge badge-success">{{row.status}}</span>
                        </td>

                        <td>{{ row.CustomerType }}</td>
                        <td>{{ row.DistrictNameE }}</td>
                        <td>{{ row.CityNameE }}</td>

                        <td>{{ row.CustomerNameA }}</td>
                        <td>{{ row.BrandAvailabilityText }}</td>

                        <td>{{ row.CustomerNameE }}</td>

                        <td>{{ row.TelAvailabilityText }}</td>
                        <td>{{ row.Tel }}</td>

                        <td>{{ row.Address }}</td>
                        <td>{{ row.Neighborhood }}</td>
                        <td>{{ row.Landmark }}</td>

                        <td>{{ row.BrandSourcePurchase }}</td>
                        <td>{{ row.CustomerCode }}</td>
                        <td>{{ row.Latitude }} , {{ row.Longitude }}</td>

                        <td>{{ row.Comment }}</td>

                        <td>{{ row.OwnerName }}</td>

                        <td>{{ row.JPlan }}</td>
                        <td>{{ row.Journee }}</td>

                    </tr>
                </tbody>
            </table>
        </div>
        <!--  -->

    </div>

</template>

<script>

import Multiselect  from    '@vueform/multiselect'

export default {

    components  : { 

        Multiselect :   Multiselect
    },

    data() {
        return {

            liste_route_link    :   [],

            //

            route_links         :   [],
            start_date          :   "",
            end_date            :   "",

            //

            data_census_reports_chart     :   null,

            data_census_reports_options   :   {
                maintainAspectRatio     :   false,
                scales                  :   {
                    y                       :   {
                        beginAtZero: true
                    }
                }
            },

            data_census_reports_data        :   {
                labels                          :   [],
                datasets                        :   []
            },

            //

            total_by_day_object             :   null,

            //

            show_data_census_reports_chart  :   false,

            //

            data_census_table               :   null,
            datatable_data_census_table     :   null
        }
    },

    async mounted() {

        //
        this.fetchMaps()
    },

    methods : {

        async getData() {

            try {

                if((this.start_date  !=  "")&&(this.end_date  !=  "")) {

                    this.$showLoadingPage()

                    // Destroy DataTable
                    if(this.datatable_data_census_table)  {

                        this.datatable_data_census_table.destroy()
                    }

                    // Initialisation 
                    this.data_census_table              =   [];

                    //

                    let formData                        =   new FormData()

                    formData.append("route_links"   , JSON.stringify(this.route_links))
                    formData.append("start_date"    , this.start_date)
                    formData.append("end_date"      , this.end_date)

                    await this.$callApi("post",     "/statistics/data_census_reports",  formData)
                    .then(async (res)=> {

                        console.log(res)

                        //
                        // this.data_census_reports_data.labels          =   res.data.data_census_reports.labels;
                        // this.data_census_reports_data.datasets        =   res.data.data_census_reports.datasets;
                        this.data_census_table                          =   res.data.data_census_table;

                        //
                        this.show_data_census_reports_chart             =   true

                        //
                        await this.$nextTick()

                        //
                        this.datatable_data_census_table                =   await this.$DataTableCreate("data_census_table")

                        //
                        this.$hideLoadingPage()
                    })
                }
            }

            catch(e) {

                console.log(e)
            }
        },

        //  //  //  //  //

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
