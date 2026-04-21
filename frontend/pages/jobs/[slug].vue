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
          <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center shrink-0 overflow-hidden">
            <img v-if="job.company?.logo_url" :src="job.company.logo_url" :alt="job.company.name" class="w-full h-full object-cover">
            <svg v-else class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
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
          <!-- Direct Apply: recruiter-posted job accepting applications on VueJobs -->
          <button
            v-if="job.is_direct_apply && auth.isLoggedIn && auth.isDeveloper"
            class="bg-vue text-white px-6 py-2.5 rounded-lg font-medium hover:bg-vue/90 transition"
            @click="showApplyModal = true"
          >
            Apply Now
          </button>

          <!-- External Apply: scraped job or recruiter chose external link -->
          <a
            v-else-if="job.apply_url"
            :href="job.apply_url"
            target="_blank"
            rel="noopener"
            class="bg-vue text-white px-6 py-2.5 rounded-lg font-medium hover:bg-vue/90 transition"
          >
            Apply Externally &rarr;
          </a>

          <!-- Guest: prompt login for direct-apply jobs -->
          <NuxtLink
            v-else-if="job.is_direct_apply && !auth.isLoggedIn"
            to="/login"
            class="bg-vue text-white px-6 py-2.5 rounded-lg font-medium hover:bg-vue/90 transition"
          >
            Login to Apply
          </NuxtLink>

          <!-- Source badge -->
          <span v-if="job.source === 'scraped'" class="self-center text-xs text-gray-400">
            Sourced from external job board
          </span>
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

          <!-- Success state -->
          <div v-if="applySuccess" class="text-center py-4">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
              <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-sm text-gray-700 mb-4">Application submitted successfully.</p>
            <button class="text-sm text-vue hover:underline" @click="showApplyModal = false">Close</button>
          </div>

          <!-- Form -->
          <form v-else @submit.prevent="submitApplication">
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
              <button
                type="submit"
                class="inline-flex items-center gap-2 bg-vue text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="applying"
              >
                <span v-if="applying" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
                {{ applying ? 'Submitting...' : 'Submit Application' }}
              </button>
              <button type="button" class="text-gray-500 text-sm" :disabled="applying" @click="showApplyModal = false">Cancel</button>
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

// Dynamic SEO meta + JobPosting JSON-LD
watch(job, (j) => {
  if (!j) return
  const jsonLd = useJobJsonLd(j)
  useSeo({
    title: `${j.title} at ${j.company?.name || 'Company'} — VueJobs`,
    description: j.description?.substring(0, 160) || `Apply for ${j.title} at ${j.company?.name}. Vue.js job on VueJobs.`,
    url: `/jobs/${j.slug}`,
    image: j.company?.logo_url,
    type: 'article',
    jsonLd: jsonLd || undefined,
  })
}, { immediate: true })

const showApplyModal = ref(false)
const applying = ref(false)
const applyError = ref('')
const applySuccess = ref(false)
const applicationForm = reactive({ cover_letter: '', resume_url: '' })

async function submitApplication() {
  applying.value = true
  applyError.value = ''
  try {
    await apiFetch(`/jobs/${job.value.id}/apply`, {
      method: 'POST',
      body: applicationForm,
    })
    applySuccess.value = true
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
