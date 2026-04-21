<template>
  <NuxtLink
    :to="`/jobs/${job.slug}`"
    class="block bg-white rounded-xl p-6 border border-gray-200 hover:border-vue/50 hover:shadow-md transition group"
    :class="{ 'ring-2 ring-vue/30 border-vue/40': job.featured_until }"
  >
    <div class="flex items-start gap-4">
      <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 overflow-hidden">
        <img
          v-if="job.company?.logo_url"
          :src="job.company.logo_url"
          :alt="job.company.name"
          class="w-full h-full object-cover"
        >
        <svg v-else class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
        </svg>
      </div>
      <div class="min-w-0">
        <div v-if="job.featured_until" class="text-xs text-vue font-medium mb-1">Featured</div>
        <h3 class="font-semibold text-gray-900 group-hover:text-vue transition truncate">
          {{ job.title }}
        </h3>
        <p class="text-sm text-gray-500 truncate">{{ job.company?.name }}</p>
      </div>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
      <span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-xs text-gray-600">
        {{ formatLocationType(job.location_type) }}
      </span>
      <span v-if="job.contract_type" class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-xs text-gray-600">
        {{ formatContractType(job.contract_type) }}
      </span>
      <span v-if="job.experience_level" class="inline-flex items-center px-2 py-1 rounded-md bg-blue-50 text-xs text-blue-600">
        {{ job.experience_level }}
      </span>
      <span v-if="job.vue_version" class="inline-flex items-center px-2 py-1 rounded-md bg-vue/10 text-xs text-vue">
        Vue {{ job.vue_version }}
      </span>
      <span v-if="job.requires_typescript" class="inline-flex items-center px-2 py-1 rounded-md bg-blue-50 text-xs text-blue-700">
        TypeScript
      </span>
      <span v-if="job.is_direct_apply" class="inline-flex items-center px-2 py-1 rounded-md bg-green-50 text-xs text-green-700">
        Direct Apply
      </span>
    </div>

    <div class="mt-4 flex items-center justify-between text-sm">
      <span v-if="job.salary_min || job.salary_max" class="text-gray-700 font-medium">
        {{ formatSalary(job) }}
      </span>
      <span v-else class="text-gray-400">Salary not disclosed</span>
      <span class="text-gray-400 text-xs">{{ timeAgo(job.created_at) }}</span>
    </div>
  </NuxtLink>
</template>

<script setup lang="ts">
defineProps<{ job: any }>()

function formatLocationType(type: string) {
  return type?.replace('_', '-').replace(/\b\w/g, c => c.toUpperCase()) ?? ''
}

function formatContractType(type: string) {
  return type?.replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase()) ?? ''
}

function formatSalary(job: any) {
  const parts = []
  if (job.salary_min) parts.push(new Intl.NumberFormat('en', { notation: 'compact' }).format(job.salary_min))
  if (job.salary_max) parts.push(new Intl.NumberFormat('en', { notation: 'compact' }).format(job.salary_max))
  const range = parts.join(' - ')
  return `${job.currency ?? 'USD'} ${range}/${job.salary_interval ?? 'yr'}`
}

function timeAgo(date: string) {
  if (!date) return ''
  const diff = Date.now() - new Date(date).getTime()
  const days = Math.floor(diff / 86400000)
  if (days === 0) return 'Today'
  if (days === 1) return '1d ago'
  if (days < 30) return `${days}d ago`
  return `${Math.floor(days / 30)}mo ago`
}
</script>
