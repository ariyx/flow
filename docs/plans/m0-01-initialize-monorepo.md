# Plan: M0-01 Initialize Monorepo

## Goal

Create the smallest valid Webilo Flow monorepo foundation: a stock Vite React + TypeScript web app, a stock Laravel 13 API app, a minimal Go 1.26 runtime module, and empty canonical contract directories.

## Current state

The repository contains product and architecture documentation only. `origin` is `https://github.com/ariyx/flow.git`; the runtime module path is therefore `github.com/ariyx/flow/apps/runtime`.

## Proposed changes

- Add `apps/web` using the stock Vite React + TypeScript scaffold, without Vitest or product dependencies.
- Add `apps/api` using the stock Laravel 13 scaffold.
- Add `apps/runtime` with a minimal `main` package and no network or worker behavior.
- Add `packages/contracts/{openapi,schemas,examples}` and package documentation only.
- Document setup and validation in the root README and record M0-01 in the changelog.

## Files and modules affected

- `apps/web/**`
- `apps/api/**`
- `apps/runtime/go.mod`
- `apps/runtime/main.go`
- `packages/contracts/openapi/.gitkeep`
- `packages/contracts/schemas/.gitkeep`
- `packages/contracts/examples/.gitkeep`
- `packages/contracts/README.md`
- `.gitignore`
- `README.md`
- `CHANGELOG.md`

## Data and contract changes

No data models, API definitions, JSON Schemas, or contract examples are introduced.

## Test strategy

Run Vite lint and build, Laravel Composer validation and stock tests, and Go formatting, vetting, tests, and build from `apps/runtime`.

## Risks

Scaffold output depends on the installed official generators. No Docker, database, Redis, CI, authentication, product UI, or other later-milestone work may be added.

## Implementation steps

1. Create the stock web and Laravel 13 scaffolds.
2. Initialize the Go module using the verified remote-derived path.
3. Create the empty contracts package structure and root ignore rules.
4. Update root documentation and the changelog.
5. Run the defined validation commands and inspect the complete diff.

## Completion evidence

All four required directories exist; each native validation command passes; root documentation lists setup and validation commands; and the final diff contains no secrets, tracked dependency directories, Docker configuration, or later-milestone features.
