export default {

    setIsOnlineAction(context, value) {

        context.commit("internet/setIsOnline"   , value, {root: true})
    },
}