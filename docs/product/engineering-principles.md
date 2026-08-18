# Engineering Principles

## Primary rule

Build the smallest reliable implementation that satisfies v0.1. Preserve clean boundaries, but do not implement future product layers early.

## What future-ready means

- Published contracts are versioned.
- Responsibilities are clearly separated.
- Data has stable ownership.
- Critical behavior is tested.
- Replacing or scaling a component later remains possible.

It does **not** mean building unused abstractions, services, tables, configuration systems or extension points.

## V0.1 simplicity rules

- One React app, one Laravel app and one Go runtime.
- One PostgreSQL database and one Redis instance.
- One VPS and Docker Compose.
- No generic event bus or plugin framework.
- No team schema until team collaboration exists.
- No feature-flag platform.
- No generic workflow SDK.
- No observability stack beyond logs, health checks and lightweight error tracking.
- No performance optimization without profiling or a failing target.
- Prefer explicit node handlers and contracts over a deeply generic node framework.

## Decision test

Before adding complexity, answer:

1. Which current v0.1 requirement needs it?
2. What simpler option was considered?
3. What concrete failure occurs without it?
4. Can it be added later without blocking the release?

If the answers are weak, defer it.
