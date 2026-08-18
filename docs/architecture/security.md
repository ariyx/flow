# Security Architecture

## Authentication

- Laravel Sanctum cookie-based sessions
- CSRF protection
- Session rotation after login
- Password reset links expire after 60 minutes
- Email verification is not required in v0.1

## Workspace isolation

- Scope every Laravel query to the authenticated workspace
- Enforce access through policies and explicit query constraints
- Do not trust workspace identifiers supplied by the browser
- Cross-workspace access is a critical defect

## Telegram bot tokens

- Encrypt at application level before database storage
- Never return raw tokens to the browser
- Never place raw tokens in queue payloads or logs
- Display masked values only
- Support replacement and disconnect

## Webhooks

- Dedicated public bot identifier and random secret in webhook URL
- Telegram secret-token header validation
- POST only
- Request size limit
- No permanent raw-update storage by default

## Environment files

Agents may read `.env` when necessary. They must never expose, print, commit or copy real values into artifacts, logs, tests or reports.
