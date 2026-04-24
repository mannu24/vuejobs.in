<template>
  <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center gap-2">
          <img src="/logo-small.png" alt="VueJobs" class="md:hidden" style="width:40px;height:40px;">
          <img src="/logo.png" alt="VueJobs" class="hidden md:block" style="width:130px;height:40px;">
        </NuxtLink>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-6">
          <NuxtLink to="/jobs" class="text-gray-600 hover:text-vue transition">
            Find Jobs
          </NuxtLink>
          <NuxtLink to="/blog" class="text-gray-600 hover:text-vue transition">
            Blog
          </NuxtLink>
          <NuxtLink v-if="auth.isEmployer" to="/recruiter" class="text-gray-600 hover:text-vue transition">
            Recruiter Panel
          </NuxtLink>
        </nav>

        <!-- Desktop Auth + Mobile Hamburger -->
        <div class="flex items-center gap-4">
          <!-- Desktop auth -->
          <template v-if="auth.isLoggedIn">
            <div class="hidden md:block relative">
              <button
                class="flex w-9 h-9 rounded-full bg-vue/20 items-center justify-center text-vue font-semibold text-sm hover:ring-2 hover:ring-vue/30 transition"
                @click="dropdownOpen = !dropdownOpen"
                aria-label="User menu"
              >
                {{ auth.user?.name?.charAt(0)?.toUpperCase() }}
              </button>

              <!-- Dropdown -->
              <Transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
              >
                <div
                  v-if="dropdownOpen"
                  class="absolute right-0 mt-2 w-56 bg-white rounded-xl border border-gray-200 shadow-lg py-1 z-50"
                >
                  <!-- User info -->
                  <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth.user?.name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth.user?.email }}</p>
                  </div>

                  <NuxtLink
                    :to="auth.isEmployer ? '/recruiter' : '/dashboard'"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition"
                    @click="dropdownOpen = false"
                  >
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                    Dashboard
                  </NuxtLink>

                  <NuxtLink
                    :to="auth.isEmployer ? '/recruiter/profile' : '/dashboard/profile'"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition"
                    @click="dropdownOpen = false"
                  >
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    My Profile
                  </NuxtLink>

                  <NuxtLink
                    v-if="auth.isDeveloper"
                    to="/dashboard/saved-jobs"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition"
                    @click="dropdownOpen = false"
                  >
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                    Saved Jobs
                  </NuxtLink>

                  <NuxtLink
                    v-if="auth.isEmployer"
                    to="/recruiter/jobs/create"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition"
                    @click="dropdownOpen = false"
                  >
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Post a Job
                  </NuxtLink>

                  <div class="border-t border-gray-100 mt-1">
                    <button
                      class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition"
                      @click="auth.logout(); dropdownOpen = false"
                    >
                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                      Logout
                    </button>
                  </div>
                </div>
              </Transition>
            </div>
          </template>
          <template v-else>
            <NuxtLink to="/login" class="hidden md:inline text-gray-600 hover:text-vue transition text-sm">
              Login
            </NuxtLink>
            <NuxtLink
              to="/register"
              class="hidden md:inline-block bg-vue text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition"
            >
              Sign Up
            </NuxtLink>
          </template>

          <!-- Mobile hamburger -->
          <button class="md:hidden p-1.5 text-gray-600" @click="mobileOpen = !mobileOpen" aria-label="Toggle menu">
            <svg v-if="!mobileOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg v-else class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-if="mobileOpen" class="md:hidden border-t border-gray-100 bg-white">
      <nav class="px-4 py-3 space-y-1">
        <NuxtLink to="/jobs" class="block py-2 text-gray-700 hover:text-vue transition" @click="mobileOpen = false">Find Jobs</NuxtLink>
        <NuxtLink to="/blog" class="block py-2 text-gray-700 hover:text-vue transition" @click="mobileOpen = false">Blog</NuxtLink>
        <NuxtLink v-if="auth.isEmployer" to="/recruiter" class="block py-2 text-gray-700 hover:text-vue transition" @click="mobileOpen = false">Recruiter Panel</NuxtLink>
      </nav>
      <div class="px-4 py-3 border-t border-gray-100 space-y-1">
        <template v-if="auth.isLoggedIn">
          <NuxtLink :to="auth.isEmployer ? '/recruiter' : '/dashboard'" class="block py-2 text-gray-700 hover:text-vue transition" @click="mobileOpen = false">Dashboard</NuxtLink>
          <button class="block py-2 text-red-500 text-sm" @click="auth.logout(); mobileOpen = false">Logout</button>
        </template>
        <template v-else>
          <NuxtLink to="/login" class="block py-2 text-gray-700 hover:text-vue transition" @click="mobileOpen = false">Login</NuxtLink>
          <NuxtLink to="/register" class="block py-2 text-vue font-medium" @click="mobileOpen = false">Sign Up</NuxtLink>
        </template>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
const auth = useAuthStore()
const mobileOpen = ref(false)
const dropdownOpen = ref(false)

// Close dropdown on click outside
function onClickOutside(e: MouseEvent) {
  const target = e.target as HTMLElement
  if (!target.closest('.relative')) {
    dropdownOpen.value = false
  }
}

onMounted(() => document.addEventListener('click', onClickOutside))
onUnmounted(() => document.removeEventListener('click', onClickOutside))

// Close menu on route change
const route = useRoute()
watch(() => route.path, () => {
  mobileOpen.value = false
  dropdownOpen.value = false
})
</script>
