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
  const isEmployer = computed(() => user.value?.role === 'employer' || user.value?.role === 'recruiter')
  const isRecruiter = computed(() => user.value?.role === 'recruiter')
  const isDeveloper = computed(() => user.value?.role === 'developer')

  async function fetchUser(force = false) {
    if (!token.value) return
    if (user.value && !force) return // Already fetched, skip redundant call
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

  async function loginWithGoogle(code: string, role?: string) {
    const body: Record<string, string> = { code }
    if (role) body.role = role

    const res = await apiFetch<{ token: string; user: { data: User }; is_new: boolean }>('/auth/google/callback', {
      method: 'POST',
      body,
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
    isRecruiter,
    isDeveloper,
    fetchUser,
    login,
    register,
    loginWithGoogle,
    logout,
  }
})
