import { createStore }      from "vuex";

import authentification     from "./modules/authentification/authentification"
import client               from "./modules/client/client"
import journey_plan         from "./modules/journey_plan/journey_plan"
import type_client          from "./modules/type_client/type_client"
import journee              from "./modules/journee/journee"

const store = createStore({});

store.registerModule("authentification" , authentification);
store.registerModule("client"           , client);
store.registerModule("journey_plan"     , journey_plan);
store.registerModule("type_client"      , type_client);
store.registerModule("journee"          , journee);

export default store ;
