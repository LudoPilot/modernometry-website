<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ArticleCard from '@/Components/ArticleCard.vue'


defineProps({
	latestArticles: {
		type: Array,
		default: () => []
	}
})
</script>

<template>
	<Head title="Le développement web expliqué">
		<meta name="description"
			content="Articles et tutoriels pour apprendre simplement le développement web moderne" />
	</Head>


	<AppLayout>

		<!-- HERO -->
		<template #hero>
			<section class="py-20 bg-gray-50 dark:bg-gray-900">
				<div class="max-w-5xl mx-auto px-4 text-center">
					<h1 class="text-4xl sm:text-5xl font-bold mb-6">
						Le développement web expliqué
					</h1>

					<p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-8">
						Articles et tutoriels pour comprendre le web moderne,
						avec des explications claires et sans jargon inutile.
					</p>

					<div class="flex justify-center gap-4">
						<Link :href="route('blog.index')"
							class="px-6 py-3 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 font-medium hover:opacity-90 transition">
							Lire le blog
						</Link>

						<Link :href="route('tutorials.index')"
							class="px-6 py-3 rounded-lg border border-gray-300 dark:border-gray-600 font-medium hover:bg-gray-100 dark:hover:bg-gray-800 transition">
							Voir les tutoriels
						</Link>
					</div>
				</div>
			</section>
		</template>

		<!-- CONTENU NORMAL -->
		<section class="mb-16">
			<h2 class="text-2xl font-bold mb-6 text-center">
				Pourquoi Modernometry ?
			</h2>

			<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
				<div class="card">
					<h3 class="font-semibold mb-2">Explications claires</h3>
					<p class="text-sm text-gray-600 dark:text-gray-400">
						Comprendre le “pourquoi”, pas seulement copier du code.
					</p>
				</div>

				<div class="card">
					<h3 class="font-semibold mb-2">Web moderne</h3>
					<p class="text-sm text-gray-600 dark:text-gray-400">
						PHP, Laravel, JavaScript et outils actuels.
					</p>
				</div>

				<div class="card">
					<h3 class="font-semibold mb-2">Progression logique</h3>
					<p class="text-sm text-gray-600 dark:text-gray-400">
						Des bases solides vers des sujets plus avancés.
					</p>
				</div>
			</div>
		</section>

		<!-- Derniers articles-->
		<section class="mb-20">
		<div class="flex items-center justify-between mb-6">
			<h2 class="text-2xl font-bold">
			Derniers articles
			</h2>

			<Link
			:href="route('blog.index')"
			class="text-sm text-blue-600 hover:underline"
			>
			Voir tous les articles →
			</Link>
		</div>

		<div
			v-if="latestArticles.length"
			class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
		>
			<ArticleCard
			v-for="article in latestArticles"
			:key="article.id"
			:article="article"
			/>
		</div>

		<p
			v-else
			class="text-gray-500 text-sm"
		>
			Aucun article pour le moment.
		</p>
		</section>

	</AppLayout>
</template>

<style scoped>
.card {
	@apply rounded-xl border border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900;
}
</style>
