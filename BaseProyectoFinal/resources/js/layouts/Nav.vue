<template>
  <nav>
    <div class="navMenu" id="nav">

      <div class="nav-item" @click="handleNav('/profile')" style="cursor: pointer; position: relative;">
        <transition name="floating-circle" appear>
          <div class="floating-circle" v-if="isActive('/profile')"></div>
        </transition>
        <img src="/images/icon_profile.svg" alt="Profile" class="icon" />
        {{ $t('profile') }}
      </div>

      <div class="nav-item" style="position: relative;">
        <transition name="floating-circle" appear>
          <div class="floating-circle" v-if="isActive('/')"></div>
        </transition>
        <router-link to="/">
          <img src="/images/feed.svg" alt="Feed image" class="icon" />
          <p>Feed</p>
        </router-link>
      </div>

      <div class="nav-item" style="position: relative;">
        <transition name="floating-circle" appear>
          <div class="floating-circle" v-if="isActive('/home')"></div>
        </transition>
        <router-link to="/home" aria-current="page">
          <img src="/images/emoji_map.webp" :alt="$t('home')" />
        </router-link>
      </div>

      <div class="nav-item search-item" style="position: relative;">
        <transition name="floating-circle" appear>
          <div class="floating-circle" v-if="isActive('/search')"></div>
        </transition>
        <router-link to="/search">
          <img src="/images/icon_search.svg" alt="Search" class="icon" />
          <p>{{ $t('search') }}</p>
        </router-link>
      </div>

      <div class="nav-item" style="position: relative;">
        <transition name="floating-circle" appear>
          <div class="floating-circle" v-if="isActive('/settings')"></div>
        </transition>
        <router-link to="/settings">
          <img src="/images/settings.svg" alt="Settings image" class="icon" />
          <p>Settings</p>
        </router-link>
      </div>

    </div>
  </nav>
</template>

<script setup>
import useAuth from "@/composables/auth";
import { authStore } from "../store/auth";
import { useRouter, useRoute } from "vue-router";

const { processing, logout } = useAuth();
const router = useRouter();
const route = useRoute();

function isActive(path) {
  if (path === "/") {
    return route.path === "/";
  }
  return route.path.startsWith(path);
}

function handleNav(targetPath) {
  if (route.path.includes(targetPath)) {
    window.location.href = targetPath;
  } else {
    router.push(targetPath);
  }
}
</script>

<style scoped>
.navMenu {
  position: relative;
  display: flex;
  justify-content: space-around;
  align-items: flex-end;
}

.nav-item {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  cursor: pointer;
}

.search-item {
  /* Igual que nav-item, puedes modificar si quieres estilos distintos */
}

.floating-circle {
  position: absolute;
  top: -38px;
  left: 50%;
  transform: translateX(-50%) scale(1);
  width: 6rem;
  height: 6rem;
  max-width: none !important;
  max-height: none !important;
  background-color: var(--background0);
  border-radius: 50%;
  z-index: -1;

  transition: opacity 0.3s ease, transform 0.3s ease;
  opacity: 1;
}

/* Animaciones para la transición */

.floating-circle-enter-active,
.floating-circle-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.floating-circle-enter-from,
.floating-circle-leave-to {
  opacity: 0;
  transform: translateX(-50%) scale(0.8);
}

.floating-circle-enter-to,
.floating-circle-leave-from {
  opacity: 1;
  transform: translateX(-50%) scale(1);
}

.icon {
  width: 32px;
  height: 32px;
}
</style>
