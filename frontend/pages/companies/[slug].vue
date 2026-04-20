<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div v-if="pending" class="animate-pulse space-y-4">
      <div class="h-8 bg-gray-200 rounded w-1/3" />
      <div class="h-4 bg-gray-200 rounded w-1/2" />
    </div>

    <template v-else-if="company">
      <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-start gap-4">
          <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 font-bold text-lg shrink-0 overflow-hidden">
            <img v-if="company.logo_url" :src="company.logo_url" :alt="company.name" class="w-full h-full object-cover">
            <span v-else>{{ company.name?.charAt(0) }}</span>
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
</script>
