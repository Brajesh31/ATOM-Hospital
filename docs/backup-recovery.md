# 💾 Backup and recovery

## Current data stores

The application uses four SQLite files: users, clinic, laboratory, and archive. They collectively contain account, patient, order, and historical data. The repository currently contains database binaries; these should be considered potentially sensitive, not disposable fixtures.

## Safe backup procedure

1. Stop writes or use SQLite's online backup mechanism.
2. Back up all related database files together at a consistent point.
3. Preserve restrictive file permissions.
4. Encrypt backups at rest and in transit.
5. Store copies separately from the application/web root.
6. Define retention and access controls.
7. Record backup completion and verification without copying patient data into logs.

## Recovery procedure

1. Restrict application access.
2. Restore the related database set into a protected environment.
3. Validate SQLite integrity with PRAGMA integrity_check.
4. Verify schema and record relationships.
5. Run role workflow tests with synthetic records.
6. Re-enable access only after integrity and authorization checks pass.

## Cross-database consistency

The archive flow reads active data, inserts an archive record, and deletes the active record across separate database files. Without an appropriate transaction strategy, partial failures may result in duplicates or lost workflow state. A safer design should use a single database or carefully coordinated transactions/compensating logic.

## Retention

No formal backup retention schedule or disaster recovery objective is defined in the source. Production owners must define RPO/RTO and legal retention requirements before deployment.
