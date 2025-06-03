<!-- SCRIPT -->
<script setup>
import { onMounted, ref } from 'vue';
import { authStore } from '../../store/auth';
import { useRoute } from 'vue-router'
import Popover from 'primevue/popover';
import ConfirmButtonPopup from '../../components/ConfirmButtonPopup.vue';
import useMarkers from "../../composables/useMarkers.js";
import { getEmojiById, getMarkerListById } from "../../composables/useMarkerList.js";
import { GetMapImageUrlFromCoordsAndZoom } from "../../composables/MapUtils.js";
import { useFriends } from '../../composables/useFriends.js';

const route = useRoute();
const friendsApi = useFriends();
const apiMarkers = useMarkers();

const userPFP = ref("");
const requestedUserData = ref({});
const requestedUserFriendList = ref([]);
const requestMarkerData = ref();
const markersDividedByList = ref([]);
const friendRequestStatus = ref(null);

async function loadDataFromRequestUser() {
    const user = await friendsApi.getUserDataFromName(route.params.username);
    if (user) {
        requestedUserData.value = user;
        userPFP.value = user.image;

        checkFriendStatus();
        getFriendsFromRequestedUser();

        requestMarkerData.value = await apiMarkers.showAllMarkersFromUserId(requestedUserData.value.id);
        console.log('requestMarkerData:', requestMarkerData.value);
        loadMarkers();
    } else {
        requestedUserData.value = {};
        userPFP.value = "/images/default_pf.jpg";
    }
}

async function loadMarkers() {
    let groupedMarkers = {};

    if (!requestMarkerData.value || !Array.isArray(requestMarkerData.value.markers)) {
        console.warn('No markers found or markers is not an array.');
        markersDividedByList.value = {};
        return;
    }

    for (let marker of requestMarkerData.value.markers) {
        const listId = marker.marker_list_id;
        if (!groupedMarkers[listId]) {
            groupedMarkers[listId] = { marker_list: await getMarkerListById(listId), markers: [] };
        }
        groupedMarkers[listId].markers.push(marker);
    }

    markersDividedByList.value = groupedMarkers;
    console.log(markersDividedByList.value);
}


async function getFriendsFromRequestedUser() {
    if (!requestedUserData.value.id) return;

    requestedUserFriendList.value = await friendsApi.getFriendsFromUserId(requestedUserData.value.id);
}


async function deleteRequestAsSender(friend_id) {
    if (!friend_id) {
        console.error("Friend_id is not defined on deleteRequestAsSender(friend_id)");
        return;
    }

    try {
        await friendsApi.deleteRequestAsSender(authStore().user.id, friend_id);
        requestedUserFriendList.value = requestedUserFriendList.value.filter(friend => friend.user.id !== Number(friend_id));

        if (friend_id == requestedUserData.value.id)
            friendRequestStatus.value = false;

    } catch (error) {
        console.error('There was an error deleting the sender friend request:', error.message);
    }
}

async function sendFriendRequest(id_receiver) {
    try {
        await friendsApi.sendRequest(authStore().user.id, id_receiver);
        if (id_receiver == requestedUserData.value.id) {
            friendRequestStatus.value = true;
        }
    } catch (error) {
        console.error(error);
    }
}


// PrimeVue Popover template code
const op = ref();
const toggle_showFriends = (event) => {
    op.value.toggle(event);
}

async function checkFriendStatus() {

    friendRequestStatus.value = await friendsApi.checkFriendStatus(requestedUserData.value.id);
}

function ProfileIsVisible() {
    if (requestedUserData.value.id === authStore().user.id)
        return true;
    else if (friendRequestStatus.value == true)
        return true;
    else
        return false;
}


onMounted(async () => {
    await loadDataFromRequestUser();
    await checkFriendStatus(requestedUserData.value.id)
})

</script>

<template>
    <div v-if="requestedUserData.id && friendRequestStatus !== null" class="profile-background">

        <div class="profile-info-container" style="background: linear-gradient(#99de45, var(--background1));">
            <img :src="userPFP" alt="Profile Image" class="profile-info-pfp">

            <h1 class="profile-info-name">{{ requestedUserData.name }}</h1>
            <h3 class="profile-info-username">@{{ requestedUserData.username }}</h3>
            <p>{{ requestedUserData.desc }}</p>

            <span v-if="authStore().user.id != requestedUserData.id" class="m-1">

                <Button v-if="friendRequestStatus === false" @click="sendFriendRequest(requestedUserData.id)"
                    class="primary-button" label="Add Friend"
                    style="padding: 8px !important; padding-left: 12px !important; padding-right: 12px !important;" />

                <Button v-else @click="deleteRequestAsSender(requestedUserData.id)"
                    class="secondary-button danger-button-hover" label="UnFriend" />
            </span>

            <span class="m-1">
                <button v-if="false" class="secondary-button m-1">🗺️ {{ $t('viewfriendmap') }}</button>

                <button v-ripple @click="toggle_showFriends" class="secondary-button m-1"
                    style="--p-ripple-background: black">
                    <b>{{ requestedUserFriendList.length }}</b>
                    {{ $t('friendscounter') }}
                </button>
            </span>
        </div>

        <div v-if="ProfileIsVisible()" class="profile-markers-list m-3">
            <h4>📍 ALL MARKERS</h4>
            <div v-if="requestMarkerData" class="d-flex gap-3 w-100" style="overflow-x: scroll;">
                <div v-for="marker in requestMarkerData.markers">
                    <p class="m-0">{{ marker.name }}</p>
                    <img :src="GetMapImageUrlFromCoordsAndZoom({ lng: marker.lng, lat: marker.lat })">
                </div>
            </div>

            <div v-for="(markerList, index) in markersDividedByList" :key="index">
                <h4 class="mt-5">{{ getEmojiById(markerList.marker_list.emoji_identifier) }} {{
                    markerList.marker_list.name }}</h4>
                <div v-if="requestMarkerData" class="d-flex gap-3 w-100" style="overflow-x: scroll;">
                    <div v-for="marker in markerList.markers" :key="marker.id">
                        <p class="m-0">{{ marker.name }}</p>
                        <img :src="GetMapImageUrlFromCoordsAndZoom({ lng: marker.lng, lat: marker.lat })">
                    </div>
                </div>
            </div>

        </div>

        <div style="height: 64px;"></div>

        <!-- Friends Popup -->
        <Popover ref="op">
            <div class="flex flex-col gap-4" style="
            overflow-y: scroll; height: 25vh; scrollbar-width: thin; scrollbar-color: black white;">
                <div>
                    <ul class="list-none p-0 m-0 flex flex-col">
                        <li>
                            <h4 class="m-0 p-0" style="color: #000000;">
                                Friends
                            </h4>
                        </li>

                        <li v-for="user in requestedUserFriendList" :key="user.name"
                            class="flex items-center gap-2 px-2 py-3 hover:bg-emphasis cursor-pointer rounded-2 popover-li-hover">
                            <div>
                                <div class="d-flex flex-column">
                                    <span class="search-user-information-name" style="color: #000000;">
                                        <a :href="'/profile/' + user.user.username" style="color: black !important;">
                                            {{ user.user.name }}
                                        </a>
                                    </span>
                                    <div class="d-flex justify-content-between align-items-center">

                                        <span class="search-user-information-username"
                                            style="color: white; background-color: #000000; width: fit-content;">
                                            <a :href="'/profile/' + user.user.username"
                                                style="color: white !important;">
                                                @{{ user.user.username }}
                                            </a>
                                        </span>


                                        <ConfirmButtonPopup v-if="authStore().user.id == requestedUserData.id"
                                            name="Delete" header="Delete Friend" positive_option="Delete Friend"
                                            positive_severity="danger" button_class="danger-button border-0"
                                            @confirmed="(result) => { if (result) { deleteRequestAsSender(user.user.id) } }" />

                                    </div>
                                </div>
                            </div>

                        </li>
                    </ul>

                </div>
            </div>
        </Popover>


    </div>
</template>
