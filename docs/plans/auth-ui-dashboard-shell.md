# Plan: Approved authentication UI and dashboard shell

## Goal

Apply the approved dark, technical visual direction to the existing M0-04 authentication experience and add the responsive, authenticated empty dashboard shell without expanding product scope.

## Current state

The standalone Vite/React app already has cookie-session authentication routes and one protected workspace route. It has no Tailwind or shadcn installation, and its current CSS is a minimal light card presentation.

## Proposed changes

1. Add Tailwind only, using the existing React/Vite stack; do not add a component library.
2. Create small reusable UI primitives for the product mark, fields, password visibility, form errors, auth layout, and dashboard shell.
3. Restyle all existing auth routes while preserving their API calls, validations, redirects, and loading behavior.
4. Replace the authenticated placeholder with an accessible responsive shell containing only the implemented Dashboard route, workspace identity, account identity, logout, and an intentional empty state.
5. Add focused web tests for the login UI and authenticated shell states; run web quality checks.

## Files and modules affected

- `apps/web` styling/configuration, UI components, routes, and tests
- this plan only in `docs/plans`

## Data and contract changes

None. The existing Sanctum/session API contract remains unchanged.

## Test strategy

- Web lint, typecheck, Vitest, and production build
- Cover password visibility and session-shell loading/error/authenticated behavior with mocked existing API calls

## Risks

- Keep decorative visuals CSS/SVG-only and non-interactive to avoid implying unsupported flows or dashboard functionality.
- No runtime, contracts, API, or stash changes.

## Completion evidence

Implemented on 2026-08-23. Web lint, typecheck, Vitest, and production build pass. The change preserves the existing Sanctum/session contract and does not modify the API, runtime, or contracts.
