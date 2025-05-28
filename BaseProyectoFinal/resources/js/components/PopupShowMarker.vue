<script setup>
import { computed, ref, watch, onMounted } from "vue";
import { getEmojiById, getMarkerListById } from '@/composables/useMarkerList';
import { flyMapPositionAndRotation } from "@/composables/MapUtils.js";
import { GetAvgStarsByMarkerId, SetReviewToMarker, GetMyReviewByMarkerId } from "@/composables/useMarkerReviews";

const props = defineProps({
  visible: Boolean,
  marker: Object
});
const emit = defineEmits(['update:visible']);
const visible = computed({
  get: () => props.visible,
  set: val => emit('update:visible', val)
});

let currentMarkerId = ref(null);
const listData = ref('');
const loading = ref(false);
const rating_avg = ref({ average_stars: 0 });   // Es el valor promedio que tiene el marcador
const rating_client_value = ref();              // Es el valor que le da el usuario al marcador

// Ejecutar la carga de datos cuando el componente se monta
onMounted(async () => {
  if (props.visible) {
    await loadMarkerData(); // Cargar los datos si el componente ya está visible al montarse
  }
});

// Observar cambios en el marcador y en la visibilidad
watch([() => props.marker, () => props.visible], async ([newMarker, isVisible]) => {
  if (isVisible) {
    await loadMarkerData(newMarker);
  }
});

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

// Función que carga los datos
const loadMarkerData = async (marker = props.marker) => {
  try {
    loading.value = true;

    if (currentMarkerId.value !== marker.id) {
      rating_avg.value = { average_stars: 0 };
      currentMarkerId.value = marker.id;
    }

    const markerList = await getMarkerListById(marker.marker_list_id);
    const emoji = await getEmojiById(markerList.emoji_identifier);

    if (markerList.value)
      listData.value = `${emoji} ${markerList.name}`;
    else
      listData.value = `${emoji} All Markers`;

    // Estrellas promedias
    rating_avg.value = await GetAvgStarsByMarkerId(props.marker.id) || { average_stars: 0 };

    // Mis estrellas
    const myReviewResponse = await GetMyReviewByMarkerId(marker.id);
    if (myReviewResponse && myReviewResponse.review) {
      rating_client_value.value = myReviewResponse.review.review_stars;
    } else {
      rating_client_value.value = null;
    }

    flyMapPositionAndRotation([marker.lng, marker.lat], marker.zoom, marker.pitch, marker.bearing);

  } catch (error) {
    console.error('Error al cargar los datos:', error);
    listData.value = 'Error al cargar los datos';
  } finally {
    loading.value = false;
  }
};

// Place a review code:
const showRatingDialog = ref(false);
function openRatingDialog() {
  showRatingDialog.value = true;
}

async function RateMarker() {

  await SetReviewToMarker(props.marker.id, rating_client_value.value);
  showRatingDialog.value = false;

  await sleep(100);
  rating_avg.value = await GetAvgStarsByMarkerId(props.marker.id) || { average_stars: 0 };
}

</script>


<template>
  <Dialog position="bottom" v-model:visible="visible" class="popup bottom-popup">
    <div class="w-100 pt-5 text-center popup-header">
      <h2 style="font-weight: 800;">{{ marker.name }}</h2>
    </div>

    <div class="w-100 d-flex flex-column flex-grow-1 p-3">
      <h3 class="m-1">{{ loading ? 'Cargando...' : listData }}</h3>

      <p
        style="margin-left: 16px !important; height: auto; word-wrap: break-word; overflow-wrap: break-word; max-width: 100%; font-size: medium;">
        {{ marker.description }}
      </p>
    </div>

    <div class="w-75 d-flex flex-grow-1 justify-content-center align-items-center m-auto p-3" style="max-height: 64px;">
      <Rating v-model="rating_avg.average_stars" :stars="10" readonly></Rating>
      <p class="m-2" style="font-weight: normal;">({{ rating_avg.count }})</p>
    </div>

    <span>
      <Button class="primary-button w-75 m-2" @click="openRatingDialog">
        Review
      </Button>

      <Button class="primary-button w-auto" style="border-radius: 50% !important; width: 45px !important; height: 45px !important;" @click="openRatingDialog">
        <i class="pi pi-star"></i>
        <i v-if="false" class="pi pi-star-fill"></i>
      </Button>
    </span>
  </Dialog>

  <!-- Diálogo para dar la valoración (rating) -->
  <Dialog v-model:visible="showRatingDialog" modal>
    <div class="w-100 m-auto d-flex flex-column align-items-center p-3">
      <p class="w-100">Place a Review:</p>
      <Rating @click="RateMarker" v-model="rating_client_value" :stars="10"></Rating>
    </div>
  </Dialog>
</template>

<style scoped>

</style>