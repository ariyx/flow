# AGENTS.md

## Mission

Implement Webilo Flow v0.1 as a polished, reliable portfolio product without premature platform engineering.

## Required reading

Before non-trivial work, read:

- `docs/product/prd-v0.1.md`
- `docs/product/scope-v0.1.md`
- `docs/product/engineering-principles.md`
- `docs/architecture/overview.md`
- Relevant files in `docs/decisions/`
- `docs/quality/definition-of-done.md`

## Application boundaries

- `apps/web`: React UI, Flow Builder and dashboard
- `apps/api`: Laravel control plane and business management
- `apps/runtime`: Go Telegram runtime and flow execution
- `packages/contracts`: canonical OpenAPI, JSON Schema and examples

Do not move responsibilities across these boundaries without an ADR.

## Anti-overengineering rules

- Prefer a modular monolith and a single Go runtime service.
- Do not add Kafka, NATS, RabbitMQ, Kubernetes, Temporal or new services without measured need and an accepted ADR.
- Do not build future-version features during v0.1 work.
- Prefer straightforward code over generic frameworks, plugin systems or premature abstractions.
- A local abstraction is justified only when it removes current duplication or protects a published contract.
- Optimize for clarity, testability and delivery, not hypothetical scale.

## Contracts

- OpenAPI and JSON Schema are the source of truth.
- Update schemas and examples before or with implementation changes.
- Generated files must not be edited manually.
- Breaking changes require version increments and an ADR.

## Security

- `.env` may be read when required to complete a task.
- Never expose, print, log, copy into tests, commit or summarize real secrets from `.env`.
- Never commit credentials, bot tokens or production data.
- Mask tokens in API responses, logs, fixtures and screenshots.
- Fail closed when authorization or workspace ownership is unclear.

## Database rules

- Do not edit already-applied migrations; create a new migration.
- Destructive migrations require an explicit migration and rollback plan.
- Laravel and Go own their documented tables.
- Use ULIDs for primary business entities.
- Store timestamps in UTC.

## Testing requirements

Test the behavior changed by the task. Critical paths always require tests:

- workspace isolation
- token encryption and masking
- flow publishing
- runtime definition validation
- update idempotency
- per-user execution locking
- waiting/resume behavior
- condition branching
- Telegram retries
- submission creation

Do not delete or weaken tests to make CI pass.

## Definition of done

A task is complete only when:

- acceptance criteria pass
- relevant tests exist and pass
- localization keys are used for user-facing text
- empty, loading and error states are handled
- contracts and documentation are updated
- security and workspace isolation are reviewed
- no debug logs or secrets remain
- CI is green

## Planning

For multi-file or multi-stage changes, update or create an execution plan following `PLANS.md` before implementation.

## Codex completion report

End each task with:

```text
Implemented:
- ...

Tests:
- ...

Documentation:
- ...

Not completed:
- ...

Risks or follow-ups:
- ...
```

## Codex agent delegation

Project-scoped Codex agents are available in `.codex/agents/`.

Use delegated agents when specialization, independent review, or read-only
parallel investigation materially improves the result. Do not delegate work
only for the sake of using multiple agents.

All delegated agents must follow this `AGENTS.md`, the current execution plan,
the current milestone, acceptance criteria, relevant documentation and accepted
ADRs. Project rules override generic preferences in an agent's own instructions.

Available project agents:

- `Codebase Onboarding Engineer`
  - Use for read-only repository exploration, unfamiliar code paths, ownership
    mapping and evidence-based codebase understanding.
  - Prefer this agent before implementation when the relevant area of the
    codebase is not already understood.
  - It must not modify files.

- `Minimal Change Engineer`
  - Use for focused implementation, bug fixes and tasks where strict scope
    control is important.
  - Prefer the smallest correct change that satisfies the current acceptance
    criteria.
  - Do not perform unrelated cleanup or speculative refactoring.

- `Software Architect`
  - Use when a task may change application boundaries, contracts, persistence
    ownership, service responsibilities or other ADR-level architecture.
  - Do not invoke it automatically for straightforward implementation work.
  - Existing accepted architecture remains authoritative unless the task
    explicitly requires reconsideration.

- `Frontend Developer`
  - Use for specialized work primarily within `apps/web`.
  - Project scope, existing design decisions and acceptance criteria override
    generic frontend recommendations.

- `Backend Architect`
  - Use for specialized Laravel, API, PostgreSQL and Redis work primarily within
    `apps/api`.
  - Do not introduce infrastructure, scaling mechanisms or architectural
    patterns that are not required by the current task.

- `Code Reviewer`
  - Use for an independent review after non-trivial implementation when it can
    materially improve confidence.
  - Review correctness, regressions, security, maintainability, contracts and
    relevant test coverage.
  - Findings outside the current task may be reported as follow-ups but must not
    expand the implementation scope.

Delegation rules:

- The main Codex agent remains responsible for the final result.
- Delegate bounded tasks with a clear goal, scope and expected output.
- Prefer parallel delegation for independent read-only investigation.
- Avoid parallel write tasks that touch overlapping files or responsibilities.
- Do not let delegated agents expand the current milestone.
- Do not implement suggestions merely because a delegated agent recommends them.
- Validate delegated findings against the repository, project documentation and
  actual test results.
- Evidence from executed commands and tests takes precedence over claims.
- If an agent's generic instructions conflict with this repository's rules,
  follow this repository's rules.