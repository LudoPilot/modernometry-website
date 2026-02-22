<script setup>
import { Link } from '@inertiajs/vue3'
import CategoryBadge from '@/Components/CategoryBadge.vue'
import TagBadge from '@/Components/TagBadge.vue'

const props = defineProps({
  article: {
    type: Object,
    required: true
  }
})

const excerpt = (html, limit = 140) => {
  const text = (html ?? '').replace(/<[^>]*>/g, '').trim()
  return text.length > limit ? text.slice(0, limit) + '…' : text
}
</script>

<template>
  <Link
    :href="route('blog.articles.show', article.slug)"
    class="block p-5 rounded-xl border border-gray-200 dark:border-gray-700
           bg-white dark:bg-gray-800 hover:shadow-lg dark:hover:shadow-xl
           transition-all duration-200"
  >

	<!-- Image-->
	<img
	v-if="article.cover_url"
	:src="article.cover_url"
	class="w-full h-44 object-cover rounded-lg mb-4"
	alt=""
	/>  
    <!-- Title -->
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
      {{ article.title }}
    </h2>

    <!-- Excerpt -->
    <p class="text-gray-600 dark:text-gray-300 mb-3">
      {{ excerpt(article.content, 140) }}
    </p>

	<!-- Catégorie -->
	<CategoryBadge
		v-if="article.category"
		:category="article.category"
	/>
	<!-- Tags -->
	<div class="flex gap-2 mt-2 flex-wrap">
		<TagBadge v-for="t in article.tags" :key="t.id" :tag="t" />
	</div>


    <!-- Footer -->
    <div class="text-sm text-gray-500 dark:text-gray-400 flex justify-between">
      <span>Publié le {{ new Date(article.created_at).toLocaleDateString() }}</span>
      <span class="text-blue-600 dark:text-blue-400 hover:underline">Voir plus →</span>
    </div>
  </Link>
</template>
