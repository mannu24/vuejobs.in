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
            <NuxtLink :to="auth.isEmployer ? '/recruiter' : '/dashboard'" class="hidden md:inline text-gray-600 hover:text-vue transition text-sm">
              Dashboard
            </NuxtLink>
            <button
              class="hidden md:inline text-sm text-gray-500 hover:text-red-500 transition"
              @click="auth.logout()"
            >
              Logout
            </button>
            <div class="hidden md:flex w-8 h-8 rounded-full bg-vue/20 items-center justify-center text-vue font-semibold text-sm">
              {{ auth.user?.name?.charAt(0)?.toUpperCase() }}
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

// Close menu on route change
const route = useRoute()
watch(() => route.path, () => { mobileOpen.value = false })
</script>
