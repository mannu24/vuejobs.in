<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-xl font-bold text-gray-900">Companies</h1>
      <NuxtLink to="/recruiter/companies/create" class="bg-vue text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition">
        + Add Company
      </NuxtLink>
    </div>

    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div v-for="i in 4" :key="i" class="bg-white rounded-xl border border-gray-200 p-5 animate-pulse">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-lg bg-gray-200 shrink-0" />
          <div class="flex-1 min-w-0">
            <div class="h-4 bg-gray-200 rounded w-2/3 mb-1" />
            <div class="h-3 bg-gray-200 rounded w-1/2" />
          </div>
          <div class="h-4 bg-gray-200 rounded w-10" />
        </div>
      </div>
    </div>

    <div v-else-if="companies.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div v-for="c in companies" :key="c.id" class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 overflow-hidden">
            <img v-if="c.logo_url" :src="c.logo_url" :alt="c.name" class="w-full h-full object-cover">
            <svg v-else class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="font-semibold text-gray-900 text-sm truncate">{{ c.name }}</div>
            <div class="text-xs text-gray-500">{{ c.jobs_count ?? 0 }} jobs &middot; {{ c.industry || 'No industry' }}</div>
          </div>
          <NuxtLink :to="`/companies/${c.slug}`" class="text-xs text-vue hover:underline shrink-0">View</NuxtLink>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-16 text-sm text-gray-400">
      No companies yet. <NuxtLink to="/recruiter/companies/create" class="text-vue underline">Add one</NuxtLink>.
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'recruiter', middleware: 'recruiter' })

const { apiFetch } = useApi()
const companies = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  try { const res = await apiFetch<{ data: any[] }>('/companies'); companies.value = res.data } catch {} finally { loading.value = false }
})
</script>
