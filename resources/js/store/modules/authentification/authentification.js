import getters      from "./getters"
import actions      from "./actions"
import mutations    from "./mutations"

export default {

    namespaced  :   true,

    state() {

        return {

            user                :   {},
            access_token        :   "",
            isAuthentificated   :   false
        }
    },

    mutations   : mutations,
    getters     : getters,
    actions     : actions,   

}
