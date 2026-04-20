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
      appName: 'VueJobs',
    },
  },

  googleFonts: {
    families: {
      Inter: [400, 500, 600, 700],
    },
  },

  app: {
    head: {
      title: 'VueJobs - Vue.js & Nuxt.js Job Board',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Find Vue.js, Nuxt.js and frontend developer jobs. The dedicated job board for the Vue ecosystem.' },
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      ],
    },
  },

  // Output to .output/public — upload this folder to Hostinger
  nitro: {
    preset: 'static',
  },
})
