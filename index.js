friendsConnected =
[
    { name: "Adrian", lng: 2.02690062977777, lat: 41.4113279581609 },
    { name: "Paul", lng: 3.02690062977777, lat: 40.4113279581609 },
    { name: "Holden", lng: 5.02690062977777, lat: 42.4113279581609 },
]

mapboxgl.accessToken = 'pk.eyJ1IjoiZXRoYW5ncmFuZSIsImEiOiJjbTVyMWNsZDAwNmNsMnFxdTl5enQ2dXAxIn0.gCn0a-Ef8cuqw1pEozCo0Q';
window.onload = () => 
{
    const center = { lng: 41.4113279581609, lon: 2.02690062977777 };
    map = InitializeMap(center);

    for (let i = 0; i < friendsConnected.length; i++) 
    {
        AddMarkerToMap(friendsConnected[i].lng, friendsConnected[i].lat, map, friendsConnected[i].name);
    }
}