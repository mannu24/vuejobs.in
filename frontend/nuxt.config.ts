export default defineNuxtConfig({
  compatibilityDate: '2025-04-20',
  devtools: { enabled: true },

  // SPA mode for the whole app. Blog pages are selectively pre-rendered
  // via routeRules below — prerender works independently of this flag.
  ssr: false,

  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@nuxtjs/google-fonts',
  ],

  css: ['~/assets/css/transitions.css'],

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
    pageTransition: { name: 'page', mode: 'out-in' },
    head: {
      htmlAttrs: { lang: 'en' },
      title: 'VueJobs — Vue.js & Nuxt.js Job Board | Find Vue Developer Jobs',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'VueJobs is the dedicated job board for Vue.js, Nuxt.js and the Vue ecosystem. Browse remote and on-site Vue developer jobs, post openings, and hire top Vue talent.' },
        { name: 'keywords', content: 'vue.js jobs, nuxt.js jobs, vue developer jobs, frontend developer jobs, vue.js careers, nuxt developer, remote vue jobs, hire vue developer' },
        { name: 'author', content: 'VueJobs' },
        { name: 'robots', content: 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' },

        // Open Graph
        { property: 'og:type', content: 'website' },
        { property: 'og:site_name', content: 'VueJobs' },
        { property: 'og:title', content: 'VueJobs — Vue.js & Nuxt.js Job Board' },
        { property: 'og:description', content: 'Find Vue.js, Nuxt.js and frontend developer jobs. The dedicated job board for the Vue ecosystem.' },
        { property: 'og:url', content: 'https://vuejobs.in' },
        { property: 'og:image', content: 'https://vuejobs.in/og-image.png' },
        { property: 'og:image:width', content: '1200' },
        { property: 'og:image:height', content: '600' },
        { property: 'og:locale', content: 'en_US' },

        // Twitter Card
        { name: 'twitter:card', content: 'summary_large_image' },
        { name: 'twitter:title', content: 'VueJobs — Vue.js & Nuxt.js Job Board' },
        { name: 'twitter:description', content: 'Find Vue.js, Nuxt.js and frontend developer jobs. The dedicated job board for the Vue ecosystem.' },
        { name: 'twitter:image', content: 'https://vuejobs.in/og-image.png' },
        { name: 'twitter:image:width', content: '1200' },
        { name: 'twitter:image:height', content: '600' },
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/logo.ico' },
        { rel: 'canonical', href: 'https://vuejobs.in' },
      ],
      script: [
        // Google Analytics (gtag.js)
        {
          src: 'https://www.googletagmanager.com/gtag/js?id=G-H9642EHLLV',
          async: true,
        },
        {
          innerHTML: "window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-H9642EHLLV');",
        },
        // Organization + WebSite JSON-LD Graph
        {
          type: 'application/ld+json',
          innerHTML: JSON.stringify({
            '@context': 'https://schema.org',
            '@graph': [
              {
                '@id': 'https://vuejobs.in/#website',
                '@type': 'WebSite',
                name: 'VueJobs',
                url: 'https://vuejobs.in',
                description: 'The dedicated job board for Vue.js, Nuxt.js and the Vue ecosystem.',
                inLanguage: 'en',
                publisher: { '@id': 'https://vuejobs.in/#organization' },
                potentialAction: {
                  '@type': 'SearchAction',
                  target: 'https://vuejobs.in/jobs?search={search_term_string}',
                  'query-input': 'required name=search_term_string',
                },
              },
              {
                '@id': 'https://vuejobs.in/#organization',
                '@type': 'Organization',
                name: 'VueJobs',
                url: 'https://vuejobs.in',
                logo: 'https://vuejobs.in/logo.png',
                sameAs: [
                  'https://github.com/vuejobs',
                ],
              },
              {
                '@id': 'https://vuejobs.in/#webpage',
                '@type': 'WebPage',
                name: 'VueJobs — Vue.js & Nuxt.js Job Board | Find Vue Developer Jobs',
                url: 'https://vuejobs.in',
                isPartOf: { '@id': 'https://vuejobs.in/#website' },
                about: { '@id': 'https://vuejobs.in/#organization' },
                primaryImageOfPage: {
                  '@type': 'ImageObject',
                  url: 'https://vuejobs.in/og-image.png',
                },
              },
            ],
          }),
        },
      ],
    },
  },

  // Output to .output/public — upload this folder to Hostinger
  nitro: {
    preset: 'static',
  },

  // Blog pages: pre-rendered at build time (SSG) for SEO.
  // Everything else: SPA (client-side only).
  routeRules: {
    '/': { prerender: true },
    '/blog': { prerender: true },
    '/blog/**': { prerender: true },
    '/about': { prerender: true },
    '/sitemap.xml': { prerender: true },
  },
})
