<script setup lang="ts">
const props = defineProps({
	show: Boolean,
	title: {
		type: String,
		default: 'Confirmation'
	},
	message: {
		type: String,
		default: 'Êtes-vous sûr ?'
	}
});

const emit = defineEmits(['confirm', 'close'])
</script>

<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
  >
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
      <h2 class="text-2xl font-bold mb-4">{{ title }}</h2>
      <p class="mb-6 text-gray-700">{{ message }}</p>

      <div class="flex justify-end space-x-3">
        <button
          @click="emit('close')"
          class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300"
        >
          Annuler
        </button>

        <button
          @click="emit('confirm')"
          class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700"
        >
          Confirmer
        </button>
      </div>
    </div>
  </div>
</template>