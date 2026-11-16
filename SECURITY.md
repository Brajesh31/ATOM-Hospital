# 🔐 Security policy

## Security status

ATOM Hospital is an educational prototype and is **not safe for real patient data or production use** in its current form. The source contains known authentication, authorization, password exposure, and filesystem path risks. See [the source-level security review](docs/security-review.md).

## Reporting vulnerabilities

Use GitHub's private Security Advisories feature:
https://github.com/Brajesh31/ATOM-Hospital/security/advisories/new

Do not publish sensitive patient data, credentials, exploit payloads, or private server paths in public issues.

Include the affected commit/file, platform/runtime, minimal synthetic reproduction, expected and actual behavior, and relevant sanitized logs.

## Known high-risk areas

- Plaintext password comparisons/returns and hardcoded administrator bypass.
- Client-side localStorage token used as identity state.
- Inconsistent server-side role and resource authorization.
- Admin endpoint capable of returning stored passwords.
- Caller-controlled upload/list directory paths.
- Dynamic HTML construction from user/database values.
- Missing consistent CSRF/session protection.

## Safe-use requirements

Use only isolated development environments and synthetic records. Do not connect this prototype to production medical systems, public patient databases, real clinical files, or real patient workflows.

## Response process

Restrict affected access, preserve evidence, rotate exposed credentials/sessions, assess data exposure, remediate the root cause, add regression tests, and communicate the impact through the project's approved channels.

## Security checks

CI scans repository history for secrets and performs syntax/repository checks. Passing those checks does not constitute a security audit, penetration test, compliance assessment, or clinical safety validation.
