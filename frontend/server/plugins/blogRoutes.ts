/**
 * At generate time, fetch all published blog slugs from the API
 * and add them to the pre-render list so each blog page gets
 * a static HTML file.
 */
export default defineNitroPlugin((nitro) => {
  if (!import.meta.prerender) return

  nitro.hooks.hook('prerender:routes', async (routes) => {
    const apiBase = process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api'

    try {
      const res = await fetch(`${apiBase}/blogs`)
      if (!res.ok) return

      const json = await res.json()
      const blogs = json.data || []

      for (const blog of blogs) {
        if (blog.slug) {
          routes.add(`/blog/${blog.slug}`)
        }
      }

      console.log(`[SSG] Added ${blogs.length} blog routes for pre-rendering`)
    } catch (e) {
      console.warn('[SSG] Could not fetch blog slugs:', (e as Error).message)
    }
  })
})
