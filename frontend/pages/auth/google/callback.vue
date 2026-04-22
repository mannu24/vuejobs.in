<template>
  <div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="text-center">
      <div v-if="error" class="max-w-md">
        <p class="text-red-500 mb-4">{{ error }}</p>
        <NuxtLink to="/login" class="text-vue hover:underline text-sm">Back to Login</NuxtLink>
      </div>
      <div v-else>
        <div class="w-8 h-8 border-2 border-vue border-t-transparent rounded-full animate-spin mx-auto mb-4" />
        <p class="text-gray-500">Signing you in with Google...</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const auth = useAuthStore()
const error = ref('')

onMounted(async () => {
  const code = route.query.code as string
  const errorParam = route.query.error as string

  if (errorParam) {
    error.value = errorParam === 'access_denied'
      ? 'You cancelled the Google sign-in.'
      : `Google returned an error: ${errorParam}`
    return
  }

  if (!code) {
    error.value = 'No authorization code received from Google.'
    return
  }

  // Retrieve the role the user selected before being redirected (if any)
  const role = localStorage.getItem('google_signup_role') || undefined
  localStorage.removeItem('google_signup_role')

  try {
    await auth.loginWithGoogle(code, role)
    navigateTo(auth.isEmployer ? '/recruiter' : '/dashboard')
  } catch (e: any) {
    error.value = e?.data?.message || 'Google authentication failed. Please try again.'
  }
})
</script>
