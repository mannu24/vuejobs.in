<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">My Applications</h1>

    <div v-if="loading" class="space-y-4">
      <div v-for="i in 3" :key="i" class="bg-white rounded-xl p-6 border border-gray-200 animate-pulse">
        <div class="h-4 bg-gray-200 rounded w-1/2 mb-2" />
        <div class="h-3 bg-gray-200 rounded w-1/3" />
      </div>
    </div>

    <div v-else-if="applications.length" class="space-y-4">
      <div v-for="app in applications" :key="app.id" class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-start justify-between">
          <div>
            <NuxtLink :to="`/jobs/${app.job?.slug}`" class="font-semibold text-gray-900 hover:text-vue transition">
              {{ app.job?.title }}
            </NuxtLink>
            <p class="text-sm text-gray-500">{{ app.job?.company?.name }}</p>
            <p class="text-xs text-gray-400 mt-1">Applied {{ timeAgo(app.created_at) }}</p>
          </div>
          <span
            class="px-2 py-0.5 rounded text-xs font-medium capitalize"
            :class="{
              'bg-blue-100 text-blue-700': app.status === 'applied',
              'bg-yellow-100 text-yellow-700': app.status === 'reviewed',
              'bg-green-100 text-green-700': app.status === 'shortlisted',
              'bg-red-100 text-red-700': app.status === 'rejected',
              'bg-purple-100 text-purple-700': app.status === 'hired',
            }"
          >
            {{ app.status }}
          </span>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-16 text-gray-500">
      No applications yet.
      <NuxtLink to="/jobs" class="text-vue underline">Browse jobs</NuxtLink>.
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const applications = ref<any[]>([])
const loading = ref(true)

function timeAgo(date: string) {
  if (!date) return ''
  const days = Math.floor((Date.now() - new Date(date).getTime()) / 86400000)
  if (days === 0) return 'today'
  if (days === 1) return '1 day ago'
  return `${days} days ago`
}

onMounted(async () => {
  try {
    const res = await apiFetch<{ data: any[] }>('/my-applications')
    applications.value = res.data
  } catch {} finally {
    loading.value = false
  }
})
</script>
