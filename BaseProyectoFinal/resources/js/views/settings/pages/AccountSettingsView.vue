<script setup>
import { ref } from "vue";

const color = ref();
</script>

<template>
    <h2>Configuracion Cuenta</h2>

    <div style="margin-left: 32px !important;">

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>
                <p class="settings-option-title">Modificar foto de perfil</p>
                <p class="settings-option-description">Cambiar imagen con la que los otros usuarios pueden asociarte.
                </p>
            </span>
            <button class="btn secondary-button" style="padding-left: 32px !important; padding-right: 32px !important;">
                <span>(icon)</span>
                Change
            </button>
        </div>
        <hr>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>
                <p class="settings-option-title">Modificar nombre de perfil</p>
                <p class="settings-option-description">Cambiar nombre (no el @username) con la que los otros usuarios
                    pueden asociarte.</p>
            </span>
            <button class="btn secondary-button" style="padding-left: 32px !important; padding-right: 32px !important;">
                <span>(icon)</span>
                Change
            </button>
        </div>
        <hr>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>
                <p class="settings-option-title">Modificar contraseña</p>
                <p class="settings-option-description">Cambiar nombre (no el @username) con la que los otros usuarios
                    pueden asociarte.</p>
            </span>
            <button class="btn secondary-button" style="padding-left: 32px !important; padding-right: 32px !important;">
                <span>(icon)</span>
                Change
            </button>
        </div>
        <hr>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>
                <p class="settings-option-title">Modificar color de cuenta</p>
                <p class="settings-option-description">Cambiar nombre (no el @username) con la que los otros usuarios
                    pueden asociarte.</p>
            </span>
            <div class="card flex justify-center">
                <ColorPicker v-model="color" format="hex" style="background: fixed !important;" />
            </div>
        </div>
        <hr>

    </div>

</template>