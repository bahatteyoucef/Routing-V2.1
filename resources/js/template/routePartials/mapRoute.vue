<template>
    <div>

        <div id="content_route">
            <div id="map"></div>

            <div class="scrollbar scrollbar-deep-blue" id="tableau_data">
                <div class="mt-3">

                    <!-- Show Liste of Clients -->
                    <parRouteImportDetails      :key="id_route_import"  :id_route_import="id_route_import"></parRouteImportDetails>

                </div>
            </div>
        </div>

        <!-- Modal Add  -->
        <modalClientAdd             ref="modalClientAdd">   </modalClientAdd>

        <!-- Modal Update  -->
        <modalClientUpdate          ref="modalClientUpdate"></modalClientUpdate>

        <!-- Modal Change Route -->
        <modalClientsChangeRoute    ref="modalClientsChangeRoute"></modalClientsChangeRoute>

    </div>
</template>

<script>

import modalClientAdd               from "../../components/clients/modalClientAdd.vue"
import modalClientUpdate            from "../../components/clients/modalClientUpdate.vue"
import modalClientsChangeRoute      from "../../components/clients/modalClientsChangeRoute.vue"

import parRouteImportDetails        from "../../components/routes/obs/parRouteImportDetails.vue"

import { mapGetters }               from "vuex";

export default {

    data() {

        return { 

            id_route_import :   null
        }
    },

    mounted() {

        // add Map
        this.addMap()

        this.id_route_import    =   this.$route.params.id_route_import
    },

    components : {

        parRouteImportDetails,

        modalClientAdd,
        modalClientUpdate,
        modalClientsChangeRoute
    },

    computed: {

        ...mapGetters({

            getAddClient            : 'client/getAddClient',
            getUpdateClient         : 'client/getUpdateClient',
            getClientsChangeRoute   : 'client/getClientsChangeRoute'
        })
    },

    methods : {

        addMap() {

            this.$map.$createMap()
        },

        addClient(client) {

            try {
            
                this.$refs.modalClientAdd.getData(client)
            }
            catch(e) {

                console.log(e)
            }
        },

        updateClient(client) {

            try {
                this.$refs.modalClientUpdate.getData(client)
            }
            catch(e) {

                console.log(e)
            }
        },

        updateClientsRoute(clients) {

            try {
                this.$refs.modalClientsChangeRoute.getData(clients)
            }
            catch(e) {

                console.log(e)
            }
        }   
    },

    watch: {

        getAddClient(newValue, oldValue) {

            if(newValue != null) {
                
                // ShowModal
                var addModal    =   new Modal(document.getElementById("addClientModal"));
                addModal.show();

                // Send DATA To Modal
                this.addClient(newValue)
            }
        },

        getUpdateClient(newValue, oldValue) {

            if(newValue != null) {
                
                // ShowModal
                var updateModal     =   new Modal(document.getElementById("updateClientModal"));
                updateModal.show();

                // Send DATA To Modal
                this.updateClient(newValue)
            }
        },

        getClientsChangeRoute(newValue, oldValue) {

            if(newValue != null) {
                
                // ShowModal
                var clientsChangeRouteModal     =   new Modal(document.getElementById("clientsChangeRouteModal"));
                clientsChangeRouteModal.show();

                // Send DATA To Modal
                this.updateClientsRoute(newValue)
            }
        }
    },
}
</script>