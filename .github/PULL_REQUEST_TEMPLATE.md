## 🎯 Summary

Describe the issue and the intended change.

## 🏥 Affected workflow

- [ ] Login / session
- [ ] Admin
- [ ] Doctor
- [ ] Doctor assistant
- [ ] Nurse
- [ ] Receptionist
- [ ] Laboratory
- [ ] Medical files
- [ ] Database / migration
- [ ] Documentation / CI

List exact endpoints, browser scripts, pages, and SQLite files affected.

## 🔐 Security and privacy review

- [ ] Identity is derived from a trusted server-side session.
- [ ] Role and record-level authorization are enforced server-side.
- [ ] Client-provided identity/path values are not trusted.
- [ ] CSRF protection is applied to state-changing requests.
- [ ] No password/hash/token is returned to the browser.
- [ ] User-controlled content is safely rendered.
- [ ] Uploads use server-side private storage and validation.
- [ ] No patient-identifiable data, credentials or database dumps are included.

Explain security/privacy implications and any remaining risk.

## 🗄️ Data and migration

- Database/schema changes:
- Migration approach:
- Backup and rollback plan:
- Cross-database consistency impact:

## 🧪 Validation

- [ ] PHP syntax checks
- [ ] JavaScript syntax checks
- [ ] Synthetic fixture/integration tests
- [ ] Authorization tests
- [ ] CSRF tests
- [ ] Upload/path traversal tests
- [ ] XSS/output encoding tests
- [ ] Manual role workflow tests

Commands and actual results:

## 📚 Documentation

- [ ] README/API docs updated
- [ ] Architecture/role diagrams updated
- [ ] Security review updated
- [ ] CHANGELOG/ROADMAP updated

## ⚠️ Limitations

List any untested behavior, compatibility concerns, deferred remediation, and operational prerequisites.

**Never include real patient or staff information in this pull request.**
