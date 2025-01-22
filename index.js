friendsConnected = [
    { name: "Adrian", lon: 41.4113279581609, lng: 2.02690062977777 },
    { name: "Paul", lon: 40.4113279581609, lng: 2.02690062977777 },
    { name: "Holden", lon: 39.4113279581609, lng: 2.02690062977777 },
]

mapboxgl.accessToken = 'pk.eyJ1IjoiZXRoYW5ncmFuZSIsImEiOiJjbTVyMWNsZDAwNmNsMnFxdTl5enQ2dXAxIn0.gCn0a-Ef8cuqw1pEozCo0Q';
window.onload = () => 
{
    InitializeMap({ lng: 41.4113279581609, lon: 2.02690062977777 });
}

function InitializeMap(currentPosition) 
{
    const mapStyle = "mapbox://styles/ethangrane/cm5r25hne00ka01plf02k59lw";

    // Render Map on div
    const map = new mapboxgl.Map({
        container: 'map',
        style: mapStyle,
        projection: 'globe',
        zoom: 5,
        center: [currentPosition.lon, currentPosition.lng],
    });

    // Hide Controls
    map.addControl(new mapboxgl.NavigationControl({
        showCompass: false,
        showZoom: false,
        visualizePitch: false
    }));

    // Disable some Intreaction Handles
    map['dragRotate'].disable();

}

function MapStart() {

}