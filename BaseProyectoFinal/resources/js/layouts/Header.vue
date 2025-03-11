<template>
    <header>
        <div>
            <router-link to="/friends">
                <img src="/images/icon_notification-bell.svg" class="icon icon-shake-hover">
            </router-link>
        </div>
        <div>
            <h2 class="brandName">{{$t('brandName')}}.</h2>
        </div>
        <div>
            <LocaleSwitcher />
        </div>
    </header>
</template>

<script setup>
import LocaleSwitcher from "../components/LocaleSwitcher.vue";
</script>