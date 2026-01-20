<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  categories: Array,
  tags: Array,
  activeCategory: String,   // slug
  activeTag: String,        // slug
})
</script>

<template>
  <aside class="w-64 shrink-0 p-4 bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
    
    <!-- Catégories -->
    <h2 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Catégories</h2>
    <ul class="flex flex-col gap-1 mb-6">
      <li v-for="cat in categories" :key="cat.id">
        <Link
          :href="route('blog.categories.show', cat.slug)"
          class="block px-3 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800"
          :class="cat.slug === activeCategory ? 'bg-gray-200 dark:bg-gray-700 font-medium' : ''"
        >
          {{ cat.name }}
        </Link>
      </li>
    </ul>

    <!-- Tags -->
    <h2 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Tags</h2>
    <div class="flex flex-wrap gap-2">
      <Link
        v-for="tag in tags"
        :key="tag.id"
        :href="route('blog.tags.show', tag.slug)"
        class="px-3 py-1 text-sm rounded-full border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800"
        :class="tag.slug === activeTag ? 'bg-gray-200 dark:bg-gray-700 font-medium' : ''"
      >
        #{{ tag.name }}
      </Link>
    </div>

  </aside>
</template>
