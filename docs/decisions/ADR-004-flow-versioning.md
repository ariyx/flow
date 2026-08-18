# ADR-004: Immutable Published Flow Versions

Status: Accepted

## Decision

Draft definitions are editable. Publishing creates an immutable version containing source and compiled runtime definitions. Active executions always reference the exact version they started with.

## Consequences

Editing a draft cannot break existing executions. Published versions may be archived but not deleted while referenced.
