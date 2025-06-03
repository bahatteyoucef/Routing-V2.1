<template>
    <div class="content-wrapper p-3">
        <div class="card">
            <div class="card-body">

                <!-- Header -->
                <h4 class="fw-bold" v-if="route_import"><u>Clients of route import : <span class="text_primary">{{ route_import.libelle }}</span></u></h4>

                <!-- Export Data    -->
                <div class="row mt-2">
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
    <ModalClientAdd             ref="ModalClientAdd"            :id_route_import="getRouteImportId"                             >   </ModalClientAdd>

    <!-- Modal Update                   -->
    <ModalClientUpdate          ref="ModalClientUpdate"         :id_route_import="getRouteImportId"      :mode="'permanent'"    >   </ModalClientUpdate>
</template>
 
<script>

import HeaderComponent              from    "@/template/components/HeaderComponent.vue"

import ModalClientAdd               from    "@/components/clients/shared/ModalClientAdd.vue"
import ModalClientUpdate            from    "@/components/clients/shared/ModalClientUpdate.vue"

import {mapGetters, mapActions}     from    "vuex"

import DatatableHelper              from    "@/services/DatatableHelper"

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
        this.emitter.on('reSetAdd'          , async (client)    =>  {
            await this.addClientToDatatable(client)
        })

        this.emitter.on('reSetUpdate'       , async (client)    =>  {
            await this.updateClientToDatatable(client)
        })

        this.emitter.on('reSetDelete'       , async (client)    =>  {
            await this.deleteClientToDatatable(client)
        })

        await this.setDataTable()
    },

    unmounted() {

        this.emitter.off('reSetAdd')
        this.emitter.off('reSetUpdate')
        this.emitter.off('reSetDelete')
    },

    methods : {

        ...mapActions("client" ,  [
            "setUpdateClientAction"   ,
        ]),

        //

        async setDataTable() {

            this.$showLoadingPage()

            // Initialisation 
            this.route_import_clients_data  =   [];

            this.$callApi("post",   "/route_import/"+this.$route.params.id_route_import+"/clients",     null)
            .then(async (res)=> {

                this.route_import                       =   res.data.route_import
                this.route_import_clients_data          =   res.data.clients

                // Create DataTable
                this.datatable_route_import_clients     =   this.datatable_route_import_clients_instance.$DataTableCreate("route_import_clients", this.route_import_clients_data, this.route_import_clients_columns, this.setDataTable, this.addElement, this.updateElement, null, this.selectRow, "Route Import Clients")      

                //
                this.$hideLoadingPage()
            })
        },

        //

        async addElement() {
            
            // ShowModal
            var addModal    =   new Modal(document.getElementById("ModalClientAdd"));
            addModal.show();

            let client      =   { lat : 0, lng : 0 }
            await this.$refs.ModalClientAdd.getData(client, this.route_import_clients_data)
        },

        async updateElement(selected_row) {

            if(selected_row) {
                var updateModal     =   new Modal(document.getElementById("ModalClientUpdate"));
                updateModal.show();

                await this.$refs.ModalClientUpdate.getData(selected_row, this.route_import_clients_data)
            }
        },

        //  //  //  //  //

        //  //  //  //  //

        //  //  //  //  //

        async downloadCustomerCodeImages() {

            //
            this.$showLoadingPage()

            let formData                        =   new FormData()

            formData.append("status"            ,   this.status)
            formData.append("id_route_import"   ,   this.$route.params.id_route_import)

            this.$callApiResponse('post', '/route_import/all_data/images/customer_code', formData, 'blob')
            .then(response => {

                if(response.status  ==  200) {

                    const url   =   window.URL.createObjectURL(new Blob([response.data]));
                    const link  =   document.createElement('a');

                    link.href   =   url;

                    link.setAttribute('download', this.route_import.libelle+' Customer Code Images.zip');

                    document.body.appendChild(link);

                    link.click();

                    //
                    this.$hideLoadingPage()
                }

                else {

                    // Send Errors
                    this.$showErrors("Error !", ["No Images to Download"])

                    //
                    this.$hideLoadingPage()
                }

            }).catch(error => {

                console.error('Error downloading file:', error);
            });
        },

        async downloadFacadeImages() {

            //
            this.$showLoadingPage()

            let formData                        =   new FormData()

            formData.append("status"            ,   this.status)
            formData.append("id_route_import"   ,   this.$route.params.id_route_import)

            this.$callApiResponse('post', '/route_import/all_data/images/facade', formData, 'blob')
            .then(response => {

                if(response.status  ==  200) {

                    const url   =   window.URL.createObjectURL(new Blob([response.data]));
                    const link  =   document.createElement('a');

                    link.href   =   url;

                    link.setAttribute('download', this.route_import.libelle+' Facade Images.zip');

                    document.body.appendChild(link);

                    link.click();

                    //
                    this.$hideLoadingPage()
                }

                else {

                    // Send Errors
                    this.$showErrors("Error !", ["No Images to Download"])

                    //
                    this.$hideLoadingPage()
                }

            }).catch(error => {

                console.error('Error downloading file:', error);
            });
        },

        async downloadInStoreImages() {

            //
            this.$showLoadingPage()

            let formData                        =   new FormData()

            formData.append("status"            ,   this.status)
            formData.append("id_route_import"   ,   this.$route.params.id_route_import)

            this.$callApiResponse('post', '/route_import/all_data/images/in_store', formData, 'blob')
            .then(response => {

                if(response.status  ==  200) {

                    const url   =   window.URL.createObjectURL(new Blob([response.data]));
                    const link  =   document.createElement('a');

                    link.href   =   url;

                    link.setAttribute('download', this.route_import.libelle+' In Store Images.zip');

                    document.body.appendChild(link);

                    link.click();

                    //
                    this.$hideLoadingPage()
                }

                else {

                    // Send Errors
                    this.$showErrors("Error !", ["No Images to Download"])

                    //
                    this.$hideLoadingPage()
                }

            }).catch(error => {

                console.error('Error downloading file:', error);
            });
        },

        //

        async downloadData() {

            //
            this.$showLoadingPage()

            // Initialisation 
            this.route_import_clients_data_export     =   [];

            let formData            =   new FormData()

            formData.append("status"      ,   this.status)
            formData.append("id_route_import"   ,   this.$route.params.id_route_import)

            this.$callApi("post",   "/route_import/all_data", formData)
            .then(async (res)=> {

                this.route_import_clients_data_export  =   res.data.clients;

                await this.exportToExcel()

                //
                this.$hideLoadingPage()
            })
        },

        async exportToExcel() {

            const worksheet     =   XLSX.utils.json_to_sheet(this.route_import_clients_data_export);
            const workbook      =   XLSX.utils.book_new();

            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

            const excelBuffer   =   XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveExcelFile(excelBuffer, this.route_import.libelle+' Clients.xlsx');
        },

        saveExcelFile(buffer, filename) {

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

                setTimeout(() => {

                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                }, 0);
            }
        },

        //  //  //  //  //

        //  //  //  //  //

        //  //  //  //  //

        async addClientToDatatable(client) {

            let new_client      =   {}

            // Add Client
            new_client.id                       =   client.id

            new_client.CustomerIdentifier       =   client.CustomerIdentifier

            new_client.CustomerCode             =   client.CustomerCode

            new_client.CustomerNameE            =   client.CustomerNameE
            new_client.CustomerNameA            =   client.CustomerNameA
            new_client.Tel                      =   client.Tel

            new_client.Latitude                 =   client.Latitude         
            new_client.Longitude                =   client.Longitude        

            new_client.Address                  =   client.Address
            new_client.Neighborhood             =   client.Neighborhood
            new_client.Landmark                 =   client.Landmark

            new_client.DistrictNo               =   client.DistrictNo      
            new_client.DistrictNameE            =   client.DistrictNameE 

            new_client.CityNo                   =   client.CityNo           
            new_client.CityNameE                =   client.CityNameE       

            new_client.CustomerType             =   client.CustomerType     

            new_client.BrandAvailability        =   client.BrandAvailability       
            new_client.BrandSourcePurchase      =   client.BrandSourcePurchase       

            new_client.JPlan                    =   client.JPlan            
            new_client.Journee                  =   client.Journee        

            new_client.Frequency                =   client.Frequency
            new_client.SuperficieMagasin        =   client.SuperficieMagasin
            new_client.NbrAutomaticCheckouts    =   client.NbrAutomaticCheckouts

            new_client.AvailableBrands                      =   client.AvailableBrands                      // should be json
            new_client.AvailableBrands_array_formatted      =   client.AvailableBrands_array_formatted      // should be array
            new_client.AvailableBrands_string_formatted     =   client.AvailableBrands_string_formatted     // should be string

            new_client.status                   =   client.status            
            new_client.nonvalidated_details     =   client.nonvalidated_details        

            new_client.comment                  =   client.comment

            new_client.facade_image                         =   client.facade_image            
            new_client.in_store_image                       =   client.in_store_image        
            new_client.facade_image_original_name           =   client.facade_image_original_name            
            new_client.in_store_image_original_name         =   client.in_store_image_original_name        
            new_client.CustomerBarCode_image                =   client.CustomerBarCode_image            
            new_client.CustomerBarCode_image_original_name  =   client.CustomerBarCode_image_original_name        

            new_client.owner_name       =   this.getUser.nom
            new_client.created_at       =   this.$formatDate(new Date())
 
            this.route_import_clients_data.push(new_client)

            //

            // Refresh DataTable
            this.datatable_route_import_clients     =   this.datatable_route_import_clients_instance.$DataTableCreate("route_import_clients", this.route_import_clients_data, this.route_import_clients_columns, this.setDataTable, this.addElement, this.updateElement, null, this.selectRow, "Route Import Clients")      
        },

        async updateClientToDatatable(client) {

            for (let i = 0; i < this.route_import_clients_data.length; i++) {
                
                if(this.route_import_clients_data[i].id  ==  client.id) {

                    this.route_import_clients_data[i].CustomerIdentifier    =   client.CustomerIdentifier
                    this.route_import_clients_data[i].CustomerCode          =   client.CustomerCode

                    this.route_import_clients_data[i].CustomerNameE         =   client.CustomerNameE
                    this.route_import_clients_data[i].CustomerNameA         =   client.CustomerNameA
                    this.route_import_clients_data[i].Tel                   =   client.Tel

                    this.route_import_clients_data[i].Latitude              =   client.Latitude         
                    this.route_import_clients_data[i].Longitude             =   client.Longitude        

                    this.route_import_clients_data[i].Address               =   client.Address
                    this.route_import_clients_data[i].Neighborhood          =   client.Neighborhood
                    this.route_import_clients_data[i].Landmark              =   client.Landmark

                    this.route_import_clients_data[i].DistrictNo            =   client.DistrictNo      
                    this.route_import_clients_data[i].DistrictNameE         =   client.DistrictNameE  

                    this.route_import_clients_data[i].CityNo                =   client.CityNo           
                    this.route_import_clients_data[i].CityNameE             =   client.CityNameE       

                    this.route_import_clients_data[i].CustomerType          =   client.CustomerType     

                    this.route_import_clients_data[i].BrandAvailability     =   client.BrandAvailability       
                    this.route_import_clients_data[i].BrandSourcePurchase   =   client.BrandSourcePurchase       

                    this.route_import_clients_data[i].JPlan                 =   client.JPlan            
                    this.route_import_clients_data[i].Journee               =   client.Journee        

                    this.route_import_clients_data[i].status                =   client.status            
                    this.route_import_clients_data[i].nonvalidated_details   =   client.nonvalidated_details        

                    this.route_import_clients_data[i].comment                =   client.comment        

                    this.route_import_clients_data[i].facade_image                           =   client.facade_image            
                    this.route_import_clients_data[i].in_store_image                         =   client.in_store_image        
                    this.route_import_clients_data[i].facade_image_original_name             =   client.facade_image_original_name            
                    this.route_import_clients_data[i].in_store_image_original_name           =   client.in_store_image_original_name        
                    this.route_import_clients_data[i].CustomerBarCode_image                  =   client.CustomerBarCode_image            
                    this.route_import_clients_data[i].CustomerBarCode_image_original_name    =   client.CustomerBarCode_image_original_name        

                    break
                }
            }

            //

            // Destroy DataTable
            this.datatable_route_import_clients     =   this.datatable_route_import_clients_instance.$DataTableCreate("route_import_clients", this.route_import_clients_data, this.route_import_clients_columns, this.setDataTable, this.addElement, this.updateElement, null, this.selectRow, "Route Import Clients")      
        },

        async deleteClientToDatatable(client) {

            for (let i = 0; i < this.route_import_clients_data.length; i++) {
                
                if(this.route_import_clients_data[i].id  ==  client.id) {

                    this.route_import_clients_data.splice(i, 1)

                    break
                }
            }

            //
            this.datatable_route_import_clients     =   this.datatable_route_import_clients_instance.$DataTableCreate("route_import_clients", this.route_import_clients_data, this.route_import_clients_columns, this.setDataTable, this.addElement, this.updateElement, null, this.selectRow, "Route Import Clients")      
        },

        //

        selectRow(selected_row, selected_row_id) {

            this.selected_row       =   selected_row
            this.selected_row_id    =   selected_row_id
        },
    }
};

</script>