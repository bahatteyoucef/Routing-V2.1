// 

import store    from    "../store/store"

export default class Map {
    
    constructor() {

        //
        this.left_tools                                     =   false
        this.right_tools                                    =   false

        // Mode
        this.marker_cluster_mode                            =   "cluster"

        // General
        this.map                                            =   null
        this.titleLayer                                     =   null

        // Clusters and Markers Mode
        this.markers_lat_lng                                =   {}          // used only to store getLatLngs
        this.clusters                                       =   {}          // used to store clusters
        this.markers                                        =   {}          // used to store markers in order to use markers functions for example : bindToolTip

        //
        this.markerGroups                                   =   {}
        this.canvasRenderer                                 =   L.canvas({ padding: 0.5 })

        // Icons
        this.markers_icons                                  =   {}
        this.clusters_icons                                 =   {}

        // Territories
        this.journey_plan_territories                       =   []
        this.journee_territories                            =   []
        this.user_territories                               =   []

        // Left Tools
        this.draw_plugin_options                            =   []
        this.draw_control                                   =   null
        this.editable_layers                                =   new L.FeatureGroup()

        // Right Tools
        this.draw_plugin_journey_plan_territory_options     =   []
        this.draw_control_journey_plan_territory            =   null
        this.editable_layers_journey_plan_territory         =   new L.FeatureGroup()

        // Path
        this.path                                           =   null

        // Colors
        this.colors                                         =   [       
                                                                    '#A52714'       , '#F9A825'     , '#3949AB'     , '#817717'     , '#558B2F'     , 
                                                                    '#097138'       , '#006064'     , '#01579B'     , '#1A237E'     , '#673AB7'     ,
                                                                    '#4E342E'       , '#C2185B'     , '#FF5252'     , '#F57C00'     , '#000000'     ,
                                                                    '#FFEA00'       , '#AFB42B'     , '#7CB342'     , '#0F9D58'     , '#0097A7'     ,
                                                                    '#0288D1'       , '#FFD600'     , '#9C27B0'     , '#E65100'     , '#880E4F'     ,
                                                                    '#795548'       , '#BDBDBD'     , '#757575'     , '#424243'     , '#FBC02D'     
                                                                ]

        // KML Drawings
        this.kml_willayas                                   =   []
        this.kml_layers                                     =   []

        // FrontOffice Marker
        this.user_latitude                                  =   0
        this.user_longitude                                 =   0
        this.user_marker                                    =   null

        // User Role
        this.user_role                                      =   null
    }

    //  //  Create/Destroy Map  //  //

    $createMap(role, map_id, left_tools = true, right_tools = true, marker_cluster_mode = 'cluster') {

        //
        this.left_tools     =   left_tools
        this.right_tools    =   right_tools

        //
        this.$destroyMap()

        // Create Map
        this.map            =   L.map(map_id, { zoomAnimation: true }).setView([31.26456666666666, 2.7863816666666663], 5);

        //

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

        // TitleLayer
        this.titleLayer     =   L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(this.map);

        // create & store a canvas renderer
        this.map.addLayer(this.canvasRenderer);

        // User Role
        this.user_role              =   role
        this.marker_cluster_mode    =   marker_cluster_mode

        if((this.user_role == "Super Admin")||(this.user_role   ==  "BU Manager")||(this.user_role == "BackOffice")) {

            if(right_tools) {

                // Add Journey Plan Territory
                this.$drawJourneyPlanTerritory()

                // 1) Before you add any territories:
                this.map.createPane('territoryPane');
                this.map.getPane('territoryPane').style.zIndex = 650;
            }

            if(left_tools) {

                // Add Draw Function
                this.$drawMultiUpdate()
            }

            if((right_tools)&&(left_tools)) {

                // Add Events
                this.$drawSelection()
            }
        }

        if((this.user_role == "FrontOffice")) {

            // setCurrentPosition
            this.$setCurrentPosition()
        }
    }

    $destroyMap() {

        // Destroy Map
        if (this.map && this.map.remove) {
            this.map.off();
            this.map.remove();

            //
            this.map        = null;
            this.titleLayer = null; // If you track the tileLayer instance

            // Reset collections of markers or other map-related data
            this.markers            = {};
            this.markers_lat_lng    = {};
            this.markers_icons      = {};
        }   
    }

    //  //  //  //  //  //  //  //  //


    //  //  Show Markers    //  //
 
    $setRouteMarkers(clients, group, color) {

        if(this.marker_cluster_mode     ==  "cluster") {

            // Add Cluster
            this.$addCluster(group, color)   

            //
            this.$addClusterMarkers(clients, group, color)
        }

        if(this.marker_cluster_mode     ==  "marker") {

            // Show Markers
            this.$showMarkersMode(clients, group, color)        
        }
    }

    //  //  //  //  //  //  //  //


    //  //  Show Cluster Mode   //  //

    $addCluster(group, color) {

        // 1. CHECK FOR COLOR CHANGE
        // If the group exists, check if the color stored on it matches the new color.
        if (this.clusters_icons[group]) {
            if (this.clusters_icons[group].options.customColor !== color) {
                // The color changed! Remove the old group entirely.
                if (this.map.hasLayer(this.clusters_icons[group])) {
                    this.map.removeLayer(this.clusters_icons[group]);
                }
                delete this.clusters_icons[group];
                delete this.clusters[group];
            }
        }

        // 2. CREATE GROUP (If it doesn't exist or was just deleted above)
        if (typeof this.clusters_icons[group] == "undefined") {

            this.clusters_icons[group] = L.markerClusterGroup({
                // Store the color in the options so we can compare it later (see step 1)
                customColor: color, 

                iconCreateFunction: (cluster) => {
                    var childCount = cluster.getChildCount();
                    var c = 'marker-cluster-custom-';

                    if (childCount < 10) { c += 'small'; } 
                    else if (childCount < 100) { c += 'medium'; } 
                    else { c += 'large'; }

                    var div = document.createElement("div");
                    
                    // This 'color' variable is now the NEW color because 
                    // we forced the recreation of this function
                    div.style.backgroundColor = color; 
                    div.style.border = "3px solid #FFFFFF";
                    div.style.color = "white";
                    div.style.borderRadius = "50%";
                    div.innerHTML = "<span>" + childCount + "</span>";

                    return new L.DivIcon({
                        html: div,
                        className: 'marker-cluster ' + c,
                        iconSize: new L.Point(50, 50)
                    });
                }
            });
            
            // Ensure the map actually adds the layer
            this.map.addLayer(this.clusters_icons[group]);
        }

        // Link references
        this.clusters[group] = this.clusters_icons[group];
    }

    $addClusterMarkers(clients, group, color) {

        //
        this.$setMarkerIcon(group, color) 

        //
        var icon                =   this.markers_icons[group]

        //
        const markers_to_add    =   []; // Array to collect markers before adding in batch
        let marker_tempo        =   null

        // Add Markers
        for (let i = 0; i < clients.length; i++) {
            
            const client    =   clients[i];

            // Robust coordinate validation and defaulting
            if (client.Latitude === null || client.Longitude === null || isNaN(client.Latitude) || isNaN(client.Longitude) || client.Latitude > 90 || client.Latitude < -90 || client.Longitude > 180 || client.Longitude < -180) {
                client.Latitude     =   0; // Default to 0,0 if invalid
                client.Longitude    =   0;
            }

            // Add Marker
            marker_tempo  =   this.$addRouteMarker(client, group, icon)
            markers_to_add.push(marker_tempo)
        }

        //
        if (this.clusters[group]) {
            this.clusters[group].addLayers(markers_to_add);
        }
    }

    //  //  //  //  //  //  //  //  //


    //  //  Show Markers Mode (Add)   //  //

    $showMarkersMode(clients, group, color) {

        // 1) filter/normalize coords
        const pts   =   clients
                            .map(c => ({
                                lat: +c.Latitude || 0,
                                lng: +c.Longitude || 0,
                                client: c,
                            }))
                            .filter(p => !isNaN(p.lat) && !isNaN(p.lng));

        // 2) remove any existing canvas markers for this group
        //    (we assume you never mix icons and canvas for one group)
        //    you could also keep a per-group layerGroup of circleMarkers
        if (!this.markerGroups[group]) { this.markerGroups[group]    =   L.layerGroup().addTo(this.map) }

        // 3) draw on canvas: create circleMarkers with the shared renderer
        const renderer      =   this.canvasRenderer;
        const layerGroup    =   this.markerGroups[group];

        pts.forEach(({ lat, lng, client }) => {

            const m = L.circleMarker([lat, lng], {
                renderer,               // ← draw into the shared canvas
                radius: 8,              // circle radius in pixels
                fillColor: color,       // your group-specific fill
                fillOpacity: 1,         // fully opaque fill
                stroke: true,           // enable the outline
                color: '#ffffff',     // stroke (border) color
                weight: 1,              // border width in pixels
            });

            m.client = client;

            // add your popup/click handlers as before:
            this.$addRouteDescription(client, m);

            this.markers[client.id]         =   { marker: m, group };
            this.markers_lat_lng[client.id] =   { lat, lng, group };
            layerGroup.addLayer(m);
        });
    }

    //  //  //  //  //  //  //  //  //


    //  //  Global CRUD Markers Functions   //  //

    $setMarkerIcon(group, color) {
        if (typeof this.markers_icons[group] === "undefined") {
            this.markers_icons[group] = new L.Icon({
                iconUrl: '/images/' + color.substring(1) + '.png',
                iconSize: [15, 15]
            });
        }
    }

    $addRouteMarker(client, group, icon) {

        // Add Marker
        let marker  =   L.marker([client.Latitude, client.Longitude], { icon : icon })

        //
        this.markers_lat_lng[client.id]     =   { lat : marker.getLatLng().lat, lng : marker.getLatLng().lng, group : group }
        this.markers[client.id]             =   { marker : marker, group : group }

        // Add Description
        this.$addRouteDescription(client, marker)

        //
        return marker
    }

    $addRouteDescription(client, marker) {

        // Store client data directly on the marker for easy access
        marker.client = client;

        // Use 'mouseover' and 'mouseout' to handle popup open/close
        // Lazy load popup content on 'mouseover' to avoid creating all popups upfront
        marker.on('mouseover', (e) => {
            // Only bind and open if the popup content hasn't been set yet
            // or if it's currently closed.
            if (!e.target._popup || !e.target._popup._content) {
                const popupContent = `
                    <b>Customer Code:</b> ${client.CustomerCode}<br />
                    <b>Customer Name:</b> ${client.CustomerNameE}<br />
                    <b>Customer Type:</b> ${client.CustomerType}<br />
                    <b>JPlan:</b> ${client.JPlan}<br />
                    <b>Journee:</b> ${client.Journee}<br />
                    <b>Tel:</b> ${client.Tel}<br />
                    <b>District:</b> ${client.DistrictNameE}<br />
                    <b>City:</b> ${client.CityNameE}<br />
                    <b>Address:</b> ${client.Address}<br />
                    <b>Latitude:</b> ${client.Latitude}<br />
                    <b>Longitude:</b> ${client.Longitude}
                `;
                e.target.bindPopup(popupContent, { autoPan: false, closeButton: false }).openPopup();
            } else {
                e.target.openPopup();
            }
        });

        marker.on('mouseout', (e) => {
        });

        // Add click event for modal update
        marker.on('click', (event) => {
            this.$updateModalClient(event.target.client);
        });
    }

    //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  Switch Mode //  //  //

    $switchMarkerClusterMode(new_mode) {

        this.marker_cluster_mode    =   new_mode
    }

    //  //  //  //  //  //  //  //  //


    //  //  Delete Markers    //   //  //

    $deleteRouteMarkers(clients) {

        clients.forEach(c => {
            const entry = this.markers[c.id];
            if (!entry) return;

            const { marker, group } = entry;

            if (this.marker_cluster_mode === 'cluster') {
                // remove from the cluster group
                const cg = this.clusters[group];

                if (cg) {
                    // remove the marker from the cluster
                    try { cg.removeLayer(marker); } catch (err) { /* ignore */ }

                    // if cluster group has no more child layers, remove & cleanup
                    try {
                        const remaining = (typeof cg.getLayers === 'function') ? cg.getLayers().length : 0;
                        if (remaining === 0) {
                            // clear layers defensively
                            try { if (typeof cg.clearLayers === 'function') cg.clearLayers(); } catch(e){}

                            // remove from map if present
                            if (this.map && typeof this.map.hasLayer === 'function' && this.map.hasLayer(cg)) {
                                try { this.map.removeLayer(cg); } catch (e) {}
                            }

                            // delete cluster references so next add recreates them with new color
                            delete this.clusters[group];

                            if (this.clusters_icons && this.clusters_icons[group]) {
                                delete this.clusters_icons[group];
                            }

                            // also delete cached icon for this group so marker icons are recreated
                            if (this.markers_icons && this.markers_icons[group]) {
                                delete this.markers_icons[group];
                            }
                        }
                    } 
                    
                    catch (err) {
                        console.warn('Error while cleaning cluster group', group, err);
                    }
                }
            } 

            else {
                // remove from the plain-marker FeatureGroup
                const lg = this.markerGroups[group];
                if (lg) {
                    try { lg.removeLayer(marker); } catch (err) { /* ignore */ }

                    // If empty, remove and cleanup
                    try {
                        const layerCount = lg._layers ? Object.keys(lg._layers).length : 0;
                        if (layerCount === 0) {
                            if (this.map && typeof this.map.hasLayer === 'function' && this.map.hasLayer(lg)) {
                            try { this.map.removeLayer(lg); } catch (e) {}
                            }
                            delete this.markerGroups[group];
                        }
                    } 
                    catch (err) {
                        console.warn('Error while cleaning marker group', group, err);
                    }

                    // delete markers_icons cache so icons will be recreated with new color
                    if (this.markers_icons && this.markers_icons[group]) {
                        delete this.markers_icons[group];
                    }
                }
            }

            // finally clean up your lookup entries for this marker
            delete this.markers[c.id];
            delete this.markers_lat_lng[c.id];
        });
    }

    //  //  //  //  //  //  //  //  //


    //  //  Focus Markers   //  //

    $focuseMarkers() {

        if(this.marker_cluster_mode ==  "cluster") {

            this.$focuseMarkersClusterMode()
        }

        if(this.marker_cluster_mode ==  "marker") {

            this.$focuseMarkersMode()
        }
    }

    $focuseMarkersClusterMode() {

        // gather every child marker from each cluster
        const allChildMarkers = [];
        Object.values(this.clusters).forEach(clusterGroup => {
                clusterGroup.getLayers().forEach(child => {
                allChildMarkers.push(child.getLatLng());
            });
        });

        if (allChildMarkers.length === 0) return;

        const bounds = L.latLngBounds(allChildMarkers);
        this.map.fitBounds(bounds);
    }

    $focuseMarkersMode() {

        const latlngs = Object.values(this.markers).map(({ marker }) =>
            marker.getLatLng()
        );

        if (!latlngs.length) return;
        this.map.fitBounds(L.latLngBounds(latlngs));
    }

    //  //  //  //  //  //  //  //


    //  //  Clear Markers/Path  //  //

    $getCurrentTimeHMS() {

        const now       =   new Date();
        const hours     =   String(now.getHours()).padStart(2, '0');
        const minutes   =   String(now.getMinutes()).padStart(2, '0');
        const seconds   =   String(now.getSeconds()).padStart(2, '0');

        return `${hours}:${minutes}:${seconds}`;
    }

    $clearRouteMarkers() {

        // clear clusters:
        Object.values(this.clusters).forEach(cg => cg.clearLayers());
    
        // clear all canvas groups:
        Object.values(this.markerGroups).forEach(lg => lg.clearLayers());
    
        // caches:
        this.clusters           =   {};
        this.markers            =   {};
        this.markers_lat_lng    =   {};
        this.markerGroups       =   {};
    }

    $clearPath() {

        this.map.removeLayer(this.path)
        this.path.remove()

        this.path   =   null
    }

    //  //  //  //  //  //  //  //  //


    //  //  (AUTO and JPLAN) Territories Functions  //  //

    $showTerritories() {

        // remove any existing territory polygons
        this.$hideTerritores();

        // decide whether we’re in cluster or plain-marker mode
        const groupSources = this.marker_cluster_mode === 'cluster'
            // cluster: each clusterGroup is an L.MarkerClusterGroup instance
            ? this.clusters
            // marker: each group is an L.LayerGroup of circleMarkers
            : this.markerGroups;

        // walk each group
        for (const [group, layerGroup] of Object.entries(groupSources)) {
            // get every child marker’s LatLng
            const pts = layerGroup.getLayers()
            .map(m => m.getLatLng())
            .filter(latlng => latlng);  // skip any weird nulls

            if (pts.length === 0) continue;

            // build the smallest territory hull around pts
            const coords = pts.map(p => [p.lat, p.lng]);
            let hullFeature;
            if (coords.length >= 3) {
            const turfPts = coords.map(c => turf.point(c));
            hullFeature    = turf.convex(turf.featureCollection(turfPts));
            } else if (coords.length === 2) {
            // add midpoint so turf.convex() returns a line‐buffered polygon
            const [a, b] = coords;
            coords.push([(a[0]+b[0])/2, (a[1]+b[1])/2]);
            hullFeature = turf.convex(turf.featureCollection(coords.map(c=>turf.point(c))));
            } else {
            // single point: buffer it
            hullFeature = turf.buffer(turf.point(coords[0]), 0.01, { units:'kilometers' });
            }

            // expand & convert back to Leaflet latlngs
            const expanded = turf.transformScale(hullFeature, 1.1);
            const latlngs  = expanded.geometry.coordinates[0].map(c => [c[0], c[1]]);

            // draw the polygon
            const poly = L.polygon(latlngs, { color: 'red' }).addTo(this.map);
            this.journey_plan_territories.push(poly);
            this.editable_layers_journey_plan_territory.addLayer(poly);

    // wire edit & click exactly as before
    poly.on('edit',  () => console.log('territory edited!'));
    poly.on('click', e => this.$addTerritory(e));
  }
    }

    $getTerritoryLatLngs(markers) {

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

    $showJPlanBDTerritories(territories) {

        // Hide
        this.$hideTerritores()

        // Show

        for (let i = 0; i < territories.length; i++) {

            var latlngs =   JSON.parse(territories[i].latlngs)

            if(Array.isArray(latlngs) && (latlngs.length > 0)) {

                // Territory
                var territory           =   L.polygon(latlngs, { pane: 'territoryPane', color: territories[i].color }).addTo(this.map)
                this.journey_plan_territories.push(territory)

                // Add to Editable Layers by Right Drawing tool
                this.editable_layers_journey_plan_territory.addLayer(territory)

                // Add Description 
                let territory_obj             =   territory.bindPopup(
                    "JPlan   : " + territories[i].JPlan,
                    { 
                        autoPan: false 
                    }       
                )

                // You would then need to handle opening the popup on hover and closing it on mouseout.
                territory_obj.on('mouseover', (e) => {
                    e.target.bringToFront();       // moves this polygon into the highest overlay
                    e.target.openPopup();
                });

                territory_obj.on('mouseout', (e) => {
                    // move it back behind the markers again:
                    this.map.getPane('territoryPane').style.zIndex = 550;
                });

                //
                territory_obj.journey_plan    =   territories[i]

                // Add Event Edit
                territory_obj.on("edit", function(event) {
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

    //  //  //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  //  Journee Territories //  //  //  //

    $showJourneeBDTerritories(territories) {

        // Hide
        this.$hideJourneeTerritores()

        // Show
        for (let i = 0; i < territories.length; i++) {

            var latlngs =   JSON.parse(territories[i].latlngs)

            if(Array.isArray(latlngs) && (latlngs.length > 0)) {

                // Territory
                var territory           =   L.polygon(latlngs, { pane: 'territoryPane', color: territories[i].color }).addTo(this.map)
                this.journee_territories.push(territory)

                // Add to Editable Layers by Right Drawing tool
                this.editable_layers_journey_plan_territory.addLayer(territory)

                // Add Description 
                let territory_obj           =   territory.bindPopup(
                    "JPlan      : "     +territories[i].JPlan       +"<br />"+
                    "Journee    : "     +territories[i].Journee     +"<br />",
                    { 
                        autoPan: false 
                    }       
                )

                // You would then need to handle opening the popup on hover and closing it on mouseout.
                territory_obj.on('mouseover', (e) => {
                    e.target.bringToFront();       // moves this polygon into the highest overlay
                    e.target.openPopup();
                });

                territory_obj.on('mouseout', (e) => {
                    // move it back behind the markers again:
                    this.map.getPane('territoryPane').style.zIndex = 550;
                });

                territory_obj.journee       =   territories[i]

                // Add Event Edit
                territory_obj.on("edit", function(event) {
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

    //  //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  //  User Territories    //  //  //  //

    $showUserBDTerritoriesFront(territories) {

        // Show
        for (let i = 0; i < territories.length; i++) {

            var latlngs     =   JSON.parse(territories[i].latlngs)

            if(Array.isArray(latlngs) && (latlngs.length > 0)) {

                // Territory
                var territory           =   L.polygon(latlngs, { pane: 'territoryPane', color: territories[i].color }).addTo(this.map)

                this.user_territories.push(territory)

                // Add to Editable Layers by Right Drawing tool
                this.editable_layers_journey_plan_territory.addLayer(territory)
            }          
        }
    }

    $showUserBDTerritories(territories) {

        // Hide
        this.$hideUserTerritores()

        // Show

        for (let i = 0; i < territories.length; i++) {

            var latlngs     =   JSON.parse(territories[i].latlngs)

            if(Array.isArray(latlngs) && (latlngs.length > 0)) {

                // Territory
                var territory           =   L.polygon(latlngs, { pane: 'territoryPane', color: territories[i].color }).addTo(this.map)
                this.user_territories.push(territory)

                // Add to Editable Layers by Right Drawing tool
                this.editable_layers_journey_plan_territory.addLayer(territory)

                // Add Description 
                let territory_obj       =   territory.bindPopup(
                    "Description    : "     +territories[i].description     +"<br />"+
                    "User           : "     +territories[i].user            +"<br />",
                    { 
                        autoPan: false 
                    }       
                )

                // You would then need to handle opening the popup on hover and closing it on mouseout.
                territory_obj.on('mouseover', (e) => {
                    e.target.bringToFront();       // moves this polygon into the highest overlay
                    e.target.openPopup();
                });

                territory_obj.on('mouseout', (e) => {
                    // move it back behind the markers again:
                    this.map.getPane('territoryPane').style.zIndex = 550;
                });

                territory_obj.user    =   territories[i]

                // Add Event Edit
                territory_obj.on("edit", function(event) {
                })

                // Add Event Click
                territory_obj.on("click", (event)   => {
                    
                    this.$addUserTerritory(event)
                })    
            }          
        }
    }

    $hideUserTerritores() {

        // Clear Markers
        this.user_territories.forEach(territory => {

            this.map.removeLayer(territory)
        });
    }

    //  //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  //  Global Territories Functions    //  //  //  //

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

    //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  //  Right Drawing Tools //  //  //  //

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

    //  //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  //  Left Drawing Tools  //  //  //  //

    $drawMultiUpdate() {

        // Set Draw Options
        this.$drawMultiUpdateOptions()
    }

    $drawMultiUpdateOptions() {

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
                edit            : true,                 // Include the edit option
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

    //  //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  Global Drawing Tools    //  //  //

    $setDraw(event) {

        let layer   =   event.layer

        this.editable_layers.addLayer(layer)
    }

    $drawSelection() {

        let clients_change_route    =   []

        this.map.on("draw:created", (event)   =>  {

            var layer   =   event.layer;

            if (layer instanceof L.Polygon) {

                // Change Route
                if (layer.options.color === 'blue') {

                    // Set Draw
                    this.$setDraw(event)
                    const layer     =   event.layer

                    // 2) define one small helper to re‐compute & send the selected clients
                    const updateSelection   =   () => {

                        if (this.marker_cluster_mode    === "cluster")  clients_change_route      =   this.$getClientsFromSelection({ layer })
                        if (this.marker_cluster_mode    === "marker")   clients_change_route      =   this.$getClientsFromSelectionMarkersMode({ layer })
                        
                        this.$updateModalClientsRoute(clients_change_route)
                    };

                    // 3) run it once immediately
                    updateSelection()

                    // 5) if you still need the click behavior, you can similarly re‐use it:
                    layer.on("click", (event)   => { updateSelection() })
                }

                // Journey Plan Territory
                if (layer.options.color !== 'blue') {

                    // 
                    this.$setDrawJourneyPlanTerritory(event)

                    // Add Event
                    layer.on("edit", (event)    =>  {
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

                // Click Event
                layer.on("click", ()    =>  {

                    // Add New Customer
                    this.$addModalClient(event)
                })
            }

            //

        });   
    }

    //  //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  //  Left Tools Events   //  //  //  //

    $updateModalClientsRoute(clients_change_route) {

        store.commit("client/setClientsChangeRoute" , clients_change_route)
    }

    $addModalClient(event) {

        store.commit("client/setAddClient"          ,  event.layer.getLatLng())
    }

    $updateModalClient(client) {

        store.commit("client/setUpdateClient"       , client)
    }

    //  //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  Polygon Drawing Tools Functions //  //  //

    $getClientsFromSelection(event) {

        let clients_change_route    =   []

        for (const [key, cluster] of Object.entries(this.clusters)) {

            cluster.eachLayer(marker => {

                if (this.$isMarkerInsidePolygon(marker, event)) {

                    clients_change_route.push(marker.client)
                }
            });
        };

        return clients_change_route
    }

    $getClientsFromSelectionMarkersMode(event) {

        let clients_change_route    =   []

        for (const [key, marker_route] of Object.entries(this.markers)) {

            if (this.$isMarkerInsidePolygon(marker_route.marker, event)) {

                clients_change_route.push(marker_route.marker.client)
            }
        };

        return clients_change_route
    }

    $isMarkerInsidePolygon(marker, event) {

        const poly = event.layer;
        const point = marker.getLatLng();
        
        // Retrieve the polygon's vertices (the outer ring)
        // Leaflet polygons are an array of arrays (to support holes), so we select [0]
        const polyPoints = poly.getLatLngs()[0]; 
        
        const x = point.lat;
        const y = point.lng;

        let inside = false;
        
        // Ray Casting Algorithm to check if point is inside polygon
        for (let i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) {
            const xi = polyPoints[i].lat, yi = polyPoints[i].lng;
            const xj = polyPoints[j].lat, yj = polyPoints[j].lng;

            const intersect = ((yi > y) != (yj > y))
                && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                
            if (intersect) inside = !inside;
        }

        return inside;
    }

    //  //  //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  //  Right Drawing Tools     //  //  //  //

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

    $addUserTerritory(event) {

        var latLngs = event.target.getLatLngs();
            
        // Extract the lat and lng values
        var latlngs = latLngs[0].map(function(latlng) {
            return [latlng.lat, latlng.lng];
        });

        //

        let user    =   event.target.user
        
        if(user) {

            user.latlngs    =   latlngs
        }

        //

        // Add User Territory
        store.commit("journey_plan/setAddJourneyPlan" ,  {"user" : user})
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

    //  //  //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  //  //  Show Position   //  //  //  //  //

    $setCurrentPosition() {

        if(this.user_role   ==  "FrontOffice") {

            // Define a custom icon for the user's position marker
            var customIcon = L.icon({
                iconUrl         : '/images/user_marker.png'     ,
                iconSize        : [35, 35]                      , // Set the size of the icon
                iconAnchor      : [17, 17]                      , // Set the anchor point of the icon (centered at the bottom)
            });

            // Create a marker with the custom icon
            this.user_marker = L.marker([0, 0], { icon: customIcon }).addTo(this.map);

            // 
            navigator.geolocation.watchPosition(()  =>  {
                this.$updateUserPosition();
            });
        }
    }

    $updateUserPosition() {

        navigator.geolocation.getCurrentPosition((position) =>  {

            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            this.user_latitude   =   lat
            this.user_longitude  =   lng

            // Update the marker's position
            this.user_marker.setLatLng([this.user_latitude, this.user_longitude]);
        },

            (error) => {
            
                console.error('Error getting geolocation:', error);
            },
        
            { 
                enableHighAccuracy  : true      ,
                maximumAge          : 100000    ,   // Maximum age of cached position in milliseconds
                timeout             : 95000         // Maximum time to wait for a new position in milliseconds
            }
        );
    }

    $showCurrentPosition() {

        // Get the bounds of all the markers
        // var groupBounds = this.user_marker.getBounds();

        // Zoom the map to fit the bounds of the markers
        this.map.setView([this.user_marker.getLatLng().lat, this.user_marker.getLatLng().lng], this.map.getZoom());
    }

    //  //  //  //  //  //  //  //  //  //  //  //  //  //


    //  //  //  //  //  KML Functions   //  //  //  //  //

    $setKMLLayers(kml_layers) {

        // Delete all kml layers
        this.kml_layers.forEach(kml_layer => {

            console.log(kml_layer)

            this.map.removeLayer(kml_layer);            
        })

        // Add all kml layers
        kml_layers.forEach(kml_layer => {
            
            console.log(kml_layer)

            // Add new layers
            this.kml_layers[kml_layer]   =   omnivore.kml('/kml/'+kml_layer+'.kml').addTo(this.map);
        });

        console.log(this.kml_layers)
    }

    //  //  //  //  //  //  //  //  //  //  //  //  //  //
}