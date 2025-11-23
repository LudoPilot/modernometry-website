<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

// Heroicons
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline'

// Flash messages (toasts plus tard)
const page = usePage()

watch(
  () => page.props.flash,
  flash => {
    if (flash?.success) {
      console.log('FLASH:', flash.success)
    }
  },
  { deep: true }
)

// état du menu mobile
const mobileOpen = ref(false)
const toggleMobileMenu = () => mobileOpen.value = !mobileOpen.value
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

            <Link :href="route('blog.index')" class="navlink">
              Blog
            </Link>

            <Link :href="route('tutorials.index')" class="navlink">
              Tutoriels
            </Link>

            <Link :href="route('contact.index')" class="navlink">
              Contact
            </Link>

            <Link :href="route('dashboard')" v-if="page.props.auth.user" class="navlink">
              Dashboard
            </Link>

            <Link href="/login" v-else class="navlink">
              Connexion
            </Link>
          </div>

          <!-- BOUTON MOBILE -->
          <button
            class="md:hidden p-2 rounded hover:bg-gray-100"
            @click="toggleMobileMenu"
          >
            <Bars3Icon v-if="!mobileOpen" class="h-6 w-6 text-gray-800" />
            <XMarkIcon v-else class="h-6 w-6 text-gray-800" />
          </button>

        </div>
      </div>

      <!-- MENU MOBILE -->
      <div v-if="mobileOpen" class="md:hidden bg-white border-t shadow-sm">
        <div class="px-4 py-3 space-y-3 flex flex-col">

          <Link :href="route('blog.index')" class="navlink-mobile">
            Blog
          </Link>

          <Link :href="route('tutorials.index')" class="navlink-mobile">
            Tutoriels
          </Link>

          <Link :href="route('contact.index')" class="navlink-mobile">
            Contact
          </Link>

          <Link :href="route('dashboard')" v-if="page.props.auth.user" class="navlink-mobile">
            Dashboard
          </Link>

          <Link href="/login" v-else class="navlink-mobile">
            Connexion
          </Link>

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

<style scoped>
.navlink {
  @apply text-gray-700 hover:text-black transition;
}

.navlink-mobile {
  @apply block text-gray-800 text-lg font-medium hover:text-black transition;
}
</style>
