<template>
  <div class="min-h-[70vh] flex items-center justify-center">
    <div class="text-center">
      <div v-if="error" class="text-red-500">{{ error }}</div>
      <div v-else class="text-gray-500">Authenticating with Google...</div>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const auth = useAuthStore()
const error = ref('')

onMounted(async () => {
  const code = route.query.code as string
  if (!code) {
    error.value = 'No authorization code received'
    return
  }
  try {
    await auth.loginWithGoogle(code)
    navigateTo('/dashboard')
  } catch {
    error.value = 'Google authentication failed. Please try again.'
  }
})
</script>
