<!-- SCRIPT -->
<script setup>
import { ref } from 'vue';
import { authStore } from '../../store/auth';
import useUsers from '../../composables/users';
import { useRoute } from 'vue-router'

const { updateImg } = useUsers();
const route = useRoute();

const userFriendsListPopupActive = ref(false);
const userPFP = ref("/images/ProfilePicture_8.jpg");

const requestedUserData = ref({});
const requestedUserFriendList = ref();

async function loadDataFromRequestUser() {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/user/showUserByUsername?username=' + route.params.username);
        if (response.data) {
            requestedUserData.value = response.data;
            getFriendsFromRequestedUser();
        } else {
            requestedUserData.value = {};
        }
    } catch (error) {
        console.error("[ProfileView.vue] Error:", error);
        requestedUserData.value = {};
    }
}



async function getFriendsFromRequestedUser() {
    if (!requestedUserData.value.id) return; 

    axios.get('http://127.0.0.1:8000/api/friends/allFriends?user_id=' + requestedUserData.value.id)
        .then(response => {
            requestedUserFriendList.value = response.data || [];
        })
        .catch(error => {
            console.error("[ProfileView.vue] Error:", error);
            requestedUserFriendList.value = [];
        });
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

        setTimeout(getFriendsFromRequestedUser, 1000);
    } catch (error) {
        console.log(error);
    }
}

loadDataFromRequestUser();

</script>

<template>
    <div v-if="requestedUserData.id && requestedUserFriendList.length" class="profile-background">
        <div class="profile-info-container" style="background: linear-gradient(#99de45, #000000);">

            <img :src="userPFP" :alt="userPFP" class="profile-info-pfp">

            <h1 class="profile-info-name">{{ requestedUserData.name }}</h1>
            <h3 class="profile-info-username">@{{ requestedUserData.username }}</h3>

            <p>{{ requestedUserData.desc }}</p>
            <button v-if="false" class="secondary-button m-1">🗺️ {{ $t('viewfriendmap') }}</button>
            <button @click="() => { userFriendsListPopupActive = true; }" class="secondary-button m-1">
                <b>{{ requestedUserFriendList.length }}</b> 
                {{ $t('friendscounter') }}
            </button>
        </div>
        <div class="profile-markers-list">
            <h4>📍 ALL MARKERS</h4>
        </div>

        <!-- Friends Popup -->
        <!-- <transition name="fade">
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
                    <div v-if="requestedUserFriendList.length < 1" id="notfoundsearcherror">
                        <h2>{{ $t('withoutfriends') }}</h2>
                    </div>
                    <div v-for="(user, index) in requestedUserFriendList" :key="index" class="search-user-container">
                        <div class="search-user-information-container" v-if="user.request_status == 1">
                            <div>
                                <img src="/images/icon_profile.svg" alt="User image" class="search-user-information-image">
                            </div>
                            <div class="search-user-information">
                                <b>
                                    <p class="search-user-information-name">{{ user.user.name }}</p>
                                </b>
                                <p class="search-user-information-username">{{ user.user.username }}</p>
                            </div>
                        </div>
                        <div v-if="user.request_status == 1">
                            <button @click="deleteFriend(user.id)" class="secondary-button">{{ $t('deleteFriend') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </transition> -->

    </div>
</template>
