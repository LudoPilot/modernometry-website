<script setup>
import { computed } from 'vue'
import CoverUploader from '@/Components/CoverUploader.vue'
import RichTextEditor from '@/Components/RichTextEditor.vue'

const props = defineProps({
	form: Object,     // useForm()
	article: Object,  // nullable
})

const isEdit = computed(() => !!props.article)
</script>

<template>
	<div class="space-y-6">
		<div>
			<label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Titre</label>
			<input v-model="form.title" type="text"
				class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" />
			<div v-if="form.errors.title" class="text-sm text-red-600 mt-1">{{ form.errors.title }}</div>
		</div>

		<div>
			<label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Type</label>
			<select v-model="form.type" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
				<option value="blog">Blog</option>
				<option value="tutorial">Tutoriel</option>
			</select>
			<div v-if="form.errors.type" class="text-sm text-red-600 mt-1">{{ form.errors.type }}</div>
		</div>

		<!-- Image -->
		<CoverUploader v-model="form.cover" :existing-url="article?.cover_url" />
		<div v-if="form.errors.cover" class="text-sm text-red-600 mt-1">{{ form.errors.cover }}</div>

		<div>
			<label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Contenu</label>
			<RichTextEditor v-model="form.content" />
			<div v-if="form.errors.content" class="text-sm text-red-600 mt-1">{{ form.errors.content }}</div>
		</div>

		<div class="flex items-center justify-between">
			<div class="text-sm text-gray-600 dark:text-gray-300" v-if="isEdit">
				<span v-if="article?.published_at">Statut : <b>Publié</b></span>
				<span v-else>Statut : <b>Brouillon</b></span>
			</div>
		</div>
	</div>
</template>
