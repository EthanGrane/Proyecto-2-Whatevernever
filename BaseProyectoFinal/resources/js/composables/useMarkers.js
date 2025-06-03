import { ref } from 'vue';
import axios from 'axios';
import { GetMap, GetMapCenterCoordinates, HideCenterMarker, AddMarker, ReloadMapMarkers } from "./MapUtils.js";
import { authStore } from '../store/auth.js';

export const DEFAULT_MARKER_DATA = { name: "", description: "", marker_list_id: undefined, lng: 0.0, lat: 0.0 };

const markers = ref({ data: [] });

export default function useMarkers() {

    const getMarkers = async () => {
        try {
            const response = await axios.get('/api/markers', {});
            markers.value = {
                data: response.data.data ?? response.data
            };
        } catch (error) {
            console.error('Error al obtener los markers:', error);
        }
    };

    const deleteMarker = async (id, index = null) => {
        try {
            await axios.delete(`/api/markers/${id}`);
            if (markers.value.data && index !== null) {
                markers.value.data.splice(index, 1);
            }
        } catch (error) {
            console.error('Error al eliminar el marker:', error);
        }
    };

    const createNewMarker = async (markerData, onSuccess = () => { }, onError = () => { }) => {
        const map = GetMap();
        markerData.user_id = authStore().user.id;
        markerData.lng = map.getCenter().lng;
        markerData.lat = map.getCenter().lat;
        markerData.zoom = map.getZoom();
        markerData.pitch = map.getPitch();
        markerData.bearing = map.getBearing();

        try {
            const res = await axios.post('/api/markers', {
                name: markerData.name,
                description: markerData.description,
                lng: markerData.lng,
                lat: markerData.lat,
                zoom: markerData.zoom,
                pitch: markerData.pitch,
                bearing: markerData.bearing,
                user_id: markerData.user_id
            });

            markerData.id = res.data.marker.id;
            HideCenterMarker();
            AddMarker(markerData);
            ReloadMapMarkers();

            onSuccess(markerData, "Marcador creado con éxito");
        } catch (err) {
            console.error('Error al crear marcador:', err);
            onError(err.response?.data?.message || "Error al crear el marcador");
        }
    };

    const showMarkerById = async (id) => {
        try {
            const response = await axios.get('/api/markers/' + id);
            return response.data;
        } catch (error) {
            console.error("Error al cargar marcador por ID:", error);
            return [];
        }
    };

    const showAllMarkersFromUserId = async (user_id) => {
        try {
            const response = await axios.get(`/api/markers/getAllMarkersFromFriendId/?user_id=${user_id}`);
            return response.data;
        } catch (error) {
            console.error("Error al cargar marcadores:", error);
            return [];
        }
    };

    const getLastMarkersFromFriends = async () => {
        try {
            const response = await axios.get('/api/markers/getLastMarkerFromFriends');
            return response;
        } catch (error) {
            console.error("Error al obtener los últimos markers de amigos:", error);
            return [];
        }
    };


    return {
        markers,
        getMarkers,
        deleteMarker,
        createNewMarker,
        showMarkerById,
        showAllMarkersFromUserId,
        getLastMarkersFromFriends,
        DEFAULT_MARKER_DATA
    };
}
