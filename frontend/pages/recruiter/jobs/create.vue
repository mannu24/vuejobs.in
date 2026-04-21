<template>
  <div class="max-w-3xl">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Post a New Job</h1>

    <div v-if="!companies.length && !loadingCompanies" class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6">
      <p class="text-yellow-800 text-sm">You need to create a company first.</p>
      <NuxtLink to="/recruiter/companies/create" class="text-vue font-medium text-sm hover:underline mt-1 inline-block">Create Company &rarr;</NuxtLink>
    </div>

    <form v-else class="space-y-5" @submit.prevent="handleSubmit">
      <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
        <h2 class="font-semibold text-gray-900 text-sm">Basic Info</h2>
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Company</label>
          <select v-model="form.company_id" required class="w-full rounded-lg border-gray-300 text-sm">
            <option value="">Select company</option>
            <option v-for="c in companies" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Job Title</label>
          <input v-model="form.title" type="text" required class="w-full rounded-lg border-gray-300 text-sm" placeholder="Senior Vue.js Developer">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Location Type</label>
            <select v-model="form.location_type" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="remote">Remote</option>
              <option value="hybrid">Hybrid</option>
              <option value="on_site">On-site</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Contract</label>
            <select v-model="form.contract_type" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="full_time">Full Time</option>
              <option value="part_time">Part Time</option>
              <option value="contract">Contract</option>
              <option value="freelance">Freelance</option>
              <option value="internship">Internship</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Location</label>
            <input v-model="form.location" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="City">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Country</label>
            <input v-model="form.country" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="India">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Experience</label>
            <select v-model="form.experience_level" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="">Any</option>
              <option value="junior">Junior</option>
              <option value="mid">Mid</option>
              <option value="senior">Senior</option>
              <option value="lead">Lead</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Department</label>
            <input v-model="form.department" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Engineering">
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
        <h2 class="font-semibold text-gray-900 text-sm">Tech & Salary</h2>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Vue Version</label>
            <select v-model="form.vue_version" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="">Any</option><option value="2">Vue 2</option><option value="3">Vue 3</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Nuxt Version</label>
            <select v-model="form.nuxt_version" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="">Any</option><option value="2">Nuxt 2</option><option value="3">Nuxt 3</option>
            </select>
          </div>
        </div>
        <label class="flex items-center gap-2 text-xs text-gray-700">
          <input v-model="form.requires_typescript" type="checkbox" class="rounded border-gray-300 text-vue"> TypeScript Required
        </label>
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Skills (comma separated)</label>
          <input v-model="skillsInput" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Vue.js, Nuxt, Pinia, TailwindCSS">
        </div>
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Min Salary</label>
            <input v-model.number="form.salary_min" type="number" class="w-full rounded-lg border-gray-300 text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Max Salary</label>
            <input v-model.number="form.salary_max" type="number" class="w-full rounded-lg border-gray-300 text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Currency</label>
            <select v-model="form.currency" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="INR">INR</option><option value="USD">USD</option><option value="EUR">EUR</option><option value="GBP">GBP</option>
            </select>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
        <h2 class="font-semibold text-gray-900 text-sm">Description & Apply Method</h2>
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Job Description</label>
          <textarea v-model="form.description" rows="8" required class="w-full rounded-lg border-gray-300 text-sm" placeholder="Describe the role..." />
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-2">How should candidates apply?</label>
          <div class="flex gap-3">
            <button type="button" class="flex-1 py-2.5 px-4 rounded-lg text-xs font-medium border-2 transition text-left"
              :class="applyMethod === 'direct' ? 'border-vue bg-vue/5 text-vue' : 'border-gray-200 text-gray-500'"
              @click="applyMethod = 'direct'">
              <span class="block font-semibold">Direct Apply</span>
              <span class="block text-[10px] mt-0.5 opacity-75">Receive on VueJobs</span>
            </button>
            <button type="button" class="flex-1 py-2.5 px-4 rounded-lg text-xs font-medium border-2 transition text-left"
              :class="applyMethod === 'external' ? 'border-vue bg-vue/5 text-vue' : 'border-gray-200 text-gray-500'"
              @click="applyMethod = 'external'">
              <span class="block font-semibold">External Link</span>
              <span class="block text-[10px] mt-0.5 opacity-75">Your careers page</span>
            </button>
          </div>
        </div>
        <div v-if="applyMethod === 'external'">
          <label class="block text-xs font-medium text-gray-600 mb-1">Apply URL</label>
          <input v-model="form.apply_url" type="url" required class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://company.com/careers/apply">
        </div>
      </div>

      <div v-if="error" class="text-red-500 text-sm">{{ error }}</div>

      <div class="flex gap-3">
        <button type="submit" class="bg-vue text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition" :disabled="submitting">
          {{ submitting ? 'Posting...' : 'Publish Job' }}
        </button>
        <button type="button" class="border border-gray-300 text-gray-600 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition" :disabled="submitting" @click="saveDraft">
          Save Draft
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'recruiter', middleware: 'recruiter' })

const { apiFetch } = useApi()
const companies = ref<any[]>([])
const loadingCompanies = ref(true)
const submitting = ref(false)
const error = ref('')
const skillsInput = ref('')
const applyMethod = ref<'direct' | 'external'>('direct')

const form = reactive({
  company_id: '', title: '', department: '', location_type: 'remote', contract_type: 'full_time',
  location: '', country: '', experience_level: '', vue_version: '', nuxt_version: '',
  requires_typescript: false, salary_min: null as number | null, salary_max: null as number | null,
  currency: 'INR', salary_interval: 'yearly', description: '', apply_url: '',
})

onMounted(async () => {
  try { const res = await apiFetch<{ data: any[] }>('/companies'); companies.value = res.data } catch {} finally { loadingCompanies.value = false }
})

function buildPayload(status: string) {
  return {
    ...form, status, source: 'manual',
    skills: skillsInput.value ? skillsInput.value.split(',').map((s: string) => s.trim()).filter(Boolean) : [],
    apply_url: applyMethod.value === 'external' ? form.apply_url : undefined,
  }
}

async function handleSubmit() {
  submitting.value = true; error.value = ''
  try { await apiFetch('/jobs', { method: 'POST', body: buildPayload('published') }); navigateTo('/recruiter/jobs') }
  catch (e: any) { error.value = e?.data?.message || 'Failed to create job' }
  finally { submitting.value = false }
}

async function saveDraft() {
  submitting.value = true; error.value = ''
  try { await apiFetch('/jobs', { method: 'POST', body: buildPayload('draft') }); navigateTo('/recruiter/jobs') }
  catch (e: any) { error.value = e?.data?.message || 'Failed to save draft' }
  finally { submitting.value = false }
}
</script>
