<template>
  <div
    v-if="loading"
    class="fixed top-0 left-0 right-0 z-[9999] h-[3px]"
  >
    <div class="h-full bg-vue rounded-r-full loading-bar" />
  </div>
</template>

<script setup lang="ts">
const nuxtApp = useNuxtApp()
const loading = ref(false)

nuxtApp.hook('page:start', () => { loading.value = true })
nuxtApp.hook('page:finish', () => {
  setTimeout(() => { loading.value = false }, 150)
})
</script>

<style scoped>
.loading-bar {
  animation: loading 1.5s ease-in-out infinite;
}

@keyframes loading {
  0% { width: 0%; margin-left: 0; }
  50% { width: 70%; margin-left: 0; }
  100% { width: 100%; margin-left: 0; }
}
</style>
