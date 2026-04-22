<template>
  <div class="min-h-screen bg-gray-100 font-display">
    <!-- Top bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
      <div class="flex items-center justify-between h-14 px-4 lg:px-6">
        <div class="flex items-center gap-4">
          <button class="lg:hidden text-gray-500" @click="sidebarOpen = !sidebarOpen">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <NuxtLink to="/dashboard" class="flex items-center gap-2">
            <img src="/logo-small.png" alt="VueJobs" style="width:32px;height:32px;">
            <span class="hidden sm:inline text-sm font-semibold text-gray-800">Dashboard</span>
          </NuxtLink>
        </div>
        <div class="flex items-center gap-3">
          <NuxtLink to="/jobs" class="text-xs text-gray-500 hover:text-vue transition">Browse Jobs</NuxtLink>
          <NuxtLink to="/blog" class="text-xs text-gray-500 hover:text-vue transition">Blog</NuxtLink>
          <div class="w-8 h-8 rounded-full bg-vue/20 flex items-center justify-center text-vue font-semibold text-xs">
            {{ auth.user?.name?.charAt(0)?.toUpperCase() }}
          </div>
          <button class="text-xs text-gray-400 hover:text-red-500" @click="auth.logout()">Logout</button>
        </div>
      </div>
    </header>

    <div class="flex">
      <div v-if="sidebarOpen" class="fixed inset-0 bg-black/30 z-40 lg:hidden" @click="sidebarOpen = false" />

      <aside
        class="fixed lg:sticky top-14 left-0 z-40 h-[calc(100vh-3.5rem)] w-60 bg-white border-r border-gray-200 overflow-y-auto transition-transform lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
      >
        <nav class="p-4 space-y-1">
          <NuxtLink
            v-for="item in navItems" :key="item.to" :to="item.to"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition"
            :class="isActive(item.to) ? 'bg-vue/10 text-vue font-medium' : 'text-gray-600 hover:bg-gray-50'"
            @click="sidebarOpen = false"
          >
            <span v-html="item.icon" class="w-5 text-center text-lg" style="font-style:normal;" />
            {{ item.label }}
          </NuxtLink>
        </nav>
      </aside>

      <main class="flex-1 min-h-[calc(100vh-3.5rem)] p-4 lg:p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
const auth = useAuthStore()
const route = useRoute()
const sidebarOpen = ref(false)

const navItems = [
  { to: '/dashboard', label: 'Overview', icon: '&#9632;' },
  { to: '/dashboard/profile', label: 'My Profile', icon: '&#9787;' },
  { to: '/dashboard/applications', label: 'Applications', icon: '&#9993;' },
  { to: '/dashboard/saved-jobs', label: 'Saved Jobs', icon: '&#9829;' },
  { to: '/dashboard/alerts', label: 'Job Alerts', icon: '&#9888;' },
]

function isActive(path: string) {
  if (path === '/dashboard') return route.path === '/dashboard'
  return route.path.startsWith(path)
}
</script>
