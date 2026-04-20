export const useApi = () => {
  const config = useRuntimeConfig()
  const token = useCookie('auth_token')

  const apiFetch = <T>(url: string, opts: Record<string, any> = {}) => {
    const headers: Record<string, string> = {
      Accept: 'application/json',
      ...opts.headers,
    }

    if (token.value) {
      headers.Authorization = `Bearer ${token.value}`
    }

    return $fetch<T>(`${config.public.apiBase}${url}`, {
      ...opts,
      headers,
    })
  }

  return { apiFetch }
}
