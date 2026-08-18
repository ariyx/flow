# Data Model

## Identifiers and conventions

- ULID primary identifiers for business entities
- snake_case database names
- UTC timestamps
- string states validated in code; no database enums
- versioned JSON documents

## Laravel-owned tables

- users
- workspaces
- bots
- flows
- flow_versions
- templates
- submissions

V0.1 stores ownership directly on the workspace. Team membership tables are deferred until collaboration is actually implemented.

## Runtime-owned tables

- telegram_users
- executions
- execution_events
- processed_updates
- telegram_outbox when required

## Important relationships

- Workspace has many bots.
- Bot has many flows and Telegram users.
- Flow has many immutable versions.
- Execution references the exact flow version used.
- Submission references workspace, bot, flow, execution and Telegram user.

## Data retention

- Published flow versions: retained while referenced
- Successful execution events: 30 days
- Failed execution events: 90 days
- Processed updates: 30 days
- Waiting executions: expire after 30 days
- Soft-deleted bots: eligible for cleanup after 30 days
- Submissions: retained until workspace deletion

A simple scheduled cleanup command is sufficient for v0.1.
