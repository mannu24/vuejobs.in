<template>
  <div>
    <h1 class="text-xl font-bold text-gray-900 mb-6">All Applications</h1>

    <AppSpinner v-if="loading" />

    <div v-else-if="allApps.length" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="text-left text-xs font-medium text-gray-500 uppercase px-5 py-3">Candidate</th>
            <th class="text-left text-xs font-medium text-gray-500 uppercase px-5 py-3 hidden md:table-cell">Job</th>
            <th class="text-left text-xs font-medium text-gray-500 uppercase px-5 py-3">Status</th>
            <th class="text-left text-xs font-medium text-gray-500 uppercase px-5 py-3 hidden md:table-cell">Date</th>
            <th class="text-right text-xs font-medium text-gray-500 uppercase px-5 py-3">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="app in allApps" :key="app.id" class="hover:bg-gray-50">
            <td class="px-5 py-3">
              <div class="text-sm font-medium text-gray-900">{{ app.user?.name }}</div>
              <div class="text-xs text-gray-500">{{ app.user?.email }}</div>
            </td>
            <td class="px-5 py-3 hidden md:table-cell">
              <div class="text-sm text-gray-700">{{ app.jobTitle }}</div>
            </td>
            <td class="px-5 py-3">
              <select
                :value="app.status"
                :disabled="updatingId === app.id"
                class="rounded-lg border-gray-300 text-xs py-1 pr-8 disabled:opacity-50"
                @change="updateStatus(app.id, ($event.target as HTMLSelectElement).value)"
              >
                <option value="applied">Applied</option>
                <option value="reviewed">Reviewed</option>
                <option value="shortlisted">Shortlisted</option>
                <option value="rejected">Rejected</option>
                <option value="hired">Hired</option>
              </select>
            </td>
            <td class="px-5 py-3 hidden md:table-cell text-xs text-gray-500">{{ formatDate(app.created_at) }}</td>
            <td class="px-5 py-3 text-right">
              <a v-if="app.resume_url" :href="app.resume_url" target="_blank" class="text-xs text-vue hover:underline">Resume</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="text-center py-16 text-sm text-gray-400">No applications received yet.</div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'recruiter', middleware: 'recruiter' })

const { apiFetch } = useApi()
const allApps = ref<any[]>([])
const loading = ref(true)
const updatingId = ref<number | false>(false)

function formatDate(d: string) {
  return d ? new Date(d).toLocaleDateString('en', { month: 'short', day: 'numeric' }) : ''
}

async function updateStatus(appId: number, status: string) {
  updatingId.value = appId
  try { await apiFetch(`/applications/${appId}`, { method: 'PATCH', body: { status } }) }
  catch {} finally { updatingId.value = false }
}

onMounted(async () => {
  try {
    // Fetch all jobs, then fetch applications for each
    const jobsRes = await apiFetch<{ data: any[] }>('/jobs/mine')
    const jobs = jobsRes.data ?? []
    const apps: any[] = []
    for (const job of jobs) {
      try {
        const res = await apiFetch<{ data: any[] }>(`/jobs/${job.id}/applications`)
        for (const a of res.data ?? []) {
          apps.push({ ...a, jobTitle: job.title, jobId: job.id })
        }
      } catch {}
    }
    allApps.value = apps.sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime())
  } catch {} finally { loading.value = false }
})
</script>
