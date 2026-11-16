# ADR 0001: Keep existing role workspaces while centralizing security controls

- **Status:** Proposed
- **Date:** 2026-11-16
- **Decision owner:** Maintainer

## Context

The application has separate HTML/JavaScript workspaces and PHP endpoints for admin, doctor, assistant, nurse, receptionist, and laboratory roles. The security model is currently client-directed and inconsistent across endpoints.

## Decision

Retain the role-specific page structure in the first security refactor, but introduce shared server-side authentication, role authorization, CSRF validation, response helpers, and database access helpers. Do not treat page visibility or localStorage as authorization.

## Alternatives

### Rewrite the entire UI at once

Rejected for the first phase because it increases migration surface and makes it harder to isolate security behavior changes.

### Keep relying on localStorage

Rejected because a browser-controlled object can be modified and does not prove an authenticated server identity.

## Consequences

The UI can remain familiar while permissions are enforced centrally. Every endpoint must be migrated before the application is considered secure. This ADR is a proposal and does not claim that the target security architecture has already been implemented.
