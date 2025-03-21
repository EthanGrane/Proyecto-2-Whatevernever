<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { authStore } from '../../store/auth';

const auth = authStore();

const loading = ref(false);
const users = ref([]);
const friendnumber = ref(0);
const user_id = ref(auth.user?.id);

onMounted(async () => {
    await getFeed();
});

async function getFeed() {
    loading.value = true;

    try {
        const response = await axios.post('http://127.0.0.1:8000/api/markers/getLastMarkerFromFriends', {
            user_id: user_id,
        });
        users.value = response.data.markers;
        friendnumber.value = users.value.length;
        loading.value = false;

        console.log(response.data)
    } catch (error) {
        console.error("[ProfileView.vue] Error:", error);
        loading.value = false;
    }
}
</script>

<template>
    <!-- Tu template aquí -->
</template>
