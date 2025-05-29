<template>
  <div class="feed-card" @mouseover="hovering = true" @mouseleave="hovering = false">
    <!-- Fondo rojo -->
    <div :class="`feed-card-gradient-${index} feed-card-background`"></div>

    <!-- Fondo mapa con transición -->
    <div v-if="mapUrl" class="feed-card-background map-bg"
      :style="{ backgroundImage: 'url(' + mapUrl + ')', opacity: hovering ? 1 : 0 }"></div>

    <div class="feed-card-pfp" :style="{ backgroundImage: 'url(/images/placeholder.jpg)' }"></div>
    <h2 class="feed-card-title" :class="{ hidden: hovering }">{{ marker.name }}</h2>
    <p class="feed-card-description" :class="{ hidden: hovering }">{{ marker.description }}</p>

  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import { GetMapImageUrlFromCoordsAndZoom } from "../composables/MapUtils";

export default {
  props: {
    marker: { type: Object, required: true },
    index: { type: Number, required: false }
  },
  setup(props) {
    const mapUrl = ref(null);
    const hovering = ref(false);

    onMounted(async () => {
      mapUrl.value = await GetMapImageUrlFromCoordsAndZoom({
        lng: props.marker.lng,
        lat: props.marker.lat
      });
    });

    return { mapUrl, hovering };
  }
};
</script>


<style scoped>
.feed-card {
  position: relative;
  width: 30vh;
  height: 30vh;
  border-radius: 2rem;
  overflow: hidden;
}

.feed-card-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  transition: opacity 0.25s ease;
  z-index: 0;
}


.map-bg {
  z-index: 1;
  opacity: 0;
}

.feed-card-pfp {
  position: absolute;
  bottom: 0;
  right: 0;
  border-radius: 100%;
  width: 5vw;
  height: 5vw;
  background-position: center;
  background-size: cover;
  z-index: 2;
}

.feed-card-title,
.feed-card-description {
  position: relative;
  z-index: 2;
}

.feed-card-title {
  margin-top: 24px;
  margin-left: 24px;
}

.feed-card-description {
  margin: 0px 32px;
  word-wrap: break-word;
  overflow-wrap: break-word;
}

.feed-card-title,
.feed-card-description {
  position: relative;
  z-index: 2;
  transition: opacity 0.25s ease, transform 0.25s ease;
}

.hidden {
  opacity: 0;
  transform: translateY(10px);
  pointer-events: none;
}
</style>
