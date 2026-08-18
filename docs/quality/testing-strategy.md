# Testing Strategy

V0.1 does not require an arbitrary global coverage percentage. Tests focus on product-critical behavior.

## Web

- Vitest and React Testing Library
- Builder state, properties forms, validation and undo/redo
- Playwright for registration, bot connection with mocks, flow creation, publish, execution view and submission view

## Laravel

- Pest feature tests
- Workspace policy and isolation tests
- Bot token encryption and masking
- Publishing workflow
- Soft delete and webhook disconnect behavior
- OpenAPI contract validation

## Go

- Table-driven node-handler tests
- Runtime compiler and schema validation
- PostgreSQL and Redis integration tests
- Duplicate update protection
- Per-user locking and ordering
- Execution reset, wait and resume
- Telegram retry and rate-limit behavior
- Fake Telegram client

## Shared fixtures

- simple_welcome
- multi_step_menu
- registration_form
- support_ticket
- condition_branch
- loop_with_wait
- invalid_infinite_loop

JSON Schema examples and fixtures must be validated in CI.
