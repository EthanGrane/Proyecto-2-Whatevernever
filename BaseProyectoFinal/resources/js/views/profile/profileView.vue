<!-- SCRIPT -->
<script setup>
import { ref } from 'vue';
import { authStore } from '../../store/auth';
import useUsers from '../../composables/users';

const { updateImg } = useUsers()

const userFriendsListPopupActive = ref(false);
const image = ref("/images/ProfilePicture_8.jpg");
const name = ref(authStore().user?.name);
const username = ref(authStore().user?.username);
const description = ref(authStore().user?.desc);
const friendnumber = ref(0);
const userUpdatePicture = ref();

const auth = authStore();
const users = ref([]);
const loading = ref(false);
const user_id = ref(auth.user?.id);

function ShowFriendListPopup() {
    userFriendsListPopupActive.value = true;
}

async function LoadProfile()
{
    loading.value = true;
    axios.get('http://127.0.0.1:8000/api/profile/allFriends?user=' + user_id.value)
        .then(response => {
            users.value = response.data;
            friendnumber.value = users.value.length;
            loading.value = false;
        })
        .catch(error => {
            console.error("[ProfileView.vue] Error:", error);
            loading.value = false;
        })
}

async function showMyFriends() 
{
    axios.get('http://127.0.0.1:8000/api/friends/allFriends?user=' + user_id.value)
        .then(response => {
            users.value = response.data;
            friendnumber.value = users.value.length;
        })
        .catch(error => {
            console.error("[ProfileView.vue] Error:", error);
        })
}

async function deleteFriend(id_friendship) {
    console.log(id_friendship);
    try {
        axios.post('http://127.0.0.1:8000/api/friends/delete', {
            "friend_id": id_friendship,
        })
            .then(response => {
                console.log(response);
            })
            .catch(error => {
                console.error("[SearchView.vue] Error:", error);
            });

        users.value = [];
        setTimeout(showMyFriends, 1000);
    } catch (error) {
        console.log(error);
    }
}

showMyFriends();

</script>

<template>
    <div class="profile-background">
        <div class="profile-info-container" style="background: linear-gradient(#99de45, #000000);
        ">
        
            <img :src="image" :alt="image" class="profile-info-pfp">

            <h1 class="profile-info-name">{{ name }}</h1>

            <h3 class="profile-info-username">{{ username }}</h3>

            <p>{{ description }}</p>
            <button v-if="false" class="secondary-button m-1">🗺️ {{ $t('viewfriendmap') }}</button>
            <button @click="ShowFriendListPopup" class="secondary-button m-1"><b>{{ friendnumber }}</b> {{ $t('friendscounter')
                }}</button>
        </div>
        <div class="profile-markers-list">
            <h4>📍 ALL MARKERS</h4>
        </div>
        <!--
        <input type="file" ref="userUpdatePicture" @change="updateImg(userUpdatePicture.files[0])" name="picture">
        <button type="submit">Submit</button>
        -->

        <!--Amigos-->
        <transition name="fade">
            <div v-if="userFriendsListPopupActive" class="friends-panel">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="">{{ $t('friendscounter') }}</h3>
                    </div>
                    <div>
                        <button @click="userFriendsListPopupActive = false" class="closse-friend-panel">X</button>
                    </div>
                </div>
                <div class="friendlistprofile">
                    <div v-if="loading" v-for="n in 4" :key="n" class="search-user-container">
                        <div class="search-user-information-container">
                            <div>
                                <div class="search-fake-user-image"></div>
                            </div>
                            <div>
                                <div class="search-fake-user-username"></div>

                                <div class="d-flex flex-row">
                                    <div class="search-fake-user-name"></div>
                                    <div class="search-fake-user-description"></div>
                                </div>

                            </div>
                        </div>
                        <div>
                            <div class="search-fake-button"></div>
                        </div>
                    </div>
                    <div v-for="(user, index) in users" :key="index" class="search-user-container">
                        <div class="search-user-information-container" v-if="user.request_status == 1">
                            <div>
                                <img src="/images/icon_profile.svg" alt="User image"
                                    class="search-user-information-image">
                            </div>
                            <div class="search-user-information">
                                <b>
                                    <p class="search-user-information-name">{{ user.user.name }}</p>
                                </b>
                                <p class="search-user-information-username">{{ user.user.username }}</p>
                            </div>
                        </div>
                        <div v-if="user.request_status == 1">
                            <button @click="deleteFriend(user.id)" class="secondary-button">{{ $t('deleteFriend')
                                }}</button>
                        </div>
                    </div>
                    <div v-if="users.length < 1 && !loading" id="notfoundsearcherror">
                        <h2>{{ $t('withoutfriends') }}</h2>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
