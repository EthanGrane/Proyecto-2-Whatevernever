import axios from 'axios';

export function useFriends() 
{
    
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

    return {
        getUsers,
        getFriendRequests,
        sendRequest,
        deleteRequestAsSender,
        createFriendRequestMap
    };
}
