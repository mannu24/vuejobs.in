<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-900 mb-2">
      Welcome back, {{ auth.user?.name }}
    </h1>
    <p class="text-gray-500 mb-8">Developer Dashboard</p>

    <div v-if="loading">
      <SkeletonDashboardStats :count="3" />
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <NuxtLink to="/dashboard/applications" class="bg-white rounded-xl border border-gray-200 p-6 hover:border-vue/50 transition">
        <div class="text-3xl font-bold text-vue">{{ stats.applications ?? 0 }}</div>
        <div class="text-sm text-gray-500 mt-1">Applications Sent</div>
      </NuxtLink>
      <NuxtLink to="/dashboard/saved-jobs" class="bg-white rounded-xl border border-gray-200 p-6 hover:border-vue/50 transition">
        <div class="text-3xl font-bold text-vue">{{ stats.savedJobs ?? 0 }}</div>
        <div class="text-sm text-gray-500 mt-1">Saved Jobs</div>
      </NuxtLink>
      <NuxtLink to="/dashboard/alerts" class="bg-white rounded-xl border border-gray-200 p-6 hover:border-vue/50 transition">
        <div class="text-3xl font-bold text-vue">{{ stats.alerts ?? 0 }}</div>
        <div class="text-sm text-gray-500 mt-1">Job Alerts</div>
      </NuxtLink>
    </div>

    <NuxtLink to="/jobs" class="bg-vue text-white px-6 py-2.5 rounded-lg font-medium hover:bg-vue/90 transition">
      Browse Jobs
    </NuxtLink>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard', middleware: 'auth' })

const auth = useAuthStore()
const { apiFetch } = useApi()

const stats = ref<Record<string, number>>({})
const loading = ref(true)

onMounted(async () => {
  await auth.fetchUser()

  if (auth.isEmployer) {
    navigateTo('/recruiter')
    return
  }

  try {
    const [appsRes, savedRes, alertsRes] = await Promise.all([
      apiFetch<{ data: any[]; meta: any }>('/my-applications'),
      apiFetch<{ data: any[]; meta: any }>('/saved-jobs'),
      apiFetch<{ data: any[] }>('/job-alerts'),
    ])
    stats.value = {
      applications: appsRes.meta?.total ?? appsRes.data?.length ?? 0,
      savedJobs: savedRes.meta?.total ?? savedRes.data?.length ?? 0,
      alerts: alertsRes.data?.length ?? 0,
    }
  } catch {} finally {
    loading.value = false
  }
})
</script>
