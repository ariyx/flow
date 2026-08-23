# Plan: M1-01 Telegram client abstraction

## Goal

Provide one minimal Telegram token-validation interface with real and deterministic fake implementations, mapping Telegram's `getMe` response into an internal bot profile.

## Current state

The Laravel control plane has no bot client, bot model, persistence, or bot endpoints.

## Proposed changes

1. Add a `TelegramClient` interface with one token-validation operation.
2. Add an internal `BotProfile` value object containing only Telegram ID, optional username, and display name.
3. Add a real `getMe` implementation using Laravel's existing HTTP client and small Telegram-private response mapping.
4. Add a deterministic fake configurable for success, invalid token, or API failure.
5. Add unit tests for all four M1-01 outcomes.

## Files and modules affected

- `apps/api/app/Telegram`
- `apps/api/tests/Unit`
- this plan

## Data and contract changes

None. No API route, schema, database table, token storage, or runtime change is introduced.

## Test strategy

- Fake Laravel HTTP responses for successful `getMe`, invalid-token, and API failure responses.
- Exercise the fake through the same interface.

## Risks

No token is retained or included in exception messages. Telegram-specific response shapes remain inside the real implementation.

## Completion evidence

Implemented on 2026-08-23. Focused real-client mapping, invalid token, Telegram failure, and fake-client tests pass. No API route, database, runtime, or contract change was made.
