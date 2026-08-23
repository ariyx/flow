import { useState } from 'react'

type PasswordInputProps = { id: string; value: string; onChange: (value: string) => void; autoComplete: string; required?: boolean; minLength?: number }

export function PasswordInput({ id, value, onChange, autoComplete, required = true, minLength }: PasswordInputProps) {
  const [visible, setVisible] = useState(false)
  return <div className='relative'><input autoComplete={autoComplete} className='h-11 w-full rounded-md border border-line bg-surface-raised px-3 pr-11 text-sm text-ink outline-none placeholder:text-muted focus:border-accent' id={id} minLength={minLength} onChange={(event) => onChange(event.target.value)} required={required} type={visible ? 'text' : 'password'} value={value} /><button aria-label={visible ? 'Hide password' : 'Show password'} className='absolute inset-y-0 right-0 grid w-11 place-items-center text-muted hover:text-ink' onClick={() => setVisible(!visible)} type='button'><svg aria-hidden='true' className='h-4 w-4' fill='none' viewBox='0 0 24 24'>{visible ? <path d='M3 3l18 18M10.6 10.7a3 3 0 0 0 4.2 4.2M9.9 5.1A10.9 10.9 0 0 1 12 5c5.2 0 8.7 4.2 9.7 7-0.5 1.4-1.5 2.9-2.9 4.1M6.2 6.2C4.3 7.6 3.2 9.8 2.3 12c1 2.8 4.5 7 9.7 7 0.8 0 1.6-0.1 2.3-0.3' stroke='currentColor' strokeLinecap='round' strokeWidth='1.7' /> : <><path d='M2.5 12S6.2 5 12 5s9.5 7 9.5 7-3.7 7-9.5 7-9.5-7-9.5-7Z' stroke='currentColor' strokeWidth='1.7' /><circle cx='12' cy='12' r='3' stroke='currentColor' strokeWidth='1.7' /></>}</svg></button></div>
}
