<!-- SCRIPT -->
<script setup>
import { onMounted, ref } from 'vue';
import { authStore } from '../../store/auth';
import useUsers from '../../composables/users';
import { useRoute } from 'vue-router'
import Popover from 'primevue/popover';
import ConfirmButtonPopup from '../../components/ConfirmButtonPopup.vue';


const { updateImg } = useUsers();
const route = useRoute();

const userPFP = ref("/images/ProfilePicture_8.jpg");
const requestedUserData = ref({});
const requestedUserFriendList = ref([]);

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


async function deleteRequest(friend_id) {
    axios.get(`http://127.0.0.1:8000/api/friends/destroyRequest?id_sender=${authStore().user.id}&id_receiver=${friend_id}`)
        .then(response => {
            console.log('Friendship deleted:', response.data);
            console.log(requestedUserFriendList.value);
            requestedUserFriendList.value = requestedUserFriendList.value.filter(friend => friend.user.id !== Number(friend_id));
        })
        .catch(error => {
            console.error('There was an error deleting the friendship:', error.response?.data || error.message);
        });
}

onMounted(async () => {
    loadDataFromRequestUser();
})

// PrimeVue Popover template code
const op = ref();
const toggle = (event) => {
    op.value.toggle(event);
}

</script>

<template>
    <div v-if="requestedUserData.id && requestedUserFriendList.length" class="profile-background">
        <div class="profile-info-container" style="background: linear-gradient(#99de45, #000000);">

            <img :src="userPFP" :alt="userPFP" class="profile-info-pfp">

            <h1 class="profile-info-name">{{ requestedUserData.name }}</h1>
            <h3 class="profile-info-username">@{{ requestedUserData.username }}</h3>

            <p>{{ requestedUserData.desc }}</p>
            <button v-if="false" class="secondary-button m-1">🗺️ {{ $t('viewfriendmap') }}</button>
            <button v-ripple @click="toggle" class="secondary-button m-1" style="--p-ripple-background: black">
                <b>{{ requestedUserFriendList.length }}</b>
                {{ $t('friendscounter') }}
            </button>
        </div>
        <div class="profile-markers-list">
            <h4>📍 ALL MARKERS</h4>
        </div>

        <!-- Friends Popup -->
        <Popover ref="op">
            <div class="flex flex-col gap-4" style="
            overflow-y: scroll; height: 25vh; scrollbar-width: thin; scrollbar-color: black white;
            ">
                <div>
                    <ul class="list-none p-0 m-0 flex flex-col">
                        <li>
                            <h4 class="m-0 p-0" style="color: #000000;">
                                Friends
                            </h4>
                        </li>
                        <li v-for="user in requestedUserFriendList" :key="user.name"
                            class="flex items-center gap-2 px-2 py-3 hover:bg-emphasis cursor-pointer rounded-2 popover-li-hover">
                            <a :href="'/profile/' + user.user.username">
                                <div class="d-flex flex-column">
                                    <span class="search-user-information-name" style="color: #000000;">
                                        {{ user.user.name }}
                                    </span>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="search-user-information-username"
                                            style="color: white; background-color: #000000; width: fit-content;">
                                            @{{ user.user.username }}
                                        </span>
                                        <button @click.stop.prevent="deleteRequest(user.user.id)"
                                            v-if="requestedUserData.id == authStore().user.id" class="btn m-0 ml-auto danger-button-hover">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </a>

                        </li>
                    </ul>
                </div>
            </div>
        </Popover>

    </div>
</template>
