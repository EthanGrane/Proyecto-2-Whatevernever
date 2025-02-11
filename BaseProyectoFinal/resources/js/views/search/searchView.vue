<template>
    <div id="fondobuscador">
        <div>
            <input :placeholder="$t('buscadoramigos')">
        </div>
        <div id="resultadobusqueda">
            <div v-for="(user, index) in users" :key="index" class="searchuserdiv">
                <div class="infousersearch">
                    <div>
                        <img :src="user.image" :alt="user.name">
                    </div>
                    <div>
                        <b><p>{{ user.name }}</p></b>
                        <p>{{ user.username }}</p>
                    </div>
                </div>
                <div>
                    <button class="ternaryButton">{{ $t('addfriendbutton') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

//Se deveria obtener de la base de datos con la api
const users = ref([
    { name: "Rana Gustavo", username: "ranagustavo", image: "/images/users/ranagustavo.webp" },
    { name: "Juan Pérez", username: "juanperez", image: "/images/users/ranagustavo.webp" },
    { name: "Maria López", username: "marialopez", image: "/images/users/ranagustavo.webp" }
]);

</script>
