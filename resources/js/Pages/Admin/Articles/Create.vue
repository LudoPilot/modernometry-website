<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import ArticleForm from './ArticleForm.vue'

const form = useForm({
	title: '',
	type: 'blog',
	content: '',
})

const submit = () => {
	form.post(route('admin.articles.store'))
}
</script>

<template>
	<AppLayout>

		<Head title="Admin – Nouvel article" />

		<div class="flex items-center justify-between mb-6">
			<h1 class="text-3xl font-bold">Nouvel article</h1>
			<Link :href="route('admin.articles.index')"
				class="px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-900">
				Retour
			</Link>
		</div>

		<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
			<form @submit.prevent="submit" class="space-y-6">
				<ArticleForm :form="form" :article="null" />

				<div class="flex items-center justify-end gap-2">
					<button type="submit" :disabled="form.processing"
						class="px-4 py-2 rounded-lg bg-black text-white hover:opacity-90 disabled:opacity-50">
						Créer
					</button>
				</div>
			</form>
		</div>
	</AppLayout>
</template>
