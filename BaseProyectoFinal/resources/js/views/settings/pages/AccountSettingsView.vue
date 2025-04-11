<script setup>
import { ref } from "vue";
import axios from "axios";
import { useToast } from "primevue/usetoast";

const toast = useToast();

const color = ref();
const imageFile = ref(null);
const fileInput = ref(null);
const new_username = ref();
const new_description = ref();

const triggerFileInput = () => {
    fileInput.value.click();
};

function showMessage(message, type) {
    let adapt_type = "";

    if (type == "good") {
        adapt_type = "success";
    } else if (type == "bad") {
        adapt_type = "error";
    } else {
        adapt_type = "warn";
        console.log(message);
    }

    toast.add({ severity: adapt_type, summary: 'Info', detail: message, life: 3000 });
}

// Cuando se selecciona un archivo se sube
const handleFileChange = async (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("image", file);

    try {
        const response = await axios.post("http://127.0.0.1:8000/api/users/updateimg", formData, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
        });

        console.log(response);

        showMessage("Image updated", "good");
    } catch (err) {
        console.error(err);
        showMessage("Error while uploading image", "bad");
    }
};

async function updateUsername(username) {
    try {
        const response = await axios.post("http://127.0.0.1:8000/api/users/updateusername", {
            'username': username,
        })

        showMessage("Name updated", "good");
        console.log(response);

    } catch (error) {
        showMessage("Error while updating name", "bad");
        console.log(error);
    }
}

async function updateDescription(desc) {
    try {
        const response = await axios.post("http://127.0.0.1:8000/api/users/updatedescription", {
            'desc': desc,
        })

        showMessage("Description updated", "good");
        console.log(response);

    } catch (error) {
        showMessage("Error while updating description", "bad");
        console.log(error);
    }
}
</script>

<template>
    <h2>Configuración Cuenta</h2>

    <div style="margin-left: 32px !important;">
        <!-- Actualizar imagen -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
        <span>
            <p class="settings-option-title">Modificar foto de perfil</p>
            <p class="settings-option-description">
            Cambiar imagen con la que los otros usuarios pueden asociarte.
            </p>
        </span>
        <button
            class="btn secondary-button"
            style="padding-left: 32px !important; padding-right: 32px !important;"
            @click="triggerFileInput">
            <span>(icon)</span>
            Change
        </button>
        <input
            type="file"
            accept="image/*"
            ref="fileInput"
            @change="handleFileChange"
            style="display: none;"
        />
        </div>
        <hr />

        <!-- Otros campos -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>
                <p class="settings-option-title">Modificar nombre de perfil</p>
                <p class="settings-option-description">
                Cambiar nombre (no el @username) con la que los otros usuarios pueden asociarte.
                </p>
            </span>
            <div class="secondary-button">
                <input placeholder="New name" v-model="new_username">
                <button @click="updateUsername(new_username)" class="btn secondary-button" style="padding-left: 32px !important; padding-right: 32px !important;">
                    <span>(icon)</span>
                    Change
                </button>
            </div>
        </div>
        <hr />

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>
                <p class="settings-option-title">Modificar descripcion de usuario</p>
                <p class="settings-option-description">
                Cambiar descripcion para que la vean otros usuarios.
                </p>
            </span>
            <div class="secondary-button">
                <input placeholder="New description" v-model="new_description">
                <button @click="updateDescription(new_description)" class="btn secondary-button" style="padding-left: 32px !important; padding-right: 32px !important;">
                    <span>(icon)</span>
                    Change
                </button>
            </div>
        </div>
        <hr />

        <div style="display: flex; justify-content: space-between; align-items: center;">
        <span>
            <p class="settings-option-title">Modificar contraseña</p>
            <p class="settings-option-description">
            Cambiar nombre (no el @username) con la que los otros usuarios pueden asociarte.
            </p>
        </span>
        <button class="btn secondary-button" style="padding-left: 32px !important; padding-right: 32px !important;">
            <span>(icon)</span>
            Change
        </button>
        </div>
        <hr />

        <div style="display: flex; justify-content: space-between; align-items: center;">
        <span>
            <p class="settings-option-title">Modificar color de cuenta</p>
            <p class="settings-option-description">
            Cambiar nombre (no el @username) con la que los otros usuarios pueden asociarte.
            </p>
        </span>
        <div class="card flex justify-center">
            <ColorPicker v-model="color" format="hex" style="background: fixed !important;" />
        </div>
        </div>
        <hr />
        <Toast />
    </div>
</template>
