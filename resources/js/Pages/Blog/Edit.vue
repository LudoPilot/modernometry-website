<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
  article: {
    type: Object,
    required: true,
  }
})

const form = useForm({
  title: props.article.title,
  content: props.article.content,
})

const submit = () => {
  form.patch(route('blog.articles.update', props.article.id))
}
</script>

<template>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Modifier l'article</h1>

    <form @submit.prevent="submit" class="space-y-6">

      <!-- Titre -->
      <div>
        <label class="block font-medium mb-1">Titre</label>
        <input
          v-model="form.title"
          type="text"
          class="w-full border rounded-lg p-2"
        />
        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
          {{ form.errors.title }}
        </div>
      </div>

      <!-- Contenu -->
      <div>
        <label class="block font-medium mb-1">Contenu</label>
        <textarea
          v-model="form.content"
          rows="10"
          class="w-full border rounded-lg p-3"
        ></textarea>
        <div v-if="form.errors.content" class="text-red-500 text-sm mt-1">
          {{ form.errors.content }}
        </div>
      </div>

      <div class="flex items-center gap-4">
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 disabled:bg-gray-400"
          :disabled="form.processing"
        >
          Mettre à jour
        </button>

        <Link :href="route('blog.index')" class="text-gray-700 underline">
          Annuler
        </Link>
      </div>
    </form>

    <!-- Bouton supprimer -->
    <div class="mt-10">
      <form
        method="post"
        :action="route('blog.articles.destroy', article.id)"
        @submit.prevent="() => form.delete(route('blog.articles.destroy', article.id))"
      >
        <button
          type="submit"
          class="text-red-600 underline hover:text-red-800"
        >
          Supprimer cet article
        </button>
      </form>
    </div>
  </div>
</template>
