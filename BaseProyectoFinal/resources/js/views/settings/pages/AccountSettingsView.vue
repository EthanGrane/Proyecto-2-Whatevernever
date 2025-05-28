<script setup>
import { ref } from "vue";
import axios from "axios";
import { useToast } from "primevue/usetoast";
import SettingField from "@/components/SettingField.vue";
import { useUsers } from "@/composables/useUser";

const toast = useToast();

const color = ref("");
const new_username = ref("");
const new_description = ref("");
const userApi = useUsers();

// Toast
function showMessage(message, type) {
  let adapt_type = type === "good" ? "success" : type === "bad" ? "error" : "warn";
  if (adapt_type === "warn") console.log(message);

  toast.add({ severity: adapt_type, summary: 'Info', detail: message, life: 3000 });
}

const handleFileChange = async (e) => {
  const file = e.target.files[0];
  if (!file) return;

  const response = await userApi.updateUserImage(file);

  if (response?.success) {
    console.log(response.data);
    showMessage("Image updated", "good");
  } else {
    console.error(response?.error);
    showMessage("Error while uploading image", "bad");
  }
};

const updateUsername = async () => {
  const response = await userApi.updateUserName(new_username.value);

  if (response?.success) {
    showMessage("Name updated", "good");
  } else {
    showMessage("Error while updating name", "bad");
  }
};

const updateDescription = async () => {

  const response = await userApi.updateUserDescription(new_description.value)
  if (response?.success) {
    showMessage("Description updated", "good");
  } else {
    showMessage("Error while updating description", "bad");
  }
};
</script>

<template>
  <div>
    <!-- Foto de perfil -->
    <SettingField title="Modificar foto de perfil"
      description="Cambiar imagen con la que los otros usuarios pueden asociarte." type="file" accept="image/*"
      @change="handleFileChange" />
    <hr />

    <!-- Nombre -->
    <SettingField v-model="new_username" title="Modificar nombre de perfil"
      description="Cambiar nombre (no el @username) con la que los otros usuarios pueden asociarte." type="input"
      placeholder="New name" icon="pi-pen-to-square" @submitInput="updateUsername" />
    <hr />

    <!-- Descripción -->
    <SettingField v-model="new_description" title="Modificar descripcion de usuario"
      description="Cambiar descripcion para que la vean otros usuarios." type="input" placeholder="New description"
      icon="pi-pen-to-square" @submitInput="updateDescription" />
    <hr />

    <!-- Contraseña -->
    <SettingField title="Modificar contraseña" description="Cambiar contraseña de la cuenta." type="button"
      icon="pi-pen-to-square" @click="() => showMessage('Cambiar contraseña no implementado aún', 'warn')" />
    <hr />

    <!-- Color 
    <SettingField
      v-model="color"
      title="Modificar color de cuenta"
      description="Color de identificación de tu cuenta para los demás."
      type="color"
    />

    <hr />
-->
    <Toast />
  </div>
</template>

<style>
.p-button-text.p-button-secondary
{
  background-color: var(--background2) !important;
}
</style>