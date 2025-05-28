<script setup>
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import Friendsgroups from './pages/friendsgroups.vue';
import GeneralSettingsView from './pages/GeneralSettingsView.vue';
import AccountSettingsView from './pages/AccountSettingsView.vue';
import MarkerListSettingsView from './pages/MarkerListSettingsView.vue';
import useAuth from "@/composables/auth";

const { logout } = useAuth();
const { t } = useI18n();

const conf_page = ref("general");
const show_pages = ref(true);

function changePage(page) {
    conf_page.value = page;
}

const menuItems = computed(() => [
    {
        label: t('generalSettingsButton'),
        icon: 'pi pi-align-justify',
        style: conf_page.value === 'general' ? { border: '2px solid var(--background4)' } : {},
        command: () => changePage('general')
    },
    {
        label: t('accountSettingsButton'),
        icon: 'pi pi-user',
        style: conf_page.value === 'account' ? { border: '2px solid var(--background4)' } : {},
        command: () => changePage('account')
    },
    {
        label: t('groupsSettingsButton'),
        icon: 'pi pi-users',
        style: conf_page.value === 'groups' ? { border: '2px solid var(--background4)' } : {},
        command: () => changePage('groups')
    },
    {
        label: t('markersSettingsButton'),
        icon: 'pi pi-map-marker',
        style: conf_page.value === 'markers' ? { border: '2px solid var(--background4)' } : {},
        command: () => changePage('markers')
    }
]);


</script>


<template>
    <Menubar class="mobile-menubar" :model="menuItems" />

    <div class="settings-background">

        <div class="settings-side-menu">

            <Button class="secondary-button" :class="{ active: conf_page === 'general' }" @click="changePage('general')"
                icon="pi pi-align-justify" :label="$t('generalSettingsButton')" />
            <Button class="secondary-button" :class="{ active: conf_page === 'account' }" @click="changePage('account')"
                icon="pi pi-user" :label="$t('accountSettingsButton')" />
            <Button class="secondary-button" :class="{ active: conf_page === 'groups' }" @click="changePage('groups')"
                icon="pi pi-users" :label="$t('groupsSettingsButton')" />
            <Button class="secondary-button" :class="{ active: conf_page === 'markers' }" @click="changePage('markers')"
                icon="pi pi-map-marker" :label="$t('markersSettingsButton')" />

            <hr>
            <Button class="secondary-button danger-button-hover" style="padding: 8px !important;" @click="logout"
                icon="pi pi-sign-out" :label="$t('signoutbutton')" />
        </div>

        <div v-if="show_pages" class="settings-pages w-100 m-1">
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

<style scoped>
.secondary-button.active {
    background-color: var(--background4) !important;
}

.p-menubar {
    color: white !important;
    background: none !important;
    border: none;
    margin-top: 8px;
    margin-left: 8px;
}

/* Cuando el ancho de la pantalla sea menor a 512px, ocultamos el texto */
@media (max-width: 512px) {
    .secondary-button .p-button-label {
        display: none;
    }

    .settings-side-menu button {
        width: 42px !important;
        height: 42px !important;
        margin: 0 !important;
        padding: 0px !important;
        border-radius: 50% !important;
    }
}

@media (max-width: 768px) {
    .settings-side-menu {
        display: none;
    }
}

.mobile-menubar {
    display: none;
}

@media (max-width: 768px) {
    .mobile-menubar {
        display: block;
    }

    .settings-side-menu {
        display: none;
    }
}
</style>

<style>
.p-menubar-button {
    color: white !important;
}

.p-menubar-button:hover {
    background: none !important;
}

.p-menubar-mobile .p-menubar-root-list {
    background-color: white !important;
    border: none !important;
}

.p-menubar-item-link {
    color: black !important;
}

.p-menubar-item-content:hover {
    background-color: white !important;
}

.p-menubar-root-list>.p-menubar-item>.p-menubar-item-content {
    background-color: white !important;
}

.p-menubar-item-icon {
    color: black !important;
}
</style>