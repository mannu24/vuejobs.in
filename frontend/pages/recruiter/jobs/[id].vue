<template>
  <div>
    <AppSpinner v-if="loading" />

    <template v-else-if="job">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
          <NuxtLink to="/recruiter/jobs" class="text-xs text-gray-400 hover:text-vue transition">&larr; Back to Jobs</NuxtLink>
          <h1 class="text-xl font-bold text-gray-900 mt-1">{{ job.title }}</h1>
          <p class="text-sm text-gray-500">{{ job.company?.name }}</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          <span
            class="px-2.5 py-1 rounded text-xs font-medium"
            :class="{
              'bg-green-100 text-green-700': job.status === 'published',
              'bg-yellow-100 text-yellow-700': job.status === 'draft',
              'bg-gray-100 text-gray-500': job.status === 'archived',
            }"
          >{{ job.status }}</span>
          <NuxtLink :to="`/recruiter/jobs/${job.id}/edit`" class="border border-gray-300 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-50 transition">
            Edit
          </NuxtLink>
          <button
            v-if="job.status === 'draft'"
            class="bg-vue text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-vue/90 transition disabled:opacity-50"
            :disabled="actionLoading"
            @click="publish"
          >{{ actionLoading === 'publish' ? 'Publishing...' : 'Publish' }}</button>
          <button
            v-if="job.status === 'published'"
            class="border border-yellow-300 text-yellow-700 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-yellow-50 transition disabled:opacity-50"
            :disabled="actionLoading"
            @click="archive"
          >{{ actionLoading === 'archive' ? 'Archiving...' : 'Archive' }}</button>
          <a :href="`/jobs/${job.slug}`" target="_blank" class="text-xs text-vue hover:underline">View Public &rarr;</a>
        </div>
      </div>

      <!-- Stats cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
          <div class="text-lg font-bold text-gray-900">{{ applications.length }}</div>
          <div class="text-xs text-gray-500">Applications</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
          <div class="text-lg font-bold text-blue-600">{{ applicationsByStatus('reviewed') }}</div>
          <div class="text-xs text-gray-500">Reviewed</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
          <div class="text-lg font-bold text-green-600">{{ applicationsByStatus('shortlisted') }}</div>
          <div class="text-xs text-gray-500">Shortlisted</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
          <div class="text-lg font-bold text-purple-600">{{ applicationsByStatus('hired') }}</div>
          <div class="text-xs text-gray-500">Hired</div>
        </div>
      </div>

      <!-- Job details -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-4">
          <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="font-semibold text-gray-900 text-sm mb-3">Job Details</h2>
            <div class="grid grid-cols-2 gap-3 text-sm">
              <div><span class="text-gray-500">Location:</span> <span class="text-gray-900">{{ formatLocation(job) }}</span></div>
              <div><span class="text-gray-500">Type:</span> <span class="text-gray-900 capitalize">{{ job.contract_type?.replace('_', ' ') }}</span></div>
              <div><span class="text-gray-500">Experience:</span> <span class="text-gray-900 capitalize">{{ job.experience_level || 'Any' }}</span></div>
              <div><span class="text-gray-500">Apply:</span> <span :class="job.is_direct_apply ? 'text-green-600' : 'text-blue-600'">{{ job.is_direct_apply ? 'Direct on VueJobs' : 'External Link' }}</span></div>
              <div v-if="job.salary_min || job.salary_max"><span class="text-gray-500">Salary:</span> <span class="text-gray-900">{{ formatSalary(job) }}</span></div>
              <div v-if="job.vue_version"><span class="text-gray-500">Vue:</span> <span class="text-gray-900">{{ job.vue_version }}</span></div>
              <div v-if="job.published_at"><span class="text-gray-500">Published:</span> <span class="text-gray-900">{{ formatDate(job.published_at) }}</span></div>
              <div v-if="job.expires_at"><span class="text-gray-500">Expires:</span> <span class="text-gray-900">{{ formatDate(job.expires_at) }}</span></div>
            </div>
            <div v-if="job.skills?.length" class="mt-3 flex flex-wrap gap-1.5">
              <span v-for="s in job.skills" :key="s" class="px-2 py-0.5 bg-gray-100 rounded text-xs text-gray-600">{{ s }}</span>
            </div>
          </div>
          <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="font-semibold text-gray-900 text-sm mb-3">Description</h2>
            <div class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ job.description }}</div>
          </div>
        </div>
        <div class="space-y-4">
          <div v-if="job.apply_url" class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="font-semibold text-gray-900 text-sm mb-2">External Apply URL</h2>
            <a :href="job.apply_url" target="_blank" class="text-xs text-vue break-all hover:underline">{{ job.apply_url }}</a>
          </div>
          <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="font-semibold text-gray-900 text-sm mb-2">Company</h2>
            <div class="text-sm text-gray-700">{{ job.company?.name }}</div>
            <div v-if="job.company?.website" class="text-xs text-gray-500 mt-1">{{ job.company.website }}</div>
          </div>
        </div>
      </div>

      <!-- Applications -->
      <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100">
          <h2 class="font-semibold text-gray-900 text-sm">Applications ({{ applications.length }})</h2>
        </div>
        <div v-if="applications.length" class="divide-y divide-gray-100">
          <div v-for="app in applications" :key="app.id" class="px-5 py-4">
            <div class="flex items-start justify-between gap-4">
              <div>
                <div class="text-sm font-medium text-gray-900">{{ app.user?.name }}</div>
                <div class="text-xs text-gray-500">{{ app.user?.email }}</div>
                <div v-if="app.cover_letter" class="text-xs text-gray-600 mt-2 line-clamp-2">{{ app.cover_letter }}</div>
                <div class="flex gap-2 mt-2">
                  <a v-if="app.resume_url" :href="app.resume_url" target="_blank" class="text-xs text-vue hover:underline">Resume</a>
                  <span class="text-xs text-gray-400">Applied {{ formatDate(app.created_at) }}</span>
                </div>
              </div>
              <div class="shrink-0">
                <select
                  :value="app.status"
                  :disabled="updatingStatus === app.id"
                  class="rounded-lg border-gray-300 text-xs py-1 pr-8 disabled:opacity-50"
                  @change="updateStatus(app.id, ($event.target as HTMLSelectElement).value)"
                >
                  <option value="applied">Applied</option>
                  <option value="reviewed">Reviewed</option>
                  <option value="shortlisted">Shortlisted</option>
                  <option value="rejected">Rejected</option>
                  <option value="hired">Hired</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="p-8 text-center text-sm text-gray-400">
          {{ job.is_direct_apply ? 'No applications yet.' : 'This job uses an external apply link.' }}
        </div>
      </div>
    </template>

    <div v-else class="text-center py-16 text-gray-500">Job not found.</div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'recruiter', middleware: 'recruiter' })

const route = useRoute()
const { apiFetch } = useApi()

const job = ref<any>(null)
const applications = ref<any[]>([])
const loading = ref(true)
const actionLoading = ref<string | false>(false)
const updatingStatus = ref<number | false>(false)

function applicationsByStatus(status: string) {
  return applications.value.filter(a => a.status === status).length
}

function formatLocation(j: any) {
  return [j.location, j.country].filter(Boolean).join(', ') || j.location_type?.replace('_', ' ')
}

function formatSalary(j: any) {
  const parts = []
  if (j.salary_min) parts.push(new Intl.NumberFormat('en').format(j.salary_min))
  if (j.salary_max) parts.push(new Intl.NumberFormat('en').format(j.salary_max))
  return `${j.currency ?? 'USD'} ${parts.join(' – ')} / ${j.salary_interval ?? 'year'}`
}

function formatDate(d: string) {
  return d ? new Date(d).toLocaleDateString('en', { month: 'short', day: 'numeric', year: 'numeric' }) : ''
}

async function publish() {
  actionLoading.value = 'publish'
  try { await apiFetch(`/jobs/${route.params.id}/publish`, { method: 'POST' }); await fetchJob() }
  catch {} finally { actionLoading.value = false }
}

async function archive() {
  actionLoading.value = 'archive'
  try { await apiFetch(`/jobs/${route.params.id}/archive`, { method: 'POST' }); await fetchJob() }
  catch {} finally { actionLoading.value = false }
}

async function updateStatus(appId: number, status: string) {
  updatingStatus.value = appId
  try { await apiFetch(`/applications/${appId}`, { method: 'PATCH', body: { status } }); await fetchApplications() }
  catch {} finally { updatingStatus.value = false }
}

async function fetchJob() {
  try { const res = await apiFetch<{ data: any }>(`/jobs/mine/${route.params.id}`); job.value = res.data } catch {}
}

async function fetchApplications() {
  try { const res = await apiFetch<{ data: any[] }>(`/jobs/${route.params.id}/applications`); applications.value = res.data } catch {}
}

onMounted(async () => {
  await Promise.all([fetchJob(), fetchApplications()])
  loading.value = false
})
</script>
