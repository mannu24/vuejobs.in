<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Job Alerts</h1>
    </div>

    <div v-if="loading" class="space-y-4">
      <div v-for="i in 2" :key="i" class="bg-white rounded-xl p-6 border border-gray-200 animate-pulse">
        <div class="h-4 bg-gray-200 rounded w-1/2" />
      </div>
    </div>

    <div v-else-if="alerts.length" class="space-y-4">
      <div v-for="alert in alerts" :key="alert.id" class="bg-white rounded-xl border border-gray-200 p-6 flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-700">{{ JSON.stringify(alert.filters) }}</p>
          <p class="text-xs text-gray-400 mt-1">{{ alert.frequency }} &middot; Created {{ alert.created_at }}</p>
        </div>
        <button class="text-red-500 text-sm hover:underline" @click="deleteAlert(alert.id)">Delete</button>
      </div>
    </div>

    <div v-else class="text-center py-16 text-gray-500">
      No job alerts set up yet.
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const alerts = ref<any[]>([])
const loading = ref(true)

async function fetchAlerts() {
  try {
    const res = await apiFetch<{ data: any[] }>('/job-alerts')
    alerts.value = res.data
  } catch {} finally {
    loading.value = false
  }
}

async function deleteAlert(id: number) {
  await apiFetch(`/job-alerts/${id}`, { method: 'DELETE' })
  fetchAlerts()
}

onMounted(fetchAlerts)
</script>
