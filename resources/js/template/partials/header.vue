<template>
    <nav v-if="user.nom" class="navbar navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row navbar-light navbar-expand-lg" id="template-header">
        
        <!-- Logo -->
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
            <router-link to="/" aria-current="page" class="navbar-brand brand-logo active router-link-active p-0">
              <img  :src="'/images/header.png'"     alt="logo" class="mt-2"/>
            </router-link>

            <router-link to="/" aria-current="page"   class="navbar-brand brand-logo-mini active router-link-active p-0" id="mini_logo_custom">
              <img  :src="'/images/mini_header.png'"  alt="logo" id="mini_logo_custom_image"/>
            </router-link>
        </div>

        <div class="navbar-menu-wrapper d-flex align-items-center ml-auto ml-lg-0" id="header_custom">

            <!-- Route -->
            <div class="d-flex align-items-left w-25" id="header_select_route">
              <Multiselect
                  v-model             =   "route_link"
                  :options            =   "liste_route_link"
                  mode                =   "single" 
                  placeholder         =   "Select Map"
                  class               =   "mt-1"

                  :close-on-select    =   "true"
                  :searchable         =   "true"
                  :create-option      =   "true"

                  :canDeselect        =   "false"
                  :canClear           =   "false"
                  :allowAbsent        =   "false"

                  :groups             =   "true"

              />
            </div>
            <!--                -->

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
            <!--                -->

        </div>
    </nav>
</template>

<script>

import Multiselect              from '@vueform/multiselect'

import {mapGetters, mapActions} from    "vuex"

export default {

    data() {
      return {

          route_link          :   null  ,
          liste_route_link    :   null  ,

          liste_route_import  :   null  ,

          user                :   {}
      }
    },

    computed : {

        ...mapGetters({
            getUser                     :   'authentification/getUser'              ,
            getAccessToken              :   'authentification/getAccessToken'       ,
            getIsAuthentificated        :   'authentification/getIsAuthentificated'
        }),
    },

    components: {
        Multiselect
    },

    async mounted() {

        this.user = this.getUser

        await this.fetchMaps()

        this.emitter.on('reSetRouteImport'          , async ()    =>  {

          await this.fetchMaps()
        })
    },  

    methods: {

        ...mapActions("authentification" ,  [
          "setUserAction"                 ,
          "setAccessTokenAction"          ,
          "setIsAuthentificatedAction"    
        ]),

        // 

        async fetchMaps() {

          try {

            this.$callApi("post",    "/route_import", null)
            .then((res)=> {

                this.liste_route_import = res.data

                this.prepareRouteLink()
            })
          }
          catch(e) {

              console.log(e)
          }
        },

        //

        prepareRouteLink() {

          this.liste_route_link = [ {"label" : "Links", "options" : {}, "class" : "mt-5"}, {"label" : "Route Imports", "options" : {}, "class" : "mt-5"} ]

          this.liste_route_link[0]["options"]["/"]                            = "Dashboard"
          this.liste_route_link[0]["options"]["/route/obs/route_import/add"]  = "Importer une Route"

          for (let i = 0; i < this.liste_route_import.length; i++) {

              this.liste_route_link[1]["options"][this.liste_route_import[i].id]  = this.liste_route_import[i].libelle     
          }
        },

        setRouteLink() {

            // Dashboard
            if(this.$route.path ==  "/") {

              this.route_link = this.$route.path
            }
            
            // Add
            if(this.$route.path ==  "/route/obs/route_import/add") {

              this.route_link = this.$route.path
            }

            let map_link_format = /^\/route\/obs\/route_import\/[^\/]+\/details$/

            // Map
            if(map_link_format.test(this.$route.path)) {

              this.route_link = this.$route.params.id_route_import
            }
        },

        goToRoute() {

            // Dashboard
            if(this.route_link  ==  "/") {

              if(this.$route.path !=  "/") {

                this.$goTo('/')
              }
            }

            // Add
            if(this.route_link  ==  "/route/obs/route_import/add") {

              if(this.$route.path !=  "/route/obs/route_import/add") {

                this.$goTo('/route/obs/route_import/add')
              }
            }

            // Map
            if(!isNaN(this.route_link)) {

              if(this.$route.path !=  '/route/obs/route_import/'+this.route_link+'/details') {

                this.$goTo('/route/obs/route_import/'+this.route_link+'/details')
              }
            }
        },

        //

        async logOut() {

          const res = await this.$callApi("post", "/logout" , null)

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
        },

        route_link(new_route_link, old_route_link) {

          this.goToRoute()
        },

        $route(to, from) {

          this.setRouteLink()
        }
    }
};
</script>

<style scoped></style>
