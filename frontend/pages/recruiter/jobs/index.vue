<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-xl font-bold text-gray-900">Jobs</h1>
      <NuxtLink to="/recruiter/jobs/create" class="bg-vue text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition">
        + New Job
      </NuxtLink>
    </div>

    <!-- Filter tabs -->
    <div class="flex gap-1 mb-4 bg-white rounded-lg border border-gray-200 p-1 w-fit">
      <button
        v-for="tab in tabs" :key="tab.value"
        class="px-3 py-1.5 rounded-md text-xs font-medium transition"
        :class="activeTab === tab.value ? 'bg-vue text-white' : 'text-gray-500 hover:text-gray-700'"
        @click="activeTab = tab.value"
      >
        {{ tab.label }} ({{ tabCounts[tab.value] ?? 0 }})
      </button>
    </div>

    <!-- Jobs table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
      <SkeletonTable v-if="loading" :rows="5" />
      <table v-else-if="filteredJobs.length" class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="text-left text-xs font-medium text-gray-500 uppercase px-5 py-3">Job</th>
            <th class="text-left text-xs font-medium text-gray-500 uppercase px-5 py-3 hidden md:table-cell">Status</th>
            <th class="text-left text-xs font-medium text-gray-500 uppercase px-5 py-3 hidden lg:table-cell">Apply</th>
            <th class="text-left text-xs font-medium text-gray-500 uppercase px-5 py-3 hidden md:table-cell">Date</th>
            <th class="text-right text-xs font-medium text-gray-500 uppercase px-5 py-3">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="job in filteredJobs" :key="job.id" class="hover:bg-gray-50 transition">
            <td class="px-5 py-4">
              <NuxtLink :to="`/recruiter/jobs/${job.id}`" class="text-sm font-medium text-gray-900 hover:text-vue transition">
                {{ job.title }}
              </NuxtLink>
              <div class="text-xs text-gray-500">{{ job.company?.name }} &middot; {{ formatLocation(job) }}</div>
            </td>
            <td class="px-5 py-4 hidden md:table-cell">
              <span
                class="px-2 py-0.5 rounded text-xs font-medium"
                :class="{
                  'bg-green-100 text-green-700': job.status === 'published',
                  'bg-yellow-100 text-yellow-700': job.status === 'draft',
                  'bg-gray-100 text-gray-500': job.status === 'archived',
                }"
              >{{ job.status }}</span>
            </td>
            <td class="px-5 py-4 hidden lg:table-cell">
              <span v-if="job.is_direct_apply" class="text-xs text-green-600">Direct</span>
              <span v-else-if="job.apply_url" class="text-xs text-blue-600">External</span>
              <span v-else class="text-xs text-gray-400">—</span>
            </td>
            <td class="px-5 py-4 hidden md:table-cell text-xs text-gray-500">{{ formatDate(job.created_at) }}</td>
            <td class="px-5 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <NuxtLink :to="`/recruiter/jobs/${job.id}`" class="text-xs text-vue hover:underline">View</NuxtLink>
                <NuxtLink :to="`/recruiter/jobs/${job.id}/edit`" class="text-xs text-gray-500 hover:underline">Edit</NuxtLink>
                <button v-if="job.status === 'draft'" class="text-xs text-green-600 hover:underline disabled:opacity-50" :disabled="!!busyJobId" @click="publishJob(job.id)">
                  {{ busyJobId === job.id ? '...' : 'Publish' }}
                </button>
                <button v-if="job.status === 'published'" class="text-xs text-yellow-600 hover:underline disabled:opacity-50" :disabled="!!busyJobId" @click="archiveJob(job.id)">
                  {{ busyJobId === job.id ? '...' : 'Archive' }}
                </button>
                <button class="text-xs text-red-500 hover:underline disabled:opacity-50" :disabled="!!busyJobId" @click="deleteJob(job.id)">
                  {{ busyJobId === job.id ? '...' : 'Delete' }}
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-else class="p-8 text-center text-sm text-gray-400">
        No {{ activeTab === 'all' ? '' : activeTab }} jobs found.
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'recruiter', middleware: 'recruiter' })

const { apiFetch } = useApi()
const jobs = ref<any[]>([])
const loading = ref(true)
const activeTab = ref('all')
const busyJobId = ref<number | null>(null)

const tabs = [
  { label: 'All', value: 'all' },
  { label: 'Published', value: 'published' },
  { label: 'Draft', value: 'draft' },
  { label: 'Archived', value: 'archived' },
]

const tabCounts = computed(() => {
  const counts: Record<string, number> = { all: jobs.value.length }
  for (const j of jobs.value) counts[j.status] = (counts[j.status] ?? 0) + 1
  return counts
})

const filteredJobs = computed(() =>
  activeTab.value === 'all' ? jobs.value : jobs.value.filter(j => j.status === activeTab.value)
)

function formatLocation(job: any) {
  return [job.location, job.country].filter(Boolean).join(', ') || job.location_type
}

function formatDate(d: string) {
  return d ? new Date(d).toLocaleDateString('en', { month: 'short', day: 'numeric', year: 'numeric' }) : ''
}

async function fetchJobs() {
  loading.value = true
  try {
    const res = await apiFetch<{ data: any[] }>('/jobs/mine')
    jobs.value = res.data
  } catch {} finally { loading.value = false }
}

async function publishJob(id: number) {
  busyJobId.value = id
  try { await apiFetch(`/jobs/${id}/publish`, { method: 'POST' }); await fetchJobs() }
  catch {} finally { busyJobId.value = null }
}

async function archiveJob(id: number) {
  busyJobId.value = id
  try { await apiFetch(`/jobs/${id}/archive`, { method: 'POST' }); await fetchJobs() }
  catch {} finally { busyJobId.value = null }
}

async function deleteJob(id: number) {
  if (!confirm('Delete this job permanently?')) return
  busyJobId.value = id
  try { await apiFetch(`/jobs/${id}`, { method: 'DELETE' }); await fetchJobs() }
  catch {} finally { busyJobId.value = null }
}

onMounted(fetchJobs)
</script>
