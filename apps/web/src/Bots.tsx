import { useEffect, useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import { ApiError, api } from './api'
import type { ConnectedBot, Session, WebhookStatus } from './api'
import { AppShell } from './components/AppShell'
import { t } from './i18n'

const statusClass: Record<WebhookStatus, string> = {
  healthy: 'border-success/30 bg-success/10 text-emerald-100',
  not_configured: 'border-warning/30 bg-warning/10 text-amber-100',
  mismatch: 'border-danger/30 bg-danger/10 text-red-100',
  error: 'border-danger/30 bg-danger/10 text-red-100',
}

const statusLabel: Record<WebhookStatus, string> = {
  healthy: t('bots.status.healthy'),
  not_configured: t('bots.status.not_configured'),
  mismatch: t('bots.status.mismatch'),
  error: t('bots.status.error'),
}

function PageFrame({ children }: { children: (session: Session, retry: () => void) => React.ReactNode }) {
  const navigate = useNavigate(); const [session, setSession] = useState<Session | null>(null); const [error, setError] = useState(false); const [mobileOpen, setMobileOpen] = useState(false)
  const load = () => { setError(false); api.session().then(setSession).catch((reason) => { if (reason instanceof ApiError && reason.status === 401) navigate('/login', { replace: true }); else setError(true) }) }
  useEffect(() => { api.session().then(setSession).catch((reason) => { if (reason instanceof ApiError && reason.status === 401) navigate('/login', { replace: true }); else setError(true) }) }, [navigate])
  if (!session) return <main className='grid min-h-svh place-items-center bg-canvas p-6 text-center text-sm text-muted'>{error ? <div><p>{t('dashboard.loadError')}</p><button className='mt-4 rounded-md border border-line px-3 py-2 text-ink hover:bg-surface-raised' onClick={load} type='button'>{t('dashboard.retry')}</button></div> : <p role='status'>{t('auth.loading')}</p>}</main>
  async function logout() { try { await api.logout() } finally { navigate('/login', { replace: true }) } }
  return <AppShell mobileOpen={mobileOpen} onCloseMobile={() => setMobileOpen(false)} onLogout={logout} onOpenMobile={() => setMobileOpen(true)} session={session}>{children(session, load)}</AppShell>
}

export function BotOverview() {
  const [bots, setBots] = useState<ConnectedBot[] | null>(null); const [error, setError] = useState(false)
  const loadBots = () => { setError(false); api.bots().then((result) => setBots(result.bots)).catch(() => setError(true)) }
  useEffect(() => { api.bots().then((result) => setBots(result.bots)).catch(() => setError(true)) }, [])
  return <PageFrame>{() => <section className='mx-auto max-w-4xl px-5 py-8 sm:px-8 sm:py-10'><header className='flex flex-wrap items-start justify-between gap-4 border-b border-line pb-6'><div><h1 className='text-2xl font-semibold tracking-tight'>{t('bots.overviewTitle')}</h1><p className='mt-2 text-sm text-muted'>{t('bots.overviewDescription')}</p></div><Link className='inline-flex h-10 items-center rounded-md bg-accent px-4 text-sm font-medium text-white hover:bg-accent-hover' to='/bots/connect'>{t('bots.connect')}</Link></header><div className='pt-6'>{error ? <button className='rounded-md border border-line px-3 py-2 text-sm text-ink hover:bg-surface-raised' onClick={loadBots} type='button'>{t('dashboard.retry')}</button> : bots === null ? <p className='text-sm text-muted' role='status'>{t('auth.loading')}</p> : bots.length === 0 ? <p className='rounded-lg border border-line bg-surface p-5 text-sm text-muted'>{t('bots.empty')}</p> : <div className='grid gap-3'>{bots.map((bot) => <BotCard bot={bot} key={bot.id} />)}</div>}</div></section>}</PageFrame>
}

export function BotDetail() {
  const { botId } = useParams(); const [bot, setBot] = useState<ConnectedBot | null>(null); const [error, setError] = useState(false)
  const loadBot = () => { if (!botId) return; setError(false); api.bot(botId).then((result) => setBot(result.bot)).catch(() => setError(true)) }
  useEffect(() => { if (!botId) return; api.bot(botId).then((result) => setBot(result.bot)).catch(() => setError(true)) }, [botId])
  return <PageFrame>{() => <section className='mx-auto max-w-2xl px-5 py-8 sm:px-8 sm:py-10'><Link className='text-sm text-accent hover:text-violet-300' to='/bots'>{t('bots.back')}</Link><header className='mt-5 border-b border-line pb-6'><h1 className='text-2xl font-semibold tracking-tight'>{bot?.display_name ?? t('bots.overviewTitle')}</h1>{bot?.telegram_username && <p className='mt-2 text-sm text-muted'>@{bot.telegram_username}</p>}</header><div className='pt-6'>{error ? <button className='rounded-md border border-line px-3 py-2 text-sm text-ink hover:bg-surface-raised' onClick={loadBot} type='button'>{t('dashboard.retry')}</button> : bot === null ? <p className='text-sm text-muted' role='status'>{t('auth.loading')}</p> : <BotDetails bot={bot} />}</div></section>}</PageFrame>
}

function BotCard({ bot }: { bot: ConnectedBot }) { return <Link className='block rounded-lg border border-line bg-surface p-5 transition-colors hover:bg-surface-raised focus-visible:outline focus-visible:outline-2 focus-visible:outline-accent' to={`/bots/${bot.id}`}><div className='flex flex-wrap items-start justify-between gap-3'><div><p className='font-medium text-ink'>{bot.display_name}</p>{bot.telegram_username && <p className='mt-1 text-sm text-muted'>@{bot.telegram_username}</p>}</div><Status status={bot.webhook_status} /></div></Link> }
function BotDetails({ bot }: { bot: ConnectedBot }) { return <div className='rounded-lg border border-line bg-surface p-5 sm:p-6'><div className='flex flex-wrap items-center justify-between gap-3'><h2 className='font-medium text-ink'>{t('bots.webhook')}</h2><Status status={bot.webhook_status} /></div><dl className='mt-6 grid gap-4 text-sm'><div><dt className='text-muted'>{t('bots.maskedToken')}</dt><dd className='mt-1 font-mono text-ink'>{bot.token_masked}</dd></div>{bot.webhook_checked_at && <div><dt className='text-muted'>{t('bots.checkedAt')}</dt><dd className='mt-1 text-ink'>{new Date(bot.webhook_checked_at).toLocaleString()}</dd></div>}</dl></div> }
function Status({ status }: { status: WebhookStatus }) { return <span className={`rounded-full border px-2.5 py-1 text-xs font-medium ${statusClass[status]}`}>{statusLabel[status]}</span> }
