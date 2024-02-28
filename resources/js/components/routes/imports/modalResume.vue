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

                    <div class="row">
                        <div class="col-sm-11">
                            <input type="number" class="form-control" placeholder="number of routes"    v-model="nomber_routes"/>
                        </div>

                        <div class="col-sm-1">
                            <button class="btn btn-primary w-100"   @click="decouperRoutes()"> Divide </button>
                        </div>
                    </div>

                    <hr />

                    <div class="mt-5">

                        <div class="col-12 p-0"> 
                            <h5>Resume (Global) :</h5>
                        </div>

                        <hr />

                        <table class="table mt-3">

                            <thead>
                                <tr>
                                    <th class="col-2">JPlan</th>
                                    <th class="col-1">JPlan Clients</th>

                                    <th class="col-1">District</th>
                                    <th class="col-1">District Clients</th>

                                    <th class="col-1">City</th>
                                    <th class="col-1">City Clients</th>

                                    <th class="col-1">CustomerType</th>
                                    <th class="col-1">CustomerType Clients</th>

                                    <th class="col-2">Options</th>
                                </tr>
                            </thead>

                            <tbody id="datatable_resume_global_body">

                            </tbody>
                            
                        </table>

                    </div>

                    <hr />

                    <div class="mt-5">

                        <div class="col-12 p-0"> 
                            <h5>Resume (Journee) :</h5>
                        </div>

                        <hr />

                        <table class="table mt-3">

                            <thead>
                                <tr>
                                    <th class="col-2">JPlan</th>
                                    <th class="col-2">Journee</th>
                                    <th class="col-2">Journee Clients</th>
                                    <th class="col-2">City</th>
                                    <th class="col-2">City Clients</th>
                                </tr>
                            </thead>

                            <tbody id="datatable_resume_par_jour_body">

                            </tbody>

                        </table>


                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"   @click="valider()">Confirm</button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

export default {

    data() {
        return { 

            datatable_resume_global     :   null    ,

            nomber_routes               :   0       ,

            nombre_journee              :   ""      ,
            liste_jourey_plan           :   []      ,

            resume_liste_journey_plan   :   null    ,

            clients                     :   []
        }
    },

    props   : ["type", "id_route_import_tempo"],

    mounted() {

        this.clearData("#modalResume")
    },  

    methods : {

        async getClients() {

            if(this.type    ==  "temporary") {

                await this.getClientsTemporary()
                this.setResume()
            }

            if(this.type    ==  "permanent") {

                await this.getClientsPermanent()
                this.setResume()
            }
        },

        async getClientsTemporary() {

            this.$showLoadingPage()

            // Set Data

            const res   =   await this.$callApi('post'  ,   '/route_import_tempo/last'  ,   null)         
            console.log(res)

            if(res.status===200){

                if(typeof res.data.clients              !=  "undefined") {

                    this.clients                        =   res.data.clients
                }

                this.$hideLoadingPage()
            }
            
            else{

                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },

        async getClientsPermanent() {

            this.$showLoadingPage()

            const res                   =   await this.$callApi("post"  ,   "/route/obs/route_import/"+this.$route.params.id_route_import+"/details",   null)

            // Set Clients
            this.clients                =   res.data.route_import.data

            this.$hideLoadingPage()
        },

        //

        setResume() {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(async () => {
                
                this.resume_liste_journey_plan  =   this.$getResumeFileRouting(this.clients)

                // Global
                this.addGlobalBody()

                // Par Jour
                this.addParJourBody()

                // Hide Loading Page
                this.$hideLoadingPage()
            }, 55);
        },

        //

        decouperRoutes() {

            //

            if(this.nomber_routes  >   0) {

                // Show Loading Page
                this.$showLoadingPage()

                setTimeout(() => {

                    let nombre_client_visite_par_route      =   0

                    let clients_par_tous_les_route          =   []

                    let clients                             =   []
                    let clients_par_route                   =   []
                    let clients_ajoutes                     =   []

                    let existe_in_finale                    =   []

                    let Route                               =   0

                    let clients_neighbours                  =   []
                    let clients_neighbours_index            =   0
                    let nombre_neighbours                   =   0

                    let nombre_clients_sans_route           =   0

                    //

                    clients                         =   [...this.clients.sort((b, a) => this.getDistance(0, 0, a.Latitude, a.Longitude) - this.getDistance(0, 0, b.Latitude, b.Longitude))]

                    clients_par_tous_les_route      =   [...[]]

                    nombre_client_visite_par_route  =   Math.floor((clients.length) / this.nomber_routes)

                    Route                           =   0

                    nombre_clients_sans_route       =   clients.length  -   nombre_client_visite_par_route*this.nomber_routes

                    for (let j = 0; j < clients.length; j++) {

                        clients_par_route                   =   [...[]]                    
                        existe_in_finale                    =   this.checkIfClientAddedRouteMap(clients_ajoutes, clients[j])    

                        if(!existe_in_finale) {

                            Route                           =   Route   +   1

                            clients_neighbours              =   [...clients.slice(j+1, clients.length)]
                            clients_neighbours_index        =   0
                            nombre_neighbours               =   0
                            
                            clients_neighbours.sort((a, b) => this.getDistance(clients[j].Latitude, clients[j].Longitude, a.Latitude, a.Longitude) - this.getDistance(clients[j].Latitude, clients[j].Longitude, b.Latitude, b.Longitude));

                            while(nombre_neighbours  <  nombre_client_visite_par_route - 1) {                      
                            
                                if(clients_neighbours[clients_neighbours_index]) {

                                    existe_in_finale                    =   this.checkIfClientAddedRouteMap(clients_ajoutes, clients_neighbours[clients_neighbours_index])

                                    if(!existe_in_finale) {

                                        clients_neighbours[clients_neighbours_index].JPlan  =   "Route "    +   Route

                                        clients_par_route.push(clients_neighbours[clients_neighbours_index])
                                        clients_ajoutes.push(clients_neighbours[clients_neighbours_index])

                                        nombre_neighbours       =   nombre_neighbours           +   1
                                    }

                                    clients_neighbours_index    =   clients_neighbours_index    +   1
                                }

                                else {

                                    break
                                }
                            }

                            //

                            if(nombre_clients_sans_route  >   0) {

                                while(true) {                      
                                
                                    if(clients_neighbours[clients_neighbours_index]) {

                                        existe_in_finale                    =   this.checkIfClientAddedRouteMap(clients_ajoutes, clients_neighbours[clients_neighbours_index])

                                        if(!existe_in_finale) {

                                            clients_neighbours[clients_neighbours_index].JPlan  =   "Route "    +   Route

                                            clients_par_route.push(clients_neighbours[clients_neighbours_index])
                                            clients_ajoutes.push(clients_neighbours[clients_neighbours_index])

                                            nombre_neighbours               =   nombre_neighbours           +   1

                                            nombre_clients_sans_route     =   nombre_clients_sans_route -   1

                                            break
                                        }

                                        clients_neighbours_index    =   clients_neighbours_index    +   1
                                    }

                                    else {

                                        break
                                    }
                                }
                            }

                            //

                            clients[j].JPlan    =   "Route "    +   Route

                            clients_par_route.push(clients[j])
                            clients_ajoutes.push(clients[j])
                            clients_par_tous_les_route.push(clients_par_route)
                        }
                    }

                    //

                    for (let j = 0; j < clients_par_tous_les_route.length; j++) {

                        for (let k = 0; k < clients_par_tous_les_route[j].length; k++) {

                            for (let l = 0; l < this.clients.length; l++) {

                                if(this.clients[l].id   ==  clients_par_tous_les_route[j][k].id) {

                                    this.clients[l]         =   clients_par_tous_les_route[j][k]
                                }
                            }
                        }
                    }
                    
                    //

                    // ReResume
                    this.setResume()

                    // Hide Loading Page
                    this.$hideLoadingPage()
    
                }, 55)
            }
        },

        //

        decouperClients(key) {

            let nombre_journee  =   document.getElementById("route_"+key).value

            //

            if(nombre_journee  >   0) {

                // Show Loading Page
                this.$showLoadingPage()

                setTimeout(() => {

                    let nombre_client_visite_par_jour       =   0

                    let clients_par_route_tous_les_jour     =   []

                    let clients_par_route                   =   []
                    let clients_par_jour                    =   []
                    let clients_ajoutes                     =   []

                    let existe_in_finale                    =   []

                    let Journee                             =   0

                    let clients_neighbours                  =   []
                    let clients_neighbours_index            =   0
                    let nombre_neighbours                   =   0

                    let nombre_clients_sans_journee         =   0

                    //

                    clients_par_route               =   [...this.resume_liste_journey_plan[key].clients.sort((b, a) => this.getDistance(0, 0, a.Latitude, a.Longitude) - this.getDistance(0, 0, b.Latitude, b.Longitude))]

                    clients_par_route_tous_les_jour =   [...[]]

                    nombre_client_visite_par_jour   =   Math.floor(clients_par_route.length / nombre_journee)

                    Journee                         =   0

                    nombre_clients_sans_journee     =   clients_par_route.length    -   nombre_client_visite_par_jour*nombre_journee

                    //

                    for (let j = 0; j < clients_par_route.length; j++) {

                        clients_par_jour                    =   [...[]]                    
                        existe_in_finale                    =   this.checkIfClientAddedJourSemaineMap(clients_ajoutes, clients_par_route[j])    

                        if(!existe_in_finale) {

                            Journee                         =   Journee   +   1

                            clients_neighbours          =   [...clients_par_route.slice(j+1, clients_par_route.length)]
                            clients_neighbours_index    =   0
                            nombre_neighbours           =   0
                            
                            clients_neighbours.sort((a, b) => this.getDistance(clients_par_route[j].Latitude, clients_par_route[j].Longitude, a.Latitude, a.Longitude) - this.getDistance(clients_par_route[j].Latitude, clients_par_route[j].Longitude, b.Latitude, b.Longitude));

                            while(nombre_neighbours  <  nombre_client_visite_par_jour - 1) {                      
                            
                                if(clients_neighbours[clients_neighbours_index]) {

                                    existe_in_finale                    =   this.checkIfClientAddedRouteMap(clients_ajoutes, clients_neighbours[clients_neighbours_index])

                                    if(!existe_in_finale) {

                                        clients_neighbours[clients_neighbours_index].Journee    =   "Jour " +   Journee

                                        clients_par_jour.push(clients_neighbours[clients_neighbours_index])
                                        clients_ajoutes.push(clients_neighbours[clients_neighbours_index])

                                        nombre_neighbours       =   nombre_neighbours           +   1
                                    }
                                    
                                    clients_neighbours_index    =   clients_neighbours_index    +   1
                                }

                                else {

                                    break
                                }
                            }

                            //

                            if(nombre_clients_sans_journee  >   0) {

                                while(true) {                      
                                
                                    if(clients_neighbours[clients_neighbours_index]) {

                                        existe_in_finale                    =   this.checkIfClientAddedRouteMap(clients_ajoutes, clients_neighbours[clients_neighbours_index])

                                        if(!existe_in_finale) {

                                            clients_neighbours[clients_neighbours_index].Journee    =   "Jour " +   Journee

                                            clients_par_jour.push(clients_neighbours[clients_neighbours_index])
                                            clients_ajoutes.push(clients_neighbours[clients_neighbours_index])

                                            nombre_neighbours               =   nombre_neighbours           +   1

                                            nombre_clients_sans_journee     =   nombre_clients_sans_journee -   1

                                            break
                                        }

                                        clients_neighbours_index    =   clients_neighbours_index    +   1
                                    }

                                    else {

                                        break
                                    }
                                }
                            }

                            //

                            clients_par_route[j].Journee    =   "Jour " +   Journee

                            clients_par_jour.push(clients_par_route[j])
                            clients_ajoutes.push(clients_par_route[j])
                            clients_par_route_tous_les_jour.push(clients_par_jour)
                        }
                    }

                    //

                    for (let j = 0; j < clients_par_route_tous_les_jour.length; j++) {

                        for (let k = 0; k < clients_par_route_tous_les_jour[j].length; k++) {

                            for (let l = 0; l < this.clients.length; l++) {

                                if(this.clients[l].id   ==  clients_par_route_tous_les_jour[j][k].id) {

                                    this.clients[l]     =   clients_par_route_tous_les_jour[j][k]
                                }
                            }
                        }
                    }
                    
                    // ReResume
                    this.setResume()

                    // Hide Loading Page
                    this.$hideLoadingPage()
    
                }, 55)
            }
        },

        //

        getMinDistanceJournee(clients_par_route_tous_les_jour, client, journee_util_array) {

            let min_distance_index      =   -1
            let min_distance            =   -1

            let tempo_min_distance      =   -1

            for (let i = 0; i < clients_par_route_tous_les_jour.length; i++) {

                tempo_min_distance              =   this.getDistance(clients_par_route_tous_les_jour[i][0].Latitude, clients_par_route_tous_les_jour[i][0].Longitude, client.Latitude, client.Longitude)

                if(((min_distance_index == -1)||(min_distance    >   tempo_min_distance))&&(!journee_util_array.includes(i))) {

                    min_distance_index          =   i
                    min_distance                =   tempo_min_distance
                }
            }

            return min_distance_index
        },

        getMinDistanceRoute(clients_par_tous_les_route, client, route_util_array) {

            let min_distance_index      =   -1
            let min_distance            =   -1

            let tempo_min_distance      =   -1

            for (let i = 0; i < clients_par_tous_les_route.length; i++) {

                tempo_min_distance              =   this.getDistance(clients_par_tous_les_route[i][0].Latitude, clients_par_tous_les_route[i][0].Longitude, client.Latitude, client.Longitude)

                if(((min_distance_index == -1)||(min_distance    >   tempo_min_distance))&&(!route_util_array.includes(i))) {

                    min_distance_index          =   i
                    min_distance                =   tempo_min_distance
                }
            }

            return min_distance_index
        },

        //

        getMinDistanceJourneeParClients(clients_par_route_tous_les_jour, client, journee_util_array) {

            let min_distance_index      =   -1
            let min_distance            =   -1

            let tempo_min_distance      =   -1

            for (let i = 0; i < clients_par_route_tous_les_jour.length; i++) {

                tempo_min_distance              =   this.getDistance(clients_par_route_tous_les_jour[i][0].Latitude, clients_par_route_tous_les_jour[i][0].Longitude, client.Latitude, client.Longitude)

                if(((min_distance_index == -1)||(min_distance    >   tempo_min_distance))&&(!journee_util_array.includes(i))) {

                    min_distance_index          =   i
                    min_distance                =   tempo_min_distance
                }
            }

            return min_distance_index
        },

        getMinDistanceRouteClients(clients_par_route, clients, nombre_client_visite_par_route, client_util_array) {

            //

            let min_distance_index      =   -1
            let min_distance            =   -1

            let tempo_min_distance      =   -1

            for(let i = nombre_client_visite_par_route*this.nomber_routes; i < clients.length; i++) {

                tempo_min_distance              =   this.getDistance(clients_par_route[0].Latitude, clients_par_route[0].Longitude, clients[i].Latitude, clients[i].Longitude)

                if(((min_distance_index == -1)||(min_distance    >   tempo_min_distance))&&(!client_util_array.includes(i))) {

                    min_distance_index          =   i
                    min_distance                =   tempo_min_distance
                }
            }

            return min_distance_index;
        },

        //

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
    
                }, 55)
            }
        },

        //

        async valider() {

            if(this.type    ==  "temporary") {

                this.$showLoadingPage()

                let formData    =   new FormData();

                formData.append("data"  ,   JSON.stringify(this.clients))

                const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo/update", formData)
                console.log(res.data)

                if(res.status===200){

                    this.emitter.emit('reSetClientsDecoupeByJourneeAdd' , this.clients)

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Feedback
                    this.$feedbackSuccess(res.data.header     ,   res.data.message)

                    // Close Modal
                    this.$hideModal("modalResume")
                }
                
                else{

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)
                }            
            }

            else {

                this.$showLoadingPage()

                let formData    =   new FormData();

                formData.append("data"  ,   JSON.stringify(this.clients))

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/update",  formData)
                console.log(res.data)

                if(res.status===200){

                    this.emitter.emit('reSetClientsDecoupeByJourneeMap' , this.clients)

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Feedback
                    this.$feedbackSuccess(res.data.header     ,   res.data.message)

                    // Close Modal
                    this.$hideModal("modalResume")
                }
                
                else{

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)
                }
            }
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

                                        tbody.appendChild(districtRow)
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

                //

                district_index          =   1

                //

                var OptionsCell                                 =   document.createElement("td")
                OptionsCell.setAttribute("rowspan"              ,   journey_plan.rowspan)

                var OptionsDiv                                  =   document.createElement("div")
                OptionsDiv.classList.add("row")
                OptionsDiv.classList.add("justify-content-center")

                var OptionsInput                                =   document.createElement("input")
                OptionsInput.setAttribute("type", "number")
                OptionsInput.setAttribute("id"  , "route_"+key)
                OptionsInput.setAttribute("placeholder" ,   "number of workdays ...")

                OptionsInput.classList.add("form-control")
                OptionsInput.classList.add("w-75")

                var OptionsButton                               =   document.createElement("button")
                OptionsButton.setAttribute("type", "button")
                OptionsButton.classList.add("btn-primary")
                OptionsButton.classList.add("btn")
                OptionsButton.classList.add("mt-1")
                OptionsButton.classList.add("w-75")

                OptionsButton.textContent                       =   "Divide"

                //

                OptionsButton.addEventListener("click", ()  =>  {this.decouperClients(key)})

                OptionsDiv.appendChild(OptionsInput)
                OptionsDiv.appendChild(OptionsButton)
                OptionsCell.appendChild(OptionsDiv)
                customerJourneyPlanRow.appendChild(OptionsCell)

                //
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

        checkIfClientAddedRouteMap(clients_ajoutes, client) {

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
