<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  tag: Object,
  link: {
    type: Boolean,
    default: true,
  }
})
</script>

<template>
  <Link
    v-if="link"
    :href="route('blog.tags.show', tag.slug)"
    class="inline-block px-3 py-1 text-xs font-medium rounded-full
           bg-blue-100 text-blue-700
           dark:bg-blue-900 dark:text-blue-300
           hover:bg-blue-200 dark:hover:bg-blue-800 transition"
  >
    #{{ tag.name }}
  </Link>

  <span
    v-else
    class="inline-block px-3 py-1 text-xs font-medium rounded-full
           bg-blue-100 text-blue-700
           dark:bg-blue-900 dark:text-blue-300"
  >
    #{{ tag.name }}
  </span>
</template>
