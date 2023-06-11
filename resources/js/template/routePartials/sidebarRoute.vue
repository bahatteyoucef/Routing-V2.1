<template>
    <!-- Organisation -->
    <li v-for="organisation in organisations" :key="organisation.BUID" class="nav-item p-0 pl-3">

        <!-- Organisation -->
        <span
            :id="'organisation_parent_'+organisation.BUID"

            class="nav-link pl-1 route_link"
            role="button"
        >

            <!-- Icon -->
            <span class="menu-icon ml-0">
                <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"></i>
            </span>

            <!-- Title -->
            <span class="menu-title" @click="linkClickedDetails($event, organisation.BUID)">{{organisation.ShortCode}}</span>

            <!-- Arrow + Icon -->
            <span class="menu-icon">
                <i class="mdi mdi-arrow-left-bold menu_arrow" @click="$accordion($event, 'organisation_'+organisation.BUID)"></i>
                <i class="mdi mdi-google-maps menu_icon_i"></i>
            </span>
        </span>

        <div
            class="animate__animated hide_submenu animate__faster w-100"
            :id="'organisation_'+organisation.BUID"
        >
            <ul class="nav flex-column sub-menu ml-3">
                <li class="nav-item" v-for="vendeur in organisation.vendeurs" :key="vendeur">

                    <!-- Vendeur -->
                    <span
                        :id="'vendeur_'+vendeur.SalesmanNo+'_li_'+organisation.BUID"

                        :class="'nav-link pr-0 pl-1 route_link '+vendeur.SalesmanNo"
                        role="button"
                    >
                        <!-- Icon -->
                        <span class="menu-icon ml-0">
                            <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"></i>
                        </span>

                        <!-- Title -->
                        <span class="menu-title" @click="linkClickedVendeurDetails($event, organisation.BUID,    vendeur.SalesmanNo)">{{ vendeur.JPlan }}</span>

                        <!-- Arrow -->
                        <span class="menu-icon">
                            <i class="mdi mdi-arrow-left-bold menu_icon_i menu_arrow" @click="$accordion($event, 'vendeur_semaine_'+vendeur.SalesmanNo)"></i>
                        </span>

                    </span>

                    <div
                        class="animate__animated hide_submenu animate__faster float-left w-100"
                        :id="'vendeur_semaine_'+vendeur.SalesmanNo"
                    >

                        <ul class="nav flex-column sub-menu ml-3">
                            <li class="nav-item" v-for="i in 4" :key="i">

                                <!-- Semaine i -->
                                <span
                                    :id="'semaine_'+vendeur.SalesmanNo+'_li_'+organisation.BUID+'_li_'+i"

                                    :class="'nav-link pr-0 pl-1 route_link '+vendeur.SalesmanNo"
                                    role="button"
                                >
                                    <!-- Icon -->
                                    <span class="menu-icon ml-0">
                                        <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"></i>
                                    </span>

                                    <!-- Title -->
                                    <span class="menu-title" @click="linkClickedVendeurSemaine($event, organisation.BUID,    vendeur.SalesmanNo,     i)">Semaine {{i}}</span>

                                    <!-- Arrow -->
                                    <span class="menu-icon">
                                        <i class="mdi mdi-arrow-left-bold menu_icon_i menu_arrow" @click="$accordion($event, 'vendeur_'+vendeur.SalesmanNo+'_semaine_'+i)"></i>
                                    </span>

                                </span>

                                <div
                                    class="animate__animated hide_submenu animate__faster float-left w-100"
                                    :id="'vendeur_'+vendeur.SalesmanNo+'_semaine_'+i"
                                >
                                    <ul class="nav flex-column sub-menu ml-3">
                                        
                                        <!-- Samedi -->
                                        <span   class="nav-link pl-1 route_link" @click="linkClickedVendeurJour($event, organisation.BUID,    vendeur.SalesmanNo,  i,  1)" 
                                                role="button"
                                                :id="'jour_'+vendeur.SalesmanNo+'_li_'+i+'_li_'+organisation.BUID+'_li_'+1"
                                        >

                                            <!-- Icon -->
                                            <span class="menu-icon ml-0">
                                                <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"></i>
                                            </span>

                                            <!-- Title -->
                                            <span :class="'menu-title float-left ml-0 '+vendeur.SalesmanNo">Samedi</span>

                                        </span>

                                        <!-- Dimanche -->
                                        <span   class="nav-link pl-1 route_link" @click="linkClickedVendeurJour($event, organisation.BUID,    vendeur.SalesmanNo,  i,  2)" 
                                                role="button"
                                                :id="'jour_'+vendeur.SalesmanNo+'_li_'+i+'_li_'+organisation.BUID+'_li_'+2"
                                        >

                                            <!-- Icon -->
                                            <span class="menu-icon ml-0">
                                                <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"></i>
                                            </span>

                                            <!-- Title -->
                                            <span :class="'menu-title '+vendeur.SalesmanNo">Dimanche</span>

                                        </span>

                                        <!-- Lundi -->
                                        <span   class="nav-link pl-1 route_link" @click="linkClickedVendeurJour($event, organisation.BUID,    vendeur.SalesmanNo,  i,  3)" 
                                                role="button"
                                                :id="'jour_'+vendeur.SalesmanNo+'_li_'+i+'_li_'+organisation.BUID+'_li_'+3"
                                        >

                                            <!-- Icon -->
                                            <span class="menu-icon ml-0">
                                                <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"></i>
                                            </span>

                                            <!-- Title -->
                                            <span :class="'menu-title '+vendeur.SalesmanNo">Lundi</span>

                                        </span>

                                        <!-- Mardi -->
                                        <span   class="nav-link pl-1 route_link" @click="linkClickedVendeurJour($event, organisation.BUID,    vendeur.SalesmanNo,  i,  4)" 
                                                role="button"
                                                :id="'jour_'+vendeur.SalesmanNo+'_li_'+i+'_li_'+organisation.BUID+'_li_'+4"
                                        >

                                            <!-- Icon -->
                                            <span class="menu-icon ml-0">
                                                <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"></i>
                                            </span>

                                            <!-- Title -->
                                            <span :class="'menu-title '+vendeur.SalesmanNo">Mardi</span>

                                        </span>

                                        <!-- Mercredi -->
                                        <span   class="nav-link pl-1 route_link" @click="linkClickedVendeurJour($event, organisation.BUID,    vendeur.SalesmanNo,  i,  5)" 
                                                role="button"
                                                :id="'jour_'+vendeur.SalesmanNo+'_li_'+i+'_li_'+organisation.BUID+'_li_'+5"
                                        >

                                            <!-- Icon -->
                                            <span class="menu-icon ml-0">
                                                <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"></i>
                                            </span>

                                            <!-- Title -->
                                            <span :class="'menu-title '+vendeur.SalesmanNo">Mercredi</span>

                                        </span>

                                        <!-- Jeudi -->
                                        <span   class="nav-link pl-1 route_link" @click="linkClickedVendeurJour($event, organisation.BUID,    vendeur.SalesmanNo,  i,  6)" 
                                                role="button"
                                                :id="'jour_'+vendeur.SalesmanNo+'_li_'+i+'_li_'+organisation.BUID+'_li_'+6"
                                        >

                                            <!-- Icon -->
                                            <span class="menu-icon ml-0">
                                                <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"></i>
                                            </span>

                                            <!-- Title -->
                                            <span :class="'menu-title '+vendeur.SalesmanNo">Jeudi</span>

                                        </span>

                                    </ul>
                                </div>

                            </li>
                        </ul>
                    </div>
                </li>

                <!--  -->

                <li class="nav-item">
                    <!-- Vendeur -->
                    <span
                        :id="'vendeur_'+'default'+'_li_'+organisation.BUID"

                        :class="'nav-link pr-0 pl-1 route_link '+'default'"
                        role="button"
                    >
                        <!-- Icon -->
                        <span class="menu-icon ml-0">
                            <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"></i>
                        </span>

                        <!-- Title -->
                        <span class="menu-title" @click="linkClickedVendeurDefaultDetails($event, organisation.BUID)">Default Routing</span>
                    </span>
                </li>
            </ul>
        </div>
    </li>
</template>

<script>
export default {

    data() {
        return {

            organisations   :   null,

            user            :   {
                nom         :   null,
                prenom      :   null
            }
        }
    },

    async mounted() {

        this.user.nom     = JSON.parse(localStorage.getItem('vuex')).user.nom
        this.user.prenom  = JSON.parse(localStorage.getItem('vuex')).user.prenom

        //

        this.emitter.on("refreshObservationRouting", async () => {

            await this.getData()
        })
    },

    methods : {

        async getData() {

            // Show Loading Page
            this.$showLoadingPage()

            const res               =   await this.$callApi("post"  ,   "/route/obs/organisations/informations" ,   null)
            console.log(res.data)

            this.organisations      =   res.data

            // Hide Loading Page
            this.$hideLoadingPage()
        },  

        //

        linkClickedDetails(event,  BUID) {

            // Go To Route
                this.$parent.goToRoute('route/obs/organisations/'+BUID+'/details', 'observation_routing_arrow')

            // Add Active
                this.addActiveDetails(event,  BUID)

            setTimeout(() => {

                // Emit Event

                    this.emitter.emit("linkClickedDetails", {
                        BUID     :   this.$route.params.id
                    });
                // 

            }, 355);
        },

        linkClickedVendeurDetails(event,  BUID, SalesmanNo) {

            // Go To Route
                this.$parent.goToRoute('route/obs/organisations/'+BUID+'/vendeurs/'+SalesmanNo+'/details', 'observation_routing_arrow')

            // Add Active
                this.addActiveVendeurDetails(event,  BUID, SalesmanNo)


            setTimeout(() => {

                // Emit Event

                    this.emitter.emit("linkClickedVendeurDetails", {
                        BUID                :   this.$route.params.id,
                        SalesmanNo          :   SalesmanNo
                    });
                // 
            }, 355);
        },

        linkClickedVendeurDefaultDetails(event, BUID) {

            // Go To Route
                this.$parent.goToRoute('route/obs/organisations/'+BUID+'/default/vendeurs/details', 'observation_routing_arrow')

            // Add Active
                this.addActiveVendeurDefaultDetails(event,  BUID)

            setTimeout(() => {

                // Emit Event

                    this.emitter.emit("addEventlinkClickedVendeurDefaultDetails", {
                        BUID                :   this.$route.params.id,
                    });
                // 
            }, 355);
        },

        linkClickedVendeurSemaine(event,  BUID, SalesmanNo, semaine) {

            // Go To Route
                this.$parent.goToRoute('route/obs/organisations/'+BUID+'/vendeurs/'+SalesmanNo+'/semaines/'+semaine, 'observation_routing_arrow')

            // Add Active
                this.addActiveVendeurSemaine(event,  BUID, SalesmanNo, semaine)

            setTimeout(() => {
            
                // Emit Event

                    this.emitter.emit("linkClickedVendeurSemaine", {
                        BUID                :   this.$route.params.id   ,
                        SalesmanNo          :   SalesmanNo              ,   
                        semaine             :   semaine
                    });
                // 
            }, 355);

        },

        linkClickedVendeurJour(event,  BUID, SalesmanNo, semaine, jour) {

            // Go To Route
                this.$parent.goToRoute('route/obs/organisations/'+BUID+'/vendeurs/'+SalesmanNo+'/semaines/'+semaine+'/jours/'+jour, 'observation_routing_arrow')

            // Add Active
                this.addActiveVendeurJour(event,  BUID, SalesmanNo, semaine, jour)

            setTimeout(() => {
            
                // Emit Event

                    this.emitter.emit("linkClickedVendeurJour", {
                        BUID                :   this.$route.params.id   ,
                        SalesmanNo          :   SalesmanNo              ,
                        semaine             :   semaine                 ,
                        jour                :   jour                    
                    });
                // 
            }, 355);
        },

        //

        addActiveDetails(event,  BUID) {

            // remove active class from all elements
                
                const list_active_elements  =   document.querySelectorAll(".active")

                list_active_elements.forEach(element => {

                    element.classList.remove('active')
                });

            // 
    
            // add active class to event.target

                const current_element  =   document.getElementById("organisation_parent_"+BUID)

                if(current_element) {                

                    current_element.classList.add('active')
                }
            //
        },

        addActiveVendeurDetails(event,  BUID, SalesmanNo) {

            // remove active class from all elements
                
                const list_active_elements  =   document.querySelectorAll(".active")

                list_active_elements.forEach(element => {

                    element.classList.remove('active')
                });

            // 
    
            // add active class to event.target

                const current_vendeur       =   document.getElementById("vendeur_"+SalesmanNo+"_li_"+BUID)

                if(current_vendeur) {                

                    current_vendeur.classList.add('active')
                }

                const current_organisation  =   document.getElementById("organisation_parent_"+BUID)

                if(current_organisation) {                

                    current_organisation.classList.add('active')
                }
            //
        },

        addActiveVendeurDefaultDetails(event, BUID) {

            // remove active class from all elements
                
                const list_active_elements  =   document.querySelectorAll(".active")

                list_active_elements.forEach(element => {

                    element.classList.remove('active')
                });

            // 
    
            // add active class to event.target

                const current_vendeur       =   document.getElementById("vendeur_"+"default"+"_li_"+BUID)

                if(current_vendeur) {                

                    current_vendeur.classList.add('active')
                }

                const current_organisation  =   document.getElementById("organisation_parent_"+BUID)

                if(current_organisation) {                

                    current_organisation.classList.add('active')
                }
            //
        },

        addActiveVendeurSemaine(event, BUID, SalesmanNo, semaine) {

            // remove active class from all elements
                    
                const list_active_elements  =   document.querySelectorAll(".active")

                list_active_elements.forEach(element => {

                    element.classList.remove('active')
                });

            // 
    
            // add active class to event.target

                const current_semaine       =   document.getElementById("semaine_"+SalesmanNo+'_li_'+BUID+'_li_'+semaine)

                if(current_semaine) {                

                    current_semaine.classList.add('active')
                }

                const current_vendeur       =   document.getElementById("vendeur_"+SalesmanNo+"_li_"+BUID)

                if(current_vendeur) {                

                    current_vendeur.classList.add('active')
                }

                const current_organisation  =   document.getElementById("organisation_parent_"+BUID)

                if(current_organisation) {                

                    current_organisation.classList.add('active')
                }
            //
        }, 

        addActiveVendeurJour(event,  BUID, SalesmanNo, semaine, jour) {

            // remove active class from all elements
                    
                const list_active_elements  =   document.querySelectorAll(".active")

                list_active_elements.forEach(element => {

                    element.classList.remove('active')
                });

            // 

            // add active class to event.target

                const current_jour          =   document.getElementById("jour_"+SalesmanNo+'_li_'+semaine+'_li_'+BUID+'_li_'+jour)

                if(current_jour) {                

                    current_jour.classList.add('active')
                }

                const current_semaine       =   document.getElementById("semaine_"+SalesmanNo+'_li_'+BUID+'_li_'+semaine)

                if(current_semaine) {                

                    current_semaine.classList.add('active')
                }

                const current_vendeur       =   document.getElementById("vendeur_"+SalesmanNo+"_li_"+BUID)

                if(current_vendeur) {                

                    current_vendeur.classList.add('active')
                }

                const current_organisation  =   document.getElementById("organisation_parent_"+BUID)

                if(current_organisation) {                

                    current_organisation.classList.add('active')
                }
            //
        }

        //

        
    }
};
</script>
