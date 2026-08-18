# Security

Webilo Flow is a private project. Report security issues directly to the repository owner and do not create public disclosures.

## Sensitive data rules

- Telegram bot tokens must be encrypted at rest and masked everywhere else.
- Passwords and tokens must never appear in logs, fixtures, snapshots or issue descriptions.
- `.env` may be read only when needed for implementation or diagnosis; its values must never be exposed or committed.
- Cross-workspace access is treated as a critical security defect.
