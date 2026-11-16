# 💾 Backup and recovery

## Data stores

The application uses four SQLite files: users, clinic, laboratory and archive. They contain account, patient, order and historical information. Treat committed database binaries as potentially sensitive.

## Safe backup procedure

1. Stop writes or use SQLite's online backup mechanism.
2. Back up related databases consistently.
3. Preserve restrictive permissions.
4. Encrypt backups at rest and in transit.
5. Store copies outside the application/web root.
6. Define retention and access controls.
7. Record backup completion without copying patient data into logs.

## Recovery procedure

1. Restrict application access.
2. Restore related database files into a protected environment.
3. Run PRAGMA integrity_check.
4. Verify schema and record relationships.
5. Run role workflow tests using synthetic records.
6. Re-enable access only after integrity and authorization checks pass.

## Cross-database consistency

Archive code reads active data, inserts an archive record, and deletes the active record across separate database files. Partial failure may result in duplicates or lost workflow state. A safer design should use a single database or carefully coordinated transactions/compensating logic.

No formal backup retention schedule or disaster recovery objectives are defined in the source. Production owners must define RPO/RTO before deployment.
