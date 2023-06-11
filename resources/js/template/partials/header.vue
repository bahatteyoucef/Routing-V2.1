<template>
    <nav v-if="user.nom" class="navbar navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row navbar-light navbar-expand-lg" id="template-header">
        
        <!-- Logo -->
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
            <router-link to="/" aria-current="page" class="navbar-brand brand-logo active router-link-active p-0">
              <img  :src="'/images/header.png'" alt="logo" />
            </router-link>

            <router-link to="/" aria-current="page" class="navbar-brand brand-logo-mini active router-link-active p-0" id="mini_logo_custom">
              <img  :src="'/images/mini_header.png'" alt="logo" id="mini_logo_custom_image"/>
            </router-link>
        </div>

        <div class="navbar-menu-wrapper d-flex align-items-center ml-auto ml-lg-0" id="header_custom">

            <!-- Toggle Side Bar  -->
            <button type="button" class="navbar-toggler navbar-toggler align-self-center d-lg-block" @click="toggleSidebar()">
                <span class="mdi mdi-menu"></span>
            </button>

            <!-- Options -->
            <ul class="navbar-nav navbar-nav-right ml-auto">

              <!-- Profile        -->
              <li class="nav-item b-nav-dropdown dropdown nav-profile"  id="profile_header_list_parent">

                <span id="profileDropdown" role="button" data-bs-toggle="collapse" href="#profile_header_list" aria-haspopup="true" aria-expanded="false" aria-controls="profile_header_list" class="nav-link dropdown-toggle">

                    <div class="nav-profile-img">
                      <img :src="'/images/profile.jpg'" alt="image"/>
                      <span class="availability-status online"></span>
                    </div>

                    <div class="nav-profile-text">
                        <p class="mb-1 text-black">
                            {{user.nom}} {{user.prenom}}
                        </p>
                    </div>

                </span>

                <ul tabindex="-1" class="dropdown-menu dropdown-menu-right" id="profile_header_list">
                    <li role="presentation" class="preview-item" @click="logOut()">
                        <span  role="button" class="dropdown-item">
                          <i class="mdi mdi-logout mr-2 text_light_purple"></i>
                          Log Out
                        </span>
                    </li>
                </ul>

              </li>

            </ul>

            <!-- Toggle Side Bar for Mobile -->
            <button type="button" class="navbar-toggler navbar-toggler-right align-self-center" @click="toggleMobileSidebar()">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </nav>
</template>

<script>

import {mapGetters, mapActions} from    "vuex"

export default {

    data() {
      return {
          user        :   {}
      }
    },

    computed : {

        ...mapGetters({
            getUser                     :   'authentification/getUser'              ,
            getAccessToken              :   'authentification/getAccessToken'       ,
            getIsAuthentificated        :   'authentification/getIsAuthentificated'
        }),
    },

    mounted() {

        this.user = this.getUser
    },  

    methods: {

        ...mapActions("authentification" ,  [
          "setUserAction"                 ,
          "setAccessTokenAction"          ,
          "setIsAuthentificatedAction"    
        ]),

        toggleSidebar() {

          document.querySelector("body").classList.toggle("sidebar-icon-only");

          this.$sidebarToggle()
        },

        toggleMobileSidebar() {

            document.querySelector("#sidebar").classList.toggle("active");
        },

        // 

        async logOut() {

          const res = await this.$callApi("post", "/logout" , null)
          console.log(res)

          if(res.status ==  200) {

            // Success
            this.$feedbackSuccess("Logged Out !" , "")

            // Update State
            this.setUserAction({})
            this.setAccessTokenAction("")
            this.setIsAuthentificatedAction(false)

            // Router
            this.$goTo("/login")
          }

          else {

            this.$showErrors("Error !" , res.errors)
          }
        }
    },

    watch : {

        getUser(newUser, oldUser) {

            this.user   =   newUser
        }
    }
};
</script>

<style scoped></style>
