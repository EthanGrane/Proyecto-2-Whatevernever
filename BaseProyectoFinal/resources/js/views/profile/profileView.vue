<template>
    <div class="fondoperfil">
        <div class="infoperfil">
            <img :src="image" :alt="image">
            <h1>{{ name }}</h1>
            <h3 class="usernamestyle">{{ username }}</h3>
            <p>{{ description }}</p>
            <button class="ternaryButton">🗺️ {{ $t('viewfriendmap') }}</button>
        </div>
        <div class="markers">
            <h4>📍 {{ $t('lastmarkers') }}</h4>
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