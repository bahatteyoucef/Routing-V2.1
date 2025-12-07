<template>
    <div class="p-3 m-2 h-100" style="background-color: #f2edf3; padding : 15px;">
        <div class="col-sm-12 p-2">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row">

                        <div class="col-sm-3">
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

                        <div class="col-sm-3">
                            <Multiselect
                                v-model             =   "selected_users"
                                :options            =   "users"
                                mode                =   "tags"
                                placeholder         =   "Select Users"
                                class               =   "mt-1"

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "false"

                                :canDeselect        =   "true"
                                :canClear           =   "true"
                                :allowAbsent        =   "true"
                            />
                        </div>

                        <!-- Select Date Range  -->
                        <div class="col-sm-2 mt-1">
                            <input type="date"      class="form-control"          v-model="start_date"/>
                        </div>
                        <!--  -->

                        <!-- Select Date Range  -->
                        <div class="col-sm-2 mt-1">
                            <input type="date"      class="form-control"          v-model="end_date"/>
                        </div>
                        <!--  -->

                        <!-- Get Range      -->
                        <div class="col-sm-2 mt-1">
                            <button                 class="btn primary w-100"     @click="getData()">Get Data</button>
                        </div>
                        <!--  -->

                    </div>
                </div>
            </div>
        </div>

        <div v-if="pointings" class="col-sm-12 p-2 mt-2">
            <div v-for="pointing, index_1 in pointings" :key="index_1" class="mt-3">
                <!-- Pointing Details -->
                <div class="row h-equal p-0 pl-2 m-0">
                    <div class="col p-1">
                        <div v-if="pointing.details.number_clients_confirmed   !=  null" class="card h-100 card-img-holder text-white p-3"       style="background-color: #0F9D58">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Confirmed <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{pointing.details.number_clients_confirmed}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="pointing.details.number_clients_validated   !=  null" class="card h-100 card-img-holder text-white p-3"       style="background-color: #0F9D58">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{pointing.details.number_clients_validated}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="pointing.details.number_clients_ferme   !=  null" class="card h-100 card-img-holder text-white p-3"           style="background-color: #0288D1;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Ferme <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{pointing.details.number_clients_ferme}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="pointing.details.number_clients_refus   !=  null" class="card h-100 card-img-holder text-white p-3"           style="background-color: #880E4F;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Refus <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{pointing.details.number_clients_refus}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="pointing.details.number_clients_introuvable   !=  null" class="card h-100 card-img-holder text-white p-3"     style="background-color: #000000;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Introuvable <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{pointing.details.number_clients_introuvable}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="pointing.details.number_clients_pending !=  null" class="card h-100 card-img-holder text-white p-3"           style="background-color: #F57C00;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Pending <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{pointing.details.number_clients_pending}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="pointing.details.number_clients_nonvalidated    !=  null" class="card h-100 card-img-holder text-white p-3"   style="background-color: #F70000;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Non Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{pointing.details.number_clients_nonvalidated}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="pointing.details.number_clients_visible !=  null" class="card h-100 card-img-holder text-white p-3"           style="background-color: #3949AB;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Visible <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{pointing.details.number_clients_visible}}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pointing Start Time + Days -->
                <div class="row h-equal p-0 pl-2 m-0">
                    <div class="col-sm-2 p-1">
                        <div v-if="pointing.username   !=  null" class="card h-100 card-img-holder p-3">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">User<i class="mdi mdi-account-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{pointing.username}}</h2>
                            </div>
                        </div>
                    </div>

                    <!-- Pointing Start Time -->
                    <div class="col-sm-2 p-1">
                        <div :id="'start_time_col_'+pointing.id" class="h-50">
                            <div v-if="pointing.details.start_time   !=  null" class="card h-100 p-3">
                                <div class="card-body p-1">
                                    <h4 class="font-weight-normal mb-3">Start Time <i class="mdi mdi-clock-outline mdi-24px float-right"></i></h4>
                                    <h2 class="mb-1">{{pointing.details.start_time}}</h2>
                                </div>
                            </div>

                            <div v-else class="card h-100 p-3">
                                <div class="card-body p-1">
                                    <h4 class="font-weight-normal mb-3">Start Time <i class="mdi mdi-clock-outline mdi-24px float-right"></i></h4>
                                    <h2 class="mb-1">00:00:00</h2>
                                </div>
                            </div>
                        </div>

                        <div :id="'end_time_col_'+pointing.id" class="end_time_col h-50">
                            <div v-if="pointing.details.end_time   !=  null" class="card h-100 p-3">
                                <div class="card-body p-1">
                                    <h4 class="font-weight-normal mb-3">End Time <i class="mdi mdi-clock-outline mdi-24px float-right"></i></h4>
                                    <h2 class="mb-1">{{pointing.details.end_time}}</h2>
                                </div>
                            </div>

                            <div v-else class="card h-100 p-3">
                                <div class="card-body p-1">
                                    <h4 class="font-weight-normal mb-3">End Time <i class="mdi mdi-clock-outline mdi-24px float-right"></i></h4>
                                    <h2 class="mb-1">00:00:00</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pointing Days -->
                    <div class="col-sm-8 p-1 pointing_days_col"  :id="'pointing_days_col_'+pointing.id">
                        <div v-if="pointing.clients" class="card h-100 p-3">
                            <div :id="'pointing_days_parent_'+pointing.id"   class="pointing_days_parent table_scroll table_container">
                                <table  class="table table-bordered pointing_days" id="pointing_days">
                                    <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Confirmed</th>
                                            <th>Validated</th>
                                            <th>Ferme</th>
                                            <th>Refus</th>
                                            <th>Introuvable</th>
                                            <th>Pending</th>
                                            <th>Non Validated</th>
                                            <th>Visible</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <th>Total</th>
                                            <th>{{ pointing.details.start_time }}</th>
                                            <th>{{ pointing.details.end_time }}</th>
                                            <th>{{ pointing.details.number_clients_confirmed }}</th>
                                            <th>{{ pointing.details.number_clients_validated }}</th>
                                            <th>{{ pointing.details.number_clients_ferme }}</th>
                                            <th>{{ pointing.details.number_clients_refus }}</th>
                                            <th>{{ pointing.details.number_clients_introuvable }}</th>
                                            <th>{{ pointing.details.number_clients_pending }}</th>
                                            <th>{{ pointing.details.number_clients_nonvalidated }}</th>
                                            <th>{{ pointing.details.number_clients_visible }}</th>
                                        </tr>

                                        <tr v-for="day, index_2 in pointing.days" :key="index_2">
                                            <td>{{ day.day }}</td>
                                            <td>{{ day.start_time }}</td>
                                            <td>{{ day.end_time }}</td>
                                            <td>{{ day.number_clients_confirmed }}</td>
                                            <td>{{ day.number_clients_validated }}</td>
                                            <td>{{ day.number_clients_ferme }}</td>
                                            <td>{{ day.number_clients_refus }}</td>
                                            <td>{{ day.number_clients_introuvable }}</td>
                                            <td>{{ day.number_clients_pending }}</td>
                                            <td>{{ day.number_clients_nonvalidated }}</td>
                                            <td>{{ day.number_clients_visible }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pointing End Time + Clients -->
                <div class="row h-equal p-0 pl-2 m-0">
                    <div class="col-sm-12 p-1"  id="clients_col">
                        <div v-if="pointing.clients" class="card h-100 p-3">
                            <div :id="'pointing_clients_parent'+pointing.id"   class="pointing_clients_parent table_scroll table_container pb-3">
                                <table  class="table table-bordered pointing_clients" :id="'pointing_clients_'+pointing.id">
                                    <thead>
                                        <tr>
                                            <th class="col-sm-1">Index</th>

                                            <th class="col-sm-2">Id</th>

                                            <th class="col-sm-2">Created At</th>
                                            <!-- <th class="col-sm-2">Start Adding Time</th> -->
                                            <!-- <th class="col-sm-2">Adding Duration</th> -->
                                            <th class="col-sm-2">Status</th>
                                            <th class="col-sm-2">Owner</th>

                                            <th class="col-sm-2">CustomerIdentifier</th>

                                            <th class="col-sm-2">CustomerBarCodeExiste</th>
                                            <th class="col-sm-2">CustomerCode (BarCode)</th>

                                            <th class="col-sm-2">CustomerNameE</th>
                                            <th class="col-sm-2">CustomerNameA</th>

                                            <!-- <th class="col-sm-1">DistrictNo</th> -->
                                            <th class="col-sm-2">DistrictNameE</th>

                                            <!-- <th class="col-sm-1">CityNo</th> -->
                                            <th class="col-sm-2">CityNameE</th>

                                            <!-- <th class="col-sm-2">CustomerBarCode Image</th> -->
                                            <!-- <th class="col-sm-2">In Store Image</th> -->
                                            <!-- <th class="col-sm-2">Facade Image</th> -->

                                            <th class="col-sm-2">Address</th>
                                            <!-- <th class="col-sm-2">Neighborhood</th> -->
                                            <!-- <th class="col-sm-2">Landmark</th> -->

                                            <!-- <th class="col-sm-2">Latitude</th> -->
                                            <!-- <th class="col-sm-2">Longitude</th> -->

                                            <th class="col-sm-2">Tel</th>

                                            <th class="col-sm-1">CustomerType</th>

                                            <!-- <th class="col-sm-2">JPlan</th> -->
                                            <!-- <th class="col-sm-2">Journee</th> -->

                                            <!-- <th class="col-sm-2">Frequency</th> -->
                                            <!-- <th class="col-sm-2">SuperficieMagasin</th> -->
                                            <!-- <th class="col-sm-2">NbrAutomaticCheckouts</th> -->
                                            <!-- <th class="col-sm-2">AvailableBrands</th> -->

                                            <!--  -->

                                            <th class="col-sm-2">Comment</th>
                                            <!-- <th class="col-sm-2">BrandAvailability</th> -->
                                            <!-- <th class="col-sm-2">BrandSourcePurchase</th> -->
                                        </tr>
                                    </thead>

                                    <thead>
                                        <tr :class="'pointing_clients_filters pointing_clients_'+pointing.id+'_filters'">

                                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Index"                    /></th>

                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Id"                       /></th>

                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Created_At"               /></th>
                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Start Adding Time"        /></th> -->
                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Adding Duration"          /></th> -->
                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Status"                   /></th>
                                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Owner"                    /></th>

                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerIdentifier"       /></th>

                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerBarCodeExiste"    /></th>
                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerCode"             /></th>
                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameE"            /></th>
                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameA"            /></th>

                                            <!-- <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="DistrictNo"               /></th> -->
                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="DistrictNameE"            /></th>

                                            <!-- <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CityNo"                   /></th> -->
                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CityNameE"                /></th>

                                            <!-- <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerBarCode Image"    /></th> -->
                                            <!-- <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="In Store Image"           /></th> -->
                                            <!-- <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Facade Image"             /></th> -->

                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Address"                  /></th>
                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Neighborhood"             /></th> -->
                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Landmark"                 /></th> -->

                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Latitude"                 /></th> -->
                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Longitude"                /></th> -->

                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Tel"                      /></th>

                                            <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerType"             /></th>

                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="JPlan"                    /></th> -->
                                            <!-- <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Journee"                  /></th> -->

                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Frequency"                /></th> -->
                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="SuperficieMagasin"        /></th> -->
                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="NbrAutomaticCheckouts"    /></th> -->
                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="AvailableBrands"          /></th> -->

                                            <!--  -->

                                            <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Comment"                  /></th>
                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="BrandAvailability"        /></th> -->
                                            <!-- <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="BrandSourcePurchase"      /></th> -->
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <tr v-for="client, index_3 in pointing.clients" :key="index_3"    :id="'pointing_clients_'+pointing.id+'_'+client.id">
                                            <td>{{index_3 +   1}}</td>

                                            <td>{{client.id}}</td>

                                            <td>{{client.created_at}}</td>
                                            <!-- <td>{{client.start_adding_time}}</td> -->
                                            <!-- <td>{{client.adding_duration}}</td> -->

                                            <td>
                                                <span v-if="client.status=='nonvalidated'"  href="#" class="badge badge-danger">{{client.status}}</span>
                                                <span v-if="client.status=='pending'"       href="#" class="badge badge-warning">{{client.status}}</span>
                                                <span v-if="client.status=='confirmed'"     href="#" class="badge badge-success">{{client.status}}</span>
                                                <span v-if="client.status=='validated'"     href="#" class="badge badge-success">{{client.status}}</span>
                                                <span v-if="client.status=='visible'"       href="#" class="badge badge-info">{{client.status}}</span>
                                                <span v-if="client.status=='ferme'"         href="#" class="badge badge-secondary">{{client.status}}</span>
                                                <span v-if="client.status=='refus'"         href="#" class="badge badge-refus">{{client.status}}</span>
                                            </td>

                                            <td>{{client.owner_name}}</td>

                                            <td>{{client.CustomerIdentifier}}</td>

                                            <td>{{client.CustomerBarCodeExiste}}</td>
                                            <td>{{client.CustomerCode}}</td>
                                            <td>{{client.CustomerNameE}}</td>
                                            <td>{{client.CustomerNameA}}</td>

                                            <!-- <td>{{client.DistrictNo}}</td> -->
                                            <td>{{client.DistrictNameE}}</td>

                                            <!-- <td>{{client.CityNo}}</td> -->
                                            <td>{{client.CityNameE}}</td>

                                            <!-- <td>{{client.CustomerBarCode_image}}</td> -->
                                            <!-- <td>{{client.in_store_image}}</td> -->
                                            <!-- <td>{{client.facade_image}}</td> -->

                                            <td>{{client.Address}}</td>
                                            <!-- <td>{{client.Neighborhood}}</td> -->
                                            <!-- <td>{{client.Landmark}}</td> -->

                                            <!-- <td>{{client.Latitude}}</td> -->
                                            <!-- <td>{{client.Longitude}}</td> -->

                                            <td>{{client.Tel}}</td>

                                            <td>{{client.CustomerType}}</td>

                                            <!-- <td>{{client.JPlan}}</td> -->
                                            <!-- <td>{{client.Journee}}</td> -->

                                            <!-- <td>{{client.Frequency}}</td> -->
                                            <!-- <td>{{client.SuperficieMagasin}}</td> -->
                                            <!-- <td>{{client.NbrAutomaticCheckouts}}</td> -->
                                            <!-- <td>{{client.AvailableBrands_string_formatted}}</td> -->

                                            <!--  -->

                                            <td>{{client.comment}}</td>
                                            <!-- <td>{{client.BrandAvailability}}</td> -->
                                            <!-- <td>{{client.BrandSourcePurchase}}</td> -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!--  -->
                <hr class="mt-5 mb-5" />
            </div>
        </div>
    </div>
</template>
 
<script>

import Multiselect  from  "@vueform/multiselect"

export default {

    data() {
        return {
            pointings               :   [],

            start_date              :   "",
            end_date                :   "",

            //

            liste_route_link_all    :   [],
            liste_route_link        :   [],
            route_links             :   [],

            users_all               :   [],
            users                   :   [],
            selected_users          :   [],

            //

            list_datatable_poiting  :   {}
        }
    },

    components : {
        Multiselect :   Multiselect
    },

    async mounted() {

        this.start_date     =   new Date().toISOString().slice(0,10);
        this.end_date       =   new Date().toISOString().slice(0,10);

        //
        await this.getComboData()
    },

    methods : {

        async getData() {

            try {

                if((this.start_date  !=  "")&&(this.end_date  !=  "")) {

                    //
                    this.$showLoadingPage()

                    // Initialisation 
                    this.pointings  =   [];

                    //
                    let formData    =   new FormData()

                    if(this.route_links.length  >   0) {
                        formData.append("route_links"       , JSON.stringify(this.route_links))
                    }

                    if(this.selected_users.length  >   0) {
                        formData.append("selected_users"    , JSON.stringify(this.selected_users))
                    }

                    formData.append("start_date"    , this.start_date)
                    formData.append("end_date"      , this.end_date)

                    this.$callApi("post",    "/users/pointings",  formData)
                    .then(async (res)=> {

                        console.log(res)

                        //
                        this.pointings      =   res.data;
                        this.prepareDatatables()

                        //
                        this.$hideLoadingPage()
                    })
                }
            }

            catch(e) {

                console.log(e)
                this.$hideLoadingPage()
            }
        },

        //

        async getComboData() {

            //
            this.$showLoadingPage()

            const res_1                     =   await this.$callApi("post",     "/route_import/combo"       ,   null)
            const res_2                     =   await this.$callApi("post",     "/users/combo/backoffice"   ,   null)

            this.liste_route_import_all     =   res_1.data
            this.users_all                  =   res_2.data

            for (let i = 0; i < this.liste_route_import_all.length; i++) {

                this.liste_route_link.push({ value : this.liste_route_import_all[i].id , label : this.liste_route_import_all[i].libelle})
            }

            for (let i = 0; i < this.users_all.length; i++) {

                this.users.push({ value : this.users_all[i].id , label : this.users_all[i].username})
            }

            //
            this.$hideLoadingPage()
        },

        //

        async prepareDatatables() {

            for (let index = 0; index < this.pointings.length; index++) 
                this.list_datatable_poiting[this.pointings[index].id]   =   await this.$DataTableCreate("pointing_clients_"+this.pointings[index].id)
        }
    }
};

</script>

<style scoped>

.start_time_col, .end_time_col, .pointing_days_col {
    /* height : 300px; */
}

.pointing_days_parent {
    height : 300px;
    overflow : auto;
}

.pointing_clients_parent {
    overflow : auto;
}

.pointing_clients {
    overflow : auto
}

</style>