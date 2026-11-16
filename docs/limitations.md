# ⚠️ Current limitations

- The repository is a prototype, not a validated clinical information system.
- Login and role routing rely on client-side state and insecure legacy behavior.
- Multiple endpoints do not enforce server-side authorization.
- Password management retains plaintext-related code paths.
- Upload and file listing accept caller-supplied filesystem paths.
- JavaScript inserts user/database values into HTML in several views.
- No consistent CSRF framework is present.
- No centralized API contract or schema migration system exists.
- No automated integration/security test suite was present in the original project.
- SQLite database files are committed in the repository.
- No production deployment infrastructure or operational monitoring configuration exists.
- No formal privacy, clinical safety, or regulatory assurance is established.

## Safe usage boundary

Run only locally with synthetic test data in an isolated environment. Do not use real patient information, live hospital workflows or clinical decisions.

Resolve authentication, authorization, password handling, private file storage and output encoding before adding new UI features.
