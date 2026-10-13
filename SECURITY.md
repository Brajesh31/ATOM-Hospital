# Security Policy

## Current security limitations

ATOM Hospital is an educational prototype. The current code includes plaintext password columns and comparisons, a hardcoded `admin`/`admin` fallback, client-side `localStorage` identity data, PHP endpoints without a consistent authenticated session boundary, and an upload endpoint that accepts a caller-provided path. These behaviors make the project unsuitable for real clinical or production use.

Before deployment, require password hashing and verification, server-side sessions, CSRF protection, authorization on every endpoint, secure cookies, strict input validation, upload isolation, audit logging, TLS, least-privilege SQLite/file permissions, and secrets outside the repository.

## Reporting

Do not open a public issue for a suspected vulnerability. Contact Brajesh Kumar privately through <https://github.com/brajesh31>. Include the affected file or endpoint, reproduction steps, impact, and a suggested mitigation. Do not include real patient records, credentials, or uploaded medical files.
