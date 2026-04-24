/**
 * Generates sitemap.xml by fetching jobs, blogs, and companies from the Laravel API.
 * Pre-rendered at build time into a static file.
 */
export default defineEventHandler(async (event) => {
  const apiBase = process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api'
  const siteUrl = process.env.NUXT_PUBLIC_SITE_URL || 'https://vuejobs.in'

  interface SitemapUrl {
    loc: string
    lastmod?: string
    changefreq: string
    priority: string
  }

  const urls: SitemapUrl[] = [
    { loc: `${siteUrl}/`, changefreq: 'daily', priority: '1.0' },
    { loc: `${siteUrl}/jobs`, changefreq: 'daily', priority: '0.9' },
    { loc: `${siteUrl}/blog`, changefreq: 'daily', priority: '0.8' },
    { loc: `${siteUrl}/about`, changefreq: 'monthly', priority: '0.5' },
  ]

  // Fetch jobs
  try {
    let page = 1
    let hasMore = true
    while (hasMore) {
      const res = await fetch(`${apiBase}/jobs?page=${page}&per_page=100`)
      if (!res.ok) break
      const json = await res.json()
      const jobs = json.data || []
      for (const job of jobs) {
        if (job.slug) {
          urls.push({
            loc: `${siteUrl}/jobs/${job.slug}`,
            lastmod: job.updated_at || job.created_at,
            changefreq: 'weekly',
            priority: '0.8',
          })
        }
      }
      hasMore = json.meta?.current_page < json.meta?.last_page
      page++
    }
  } catch (e) {
    console.warn('[Sitemap] Could not fetch jobs:', (e as Error).message)
  }

  // Fetch blogs
  try {
    const res = await fetch(`${apiBase}/blogs`)
    if (res.ok) {
      const json = await res.json()
      for (const blog of json.data || []) {
        if (blog.slug) {
          urls.push({
            loc: `${siteUrl}/blog/${blog.slug}`,
            lastmod: blog.updated_at || blog.published_at,
            changefreq: 'weekly',
            priority: '0.7',
          })
        }
      }
    }
  } catch (e) {
    console.warn('[Sitemap] Could not fetch blogs:', (e as Error).message)
  }

  // Build XML
  let xml = '<?xml version="1.0" encoding="UTF-8"?>\n'
  xml += '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n'

  for (const url of urls) {
    xml += '  <url>\n'
    xml += `    <loc>${escapeXml(url.loc)}</loc>\n`
    if (url.lastmod) {
      xml += `    <lastmod>${url.lastmod}</lastmod>\n`
    }
    xml += `    <changefreq>${url.changefreq}</changefreq>\n`
    xml += `    <priority>${url.priority}</priority>\n`
    xml += '  </url>\n'
  }

  xml += '</urlset>'

  setResponseHeader(event, 'content-type', 'application/xml')
  return xml
})

function escapeXml(str: string): string {
  return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
}
