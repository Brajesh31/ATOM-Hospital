# 🗄️ Database inventory and data handling

## Database files

| File | Observed purpose | Sensitive content |
|---|---|---|
| database/users.db | User accounts, roles/status, assistant mappings | Authentication data and staff information |
| database/clinic.db | Active patient records and assignments | Patient identifiers, demographics, conditions, phone, room |
| database/laboratory.db | Medical orders, tests and paths | Clinical workflow and medical file references |
| database/archive.db | Archived patient records and status/outcome | Historical patient information |

The repository contains binary SQLite files. Their current contents must be treated as potentially sensitive. Do not publish their contents, sample rows, or screenshots. Production databases should not be committed to version control.

## Schemas inferred from PHP

### users.db

The PHP source creates or queries a users table with id, name, password, password_hash, status and role. An assist table stores name_doctor and name_assist. The current coexistence of password and password_hash is a security defect.

### clinic.db

The receptionist endpoint creates a patients table containing id, name, age, condition, phone_number, doctor_name, patient_gender, room, created_at and code. Other endpoints depend on this table.

### laboratory.db

The patient bridge creates a medical table containing id, name, doctor_name, test, orderd and path.

### archive.db

The archive table stores patient demographics/condition, doctor, room, dates, code and status.

These are source-derived observations. The committed DB files may contain additional tables/columns or schema drift. Inspect a protected copy before migrations.

## Operational requirements

- Back up SQLite files before schema changes.
- Never expose database files through the web server.
- Use restrictive filesystem permissions.
- Use parameterized statements for all user-supplied values.
- Use transactions for archive/move sequences and cross-database workflows.
- Add indexes only after examining query patterns and safely migrating.
- Avoid logging patient content, passwords, tokens, or file contents.
- Use sanitized test databases in CI.

## Schema discovery

On a local disposable copy, use:

~~~sql
SELECT name, sql
FROM sqlite_master
WHERE type = 'table'
ORDER BY name;
~~~

Record schema version and migration steps. Do not paste live patient rows into issues or documentation.
