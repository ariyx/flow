# Risk Register

| ID | Risk | Probability | Impact | Mitigation | Warning signs | Owner | Status |
|---|---|---:|---:|---|---|---|---|
| R1 | Scope growth | High | High | Lock v0.1 node and feature scope | New features enter active milestone | Product Owner | Active |
| R2 | Contract drift across React, PHP and Go | Medium | High | JSON Schema, OpenAPI, shared examples and CI validation | Manual type edits or mismatched fixtures | Product Owner | Active |
| R3 | Execution state inconsistency | Medium | High | Explicit state machine, transactions, idempotency and fixtures | Stuck or duplicated executions | Product Owner | Active |
| R4 | Premature infrastructure complexity | Medium | High | PostgreSQL, Redis and Docker Compose only unless measured need exists | Proposal for new broker/service without metrics | Product Owner | Active |
| R5 | Builder is difficult for non-technical users | Medium | High | Templates, simple copy, contextual add button and actionable validation | Users require developer help for basic flow | Product Owner | Active |
| R6 | Telegram API dependency | Medium | Medium | Telegram client abstraction and retry handling | API logic spreads across modules | Product Owner | Active |
| R7 | Solo development delay | High | Medium | Vertical slices, small PRs and feature freeze | Long-lived branches or broad tasks | Product Owner | Active |
