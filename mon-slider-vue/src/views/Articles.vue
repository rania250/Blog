<template>
  <div class="articles-page">
    <!-- Header de la page -->
    <div class="page-header">
      <div class="container">
        <h1>Tous nos articles</h1>
        <p v-if="!loading">Découvrez nos articles passionnants</p>
        <p v-else>Chargement en cours...</p>
      </div>
    </div>

    <!-- Navigation -->
    <div class="page-navigation">
      <div class="nav-container">
        <router-link to="/" class="back-btn">
          ← Retour à l'accueil
        </router-link>
        
        <!-- Filtres de catégories -->
        <div class="filters" v-if="!loading">
          <button 
            v-for="category in categories" 
            :key="category"
            @click="filterByCategory(category)"
            :class="{ active: selectedCategory === category }"
            class="filter-btn"
          >
            {{ category }} <span>({{ getArticleCountByCategory(category) }})</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Contenu articles -->
    <div class="articles-content">
      <div class="container">
        
        <!-- Compteur d'articles filtré - Style Symfony -->
        <div v-if="!loading && articles.length > 0" class="articles-count">
          <strong>{{ filteredArticles.length }}</strong> articles retrouvés
          <span v-if="selectedCategory !== 'Tous'"> dans la catégorie "{{ selectedCategory }}"</span>
        </div>
        
        <!-- Loading state -->
        <div v-if="loading" class="no-articles">
          <div class="loading-spinner"></div>
          <h3>Chargement des articles...</h3>
          <p>Connexion à l'API Symfony en cours</p>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="no-articles">
          <h3>❌ Erreur de chargement</h3>
          <p>{{ error }}</p>
          <button @click="fetchArticles" class="btn-primary" style="margin-top: 1rem;">
            Réessayer
          </button>
        </div>

        <!-- No articles found -->
        <div v-else-if="filteredArticles.length === 0 && articles.length > 0" class="no-articles">
          <h3>🔍 Aucun article trouvé</h3>
          <p>Aucun article ne correspond au filtre "{{ selectedCategory }}"</p>
          <button @click="filterByCategory('Tous')" class="btn-primary" style="margin-top: 1rem;">
            Voir tous les articles
          </button>
        </div>

        <!-- Articles grid -->
        <div v-else-if="filteredArticles.length > 0">
          <div class="articles-grid">
            <article 
              v-for="article in filteredArticles" 
              :key="article.id"
              class="article-card fade-in-up"
              @click="goToArticle(article.slug)"
            >
              <div class="article-image">
                <img :src="article.image" :alt="article.title"  loading="lazy"/>
                <div class="article-overlay">
                  <span class="read-more">Lire l'article</span>
                </div>
              </div>
              
              <div class="article-content">
                <h2 class="article-title">{{ article.title }}</h2>
                <p class="article-description">{{ truncateText(article.description, 150) }}</p>
                
                <!-- Author section - Style Symfony -->
                <div class="article-meta">
                  <div class="author-avatar">
                    {{ getAuthorInitials(article.author?.fullname || 'Auteur Jstore') }}
                  </div>
                  <div class="author-info">
                    <div class="author">{{ article.author?.fullname || 'Auteur Jstore' }}</div>
                    <div class="date">{{ formatDate(article.createdAt) }}</div>
                  </div>
                </div>
              </div>
            </article>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const articles = ref([])
const selectedCategory = ref('Tous')
const loading = ref(true)
const error = ref(null)

const fetchArticles = async () => {
  loading.value = true
  error.value = null
  
  try {
    console.log('🔄 Récupération de tous les articles...')
    const response = await fetch('http://localhost:8000/api/articles')
    
    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`)
    }
    
    const data = await response.json()
    articles.value = data
    console.log('✅ Tous les articles chargés !', articles.value.length)
    
  } catch (err) {
    console.error('❌ Erreur:', err)
    error.value = `Impossible de charger les articles: ${err.message}`
  } finally {
    loading.value = false
  }
}

// Extraction des catégories basée sur les mots-clés dans les titres
const getCategoryFromTitle = (title) => {
  const titleLower = title.toLowerCase()
  if (titleLower.includes('économie')) return 'Économie'
  if (titleLower.includes('sport')) return 'Sport'
  if (titleLower.includes('politique')) return 'Politique'
  if (titleLower.includes('science') || titleLower.includes('spatial')) return 'Science'
  if (titleLower.includes('actualités') || titleLower.includes('actualités') || titleLower.includes('news')) return 'Actualités'
  if (titleLower.includes('breaking')) return 'Breaking News'
  return 'Général'
}

const categories = computed(() => {
  const cats = ['Tous']
  if (articles.value.length > 0) {
    const articleCategories = [...new Set(articles.value.map(article => getCategoryFromTitle(article.title)))]
    cats.push(...articleCategories.sort())
  }
  return cats
})

const getArticleCountByCategory = (category) => {
  if (category === 'Tous') return articles.value.length
  return articles.value.filter(article => 
    getCategoryFromTitle(article.title) === category
  ).length
}

const filteredArticles = computed(() => {
  if (selectedCategory.value === 'Tous') {
    return articles.value
  }
  return articles.value.filter(article => 
    getCategoryFromTitle(article.title) === selectedCategory.value
  )
})

const filterByCategory = (category) => {
  selectedCategory.value = category
  console.log(`🔍 Filtrage par: ${category}`)
}

const goToArticle = (slug) => {
  window.open(`http://localhost:8000/article/${slug}`, '_blank')
}

const formatDate = (dateString) => {
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    })
  } catch {
    return 'Date inconnue'
  }
}

const truncateText = (text, maxLength) => {
  if (!text) return 'Pas de description disponible'
  if (text.length <= maxLength) return text
  return text.substring(0, maxLength).trim() + '...'
}

// Fonction pour générer les initiales de l'auteur - Style Symfony
const getAuthorInitials = (fullName) => {
  if (!fullName) return 'JS'
  const names = fullName.split(' ')
  if (names.length >= 2) {
    return names[0][0].toUpperCase() + names[1][0].toUpperCase()
  }
  return names[0][0].toUpperCase() + (names[0][1]?.toUpperCase() || 'S')
}

onMounted(() => {
  fetchArticles()
})
</script>