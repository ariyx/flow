export type Session = {
  user: { id: number; name: string; email: string }
  workspace: { id: string }
}

type ApiErrorBody = { message?: string; errors?: Record<string, string[]> }

const apiUrl = import.meta.env.VITE_API_URL ?? 'http://localhost:8000'

function xsrfToken(): string | undefined {
  const cookie = document.cookie.split('; ').find((item) => item.startsWith('XSRF-TOKEN='))

  return cookie ? decodeURIComponent(cookie.split('=').slice(1).join('=')) : undefined
}

async function request<T>(path: string, init: RequestInit = {}, csrf = false): Promise<T> {
  if (csrf) {
    await fetch(`${apiUrl}/sanctum/csrf-cookie`, { credentials: 'include' })
  }

  const token = xsrfToken()
  const response = await fetch(`${apiUrl}${path}`, {
    ...init,
    credentials: 'include',
    headers: {
      Accept: 'application/json',
      ...(init.body ? { 'Content-Type': 'application/json' } : {}),
      ...(token ? { 'X-XSRF-TOKEN': token } : {}),
      ...init.headers,
    },
  })

  if (!response.ok) {
    const body = (await response.json().catch(() => ({}))) as ApiErrorBody
    throw new Error(body.errors ? Object.values(body.errors).flat()[0] : body.message ?? 'Request failed')
  }

  return response.status === 204 ? (undefined as T) : (response.json() as Promise<T>)
}

const post = <T>(path: string, body: object): Promise<T> =>
  request<T>(path, { method: 'POST', body: JSON.stringify(body) }, true)

export const api = {
  register: (body: { name: string; email: string; password: string; password_confirmation: string }) => post<Session>('/api/v1/register', body),
  login: (body: { email: string; password: string }) => post<Session>('/api/v1/login', body),
  logout: () => post<void>('/api/v1/logout', {}),
  forgotPassword: (email: string) => post<void>('/api/v1/forgot-password', { email }),
  resetPassword: (body: { email: string; token: string; password: string; password_confirmation: string }) => post<void>('/api/v1/reset-password', body),
  session: () => request<Session>('/api/v1/session'),
}
