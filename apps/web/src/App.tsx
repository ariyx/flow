import { useEffect, useState } from 'react'
import type { FormEvent } from 'react'
import { BrowserRouter, Link, Navigate, Route, Routes, useNavigate, useSearchParams } from 'react-router-dom'
import { api } from './api'
import type { Session } from './api'
import { t } from './i18n'
import './App.css'

function Page({ children }: { children: React.ReactNode }) {
  return <main className='auth-page'><section className='card'><h1>{t('app.title')}</h1>{children}</section></main>
}

function ErrorMessage({ message }: { message: string | null }) {
  return message ? <p className='error' role='alert'>{message}</p> : null
}

function Login() {
  const navigate = useNavigate()
  const [params] = useSearchParams()
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState<string | null>(null)
  const [loading, setLoading] = useState(false)

  async function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault()
    setLoading(true); setError(null)
    try { await api.login({ email, password }); navigate('/app') } catch (reason) { setError(reason instanceof Error ? reason.message : t('auth.error')) } finally { setLoading(false) }
  }

  return <Page><h2>{t('auth.login')}</h2><form onSubmit={submit}>
    <label>{t('auth.email')}<input type='email' value={email} onChange={(e) => setEmail(e.target.value)} required /></label>
    <label>{t('auth.password')}<input type='password' value={password} onChange={(e) => setPassword(e.target.value)} required /></label>
    <ErrorMessage message={error} /><button disabled={loading}>{loading ? t('auth.loading') : t('auth.login')}</button>
  </form>{params.get('reset') && <p role='status'>{t('auth.passwordReset')}</p>}<p>{t('auth.noAccount')} <Link to='/register'>{t('auth.register')}</Link></p><Link to='/forgot-password'>{t('auth.forgot')}</Link></Page>
}

function Register() {
  const navigate = useNavigate()
  const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '' })
  const [error, setError] = useState<string | null>(null)
  const [loading, setLoading] = useState(false)
  const update = (key: keyof typeof form, value: string) => setForm({ ...form, [key]: value })

  async function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault()
    setLoading(true); setError(null)
    try { await api.register(form); navigate('/app') } catch (reason) { setError(reason instanceof Error ? reason.message : t('auth.error')) } finally { setLoading(false) }
  }

  return <Page><h2>{t('auth.register')}</h2><form onSubmit={submit}>
    <label>{t('auth.name')}<input value={form.name} onChange={(e) => update('name', e.target.value)} required /></label>
    <label>{t('auth.email')}<input type='email' value={form.email} onChange={(e) => update('email', e.target.value)} required /></label>
    <label>{t('auth.password')}<input type='password' value={form.password} onChange={(e) => update('password', e.target.value)} minLength={8} required /></label>
    <label>{t('auth.confirmPassword')}<input type='password' value={form.password_confirmation} onChange={(e) => update('password_confirmation', e.target.value)} minLength={8} required /></label>
    <ErrorMessage message={error} /><button disabled={loading}>{loading ? t('auth.loading') : t('auth.register')}</button>
  </form><p>{t('auth.haveAccount')} <Link to='/login'>{t('auth.login')}</Link></p></Page>
}

function ForgotPassword() {
  const [email, setEmail] = useState('')
  const [sent, setSent] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [loading, setLoading] = useState(false)
  async function submit(event: FormEvent<HTMLFormElement>) { event.preventDefault(); setLoading(true); setError(null); try { await api.forgotPassword(email); setSent(true) } catch (reason) { setError(reason instanceof Error ? reason.message : t('auth.error')) } finally { setLoading(false) } }
  return <Page><h2>{t('auth.reset')}</h2><form onSubmit={submit}><label>{t('auth.email')}<input type='email' value={email} onChange={(e) => setEmail(e.target.value)} required /></label><ErrorMessage message={error} />{sent && <p role='status'>{t('auth.resetRequested')}</p>}<button disabled={loading}>{loading ? t('auth.loading') : t('auth.requestReset')}</button></form><Link to='/login'>{t('auth.login')}</Link></Page>
}

function ResetPassword() {
  const navigate = useNavigate(); const [params] = useSearchParams()
  const [password, setPassword] = useState(''); const [confirmation, setConfirmation] = useState(''); const [error, setError] = useState<string | null>(null); const [loading, setLoading] = useState(false)
  async function submit(event: FormEvent<HTMLFormElement>) { event.preventDefault(); setLoading(true); setError(null); try { await api.resetPassword({ email: params.get('email') ?? '', token: params.get('token') ?? '', password, password_confirmation: confirmation }); navigate('/login?reset=1') } catch (reason) { setError(reason instanceof Error ? reason.message : t('auth.error')) } finally { setLoading(false) } }
  return <Page><h2>{t('auth.reset')}</h2><form onSubmit={submit}><label>{t('auth.password')}<input type='password' value={password} onChange={(e) => setPassword(e.target.value)} minLength={8} required /></label><label>{t('auth.confirmPassword')}<input type='password' value={confirmation} onChange={(e) => setConfirmation(e.target.value)} minLength={8} required /></label><ErrorMessage message={error} /><button disabled={loading}>{loading ? t('auth.loading') : t('auth.reset')}</button></form></Page>
}

function Workspace() {
  const navigate = useNavigate(); const [session, setSession] = useState<Session | null | undefined>(undefined)
  useEffect(() => { api.session().then(setSession).catch(() => setSession(null)) }, [])
  if (session === undefined) return <Page><p role='status'>{t('auth.loading')}</p></Page>
  if (session === null) return <Navigate to='/login' replace />
  async function logout() { await api.logout(); navigate('/login') }
  return <Page><h2>{t('workspace.title')}</h2><p>{t('workspace.signedInAs')} {session.user.name} ({session.user.email})</p><p>{t('workspace.identifier')}: <code>{session.workspace.id}</code></p><p>{t('workspace.placeholder')}</p><button onClick={logout}>{t('auth.logout')}</button></Page>
}

export default function App() {
  return <BrowserRouter><Routes><Route path='/login' element={<Login />} /><Route path='/register' element={<Register />} /><Route path='/forgot-password' element={<ForgotPassword />} /><Route path='/reset-password' element={<ResetPassword />} /><Route path='/app' element={<Workspace />} /><Route path='*' element={<Navigate to='/app' replace />} /></Routes></BrowserRouter>
}
