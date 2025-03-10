<template>

    <!-- Modal -->
    <div class="modal fade" id="clientsChangeRouteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" v-if="clients">Change Clients Route : {{clients.length}} clients</h5>
                    <h5 class="modal-title" v-if="!clients">Change Clients Route : 0 clients</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3 table-responsive">

                    <form>

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
                                        <option v-for="district in districts"                   :key="district.DistrictNo"      :value="district.DistrictNo">{{district.DistrictNo}}- {{district.DistrictNameE}}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- CityNo                            -->
                            <div class="col">
                                <div class="mb-3">
                                    <label for="CityNo"             class="form-label">CityNo (CityNo)</label>
                                    <select                         class="form-select"         id="CityNo"                     v-model="CityNo">
                                        <option value=""></option>
                                        <option v-for="cite in cites"           :key="cite.CITYNO"    :value="cite.CITYNO">{{cite.CITYNO}}- {{cite.CityNameE}}</option>
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
                                </select>
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

                                    <select                         class="form-select"         id="Journee"                 v-model="Journee">
                                        <option     :value="'Jour 1'">Samedi 1 (Jour 1)</option>
                                        <option     :value="'Jour 2'">Dimanche 1 (Jour 2)</option>
                                        <option     :value="'Jour 3'">Lundi 1 (Jour 3)</option>
                                        <option     :value="'Jour 4'">Mardi 1 (Jour 4)</option>
                                        <option     :value="'Jour 5'">Mercredi 1 (Jour 5)</option>
                                        <option     :value="'Jour 6'">Jeudi 1 (Jour 6)</option>
                                        <option     :value="'Jour 7'">Samedi 2 (Jour 7)</option>
                                        <option     :value="'Jour 8'">Dimanche 2 (Jour 8)</option>
                                        <option     :value="'Jour 9'">Lundi 2 (Jour 9)</option>
                                        <option     :value="'Jour 10'">Mardi 2 (Jour 10)</option>
                                        <option     :value="'Jour 11'">Mercredi 2 (Jour 11)</option>
                                        <option     :value="'Jour 12'">Jeudi 2 (Jour 12)</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </form>

                    <table class="table table-striped scrollbar scrollbar-deep-blue datatable_client_change_route" id="datatable_client_change_route">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <div class="form-check pt-1 m-0">
                                        <input @change="checkGlobal($event)" type="checkbox" class="form-check-input" id="client_global" checked>
                                    </div>
                                </th>
                                <th class="col-sm-1">Index</th>

                                <th class="col-sm-1">CustomerCode</th>

                                <th class="col-sm-1">CustomerNameE</th>
                                <th class="col-sm-1">CustomerNameA</th>

                                <th class="col-sm-1">DistrictNo</th>
                                <th class="col-sm-2">DistrictNameE</th>

                                <th class="col-sm-1">CityNo</th>
                                <th class="col-sm-2">CityNameE</th>

                                <th class="col-sm-2">Address</th>

                                <th class="col-sm-2">Latitude</th>
                                <th class="col-sm-2">Longitude</th>

                                <th class="col-sm-2">Tel</th>

                                <th class="col-sm-1">CustomerType</th>

                                <th class="col-sm-2">JPlan</th>
                                <th class="col-sm-1">Journee</th>

                                <!--  -->

                                <th class="col-sm-2">Created At</th>
                                <th class="col-sm-2">Status</th>
                                <th class="col-sm-2">Owner</th>
                            </tr>
                        </thead>

                        <thead>
                            <tr class="datatable_client_change_route_filters">

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder=""                 /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Index"            /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerCode"     /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameE"    /></th>
                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerNameA"    /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="DistrictNo"       /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="DistrictNameE"    /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CityNo"           /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="CityNameE"        /></th>

                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Address"          /></th>

                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Latitude"         /></th>
                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Longitude"        /></th>

                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="Tel"              /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="CustomerType"     /></th>

                                <th class="col-sm-2"><input type="text" class="form-control form-control-sm" placeholder="JPlan"            /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Journee"          /></th>

                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Created At"       /></th>
                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Status"           /></th>
                                <th class="col-sm-1"><input type="text" class="form-control form-control-sm" placeholder="Owner"            /></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(client, index) in all_clients" :key="client.id">
                                <td>
                                    <div class="form-check">
                                        <input @change="checkClient($event)" class="form-check-input client_checkbox" type="checkbox" :value="client.id" :id="'client_'+client.id" checked>
                                    </div>
                                </td>
                                <td>{{index + 1}}</td>

                                <td>{{client.CustomerCode}}</td>

                                <td>{{client.CustomerNameE}}</td>
                                <td>{{client.CustomerNameA}}</td>

                                <td>{{client.DistrictNo}}</td>
                                <td>{{client.DistrictNameE}}</td>

                                <td>{{client.CityNo}}</td>
                                <td>{{client.CityNameE}}</td>

                                <td>{{client.Address}}</td>

                                <td>{{client.Latitude}}</td>
                                <td>{{client.Longitude}}</td>

                                <td>{{client.Tel}}</td>

                                <td>{{client.CustomerType}}</td>

                                <td>{{client.JPlan}}</td>
                                <td>{{client.Journee}}</td>

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

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"    @click="deleteClients()">Delete</button>

                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"   @click="sendData()">Confirm</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

import { nextTick } from "vue";
import {mapGetters, mapActions} from    "vuex"

export default {

    data() {
        return {

            datatable_client_change_route   :   null,

            //

            DistrictNo                      :   ""  ,
            CityNo                          :   ""  ,
            JPlan                           :   ""  ,
            Journee                         :   ""  ,
            owner                           :   ""  ,
            status                          :   ""  ,

            //

            all_clients                     :   []  ,
            clients                         :   []  ,

            //

            liste_journey_plan              :   []  ,
            liste_journee                   :   []  ,
            liste_type_client               :   []  ,

            //

            districts                       :   []  ,
            cites                           :   []  ,
            users                           :   []
        }
    },

    computed : {

        ...mapGetters({
            getClientsChangeRoute           :   'client/getClientsChangeRoute'          ,
            getListeJourneyPlan             :   'journey_plan/getListeJourneyPlan'      ,
            getListeTypeClient              :   'type_client/getListeTypeClient'        ,  
            getListeJournee                 :   'journee/getListeJournee'    
        }),
    },

    mounted() {

        this.clearData("#clientsChangeRouteModal")
    },  

    methods : {

        ...mapActions("client" ,  [
            "setClientsChangeRouteAction"   ,
        ]),

        //

        async setDataTable(clients) {

            try {

                // Destroy DataTable
                if(this.datatable_client_change_route)  {

                    this.datatable_client_change_route.destroy()
                }

                // Set Value
                this.all_clients    =   [...clients]
                this.clients        =   [...clients]
                    
                this.datatable_client_change_route  =   await this.$DataTableCreate("datatable_client_change_route")

                //
                await nextTick()

                //
                this.checkAll();
            }

            catch(e) {

                console.log(e)
            }
        },

        async sendData() {

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

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                // Send Client
                this.emitter.emit('reSetChangeRoute' , clients_copy)

                // Close Modal
                this.$hideModal("clientsChangeRouteModal")
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },

        async deleteClients() {

            this.$showLoadingPage()

            let formData                =   new FormData()

            let clients_ids_copy        =   [...this.clients].map(client => client.id)

            //

            formData.append("clients", JSON.stringify(clients_ids_copy))

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/delete",   formData)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                //
                let clients_copy            =   [...this.clients].map(client => { return { id : client.id }})  
                console.log(clients_copy)

                // Send Client
                this.emitter.emit('reSetChangeRouteDelete' , clients_copy)

                // Close Modal
                this.$hideModal("clientsChangeRouteModal")
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },

        //

        checkClient(event) {

            // Route Checkded
            if (event.target.checked) {

                let index   =   this.getIndexPush(event.target.value)
                this.clients.push(this.all_clients[index])
            }

            // Route UnCheckded
            else {

                let index   =   this.getIndexDelete(event.target.value)
                this.clients.splice(index, 1)
            }
        },

        checkGlobal(event) {

            if (event.target.checked) {

                this.checkAll();
            } else {

                this.uncheckAll();
            }
        },

        checkAll() {

            const checkboxes        =   document.querySelectorAll(".client_checkbox")

            checkboxes.forEach(checkbox => {

                checkbox.checked    =   true
            });

            this.clients            =   [...this.all_clients]
        },

        uncheckAll() {

            const checkboxes        =   document.querySelectorAll(".client_checkbox")

            checkboxes.forEach(checkbox => {

                checkbox.checked    =   false
            });

            this.clients            =   []
        },

        //

        getIndexPush(id_client) {

            for (let i = 0; i < this.all_clients.length; i++) {
                
                if(this.all_clients[i].id   ==  id_client) {

                    return i
                }
            }

            return null
        },

        getIndexDelete(id_client) {

            for (let i = 0; i < this.clients.length; i++) {
                
                if(this.clients[i].id   ==  id_client) {

                    return i
                }
            }

            return null
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                // 
                this.setClientsChangeRouteAction(null)

                this.DistrictNo                     =   ''
                this.CityNo                         =   ''
                this.JPlan                          =   ''
                this.Journee                        =   ''
                this.owner                          =   ''
                this.status                         =   ''

                //

                this.liste_journey_plan             =   []  
                this.liste_journee                  =   []  
                this.liste_type_client              =   []  

                //

                this.districts                      =   []  
                this.cites                          =   []  
                this.users                          =   []  

                //

                this.removeDrawings()
            });
        },

        removeDrawings() {

            // Not Map
            if(!this.$route.path.startsWith("/routes/obs/")) {

                // Do Nothing 
            }

            // Map
            else {

                // Remove Drawings
                this.$map.$removeDrawings()
            }   
        },

        //

        async getData(clients) {

            // Show Loading Page
            this.$showLoadingPage()

            await this.setDataTable(clients)

            this.getComboData()

            // Hide Loading Page
            this.$hideLoadingPage()
        },

        async getComboData() {

            const res_1                     =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/users/frontOffice"    ,   null)
            const res_2                     =   await this.$callApi("post"  ,   "/rtm_willayas"                                                             ,   null)

            this.users                      =   res_1.data
            this.districts                  =   res_2.data
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

        checkUncheck(id) {

            const jour  =   document.getElementById(id)

            if(jour.checked) {

                jour.checked    =   false
            }
            else {

                jour.checked    =   true
            }
        },

        //

        clientMapRightProperties(clients) {

            const fields = ['owner', 'owner_name', 'DistrictNo', 'DistrictNameE', 'CityNo', 'CityNameE', 'JPlan', 'Journee', 'status'];
            
            return clients.map(obj => {
                let result = { id: obj.id };
                
                fields.forEach(field => {
                    if (this[field] !== "") {
                        result[field] = obj[field];
                    }
                });
                
                return result;
            });
        }
    },

    watch : {

        getListeJourneyPlan(new_liste_journey_plan, old_liste_journey_plan) {

            this.liste_journey_plan     =   new_liste_journey_plan
        },

        getListeJournee(new_liste_journee, old_liste_journee) {

            this.liste_journee          =   new_liste_journee
        },

        getListeTypeClient(new_liste_type_client, old_liste_type_client) {

            this.liste_type_client      =   new_liste_type_client
        }
    }
};
</script>