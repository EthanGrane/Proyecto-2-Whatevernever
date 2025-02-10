import './bootstrap';
import { createApp } from 'vue';

// import here CSS StyleSheet ->
import '../css/app.css';
import '../css/map-styles.css';

import App from './App.vue';
import router from './router';

const app = createApp(App);

app.use(router);
app.mount('#app');