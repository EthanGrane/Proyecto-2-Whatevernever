<script setup>
import { ref } from 'vue';
import { authStore } from "../../store/auth";
import { useFriends } from "../../composables/useFriends";

const auth = authStore();
const userId = ref(auth.user?.id);
const friendsApi = useFriends();

const isLoading = ref(false);
const friendRequests = ref([]);
const showSentRequests = ref(true);

async function fetchReceivedRequests() {
  try {
    const response = await friendsApi.getRequestReceived(userId.value);
    friendRequests.value = response;
  } catch (error) {
    console.error("[SearchView.vue] Error fetching received requests:", error);
  } finally {
    isLoading.value = false;
  }
}

async function fetchSentRequests() {
  try {
    const response = await friendsApi.getRequestSend(userId.value);
    friendRequests.value = response;
  } catch (error) {
    console.error("[SearchView.vue] Error fetching sent requests:", error);
  } finally {
    isLoading.value = false;
  }
}

function showSent() {
  showSentRequests.value = true;
  friendRequests.value = [];
  fetchSentRequests();
}

function showReceived() {
  showSentRequests.value = false;
  friendRequests.value = [];
  fetchReceivedRequests();
}

async function acceptFriendRequest(friendshipId) {
  try {
    await friendsApi.acceptFriend(friendshipId);
    await fetchReceivedRequests();
  } catch (error) {
    console.error("[SearchView.vue] Error accepting friend request:", error);
  }
}

async function cancelFriendRequest(friendshipId) {
  try {
    await friendsApi.deleteRequestAsSender(userId.value,friendshipId);
    await fetchSentRequests();
  } catch (error) {
    console.error("[SearchView.vue] Error cancelling friend request:", error);
  }
}

// Carga inicial: mostrar solicitudes enviadas
fetchSentRequests();

</script>

<template>
  <div id="backgroundfriends">
    <div id="friendsbuttons">
      <button
        :class="{ friendsbuttonsselected: showSentRequests, friendsbuttonsNOTselected: !showSentRequests }"
        @click="showSent"
      >
        {{ $t('sendRequests') }}
      </button>
      <button
        :class="{ friendsbuttonsselected: !showSentRequests, friendsbuttonsNOTselected: showSentRequests }"
        @click="showReceived"
      >
        {{ $t('recivedRequests') }}
      </button>
    </div>

    <!-- Solicitudes enviadas -->
    <div v-if="showSentRequests" class="friendrequestspage">
      <div v-if="isLoading" v-for="n in 4" :key="n" class="search-user-container">
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

      <div v-for="(request, index) in friendRequests" :key="index" class="search-user-container">
        <div v-if="request.request_status === 0" class="search-user-information-container">
          <div>
            <img src="/images/icon_profile.svg" alt="User image" class="search-user-information-image" />
          </div>
          <div class="search-user-information">
            <b><p class="search-user-information-name">{{ request.reciver.name }}</p></b>
            <p class="search-user-information-username">@{{ request.reciver.username }}</p>
          </div>
        </div>
        <div v-if="request.request_status === 0">
          <button @click="cancelFriendRequest(request.id)" class="secondary-button danger-button">
            {{ $t('cancelFriendRequest') }}
          </button>
        </div>
      </div>

      <div v-if="friendRequests.length === 0 && !isLoading" id="notfoundsearcherror">
        <h2>{{ $t('withoutrequests') }}</h2>
      </div>
    </div>

    <!-- Solicitudes recibidas -->
    <div v-else class="friendrequestspage">
      <div v-if="isLoading" v-for="n in 4" :key="n" class="search-user-container">
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

      <div v-for="(request, index) in friendRequests" :key="index" class="search-user-container">
        <div v-if="request.request_status === 0" class="search-user-information-container">
          <div>
            <img src="/images/icon_profile.svg" alt="User image" class="search-user-information-image" />
          </div>
          <div class="search-user-information">
            <b><p class="search-user-information-name">{{ request.sender.name }}</p></b>
            <p class="search-user-information-username">@{{ request.sender.username }}</p>
          </div>
        </div>
        <div v-if="request.request_status === 0">
          <button @click="acceptFriendRequest(request.id)" class="secondary-button">
            {{ $t('acceptFriendRequest') }}
          </button>
        </div>
      </div>

      <div v-if="friendRequests.length === 0 && !isLoading" id="notfoundsearcherror">
        <h2>{{ $t('withoutrequests') }}</h2>
      </div>
    </div>
  </div>
</template>
