import getters      from "./getters"
import actions      from "./actions"
import mutations    from "./mutations"

export default {

    namespaced  :   true,

    state() {

        return {

            show_loading_page   :   false
        }
    },

    mutations   : mutations,
    getters     : getters,
    actions     : actions,   
}
