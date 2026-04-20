<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div v-if="pending" class="animate-pulse space-y-4">
      <div class="h-8 bg-gray-200 rounded w-2/3" />
      <div class="h-4 bg-gray-200 rounded w-1/3" />
      <div class="h-40 bg-gray-200 rounded" />
    </div>

    <template v-else-if="job">
      <!-- Header -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-start gap-4">
          <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 font-bold text-lg shrink-0 overflow-hidden">
            <img v-if="job.company?.logo_url" :src="job.company.logo_url" :alt="job.company.name" class="w-full h-full object-cover">
            <span v-else>{{ job.company?.name?.charAt(0) }}</span>
          </div>
          <div>
            <div v-if="job.featured_until" class="text-xs text-vue font-medium mb-1">Featured</div>
            <h1 class="text-2xl font-bold text-gray-900">{{ job.title }}</h1>
            <p class="text-gray-500 mt-1">
              <NuxtLink v-if="job.company" :to="`/companies/${job.company.slug}`" class="hover:text-vue transition">
                {{ job.company.name }}
              </NuxtLink>
              <span v-if="job.location"> &middot; {{ job.location }}</span>
              <span v-if="job.country"> &middot; {{ job.country }}</span>
            </p>
          </div>
        </div>

        <!-- Tags -->
        <div class="mt-4 flex flex-wrap gap-2">
          <span class="px-3 py-1 rounded-full bg-gray-100 text-sm text-gray-600">{{ formatLocationType(job.location_type) }}</span>
          <span v-if="job.contract_type" class="px-3 py-1 rounded-full bg-gray-100 text-sm text-gray-600">{{ formatContractType(job.contract_type) }}</span>
          <span v-if="job.experience_level" class="px-3 py-1 rounded-full bg-blue-50 text-sm text-blue-600 capitalize">{{ job.experience_level }}</span>
          <span v-if="job.vue_version" class="px-3 py-1 rounded-full bg-vue/10 text-sm text-vue">Vue {{ job.vue_version }}</span>
          <span v-if="job.nuxt_version" class="px-3 py-1 rounded-full bg-vue/10 text-sm text-vue">Nuxt {{ job.nuxt_version }}</span>
          <span v-if="job.requires_typescript" class="px-3 py-1 rounded-full bg-blue-50 text-sm text-blue-700">TypeScript</span>
        </div>

        <!-- Salary -->
        <div v-if="job.salary_min || job.salary_max" class="mt-4 text-lg font-semibold text-gray-800">
          {{ formatSalary(job) }}
        </div>

        <!-- Actions -->
        <div class="mt-6 flex flex-wrap gap-3">
          <a
            v-if="job.apply_url"
            :href="job.apply_url"
            target="_blank"
            rel="noopener"
            class="bg-vue text-white px-6 py-2.5 rounded-lg font-medium hover:bg-vue/90 transition"
          >
            Apply Externally &rarr;
          </a>
          <button
            v-else-if="auth.isLoggedIn && auth.isDeveloper"
            class="bg-vue text-white px-6 py-2.5 rounded-lg font-medium hover:bg-vue/90 transition"
            @click="showApplyModal = true"
          >
            Apply Now
          </button>
          <NuxtLink
            v-else-if="!auth.isLoggedIn"
            to="/login"
            class="bg-vue text-white px-6 py-2.5 rounded-lg font-medium hover:bg-vue/90 transition"
          >
            Login to Apply
          </NuxtLink>
        </div>
      </div>

      <!-- Skills -->
      <div v-if="job.skills?.length" class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="font-semibold text-gray-900 mb-3">Skills</h2>
        <div class="flex flex-wrap gap-2">
          <span v-for="skill in job.skills" :key="skill" class="px-3 py-1 rounded-full bg-gray-100 text-sm text-gray-700">
            {{ skill }}
          </span>
        </div>
      </div>

      <!-- Description -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="font-semibold text-gray-900 mb-3">Job Description</h2>
        <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-wrap">{{ job.description }}</div>
      </div>

      <!-- Benefits -->
      <div v-if="job.benefits?.length" class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="font-semibold text-gray-900 mb-3">Benefits</h2>
        <ul class="list-disc list-inside space-y-1 text-gray-700 text-sm">
          <li v-for="benefit in job.benefits" :key="benefit">{{ benefit }}</li>
        </ul>
      </div>

      <!-- Apply Modal -->
      <div v-if="showApplyModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click.self="showApplyModal = false">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Apply to {{ job.title }}</h3>
          <form @submit.prevent="submitApplication">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Cover Letter (optional)</label>
              <textarea v-model="applicationForm.cover_letter" rows="4" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Why are you a great fit?" />
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Resume URL (optional)</label>
              <input v-model="applicationForm.resume_url" type="url" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://...">
            </div>
            <div v-if="applyError" class="text-red-500 text-sm mb-3">{{ applyError }}</div>
            <div class="flex gap-3">
              <button type="submit" class="bg-vue text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition" :disabled="applying">
                {{ applying ? 'Submitting...' : 'Submit Application' }}
              </button>
              <button type="button" class="text-gray-500 text-sm" @click="showApplyModal = false">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </template>

    <div v-else class="text-center py-16 text-gray-500">
      Job not found.
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const auth = useAuthStore()
const { apiFetch } = useApi()

const { data, pending } = await useAsyncData(`job-${route.params.slug}`, () =>
  apiFetch<{ data: any }>(`/jobs/${route.params.slug}`)
)

const job = computed(() => data.value?.data)

const showApplyModal = ref(false)
const applying = ref(false)
const applyError = ref('')
const applicationForm = reactive({ cover_letter: '', resume_url: '' })

async function submitApplication() {
  applying.value = true
  applyError.value = ''
  try {
    await apiFetch(`/jobs/${job.value.id}/apply`, {
      method: 'POST',
      body: applicationForm,
    })
    showApplyModal.value = false
    alert('Application submitted successfully!')
  } catch (e: any) {
    applyError.value = e?.data?.message || 'Failed to submit application'
  } finally {
    applying.value = false
  }
}

function formatLocationType(type: string) {
  return type?.replace('_', '-').replace(/\b\w/g, (c: string) => c.toUpperCase()) ?? ''
}
function formatContractType(type: string) {
  return type?.replace('_', ' ').replace(/\b\w/g, (c: string) => c.toUpperCase()) ?? ''
}
function formatSalary(job: any) {
  const parts = []
  if (job.salary_min) parts.push(new Intl.NumberFormat('en').format(job.salary_min))
  if (job.salary_max) parts.push(new Intl.NumberFormat('en').format(job.salary_max))
  return `${job.currency ?? 'USD'} ${parts.join(' - ')} / ${job.salary_interval ?? 'year'}`
}
</script>
