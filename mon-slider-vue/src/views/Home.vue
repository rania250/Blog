<template>
  <div class="home-page">

    <HeroSlider />

    <!-- 2. SECTION ARTICLES EN DESSOUS -->
    <section class="articles-section">
      <div class="container">
        <!-- En-tête de section -->
        <div class="section-header">
          <h2>Articles récents</h2>
          <p>Découvrez nos derniers articles passionnants</p>
        </div>

        <!-- Compteur d'articles -->
        <div
          v-if="!store.loading && store.articles.length > 0"
          class="articles-counter"
        >
          <div class="counter-text">
            <span class="counter-number">{{ store.articlesCount }}</span>
            article{{ store.articlesCount > 1 ? "s" : "" }} retrouvé{{
              store.articlesCount > 1 ? "s" : ""
            }}
          </div>
        </div>

        <!-- État de chargement -->
        <div v-if="store.loading" class="loading-state">
          <div class="loading-spinner"></div>
          <h3>🔄 [Pinia] Chargement des articles...</h3>
          <p>Connexion à l'API Symfony via Pinia store</p>
        </div>

        <!-- État d'erreur -->
        <div v-else-if="store.error" class="error-state">
          <h3>❌ Erreur de chargement</h3>
          <p>{{ store.error }}</p>
          <button @click="store.fetchArticles" class="btn-primary">
            Réessayer
          </button>
        </div>

        <!-- Grille d'articles -->
        <div v-else-if="store.articles.length > 0" class="articles-grid">
          <article
            v-for="(article, index) in store.articles.slice(0, 6)"
            :key="article.id"
            class="article-card"
            @click="goToArticle(article.slug)"
          >
            <div class="article-image">
              <img :src="article.image" :alt="article.title" />
              <div class="article-overlay">
                <span class="read-more">Lire l'article</span>
              </div>
            </div>

            <div class="article-content">
              <h3 class="article-title">{{ article.title }}</h3>
              <p class="article-description">
                {{ truncateText(article.description, 120) }}
              </p>

              <div class="article-meta">
                <div class="author">
                  <div
                    class="author-avatar"
                    :style="{
                      backgroundColor: getAuthorColor(
                        article.author?.fullname || 'Jstore'
                      ),
                    }"
                  >
                    {{
                      getAuthorInitials(article.author?.fullname || "Jstore")
                    }}
                  </div>
                  <span>{{ article.author?.fullname || "Auteur Jstore" }}</span>
                </div>
                <div class="date">
                  {{ formatDate(article.createdAt) }}
                </div>
              </div>
            </div>
          </article>
        </div>

        <!-- Aucun article -->
        <div v-else class="loading-state">
          <h3>📭 Aucun article trouvé</h3>
          <p>Il n'y a pas encore d'articles dans votre base de données.</p>
        </div>

        
        <div v-if="store.articles.length > 6" class="view-more-section">
          <h3>Encore {{ store.articles.length - 6 }} articles à découvrir !</h3>
          <router-link to="/articles" class="btn-primary">
            Voir tous les {{ store.articlesCount }} articles →
          </router-link>
        </div>

        <!-- Statistiques PINIA -->
        <div
          v-if="store.articles.length > 0"
          class="stats-section"
          style="
            margin-top: 3rem;
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
          "
        >
          <div
            style="
              background: white;
              padding: 1.5rem;
              border-radius: 10px;
              text-align: center;
              box-shadow: var(--shadow);
              min-width: 200px;
            "
          >
            <h4
              style="
                color: var(--primary-color);
                font-size: 2rem;
                margin-bottom: 0.5rem;
              "
            >
              {{ store.articlesCount }}
            </h4>
            <p>Articles (Pinia)</p>
          </div>
          <div
            style="
              background: white;
              padding: 1.5rem;
              border-radius: 10px;
              text-align: center;
              box-shadow: var(--shadow);
              min-width: 200px;
            "
          >
            <h4
              style="
                color: var(--secondary-color);
                font-size: 2rem;
                margin-bottom: 0.5rem;
              "
            >
              {{ store.uniqueAuthors }}
            </h4>
            <p>Auteurs (Pinia)</p>
          </div>
          <div
            style="
              background: white;
              padding: 1.5rem;
              border-radius: 10px;
              text-align: center;
              box-shadow: var(--shadow);
              min-width: 200px;
            "
          >
            <h4
              style="
                color: var(--primary-color);
                font-size: 2rem;
                margin-bottom: 0.5rem;
              "
            >
              {{ store.recentArticles }}
            </h4>
            <p>Cette semaine (Pinia)</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useArticlesStore } from "../stores/articles";
import HeroSlider from "../components/HeroSlider.vue";

// 🎯 UTILISATION DE PINIA
const store = useArticlesStore();

const goToArticle = (slug) => {
  window.open(`http://localhost:8000/article/${slug}`, "_blank");
};

const formatDate = (dateString) => {
  try {
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
      day: "numeric",
      month: "long",
    });
  } catch {
    return "Date inconnue";
  }
};

const truncateText = (text, maxLength) => {
  if (!text) return "Pas de description disponible";
  if (text.length <= maxLength) return text;
  return text.substring(0, maxLength).trim() + "...";
};

const getAuthorInitials = (fullname) => {
  if (!fullname) return "J";
  const names = fullname.split(" ");
  return names.length > 1
    ? names[0][0] + names[1][0]
    : names[0][0] + (names[0][1] || "");
};

const getAuthorColor = (fullname) => {
  const colors = [
    "#667eea",
    "#764ba2",
    "#f093fb",
    "#f5576c",
    "#4facfe",
    "#00f2fe",
    "#43e97b",
    "#38f9d7",
  ];

  if (!fullname) return colors[0];

  let hash = 0;
  for (let i = 0; i < fullname.length; i++) {
    hash = fullname.charCodeAt(i) + ((hash << 5) - hash);
  }

  return colors[Math.abs(hash) % colors.length];
};

onMounted(() => {
  console.log("🚀 [Home] Composant monté - Utilisation de Pinia store");
  store.fetchArticles();
});
</script>
