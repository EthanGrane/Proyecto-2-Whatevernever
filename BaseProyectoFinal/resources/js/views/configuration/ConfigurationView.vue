<script setup>
import { ref } from 'vue';
import Friendsgroups from '../../components/friendsgroups.vue';
import GeneralConfigurationView from '../../components/GeneralConfigurationView.vue';
import AccountConfigurationView from '../../components/AccountConfigurationView.vue';
import MarquerListConfiguraitonView from '../../components/MarquerListConfigurationView.vue';

import useAuth from "@/composables/auth";

const { logout } = useAuth();

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
            <button class="secondary-button" @click="changePage('account')" >{{ $t('accountconfigurationbutton') }}</button>
            <button class="secondary-button" @click="changePage('groups')" >{{ $t('groupsconfigurationbutton') }}</button>
            <button class="secondary-button" @click="changePage('markers')" >{{ $t('markersconfigurationbutton') }}</button>

            <button class="secondary-button sign-out-button" @click="logout" >{{ $t('signoutbutton') }}</button>
        </div>
        <transition name="fade" mode="out-in">
            <div v-if="show_pages" class="configuration-pages">
                <div v-if="conf_page == 'general'">
                    <GeneralConfigurationView/>
                </div>

                <div v-if="conf_page == 'account'">
                    <AccountConfigurationView/>
                </div>

                <div v-if="conf_page == 'groups'">
                    <Friendsgroups/>
                </div>

                <div v-if="conf_page == 'markers'">
                    <MarquerListConfiguraitonView></MarquerListConfiguraitonView>
                </div>
            </div>
        </transition>
    </div>
</template>