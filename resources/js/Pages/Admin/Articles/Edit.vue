<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import ArticleForm from './ArticleForm.vue'

const props = defineProps({
	article: Object,
})

const form = useForm({
	title: props.article.title ?? '',
	type: props.article.type ?? 'blog',
	content: props.article.content ?? '',
})

const submit = () => {
	form.patch(route('admin.articles.update', props.article.slug))
}

const publish = () => {
	router.patch(route('admin.articles.publish', props.article.slug), {}, { preserveScroll: true })
}
const unpublish = () => {
	router.patch(route('admin.articles.unpublish', props.article.slug), {}, { preserveScroll: true })
}
</script>

<template>
	<AppLayout>

		<Head :title="`Admin – ${article.title}`" />

		<div class="flex items-center justify-between mb-6">
			<div>
				<h1 class="text-3xl font-bold">Éditer</h1>
				<div class="text-sm text-gray-500 dark:text-gray-400">{{ article.slug }}</div>
			</div>

			<div class="flex items-center gap-2">
				<button v-if="!article.published_at" @click="publish"
					class="px-4 py-2 rounded-lg bg-black text-white hover:opacity-90">
					Publier
				</button>
				<button v-else @click="unpublish"
					class="px-4 py-2 rounded-lg bg-gray-200 text-gray-900 hover:opacity-90 dark:bg-gray-700 dark:text-white">
					Dépublier
				</button>

				<Link :href="route('admin.articles.index')"
					class="px-3 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-900">
					Retour
				</Link>
			</div>
		</div>

		<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
			<form @submit.prevent="submit" class="space-y-6">
				<ArticleForm :form="form" :article="article" />

				<div class="flex items-center justify-end gap-2">
					<button type="submit" :disabled="form.processing"
						class="px-4 py-2 rounded-lg bg-black text-white hover:opacity-90 disabled:opacity-50">
						Enregistrer
					</button>
				</div>
			</form>
		</div>
	</AppLayout>
</template>
