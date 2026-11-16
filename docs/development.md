# 🧑‍💻 Development guide

## Repository organization

- admin/: administrative UI, PHP operations, charts and styling.
- bridge/: PHP endpoints called by role-specific scripts.
- database/: SQLite files. Treat as potentially sensitive.
- js/: browser-side role workflows and legacy login/session logic.
- medical/: medical file UI and upload/list PHP endpoints.
- style/: CSS files.
- workspace/: role dashboard HTML.
- logo/: branding assets.

## Local workflow

~~~bash
php -S 127.0.0.1:8000
~~~

In a second terminal, syntax-check PHP:

~~~bash
find . -name '*.php' -not -path './.git/*' -print0 | xargs -0 -n1 php -l
~~~

Optionally syntax-check JavaScript:

~~~bash
find js admin -name '*.js' -print0 | xargs -0 -n1 node --check
~~~

## Conventions

- Preserve parameterized SQLite statements.
- Validate all POST fields server-side.
- Do not trust browser role/name/id/path values.
- Never return passwords or password hashes.
- Do not insert user/database content into HTML through innerHTML.
- Keep medical uploads outside the public document root.
- Keep patient data out of logs, screenshots and test fixtures.
- Use transactions for data movement/archival where consistency matters.
- Return consistent HTTP status and JSON response structures.

## Change checklist

1. Identify all browser callers and PHP endpoints affected.
2. Document current behavior and target behavior.
3. Review authentication, role, patient ownership and CSRF requirements.
4. Implement server-side validation.
5. Add regression tests using synthetic fixtures.
6. Run PHP/JavaScript syntax checks.
7. Update API, architecture, security, and troubleshooting docs.
8. Review database migration and rollback procedure.
9. Verify no secrets or patient data entered the diff.

## Security refactor rule

Authentication cannot be fixed safely by editing only the login page. Every protected PHP endpoint and every role workspace must move to a shared server-side session model. Keep changes in a coherent migration series and test direct endpoint calls.
