# M0-04 — Authentication and owner workspace

## Goal

Deliver the minimal v0.1 authentication flow for the standalone React SPA and Laravel API, including one atomically-created owner workspace per registered account.

## Approach

1. Add Laravel Sanctum's maintained stateful, cookie-based SPA authentication and expose only `/api/v1` authentication/session endpoints.
2. Add a ULID `workspaces` table with one unique `owner_id`, its model relationship, and a transactional registration service.
3. Use Laravel's password broker for reset requests and reset completion; retain the existing test-safe mail configuration.
4. Add small React routes for registration, login, reset, and the protected authenticated placeholder. Use local message keys rather than a new localization framework.
5. Cover API authentication, atomic workspace creation, and isolation with feature tests; run the existing M0-03 checks.

## Boundaries

- No token scheme, email verification, teams, memberships, workspace switching, social login, dashboard features, runtime work, or contracts work.
- The current-user endpoint derives its workspace from the authenticated user; it accepts no workspace identifier and fails closed if ownership is absent.

## Validation

- API: Pint, PHPStan, PHPUnit feature tests, and Laravel route/config checks.
- Web: lint, typecheck, unit tests, and production build.
- CI: run the M0-03 quality commands unchanged after implementation.

## Completion

Implemented and validated locally on 2026-08-23. The Compose API was exercised with PostgreSQL and Redis using a temporary local account; no runtime or contract changes were made.
