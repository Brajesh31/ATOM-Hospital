# 🔐 Security review and remediation plan

## Executive assessment

**Status: NOT SAFE FOR REAL PATIENT DATA OR PRODUCTION USE.**

This is a source-level review of the checked-in files, not a penetration test or compliance audit. Findings below are based on directly observed implementation patterns.

## Critical findings

### 1. Login discloses credentials to the browser

bridge/login.php selects both password and password_hash, compares the submitted password to a plaintext password field, and returns the submitted password and stored password_hash in the JSON response. index.html stores the resulting object in localStorage.

**Impact:** a user with browser access can inspect credentials/token material. XSS or shared-device access can expose that data. The application cannot treat a localStorage object as proof of identity.

**Remediation:** migrate accounts to password_hash/password_verify; remove plaintext password storage and every response field containing a password/hash; establish a server-side PHP session after successful verification; rotate session IDs; use Secure, HttpOnly, SameSite cookies; invalidate the session server-side on logout.

### 2. Hardcoded administrator fallback

bridge/login.php and bridge/cheker.php include a fixed admin/admin fallback and a static administrator marker.

**Impact:** an attacker can obtain administrator access if the endpoint is reachable and fallback remains enabled.

**Remediation:** remove fallback credentials and markers. Bootstrap the initial administrator through a one-time deployment procedure that creates a password hash, then disable setup.

### 3. Role routing is client-side and is not authorization

The browser chooses a page based on role data. Multiple bridge endpoints accept identity values such as name, id, doctor_name, and role from POST parameters. Page-level redirection does not stop direct endpoint requests.

**Impact:** callers may attempt to act as another user or access records outside their assignment.

**Remediation:** create a shared server-side authentication/authorization bootstrap. Every endpoint must load the authenticated principal from the session, enforce the required role and resource ownership, reject unauthorized access with HTTP 401/403, and ignore client-supplied identity claims.

### 4. Password disclosure in admin API

admin/admin.php exposes get_password, which queries and returns the stored password field.

**Impact:** administrators and any caller able to reach that action may retrieve user passwords in plaintext.

**Remediation:** remove the action and UI workflow. Passwords must never be retrievable. Provide a password reset flow that stores only a newly generated password hash.

### 5. Caller-controlled medical file path

medical/upload.php accepts POST path, creates that directory, and stores files there. medical/medical.php accepts POST dir and passes it to scandir.

**Impact:** path traversal, arbitrary directory enumeration, and writes outside the intended medical storage area may be possible depending on filesystem permissions.

**Remediation:** derive storage roots server-side, map opaque record IDs to authorized records, canonicalize paths under a fixed private directory, reject symlinks/path escapes, and keep uploads outside the public document root.

## High-priority findings

### 6. Medical files may be publicly addressable

The medical workflow passes a path/record code in a URL query and directory listing returns filenames. Storage is coupled to public project paths in the current design.

**Remediation:** store private metadata in the database and stream files through an authenticated authorization-checked download endpoint. Do not expose raw filesystem paths.

### 7. HTML injection risk in JavaScript rendering

Some scripts construct table rows, modal HTML, and inline event handlers from database values using template strings and innerHTML.

**Impact:** stored XSS may be possible through patient/user fields, depending on the value's path to the browser.

**Remediation:** build DOM nodes and assign user-controlled values using textContent; avoid inline handlers; validate and encode every output for its context.

### 8. CSRF and session protection are missing

State-changing POST operations do not consistently use CSRF tokens. The current localStorage identity cannot provide CSRF protection or session security.

**Remediation:** after server-side sessions are introduced, add per-session CSRF tokens to all state-changing requests, validate Origin/Referer as a supplementary measure, and use appropriate SameSite cookie policy.

### 9. Upload validation is incomplete

The upload handler accepts multiple document types and trusts caller-selected directories. It does not consistently validate every PHP upload error, array shape, actual file size, file count, or safe random destination filename. Its response array is overwritten rather than consistently accumulated.

**Remediation:** validate upload error code, size, count, extension/MIME using Fileinfo, inspect PDF/image content as appropriate, assign random server-generated names, store outside web root, and return per-file results.

### 10. Error handling may disclose internals

Several handlers return exception text or database errors to callers.

**Remediation:** log detailed errors privately with correlation IDs; return generic messages and appropriate status codes.

## Recommended sequence

1. Disable public/production access and use test data only.
2. Remove hardcoded admin bypass and password-return APIs.
3. Add shared PHP session authentication and server-side role checks.
4. Migrate plaintext passwords to password hashes, then remove plaintext columns after backup and verification.
5. Protect all state-changing operations with CSRF tokens.
6. Replace caller-controlled file paths with opaque record/file IDs and private storage.
7. Remove unsafe innerHTML/inline event construction for user-controlled values.
8. Add audit logging for patient access, updates, archive actions, and downloads.
9. Add authorization, upload, CSRF, and XSS regression tests.
10. Review applicable privacy, security, legal, and clinical requirements before any real-world deployment.

## Verification needed

A proper remediation requires testing direct endpoint access without a session, cross-role access, horizontal record access, session fixation, CSRF, malicious upload payloads, path traversal, stored XSS, database backup/recovery, and audit completeness.
