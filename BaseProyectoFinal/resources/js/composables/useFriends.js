import { ref } from 'vue'
import axios from 'axios'

export function useFriends() {

    const checkFriendStatus = async (friendId) => {
        try {
            const response = await axios.get(`/api/friends/getRequestStatus?friend_id=${friendId}`)
            console.log(response.data);
            return response.data.value
        } catch (error) {
            console.error('[useFriends] Error al comprobar estado de amistad:', error.response?.data || error.message)
        }
    }

    const getUsers = async (searchTerm) => {
        const response = await axios.get('http://127.0.0.1:8000/api/friends/showFriends', {
            params: { search: searchTerm }
        });
        return response.data.map(user => ({
            ...user,
            image: user.media_url ? user.media_url.split("localhost/")[1] : ""
        }));
    };

    const getFriendRequests = async () => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/friends/GetUsersWithFriendRequests');
            return response.data;
        } catch (error) {
            console.error("[useFriends] Error al obtener solicitudes de amistad:", error);
            throw error;
        }
    };

    const sendRequest = async (id_sender, id_receiver) => {
        try {
            const response = await axios.post('http://127.0.0.1:8000/api/friend', {
                id_sender,
                id_receiver
            });
            return response.data;
        } catch (error) {
            console.error("[useFriends] Error al enviar solicitud de amistad:", error);
            throw error;
        }
    };

    const deleteRequestAsSender = async (id_sender, id_receiver) => {
        try {
            const response = await axios.get('/api/friends/destroyRequestAsSender', {
                params: { id_sender, id_receiver }
            });
            return response.data;
        } catch (error) {
            console.error("[useFriends] Error al eliminar solicitud:", error);
            throw error;
        }
    };

    const createFriendRequestMap = (requestsArray) => {
        return new Map(requestsArray.map(friend => [friend.id, true]));
    };

    const getUserDataFromName = async (username) => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/user/showUserByUsername?username=' + username);
            if (response.data) {
                const user = response.data;
                return {
                    ...user,
                    image: user.media_url ? "http://127.0.0.1:8000/" + user.media_url.split("localhost/")[1] : "/images/default_pf.jpg"
                };
            }
            return null;
        } catch (error) {
            console.error("[useFriends] Error al obtener datos del usuario:", error);
            return null;
        }
    };

    const getFriendsFromUserId = async (userId) => {
        try {
            const response = await axios.get(`http://127.0.0.1:8000/api/friends/allFriends`, {
                params: { user_id: userId }
            });
            return response.data || [];
        } catch (error) {
            console.error("[useFriends] Error al obtener amigos:", error);
            return [];
        }
    };

    const getRequestReceived = async (user_id) => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/friends/showRequestsRecived?user=' + user_id);
            return response.data;
        } catch (error) {
            console.error(error);
            return error;
        }
    }

        const getRequestSend = async (user_id) => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/friends/showRequestsSent?user=' + user_id);
            return response.data;
        } catch (error) {
            console.error(error);
            return error;
        }
    }


    return {
        getUsers,
        getFriendRequests,
        sendRequest,
        deleteRequestAsSender,
        createFriendRequestMap,
        getUserDataFromName,
        getFriendsFromUserId,
        checkFriendStatus,
        getRequestReceived,
        getRequestSend
    };
}
