import axios from 'axios'

export default {

    methods: {

        $somethingSideBar() {

            const body = document.querySelector("body");

            // add class 'hover-open' to sidebar navitem while hover in sidebar-icon-only menu
            document.querySelectorAll(".sidebar .nav-item").forEach(function (el) {
                el.addEventListener("mouseover", function () {
                    if (body.classList.contains("sidebar-icon-only")) {
                        el.classList.add("hover-open");
                    }
                });
                el.addEventListener("mouseout", function () {
                    if (body.classList.contains("sidebar-icon-only")) {
                        el.classList.remove("hover-open");
                    }
                });
            });
        },

        //

        async $callApi(method, url, dataObj ){
            try {

                if(method == "post") {
                    return await axios.post(url, dataObj)
                }

                if(method == "get") {
                    return await axios.get(url, dataObj)
                }

            } catch (e) {
                return e.response
            }
        },

        //

        $goTo(route) {

            this.$router.push(route)
        },

        //

        $hideModal(id) {

            const container     =   document.getElementById(id)
            const modal         =   Modal.getInstance(container)

            modal.hide()
        },

        //

        $sidebarToggle() {

            const   body            =   document.querySelector("body")
            const   main            =   document.getElementById("main_content")
            
            let     id              =   null

            let     style           =   main.currentStyle || window.getComputedStyle(main)
            let     margin_left     =   parseInt(style.marginLeft.match(/(\d+)/)[0])

            // small
            if(body.classList.contains("sidebar-icon-only")) {

                clearInterval(id)

                id  =   setInterval(frame, 25)

                function frame() {

                    if (margin_left <= 75) {
                        
                        clearInterval(id)
                    }

                    else {
                    
                        margin_left             =   margin_left - 25
                        main.style.marginLeft   =   margin_left + "px"
                    }
                }
            }

            else {

                clearInterval(id)

                id  =   setInterval(frame, 25)

                function frame() {

                    if (margin_left >= 275) {
                        
                        clearInterval(id)
                    }

                    else {
                    
                        margin_left             =   margin_left + 25
                        main.style.marginLeft   =   margin_left + "px"
                    }
                }
            }
        },

        // 

        $showLoadingPage() {

            const loading_page          =   document.getElementById("loading_screen")
            loading_page.style.display  =   "block";
        },

        // 

        $hideLoadingPage() {

            const loading_page          =   document.getElementById("loading_screen")
            loading_page.style.display  =   "none";
        },

        //

        $accordion(event, element_id) {

            const collapsed_menu    =   document.querySelector('#'+element_id)

            if(collapsed_menu.classList.contains("hide_submenu")) {

                // Change Icon
                event.target.classList.remove("mdi-arrow-left-bold")
                event.target.classList.add("mdi-arrow-down-bold")

                // Hide Sub Menu
                collapsed_menu.classList.remove("hide_submenu")
                collapsed_menu.classList.add("show_submenu")

                collapsed_menu.classList.remove("animate__fadeOutRight")
                collapsed_menu.classList.add("animate__fadeInLeft")

                return 1
            }

            else {

                // Change Icon
                event.target.classList.remove("mdi-arrow-down-bold")
                event.target.classList.add("mdi-arrow-left-bold")

                // Show Sub Menu
                collapsed_menu.classList.remove("animate__fadeInLeft")
                collapsed_menu.classList.add("animate__fadeOutRight")

                setTimeout(()=> {
                    collapsed_menu.classList.remove("show_submenu")
                    collapsed_menu.classList.add("hide_submenu")
                }, 201)

                return 0
            }
        },

        //

        $highlightArrow() {

            setTimeout(() => {

                // Set Active class to arrow
                const elements  =   document.querySelectorAll("i.active_item");

                Array.prototype.forEach.call(elements, function(element) {

                    if(element.previousSibling != null) {

                        // get Arrow and make it active
                        element.previousSibling.classList.add("active_item")
                    }
                });  
            }, 55)
        },

        //

        $getResumeFileRouting(clients) {

            let resume_liste_journey_plan   =   this.$getListeJourneyPlans(clients)

            return resume_liste_journey_plan
        },

        //

        $getListeJourneyPlans(clients) {

            let liste_journey_plan  =   {}

            let journey_plan_existe =   false

            for (let i = 0; i < clients.length; i++) {

                journey_plan_existe         =   this.$checkExistJPlan(liste_journey_plan, clients[i].JPlan) 

                if(!journey_plan_existe) {

                    liste_journey_plan[clients[i].JPlan]                =   {JPlan  :   ""}
                    liste_journey_plan[clients[i].JPlan].JPlan          =   clients[i].JPlan   
                    liste_journey_plan[clients[i].JPlan].clients        =   this.$getJourneyPlanClients(clients, clients[i].JPlan)
                    liste_journey_plan[clients[i].JPlan].districts      =   this.$getJourneyPlanDistricts(liste_journey_plan[clients[i].JPlan].clients)
                    liste_journey_plan[clients[i].JPlan].liste_journee  =   this.$getJourneyPlanJournee(liste_journey_plan[clients[i].JPlan].clients)
                }
            }

            return liste_journey_plan
        },

        $checkExistJPlan(liste_journey_plan, JPlan) {

            for (const [key, value] of Object.entries(liste_journey_plan)) {

                if(liste_journey_plan[key].JPlan    ==  JPlan) {

                    return true
                }
            }

            return false
        },

        $getJourneyPlanClients(clients, JPlan) {

            let journey_plan_clients    =   []

            for (let i = 0; i < clients.length; i++) {

                if(clients[i].JPlan ==  JPlan) {

                    journey_plan_clients.push(clients[i])
                }
            }

            return journey_plan_clients
        },

        $getJourneyPlanRowSpan(districts) {

            let rowspan     =   0

            for (const [key_1, value_1] of Object.entries(districts)) {

                for (const [key_2, value_2] of Object.entries(districts[key_1].cites)) {

                    rowspan     =   rowspan +   1
                }
            }

            if(rowspan  ==  0) {

                rowspan     =   1
            }

            return rowspan
        },

        //

        $getJourneyPlanDistricts(journey_plan_clients) {

            let districts       =   {}
            let district_existe =   false

            for (let i = 0; i < journey_plan_clients.length; i++) {

                district_existe         =   this.$checkExistDistrict(districts, journey_plan_clients[i].DistrictNo) 

                if(!district_existe) {

                    districts[journey_plan_clients[i].DistrictNo]                           =   {DistrictNo  :   "", DistrictNameE : ""}
                    districts[journey_plan_clients[i].DistrictNo].DistrictNo                =   journey_plan_clients[i].DistrictNo   
                    districts[journey_plan_clients[i].DistrictNo].DistrictNameE             =   journey_plan_clients[i].DistrictNameE   
                    districts[journey_plan_clients[i].DistrictNo].clients                   =   this.$getDistrictClients(journey_plan_clients, journey_plan_clients[i].DistrictNo)
                    districts[journey_plan_clients[i].DistrictNo].cites                     =   this.$getDistrictCites(districts[journey_plan_clients[i].DistrictNo].clients)
                    districts[journey_plan_clients[i].DistrictNo].liste_type_client         =   this.$getDistrictTypeClient(districts[journey_plan_clients[i].DistrictNo].clients)
                }
            }

            return districts
        },

        $checkExistDistrict(districts, DistrictNo) {

            for (const [key, value] of Object.entries(districts)) {

                if(districts[key].DistrictNo    ==  DistrictNo) {

                    return true
                }
            }

            return false
        },

        $getDistrictClients(journey_plan_clients, DistrictNo) {

            let district_clients    =   []

            for (let i = 0; i < journey_plan_clients.length; i++) {

                if(journey_plan_clients[i].DistrictNo   ==  DistrictNo) {

                    district_clients.push(journey_plan_clients[i])
                }
            }

            return district_clients
        },

        //

        $getDistrictCites(district_clients) {

            let cites       =   {}
            let cite_existe =   false

            for (let i = 0; i < district_clients.length; i++) {

                cite_existe     =   this.$checkExistCite(cites, district_clients[i].CityNo) 

                if(!cite_existe) {

                    cites[district_clients[i].CityNo]                           =   {CityNo  :   "", CityNameE : ""}
                    cites[district_clients[i].CityNo].CityNo                    =   district_clients[i].CityNo   
                    cites[district_clients[i].CityNo].CityNameE                 =   district_clients[i].CityNameE   
                    cites[district_clients[i].CityNo].clients                   =   this.$getCiteClients(district_clients, district_clients[i].CityNo)
                    cites[district_clients[i].CityNo].liste_type_client         =   this.$getCiteTypeClient(cites[district_clients[i].CityNo].clients)
                }
            }

            return cites
        },

        $checkExistCite(cites, CityNo) {

            for (const [key, value] of Object.entries(cites)) {

                if(cites[key].CityNo    ==  CityNo) {

                    return true
                }
            }

            return false
        },

        $getCiteClients(district_clients, CityNo) {

            let cite_clients    =   []

            for (let i = 0; i < district_clients.length; i++) {

                if(district_clients[i].CityNo   ==  CityNo) {

                    cite_clients.push(district_clients[i])
                }
            }

            return cite_clients
        },

        //

        $getDistrictTypeClient(district_clients) {

            let liste_type_client       =   {}
            let type_client_existe =   false

            for (let i = 0; i < district_clients.length; i++) {

                type_client_existe     =   this.$checkExistTypeClient(liste_type_client, district_clients[i].CustomerType) 

                if(!type_client_existe) {

                    liste_type_client[district_clients[i].CustomerType]                     =   {CustomerType  :   ""}
                    liste_type_client[district_clients[i].CustomerType].CustomerType        =   district_clients[i].CustomerType   
                    liste_type_client[district_clients[i].CustomerType].clients             =   this.$getTypeClientClients(district_clients, district_clients[i].CustomerType)
                }
            }

            return liste_type_client
        },

        $checkExistTypeClient(liste_type_client, CustomerType) {

            for (const [key, value] of Object.entries(liste_type_client)) {

                if(liste_type_client[key].CustomerType  ==  CustomerType) {

                    return true
                }
            }

            return false
        },

        $getTypeClientClients(district_clients, CustomerType) {

            let type_client_clients    =   []

            for (let i = 0; i < district_clients.length; i++) {

                if(district_clients[i].CustomerType ==  CustomerType) {

                    type_client_clients.push(district_clients[i])
                }
            }

            return type_client_clients
        },

        //

        $getCiteTypeClient(cite_clients) {

            let liste_type_client       =   {}
            let type_client_existe      =   false

            for (let i = 0; i < cite_clients.length; i++) {

                type_client_existe     =   this.$checkExistTypeClient(liste_type_client, cite_clients[i].CustomerType) 

                if(!type_client_existe) {

                    liste_type_client[cite_clients[i].CustomerType]                     =   {CustomerType  :   ""}
                    liste_type_client[cite_clients[i].CustomerType].CustomerType        =   cite_clients[i].CustomerType   
                    liste_type_client[cite_clients[i].CustomerType].clients             =   this.$getTypeClientClients(cite_clients, cite_clients[i].CustomerType)
                }
            }

            return liste_type_client
        },

        $getTypeClientClients(cite_clients, CustomerType) {

            let type_client_clients    =   []

            for (let i = 0; i < cite_clients.length; i++) {

                if(cite_clients[i].CustomerType ==  CustomerType) {

                    type_client_clients.push(cite_clients[i])
                }
            }

            return type_client_clients
        },

        //

        $getJourneyPlanJournee(journey_plan_clients) {

            let liste_journee       =   {}
            let district_existe     =   false

            for (let i = 0; i < journey_plan_clients.length; i++) {

                district_existe         =   this.$checkExistDistrict(liste_journee, journey_plan_clients[i].Journee) 

                if(!district_existe) {

                    liste_journee[journey_plan_clients[i].Journee]                           =   {Journee  :   ""}
                    liste_journee[journey_plan_clients[i].Journee].Journee                   =   journey_plan_clients[i].Journee   
                    liste_journee[journey_plan_clients[i].Journee].clients                   =   this.$getJourneeClients(journey_plan_clients, journey_plan_clients[i].Journee)
                    liste_journee[journey_plan_clients[i].Journee].cites                     =   this.$getJourneeCites(liste_journee[journey_plan_clients[i].Journee].clients)
                }
            }

            return liste_journee
        },

        $checkExistJournee(liste_journee, Journee) {

            for (const [key, value] of Object.entries(liste_journee)) {

                if(liste_journee[key].Journee    ==  Journee) {

                    return true
                }
            }

            return false
        },

        $getJourneeClients(journey_plan_clients, Journee) {

            let journee_clients     =   []

            for (let i = 0; i < journey_plan_clients.length; i++) {

                if(journey_plan_clients[i].Journee   ==  Journee) {

                    journee_clients.push(journey_plan_clients[i])
                }
            }

            return journee_clients
        },

        //

        $getJourneeCites(journee_clients) {

            let cites       =   {}
            let cite_existe =   false

            for (let i = 0; i < journee_clients.length; i++) {

                cite_existe     =   this.$checkExistCite(cites, journee_clients[i].CityNo) 

                if(!cite_existe) {

                    cites[journee_clients[i].CityNo]                           =   {CityNo  :   "", CityNameE : ""}
                    cites[journee_clients[i].CityNo].CityNo                    =   journee_clients[i].CityNo   
                    cites[journee_clients[i].CityNo].CityNameE                 =   journee_clients[i].CityNameE   
                    cites[journee_clients[i].CityNo].clients                   =   this.$getCiteClients(journee_clients, journee_clients[i].CityNo)
                }
            }

            return cites
        },

        //

        $getDoublantTel(clients) {

            let clients_tempo   =   []

            let double_existe   =   false

            for (let i = 0; i < clients.length - 1; i++) {

                double_existe   =   false

                for (let j = i + 1; j < clients.length; j++) {

                    if(clients[i].Tel   ==  clients[j].Tel) {

                        if(!this.$clientExisteInArray(clients[j], clients_tempo)) {

                            if(double_existe    ==  false) {

                                double_existe   =   true
                                clients_tempo.push(clients[i])
                            }

                            clients_tempo.push(clients[j])
                        }
                    }
                }                
            }

            return clients_tempo
        },

        $getDoublantLatitudeLongitude(clients) {

            let clients_tempo   =   []

            let double_existe   =   false

            for (let i = 0; i < clients.length - 1; i++) {

                double_existe   =   false

                for (let j = i + 1; j < clients.length; j++) {

                    if((clients[i].Latitude ==  clients[j].Latitude)&&(clients[i].Longitude ==  clients[j].Longitude)) {

                        if(!this.$clientExisteInArray(clients[j], clients_tempo)) {

                            if(double_existe    ==  false) {

                                double_existe   =   true
                                clients_tempo.push(clients[i])
                            }

                            clients_tempo.push(clients[j])
                        }
                    }
                }                
            }

            return clients_tempo
        },

        $getDoublantCustomerNameE(clients) {

            let clients_tempo   =   []

            let double_existe   =   false

            for (let i = 0; i < clients.length - 1; i++) {

                for (let j = i + 1; j < clients.length; j++) {

                    if(clients[i].CustomerNameE ==  clients[j].CustomerNameE) {

                        if(!this.$clientExisteInArray(clients[j], clients_tempo)) {

                            if(double_existe    ==  false) {

                                double_existe   =   true
                                clients_tempo.push(clients[i])
                            }

                            clients_tempo.push(clients[j])
                        }
                    }
                }                
            }

            return clients_tempo
        },

        $getDoublantCustomerCode(clients) {

            let clients_tempo   =   []

            let double_existe   =   false

            for (let i = 0; i < clients.length - 1; i++) {

                double_existe   =   false

                for (let j = i + 1; j < clients.length; j++) {

                    if(clients[i].CustomerCode  ==  clients[j].CustomerCode) {

                        if(!this.$clientExisteInArray(clients[j], clients_tempo)) {

                            if(double_existe    ==  false) {

                                double_existe   =   true
                                clients_tempo.push(clients[i])
                            }

                            clients_tempo.push(clients[j])
                        }
                    }
                }                
            }

            return clients_tempo
        },

        //

        $clientExisteInArray(client, clients) {

            for (let i = 0; i < clients.length; i++) {

                if(clients[i].CustomerNo    ==  client.CustomerNo) {

                    return true
                }                
            }

            return false
        }
    }
}