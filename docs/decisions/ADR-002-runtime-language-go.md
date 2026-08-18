# ADR-002: Use Go for the Telegram Runtime

Status: Accepted

## Context

The runtime handles concurrent Telegram updates, durable execution state, retries and per-user ordering.

## Decision

Implement the runtime in Go as one modular service.

## Alternatives considered

Laravel-only runtime was simpler initially but offered less separation and less portfolio value. Multiple runtime services were rejected as premature.

## Consequences

Contracts between Laravel and Go must be explicit and tested. The runtime must remain one deployable service in v0.1.
