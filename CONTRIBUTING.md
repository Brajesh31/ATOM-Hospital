# Contributing to ATOM Hospital

Contributions are welcome, but this project handles simulated health information and should be treated as privacy-sensitive.

## Workflow

1. Create a focused branch from `main`.
2. Run the application through PHP and use disposable SQLite data.
3. Keep changes scoped to one workflow or documentation topic.
4. Test affected roles, endpoints, validation, and error paths manually.
5. Update documentation when routes, schemas, uploads, or setup change.

Never commit patient data, credentials, generated uploads, or production database files. Do not add a security control that is only enforced in JavaScript; sensitive operations must be checked by PHP.

## Pull requests

Describe the affected role and endpoint, database impact, manual verification performed, and any migration or privacy considerations. Report suspected vulnerabilities privately using [SECURITY.md](SECURITY.md).
