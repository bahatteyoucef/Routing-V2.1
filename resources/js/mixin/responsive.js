
export default {

    methods: {

        $showHeaderMenu() {

            let main_content                    =   document.getElementById("main_content")
            main_content.style.marginTop        =   "205px"

            //

            let header_menu                     =   document.getElementById("header_menu")
            header_menu.classList.remove("animate__slideOutRight")
            header_menu.classList.add("animate__slideInRight")

            header_menu.style.display           =   "block" 
            
            //

            let show_header_menu_div            =   document.getElementById("show_header_menu_div")
            show_header_menu_div.style.display  =   "none"

            let hide_header_menu_div            =   document.getElementById("hide_header_menu_div")
            hide_header_menu_div.style.display  =   "block"

            //
        },

        $hideHeaderMenu() {

            let main_content                    =   document.getElementById("main_content")
            main_content.style.marginTop        =   "5px"

            //

            let header_menu                     =   document.getElementById("header_menu")
            header_menu.classList.remove("animate__slideInRight")
            header_menu.classList.add("animate__slideOutRight")

            setTimeout(()   =>  {

                header_menu.style.display       =   "none" 
            }, 555)

            //

            let hide_header_menu_div            =   document.getElementById("hide_header_menu_div")
            hide_header_menu_div.style.display  =   "none"

            let show_header_menu_div            =   document.getElementById("show_header_menu_div")
            show_header_menu_div.style.display  =   "block"

            //
        },

        //

        $showMapOptions() {

            let map_top_buttons_parent_div                     =   document.getElementById("map_top_buttons_parent_div")
            map_top_buttons_parent_div.classList.remove("animate__slideOutRight")
            map_top_buttons_parent_div.classList.add("animate__slideInRight")

            map_top_buttons_parent_div.style.display           =   "block" 
            
            //

            let show_map_options_div            =   document.getElementById("show_map_options_div")
            show_map_options_div.style.display  =   "none"

            let hide_map_options_div            =   document.getElementById("hide_map_options_div")
            hide_map_options_div.style.display  =   "block"

            //
        },

        $hideMapOptions() {

            let map_top_buttons_parent_div                  =   document.getElementById("map_top_buttons_parent_div")
            map_top_buttons_parent_div.classList.remove("animate__slideInRight")
            map_top_buttons_parent_div.classList.add("animate__slideOutRight")

            setTimeout(()   =>  {

                map_top_buttons_parent_div.style.display    =   "none" 
            }, 555)

            //

            let hide_map_options_div            =   document.getElementById("hide_map_options_div")
            hide_map_options_div.style.display  =   "none"

            let show_map_options_div            =   document.getElementById("show_map_options_div")
            show_map_options_div.style.display  =   "block"

            //
        }
    }
}