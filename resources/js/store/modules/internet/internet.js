import getters      from "./getters"
import actions      from "./actions"
import mutations    from "./mutations"

export default {

    namespaced  :   true,

    state() {

        return {

            is_online           :   false
        }
    },

    mutations   : mutations,
    getters     : getters,
    actions     : actions,   
}
