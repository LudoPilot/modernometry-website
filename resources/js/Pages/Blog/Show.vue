<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3'

defineProps({
  article: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <AppLayout>
  <div class="max-w-3xl mx-auto p-6">
    <!-- Titre -->
    <h1 class="text-4xl font-bold mb-4">
      {{ article.title }}
    </h1>

    <!-- Métadonnées (optionnel) -->
    <p class="text-gray-500 text-sm mb-6">
      Publié le {{ new Date(article.created_at).toLocaleDateString() }}
      <span v-if="article.updated_at && article.updated_at !== article.created_at">
        — modifié le {{ new Date(article.updated_at).toLocaleDateString() }}
      </span>
    </p>

    <!-- Contenu -->
    <div class="prose max-w-none mb-10">
      {{ article.content }}
    </div>

    <!-- Boutons -->
    <div class="flex items-center gap-4">
      <Link
        :href="route('blog.index')"
        class="text-blue-600 underline"
      >
        ← Retour au blog
      </Link>

      <Link
        :href="route('blog.articles.edit', article.id)"
        class="text-gray-600 underline"
      >
        Modifier
      </Link>
    </div>
  </div>
  </AppLayout>
</template>
