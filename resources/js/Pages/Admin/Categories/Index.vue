<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  categories: Array
})
</script>

<template>
  <AppLayout>
    <div class="max-w-3xl mx-auto py-10">

      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Catégories</h1>
        <Link
          :href="route('admin.categories.create')"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg"
        >
          Ajouter
        </Link>
      </div>

      <div v-for="c in categories" :key="c.id" class="border-b py-3 flex justify-between">
        <div>
          <strong>{{ c.name }}</strong>
          <div class="text-gray-500 text-sm">/blog/category/{{ c.slug }}</div>
        </div>

        <div class="flex gap-4">
          <Link :href="route('admin.categories.edit', c.id)" class="text-blue-600">
            Modifier
          </Link>

          <form :action="route('admin.categories.destroy', c.id)" method="post">
            <input type="hidden" name="_token" :value="$page.props.csrf_token" />
            <input type="hidden" name="_method" value="DELETE" />

            <button class="text-red-600">Supprimer</button>
          </form>
        </div>
      </div>

    </div>
  </AppLayout>
</template>
