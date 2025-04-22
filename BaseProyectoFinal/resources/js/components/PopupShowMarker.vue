<script setup>
import { computed, ref, watch, onMounted } from "vue";
import { getEmojiById, getMarkerListById as getMarkerListById } from '../composables/useMarkerList';
import { flyMapPositionAndRotation } from "../composables/MapUtils.js";

const props = defineProps({
  visible: Boolean,
  marker: Object
});
const emit = defineEmits(['update:visible']);
const visible = computed({
  get: () => props.visible,
  set: val => emit('update:visible', val)
});

const listData = ref('');
const loading = ref(false);

// Función que carga los datos
const loadMarkerData = async () => {
  try {
    loading.value = true;

    const marker = props.marker;
    const markerList = await getMarkerListById(marker.marker_list_id);
    const emoji = await getEmojiById(markerList.emoji_identifier);
    listData.value = `${emoji} ${markerList.name}`;

    flyMapPositionAndRotation([marker.lng, marker.lat], marker.zoom, marker.pitch, marker.bearing);
  } catch (error) {
    console.error('Error al cargar los datos:', error);
    listData.value = 'Error al cargar los datos';
  } finally {
    loading.value = false;
  }
};

// Ejecutar la carga de datos cuando el componente se monta
onMounted(() => {
  if (props.visible) {
    loadMarkerData(); // Cargar los datos si el componente ya está visible al montarse
  }
});

// Se ejecuta cada vez que visible cambia
watch(() => props.visible, async (newVal) => {
  if (!newVal) {
    listData.value = '';
    return;
  }

  loadMarkerData();
});
</script>

<template>
  <Dialog position="bottom" v-model:visible="visible" class="popup bottom-popup">
    <div class="w-100 pt-5 text-center popup-header">
      <h2 style="font-weight: 800;">{{ marker.name }}</h2>
    </div>

    <div class="w-100 d-flex flex-column flex-grow-1">
      <h3 class="m-1" style="font-style: italic;">{{ loading ? 'Cargando...' : listData }}</h3>

      <p
        style="margin-left: 16px !important; height: auto; word-wrap: break-word; overflow-wrap: break-word; max-width: 100%; font-size: medium;">
        {{ marker.description }}
      </p>

    </div>
  </Dialog>
</template>
