# 🤝 Contributing to ATOM Hospital

Thank you for considering a contribution. ATOM Hospital is an educational PHP/JavaScript/SQLite prototype. Contributions should improve correctness, clarity, privacy, and security without overstating the system's clinical readiness.

## Safety first

- Do not use real patient/staff data in local development, fixtures, screenshots, issues, or pull requests.
- Do not commit live SQLite databases, backups, medical documents, credentials, tokens, or deployment secrets.
- Do not treat localStorage values or client-side page routing as authorization.
- Do not add a workflow feature that bypasses server-side permission checks.
- Security defects affecting authentication, authorization, and patient data take priority over cosmetic work.

## Local setup

See [docs/installation.md](docs/installation.md). Use a local PHP server and disposable SQLite data.

## Coding expectations

- Keep SQL parameterized.
- Validate all inputs server-side.
- Derive identity and role from a trusted server-side session.
- Check authorization for every record access and mutation.
- Use CSRF defenses on state-changing requests.
- Use textContent/DOM APIs for untrusted display values.
- Keep medical uploads outside the public document root.
- Return sanitized errors and avoid leaking SQL, paths, credentials, or patient data.
- Maintain PHP and JavaScript syntax checks.

## Tests

Include reproducible checks using synthetic data. Security-sensitive changes need tests for missing sessions, incorrect roles, cross-user record access, CSRF, unsafe paths, invalid uploads, and HTML injection. Syntax-only CI is not sufficient evidence of security.

## Documentation

Update the README, relevant endpoint contracts, architecture diagrams, security review, migration notes, and CHANGELOG when behavior changes.

## Pull requests

Use the pull request template. Explain the affected roles, endpoints, databases, data fields, migration/rollback plan, privacy impact, tests run, and remaining risks.

## Reporting security issues

Follow [SECURITY.md](SECURITY.md). Do not publish exploit details or patient information in a public issue.
