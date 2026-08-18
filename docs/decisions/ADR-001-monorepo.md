# ADR-001: Use a Monorepo

Status: Accepted

## Context

React, Laravel, Go and shared contracts must evolve together under solo development and agent-assisted implementation.

## Decision

Use one repository with `apps/web`, `apps/api`, `apps/runtime`, `packages/contracts` and `docs`.

## Consequences

Contract and documentation changes are reviewed together. CI can validate all applications in one pull request.
