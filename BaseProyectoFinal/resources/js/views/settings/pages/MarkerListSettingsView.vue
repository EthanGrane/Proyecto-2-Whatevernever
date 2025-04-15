<script setup>

    /*
    * Esta view es un desastre tanto en back y front, porfa rehazla usando el figma
    */

import axios from 'axios';
import { ref } from 'vue';
import { GenerateRandomEmoji, GetEmojiById, GetIdByEmoji } from '../../../composables/MarkerListEmoji.js';

const marker_creator_list = ref("");
const myLists = ref([]);        // nombres de variables poco claras
const showModMenu = ref(null);

// Recojen el valor de los inputs mediante v-model
const markerList_update_name = ref();
const markerList_update_icon_value = ref();

async function createList() {

    console.log(marker_creator_list.value);
    try {
        let response = await axios.post("http://127.0.0.1:8000/api/markersLists", {
            "name": marker_creator_list.value
        });

        console.log(response);

        showLists();

    } catch (error) {
        console.log(error);
    }
}

async function showLists() {
    try 
    {
        let response = await axios.get("http://127.0.0.1:8000/api/markersLists");

        myLists.value = response.data;

        console.log(myLists.value);

    } catch (error) 
    {
        console.log(error);
    }
}

async function deleteList(id_list) {
    try {
        let response = await axios.delete("http://127.0.0.1:8000/api/markersLists/" + id_list);

        console.log(response);

        showLists();

    } catch (error) {
        console.log(error);
    }
}

async function updateMarkerList(id_list) {
    const name = markerList_update_name.value;
    const emoji_identifier = GetIdByEmoji(markerList_update_icon_value.value);

    try {
        let response = await axios.put("http://127.0.0.1:8000/api/markersLists/" + id_list, {
            "name": name,
            "emoji_identifier": emoji_identifier
        });

        console.log(response);

        showLists();
        updateMenu();

        markerList_update_name.value = "";

    } catch (error) {
        console.log(error);
    }
}

function updateMenu(id_list) {
    if (showModMenu.value == null) {
        showModMenu.value = myLists.value[id_list].name;
        markerList_update_name = list
    } else {
        showModMenu.value = null;
    }
}

function generateRandomIcon() {
    markerList_update_icon_value.value = GenerateRandomEmoji();
}

showLists();
</script>

<template>
    <div class="marker-list-configuration-background">
        <div class="marker-list">
            <div v-for="(item, index) in myLists" :key="index" class="search-group-container">
                <div>
                    <b>
                        <p>{{ GetEmojiById(item.emoji_identifier) }} {{ item.name }}</p>
                    </b>
                </div>
                <div class="friend-groups-admin-delete-button">
                    <button class="secondary-button" @click="updateMenu(item.id)"><img class="mod-menu-button-image"
                            src="images/settings.svg"></button>
                    <button class="secondary-button danger-button-hover" @click="deleteList(item.id)">Drop</button>
                </div>
            </div>
        </div>

        <!-- Aqui no se pueden crear nuevas listas -->
        <div class="marker-list-creator d-none">
            <input placeholder="List Name..." v-model="marker_creator_list">
            <button class="secondary-button" @click="createList">Create List</button>
        </div>

        <div class="update-list-menu" v-if="showModMenu != null">
            <h2>Update List</h2>
            <span class="d-flex align-items-center gap-2">

                <input placeholder="New list name..." class="popup-input w-75" v-model="markerList_update_name">

                <input readonly class="popup-input w-25 text-center" type="text" id="marker-name"
                    style="font-size: large;" v-model="markerList_update_icon_value">
                <button @click="generateRandomIcon()"
                    class="btn button-secondary fw-semibold fs-2 p-0 bg-transparent border-0">🎲</button>
            </span>
            <button class="secondary-button d-flex m-auto mt-3" @click="updateMarkerList(showModMenu)">Apply</button>
        </div>

    </div>
</template>