<script setup>
import LocaleSwitcher from "../../../components/LocaleSwitcher.vue";
</script>

<template>
    <h2>Configuracion General</h2>

        <div style="margin-left: 32px !important;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>
                <p class="settings-option-title">Language</p>
                <p class="settings-option-description">Change the lenguaje of all page.</p>
            </span>
            <LocaleSwitcher />
        </div>

        <hr>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>
                <p class="settings-option-title">Hide my Real-Time position</p>
                <p class="settings-option-description">Hiddes your real time position so people can't track you</p>
            </span>
            <LocaleSwitcher />
        </div>

        <hr>

        <p class="settings-option-title">Hide my Real-Time position</p>
        <p class="settings-option-description">Hiddes your real time position so people can't track you</p>

        <hr>
    </div>

</template>