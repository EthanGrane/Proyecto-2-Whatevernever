<script setup>
import { onMounted } from 'vue';
import { useUserSearch } from '@/composables/useUserSearch.js';

const {
  selectedFilter,
  usersList,
  inputbusqueda,
  friendsRequestSended,
  friendsRequestMap,
  filteredUsers,
  loadUsersData,
  sendRequest,
  deleteRequest,
  manejarInput
} = useUserSearch();

onMounted(loadUsersData);
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
                        class="secondary-button-stroke danger-button-hover" style="min-width: 7rem;">
                        {{ $t('UnfriendText') }}
                    </button>

                    <button v-else @click.stop.prevent="sendRequest(user.id)" class="primary-button button-hover" style="min-width: 7rem;">
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
