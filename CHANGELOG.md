# Changelog

All notable changes to Webilo Flow will be documented here.

## [Unreleased]

### Added

- Product and architecture specification for v0.1 Origin.
- M0-01 monorepo foundation with stock React, Laravel 13, and Go application scaffolds and canonical contract directories.
- M0-02 local Docker Compose stack for Web, API, runtime, PostgreSQL, and Redis with pinned images, dependency readiness checks, and a signal-aware runtime lifecycle.
- M0-03 pull-request quality checks for Web, Laravel, Go, and contract examples.
- M0-04 Laravel Sanctum cookie-session authentication, password reset endpoints, and an atomically created owner workspace with a ULID identifier for each registered account.
- M1-02 authenticated Telegram bot connection with M1-01 token validation, encrypted token storage, workspace-scoped bot metadata, and masked token responses.
