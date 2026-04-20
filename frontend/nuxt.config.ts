export default defineNuxtConfig({
  compatibilityDate: '2025-04-20',
  devtools: { enabled: true },

  // SPA mode — generates static files deployable to any shared hosting
  ssr: false,

  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@nuxtjs/google-fonts',
  ],

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api',
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'https://vuejobs.in',
      appName: 'VueJobs',
    },
  },

  googleFonts: {
    families: {
      Inter: [400, 500, 600, 700, 800, 900],
    },
  },

  app: {
    head: {
      htmlAttrs: { lang: 'en' },
      title: 'VueJobs — Vue.js & Nuxt.js Job Board | Find Vue Developer Jobs',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'VueJobs is the dedicated job board for Vue.js, Nuxt.js and the Vue ecosystem. Browse remote and on-site Vue developer jobs, post openings, and hire top Vue talent.' },
        { name: 'keywords', content: 'vue.js jobs, nuxt.js jobs, vue developer jobs, frontend developer jobs, vue.js careers, nuxt developer, remote vue jobs, hire vue developer' },
        { name: 'author', content: 'VueJobs' },
        { name: 'robots', content: 'index, follow' },

        // Open Graph
        { property: 'og:type', content: 'website' },
        { property: 'og:site_name', content: 'VueJobs' },
        { property: 'og:title', content: 'VueJobs — Vue.js & Nuxt.js Job Board' },
        { property: 'og:description', content: 'Find Vue.js, Nuxt.js and frontend developer jobs. The dedicated job board for the Vue ecosystem.' },
        { property: 'og:url', content: 'https://vuejobs.in' },
        { property: 'og:image', content: 'https://vuejobs.in/og-image.png' },
        { property: 'og:locale', content: 'en_US' },

        // Twitter Card
        { name: 'twitter:card', content: 'summary_large_image' },
        { name: 'twitter:title', content: 'VueJobs — Vue.js & Nuxt.js Job Board' },
        { name: 'twitter:description', content: 'Find Vue.js, Nuxt.js and frontend developer jobs. The dedicated job board for the Vue ecosystem.' },
        { name: 'twitter:image', content: 'https://vuejobs.in/og-image.png' },
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/logo.ico' },
        { rel: 'canonical', href: 'https://vuejobs.in' },
      ],
      script: [
        // Organization JSON-LD
        {
          type: 'application/ld+json',
          innerHTML: JSON.stringify({
            '@context': 'https://schema.org',
            '@type': 'WebSite',
            name: 'VueJobs',
            url: 'https://vuejobs.in',
            description: 'The dedicated job board for Vue.js, Nuxt.js and the Vue ecosystem.',
            potentialAction: {
              '@type': 'SearchAction',
              target: 'https://vuejobs.in/jobs?search={search_term_string}',
              'query-input': 'required name=search_term_string',
            },
          }),
        },
      ],
    },
  },

  // Output to .output/public — upload this folder to Hostinger
  nitro: {
    preset: 'static',
  },
})
