# Webilo Flow

**Build Telegram bots visually.**

Webilo Flow is a private, portfolio-oriented product alpha for creating Telegram bots through a visual flow builder. The first release focuses on a small, reliable core that can later evolve into a broader product.

## Product status

- Product: Webilo Flow
- Version: v0.1
- Codename: Origin
- Repository: private and proprietary
- Primary goal: a polished, working portfolio product for Webilo and the product owner
- Secondary goal: preserve a clean path for future expansion

## V0.1 capabilities

- Email/password authentication
- Automatic single workspace per account
- Multiple Telegram bots per workspace
- Automatic bot validation and webhook configuration
- Visual flow builder
- Draft and immutable published versions
- Go-based Telegram runtime
- Test publish through a Telegram test code
- Execution logs and timeline
- Internal Webilo templates
- Form and support submissions
- Read-only Telegram user list

## Architecture

```text
React + TypeScript      Laravel 13                Go Runtime
Dashboard and Builder   Control Plane             Telegram execution
        |                    |                          |
        +---------------- PostgreSQL ------------------+
                             |
                           Redis
```

The first release intentionally avoids Kafka, Kubernetes, Temporal, microservice proliferation, advanced analytics, billing, marketplace features, plugin systems, and enterprise-grade infrastructure.

## Repository layout

```text
apps/web        React application
apps/api        Laravel control plane
apps/runtime    Go runtime
packages/contracts
                OpenAPI, JSON Schema, examples
docs            Product, architecture, roadmap and quality documents
```

## Development

### Prerequisites

- Docker Desktop with Linux containers and Docker Compose for the complete local stack
- Node.js and npm for `apps/web`
- PHP and Composer for `apps/api`
- Go 1.26.x for `apps/runtime`

### Docker Compose

Start the complete local stack:

```text
docker compose up --build
```

The local services are available at:

- Web: `http://localhost:5173`
- API: `http://localhost:8000`
- PostgreSQL: `localhost:5432`
- Redis: `localhost:6379`

Compose waits for native PostgreSQL and Redis readiness checks before starting Laravel. Laravel runs the existing migrations, uses PostgreSQL for durable data, and uses Redis for cache, sessions, and queues. The runtime uses only a signal-aware process lifecycle so it remains running and shuts down cleanly under Compose; it does not add a server, listener, health endpoint, worker loop, queue behavior, or Telegram behavior.

Stop the stack while retaining PostgreSQL data:

```text
docker compose down
```

To intentionally remove the local PostgreSQL data volume as well, run `docker compose down --volumes`.

### Setup

Install the stock application dependencies:

```text
npm --prefix apps/web install
composer --working-dir=apps/api install
```

Laravel creates an untracked local `.env` from `.env.example` when needed. Generate a local application key before serving the API:

```text
php apps/api/artisan key:generate
```

The web app uses Sanctum's same-party, cookie-based session flow against `http://localhost:8000`. When running outside Compose, keep `FRONTEND_URL` and `SANCTUM_STATEFUL_DOMAINS` aligned with the Vite origin (default: `http://localhost:5173`). Password-reset mail uses Laravel's configured mailer; the default local configuration writes it to logs and tests use Laravel's array mailer.

The native setup can use separately installed PostgreSQL and Redis. The Docker Compose setup above supplies both dependencies and the required Laravel connection configuration.

### Run

Run each application from a separate terminal:

```text
npm --prefix apps/web run dev
php apps/api/artisan serve
cd apps/runtime && go run .
```

The runtime has only a signal-aware process lifecycle for Docker Compose; it does not start a server, health endpoint, Telegram handler, worker, or processing loop.

### Validate

```text
npm --prefix apps/web run lint
npm --prefix apps/web run build
npm --prefix apps/web run test
composer --working-dir=apps/api validate --strict
php apps/api/artisan --version
cd apps/api
php artisan test
composer run analyse
cd apps/runtime && gofmt -d . && go vet ./... && go test ./... && go build ./...
```

## CI checks

GitHub Actions runs these pull-request checks:

```text
npm --prefix apps/web run lint
npm --prefix apps/web run typecheck
npm --prefix apps/web run test
composer --working-dir=apps/api run lint
composer --working-dir=apps/api run analyse
composer --working-dir=apps/api test
```

Runtime CI fails on unformatted Go files, then runs vet and tests. Contract CI runs `node scripts/validate-contract-examples.mjs`; it validates JSON syntax only when examples exist, while runtime schemas and fixtures remain deferred to M3-01.

## Start here

1. Read `AGENTS.md` before making code changes.
2. Read `docs/product/prd-v0.1.md` for product requirements.
3. Read `docs/roadmap/milestones.md` for implementation order.
4. Read accepted ADRs before changing architecture.

## Ownership

This repository is private and proprietary. No open-source license is granted.
