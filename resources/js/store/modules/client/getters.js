export default {

    getUpdateClient(state)    {

        return state.update_client
    },

    getAddClient(state)    {

        return state.add_client
    },

    getClientsChangeRoute(state) {

        return state.clients_change_route
    },

    //

    getAllClients(state) {

        return state.all_clients
    },

    getClient(state) {

        return state.client
    },

    //

    getFilterStatusRouteImportClientsByStatus(state) {

        return state.filter_status_route_import_clients_by_status
    },

    getSelectedClients(state) {

        return state.selected_clients
    },
    
}