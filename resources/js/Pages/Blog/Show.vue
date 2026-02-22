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
		<div class="max-w-3xl mx-auto py-10">

			<h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">
				{{ article.title }}
			</h1>

			<p class="text-gray-600 dark:text-gray-400 mb-10">
				Publié le {{ new Date(article.created_at).toLocaleDateString() }}
			</p>

			<!-- Image -->
			<img v-if="article.cover_url" :src="article.cover_url" class="w-full h-72 object-cover rounded-xl mb-6" />

			<!-- Content -->
			<div class="prose dark:prose-invert max-w-none mb-10">
				<!-- {{ article.content }} -->
				<div class="prose dark:prose-invert max-w-none mb-10" v-html="article.content"></div>

			</div>

			<!-- Catégorie -->
			<div v-if="article.category" class="text-blue-500 font-medium mb-4">
				{{ article.category.name }}
			</div>

			<!-- Tags-->
			<div class="flex gap-2 mb-8 flex-wrap">
				<Link v-for="tag in article.tags" :key="tag.id" :href="route('blog.tags.show', tag.slug)" class="px-3 py-1 text-sm rounded-full bg-blue-100 dark:bg-blue-900 
				text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800
				transition">
					#{{ tag.name }}
				</Link>
			</div>

			<!-- Footer actions -->
			<div class="flex items-center gap-6">
				<Link :href="route('blog.index')" class="text-blue-600 dark:text-blue-400 hover:underline">
					← Retour au blog
				</Link>

				<Link v-if="$page.props.auth.user" :href="route('blog.articles.edit', article.slug)"
					class="text-gray-700 dark:text-gray-300 hover:underline">
					Modifier
				</Link>
			</div>

		</div>
	</AppLayout>
</template>