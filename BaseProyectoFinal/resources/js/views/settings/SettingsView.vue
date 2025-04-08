<script setup>
import { ref } from 'vue';
import Friendsgroups from './pages/friendsgroups.vue';
import GeneralSettingsView from './pages/GeneralSettingsView.vue';
import AccountSettingsView from './pages/AccountSettingsView.vue';
import MarkerListSettingsView from './pages/MarkerListSettingsView.vue';

import useAuth from "@/composables/auth";

const { logout } = useAuth();

const conf_page = ref("general");
const show_pages = ref(true);

function changePage(page) {

    conf_page.value = page;
}
</script>

<template>
    <div class="settings-background">
        <div class="settings-side-menu">
            <button class="secondary-button" @click="changePage('general')">{{ $t('generalSettingsButton') }}</button>
            <button class="secondary-button" @click="changePage('account')">{{ $t('accountSettingsButton') }}</button>
            <button class="secondary-button" @click="changePage('groups')">{{ $t('groupsSettingsButton') }}</button>
            <button class="secondary-button" @click="changePage('markers')">{{ $t('markersSettingsButton') }}</button>

            <button class="secondary-button mt-auto w-75 danger-button-hover" style="padding: 8px !important;"
                @click="logout">{{ $t('signoutbutton') }}
            </button>
        </div>
        <div v-if="show_pages" class="settings-pages">
            <div v-if="conf_page == 'general'">
                <GeneralSettingsView />
            </div>

            <div v-if="conf_page == 'account'">
                <AccountSettingsView />
            </div>

            <div v-if="conf_page == 'groups'">
                <Friendsgroups />
            </div>

            <div v-if="conf_page == 'markers'">
                <MarkerListSettingsView />
            </div>
        </div>
    </div>
</template>