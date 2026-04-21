<template>
  <div>
    <div class="mb-6">
      <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-sm text-gray-500">Welcome back, {{ auth.user?.name }}</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-2xl font-bold text-vue">{{ stats.published ?? 0 }}</div>
        <div class="text-xs text-gray-500 mt-1">Active Jobs</div>
      </div>
      <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-2xl font-bold text-yellow-600">{{ stats.draft ?? 0 }}</div>
        <div class="text-xs text-gray-500 mt-1">Drafts</div>
      </div>
      <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-2xl font-bold text-blue-600">{{ stats.applications ?? 0 }}</div>
        <div class="text-xs text-gray-500 mt-1">Total Applications</div>
      </div>
      <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-2xl font-bold text-gray-700">{{ stats.companies ?? 0 }}</div>
        <div class="text-xs text-gray-500 mt-1">Companies</div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-3 mb-8">
      <NuxtLink to="/recruiter/jobs/create" class="bg-vue text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition">
        Post New Job
      </NuxtLink>
      <NuxtLink to="/recruiter/companies/create" class="border border-gray-300 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
        Add Company
      </NuxtLink>
    </div>

    <!-- Recent Jobs -->
    <div class="bg-white rounded-xl border border-gray-200">
      <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
        <h2 class="font-semibold text-gray-900 text-sm">Recent Jobs</h2>
        <NuxtLink to="/recruiter/jobs" class="text-xs text-vue hover:underline">View all</NuxtLink>
      </div>
      <div v-if="loading" class="p-5">
        <AppSpinner />
      </div>
      <div v-else-if="recentJobs.length" class="divide-y divide-gray-100">
        <NuxtLink
          v-for="job in recentJobs" :key="job.id"
          :to="`/recruiter/jobs/${job.id}`"
          class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition"
        >
          <div>
            <div class="text-sm font-medium text-gray-900">{{ job.title }}</div>
            <div class="text-xs text-gray-500">{{ job.company?.name }}</div>
          </div>
          <div class="flex items-center gap-3">
            <span
              class="px-2 py-0.5 rounded text-xs font-medium"
              :class="{
                'bg-green-100 text-green-700': job.status === 'published',
                'bg-yellow-100 text-yellow-700': job.status === 'draft',
                'bg-gray-100 text-gray-500': job.status === 'archived',
              }"
            >{{ job.status }}</span>
            <span class="text-xs text-gray-400">{{ timeAgo(job.created_at) }}</span>
          </div>
        </NuxtLink>
      </div>
      <div v-else class="p-5 text-center text-sm text-gray-400">No jobs yet</div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'recruiter', middleware: 'recruiter' })

const auth = useAuthStore()
const { apiFetch } = useApi()

const stats = ref<Record<string, number>>({})
const recentJobs = ref<any[]>([])
const loading = ref(true)

function timeAgo(date: string) {
  if (!date) return ''
  const days = Math.floor((Date.now() - new Date(date).getTime()) / 86400000)
  if (days === 0) return 'Today'
  if (days === 1) return '1d ago'
  if (days < 30) return `${days}d ago`
  return `${Math.floor(days / 30)}mo ago`
}

onMounted(async () => {
  await auth.fetchUser()
  try {
    const [jobsRes, companiesRes] = await Promise.all([
      apiFetch<{ data: any[]; meta: any }>('/jobs/mine'),
      apiFetch<{ data: any[]; meta: any }>('/companies'),
    ])
    const allJobs = jobsRes.data ?? []
    recentJobs.value = allJobs.slice(0, 5)

    // Count applications across all jobs
    let totalApps = 0
    for (const job of allJobs) {
      try {
        const res = await apiFetch<{ data: any[]; meta: any }>(`/jobs/${job.id}/applications`)
        totalApps += res.meta?.total ?? res.data?.length ?? 0
      } catch {}
    }

    stats.value = {
      published: allJobs.filter((j: any) => j.status === 'published').length,
      draft: allJobs.filter((j: any) => j.status === 'draft').length,
      applications: totalApps,
      companies: companiesRes.meta?.total ?? companiesRes.data?.length ?? 0,
    }
  } catch {} finally {
    loading.value = false
  }
})
</script>
