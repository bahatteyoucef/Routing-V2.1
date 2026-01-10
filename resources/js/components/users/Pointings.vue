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

        <div v-if="users_pointings" class="col-sm-12 p-2 mt-2">
            <div v-for="user_pointing, index_1 in users_pointings" :key="index_1" class="mt-3">
                <!-- User Pointing Details -->
                <div class="row h-equal p-0 pl-2 m-0">
                    <div class="col p-1">
                        <div v-if="user_pointing.details.number_clients_confirmed   !=  null" class="card h-100 card-img-holder text-white p-3"       style="background-color: #0F9D58">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Confirmed <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{user_pointing.details.number_clients_confirmed}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="user_pointing.details.number_clients_validated   !=  null" class="card h-100 card-img-holder text-white p-3"       style="background-color: #0F9D58">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{user_pointing.details.number_clients_validated}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="user_pointing.details.number_clients_ferme   !=  null" class="card h-100 card-img-holder text-white p-3"           style="background-color: #0288D1;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Ferme <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{user_pointing.details.number_clients_ferme}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="user_pointing.details.number_clients_refus   !=  null" class="card h-100 card-img-holder text-white p-3"           style="background-color: #880E4F;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Refus <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{user_pointing.details.number_clients_refus}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="user_pointing.details.number_clients_introuvable   !=  null" class="card h-100 card-img-holder text-white p-3"     style="background-color: #000000;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Introuvable <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{user_pointing.details.number_clients_introuvable}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="user_pointing.details.number_clients_pending !=  null" class="card h-100 card-img-holder text-white p-3"           style="background-color: #F57C00;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Pending <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{user_pointing.details.number_clients_pending}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="user_pointing.details.number_clients_nonvalidated    !=  null" class="card h-100 card-img-holder text-white p-3"   style="background-color: #F70000;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Non Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{user_pointing.details.number_clients_nonvalidated}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col p-1">
                        <div v-if="user_pointing.details.number_clients_visible !=  null" class="card h-100 card-img-holder text-white p-3"           style="background-color: #3949AB;">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">Visible <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{user_pointing.details.number_clients_visible}}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Pointing Start Time + Days -->
                <div class="row h-equal p-0 pl-2 m-0">
                    <div class="col-sm-2 p-1">
                        <div v-if="user_pointing.username   !=  null" class="card h-100 card-img-holder p-3">
                            <div class="card-body p-1">
                                <h4 class="font-weight-normal mb-3">User<i class="mdi mdi-account-outline mdi-24px float-right"></i></h4>
                                <h2 class="mb-1">{{user_pointing.username}}</h2>
                            </div>
                        </div>
                    </div>

                    <!-- User Pointing Start Time -->
                    <div class="col-sm-2 p-1">
                        <div :id="'start_time_col_'+user_pointing.id" class="h-50">
                            <div v-if="user_pointing.details.start_time   !=  null" class="card h-100 p-3">
                                <div class="card-body p-1">
                                    <h4 class="font-weight-normal mb-3">Start Time <i class="mdi mdi-clock-outline mdi-24px float-right"></i></h4>
                                    <h2 class="mb-1">{{user_pointing.details.start_time}}</h2>
                                </div>
                            </div>

                            <div v-else class="card h-100 p-3">
                                <div class="card-body p-1">
                                    <h4 class="font-weight-normal mb-3">Start Time <i class="mdi mdi-clock-outline mdi-24px float-right"></i></h4>
                                    <h2 class="mb-1">00:00:00</h2>
                                </div>
                            </div>
                        </div>

                        <div :id="'end_time_col_'+user_pointing.id" class="end_time_col h-50">
                            <div v-if="user_pointing.details.end_time   !=  null" class="card h-100 p-3">
                                <div class="card-body p-1">
                                    <h4 class="font-weight-normal mb-3">End Time <i class="mdi mdi-clock-outline mdi-24px float-right"></i></h4>
                                    <h2 class="mb-1">{{user_pointing.details.end_time}}</h2>
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

                    <!-- User Pointing Days -->
                    <div class="col-sm-8 p-1 user_pointing_days_col"  :id="'user_pointing_days_col_'+user_pointing.id">
                        <div v-if="user_pointing.clients" class="card h-100 p-3">
                            <div :id="'user_pointing_days_parent_'+user_pointing.id"   class="user_pointing_days_parent table_scroll table_container">
                                <table  class="table table-bordered user_pointing_days" id="user_pointing_days">
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
                                            <th>{{ user_pointing.details.start_time }}</th>
                                            <th>{{ user_pointing.details.end_time }}</th>
                                            <th>{{ user_pointing.details.number_clients_confirmed }}</th>
                                            <th>{{ user_pointing.details.number_clients_validated }}</th>
                                            <th>{{ user_pointing.details.number_clients_ferme }}</th>
                                            <th>{{ user_pointing.details.number_clients_refus }}</th>
                                            <th>{{ user_pointing.details.number_clients_introuvable }}</th>
                                            <th>{{ user_pointing.details.number_clients_pending }}</th>
                                            <th>{{ user_pointing.details.number_clients_nonvalidated }}</th>
                                            <th>{{ user_pointing.details.number_clients_visible }}</th>
                                        </tr>

                                        <tr v-for="day, index_2 in user_pointing.days" :key="index_2">
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

                <!-- User Pointing End Time + Clients -->
                <div class="row h-equal p-0 pl-2 m-0">
                    <div class="col-sm-12 p-1"  id="clients_col">
                        <div v-if="user_pointing.clients" class="card h-100 p-3">
                            <div :id="'user_pointing_clients_'+user_pointing.id+'_container'" class="table-container mt-5">
                                <table  class="table table-bordered user_pointing_clients" :id="'user_pointing_clients_'+user_pointing.id">
                                    <thead>
                                        <tr>
                                            <th role="button">#</th>
                                            <th v-for="user_pointing_clients_column in user_pointing_clients_columns" :key="user_pointing_clients_column" role="button">{{ user_pointing_clients_column.title }}</th>
                                        </tr>
                                    </thead>

                                    <thead>
                                        <tr :id="'user_pointing_clients_'+user_pointing.id+'_filters'" class="user_pointing_clients_filters">
                                            <th></th>

                                            <th v-for="user_pointing_clients_column in user_pointing_clients_columns" :key="user_pointing_clients_column">
                                                <div class="filter_group" :data-column="user_pointing_clients_column.title">
                                                    <select class="filter_type form-select-sm w-100 mb-2">
                                                        <option value="contains">contains</option>
                                                        <option value="not_contains">not contains</option>
                                                        <option value="exact">exact</option>
                                                        <option value="starts_with">starts with</option>
                                                        <option value="ends_with">ends with</option>
                                                    </select>
                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm filter_input"
                                                        :placeholder="user_pointing_clients_column.title"
                                                    />
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
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

import Multiselect          from    "@vueform/multiselect"

import DatatableHelper      from    "@/services/DatatableHelper"

export default {

    data() {
        return {
            user_pointing_clients_columns   :   [
                                                    { data: "id"                        , title: "Id"                   },
                                                    { data: "created_at"                , title: "Created At"           },
                                                    { data: "status"                    , title: "Status"               },
                                                    { data: "owner_username"            , title: "Owner"                },
                                                    { data: "CustomerIdentifier"        , title: "CustomerIdentifier"   },
                                                    // { data: "CustomerBarCodeExiste"     , title: "CustomerBarCodeExiste"},
                                                    { data: "CustomerCode"              , title: "CustomerCode"         },
                                                    { data: "CustomerNameE"             , title: "CustomerNameE"        },
                                                    { data: "CustomerNameA"             , title: "CustomerNameA"        },
                                                    { data: "DistrictNameE"             , title: "DistrictNameE"        },
                                                    { data: "CityNameE"                 , title: "CityNameE"            },
                                                    { data: "Address"                   , title: "Address"              },
                                                    { data: "Tel"                       , title: "Tel"                  },
                                                    { data: "CustomerType"              , title: "CustomerType"         },
                                                    // { data: "Comment"                   , title: "Comment"              },
                                                ],

            //

            users_pointings         :   [],

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

            list_datatable_users_pointings  :   {}
        }
    },

    components : {
        Multiselect :   Multiselect
    },

    async mounted() {

        this.datatable_users_pointings_instance     =   new DatatableHelper()   

        //
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
                    await this.$showLoadingPage()

                    // Initialisation 
                    this.users_pointings  =   [];

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
                        this.users_pointings      =   res.data.pointings;
                        await this.prepareDatatables()

                        //
                        await this.$hideLoadingPage()
                    })
                }
            }

            catch(e) {

                console.log(e)
                await this.$hideLoadingPage()
            }
        },

        //

        async getComboData() {

            //
            await this.$showLoadingPage()

            const res_1                     =   await this.$callApi("post",     "/route-imports/combo"       ,   null)
            const res_2                     =   await this.$callApi("post",     "/users/combo/backoffice"   ,   null)

            console.log(res_1)
            console.log(res_2)

            this.liste_route_import_all     =   res_1.data.liste_route_import
            this.users_all                  =   res_2.data.users

            for (let i = 0; i < this.liste_route_import_all.length; i++) {

                this.liste_route_link.push({ value : this.liste_route_import_all[i].id , label : this.liste_route_import_all[i].libelle})
            }

            for (let i = 0; i < this.users_all.length; i++) {

                this.users.push({ value : this.users_all[i].id , label : this.users_all[i].username })
            }

            //
            await this.$hideLoadingPage()
        },

        //

        async prepareDatatables() {

            await this.$nextTick(() => {
                for (let index = 0; index < this.users_pointings.length; index++) {
                    this.list_datatable_users_pointings[this.users_pointings[index].id]     =   this.datatable_users_pointings_instance.$DataTableCreate("user_pointing_clients_"+this.users_pointings[index].id, this.users_pointings[index].clients, this.user_pointing_clients_columns, null, null, null, null, null, "Pointings")      
                }
            });
        }
    }
};

</script>

<style scoped>

.start_time_col, .end_time_col, .user_pointing_days_col {
    /* height : 300px; */
}

.user_pointing_days_parent {
    height : 300px;
    overflow : auto;
}

.user_pointing_clients_parent {
    overflow : auto;
}

.user_pointing_clients {
    overflow : auto
}

</style>