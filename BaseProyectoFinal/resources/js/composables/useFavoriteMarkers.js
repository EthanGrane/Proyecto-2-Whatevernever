import axios from 'axios'

export function useFavoriteMarkers() {

    const addFavorites = async (marker_id) => {
        try {
            const response = await axios.post(`/api/markers/${marker_id}/favorite`)
            console.log(response.data)
            return response.data
        } catch (error) {
            console.error(error)
        }
    }

    const deleteFavorites = async (marker_id) => {
        try {
            const response = await axios.delete(`/api/markers/${marker_id}/favorite`)
            console.log(response.data)
            return response.data
        } catch (error) {
            console.error(error)
        }
    }

    const getFavorites = async () => {
        try {
            const response = await axios.get(`/api/favorite`)
            // console.log(response.data)
            return response.data
        } catch (error) {
            console.error(error)
        }
    }

    const toggleSetFavorite = async (marker_id) => {
        const response = await getFavorites();
        const currentFavorites = response.favorites || [];
        const isFavorite = currentFavorites.some(marker => marker.id === marker_id);
        if (isFavorite) {
            return await deleteFavorites(marker_id);
        } else {
            return await addFavorites(marker_id);
        }
    }


    const isFavorite = async (marker_id) => {
        const response = await getFavorites();
        const currentFavorites = response.favorites || [];

        console.log(currentFavorites);
        console.log(currentFavorites.includes(marker_id));

        return currentFavorites.includes(marker_id)
    }

    return {
        getFavorites,
        deleteFavorites,
        addFavorites,
        toggleSetFavorite,
        isFavorite
    }
}
