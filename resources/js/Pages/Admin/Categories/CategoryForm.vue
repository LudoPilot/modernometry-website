<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  category: {
    type: Object,
    default: () => ({ name: '' })
  },
  submitUrl: String,
  method: {
    type: String,
    default: 'post'
  }
})

const form = useForm({
  name: props.category.name
})

const submit = () => {
  form[props.method](props.submitUrl)
}
</script>

<template>
  <form @submit.prevent="submit" class="space-y-6">
    <div>
      <label class="block font-medium">Nom</label>
      <input
        v-model="form.name"
        class="border rounded w-full p-2"
        type="text"
      />

      <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
        {{ form.errors.name }}
      </div>
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
      Enregistrer
    </button>
  </form>
</template>
