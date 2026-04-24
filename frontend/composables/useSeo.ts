/**
 * Sets page-level SEO meta tags and optional JSON-LD structured data.
 */
export const useSeo = (opts: {
  title: string
  description: string
  url?: string
  image?: string
  type?: string
  jsonLd?: Record<string, any>
  article?: {
    publishedTime?: string
    modifiedTime?: string
    author?: string
    tags?: string[]
  }
}) => {
  const config = useRuntimeConfig()
  const fullUrl = opts.url ? `${config.public.siteUrl}${opts.url}` : config.public.siteUrl
  const image = opts.image || `${config.public.siteUrl}/og-image.png`

  const meta: any[] = [
    { name: 'description', content: opts.description },
    { property: 'og:title', content: opts.title },
    { property: 'og:description', content: opts.description },
    { property: 'og:url', content: fullUrl },
    { property: 'og:image', content: image },
    { property: 'og:image:width', content: '1200' },
    { property: 'og:image:height', content: '600' },
    { property: 'og:type', content: opts.type || 'website' },
    { name: 'twitter:title', content: opts.title },
    { name: 'twitter:description', content: opts.description },
    { name: 'twitter:image', content: image },
  ]

  // Article-specific OG tags
  if (opts.article) {
    if (opts.article.publishedTime) meta.push({ property: 'article:published_time', content: opts.article.publishedTime })
    if (opts.article.modifiedTime) meta.push({ property: 'article:modified_time', content: opts.article.modifiedTime })
    if (opts.article.author) meta.push({ property: 'article:author', content: opts.article.author })
    if (opts.article.tags) {
      opts.article.tags.forEach(tag => meta.push({ property: 'article:tag', content: tag }))
    }
  }

  useHead({
    title: opts.title,
    meta,
    link: [
      { rel: 'canonical', href: fullUrl },
    ],
    script: opts.jsonLd
      ? [{ type: 'application/ld+json', innerHTML: JSON.stringify(opts.jsonLd) }]
      : [],
  })
}

/**
 * Builds a Google-compatible JobPosting JSON-LD object.
 * https://developers.google.com/search/docs/appearance/structured-data/job-posting
 */
export const useJobJsonLd = (job: any) => {
  if (!job) return null

  const jsonLd: Record<string, any> = {
    '@context': 'https://schema.org',
    '@type': 'JobPosting',
    title: job.title,
    description: job.description,
    datePosted: job.published_at || job.created_at,
    employmentType: mapEmploymentType(job.contract_type),
    jobLocationType: job.location_type === 'remote' ? 'TELECOMMUTE' : undefined,
  }

  if (job.expires_at) {
    jsonLd.validThrough = job.expires_at
  }

  if (job.company) {
    jsonLd.hiringOrganization = {
      '@type': 'Organization',
      name: job.company.name,
      sameAs: job.company.website || undefined,
      logo: job.company.logo_url || undefined,
    }
  }

  if (job.location && job.country) {
    jsonLd.jobLocation = {
      '@type': 'Place',
      address: {
        '@type': 'PostalAddress',
        addressLocality: job.location,
        addressCountry: job.country,
      },
    }
  }

  if (job.salary_min || job.salary_max) {
    jsonLd.baseSalary = {
      '@type': 'MonetaryAmount',
      currency: job.currency || 'USD',
      value: {
        '@type': 'QuantitativeValue',
        minValue: job.salary_min || undefined,
        maxValue: job.salary_max || undefined,
        unitText: mapSalaryUnit(job.salary_interval),
      },
    }
  }

  return jsonLd
}

function mapEmploymentType(type: string): string {
  const map: Record<string, string> = {
    full_time: 'FULL_TIME',
    part_time: 'PART_TIME',
    contract: 'CONTRACTOR',
    freelance: 'CONTRACTOR',
    internship: 'INTERN',
  }
  return map[type] || 'OTHER'
}

function mapSalaryUnit(interval: string): string {
  const map: Record<string, string> = {
    yearly: 'YEAR',
    monthly: 'MONTH',
    weekly: 'WEEK',
    daily: 'DAY',
    hourly: 'HOUR',
  }
  return map[interval] || 'YEAR'
}
