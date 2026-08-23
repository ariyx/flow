# Plan: M1-02 Connect a bot

## Goal

Allow an authenticated owner to submit a Telegram token, validate it through the M1-01 client, persist its encrypted form with validated metadata in the current workspace, and receive only safe bot data.

## Current state

M1-01 provides a real and fake `TelegramClient`; there is no bot table, model, route, or UI.

## Proposed changes

1. Add a ULID `bots` table scoped to workspace with globally unique Telegram identity and encrypted token storage.
2. Add `Bot` and workspace relationship.
3. Bind the M1-01 real client for production, validate first, then atomically persist a bot under the authenticated owner workspace.
4. Add one authenticated API endpoint that returns metadata and a masked token only.
5. Add a connect-bot page and a dashboard action using existing app-shell styling.
6. Add API tests for validation, encryption, masking, ownership, isolation, failure, and duplicates.

## Data and contract changes

Adds Laravel-owned `bots`; adds `POST /api/v1/bots`.

## Test strategy

Use the deterministic fake client through the same interface in feature tests; run API, web, runtime, and contract quality checks.

## Risks

Raw tokens are accepted only in the request, encrypted through Laravel's encrypted cast, and never included in response payloads, exception messages, or test output.

## Completion evidence

- Added the workspace-scoped ULID bot model, encrypted token cast, and authenticated `POST /api/v1/bots` endpoint.
- Added the protected `/bots/connect` UI route, shell navigation, and dashboard action; its successful state renders metadata and a masked token only.
- `php artisan test --filter=ConnectBotTest` passes: 6 tests, 26 assertions.
- Full Laravel suite, Laravel lint/static analysis, Web lint/typecheck/tests/build, Go format/vet/tests, and contract-example validation pass.
