export default {

    setAddJourneeAction(context, value) {

        context.commit("journee/setAddJournee"     , value, {root: true})
    },

    setListeJourneeAction(context, value) {

        context.commit("journee/setListeJournee"     , value, {root: true})
    },
}