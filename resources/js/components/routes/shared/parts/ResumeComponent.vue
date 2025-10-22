<template>
    <div>
        <div v-if="(($isRole('Super Admin'))||($isRole('BU Manager'))||($isRole('BackOffice')))"    class="row">
            <div class="col-sm-3">
                <input type="number" class="form-control" placeholder="number of routes"        v-model="nomber_routes"/>
            </div>

            <div class="col-sm-3">
                <input type="text"  class="form-control" placeholder="routes label prefix"      v-model="routeLabelPrefix"/>
            </div>

            <div class="col-sm-3">
                <input type="text"  class="form-control" placeholder="journees label prefix"    v-model="journeeLabelPrefix"/>
            </div>

            <div class="col-sm-3">
                <button class="btn btn-primary w-100"   @click="decouperRoutes()"> Divide </button>
            </div>
        </div>

        <hr v-if="(($isRole('Super Admin'))||($isRole('BU Manager'))||($isRole('BackOffice')))" />

        <div class="mt-5">

            <div class="col-sm-12 p-0"> 
                <h5>Resume (Global) :</h5>
            </div>

            <hr />

            <table class="table mt-3">

                <thead>
                    <tr>
                        <th class="col-sm-2">JPlan</th>
                        <th class="col-sm-1">JPlan Clients</th>

                        <th class="col-sm-1">District</th>
                        <th class="col-sm-1">District Clients</th>

                        <th class="col-sm-1">City</th>
                        <th class="col-sm-1">City Clients</th>

                        <th class="col-sm-1">CustomerType</th>
                        <th class="col-sm-1">CustomerType Clients</th>

                        <th class="col-sm-2">Options</th>
                    </tr>
                </thead>

                <tbody id="datatable_resume_global_body">

                </tbody>
                
            </table>

        </div>

        <hr />

        <div class="mt-5">
            <div class="col-sm-12 p-0"> 
                <h5>Resume (Journee) :</h5>
            </div>

            <hr />

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th class="col-sm-2">JPlan</th>
                        <th class="col-sm-2">Journee</th>
                        <th class="col-sm-2">Journee Clients</th>
                        <th class="col-sm-2">City</th>
                        <th class="col-sm-2">City Clients</th>
                    </tr>
                </thead>

                <tbody id="datatable_resume_par_jour_body">
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>

export default {

    data() {
        return { 

            nomber_routes               :   0       ,

            nombre_journee              :   ""      ,
            liste_jourey_plan           :   []      ,

            resume_liste_journey_plan   :   null    ,

            //

            routeLabelPrefix            :   "ROUTE" ,
            journeeLabelPrefix          :   "Jour"
        }
    },

    props   : ["clients", "id_route_import_tempo", "id_route_import"],

    mounted() {
    },  

    methods : {

        setResume() {

            // Show Loading Page
            this.$showLoadingPage()
                
            this.resume_liste_journey_plan  =   this.$getResumeFileRouting(this.clients)

            // Global
            this.addGlobalBody()

            // Par Jour
            this.addParJourBody()

            // Hide Loading Page
            this.$hideLoadingPage()
        },

        //  //  //  //  //

        async partitionClients({ clientList, numGroups, propertyName, labelPrefix }) {
            if (!clientList || clientList.length === 0 || !numGroups || numGroups <= 0) {
                return [];
            }

            // Use a Set for O(1) average time complexity checks to see if a client has been added.
            // This is a massive performance improvement over Array.prototype.find or a for-loop.
            const addedClientIds = new Set();
            
            // Work on a shallow copy to avoid modifying the original array's order during sorting.
            const clients = [...clientList];
            
            // Initial sort to pick the first "seed" client for the first route.
            // Sorting by distance from 0,0 is arbitrary. Consider a better heuristic,
            // e.g., finding the geometric center of all clients and starting with the one farthest from it.
            clients.sort((b, a) => this.getDistance(0, 0, a.Latitude, a.Longitude) - this.getDistance(0, 0, b.Latitude, b.Longitude));

            const allGroups = [];
            const clientsPerGroup = Math.floor(clients.length / numGroups);
            let remainingClients = clients.length % numGroups;
            let groupCounter = 0;

            for (const startClient of clients) {
                // If we have already created all the required groups, stop.
                if (allGroups.length >= numGroups) break;

                // Skip this client if it has already been assigned to a group.
                if (addedClientIds.has(startClient.id)) {
                    continue;
                }

                groupCounter++;
                const currentGroup = [];
                
                // Add the starting client to the new group and mark it as added.
                startClient[propertyName] = `${labelPrefix} ${groupCounter}`;
                currentGroup.push(startClient);
                addedClientIds.add(startClient.id);

                // Create a list of potential neighbors (those not yet assigned).
                // This is still the most expensive part of the algorithm.
                const neighbors = clients.filter(c => !addedClientIds.has(c.id));
                
                // Sort neighbors by distance to the current group's starting client.
                neighbors.sort((a, b) => 
                    this.getDistance(startClient.Latitude, startClient.Longitude, a.Latitude, a.Longitude) -
                    this.getDistance(startClient.Latitude, startClient.Longitude, b.Latitude, b.Longitude)
                );

                // Determine how many clients this specific group should get.
                // This distributes the remainder clients one by one to the first few groups.
                const numToTake = clientsPerGroup - 1 + (remainingClients > 0 ? 1 : 0);
                remainingClients--;

                // Add the closest neighbors to the group.
                const clientsToAdd = neighbors.slice(0, numToTake);
                for (const clientToAdd of clientsToAdd) {
                    clientToAdd[propertyName] = `${labelPrefix} ${groupCounter}`;
                    currentGroup.push(clientToAdd);
                    addedClientIds.add(clientToAdd.id);
                }
                
                allGroups.push(currentGroup);
            }
            
            return allGroups;
        },

        async decouperRoutes() {
            if (this.nomber_routes > 0) {
                this.$showLoadingPage();
                
                // The 'await' keyword pauses execution here until the promise resolves,
                // but it does NOT block the UI thread. The loading page will be visible.
                await this.partitionClients({
                    clientList: this.clients,
                    numGroups: this.nomber_routes,
                    propertyName: 'JPlan',
                    labelPrefix: this.routeLabelPrefix
                });
                
                // Once partitioning is done, continue.
                this.setResume();
                this.$hideLoadingPage();
            }

            console.log(this.clients)
        },

        async decouperClients(key) {
            const nombre_journee = parseInt(document.getElementById("route_" + key).value, 10);

            if (nombre_journee > 0) {
                this.$showLoadingPage();
                
                const clientsForThisRoute = this.resume_liste_journey_plan[key].clients;

                await this.partitionClients({
                    clientList: clientsForThisRoute,
                    numGroups: nombre_journee,
                    propertyName: 'Journee',
                    labelPrefix: this.journeeLabelPrefix
                });

                this.setResume();
                this.$hideLoadingPage();
            }
        },

        getDistance(latitude_1, longitude_1, latitude_2, longitude_2) {

            return this.$map.$setDistanceStraight(latitude_1, longitude_1, latitude_2, longitude_2)
        },

        //  //  //  //  //

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

                if((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice'))) {

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
                }

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
    }
}

</script>
