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
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <NuxtLink
            to="/jobs"
            class="bg-vue text-white px-8 py-3 rounded-lg font-semibold hover:bg-vue/90 transition"
          >
            Find Jobs
          </NuxtLink>
          <NuxtLink
            to="/dashboard/jobs/create"
            class="bg-white text-vue-dark px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition"
          >
            Post a Job
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Featured Jobs -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Latest Jobs</h2>
        <NuxtLink to="/jobs" class="text-vue font-medium hover:underline text-sm">
          View all &rarr;
        </NuxtLink>
      </div>

      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="i in 6" :key="i" class="bg-white rounded-xl p-6 border border-gray-200 animate-pulse">
          <div class="h-4 bg-gray-200 rounded w-3/4 mb-3" />
          <div class="h-3 bg-gray-200 rounded w-1/2 mb-4" />
          <div class="h-3 bg-gray-200 rounded w-full" />
        </div>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <JobCard v-for="job in jobs" :key="job.id" :job="job" />
      </div>

      <div v-if="!pending && (!jobs || jobs.length === 0)" class="text-center py-12 text-gray-500">
        No jobs posted yet. Be the first to
        <NuxtLink to="/dashboard/jobs/create" class="text-vue underline">post a job</NuxtLink>.
      </div>
    </section>

    <!-- How it works -->
    <section class="bg-white py-16">
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

    <!-- SEO Content — crawlable text for search engines -->
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

          <h3 class="text-lg font-semibold text-gray-800 mt-8">For Vue.js Developers</h3>
          <p>
            Find Vue.js developer jobs, Nuxt.js developer positions, and full-stack roles using Laravel + Vue
            or Node + Vue. Create your developer profile, upload your resume, set up job alerts, and apply
            with one click. From junior Vue developer roles to senior frontend architect positions, VueJobs
            has opportunities at every level.
          </p>

          <h3 class="text-lg font-semibold text-gray-800 mt-8">For Employers & Recruiters</h3>
          <p>
            Post Vue.js job listings and reach a targeted audience of Vue developers. Add your company profile,
            manage applications, and find the right candidates for your team. Whether you're a startup using
            Nuxt.js or an enterprise migrating to Vue 3, VueJobs helps you hire specialized frontend talent.
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

useSeo({
  title: 'VueJobs — Vue.js & Nuxt.js Job Board | Find Vue Developer Jobs',
  description: 'VueJobs is India\'s dedicated job board for Vue.js, Nuxt.js and the Vue ecosystem. Browse remote and on-site Vue developer jobs, post openings, and hire top Vue talent.',
  url: '/',
})

const { data, pending } = await useAsyncData('home-jobs', () =>
  apiFetch<{ data: any[] }>('/jobs?per_page=6')
)

const jobs = computed(() => data.value?.data ?? [])
</script>
