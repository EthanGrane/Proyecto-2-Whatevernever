import { ref, computed } from 'vue';
import { authStore } from "../store/auth";
import { useFriends } from './useFriends';

export function useUserSearch() {
  const selectedFilter = ref("alphabetical Asc");
  const usersList = ref([]);
  const inputbusqueda = ref("");
  const friendsRequestSended = ref([]);
  const friendsRequestMap = ref(new Map());
  const friendsApi = useFriends();

  let debounceTimeout = null;

  const filteredUsers = computed(() => {
    let filtered = [...usersList.value];

    if (selectedFilter.value === "alphabetical Asc") {
      filtered.sort((a, b) => a.name.localeCompare(b.name));
    }

    if (selectedFilter.value === "alphabetical Des") {
      filtered.sort((a, b) => b.name.localeCompare(a.name));
    }

    if (selectedFilter.value === "friendRequests") {
      filtered = filtered.filter(user =>
        friendsRequestSended.value.some(friend => friend.id === user.id)
      );
    }

    if (selectedFilter.value === "no friendRequests") {
      filtered = filtered.filter(user =>
        !friendsRequestSended.value.some(friend => friend.id === user.id)
      );
    }

    return filtered;
  });

  async function loadUsersData() {
    try {
      usersList.value = await friendsApi.getUsers(inputbusqueda.value);
      const requestsRes = await friendsApi.getFriendRequests();
      friendsRequestSended.value = requestsRes;
      friendsRequestMap.value = new Map(
        friendsRequestSended.value.map(friend => [friend.id, true])
      );
    } catch (error) {
      console.error("[useUserSearch] Error:", error);
    }
  }

  async function sendRequest(id_receiver) {
    try {
      await friendsApi.sendRequest(authStore().user.id, id_receiver);
      friendsRequestSended.value.push({ id: id_receiver });
      friendsRequestMap.value.set(id_receiver, true);
    } catch (error) {
      console.error("[useUserSearch] Error al enviar solicitud:", error);
    }
  }

  async function deleteRequest(friend_id) {
    await DeleteRequestAsSender(authStore().user.id, friend_id);
    friendsRequestSended.value = friendsRequestSended.value.filter(friend => friend.id !== friend_id);
    friendsRequestMap.value.delete(friend_id);
  }

  function manejarInput() {
    if (debounceTimeout) clearTimeout(debounceTimeout);
    if (inputbusqueda.value === '') {
      usersList.value = [];
    }
    debounceTimeout = setTimeout(() => {
      loadUsersData();
    }, 500);
  }

  return {
    selectedFilter,
    usersList,
    inputbusqueda,
    friendsRequestSended,
    friendsRequestMap,
    filteredUsers,
    loadUsersData,
    sendRequest,
    deleteRequest,
    manejarInput
  };
}
