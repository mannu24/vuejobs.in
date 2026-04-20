<template>
  <div>
    <section class="bg-gradient-to-br from-vue-dark to-gray-900 text-white py-16">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">About VueJobs</h1>
        <p class="text-gray-300 text-lg max-w-2xl mx-auto">
          The dedicated job board for the Vue.js ecosystem.
        </p>
      </div>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="prose prose-lg max-w-none text-gray-700">
        <h2 class="text-gray-900">Why VueJobs?</h2>
        <p>
          VueJobs was created to solve a simple problem: Vue.js developers and employers need a focused
          place to find each other. General job boards bury Vue-specific roles under thousands of unrelated
          listings. VueJobs changes that by being 100% dedicated to the Vue ecosystem.
        </p>

        <h2 class="text-gray-900">What We Cover</h2>
        <p>
          Our platform covers the entire Vue.js technology stack, including:
        </p>
        <ul>
          <li>Vue.js 2 and Vue.js 3 developer positions</li>
          <li>Nuxt.js (Nuxt 2 and Nuxt 3) full-stack roles</li>
          <li>Frontend engineers working with Pinia, Vuex, Vue Router</li>
          <li>Full-stack developers using Laravel + Vue, Node.js + Vue</li>
          <li>TypeScript-focused Vue development roles</li>
          <li>Remote, hybrid, and on-site positions worldwide</li>
          <li>Full-time, contract, freelance, and internship opportunities</li>
        </ul>

        <h2 class="text-gray-900">For Developers</h2>
        <p>
          Create your developer profile, showcase your Vue.js skills, and get discovered by employers
          who specifically need Vue talent. Set up job alerts to get notified when new positions match
          your preferences. Apply directly on the platform or through external links.
        </p>

        <h2 class="text-gray-900">For Employers</h2>
        <p>
          Post your Vue.js job openings and reach a targeted audience of Vue developers. No noise from
          unrelated candidates. Add your company profile, manage applications, track performance, and
          find the right developers for your team.
        </p>

        <h2 class="text-gray-900">Our Mission</h2>
        <p>
          We aim to become the largest Vue.js-focused job board, connecting talented developers with
          companies that value the Vue ecosystem. Whether you're building with Vue 3 and the Composition
          API, deploying Nuxt applications, or managing state with Pinia, VueJobs is your home for
          career growth and hiring.
        </p>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
useSeo({
  title: 'About VueJobs — The Vue.js & Nuxt.js Job Board',
  description: 'VueJobs is the dedicated job platform for Vue.js, Nuxt.js and the Vue ecosystem. Learn about our mission to connect Vue developers with the right opportunities.',
  url: '/about',
})
</script>
