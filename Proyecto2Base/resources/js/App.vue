<template>
    <div>
        <router-view></router-view>
        <nav id="menunavegacion">
            <router-link class="boton1" to="/perfil">Perfil</router-link>
            <router-link class="boton1" to="/">Mapa</router-link>
            <router-link class="boton1" to="/amigos">Amigos</router-link>
        </nav>
    </div>
</template>

<script>
export default {
    name: 'App',
};
</script>