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
const image = ref("/images/users/ranagustavo.webp");
const name = ref("Paco Sanz");
const username = ref("pacosanz");
const description = ref("https://www.pacosanz.com/donaciones");


</script>