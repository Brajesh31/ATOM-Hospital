# 🛡️ Privacy and sensitive-data handling

## Data categories visible in the source

The application may process staff account details, patient names, ages, gender, phone numbers, conditions, room assignments, doctor assignments, medical test names, test results/file paths, archive statuses, and uploaded documents.

These are sensitive data categories. This documentation does not establish compliance with any jurisdiction's health-data law or clinical governance requirements.

## Repository and issue hygiene

- Do not commit live SQLite databases or database backups.
- Do not add patient-identifiable screenshots or sample data.
- Use synthetic test fixtures.
- Do not paste medical records or credentials into GitHub issues.
- Remove tokens, passwords and private paths from logs.
- Restrict repository access where sensitive operational details may exist.

## Data minimization

Only collect fields required for an explicit workflow. Avoid returning complete patient rows when a screen needs only a limited subset. Avoid using names as security identifiers. Use opaque internal IDs with authorization checks.

## Retention and deletion

The current archive workflow is a feature-level record movement, not a documented retention policy. Before production use, define retention, legal hold, correction, deletion, backup retention, and access-review procedures with the appropriate privacy/legal owners.

## Incident response

Any suspected exposure of patient files, database contents, credentials, or session data should trigger immediate access restriction, evidence preservation, credential/session revocation, and a formal incident assessment. Do not attempt to hide or overwrite evidence.
