<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import TagInput from '@/Components/TagInput.vue'
import { ref } from 'vue'

const props = defineProps({
  article: Object,
  categories: Array,
  tags: Array,
})

// tags existants → tableau de noms
const initialTags = props.article.tags?.map(t => t.name) ?? []

const form = useForm({
  title: props.article.title,
  content: props.article.content,
  category_id: props.article.category_id,
  tags: [...initialTags],        // noms, pas IDs
})

const submit = () => {
  form.patch(route('blog.articles.update', props.article.slug))
}

// modale
const showDeleteModal = ref(false)
const confirmDelete = () => {
  form.delete(route('blog.articles.destroy', props.article.id))
  showDeleteModal.value = false
}
</script>

<template>
<AppLayout>
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

      <!-- Catégorie -->
      <div>
        <label class="block font-medium mb-1">Catégorie</label>
        <select v-model="form.category_id" class="border rounded p-2 w-full">
          <option :value="null">Aucune catégorie</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">
            {{ cat.name }}
          </option>
        </select>
      </div>

      <!-- TAGS (TagInput comme Create.vue) -->
      <div>
        <label class="block font-medium mb-1">Tags</label>
        <TagInput v-model="form.tags" :existing-tags="tags" />
        <div v-if="form.errors.tags" class="text-red-500 text-sm mt-1">
          {{ form.errors.tags }}
        </div>
      </div>

      <div class="flex items-center justify-between mt-8">
		<div class="flex items-center gap-4">
			<button
			type="submit"
			class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
			>
			Mettre à jour
			</button>

			<Link
			:href="route('blog.index')"
			class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
			>
			Annuler
			</Link>
		</div>

		<!-- Bouton supprimer -->
		<button
		@click="showDeleteModal = true"
		class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 shadow-sm"
		>
		Supprimer cet article
		</button>
      </div>
	  
    </form>

    <!-- Modal -->
    <ConfirmModal
      :show="showDeleteModal"
      title="Supprimer l'article"
      message="Êtes-vous sûr de vouloir supprimer cet article ? Cette action est définitive."
      @close="showDeleteModal = false"
      @confirm="confirmDelete"
    />
  </div>
</AppLayout>
</template>
