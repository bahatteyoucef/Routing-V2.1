<template>
    <div>

        <!-- OPTIONS -->
        <div class="row p-3 pb-0 pt-0">
            <div class="col-lg-7 mx-auto">

                <div class="row mt-2">
                    <div class="p-1 pt-0 col-12 d-flex justify-content-end">
                        <div class="col-2 pl-1 pr-1">
                            <button class="btn primary w-100 m-0"   @click="showHideCodeBar()"><i class="mdi mdi-barcode"></i></button>
                        </div>

                        <div class="col-2 pl-1 pr-1">
                            <button class="btn primary w-100 m-0"   @click="showMap()"><i class="mdi mdi-map-marker-circle"></i></button>
                        </div>

                        <div class="col-8 pl-1 pr-1">
                            <select         class="form-select"     id="filter_status"      v-model="filter_status"     @change="getClients()">
                                <option     :value="'validated'">Validé</option>
                                <option     :value="'pending'">en Attente</option>
                                <option     :value="'nonvalidated'">Refusé</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 p-0">
                        <input class="form-control" placeholder="Filtrer Par Raison Social..." v-model="search_by_CustomerNameA_value" @input="searchByCustomerNameA()"/>
                    </div>
                </div>

                <div class="row mt-1" v-if="show_barcode_div">

                    <div id="reader" class="scanner_reader mt-1"></div>

                    <div v-show="search_by_clientbarcode_value   !=  ''"    id="refresh_client_barcode_filter_button" class="mt-1 p-0">
                        <button type="button" class="btn primary w-100" @click="setBarCodeReader()">Re-Capture Bar Code</button>
                    </div>

                    <div v-show="search_by_clientbarcode_value   !=  ''"    id="clientbarcode_value" class="mt-1 p-0"></div>

                </div>
            </div>
        </div>

        <!--  -->

        <!-- Client Cards -->
        <div class="row mt-3" style="overflow-y: auto; height : 1000px">
            <div class="col-lg-8 mx-auto mb-3">
                <!-- List group-->
                <ul class="list-group shadow mb-5">

                    <!-- list group item-->
                    <li v-for="client, index in clients_filtered" :key="index"  @click="getDetailsPage(client)"   class="list-group-item"     role="button">
                        <!-- Custom content-->
                        <div class="media align-items-lg-center flex-column flex-lg-row p-1">

                            <div class="media-body order-2 order-lg-1 w-100">

                                <h5 class="mt-0 font-weight-bold mb-2">{{ client.CustomerNameA }} ({{ client.CustomerNameE }})</h5>
                            
                                <p class="font-italic text-muted mb-0 small">{{ client.Address }} - {{ client.CityNameE }}</p>

                                <div>
                                    <div v-if="client.status    ==  'validated'"        class="badge badge-success mt-1 mb-1">
                                        Validé
                                    </div>

                                    <div v-if="client.status    ==  'pending'"          class="badge badge-warning mt-1 mb-1">
                                        en Attente
                                    </div>

                                    <div v-if="client.status    ==  'nonvalidated'"     class="badge badge-danger mt-1 mb-1">
                                        Refusé
                                    </div>
                                </div>

                                <div v-if="client.status    ==  'nonvalidated'" class="mt-1 mb-1">
                                    <span class="text-small text-danger">{{ client.nonvalidated_details }}</span>
                                </div>

                            </div>

                        </div> <!-- End -->
                    </li> <!-- End -->

                </ul> <!-- End -->
            </div>
        </div>
    </div>
</template>
 
<script>

import Multiselect                  from    '@vueform/multiselect'

import {mapGetters, mapActions}     from    "vuex"

export default {

    data() {
        
        return {

            clients                             :   [],
            clients_filtered                    :   [],

            //

            search_by_CustomerNameA_value       :   "",
            search_by_clientbarcode_value       :   "",
        
            //

            scanner                             :   null,
            show_barcode_div                    :   false,

            //

            filter_status                       :   "validated"           
        }
    },

    computed : {

        ...mapGetters({
            getUser                                     :   'authentification/getUser'                          ,
            getAccessToken                              :   'authentification/getAccessToken'                   ,
            getIsAuthentificated                        :   'authentification/getIsAuthentificated'             ,

            getAddClient                                :   'client/getAddClient'                               ,
            getUpdateClient                             :   'client/getUpdateClient'                            ,
            getFilterStatusRouteImportClientsByStatus   :   'client/getFilterStatusRouteImportClientsByStatus'
        }),
    },

    components: {

        Multiselect
    },

    beforeMount() {

        let filter_status       =   this.getFilterStatusRouteImportClientsByStatus

        console.log(filter_status)

        if(filter_status    !=  "") {

            this.filter_status      =   this.getFilterStatusRouteImportClientsByStatus
        }
    },

    async mounted() {

        await this.getClients()
    },

    beforeUnmount() {

        if(this.scanner) {

            this.scanner.clear().then(_ => {

            }).catch(error => {

            });
        }
    },

    //

    methods : {

        ...mapActions("client" ,  [
            "setSelectedClientsAction"                          ,
            "setFilterStatusRouteImportClientsByStatusAction"   ,

            "setUpdateClientAction"
        ]),

        //

        async getClients() {

            try {

                if(this.$connectedToInternet) {

                    this.$showLoadingPage()

                    let formData    =   new FormData()

                    formData.append("status", this.filter_status)

                    this.$callApi("post",   "/route_import/"+this.getUser.id_route_import+"/clients/by_status",     formData).then(async (res)=> { 
                        
                        //
                        let clients             =   this.$checkIfClientsInsideTheZone(res.data, this.getUser.user_territories)

                        this.clients            =   clients
                        this.clients_filtered   =   this.clients

                        //
                        this.setFilterStatusRouteImportClientsByStatusAction(this.filter_status)

                        //
                        this.$hideLoadingPage()
                    })
                }

                else {

                    this.clients            =   await this.$indexedDB.$getClientsByStatus(this.getUser.id_route_import, this.filter_status)
                    this.clients_filtered   =   this.clients

                    //
                    this.setFilterStatusRouteImportClientsByStatusAction(this.filter_status)

                    console.log(this.clients)
                }
            }

            catch(e) {

                console.log(e)
            }

        },

        //

        getDetailsPage(client) {

            this.setUpdateClientAction(client)      

            this.$router.push('/route_import/'+client.id_route_import+'/clients/'+client.id+'/details')
        },

        showMap() {

            this.setSelectedClientsAction(this.clients_filtered)

            this.$router.push('/route/frontoffice/obs/route_import/'+this.$route.params.id_route_import+'/clients/selected')
        },

        //

        searchByCustomerNameA() {

            this.clients_filtered     =   this.clients
            
            if ((this.search_by_CustomerNameA_value != '')&&(this.search_by_CustomerNameA_value)) {

                this.clients_filtered     =   this.clients_filtered.filter((client) => {

                    return client.CustomerNameA.toUpperCase().includes(this.search_by_CustomerNameA_value.toUpperCase())
                })
            }

            return this.clients_filtered
        },

        //  //  //  BarCode

        showHideCodeBar() {

            if(this.show_barcode_div    ==  false) {

                this.show_barcode_div   =   true

                setTimeout(() => {

                    this.setBarCodeReader()
                }, 555);
            }

            else {

                this.search_by_clientbarcode_value    =   ""

                this.show_barcode_div                   =   false

                this.scanner.clear().then(_ => {

                }).catch(error => {

                });
            }
        },

        setBarCodeReader() {

            document.getElementById('reader').style.display =   "block";

            this.search_by_clientbarcode_value   =   ""

            this.scanner = new Html5QrcodeScanner('reader', { 

                    // Scanner will be initialized in DOM inside element with id of 'reader'
                    qrbox: {
                        width: 250,
                        height: 250,
                    },  // Sets dimensions of scanning box (set relative to reader element width)
                    fps: 20, // Frames per second to attempt a scan
                });

            // 
            this.scanner.render(this.success, this.error);
        },

        success(clientbarcode_value) {

            document.getElementById('clientbarcode_value').innerHTML = `
                <div class="col-12 p-0 text-center">
                    <span class="">ClientBarCode : ${clientbarcode_value}</span>
                </div>
            `;

            // 
            this.search_by_clientbarcode_value   =   clientbarcode_value

            this.scanner.clear();

            document.getElementById('reader').style.display =   "none"

            // searchByClientBarCode
            this.searchByClientBarCode()
        },

        error(err) {

        },

        searchByClientBarCode() {

            this.clients_filtered =   this.clients
            
            if ((this.search_by_clientbarcode_value != '')&&(this.search_by_clientbarcode_value)) {

                this.clients_filtered     =   this.clients_filtered.filter((client) => {

                    return client.CustomerCode.toUpperCase().includes(this.search_by_clientbarcode_value.toUpperCase())
                })
            }

            return this.clients_filtered
        }
    },

    watch : {

        search_by_clientbarcode_value(newValue, oldValue) {

            this.searchByClientBarCode()
        }
    }
}

</script>