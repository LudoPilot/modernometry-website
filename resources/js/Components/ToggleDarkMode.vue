<script setup>
import { ref, onMounted } from 'vue'
import { MoonIcon, SunIcon } from '@heroicons/vue/24/solid'

const theme = ref('light')

// Chargement du thème
const loadTheme = () => {
  theme.value =
    localStorage.getItem('theme') ||
    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')

  document.documentElement.classList.toggle('dark', theme.value === 'dark')
}

// Basculer d'un thème à l'autre
const toggleTheme = () => {
  theme.value = theme.value === 'dark' ? 'light' : 'dark'
  document.documentElement.classList.toggle('dark', theme.value === 'dark')
  localStorage.setItem('theme', theme.value)
}

onMounted(loadTheme)
</script>

<template>
  <button
    @click="toggleTheme"
    class="relative flex items-center w-[64px] h-[32px] rounded-full transition-colors duration-300"
    :class="theme === 'dark' ? 'bg-gray-700' : 'bg-gray-300'"
  >
    <!-- Moon (left) -->
    <MoonIcon
      class="absolute left-[8px] w-[16px] h-[16px] transition-all duration-300"
      :class="theme === 'dark' ? 'text-white opacity-100' : 'text-gray-500 opacity-60'"
    />

    <!-- Sun (right) -->
    <SunIcon
      class="absolute right-[8px] w-[16px] h-[16px] transition-all duration-300"
      :class="theme === 'dark' ? 'text-yellow-400 opacity-80' : 'text-yellow-400 opacity-100'"
    />

    <!-- Sliding circle -->
    <span
      class="absolute top-[4px] w-[24px] h-[24px] rounded-full bg-white dark:bg-gray-200 shadow-md transition-all duration-300"
      :class="theme === 'dark' ? 'left-[4px]' : 'left-[36px]'"
    ></span>
  </button>
</template>
