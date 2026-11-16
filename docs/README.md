# 📚 ATOM Hospital documentation

This documentation describes the existing PHP/JavaScript/SQLite prototype. Read the security warning before running it with any data.

## Start here

| Topic | Guide |
|---|---|
| Project scope and roles | [Project overview](./project-overview.md) |
| Setup | [Installation](./installation.md) |
| Request handlers | [API and endpoint reference](./api.md) |
| Application structure | [Architecture](./architecture.md) |
| SQLite files | [Database guide](./database.md) |
| Security risks | [Security review](./security-review.md) |
| Upload workflow | [Medical file handling](./medical-files.md) |
| Test and validation | [Testing](./testing.md) |
| Development | [Development workflow](./development.md) |
| Deployment readiness | [Deployment](./deployment.md) |
| Troubleshooting | [Troubleshooting](./troubleshooting.md) |

## Architecture and workflows

- [Architecture](./architecture.md)
- [Role workflows](./role-workflows.md)
- [Database schema inventory](./database.md)
- [API endpoints](./api.md)
- [Medical file lifecycle](./medical-files.md)

## Security, privacy and operations

- [Security review and remediation plan](./security-review.md)
- [Privacy and sensitive-data handling](./privacy.md)
- [Deployment checklist](./deployment.md)
- [Backup and recovery](./backup-recovery.md)
- [Testing strategy](./testing.md)
- [Limitations](./limitations.md)

## Engineering governance

- [Development guide](./development.md)
- [Contribution guide](../CONTRIBUTING.md)
- [FAQ](./faq.md)
- [Troubleshooting](./troubleshooting.md)
- [Glossary](./glossary.md)
- [Architecture Decision Records](./ADRs/README.md)
- [Diagrams catalogue](./diagrams/README.md)

## Important warning

ATOM Hospital is an educational prototype, not a validated clinical information system. Do not enter real patient data or use it to make clinical decisions. The current source contains known authentication, authorization, sensitive-data exposure, and upload path risks. Documentation does not remediate those defects. See [security review](./security-review.md).
