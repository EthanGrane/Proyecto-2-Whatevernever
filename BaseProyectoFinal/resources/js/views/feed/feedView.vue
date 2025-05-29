<script setup>
import { ref, onMounted } from 'vue';
import { authStore } from '../../store/auth';
import FeedCard from '../../components/feedCard.vue';
import useMarkers from "../../composables/useMarkers"

const markersApi = useMarkers();
const auth = authStore();
const loading = ref(false);
const markers = ref([]);
const friendnumber = ref(0);
const user_id = ref(auth.user?.id);

onMounted(async () => {
    await getFeed();
});

async function getFeed() {
    loading.value = true;

    try {
        const response = await markersApi.getLastMarkersFromFriends();

        markers.value = response.data.markers;
        loading.value = false;

    } catch (error) {
        console.error("[ProfileView.vue] Error:", error);
        loading.value = false;
    }
}
</script>

<template>
    <div class="d-flex m-auto" style="width: 90%;">
        <span class="d-flex align-content-center">
            <img src="/images/feed.svg" style="width: 42px;">
            <h1>Your Feed</h1>
        </span>
    </div>
    <div class="d-flex flex-wrap align-items-center m-auto gap-5" style="width: 90%;">

        <FeedCard v-for="(marker, index) in markers" :key="index" :marker="marker" :index="(index + 1) % 4"/>

    </div>
</template>