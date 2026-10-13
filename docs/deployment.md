# Deployment

The project should only be deployed after a security review and test-data purge.

## Required controls

- Serve over HTTPS behind a maintained PHP runtime.
- Remove the hardcoded administrator fallback and all default credentials.
- Use server-side sessions and enforce role authorization on every PHP endpoint.
- Keep SQLite and medical uploads outside the public web root where possible.
- Restrict file permissions and configure backups with documented retention.
- Limit upload size and type using server-side content inspection.
- Disable verbose PHP errors in production and centralize sanitized logs.

There is no deployment manifest or CI workflow in this repository. Deployment is intentionally a host-specific PHP setup.
