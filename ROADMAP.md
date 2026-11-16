# 🗺️ Roadmap

This roadmap separates existing prototype workflows from future engineering and security requirements. Planned items are not current capabilities.

## Existing prototype foundations

- [x] HTML/CSS/JavaScript role workspaces.
- [x] PHP endpoints for user, patient, archive, lab and medical-file workflows.
- [x] SQLite persistence for user, clinic, laboratory and archive data.
- [x] Documentation of role workflows and endpoint inventory.
- [x] Source-level security review and remediation guidance.
- [x] PHP/JavaScript syntax CI and documentation checks.

## Critical security work

- [ ] Remove hardcoded administrator fallback from login/checker.
- [ ] Remove password-return admin API and frontend control.
- [ ] Migrate to password_hash/password_verify only.
- [ ] Implement server-side PHP session authentication.
- [ ] Add endpoint-level role and patient/resource authorization.
- [ ] Stop trusting identity, role and doctor-name values from POST.
- [ ] Add CSRF token validation on all state-changing operations.
- [ ] Replace caller-controlled medical paths with record IDs and private file storage.
- [ ] Replace untrusted innerHTML/template interpolation.
- [ ] Add sanitized audit events for access and mutations.

## Engineering improvements

- [ ] Centralize database initialization/configuration and error handling.
- [ ] Add versioned SQLite schema migrations.
- [ ] Define consistent JSON response and HTTP status contracts.
- [ ] Add integration tests with synthetic SQLite fixtures.
- [ ] Add authorization and upload security regression tests.
- [ ] Add dependency inventory and controlled asset versioning.
- [ ] Add safe backup/restore automation and documented retention.

## Operational readiness

- [ ] Define supported PHP/web server versions.
- [ ] Add environment-specific configuration outside source control.
- [ ] Store database and medical uploads outside the public web root.
- [ ] Add production log handling and alerting.
- [ ] Conduct independent privacy, security and clinical safety review.
- [ ] Define applicable regulatory and organizational responsibilities.

## Priority rule

Authentication, authorization, password handling, private file storage, and data privacy take precedence over UI expansion. Do not market this prototype as a clinical production system while critical security items remain open.
