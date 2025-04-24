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

                        <th class="col-sm-2">OpenCustomer</th>
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
                        <th class="col-sm-3"><input type="text" class="form-control form-control-sm" placeholder="OpenCustomer"/></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(row, index) in data_census_report_table_data.rows" :key="row" @click="selectRow(row)" role="button"  :id="'data_census_report_table_'+row.id">
                        <td>{{index +   1}}</td>

                        <td>{{ row.created_at }}</td>
                        <td>{{ row.start_adding_time }}</td>
                        <td>{{ row.adding_duration }}</td>

                        <td>
                            <span v-if="row.status=='nonvalidated'"  href="#" class="badge badge-danger">{{row.status}}</span>
                            <span v-if="row.status=='pending'"       href="#" class="badge badge-warning">{{row.status}}</span>
                            <span v-if="row.status=='validated'"     href="#" class="badge badge-success">{{row.status}}</span>
                            <span v-if="row.status=='visible'"       href="#" class="badge badge-info">{{row.status}}</span>
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

                        <td>{{ row.OpenCustomer }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal Update                   -->
        <modalClientUpdate          ref="modalClientUpdate"         ></modalClientUpdate>

    </div>

</template>

<script>

import modalClientUpdate            from    "../../clients/permanent/modalClientUpdate.vue"

import {mapGetters, mapActions}     from    "vuex"

export default {

    data() {
        return {

            show_data_census_reports_chart      :   false,

            //

            datatable_data_census_report_table  :   null
        }
    },

    props : ["data_census_report_table_data"],

    components : {
        modalClientUpdate           
    },

    async mounted() {

        //
        this.setTable()

        //
        await this.$nextTick()

        //
        this.emitter.emit('show_data_census_report_content_ready')

        this.emitter.on('reSetUpdate'       , async (client)    =>  {

            await this.updateClientToDatatable(client)
        })
    },

    methods : {

        ...mapActions("client" ,  [
            "setUpdateClientAction"   ,
        ]),

        //

        async setTable() {

            try {

                this.datatable_data_census_report_table     =   await this.$DataTableCreate("data_census_report_table")
            }

            catch(e) {

                console.log(e)
            }
        },

        async updateClientToDatatable(client) {

            for (let i = 0; i < this.data_census_report_table_data.rows.length; i++) {
                
                if(this.data_census_report_table_data.rows[i].id  ==  client.id) {

                    this.data_census_report_table_data.rows[i].OpenCustomer    =   client.OpenCustomer
                    this.data_census_report_table_data.rows[i].CustomerCode    =   client.CustomerCode

                    this.data_census_report_table_data.rows[i].CustomerNameE   =   client.CustomerNameE
                    this.data_census_report_table_data.rows[i].CustomerNameA   =   client.CustomerNameA
                    this.data_census_report_table_data.rows[i].Tel             =   client.Tel

                    this.data_census_report_table_data.rows[i].Latitude        =   client.Latitude         
                    this.data_census_report_table_data.rows[i].Longitude       =   client.Longitude        

                    this.data_census_report_table_data.rows[i].Address         =   client.Address
                    this.data_census_report_table_data.rows[i].Neighborhood    =   client.Neighborhood
                    this.data_census_report_table_data.rows[i].Landmark        =   client.Landmark

                    this.data_census_report_table_data.rows[i].DistrictNo      =   client.DistrictNo      
                    this.data_census_report_table_data.rows[i].DistrictNameE   =   client.DistrictNameE  

                    this.data_census_report_table_data.rows[i].CityNo          =   client.CityNo           
                    this.data_census_report_table_data.rows[i].CityNameE       =   client.CityNameE       

                    this.data_census_report_table_data.rows[i].CustomerType    =   client.CustomerType     

                    this.data_census_report_table_data.rows[i].BrandAvailability      =   client.BrandAvailability       
                    this.data_census_report_table_data.rows[i].BrandSourcePurchase    =   client.BrandSourcePurchase       

                    this.data_census_report_table_data.rows[i].JPlan              =   client.JPlan            
                    this.data_census_report_table_data.rows[i].Journee            =   client.Journee        

                    this.data_census_report_table_data.rows[i].status                 =   client.status            
                    this.data_census_report_table_data.rows[i].nonvalidated_details   =   client.nonvalidated_details        

                    this.data_census_report_table_data.rows[i].comment                =   client.comment        

                    this.data_census_report_table_data.rows[i].facade_image                           =   client.facade_image            
                    this.data_census_report_table_data.rows[i].in_store_image                         =   client.in_store_image        
                    this.data_census_report_table_data.rows[i].facade_image_original_name             =   client.facade_image_original_name            
                    this.data_census_report_table_data.rows[i].in_store_image_original_name           =   client.in_store_image_original_name        
                    this.data_census_report_table_data.rows[i].CustomerBarCode_image                  =   client.CustomerBarCode_image            
                    this.data_census_report_table_data.rows[i].CustomerBarCode_image_original_name    =   client.CustomerBarCode_image_original_name        

                    break
                }
            }

            //

            // Destroy DataTable
            if(this.datatable_data_census_report_table)  {

                this.datatable_data_census_report_table.destroy()
            }

            this.datatable_data_census_report_table    =   await this.$DataTableCreate("data_census_report_table")
        },

        //

        selectRow(client) {

            // Get Element
            const row           =   document.getElementById("data_census_report_table_"+client.id)

            if(!row.classList.contains("active_row")) {

                // remove Active Class
                const active_row    =   document.getElementsByClassName("active_row")[0]

                if(active_row) {

                    active_row.classList.remove("active_row")
                }

                // add Active Class
                row.classList.add("active_row")

                // Selected Row
                this.selected_row   =   client

                this.updateElement(client)      
            }

            else {

                // remove Active Class
                row.classList.remove("active_row")

                // Selected Row
                this.selected_row   =   null
            }
        },

        async updateElement() {

            try {

                if(this.$isRole("Super Admin")||this.$isRole('BU Manager')||this.$isRole("BackOffice")||this.$isRole('Viewer')) {

                    // ShowModal
                    var updateModal     =   new Modal(document.getElementById("updateClientModal"));
                    updateModal.show();

                    this.$refs.modalClientUpdate.getData(this.selected_row, this.data_census_report_table_data.rows)
                }
            }
            catch(e) {

                console.log(e)
            }
        },
    }
}

</script>
