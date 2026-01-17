<template>
    <div class="content-wrapper p-3">
        <div class="card">
            <div class="card-body">

                <!-- Header -->
                <h4 class="fw-bold" v-if="route_import"><u>Clients of route import : <span class="text_primary">{{ route_import.libelle }}</span></u></h4>

                <!-- Export Data    -->
                <div class="row mt-2 gy-1">
                    <div class="col-sm-4">
                        <select     class="form-select form-select-sm"      v-model="status">
                            <option value="All">All</option>
                            <option value="Validated">Validated</option>
                            <option value="Pending">Pending</option>
                            <option value="NonValidated">NonValidated</option>
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <button class="btn btn-sm btn-primary w-100" @click="downloadData()">Export Data</button>
                    </div>

                    <div class="col-sm-2">
                        <button class="btn btn-sm btn-primary w-100" @click="downloadCustomerCodeImages()">Export Code Bar Images</button>
                    </div>

                    <div class="col-sm-2">
                        <button class="btn btn-sm btn-primary w-100" @click="downloadFacadeImages()">Export Facade Images</button>
                    </div>

                    <div class="col-sm-2">
                        <button class="btn btn-sm btn-primary w-100" @click="downloadInStoreImages()">Export In Store Images</button>
                    </div>
                </div>

                <!--  -->
                <!--  -->
                <!--  -->

                <div id="route_import_clients_container" class="table-container mt-5">
                    <table class="table table-hover table-bordered route_import_clients mt-0 mb-0 w-100" id="route_import_clients">
                        <thead>
                            <tr>
                                <th role="button">#</th>
                                <th v-for="route_import_client_column in route_import_clients_columns" :key="route_import_client_column" role="button">{{ route_import_client_column.title }}</th>
                            </tr>

                            <tr id="route_import_clients_filters" class="route_import_clients_filters">
                                <th></th>

                                <th v-for="route_import_client_column in route_import_clients_columns" :key="route_import_client_column">
                                    <div class="filter_group" :data-column="route_import_client_column.title">
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
                                            :placeholder="route_import_client_column.title"
                                        />
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Add                      -->
    <ModalClientAdd             ref="ModalClientAdd"            :id_route_import="getRouteImportId"                                                                 :users_all="users_all"      :districts_all="districts_all">   </ModalClientAdd>

    <!-- Modal Update                   -->
    <ModalClientUpdate          ref="ModalClientUpdate"         :id_route_import="getRouteImportId"     :update_type="'normal_update'"      :mode="'permanent'"     :users_all="users_all"      :districts_all="districts_all">   </ModalClientUpdate>
</template>
 
<script>

import HeaderComponent              from    "@/template/components/HeaderComponent.vue"

import ModalClientAdd               from    "@/components/clients/shared/ModalClientAdd.vue"
import ModalClientUpdate            from    "@/components/clients/shared/ModalClientUpdate.vue"

import {mapGetters, mapActions}     from    "vuex"

import DatatableHelper              from    "@/services/DatatableHelper"

import emitter                  from    "@/utils/emitter"

export default {

    data() {
        return {

            route_import_clients_data           :   []      ,
            route_import_clients_columns        :   [
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

            datatable_route_import_clients          :   null    ,
            datatable_route_import_clients_instance :   null    ,

            //

            route_import                            :   null    ,

            //

            status                                  :   "All"   ,
            clients_export                          :   []      ,

            //

            users_all                               :   []      ,
            districts_all                           :   []      ,

            //

            selected_row                            :   null    ,
            selected_row_id                         :   null
        }
    },

    components : {
        HeaderComponent             ,

        ModalClientAdd              ,
        ModalClientUpdate           
    },

    computed : {
        ...mapGetters({
            getUser                 :   'authentification/getUser'
        }),

        getRouteImportId() {

            return this.$route.params.id_route_import
        }
    },

    async mounted() {

        //
        this.datatable_route_import_clients_instance    =   new DatatableHelper()

        //
        emitter.on('reSetAdd'          , async (client)    =>  {
            await this.addClientToDatatable(client)
        })

        emitter.on('reSetUpdate'       , async (client)    =>  {
            await this.updateClientToDatatable(client)
        })

        emitter.on('reSetDelete'       , async (client)    =>  {
            await this.deleteClientToDatatable(client)
        })

        await this.setDataTable()
        await this.getComboData()
    },

    unmounted() {

        emitter.off('reSetAdd')
        emitter.off('reSetUpdate')
        emitter.off('reSetDelete')
    },

    methods : {

        async setDataTable() {

            await this.$showLoadingPage()

            // Initialisation 
            this.route_import_clients_data  =   [];

            this.$callApi("post",   "/route-imports/"+this.$route.params.id_route_import+"/clients",     null)
            .then(async (res)=> {

                this.route_import                       =   res.data.route_import
                this.route_import_clients_data          =   res.data.clients

                // Create DataTable
                this.datatable_route_import_clients     =   this.datatable_route_import_clients_instance.$DataTableCreate("route_import_clients", this.route_import_clients_data, this.route_import_clients_columns, this.setDataTable, null, null, null, this.selectRow, "Map Clients")      

                //
                await this.$hideLoadingPage()
            })
        },

        //

        async getComboData() {

            await this.$showLoadingPage()

            const res_1         =   await this.$callApi("post"  ,   "/users/combo"      ,   null)
            const res_2         =   await this.$callApi("post"  ,   "/rtm_willayas"     ,   null)

            this.users_all      =   res_1.data.users
            this.districts_all  =   res_2.data.willayas

            await this.$hideLoadingPage()
        },

        //  //  //  //  //

        //  //  //  //  //

        //  //  //  //  //

        async downloadCustomerCodeImages() {

            //
            await this.$showLoadingPage()

            let formData                        =   new FormData()

            formData.append("status"            ,   this.status)
            formData.append("id_route_import"   ,   this.$route.params.id_route_import)

            this.$callApiResponse('post', '/route-imports/all-data/images/customer-code', formData, 'blob')
            .then(async (response) => {

                if(response.status  ==  200) {

                    const url   =   window.URL.createObjectURL(new Blob([response.data]));
                    const link  =   document.createElement('a');

                    link.href   =   url;

                    link.setAttribute('download', this.route_import.libelle+' Customer Code Images.zip');

                    document.body.appendChild(link);

                    link.click();

                    //
                    await this.$hideLoadingPage()
                }

                else {

                    //
                    await this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", ["No Images to Download"])
                }

            }).catch(error => {

                console.error('Error downloading file:', error);
            });
        },

        async downloadFacadeImages() {

            //
            await this.$showLoadingPage()

            let formData                        =   new FormData()

            formData.append("status"            ,   this.status)
            formData.append("id_route_import"   ,   this.$route.params.id_route_import)

            this.$callApiResponse('post', '/route-imports/all-data/images/facade', formData, 'blob')
            .then(async (response) => {

                if(response.status  ==  200) {

                    const url   =   window.URL.createObjectURL(new Blob([response.data]));
                    const link  =   document.createElement('a');

                    link.href   =   url;

                    link.setAttribute('download', this.route_import.libelle+' Facade Images.zip');

                    document.body.appendChild(link);

                    link.click();

                    //
                    await this.$hideLoadingPage()
                }

                else {

                    //
                    await this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", ["No Images to Download"])
                }

            }).catch(error => {

                console.error('Error downloading file:', error);
            });
        },

        async downloadInStoreImages() {

            //
            await this.$showLoadingPage()

            let formData                        =   new FormData()

            formData.append("status"            ,   this.status)
            formData.append("id_route_import"   ,   this.$route.params.id_route_import)

            this.$callApiResponse('post', '/route-imports/all-data/images/in-store', formData, 'blob')
            .then(async (response) => {

                if(response.status  ==  200) {

                    const url   =   window.URL.createObjectURL(new Blob([response.data]));
                    const link  =   document.createElement('a');

                    link.href   =   url;

                    link.setAttribute('download', this.route_import.libelle+' In Store Images.zip');

                    document.body.appendChild(link);

                    link.click();

                    //
                    await this.$hideLoadingPage()
                }

                else {

                    //
                    await this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", ["No Images to Download"])
                }

            }).catch(error => {

                console.error('Error downloading file:', error);
            });
        },

        //

        async downloadData() {

            //
            await this.$showLoadingPage()

            // Initialisation 
            this.route_import_clients_data_export     =   [];

            let formData            =   new FormData()

            formData.append("status"      ,   this.status)
            formData.append("id_route_import"   ,   this.$route.params.id_route_import)

            this.$callApi("post",   "/route-imports/all-data", formData)
            .then(async (res)=> {

                this.route_import_clients_data_export  =   res.data.clients;

                await this.exportToExcel()

                //
                await this.$hideLoadingPage()
            })
        },

        async exportToExcel() {

            const worksheet     =   XLSX.utils.json_to_sheet(this.route_import_clients_data_export);
            const workbook      =   XLSX.utils.book_new();

            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

            const excelBuffer   =   XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
            await this.saveExcelFile(excelBuffer, this.route_import.libelle+' Clients.xlsx');
        },

        async saveExcelFile(buffer, filename) {

            const data = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

            if (window.navigator.msSaveOrOpenBlob) {
            
                // For IE
                window.navigator.msSaveOrOpenBlob(data, filename);
            } 

            else {
            
                // For other browsers
                const a     =   document.createElement('a');
                const url   =   URL.createObjectURL(data);

                a.href      =   url;
                a.download  =   filename;

                document.body.appendChild(a);

                a.click();

                await this.$nextTick()

                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }
        },

        //  //  //  //  //

        selectRow(selected_row, selected_row_id) {

            this.selected_row       =   selected_row
            this.selected_row_id    =   selected_row_id
        },
    }
};

</script>