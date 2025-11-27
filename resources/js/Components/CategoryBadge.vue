<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  category: Object,
  link: {
    type: Boolean,
    default: true,
  }
})
</script>

<template>
  <Link
    v-if="link"
    :href="route('blog.categories.show', category.slug)"
    class="inline-block px-2 py-1 text-xs font-semibold rounded
           bg-green-100 text-green-700
           dark:bg-green-900 dark:text-green-300
           hover:bg-green-200 dark:hover:bg-green-800 transition"
  >
    {{ category.name }}
  </Link>

  <span
    v-else
    class="inline-block px-2 py-1 text-xs font-semibold rounded
           bg-green-100 text-green-700
           dark:bg-green-900 dark:text-green-300"
  >
    {{ category.name }}
  </span>
</template>
