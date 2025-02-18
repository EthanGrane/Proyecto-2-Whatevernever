<template>
    <header>
        <div>
            <router-link to="/friends">
                <img src="/images/icon_friends.svg" class="icon">
            </router-link>
        </div>
        <div>
            <h2>{{$t('brandName')}}.</h2>
        </div>
        <div>
            <LocaleSwitcher />
        </div>
    </header>
</template>

<script setup>
import LocaleSwitcher from "../components/LocaleSwitcher.vue";
</script>