# V0.1 Implementation Backlog

This backlog is ordered. Each item should normally become one focused GitHub Issue and one pull request.

## M0 — Foundation

### M0-01 Initialize monorepo

**Acceptance criteria**
- `apps/web`, `apps/api`, `apps/runtime` and `packages/contracts` exist.
- Root development commands are documented.
- No placeholder infrastructure beyond PostgreSQL and Redis is added.

### M0-02 Add local Docker Compose

- Web, API, runtime, PostgreSQL and Redis start locally.
- Health checks report dependency readiness.
- Images use pinned versions.

### M0-03 Add CI quality checks

- Frontend lint/typecheck/test, Laravel lint/static analysis/test and Go format/vet/test run on pull requests.
- Contract examples validate.

### M0-04 Implement authentication and owner workspace

- Register, login, logout and password reset work.
- One workspace is automatically created and owned by the user.
- Email verification is not required.

## M1 — Bot Management

### M1-01 Create Telegram client abstraction
- Real and fake clients share one interface.
- Token-validation response is mapped into an internal bot profile.

### M1-02 Connect a bot
- User submits a token.
- Token is validated, encrypted and never returned raw.
- Bot metadata is stored under the current workspace.

### M1-03 Configure and verify webhook
- Unique webhook URL and secret are generated.
- Telegram webhook is registered automatically.
- Bot overview shows webhook status.

### M1-04 Manage bot lifecycle
- Enable, disable, disconnect and soft delete work.
- Disconnect removes the Telegram webhook.
- Deleted bots stop accepting runtime updates.

### M1-05 Add simple Filament administration
- Admin can inspect users, bots and failed runtime activity.
- Admin cannot view raw bot tokens.

## M2 — Builder Foundation

### M2-01 Create flow CRUD
- A bot can have multiple flows.
- Name, optional description and one trigger are stored.

### M2-02 Implement builder shell
- Node library, canvas and properties panel are present.
- Desktop-only editing rule is enforced.

### M2-03 Implement draft persistence
- Draft definition includes nodes, edges and viewport.
- Auto-save shows saving and saved states.

### M2-04 Add editor operations
- Undo, redo, duplicate, delete, zoom and fit-view work.
- Contextual add button can insert a connected node.

### M2-05 Add draft validation UI
- Invalid nodes are marked.
- Errors block publishing; warnings are distinguishable.

## M3 — Runtime Core

### M3-01 Define runtime schemas
- Runtime flow and initial node schemas exist with valid and invalid examples.
- TypeScript, PHP and Go validate shared fixtures.

### M3-02 Compile and publish immutable versions
- Laravel validates the draft.
- Go validates the compiled runtime definition.
- Publishing creates and activates an immutable version.

### M3-03 Receive Telegram webhooks
- Secret and request method are validated.
- Accepted updates are queued and acknowledged quickly.
- Duplicate updates are ignored safely.

### M3-04 Implement execution lifecycle
- Execution can start, run, complete, fail, cancel and expire.
- Events are appended to the execution timeline.

### M3-05 Implement core triggers and nodes
- `/start`, custom command, text matching, Send Message and End Flow execute.
- `/start` restarts an active execution.

### M3-06 Add per-user locking
- Updates for one bot/user pair are serialized.
- Different users may execute concurrently.

## M4 — Interactive Flows

### M4-01 Implement Ask Question
- Runtime waits and resumes on the next matching input.
- Accepted answer is saved to a flow variable.

### M4-02 Implement input validation
- Text, number, email, phone and button-choice inputs work.
- Custom error message is repeated for up to three failed attempts.

### M4-03 Implement variables
- System, flow and persistent user variables resolve.
- Interpolated values are escaped for the chosen Telegram parse mode.

### M4-04 Implement buttons and callbacks
- Inline callback and URL buttons render.
- Callback input can resume a waiting execution.

### M4-05 Implement conditions and Go To
- Approved operators branch to true or false paths.
- Loops work within the consecutive-step limit.

### M4-06 Implement waiting expiry and reset
- Waiting executions expire after 30 days.
- A new matching trigger resets the current execution.

## M5 — Test and Debug

### M5-01 Link owner Telegram account by test code
- Panel creates a short-lived test code.
- Sending `/test CODE` links the Telegram account to the owner.

### M5-02 Execute a draft in test mode
- Draft runs only for the linked owner.
- Active published flow remains unchanged.

### M5-03 Build execution list and timeline
- Owner can filter and inspect execution events.
- Sensitive values are masked.

### M5-04 Add builder test status
- Running, completed and failed node states can be displayed during test execution.

### M5-05 Add Telegram retry behavior
- Temporary errors retry with backoff.
- `retry_after` is respected.
- Permanent failures are logged without pointless retries.

## M6 — Templates and Submissions

### M6-01 Implement template gallery and cloning
- Webilo templates can be previewed and cloned into independent flows.

### M6-02 Create four templates
- Welcome menu, registration form, support ticket and FAQ pass runtime fixtures.

### M6-03 Store and manage submissions
- Submission data links to the originating execution and user.
- Owner can change status.

### M6-04 Add read-only Telegram users
- User identity, activity, variables and related submissions are viewable.

## M7 — Product Polish

### M7-01 Dashboard and onboarding checklist
- Summary cards and getting-started progress reflect real data.

### M7-02 Theme and localization polish
- Light/dark themes work.
- User-facing text comes from localization keys.

### M7-03 Empty, loading and error states
- Every primary page has purposeful states and actionable errors.

### M7-04 Dangerous action confirmations
- Bot deletion requires typing the bot username.
- Disconnect, discard, archive and reset require confirmation.

### M7-05 Demo seed data
- Local demo workspace includes representative flows, users, submissions and execution timelines without real credentials.

## M8 — Hardening and Release

### M8-01 Complete critical automated journeys
- Main Playwright flow and runtime fixture suite pass reliably.

### M8-02 Validate backup and restore
- Daily backup command exists.
- Restore is performed successfully once and documented.

### M8-03 Add lightweight production observability
- Structured logs, health checks and error tracking are active.
- No full metrics platform is required for v0.1.

### M8-04 Production deployment
- Docker Compose deployment, rollback and environment setup are documented.

### M8-05 Release candidate and demo soak
- No Critical or High defects are open.
- Demo remains stable for 72 hours.

### M8-06 Release v0.1.0 Origin
- Version tag, changelog and release notes are produced.
