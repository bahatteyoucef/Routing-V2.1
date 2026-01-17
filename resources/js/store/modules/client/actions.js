export default {

    setFilterStatusRouteImportClientsByStatusAction(context, value) {

        context.commit("client/setFilterStatusRouteImportClientsByStatus"   , value, {root: true})
    },

    setSelectedClientsAction(context, value) {

        context.commit("client/setSelectedClients"                          , value, {root: true})
    }

}