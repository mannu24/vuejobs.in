<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Blog</h1>
      <p class="text-gray-500 text-sm mt-1">Tips, guides and insights for Vue.js developers and employers.</p>
    </div>

    <!-- 3-column layout -->
    <div class="flex gap-6">

      <!-- Left Sidebar: Tags -->
      <aside class="hidden lg:block w-56 shrink-0">
        <div class="sticky top-20 space-y-6">
          <!-- Sort tabs -->
          <div class="bg-white rounded-xl border border-gray-200 p-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Sort by</h3>
            <div class="space-y-1">
              <button
                class="w-full text-left px-3 py-2 rounded-lg text-sm transition"
                :class="sortBy === 'latest' ? 'bg-vue/10 text-vue font-medium' : 'text-gray-600 hover:bg-gray-50'"
                @click="sortBy = 'latest'; fetchBlogs()"
              >
                Latest
              </button>
              <button
                class="w-full text-left px-3 py-2 rounded-lg text-sm transition"
                :class="sortBy === 'oldest' ? 'bg-vue/10 text-vue font-medium' : 'text-gray-600 hover:bg-gray-50'"
                @click="sortBy = 'oldest'; fetchBlogs()"
              >
                Oldest
              </button>
            </div>
          </div>

          <!-- Popular Tags -->
          <div class="bg-white rounded-xl border border-gray-200 p-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Popular Tags</h3>
            <nav class="space-y-0.5">
              <button
                class="w-full text-left px-3 py-2 rounded-lg text-sm transition flex items-center gap-2"
                :class="!activeTag ? 'bg-vue/10 text-vue font-medium' : 'text-gray-600 hover:bg-gray-50'"
                @click="filterByTag('')"
              >
                <span class="w-2 h-2 rounded-full bg-vue" />
                All Posts
              </button>
              <button
                v-for="tag in popularTags"
                :key="tag"
                class="w-full text-left px-3 py-2 rounded-lg text-sm transition flex items-center gap-2"
                :class="activeTag === tag ? 'bg-vue/10 text-vue font-medium' : 'text-gray-600 hover:bg-gray-50'"
                @click="filterByTag(tag)"
              >
                <span class="text-gray-400">#</span>
                {{ tag }}
              </button>
            </nav>
          </div>
        </div>
      </aside>

      <!-- Main Feed -->
      <div class="flex-1 min-w-0">
        <!-- Mobile tag filter -->
        <div class="lg:hidden flex flex-wrap gap-2 mb-6">
          <button
            class="px-3 py-1.5 rounded-full text-sm transition"
            :class="!activeTag ? 'bg-vue text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
            @click="filterByTag('')"
          >All</button>
          <button
            v-for="tag in popularTags"
            :key="tag"
            class="px-3 py-1.5 rounded-full text-sm transition"
            :class="activeTag === tag ? 'bg-vue text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
            @click="filterByTag(tag)"
          >
            #{{ tag }}
          </button>
        </div>

        <!-- Loading skeletons -->
        <div v-if="pending" class="space-y-5">
          <div v-for="i in 4" :key="i" class="bg-white rounded-xl border border-gray-200 overflow-hidden animate-pulse">
            <div v-if="i === 1" class="h-52 bg-gray-200" />
            <div class="p-5 space-y-3">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-200" />
                <div>
                  <div class="h-3 bg-gray-200 rounded w-20 mb-1" />
                  <div class="h-2.5 bg-gray-200 rounded w-16" />
                </div>
              </div>
              <div class="h-5 bg-gray-200 rounded w-4/5" />
              <div class="flex gap-2">
                <div class="h-5 bg-gray-200 rounded-full w-14" />
                <div class="h-5 bg-gray-200 rounded-full w-16" />
              </div>
              <div class="flex items-center gap-4 pt-2">
                <div class="h-3 bg-gray-200 rounded w-16" />
                <div class="h-3 bg-gray-200 rounded w-12" />
              </div>
            </div>
          </div>
        </div>

        <!-- Blog feed -->
        <div v-else-if="blogs.length" class="space-y-5">
          <NuxtLink
            v-for="(blog, index) in blogs"
            :key="blog.id"
            :to="`/blog/${blog.slug}`"
            class="block bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-vue/40 hover:shadow-sm transition group"
          >
            <!-- Hero image only for first post -->
            <img
              v-if="blog.hero_image && index === 0"
              :src="blog.hero_image"
              :alt="blog.title"
              class="w-full h-52 object-cover"
            >

            <div class="p-5">
              <!-- Author row -->
              <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-full bg-vue/20 flex items-center justify-center text-vue text-xs font-semibold shrink-0">
                  {{ blog.author?.name?.charAt(0)?.toUpperCase() || 'V' }}
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900 leading-tight">{{ blog.author?.name || 'VueJobs' }}</p>
                  <p class="text-xs text-gray-400">{{ formatDate(blog.published_at) }}</p>
                </div>
              </div>

              <!-- Title -->
              <h2 class="text-lg font-bold text-gray-900 group-hover:text-vue transition leading-snug mb-2 line-clamp-2">
                {{ blog.title }}
              </h2>

              <!-- Tags -->
              <div v-if="blog.tags?.length" class="flex flex-wrap gap-1.5 mb-3">
                <span
                  v-for="tag in blog.tags"
                  :key="tag"
                  class="px-2 py-0.5 rounded-md text-xs text-gray-500 hover:bg-gray-100 transition"
                >
                  #{{ tag }}
                </span>
              </div>

              <!-- Description -->
              <p v-if="blog.meta_description" class="text-gray-500 text-sm line-clamp-2 mb-3">
                {{ blog.meta_description }}
              </p>

              <!-- Footer: reactions / read time -->
              <div class="flex items-center justify-between text-xs text-gray-400 pt-2">
                <div class="flex items-center gap-4">
                  <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                    {{ randomReactions(blog.id) }} reactions
                  </span>
                  <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" /></svg>
                    {{ randomComments(blog.id) }} comments
                  </span>
                </div>
                <span>{{ estimateReadTime(blog.content) }} min read</span>
              </div>
            </div>
          </NuxtLink>
        </div>

        <div v-else class="text-center py-16 text-gray-500 bg-white rounded-xl border border-gray-200">
          No blog posts yet. Check back soon.
        </div>

        <!-- Pagination -->
        <div v-if="meta && meta.last_page > 1" class="flex justify-center gap-2 mt-8">
          <button
            v-for="page in meta.last_page"
            :key="page"
            class="px-3 py-1.5 rounded-lg text-sm transition"
            :class="page === meta.current_page ? 'bg-vue text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-vue'"
            @click="goToPage(page)"
          >
            {{ page }}
          </button>
        </div>
      </div>

      <!-- Right Sidebar -->
      <aside class="hidden xl:block w-72 shrink-0">
        <div class="sticky top-20 space-y-6">
          <!-- Active tag info -->
          <div v-if="activeTag" class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-lg font-bold text-gray-900">#{{ activeTag }}</span>
            </div>
            <p class="text-sm text-gray-500 mb-3">Posts tagged with {{ activeTag }}</p>
            <button
              class="text-sm text-vue hover:underline"
              @click="filterByTag('')"
            >
              Clear filter
            </button>
          </div>

          <!-- Trending posts -->
          <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Trending Posts</h3>
            <div class="space-y-4">
              <NuxtLink
                v-for="blog in trendingBlogs"
                :key="blog.id"
                :to="`/blog/${blog.slug}`"
                class="block group"
              >
                <p class="text-sm font-medium text-gray-800 group-hover:text-vue transition line-clamp-2 leading-snug">
                  {{ blog.title }}
                </p>
                <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                  <span v-if="blog.author">{{ blog.author.name }}</span>
                  <span v-if="blog.tags?.length" class="text-gray-300">&middot;</span>
                  <span v-if="blog.tags?.length" class="text-vue/70">#{{ blog.tags[0] }}</span>
                </div>
              </NuxtLink>
            </div>
          </div>

          <!-- CTA -->
          <div class="bg-gradient-to-br from-vue-dark to-gray-900 rounded-xl p-5 text-white">
            <h3 class="font-bold mb-2">Write for VueJobs</h3>
            <p class="text-sm text-white/70 mb-4">Share your Vue.js knowledge with the community.</p>
            <NuxtLink
              to="/login"
              class="inline-block bg-white text-vue px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition"
            >
              Start Writing
            </NuxtLink>
          </div>

          <!-- Browse Jobs CTA -->
          <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-2">Looking for work?</h3>
            <p class="text-xs text-gray-500 mb-3">Browse Vue.js, Nuxt.js and frontend developer jobs.</p>
            <NuxtLink
              to="/jobs"
              class="inline-block bg-vue text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition"
            >
              Browse Jobs &rarr;
            </NuxtLink>
          </div>
        </div>
      </aside>

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
const sortBy = ref('latest')

// All unique tags from fetched blogs
const popularTags = computed(() => {
  const tags = new Set<string>()
  blogs.value.forEach((b: any) => b.tags?.forEach((t: string) => tags.add(t)))
  return Array.from(tags).sort().slice(0, 15)
})

// Top 5 blogs for trending sidebar
const trendingBlogs = computed(() => blogs.value.slice(0, 5))

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
  if (!date) return ''
  const d = new Date(date)
  const now = new Date()
  const diffDays = Math.floor((now.getTime() - d.getTime()) / 86400000)
  if (diffDays === 0) return 'Today'
  if (diffDays === 1) return 'Yesterday'
  if (diffDays < 7) return `${diffDays} days ago`
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}

function estimateReadTime(content: string) {
  if (!content) return 1
  const words = content.replace(/<[^>]*>/g, '').split(/\s+/).length
  return Math.max(1, Math.ceil(words / 200))
}

// Deterministic pseudo-random numbers based on blog id for reactions/comments
function randomReactions(id: number) {
  return ((id * 7 + 3) % 40) + 2
}

function randomComments(id: number) {
  return ((id * 3 + 1) % 15)
}

await fetchBlogs()
</script>
