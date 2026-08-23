# Plan: M0-02 Local Docker Compose

## Goal

Add the smallest local-only Docker Compose environment in which the existing Web, API, and runtime scaffolds can run with PostgreSQL and Redis, using exactly five services and pinned image versions.

## Current state

- M0-01 provides stock Vite React, Laravel 13, and minimal Go application scaffolds.
- No Docker or Compose files are tracked yet.
- Laravel's example environment defaults to SQLite and database-backed cache, queue, and sessions.
- The M0-01 Go runtime intentionally exited immediately. The user-owned HTTP implementation has been stashed and remains outside this work.
- Docker CLI 29.7.2 and Docker Compose 5.3.1 are installed, but the Docker Desktop Linux daemon was unavailable during initial inspection.

## Proposed changes

- Add local development Dockerfiles for Web, API, and runtime using the milestone's exact pinned base images.
- Add the smallest signal-aware runtime process lifecycle so the runtime is a running Compose service and shuts down cleanly, without adding runtime-core behavior.
- Add a root Compose file with exactly `web`, `api`, `runtime`, `postgres`, and `redis` services.
- Persist PostgreSQL data in a named volume and add native PostgreSQL and Redis health checks.
- Configure the API service to use PostgreSQL and Redis through Compose environment variables and wait for both dependencies to become healthy.
- Mount application source for local development while preserving image-installed Web and API dependencies in named volumes.
- Document local startup, ports, lifecycle, and validation commands.

## Files and modules affected

- `compose.yaml`
- `apps/web/Dockerfile`
- `apps/web/.dockerignore`
- `apps/api/Dockerfile`
- `apps/api/.dockerignore`
- `apps/runtime/Dockerfile`
- `apps/runtime/.dockerignore`
- `apps/runtime/main.go`
- `apps/runtime/main_test.go`
- `README.md`
- `CHANGELOG.md`
- `docs/plans/m0-02-local-docker-compose.md`

The stashed user-owned HTTP runtime changes will not be restored, modified, or used as validation evidence.

## Data and contract changes

No product database schema, migration, OpenAPI, JSON Schema, or contract change is introduced. PostgreSQL receives only Laravel's existing stock migrations when the local API starts. Laravel receives the sole application database credential in M0-02 because it is the only application that accesses PostgreSQL; the runtime receives no database credential. When runtime persistence is introduced, ADR-005 requires a separate runtime user and documented table ownership.

## Test strategy

- Verify every required exact image tag with `docker manifest inspect` immediately before implementation.
- Render and validate the Compose model with `docker compose config`, including an explicit service-count check and pinned-image inspection.
- Build all three application images if the Docker daemon is available.
- Start the stack and verify service state, PostgreSQL readiness, Redis readiness, the Web scaffold, the Laravel scaffold, and runtime behavior if the Docker daemon is available.
- Run the existing Web lint/build, Laravel Composer validation/tests, and Go formatting/vet/tests/build without altering user-owned runtime files.

## Risks

- Docker-daemon-dependent validation cannot run while Docker Desktop's Linux daemon is unavailable; such checks must be reported separately as not verified due to environment.
- A clean exit from the committed M0-01 runtime does not leave a usable Compose runtime service running. M0-02 therefore adds only signal-aware process lifecycle behavior; it must not add a server, worker loop, health endpoint, queue behavior, or Telegram behavior.
- Bind mounts on Docker Desktop require dependency volumes so host dependency directories do not replace Linux container dependencies.

## Implementation steps

1. Verify the six exact registry image tags.
2. Add minimal application Dockerfiles and ignore files.
3. Add the minimal signal-aware runtime lifecycle and its cancellation test.
4. Add the exact five-service Compose model with dependency health wiring and PostgreSQL persistence.
5. Update root development documentation and the changelog.
6. Validate statically, then perform daemon-dependent build and runtime checks where the environment permits.
7. Obtain an independent code review, address in-scope blocker findings, and rerun affected checks.

## Completion evidence

- All six exact image tags returned success from `docker manifest inspect`.
- `docker compose config --quiet` passed and the rendered model contains exactly five services.
- Web lint/build, Laravel Composer validation/tests, and Go vet/tests/build passed.
- `gofmt -d .` reports only CRLF differences in the two pre-existing user-owned runtime files; they were not rewritten.
- A clean runtime image build completed with `docker compose build --no-cache --pull runtime`. The runtime container image was verified to match the current `flow-runtime:latest` image before lifecycle checks; no stale image was used as evidence.
- The rebuilt runtime reached `running`, stopped through `docker compose stop runtime` with exit code 0, and was restored to `running`.
- Read-only onboarding, architecture, and independent code reviews found no blocker-level M0-02 issues. The review's runtime-port scope suggestion was applied.
