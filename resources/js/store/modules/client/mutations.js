export default {

    setUpdateClient(state, value) {

        state.update_client         =   value
    },

    setAddClient(state, value) {

        state.add_client            =   value
    },

    setClientsChangeRoute(state, value) {

        state.clients_change_route  =   value
    },

    //

    setAllClients(state, value) {

        state.all_clients           =   value
    },

    setClient(state, value) {

        state.client                =   value
    },

    //

    setFilterStatusRouteImportClientsByStatus(state, value) {

        state.filter_status_route_import_clients_by_status  =   value
    }
}