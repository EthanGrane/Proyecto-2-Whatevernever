<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import { authStore } from "../../store/auth";
import { DeleteRequestAsSender } from '@/composables/useFriends.js';
import ConfirmButtonPopup from '@/components/ConfirmButtonPopup.vue';
import { useToast } from "primevue/usetoast";

const toast = useToast();

const selectedFilter = ref("alphabetical Asc");
const usersList = ref([]);
const inputbusqueda = ref("");
const friendsRequestSended = ref([]);
const friendsRequestMap = ref(new Map());

let debounceTimeout = null;

const filteredUsers = computed(() => {
    let filtered = [...usersList.value];

    if (selectedFilter.value === "alphabetical Asc") {
        filtered.sort((a, b) => a.name.localeCompare(b.name));
    }

    if (selectedFilter.value === "alphabetical Des") {
        filtered.sort((a, b) => b.name.localeCompare(a.name));
    }

    if (selectedFilter.value === "friendRequests") {
        filtered = filtered.filter(user =>
            friendsRequestSended.value.some(friend => friend.id === user.id)
        );
    }

    if (selectedFilter.value === "no friendRequests") {
        filtered = filtered.filter(user =>
            !friendsRequestSended.value.some(friend => friend.id === user.id)
        );
    }

    return filtered;
});

async function cargarUsers() {
    try {
        const [usersRes, requestsRes] = await Promise.all([
            axios.get('http://127.0.0.1:8000/api/friends/showFriends?search=' + inputbusqueda.value),
            axios.get('http://127.0.0.1:8000/api/friends/GetUsersWithFriendRequests')
        ]);

        usersList.value = usersRes.data.map(user => {
            user.image = user.media_url ? user.media_url.split("localhost/")[1] : "";
            return user;
        });

        friendsRequestSended.value = requestsRes.data;
        friendsRequestMap.value = new Map(
            friendsRequestSended.value.map(friend => [friend.id, true])
        );

    } catch (error) {
        console.error("[SearchView.vue] Error:", error);
    }
}

async function sendRequest(id_reciver) {
    await axios.post('http://127.0.0.1:8000/api/friend', {
        "id_sender": authStore().user.id,
        "id_receiver": id_reciver
    }).then(response => {
        friendsRequestSended.value.push({ id: id_reciver });
        friendsRequestMap.value.set(id_reciver, true);
    }).catch(error => {
        console.error(error);
    });
}

async function deleteRequest(friend_id) {
    await DeleteRequestAsSender(authStore().user.id, friend_id);
    friendsRequestSended.value = friendsRequestSended.value.filter(friend => friend.id !== friend_id);
    friendsRequestMap.value.delete(friend_id);
}

function manejarInput() {
    if (debounceTimeout) clearTimeout(debounceTimeout);

    if (inputbusqueda.value === '') {
        usersList.value = [];
    }

    debounceTimeout = setTimeout(() => {
        cargarUsers();
    }, 500);
}

cargarUsers();
</script>

<template>
    <div class="search-background">
        <Toast />

        <div>
            <input class="search-field" v-model="inputbusqueda" @input="manejarInput"
                :placeholder="$t('buscadoramigos')">
            <div class="filters-container">
                <label>{{ $t('filter') }}</label>
                <select v-model="selectedFilter">
                    <option value="alphabetical Asc">{{ $t('alphabetical') }} ↑</option>
                    <option value="alphabetical Des">{{ $t('alphabetical') }} ↓</option>
                    <option value="friendRequests">{{ $t('friendrequests') }}</option>
                    <option value="no friendRequests">{{ $t('nofriendrequests') }}</option>
                </select>
            </div>
        </div>

        <hr>

        <div class="search-user-list-container">
            <!-- Fake loading -->
            <div v-if="usersList.length === 0" v-for="n in 4" :key="n" class="search-user-container">
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

            <!-- Search results -->
            <a v-for="(user, index) in filteredUsers" :key="index" :href="'profile/' + user.username"
                :class="['search-user-container', index % 2 === 0 ? 'user-background-light' : 'user-background-dark']"
                style="color: white;">

                <div class="search-user-information-container">
                    <div>
                        <img :src="user.image ? user.image : '/images/default_pf.jpg'" alt="User image"
                            class="search-user-information-image">
                    </div>

                    <div>
                        <b>
                            <p class="search-user-information-name">{{ user.name }}</p>
                        </b>
                        <span class="search-user-information-username">@{{ user.username }}</span>
                    </div>
                </div>

                <div>
                    <button v-if="friendsRequestMap.get(user.id)" @click.stop.prevent="deleteRequest(user.id)"
                        class="primary-button danger-button-hover" style="min-width: 6rem; max-height: 3rem;">
                        {{ $t('cancel') }}
                    </button>

                    <button v-else @click.stop.prevent="sendRequest(user.id)" class="primary-button button-hover"
                        style="min-width: 7rem;">
                        {{ $t('addFriendText') }}
                    </button>
                </div>
            </a>

            <div v-if="usersList.length === 0 && inputbusqueda !== ''" id="notfoundsearcherror">
                <h2>{{ $t('usernotfound') }}</h2>
            </div>
        </div>
    </div>
</template>

<style scoped>
@media (max-width: 520px) {
    .search-user-list-container {
        width: 100% !important;
        margin: 8px !important;
    }

    .search-background {
        margin: 8px !important;
    }

    .search-field {
        width: 90vw !important;
        margin: 16px !important;
    }
}
</style>
