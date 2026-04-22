export default defineNuxtRouteMiddleware(() => {
  const auth = useAuthStore()
  if (auth.token) {
    return navigateTo(auth.isEmployer ? '/recruiter' : '/dashboard')
  }
})
