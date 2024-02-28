export default {

    setAddTypeClientAction(context, value) {

        context.commit("type_client/setAddTypeClient"     , value, {root: true})
    },

    setListeTypeClientAction(context, value) {

        context.commit("type_client/setListeTypeClient"     , value, {root: true})
    },
}