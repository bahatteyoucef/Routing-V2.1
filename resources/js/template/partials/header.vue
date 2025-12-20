<template>
    <nav v-if="user.username" class="navbar navbar default-layout-navbar col-sm-lg-12 col-sm-12 p-0 fixed-top d-flex flex-row navbar-light navbar-expand-lg" id="template-header">
        
        <!-- Logo -->
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center" id="logo_div_parent">

            <!-- <div> -->
              <router-link to="/" aria-current="page" class="navbar-brand brand-logo active router-link-active p-0">
                <img  :src="'/images/custom/header.png'"     alt="logo" class="mt-2"/>
              </router-link>

              <router-link to="/" aria-current="page"   class="navbar-brand brand-logo-mini active router-link-active p-0" id="mini_logo_custom" style="margin-top: 5px;">
                <img  :src="'/images/custom/logo.png'"     alt="logo" id="mini_logo_custom_image" class="mt-2"/>
              </router-link>
            <!-- </div> -->

            <!--  -->

            <div id="header_menu_buttons_div" style="display:none">
              <div id="show_header_menu_div" @click="$showHeaderMenu()">
                <span class="page-title-icon bg-gradient-primary text-white mr-2" id="show_header_menu_button">
                  <i class="mdi mdi-menu-left"></i>
                </span> 
              </div>

              <div id="hide_header_menu_div" style="display:none" @click="$hideHeaderMenu()">
                <span class="page-title-icon bg-gradient-primary text-white mr-2" id="hide_header_menu_button">
                  <i class="mdi mdi-menu-right"></i>
                </span> 
              </div>
            </div>

        </div>

        <div class="navbar-menu-wrapper d-flex align-items-end row h-100 w-100 pr-0 pl-4">

            <!-- Options -->
            <ul class="navbar-nav navbar-nav-right ml-5 container m-0 mt-1 animate__animated pr-0" id="header_menu">

              <!-- Route -->
              <li class="col-sm-3 nav-item" >
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
                />
              </li>
              <!--                -->

              <!-- Buttons        -->
              <li class="col-sm-7 nav-item">

                <div class="row justify-content-end">
                  <div class="col mt-1">
                    <button class="float-right btn bg-gradient-primary btn-block text-white h-100" @click="goToSelfServiceStats()">Self-Service Stats</button>
                  </div>

                  <div class="col mt-1">
                    <button class="float-right btn bg-gradient-primary btn-block text-white h-100" @click="goToStats()">Standard Stats</button>
                  </div>

                  <div class="col mt-1"  v-if="$isRole('Super Admin')||$isRole('BU Manager')">
                    <button class="float-right btn bg-gradient-primary btn-block text-white h-100" @click="goToUsers()">Users</button>
                  </div>

                  <div class="col mt-1"  v-if="$isRole('Super Admin')">
                    <button class="float-right btn bg-gradient-primary btn-block text-white h-100" @click="goToDistricts()">Districts</button>
                  </div>

                  <div class="col mt-1"  v-if="$isRole('Super Admin')">
                    <button class="float-right btn bg-gradient-primary btn-block text-white h-100" @click="goToUsersPointings()">Pointings</button>
                  </div>

                  <div class="col mt-1"  v-if="$isRole('Super Admin')||$isRole('BU Manager')">
                    <button class="float-right btn bg-gradient-primary btn-block text-white h-100" @click="AddRouteImport()">New Import</button>
                  </div>

                  <div class="col mt-1"  v-if="(route_import_existe)">
                    <button class="float-right btn bg-gradient-primary btn-block text-white h-100" @click="goToRouteTempo()">Suspended Import</button>
                  </div>
                </div>

              </li>

              <!-- Profile        -->
              <li class="col-sm-2 nav-item b-nav-dropdown dropdown nav-profile d-flex justify-content-center">

                <span id="profileDropdown" role="button" data-bs-toggle="collapse" href="#profile_header_list" aria-haspopup="true" aria-expanded="false" aria-controls="profile_header_list" class="nav-link dropdown-toggle">

                    <div class="nav-profile-img mt-1">
                      <img :src="'/images/custom/profile.png'" alt="image"/>
                      <span class="availability-status online"></span>
                    </div>

                    <div class="nav-profile-text mt-1">
                        <p class="mb-1 text-black">
                            {{user.first_name}} {{user.last_name}}
                        </p>
                    </div>

                </span>

                <ul tabindex="-1" class="dropdown-menu dropdown-menu-right" id="profile_header_list">

                    <li role="presentation" class="preview-item mt-1" @click="showProfile()">
                        <span  role="button" class="dropdown-item">
                          <i class="mdi mdi-account mr-2 text_primary"></i>
                          Profile
                        </span>
                    </li>

                    <li role="presentation" class="preview-item mt-1" @click="logOut()">
                        <span  role="button" class="dropdown-item">
                          <i class="mdi mdi-logout mr-2 text_primary"></i>
                          Log Out
                        </span>
                    </li>

                    <li class="m-3 text-secondary">
                        <span  role="button">
                          software version : V2.1
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

          route_link                :   null  ,
          liste_route_link          :   null  ,

          liste_route_import        :   null  ,

          route_import_existe       :   false ,

          user                      :   {}    
      }
    },

    computed : {

        ...mapGetters({
            getUser                     :   'authentification/getUser'              ,
            getAccessToken              :   'authentification/getAccessToken'       ,
            getIsAuthentificated        :   'authentification/getIsAuthentificated' ,

            getAddClient                :   'client/getAddClient'                   ,
            getUpdateClient             :   'client/getUpdateClient'                
        }),
    },

    components: {
        Multiselect
    },

    async mounted() {

        this.user = this.getUser
        console.log(this.getUser)

        await this.getRouteTempo()
        await this.fetchMaps()

        this.emitter.on('reSetRouteImport'          , async ()    =>  {

          this.route_import_existe  = false
          await this.fetchMaps()
        })

        //
        this.hideProfileHeaderList()
    },  

    unmounted() {

        this.emitter.off('reSetRouteImport')
    },

    methods: {

        ...mapActions("authentification" ,  [
          "setUserAction"                 ,
          "setAccessTokenAction"          ,
          "setIsAuthentificatedAction"    
        ]),

        //

        async getRouteTempo() {

            this.$callApi("post",    "/route_import_tempo/last", null)
            .then((res)=> {

              if(res.data) {

                this.route_import_existe  = true
              }
            })
        }, 

        async fetchMaps() {

            this.$callApi("post",    "/route_import/header", null)
            .then((res)=> {

                this.liste_route_import = res.data

                this.prepareRouteLink()
            })
        },

        prepareRouteLink() {

          this.liste_route_link = []

          for (let i = 0; i < this.liste_route_import.length; i++) {

              this.liste_route_link.push({ value : this.liste_route_import[i].id , label : this.liste_route_import[i].libelle})
          }
        },

        setRouteLink() {

          let map_link_format = /^\/route\/obs\/route_import\/[^\/]+\/details$/

          // Map
          if(map_link_format.test(this.$route.path)) {

            this.route_link = this.$route.params.id_route_import
          }

          else {

            this.route_link = null
          }
        },

        //

        AddRouteImport() {
    
            // Go To Route
            this.$router.push('/route/obs/route_import/add')
        },

        //

        goToSelfServiceStats() {

            // Go To Route
            this.$router.push('/stats/self-service')
        },

        goToStats() {

            // Go To Route
            this.$router.push('/stats/standard')
        },

        goToUsers() {

            // Go To Route
            this.$router.push('/users')
        },

        goToDistricts() {

            // Go To Route
            this.$router.push('/districts/expected_clients')
        },

        goToUsersPointings() {

            // Go To Route
            this.$router.push('/users/pointings')
        },

        goToMap() {

            if(this.route_link  !=  null) {

              this.$goTo('/route/obs/route_import/'+this.route_link+'/details')
            }
        },

        goToRouteTempo() {

            this.$goTo('/route/obs/route_import_tempo')
        },

        //

        async showProfile() {

          this.$router.push('/users/'+this.getUser.id+'/show')
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
        },

        // 

        async addClient() {

          let response     =   await this.$currentPosition(this.getUser.accuracy)

          if(response.success) {

            this.$router.push('/route_import/'+this.getUser.id_route_import+'/clients/add/'+response.position.coords.latitude+'/'+response.position.coords.longitude)
          }

          else {

            //
            this.$customMessages("GPS Error", "Vérifiez si votre GPS est activée", "error", "OK", "", "", "")
          }
        },

        async updateClient() {

          if(this.getUpdateClient) {

            this.$router.push('/route_import/'+this.getUser.id_route_import+'/clients/'+this.getUpdateClient.id+'/update')
          }
        },

        getClients() {

          this.$router.push('/route_import/'+this.getUser.id_route_import+'/clients')
        },

        //

        hideProfileHeaderList() {

          var profile_header_list_element = document.getElementById('profile_header_list');
          var map_options_element = document.getElementById('map_options');

          window.onclick = function (event) {

            if(profile_header_list_element) {

              profile_header_list_element.classList.remove("show")
            }

            if(map_options_element) {

              map_options_element.classList.remove("show")
            }
          }
        }
    },

    watch : {

        getUser(newUser, oldUser) {

            this.user   =   newUser
        },

        route_link(newRouterLink, oldRouterLink) {

          this.goToMap()
        },

        $route(to, from) {

            this.setRouteLink()
        }
    }
};
</script>

<style scoped></style>
