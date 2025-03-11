<template>
    <div class="profile-background">
        <div class="profile-info-container">
            <img :src="image" :alt="image">
            <h1>{{ name }}</h1>
            <h3 class="profile-info-username">{{ username }}</h3>
            <p>{{ description }}</p>
            <button class="secondary-button">🗺️ {{ $t('viewfriendmap') }}</button>
        </div>
        <div class="profile-markers-list">
            <h4>📍 {{ $t('lastprofile-markers-list') }}</h4>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { authStore } from "../../store/auth";

const image = ref("/images/users/ranagustavo.webp");
const name = ref( authStore().user?.name );
const username = ref( authStore().user?.username );
const description = ref( authStore().user?.desc );


</script>