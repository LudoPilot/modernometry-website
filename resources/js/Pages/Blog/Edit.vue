<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'

const props = defineProps({
  article: {
    type: Object,
    required: true,
  },
  categories: Array,
  tags: Array,
})

const form = useForm({
  title: props.article.title,
  content: props.article.content,
  category_id: props.article.category_id,
  tags: props.article.tags?.map(t => t.id) ?? [],
})

const submit = () => {
  form.patch(route('blog.articles.update', props.article.id))
}

// gestion de la modale
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
          <label class="block font-medium mb-2">Tags</label>

          <div class="flex flex-wrap gap-3 mt-2">
            <label v-for="tag in tags" :key="tag.id"
                   class="flex items-center gap-2 px-3 py-1 rounded-full border
                          cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
              <input type="checkbox" :value="tag.id" v-model="form.tags" />
              {{ tag.name }}
            </label>
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

  </div>
    
  <!-- Bouton supprimer -->
	<button
	@click="showDeleteModal = true"
	class="text-red-600 underline hover:text-red-800"
	>
	Supprimer cet article
	</button>
	
  <!-- Modal -->
  <ConfirmModal
    :show="showDeleteModal"
    title="Supprimer l'article"
    message="Êtes-vous sûr de vouloir supprimer cet article ? Cette action est définitive."
    @close="showDeleteModal = false"
    @confirm="confirmDelete"
  />
  </AppLayout>
</template>
