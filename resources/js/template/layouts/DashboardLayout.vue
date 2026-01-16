<template>
    <div class="container-scroller" id="page_route">

        <div v-if="$isRole('Super Admin') || $isRole('BU Manager') || $isRole('BackOffice') || $isRole('Viewer')">
            
            <header-part></header-part>

            <div class="container-fluid page-body-wrapper">
                <div class="main-panel w-100 animate__animated" id="main_content">
                    
                    <router-view :key="$route.path"></router-view>

                </div>
            </div>
        </div>

        <div v-if="$isRole('FrontOffice')" class="vh-100">
            
            <header-store-part v-if="!isObsRoute"></header-store-part>

            <div id="main_content_fo" class="h-100">
                
                <router-view :key="$route.path"></router-view>

            </div>
        </div>

    </div>
</template>

<script>
// Import your layout components here so they aren't cluttering MainApp
// Note: Adjust paths based on your actual folder structure
import HeaderPart       from '../partials/Header.vue'; // Example path
import HeaderStorePart  from '../partials/HeaderStore.vue'; // Example path

export default {
    name: "DashboardLayout",
    components: {
        HeaderPart,
        HeaderStorePart
    },
    computed: {
        // Detect if we are on an OBS route to hide the header
        isObsRoute() {
            const p = this.$route && this.$route.path ? this.$route.path : ''
            return p.startsWith('/route/frontoffice/obs')
        }
    }
}
</script>