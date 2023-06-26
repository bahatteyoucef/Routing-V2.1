<template>

    <!-- Modal -->
    <div class="modal fade" id="modalResume" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" v-if="clients">Resume : </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3 table-responsive">
                        
                    <!-- Inputs -->
                    <div class="row mt-3 text-center">

                        <div class="col-10"> 
                            <input type="number"        class="form-control"        id="nombre_journee"     v-model="nombre_journee"    placeholder="Veuillez saisir le nombre des journees SVP ...">
                        </div>

                        <div class="col-2">
                            <button type="button" class="btn btn-primary"   @click="decouperClients()">Decouper</button>
                        </div>

                    </div>

                    <hr />

                    <div class="mt-5">

                        <div class="col-12 p-0"> 
                            <h5>Resume Globale :</h5>
                        </div>

                        <hr />

                        <table class="table mt-3">

                            <thead>
                                <tr>
                                    <th>JPlan</th>
                                    <th>JPlan Clients</th>

                                    <th>District</th>
                                    <th>District Clients</th>

                                    <th>City</th>
                                    <th>City Clients</th>

                                    <th>CustomerType</th>
                                    <th>CustomerType Clients</th>
                                </tr>
                            </thead>

                            <tbody id="datatable_resume_global_body">

                            </tbody>
                            
                        </table>

                    </div>

                    <hr />

                    <div class="mt-5">

                        <div class="col-12 p-0"> 
                            <h5>Resume Par Jour :</h5>
                        </div>

                        <hr />

                        <table class="table mt-3">

                            <thead>
                                <tr>
                                    <th>JPlan</th>
                                    <th>Journee</th>
                                    <th>Journee Clients</th>
                                    <th>City</th>
                                    <th>City Clients</th>
                                </tr>
                            </thead>

                            <tbody id="datatable_resume_par_jour_body">

                            </tbody>

                        </table>


                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"   @click="valider()">Valider</button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

export default {
    data() {
        return { 

            datatable_resume_global     :   null,

            nombre_journee              :   ""  ,
            liste_jourey_plan           :   []  ,

            resume_liste_journey_plan   :   null
        }
    },

    props : ["clients"],

    mounted() {

        this.clearData("#modalResume")
    },  

    methods : {

        setResume() {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(async () => {
                
                console.log(this.clients)

                this.resume_liste_journey_plan  =   this.$getResumeFileRouting(this.clients)

                console.log(this.resume_liste_journey_plan)

                // Global
                this.addGlobalBody()

                // Par Jour
                this.addParJourBody()

                // Hide Loading Page
                this.$hideLoadingPage()
            }, 55);
        },

        decouperClients() {

            if(this.$route.path.startsWith("/route/obs/route_import/add")) {

                console.log(111)

                this.decouperClientsAdd()
            }

            else {

                console.log(222)

                this.decouperClientsMap()
            }
        },

        decouperClientsAdd() {

            if(this.nombre_journee  >   0) {

                // Show Loading Page
                this.$showLoadingPage()

                setTimeout(() => {

                    let nombre_client_visite_par_jour       =   0

                    let clients_par_tous_les_routes_tempo   =   []

                    let clients_par_route_tous_les_jour     =   []

                    let clients_par_route                   =   []
                    let clients_par_jour                    =   []
                    let clients_ajoutes                     =   []

                    let existe_in_finale                    =   []

                    let Journee                             =   0

                    for (const [key, value] of Object.entries(this.resume_liste_journey_plan)) {

                        clients_par_route               =   [...value.clients.sort((b, a) => this.getDistance(0, 0, a.Latitude, a.Longitude) - this.getDistance(0, 0, b.Latitude, b.Longitude))]

                        clients_par_route_tous_les_jour =   [...[]]

                        nombre_client_visite_par_jour   =   Math.ceil(clients_par_route.length / this.nombre_journee)

                        Journee                         =   0

                        for (let j = 0; j < clients_par_route.length; j++) {

                            clients_par_jour                    =   [...[]]                    
                            existe_in_finale                    =   this.checkIfClientAddedJourSemaineAdd(clients_ajoutes, clients_par_route[j])    

                            if(!existe_in_finale) {

                                Journee                             =   Journee +   1

                                for (let k = 0; k < (nombre_client_visite_par_jour - 1); k++) {

                                    let min_distance_index      =   -1
                                    let min_distance            =   -1

                                    let tempo_min_distance      =   -1

                                    for (let l = j+1; l < clients_par_route.length; l++) {
                                        
                                        existe_in_finale                    =   this.checkIfClientAddedJourSemaineAdd(clients_ajoutes, clients_par_route[l])

                                        if(!existe_in_finale) {

                                            tempo_min_distance              =   this.getDistance(clients_par_route[j].Latitude, clients_par_route[j].Longitude, clients_par_route[l].Latitude, clients_par_route[l].Longitude)

                                            if((min_distance_index == -1)||(min_distance    >   tempo_min_distance)) {

                                                min_distance_index          =   l
                                                min_distance                =   tempo_min_distance
                                            }
                                        }
                                    }

                                    if(min_distance_index !=    -1) {

                                        clients_par_route[min_distance_index].Journee   =   "Jour "+Journee

                                        clients_par_jour.push(clients_par_route[min_distance_index])
                                        clients_ajoutes.push(clients_par_route[min_distance_index])
                                    }
                                }

                                clients_par_route[j].Journee    =   "Jour "+Journee

                                clients_par_jour.push(clients_par_route[j])
                                clients_ajoutes.push(clients_par_route[j])

                                clients_par_route_tous_les_jour.push(clients_par_jour)
                            }
                        }

                        clients_par_tous_les_routes_tempo.push(clients_par_route_tous_les_jour)

                    }

                    this.clients.sort((a,b) => b.CustomerNo -   a.CustomerNo)

                    for (let i = 0; i < clients_par_tous_les_routes_tempo.length; i++) {

                        for (let j = 0; j < clients_par_tous_les_routes_tempo[i].length; j++) {

                            for (let k = 0; k < clients_par_tous_les_routes_tempo[i][j].length; k++) {

                                for (let l = 0; l < this.clients.length; l++) {

                                    if(this.clients[l].CustomerNo   ==  clients_par_tous_les_routes_tempo[i][j][k].CustomerNo) {

                                        this.clients[l]     =   clients_par_tous_les_routes_tempo[i][j][k]
                                    }
                                }
                            }
                        }
                    }

                    // ReResume
                    this.setResume()

                    // Hide Loading Page
                    this.$hideLoadingPage()
    
                    // Send Feedback
                    this.$feedbackSuccess("Decoupage par Journee Realisés"     ,   "le decoupage a été realisés avec succès !")

                }, 55)
            }
        },

        decouperClientsMap() {

            if(this.nombre_journee  >   0) {

                // Show Loading Page
                this.$showLoadingPage()

                setTimeout(() => {

                    let nombre_client_visite_par_jour       =   0

                    let clients_par_tous_les_routes_tempo   =   []

                    let clients_par_route_tous_les_jour     =   []

                    let clients_par_route                   =   []
                    let clients_par_jour                    =   []
                    let clients_ajoutes                     =   []

                    let existe_in_finale                    =   []

                    let Journee                             =   0

                    for (const [key, value] of Object.entries(this.resume_liste_journey_plan)) {

                        clients_par_route               =   [...value.clients.sort((b, a) => this.getDistance(0, 0, a.Latitude, a.Longitude) - this.getDistance(0, 0, b.Latitude, b.Longitude))]

                        clients_par_route_tous_les_jour =   [...[]]

                        nombre_client_visite_par_jour   =   Math.ceil(clients_par_route.length / this.nombre_journee)

                        Journee                         =   0

                        for (let j = 0; j < clients_par_route.length; j++) {

                            clients_par_jour                    =   [...[]]                    
                            existe_in_finale                    =   this.checkIfClientAddedJourSemaineMap(clients_ajoutes, clients_par_route[j])    

                            if(!existe_in_finale) {

                                Journee                             =   Journee +   1

                                for (let k = 0; k < (nombre_client_visite_par_jour - 1); k++) {

                                    let min_distance_index      =   -1
                                    let min_distance            =   -1

                                    let tempo_min_distance      =   -1

                                    for (let l = j+1; l < clients_par_route.length; l++) {
                                        
                                        existe_in_finale                    =   this.checkIfClientAddedJourSemaineMap(clients_ajoutes, clients_par_route[l])

                                        if(!existe_in_finale) {

                                            tempo_min_distance              =   this.getDistance(clients_par_route[j].Latitude, clients_par_route[j].Longitude, clients_par_route[l].Latitude, clients_par_route[l].Longitude)

                                            if((min_distance_index == -1)||(min_distance    >   tempo_min_distance)) {

                                                min_distance_index          =   l
                                                min_distance                =   tempo_min_distance
                                            }
                                        }
                                    }

                                    if(min_distance_index !=    -1) {

                                        clients_par_route[min_distance_index].Journee   =   "Jour "+Journee

                                        clients_par_jour.push(clients_par_route[min_distance_index])
                                        clients_ajoutes.push(clients_par_route[min_distance_index])
                                    }
                                }

                                clients_par_route[j].Journee    =   "Jour "+Journee

                                clients_par_jour.push(clients_par_route[j])
                                clients_ajoutes.push(clients_par_route[j])

                                clients_par_route_tous_les_jour.push(clients_par_jour)
                            }
                        }

                        clients_par_tous_les_routes_tempo.push(clients_par_route_tous_les_jour)

                    }

                    this.clients.sort((a,b) => b.id -   a.id)

                    for (let i = 0; i < clients_par_tous_les_routes_tempo.length; i++) {

                        for (let j = 0; j < clients_par_tous_les_routes_tempo[i].length; j++) {

                            for (let k = 0; k < clients_par_tous_les_routes_tempo[i][j].length; k++) {

                                for (let l = 0; l < this.clients.length; l++) {

                                    if(this.clients[l].id   ==  clients_par_tous_les_routes_tempo[i][j][k].id) {

                                        this.clients[l]     =   clients_par_tous_les_routes_tempo[i][j][k]
                                    }
                                }
                            }
                        }
                    }

                    // ReResume
                    this.setResume()

                    // Hide Loading Page
                    this.$hideLoadingPage()
    
                    // Send Feedback
                    this.$feedbackSuccess("Decoupage par Journee Realisés"     ,   "le decoupage a été realisés avec succès !")

                }, 55)
            }
        },

        //

        valider() {

            this.emitter.emit('reSetClientsDecoupeByJournee' , this.clients)

            // Send Feedback
            this.$feedbackSuccess("Decoupage par Journee Validés"     ,   "le decoupage a été validés avec succès !")

            // Close Modal
            this.$hideModal("modalResume")
        },

        //

        addGlobalBody() {

            let district_index          =   1
            let cite_index              =   1
            let type_client_index       =   1

            let tbody                   =   document.getElementById("datatable_resume_global_body")

            tbody.innerHTML             =   ""

            // Create rows and cells for each data entry
            for (const [key, journey_plan] of Object.entries(this.resume_liste_journey_plan)) {

                var customerJourneyPlanRow                          =   document.createElement("tr");

                //

                var JourneyPlanCell                                 =   document.createElement("td");
                JourneyPlanCell.textContent                         =   journey_plan.JPlan;
                JourneyPlanCell.setAttribute("rowspan"              ,   journey_plan.rowspan);

                //

                var JourneyPlanClientsCell                          =   document.createElement("td");
                JourneyPlanClientsCell.textContent                  =   journey_plan.clients.length;
                JourneyPlanClientsCell.setAttribute("rowspan"       ,   journey_plan.rowspan);

                //

                customerJourneyPlanRow.appendChild(JourneyPlanCell)
                customerJourneyPlanRow.appendChild(JourneyPlanClientsCell)

                //

                for (const [key_2, district] of Object.entries(this.resume_liste_journey_plan[key].districts)) {

                    var districtRow                          =   document.createElement("tr");

                    //

                    var districtCell                                =   document.createElement("td");
                    districtCell.textContent                        =   district.DistrictNameE;
                    districtCell.setAttribute("rowspan"             ,   district.rowspan);

                    //

                    var districtClientsCell                         =   document.createElement("td");
                    districtClientsCell.textContent                 =   district.clients.length;
                    districtClientsCell.setAttribute("rowspan"      ,   district.rowspan);

                    //

                    if(district_index   ==  1) {

                        customerJourneyPlanRow.appendChild(districtCell)
                        customerJourneyPlanRow.appendChild(districtClientsCell)
                    }

                    else {

                        districtRow.appendChild(districtCell)
                        districtRow.appendChild(districtClientsCell)
                    }

                    //

                    // Create the remaining cites rows
                    for (const [key_3, cite] of Object.entries(this.resume_liste_journey_plan[key].districts[key_2].cites)) {

                        var citeRow                                 =   document.createElement("tr");

                        //

                        var citeCell                                =   document.createElement("td");
                        citeCell.textContent                        =   cite.CityNameE;
                        citeCell.setAttribute("rowspan"             ,   cite.rowspan);

                        //

                        var citeClientsCell                         =   document.createElement("td");
                        citeClientsCell.textContent                 =   cite.clients.length;
                        citeClientsCell.setAttribute("rowspan"      ,   cite.rowspan);

                        //

                        if(district_index   ==  1) {

                            if(cite_index   ==  1) {

                                customerJourneyPlanRow.appendChild(citeCell)
                                customerJourneyPlanRow.appendChild(citeClientsCell)
                            }

                            else {

                                citeRow.appendChild(citeCell)
                                citeRow.appendChild(citeClientsCell)
                            }
                        }

                        else {

                            if(cite_index   ==  1) {

                                districtRow.appendChild(citeCell)
                                districtRow.appendChild(citeClientsCell)
                            }

                            else {

                                citeRow.appendChild(citeCell)
                                citeRow.appendChild(citeClientsCell)
                            }
                        }

                        // Create the remaining type client rows
                        for (const [key_4, type_client] of Object.entries(this.resume_liste_journey_plan[key].districts[key_2].cites[key_3].liste_type_client)) {

                            var CustomerTypeRow                             =   document.createElement("tr");

                            //

                            var CustomerTypeCell                            =   document.createElement("td");
                            CustomerTypeCell.textContent                    =   type_client.CustomerType;
                            CustomerTypeCell.setAttribute("rowspan"         ,   type_client.rowspan);

                            //

                            var CustomerTypeClientsCell                     =   document.createElement("td");
                            CustomerTypeClientsCell.textContent             =   type_client.clients.length;
                            CustomerTypeClientsCell.setAttribute("rowspan"  ,   type_client.rowspan);

                            //

                            if(district_index   ==  1) {

                                if(cite_index   ==  1) {

                                    if(type_client_index    ==  1) {

                                        customerJourneyPlanRow.appendChild(CustomerTypeCell)
                                        customerJourneyPlanRow.appendChild(CustomerTypeClientsCell)

                                        tbody.appendChild(customerJourneyPlanRow)
                                    }

                                    else {

                                        CustomerTypeRow.appendChild(CustomerTypeCell)
                                        CustomerTypeRow.appendChild(CustomerTypeClientsCell)

                                        tbody.appendChild(CustomerTypeRow)
                                    }
                                }

                                else {

                                    if(type_client_index    ==  1) {

                                        citeRow.appendChild(CustomerTypeCell)
                                        citeRow.appendChild(CustomerTypeClientsCell)

                                        tbody.appendChild(citeRow)
                                    }

                                    else {

                                        CustomerTypeRow.appendChild(CustomerTypeCell)
                                        CustomerTypeRow.appendChild(CustomerTypeClientsCell)

                                        tbody.appendChild(CustomerTypeRow)
                                    }
                                }
                            }

                            else {

                                if(cite_index   ==  1) {

                                    if(type_client_index    ==  1) {

                                        districtRow.appendChild(CustomerTypeCell)
                                        districtRow.appendChild(CustomerTypeClientsCell)

                                        tbody.appendChild(citeRow)
                                    }

                                    else {

                                        CustomerTypeRow.appendChild(CustomerTypeCell)
                                        CustomerTypeRow.appendChild(CustomerTypeClientsCell)

                                        tbody.appendChild(CustomerTypeRow)
                                    }
                                }

                                else {

                                    if(type_client_index    ==  1) {

                                        citeRow.appendChild(CustomerTypeCell)
                                        citeRow.appendChild(CustomerTypeClientsCell)

                                        tbody.appendChild(citeRow)
                                    }

                                    else {

                                        CustomerTypeRow.appendChild(CustomerTypeCell)
                                        CustomerTypeRow.appendChild(CustomerTypeClientsCell)

                                        tbody.appendChild(CustomerTypeRow)
                                    }
                                }
                            }

                            //

                            type_client_index                       =   type_client_index   +   1
                        }

                        type_client_index                           =   1

                        cite_index                                  =   cite_index          +   1
                    }

                    cite_index                                      =   1   

                    district_index                                  =   district_index      +   1
                }

                district_index          =   1
            }
        },

        addParJourBody() {

            let journee_index           =   1
            let cite_index              =   1

            let tbody                   =   document.getElementById("datatable_resume_par_jour_body")

            tbody.innerHTML             =   ""

            // Create rows and cells for each data entry
            for (const [key, journey_plan] of Object.entries(this.resume_liste_journey_plan)) {

                var customerJourneyPlanRow                          =   document.createElement("tr");

                //

                var JourneyPlanCell                                 =   document.createElement("td");
                JourneyPlanCell.textContent                         =   journey_plan.JPlan;
                JourneyPlanCell.setAttribute("rowspan"              ,   journey_plan.rowspan_journee);

                //

                customerJourneyPlanRow.appendChild(JourneyPlanCell)

                //

                for (const [key_2, journee] of Object.entries(this.resume_liste_journey_plan[key].liste_journee)) {

                    var journeeRow                                  =   document.createElement("tr");

                    //

                    var journeeCell                                 =   document.createElement("td");
                    journeeCell.textContent                         =   journee.Journee;
                    journeeCell.setAttribute("rowspan"              ,   journee.rowspan_journee);    

                    //

                    var journeeClientsCell                          =   document.createElement("td");
                    journeeClientsCell.textContent                  =   journee.clients.length;
                    journeeClientsCell.setAttribute("rowspan"       ,   journee.rowspan_journee);

                    //

                    if(journee_index   ==  1) {

                        customerJourneyPlanRow.appendChild(journeeCell)
                        customerJourneyPlanRow.appendChild(journeeClientsCell)
                    }

                    else {

                        journeeRow.appendChild(journeeCell)
                        journeeRow.appendChild(journeeClientsCell)
                    }

                    //

                    // Create the remaining cites rows
                    for (const [key_3, cite] of Object.entries(this.resume_liste_journey_plan[key].liste_journee[key_2].cites)) {

                        var citeRow                                 =   document.createElement("tr");

                        //

                        var citeCell                                =   document.createElement("td");
                        citeCell.textContent                        =   cite.CityNameE;
                        citeCell.setAttribute("rowspan"             ,   cite.rowspan_journee);

                        //

                        var citeClientsCell                         =   document.createElement("td");
                        citeClientsCell.textContent                 =   cite.clients.length;
                        citeClientsCell.setAttribute("rowspan"      ,   cite.rowspan_journee);

                        //

                        if(journee_index    ==  1) {

                            if(cite_index   ==  1) {

                                customerJourneyPlanRow.appendChild(citeCell)
                                customerJourneyPlanRow.appendChild(citeClientsCell)

                                tbody.appendChild(customerJourneyPlanRow)
                            }

                            else {

                                citeRow.appendChild(citeCell)
                                citeRow.appendChild(citeClientsCell)

                                tbody.appendChild(citeRow)
                            }
                        }

                        else {

                            if(cite_index   ==  1) {

                                journeeRow.appendChild(citeCell)
                                journeeRow.appendChild(citeClientsCell)

                                tbody.appendChild(journeeRow)
                            }

                            else {

                                citeRow.appendChild(citeCell)
                                citeRow.appendChild(citeClientsCell)

                                tbody.appendChild(citeRow)
                            }
                        }

                        cite_index                                  =   cite_index  +   1
                    }

                    cite_index                                      =   1   

                    journee_index                                   =   journee_index      +   1
                }

                journee_index   =   1
            }            

        },

        //

        getRowSpan(value) {

            let count = 1;

            if (this.isObject(value)) {

                for (const subKey in value) {

                    if(subKey   !=  "clients") {

                        if (this.isObject(value[subKey])) {
                            count += this.getRowSpan(value[subKey]);
                        } else {
                            count++;
                        }
                    }
                }
            }
    
            return count;
        },

        isObject(value) {

            return typeof value === "object" && value !== null;
        },

        //

        checkIfClientAddedJourSemaineAdd(clients_ajoutes, client) {

            for (let i = 0; i < clients_ajoutes.length; i++) {
                
                if(client.CustomerNo  ==  clients_ajoutes[i].CustomerNo) {

                    return true;
                }
            }

            return false;
        },

        checkIfClientAddedJourSemaineMap(clients_ajoutes, client) {

            for (let i = 0; i < clients_ajoutes.length; i++) {
                
                if(client.id    ==  clients_ajoutes[i].id) {

                    return true;
                }
            }

            return false;
        },    

        //

        getDistance(latitude_1, longitude_1, latitude_2, longitude_2) {

            return this.$map.$setDistanceStraight(latitude_1, longitude_1, latitude_2, longitude_2)
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {
    
                this.resume_liste_journey_plan      =   null
            });
        }

    }
}
</script>
