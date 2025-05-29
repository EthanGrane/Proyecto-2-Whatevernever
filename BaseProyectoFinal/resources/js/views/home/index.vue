<script setup>
import { onMounted, ref } from 'vue';

// MaboxGL Compostable
import { emitter } from '@/composables/MapUtils';
import { InitializeMap, SetFriends, ReloadMapMarkers, AddMarkerToMap, SetMarkers, HideCenterMarker, OnMapDblClick, ShowMarkerOnMapCenter } from "@/composables/MapUtils.js";
import PopupCreateMarker from '../../components/PopupCreateMarker.vue';
import PopupShowMarker from '../../components/PopupShowMarker.vue';
import useMarkers from '../../composables/useMarkers';
import {useFriends} from '../../composables/useFriends';

import { create } from 'lodash';
import MapHintHelp from '@/components/MapHintHelp.vue';

const createMarkerPopupVisible = ref(false);
const showMarkerDataPopupVisible = ref(true);

const selectedMarkerData = ref(null);
const marekrsApi = useMarkers();
const { getMarkers, markers, showMarkerById } = useMarkers(); 

const friendsApi = useFriends();

async function handleMarkerClick(id) {
    const data = await showMarkerById(id);
    selectedMarkerData.value = data;
    showMarkerDataPopupVisible.value = true;
}

onMounted(async () => {
    // Event, on marked clicked get id.
    emitter.on('marker-clicked', handleMarkerClick);

    const friendsConnected = await loadUsers();
    const allMarkers = await loadMarkers();

    console.log(friendsConnected);

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
            createMarkerPopupVisible.value = true;
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
        if (createMarkerPopupVisible.value == true)
            ShowMarkerOnMapCenter();
        else
            HideCenterMarker();
    }
});

async function loadUsers() {
    try {
        const response = await friendsApi.getUsers();
        return response;
    } catch (error) {
        console.error("[SearchView.vue] Error al cargar amigos:", error);
        return [];
    }
}

async function loadMarkers() {
    try {
        await marekrsApi.getMarkers();
        return marekrsApi.markers.value.data;
    } catch (error) {
        console.error("[SearchView.vue] Error al cargar marcadores:", error);
        return [];
    }
}

function ToggleCreateMarker() {
    createMarkerPopupVisible.value = !createMarkerPopupVisible.value;
}

</script>

<template>

    <div>
        <PopupCreateMarker v-model:visible="createMarkerPopupVisible" />
        <PopupShowMarker v-if="selectedMarkerData != null" v-model:visible="showMarkerDataPopupVisible"
            :marker=selectedMarkerData />

        <button class="button-primary d-block d-sm-none" @click="ToggleCreateMarker"
            style="position: fixed; bottom: 64px; right: 16px; width: 32px; height: 32px; z-index: 999; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; border-radius: 50%; border: 0;">+</button>

        <div id="map"></div>
        <MapHintHelp />
    </div>
</template>

<style scoped>
.hint {
    position: fixed;
    bottom: 84px;
    right: 32px;

    width: 32px;
    height: 32px;

    background-color: white;
    border-radius: 50%;
}

.hint:hover {
    background-color: rgb(225, 225, 225);
}
</style>