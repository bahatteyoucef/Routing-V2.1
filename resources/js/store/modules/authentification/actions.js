export default {

    setUserAction(context, value) {

        context.commit("authentification/setUser"               , value, {root: true})
    },

    setAccessTokenAction(context, value) {

        context.commit("authentification/setAccessToken"        , value, {root: true})
    },

    setIsAuthentificatedAction(context, value) {

        context.commit("authentification/setIsAuthentificated"  , value, {root: true})
    }
}