<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Saved Jobs</h1>

    <div v-if="loading" class="space-y-4">
      <SkeletonJobCard v-for="i in 4" :key="i" />
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
definePageMeta({ layout: 'dashboard', middleware: 'auth' })

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
