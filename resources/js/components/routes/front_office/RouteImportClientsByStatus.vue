<template>
    <div>
        <div class="row p-3 pb-0 pt-0">
            <div class="col-lg-7 mx-auto">

                <div class="row mt-2">
                    <div class="p-1 pt-0 col-sm-12 d-flex justify-content-end">
                        <div class="col-2 pl-1 pr-1">
                            <button class="btn btn-primary w-100 m-0"   @click="showHideCodeBar()"><i class="mdi mdi-barcode"></i></button>
                        </div>

                        <div class="col-2 pl-1 pr-1">
                            <button class="btn btn-primary w-100 m-0"   @click="showMap()"><i class="mdi mdi-map-marker-circle"></i></button>
                        </div>

                        <div class="col-8 pl-1 pr-1">
                            <select         class="form-select"     id="filter_status"      v-model="filter_status"     @change="getClients()">
                                <option     :value="'confirmed'">Confirmé</option>
                                <option     :value="'validated'">Validé</option>
                                <option     :value="'pending'">en Attente</option>
                                <option     :value="'nonvalidated'">Non Valide</option>
                                <option     :value="'visible'">Visible</option>
                                <option     :value="'ferme'">Fermé</option>
                                <option     :value="'refus'">Refusé</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-12 p-0">
                        <input class="form-control" placeholder="Filtrer Par Raison Social..." v-model="search_by_CustomerNameA_value" @input="searchByCustomerNameA()"/>
                    </div>
                </div>

                <div class="row mt-1" v-if="show_barcode_div">

                    <div id="reader" class="scanner_reader mt-1"></div>

                    <div v-show="search_by_clientbarcode_value   !=  ''"    id="refresh_client_barcode_filter_button" class="mt-1 p-0">
                        <button type="button" class="btn btn-primary w-100" @click="setBarCodeReader()">Re-Capture Bar Code</button>
                    </div>

                    <div v-show="search_by_clientbarcode_value   !=  ''"    id="clientbarcode_value" class="mt-1 p-0"></div>

                </div>
            </div>
        </div>

        <RecycleScroller
            class="row mt-2 pb-5"
            style="height: 80vh;"
            :items="clients_filtered"
            :item-size="itemHeight + gap" 
            key-field="id" 
            v-slot="{ item }"
        >
            <div class="col mx-auto pl-2 pr-2" :style="{ paddingBottom: gap + 'px' }">
                
                <ul class="list-group" style="margin-bottom: 0;">
                    <li
                        @click="getDetailsPage(item)"
                        class="list-group-item shadow border-0 py-1"
                        role="button"
                        :style="{ height: itemHeight + 'px' }" 
                    >
                    <div class="media align-items-lg-center flex-column flex-lg-row" style="height: 100%; overflow: hidden;">
                            <div class="media-body order-2 order-lg-1 w-100">
                                <span class="mt-0 font-weight-bold mb-2">{{ item.CustomerNameA }} ({{ item.CustomerNameE }})</span>
                                <p class="font-italic text-muted mb-0 small">{{ item.Address }} - {{ item.CityNameE }}</p>

                                <div>
                                    <span v-if="item.status === 'validated'" class="badge badge-success mt-1 mb-1">Validé</span>
                                    <span v-if="item.status === 'pending'" class="badge badge-warning mt-1 mb-1">en Attente</span>
                                    <span v-if="item.status === 'nonvalidated'" class="badge badge-danger mt-1 mb-1">Refusé</span>
                                    <span v-if="item.status === 'visible'" class="badge badge-info mt-1 mb-1">Visible</span>
                                    <span v-if="item.status === 'ferme'" class="badge badge-secondary mt-1 mb-1">Fermé</span>
                                </div>

                                <div v-if="item.status === 'nonvalidated'" class="mt-1 mb-1">
                                    <span class="text-small text-danger">{{ item.nonvalidated_details }}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </RecycleScroller>
    </div>
</template>

<script>

import 'vue-virtual-scroller/dist/vue-virtual-scroller.css'
import { RecycleScroller } from 'vue-virtual-scroller'

import Multiselect from '@vueform/multiselect'
import { mapGetters, mapActions } from "vuex"

export default {

    components: {
        RecycleScroller,
        Multiselect
    },

    data() {
        return {
            clients                         : [],
            clients_filtered                : [],
            search_by_CustomerNameA_value   : "",
            search_by_clientbarcode_value   : "",
            scanner                         : null,
            show_barcode_div                : false,
            filter_status                   : "confirmed",
            
            //
            // 1. The visual height of the white card
            itemHeight: 110, 
            
            // 2. The size of the empty space between cards
            gap: 5,   
        }
    },

    computed: {
        ...mapGetters({
            getUser: 'authentification/getUser',
            getFilterStatusRouteImportClientsByStatus: 'client/getFilterStatusRouteImportClientsByStatus'
        }),
    },

    beforeMount() {
        
        let filter_status       =   this.getFilterStatusRouteImportClientsByStatus

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

    methods: {
        ...mapActions("client", [
            "setSelectedClientsAction",
            "setFilterStatusRouteImportClientsByStatusAction",
        ]),

        // getClients, getDetailsPage, showMap (No changes needed here)
        async getClients() {

            await this.$showLoadingPage()

            let formData    =   new FormData()

            formData.append("status", this.filter_status)

            this.$callApi("post",   "/route-imports/"+this.getUser.id_route_import+"/clients/by-status",     formData).then(async (res)=> { 
                
                //
                this.clients            =   res.data.clients
                this.clients_filtered   =   this.clients

                //
                this.setFilterStatusRouteImportClientsByStatusAction(this.filter_status)

                //
                await this.$hideLoadingPage()
            })
        },

        getDetailsPage(client) {
            this.$router.push('/route-imports/'+client.id_route_import+'/clients/'+client.id+'/details')
        },

        showMap() {

            this.setSelectedClientsAction(this.clients_filtered)

            this.$router.push('/route/frontoffice/obs/route-imports/'+this.$route.params.id_route_import+'/details')
        },

        // searchByCustomerNameA (No changes needed here)
        searchByCustomerNameA() {

            this.clients_filtered     =   this.clients
            
            if ((this.search_by_CustomerNameA_value != '')&&(this.search_by_CustomerNameA_value)) {

                this.clients_filtered     =   this.clients_filtered.filter((client) => {

                    if(client.CustomerNameA) {

                        return client.CustomerNameA.toUpperCase().includes(this.search_by_CustomerNameA_value.toUpperCase())
                    }
                })
            }

            return this.clients_filtered
        },

        // BarCode Methods (No changes needed here)
        async showHideCodeBar() {

            if(this.show_barcode_div    ==  false) {

                this.show_barcode_div   =   true

                await this.$nextTick()

                this.setBarCodeReader()
            }

            else {

                this.search_by_clientbarcode_value    =   ""

                this.show_barcode_div                   =   false

                this.scanner.clear().then(_ => {

                }).catch(error => {

                });
            }
        },

        async setBarCodeReader() {

            document.getElementById('reader').style.display =   "block";

            this.search_by_clientbarcode_value   =   ""

            //
            const requestCamera = async () => {
                const devices       = await navigator.mediaDevices.enumerateDevices();
                const videoDevices  = devices.filter(device => device.kind === 'videoinput');
                const backCamera    = videoDevices.find(device => device.label.includes('back') || device.label.includes('rear'));
                
                if (backCamera) {

                    return { exact: backCamera.deviceId };
                }
                
                return undefined;
            };

            //
            this.scanner = new Html5QrcodeScanner('reader', {

                qrbox   : {
                    width   : 250,
                    height  : 250,
                },

                fps     : 20,

                supportedScanTypes  : [
                    Html5QrcodeScanType.SCAN_TYPE_CAMERA
                ],
            });

            try {
                await this.scanner.render(this.success, this.error, requestCamera);
            } catch (error) {
                console.error('Error rendering scanner:', error);
            }
        },

        success(clientbarcode_value) {

            document.getElementById('clientbarcode_value').innerHTML = `
                <div class="col-sm-12 p-0 text-center">
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
        },
    },

    watch: {

        // watch (No changes needed here)
        search_by_clientbarcode_value(newValue, oldValue) {

            this.searchByClientBarCode()
        }
    }
}

</script>

<style scoped>

</style>