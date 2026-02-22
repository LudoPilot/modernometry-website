<script setup>
import { computed, onBeforeUnmount, watch } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import Link from '@tiptap/extension-link'
import Image from '@tiptap/extension-image'

const props = defineProps({
  modelValue: { type: String, default: '' },
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
  content: props.modelValue || '',
  extensions: [
    StarterKit,
    Underline,
    Link.configure({ openOnClick: false }),
    Image,
  ],
  editorProps: {
    attributes: {
      class:
        'prose dark:prose-invert max-w-none focus:outline-none min-h-[250px] px-3 py-2',
    },
  },
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML())
  },
})

// si le parent change modelValue (par exemple un chargement async), on synchronise
watch(
  () => props.modelValue,
  (val) => {
    if (!editor.value) return
    const current = editor.value.getHTML()
    if (val !== current) editor.value.commands.setContent(val || '', false)
  }
)

onBeforeUnmount(() => {
  editor.value?.destroy()
})

const can = computed(() => editor.value?.can())
const cmd = computed(() => editor.value?.commands)

const setLink = () => {
  const url = prompt('URL du lien :')
  if (!url) return
  editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
}

const unsetLink = () => {
  editor.value.chain().focus().unsetLink().run()
}

const addImageByUrl = () => {
  const url = prompt("URL de l'image :")
  if (!url) return
  editor.value.chain().focus().setImage({ src: url }).run()
}
</script>

<template>
  <div class="rounded-lg border border-gray-300 dark:border-gray-700 overflow-hidden">
    <div class="flex flex-wrap gap-2 p-2 bg-gray-50 dark:bg-gray-900/40 border-b border-gray-200 dark:border-gray-700">
      <button type="button" class="px-2 py-1 rounded border"
        @click="editor.chain().focus().toggleBold().run()"
        :disabled="!can?.chain?.().focus?.().toggleBold?.().run"
      >
        <b>B</b>
      </button>

      <button type="button" class="px-2 py-1 rounded border"
        @click="editor.chain().focus().toggleItalic().run()">
        <i>I</i>
      </button>

      <button type="button" class="px-2 py-1 rounded border"
        @click="editor.chain().focus().toggleUnderline().run()">
        <u>U</u>
      </button>

      <button type="button" class="px-2 py-1 rounded border"
        @click="editor.chain().focus().toggleHeading({ level: 1 }).run()">
        H1
      </button>

      <button type="button" class="px-2 py-1 rounded border"
        @click="editor.chain().focus().toggleHeading({ level: 2 }).run()">
        H2
      </button>

      <button type="button" class="px-2 py-1 rounded border"
        @click="editor.chain().focus().toggleBulletList().run()">
        • Liste
      </button>

      <button type="button" class="px-2 py-1 rounded border"
        @click="editor.chain().focus().toggleOrderedList().run()">
        1. Liste
      </button>

      <button type="button" class="px-2 py-1 rounded border" @click="setLink">
        Lien
      </button>

      <button type="button" class="px-2 py-1 rounded border" @click="unsetLink">
        Unlink
      </button>

      <button type="button" class="px-2 py-1 rounded border" @click="addImageByUrl">
        Image (URL)
      </button>
    </div>

    <EditorContent :editor="editor" class="bg-white dark:bg-gray-900" />
  </div>
</template>