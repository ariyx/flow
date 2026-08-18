# Release Plan

- `v0.0.1` Foundation
- `v0.0.2` Bot connection
- `v0.0.3` Builder demonstration
- `v0.0.4` First executable flow
- `v0.0.5` Interactive flow
- `v0.0.6` Test and execution logs
- `v0.0.7` Templates and submissions
- `v0.0.8` Product polish
- `v0.1.0-rc.1` Release candidate
- `v0.1.0` Webilo Flow Origin

## Release candidate gate

- Must-have scope complete
- No open Critical or High defects
- Runtime fixtures and main E2E path pass
- Workspace isolation verified
- Backup and restore tested once
- Production Docker build succeeds
- Demo environment remains stable for 72 hours
- Setup and deployment documentation verified on a clean environment

## V0.1 release gate

- Four templates execute successfully
- Multiple Telegram users execute independently
- Waiting executions survive service restart
- Duplicate updates do not create duplicate effects
- Test publish, normal publish, timeline and submissions work
- Demo deployment is presentable
- Changelog, tag and release notes are complete
