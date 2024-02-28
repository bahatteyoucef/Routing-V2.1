import getters      from "./getters"
import actions      from "./actions"
import mutations    from "./mutations"

export default {

    namespaced  :   true,

    state() {

        return {

            liste_journee  :   null,
            add_journee    :   null
        }
    },

    mutations   : mutations,
    getters     : getters,
    actions     : actions
}
