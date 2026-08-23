export function FlowMark({ compact = false }: { compact?: boolean }) {
  return <div className='flex items-center gap-2.5 text-ink'><svg aria-hidden='true' className='h-7 w-7 shrink-0' fill='none' viewBox='0 0 28 28'><path d='M6 8.5 14 4l8 4.5v9L14 22l-8-4.5v-9Z' stroke='currentColor' strokeWidth='1.5' /><circle cx='10' cy='11' fill='#7C3AED' r='2.25' /><circle cx='18' cy='16.5' fill='#7C3AED' r='2.25' /><path d='m11.8 12.3 4.4 2.9' stroke='currentColor' strokeWidth='1.5' /></svg>{!compact && <span className='text-sm font-semibold tracking-tight'>Webilo Flow</span>}</div>
}
