<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

// MaboxGL Compostable
import { InitializeMap, SetFriends, ReloadMapMarkers, AddMarkerToMap, SetMarkers, HideCenterMarker, OnMapDblClick, ShowMarkerOnMapCenter } from "../../composables/MapUtils.js";
import PopupCreateMarker from '../../components/PopupCreateMarker.vue';

const popupVisible = ref(false);

onMounted(async () => {
    const friendsConnected = await loadUsers();
    const allMarkers = await loadMarkers();

    if (friendsConnected && Array.isArray(friendsConnected)) {
        SetFriends(friendsConnected);
        SetMarkers(allMarkers);
    }
    else {
        console.error("Error: La respuesta no es un array válido.");
    }

    // Map
    const map = InitializeMap();
    map.on('load', () => {
        OnMapDblClick((e) => {
            ShowMarkerOnMapCenter();
            popupVisible.value = true;
        });

        ReloadMapMarkers(map);

        map.on('move', () => {
            HandleCenterMarker();
        });
        map.on('zoom', () => {
            HandleCenterMarker();
        });
    });

    function HandleCenterMarker() {
        if (popupVisible.value == true)
            ShowMarkerOnMapCenter();
        else
            HideCenterMarker();
    }
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

async function loadMarkers() {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/markers/');
        return response.data;
    } catch (error) {
        console.error("[SearchView.vue] Error al cargar marcadores:", error);
        return [];
    }
}

</script>

<template>

    <div>
        <PopupCreateMarker v-model:visible="popupVisible" />

        <div id="map"></div>
    </div>
</template>
