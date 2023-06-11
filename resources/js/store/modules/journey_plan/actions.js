export default {

    setAddJourneyPlanAction(context, value) {

        context.commit("journey_plan/setAddJourneyPlan"     , value, {root: true})
    },

    setListeJourneyPlanAction(context, value) {

        context.commit("journey_plan/setListeJourneyPlan"     , value, {root: true})
    },
}