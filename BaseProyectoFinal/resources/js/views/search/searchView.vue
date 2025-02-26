
<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { forEach } from 'lodash';

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


cargarUsers();

function manejarInput() {
    clearTimeout(timeout);

    timeout = setTimeout(() => {
        cargarUsers();
    }, 500);
}

/*
@error="setDefaultImage"

function setDefaultImage(Event) {
    Event.target.src = '/images/ProfilePicture_0.jpg';
}
*/
</script>

<template>
    <div id="fondobuscador">
        <div>
            <input class="searchfield" v-model="inputbusqueda" @input="manejarInput" :placeholder="$t('buscadoramigos')">
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
                        <img :src="user.image ? user.image : '/images/icon_profile.svg'" alt="User image">
                    </div>
                    <div>
                        <b><p>{{ user.name }}</p></b>
                        <p>{{ user.username }}</p>
                    </div>
                </div>
                <div>
                    <button class="ternaryButton">{{ $t('addFriendText') }}</button>
                </div>
            </div>

            <div v-if="users.length < 1 && !loading" id="notfoundsearcherror">
                <h2>No se encontro el usuario deseado :(</h2>
            </div>
        </div>
    </div>
</template>