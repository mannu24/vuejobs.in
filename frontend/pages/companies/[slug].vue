<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div v-if="pending" class="animate-pulse space-y-4">
      <div class="h-8 bg-gray-200 rounded w-1/3" />
      <div class="h-4 bg-gray-200 rounded w-1/2" />
    </div>

    <template v-else-if="company">
      <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-start gap-4">
          <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center shrink-0 overflow-hidden">
            <img v-if="company.logo_url" :src="company.logo_url" :alt="company.name" class="w-full h-full object-cover">
            <svg v-else class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ company.name }}</h1>
            <p v-if="company.headline" class="text-gray-500 mt-1">{{ company.headline }}</p>
            <div class="flex gap-3 mt-2 text-sm text-gray-500">
              <span v-if="company.size">{{ company.size }} employees</span>
              <span v-if="company.industry">{{ company.industry }}</span>
              <a v-if="company.website" :href="company.website" target="_blank" class="text-vue hover:underline">Website</a>
            </div>
          </div>
        </div>
      </div>

      <div v-if="company.about" class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="font-semibold text-gray-900 mb-3">About</h2>
        <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ company.about }}</p>
      </div>

      <div v-if="company.tech_stack?.length" class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="font-semibold text-gray-900 mb-3">Tech Stack</h2>
        <div class="flex flex-wrap gap-2">
          <span v-for="tech in company.tech_stack" :key="tech" class="px-3 py-1 rounded-full bg-vue/10 text-sm text-vue">{{ tech }}</span>
        </div>
      </div>
    </template>

    <div v-else class="text-center py-16 text-gray-500">Company not found.</div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const { apiFetch } = useApi()

const { data, pending } = await useAsyncData(`company-${route.params.slug}`, () =>
  apiFetch<{ data: any }>(`/companies/${route.params.slug}`)
)

const company = computed(() => data.value?.data)

watch(company, (c) => {
  if (!c) return
  useSeo({
    title: `${c.name} — Company Profile & Vue.js Jobs | VueJobs`,
    description: c.about?.substring(0, 160) || `View ${c.name}'s profile and open Vue.js positions on VueJobs.`,
    url: `/companies/${c.slug}`,
    image: c.logo_url,
    jsonLd: {
      '@context': 'https://schema.org',
      '@type': 'Organization',
      name: c.name,
      url: c.website || undefined,
      logo: c.logo_url || undefined,
      description: c.about || undefined,
    },
  })
}, { immediate: true })
</script>
