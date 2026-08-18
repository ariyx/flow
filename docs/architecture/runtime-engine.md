# Runtime Engine

## Processing flow

```text
Telegram update
→ validate webhook secret
→ reject duplicate update
→ enqueue work
→ return HTTP 200 quickly
→ acquire per-user lock
→ resolve active execution or trigger
→ execute until waiting, complete or failed
→ persist state and events
```

## Ordering

Updates are ordered only for the same `bot_id + telegram_user_id`. Different users may execute concurrently.

## Delivery semantics

The queue is at-least-once. Idempotency should make externally visible effects effectively-once where practical.

## Execution statuses

- running
- waiting
- completed
- failed
- cancelled
- expired

## Node result contract

Every handler returns one of:

- `completed`
- `waiting`
- `failed`

The engine selects transitions. Individual handlers must not implement arbitrary graph traversal.

## Immediate nodes

- Send Message
- Send Photo
- Set Variable
- Condition
- Go To Node
- End Flow

## Suspending nodes

- Ask Question
- Show Buttons when callback input is required

## Limits

- 100 nodes per flow
- 100 consecutive steps without waiting
- 1 MB runtime definition
- 256 KB execution context
- 30-day waiting execution expiration

All limits are configurable.

## Reliability approach

V0.1 uses straightforward database transactions, idempotency records and Redis locks. A minimal persistent outbound table may be used for Telegram sends when required by implementation, but avoid building a generic workflow or messaging platform.
