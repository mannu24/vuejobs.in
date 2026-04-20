import { defineStore } from 'pinia'

interface User {
  id: number
  name: string
  email: string
  role: string
  headline?: string
  about?: string
  location?: string
  avatar_url?: string
  skill_tags: string[]
  is_available: boolean
  links: {
    linkedin?: string
    github?: string
    website?: string
    portfolio?: string
  }
  settings?: Record<string, any>
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = useCookie('auth_token', { maxAge: 60 * 60 * 24 * 30 })
  const { apiFetch } = useApi()

  const isLoggedIn = computed(() => !!token.value && !!user.value)
  const isEmployer = computed(() => user.value?.role === 'employer')
  const isDeveloper = computed(() => user.value?.role === 'developer')

  async function fetchUser() {
    if (!token.value) return
    try {
      const res = await apiFetch<{ data: User }>('/me')
      user.value = res.data
    } catch {
      logout()
    }
  }

  async function login(email: string, password: string) {
    const res = await apiFetch<{ token: string; user: { data: User } }>('/auth/login', {
      method: 'POST',
      body: { email, password },
    })
    token.value = res.token
    user.value = res.user.data
  }

  async function register(data: { name: string; email: string; password: string; password_confirmation: string; role: string }) {
    const res = await apiFetch<{ token: string; user: { data: User } }>('/auth/register', {
      method: 'POST',
      body: data,
    })
    token.value = res.token
    user.value = res.user.data
  }

  async function loginWithGoogle(code: string) {
    const res = await apiFetch<{ token: string; user: { data: User } }>('/auth/google/callback', {
      method: 'POST',
      body: { code },
    })
    token.value = res.token
    user.value = res.user.data
  }

  function logout() {
    if (token.value) {
      apiFetch('/auth/logout', { method: 'POST' }).catch(() => {})
    }
    token.value = null
    user.value = null
    navigateTo('/login')
  }

  return {
    user,
    token,
    isLoggedIn,
    isEmployer,
    isDeveloper,
    fetchUser,
    login,
    register,
    loginWithGoogle,
    logout,
  }
})
