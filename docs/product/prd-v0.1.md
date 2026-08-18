# PRD: Webilo Flow v0.1 — Origin

## Summary

Webilo Flow v0.1 lets a user register, connect Telegram bots, create flows visually, test drafts through Telegram, publish immutable versions and inspect execution activity.

## Target users

- Business owners
- Telegram channel and community administrators
- Other users interested in building bots without code

## Product goal

A user should be able to connect a bot, build a welcome flow, publish it and see it work in Telegram within approximately ten minutes.

## Success criteria

- Approved flows execute without serious known errors.
- Multiple Telegram users can run flows independently.
- Restarting services does not lose waiting executions.
- Duplicate Telegram updates do not create duplicate effects.
- The product is polished enough for portfolio and Webilo presentation.

## Core user journeys

### Connect a bot

Register → dashboard → paste BotFather token → validate bot → confirm → webhook configured automatically.

### Create and publish a flow

Choose bot → create blank flow or use template → configure nodes → validate → test publish → publish immutable version.

### Inspect a run

Open bot → executions → select execution → view event timeline, node states and masked variables.

### Handle a submission

Open submissions → filter or select item → review collected data → update status.

## V0.1 modules

- Authentication
- Workspace foundation
- Bot management
- Flow builder
- Flow engine
- Internal templates
- Execution logs
- Submissions
- Read-only Telegram users
- Simple internal administration

## Functional requirements

### Authentication and workspace

- Email and password registration and login
- No email verification requirement in v0.1
- One workspace created automatically per account
- Workspace stores a single owner in v0.1
- Team membership is deferred until collaboration is implemented
- Only the owner can access the workspace in v0.1

### Bot management

- Multiple bots per workspace
- Bot token validation through Telegram
- Automatic webhook registration
- Token encryption at rest
- Masked token display only
- Enable, disable, disconnect and soft delete
- Bot overview with webhook health and recent runtime status

### Flow lifecycle

- Multiple flows per bot
- Exactly one primary trigger per flow
- Duplicate active triggers are rejected at publish time
- Complete React Flow draft definition
- Immutable published versions
- Draft changes do not affect the active version until republished
- Archive published versions; do not delete referenced versions

### Builder

- Node library, canvas and properties panel
- Drag and drop plus a contextual add button
- Auto-save, save status, undo, redo, duplicate and delete
- Validation panel
- Unpublished changes warning
- Test and publish actions
- Desktop and large-tablet editing only

### Triggers

- `/start`
- Custom command
- Text message with `any`, `exact` and `contains`
- Inline button callback

### Nodes

- Send Message
- Send Photo
- Ask Question
- Show Buttons
- Set Variable
- Condition
- Go To Node
- End Flow

### Variables

- System variables
- Execution-scoped flow variables
- Persistent user variables without a dedicated edit UI
- Variable interpolation in messages
- Variable values escaped for Telegram formatting

### Ask Question

- Text, number, email, phone and button-choice input
- Save answer to a variable
- Creator-defined validation error message
- Repeat invalid question up to three times
- End execution and log failure after the third invalid attempt

### Execution rules

- One active execution per Telegram user per bot
- A matching new trigger resets the active execution
- `/start` always restarts the start flow
- Loops are allowed
- Maximum 100 consecutive steps without waiting for input
- Waiting executions expire after 30 days

### Templates

Internal Webilo templates only:

1. Welcome and Multi-level Menu
2. Contact and Registration Form
3. Support Ticket
4. FAQ Bot

Using a template creates an independent flow copy.

### Submissions

- Store form and support output
- Statuses: new, in_progress, resolved, closed
- Link submission to bot, flow, user and execution
- Allow owner to change status

## UX requirements

- English UI with localization from day one
- Light and dark themes
- Checklist-based onboarding
- Professional empty, loading and error states
- Simple language outside technical execution views
- Mobile access to lists and details; builder editing disabled on small screens

## Out of scope

See `scope-v0.1.md`.
