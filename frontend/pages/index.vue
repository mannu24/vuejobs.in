<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-vue-dark to-gray-900 text-white py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
          Find <span class="text-vue">Vue.js</span> Jobs &amp; Vue Developers
        </h1>
        <p class="text-gray-300 text-lg mb-8 max-w-2xl mx-auto">
          The dedicated job board for Vue.js, Nuxt.js and the entire Vue ecosystem.
        </p>

        <!-- Quick search -->
        <div class="max-w-xl mx-auto mb-8">
          <div class="flex rounded-lg overflow-hidden shadow-lg">
            <input
              v-model="heroSearch"
              type="text"
              placeholder="Search jobs... e.g. Senior Vue Developer"
              class="flex-1 px-4 py-3 text-gray-900 text-sm border-0 focus:ring-0"
              @keyup.enter="goSearch"
            >
            <button class="bg-vue px-6 py-3 text-white font-medium text-sm hover:bg-vue/90 transition" @click="goSearch">
              Search
            </button>
          </div>
        </div>

        <!-- Quick filter tags -->
        <div class="flex flex-wrap justify-center gap-2">
          <NuxtLink
            v-for="tag in quickTags" :key="tag.label"
            :to="tag.to"
            class="px-3 py-1.5 rounded-full bg-white/10 text-white/80 text-xs hover:bg-white/20 transition"
          >
            {{ tag.label }}
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Stats -->
    <section class="bg-white border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
          <div>
            <div class="text-3xl font-bold text-vue">{{ stats.jobs }}+</div>
            <div class="text-sm text-gray-500 mt-1">Vue.js Jobs</div>
          </div>
          <div>
            <div class="text-3xl font-bold text-vue">{{ stats.companies }}+</div>
            <div class="text-sm text-gray-500 mt-1">Companies Hiring</div>
          </div>
          <div>
            <div class="text-3xl font-bold text-vue">{{ stats.developers }}+</div>
            <div class="text-sm text-gray-500 mt-1">Vue Developers</div>
          </div>
          <div>
            <div class="text-3xl font-bold text-vue">100%</div>
            <div class="text-sm text-gray-500 mt-1">Vue Ecosystem Focus</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest Jobs -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Latest Jobs</h2>
        <NuxtLink to="/jobs" class="text-vue font-medium hover:underline text-sm">
          View all &rarr;
        </NuxtLink>
      </div>

      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <SkeletonJobCard v-for="i in 6" :key="i" />
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <JobCard v-for="job in jobs" :key="job.id" :job="job" />
      </div>

      <div v-if="!pending && (!jobs || jobs.length === 0)" class="text-center py-12 text-gray-500">
        No jobs posted yet. Be the first to
        <NuxtLink to="/dashboard/jobs/create" class="text-vue underline">post a job</NuxtLink>.
      </div>
    </section>

    <!-- Why VueJobs -->
    <section class="bg-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-4">Why VueJobs?</h2>
        <p class="text-gray-500 text-center mb-12 max-w-2xl mx-auto">Built by Vue developers, for the Vue community. Every feature is designed around the Vue ecosystem.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-gray-50 rounded-xl p-6 text-center">
            <div class="w-12 h-12 bg-vue/10 rounded-xl flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-vue" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">Vue-Only Focus</h3>
            <p class="text-gray-500 text-sm">No noise. Every job requires Vue.js, Nuxt.js or related tech.</p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6 text-center">
            <div class="w-12 h-12 bg-vue/10 rounded-xl flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-vue" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">Smart Filters</h3>
            <p class="text-gray-500 text-sm">Filter by Vue version, TypeScript, experience level, salary and more.</p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6 text-center">
            <div class="w-12 h-12 bg-vue/10 rounded-xl flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-vue" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">Job Alerts</h3>
            <p class="text-gray-500 text-sm">Get notified when new jobs match your skills and preferences.</p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6 text-center">
            <div class="w-12 h-12 bg-vue/10 rounded-xl flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-vue" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">Remote Friendly</h3>
            <p class="text-gray-500 text-sm">Find remote, hybrid and on-site Vue roles from companies worldwide.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- How it works (guests only) -->
    <section v-if="!auth.isLoggedIn" class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-12">How It Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="w-12 h-12 bg-vue/10 text-vue rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
            <h3 class="font-semibold text-gray-900 mb-2">Create Your Profile</h3>
            <p class="text-gray-500 text-sm">Sign up as a developer or employer and set up your profile.</p>
          </div>
          <div class="text-center">
            <div class="w-12 h-12 bg-vue/10 text-vue rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
            <h3 class="font-semibold text-gray-900 mb-2">Browse or Post Jobs</h3>
            <p class="text-gray-500 text-sm">Search Vue.js jobs with filters or post your open positions.</p>
          </div>
          <div class="text-center">
            <div class="w-12 h-12 bg-vue/10 text-vue rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
            <h3 class="font-semibold text-gray-900 mb-2">Apply or Hire</h3>
            <p class="text-gray-500 text-sm">Apply directly or through external links. Manage applications easily.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest Blog Posts -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">From the Blog</h2>
        <NuxtLink to="/blog" class="text-vue font-medium hover:underline text-sm">
          All posts &rarr;
        </NuxtLink>
      </div>

      <div v-if="blogs.length" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <NuxtLink
          v-for="blog in blogs" :key="blog.id"
          :to="`/blog/${blog.slug}`"
          class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-vue/50 hover:shadow-sm transition flex flex-col"
        >
          <img v-if="blog.hero_image" :src="blog.hero_image" :alt="blog.title" class="w-full h-40 object-cover">
          <div class="p-5 flex flex-col flex-1">
            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ blog.title }}</h3>
            <p v-if="blog.meta_description" class="text-gray-500 text-sm line-clamp-2 flex-1">{{ blog.meta_description }}</p>
            <div class="text-xs text-gray-400 mt-3">{{ formatDate(blog.published_at) }}</div>
          </div>
        </NuxtLink>
      </div>
    </section>

    <!-- For Developers / For Employers (guests only) -->
    <section v-if="!auth.isLoggedIn" class="bg-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="bg-gray-50 rounded-2xl p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">For Vue.js Developers</h3>
            <ul class="space-y-3 text-gray-600 text-sm">
              <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-vue shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Browse jobs filtered by Vue version, Nuxt, TypeScript
              </li>
              <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-vue shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Apply directly or via external links
              </li>
              <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-vue shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Save jobs, set alerts, track applications
              </li>
              <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-vue shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Junior to Lead level positions available
              </li>
            </ul>
            <NuxtLink to="/register" class="inline-block mt-6 bg-vue text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition">
              Create Developer Profile
            </NuxtLink>
          </div>
          <div class="bg-gray-50 rounded-2xl p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">For Employers &amp; Recruiters</h3>
            <ul class="space-y-3 text-gray-600 text-sm">
              <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-vue shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Post jobs to a targeted Vue.js audience
              </li>
              <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-vue shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Manage company profiles and job listings
              </li>
              <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-vue shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Review applications and shortlist candidates
              </li>
              <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-vue shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Feature jobs for extra visibility
              </li>
            </ul>
            <NuxtLink to="/register" class="inline-block mt-6 bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">
              Start Hiring
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Banner (guests only) -->
    <section v-if="!auth.isLoggedIn" class="bg-vue py-16">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">Ready to find your next Vue.js opportunity?</h2>
        <p class="text-white/80 mb-8">Join the community of Vue developers and employers on VueJobs.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <NuxtLink to="/jobs" class="bg-white text-vue px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
            Browse Jobs
          </NuxtLink>
          <NuxtLink to="/register" class="bg-white/20 text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/30 transition">
            Sign Up Free
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- SEO Content -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">The Vue.js Job Board for Developers & Employers</h2>
        <div class="prose prose-sm max-w-none text-gray-600 space-y-4">
          <p>
            VueJobs is the dedicated job platform built exclusively for the Vue.js ecosystem. Whether you're a
            Vue.js developer looking for your next role or an employer searching for specialized frontend talent,
            VueJobs connects you with the right opportunities.
          </p>
          <p>
            Our platform focuses on roles that require Vue.js, Nuxt.js, Pinia, Vuex, TypeScript, and related
            technologies. Filter jobs by experience level, location type (remote, hybrid, on-site), contract type,
            Vue version, and more. Every listing is tailored to the Vue ecosystem.
          </p>
          <h3 class="text-lg font-semibold text-gray-800 mt-8">Popular Vue.js Job Categories</h3>
          <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 list-none pl-0">
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-vue rounded-full" /> Vue.js Developer Jobs</li>
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-vue rounded-full" /> Nuxt.js Developer Jobs</li>
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-vue rounded-full" /> Remote Vue.js Jobs</li>
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-vue rounded-full" /> Senior Frontend Engineer (Vue)</li>
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-vue rounded-full" /> Full Stack Developer (Laravel + Vue)</li>
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-vue rounded-full" /> Vue.js + TypeScript Jobs</li>
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-vue rounded-full" /> Contract & Freelance Vue Work</li>
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-vue rounded-full" /> Vue.js Internships</li>
          </ul>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const { apiFetch } = useApi()
const auth = useAuthStore()

useSeo({
  title: 'VueJobs — Vue.js & Nuxt.js Job Board | Find Vue Developer Jobs',
  description: 'VueJobs is the dedicated job board for Vue.js, Nuxt.js and the Vue ecosystem. Browse remote and on-site Vue developer jobs, post openings, and hire top Vue talent.',
  url: '/',
})

const heroSearch = ref('')

const quickTags = [
  { label: 'Remote', to: '/jobs?location_type=remote' },
  { label: 'Vue 3', to: '/jobs?vue_version=3' },
  { label: 'Nuxt', to: '/jobs?nuxt_version=3' },
  { label: 'TypeScript', to: '/jobs?requires_typescript=1' },
  { label: 'Senior', to: '/jobs?experience_level=senior' },
  { label: 'Full Time', to: '/jobs?contract_type=full_time' },
  { label: 'Freelance', to: '/jobs?contract_type=freelance' },
]

function goSearch() {
  if (heroSearch.value.trim()) {
    navigateTo(`/jobs?search=${encodeURIComponent(heroSearch.value.trim())}`)
  } else {
    navigateTo('/jobs')
  }
}

// Jobs
const { data, pending } = await useAsyncData('home-jobs', () =>
  apiFetch<{ data: any[] }>('/jobs?per_page=6')
)
const jobs = computed(() => data.value?.data ?? [])

// Blogs
const blogs = ref<any[]>([])
onMounted(async () => {
  try {
    const res = await apiFetch<{ data: any[] }>('/blogs?per_page=3')
    blogs.value = res.data ?? []
  } catch {}
})

// Stats (placeholder numbers, replace with real API later)
const stats = reactive({ jobs: 50, companies: 20, developers: 200 })

function formatDate(date: string) {
  if (!date) return ''
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}
</script>
