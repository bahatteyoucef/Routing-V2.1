import getters      from "./getters"
import actions      from "./actions"
import mutations    from "./mutations"

export default {

    namespaced  :   true,

    state() {

        return {

            liste_journey_plan  :   null,
            add_journey_plan    :   null
        }
    },

    mutations   : mutations,
    getters     : getters,
    actions     : actions
}
