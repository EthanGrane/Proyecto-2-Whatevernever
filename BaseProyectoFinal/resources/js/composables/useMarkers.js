import { GetMapCenterCoordinates, HideCenterMarker, AddMarker, ReloadMapMarkers, GetMap } from "./MapUtils.js";
import { authStore } from '../store/auth.js';
export const DEFAULT_MARKER_DATA = { name: "", description: "", marker_list_id: undefined, lng: 0.0, lat: 0.0 };

export async function createNewMarker(markerData, onSuccess = () => { }, onError = () => { }) {
    console.log(markerData);

    const map = GetMap();

    markerData.user_id = authStore().user.id;
    markerData.lng = map.getCenter().lng;
    markerData.lat = map.getCenter().lat;
    markerData.zoom = map.getZoom();
    markerData.pitch = map.getPitch();
    markerData.bearing = map.getBearing();

    axios.post('/api/markers', {
        name: markerData.name,
        description: markerData.description,
        lng: markerData.lng,
        lat: markerData.lat,
        zoom: markerData.zoom,
        pitch: markerData.pitch,
        bearing: markerData.bearing,
        marker_list_id: markerData.marker_list_id,
        user_id: markerData.user_id,
        marker_list_id: markerData.marker_list_id ?? null
    })
        .then(res => {
            console.log('Marcador creado:', res.data);

            markerData.id = res.data.marker.id;

            HideCenterMarker();
            AddMarker(markerData);
            ReloadMapMarkers();

            onSuccess(markerData, "Marcador creado con éxito");
        })
        .catch(err => {
            console.error('Error al crear marcador:', err)
            onError(err.response?.data?.message || "Error al crear el marcador");
        });
}

export async function showMarkerById(id) {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/markers/' + id);
        return response.data;
    } catch (error) {
        console.error("[SearchView.vue] Error al cargar marcadores:", error);
        return [];
    }
}

export async function showAllMarkersFromUserId(user_id)
{
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/markers/getAllMarkersFromFriendId/?user_id=' + user_id);
        return response.data;
    } catch (error) {
        console.error("[SearchView.vue] Error al cargar marcadores:", error);
        return [];
    }
}
