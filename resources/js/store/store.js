import { createStore }      from "vuex";

import authentification     from "./modules/authentification/authentification"
import client               from "./modules/client/client"
import journey_plan         from "./modules/journey_plan/journey_plan"

const store = createStore({});

store.registerModule("authentification" , authentification);
store.registerModule("client"           , client);
store.registerModule("journey_plan"     , journey_plan);

export default store ;
