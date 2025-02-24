<template>
    <div id="backgroundfriends">
        <div>
            <h1>{{ $t('friends_title') }}</h1>
        </div>
        <div id="friendsbuttons">
            <button :class="{ friendsbuttonsselected: pages, friendsbuttonsNOTselected: !pages }" @click="page1">
                Notificaciones
            </button>
            <button :class="{ friendsbuttonsselected: !pages, friendsbuttonsNOTselected: pages }" @click="page2">
                Solicitudes
            </button>

        </div>
        <div v-if="pages">
            <p>Amigos</p>
        </div>
        <div v-if="!pages">
            <p>Solicitudes</p>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';

    const loading = ref(false);
    const users = ref();
    const pages = ref(true);

    async function cargarUsers() {
        loading.value = true;
        axios.get('http://127.0.0.1:8000/api/friends/myFriends?user=101')
        .then(response => 
        {
            users.value = response.data;
            loading.value = false;

            /*
            users.value.forEach(user => {
                try {
                    user.image = "images/users/"+user.media[0].file_name;
                } catch (error) {
                    user.image = "";
                }
            });
            */

            console.log(users.value);

        })
        .catch(error => 
        {
            console.error("[SearchView.vue] Error:", error);
            loading.value = false;
        });
    }

    function page1() {
        pages.value = true;
    }

    function page2() {
        pages.value = false;
    }

    cargarUsers();
</script>