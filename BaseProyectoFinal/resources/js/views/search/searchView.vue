
<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { authStore } from "../../store/auth";

const auth = authStore();
const user_id = ref(auth.user?.id);

const users = ref("");
const loading = ref(true);
const inputbusqueda = ref("");

const showMessageBool = ref(false);
const popupMessage = ref("");
const messageType = ref("");

let timeout;

function showMessage(message, type) {
    showMessageBool.value = true;

    messageType.value = type;
    popupMessage.value = message;

    setTimeout(() => {
        showMessageBool.value = false;
    }, 3000);
}

async function cargarUsers() {
    loading.value = true;
    axios.get('http://127.0.0.1:8000/api/friends/showFriends?search='+inputbusqueda.value)
    .then(response => 
    {
        users.value = response.data;
        loading.value = false;

        users.value.forEach(user => {
            try {
                user.image = "images/users/"+user.media[0].file_name;
            } catch (error) {
                user.image = "";
            }
        });

    })
    .catch(error => 
    {
        console.error("[SearchView.vue] Error:", error);
        loading.value = false;
    });
}

async function sendRequest(id_reciver) {
    let response = await axios.post('http://127.0.0.1:8000/api/friends/request', {
        "id_sender": user_id.value,
        "id_receiver": id_reciver
    })

    showMessage(response.data.message, response.data.type);
}


cargarUsers();
function manejarInput() {
    clearTimeout(timeout);

    timeout = setTimeout(() => {
        cargarUsers();
    }, 500);
}

// Call

// DEBUG DELAY (BORRAR EN PORDUCCION)
setTimeout(() => {
    cargarUsers();
}, 2000);


</script>

<template>
    <div class="search-background">
        
        <div>
            <input class="search-field" v-model="inputbusqueda" @input="manejarInput" :placeholder="$t('buscadoramigos')">
        </div>

        <div id="search-user-list-container">
            <div v-if="loading" v-for="n in 4" :key="n" class="search-user-container">
                <div class="search-user-information-container">
                    <div>
                        <div class="search-fake-user-image"></div>
                    </div>
                    <div>
                        <div class="search-fake-user-username"></div>

                        <div class="d-flex flex-row">
                            <div class="search-fake-user-name"></div>
                            <div class="search-fake-user-description"></div>
                        </div>

                    </div>
                </div>
                <div>
                    <div class="search-fake-button"></div>
                </div>
            </div>

            <div v-for="(user, index) in users" :key="index" class="search-user-container">
                <div class="search-user-information-container">
                    <div>
                        <img :src="user.image ? user.image : '/images/ProfilePicture_6.jpg'" alt="User image" class="search-user-information-image">
                    </div>
                    
                    <div class="search-user-information">
                        <b><p class="search-user-information-name">{{ user.name }}</p></b>
                        <p class="search-user-information-username">{{ user.username }}</p>
                    </div>

                </div>

                <div>
                    <button @click="sendRequest(user.id)" class="secondary-button">{{ $t('addFriendText') }}</button>

                </div>
            </div>

            <div v-if="users.length < 1 && !loading" id="notfoundsearcherror">
                <h2>{{ $t('usernotfound') }}</h2>
            </div>
        </div>
        <div v-if="showMessageBool" :class="[messageType == 'good' ? 'search-popup-message-good' : 'search-popup-message-bad']">
            <h3>Info:</h3>
            <p>{{ popupMessage }}</p>
        </div>
    </div>
</template>