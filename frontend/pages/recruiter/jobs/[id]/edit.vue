<template>
  <div>
    <NuxtLink :to="`/recruiter/jobs/${route.params.id}`" class="text-xs text-gray-400 hover:text-vue transition">&larr; Back to Job</NuxtLink>
    <h1 class="text-xl font-bold text-gray-900 mt-1 mb-6">Edit Job</h1>

    <div v-if="loading" class="space-y-4">
      <div v-for="i in 3" :key="i" class="h-20 bg-gray-200 rounded-xl animate-pulse" />
    </div>

    <form v-else class="space-y-5 max-w-3xl" @submit.prevent="handleSubmit">
      <!-- Basic Info -->
      <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
        <h2 class="font-semibold text-gray-900 text-sm">Basic Info</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">Job Title</label>
            <input v-model="form.title" type="text" required class="w-full rounded-lg border-gray-300 text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Location Type</label>
            <select v-model="form.location_type" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="remote">Remote</option>
              <option value="hybrid">Hybrid</option>
              <option value="on_site">On-site</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Contract Type</label>
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
            <input v-model="form.location" type="text" class="w-full rounded-lg border-gray-300 text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Country</label>
            <input v-model="form.country" type="text" class="w-full rounded-lg border-gray-300 text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Experience Level</label>
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
            <input v-model="form.department" type="text" class="w-full rounded-lg border-gray-300 text-sm">
          </div>
        </div>
      </div>

      <!-- Tech & Salary -->
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
          <input v-model="skillsInput" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Vue.js, Nuxt, Pinia">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Interval</label>
            <select v-model="form.salary_interval" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="yearly">Yearly</option><option value="monthly">Monthly</option><option value="hourly">Hourly</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Description -->
      <div class="bg-white rounded-xl border border-gray-200 p-5">
        <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
        <textarea v-model="form.description" rows="10" class="w-full rounded-lg border-gray-300 text-sm" />
      </div>

      <!-- Apply Method -->
      <div class="bg-white rounded-xl border border-gray-200 p-5">
        <label class="block text-xs font-medium text-gray-600 mb-1">Apply URL (leave empty for direct apply on VueJobs)</label>
        <input v-model="form.apply_url" type="url" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://...">
      </div>

      <div v-if="error" class="text-red-500 text-sm">{{ error }}</div>

      <div class="flex gap-3">
        <button type="submit" class="bg-vue text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition" :disabled="saving">
          {{ saving ? 'Saving...' : 'Save Changes' }}
        </button>
        <NuxtLink :to="`/recruiter/jobs/${route.params.id}`" class="border border-gray-300 text-gray-600 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
          Cancel
        </NuxtLink>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'recruiter', middleware: 'recruiter' })

const route = useRoute()
const { apiFetch } = useApi()
const loading = ref(true)
const saving = ref(false)
const error = ref('')
const skillsInput = ref('')

const form = reactive({
  title: '', department: '', location_type: 'remote', contract_type: 'full_time',
  location: '', country: '', experience_level: '',
  vue_version: '', nuxt_version: '', requires_typescript: false,
  salary_min: null as number | null, salary_max: null as number | null,
  currency: 'INR', salary_interval: 'yearly',
  description: '', apply_url: '', company_id: 0,
})

onMounted(async () => {
  try {
    const res = await apiFetch<{ data: any }>(`/jobs/mine/${route.params.id}`)
    const j = res.data
    Object.assign(form, {
      title: j.title, department: j.department ?? '', location_type: j.location_type,
      contract_type: j.contract_type, location: j.location ?? '', country: j.country ?? '',
      experience_level: j.experience_level ?? '',
      vue_version: j.vue_version ?? '', nuxt_version: j.nuxt_version ?? '',
      requires_typescript: j.requires_typescript ?? false,
      salary_min: j.salary_min, salary_max: j.salary_max,
      currency: j.currency ?? 'INR', salary_interval: j.salary_interval ?? 'yearly',
      description: j.description, apply_url: j.apply_url ?? '', company_id: j.company?.id,
    })
    skillsInput.value = (j.skills ?? []).join(', ')
  } catch {} finally { loading.value = false }
})

async function handleSubmit() {
  saving.value = true
  error.value = ''
  try {
    await apiFetch(`/jobs/${route.params.id}`, {
      method: 'PUT',
      body: {
        ...form,
        skills: skillsInput.value ? skillsInput.value.split(',').map((s: string) => s.trim()).filter(Boolean) : [],
        apply_url: form.apply_url || undefined,
      },
    })
    navigateTo(`/recruiter/jobs/${route.params.id}`)
  } catch (e: any) {
    error.value = e?.data?.message || 'Failed to update'
  } finally { saving.value = false }
}
</script>
