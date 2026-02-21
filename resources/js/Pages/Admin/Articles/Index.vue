<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

const props = defineProps({
	articles: Object, // paginator
	filters: Object,  // { type: 'all'|'blog'|'tutorial', status: 'all'|'published'|'draft', q: '' }
})

const publish = (slug) => {
  router.patch(
    route('admin.articles.publish', slug),
    {},
    { preserveScroll: true, preserveState: false }
  )
}

// const publish = (slug) => {
//   router.patch(route('admin.articles.publish', slug), {}, {
//     preserveScroll: true,
//     onSuccess: () => router.reload({ only: ['articles'] }),
//   })
// }

const unpublish = (slug) => {
  router.patch(
    route('admin.articles.unpublish', slug),
    {},
    { preserveScroll: true, preserveState: false }
  )
}

const applyFilters = (overrides = {}) => {
	router.get(
		route('admin.articles.index'),
		{ ...props.filters, ...overrides },
		{ preserveState: true, preserveScroll: true, replace: true }
	)
}
</script>

<template>
	<AppLayout>

		<Head title="Admin – Articles" />

		<div class="flex items-center justify-between mb-6">
			<h1 class="text-3xl font-bold">Articles</h1>

			<Link :href="route('admin.articles.create')"
				class="inline-flex items-center px-4 py-2 rounded-lg bg-black text-white hover:opacity-90">
				Nouvel article
			</Link>
		</div>

		<!-- Filtres -->
		<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-6">
			<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
				<div>
					<label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Type</label>
					<select class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900"
						:value="filters?.type ?? 'all'" @change="applyFilters({ type: $event.target.value })">
						<option value="all">Tous</option>
						<option value="blog">Blog</option>
						<option value="tutorial">Tutoriels</option>
					</select>
				</div>

				<div>
					<label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Statut</label>
					<select class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900"
						:value="filters?.status ?? 'all'" @change="applyFilters({ status: $event.target.value })">
						<option value="all">Tous</option>
						<option value="published">Publiés</option>
						<option value="draft">Brouillons</option>
					</select>
				</div>

				<div class="md:col-span-2">
					<label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Recherche</label>
					<input class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" type="text"
						:value="filters?.q ?? ''" placeholder="Titre…"
						@keyup.enter="applyFilters({ q: $event.target.value })"
						@blur="applyFilters({ q: $event.target.value })" />
				</div>
			</div>
		</div>

		<!-- Liste -->
		<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
			<div class="overflow-x-auto">
				<table class="min-w-full text-sm">
					<thead class="bg-gray-50 dark:bg-gray-900/40">
						<tr class="text-left">
							<th class="px-6 py-3 font-semibold text-gray-700 dark:text-gray-200">Titre</th>
							<th class="px-6 py-3 font-semibold text-gray-700 dark:text-gray-200">Type</th>
							<th class="px-6 py-3 font-semibold text-gray-700 dark:text-gray-200">Statut</th>
							<th class="px-6 py-3 font-semibold text-gray-700 dark:text-gray-200">Créé</th>
							<th class="px-6 py-3 font-semibold text-gray-700 dark:text-gray-200 text-right">Actions</th>
						</tr>
					</thead>

					<tbody class="divide-y divide-gray-100 dark:divide-gray-700">
						<tr v-for="a in articles.data" :key="a.id">
							<td class="px-6 py-4">
								<Link :href="route('admin.articles.edit', a.slug)"
									class="text-blue-600 hover:underline">
									{{ a.title }}
								</Link>
								<div class="text-xs text-gray-500 dark:text-gray-400">{{ a.slug }}</div>
							</td>

							<td class="px-6 py-4 capitalize">{{ a.type }}</td>

							<td class="px-6 py-4">
								<span v-if="a.published_at"
									class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
									Publié
								</span>
								<span v-else
									class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200">
									Brouillon
								</span>
							</td>

							<td class="px-6 py-4 text-gray-600 dark:text-gray-300">
								{{ a.created_at }}
							</td>

							<td class="px-6 py-4 text-right space-x-2">
								<button v-if="!a.published_at" @click="publish(a.slug)"
									class="px-3 py-1.5 rounded-lg bg-black text-white hover:opacity-90">
									Publier
								</button>
								<button v-else @click="unpublish(a.slug)"
									class="px-3 py-1.5 rounded-lg bg-gray-200 text-gray-900 hover:opacity-90 dark:bg-gray-700 dark:text-white">
									Dépublier
								</button>

								<Link :href="route('admin.articles.edit', a.slug)"
									class="px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-900">
									Éditer
								</Link>
							</td>
						</tr>

						<tr v-if="articles.data.length === 0">
							<td class="px-6 py-6 text-gray-600 dark:text-gray-300" colspan="5">
								Aucun article.
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- Pagination s-->
			<div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 dark:border-gray-700">
				<div class="text-sm text-gray-600 dark:text-gray-300">
					Page {{ articles.current_page }} / {{ articles.last_page }}
				</div>

				<div class="space-x-2">
					<Link v-if="articles.prev_page_url" :href="articles.prev_page_url"
						class="px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-900">
						Précédent
					</Link>
					<Link v-if="articles.next_page_url" :href="articles.next_page_url"
						class="px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-900">
						Suivant
					</Link>
				</div>
			</div>
		</div>
	</AppLayout>
</template>
