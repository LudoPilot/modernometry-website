<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  articles: {
    type: Array,
    default: () => []
  }
})
</script>

<template>
  <div>
    <h1 class="text-3xl font-bold mb-4">Blog</h1>

    <ul>
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
    </ul>
  </div>
</template>
