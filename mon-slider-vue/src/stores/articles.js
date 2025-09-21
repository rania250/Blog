// src/stores/articles.js
import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useArticlesStore = defineStore("articles", () => {
  // État
  const articles = ref([]);
  const loading = ref(false);
  const error = ref(null);

  // Actions
  const fetchArticles = async () => {
    loading.value = true;
    error.value = null;

    try {
      console.log("🔄 [Pinia] Récupération des articles depuis Symfony...");
      const response = await fetch("http://localhost:8000/api/articles");

      if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`);
      }

      const data = await response.json();
      articles.value = data;
      console.log("✅ [Pinia] Articles chargés !", articles.value.length);
    } catch (err) {
      console.error("❌ [Pinia] Erreur:", err);
      error.value = `Impossible de charger les articles: ${err.message}`;
    } finally {
      loading.value = false;
    }
  };

  // Getters (computed)
  const articlesCount = computed(() => articles.value.length);

  const uniqueAuthors = computed(() => {
    const authors = new Set(
      articles.value.map((article) => article.author?.fullname || "Jstore")
    );
    return authors.size;
  });

  const recentArticles = computed(() => {
    const oneWeekAgo = new Date();
    oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);

    return articles.value.filter((article) => {
      try {
        const articleDate = new Date(article.createdAt);
        return articleDate >= oneWeekAgo;
      } catch {
        return false;
      }
    }).length;
  });

  const getArticlesByCategory = computed(() => {
    return (category) => {
      if (category === "Tous") return articles.value;

    };
  });

  // Reset store
  const resetStore = () => {
    articles.value = [];
    loading.value = false;
    error.value = null;
  };

  return {
    // État
    articles,
    loading,
    error,

    // Actions
    fetchArticles,
    resetStore,

    // Getters
    articlesCount,
    uniqueAuthors,
    recentArticles,
    getArticlesByCategory,
  };
});
