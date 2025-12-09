import axios                from 'axios'

import * as XLSX            from 'xlsx';

// store
import store                from '../store/store'

export default {

    data() {

        return {

            show_map        :   null    ,
            territories     :   []
        }
    },

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

        async $callApiResponse(method, url, dataObj, responseType ){

            if(method == "post") {

                return await axios.post(url, dataObj, {
                    responseType: responseType
                })
            }

            if(method == "get") {

                return await axios.get(url, dataObj, {
                    responseType: responseType
                })
            }
        },

        //

        $goTo(route) {

            this.$router.push(route)
        },

        $goBack() {

            if(window.history.length    >   0) {

                this.$router.go(-1); 
            }
        },

        //

        $hideModal(id) {

            const container = document.getElementById(id);
            const modal = Modal.getInstance(container);

            return new Promise(resolve => {
                // Listen for Bootstrap’s `hidden.bs.modal` event
                container.addEventListener('hidden.bs.modal', () => {
                    resolve();
                }, { once: true });
                
                // Kick off the hide animation
                modal.hide();
            });
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

            store.commit("loading_page/setShowLoadingPage", true)
        },

        // 

        $hideLoadingPage() {

            store.commit("loading_page/setShowLoadingPage", false)
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

        $formatDate(date) {

            const months = [
                "January", "February", "March", "April", "May", "June", "July",
                "August", "September", "October", "November", "December"
            ];

            const day = date.getDate();
            const month = months[date.getMonth()];
            const year = date.getFullYear();

            return `${day} ${month} ${year}`;
        },

        //

        async $imageURLToBase64(url) {

            return new Promise((resolve, reject) => {

                const xhr           =   new XMLHttpRequest();
                xhr.open('GET', url, true);

                xhr.responseType    =   'blob';

                xhr.onload = function() {

                    if (this.status === 200) {

                        const reader = new FileReader();
                        reader.readAsDataURL(this.response);
                        reader.onloadend = function() {
                            
                            resolve(this.result);
                        };
                    } 

                    else {

                        resolve(null);
                    }
                };

                xhr.onerror = function(error) {
                
                    resolve(null);
                };

                xhr.send();
            });
        },

        // Function to convert an image to Base64
        $imageToBase64(imageFile) {

            return new Promise((resolve, reject) => {

                const reader = new FileReader();
                reader.onload = () => resolve(reader.result.split(",")[1]);
                reader.onerror = (error) => reject(error);
                reader.readAsDataURL(imageFile);
            });
        },

        // Function to convert Base64 back to an image and display it
        $base64ToImage(base64String, outputElement) {

            const image     =   new Image();
            image.src       =   "data:image/jpeg;base64," + base64String;
            
            image.onload = () => {
                outputElement.src   =   image.src;
            };
        },

        $displayImage(file, imgElement) {

            if (!imgElement) return;
    
            // Clean up previous object URL
            if (imgElement._objectURL) {
                URL.revokeObjectURL(imgElement._objectURL);
                imgElement._objectURL = null;
            }

            if (!file) {
                imgElement.src = "";
                return;
            }

            try {
                // Create and store object URL
                const objectUrl = URL.createObjectURL(file);
                imgElement.src = objectUrl;
                imgElement._objectURL = objectUrl;  // Store reference on element
                
                // Keep component reference for cleanup
                this.customerBarCodeObjectURL = objectUrl;
            } 
            catch (error) {
                console.error("Image display failed:", error);
                imgElement.src = "";
            }
        },

        //

        $createFile(original_name, file_input_id) {

            if(original_name   !=  null) {

                // Create a new workbook
                const workbook = XLSX.utils.book_new();

                // Create a new worksheet
                const worksheet = XLSX.utils.aoa_to_sheet([["Hello", "World"]]);

                // Add the worksheet to the workbook
                XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");

                // Generate the Excel file binary data
                const excelBinaryData = XLSX.write(workbook, { type: "binary", bookType: "xlsx" });

                // Convert the binary data to a Blob
                const fileData = new Blob([this.$s2ab(excelBinaryData)], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });

                // Create a new file object
                const file = new File([fileData], original_name, { type: fileData.type });

                // Get the file input element
                const fileInput = document.getElementById(file_input_id);

                // Create a new file list
                const fileList = new DataTransfer();

                // Add the file to the file list
                fileList.items.add(file);

                // Set the file list to the file input
                fileInput.files = fileList.files;
            }
        },

        // Utility function to convert a string to ArrayBuffer
        $s2ab(s) {

            const buf = new ArrayBuffer(s.length);
            const view = new Uint8Array(buf);

            for (let i = 0; i < s.length; i++) {
                view[i] = s.charCodeAt(i) & 0xFF;
            }

            return buf;
        },

        //

        $getDoublant(clients) {

            let clients_double                              =   {}

            let clients_tempo_double_tel                    =   []
            let clients_tempo_double_latitude_longitude     =   []
            let clients_tempo_double_customer_namee         =   []
            let clients_tempo_double_customer_code          =   []

            let double_tel_existe                           =   false
            let double_latitude_longitude_existe            =   false
            let double_customer_namee_existe                =   false
            let double_customer_code_existe                 =   false

            for (let i = 0; i < clients.length - 1; i++) {

                double_tel_existe                               =   false
                double_latitude_longitude_existe                =   false
                double_customer_namee_existe                    =   false
                double_customer_code_existe                     =   false

                for (let j = i + 1; j < clients.length; j++) {

                    // Double Tel
                    if(clients[i].Tel   ==  clients[j].Tel) {

                        if(!this.$clientExisteInArray(clients[j], clients_tempo_double_tel)) {

                            if(double_tel_existe    ==  false) {

                                double_tel_existe   =   true
                                clients_tempo_double_tel.push(clients[i])
                            }

                            clients_tempo_double_tel.push(clients[j])
                        }
                    }

                    // Double Latitude Longitude
                    if((clients[i].Latitude ==  clients[j].Latitude)&&(clients[i].Longitude ==  clients[j].Longitude)) {

                        if(!this.$clientExisteInArray(clients[j], clients_tempo_double_latitude_longitude)) {

                            if(double_latitude_longitude_existe    ==  false) {

                                double_latitude_longitude_existe   =   true
                                clients_tempo_double_latitude_longitude.push(clients[i])
                            }

                            clients_tempo_double_latitude_longitude.push(clients[j])
                        }
                    }

                    // Double CustomerNameE
                    if(clients[i].CustomerNameE ==  clients[j].CustomerNameE) {

                        if(!this.$clientExisteInArray(clients[j], clients_tempo_double_customer_namee)) {

                            if(double_customer_namee_existe    ==  false) {

                                double_customer_namee_existe    =   true
                                clients_tempo_double_customer_namee.push(clients[i])
                            }

                            clients_tempo_double_customer_namee.push(clients[j])
                        }
                    }

                    // Double CustomerCode
                    if(clients[i].CustomerCode  ==  clients[j].CustomerCode) {

                        if(!this.$clientExisteInArray(clients[j], clients_tempo_double_customer_code)) {

                            if(double_customer_code_existe      ==  false) {

                                double_customer_code_existe         =   true
                                clients_tempo_double_customer_code.push(clients[i])
                            }

                            clients_tempo_double_customer_code.push(clients[j])
                        }
                    }
                }                
            }

            clients_double.$getDoublantTel                  =   clients_tempo_double_tel
            clients_double.$getDoublantGPS                  =   clients_tempo_double_latitude_longitude
            clients_double.$getDoublantCustomerNameE        =   clients_tempo_double_customer_namee
            clients_double.$getDoublantCustomerCode         =   clients_tempo_double_customer_code

            return clients_double
        },

        //

        $clientExisteInArray(client, clients) {

            for (let i = 0; i < clients.length; i++) {

                if(clients[i].CustomerNo    ==  client.CustomerNo) {

                    return true
                }                
            }

            return false
        },

        //

        $setRowspans(resume_liste_journey_plan) {

            let journey_plan_rowspan_global =   0
            let district_rowspan_global     =   0
            let cite_rowspan_global         =   0

            for (const [key, value] of Object.entries(resume_liste_journey_plan)) {

                journey_plan_rowspan_global     =   0

                for (const [key_2, value_2] of Object.entries(resume_liste_journey_plan[key].districts)) {

                    district_rowspan_global         =   0   

                    for (const [key_3, value_3] of Object.entries(resume_liste_journey_plan[key].districts[key_2].cites)) {

                        cite_rowspan_global             =   0

                        for (const [key_4, value_4] of Object.entries(resume_liste_journey_plan[key].districts[key_2].cites[key_3].liste_type_client)) {

                            resume_liste_journey_plan[key].districts[key_2].cites[key_3].liste_type_client[key_4].rowspan   =   1

                            cite_rowspan_global                                                                             =   cite_rowspan_global     +   1
                        }

                        resume_liste_journey_plan[key].districts[key_2].cites[key_3].rowspan    =   cite_rowspan_global

                        district_rowspan_global                                                 =   district_rowspan_global +   cite_rowspan_global
                    }

                    resume_liste_journey_plan[key].districts[key_2].rowspan     =   district_rowspan_global

                    journey_plan_rowspan_global                                 =   journey_plan_rowspan_global +   district_rowspan_global
                }

                resume_liste_journey_plan[key].rowspan      =   journey_plan_rowspan_global
            }

            //

            let journee_rowspan_journee_global      =   0
            let cite_rowspan_journee_global         =   0

            for (const [key, value] of Object.entries(resume_liste_journey_plan)) {

                journee_rowspan_journee_global     =   0

                for (const [key_2, value_2] of Object.entries(resume_liste_journey_plan[key].liste_journee)) {

                    cite_rowspan_journee_global      =   0

                    for (const [key_3, value_3] of Object.entries(resume_liste_journey_plan[key].liste_journee[key_2].cites)) {

                        resume_liste_journey_plan[key].liste_journee[key_2].cites[key_3].rowspan_journee    =   1

                        cite_rowspan_journee_global                                                         =   cite_rowspan_journee_global +   1
                    }

                    resume_liste_journey_plan[key].liste_journee[key_2].rowspan_journee     =   cite_rowspan_journee_global

                    journee_rowspan_journee_global                                          =   journee_rowspan_journee_global  +   cite_rowspan_journee_global
                }

                resume_liste_journey_plan[key].rowspan_journee  =   journee_rowspan_journee_global
            }

            return resume_liste_journey_plan
        },

        //

        $currentPosition(accuracy_max) {

            let maxRetries  =   10;
            let attempts    =   0;

            return new Promise((resolve) => {
                if (!navigator.geolocation) {
                    resolve({ success: false, error: "Geolocation is not supported by this browser" });
                    return;
                }

                const attemptPosition = () => {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const accuracy = position.coords.accuracy;

                            console.log(accuracy);

                            if (Math.ceil(accuracy) <= accuracy_max) {
                                resolve({ success: true, position });
                            } else if (attempts >= maxRetries) {
                                resolve({ success: false, error: "Maximum retries reached, accuracy insufficient." });
                            } else {
                                attempts++;
                                attemptPosition(); // Retry
                            }
                        },
                        (error) => {
                            console.error(`Geolocation error: ${error.message}`);
                            if (attempts >= maxRetries) {
                                resolve({ success: false, error });
                            } else {
                                attempts++;
                                attemptPosition(); // Retry on error
                            }
                        },
                        {
                            enableHighAccuracy: true,
                            maximumAge: 0,
                            timeout: 10000,
                        }
                    );
                };

                attemptPosition(); // Start
            });
        },

        //

        $variableSize(variable) {

            let serializedVariable;

            if (variable instanceof File || variable instanceof Blob) {
                // If the variable is a File or Blob, use it directly
                serializedVariable = variable;
            } else if (typeof variable === 'object') {
                // If the variable is an object, serialize it to a JSON string
                serializedVariable = JSON.stringify(variable);
            } else if (typeof variable === 'string') {
                // If the variable is a string, use it directly
                serializedVariable = variable;
            } else {
                console.error('Unsupported variable type');
                return;
            }

            // Calculate the size in bytes
            let byteSize = new Blob([serializedVariable]).size;

            // Convert bytes to kilobytes
            let kilobyteSize = byteSize / 1024;

            return kilobyteSize.toFixed(2);
        },

        //

        async $compressImage(file, max_size_mb = 0.125, max_width_or_height = 750) {

            const options = {
                maxWidthOrHeight: max_width_or_height,      // still cap at 500px if you want :contentReference[oaicite:5]{index=5}
                maxSizeMB: max_size_mb,                     // target ≤0.5 MB (instead of only constraining dimensions)
                useWebWorker: true,                         // keep UI responsive
                fileType: 'image/webp',                     // switch to WebP for better quality/size ratio
                initialQuality: 1,                          // start high; you can dial down if needed
                alwaysKeepResolution: false                 // preserve original pixel count if the library supports it
            };

            const compressedBlob = await imageCompression(file, options);
            return new File([compressedBlob], file.name.replace(/\.\w+$/, '.webp'), {
                type: 'image/webp'
            });
        },

        $compressImageMuch(file) {

            return new Promise((resolve, reject) => {

                const reader = new FileReader();

                reader.onload = (event) => {

                    const img   =   new Image();
                    img.src     =   event.target.result;

                    img.onload  = () => {

                        const maxWidth  =   200 // 800; // Maximum width
                        const maxHeight =   200 // 800; // Maximum height

                        let width       =   img.width;
                        let height      =   img.height;

                        // Scale the image to fit within the maximum dimensions while preserving aspect ratio
                        if (width > maxWidth || height > maxHeight) {

                            const aspectRatio = width / height;

                            if (width > height) {

                                width   =   maxWidth;
                                height  =   width / aspectRatio;
                            } else {

                                height  =   maxHeight;
                                width   =   height * aspectRatio;
                            }
                        }

                        const canvas    =   document.createElement('canvas');
                        const ctx       =   canvas.getContext('2d');

                        canvas.width    =   width;
                        canvas.height   =   height;

                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob((blob) => {

                            const compressedFile = new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() });
                            resolve(compressedFile);
                        }, 'image/jpeg', 0.9);
                    };
                };

                reader.readAsDataURL(file);
            });
        },

        //

        $showPositionOnMap(map_id, latitude, longitude, territories) {

            if(window.navigator.onLine) {

                if (this.show_map) {

                    // Clear all existing layers (including markers)
                    this.show_map.eachLayer((layer) =>  {
                        this.show_map.removeLayer(layer);
                    });

                    //
                    this.$hideTerritores()
                    this.$showTerritories(territories)

                    //
                    this.show_map.setView([latitude, longitude], 15); // Replace with your coordinates and zoom level

                    // Define tile layer (e.g., OpenStreetMap)
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(this.show_map);

                    // Create a marker
                    let marker  =   L.marker([latitude, longitude])
                    marker.addTo(this.show_map);

                    return marker
                }

                else {

                    this.show_map   =   L.map(map_id).setView([latitude, longitude], 15); // Replace with your coordinates and zoom level

                    // added this to prevent error when i zoom during showing popup
                    const _oldAnimate = L.Popup.prototype._animateZoom;

                    L.Popup.prototype._animateZoom = function(zoom) {
                        
                        if (!this._map) {
                            // map reference is gone, skip repositioning
                            return this;
                        }
                        
                        // otherwise call the original method
                        return _oldAnimate.call(this, zoom);
                    };

                    //
                    this.$hideTerritores()
                    this.$showTerritories(territories)

                    // Define tile layer (e.g., OpenStreetMap)
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(this.show_map);

                    // Create a marker
                    let marker  =   L.marker([latitude, longitude])
                    marker.addTo(this.show_map);

                    //
                    return marker
                }
            }
        },

        //  //  //  //  //

        async $showPositionOnMapMultiMap(show_map, map_id, client, close_clients) {

            let closeIcon       = null;
            let mainIcon        = null;
            let mainMarker      = null;
            let closeMarkers    = null;

            if (!window.navigator.onLine) throw new Error("Cannot show map while offline");
            
            //
            const container     =   document.getElementById(map_id);

            // Wait for container to be fully visible
            await new Promise(resolve => {
                const checkReady = () => {
                    if (container.offsetWidth > 0 && container.offsetHeight > 0) {
                        resolve();
                    } else {
                        requestAnimationFrame(checkReady);
                    }
                };

                checkReady();
            });

            // REUSE MAP INSTANCE OR CREATE NEW
            const map = show_map || new L.Map(map_id, {
                preferCanvas        : true,
                zoomControl         : true,
                zoom                : 12,
                zoomAnimation       : true,    // keep the map zoom animation
                markerZoomAnimation : true,    // you can still keep marker zoom animation if you like
                center              : [0, 0]
            });

            // added this to prevent error when i zoom during showing popup
            const _oldAnimate = L.Popup.prototype._animateZoom;

            L.Popup.prototype._animateZoom = function(zoom) {
                
                if (!this._map) {
                    // map reference is gone, skip repositioning
                    return this;
                }
                
                // otherwise call the original method
                return _oldAnimate.call(this, zoom);
            };

            // TILE LAYER MANAGEMENT
            if (!show_map) {
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
            }

            // ASYNC MAP PREPARATION
            await new Promise(resolve => map.whenReady(resolve));
            map.invalidateSize({ animate: false, pan: false });

            // MARKER MANAGEMENT
            const clearExistingMarkers = () => {
                map.eachLayer(layer => {
                    if (layer instanceof L.Marker) map.removeLayer(layer);
                });
            };

            if (show_map) clearExistingMarkers();

            // MAIN MARKER
            if (client) {
                mainIcon = L.icon({
                    iconUrl: '/images/A52714.png',
                    iconSize: [25, 25],
                    popupAnchor: [0, -15]
                });

                mainMarker  =    L.marker([client.Latitude, client.Longitude]       , { draggable: true , icon: mainIcon })
                                    .bindPopup(this.$createTooltipContent(client)   , { permanent: false, autoPan: false })
                                    .addTo(map);
            }

            // CLOSE CLIENTS MARKERS
            if (close_clients) {
                
                closeIcon           =   L.icon({ iconUrl: '/images/F9A825.png', iconSize: [20, 20], popupAnchor: [0, -10] });
                
                closeMarkers        =   close_clients.map(c => {
                                                const marker    =   L.marker([c.Latitude, c.Longitude]              , { icon: closeIcon                     })
                                                                        .bindPopup(this.$createTooltipContent(c)    , { permanent: false, autoPan: false    })
                                                                        .addTo(map);

                                                return marker;
                                            }
                                        );

                const allMarkers    =   L.featureGroup([mainMarker, ...closeMarkers]);
                map.fitBounds(allMarkers.getBounds().pad(0.2));
            }

            return {
                show_map: map,
                position_marker: mainMarker,
                close_clients_markers: closeMarkers
            };
        },

        $createTooltipContent(client) {
            return `
                CustomerCode   : ${client.CustomerCode}  <br />
                CustomerNameA  : ${client.CustomerNameA} <br />
                CustomerNameE  : ${client.CustomerNameE} <br />
                CustomerType   : ${client.CustomerType}  <br />
            `;
        },

        $focuseMarkers(show_map, clients_markers) {

            // Create a marker group
            var markers     =   L.featureGroup();

            // Add markers to the marker group
            var markerArray =   []  

            // Set Markers
            clients_markers.forEach(marker => {
                markerArray.push(marker.addTo(markers))
            });

            // Add the marker group to the map
            show_map.addLayer(markers);

            if(markerArray.length > 0) {

                // Get the bounds of all the markers
                var groupBounds = markers.getBounds();

                // Zoom the map to fit the bounds of the markers
                show_map.fitBounds(groupBounds);
            }
        },

        //  //  //  //  //

        $checkMarkerInsideUserPolygons(marker) {

            for (let index = 0; index < this.territories.length; index++) {

                if (this.territories[index].contains(marker.getLatLng())) {

                    return true
                }
            }

            return false
        },

        $checkMarkerInsideUserPolygonsWithoutMap(point_latitude, point_longitude ,polygons_latitude_longitude_array) {

            // Create a point
            let point   =   L.latLng(point_latitude, point_longitude);

            //
            for (let index = 0; index < polygons_latitude_longitude_array.length; index++) {

                // Initialize a polygon using Leaflet
                let polygon     =   L.polygon(JSON.parse(polygons_latitude_longitude_array[index].latlngs));

                // Check if the point is inside the polygon
                let isInside    =   polygon.contains(point);

                //
                if(isInside) {

                    return true
                }
            }

            return false
        },

        $checkIfClientsInsideTheZone(clients, user_territories) {

            let clients_result  =   []

            for (let index = 0; index < clients.length; index++) {

                //
                let point_is_inside_user_polygons  =   true; //this.$checkMarkerInsideUserPolygonsWithoutMap(clients[index].Latitude, clients[index].Longitude, user_territories)

                //
                if(point_is_inside_user_polygons) {

                    clients_result.push(clients[index])
                }
            }

            //
            return clients_result
        },

        //

        $hideTerritores() {

            if(window.navigator.onLine) {

                // Clear Markers
                this.territories.forEach(territory => {

                    this.show_map.removeLayer(territory)
                });
            }
        },

        $showTerritories(territories) {

            if(window.navigator.onLine) {

                if(territories) {
                    for (let i = 0; i < territories.length; i++) {

                        var latlngs     =   JSON.parse(territories[i].latlngs)

                        if(Array.isArray(latlngs) && (latlngs.length > 0)) {

                            // Territory
                            var territory           =   L.polygon(latlngs, { color: territories[i].color }).addTo(this.show_map)
                            this.territories.push(territory)
                        }          
                    }
                }
            }
        },

        //

        $plusSlides(slide_number, slideIndex) {

            return this.$showSlides(slide_number, slideIndex);
        },

        $currentSlide(current_slide, slideIndex) {

            return this.$showSlides(current_slide, slideIndex);
        },

        $showSlides(current_slide, slideIndex) {

            let i           =   0;

            let slides  =   document.getElementsByClassName("mySlides");
            // let dots    =   document.getElementsByClassName("dot");

            if (current_slide > slides.length) {

                slideIndex = 1
            }

            if (current_slide < 1) {

                slideIndex = slides.length
            }

            for (i = 0; i < slides.length; i++) {

                slides[i].style.display = "none";  
            }

            // for (i = 0; i < dots.length; i++) {

            //     dots[i].className               =   dots[i].className.replace(" active", "");
            // }

            slides[slideIndex-1].style.display  =   "block";  
            // dots[slideIndex-1].className        +=  " active";

            return slideIndex
        },

        //

        $exportChart(chart, chart_name) {

            //
            var a       =   document.createElement('a');
            a.href      =   chart.toBase64Image();
            a.download  =   chart_name+'.png';

            // Trigger the download
            a.click();
        },

        $exportTable(table_id, table_name) {

            /* Get table element */
            var table   =   document.getElementById(table_id);

            /* Convert table to worksheet */
            var ws      =   XLSX.utils.table_to_sheet(table);

            /* Create workbook and add the worksheet */
            var wb      =   XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

            /* Save workbook to file */
            var filename = table_name+".xlsx";
            XLSX.writeFile(wb, filename);
        },

        //

        $canvasToImage(canvas) {

            const img   = new Image();
            img.src     = canvas.toDataURL("image/png");
            return img;
        },

        $downloadExcelFile(data, filename) {
        
            const blob  = new Blob([data], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            const url   = URL.createObjectURL(blob);
            const a     = document.createElement("a");
            a.href      = url;
            a.download  = filename;
            a.click();
            URL.revokeObjectURL(url);
        },

        //

        $getArrayOfObjectsColumns(array) {

            let columns =   []

            if(array.length > 0) {

                const keys  =   Object.keys(array[0])

                for (let index = 0; index < keys.length; index++) {

                    columns.push({header: keys[index], key: keys[index], width: 10})
                }
            }

            return columns
        },

        //

        $setToDefaultFileInputs() {

            const file_inputs   =   document.querySelectorAll('input[type="file"]');

            file_inputs.forEach(function(file_input) {

                file_input.value    =   ""
            });
        },

        $setToDefaultImages() {

            const image_elements    =   document.querySelectorAll('img');

            image_elements.forEach(function(image_element) {

                image_element.src       =   ""
            });
        },

        //

        $clickFile(file_id) {

            document.getElementById(file_id).click()
        },

        //

        $isUppercase(str) {

            return [...str].every(char => {
                return !/[a-zA-ZÀ-ÿ]/.test(char) || char === char.toUpperCase();
            });
        },

        //

        $isValidForFileName(string) {

            // Define a regex pattern to allow only safe characters
            return !(/[\/\\:*?"<>|& ]/.test(string));
        },

        //

        $arrayToJson(arr, prefix) {

            let json_result     =   {};
            
            //
            arr.forEach((item, index) => {
                json_result[`${prefix}${index}`]   =   item;
            });

            //
            return json_result;
        },

        $arrayToString(arr, separator) {
            return arr.map(item => `"${item}"`).join(separator);
        },

        //

        $humanToISO(humanDate) {
        
            const [dayStr, monthName, yearStr]  =   humanDate.split(' ');
            const day                           =   dayStr.padStart(2, '0');
            const year                          =   yearStr;

            //
            const monthMap                      =   {
                January : '01', February    :'02', March        : '03'  ,
                April   : '04', May         :'05', June         : '06'  ,
                July    : '07', August      :'08', September    : '09'  ,
                October : '10', November    :'11', December     : '12'
            };

            //
            const month                         =   monthMap[monthName];

            if (!month) throw new Error(`Unknown month "${monthName}"`);

            return `${year}-${month}-${day}`;
        },

        //  //  //

        $magnify_old(imgID, zoom, zoomContainerID) {

            //
            let zoomContainer           =   document.getElementById(zoomContainerID)
            zoomContainer.style.display =   "block"

            //
            var img, glass, w, h, bw;
            img     =   document.getElementById(imgID);

            /* Create magnifier glass: */
            glass   =   document.createElement("DIV");
            glass.setAttribute("class", "img-magnifier-glass");

            /* Insert magnifier glass: */
            img.parentElement.insertBefore(glass, img);

            /* Set background properties for the magnifier glass: */
            glass.style.backgroundImage     =   "url('" + img.src + "')";
            glass.style.backgroundRepeat    =   "no-repeat";
            glass.style.backgroundSize      =   (img.width * zoom) + "px " + (img.height * zoom) + "px";
            bw                              =   3;
            w                               =   glass.offsetWidth / 2;
            h                               =   glass.offsetHeight / 2;

            /* Execute a function when someone moves the magnifier glass over the image: */
            glass.addEventListener("mousemove"  , moveMagnifier);
            img.addEventListener("mousemove"    , moveMagnifier);

            /*and also for touch screens:*/
            glass.addEventListener("touchmove"  , moveMagnifier);
            img.addEventListener("touchmove"    , moveMagnifier);

            function moveMagnifier(e) {

                var pos, x, y;

                /* Prevent any other actions that may occur when moving over the image */
                e.preventDefault();

                /* Get the cursor's x and y positions: */
                pos =   getCursorPos(e);
                x   =   pos.x;
                y   =   pos.y;

                /* Prevent the magnifier glass from being positioned outside the image: */
                if (x > img.width - (w / zoom)) {x = img.width - (w / zoom);}
                if (x < w / zoom) {x = w / zoom;}
                if (y > img.height - (h / zoom)) {y = img.height - (h / zoom);}
                if (y < h / zoom) {y = h / zoom;}

                /* Set the position of the magnifier glass: */
                glass.style.left    =   (x - w) + "px";
                glass.style.top     =   (y - h) + "px";

                /* Display what the magnifier glass "sees": */
                glass.style.backgroundPosition  =   "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
            }

            function getCursorPos(e) {
                var a, x = 0, y = 0;
                e   =   e || window.event;

                /* Get the x and y positions of the image: */
                a   =   img.getBoundingClientRect();

                /* Calculate the cursor's x and y coordinates, relative to the image: */
                x   =   e.pageX - a.left;
                y   =   e.pageY - a.top;

                /* Consider any page scrolling: */
                x   =   x - window.pageXOffset;
                y   =   y - window.pageYOffset;

                return {x : x, y : y};
            }
        },

        $magnify(imgID, zoom, zoomContainerID) {

            let zoomContainer           =   document.getElementById(zoomContainerID)
            zoomContainer.style.display =   "block"

            //

            var img, glass, w, h, bw;
            img = document.getElementById(imgID);

            /* Create magnifier glass: */
            glass = document.createElement("DIV");
            glass.setAttribute("class", "img-magnifier-glass");

            /* Insert magnifier glass: */
            img.parentElement.insertBefore(glass, img);

            /* Set background properties for the magnifier glass: */
            glass.style.backgroundImage = "url('" + img.src + "')";
            glass.style.backgroundRepeat = "no-repeat";
            glass.style.backgroundSize = (img.width * zoom) + "px " + (img.height * zoom) + "px";
            bw = 3;
            w = glass.offsetWidth / 2;
            h = glass.offsetHeight / 2;

            /* Execute a function when someone moves the magnifier glass over the image: */
            glass.addEventListener("mousemove", moveMagnifier);
            img.addEventListener("mousemove", moveMagnifier);

            /*and also for touch screens:*/
            glass.addEventListener("touchmove", moveMagnifier);
            img.addEventListener("touchmove", moveMagnifier);
            function moveMagnifier(e) {
                var pos, x, y;
                /* Prevent any other actions that may occur when moving over the image */
                e.preventDefault();
                /* Get the cursor's x and y positions: */
                pos = getCursorPos(e);
                x = pos.x;
                y = pos.y;
                /* Prevent the magnifier glass from being positioned outside the image: */
                if (x > img.width - (w / zoom)) {x = img.width - (w / zoom);}
                if (x < w / zoom) {x = w / zoom;}
                if (y > img.height - (h / zoom)) {y = img.height - (h / zoom);}
                if (y < h / zoom) {y = h / zoom;}
                /* Set the position of the magnifier glass: */
                glass.style.left = (x - w) + "px";
                glass.style.top = (y - h) + "px";
                /* Display what the magnifier glass "sees": */
                glass.style.backgroundPosition = "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
            }

            function getCursorPos(e) {
                var a, x = 0, y = 0;
                e = e || window.event;
                /* Get the x and y positions of the image: */
                a = img.getBoundingClientRect();
                /* Calculate the cursor's x and y coordinates, relative to the image: */
                x = e.pageX - a.left;
                y = e.pageY - a.top;
                /* Consider any page scrolling: */
                x = x - window.pageXOffset;
                y = y - window.pageYOffset;
                return {x : x, y : y};
            }
        },

        //  //  //

        $getCurrentTimeHMS() {

            const now       =   new Date();
            const hours     =   String(now.getHours()).padStart(2, '0');
            const minutes   =   String(now.getMinutes()).padStart(2, '0');
            const seconds   =   String(now.getSeconds()).padStart(2, '0');

            return `${hours}:${minutes}:${seconds}`;
        },

        //  //  //

        $chartRendered(callback) {
            return {
                id: 'afterChartRendered',
                // Change from 'afterInit' to 'afterRender'.
                // 'afterRender' fires after the initial draw, which is much safer.
                afterRender: (chart) => { 
                    // Optional: You can check if the chart is currently animating 
                    // if (chart.animating) { return; } 

                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            };
        },

        $splitOnSpaceChartJSLabels(arr) {
            if (!Array.isArray(arr)) return [];
            return arr.map(item => {
                const s = String(item ?? '');
                // regex: capture parenthesis groups or words
                const regex = /\([^()]*\)|[^\s()]+/g;
                const parts = [];
                let m;
                while ((m = regex.exec(s)) !== null) {
                    parts.push(m[0]);
                }
                // fallback to original string if nothing matched
                return parts.length ? parts : [s];
            });
        },

        $valueOnTopOfEachBarPlugin(id = 'value_on_top', format_billion_million = true, decimal_number = 2) {
            return {
                id: id,
                beforeDatasetsDraw(chart, args, options = {}) {
                    const ctx = chart.ctx;
                    ctx.save();

                    const opts = Object.assign({
                        format_billion_million: format_billion_million,
                        decimal_number: decimal_number,
                        // minimal slice angle (radians) to attempt drawing inside pie
                        minSliceAngleToDraw: 0.07 // ~4 degrees; tune if needed
                    }, options);

                    const fmt = (value) => {
                        if (value === null || value === undefined) return '';
                        const n = Number(value);
                        if (Number.isNaN(n)) return String(value);
                        const dec = Number.isInteger(opts.decimal_number) ? opts.decimal_number : 2;
                        if (opts.format_billion_million) {
                        if (Math.abs(n) >= 1e9) return (n / 1e9).toFixed(dec) + 'B';
                        if (Math.abs(n) >= 1e6) return (n / 1e6).toFixed(dec) + 'M';
                        if (Math.abs(n) >= 1e3) return (n / 1e3).toFixed(dec) + 'K';
                        return n.toFixed(dec).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                        } else {
                        return n.toFixed(dec).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                        }
                    };

                    // iterate datasets
                    chart.data.datasets.forEach((dataset, datasetIndex) => {
                        const meta = chart.getDatasetMeta(datasetIndex);
                        if (!meta || meta.hidden) return;

                        // pick a safe default color (string)
                        const datasetBg = dataset.backgroundColor;
                        const datasetBorder = dataset.borderColor;

                        // BAR case
                        if (meta.type === 'bar' || chart.config.type === 'bar') {
                            meta.data.forEach((barEl, i) => {
                                const value = dataset.data[i];
                                const text = fmt(value);
                                if (!text) return;
                                ctx.font = 'bold 12px sans-serif';
                                ctx.fillStyle = (Array.isArray(datasetBorder) ? (datasetBorder[i] || '#000') : (datasetBorder || '#000'));
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'bottom';
                                const x = (barEl.x !== undefined) ? barEl.x : (barEl.centerX ?? 0);
                                const y = (barEl.y !== undefined) ? barEl.y : (barEl.centerY ?? 0);
                                ctx.fillText(text, x, y - 6);
                            });
                        }
                        // LINE case
                        else if (meta.type === 'line' || chart.config.type === 'line') {
                            meta.data.forEach((pointEl, i) => {
                                const value = dataset.data[i];
                                const text = fmt(value);
                                if (!text) return;
                                ctx.font = 'bold 12px sans-serif';
                                ctx.fillStyle = (Array.isArray(datasetBorder) ? (datasetBorder[i] || '#000') : (datasetBorder || '#000'));
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'bottom';
                                const x = pointEl.x ?? (pointEl.cx ?? 0);
                                const y = pointEl.y ?? (pointEl.cy ?? 0);
                                ctx.fillText(text, x, y - 6);
                            });
                        }
                    });

                    ctx.restore();
                }
            };
        },

        $setDefaultColors(datasets, labels, chartType = 'bar') {
            const chartOptions = {
                barColors: [
                    'rgba(17, 141, 255, 0.6)', 'rgba(33, 155, 157, 0.6)', 'rgba(220, 53, 69, 0.6)', 'rgba(249, 168, 37, 0.6)',
                    'rgba(57, 73, 171, 0.6)', 'rgba(129, 119, 23, 0.6)', 'rgba(85, 139, 47, 0.6)', 'rgba(9, 113, 56, 0.6)',
                    'rgba(0, 96, 100, 0.6)', 'rgba(1, 87, 155, 0.6)', 'rgba(26, 35, 126, 0.6)', 'rgba(103, 58, 183, 0.6)',
                    'rgba(78, 52, 46, 0.6)', 'rgba(194, 24, 91, 0.6)', 'rgba(247, 0, 0, 0.6)', 'rgba(245, 124, 0, 0.6)',
                    'rgba(0, 0, 0, 0.6)', 'rgba(255, 234, 0, 0.6)', 'rgba(175, 180, 43, 0.6)', 'rgba(124, 179, 66, 0.6)',
                    'rgba(15, 157, 88, 0.6)', 'rgba(0, 151, 167, 0.6)', 'rgba(2, 136, 209, 0.6)', 'rgba(255, 214, 0, 0.6)',
                    'rgba(156, 39, 176, 0.6)', 'rgba(230, 81, 0, 0.6)', 'rgba(136, 14, 79, 0.6)', 'rgba(121, 85, 72, 0.6)',
                    'rgba(189, 189, 189, 0.6)', 'rgba(117, 117, 117, 0.6)', 'rgba(66, 66, 67, 0.6)', 'rgba(251, 192, 45, 0.6)',
                    'rgba(165, 39, 20, 0.6)'
                ],
                borderColor: [
                    'rgba(17, 141, 255, 0.8)', 'rgba(33, 155, 157, 0.8)', 'rgba(220, 53, 69, 0.8)', 'rgba(249, 168, 37, 0.8)',
                    'rgba(57, 73, 171, 0.8)', 'rgba(129, 119, 23, 0.8)', 'rgba(85, 139, 47, 0.8)', 'rgba(9, 113, 56, 0.8)',
                    'rgba(0, 96, 100, 0.8)', 'rgba(1, 87, 155, 0.8)', 'rgba(26, 35, 126, 0.8)', 'rgba(103, 58, 183, 0.8)',
                    'rgba(78, 52, 46, 0.8)', 'rgba(194, 24, 91, 0.8)', 'rgba(247, 0, 0, 0.8)', 'rgba(245, 124, 0, 0.8)',
                    'rgba(0, 0, 0, 0.8)', 'rgba(255, 234, 0, 0.8)', 'rgba(175, 180, 43, 0.8)', 'rgba(124, 179, 66, 0.8)',
                    'rgba(15, 157, 88, 0.8)', 'rgba(0, 151, 167, 0.8)', 'rgba(2, 136, 209, 0.8)', 'rgba(255, 214, 0, 0.8)',
                    'rgba(156, 39, 176, 0.8)', 'rgba(230, 81, 0, 0.8)', 'rgba(136, 14, 79, 0.8)', 'rgba(121, 85, 72, 0.8)',
                    'rgba(189, 189, 189, 0.8)', 'rgba(117, 117, 117, 0.8)', 'rgba(66, 66, 67, 0.8)', 'rgba(251, 192, 45, 0.8)',
                    'rgba(165, 39, 20, 0.8)'
                ]
            };

            const isPieLike = (chartType === 'pie' || chartType === 'doughnut');

            datasets.forEach((dataset, index) => {
                // --- SCENARIO 1: Pie/Doughnut Chart (Rainbow Slices) ---
                if (isPieLike) {
                    // We need an array of colors, one for every slice (label)
                    const bgColors = [];
                    const bdColors = [];
                    for (let i = 0; i < labels.length; i++) {
                        bgColors.push(chartOptions.barColors[i % chartOptions.barColors.length]);
                        bdColors.push(chartOptions.borderColor[i % chartOptions.borderColor.length]);
                    }
                    dataset.backgroundColor = bgColors;
                    dataset.borderColor = bdColors;
                    dataset.borderWidth = 2;
                } 
                // --- SCENARIO 2: Bar/Line Chart (Distinct Color per Dataset) ---
                else {
                    // We need one single color for the whole dataset
                    const color = chartOptions.barColors[index % chartOptions.barColors.length];
                    const border = chartOptions.borderColor[index % chartOptions.borderColor.length];
                    
                    dataset.backgroundColor = color;
                    dataset.borderColor = border;
                    dataset.borderWidth = 1;
                }
            });

            return datasets;
        },

        $colorForIndex(col, idx, fallback = 'rgba(0,0,0,0.1)') {
            if (Array.isArray(col)) return col[idx] ?? fallback;
            return col ?? fallback;
        }

    }
}