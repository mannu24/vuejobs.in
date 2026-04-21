export default defineNuxtRouteMiddleware(() => {
  const auth = useAuthStore()
  if (!auth.token) return navigateTo('/login')
  if (!auth.isEmployer) return navigateTo('/dashboard')
})
