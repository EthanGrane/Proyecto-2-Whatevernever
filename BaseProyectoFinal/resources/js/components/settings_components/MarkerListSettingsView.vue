<script setup>

import axios from 'axios';
import { ref } from 'vue';

const marker_creator_list = ref("");
const myLists = ref([]);
const showModMenu = ref(null);
const marker_list_updater = ref("");

async function createList() {

    console.log(marker_creator_list.value);
    try {
        let response = await axios.post("http://127.0.0.1:8000/api/markersLists", {
            "name":marker_creator_list.value
        });

        console.log(response);

        showLists();

    } catch (error) {
        console.log(error);
    }
}

async function showLists() {
    try {
        let response = await axios.get("http://127.0.0.1:8000/api/markersLists");

        myLists.value = response.data;

        console.log(myLists.value);

    } catch (error) {
        console.log(error);
    }
}

async function deleteList(id_list) {
    try {
        let response = await axios.delete("http://127.0.0.1:8000/api/markersLists/"+id_list);

        console.log(response);

        showLists();

    } catch (error) {
        console.log(error);
    }
}

async function updateList(id_list) {
    console.log(id_list);

    try {
        let response = await axios.put("http://127.0.0.1:8000/api/markersLists/"+id_list, {
            "name":marker_list_updater.value
        });

        console.log(response);

        showLists();
        updateMenu();

        marker_list_updater.value = "";

    } catch (error) {
        console.log(error);
    }
}

function updateMenu(id_list) {
    if (showModMenu.value == null) {
        showModMenu.value = id_list;
    } else {
        showModMenu.value = null;
    }
}

showLists();
</script>

<template>
    <div class="marker-list-configuration-background">
        <div class="marker-list">
            <h2>{{ $t('markersconfigurationbutton') }}</h2>
            <div v-for="(item, index) in myLists" :key="index" class="search-group-container">
                <div>
                    <b><p>{{ item.name }}</p></b>
                </div>
                <div class="friend-groups-admin-delete-button">
                    <button class="secondary-button" @click="updateMenu(item.id)"><img class="mod-menu-button-image" src="images/settings.svg"></button>
                    <button class="secondary-button danger-button-hover" @click="deleteList(item.id)">Drop</button>
                </div>
            </div>
        </div>
        <div class="marker-list-creator">
            <input placeholder="List Name..." v-model="marker_creator_list">
            <button class="secondary-button" @click="createList">Create List</button>
        </div>
        <div class="update-list-menu" v-if="showModMenu != null">
            <h2>Update List</h2>
            <input placeholder="New list name..." v-model="marker_list_updater">
            <button class="secondary-button" @click="updateList(showModMenu)">Apply</button>
        </div>
    </div>
</template>