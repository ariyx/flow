# Architecture Overview

## Goal

Provide enough separation to demonstrate strong engineering and support future growth without building an enterprise platform in v0.1.

## Components

```text
apps/web
  React, TypeScript, React Flow

apps/api
  Laravel control plane

apps/runtime
  Go Telegram runtime

PostgreSQL
  Durable product and execution data

Redis
  Queues, short-lived locks and cache
```

## Control plane responsibilities

Laravel owns:

- authentication and workspace access
- bot management
- flow drafts and published metadata
- templates
- submissions
- dashboard APIs
- internal admin panel

## Runtime responsibilities

Go owns:

- Telegram webhooks
- update idempotency
- trigger matching
- execution state transitions
- waiting and resume
- Telegram outbound operations
- runtime execution events

## Communication

- Browser communicates with Laravel through `/api/v1`.
- Telegram communicates directly with Go webhooks.
- Laravel and Go use internal HTTP only for limited control operations.
- Redis carries asynchronous work.
- Both services use PostgreSQL with separate database users and documented ownership.

## Deployment

A single VPS with Docker Compose:

```text
nginx
web
api
api-worker
runtime
runtime-worker
postgres
redis
```

## Guardrail

Do not split additional services, add a message broker or add orchestration infrastructure in v0.1. Scale the existing units only after measurement demonstrates a real constraint.
