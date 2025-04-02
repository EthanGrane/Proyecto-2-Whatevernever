<script setup>
import { onMounted } from 'vue';
import axios from 'axios';
import { InitializeMap, SetFriends, ReloadMapMarkers, AddMarkerToMap } from "../../composables/MapUtils.js";

onMounted(async () => 
{
    const center = { lng: 41.4113279581609, lon: 2.02690062977777 };
    const friendsConnected = await loadUsers();

    if (friendsConnected && Array.isArray(friendsConnected)) 
    {
        SetFriends(friendsConnected);
    }
    else 
    {
        console.error("Error: La respuesta no es un array válido.");
    }

    // Map
    const map = InitializeMap(center);

    map.on('load', () => 
    {
        AddMarkerToMap(-7.817, 52.659, map);

        ReloadMapMarkers(map);
    });
});

async function loadUsers() {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/friends/showFriends');
        return response.data;
    } catch (error) {
        console.error("[SearchView.vue] Error al cargar amigos:", error);
        return [];
    }
}
</script>



<template>
<div>
    <div class="position-absolute rounded" style="width: 20vw; height: 5vw; background: linear-gradient(186deg, rgba(255,50,50,1) 61%, rgba(255,142,142,1) 100%); bottom: 12vh; right: 32px; z-index: 999;">
    <p class="text-center">NEW MARKER</p>
    </div>

    <div id="map">
    </div>
</div>
</template>