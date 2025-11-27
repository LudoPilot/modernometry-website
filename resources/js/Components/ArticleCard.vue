<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  article: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <Link
    :href="route('blog.articles.show', article.slug)"
    class="block p-5 rounded-xl border border-gray-200 dark:border-gray-700
           bg-white dark:bg-gray-800 hover:shadow-lg dark:hover:shadow-xl
           transition-all duration-200"
  >
    <!-- Title -->
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
      {{ article.title }}
    </h2>

    <!-- Excerpt -->
    <p class="text-gray-600 dark:text-gray-300 mb-3">
      {{ article.content.substring(0, 140) }}…
    </p>

    <!-- Footer -->
    <div class="text-sm text-gray-500 dark:text-gray-400 flex justify-between">
      <span>Publié le {{ new Date(article.created_at).toLocaleDateString() }}</span>
      <span class="text-blue-600 dark:text-blue-400 hover:underline">Voir plus →</span>
    </div>
  </Link>
</template>
