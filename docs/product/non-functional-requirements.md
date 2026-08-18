# Non-functional Requirements

These are internal quality targets for a portfolio alpha, not commercial SLAs.

## Reliability

- Waiting executions survive process restart.
- Duplicate Telegram updates do not create duplicate executions or submissions.
- Updates for the same bot and Telegram user are processed in order.
- Runtime failures are visible in execution events and structured logs.
- No execution remains indefinitely in `running`; recovery or expiry must resolve it.

## Performance

- Webhook endpoints acknowledge accepted updates quickly and perform flow work asynchronously.
- Dashboard pages should become usable in roughly two seconds on a normal connection.
- The builder must remain usable for the v0.1 maximum of 100 nodes.
- Execution lists use server-side pagination.

No high-scale bot-count or throughput promise is part of v0.1. Load testing only verifies that the online demo and expected early usage remain stable on one VPS.

## Security

- Workspace isolation is mandatory.
- Bot tokens are encrypted and never returned raw.
- Secrets are excluded from logs, fixtures and reports.
- Critical write endpoints are rate-limited and validated.

## Recovery

- Daily PostgreSQL backup.
- At least seven daily backups retained.
- Restore procedure tested once before release.
- Target RPO: 24 hours.
- Target RTO: 4 hours.

## Availability

There is no contractual SLA. The online demo should remain stable enough for presentation, with documented restart and rollback procedures.

## Compatibility

- Latest two versions of Chrome, Edge and Firefox.
- Current Safari.
- Builder editing is not supported on small mobile screens.
