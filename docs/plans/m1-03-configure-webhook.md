# Plan: M1-03 Configure and verify Telegram webhook

## Goal

Generate a per-bot webhook URL and secret, register it through the M1-01 Telegram client, and show a safe, workspace-scoped webhook status.

## Current state

M1-02 stores encrypted bot tokens and validated Telegram metadata. The client supports token validation only; bots have no webhook configuration or overview endpoint.

## Proposed changes

1. Extend the Telegram client with only `setWebhook` and `getWebhookInfo`, including deterministic fake support.
2. Add encrypted webhook-secret and minimal URL/status fields to Laravel-owned bots.
3. Generate a stable URL from configured public HTTPS base URL and the bot ULID; register before persisting a new bot.
4. Add workspace-scoped bot list/detail endpoints; the detail request refreshes its status from Telegram.
5. Add a compact bot overview and detail UI that shows the safe masked token and real webhook state only.

## Files and modules affected

Laravel Telegram client, bot model/migration/controller/routes/tests, React bot pages/API/types/localization, and M1-03 documentation.

## Data and contract changes

Adds `POST /api/v1/bots` webhook registration behavior plus `GET /api/v1/bots` and `GET /api/v1/bots/{bot}`. Adds minimal webhook fields to `bots`.

## Test strategy

Use the deterministic fake client to assert URL/secret generation, registration arguments, failures, status mapping, mismatch detection, workspace isolation, and absence of token/secret in API responses.

## Risks

Telegram requires a publicly reachable HTTPS URL. Localhost is intentionally not accepted as a live webhook base; tests do not call Telegram.

## Implementation steps

1. Add plan and webhook configuration setting.
2. Extend client DTOs and fake/real implementations.
3. Add database fields and workspace-scoped API behavior.
4. Add the minimal overview/detail UI.
5. Run all quality checks and record evidence.

## Completion evidence

- Added `setWebhook` and `getWebhookInfo` to the M1-01 abstraction, with a deterministic fake and focused real-client request/mapping test.
- Added encrypted per-bot webhook secrets, stable URL generation from `TELEGRAM_WEBHOOK_BASE_URL`, automatic registration before persistence, and status refresh/mapping.
- Added safe, workspace-scoped bot list/detail endpoints and the corresponding authenticated Web UI.
- Full Laravel suite passes: 33 tests, 118 assertions. Web lint/typecheck/tests/build, Go format/vet/tests, contract validation, and diff whitespace checks pass.
