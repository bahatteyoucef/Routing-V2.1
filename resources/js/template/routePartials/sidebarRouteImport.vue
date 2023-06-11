<template>
    <div>
        <!-- Organisation -->
        <li v-for="organisation in organisations" :key="organisation.BUID" class="nav-item p-0 pl-3">

            <!-- Organisation -->
            <span
                :id="'organisation_route_import_parent_'+organisation.BUID"

                class="nav-link pl-1 route_link"
                role="button"
            >

                <!-- Icon -->
                <span class="menu-icon ml-0">
                    <i class="mdi mdi-arrow-right-bold-hexagon-outline ml-0 mr-1"></i>
                </span>

                <!-- Title -->
                <span class="menu-title">{{organisation.ShortCode}}</span>

                <!-- Arrow + Icon -->
                <span class="menu-icon">
                    <i class="mdi mdi-arrow-left-bold route_import_arrow menu_arrow" @click="$accordion($event, 'organisation_route_import_'+organisation.BUID)"></i>
                    <i class="mdi mdi-google-maps menu_icon_i"></i>
                </span>
            </span>
        </li>

    </div>
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

        this.emitter.on("refreshRouteImport", async () => {

            await this.getData()
        })
    },

    methods : {

        async getData() {

            // Show Loading Page
            this.$showLoadingPage()

            const res               =   await this.$callApi("post"  ,   "/route/obs/organisations/route_import/informations" ,   null)
            console.log(res.data)

            this.organisations      =   res.data

            // Hide Loading Page
            this.$hideLoadingPage()
        },  

        //

        linkClickedRouteImportDetails(event,  BUID, id_route_import) {

            // Go To Route
            this.$parent.goToRoute('route/obs/organisations/'+BUID+'/route_import/'+id_route_import+'/details', 'observation_route_import_arrow')

            // Add Active
            this.addActiveRouteImportDetails(event, BUID, id_route_import)

            setTimeout(() => {

                // Emit Event

                    this.emitter.emit("linkClickedRouteImportDetails", {
                        BUID            :   this.$route.params.id   ,
                        id_route_import :   id_route_import
                    });

            }, 355);
        },

        //

        addActiveRouteImportDetails(BUID, id_route_import) {

            // remove active class from all elements
                
                const list_active_elements  =   document.querySelectorAll(".active")

                list_active_elements.forEach(element => {

                    element.classList.remove('active')
                });

            // 

            // Remove Active class from arrows

            const arrows            =   document.querySelectorAll('.menu_arrow')

            for (let i = 0; i < arrows.length; i++) {

                arrows[i].classList.remove("active_item")
            }

            //
    
            // add active class to event.target

                if(id_route_import > -1) {

                    console.log(id_route_import)
                    console.log(BUID)

                    const current_route_import       =   document.getElementById("route_import_"+id_route_import+"_li_"+BUID)

                    if(current_route_import) {                

                        console.log(1)

                        current_route_import.classList.add('active')
                    }            
                }

                else {

                    const add_route_import       =   document.getElementById("route_import_add_route_import_"+BUID)

                    if(add_route_import) {                

                        console.log(2)

                        add_route_import.classList.add('active')
                    } 
                }

                const current_organisation  =   document.getElementById("organisation_route_import_parent_"+BUID)

                if(current_organisation) {                

                    current_organisation.classList.add('active')
                }
            //
        }
    }
};
</script>
