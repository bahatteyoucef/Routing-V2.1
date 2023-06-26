// 

import store    from    "../store/store"

export default class Map {
    
    constructor() {

        this.marker_cluster_mode                            =   "cluster"

        //

        this.map                                            =   null
        this.titleLayer                                     =   null

        this.distributeur                                   =   null

        this.WareHouses                                     =   []

        this.markers_mode_array_markers                     =   {}
        this.markers_mode_array                             =   []

        this.markers                                        =   {}
        this.clusters                                       =   []

        this.journey_plan_territories                       =   []
        this.journee_territories                            =   []

        this.draw_plugin_options                            =   []
        this.draw_control                                   =   null
        this.editable_layers                                =   new L.FeatureGroup()

        this.draw_plugin_journey_plan_territory_options     =   []
        this.draw_control_journey_plan_territory            =   null
        this.editable_layers_journey_plan_territory         =   new L.FeatureGroup()

        this.path                                           =   null

        this.colors                                         =   [   '#00CCFF', '#6ECC39', '#F0C20C', '#F1D3B7', '#FF0066', '#FF99CC', '#CC99FF', '#F0C2DC', 
                                                                    '#33CCFF', '#6ECCB9', '#F0C200', '#9933FF', '#F1D357', '#FF3399', '#F1D3F7', '#F180B7',
                                                                    '#33FFFF', '#66FF99', '#F18E17', '#9900CC', '#FFCC00', '#FF6699', '#F180C7', '#F180E7', 
                                                                    '#99CCFF', '#99FF99', '#F18017', '#F1D3D3', '#FFCC99', '#FD9CE3', '#FF9966', '#FF6600', 
                                                                    '#99FFFF', '#99FFCC', '#F18417', '#FD9C73', '#CCFF00', '#FF33CC', '#B5E28C', '#B5E2FC'  ];

    }

    // Map

    $createMap() {

        // Create Map
        this.map        =   L.map('map').setView(["31.26456666666666", "2.7863816666666663"], 5);

        // TitleLayer
        this.titleLayer =   L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(this.map);

        // Add Journey Plan Territory
        this.$drawJourneyPlanTerritory()

        // Add Draw Function
        this.$drawFonction()

        // Add Events
        this.$drawSelection()
    }

    $destroyMap() {

        // Destroy Map
        if (this.map && this.map.remove) {
            this.map.off();
            this.map.remove();
        }   
    }

    // Change Mode

    $switchMarkerClusterMode(new_mode) {

        this.marker_cluster_mode    =   new_mode
    }

    // Show Markers 

    $setRouteMarkers(clients, JPID, color) {

        if(this.marker_cluster_mode     ==  "cluster") {

            // Show Markers
            this.$showClusters(JPID, color)        

            // Add Markers
            this.$addRouteMarkers(clients, JPID, color)
        }

        if(this.marker_cluster_mode     ==  "marker") {

            // Show Markers
            this.$showMarkersMode(clients, JPID, color)        

            // Add Markers
            this.$addRouteDescriptionMarkersMode(clients, JPID, color)
        }
    }

    // Show Markers Mode

    $showMarkersMode(clients, JPID, color) {

        this.markers_mode_array[JPID]   =   []

        var icon                        =   this.$setMarkerIconMarkerMode(JPID, color) 

        // Add Markers
        for (let i = 0; i < clients.length; i++) {
            
            if(((clients[i].Latitude != null)&&(clients[i].Longitude != null))&&(!isNaN(clients[i].Latitude)&&(!isNaN(clients[i].Longitude)))) {
                // Add Marker
                this.$addRouteMarkerMode(clients[i], clients[i].Latitude, clients[i].Longitude, JPID, icon)
            }
        }
    }

    $addRouteMarkerMode(client, Latitude, Longitude, JPID, icon) {

        // Add Marker
        let marker  =   L.marker([Latitude, Longitude], { icon : icon })
        marker.addTo(this.map)

        if(typeof this.markers_mode_array_markers[client.JPlan]    ==  "undefined") {

            this.markers_mode_array_markers[client.JPlan]  =   []
            this.markers_mode_array_markers[client.JPlan].push(marker.getLatLng())
        }

        else {

            this.markers_mode_array_markers[client.JPlan].push(marker.getLatLng())
        }

        this.markers_mode_array[JPID].push(marker)
    }

    $addRouteDescriptionMarkersMode(clients, JPID) {

        let marker  =   null

        if(this.markers_mode_array[JPID]) {

            for (let i = 0; i < this.markers_mode_array[JPID].length; i++) {
                
                // Add Description
                marker  =   this.markers_mode_array[JPID][i].bindTooltip(
                    "CustomerCode   : "     +clients[i].CustomerCode    +"<br />"+
                    "CustomerNameE  : "     +clients[i].CustomerNameE   +"<br />"+
                    "CustomerType   : "     +clients[i].CustomerType    +"<br />"+
                    "JPlan          : "     +clients[i].JPlan           +"<br />"+
                    "Journee        : "     +clients[i].Journee         +"<br />"+
                    "Tel            : "     +clients[i].Tel             +"<br />"+
                    "DistrictName   : "     +clients[i].DistrictNameE   +"<br />"+
                    "CityName       : "     +clients[i].CityNameE       +"<br />"+
                    "Address        : "     +clients[i].Address         +"<br />"+
                    "Latitude       : "     +clients[i].Latitude        +"<br />"+
                    "Longitude      : "     +clients[i].Longitude           
                )

                marker.client    =   clients[i];

                marker.on('click',(event)   => {
                    
                    this.$updateModalClient(event.target.client)
                });
            }
        }
    }

    $setMarkerIconMarkerMode(JPID, color) {

        let icon        =   null

        icon    =   new L.Icon({
            iconUrl  : '/images/'+color.substring(1)+'.png'
        });

        return icon
    }

    //  Show Cluster Mode

    $addRouteMarkers(clients, JPID, color) {

        var icon                            =   this.$setMarkerIcon(JPID, color) 

        // Add Markers
        for (let i = 0; i < clients.length; i++) {
            
            if(((clients[i].Latitude != null)&&(clients[i].Longitude != null))&&(!isNaN(clients[i].Latitude)&&(!isNaN(clients[i].Longitude)))) {

                // Add Marker
                this.$addRouteMarker(clients, i, JPID, icon)
            }
        }
    }

    $addRouteMarker(clients, i, JPID, icon) {

        // Add Marker
        let marker          =   L.marker([clients[i].Latitude, clients[i].Longitude], { icon : icon })

        if(typeof this.markers[clients[i].JPlan]    ==  "undefined") {

            this.markers[clients[i].JPlan]  =   []
            this.markers[clients[i].JPlan].push(marker.getLatLng())
        }

        else {

            this.markers[clients[i].JPlan].push(marker.getLatLng())
        }

        // Add Description
        this.$addRouteDescription(clients, i, marker)

        // Add Marker
        this.clusters[JPID].addLayer(marker)
    }

    $addRouteDescription(clients, i, marker) {

        let marker_obj  =   null

        // Add Description
        marker_obj      =   marker.bindTooltip(
            "CustomerCode   : "     +clients[i].CustomerCode      +"<br />"+
            "CustomerNameE  : "     +clients[i].CustomerNameE   +"<br />"+
            "CustomerType   : "     +clients[i].CustomerType    +"<br />"+
            "JPlan          : "     +clients[i].JPlan           +"<br />"+
            "Journee        : "     +clients[i].Journee         +"<br />"+
            "Tel            : "     +clients[i].Tel             +"<br />"+
            "DistrictName   : "     +clients[i].DistrictNameE   +"<br />"+
            "CityName       : "     +clients[i].CityNameE       +"<br />"+
            "Address        : "     +clients[i].Address         +"<br />"+
            "Latitude       : "     +clients[i].Latitude        +"<br />"+
            "Longitude      : "     +clients[i].Longitude           
        )

        // Affect Client to marker
        marker_obj.client    =   clients[i];

        // add Event
        marker_obj.on('click',(event)   => {
            
            this.$updateModalClient(event.target.client)
        });
    }

    $setMarkerIcon(JPID, color) {

        let icon        =   null

        icon    =   new L.Icon({
            iconUrl  : '/images/'+color.substring(1)+'.png'
        });

        return icon
    }

    $showClusters(JPID, color) {

        this.clusters[JPID]             =   L.markerClusterGroup({
    
            iconCreateFunction: (cluster)   =>  {
    
                var childCount = cluster.getChildCount();
                // var c = 'marker-cluster-';
                var c = 'marker-cluster-custom-';

                if (childCount < 10) {
                    c += 'small';
                } 

                else if (childCount < 100) {
                    c += 'medium';
                } 

                else {
                    c += 'large';
                }

                // 

                var div                     =   document.createElement("div");

                let color_index             =   JPID % 40

                div.style.backgroundColor   =   color
                div.style.border            =   "3px solid #FFFFFF"
                div.style.borderRadius      =   "50%"

                div.innerHTML               =   "<span>" + childCount + "</span>"

                // 

                return new L.DivIcon({ html: div, className: 'marker-cluster ' + c, iconSize: new L.Point(50, 50) });
            }
        })

        this.map.addLayer(this.clusters[JPID])
    }

    $focusWareHouseMarkers() {

        // Create a marker group
        var markers = L.featureGroup();

        // Add markers to the marker group
        var markerArray = []  

        this.clusters.forEach((cluster) => {

            cluster.eachLayer((marker)  =>  {

                markerArray.push(marker.addTo(markers))
            });
        });

        // Set WareHouses
        this.WareHouses.forEach(WareHouse => {

            markerArray.push(WareHouse.addTo(markers))
        });

        if((this.clusters.length > 0)&&(this.WareHouses.length > 0)) {

            // Get the bounds of all the markers
            var groupBounds = markers.getBounds();

            // Zoom the map to fit the bounds of the markers
            this.map.fitBounds(groupBounds);
        }
    }

    $focuseMarkers() {

        if(this.marker_cluster_mode ==  "cluster") {

            this.$focuseMarkersClusterMode()
        }

        if(this.marker_cluster_mode ==  "marker") {

            this.$focuseMarkersMode()
        }
    }

    $focuseMarkersClusterMode() {

        // Create a marker group
        var markers = L.featureGroup();

        // Add markers to the marker group
        var markerArray = []  

        this.clusters.forEach((cluster) => {

            cluster.eachLayer((marker)  =>  {

                markerArray.push(marker.addTo(markers))
            });
        });

        if(markerArray.length > 0) {

            if(this.clusters.length > 0) {

                // Get the bounds of all the markers
                var groupBounds = markers.getBounds();

                // Zoom the map to fit the bounds of the markers
                this.map.fitBounds(groupBounds);
            }
        }
    }

    $focuseMarkersMode() {

        // Create a marker group
        var markers = L.featureGroup();

        // Add markers to the marker group
        var markerArray = []  

        // Set Markers
        this.markers_mode_array.forEach(marker => {
            
            for (let i = 0; i < marker.length; i++) {
                
                markerArray.push(marker[i].addTo(markers))
            }
        });

        // Add the marker group to the map
        this.map.addLayer(markers);

        // Get the bounds of all the markers
        var groupBounds = markers.getBounds();

        // Zoom the map to fit the bounds of the markers
        this.map.fitBounds(groupBounds);
    }

    // Remove Markers

    $unsetRouteMarkers(JPID) {

        // Clear Route Markers
        this.$clearRouteData(JPID)
    }

    $clearRouteMarkers() {

        // Clear Markers
        this.clusters.forEach(cluster => {
                            
            this.map.removeLayer(cluster)
        });

        this.clusters   =   []
        this.markers    =   {}

        //

        console.log(this.markers_mode_array)

        //

        // Clear Markers
        this.markers_mode_array.forEach(marker => {
            
            for (let i = 0; i < marker.length; i++) {
                
                this.map.removeLayer(marker[i])
            }
        });

        this.markers_mode_array             =   []
        this.markers_mode_array_markers     =   []
    }

    $clearRouteData(JPID) {

        // Clear Markers
        if(this.clusters[JPID]) {

            this.map.removeLayer(this.clusters[JPID])
        }

        // Clear Route Data
        this.clusters[JPID] =   []

        // Clear Markers
        if(this.markers_mode_array[JPID]) {

            for (let i = 0; i < this.markers_mode_array[JPID].length; i++) {
                
                this.map.removeLayer(this.markers_mode_array[JPID][i])
            }
        }

        // Clear Route Data
        this.markers_mode_array[JPID]    =   []
    }

    $clearPath() {

        try {

            this.map.removeLayer(this.path)
            this.path.remove()

            this.path   =   null

        }catch(e) {

        }
    }

    // JPlan Territories

    $showTerritories() {

        if(this.marker_cluster_mode ==  "cluster") {

            this.$showTerritoriesClusterMode()
        }

        if(this.marker_cluster_mode ==  "marker") {

            this.$showTerritoriesMarkerMode()
        }
    }

    $showTerritoriesClusterMode() {

        // Hide
        this.$hideTerritores()

        // Show
        for (const [key, value] of Object.entries(this.markers)) {

            if(this.markers[key].length >   0) {

                // Territory Latitude Longitude
                let territoryLatLngs    =   this.$getTerritoryLatLngs(this.markers[key])
                console.log(territoryLatLngs)

                // Territory
                var territory           =   L.polygon(territoryLatLngs, { color: 'black' }).addTo(this.map)
                this.journey_plan_territories.push(territory)

                this.editable_layers_journey_plan_territory.addLayer(territory)

                // Add Event Edit
                territory.on("edit", function(event) {
                    console.log("red layer edited !");
                })

                // Add Event Click
                territory.on("click", (event)   => {
                    this.$addTerritory(event)
                })
            }
        }
    }

    $showTerritoriesMarkerMode() {

        // Hide
        this.$hideTerritores()

        // Show
        for (const [key, value] of Object.entries(this.markers_mode_array_markers)) {

            if(this.markers_mode_array_markers[key].length >   0) {

                // Territory Latitude Longitude
                let territoryLatLngs    =   this.$getTerritoryLatLngs(this.markers_mode_array_markers[key])
                console.log(territoryLatLngs)

                // Territory
                var territory           =   L.polygon(territoryLatLngs, { color: 'black' }).addTo(this.map)
                this.journey_plan_territories.push(territory)

                this.editable_layers_journey_plan_territory.addLayer(territory)

                // Add Event Edit
                territory.on("edit", function(event) {
                    console.log("red layer edited !");
                })

                // Add Event Click
                territory.on("click", (event)   => {
                    this.$addTerritory(event)
                })
            }
        }
    }

    $showJPlanBDTerritories(territories) {

        // Hide
        this.$hideTerritores()

        // Show

        for (let i = 0; i < territories.length; i++) {

            var latlngs =   JSON.parse(territories[i].latlngs)

            if(Array.isArray(latlngs) && (latlngs.length > 0)) {

                console.log(latlngs)
                console.log(territories[i].color)

                // Territory
                var territory           =   L.polygon(latlngs, { color: territories[i].color }).addTo(this.map)
                this.journey_plan_territories.push(territory)

                // Add to Editable Layers by Right Drawing tool
                this.editable_layers_journey_plan_territory.addLayer(territory)

                // Add Description 
                let territory_obj             =   territory.bindTooltip("JPlan   : " + territories[i].JPlan)

                territory_obj.journey_plan    =   territories[i]

                // Add Event Edit
                territory_obj.on("edit", function(event) {
                    console.log("red layer edited !");
                })

                // Add Event Click
                territory_obj.on("click", (event)   => {
                    this.$addJourneyPlanTerritory(event)
                })    
            }          
        }
    }

    $hideTerritores() {

        // Clear Markers
        this.journey_plan_territories.forEach(territory => {

            this.map.removeLayer(territory)
        });
    }

    $getTerritoryLatLngs(markers) {

        console.log(markers)

        // 
        let territoryLatLngs    =   []
        let markerPassed        =   []

        // Check if their are no added markers
        let still_existe_markers    =   false

        // take the furthest marker first
        markers.sort((a, b) => this.$setDistanceStraight(0, 0, a.lat, a.lng) - this.$setDistanceStraight(0, 0, b.lat, b.lng))

        // Add First Marker
        territoryLatLngs.push([markers[0].lat, markers[0].lng])
        markerPassed.push({lat : markers[0].lat, lng : markers[0].lng})
        let marker_tempo            =   markers[0]

        for (var i = 0; i < markers.length; i++) {

            still_existe_markers    =   false

            markers.sort((a, b) => this.$setDistanceStraight(marker_tempo.lat, marker_tempo.lng, a.lat, a.lng) - this.$setDistanceStraight(marker_tempo.lat, marker_tempo.lng, b.lat, b.lng))

            for (let j = 0; j < markers.length; j++) {

                if(!this.$latlngExiste(markerPassed, markers[j].lat, markers[j].lng)) {

                    territoryLatLngs.push([markers[j].lat, markers[j].lng])
                    markerPassed.push({lat : markers[j].lat, lng : markers[j].lng})

                    marker_tempo            =   markers[j]
                    still_existe_markers    =   true

                    break
                }                
            }

            if(!still_existe_markers) {

                break;
            }
        }

        return territoryLatLngs;
    }

    $latlngExiste(markers, lat, lng) {

        for (let i = 0; i < markers.length; i++) {

            if((markers[i].lat   ==  lat)&&(markers[i].lng   ==  lng)) {

                return true
            }            
        }

        return false
    }

    // Journee Territories

    $showJourneeBDTerritories(territories) {

        // Hide
        this.$hideJourneeTerritores()

        // Show
        for (let i = 0; i < territories.length; i++) {

            var latlngs =   JSON.parse(territories[i].latlngs)

            if(Array.isArray(latlngs) && (latlngs.length > 0)) {

                console.log(latlngs)
                console.log(territories[i].color)

                // Territory
                var territory           =   L.polygon(latlngs, { color: territories[i].color }).addTo(this.map)
                this.journee_territories.push(territory)

                // Add to Editable Layers by Right Drawing tool
                this.editable_layers_journey_plan_territory.addLayer(territory)

                // Add Description 
                let territory_obj           =   territory.bindTooltip(
                    "JPlan      : "     +territories[i].JPlan       +"<br />"+
                    "Journee    : "     +territories[i].Journee     +"<br />"
                )

                territory_obj.journee       =   territories[i]

                // Add Event Edit
                territory_obj.on("edit", function(event) {
                    console.log("red layer edited !");
                })

                // Add Event Click
                territory_obj.on("click", (event)   => {
                    this.$addJourneeTerritory(event)
                })    
            }          
        }
    }

    $hideJourneeTerritores() {

        // Clear Markers
        this.journee_territories.forEach(territory => {

            this.map.removeLayer(territory)
        });
    }

    // Set Distance

    $setDistanceStraight(source_Latitude, source_Longitude, distination_Latitude, distination_Longitude) {

        let latlng1     =   null
        let latlng2     =   null

        let distance    =   null

        // Set Source
        latlng1         =   L.latLng(source_Latitude        , source_Longitude);

        // Set Distination
        latlng2         =   L.latLng(distination_Latitude   , distination_Longitude);

        // Calcule Distance
        distance        =   latlng1.distanceTo(latlng2) / 1000

        return parseFloat((Math.round(distance * 100) / 100).toFixed(2))    
    }

    // Draw Territory Journey Plan

    $drawJourneyPlanTerritory() {

        // Set Draw Options
        this.$drawJourneyPlanTerritoryOptions()
    }

    $drawJourneyPlanTerritoryOptions() {

        // Initialise the FeatureGroup to store editable layers
        this.map.addLayer(this.editable_layers_journey_plan_territory);

        this.draw_plugin_journey_plan_territory_options = {

            position: 'bottomright',

            draw: {
                polygon: {
                    shapeOptions: {
                        color: 'red',
                        fillOpacity: 0.5
                    }
                },

                polyline        : false ,
                circle          : false , 
                rectangle       : false ,
                marker          : false ,
                circlemarker    : false ,
            },

            edit: {
                featureGroup    : this.editable_layers_journey_plan_territory, //REQUIRED!!
                remove          : true
            }
        };

        // Initialise the Draw Control 
        this.draw_control_journey_plan_territory    =   new L.Control.Draw(this.draw_plugin_journey_plan_territory_options);
        this.map.addControl(this.draw_control_journey_plan_territory);
    }

    $setDrawJourneyPlanTerritory(event) {

        let layer   =   event.layer

        this.editable_layers_journey_plan_territory.addLayer(layer)
    }

    // Draw Functions

    $drawFonction() {

        // Set Draw Options
        this.$drawOptions()
    }

    $drawOptions() {

        // Initialise the FeatureGroup to store editable layers
        this.map.addLayer(this.editable_layers);

        this.draw_plugin_options = {

            position: 'bottomleft',

            draw: {
                polygon: {
                    shapeOptions: {
                        color: 'blue',
                        fillOpacity: 0.5
                    }
                },

                polyline        : false ,
                circle          : false , 
                rectangle       : false ,
                marker          : true  ,
                circlemarker    : false ,
            },

            edit: {
                featureGroup    : this.editable_layers, // REQUIRED!!
                edit            : false,                // Exclude the edit option
                remove          : true
            }
        };

        // Initialise the Draw Control 
        this.draw_control   =   new L.Control.Draw(this.draw_plugin_options);
        this.map.addControl(this.draw_control);
    }

    $removeDrawings() {

        // Clear Drawings
        this.editable_layers.eachLayer((layer)  => { 
    
            this.editable_layers.removeLayer(layer)
        })

        // Clear Drawing Data
        this.editable_layers    =   new L.FeatureGroup()
    }

    $setDraw(event) {

        let layer   =   event.layer

        this.editable_layers.addLayer(layer)
    }

    // Draw Events

    $drawSelection() {

        let clients_change_route    =   []

        this.map.on("draw:created", (event)   =>  {

            var layer   =   event.layer;
    
            if (layer instanceof L.Polygon) {

                // Change Route
                if (layer.options.color === 'blue') {

                    // Set Draw
                    this.$setDraw(event)

                    if(this.marker_cluster_mode     ==  "cluster") {

                        // Get Clients (Polygon)                    
                        clients_change_route    =   this.$getClientsFromSelection(event)
                    }

                    if(this.marker_cluster_mode     ==  "marker") {

                        // Get Clients (Polygon)                    
                        clients_change_route    =   this.$getClientsFromSelectionMarkersMode(event)
                    }

                    // Change Route
                    this.$updateModalClientsRoute(clients_change_route)

                    // Add Event
                    layer.on("edit", function(event) {
                        console.log("blue layer edited !");
                    })
                }

                // Journey Plan Territory
                if (layer.options.color !== 'blue') {

                    // 
                    this.$setDrawJourneyPlanTerritory(event)

                    // Add Event
                    layer.on("edit", (event)    =>  {
                        console.log("red layer edited !");
                    })

                    // Add Event Click
                    layer.on("click", (event)   =>  {
                        this.$addTerritory(event)
                    })
                } 
            }

            if(layer instanceof L.Marker) {

                // Set Draw
                this.$setDraw(event)

                // Add New Client
                this.$addModalClient(event)
            }

            //

        });   
    }

    // Left Bar Events

    $getClientsFromSelection(event) {

        let clients_change_route    =   []

        this.clusters.forEach(cluster => {

            cluster.eachLayer(marker => {

                if (this.$isMarkerInsidePolygon(marker, event)) {

                    clients_change_route.push(marker.client)
                }
            });
        });

        return clients_change_route
    }

    $getClientsFromSelectionMarkersMode(event) {

        let clients_change_route    =   []

        this.markers_mode_array.forEach(marker_route => {

            marker_route.forEach(marker => {

                if (this.$isMarkerInsidePolygon(marker, event)) {

                    clients_change_route.push(marker.client)
                }
            });
        });

        return clients_change_route
    }

    //

    $isMarkerInsidePolygon(marker, event) {

        return event.layer.contains(marker.getLatLng())
    }

    $updateModalClientsRoute(clients_change_route) {

        store.commit("client/setClientsChangeRoute" , clients_change_route)
    }

    $addModalClient(event) {

        store.commit("client/setAddClient"          ,  event.layer.getLatLng())
    }

    $updateModalClient(client) {

        store.commit("client/setUpdateClient"       , client)
    }

    // Right Bar Events

    $addJourneyPlanTerritory(event) {

        var latLngs = event.target.getLatLngs();
            
        // Extract the lat and lng values
        var latlngs = latLngs[0].map(function(latlng) {
            return [latlng.lat, latlng.lng];
        });

        //

        let journey_plan        =   event.target.journey_plan
        
        if(journey_plan) {

            journey_plan.latlngs    =   latlngs
        }

        //

        // Add Journey Plan
        store.commit("journey_plan/setAddJourneyPlan" ,  {"latlngs" : latlngs , "journey_plan" : journey_plan})
    }

    $addJourneeTerritory(event) {

        var latLngs = event.target.getLatLngs();
            
        // Extract the lat and lng values
        var latlngs = latLngs[0].map(function(latlng) {
            return [latlng.lat, latlng.lng];
        });

        //

        let journee        =   event.target.journee
        
        if(journee) {

            journee.latlngs    =   latlngs
        }

        //

        // Add Journey Plan
        store.commit("journey_plan/setAddJourneyPlan" ,  {"journee" : journee})
    }

    $addTerritory(event) {

        var latLngs = event.target.getLatLngs();
            
        // Extract the lat and lng values
        var latlngs = latLngs[0].map(function(latlng) {
            return [latlng.lat, latlng.lng];
        });

        //

        // Add Journey Plan
        store.commit("journey_plan/setAddJourneyPlan" ,  {"latlngs" : latlngs})
    }

    // 
}