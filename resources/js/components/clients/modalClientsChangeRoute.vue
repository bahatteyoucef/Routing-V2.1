<template>

    <!-- Modal -->
    <div class="modal fade" id="clientsChangeRouteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" v-if="clients.length">Change Clients Route {{clients.length}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3">

                    <form>

                        <div class="row mb-3">

                            <!-- DistrictNo                        -->
                            <div class="col">
                                <div class="mb-3">
                                    <label for="DistrictNo"         class="form-label">DistrictNo</label>
                                    <select                         class="form-select"         id="DistrictNo"                 v-model="DistrictNo"    @change="getCites()">
                                        <option value=""></option>
                                        <option v-for="district in districts"                   :key="district.DistrictNo"      :value="district.DistrictNo">{{district.DistrictNameE}}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- CityNo                            -->
                            <div class="col">
                                <div class="mb-3">
                                    <label for="CityNo"             class="form-label">CityNo</label>
                                    <select                         class="form-select"         id="CityNo"                     v-model="CityNo">
                                        <option value=""></option>
                                        <option v-for="cite in cites"           :key="cite.CITYNO"    :value="cite.CITYNO">{{cite.CityNameE}}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- JPlan                              -->
                            <div class="col">
                                <div class="mb-3">
                                    <label for="JPlan"              class="form-label">JPlan</label>
                                    <select                         class="form-select"         id="JPlan"                  v-model="JPlan">
                                        <option value=""></option>
                                        <option v-for="journey_plan in liste_journey_plan"      :key="journey_plan.JPlan"   :value="journey_plan.JPlan">{{journey_plan.JPlan}}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Frequence de Visite Par Semaine    -->
                            <div class="col">
                                <label for="frequence_visite"           class="form-label">Frequence de visite</label>
                                <select                                 class="form-select"     id="frequence_visite"       v-model="frequence_visite">
                                    <option value=""></option>
                                    <option value="1">journalière</option>
                                    <option value="2">3 fois par semaine</option>
                                    <option value="3">2 fois par semaine</option>
                                    <option value="4">1 fois par semaine</option>
                                    <option value="5">1 fois par 15 jours</option>
                                    <option value="6">1 fois par mois</option>
                                </select>
                            </div>

                            <!-- StartWeek                          -->
                            <div class="col">
                                <label for="StartWeek"          class="form-label">StartWeek</label>
                                <select                         class="form-select"     id="StartWeek"      v-model="StartWeek">
                                    <option value=""></option>
                                    <option value="1">Semaine 1</option>
                                    <option value="2">Semaine 2</option>
                                    <option value="3">Semaine 3</option>
                                    <option value="4">Semaine 4</option>
                                </select>
                            </div>

                            <!-- Jours                              -->
                            <div class="col">
                                <div class="mb-3">

                                    <label class="form-label">Jours</label>

                                    <div class="form-check m-0">

                                        <input type="checkbox" class="jours" id="samedi_checkbox"       @change="checkUncheck('samedi_checkbox')"/>
                                        <label for="checkbox" class="ml-1">Samedi</label>
                                        <br />

                                        <input type="checkbox" class="jours" id="dimanche_checkbox"     @change="checkUncheck('dimanche_checkbox')"/>
                                        <label for="checkbox" class="ml-1">Dimanche</label>
                                        <br />

                                        <input type="checkbox" class="jours" id="lundi_checkbox"        @change="checkUncheck('lundi_checkbox')"/>
                                        <label for="checkbox" class="ml-1">Lundi</label>
                                        <br />

                                        <input type="checkbox" class="jours" id="mardi_checkbox"        @change="checkUncheck('mardi_checkbox')"/>
                                        <label for="checkbox" class="ml-1">Mardi</label>
                                        <br />

                                        <input type="checkbox" class="jours" id="mercredi_checkbox"     @change="checkUncheck('mercredi_checkbox')"/>
                                        <label for="checkbox" class="ml-1">Mercredi</label>
                                        <br />

                                        <input type="checkbox" class="jours" id="jeudi_checkbox"        @change="checkUncheck('jeudi_checkbox')"/>
                                        <label for="checkbox" class="ml-1">Jeudi</label>
                                        <br />

                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>

                    <table class="table table-striped scrollbar scrollbar-deep-blue">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <div class="form-check pt-1 m-0">
                                        <input @change="checkGlobal($event)" type="checkbox" class="form-check-input" id="client_global" checked>
                                    </div>
                                </th>
                                <th scope="col">CustomerNo</th>
                                <th scope="col">CustomerNameE</th>
                                <th scope="col">CustomerType</th>
                                <th scope="col">Tel</th>
                                <th scope="col">Address</th>
                                <th scope="col">CityNameE</th>
                                <th scope="col">DistrictNameE</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="client in all_clients" :key="client.CustomerNo">
                                <td>
                                    <div class="form-check">
                                        <input @change="checkClient($event)" class="form-check-input client_checkbox" type="checkbox" :value="client.CustomerNo" :id="'client_'+client.CustomerNo" checked>
                                    </div>
                                </td>
                                <td>{{client.CustomerNo}}</td>
                                <td>{{client.CustomerNameE}}</td>
                                <td>{{client.CustomerType}}</td>
                                <td>{{client.Tel}}</td>
                                <td>{{client.Address}}</td>
                                <td>{{client.CityNameE}}</td>
                                <td>{{client.DistrictNameE}}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary"   @click="sendData()">Valider</button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

import {mapGetters, mapActions} from    "vuex"

export default {

    data() {
        return {

            DistrictNo              :   '',
            CityNo                  :   '',
            JPlan                   :   '',
            frequence_visite        :   '',
            StartWeek               :   '',

            all_clients             :   []  ,
            clients                 :   []  ,

            liste_journey_plan      :   []  ,

            districts               :   []  ,
            cites                   :   []  ,

            //

            jour_changed            :   false,

            sat                     :   0   ,
            sun                     :   0   ,
            mon                     :   0   ,
            tue                     :   0   ,
            wed                     :   0   ,
            thu                     :   0   
        }
    },

    computed : {

        ...mapGetters({
            getClientsChangeRoute           :   'client/getClientsChangeRoute'          ,
            getListeJourneyPlan             :   'journey_plan/getListeJourneyPlan'    
        }),
    },

    mounted() {

        this.clearData("#clientsChangeRouteModal")
    },  

    methods : {

        ...mapActions("client" ,  [
            "setClientsChangeRouteAction"   ,
        ]),

        async sendData() {

            this.setJours()

            let clients_copy            =   [...this.clients]

            for (let i = 0; i < clients_copy.length; i++) {

                // Set District City
                clients_copy[i].DistrictNo      =   this.DistrictNo 
                clients_copy[i].CityNo          =   this.CityNo 

                clients_copy[i].DistrictNameE   =   this.getDistrictNameE(this.DistrictNo)
                clients_copy[i].CityNameE       =   this.getCityNameE(this.CityNo)

                // Set JPlan
                clients_copy[i].JPlan           =   this.JPlan 

                // Set Frequency
                if((this.frequence_visite           ==  1)||(this.frequence_visite  ==  2)||(this.frequence_visite  ==  3)||(this.frequence_visite  ==  4)) {

                    clients_copy[i].Frequency       =   4
                }

                if(this.frequence_visite            ==  5) {

                    clients_copy[i].Frequency       =   2
                }

                if(this.frequence_visite            ==  6) {

                    clients_copy[i].Frequency       =   1
                }

                if(this.frequence_visite            ==  "") {

                    clients_copy[i].Frequency       =   ""
                }

                // Set StartWeek
                clients_copy[i].StartWeek       =   this.StartWeek

                // Set Journee
                clients_copy[i].jour_changed    =   this.jour_changed

                clients_copy[i].sat             =   this.sat
                clients_copy[i].sun             =   this.sun
                clients_copy[i].mon             =   this.mon
                clients_copy[i].tue             =   this.tue
                clients_copy[i].wed             =   this.wed
                clients_copy[i].thu             =   this.thu
            }

            // Send Client
            this.emitter.emit('reSetChangeRoute' , clients_copy)

            // Close Modal
            this.$hideModal("clientsChangeRouteModal")
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
                
                if(this.all_clients[i].CustomerNo   ==  id_client) {

                    return i
                }
            }

            return null
        },

        getIndexDelete(id_client) {

            for (let i = 0; i < this.clients.length; i++) {
                
                if(this.clients[i].CustomerNo   ==  id_client) {

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

                this.DistrictNo              =   ''
                this.CityNo                  =   ''
                this.JPlan                   =   ''
                this.frequence_visite        =   ''
                this.StartWeek               =   ''

                this.all_clients             =   []  
                this.clients                 =   []  

                this.districts               =   []  
                this.cites                   =   []  

                //

                this.jour_changed            =   false

                this.sat                     =   0   
                this.sun                     =   0   
                this.mon                     =   0   
                this.tue                     =   0   
                this.wed                     =   0   
                this.thu                     =   0   

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

        getData(clients) {

            // Set Value
            this.all_clients    =   [...clients]
            this.clients        =   [...clients]
        },

        async getComboData() {

            // Show Loading Page
            this.$showLoadingPage()

            const res_3                     =   await this.$callApi("post"  ,   "/rtm_willayas"         ,   null)
            this.districts                  =   res_3.data

            // Hide Loading Page
            this.$hideLoadingPage()
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

        setJours() {

            // Samedi

            const samedi_checkbox   =   document.getElementById("samedi_checkbox")
            
            if(samedi_checkbox.checked) {

                this.jour_changed   =   true

                this.sat            =   1
            }
            else {

                this.sat    =   0
            }

            //

            // Dimanche

            const dimanche_checkbox =   document.getElementById("dimanche_checkbox")
            
            if(dimanche_checkbox.checked) {

                this.jour_changed   =   true

                this.sun            =   1
            }
            else {

                this.sun    =   0
            }

            //

            // Lundi

            const lundi_checkbox    =   document.getElementById("lundi_checkbox")
            
            if(lundi_checkbox.checked) {

                this.jour_changed   =   true

                this.mon            =   1
            }
            else {

                this.mon    =   0
            }

            //

            // Mardi

            const mardi_checkbox    =   document.getElementById("mardi_checkbox")
            
            if(mardi_checkbox.checked) {

                this.jour_changed   =   true

                this.tue            =   1
            }
            else {

                this.tue    =   0
            }

            //

            // Mercredi

            const mercredi_checkbox =   document.getElementById("mercredi_checkbox")
            
            if(mercredi_checkbox.checked) {

                this.jour_changed   =   true

                this.wed            =   1
            }
            else {

                this.wed    =   0
            }

            //

            // Jeudi

            const jeudi_checkbox    =   document.getElementById("jeudi_checkbox")
            
            if(jeudi_checkbox.checked) {

                this.jour_changed   =   true

                this.thu            =   1
            }
            else {

                this.thu    =   0
            }

            //
        }
    },

    watch : {

        getClientsChangeRoute(new_clients, old_clients) {

            this.getComboData()
        },

        getListeJourneyPlan(new_liste_journey_plan, old_liste_journey_plan) {

            this.liste_journey_plan     =   new_liste_journey_plan
        }
    }
};
</script>