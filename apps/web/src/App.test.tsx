import { render, screen } from '@testing-library/react'
import { expect, test } from 'vitest'
import App from './App'

test('renders the sign-in screen', () => {
  window.history.pushState({}, '', '/login')
  render(<App />)
  expect(screen.getByRole('heading', { name: 'Sign in' })).toBeTruthy()
})
