<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Saved Jobs</h1>

    <div v-if="loading" class="space-y-4">
      <div v-for="i in 3" :key="i" class="bg-white rounded-xl p-6 border border-gray-200 animate-pulse">
        <div class="h-4 bg-gray-200 rounded w-1/2 mb-2" />
        <div class="h-3 bg-gray-200 rounded w-1/3" />
      </div>
    </div>

    <div v-else-if="jobs.length" class="space-y-4">
      <JobCard v-for="job in jobs" :key="job.id" :job="job" />
    </div>

    <div v-else class="text-center py-16 text-gray-500">
      No saved jobs.
      <NuxtLink to="/jobs" class="text-vue underline">Browse jobs</NuxtLink>.
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const jobs = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await apiFetch<{ data: any[] }>('/saved-jobs')
    jobs.value = res.data
  } catch {} finally {
    loading.value = false
  }
})
</script>
