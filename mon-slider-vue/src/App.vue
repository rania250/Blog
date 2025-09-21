<template>
  <div id="app">
    <!-- Navigation fixe en haut -->
    <header class="nav-header">
      <div class="nav-container">
        <router-link to="/" class="logo">Jstore</router-link>
        
        <nav>
          <ul class="nav-menu" :class="{ active: mobileMenuOpen }">
            <li><router-link to="/" class="nav-link" @click="closeMobileMenu">ACCUEIL</router-link></li>
            <li><router-link to="/articles" class="nav-link" @click="closeMobileMenu">ARTICLES</router-link></li>
            <li><a href="#" class="nav-link" @click="closeMobileMenu">CATEGORIES</a></li>
            <li><a href="#" class="nav-link" @click="closeMobileMenu">CONTACT</a></li>
            <li><a href="#" class="nav-link" @click="closeMobileMenu">LOGIN</a></li>
            <li><a href="#" class="nav-link" @click="closeMobileMenu">REGISTER</a></li>
          </ul>
        </nav>
        
        <!-- Icône menu mobile -->
        <div class="nav-icon" @click="toggleMobileMenu">
          <i class="bi bi-list" v-if="!mobileMenuOpen"></i>
          <i class="bi bi-x" v-else></i>
        </div>
      </div>
    </header>

    <main class="page-content">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const mobileMenuOpen = ref(false)

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value
}

const closeMobileMenu = () => {
  mobileMenuOpen.value = false
}
</script>
