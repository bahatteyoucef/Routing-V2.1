<template>

    <!-- Modal -->
    <div class="modal fade" id="modalRouteImportDelete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Delete Route Import : </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3 table-responsive">
                        
                    <div class="row mt-3 justify-content-center">
                        <div class="col-6 align-self-center">
                            <img :src="'/images/custom/logo.png'" class="card-img-top">
                        </div>
                    </div>

                    <!--  -->

                    <div class="row mt-3 justify-content-center">
                        <div class="col-12">
                            <div class="row justify-content-center">
                                <div class="col-10 align-self-center p-0">
                                    <span>Do you want to Delete this Route Import ? You will lose :</span>
                                </div>
                            </div>

                            <br />
                            
                            <ul class="mt-3 list-group">
                                <li class="list-group-item">Number of clients : {{ route_import.clients.length }}</li>
                                <li class="list-group-item">Number of JPlan Territories : {{ route_import.liste_journey_plan.length }}</li>
                                <li class="list-group-item">Number of Workday (Journee) Territories : {{ route_import.liste_journee.length }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer"       style="display: flex; justify-content: space-between;">
                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button"   class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button"   class="btn btn-primary"   @click="deleteMap()">Confirm</button>
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

            route_import : {

                id_route_import     :   null,

                clients             :   [],
                liste_journey_plan  :   [],
                liste_journee       :   []
            }
        }
    },

    methods : {

        async setRouteImportDelete(id_route_import) {

            this.route_import.id_route_import       =   id_route_import

            // Show Loading Page
            this.$showLoadingPage()

            const res_3                             =   await this.$callApi("post"  ,   "/route_import/"+this.route_import.id_route_import+"/show"      ,   null)

            this.route_import.clients               =   res_3.data.clients
            this.route_import.liste_journey_plan    =   res_3.data.liste_journey_plan
            this.route_import.liste_journee         =   res_3.data.liste_journee

            // Hide Loading Page
            this.$hideLoadingPage()
        },

        //

        async deleteMap() {

            // Show Loading Page
            this.$showLoadingPage()

            const res   = await this.$callApi('post'    ,   '/route_import/'+this.route_import.id_route_import+'/delete'    ,   null)      

            if(res.status===200){

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])

                // Get Route Import
                this.emitter.emit("reSetRouteImport")

                // Close Modal
                this.$hideModal("modalRouteImportDelete")

                // Hide Loading Page
                this.$hideLoadingPage()
            }
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)

                // Hide Loading Page
                this.$hideLoadingPage()
            }
        }
    }
}
</script>
