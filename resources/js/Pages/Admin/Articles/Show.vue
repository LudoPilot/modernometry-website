<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

const props = defineProps({
	article: Object,
})

const publish = () => {
	router.patch(route('admin.articles.publish', props.article.slug), {}, { preserveScroll: true, preserveState: false })
}
const unpublish = () => {
	router.patch(route('admin.articles.unpublish', props.article.slug), {}, { preserveScroll: true, preserveState: false })
}
</script>

<template>
	<AppLayout>

		<Head :title="`Admin – ${article.title}`" />

		<div class="flex items-start justify-between gap-4 mb-6">
			<div>
				<h1 class="text-3xl font-bold">{{ article.title }}</h1>
				<div class="text-sm text-gray-500 dark:text-gray-400">{{ article.slug }} • {{ article.created_at }}
				</div>

				<div class="mt-3 flex flex-wrap gap-2">
					<span v-if="article.deleted_at"
						class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
						Supprimé
					</span>
					<span v-else-if="article.published_at"
						class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
						Publié
					</span>
					<span v-else
						class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200">
						Brouillon
					</span>

					<span
						class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
						{{ article.type }}
					</span>
				</div>
			</div>

			<div class="flex items-center gap-2">
				<Link :href="route('admin.articles.edit', article.slug)"
					class="px-3 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-900">
					Éditer
				</Link>

				<button v-if="!article.deleted_at && !article.published_at" type="button" @click="publish"
					class="px-4 py-2 rounded-lg bg-black text-white hover:opacity-90">
					Publier
				</button>

				<button v-if="!article.deleted_at && article.published_at" type="button" @click="unpublish"
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
			<img v-if="article.cover_url" :src="article.cover_url" class="w-full h-72 object-cover rounded-xl mb-6"
				alt="Cover" />

			<div class="prose dark:prose-invert max-w-none" v-html="article.content"></div>
		</div>
	</AppLayout>
</template>