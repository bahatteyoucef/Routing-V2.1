<template>

    <!-- Modal -->
    <div class="modal fade" id="ModalClientsChangeRoute" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" v-if="clients">Clients : {{clients.length}} clients</h5>
                    <h5 class="modal-title" v-if="!clients">Clients : 0 clients</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3 table-responsive">

                    <div>
                        <div class="row mb-3">
                            <div class="col-sm-1">
                                <label for="operation"      class="form-label mt-1">Operation :</label>
                            </div>

                            <div class="col-sm-11">
                                <select                     class="form-select"     id="operation"          v-model="operation"     @change="changeOperation">
                                    <option value="multi_update">Multi update</option>
                                    <option value="devide_clients">Devide clients</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <!-- Multi Update -->
                    <form v-show="operation   ==  'multi_update'">
                        <div class="row mb-3">

                            <!-- Owner                        -->
                            <div class="col">
                                <div class="mb-3">
                                    <label for="owner"          class="form-label">Owner (Owner)</label>
                                    <select                     class="form-select"     id="owner"          v-model="owner">
                                        <option value=""></option>
                                        <option v-for="user in users"                   :key="user.id"      :value="user.id">{{user.nom}}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- DistrictNo                        -->
                            <div class="col">
                                <div class="mb-3">
                                    <label for="DistrictNo"         class="form-label">DistrictNo (DistrictNo)</label>
                                    <select                         class="form-select"         id="DistrictNo"                 v-model="DistrictNo"    @change="getCites()">
                                        <option value=""></option>
                                        <option v-for="district in districts"                   :key="district.DistrictNo"      :value="district.DistrictNo">{{district.DistrictNameE}} ({{district.DistrictNo}})</option>
                                    </select>
                                </div>
                            </div>

                            <!-- CityNo                            -->
                            <div class="col">
                                <div class="mb-3">
                                    <label for="CityNo"             class="form-label">CityNo (CityNo)</label>
                                    <select                         class="form-select"         id="CityNo"                     v-model="CityNo">
                                        <option value=""></option>
                                        <option v-for="cite in cites"           :key="cite.CITYNO"    :value="cite.CITYNO">{{cite.CityNameE}} ({{cite.CITYNO}})</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Status                             -->
                            <div class="col">
                                <label for="status"             class="form-label">Status</label>
                                <select                         class="form-select"         id="status"                         v-model="status">
                                    <option value="validated" selected>validated</option>
                                    <option value="pending">pending</option>
                                    <option value="nonvalidated">nonvalidated</option>
                                    <option value="visible">visible</option>
                                    <option value="ferme">ferme</option>
                                </select>
                            </div>

                            <!-- CustomerType                       -->
                            <div class="col">
                                <div class="mb-3">
                                    <label for="CustomerType"       class="form-label">CustomerType (CustomerType)</label>
                                    <input type="text"              class="form-control"        id="CustomerType"               v-model="CustomerType">
                                </div>
                            </div>

                            <!-- JPlan                              -->
                            <div class="col">
                                <div class="mb-3">
                                    <label for="JPlan"              class="form-label">JPlan (JPlan)</label>
                                    <input type="text"              class="form-control"        id="JPlan"                      v-model="JPlan">
                                </div>
                            </div>

                            <!-- Journee                            -->
                            <div class="col">
                                <div class="mb-3">
                                    <label for="Journee"            class="form-label">WorkDay (Journee)</label>
                                    <input type="text"              class="form-control"        id="Journee"                    v-model="Journee">
                                </div>
                            </div>

                        </div>

                        <!-- Table Clients -->
                        <div id="change_route_clients_container" class="table-container mt-5">
                            <table class="table table-hover table-bordered change_route_clients mt-0 mb-0 w-100" id="change_route_clients">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" @change="checkUncheckAllRows()" value="true" v-model="all_rows_checked"></th>
                                        <th role="button">#</th>
                                        <th v-for="change_route_client_column in change_route_clients_columns" :key="change_route_client_column" role="button">{{ change_route_client_column.title }}</th>
                                    </tr>

                                    <tr id="change_route_clients_filters" class="change_route_clients_filters">
                                        <th></th>
                                        <th></th>

                                        <th v-for="change_route_client_column in change_route_clients_columns" :key="change_route_client_column">
                                            <div class="filter_group" :data-column="change_route_client_column.title">
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
                                                    :placeholder="change_route_client_column.title"
                                                />
                                            </div>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody></tbody>
                            </table>
                        </div>
                    </form>

                    <!-- Resume -->
                    <div v-show="operation   ==  'devide_clients'">
                        <ResumeComponent 
                            ref="ResumeComponent_partly"
                            :key="clients" 

                            :mode="'permanent'"
                            :clients="clients_devide"
                            :id_route_import_tempo=null    
                            :id_route_import="$route.params.id_route_import"
                        >
                        </ResumeComponent>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"    @click="deleteClients()">Delete</button>

                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button                                     type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button v-if="operation === 'multi_update'" type="button" class="btn btn-primary" @click="multiUpdate()">Confirm</button>
                        <button v-if="operation === 'devide_clients'" type="button" class="btn btn-primary" @click="devideClients()">Confirm</button>                    
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

import DatatableHelper      from "@/services/DatatableHelper"

import ResumeComponent      from "@/components/routes/shared/parts/ResumeComponent.vue"

export default {

    data() {
        return {

            selected_row                            :   null    ,
            change_route_clients_columns            :   [
                                                            { data: "id"                    , title: "Id"                   },
                                                            { data: "CustomerCode"          , title: "CustomerCode"         },
                                                            { data: "CustomerNameE"         , title: "CustomerNameE"        },
                                                            { data: "CustomerNameA"         , title: "CustomerNameA"        },
                                                            { data: "DistrictNo"            , title: "DistrictNo"           },
                                                            { data: "DistrictNameE"         , title: "DistrictNameE"        },
                                                            { data: "CityNo"                , title: "CityNo"               },
                                                            { data: "CityNameE"             , title: "CityNameE"            },
                                                            { data: "CustomerBarCode_image" , title: "CustomerBarCode Image"},
                                                            { data: "in_store_image"        , title: "In Store Image"       },
                                                            { data: "facade_image"          , title: "Facade Image"         },
                                                            { data: "Address"               , title: "Address"              },
                                                            { data: "Neighborhood"          , title: "Neighborhood"         },
                                                            { data: "Landmark"              , title: "Landmark"             },
                                                            { data: "Latitude"              , title: "Latitude"             },
                                                            { data: "Longitude"             , title: "Longitude"            },
                                                            { data: "Tel"                   , title: "Tel"                  },
                                                            { data: "CustomerType"          , title: "CustomerType"         },
                                                            { data: "JPlan"                 , title: "JPlan"                },
                                                            { data: "Journee"               , title: "Journee"              },
                                                            { data: "comment"               , title: "Comment"              },
                                                            { data: "BrandAvailability"     , title: "BrandAvailability"    },
                                                            { data: "BrandSourcePurchase"   , title: "BrandSourcePurchase"  },
                                                            { data: "start_adding_time"     , title: "Start Adding Time"    },
                                                            { data: "adding_duration"       , title: "Adding Duration"      },
                                                            { data: "created_at"            , title: "Created At"           },
                                                            { data: "status"                , title: "Status"               },
                                                            { data: "owner"                 , title: "Owner"                }
                                                        ],

            datatable_change_route_clients          :   null    ,
            datatable_change_route_clients_instance :   null    ,

            //  //  //  //  //
            //  //  //  //  //
            //  //  //  //  //

            DistrictNo                      :   ""  ,
            CityNo                          :   ""  ,
            CustomerType                    :   ""  ,
            JPlan                           :   ""  ,
            Journee                         :   ""  ,
            owner                           :   ""  ,
            status                          :   ""  ,

            //

            all_clients                     :   []  ,
            clients                         :   []  ,

            clients_devide                  :   null,

            //

            districts                       :   []  ,
            cites                           :   []  ,
            users                           :   []  ,

            //

            all_rows_checked                :   true    ,

            //

            selected_row                    :   ""      ,
            selected_row_id                 :   ""      ,

            //

            operation                       :   "multi_update"
        }
    },

    props : ["districts_all", "users_all"],

    components : {
        ResumeComponent :   ResumeComponent
    },

    mounted() {

        //
        this.datatable_change_route_clients_instance    =   new DatatableHelper()

        //
        this.clearData("#ModalClientsChangeRoute")
    },  

    methods : {

        async setDataTable() {

            //
            this.datatable_change_route_clients     =   this.datatable_change_route_clients_instance.$DataTableCreate("change_route_clients", this.all_clients, this.change_route_clients_columns, this.setDataTable, null, null, null, this.selectRow, "Map Clients", "checkbox", this.checkRow, this.uncheckRow)
        },

        selectRow(selected_row, selected_row_id) {

            this.selected_row       =   selected_row
            this.selected_row_id    =   selected_row_id
        },

        async multiUpdate() {

            this.$showLoadingPage()

            let formData                =   new FormData()

            let clients_copy            =   [...this.clients]

            for (let i = 0; i < clients_copy.length; i++) {

                // Set Owner
                if(this.owner      !=  "") {

                    clients_copy[i].owner           =   this.owner
                    clients_copy[i].owner_name      =   this.getOwnerName(this.owner)
                }

                // Set District City
                if(this.DistrictNo      !=  "") {

                    clients_copy[i].DistrictNo      =   this.DistrictNo 
                    clients_copy[i].DistrictNameE   =   this.getDistrictNameE(this.DistrictNo)
                }

                if(this.CityNo          !=  "") {

                    clients_copy[i].CityNo          =   this.CityNo 
                    clients_copy[i].CityNameE       =   this.getCityNameE(this.CityNo)
                }

                if(this.CustomerType    !=  "") {
                    
                    clients_copy[i].CustomerType    =   this.CustomerType 
                }

                // Set JPlan
                if(this.JPlan           !=  "") {

                    clients_copy[i].JPlan           =   this.JPlan 
                }
                
                // Set Journee
                if(this.Journee         !=  "") {

                    clients_copy[i].Journee         =   this.Journee
                }

                // Set Status
                if(this.status          !=  "") {

                    clients_copy[i].status          =   this.status
                }
            }

            let clients     =   this.clientMapRightProperties(clients_copy) 

            //

            formData.append("clients", JSON.stringify(clients))

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/change_route",   formData)

            if(res.status===200){

                // 3) Show success toast (awaits timer/dismiss)
                this.$feedbackSuccess(res.data.header, res.data.message);

                // 5) Now hide the spinner
                this.$hideLoadingPage();

                // Send Client
                this.emitter.emit('reSetChangeRoute' , clients_copy)

                // Close Modal
                await this.$hideModal("ModalClientsChangeRoute")
            }
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)

                // 5) Now hide the spinner
                this.$hideLoadingPage();
			}
        },

        async devideClients() {

            this.$showLoadingPage()

            let formData    =   new FormData();

            let clients     =   this.clients_devide.map(obj => { return { id   :   obj.id  ,   JPlan   :   obj.JPlan   ,   Journee :   obj.Journee };});

            //

            console.log(clients)

            //

            formData.append("data"      ,   JSON.stringify(clients))

            const res                   =   await this.$callApi("post"  ,   "/clients/resume/update",  formData)
            console.log(res)

            if(res.status===200){

                // Send Feedback
                this.$feedbackSuccess(res.data.header     ,   res.data.message)

                // 5) Now hide the spinner
                this.$hideLoadingPage();

                //
                const clients_object = clients.reduce((acc, { id, JPlan, Journee }) => {
                    acc[id] = { JPlan, Journee };
                    return acc;
                }, {});

                //
                this.emitter.emit('reSetClientsDecoupeByJourneeMap' , clients_object)

                // Close Modal
                await this.$hideModal("ModalClientsChangeRoute")
            }
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)

                // Hide Loading Page
                this.$hideLoadingPage()
            }
        },

        async deleteClients() {

            this.$showLoadingPage()

            let formData                =   new FormData()

            let clients_ids_copy        =   [...this.clients].map(client => client.id)

            //

            formData.append("clients", JSON.stringify(clients_ids_copy))

            //

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/delete",   formData)

            if(res.status===200){

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                // 5) Now hide the spinner
                this.$hideLoadingPage();

                //
                let clients_copy            =   [...this.clients].map(client => { return { id : client.id }})  

                // Send Client
                this.emitter.emit('reSetChangeRouteDelete' , clients_copy)

                // Close Modal
                await this.$hideModal("ModalClientsChangeRoute")
            }
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)

                // 5) Now hide the spinner
                this.$hideLoadingPage();
			}
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                this.DistrictNo                     =   ''
                this.CityNo                         =   ''
                this.CustomerType                   =   ''
                this.JPlan                          =   ''
                this.Journee                        =   ''
                this.owner                          =   ''
                this.status                         =   ''

                //

                this.districts                      =   []  
                this.cites                          =   []  
                this.users                          =   []  
            });
        },

        //

        async getData(clients) {

            // Show Loading Page
            this.$showLoadingPage()

            // Set Value
            this.all_clients    =   [...clients]
            this.clients        =   [...clients]

            //
            this.clients_devide =   clients.map(({ id, JPlan, Journee, CustomerType, Latitude, Longitude }) => ({ id, JPlan, Journee, CustomerType, Latitude, Longitude }))

            //
            await this.setDataTable()

            //
            await this.getComboData()

            //
            this.$refs.ResumeComponent_partly.setResume()

            //
            this.checkUncheckAllRows()

            // Hide Loading Page
            this.$hideLoadingPage()
        },

        async getComboData() {

            if(this.users_all) {
                this.users          =   this.users_all  
            }

            else {
                const res_1         =   await this.$callApi("post"  ,   "/users/combo"  ,   null)
                this.users          =   res_1.data
            }

            //

            if(this.districts_all) {
                this.districts      =   this.districts_all  
            }

            else {
                const res_2         =   await this.$callApi("post"  ,   "/rtm_willayas"         ,   null)
                this.districts      =   res_2.data
            }
        },

        async getCites() {

            // Show Loading Page
            this.$showLoadingPage()

            const res_3                     =   await this.$callApi("post"  ,   "/rtm_willayas/"+this.DistrictNo+"/rtm_cites"         ,   null)
            this.cites                      =   res_3.data

            // Hide Loading Page
            this.$hideLoadingPage()
        },

        //

        checkRow(row) {

            const index     =   this.clients.findIndex(client => client.id === row.id)

            //
            if (index == -1)   this.clients.push(row)
        },

        uncheckRow(row) {

            const index =   this.clients.findIndex(client => client.id === row.id)

            //
            if (index !== -1)   this.clients.splice(index, 1)
        },

        checkUncheckAllRows() {

            if(this.all_rows_checked)   this.datatable_change_route_clients_instance.$checkUncheckAllRows("change_route_clients", this.datatable_change_route_clients, true)
            else                        this.datatable_change_route_clients_instance.$checkUncheckAllRows("change_route_clients", this.datatable_change_route_clients, false)
        },

        //

        getOwnerName(owner) {

            for (let i = 0; i < this.users.length; i++) {

                if(this.users[i].id     ==  owner) {

                    return this.users[i].nom
                }                
            }
        },

        getDistrictNameE(DistrictNo) {

            for (let i = 0; i < this.districts.length; i++) {

                if(this.districts[i].DistrictNo  ==  DistrictNo) {

                    return this.districts[i].DistrictNameE
                }                
            }
        },

        getCityNameE(CityNo) {

            for (let i = 0; i < this.cites.length; i++) {

                if(this.cites[i].CITYNO  ==  CityNo) {

                    return this.cites[i].CityNameE
                }                
            }
        },

        //

        clientMapRightProperties(clients) {

            const fields = ['owner', 'owner_name', 'DistrictNo', 'DistrictNameE', 'CityNo', 'CityNameE', 'CustomerType', 'JPlan', 'Journee', 'status'];
            
            return clients.map(obj => {
                let result = { id: obj.id };
                
                fields.forEach(field => {
                    if (this[field] !== "") {
                        result[field] = obj[field];
                    }
                });
                
                return result;
            });
        },

        //  //  //

        async changeOperation() {

        }
    }
};

</script>