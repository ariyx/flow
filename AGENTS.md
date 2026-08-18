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
