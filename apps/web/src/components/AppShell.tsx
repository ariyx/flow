import { useEffect, useRef } from 'react'
import { Link, useLocation } from 'react-router-dom'
import type { Session } from '../api'
import { t } from '../i18n'
import { FlowMark } from './Brand'

type AppShellProps = { children: React.ReactNode; mobileOpen: boolean; onCloseMobile: () => void; onOpenMobile: () => void; onLogout: () => void; session: Session }

export function AppShell({ children, mobileOpen, onCloseMobile, onOpenMobile, onLogout, session }: AppShellProps) {
  const menuRef = useRef<HTMLElement>(null)
  const openerRef = useRef<HTMLButtonElement>(null)
  useEffect(() => {
    if (!mobileOpen) return
    const opener = openerRef.current
    menuRef.current?.querySelector<HTMLElement>('a, button')?.focus()
    function onKeyDown(event: KeyboardEvent) {
      if (event.key === 'Escape') onCloseMobile()
      if (event.key !== 'Tab' || !menuRef.current) return
      const items = [...menuRef.current.querySelectorAll<HTMLElement>('a, button:not([disabled])')]
      const first = items[0]; const last = items.at(-1)
      if (!first || !last) return
      if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus() }
      if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus() }
    }
    document.addEventListener('keydown', onKeyDown)
    return () => { document.removeEventListener('keydown', onKeyDown); opener?.focus() }
  }, [mobileOpen, onCloseMobile])
  const sidebar = <Sidebar onLogout={onLogout} session={session} />
  return <div className='min-h-svh bg-canvas text-ink'><aside className='fixed inset-y-0 left-0 hidden w-60 border-r border-line bg-surface p-4 lg:block'>{sidebar}</aside><header className='sticky top-0 z-20 flex h-14 items-center justify-between border-b border-line bg-canvas/95 px-4 backdrop-blur lg:hidden'><button aria-expanded={mobileOpen} aria-label='Open navigation' className='grid h-10 w-10 place-items-center rounded-md text-muted hover:bg-surface-raised hover:text-ink' onClick={onOpenMobile} ref={openerRef} type='button'><MenuIcon /></button><FlowMark /><div className='w-10' /></header>{mobileOpen && <div className='fixed inset-0 z-30 lg:hidden'><button aria-label='Close navigation' className='absolute inset-0 bg-black/60' onClick={onCloseMobile} type='button' /><aside aria-label='Mobile navigation' aria-modal='true' className='relative h-full w-[min(18rem,86vw)] border-r border-line bg-surface p-4 shadow-2xl' ref={menuRef} role='dialog'>{sidebar}</aside></div>}<main className='min-w-0 lg:pl-60'>{children}</main></div>
}

function Sidebar({ onLogout, session }: { onLogout: () => void; session: Session }) {
  const location = useLocation()
  const initials = session.user.name.split(' ').map((part) => part[0]).join('').slice(0, 2).toUpperCase()
  const navClass = (active: boolean) => `flex h-10 items-center gap-3 rounded-md border-l-2 px-3 text-sm ${active ? 'border-accent bg-surface-raised font-medium text-ink' : 'border-transparent text-muted hover:bg-surface-raised hover:text-ink'}`
  return <div className='flex h-full flex-col'><FlowMark /><nav aria-label='Application' className='mt-9 space-y-1'><Link aria-current={location.pathname === '/app' ? 'page' : undefined} className={navClass(location.pathname === '/app')} to='/app'><DashboardIcon />{t('dashboard.nav')}</Link><Link aria-current={location.pathname.startsWith('/bots') ? 'page' : undefined} className={navClass(location.pathname.startsWith('/bots'))} to='/bots'><BotIcon />{t('bots.nav')}</Link></nav><div className='mt-auto space-y-4 border-t border-line pt-4'><div className='flex items-center gap-3'><div aria-hidden='true' className='grid h-9 w-9 place-items-center rounded-md border border-line bg-surface-raised text-xs font-semibold text-accent'>{initials}</div><div className='min-w-0'><p className='truncate text-sm font-medium text-ink'>{session.user.name}</p><p className='truncate text-xs text-muted'>{session.user.email}</p></div></div><div className='rounded-md border border-line bg-surface-raised px-3 py-2'><p className='text-[11px] font-medium uppercase tracking-[0.12em] text-muted'>{t('dashboard.workspace')}</p><p className='mt-1 truncate font-mono text-xs text-ink'>{session.workspace.id}</p></div><button className='flex h-10 w-full items-center gap-3 rounded-md px-3 text-sm text-muted hover:bg-surface-raised hover:text-ink' onClick={onLogout} type='button'><LogoutIcon />{t('auth.logout')}</button></div></div>
}

function MenuIcon() { return <svg aria-hidden='true' className='h-5 w-5' fill='none' viewBox='0 0 24 24'><path d='M4 7h16M4 12h16M4 17h16' stroke='currentColor' strokeLinecap='round' strokeWidth='1.8' /></svg> }
function DashboardIcon() { return <svg aria-hidden='true' className='h-4 w-4' fill='none' viewBox='0 0 24 24'><rect height='14' rx='2' stroke='currentColor' strokeWidth='1.7' width='14' x='5' y='5' /><path d='M8 15v-2m4 2V9m4 6v-4' stroke='currentColor' strokeLinecap='round' strokeWidth='1.7' /></svg> }
function BotIcon() { return <svg aria-hidden='true' className='h-4 w-4' fill='none' viewBox='0 0 24 24'><path d='M12 4a7 7 0 0 0-7 7v5h14v-5a7 7 0 0 0-7-7ZM9 20h6M12 16v4M9 11h.01M15 11h.01' stroke='currentColor' strokeLinecap='round' strokeLinejoin='round' strokeWidth='1.7' /></svg> }
function LogoutIcon() { return <svg aria-hidden='true' className='h-4 w-4' fill='none' viewBox='0 0 24 24'><path d='M10 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6a2 2 0 0 1-2-2v-2m4-5H3m0 0 3-3m-3 3 3 3' stroke='currentColor' strokeLinecap='round' strokeLinejoin='round' strokeWidth='1.7' /></svg> }
