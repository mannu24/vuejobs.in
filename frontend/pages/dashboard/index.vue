<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-2">
      Welcome back, {{ auth.user?.name }}
    </h1>
    <p class="text-gray-500 mb-8">{{ auth.isEmployer ? 'Recruiter Dashboard' : 'Developer Dashboard' }}</p>

    <!-- Employer Dashboard -->
    <template v-if="auth.isEmployer">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <NuxtLink to="/dashboard/jobs" class="bg-white rounded-xl border border-gray-200 p-6 hover:border-vue/50 transition">
          <div class="text-3xl font-bold text-vue">{{ stats.jobs ?? 0 }}</div>
          <div class="text-sm text-gray-500 mt-1">My Jobs</div>
        </NuxtLink>
        <NuxtLink to="/dashboard/companies" class="bg-white rounded-xl border border-gray-200 p-6 hover:border-vue/50 transition">
          <div class="text-3xl font-bold text-vue">{{ stats.companies ?? 0 }}</div>
          <div class="text-sm text-gray-500 mt-1">Companies</div>
        </NuxtLink>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
          <div class="text-3xl font-bold text-vue">{{ stats.applications ?? 0 }}</div>
          <div class="text-sm text-gray-500 mt-1">Applications Received</div>
        </div>
      </div>

      <div class="flex gap-4">
        <NuxtLink to="/dashboard/jobs/create" class="bg-vue text-white px-6 py-2.5 rounded-lg font-medium hover:bg-vue/90 transition">
          Post a New Job
        </NuxtLink>
        <NuxtLink to="/dashboard/companies/create" class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition">
          Add Company
        </NuxtLink>
      </div>
    </template>

    <!-- Developer Dashboard -->
    <template v-else>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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
    </template>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const auth = useAuthStore()
const { apiFetch } = useApi()

const stats = ref<Record<string, number>>({})

onMounted(async () => {
  await auth.fetchUser()

  if (auth.isEmployer) {
    try {
      const [jobsRes, companiesRes] = await Promise.all([
        apiFetch<{ data: any[]; meta: any }>('/jobs/mine'),
        apiFetch<{ data: any[]; meta: any }>('/companies'),
      ])
      stats.value = {
        jobs: jobsRes.meta?.total ?? jobsRes.data?.length ?? 0,
        companies: companiesRes.meta?.total ?? companiesRes.data?.length ?? 0,
        applications: 0,
      }
    } catch {}
  } else {
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
    } catch {}
  }
})
</script>
