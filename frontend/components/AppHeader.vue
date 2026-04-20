<template>
  <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center gap-2">
          <span class="text-vue font-bold text-2xl">Vue</span>
          <span class="text-vue-dark font-bold text-2xl">Jobs</span>
        </NuxtLink>

        <!-- Nav -->
        <nav class="hidden md:flex items-center gap-6">
          <NuxtLink to="/jobs" class="text-gray-600 hover:text-vue transition">
            Find Jobs
          </NuxtLink>
          <NuxtLink v-if="auth.isEmployer" to="/dashboard/jobs" class="text-gray-600 hover:text-vue transition">
            My Jobs
          </NuxtLink>
          <NuxtLink v-if="auth.isEmployer" to="/dashboard/jobs/create" class="text-gray-600 hover:text-vue transition">
            Post a Job
          </NuxtLink>
        </nav>

        <!-- Auth -->
        <div class="flex items-center gap-4">
          <template v-if="auth.isLoggedIn">
            <NuxtLink to="/dashboard" class="text-gray-600 hover:text-vue transition text-sm">
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
