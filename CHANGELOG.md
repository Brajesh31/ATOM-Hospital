# 📝 Changelog

Notable repository changes are recorded here.

## [Unreleased]

### Added
- Expanded README with visible image badges, role inventory, architecture flow, setup, security warning and documentation links.
- Detailed project overview, role workflows, endpoint inventory, database guide and architecture documentation.
- Security review with concrete source-level findings and remediation sequence.
- Privacy, medical file lifecycle, backup/recovery, limitations and glossary guides.
- Editable Mermaid diagrams for architecture, login, roles, medical files, target security and deployment checks.
- ADR structure and initial architecture proposal.
- PHP/JavaScript syntax CI, documentation link checker and secret scanning workflow.

### Changed
- Clarified the prototype's unsupported production/clinical status.
- Added explicit warnings against using real patient data.
- Documented actual role names, SQLite stores, and existing upload/login behavior.

### Security notes
- Documentation does not itself fix the known plaintext password, admin bypass, localStorage identity, missing authorization, caller-controlled path, or HTML injection defects. See docs/security-review.md.
