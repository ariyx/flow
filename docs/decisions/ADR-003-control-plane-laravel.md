# ADR-003: Use Laravel for the Control Plane

Status: Accepted

## Decision

Laravel owns authentication, workspaces, bot management, flow management, templates, submissions, dashboard APIs and the internal Filament admin panel.

## Consequences

Business management is developed quickly while runtime concerns remain isolated in Go.
