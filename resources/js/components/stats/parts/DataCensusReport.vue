<template>

    <div class="m-1">
        
        <!-- Title -->
        <div class="row">
            <div class="col-9 d-flex align-items-center">
                <h4 class="mb-0 ml-2">Data Census Report</h4>
            </div>
        </div>

        <!-- Show Table     -->
        <div        class="table_scroll table_scroll_x table_container table_container mt-5">
            <table  class="table table-bordered w-100" id="data_census_report_table">
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
                    <tr class="data_census_report_table_filters">
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
                    <tr v-for="(row, index) in data_census_report_table_data.rows" :key="row" role="button">
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

    </div>

</template>

<script>

export default {

    data() {
        return {

            show_data_census_reports_chart      :   false,

            //

            datatable_data_census_report_table  :   null
        }
    },

    props : ["data_census_report_table_data"],

    async mounted() {

        //
        await this.$nextTick()

        //
        this.setTable()
    },

    methods : {

        async setTable() {

            try {

                this.datatable_data_census_report_table     =   await this.$DataTableCreate("data_census_report_table")
            }

            catch(e) {

                console.log(e)
            }
        },
    }
}

</script>
