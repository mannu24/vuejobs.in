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
  </div>
</template>

<script setup lang="ts">
const { apiFetch } = useApi()

const { data, pending } = await useAsyncData('home-jobs', () =>
  apiFetch<{ data: any[] }>('/jobs?per_page=6')
)

const jobs = computed(() => data.value?.data ?? [])
</script>
