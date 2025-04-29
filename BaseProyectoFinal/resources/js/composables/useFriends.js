export async function DeleteRequestAsSender(id_sender, id_receiver) {
    try {
        const response = await axios.get('/api/friends/destroyRequestAsSender', {
            params: {
                id_sender,
                id_receiver
            }
        });
        return response.data;
    } catch (error) {
        console.error("[useFriendRequests] Error al eliminar solicitud de amistad:", error);
        throw error;
    }
}
