<script setup>
import { computed, ref, onMounted } from "vue";
import { getEmojiById, getMakerListById as getMarkerListById } from '../composables/useMarkerList';

const props = defineProps({
  visible: Boolean,
  name: String,
  list: Number,
  description: String
});
const emit = defineEmits(['update:visible']);
const visible = computed({
    get: () => props.visible,
    set: val => emit('update:visible', val)
});

const listData = ref('');

onMounted(async () => {
  try {
    const emoji = await getEmojiById(props.list);
    const marker = await getMarkerListById(props.list);
    
    console.log(marker);

    listData.value = `${emoji} ${marker.name}`;
  } catch (error) {
    console.error('Error al cargar los datos:', error);
  }
});
</script>

<template>
    <Dialog position="bottom" v-model:visible="visible" class="popup bottom-popup">
      <div class="w-100 text-center popup-header">
        <h2 style="font-weight: 800;">{{ props.name }}</h2>
      </div>
  
      <div class="w-100 d-flex flex-column flex-grow-1">
        <h3 class="m-1">{{ listData }}</h3>

        <img src="images/ProfilePicture_0.jpg" class="w-75 m-auto">

        <p style="margin-left: 16px !important; height: auto; word-wrap: break-word; overflow-wrap: break-word; max-width: 100%; font-size: medium;">
          {{ props.description }}
        </p>

      </div>
    </Dialog>
  </template>
  