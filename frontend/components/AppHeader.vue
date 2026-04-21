<template>
  <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center gap-2">
          <img src="/logo-small.png" alt="VueJobs" class="md:hidden" style="width:40px;height:40px;">
          <img src="/logo.png" alt="VueJobs" class="hidden md:block" style="width:130px;height:40px;">
        </NuxtLink>

        <!-- Nav -->
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

        <!-- Auth -->
        <div class="flex items-center gap-4">
          <template v-if="auth.isLoggedIn">
            <NuxtLink :to="auth.isEmployer ? '/recruiter' : '/dashboard'" class="text-gray-600 hover:text-vue transition text-sm">
              Dashboard
            </NuxtLink>
            <button
              class="text-sm text-gray-500 hover:text-red-500 transition"
              @click="auth.logout()"
            >
              Logout
            </button>
            <div class="w-8 h-8 rounded-full bg-vue/20 flex items-center justify-center text-vue font-semibold text-sm">
              {{ auth.user?.name?.charAt(0)?.toUpperCase() }}
            </div>
          </template>
          <template v-else>
            <NuxtLink to="/login" class="text-gray-600 hover:text-vue transition text-sm">
              Login
            </NuxtLink>
            <NuxtLink
              to="/register"
              class="bg-vue text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition"
            >
              Sign Up
            </NuxtLink>
          </template>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
const auth = useAuthStore()
</script>
