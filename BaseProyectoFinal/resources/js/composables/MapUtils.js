let map = null;
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

    // Hide Controls
    map.addControl(new mapboxgl.NavigationControl({
        showCompass: false,
        showZoom: true,
        visualizePitch: true,
    }), 'bottom-right');

    map.addControl(
        new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            trackUserLocation: true,
            showUserHeading: true
        })
    );

    // Disable some Intreaction Handles
    map['dragRotate'].disable();


    return map;
}

export function AddMarkerToMap(lng, lat, map) 
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
        FlyToPosition(lng, lat, map);
    });

    new mapboxgl.Marker(element)
        .setLngLat([lng, lat])
        .addTo(map);

}

export function AddFriendMarkerToMap(lng, lat, name, profilePicture, map) {
    markerList.push({ lng: lng, lat: lat, name: name, profilePicture: profilePicture });

    ReloadMapMarkers(map);
}

export function SetFriends(newFriends) 
{
    markerList.length = 0;
    markerList.push(...newFriends);
}

export function ReloadMapMarkers(map) 
{
    console.log("ReloadMarker");
    const markersOnView = document.querySelectorAll('.marker');
    markersOnView.forEach(marker => marker.remove());

    console.log(`Friends lenght: ${markerList.length}`)
    for (let index = 0; index < markerList.length; index++) {
        const friend = markerList[index];

        console.log(friend);

        const element = document.createElement('div');

        const sizeReduction = Math.floor(Math.random() * 32);
        const width = 64 - sizeReduction;
        const height = 64 - sizeReduction;

        element.className = 'map-marker';
        element.style.backgroundImage = `url(/images/ProfilePicture_${(index % 9)}.jpg)`;
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

        console.log(friend.profilePicture);
    }
}

function SelectFriend(friendElement) {
    if (selectedFriend !== null || selectedFriend == friendElement) {
        selectedFriend.style.width = `${selectedFriend.dataset.originalWidth}px`;
        selectedFriend.style.height = `${selectedFriend.dataset.originalHeight}px`;
        selectedFriend.style.zIndex = 0;
        selectedFriend.style.boxShadow = `rgb(0 0 0 / 15%) 0px ${selectedFriend.dataset.originalWidth / 2}px 4px`;

        if (selectedFriend == friendElement) {
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