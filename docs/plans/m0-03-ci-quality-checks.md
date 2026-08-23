# Plan: M0-03 CI Quality Checks

## Goal

Add the smallest GitHub Actions pull-request workflow that validates the existing Web, Laravel, Go, and contracts scaffolds with clear, application-owned commands.

## Current state

- No GitHub Actions workflow exists.
- Web has Oxlint and TypeScript build checking, but no explicit typecheck script or unit-test runner.
- Laravel has Pint and PHPUnit, but no Composer scripts for linting or static analysis and no static-analysis dependency.
- Go has native formatting, vet, and test tooling.
- Contracts contains only reserved directories and `.gitkeep` files; no schemas or examples exist yet.
- The user-owned runtime `/healthz` work remains safely stored in `stash@{0}` and is not part of this work.

## Proposed changes

- Add one pull-request GitHub Actions workflow with separate Web, API, runtime, and contracts jobs.
- Add explicit Web `typecheck` and `test` scripts using Vitest, jsdom, and React Testing Library, with one counter-interaction test.
- Add Laravel Composer scripts for Pint check, Larastan analysis, and the existing PHPUnit test command; configure Larastan for the existing `app/` source.
- Add a dependency-free Node contract-example validator that validates JSON syntax when examples are introduced and reports the current empty state without inventing contracts.
- Use native Go formatting, vet, and test commands in CI.

## Files and modules affected

- `.github/workflows/quality.yml`
- `apps/web/package.json`
- `apps/web/package-lock.json`
- `apps/web/vite.config.ts`
- `apps/web/src/App.test.tsx`
- `apps/api/composer.json`
- `apps/api/composer.lock`
- `apps/api/phpstan.neon`
- `scripts/validate-contract-examples.mjs`
- `README.md`
- `CHANGELOG.md`
- `docs/plans/m0-03-ci-quality-checks.md`

## Data and contract changes

No application schema, migration, API contract, JSON Schema, or example is added. The validator checks JSON example syntax only when such examples are later introduced; M3-01 remains responsible for runtime schemas and fixtures.

## Test strategy

- Run all new CI commands locally: Web lint/typecheck/test, API lint/analyse/test, Go format check/vet/test, and contract validation.
- Validate the workflow YAML and inspect that it contains four separate pull-request jobs.
- Verify the current tool and action versions before dependency installation.

## Risks

- Static analysis may surface existing framework-scaffold issues; set the smallest useful Larastan configuration rather than suppressing errors broadly.
- GitHub-hosted action execution can only be fully verified by an actual pull request.

## Implementation steps

1. Verify compatible package and action versions.
2. Add this plan before implementation.
3. Install the minimal Web and Laravel quality dependencies and add configuration/scripts/tests.
4. Add the contract validator and the separate-jobs GitHub Actions workflow.
5. Update local-development documentation and changelog.
6. Run all local checks and validate workflow structure.

## Completion evidence

- Version checks confirmed Vitest 4.1.11 supports Vite 8 and Node 24, React Testing Library 16.3.2 supports React 19, jsdom 30.0.1 supports Node 24, and Larastan 3.10.0 supports Laravel 13 and PHP 8.2+.
- GitHub Actions versions were verified as checkout v7, setup-node v7, setup-go v7, and setup-php v2.
- Web `npm ci`, lint, typecheck, and unit test passed.
- API Composer install, Pint check, Larastan analysis, and PHPUnit tests passed.
- The Linux Go format assertion, vet, and tests passed in the runtime image.
- Contract validation passed with the current empty example directory.
- `actionlint v1.7.12` accepted the workflow.
- The user-owned runtime work remains stored at `stash@{0}` and was not used.
