<script setup>
import { ref } from 'vue'

const props = defineProps({
  modelValue: Array,    // tags sélectionnés
  existingTags: Array, 
})

const emit = defineEmits(['update:modelValue'])

const inputText = ref('')

const removeTag = (tag) => {
  emit('update:modelValue', props.modelValue.filter(t => t !== tag))
}

const addTag = () => {
  const value = inputText.value.trim()

  if (!value) return

  // empêcher les doublons
  if (!props.modelValue.includes(value)) {
    emit('update:modelValue', [...props.modelValue, value])
  }

  inputText.value = ''
}

// gestion de la touche Entrée
const onKeyPress = (e) => {
  if (e.key === 'Enter') {
    e.preventDefault()
    addTag()
  }
}
</script>

<template>
  <div>
    <!-- Liste des tags -->
    <div class="flex flex-wrap gap-2 mb-3">
      <span
        v-for="tag in modelValue"
        :key="tag"
        class="flex items-center gap-2 bg-blue-100 text-blue-700 px-3 py-1 rounded-full"
      >
        {{ tag }}
        <button @click="removeTag(tag)" class="text-red-500 hover:text-red-700">
          &times;
        </button>
      </span>
    </div>

    <!-- Champ de saisie -->
    <input
      v-model="inputText"
	  @keydown.enter.prevent="addTag"
      type="text"
      placeholder="Ajouter un tag et appuyer sur Entrée"
      class="border rounded-lg px-3 py-2 w-full"
    />

    <!-- Tags existants à cliquer -->
    <div class="flex flex-wrap mt-3 gap-2">
      <button
        v-for="tag in existingTags"
        :key="tag.id"
		@click="!modelValue.includes(tag.name) 
        	&& emit('update:modelValue', [...modelValue, tag.name])"
        class="px-3 py-1 border rounded-full hover:bg-gray-200"
      >
        {{ tag.name }}
      </button>
    </div>
  </div>
</template>
