<template>
    <div class="profile-background">
        <div class="profile-info-container">
            <img :src="image" :alt="image">
            <h1>{{ name }}</h1>
            <div @click="verAmigos" class="numeroseguidores">
                <div>
                    <h3>63</h3>
                </div>
                <div>
                    <h4>{{ $t('friendscounter') }}</h4>
                </div>
            </div>
            <h3 class="profile-info-username">{{ username }}</h3>
            <p>{{ description }}</p>
            <button class="secondary-button">🗺️ {{ $t('viewfriendmap') }}</button>
        </div>
        <div class="profile-markers-list">
            <h4>📍 {{ $t('lastprofile-markers-list') }}</h4>
        </div>
        <!--Amigos-->
        <transition name="fade">
            <div v-if="panelamigos" class="panelamigos">
                <div class="tituloibotoncerraramigos">
                    <div>
                        <h3>{{ $t('friendscounter') }}</h3>
                    </div>
                    <div>
                        <button @click="panelamigos = false">X</button>
                    </div>
                </div>
                <div class="friendlistprofile">
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
                        <div class="infousersearch" v-if="user.request_status == 1">
                            <div>
                                <img src="/images/icon_profile.svg" alt="User image">
                            </div>
                            <div>
                                <b><p>{{ user.user.name }}</p></b>
                                <p>{{ user.user.username }}</p>
                            </div>
                        </div>
                        <div v-if="user.request_status == 0">
                            <button @click="acceptRequest(user.id)" class="ternaryButton">{{ $t('acceptFriendRequest') }}</button>
                        </div>
                    </div>
                    <div v-if="users.length < 1 && !loading" id="notfoundsearcherror">
                        <h2>{{$t('withoutrequests')}}</h2>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { authStore } from '../../store/auth';

const panelamigos = ref(false);
const image = ref("/images/users/ranagustavo.webp");
const name = ref( authStore().user?.name );
const username = ref( authStore().user?.username );
const description = ref( authStore().user?.desc );

const auth = authStore();
const users = ref([]);
const loading = ref(false);
const user_id = ref(auth.user?.id);

function verAmigos() {
    panelamigos.value = true;
    showMyFriends();
}

async function showMyFriends() {
    loading.value = true;
    axios.get('http://127.0.0.1:8000/api/friends/allFriends?user='+user_id.value)
    .then(response => {
        users.value = response.data;

        loading.value = false;
    })
    .catch(error => {
        console.error("[ProfileView.vue] Error:", error);
        loading.value = false;
    })
}

</script>