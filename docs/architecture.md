# 🏗️ Architecture

## System summary

ATOM Hospital is a browser interface backed by PHP request handlers and SQLite. The application is organized by role/workflow rather than a framework or service layer.

## Runtime components

| Layer | Files | Responsibility |
|---|---|---|
| Login UI | index.html | Collect credentials and initiate login request |
| Role pages | workspace/, admin/, medical/ | Render role-specific workflows |
| Browser behavior | js/, admin/admin.js | AJAX requests, table rendering, dialogs and navigation |
| PHP handlers | bridge/, admin/admin.php, medical/ | Request handling and SQL/filesystem operations |
| Persistence | database/*.db | Users, clinic, laboratory and archive records |
| Static assets | style/, logo/ | CSS and images/icons |

## Request flow

~~~mermaid
flowchart TD
  Browser["Browser HTML/JavaScript"] -->|POST| PHP["PHP endpoint"]
  PHP -->|Prepared SQL / reads| SQLite["SQLite databases"]
  PHP -->|File operations| FS["Filesystem uploads"]
  SQLite --> PHP
  FS --> PHP
  PHP -->|JSON/text response| Browser
~~~

## Current login flow

~~~mermaid
sequenceDiagram
  participant User
  participant Login as index.html
  participant PHP as bridge/login.php
  participant DB as users.db
  User->>Login: Enter username and password
  Login->>PHP: POST credentials
  PHP->>DB: Query account
  DB-->>PHP: Account record
  PHP-->>Login: Role and credential-derived fields
  Note over Login: Current code stores response in localStorage
  Login->>User: Redirect to selected workspace
~~~

This describes existing code, not a recommended authentication design. See security-review.md.

## Data stores

- users.db is opened by account/admin/login handlers.
- clinic.db stores active patient records.
- laboratory.db stores medical test workflow metadata.
- archive.db stores closed/archived patient records.

Schema definitions are scattered across PHP endpoints. Some database files are checked into the repository. This creates schema drift and sensitive-data risks.

## Design risks

- No shared authentication bootstrap.
- No consistent endpoint authorization.
- Client-controlled identity values.
- Client-controlled medical paths.
- No consistent response/error envelope.
- Some dynamic HTML construction with untrusted values.
- Multiple database files involved in related workflows without a unified transaction boundary.

## Safer target architecture

A future security refactor should introduce a shared PHP bootstrap for session identity, role checks, CSRF validation, JSON responses, and database configuration. Every endpoint should use this layer. File storage should be private and record-based. Role-specific screens should be presentation, not the permission boundary.
