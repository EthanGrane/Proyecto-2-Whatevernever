<template>
    <div id="fondobuscador">
        <div>
            <input v-model="inputbusqueda" @change="cargarUsers" :placeholder="$t('buscadoramigos')">
        </div>
        <div id="resultadobusqueda">
            <div v-if="loading" v-for="n in 4" :key="n" class="searchuserdiv">
                <div class="infousersearch">
                    <div>
                        <div class="imagenfalsa_carga"></div>
                    </div>
                    <div>
                        <div class="nombrefalso_carga"></div>
                        <div class="nombrefalso_carga"></div>
                    </div>
                </div>
                <div>
                    <div class="botonfalso_carga"></div>
                </div>
            </div>

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
import axios from 'axios';

const users = ref("");
const loading = ref(true);
const inputbusqueda = ref("");

async function cargarUsers() {
    loading.value = true;
    axios.get('http://127.0.0.1:8000/api/friends/showFriends?search='+inputbusqueda.value)
    .then(response => {
        users.value = response.data;
        loading.value = false;
    })
    .catch(error => {
        console.error("Hubo un error:", error);
        loading.value = false;
    });
}

cargarUsers();

</script>
