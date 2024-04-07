<template>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body p-0 pt-5">

                    <!-- Header -->
                    <headerComponent    v-if="route_import&&($isRole('FrontOffice'))"                           :title="'List of clients du route import : '+route_import.libelle"      />

                    <headerComponent    v-if="route_import&&($isRole('Super Admin')||$isRole('BU Manager')||$isRole('BackOffice'))"     :title="'List of clients du route import : '+route_import.libelle"      :add_modal="'addClientModal'"  :update_modal="'updateClientModal'"    :add_button="'New Client'"   
                                                                                                                                        :update_button="'Update Client'"                                        />

                    <!--  -->

                    <div id="route_import_client_index_parent" class="scrollbar scrollbar-deep-blue">
                        <table class="table route_import_client_index" id="route_import_client_index">
                            <thead>
                                <tr>
                                    <th class="col-sm-1">Index</th>

                                    <th class="col-sm-2">CustomerCode</th>

                                    <th class="col-sm-2">CustomerNameE</th>
                                    <th class="col-sm-2">CustomerNameA</th>

                                    <th class="col-sm-1">DistrictNo</th>
                                    <th class="col-sm-2">DistrictNameE</th>

                                    <th class="col-sm-1">CityNo</th>
                                    <th class="col-sm-2">CityNameE</th>

                                    <th class="col-sm-2">Address</th>
                                    <th class="col-sm-2">Neighborhood</th>
                                    <th class="col-sm-2">Landmark</th>

                                    <th class="col-sm-2">Latitude</th>
                                    <th class="col-sm-2">Longitude</th>

                                    <th class="col-sm-2">Tel</th>

                                    <th class="col-sm-1">CustomerType</th>

                                    <th class="col-sm-2">JPlan</th>

                                    <th class="col-sm-2">Journee</th>

                                    <!--  -->

                                    <th class="col-sm-2">Comment</th>
                                    <th class="col-sm-2">BrandAvailability</th>
                                    <th class="col-sm-2">BrandSourcePurchase</th>
                                    <th class="col-sm-2">Start Adding Time</th>
                                    <th class="col-sm-2">Adding Duration</th>

                                    <!--  -->

                                    <th class="col-sm-2">Created At</th>
                                    <th class="col-sm-2">Status</th>
                                    <th class="col-sm-2">Owner</th>
                                </tr>
                            </thead>

                            <thead>
                                <tr class="route_import_client_index_filters">

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Index"            /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerCode"     /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameE"    /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameA"    /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="DistrictNo"       /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="DistrictNameE"    /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CityNo"           /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CityNameE"        /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Address"          /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Neighborhood"     /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Landmark"         /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Latitude"         /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Longitude"        /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Tel"              /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerType"     /></th>

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="JPlan"            /></th>

                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Journee"          /></th>

                                    <!--  -->

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Comment"              /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="BrandAvailability"    /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="BrandSourcePurchase"  /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Start Adding Time"    /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Adding Duration"      /></th>

                                    <!--  -->

                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Created_At"       /></th>
                                    <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Status"           /></th>
                                    <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Owner"            /></th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <tr v-for="client, index in clients" :key="client" @click="selectRow(client)" role="button"   :id="'route_import_client_index_'+client.id">
                                    <td>{{index +   1}}</td>

                                    <td>{{client.CustomerCode}}</td>
                                    <td>{{client.CustomerNameE}}</td>
                                    <td>{{client.CustomerNameA}}</td>

                                    <td>{{client.DistrictNo}}</td>
                                    <td>{{client.DistrictNameE}}</td>

                                    <td>{{client.CityNo}}</td>
                                    <td>{{client.CityNameE}}</td>

                                    <td>{{client.Address}}</td>
                                    <td>{{client.Neighborhood}}</td>
                                    <td>{{client.Landmark}}</td>

                                    <td>{{client.Latitude}}</td>
                                    <td>{{client.Longitude}}</td>

                                    <td>{{client.Tel}}</td>

                                    <td>{{client.CustomerType}}</td>

                                    <td>{{client.JPlan}}</td>

                                    <td>{{client.Journee}}</td>

                                    <!--  -->

                                    <td>{{client.comment}}</td>
                                    <td>{{client.BrandAvailability}}</td>
                                    <td>{{client.BrandSourcePurchase}}</td>
                                    <td>{{client.start_adding_time}}</td>
                                    <td>{{client.adding_duration}}</td>

                                    <!--  -->
                                    <td>{{client.created_at}}</td>

                                    <td>
                                        <span v-if="client.status=='nonvalidated'"  href="#" class="badge badge-danger">{{client.status}}</span>
                                        <span v-if="client.status=='pending'"       href="#" class="badge badge-warning">{{client.status}}</span>
                                        <span v-if="client.status=='validated'"     href="#" class="badge badge-success">{{client.status}}</span>
                                    </td>

                                    <td>{{client.owner_name}}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal Add                      -->
        <modalClientAdd             ref="modalClientAdd"            ></modalClientAdd>

        <!-- Modal Update                   -->
        <modalClientUpdate          ref="modalClientUpdate"         ></modalClientUpdate>

    </div>
</template>
 
<script>

import headerComponent              from    "../../template/components/headerComponent.vue"

import modalClientAdd               from    "../clients/permanent/modalClientAdd.vue"
import modalClientUpdate            from    "../clients/permanent/modalClientUpdate.vue"

import {mapGetters, mapActions}     from    "vuex"

export default {

    data() {
        return {

            updated_user                            :   ''      ,
            added_user                              :   ''      ,

            //

            route_import                            :   null    ,

            clients                                 :   []      ,
            datatable_route_import_client_index     :   null    ,

            selected_client                         :   null    ,
            display_modal                           :   false   ,

            selected_row                            :   null
        }
    },

    components : {
        headerComponent             ,

        modalClientAdd              ,
        modalClientUpdate           
    },

    computed : {

        ...mapGetters({

            getUser                 :   'authentification/getUser'              ,
            getAccessToken          :   'authentification/getAccessToken'       ,
            getIsAuthentificated    :   'authentification/getIsAuthentificated' ,

            getAddClient            :   'client/getAddClient'                   ,
            getUpdateClient         :   'client/getUpdateClient'                ,

            getIsOnline             :   'internet/getIsOnline'
        })
    },

    async mounted() {

        this.emitter.on('reSetAdd'          , async (client)    =>  {

            await this.addClientToDatatable(client)
        })

        this.emitter.on('reSetUpdate'       , async (client)    =>  {

            await this.updateClientToDatatable(client)
        })

        this.emitter.on('reSetDelete'       , async (client)    =>  {

            await this.deleteClientToDatatable(client)
        })

        this.emitter.on('reSetValidate'     , async (client)    =>  {

            await this.validateClientToDatatable(client)
        })

        await this.setDataTable()
    },

    methods : {

        ...mapActions("client" ,  [
            "setUpdateClientAction"   ,
        ]),

        //

        async setDataTable() {

            try {

                // if(this.getIsOnline) {

                    // Destroy DataTable
                    if(this.datatable_route_import_client_index)  {

                        this.datatable_route_import_client_index.destroy()
                    }

                    // Initialisation 
                    this.clients    =   [];

                    this.$callApi("post",   "/route_import/"+this.$route.params.id_route_import+"/clients",     null)
                    .then(async (res)=> {

                        this.route_import   =   res.data.route_import
                        this.clients        =   res.data.clients

                        if(this.$isRole("FrontOffice")) {

                            this.datatable_route_import_client_index    =   await this.$DataTableCreateFrontOffice("route_import_client_index")
                        }

                        else {

                            this.datatable_route_import_client_index    =   await this.$DataTableCreate("route_import_client_index")
                        }
                    })
                // }

                // else {

                    // Destroy DataTable
                    // if(this.datatable_route_import_client_index)  {

                    //     this.datatable_route_import_client_index.destroy()
                    // }
                
                    // // Initialisation 
                    // this.clients                                =   [];

                    // this.route_import                           =   await this.$indexedDB.$getRouteImport(this.$route.params.id_route_import)
                    // this.clients                                =   this.route_import.clients

                    // if(this.$isRole("FrontOffice")) {

                    //     this.datatable_route_import_client_index    =   await this.$DataTableCreateFrontOffice("route_import_client_index")
                    // }

                    // else {

                    //     this.datatable_route_import_client_index    =   await this.$DataTableCreate("route_import_client_index")
                    // }
                // }
            }

            catch(e) {

                console.log(e)
            }
        },

        //

        async addElement() {

            try {

                if(this.$isRole("Super Admin")||this.$isRole('BU Manager')||this.$isRole("BackOffice")) {

                    let client      =   { lat : 0, lng : 0 }

                    this.$refs.modalClientAdd.getData(client, this.clients)
                }
            }
            catch(e) {

                console.log(e)
            }
        },

        async updateElement() {

            try {

                if(this.$isRole("Super Admin")||this.$isRole('BU Manager')||this.$isRole("BackOffice")) {

                    this.$refs.modalClientUpdate.getData(this.selected_row, this.clients)
                }
            }
            catch(e) {

                console.log(e)
            }
        },

        //

        async addClientToDatatable(client) {

            let new_client      =   {}

            // Add Client
            new_client.id               =   client.id
            new_client.CustomerCode     =   client.CustomerCode

            new_client.CustomerNameE    =   client.CustomerNameE
            new_client.CustomerNameA    =   client.CustomerNameA
            new_client.Tel              =   client.Tel

            new_client.Latitude         =   client.Latitude         
            new_client.Longitude        =   client.Longitude        

            new_client.Address          =   client.Address
            new_client.Neighborhood     =   client.Neighborhood
            new_client.Landmark         =   client.Landmark

            new_client.DistrictNo       =   client.DistrictNo      
            new_client.DistrictNameE    =   client.DistrictNameE 

            new_client.CityNo           =   client.CityNo           
            new_client.CityNameE        =   client.CityNameE       

            new_client.CustomerType     =   client.CustomerType     

            new_client.BrandAvailability        =   client.BrandAvailability       
            new_client.BrandSourcePurchase      =   client.BrandSourcePurchase       

            new_client.JPlan                    =   client.JPlan            
            new_client.Journee                  =   client.Journee        

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
 
            this.clients.push(new_client)

            //

            // Destroy DataTable
            if(this.datatable_route_import_client_index)  {

                this.datatable_route_import_client_index.destroy()
            }

            this.datatable_route_import_client_index    =   await this.$DataTableCreate("route_import_client_index")

        },

        async updateClientToDatatable(client) {

            for (let i = 0; i < this.clients.length; i++) {
                
                if(this.clients[i].id  ==  client.id) {

                    this.clients[i].CustomerCode   =   client.CustomerCode

                    this.clients[i].CustomerNameE  =   client.CustomerNameE
                    this.clients[i].CustomerNameA  =   client.CustomerNameA
                    this.clients[i].Tel            =   client.Tel

                    this.clients[i].Latitude       =   client.Latitude         
                    this.clients[i].Longitude      =   client.Longitude        

                    this.clients[i].Address        =   client.Address
                    this.clients[i].Neighborhood   =   client.Neighborhood
                    this.clients[i].Landmark       =   client.Landmark

                    this.clients[i].DistrictNo     =   client.DistrictNo      
                    this.clients[i].DistrictNameE  =   client.DistrictNameE  

                    this.clients[i].CityNo         =   client.CityNo           
                    this.clients[i].CityNameE      =   client.CityNameE       

                    this.clients[i].CustomerType   =   client.CustomerType     

                    this.clients[i].BrandAvailability      =   client.BrandAvailability       
                    this.clients[i].BrandSourcePurchase    =   client.BrandSourcePurchase       

                    this.clients[i].JPlan              =   client.JPlan            
                    this.clients[i].Journee            =   client.Journee        

                    this.clients[i].status                 =   client.status            
                    this.clients[i].nonvalidated_details   =   client.nonvalidated_details        

                    this.clients[i].comment                =   client.comment        

                    this.clients[i].facade_image                           =   client.facade_image            
                    this.clients[i].in_store_image                         =   client.in_store_image        
                    this.clients[i].facade_image_original_name             =   client.facade_image_original_name            
                    this.clients[i].in_store_image_original_name           =   client.in_store_image_original_name        
                    this.clients[i].CustomerBarCode_image                  =   client.CustomerBarCode_image            
                    this.clients[i].CustomerBarCode_image_original_name    =   client.CustomerBarCode_image_original_name        

                    break
                }
            }

            //

            // Destroy DataTable
            if(this.datatable_route_import_client_index)  {

                this.datatable_route_import_client_index.destroy()
            }

            this.datatable_route_import_client_index    =   await this.$DataTableCreate("route_import_client_index")
        },

        async deleteClientToDatatable(client) {

            for (let i = 0; i < this.clients.length; i++) {
                
                if(this.clients[i].id  ==  client.id) {

                    this.clients.splice(i, 1)

                    break
                }
            }

            //

            // Destroy DataTable
            if(this.datatable_route_import_client_index)  {

                this.datatable_route_import_client_index.destroy()
            }

            this.datatable_route_import_client_index    =   await this.$DataTableCreate("route_import_client_index")
        },

        async validateClientToDatatable(client) {

            for (let i = 0; i < this.clients.length; i++) {
                
                if(this.clients[i].id  ==  client.id) {

                    this.clients[i].status                  =   client.status            
                    this.clients[i].nonvalidated_details    =   client.nonvalidated_details        

                    break
                }
            }

            //

            // Destroy DataTable
            if(this.datatable_route_import_client_index)  {

                this.datatable_route_import_client_index.destroy()
            }

            this.datatable_route_import_client_index    =   await this.$DataTableCreate("route_import_client_index")
        },

        //

        selectRow(client) {

            // Get Element
            const row           =   document.getElementById("route_import_client_index_"+client.id)

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

                this.setUpdateClientAction(client)      
            }

            else {

                // remove Active Class
                row.classList.remove("active_row")

                // Selected Row
                this.selected_row   =   null
            }
        },
    }
};
</script>

<style scoped>

#route_import_client_index_parent {

    overflow : auto;
}

</style>