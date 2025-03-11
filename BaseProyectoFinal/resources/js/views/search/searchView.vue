
<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { forEach } from 'lodash';
import { authStore } from "../../store/auth";

const auth = authStore();
const user_id = ref(auth.user?.id);

const users = ref("");
const loading = ref(true);
const inputbusqueda = ref("");
let timeout;

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
    axios.post('http://127.0.0.1:8000/api/friends/request', {
        "id_sender": user_id.value,
        "id_receiver": id_reciver
    })
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
    </div>
</template>