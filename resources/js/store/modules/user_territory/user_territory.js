import getters      from "./getters"
import actions      from "./actions"
import mutations    from "./mutations"

export default {

    namespaced  :   true,

    state() {

        return {

            liste_user_territory  :   null,
            add_user_territory    :   null
        }
    },

    mutations   : mutations,
    getters     : getters,
    actions     : actions
}
