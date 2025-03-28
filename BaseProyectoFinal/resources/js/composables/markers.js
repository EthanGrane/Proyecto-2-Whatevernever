import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useMarkers() {

    const getMarker = async (userId) => {
        axios.get('/api/users?page=' + page +
            '&search_id=' + search_id +
            '&search_title=' + search_title +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)
            .then(response => {
                users.value = response.data;
            })
    }

    return {

    }
}