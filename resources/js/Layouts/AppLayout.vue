<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import ToggleDarkMode from '@/Components/ToggleDarkMode.vue'
import { ref, watch, onMounted } from 'vue'

// Heroicons
import { Bars3Icon, XMarkIcon, UserCircleIcon, ChevronDownIcon, SunIcon, MoonIcon } from '@heroicons/vue/24/outline'

// Light mode / dark mode
const theme = ref('light')
const loadTheme = () => {
  const saved = localStorage.getItem('theme')

  if (saved) {
    theme.value = saved
  } else {
    theme.value = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
  }

  document.documentElement.classList.toggle('dark', theme.value === 'dark')
}

const toggleTheme = () => {
  theme.value = theme.value === 'dark' ? 'light' : 'dark'
  document.documentElement.classList.toggle('dark', theme.value === 'dark')
  localStorage.setItem('theme', theme.value)
}

onMounted(() => loadTheme())


const page = usePage()

// Menu mobile
const mobileOpen = ref(false)
const toggleMobileMenu = () => (mobileOpen.value = !mobileOpen.value)

// Dropdown utilisateur
const userMenuOpen = ref(false)

const toggleUserMenu = () => {
  userMenuOpen.value = !userMenuOpen.value
}

// Ferme le dropdown en cliquant ailleurs
onMounted(() => {
  window.addEventListener('click', e => {
    const dropdown = document.getElementById('user-dropdown')
    const button = document.getElementById('user-menu-button')

    if (dropdown && !dropdown.contains(e.target) && !button.contains(e.target)) {
      userMenuOpen.value = false
    }
  })
})
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <!-- NAVBAR -->
	<nav class="bg-white dark:bg-gray-800 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16 items-center">

          <!-- Logo -->
          <Link :href="route('blog.index')" class="text-xl font-bold">
            Modernometry
          </Link>

          <!-- MENU DESKTOP -->
          <div class="hidden md:flex items-center space-x-6">

            <Link :href="route('blog.index')" class="navlink">Blog</Link>
            <Link :href="route('tutorials.index')" class="navlink">Tutoriels</Link>
            <Link :href="route('contact.index')" class="navlink">Contact</Link>

			<!-- Dark mode toggle -->
			<ToggleDarkMode />

            <!-- Dropdown utilisateur -->
            <div v-if="page.props.auth.user" class="relative">
              <button
                id="user-menu-button"
                @click.stop="toggleUserMenu"
                class="flex items-center space-x-2 px-3 py-1 rounded hover:bg-gray-100"
              >
                <UserCircleIcon class="h-6 w-6 text-gray-700" />
                <span>{{ page.props.auth.user.name }}</span>
                <ChevronDownIcon class="h-4 w-4 text-gray-600" />
              </button>

              <!-- MENU -->
              <div
                id="user-dropdown"
                v-if="userMenuOpen"
                class="absolute right-0 mt-2 w-48 bg-white border shadow-lg rounded-lg py-2 z-50"
              >
                <Link
                  :href="route('profile.edit')"
                  class="dropdown-item"
                >
                  Mon profil
                </Link>

                <Link
                  :href="route('dashboard')"
                  class="dropdown-item"
                >
                  Tableau de bord
                </Link>

                <form :action="route('logout')" method="post">
                  <input type="hidden" name="_token" :value="page.props.csrf_token" />
                  <button class="dropdown-item w-full text-left">
                    Déconnexion
                  </button>
                </form>
              </div>
            </div>

            <!-- Si non connecté -->
            <Link href="/login" v-else class="navlink">
              Connexion
            </Link>

          </div>

          <!-- BOUTON MOBILE -->
          <button
            class="md:hidden p-2 rounded hover:bg-gray-100"
            @click="mobileOpen = !mobileOpen"
          >
			<Bars3Icon v-if="!mobileOpen" class="h-6 w-6 text-gray-800 dark:text-gray-200" />
			<XMarkIcon v-else class="h-6 w-6 text-gray-800 dark:text-gray-200" />
          </button>

        </div>
      </div>

      <!-- MENU MOBILE -->
	  <div v-if="mobileOpen"
		class="md:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-sm">
		<!-- class="md:hidden bg-white dark:bg-gray-800 border-t shadow-sm"> -->
        <div class="px-4 py-3 space-y-3 flex flex-col">

          <Link :href="route('blog.index')" class="navlink-mobile">Blog</Link>
          <Link :href="route('tutorials.index')" class="navlink-mobile">Tutoriels</Link>
          <Link :href="route('contact.index')" class="navlink-mobile">Contact</Link>

		  <!-- Mode sombre -->
		  <ToggleDarkMode />


          <!-- Infos utilisateur -->
          <div v-if="page.props.auth.user" class="border-t pt-3">

            <div class="flex items-center space-x-2 mb-2">
              <UserCircleIcon class="h-6 w-6 text-gray-600" />
              <span class="font-medium">{{ page.props.auth.user.name }}</span>
            </div>

            <Link :href="route('profile.edit')" class="navlink-mobile">
              Mon profil
            </Link>

            <Link :href="route('dashboard')" class="navlink-mobile">
              Tableau de bord
            </Link>

            <form :action="route('logout')" method="post">
              <input type="hidden" name="_token" :value="page.props.csrf_token" />
              <button class="navlink-mobile text-left w-full">
                Déconnexion
              </button>
            </form>
          </div>

          <Link v-else href="/login" class="navlink-mobile">
            Connexion
          </Link>

        </div>
      </div>

    </nav>

    <!-- CONTENU PRINCIPAL-->
    <main class="flex-1 py-8">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>
	

	<footer class="bg-white dark:bg-gray-800 border-t dark:border-gray-700 py-4 text-center text-gray-600 dark:text-gray-300 text-sm">

      © {{ new Date().getFullYear() }} — Modernometry. Tous droits réservés.
    </footer>

  </div>
</template>

<style scoped>
.navlink {
  @apply text-gray-700 dark:text-gray-200 hover:text-black dark:hover:text-white transition;
}

.navlink-mobile {
  @apply block text-gray-800 dark:text-gray-100 text-lg font-medium hover:text-black dark:hover:text-white transition;
}

.dropdown-item {
  @apply block px-4 py-2 text-gray-700 hover:bg-gray-100;
}
</style>
