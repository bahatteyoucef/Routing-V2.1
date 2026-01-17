import getters      from "./getters"
import actions      from "./actions"
import mutations    from "./mutations"

export default {

    namespaced  :   true,

    state() {

        return {

            filter_status_route_import_clients_by_status            :   ""  ,
            selected_clients                                        :   []
        }
    },

    mutations   : mutations,
    getters     : getters,
    actions     : actions,   
}