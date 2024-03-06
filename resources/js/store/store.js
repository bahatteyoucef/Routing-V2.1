import { createStore }      from "vuex";

import authentification     from "./modules/authentification/authentification"
import client               from "./modules/client/client"
import journey_plan         from "./modules/journey_plan/journey_plan"
import type_client          from "./modules/type_client/type_client"
import journee              from "./modules/journee/journee"
import user_territory       from "./modules/user_territory/user_territory"

const store = createStore({});

store.registerModule("authentification" , authentification);
store.registerModule("client"           , client);
store.registerModule("journey_plan"     , journey_plan);
store.registerModule("type_client"      , type_client);
store.registerModule("journee"          , journee);
store.registerModule("user_territory"   , user_territory);

export default store ;
