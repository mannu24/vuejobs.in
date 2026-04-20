<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">My Jobs</h1>
      <NuxtLink to="/dashboard/jobs/create" class="bg-vue text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition">
        Post New Job
      </NuxtLink>
    </div>

    <div v-if="loading" class="space-y-4">
      <div v-for="i in 3" :key="i" class="bg-white rounded-xl p-6 border border-gray-200 animate-pulse">
        <div class="h-4 bg-gray-200 rounded w-1/2 mb-2" />
        <div class="h-3 bg-gray-200 rounded w-1/3" />
      </div>
    </div>

    <div v-else-if="jobs.length" class="space-y-4">
      <div v-for="job in jobs" :key="job.id" class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-start justify-between">
          <div>
            <h3 class="font-semibold text-gray-900">{{ job.title }}</h3>
            <p class="text-sm text-gray-500">{{ job.company?.name }} &middot; {{ job.location_type }}</p>
            <div class="flex gap-2 mt-2">
              <span
                class="px-2 py-0.5 rounded text-xs font-medium"
                :class="{
                  'bg-green-100 text-green-700': job.status === 'published',
                  'bg-yellow-100 text-yellow-700': job.status === 'draft',
                  'bg-gray-100 text-gray-600': job.status === 'archived',
                }"
              >
                {{ job.status }}
              </span>
              <span v-if="job.featured_until" class="px-2 py-0.5 rounded text-xs font-medium bg-vue/10 text-vue">Featured</span>
            </div>
          </div>
          <div class="flex gap-2">
            <button
              v-if="job.status === 'draft'"
              class="text-xs text-vue hover:underline"
              @click="publishJob(job.id)"
            >
              Publish
            </button>
            <button
              v-if="job.status === 'published'"
              class="text-xs text-gray-500 hover:underline"
              @click="archiveJob(job.id)"
            >
              Archive
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-16 text-gray-500">
      No jobs yet.
      <NuxtLink to="/dashboard/jobs/create" class="text-vue underline">Post your first job</NuxtLink>.
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const jobs = ref<any[]>([])
const loading = ref(true)

async function fetchJobs() {
  loading.value = true
  try {
    const res = await apiFetch<{ data: any[] }>('/jobs/mine')
    jobs.value = res.data
  } catch {} finally {
    loading.value = false
  }
}

async function publishJob(id: number) {
  await apiFetch(`/jobs/${id}/publish`, { method: 'POST' })
  fetchJobs()
}

async function archiveJob(id: number) {
  await apiFetch(`/jobs/${id}/archive`, { method: 'POST' })
  fetchJobs()
}

onMounted(fetchJobs)
</script>
