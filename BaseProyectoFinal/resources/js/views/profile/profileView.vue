<!-- SCRIPT -->
<script setup>
import { onMounted, ref } from 'vue';
import { authStore } from '../../store/auth';
import useUsers from '../../composables/users';
import { useRoute } from 'vue-router'
import Popover from 'primevue/popover';


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
            <div class="flex flex-col gap-4" style="overflow-y: scroll; height: 25vh;">
                <div>
                    <ul class="list-none p-0 m-0 flex flex-col">
                        <li v-for="user in requestedUserFriendList" :key="user.name"
                            class="flex items-center gap-2 px-2 py-3 hover:bg-emphasis cursor-pointer rounded-border">

                            <a :href="'/profile/' + user.user.username">
                                <span class="search-user-information-name">
                                    {{ user.user.name }}
                                </span>
                                <div class="search-user-information-username">
                                    @{{ user.user.username }}
                                </div>
                            </a>

                        </li>
                    </ul>
                </div>
            </div>
        </Popover>

    </div>
</template>
