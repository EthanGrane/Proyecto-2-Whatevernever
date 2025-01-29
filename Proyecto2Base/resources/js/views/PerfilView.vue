<script setup>
import { ref } from "vue";

const menuVisible = ref(false);

function toggleMenuLateral() {
    menuVisible.value = !menuVisible.value;
}
</script>

<template>
    <div id="fondoperfil">
        <div>
            <img src="../../../public/images/mono.webp" alt="Pablo">
            <div id="informacionperfil">
                <h2>Usuario 1</h2>
                <p>Informacion Personal</p>
            </div>
        </div>
        <div id="map">
        </div>
        <button id="botonmarcadoresperfil" :class="{ activo: menuVisible}" @click="toggleMenuLateral">></button>
        <div id="menulateralperfil" :class="{ activo: menuVisible }">
            <h2>Marcadores</h2>
        </div>
    </div>
</template>