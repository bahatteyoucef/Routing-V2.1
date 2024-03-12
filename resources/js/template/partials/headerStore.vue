<template>

    <nav v-if="map_route_obs_page" id="template-header">
        <img style="width : 100%; height : auto; position: relative; z-index: 9;" src="/images/header_mobile.png"/>
    </nav>

</template>

<script>

    export default {

        data() {

            return {

                map_route_obs_page  :   false
            }
        },

        watch   : {

            $route(new_route, old_route) {

                if(new_route.path.startsWith("/route/frontoffice/obs/")) {

                    this.map_route_obs_page     =   false
                }

                else {

                    this.map_route_obs_page     =   true
                }
            }
        }
    }

</script>