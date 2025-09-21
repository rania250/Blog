<template>
  <div class="slider-simple">
    <!-- Loading -->
    <div v-if="loading" class="slider-loading">
      <h2>🔄 Chargement du slider...</h2>
      <p>Connexion à l'API Symfony...</p>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="slider-error">
      <h2>❌ Erreur slider</h2>
      <p>{{ error }}</p>
      <button @click="fetchSlides" class="retry-btn">Réessayer</button>
    </div>

    <!-- Slides -->
    <div v-else-if="slides.length > 0" class="slides-container">
      <div
        v-for="(slide, index) in slides"
        :key="slide.id"
        class="slide-item"
        :class="{ active: currentSlide === index }"
        :style="{ backgroundImage: 'url(' + slide.image + ')' }"
      >
        <div class="slide-content">
          <h1>{{ slide.title }}</h1>
          <p>{{ slide.description }}</p>
          <a
            :href="`http://localhost:8000/article/${slide.slug}`"
            target="_blank"
            class="slide-btn"
          >
            Lire l'article
          </a>
        </div>
      </div>

      <!-- Navigation -->
      <div v-if="slides.length > 1" class="slider-nav">
        <button
          v-for="(slide, index) in slides"
          :key="`nav-${slide.id}`"
          @click="goToSlide(index)"
          class="nav-dot"
          :class="{ active: currentSlide === index }"
        ></button>
      </div>

      <!-- Flèches -->
      <div v-if="slides.length > 1" class="slider-arrows">
        <button @click="prevSlide" class="arrow arrow-left">‹</button>
        <button @click="nextSlide" class="arrow arrow-right">›</button>
      </div>
    </div>

    <!-- Fallback -->
    <div v-else class="slider-fallback">
      <div class="slide-content">
        <h1>Bienvenue sur Jstore</h1>
        <p>Votre plateforme d'articles passionnants</p>
        <router-link to="/articles" class="slide-btn"
          >Découvrir nos articles</router-link
        >
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const slides = ref([]);
const currentSlide = ref(0);
const loading = ref(true);
const error = ref(null);
let interval = null;

const fetchSlides = async () => {
  loading.value = true;
  error.value = null;

  try {
    console.log("🔄 Chargement des slides depuis Symfony...");
    const response = await fetch("http://localhost:8000/api/slides");

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`);
    }

    const data = await response.json();
    slides.value = data;
    console.log("✅ Slides chargés !", slides.value.length);

    if (slides.value.length > 1) {
      startAutoPlay();
    }
  } catch (err) {
    console.error("❌ Erreur slides:", err);
    error.value = err.message;
  } finally {
    loading.value = false;
  }
};

const nextSlide = () => {
  if (slides.value.length > 0) {
    currentSlide.value = (currentSlide.value + 1) % slides.value.length;
  }
};

const prevSlide = () => {
  if (slides.value.length > 0) {
    currentSlide.value =
      currentSlide.value === 0
        ? slides.value.length - 1
        : currentSlide.value - 1;
  }
};

const goToSlide = (index) => {
  currentSlide.value = index;
};

const startAutoPlay = () => {
  interval = setInterval(nextSlide, 5000);
};

const stopAutoPlay = () => {
  if (interval) {
    clearInterval(interval);
    interval = null;
  }
};

onMounted(() => {
  console.log("🚀 HeroSlider monté");
  fetchSlides();
});

onUnmounted(() => {
  stopAutoPlay();
});
</script>

<style scoped>
/* STYLES ULTRA SIMPLES - PAS DE POSITIONNEMENT COMPLEXE */

.slider-simple {
  width: 100%;
  height: 70vh;
  min-height: 500px;
  background: linear-gradient(135deg, #000226 0%, #17193f 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.slides-container {
  width: 100%;
  height: 100%;
  position: relative;
}

.slide-item {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 1s ease-in-out;
}

.slide-item.active {
  opacity: 1;
}

.slide-content {
  text-align: center;
  color: white;
  padding: 2rem;
  background: rgba(0, 0, 0, 0.6);
  border-radius: 15px;
  backdrop-filter: blur(10px);
  max-width: 800px;
}

.slide-content h1 {
  font-size: 3rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
}

.slide-content p {
  font-size: 1.2rem;
  margin-bottom: 2rem;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
  line-height: 1.6;
}

.slide-btn {
  background: #000226;
  color: white;
  padding: 1rem 2rem;
  width: 7rem;
  border: none;
  border-radius: 50px;
  font-size: 1.1rem;
  font-weight: bold;
  text-decoration: none;
  display: inline-block;
  transition: all 0.3s ease;
}

.slide-btn:hover {
  background: #17193f;
  transform: translateY(-2px);
}

.slider-loading,
.slider-error,
.slider-fallback {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: white;
}

.slider-loading h2,
.slider-error h2 {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.retry-btn {
  background: white;
  color: #000226;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  margin-top: 1rem;
}

.slider-nav {
  position: absolute;
  bottom: 2rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 1rem;
}

.nav-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.5);
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.nav-dot.active,
.nav-dot:hover {
  background: white;
  transform: scale(1.2);
}

.slider-arrows {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 100%;
  display: flex;
  justify-content: space-between;
  padding: 0 2rem;
}

.arrow {
  background: rgba(255, 255, 255, 0.8);
  color: #333;
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  font-size: 1.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.arrow:hover {
  background: white;
  transform: scale(1.1);
}

@media (max-width: 768px) {
  .slider-simple {
    height: 50vh;
    min-height: 300px;
  }

  .slide-content h1 {
    font-size: 2rem;
  }

  .slide-content p {
    font-size: 1rem;
  }

  .slide-content {
    padding: 1rem;
    margin: 0 1rem;
  }

  .arrow {
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
  }
}
</style>
