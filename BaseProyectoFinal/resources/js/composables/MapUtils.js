let map = null;
let friendList = [];
let markerList = [];
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

    map.addControl(
        new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            trackUserLocation: true,
            showUserHeading: true
        })
    );

    map.addControl(new mapboxgl.NavigationControl(), 'bottom-left');

    // Disable some Intreaction Handles
    // map['dragRotate'].disable();



    return map;
}

export function AddFriendMarkerToMap(lng, lat, name, profilePicture, map) {
    markerList.push({ lng: lng, lat: lat, name: name, profilePicture: profilePicture });

    ReloadMapMarkers(map);
}

export function SetFriends(_friendList) 
{
    friendList.length = 0;
    friendList.push(..._friendList);
}

export function SetMarkers(_markerList) 
{
    markerList.length = 0;
    markerList.push(..._markerList);
}

export function ReloadMapMarkers(map) 
{
    const markersOnView = document.querySelectorAll('.marker');
    markersOnView.forEach(marker => marker.remove());

    for (let index = 0; index < friendList.length; index++) 
    {
        const friend = friendList[index];
        AddFriendToMap(map, friend);
    }

    for (let index = 0; index < markerList.length; index++) 
        {
            const marker = markerList[index];
            AddMarkerToMap(map, marker);
        }
}

export function GetMapCenterCoordinates()
{
    // https://docs.mapbox.com/mapbox-gl-js/api/map/#map#getcenter
    return map.getCenter();
}

export function OnMapDblClick(callback) {
    if (!map) return;

    map.on('dblclick', (e) => {
        callback(e);
    });
}


function AddFriendToMap(map, friend)
{
    const element = document.createElement('div');

    const sizeReduction = Math.floor(Math.random() * 32);
    const width = 64 - sizeReduction;
    const height = 64 - sizeReduction;

    element.className = 'map-marker';
    element.style.backgroundImage = `url(/images/ProfilePicture_${Math.floor(Math.random() * 10)}.jpg)`;
    element.style.width = `${width}px`;
    element.style.height = `${height}px`;
    element.style.backgroundSize = '100%';
    element.style.borderRadius = '100%';
    element.style.boxShadow = 'rgb(0 0 0 / 15%) 0px 16px 4px';

    element.dataset.originalWidth = width;
    element.dataset.originalHeight = height;

    element.addEventListener('click', () => {
        FlyToPosition(friend.last_lng, friend.last_lat, map);
        SelectFriend(element);
    });

    new mapboxgl.Marker(element)
        .setLngLat([friend.last_lng, friend.last_lat])
        .addTo(map);
}

export function AddMarkerToMap(map, marker) 
{
    const element = document.createElement('div');

    const width = 32;
    const height = 32;

    element.className = 'map-marker';
    element.style.backgroundImage = `url(/images/emoji_pinRed.png)`;
    element.style.width = `${width}px`;
    element.style.height = `${height}px`;
    element.style.backgroundSize = 'contain';
    element.style.borderRadius = '100%';
    element.style.backgroundRepeat = 'no-repeat';
    element.style.backgroundPosition = 'center';

    element.dataset.originalWidth = width;
    element.dataset.originalHeight = height;

    element.addEventListener('click', () => {
        FlyToPosition(marker.lng, marker.lat, map);
    });

    new mapboxgl.Marker(element)
        .setLngLat([marker.lng, marker.lat])
        .addTo(map);

}


function SelectFriend(friendElement) {
    if (selectedFriend !== null || selectedFriend == friendElement) {
        selectedFriend.style.width = `${selectedFriend.dataset.originalWidth}px`;
        selectedFriend.style.height = `${selectedFriend.dataset.originalHeight}px`;
        selectedFriend.style.zIndex = 0;
        selectedFriend.style.boxShadow = `rgb(0 0 0 / 15%) 0px ${selectedFriend.dataset.originalWidth / 2}px 4px`;

        if (selectedFriend == friendElement) 
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