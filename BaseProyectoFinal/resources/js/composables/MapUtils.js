let map = null;
let friends = [];
let selectedFriend = null;

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
        showZoom: true,
        visualizePitch: true,
    }),'bottom-right');

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

export function ReloadMapMarkers(map) 
{
    console.log("ReloadMarker");
    const markers = document.querySelectorAll('.marker');
    markers.forEach(marker => marker.remove());

    console.log(`Friends lenght: ${friends.length}`)
    for (let index = 0; index < friends.length; index++) 
    {
        const friend = friends[index];
        
        console.log(friend);
        
        const element = document.createElement('div');
        
        const sizeReduction = Math.floor(Math.random() * 32);
        const width = 64 - sizeReduction;
        const height = 64 - sizeReduction;

        element.className = 'map-marker';
        element.style.backgroundImage = `url(/images/ProfilePicture_${(index % 9)}.jpg)`;
        //element.style.backgroundImage = `url(${friend.profilePicture})`;
        element.style.width = `${width}px`;
        element.style.height = `${height}px`;
        element.style.backgroundSize = '100%';
        element.style.borderRadius = '100%';
        element.style.boxShadow = 'rgb(0 0 0 / 15%) 0px 16px 4px';

        element.dataset.originalWidth = width;
        element.dataset.originalHeight = height;

        element.addEventListener('click', () => 
        {
            FlyToPosition(friend.last_lng, friend.last_lat, map);
            SelectFriend(element);
        });

        new mapboxgl.Marker(element)
            .setLngLat([friend.last_lng, friend.last_lat])
            .addTo(map);        
        
        console.log(friend.profilePicture);
    }
}

function SelectFriend(friendElement) 
{
    if (selectedFriend !== null || selectedFriend == friendElement) 
    {
        selectedFriend.style.width = `${selectedFriend.dataset.originalWidth}px`;
        selectedFriend.style.height = `${selectedFriend.dataset.originalHeight}px`;
        selectedFriend.style.zIndex = 0;
        selectedFriend.style.boxShadow = `rgb(0 0 0 / 15%) 0px ${selectedFriend.dataset.originalWidth / 2}px 4px`;

        if(selectedFriend == friendElement)
        {
            selectedFriend = null;
            return; 
        }
    }

    selectedFriend = friendElement;

    selectedFriend.style.width = '128px';
    selectedFriend.style.height = '128px';
    selectedFriend.style.boxShadow = 'black 0 0 32px';
    selectedFriend.style.zIndex = 1;
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