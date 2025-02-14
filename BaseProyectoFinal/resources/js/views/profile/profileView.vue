<template>
    <div class="fondoperfil">
        <div class="infoperfil">
            <img :src="image" :alt="image">
            <h1>{{ name }}</h1>
            <h3>{{ username }}</h3>
            <p>{{ description }}</p>
            <button>Pene</button>
        </div>
        <div class="markers">

        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const image = ref("/images/users/ranagustavo.webp");
const name = ref("Paco Sanz");
const username = ref("pacosanz");
const description = ref("pornogratis.com");


</script>