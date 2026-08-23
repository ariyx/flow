import { cleanup, fireEvent, render, screen } from '@testing-library/react'
import { afterEach, expect, test } from 'vitest'
import App from './App'

afterEach(cleanup)

test('renders the sign-in screen', () => {
  window.history.pushState({}, '', '/login')
  render(<App />)
  expect(screen.getByRole('heading', { name: 'Sign in' })).toBeTruthy()
})

test('allows password visibility to be toggled', () => {
  window.history.pushState({}, '', '/login')
  render(<App />)

  const password = screen.getByLabelText('Password')
  expect(password).toHaveProperty('type', 'password')
  fireEvent.click(screen.getByRole('button', { name: 'Show password' }))
  expect(password).toHaveProperty('type', 'text')
})
