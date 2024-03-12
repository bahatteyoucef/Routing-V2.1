export default {

    setUpdateClientAction(context, value) {

        context.commit("client/setUpdateClient"                             , value, {root: true})
    },

    setAddClientAction(context, value) {

        context.commit("client/setAddClient"                                , value, {root: true})
    },

    setClientsChangeRouteAction(context, value) {

        context.commit("client/setClientsChangeRoute"                       , value, {root: true})
    },

    // 

    setAllClientsAction(context, value) {

        context.commit("client/setAllClients"                               , value, {root: true})
    },

    setClientAction(context, value) {

        context.commit("client/setClient"                                   , value, {root: true})
    },

    //

    setFilterStatusRouteImportClientsByStatusAction(context, value) {

        context.commit("client/setFilterStatusRouteImportClientsByStatus"   , value, {root: true})
    }
}