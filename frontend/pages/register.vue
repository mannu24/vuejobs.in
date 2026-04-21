<template>
  <div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="bg-white rounded-xl border border-gray-200 p-8 w-full max-w-md">
      <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">Create Account</h1>

      <!-- Role Selection -->
      <div class="flex gap-3 mb-6">
        <button
          class="flex-1 py-3 rounded-lg text-sm font-medium border-2 transition"
          :class="form.role === 'developer' ? 'border-vue bg-vue/5 text-vue' : 'border-gray-200 text-gray-500'"
          @click="form.role = 'developer'"
        >
          I'm a Developer
        </button>
        <button
          class="flex-1 py-3 rounded-lg text-sm font-medium border-2 transition"
          :class="form.role === 'recruiter' ? 'border-vue bg-vue/5 text-vue' : 'border-gray-200 text-gray-500'"
          @click="form.role = 'recruiter'"
        >
          I'm a Recruiter
        </button>
      </div>

      <form @submit.prevent="handleRegister">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
          <input v-model="form.name" type="text" required class="w-full rounded-lg border-gray-300 text-sm">
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input v-model="form.email" type="email" required class="w-full rounded-lg border-gray-300 text-sm">
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input v-model="form.password" type="password" required minlength="8" class="w-full rounded-lg border-gray-300 text-sm">
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <input v-model="form.password_confirmation" type="password" required class="w-full rounded-lg border-gray-300 text-sm">
        </div>

        <div v-if="error" class="text-red-500 text-sm mb-3">{{ error }}</div>

        <button
          type="submit"
          class="w-full bg-vue text-white py-2.5 rounded-lg font-medium hover:bg-vue/90 transition disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="loading || googleLoading"
        >
          {{ loading ? 'Creating...' : 'Create Account' }}
        </button>
      </form>

      <div class="my-6 flex items-center gap-3">
        <div class="flex-1 border-t border-gray-200" />
        <span class="text-sm text-gray-400">or</span>
        <div class="flex-1 border-t border-gray-200" />
      </div>

      <button
        class="w-full flex items-center justify-center gap-2 border border-gray-300 rounded-lg py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed"
        :disabled="googleLoading || loading"
        @click="handleGoogleSignup"
      >
        <span v-if="googleLoading" class="w-4 h-4 border-2 border-gray-400 border-t-transparent rounded-full animate-spin" />
        <svg v-else class="w-5 h-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
        {{ googleLoading ? 'Redirecting...' : 'Continue with Google' }}
      </button>

      <p class="text-center text-sm text-gray-500 mt-6">
        Already have an account?
        <NuxtLink to="/login" class="text-vue font-medium hover:underline">Login</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'guest' })

useSeo({
  title: 'Sign Up — Create Your VueJobs Account',
  description: 'Join VueJobs as a Vue.js developer or employer. Create your profile, browse jobs, and connect with the Vue ecosystem.',
  url: '/register',
})

const auth = useAuthStore()
const { apiFetch } = useApi()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'developer',
})
const error = ref('')
const loading = ref(false)
const googleLoading = ref(false)

async function handleRegister() {
  loading.value = true
  error.value = ''
  try {
    await auth.register(form)
    navigateTo('/dashboard')
  } catch (e: any) {
    const errors = e?.data?.errors
    if (errors) {
      error.value = Object.values(errors).flat().join(', ')
    } else {
      error.value = e?.data?.message || 'Registration failed'
    }
  } finally {
    loading.value = false
  }
}

async function handleGoogleSignup() {
  googleLoading.value = true
  try {
    const res = await apiFetch<{ url: string }>('/auth/google/redirect')
    localStorage.setItem('google_signup_role', form.role)
    window.location.href = res.url
  } catch {
    error.value = 'Failed to initiate Google signup'
    googleLoading.value = false
  }
}
</script>
