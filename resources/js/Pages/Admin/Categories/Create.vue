<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import CategoryForm from './CategoryForm.vue'
</script>

<template>
  <AppLayout>
    <div class="max-w-3xl mx-auto py-10">
      <h1 class="text-2xl font-bold mb-6">Créer une catégorie</h1>

      <CategoryForm
        :submitUrl="route('admin.categories.store')"
      />
    </div>
  </AppLayout>
</template>
