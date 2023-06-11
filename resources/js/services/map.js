// 

import store    from    "../store/store"

export default class Map {
    
    constructor() {

        this.map                                            =   null
        this.titleLayer                                     =   null

        this.distributeur                                   =   null

        this.WareHouses                                     =   []

        this.clusters                                       =   []
        this.territories                                    =   []

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

        //

        // Load kml file
        // omnivore.kml('/kml/coca_oran/Commune.kml').addTo(this.map);

        //

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

    // Show Markers

    $setRouteMarkers(clients, JPID) {

        // Show Markers
        this.$showClusters(JPID)        

        // Add Markers
        this.$addRouteMarkers(clients, JPID)
    }

    $addRouteMarkers(clients, JPID) {

        var icon                            =   this.$setMarkerIcon(JPID) 

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
        let marker      =   L.marker([clients[i].Latitude, clients[i].Longitude], { icon : icon })

        // Add Description
        this.$addRouteDescription(clients, i, marker)

        // Add Marker
        this.clusters[JPID].addLayer(marker)
    }

    $addRouteDescription(clients, i, marker) {

        let marker_obj  =   null

        // Add Description
        marker_obj      =   marker.bindTooltip(
            "CustomerNo     : "     +clients[i].CustomerNo      +"<br />"+
            "CustomerNameE  : "     +clients[i].CustomerNameE   +"<br />"+
            "CustomerType   : "     +clients[i].CustomerType    +"<br />"+
            "JPlan          : "     +clients[i].JPlan           +"<br />"+
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

    $setMarkerIcon(JPID) {

        let icon        =   null
        let color_index =   JPID % 40

        icon    =   new L.Icon({
            iconUrl  : '/images/'+this.colors[color_index].substring(1)+'.png',
            shadowUrl: '/images/marker-shadow.png',
        });

        return icon
    }

    $showClusters(JPID) {

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

                div.style.backgroundColor   =   this.colors[color_index]
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
    }

    $clearRouteData(JPID) {

        // Clear Markers
        if(this.clusters[JPID]) {

            this.map.removeLayer(this.clusters[JPID])
        }

        // Clear Route Data
        this.clusters[JPID] =   []
    }

    $clearPath() {

        try {

            this.map.removeLayer(this.path)
            this.path.remove()

            this.path   =   null

        }catch(e) {

        }
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

                // Journey Plan Territory
                if (layer.options.color === 'red') {

                    // 
                    this.$setDrawJourneyPlanTerritory(event)

                    // Perform specific actions for the first polygon creation
                    this.$addJourneyPlanTerritory(event)

                    // Add Event
                    layer.on("edit", function(event) {
                        console.log("red layer edited !");
                    })

                } 
            }

            if (layer instanceof L.Polygon) {

                // Change Route
                if (layer.options.color === 'blue') {

                    // Set Draw
                    this.$setDraw(event)

                    // Get Clients (Polygon)                    
                    clients_change_route    =   this.$getClientsFromSelection(event)

                    // Change Route
                    this.$updateModalClientsRoute(clients_change_route)

                    // Add Event
                    layer.on("edit", function(event) {
                        console.log("blue layer edited !");
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

    $isMarkerInsidePolygon(marker, event) {

        return event.layer.contains(marker.getLatLng())
    }

    $updateModalClientsRoute(clients_change_route) {

        store.commit("client/setClientsChangeRoute"     , clients_change_route)
    }

    $addModalClient(event) {

        store.commit("client/setAddClient"          ,  event.layer.getLatLng())
    }

    $updateModalClient(client) {

        store.commit("client/setUpdateClient"   , client)
    }

    // 
}