export default {

    setUpdateClientAction(context, value) {

        context.commit("client/setUpdateClient"             , value, {root: true})
    },

    setAddClientAction(context, value) {

        context.commit("client/setAddClient"                , value, {root: true})
    },

    setClientsChangeRouteAction(context, value) {

        context.commit("client/setClientsChangeRoute"       , value, {root: true})
    }
}