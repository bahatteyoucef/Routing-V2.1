<template>

    <!-- Modal -->
    <div class="modal fade" id="modalResume" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" v-if="clients.length">Resume : </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3 table-responsive">
                        
                    <div v-if="resume_liste_journey_plan    !=  null" class="mt-5">

                        <div v-for="journey_plan in resume_liste_journey_plan" :key="journey_plan.JPlan" class="mt-5">

                            <div>
                                <h5 class="modal-title"><b>{{journey_plan.JPlan}} : {{journey_plan.clients.length}} Clients</b></h5>
                            </div>

                            <table class="table table-striped mt-3">
                                <tr>
                                    <th>District</th>
                                    <th>Clients</th>
                                    <th>CustomerType</th>
                                    <th>City</th>
                                </tr>

                                <tr v-for="district in journey_plan.districts" :key="district.DistrictNo">
                                    <td>{{district.DistrictNameE}}</td>
                                    <td>{{district.clients.length}}</td>

                                    <td>
                                        <tr v-for="type_client in district.liste_type_client" :key="type_client.CustomerType" class="p-0">
                                            <td>{{type_client.CustomerType}}</td>
                                            <td>{{type_client.clients.length}} clients</td>
                                        </tr>
                                    </td>

                                    <td>
                                        <tr v-for="cite in district.cites" :key="cite.CityNo" class="p-0">
                                            <td>{{cite.CityNameE}}</td>
                                            <td>{{cite.clients.length}} clients</td>
                                            <td>
                                                <tr v-for="type_client in cite.liste_type_client" :key="type_client.CustomerType" class="p-0">
                                                    <td>{{type_client.CustomerType}}</td>
                                                    <td>{{type_client.clients.length}} clients</td>
                                                </tr>
                                            </td>
                                        </tr>
                                    </td>

                                </tr>
                            </table>

                            <table class="table table-striped mt-3">
                                <tr>
                                    <th>Journee</th>
                                    <th>Clients</th>
                                    <th>City</th>
                                </tr>

                                <tr v-for="Journee in journey_plan.liste_journee" :key="Journee.Journee">
                                    <td>{{Journee.Journee}}</td>
                                    <td>{{Journee.clients.length}}</td>

                                    <td>
                                        <tr v-for="cite in Journee.cites" :key="cite.CityNo" class="p-0">
                                            <td>{{cite.CityNameE}}</td>
                                            <td>{{cite.clients.length}} clients</td>
                                        </tr>
                                    </td>
                                </tr>
                            </table>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</template>

<script>

export default {
    data() {
        return { 

            resume_liste_journey_plan   :   null
        }
    },

    props : ["clients", "route_import"],

    mounted() {

        this.clearData("#modalResume")
    },  

    methods : {

        setResume() {

            // Show Loading Page
            this.$showLoadingPage()

            setTimeout(() => {
                
                this.resume_liste_journey_plan  =   this.$getResumeFileRouting(this.clients)

                // Hide Loading Page
                this.$hideLoadingPage()
            }, 55);
        },

        createDatatableResumeAdd() {

            // Create the table element
            var table       =   document.createElement("table");

            // Create the table header row
            var headerRow   =   document.createElement("tr");

            // Create the table header cells
            var districtHeader                      =   document.createElement("th");
            districtHeader.textContent              =   "District";
            headerRow.appendChild(districtHeader);

            var districtClientHeader                =   document.createElement("th");
            districtClientHeader.textContent        =   "Clients";
            headerRow.appendChild(districtClientHeader);

            var customerTypeHeader                  =   document.createElement("th");
            customerTypeHeader.textContent          =   "CustomerType";
            headerRow.appendChild(customerTypeHeader);

            var clientCustomerTypeHeader            =   document.createElement("th");
            clientCustomerTypeHeader.textContent    =   "Clients";
            headerRow.appendChild(clientCustomerTypeHeader);

            // Append the header row to the table
            table.appendChild(headerRow);

            //

            // Create rows and cells for each data entry
            for (const [key, journey_plan] of Object.entries(this.resume_liste_journey_plan)) {
                
                if(key  ==  "V16-05-0021")  {

                    for (const [key_2, district] of Object.entries(this.resume_liste_journey_plan[key].districts)) {

                        var districtCell                    =   document.createElement("td");
                        districtCell.textContent            =   district.DistrictNameE;
                        districtCell.setAttribute("rowspan", this.getRowSpan(district));

                        //

                        var districtClientCell              =   document.createElement("td");
                        districtClientCell.textContent      =   district.clients.length;
                        districtClientCell.setAttribute("rowspan", this.getRowSpan(district));

                        // 

                        var customerTypeCell1               =   document.createElement("td");
                        customerTypeCell1.textContent       =   district.liste_type_client[Object.keys(district.liste_type_client)[0]].CustomerType;
                        districtCell.setAttribute("rowspan", this.getRowSpan(district));

                        //

                        var clientCustomerTypeCell1         =   document.createElement("td");
                        clientCustomerTypeCell1.textContent =   district.liste_type_client[Object.keys(district.liste_type_client)[0]].clients.length;
                        districtCell.setAttribute("rowspan", this.getRowSpan(district));

                        // Create the first subject row
                        var customerTypeRow1                =   document.createElement("tr");
                        customerTypeRow1.appendChild(districtCell);
                        customerTypeRow1.appendChild(districtClientCell);
                        customerTypeRow1.appendChild(customerTypeCell1);
                        customerTypeRow1.appendChild(clientCustomerTypeCell1);

                        // Append the first customerType row to the table
                        table.appendChild(customerTypeRow1);

                        //

                        // Create the remaining customerType rows
                        for (const [key, value] of Object.entries(district.liste_type_client)) {

                            if(key  !=  Object.keys(district.liste_type_client)[0]) {

                                var customerTypeCell                =   document.createElement("td");
                                customerTypeCell.textContent        =   district.liste_type_client[key].CustomerType;

                                var clientCustomerTypeCell          =   document.createElement("td");
                                clientCustomerTypeCell.textContent  =   district.liste_type_client[key].clients.length;

                                // 

                                var customerTypeRow = document.createElement("tr");

                                customerTypeRow.appendChild(customerTypeCell);
                                customerTypeRow.appendChild(clientCustomerTypeCell);

                                // Append the customerType row to the table
                                table.appendChild(customerTypeRow);
                            }
                        }
                    }
                }
            }

            // Append the table to the document body
            console.log(table)
            console.log(document.getElementById("datatable_route_import_add_div"))

            document.getElementById("datatable_route_import_add_div").appendChild(table);

        },

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

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {
    
                this.resume_liste_journey_plan      =   null
            });
        }

    }
}
</script>
