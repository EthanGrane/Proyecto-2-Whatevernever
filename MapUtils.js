
let map = null;

function InitializeMap(centerLngLon) {
    const mapStyle = "mapbox://styles/ethangrane/cm5r25hne00ka01plf02k59lw";

    // Render Map on div
    map = new mapboxgl.Map({
        container: 'map',
        style: mapStyle,
        projection: 'globe',
        zoom: 5,
        center: [centerLngLon.lon, centerLngLon.lng],
    });

    // Hide Controls
    map.addControl(new mapboxgl.NavigationControl({
        showCompass: false,
        showZoom: false,
        visualizePitch: false
    }));

    // Disable some Intreaction Handles
    map['dragRotate'].disable();

    return map;
}

function AddMarkerToMap(lng, lat, map, popupText = "") 
{
    new mapboxgl.Marker()
        .setLngLat([lng, lat])
        .addTo(map)
        .setPopup(new mapboxgl.Popup().setHTML(`<p>${popupText}</p>`));
}

function GetMap()
{
    return map;
}