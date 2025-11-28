<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import CategoryForm from './CategoryForm.vue'

defineProps({
  category: Object
})
</script>

<template>
  <AppLayout>
    <div class="max-w-3xl mx-auto py-10">
      <h1 class="text-2xl font-bold mb-6">Modifier la catégorie</h1>

      <CategoryForm
        :category="category"
        :submitUrl="route('admin.categories.update', category.id)"
        method="patch"
      />
    </div>
  </AppLayout>
</template>
