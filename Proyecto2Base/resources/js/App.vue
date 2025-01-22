<template>
    <div>
        <router-view></router-view>
        <nav id="menunavegacion">
            <router-link class="boton1" to="/">Home</router-link>
            <router-link class="boton1" to="/about">About</router-link>
        </nav>
    </div>
</template>

<script>
export default {
    name: 'App',
};
</script>