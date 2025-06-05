<template>

    <!-- DOUBLE PAR CustomerCode -->
    <div class="mt-3 table-responsive h-100">
        <div>
            <h4 class="fw-bold"><u>Validation : <span class="text_primary">{{ validation_type }}</span></u></h4>
        </div>

        <div id="validation_clients_container" class="table-container mt-5">
            <table class="table table-hover table-bordered validation_clients mt-0 mb-0 w-100" id="validation_clients">
                <thead>
                    <tr>
                        <th role="button">#</th>
                        <th v-for="validation_client_column in validation_clients_columns" :key="validation_client_column" role="button">{{ validation_client_column.title }}</th>
                    </tr>

                    <tr id="validation_clients_filters" class="validation_clients_filters">
                        <th></th>

                        <th v-for="validation_client_column in validation_clients_columns" :key="validation_client_column">
                            <div class="filter_group" :data-column="validation_client_column.title">
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
                                    :placeholder="validation_client_column.title"
                                />
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>
        </div>
    </div>

</template>

<script>

import DatatableHelper              from    "@/services/DatatableHelper"

export default {

    data() {
        return {

            validation_clients_columns              :   [
                                                                { data: "id"                                , title: "Id"                               },
                                                                { data: "OpenCustomer"                      , title: "OpenCustomer"                     },
                                                                { data: "CustomerIdentifier"                , title: "CustomerIdentifier"               },
                                                                { data: "CustomerCode"                      , title: "CustomerCode"                     },
                                                                { data: "CustomerNameE"                     , title: "CustomerNameE"                    },
                                                                { data: "CustomerNameA"                     , title: "CustomerNameA"                    },

                                                                { data: "DistrictNo"                        , title: "DistrictNo"                       },
                                                                { data: "DistrictNameE"                     , title: "DistrictNameE"                    },

                                                                { data: "CityNo"                            , title: "CityNo"                           },
                                                                { data: "CityNameE"                         , title: "CityNameE"                        },

                                                                { data: "Address"                           , title: "Address"                          },
                                                                { data: "Neighborhood"                      , title: "Neighborhood"                     },
                                                                { data: "Landmark"                          , title: "Landmark"                         },

                                                                { data: "Latitude"                          , title: "Latitude"                         },
                                                                { data: "Longitude"                         , title: "Longitude"                        },

                                                                { data: "Tel"                               , title: "Tel"                              },
                                                                { data: "CustomerType"                      , title: "CustomerType"                     },

                                                                { data: "JPlan"                             , title: "JPlan"                            },
                                                                { data: "Journee"                           , title: "Journee"                          },
                                                                { data: "Frequency"                         , title: "Frequency"                        },

                                                                { data: "SuperficieMagasin"                 , title: "SuperficieMagasin"                },
                                                                { data: "NbrAutomaticCheckouts"             , title: "NbrAutomaticCheckouts"            },
                                                                // { data: "AvailableBrands_string_formatted"  , title: "AvailableBrands_string_formatted" },
                                                                { data: "comment"                           , title: "comment"                          },
                                                                { data: "BrandAvailability"                 , title: "BrandAvailability"                },
                                                                { data: "BrandSourcePurchase"               , title: "BrandSourcePurchase"              },
                                                                { data: "start_adding_time"                 , title: "start_adding_time"                },
                                                                { data: "adding_duration"                   , title: "adding_duration"                  },
                                                                { data: "created_at"                        , title: "created_at"                       },
                                                                { data: "status"                            , title: "status"                           },
                                                                // { data: "owner_name"                        , title: "owner_name"                       },
                                                        ],

            //

            datatable_validation_clients            :   null    ,
            datatable_validation_clients_instance   :   null    ,

            //

            selected_row                            :   null    ,
            selected_row_id                         :   null
        }
    },

    props : ["validation_type", "validation_clients", "total_clients"],

    mounted(){

        //
        this.datatable_validation_clients_instance     =   new DatatableHelper()
    },

    unmounted() {

        this.emitter.off("reSetUpdate")
        this.emitter.off("reSetDelete")
    },

    methods : {

        async setDatatable() {

            this.datatable_validation_clients     =    this.datatable_validation_clients_instance.$DataTableCreate("validation_clients", this.validation_clients, this.validation_clients_columns, this.setDataTable, null, this.updateElement, null, this.selectRow, "Route Import Clients")
        },

        //

        async updateElement() {

            // ShowModal
            var updateModal     =   new Modal(document.getElementById("ModalClientUpdate"));
            updateModal.show();

            //
            this.$parent.$refs.ModalClientUpdate.getData(this.selected_row, this.total_clients)
        },

        //

        // async updateClientJSON(client) {

        //     console.log(clients)
        //     console.log(this.total_clients)

        //     for (let i = 0; i < this.validation_clients.length; i++) {

        //         if(this.validation_clients[i].id  ==  client.id) {

        //             // Update Client
        //             this.validation_clients[i].NewCustomer                             =   client.NewCustomer
        //             this.validation_clients[i].OpenCustomer                            =   client.OpenCustomer
        //             this.validation_clients[i].CustomerIdentifier                      =   client.CustomerIdentifier
        //             this.validation_clients[i].CustomerCode                            =   client.CustomerCode

        //             this.validation_clients[i].CustomerNameE                           =   client.CustomerNameE
        //             this.validation_clients[i].CustomerNameA                           =   client.CustomerNameA

        //             this.validation_clients[i].Tel                                     =   client.Tel
        //             this.validation_clients[i].tel_status                              =   client.tel_status
        //             this.validation_clients[i].tel_comment                             =   client.tel_comment

        //             this.validation_clients[i].Latitude                                =   client.Latitude         
        //             this.validation_clients[i].Longitude                               =   client.Longitude        

        //             this.validation_clients[i].Address                                 =   client.Address
        //             this.validation_clients[i].Neighborhood                            =   client.Neighborhood
        //             this.validation_clients[i].Landmark                                =   client.Landmark

        //             this.validation_clients[i].DistrictNo                              =   client.DistrictNo      
        //             this.validation_clients[i].DistrictNameE                           =   client.DistrictNameE  

        //             this.validation_clients[i].CityNo                                  =   client.CityNo           
        //             this.validation_clients[i].CityNameE                               =   client.CityNameE       

        //             this.validation_clients[i].CustomerType                            =   client.CustomerType     

        //             this.validation_clients[i].BrandAvailability                       =   client.BrandAvailability       
        //             this.validation_clients[i].BrandSourcePurchase                     =   client.BrandSourcePurchase       

        //             this.validation_clients[i].JPlan                                   =   client.JPlan            
        //             this.validation_clients[i].Journee                                 =   client.Journee        

        //             this.validation_clients[i].Frequency                               =   client.Frequency        
        //             this.validation_clients[i].SuperficieMagasin                       =   client.SuperficieMagasin        
        //             this.validation_clients[i].NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts        

        //             this.validation_clients[i].AvailableBrands                         =   client.AvailableBrands
        //             this.validation_clients[i].AvailableBrands_array_formatted         =   client.AvailableBrands_array_formatted      // should be array
        //             this.validation_clients[i].AvailableBrands_string_formatted        =   client.AvailableBrands_string_formatted     // should be string

        //             this.validation_clients[i].status                                  =   client.status            
        //             this.validation_clients[i].nonvalidated_details                    =   client.nonvalidated_details        

        //             this.validation_clients[i].owner                                   =   client.owner
        //             this.validation_clients[i].owner_name                              =   client.owner_name

        //             this.validation_clients[i].comment                                 =   client.comment        

        //             this.validation_clients[i].facade_image                            =   client.facade_image            
        //             this.validation_clients[i].in_store_image                          =   client.in_store_image        
        //             this.validation_clients[i].facade_image_original_name              =   client.facade_image_original_name            
        //             this.validation_clients[i].in_store_image_original_name            =   client.in_store_image_original_name        
        //             this.validation_clients[i].CustomerBarCode_image                   =   client.CustomerBarCode_image            
        //             this.validation_clients[i].CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name        

        //             break
        //         }
        //     }

        //     //

        //     await this.setDatatable()
        // },

        // async deleteClientJSON(client) {

        //     for (let i = 0; i < this.validation_clients.length; i++) {

        //         if(this.validation_clients[i].id    ==  client.id) {

        //             this.validation_clients.splice(i, 1)
        //         }                
        //     }

        //     //

        //     await this.setDatatable()
        // },

        //

        selectRow(selected_row, selected_row_id) {

            this.selected_row       =   selected_row
            this.selected_row_id    =   selected_row_id
        },
    }
}

</script>