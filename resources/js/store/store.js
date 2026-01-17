import { createStore }      from "vuex";

import authentification     from "./modules/authentification/authentification"
import client               from "./modules/client/client"
import internet             from "./modules/internet/internet"
import loading_page         from "./modules/loading_page/loading_page"

const store = createStore({});

store.registerModule("authentification" , authentification);
store.registerModule("client"           , client);
store.registerModule("internet"         , internet);
store.registerModule("loading_page"     , loading_page);

export default store ;
