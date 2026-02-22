<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import TagInput from '@/Components/TagInput.vue'
import CoverUploader from '@/Components/CoverUploader.vue'
import RichTextEditor from '@/Components/RichTextEditor.vue'

defineProps({
	categories: Array,
	tags: Array,
})

const form = useForm({
  title: '',
  content: '',
  category_id: null,
  tags: [],
  cover: null,
})

const submit = () => {
  form.post(route('blog.articles.store'), { forceFormData: true })
}
</script>

<template>
  <AppLayout>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Créer un nouvel article</h1>

    <form @submit.prevent="submit" class="space-y-6">

      <!-- Titre -->
      <div>
        <label class="block font-medium mb-1">Titre</label>
        <input
          v-model="form.title"
          type="text"
          class="w-full border rounded-lg p-2"
          placeholder="Titre de l'article"
        />
        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
          {{ form.errors.title }}
        </div>
      </div>

	  <!-- Image de cover -->
	  <CoverUploader v-model="form.cover" />

      <!-- Contenu -->
      <div>
        <label class="block font-medium mb-1">Contenu</label>
		<RichTextEditor v-model="form.content" />
        <div v-if="form.errors.content" class="text-red-500 text-sm mt-1">
          {{ form.errors.content }}
        </div>
      </div>

	  <!-- Catégorie -->
	<div>
	  <label class="block font-medium mb-1">Catégorie</label>
	  <select v-model="form.category_id"
		class="border rounded p-2 w-full">
		<option :value="null">Aucune catégorie</option>
		<option v-for="cat in categories" :key="cat.id" :value="cat.id">
			{{ cat.name }}
		</option>
	  </select>
	</div>

    <!-- Tags -->
    <div>
        <label class="block font-medium mb-1">Tags</label>
        <TagInput v-model="form.tags" :existing-tags="tags" />
    </div>

      <!-- Boutons -->
      <div class="flex items-center gap-4">
        <button
          type="submit"
          class="bg-blue-600 text-black px-4 py-2 rounded-lg hover:bg-blue-700 disabled:bg-gray-400"
          :disabled="form.processing"
        >
          Publier
        </button>

        <Link
          :href="route('blog.index')"
          class="text-gray-700 underline"
        >
          Annuler
        </Link>
      </div>
    </form>
  </div>
  </AppLayout>
</template>
