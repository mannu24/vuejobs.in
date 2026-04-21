<template>
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div v-if="pending" class="animate-pulse space-y-4">
      <div class="h-8 bg-gray-200 rounded w-2/3" />
      <div class="h-4 bg-gray-200 rounded w-1/3" />
      <div class="h-64 bg-gray-200 rounded" />
    </div>

    <template v-else-if="blog">
      <!-- Back link -->
      <NuxtLink to="/blog" class="text-sm text-vue hover:underline mb-6 inline-block">&larr; All posts</NuxtLink>

      <article class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <!-- Hero image -->
        <img
          v-if="blog.hero_image"
          :src="blog.hero_image"
          :alt="blog.title"
          class="w-full h-64 sm:h-80 object-cover"
        >

        <div class="p-6 sm:p-8">
          <!-- Tags -->
          <div v-if="blog.tags?.length" class="flex flex-wrap gap-1.5 mb-4">
            <span v-for="tag in blog.tags" :key="tag" class="px-2 py-0.5 rounded-full bg-vue/10 text-xs text-vue font-medium">{{ tag }}</span>
          </div>

          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">{{ blog.title }}</h1>

          <div class="flex items-center gap-3 text-sm text-gray-400 mb-8">
            <span v-if="blog.author">By {{ blog.author.name }}</span>
            <span v-if="blog.published_at">{{ formatDate(blog.published_at) }}</span>
          </div>

          <!-- Content -->
          <div class="prose prose-sm sm:prose max-w-none text-gray-700" v-html="blog.content" />
        </div>
      </article>

      <!-- Internal link to jobs -->
      <div class="mt-8 bg-vue/5 border border-vue/20 rounded-xl p-6 text-center">
        <p class="text-gray-700 mb-3">Looking for Vue.js opportunities?</p>
        <NuxtLink to="/jobs" class="inline-block bg-vue text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition">
          Browse Jobs
        </NuxtLink>
      </div>
    </template>

    <div v-else class="text-center py-16 text-gray-500">
      Blog post not found.
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const { apiFetch } = useApi()

const { data, pending } = await useAsyncData(`blog-${route.params.slug}`, () =>
  apiFetch<{ data: any }>(`/blogs/${route.params.slug}`)
)

const blog = computed(() => data.value?.data)

// SEO + Article JSON-LD
watch(blog, (b) => {
  if (!b) return
  useSeo({
    title: b.meta_title || `${b.title} — VueJobs Blog`,
    description: b.meta_description || b.title,
    url: `/blog/${b.slug}`,
    image: b.hero_image || undefined,
    type: 'article',
    jsonLd: {
      '@context': 'https://schema.org',
      '@type': 'BlogPosting',
      headline: b.title,
      description: b.meta_description || b.title,
      image: b.hero_image || undefined,
      datePublished: b.published_at,
      dateModified: b.updated_at,
      author: b.author ? { '@type': 'Person', name: b.author.name } : undefined,
      publisher: { '@type': 'Organization', name: 'VueJobs', url: 'https://vuejobs.in' },
    },
  })
}, { immediate: true })

function formatDate(date: string) {
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
}
</script>
