<template>
    <div id="backgroundfriends">
        <div>
            <h1>{{ $t('friends_title') }}</h1>
        </div>
        <div id="friendsbuttons">
            <button :class="{ friendsbuttonsselected: pages, friendsbuttonsNOTselected: !pages }" @click="page1">
                Amigos
            </button>
            <button :class="{ friendsbuttonsselected: !pages, friendsbuttonsNOTselected: pages }" @click="page2">
                Solicitudes
            </button>
        </div>
        <div v-if="pages">
            <p>Amigos</p>
        </div>
        <div v-if="!pages" class="friendrequestspage">
            <div v-for="(user, index) in users" :key="index" class="searchuserdiv">
                <div class="infousersearch">
                    <div>
                        <img src="/images/icon_profile.svg" alt="User image">
                    </div>
                    <div>
                        <b><p>{{ user.name }}</p></b>
                        <p>{{ user.username }}</p>
                    </div>
                </div>
                <div>
                    <button class="ternaryButton">{{ $t('acceptFriendRequest') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';

    const loading = ref(false);
    const users = ref([]);
    const pages = ref(true);

    async function cargarUsers() {
    loading.value = true;
    axios.get('http://127.0.0.1:8000/api/friends/myFriends?user=102')
        .then(response => {
            users.value = response.data.map(request => request.sender); // Extraemos solo los usuarios
            loading.value = false;
        })
        .catch(error => {
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