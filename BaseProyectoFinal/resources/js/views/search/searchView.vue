<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { authStore } from "../../store/auth";

const auth = authStore();
const user_id = ref(auth.user?.id);

const users = ref("");
const loading = ref(true);
const inputbusqueda = ref("");

const friendsRequestSended = ref([]);

let timeout;

async function cargarUsers() {
    loading.value = true;
    axios.get('http://127.0.0.1:8000/api/friends/showFriends?search=' + inputbusqueda.value)
        .then(response => {
            users.value = response.data;

            users.value.forEach(user => {
                try {
                    user.image = "images/users/" + user.media[0].file_name;
                } catch (error) {
                    user.image = "";
                }
            });

        })
        .catch(error => {
            console.error("[SearchView.vue] Error:", error);
            loading.value = false;
        });

    axios.get('http://127.0.0.1:8000/api/friends/GetUsersWithFriendRequests')
        .then(response => {
            friendsRequestSended.value = response.data;
            loading.value = false;
        })
        .catch(error => {
            console.log(error);
            loading.value = false;
        })
}

async function sendRequest(id_reciver) {
    await axios.post('http://127.0.0.1:8000/api/friend', {
        "id_sender": user_id.value,
        "id_receiver": id_reciver
    }).then(response => {
        friendsRequestSended.value.push({ id: id_reciver });
    }).catch(error => {
        console.error(error);
    });
}

async function deleteRequest(friend_id) {
    axios.get(`http://127.0.0.1:8000/api/friends/destroyRequest?id_sender=${user_id.value}&id_receiver=${friend_id}`)
    .then(response => {
        console.log('Friendship deleted:', response.data);
        friendsRequestSended.value = friendsRequestSended.value.filter(friend => friend.id !== friend_id);
    })
    .catch(error => {
        console.error('There was an error deleting the friendship:', error.response?.data || error.message);
    });
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
            <input class="search-field" v-model="inputbusqueda" @input="manejarInput"
                :placeholder="$t('buscadoramigos')">
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
                        <img :src="user.image ? user.image : '/images/ProfilePicture_6.jpg'" alt="User image"
                            class="search-user-information-image">
                    </div>

                    <div class="search-user-information">
                        <b>
                            <p class="search-user-information-name">{{ user.name }}</p>
                        </b>
                        <p class="search-user-information-username">{{ user.username }}</p>
                    </div>
                </div>

                <div>
                    <button v-if="friendsRequestSended.some(friend => friend.id === user.id)"
                        @click="deleteRequest(user.id)" class="secondary-button">
                        {{ $t('cancel') }}
                    </button>

                    <button v-else @click="sendRequest(user.id)" class="secondary-button">
                        {{ $t('addFriendText') }}
                    </button>
                </div>

            </div>

            <div v-if="users.length < 1 && !loading" id="notfoundsearcherror">
                <h2>{{ $t('usernotfound') }}</h2>
            </div>

        </div>

    </div>
</template>