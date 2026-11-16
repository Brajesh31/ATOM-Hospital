# 🛡️ Privacy and sensitive-data handling

## Data categories visible in the source

The application may process staff account details, patient names, ages, gender, phone numbers, conditions, room assignments, doctor assignments, medical test names, test result/file paths, archive statuses and uploaded documents.

These are sensitive data categories. This document does not establish compliance with any jurisdiction's health-data laws or clinical governance requirements.

## Repository and issue hygiene

- Do not commit live SQLite databases or backups.
- Do not add patient-identifiable screenshots or sample data.
- Use synthetic fixtures.
- Do not paste medical records or credentials into GitHub issues.
- Remove tokens, passwords and private paths from logs.
- Restrict repository access where operational details are sensitive.

## Data minimization

Collect only fields required for a workflow. Avoid returning complete patient rows when a screen needs only a limited subset. Avoid using names as security identifiers. Use opaque IDs with authorization checks.

## Retention and deletion

The current archive workflow is a feature-level record movement, not a formal retention policy. Define retention, legal hold, correction, deletion, backup retention and access review with appropriate privacy/legal owners before production use.

## Incident response

A suspected exposure of patient files, databases, credentials or session data should trigger access restriction, evidence preservation, credential/session revocation and formal incident assessment. Do not overwrite evidence.
