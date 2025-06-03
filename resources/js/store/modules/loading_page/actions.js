export default {

    setShowLoadingPageAction(context, value) {

        context.commit("show_loading_page/setShowLoadingPage"   , value, {root: true})
    },
}