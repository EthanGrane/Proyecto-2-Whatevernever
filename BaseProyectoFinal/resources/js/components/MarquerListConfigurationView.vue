<script setup>

import axios from 'axios';
import { ref } from 'vue';

const marker_creator_list = ref("");
const myLists = ref([]);

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
            </div>
        </div>
        <div class="marker-list-creator">
            <input placeholder="List Name..." v-model="marker_creator_list">
            <button class="secondary-button" @click="createList">Create List</button>
        </div>
    </div>
</template>