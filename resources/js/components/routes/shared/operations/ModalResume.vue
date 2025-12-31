<template>

    <!-- Modal -->
    <div class="modal fade" id="ModalResume" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" v-if="clients">Resume : </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3 table-responsive">
                    <ResumeComponent 
                        ref="ResumeComponent"
                        :key="clients" 

                        :mode="mode"
                        :clients="clients"
                        :id_route_import_tempo=id_route_import_tempo    
                        :id_route_import="id_route_import"
                    >
                    </ResumeComponent>
                </div>

                <!-- Footer -->
                <div v-if="(($isRole('Super Admin'))||($isRole('BU Manager'))||($isRole('BackOffice')))" class="modal-footer">
                    <button type="button" class="btn btn-primary"   @click="valider()">Confirm</button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

import ResumeComponent from "../parts/ResumeComponent.vue"

export default {

    data() {
        return { 
            clients     :   []
        }
    },

    props   : ["mode", "id_route_import_tempo", "id_route_import"],

    components : {
        ResumeComponent :   ResumeComponent
    },

    mounted() {

        this.clearData("#ModalResume")
    },  

    methods : {

        async getClients(clients) {

            this.clients    =   clients.map(({ id, DistrictNo, DistrictNameE, CityNo, CityNameE, JPlan, Journee, CustomerType, Latitude, Longitude }) => ({ id, DistrictNo, DistrictNameE, CityNo, CityNameE, JPlan, Journee, CustomerType, Latitude, Longitude }))

            //
            await this.$nextTick()

            //
            this.$refs.ResumeComponent.setResume()
        },

        //

        async valider() {

            if(this.mode    ==  "temporary") {

                this.$showLoadingPage()

                let formData    =   new FormData();

                let clients     =   this.clients.map(obj => { return { id   :   obj.id  ,   JPlan   :   obj.JPlan   ,   Journee :   obj.Journee };});

                //

                formData.append("data"  ,   JSON.stringify(clients))

                const res                   =   await this.$callApi("post"  ,   "/clients-tempo/resume/update", formData)
                console.log(res)

                if(res.status===200){

                    // Send Feedback
                    this.$feedbackSuccess(res.data.header     ,   res.data.message)

                    // 5) Now hide the spinner
                    this.$hideLoadingPage();

                    //
                    const clients_object = this.clients.reduce((acc, { id, JPlan, Journee }) => {
                        acc[id] = { JPlan, Journee };
                        return acc;
                    }, {});

                    //
                    this.emitter.emit('reSetClientsDevide' , clients_object)

                    // Close Modal
                    await this.$hideModal("ModalResume")
                }
                
                else{

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)

                    // Hide Loading Page
                    this.$hideLoadingPage()
                }            
            }

            else {

                this.$showLoadingPage()

                let formData    =   new FormData();

                let clients     =   this.clients.map(obj => { return { id   :   obj.id  ,   JPlan   :   obj.JPlan   ,   Journee :   obj.Journee };});

                //

                formData.append("data"      ,   JSON.stringify(clients))

                const res                   =   await this.$callApi("post"  ,   "/clients/resume/update",  formData)

                if(res.status===200){

                    // Send Feedback
                    this.$feedbackSuccess(res.data.header     ,   res.data.message)

                    // 5) Now hide the spinner
                    this.$hideLoadingPage();

                    //
                    const clients_object = this.clients.reduce((acc, { id, JPlan, Journee }) => {
                        acc[id] = { JPlan, Journee };
                        return acc;
                    }, {});

                    //
                    this.emitter.emit('reSetClientsDecoupeByJourneeMap' , clients_object)

                    // Close Modal
                    await this.$hideModal("ModalResume")
                }
                
                else{

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)

                    // Hide Loading Page
                    this.$hideLoadingPage()
                }
            }
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {
    
                this.$refs.ResumeComponent.nomber_routes                =   0       

                this.$refs.ResumeComponent.nombre_journee               =   ""      
                this.$refs.ResumeComponent.liste_jourey_plan            =   []      

                this.$refs.ResumeComponent.resume_liste_journey_plan    =   null    

                //  //  //  //  //

                const datatable_resume_global_body      =   document.getElementById("datatable_resume_global_body")
                if(datatable_resume_global_body)            datatable_resume_global_body.innerHTML      =   ""

                const datatable_resume_par_jour_body    =   document.getElementById("datatable_resume_par_jour_body")
                if(datatable_resume_par_jour_body)          datatable_resume_par_jour_body.innerHTML    =   ""
            });
        }
    }
}
</script>
