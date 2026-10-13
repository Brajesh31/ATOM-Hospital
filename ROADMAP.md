# Roadmap

## Foundation

- Document and stabilize database schemas.
- Add repeatable local setup and sample-data reset procedures.
- Add endpoint and role workflow tests.

## Security and privacy

- Replace plaintext passwords and the hardcoded admin fallback.
- Introduce server-side sessions, CSRF protection, authorization middleware, and secure cookies.
- Validate upload content and store medical files outside the public directory.
- Add audit trails, retention guidance, and least-privilege file/database permissions.

## Product improvements

- Improve accessibility and consistent error handling.
- Add database migrations and safer backups.
- Add deployment health checks and operational monitoring.
