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
React + TypeScript      Laravel 12                Go Runtime
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

## Start here

1. Read `AGENTS.md` before making code changes.
2. Read `docs/product/prd-v0.1.md` for product requirements.
3. Read `docs/roadmap/milestones.md` for implementation order.
4. Read accepted ADRs before changing architecture.

## Ownership

This repository is private and proprietary. No open-source license is granted.
