export default {

    setUser(state, value) {

        state.user                  =   value

        // Set to LocalStorage
        let vuex                    =   JSON.parse(localStorage.getItem("vuex"))   

        if(vuex != null) {
            vuex.user               =   value

            localStorage.setItem("vuex", JSON.stringify(vuex))
        }

        else {
            vuex                    =   {user : {} , access_token : '' , isAuthentificated : false}

            vuex.user               =   value
            localStorage.setItem("vuex", JSON.stringify(vuex))
        }
    },

    setAccessToken(state, value) {

        state.access_token          =   value

        // Set to LocalStorage
        let vuex                    =   JSON.parse(localStorage.getItem("vuex"))   

        if(vuex != null) {
            vuex.access_token       =   value

            localStorage.setItem("vuex", JSON.stringify(vuex))
        }

        else {
            vuex                    =   {user : {} , access_token : '' , isAuthentificated : false}

            vuex.access_token       =   value
            localStorage.setItem("vuex", JSON.stringify(vuex))
        }
    },

    setIsAuthentificated(state, value) {

        state.isAuthentificated     =   value

        // Set to LocalStorage
        let vuex                    =   JSON.parse(localStorage.getItem("vuex"))   

        if(vuex != null) {

            vuex.isAuthentificated  =   value

            localStorage.setItem("vuex", JSON.stringify(vuex))
        }

        else {

            vuex                    =   {user : {} , access_token : '' , isAuthentificated : false}

            vuex.isAuthentificated  =   value
            localStorage.setItem("vuex", JSON.stringify(vuex))
        }
    }
}