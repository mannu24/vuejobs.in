<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Blog</h1>
    <p class="text-gray-500 mb-8">Tips, guides and insights for Vue.js developers and employers.</p>

    <!-- Tag filter -->
    <div v-if="allTags.length" class="flex flex-wrap gap-2 mb-8">
      <button
        class="px-3 py-1 rounded-full text-sm transition"
        :class="!activeTag ? 'bg-vue text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
        @click="filterByTag('')"
      >
        All
      </button>
      <button
        v-for="tag in allTags"
        :key="tag"
        class="px-3 py-1 rounded-full text-sm transition"
        :class="activeTag === tag ? 'bg-vue text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
        @click="filterByTag(tag)"
      >
        {{ tag }}
      </button>
    </div>

    <!-- Loading -->
    <div v-if="pending" class="space-y-6">
      <div v-for="i in 4" :key="i" class="bg-white rounded-xl border border-gray-200 p-6 animate-pulse">
        <div class="h-5 bg-gray-200 rounded w-3/4 mb-3" />
        <div class="h-3 bg-gray-200 rounded w-full mb-2" />
        <div class="h-3 bg-gray-200 rounded w-1/2" />
      </div>
    </div>

    <!-- Blog grid -->
    <div v-else-if="blogs.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <NuxtLink
        v-for="blog in blogs"
        :key="blog.id"
        :to="`/blog/${blog.slug}`"
        class="bg-white rounded-xl border border-gray-200 p-5 hover:border-vue/50 hover:shadow-sm transition flex flex-col"
      >
        <div v-if="blog.tags?.length" class="flex flex-wrap gap-1.5 mb-3">
          <span v-for="tag in blog.tags" :key="tag" class="px-2 py-0.5 rounded-full bg-vue/10 text-xs text-vue font-medium">{{ tag }}</span>
        </div>
        <h2 class="text-base font-semibold text-gray-900 mb-2 line-clamp-2">{{ blog.title }}</h2>
        <p v-if="blog.meta_description" class="text-gray-500 text-sm mb-4 line-clamp-3 flex-1">{{ blog.meta_description }}</p>
        <div class="flex items-center gap-2 text-xs text-gray-400 mt-auto pt-3 border-t border-gray-100">
          <span v-if="blog.author">{{ blog.author.name }}</span>
          <span v-if="blog.author && blog.published_at">&middot;</span>
          <span v-if="blog.published_at">{{ formatDate(blog.published_at) }}</span>
        </div>
      </NuxtLink>
    </div>

    <div v-else class="text-center py-16 text-gray-500">
      No blog posts yet. Check back soon.
    </div>

    <!-- Pagination -->
    <div v-if="meta && meta.last_page > 1" class="flex justify-center gap-2 mt-8">
      <button
        v-for="page in meta.last_page"
        :key="page"
        class="px-3 py-1 rounded-lg text-sm transition"
        :class="page === meta.current_page ? 'bg-vue text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-vue'"
        @click="goToPage(page)"
      >
        {{ page }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
const { apiFetch } = useApi()

useSeo({
  title: 'Blog — Vue.js Tips, Guides & Career Advice | VueJobs',
  description: 'Read the latest articles on Vue.js, Nuxt.js, frontend development tips, career advice and hiring guides on the VueJobs blog.',
  url: '/blog',
})

const blogs = ref<any[]>([])
const meta = ref<any>(null)
const pending = ref(true)
const activeTag = ref('')
const currentPage = ref(1)

const allTags = computed(() => {
  const tags = new Set<string>()
  blogs.value.forEach((b: any) => b.tags?.forEach((t: string) => tags.add(t)))
  return Array.from(tags).sort()
})

async function fetchBlogs() {
  pending.value = true
  const params = new URLSearchParams()
  params.set('page', String(currentPage.value))
  if (activeTag.value) params.set('tag', activeTag.value)

  try {
    const res = await apiFetch<{ data: any[]; meta: any }>(`/blogs?${params}`)
    blogs.value = res.data
    meta.value = res.meta
  } catch {
    blogs.value = []
  } finally {
    pending.value = false
  }
}

function filterByTag(tag: string) {
  activeTag.value = tag
  currentPage.value = 1
  fetchBlogs()
}

function goToPage(page: number) {
  currentPage.value = page
  fetchBlogs()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function formatDate(date: string) {
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

await fetchBlogs()
</script>
