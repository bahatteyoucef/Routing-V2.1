<template>
  <div>
    <div v-if="$isRole('Super Admin') || $isRole('BU Manager') || $isRole('BackOffice')" class="row">
      <div class="col-sm-3">
        <input type="number" class="form-control" placeholder="number of routes" v-model="nomber_routes"/>
      </div>
      <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="routes label prefix" v-model="routeLabelPrefix"/>
      </div>
      <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="journees label prefix" v-model="journeeLabelPrefix"/>
      </div>
      <div class="col-sm-3">
        <button class="btn btn-primary w-100" @click="decouperRoutes()"> Divide </button>
      </div>
    </div>

    <hr v-if="$isRole('Super Admin') || $isRole('BU Manager') || $isRole('BackOffice')" />

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
           <tr v-for="(row, rowIndex) in generatedRows" :key="'g-'+rowIndex">
             <td v-for="(cell, cellIndex) in row.cells" 
                 :key="cellIndex" 
                 :rowspan="cell.rowspan">
                 
                 <span v-if="!cell.isOption">{{ cell.text }}</span>

                 <div v-else class="row justify-content-center">
                    <div v-if="$isRole('Super Admin') || $isRole('BU Manager') || $isRole('BackOffice')">
                       <input type="number" 
                              class="form-control w-75 mb-1" 
                              placeholder="workdays..."
                              :id="'route_' + cell.routeKey">
                       <button type="button" 
                               class="btn-primary btn w-75"
                               @click="decouperClients(cell.routeKey)">
                           Divide
                       </button>
                    </div>
                 </div>
             </td>
           </tr>
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
            <tr v-for="(row, rowIndex) in generatedRowsParJour" :key="'j-'+rowIndex">
              <td v-for="(cell, cellIndex) in row.cells" 
                  :key="cellIndex" 
                  :rowspan="cell.rowspan">
                  {{ cell.text }}
              </td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  props: ["clients", "id_route_import_tempo", "id_route_import", "mode"],
  data() {
    return {
      nomber_routes: 0,
      routeLabelPrefix: "ROUTE",
      journeeLabelPrefix: "Jour",
      
      // Data Holders for the Algorithm
      routingTree: {}, 
      
      // The arrays that feed the HTML tables
      generatedRows: [],
      generatedRowsParJour: []
    };
  },
  methods: {
    
    // ==========================================
    // 1. MAIN ENTRY POINT
    // ==========================================
    setResume() {
      this.$showLoadingPage();

      // Step A: Build the Data Tree (O(N) Complexity)
      this.routingTree = this.buildDataTree(this.clients);

      // Step B: Generate the "Global" Table Rows
      this.generatedRows = this.generateGlobalRows(this.routingTree);

      // Step C: Generate the "Par Jour" Table Rows
      this.generatedRowsParJour = this.generateParJourRows(this.routingTree);

      this.$hideLoadingPage();
    },


    // ==========================================
    // 2. CORE OPTIMIZATION: ONE-PASS GROUPING
    // ==========================================
    buildDataTree(clients) {
      const tree = {};

      // 1. Aggregation Phase
      clients.forEach(client => {
        const routeKey = client.JPlan || "Unassigned";
        
        // Ensure Route Node
        if (!tree[routeKey]) {
          tree[routeKey] = {
            id: routeKey,
            clients: [],
            districts: {}, // Map for Global View
            journees: {},  // Map for Journee View
            rowspanGlobal: 0,
            rowspanJournee: 0
          };
        }
        const routeNode = tree[routeKey];
        routeNode.clients.push(client);

        // --- Branch 1: Districts (Global View) ---
        const distKey = client.DistrictNo;
        if (!routeNode.districts[distKey]) {
          routeNode.districts[distKey] = {
            id: distKey,
            name: client.DistrictNameE,
            clients: [],
            cities: {},
            rowspan: 0
          };
        }
        const distNode = routeNode.districts[distKey];
        distNode.clients.push(client);

        const cityKey = client.CityNo;
        if (!distNode.cities[cityKey]) {
          distNode.cities[cityKey] = {
            id: cityKey,
            name: client.CityNameE,
            clients: [],
            types: {},
            rowspan: 0
          };
        }
        const cityNode = distNode.cities[cityKey];
        cityNode.clients.push(client);

        const typeKey = client.CustomerType;
        if (!cityNode.types[typeKey]) {
          cityNode.types[typeKey] = {
            id: typeKey,
            clients: [],
            rowspan: 1 // Leaf node always has rowspan 1 initially
          };
        }
        cityNode.types[typeKey].clients.push(client);

        // --- Branch 2: Journees (Par Jour View) ---
        const jourKey = client.Journee || "No Day";
        if (!routeNode.journees[jourKey]) {
          routeNode.journees[jourKey] = {
            id: jourKey,
            clients: [],
            cities: {},
            rowspan: 0
          };
        }
        const jourNode = routeNode.journees[jourKey];
        jourNode.clients.push(client);

        // We group by City again inside Journee
        if (!jourNode.cities[cityKey]) {
           jourNode.cities[cityKey] = {
             id: cityKey,
             name: client.CityNameE,
             clients: [],
             rowspan: 1 // Leaf node for this view
           };
        }
        jourNode.cities[cityKey].clients.push(client);
      });

      // 2. Sorting & Rowspan Calculation Phase
      // We convert Maps to Arrays for sorting, then calculate rowspans bottom-up
      const sortedRoutes = Object.values(tree).sort((a,b) => this.naturalSort(a.id, b.id));

      sortedRoutes.forEach(route => {
        
        // --- Process Global Tree ---
        const districtsArr = Object.values(route.districts).sort((a,b) => a.id - b.id); // Assuming DistrictNo is numeric/sortable
        route.districtsArray = districtsArr;
        
        let routeGlobalRowspan = 0;

        districtsArr.forEach(dist => {
           const citiesArr = Object.values(dist.cities).sort((a,b) => a.id - b.id);
           dist.citiesArray = citiesArr;
           let distRowspan = 0;

           citiesArr.forEach(city => {
              const typesArr = Object.values(city.types).sort((a,b) => this.naturalSort(a.id, b.id));
              city.typesArray = typesArr;
              // City Rowspan = Sum of Type Rowspans (each is 1)
              city.rowspan = typesArr.length; 
              distRowspan += city.rowspan;
           });

           dist.rowspan = distRowspan;
           routeGlobalRowspan += distRowspan;
        });
        route.rowspanGlobal = routeGlobalRowspan;


        // --- Process Journee Tree ---
        const journeesArr = Object.values(route.journees).sort((a,b) => this.naturalSort(a.id, b.id));
        route.journeesArray = journeesArr;
        
        let routeJourneeRowspan = 0;

        journeesArr.forEach(jour => {
           const citiesArr = Object.values(jour.cities).sort((a,b) => a.id - b.id);
           jour.citiesArray = citiesArr;
           
           // Journee Rowspan = number of cities in that day
           jour.rowspan = citiesArr.length; 
           routeJourneeRowspan += jour.rowspan;
        });
        route.rowspanJournee = routeJourneeRowspan;
      });

      return sortedRoutes; 
    },

    // ==========================================
    // 3. TABLE GENERATION (From Tree -> Flat Rows)
    // ==========================================
    
    generateGlobalRows(sortedRoutes) {
      const rows = [];
      
      sortedRoutes.forEach(route => {
         // We prepare the Route cells that only appear in the first row of this group
         const routeCells = [
           { text: route.id, rowspan: route.rowspanGlobal },
           { text: route.clients.length, rowspan: route.rowspanGlobal }
         ];

         // Option Cell logic
         const optionCell = { 
            isOption: true, 
            routeKey: route.id, 
            rowspan: route.rowspanGlobal 
         };

         let isFirstDist = true;

         route.districtsArray.forEach(dist => {
            const distCells = [
              { text: dist.name, rowspan: dist.rowspan },
              { text: dist.clients.length, rowspan: dist.rowspan }
            ];

            let isFirstCity = true;

            dist.citiesArray.forEach(city => {
               const cityCells = [
                 { text: city.name, rowspan: city.rowspan },
                 { text: city.clients.length, rowspan: city.rowspan }
               ];

               let isFirstType = true;

               city.typesArray.forEach(type => {
                  const typeCells = [
                    { text: type.id, rowspan: type.rowspan },
                    { text: type.clients.length, rowspan: type.rowspan }
                  ];

                  // Construct the final row
                  let currentRow = { cells: [] };

                  if (isFirstDist && isFirstCity && isFirstType) {
                     // Very Top Row of the Route Group: Add Route Data
                     currentRow.cells.push(...routeCells);
                  }
                  
                  if (isFirstCity && isFirstType) {
                     // Top Row of District
                     currentRow.cells.push(...distCells);
                  }

                  if (isFirstType) {
                     // Top Row of City
                     currentRow.cells.push(...cityCells);
                  }

                  // Always add Type
                  currentRow.cells.push(...typeCells);

                  // If it's the very first row of the route, add the Options cell at the end
                  if (isFirstDist && isFirstCity && isFirstType) {
                    currentRow.cells.push(optionCell);
                  }

                  rows.push(currentRow);
                  isFirstType = false;
               });
               isFirstCity = false;
            });
            isFirstDist = false;
         });
      });
      return rows;
    },

    generateParJourRows(sortedRoutes) {
      const rows = [];
      
      sortedRoutes.forEach(route => {
         const routeCells = [
           { text: route.id, rowspan: route.rowspanJournee }
         ];

         let isFirstJour = true;

         route.journeesArray.forEach(jour => {
            const jourCells = [
               { text: jour.id, rowspan: jour.rowspan },
               { text: jour.clients.length, rowspan: jour.rowspan }
            ];

            let isFirstCity = true;

            jour.citiesArray.forEach(city => {
               const cityCells = [
                  { text: city.name, rowspan: 1 }, // Leaves are always 1 here
                  { text: city.clients.length, rowspan: 1 }
               ];

               let currentRow = { cells: [] };

               if (isFirstJour && isFirstCity) {
                  currentRow.cells.push(...routeCells);
               }

               if (isFirstCity) {
                  currentRow.cells.push(...jourCells);
               }

               currentRow.cells.push(...cityCells);
               rows.push(currentRow);

               isFirstCity = false;
            });
            isFirstJour = false;
         });
      });
      return rows;
    },

    // ==========================================
    // 4. UTILITIES
    // ==========================================
    
    // Efficient Natural Sort (handles numbers inside strings like "Route 1", "Route 10")
    naturalSort(a, b) {
      return a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' });
    },

    // Your existing Partition logic (kept mostly as is, but calls setResume at end)
    async partitionClients({ clientList, numGroups, propertyName, labelPrefix }) {
        if (!clientList || clientList.length === 0 || !numGroups || numGroups <= 0) return;

        const addedClientIds = new Set();
        const clients = [...clientList]; // Shallow copy

        // Sort by arbitrary seed (latitude in this case)
        clients.sort((b, a) => this.getDistance(0, 0, a.Latitude, a.Longitude) - this.getDistance(0, 0, b.Latitude, b.Longitude));

        const clientsPerGroup = Math.floor(clients.length / numGroups);
        let remainingClients = clients.length % numGroups;
        let groupCounter = 0;

        for (const startClient of clients) {
            if (groupCounter >= numGroups) break;
            if (addedClientIds.has(startClient.id)) continue;

            groupCounter++;
            const label = labelPrefix + " " + groupCounter;

            startClient[propertyName] = label; // Update the client object
            addedClientIds.add(startClient.id);

            const neighbors = clients.filter(c => !addedClientIds.has(c.id));
            
            // Basic nearest neighbor
            neighbors.sort((a, b) => 
                this.getDistance(startClient.Latitude, startClient.Longitude, a.Latitude, a.Longitude) - 
                this.getDistance(startClient.Latitude, startClient.Longitude, b.Latitude, b.Longitude)
            );

            const numToTake = clientsPerGroup - 1 + (remainingClients > 0 ? 1 : 0);
            if (remainingClients > 0) remainingClients--;

            const clientsToAdd = neighbors.slice(0, numToTake);
            for (const clientToAdd of clientsToAdd) {
                clientToAdd[propertyName] = label;
                addedClientIds.add(clientToAdd.id);
            }
        }
    },

    async decouperRoutes() {
      if (this.nomber_routes > 0) {
        this.$showLoadingPage();
        await this.partitionClients({ 
            clientList: this.clients, 
            numGroups: this.nomber_routes, 
            propertyName: 'JPlan', 
            labelPrefix: this.routeLabelPrefix 
        });
        this.setResume(); // Re-calculate tree
        this.$hideLoadingPage();
      }
    },

    async decouperClients(routeKey) {
       const input = document.getElementById("route_" + routeKey);
       if(!input) return;
       
       const nombre_journee = parseInt(input.value, 10);
       
       // Find clients for this route from our generated tree
       // We can iterate the tree easily since it is an array now
       const routeNode = this.routingTree.find(r => r.id == routeKey);
       
       if (nombre_journee > 0 && routeNode) {
          this.$showLoadingPage();
          await this.partitionClients({ 
             clientList: routeNode.clients, 
             numGroups: nombre_journee, 
             propertyName: 'Journee', 
             labelPrefix: this.journeeLabelPrefix 
          });
          this.setResume(); // Re-calculate tree
          this.$hideLoadingPage();
       }
    },

    getDistance(lat1, lon1, lat2, lon2) {
       if (this.$map && this.$map.$setDistanceStraight) {
          return this.$map.$setDistanceStraight(lat1, lon1, lat2, lon2);
       }
       // Fallback if map helper not ready (Pythagoras approx for speed in sorting)
       return Math.sqrt(Math.pow(lat2-lat1, 2) + Math.pow(lon2-lon1, 2));
    }
  }
};
</script>