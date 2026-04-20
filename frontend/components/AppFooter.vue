<template>
  <footer class="bg-vue-dark text-gray-300 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <NuxtLink to="/" class="flex items-center gap-1 mb-4">
            <span class="text-vue font-bold text-xl">Vue</span>
            <span class="text-white font-bold text-xl">Jobs</span>
          </NuxtLink>
          <p class="text-sm text-gray-400">
            The dedicated job board for Vue.js, Nuxt.js and the Vue ecosystem.
          </p>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-3 text-sm">For Developers</h4>
          <ul class="space-y-2 text-sm">
            <li><NuxtLink to="/jobs" class="hover:text-vue transition">Browse Jobs</NuxtLink></li>
            <li><NuxtLink to="/register" class="hover:text-vue transition">Create Profile</NuxtLink></li>
          </ul>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-3 text-sm">For Employers</h4>
          <ul class="space-y-2 text-sm">
            <li><NuxtLink to="/dashboard/jobs/create" class="hover:text-vue transition">Post a Job</NuxtLink></li>
            <li><NuxtLink to="/register" class="hover:text-vue transition">Create Account</NuxtLink></li>
          </ul>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-3 text-sm">Company</h4>
          <ul class="space-y-2 text-sm">
            <li><NuxtLink to="/about" class="hover:text-vue transition">About</NuxtLink></li>
            <li><NuxtLink to="/contact" class="hover:text-vue transition">Contact</NuxtLink></li>
          </ul>
        </div>
      </div>
      <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-500">
        &copy; {{ new Date().getFullYear() }} VueJobs.in — All rights reserved.
      </div>
    </div>
  </footer>
</template>
