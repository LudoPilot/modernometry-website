<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

// Watcher pour les flash messages (pour les toasts plus tard)
const page = usePage()

watch(
  () => page.props.flash,
  flash => {
    if (flash?.success) {
      // TODO : remplacer alert() par un vrai toast
      console.log('FLASH:', flash.success)
    }
  },
  { deep: true }
)
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

          <!-- Logo -->
          <Link :href="route('blog.index')" class="text-xl font-bold">
            Modernometry
          </Link>

          <!-- Menu desktop -->
          <div class="hidden md:flex items-center space-x-6">

            <Link :href="route('blog.index')" class="text-gray-700 hover:text-black">
              Blog
            </Link>

            <Link :href="route('tutorials.index')" class="text-gray-700 hover:text-black">
              Tutoriels
            </Link>

            <Link :href="route('contact.index')" class="text-gray-700 hover:text-black">
              Contact
            </Link>

            <Link :href="route('dashboard')" class="text-gray-700 hover:text-black" v-if="page.props.auth.user">
              Dashboard
            </Link>

            <Link href="/login" class="text-gray-700 hover:text-black" v-else>
              Connexion
            </Link>
          </div>

        </div>
      </div>
    </nav>

    <!-- CONTENU PRINCIPAL -->
    <main class="flex-1 py-8">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t py-4 text-center text-gray-600 text-sm">
      © {{ new Date().getFullYear() }} — Modernometry. Tous droits réservés.
    </footer>

  </div>
</template>
