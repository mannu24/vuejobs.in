<template>
  <div class="min-h-screen bg-gray-100 font-display">
    <!-- Top bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
      <div class="flex items-center justify-between h-14 px-4 lg:px-6">
        <div class="flex items-center gap-4">
          <button class="lg:hidden text-gray-500" @click="sidebarOpen = !sidebarOpen">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <NuxtLink to="/recruiter" class="flex items-center gap-2">
            <img src="/logo-small.png" alt="VueJobs" style="width:32px;height:32px;">
            <span class="hidden sm:inline text-sm font-semibold text-gray-800">Recruiter Panel</span>
          </NuxtLink>
        </div>
        <div class="flex items-center gap-3">
          <NuxtLink to="/jobs" class="text-xs text-gray-500 hover:text-vue transition">Explore Jobs</NuxtLink>
          <div class="w-8 h-8 rounded-full bg-vue/20 flex items-center justify-center text-vue font-semibold text-xs">
            {{ auth.user?.name?.charAt(0)?.toUpperCase() }}
          </div>
          <button class="text-xs text-gray-400 hover:text-red-500" @click="auth.logout()">Logout</button>
        </div>
      </div>
    </header>

    <div class="flex">
      <!-- Sidebar overlay (mobile) -->
      <div v-if="sidebarOpen" class="fixed inset-0 bg-black/30 z-40 lg:hidden" @click="sidebarOpen = false" />

      <!-- Sidebar -->
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
            <span class="material-icon text-lg" v-html="item.icon" />
            {{ item.label }}
          </NuxtLink>
        </nav>
      </aside>

      <!-- Main content -->
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
  { to: '/recruiter', label: 'Dashboard', icon: '&#9632;' },
  { to: '/recruiter/jobs', label: 'Jobs', icon: '&#9998;' },
  { to: '/recruiter/jobs/create', label: 'Post Job', icon: '&#43;' },
  { to: '/recruiter/applications', label: 'Applications', icon: '&#9993;' },
  { to: '/recruiter/companies', label: 'Companies', icon: '&#9881;' },
]

function isActive(path: string) {
  if (path === '/recruiter') return route.path === '/recruiter'
  if (path === '/recruiter/jobs/create') return route.path === '/recruiter/jobs/create'
  if (path === '/recruiter/jobs') return route.path.startsWith('/recruiter/jobs') && route.path !== '/recruiter/jobs/create'
  return route.path.startsWith(path)
}
</script>

<style scoped>
.material-icon {
  width: 20px;
  text-align: center;
  font-style: normal;
}
</style>
