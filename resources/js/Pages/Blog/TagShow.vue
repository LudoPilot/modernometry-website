<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import TagBadge from '@/Components/TagBadge.vue'
import ArticleCard from '@/Components/ArticleCard.vue'

defineProps({
  tag: Object,
  articles: Array
})
</script>

<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto py-10">

      <h1 class="text-4xl font-bold mb-6">
        Tag : <TagBadge :tag="tag" :link="false" />
      </h1>

      <div v-if="articles.length === 0">
        Aucun article pour ce tag.
      </div>

      <div class="grid gap-6">
        <ArticleCard v-for="a in articles" :key="a.id" :article="a" />
      </div>
    </div>
  </AppLayout>
</template>
