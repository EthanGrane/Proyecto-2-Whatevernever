import { createRouter, createWebHistory } from 'vue-router';
import Home from './views/Home.vue'; // Ruta del componente
import About from './components/About.vue'; // Ruta del componente
import Login from './views/auth/LoginView.vue';
import Perfil from './views/PerfilView.vue';
import Amigos from './views/AmigosView.vue';
import Register from './views/auth/RegisterView.vue';

const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/about', name: 'About', component: About },
    { path: '/login', name: 'Login', component: Login},
    { path: '/register', name: 'Register', component: Register},
    { path: '/Perfil', name: 'Perfil', component: Perfil},
    { path: '/Amigos', name: 'Amigos', component: Amigos},
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
