<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
  modelValue: { type: [File, null], default: null },
  existingUrl: { type: String, default: null }, // cover_url (si image déjà en DB)
})

const emit = defineEmits(['update:modelValue'])

const preview = ref(null)

watch(
  () => props.modelValue,
  (file) => {
    if (preview.value) URL.revokeObjectURL(preview.value)
    preview.value = file ? URL.createObjectURL(file) : null
  }
)

const displayUrl = computed(() => preview.value || props.existingUrl)

const onChange = (e) => {
  const file = e.target.files?.[0] ?? null
  emit('update:modelValue', file)
}

const clear = () => {
  emit('update:modelValue', null)
}
</script>

<template>
  <div class="space-y-2">
    <label class="block font-medium mb-1">Image d’illustration</label>

    <div v-if="displayUrl" class="rounded-lg overflow-hidden border">
      <img :src="displayUrl" class="w-full h-48 object-cover" alt="Cover preview" />
    </div>

    <div class="flex items-center gap-3">
      <input type="file" accept="image/*" @change="onChange" />
      <button
        v-if="modelValue || existingUrl"
        type="button"
        class="px-3 py-1.5 rounded-lg border"
        @click="clear"
      >
        Retirer
      </button>
    </div>
  </div>
</template>