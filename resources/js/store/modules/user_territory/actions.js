export default {

    setAddUserTerritoryAction(context, value) {

        context.commit("user_territory/setAddUserTerritory"         , value, {root: true})
    },

    setListeUserTerritoryAction(context, value) {

        context.commit("user_territory/setListeUserTerritory"       , value, {root: true})
    },
}