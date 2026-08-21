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

- Node.js and npm for `apps/web`
- PHP and Composer for `apps/api`
- Go 1.26.x for `apps/runtime`

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

Local PostgreSQL, Redis, and Docker infrastructure are intentionally deferred to M0-02.

### Run

Run each application from a separate terminal:

```text
npm --prefix apps/web run dev
php apps/api/artisan serve
cd apps/runtime && go run .
```

The M0-01 runtime is a minimal executable and exits immediately; it does not start a server or process work.

### Validate

```text
npm --prefix apps/web run lint
npm --prefix apps/web run build
composer --working-dir=apps/api validate --strict
php apps/api/artisan --version
cd apps/api
php artisan test
cd apps/runtime && gofmt -d . && go vet ./... && go test ./... && go build ./...
```

## Start here

1. Read `AGENTS.md` before making code changes.
2. Read `docs/product/prd-v0.1.md` for product requirements.
3. Read `docs/roadmap/milestones.md` for implementation order.
4. Read accepted ADRs before changing architecture.

## Ownership

This repository is private and proprietary. No open-source license is granted.
