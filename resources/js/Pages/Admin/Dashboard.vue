<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    stats: Object,
    latestArticles: Array
})
</script>

<template>
    <AppLayout>
        <Head title="Admin – Dashboard" />

        <h1 class="text-3xl font-bold mb-8">Tableau de bord Admin</h1>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            
            <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm">Articles</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.articles }}</p>
            </div>

            <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm">Catégories</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.categories }}</p>
            </div>

            <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm">Tags</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.tags }}</p>
            </div>

            <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm">Utilisateurs</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.users }}</p>
            </div>

        </div>

        <!-- Derniers articles -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Derniers articles</h2>

            <ul>
                <li v-for="article in latestArticles" :key="article.id" class="py-2 border-b dark:border-gray-700 flex justify-between">
                    <Link :href="route('blog.articles.show', article.slug)" class="text-blue-600 dark:text-blue-400 hover:underline">
                        {{ article.title }}
                    </Link>

                    <span class="text-gray-500 dark:text-gray-400 text-sm">
                        {{ new Date(article.created_at).toLocaleDateString() }}
                    </span>
                </li>

                <li v-if="latestArticles.length === 0" class="text-gray-500 dark:text-gray-400">
                    Aucun article pour le moment.
                </li>
            </ul>

        </div>

    </AppLayout>
</template>
