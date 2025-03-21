<script setup>
import { ref } from 'vue';
import Friendsgroups from '../../components/friendsgroups.vue';

const conf_page = ref("general");
const show_pages = ref(true);

function changePage(page) {
    show_pages.value = false;

    conf_page.value = page;

    setTimeout(() => {show_pages.value = true}, 500);
}

</script>

<template>
    <div class="configuration-background">
        <div class="configuration-console">
            <button class="secondary-button" @click="changePage('general')" >{{ $t('generalconfigurationbutton') }}</button>
            <button class="secondary-button" @click="changePage('account')" >Account</button>
            <button class="secondary-button" @click="changePage('groups')" >Groups</button>

            <button class="secondary-button sign-out-button" >Sign Out</button>
        </div>
        <transition name="fade" mode="out-in">
            <div v-if="show_pages" class="configuration-pages">
                <div v-if="conf_page == 'general'">
                    <h2>General page</h2>
                </div>

                <div v-if="conf_page == 'account'">
                    <h2>Account page</h2>
                </div>

                <div v-if="conf_page == 'groups'">
                    <Friendsgroups/>
                </div>
            </div>
        </transition>
    </div>
</template>