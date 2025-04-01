<script setup>
import { onMounted } from 'vue';
import axios from 'axios';
import { InitializeMap, SetFriends, ReloadMapMarkers, AddMarkerToMap } from "../../composables/MapUtils.js";
import Popup from '../../components/ui/Popup.vue';

onMounted(async () => {
    const center = { lng: 41.4113279581609, lon: 2.02690062977777 };
    const friendsConnected = await loadUsers();

    if (friendsConnected && Array.isArray(friendsConnected)) {
        SetFriends(friendsConnected);
    } else {
        console.error("Error: La respuesta no es un array válido.");
    }

    // Map
    const map = InitializeMap(center);

    map.on('load', () => {
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
        <Popup />

        <div id="map"></div>
    </div>
</template>
