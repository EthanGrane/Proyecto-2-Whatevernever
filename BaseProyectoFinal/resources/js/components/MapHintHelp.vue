<script setup>
import { ref } from 'vue';

const visible = ref(false);

function toggleVisible()
{
    visible.value = !visible.value;
}
</script>

<template>
    <div class="hint d-flex" @click="toggleVisible" style="box-shadow: 0 0 8px rgb(200, 200, 200);"><i class="pi pi-question m-auto" style="color: black;"></i></div>
    <Dialog v-model:visible="visible" modal :header="$t('home_hint_header')" class="HintDialog">
        <p style="margin-top: 5rem;">{{$t('home_hint_content')}}</p>
    </Dialog>
</template>

<style scoped>
.HintDialog{
    position: fixed !important; 

    right: 32px !important;
    bottom: 128px !important; 

    width: 25rem !important; 
    height: 15rem !important;
    border-radius: 1rem !important;
}

.HintDialog .p-dialog-header {
  width: 75% !important;
  margin: auto !important;
}

</style>