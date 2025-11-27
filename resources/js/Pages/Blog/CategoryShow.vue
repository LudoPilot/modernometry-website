<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import CategoryBadge from '@/Components/CategoryBadge.vue'
import TagBadge from '@/Components/TagBadge.vue'
import ArticleCard from '@/Components/ArticleCard.vue'

defineProps({
  category: Object,
  articles: Array
})
</script>

<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto py-10">

      <h1 class="text-4xl font-bold mb-6">
        Catégorie : <CategoryBadge :category="category" :link="false" />
      </h1>

      <div v-if="articles.length === 0">
        Aucun article dans cette catégorie.
      </div>

      <div class="grid gap-6">
        <ArticleCard v-for="a in articles" :key="a.id" :article="a" />
      </div>
    </div>
  </AppLayout>
</template>
