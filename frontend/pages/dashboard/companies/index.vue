<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">My Companies</h1>
      <NuxtLink to="/dashboard/companies/create" class="bg-vue text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition">
        Add Company
      </NuxtLink>
    </div>

    <div v-if="loading" class="space-y-4">
      <div v-for="i in 2" :key="i" class="bg-white rounded-xl p-6 border border-gray-200 animate-pulse">
        <div class="h-4 bg-gray-200 rounded w-1/2" />
      </div>
    </div>

    <div v-else-if="companies.length" class="space-y-4">
      <div v-for="company in companies" :key="company.id" class="bg-white rounded-xl border border-gray-200 p-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 font-bold shrink-0 overflow-hidden">
            <img v-if="company.logo_url" :src="company.logo_url" :alt="company.name" class="w-full h-full object-cover">
            <span v-else>{{ company.name?.charAt(0) }}</span>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900">{{ company.name }}</h3>
            <p class="text-sm text-gray-500">{{ company.jobs_count ?? 0 }} jobs</p>
          </div>
        </div>
        <NuxtLink :to="`/companies/${company.slug}`" class="text-vue text-sm hover:underline">View</NuxtLink>
      </div>
    </div>

    <div v-else class="text-center py-16 text-gray-500">
      No companies yet.
      <NuxtLink to="/dashboard/companies/create" class="text-vue underline">Add your first company</NuxtLink>.
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const companies = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await apiFetch<{ data: any[] }>('/companies')
    companies.value = res.data
  } catch {} finally {
    loading.value = false
  }
})
</script>
