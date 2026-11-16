# 🔐 Security review and remediation plan

## Executive assessment

**Status: NOT SAFE FOR REAL PATIENT DATA OR PRODUCTION USE.**

This is a source-level review of checked-in files, not a penetration test or compliance audit.

## Critical findings

### 1. Login discloses credentials to the browser

bridge/login.php selects password and password_hash, compares the submitted password to a plaintext password field, and returns the submitted password and stored password_hash in JSON. index.html stores the returned object in localStorage.

**Impact:** browser users can inspect credential material; XSS/shared-device access can expose it. A localStorage object is not proof of identity.

**Remediation:** migrate accounts to password_hash/password_verify; remove plaintext storage and every response field containing a password/hash; establish a server-side PHP session after verification; rotate session IDs; use Secure, HttpOnly, SameSite cookies; invalidate the session on logout.

### 2. Hardcoded administrator fallback

bridge/login.php and bridge/cheker.php contain fixed admin/admin fallback behavior and a static administrator marker.

**Impact:** the fallback can grant administrator access while reachable.

**Remediation:** remove fallback credentials and markers. Bootstrap an initial administrator through a one-time secure deployment procedure, then disable setup.

### 3. Client-side role routing is not authorization

The browser selects pages from role data. Multiple bridge endpoints accept identity values such as name, id, doctor_name, and role from POST parameters.

**Impact:** callers may try to act as another user or access records outside their assignment.

**Remediation:** create shared server-side auth/authorization middleware. Every endpoint must load the principal from the session, enforce role and record ownership, ignore client-supplied identity claims, and reject unauthorized access with HTTP 401/403.

### 4. Password disclosure in admin API

admin/admin.php exposes get_password, which queries and returns the stored password field.

**Impact:** callers able to reach that action may retrieve plaintext passwords.

**Remediation:** remove the action and its UI. Passwords must never be retrievable. Provide a reset flow that stores only a new password hash.

### 5. Caller-controlled medical file path

medical/upload.php accepts POST path, creates that directory, and stores files there. medical/medical.php accepts POST dir and passes it to scandir.

**Impact:** path traversal, arbitrary directory enumeration, and writes outside the intended storage area may be possible depending on server permissions.

**Remediation:** derive storage roots server-side, map opaque record IDs to authorized records, canonicalize paths under a fixed private directory, reject symlinks/path escapes, and store uploads outside the public document root.

## High-priority findings

### 6. Medical files may be publicly addressable

The medical workflow passes a record code/path in a URL query and returns filenames. Store files privately and stream them through authenticated, authorization-checked downloads.

### 7. HTML injection risk in JavaScript rendering

Some scripts construct rows/modals/inline handlers from database values using template strings and innerHTML. Use DOM APIs and textContent for untrusted values; avoid inline handlers and contextually encode output.

### 8. CSRF and session protection are missing

State-changing POST operations do not consistently use CSRF tokens. After server-side sessions are implemented, add per-session tokens to state-changing requests and use secure cookie policy.

### 9. Upload validation is incomplete

The upload handler needs checks for PHP upload error codes, shape, byte size, count, MIME through Fileinfo, random destination names and a consistent per-file response. Browser MIME values are not proof of file safety.

### 10. Error handling may disclose internals

Some handlers return exception/database details. Log detailed errors privately and return sanitized messages with appropriate HTTP status codes.

## Recommended sequence

1. Restrict access and use test data only.
2. Remove administrator bypass and password-return APIs.
3. Add shared PHP session authentication and role authorization.
4. Migrate plaintext passwords to hashes, then remove plaintext columns after verified backup/migration.
5. Add CSRF validation to all state-changing requests.
6. Replace caller-controlled paths with opaque record/file IDs and private storage.
7. Replace unsafe HTML insertion for user-controlled values.
8. Add audit logging for patient access, changes, archive actions, and downloads.
9. Add authorization/upload/CSRF/XSS regression tests.
10. Review applicable privacy, legal, security, and clinical requirements before real-world use.

## Verification required

Test direct endpoint access without a session, cross-role access, horizontal record access, session fixation, CSRF, malicious uploads, path traversal, stored XSS, backup/recovery and audit completeness.
