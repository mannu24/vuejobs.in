<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Vue.js Jobs</h1>

    <!-- Search & Filters -->
    <div class="flex flex-col lg:flex-row gap-8">
      <!-- Sidebar Filters -->
      <aside class="w-full lg:w-64 shrink-0">
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-5 sticky top-20">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input
              v-model="filters.search"
              type="text"
              placeholder="Job title, skill..."
              class="w-full rounded-lg border-gray-300 text-sm"
              @input="debouncedFetch"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Location Type</label>
            <select v-model="filters.location_type" class="w-full rounded-lg border-gray-300 text-sm" @change="fetchJobs">
              <option value="">All</option>
              <option value="remote">Remote</option>
              <option value="hybrid">Hybrid</option>
              <option value="on_site">On-site</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Experience</label>
            <select v-model="filters.experience_level" class="w-full rounded-lg border-gray-300 text-sm" @change="fetchJobs">
              <option value="">All</option>
              <option value="junior">Junior</option>
              <option value="mid">Mid</option>
              <option value="senior">Senior</option>
              <option value="lead">Lead</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contract</label>
            <select v-model="filters.contract_type" class="w-full rounded-lg border-gray-300 text-sm" @change="fetchJobs">
              <option value="">All</option>
              <option value="full_time">Full Time</option>
              <option value="part_time">Part Time</option>
              <option value="contract">Contract</option>
              <option value="freelance">Freelance</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Vue Version</label>
            <select v-model="filters.vue_version" class="w-full rounded-lg border-gray-300 text-sm" @change="fetchJobs">
              <option value="">All</option>
              <option value="2">Vue 2</option>
              <option value="3">Vue 3</option>
            </select>
          </div>

          <div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
              <input v-model="filters.requires_typescript" type="checkbox" class="rounded border-gray-300 text-vue" @change="fetchJobs">
              TypeScript Required
            </label>
          </div>

          <button
            class="w-full text-sm text-gray-500 hover:text-vue transition"
            @click="resetFilters"
          >
            Clear Filters
          </button>
        </div>
      </aside>

      <!-- Job List -->
      <div class="flex-1">
        <div v-if="pending" class="space-y-4">
          <div v-for="i in 6" :key="i" class="bg-white rounded-xl p-6 border border-gray-200 animate-pulse">
            <div class="h-4 bg-gray-200 rounded w-3/4 mb-3" />
            <div class="h-3 bg-gray-200 rounded w-1/2" />
          </div>
        </div>

        <div v-else-if="jobs.length" class="space-y-4">
          <JobCard v-for="job in jobs" :key="job.id" :job="job" />
        </div>

        <div v-else class="text-center py-16 text-gray-500">
          No jobs match your filters. Try adjusting your search.
        </div>

        <!-- Pagination -->
        <div v-if="meta && meta.last_page > 1" class="flex justify-center gap-2 mt-8">
          <button
            v-for="page in meta.last_page"
            :key="page"
            class="px-3 py-1 rounded-lg text-sm transition"
            :class="page === meta.current_page ? 'bg-vue text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-vue'"
            @click="goToPage(page)"
          >
            {{ page }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { apiFetch } = useApi()

useSeo({
  title: 'Vue.js Jobs — Browse Vue, Nuxt & Frontend Developer Openings | VueJobs',
  description: 'Search and filter Vue.js, Nuxt.js and frontend developer jobs. Find remote, hybrid and on-site positions filtered by experience, contract type, Vue version and more.',
  url: '/jobs',
})

const filters = reactive({
  search: '',
  location_type: '',
  experience_level: '',
  contract_type: '',
  vue_version: '',
  requires_typescript: false,
  page: 1,
})

const jobs = ref<any[]>([])
const meta = ref<any>(null)
const pending = ref(true)

let debounceTimer: ReturnType<typeof setTimeout>
function debouncedFetch() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => fetchJobs(), 300)
}

async function fetchJobs() {
  pending.value = true
  const params = new URLSearchParams()

  if (filters.search) params.set('search', filters.search)
  if (filters.location_type) params.set('location_type', filters.location_type)
  if (filters.experience_level) params.set('experience_level', filters.experience_level)
  if (filters.contract_type) params.set('contract_type', filters.contract_type)
  if (filters.vue_version) params.set('vue_version', filters.vue_version)
  if (filters.requires_typescript) params.set('requires_typescript', '1')
  params.set('page', String(filters.page))

  try {
    const res = await apiFetch<{ data: any[]; meta: any }>(`/jobs?${params}`)
    jobs.value = res.data
    meta.value = res.meta
  } catch {
    jobs.value = []
  } finally {
    pending.value = false
  }
}

function goToPage(page: number) {
  filters.page = page
  fetchJobs()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function resetFilters() {
  Object.assign(filters, {
    search: '',
    location_type: '',
    experience_level: '',
    contract_type: '',
    vue_version: '',
    requires_typescript: false,
    page: 1,
  })
  fetchJobs()
}

await fetchJobs()
</script>
