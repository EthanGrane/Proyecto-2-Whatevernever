let map = null;
let markers = [];
let friends = [];

export function InitializeMap(centerLngLon) {
    mapboxgl.accessToken = 'pk.eyJ1IjoiZXRoYW5ncmFuZSIsImEiOiJjbTVyMWNsZDAwNmNsMnFxdTl5enQ2dXAxIn0.gCn0a-Ef8cuqw1pEozCo0Q';

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

    // Debug
    map.on('dblclick', (e) => {
        alert(`A click event has occurred at ${e.lngLat}`);
    });

    // Disable some Intreaction Handles
    map['dragRotate'].disable();

    return map;
}

export function AddMarkerToMap(lng, lat, map, popupText = "") {
    new mapboxgl.Marker()
        .setLngLat([lng, lat])
        .addTo(map)
        .setPopup(new mapboxgl.Popup().setHTML(`<p style="margin:0;">${popupText}</p>`));
}

export function AddFriendMarkerToMap(lng, lat, name, profilePicture, map) 
{
    friends.push({ lng: lng, lat: lat, name: name, profilePicture: profilePicture });

    ReloadMapMarkers(map);
}

export function SetFriends(newFriends) 
{
    friends.length = 0;
    friends.push(...newFriends);
}

export function ReloadMapMarkers(map) {
    const markers = document.querySelectorAll('.marker');
    markers.forEach(marker => marker.remove());

    for (let index = 0; index < friends.length; index++) {
        const friend = friends[index];

        const element = document.createElement('div');
        const width = 64;
        const height = 64;

        element.className = 'map-marker';
        element.style.backgroundImage = `url(${friend.profilePicture})`;
        element.style.width = `${width}px`;
        element.style.height = `${height}px`;
        element.style.backgroundSize = '100%';

        element.addEventListener('click', () => {
            FlyToPosition(friend.lng, friend.lat, map, 12);
        });

        new mapboxgl.Marker(element)
            .setLngLat([friend.lng, friend.lat])
            .addTo(map);
    }
}


export function FlyToPosition(lng, lat, map, zoom = -1) {
    if (zoom == -1) {
        map.flyTo({
            center: [lng, lat],
            speed: 5,
            curve: 2,
            easing(t) {
                return t;
            }
        });
    }
    else {
        map.flyTo({
            center: [lng, lat],
            zoom: zoom,
            speed: 5,
            curve: 1,
            easing(t) {
                return t;
            }
        });
    }
}

export function GetMap() {
    return map;
}