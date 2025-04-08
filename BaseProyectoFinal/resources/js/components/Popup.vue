<script setup>
import { ref, onMounted, computed } from "vue";
import Dialog from 'primevue/dialog';

const props = defineProps({
  visible: Boolean,
});
const emit = defineEmits(['update:visible']);


const visible = computed({
  get: () => props.visible,
  set: (val) => emit('update:visible', val)
});

const popupIndex = ref(0);

function NextPopupIndex() {
  popupIndex.value += 1;
  popupIndex.value = popupIndex.value % 3;
}

</script>

<template>
  <Dialog :position="bottom" v-model:visible="visible" class="popup bottom-popup">

    <div class="w-100 text-center popup-header">
      <h2 style="font-weight: 800;">New Marker</h2>
    </div>

    <!-- Post Info (Name, Description) -->
    <div v-if="popupIndex == 0" id="popup-newMarker-name" class="w-100 d-flex flex-column flex-grow-1">
      <label for="marker-name" style="font-weight: 600; font-size: large">Name</label>
      <input placeholder="Name Here!" class="popup-input" type="text" id="marker-name" v-model="name">

      <label for="marker-description" style="font-weight: 600; font-size: large;">Description</label>
      <textarea id="marker-description" v-model="description" class="popup-input" placeholder="Description Here!"
        style="height: 128px; width: 100%; resize: none;"></textarea>
    </div>

    <!-- Select Marker List -->
    <div v-if="popupIndex == 1" id="popup-newMarker-list" class="w-100 d-flex flex-column flex-grow-1"
      style="overflow-y: scroll; max-height: 50vh;">

      <div class="popup-list-item d-flex">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus-circle"
          viewBox="0 0 16 16">
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
          <path
            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
        </svg>
        <p class="w-100 m-auto" style="margin-left: 8px !important;">Create New List</p>
      </div>

      <div v-for="i in 50" class="popup-list-item d-flex">
        <p class="w-100 m-auto">🌉 San Francisco Travel</p>
        <div class="popup-list-item-active"></div>
      </div>

      <div class="popup-fade-bottom"></div>
    </div>

    <!-- Summary -->
    <div v-if="popupIndex == 2" id="popup-newMarker-summary" class="w-100 d-flex flex-column flex-grow-1">
      <h2 class="m-1">🌉 San Francisco Travel</h2>
      <h3 class="m-1">My Fav Park</h3>
      <p style="margin-left: 16px !important;">Esto es una descripcion de ejemplo sobre este marcador maravilloso el
        cual he marcado para poder tener marcada la ubicacion de este maravilloso parque.</p>
    </div>

    <div class="popup-footer">
      <button class="btn popup-button" @click="NextPopupIndex()">Next</button>
    </div>
  </Dialog>
</template>

<style>
.p-dialog-header
{
  padding: 0 !important;

  position: absolute;
  top: 8px;
  left: 8px;
}

.p-button
{
  background-color: var(--background2) !important;
  color:white  !important;
}

.p-button:hover
{
  background-color: white !important;
  color:black  !important;
}

.p-dialog-content
{
  padding-bottom: 0 !important;
  height: 100% !important;
}
</style>