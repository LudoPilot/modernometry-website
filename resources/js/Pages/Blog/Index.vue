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
    <h1 class="text-3xl font-bold mb-4">Blog</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <ArticleCard
        v-for="article in articles"
        :key="article.id"
        :article="article"
      />
    </div>

    <!-- <ul>
      <li v-for="article in articles" :key="article.id" class="mb-4">
        <h2 class="text-xl font-semibold">
          {{ article.title }}
        </h2>

		<p>TEST</p>

        <p class="text-gray-600">
          {{ article.content.substring(0, 120) }}…
        </p>

        <Link
          :href="route('blog.articles.show', article.id)"
          class="text-blue-500 underline"
        >
          Lire l'article
        </Link>
      </li>
    </ul> -->
  </div>
  </AppLayout>
</template>
