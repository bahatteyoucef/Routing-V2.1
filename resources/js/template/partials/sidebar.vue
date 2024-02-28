<template>
    <section class="app-sidebar">
        <nav class="sidebar sidebar-offcanvas scrollbar scrollbar-deep-blue" id="sidebar">
            <ul v-if="user.nom" class="nav">

                <!-- Profile        -->
                <li v-if="user  !=  {}" class="nav-item nav-profile">
                    <a href="javascript:void(0);" class="nav-link">
                        <div class="nav-profile-image" id="nav_profile_image">
                            <img
                                :src="'/images/profile.jpg'"
                                alt="profile"
                            />
                        </div>
                        <div class="nav-profile-text d-flex flex-column">
                            <span class="font-weight-bold mb-2"
                                >{{ user.nom }} </span
                            >
                        </div>
                    </a>
                </li>

                <!-- Dashboard      -->
                <li class="nav-item">
                    <span class="nav-link w-100 active_item"    @click="goToRoute('', '')">
                        <span class="menu-title active_item"    >Dashboard</span>
                        <i class="mdi mdi-home menu-icon active_item"   ></i>
                    </span>
                </li>

            </ul>
        </nav>
    </section>
</template>

<script>

import sidebarRouteImport       from    "../routePartials/sidebarRouteImport.vue"

import {mapGetters, mapActions} from    "vuex"

export default {

    data() {

        return {

            user        :   {}
        };
    },

    computed : {

        ...mapGetters({
            getUser                     :   'authentification/getUser'              ,
            getAccessToken              :   'authentification/getAccessToken'       ,
            getIsAuthentificated        :   'authentification/getIsAuthentificated'
        }),

        // Dashboard
        VerifyDashboardClicked          : function () {
            return {
                active_item : this.$route.path    ==  "/"
            }
        }
    },

    components : {

        sidebarRouteImport  :   sidebarRouteImport
    },

    mounted() {

        this.user                   =   this.getUser

        // Highlight the right arrow
        this.$highlightArrow()
    },

    methods: {

        goToRoute(route, route_arrow) {
    
            if(this.$route.path !=  route) {

                // remove active class from all sub elements
                        
                const list_route_link   =   document.querySelectorAll(".route_link")

                for (let i = 0; i < list_route_link.length; i++) {

                    list_route_link[i].classList.remove("active")
                }

                // 

                // Remove Active class from arrows
                const arrows            =   document.querySelectorAll('.menu_arrow')

                for (let i = 0; i < arrows.length; i++) {

                    arrows[i].classList.remove("active_item")
                }

                //

                // Set Active class to arrow
                const active_arrow      =   document.getElementsByClassName(route_arrow)

                for (let i = 0; i < active_arrow.length; i++) {

                    active_arrow[i].classList.add("active_item")
                }

                //

                // Go To Route
                this.$router.push('/'+route)
            }
        }
    },

    watch : {

        getUser(newUser, oldUser) {

            this.user   =   newUser

            // Highlight the right arrow
            this.$highlightArrow()
        }
    }
};
</script>
