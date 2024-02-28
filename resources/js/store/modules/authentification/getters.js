export default {

    getUser(state)              {

        return state.user
    },

    getAccessToken(state)       {

        return state.access_token
    },

    getIsAuthentificated(state) {

        return state.isAuthentificated
    }
}