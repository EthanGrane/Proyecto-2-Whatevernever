<template>
    <div id="map"></div>
</template>

<script setup>
import { onMounted } from 'vue';
import { InitializeMap, AddMarkerToMap, SetFriends,ReloadMapMarkers, AddFriendMarkerToMap, FlyToPosition, GetMap  } from '../compostables/MapUtils.js';

const imagePath = "images/";
const friendsConnected = 
[
    { name: "Adrian",   profilePicture: imagePath + 'ProfilePicture_0.jpg', lng: 2.02690062977777, lat: 41.4113279581609 },
    { name: "Oliver Ros Lillo",   profilePicture: imagePath + 'ProfilePicture_1.jpg', lng: 2.349057569854807, lat: 48.85281416527957},
    { name: "Paul",     profilePicture: imagePath + 'ProfilePicture_2.jpg', lng: -3.711798985923764, lat: 40.39401545431315 },
    { name: "Holden",   profilePicture: imagePath + 'ProfilePicture_3.jpg', lng: -5.95236496667161, lat: 37.38708172425217 },
    { name: "Holden",   profilePicture: imagePath + 'ProfilePicture_4.jpg', lng: -73.96151983821908, lat: 40.80372848997743 },
    { name: "Holden",   profilePicture: imagePath + 'ProfilePicture_5.jpg', lng: 14.21623662321312, lat: 40.87742787180281 },
    { name: "Holden",   profilePicture: imagePath + 'ProfilePicture_6.jpg', lng: 13.443069286288392, lat: 52.55075703102233 },
    { name: "Holden",   profilePicture: imagePath + 'ProfilePicture_7.jpg', lng: -0.2007759956829318, lat: 51.48297928478809 },
];

onMounted(() => 
{
    const center = { lng: 41.4113279581609, lon: 2.02690062977777 };
    const map = InitializeMap(center);
    map.on('load', () => 
    {
        // Code here!
        SetFriends(friendsConnected);
        ReloadMapMarkers(map);

        AddMarkerToMap(-7,40,map,"Test");
    });
})
</script>

<script>
export default {
    name: 'Home',
};
</script>