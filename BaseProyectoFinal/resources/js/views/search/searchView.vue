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
import axios from 'axios';

const users = ref("");

async function cargarUsers(params) {
    axios.get('http://127.0.0.1:8000/api/friends/showFriends')
    .then(response => {
        users.value = response.data;
    })
    .catch(error => {
        console.error("Hubo un error:", error);
    });
}

cargarUsers();

console.log(users);

</script>
