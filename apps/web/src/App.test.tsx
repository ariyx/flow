import { fireEvent, render, screen } from '@testing-library/react'
import { expect, test } from 'vitest'
import App from './App'

test('increments the starter counter', () => {
  render(<App />)

  const button = screen.getByRole('button', { name: 'Count is 0' })
  fireEvent.click(button)

  expect(button.textContent).toBe('Count is 1')
})
