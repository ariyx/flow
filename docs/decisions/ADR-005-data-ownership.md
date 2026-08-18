# ADR-005: Explicit Database Ownership

Status: Accepted

## Decision

Laravel and Go use separate database users and own documented tables. Cross-service reads and writes must be minimal and explicit.

## Consequences

The deployment remains simple with one PostgreSQL database while reducing accidental coupling.
