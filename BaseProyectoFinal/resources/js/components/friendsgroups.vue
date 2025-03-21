<script setup>
import axios from 'axios';
import { ref } from 'vue';

const groupname = ref("");
const myGroups = ref([]);
const joinedGroups = ref([]);

const showMessageBool = ref(false);
const popupMessage = ref("");
const messageType = ref("");

function showMessage(message, type) {
    showMessageBool.value = true;

    messageType.value = type;
    popupMessage.value = message;

    setTimeout(() => {
        showMessageBool.value = false;
    }, 3000);
}

async function CreateGroup(name) {
    try {
        let response = await axios.post("http://127.0.0.1:8000/api/friends/createGroup",{
            name: name
        });

        console.log(response);

        showMessage(response.data.message + ": " + groupname.value, response.data.type);

        ShowMyGroups();

    } catch (error) {
        console.log(error);
        showMessage("Error while creating group " + error, "bad");
    }
}

async function dropGroup(id) {
    try {
        let response = await axios.post("http://127.0.0.1:8000/api/friends/dropGroup", {
            id_group: id
        })

        console.log(response);

        showMessage(response.data.message, response.data.type);

        ShowMyGroups();
    } catch (error) {
        showMessage("Error while droping group "+error, "bad");
    }    
}

async function ShowMyGroups() {
    try {
        let response = await axios.get("http://127.0.0.1:8000/api/friends/showMyGroups");

        myGroups.value = response.data;

        console.log(myGroups.value);
    } catch (error) {
        console.log(error);
        showMessage("Error while showing groups "+error, "bad");
    }
}

/*
async function showJoinedGroups() {
    try {
        let response = await axios.get("http://127.0.0.1:8000/api/friends/showJoinedGroups");

        joinedGroups.value = response.data;

        console.log(joinedGroups.value);
    } catch (error) {
        console.log(error);
        showMessage("Error while showing joined groups "+error, "bad");
    }
}
*/

//showJoinedGroups();
ShowMyGroups();
</script>

<template>
    <div class="groups-background">
        <!--
        <div>
            <h2>Create Group</h2>
            <button @click="CreateGroup(groupname)">Create Group</button>
            <input v-model="groupname" placeholder="Group Name">
        </div>
        -->
        <div class="created-joined-groups">
            <div class="groups-divs">
                <h3>{{ $t('groupsconfigurationbutton') }}</h3>
                <div v-for="(item, index) in myGroups" :key="index" class="search-group-container">
                    <div>
                        <b><p>{{ item.name }}</p></b>
                    </div>
                    <button @click="dropGroup(item.id)" class="secondary-button">Delete</button>
                </div>
            </div>
            <!--
            <div class="groups-divs">
                <h3>Groups i joined</h3>
                <div v-for="(item, index) in joinedGroups" :key="index" class="search-group-container">
                    <div>
                        <p>{{ item.name }}</p>
                    </div>
                </div>
                {{ joinedGroups }}
            </div>
            -->
        </div>
        <div v-if="showMessageBool" :class="[messageType == 'good' ? 'search-popup-message-good' : 'search-popup-message-bad']">
            <h3>Info:</h3>
            <p>{{ popupMessage }}</p>
        </div>
        <div class="create-group-button-configuration">
            <div>
                <button class="secondary-button" @click="CreateGroup(groupname)">Create Group</button><input placeholder="Group name..." class="secondary-button" v-model="groupname">
            </div>
        </div>
    </div>
</template>