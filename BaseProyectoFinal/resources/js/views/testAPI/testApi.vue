<script setup>
import { ref } from 'vue';
import axios from 'axios';

const responses = ref([]);
const requestMethod = ref('GET');
const requestUrl = ref('');
const requestData = ref('');

async function sendRequest() {
    try {
        let res;
        if (requestMethod.value === 'GET') {
            res = await axios.get(requestUrl.value);
        } else if (requestMethod.value === 'POST') {
            res = await axios.post(requestUrl.value, JSON.parse(requestData.value || '{}'));
        } else if (requestMethod.value === 'PUT') {
            res = await axios.put(requestUrl.value, JSON.parse(requestData.value || '{}'));
        } else if (requestMethod.value === 'DELETE') {
            res = await axios.delete(requestUrl.value);
        }
        responses.value.unshift({ method: requestMethod.value, url: requestUrl.value, data: res.data });
    } catch (error) {
        console.error("Error:", error);
        responses.value.unshift({ method: requestMethod.value, url: requestUrl.value, data: error.response ? error.response.data : "Request failed" });
    }
}
</script>

<template>
    <div class="w-50 d-flex flex-column m-auto">
        <h1 class="text-center">API Request Testing</h1>

        <label for="method">Request Method:</label>
        <select v-model="requestMethod">
            <option value="GET">GET</option>
            <option value="POST">POST</option>
            <option value="PUT">PUT</option>
            <option value="DELETE">DELETE</option>
        </select>

        <label for="url">Set URL:</label>
        <input v-model="requestUrl" type="text" name="url" style="border: 1px solid white !important;">

        <label v-if="requestMethod !== 'GET'" for="data">Request Data (JSON):</label>
        <textarea v-if="requestMethod !== 'GET'" v-model="requestData" style="border: 1px solid white !important;"></textarea>

        <button @click="sendRequest">Send Request</button>

        <label for="responses">Request Data:</label>
        <div>
            <pre v-for="(res, index) in responses" :key="index" class="p-3" style="border: 1px dashed rgb(200, 200, 200); white-space: pre-wrap;">
                <strong>{{ res.method }} - {{ res.url }}</strong>\n{{ res.data }}
            </pre>
        </div>
    </div>
</template>