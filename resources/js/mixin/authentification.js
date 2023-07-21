
// store
import store                    from    "../store/store"

export default {

    methods: {

        $fillStore(store) {

            try {

                // Set Values from Localstorage to Store
                if(JSON.parse(localStorage.getItem('vuex')) !=  null) {    

                    store.commit("authentification/setUser"                 , JSON.parse(localStorage.getItem('vuex'))["user"])
                }

                if(JSON.parse(localStorage.getItem('vuex')) !=  null) {    

                    store.commit("authentification/setAccessToken"          , JSON.parse(localStorage.getItem('vuex'))["access_token"])
                }

                if(JSON.parse(localStorage.getItem('vuex')) !=  null) {    

                    store.commit("authentification/setIsAuthentificated"    , JSON.parse(localStorage.getItem('vuex'))["isAuthentificated"])
                }
            }

            catch(e) {

            }
        },

        //

        $rolePrincipale() {

            if(this.$findRoleIndex("Administrateur")          !== -1)     return "Administrateur"
            if(this.$findRoleIndex("RTM Manager")             !== -1)     return "RTM Manager"
            if(this.$findRoleIndex("BU Manager")              !== -1)     return "BU Manager"
            if(this.$findRoleIndex("Salesman Cashvan")        !== -1)     return "Salesman Cashvan"
            if(this.$findRoleIndex("Salesman Prevente")       !== -1)     return "Salesman Prevente"
            if(this.$findRoleIndex("Salesman Livreur")        !== -1)     return "Salesman Livreur"
        },

        $findRoleIndex(role) {

            let user    =   store.getters[`authentification/getUser`]

            if(user.roles) {
                for (let i = 0; i < user.roles.length; i++) {
                    
                    if (user.roles[i].name === role) {
                        return i; 
                    }
                }
            }

            return -1; 
        },

        $isRole(role) {

            let user    =   store.getters[`authentification/getUser`]

            if(user.roles) {
                for (let i = 0; i < user.roles.length; i++) {
                    
                    if (user.roles[i].name === role) {
                        return true; 
                    }
                }
            }

            return false; 
        },

        $canDo(role) {

            let role_principale    =   this.$rolePrincipale()

            if(role_principale ==  "Super Admin") {

                return true
            }

            if(role_principale ==  "Admin") {

                if((role ==  "RTM Manager")||(role ==  "BU Manager")||(role ==  "Salesman")) {

                    return true
                }

                else {

                    return false
                }
            }

            if(role_principale ==  "BU Manager") {

                if((role ==  "BU Manager")||(role ==  "Salesman")) {

                    return true
                }

                else {

                    return false
                }
            }

            if(role_principale ==  "Salesman Cashvan") {

                if((role ==  "Salesman")) {

                    return true
                }

                else {

                    return false
                }
            }

            if(role_principale ==  "Salesman Prevente") {

                if((role ==  "Salesman")) {

                    return true
                }

                else {

                    return false
                }
            }

            if(role_principale ==  "Salesman Livreur") {

                if((role ==  "Salesman")) {

                    return true
                }

                else {

                    return false
                }
            }
        }
    },   
}