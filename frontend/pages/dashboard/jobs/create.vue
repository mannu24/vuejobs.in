<template>
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Post a New Job</h1>

    <div v-if="!companies.length && !loadingCompanies" class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
      <p class="text-yellow-800 text-sm">You need to create a company first before posting a job.</p>
      <NuxtLink to="/dashboard/companies/create" class="text-vue font-medium text-sm hover:underline mt-2 inline-block">
        Create Company &rarr;
      </NuxtLink>
    </div>

    <form v-else class="space-y-6" @submit.prevent="handleSubmit">
      <!-- Company -->
      <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Basic Info</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
            <select v-model="form.company_id" required class="w-full rounded-lg border-gray-300 text-sm">
              <option value="">Select company</option>
              <option v-for="c in companies" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
            <input v-model="form.title" type="text" required class="w-full rounded-lg border-gray-300 text-sm" placeholder="Senior Vue.js Developer">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Department (optional)</label>
            <input v-model="form.department" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Engineering">
          </div>
        </div>
      </div>

      <!-- Location & Contract -->
      <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Location & Contract</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Location Type</label>
            <select v-model="form.location_type" required class="w-full rounded-lg border-gray-300 text-sm">
              <option value="remote">Remote</option>
              <option value="hybrid">Hybrid</option>
              <option value="on_site">On-site</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contract Type</label>
            <select v-model="form.contract_type" required class="w-full rounded-lg border-gray-300 text-sm">
              <option value="full_time">Full Time</option>
              <option value="part_time">Part Time</option>
              <option value="contract">Contract</option>
              <option value="freelance">Freelance</option>
              <option value="internship">Internship</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
            <input v-model="form.location" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="City">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
            <input v-model="form.country" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="India">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Experience Level</label>
            <select v-model="form.experience_level" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="">Any</option>
              <option value="junior">Junior</option>
              <option value="mid">Mid</option>
              <option value="senior">Senior</option>
              <option value="lead">Lead</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Tech -->
      <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Tech Requirements</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Vue Version</label>
            <select v-model="form.vue_version" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="">Any</option>
              <option value="2">Vue 2</option>
              <option value="3">Vue 3</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nuxt Version</label>
            <select v-model="form.nuxt_version" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="">Any</option>
              <option value="2">Nuxt 2</option>
              <option value="3">Nuxt 3</option>
            </select>
          </div>
          <div class="md:col-span-2">
            <label class="flex items-center gap-2 text-sm text-gray-700">
              <input v-model="form.requires_typescript" type="checkbox" class="rounded border-gray-300 text-vue">
              TypeScript Required
            </label>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Skills (comma separated)</label>
            <input v-model="skillsInput" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Vue.js, Nuxt, Pinia, TailwindCSS">
          </div>
        </div>
      </div>

      <!-- Salary -->
      <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Compensation</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Min Salary</label>
            <input v-model.number="form.salary_min" type="number" class="w-full rounded-lg border-gray-300 text-sm">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Max Salary</label>
            <input v-model.number="form.salary_max" type="number" class="w-full rounded-lg border-gray-300 text-sm">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
            <select v-model="form.currency" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="INR">INR</option>
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
              <option value="GBP">GBP</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Interval</label>
            <select v-model="form.salary_interval" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="yearly">Yearly</option>
              <option value="monthly">Monthly</option>
              <option value="hourly">Hourly</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Description -->
      <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Description</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Job Description</label>
            <textarea v-model="form.description" rows="8" required class="w-full rounded-lg border-gray-300 text-sm" placeholder="Describe the role, responsibilities, and requirements..." />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">External Apply URL (optional)</label>
            <input v-model="form.apply_url" type="url" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://company.com/careers/apply">
            <p class="text-xs text-gray-400 mt-1">Leave empty to accept applications on VueJobs</p>
          </div>
        </div>
      </div>

      <div v-if="error" class="text-red-500 text-sm">{{ error }}</div>

      <div class="flex gap-3">
        <button
          type="submit"
          class="bg-vue text-white px-6 py-2.5 rounded-lg font-medium hover:bg-vue/90 transition"
          :disabled="submitting"
        >
          {{ submitting ? 'Posting...' : 'Post Job' }}
        </button>
        <button
          type="button"
          class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition"
          :disabled="submitting"
          @click="saveDraft"
        >
          Save as Draft
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()

const companies = ref<any[]>([])
const loadingCompanies = ref(true)
const submitting = ref(false)
const error = ref('')
const skillsInput = ref('')

const form = reactive({
  company_id: '',
  title: '',
  department: '',
  location_type: 'remote',
  contract_type: 'full_time',
  location: '',
  country: '',
  experience_level: '',
  vue_version: '',
  nuxt_version: '',
  requires_typescript: false,
  salary_min: null as number | null,
  salary_max: null as number | null,
  currency: 'INR',
  salary_interval: 'yearly',
  description: '',
  apply_url: '',
  status: 'draft',
})

onMounted(async () => {
  try {
    const res = await apiFetch<{ data: any[] }>('/companies')
    companies.value = res.data
  } catch {} finally {
    loadingCompanies.value = false
  }
})

function buildPayload(status: string) {
  return {
    ...form,
    status,
    skills: skillsInput.value ? skillsInput.value.split(',').map((s: string) => s.trim()).filter(Boolean) : [],
    apply_url: form.apply_url || undefined,
  }
}

async function handleSubmit() {
  submitting.value = true
  error.value = ''
  try {
    const res = await apiFetch<{ job: any }>('/jobs', {
      method: 'POST',
      body: buildPayload('published'),
    })
    navigateTo('/dashboard/jobs')
  } catch (e: any) {
    error.value = e?.data?.message || 'Failed to create job'
  } finally {
    submitting.value = false
  }
}

async function saveDraft() {
  submitting.value = true
  error.value = ''
  try {
    await apiFetch('/jobs', {
      method: 'POST',
      body: buildPayload('draft'),
    })
    navigateTo('/dashboard/jobs')
  } catch (e: any) {
    error.value = e?.data?.message || 'Failed to save draft'
  } finally {
    submitting.value = false
  }
}
</script>
