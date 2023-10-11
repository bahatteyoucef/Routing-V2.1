import getters      from "./getters"
import actions      from "./actions"
import mutations    from "./mutations"

export default {

    namespaced  :   true,

    state() {

        return {

            update_client           :   null,
            add_client              :   null,
            clients_change_route    :   null,

            all_clients             :   null,
            client                  :   null
        }
    },

    mutations   : mutations,
    getters     : getters,
    actions     : actions,   

}
