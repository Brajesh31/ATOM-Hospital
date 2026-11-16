# 🧪 Testing and validation strategy

## Current state

The original repository has no automated test suite or configured lint/test pipeline. The workflows in this repository are being supplemented with PHP syntax and static repository checks. Those checks do not prove functional correctness or security.

## PHP syntax

Run from repository root:

~~~bash
find . -name '*.php' -not -path './.git/*' -print0 | xargs -0 -n1 php -l
~~~

## JavaScript syntax

Use Node.js only as a local syntax checker if installed:

~~~bash
find js admin -name '*.js' -print0 | xargs -0 -n1 node --check
~~~

## Manual role workflow cases

Use disposable SQLite copies and synthetic records:
- Login succeeds/fails for each role and inactive account.
- Direct endpoint requests without login are rejected.
- Users cannot submit another user's identity.
- Admin CRUD and assistant mapping.
- Receptionist patient create/read/update/archive.
- Doctor assignment and assigned-only access.
- Assistant-to-doctor relationship enforcement.
- Nurse permitted patient/test actions.
- Laboratory order/results workflow.
- Upload valid/invalid/oversized/malformed/multi-file cases.
- Logout invalidates the server-side session.
- Database backup/restore and archive consistency.

## Security regression cases

- Hardcoded administrator bypass is absent.
- No API returns plaintext password or password hash.
- Every state-changing endpoint rejects missing/invalid CSRF token.
- Every role endpoint rejects missing session.
- Horizontal and vertical authorization tests pass.
- Path traversal and symlink escape are rejected.
- User-controlled text is rendered as text, not executable HTML.
- Private files cannot be fetched directly without authorization.

## Data integrity cases

- Archive insertion and active-patient deletion are atomic.
- Duplicate submissions do not create duplicate actions.
- Failed archive operations do not lose the active record.
- Patient identifiers and code relationships remain consistent.
- Database failures do not leak raw SQL/path details.

## Important distinction

Syntax CI is not a substitute for integration, authorization, clinical workflow, privacy, usability, or regulatory testing.
