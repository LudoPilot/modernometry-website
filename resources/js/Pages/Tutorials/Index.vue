<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ArticleCard from '@/Components/ArticleCard.vue'

defineProps({
  articles: {
    type: Array,
    default: () => []
  }
})
</script>

<template>
  <AppLayout>
  <div>
    <h1 class="text-3xl font-bold mb-4">Tutoriels</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <ArticleCard
        v-for="article in articles"
        :key="article.id"
        :article="article"
      />
    </div>

  </div>
  </AppLayout>
</template>
