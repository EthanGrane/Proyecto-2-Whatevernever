<template>
    <div>
    <Header></Header>
    <router-view></router-view>
    <Navbar></Navbar>
    </div>
</template>
<script setup>
import Navbar from './Nav.vue';
import Header from './Header.vue';

</script>
