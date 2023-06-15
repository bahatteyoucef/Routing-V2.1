import getters      from "./getters"
import actions      from "./actions"
import mutations    from "./mutations"

export default {

    namespaced  :   true,

    state() {

        return {

            liste_type_client  :   null,
            add_type_client    :   null
        }
    },

    mutations   : mutations,
    getters     : getters,
    actions     : actions
}
