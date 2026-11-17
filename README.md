# 🏥 ATOM Hospital

<div align="center">
  <img src="logo/Untitled_design-removebg-preview.png" alt="ATOM Hospital logo" width="170" />
  <h1>ATOM Hospital Management Prototype</h1>
  <p><strong>Role-oriented hospital workflow prototype built with PHP, JavaScript, HTML, CSS and SQLite.</strong></p>
  <p>Patient registration · Staff workspaces · Laboratory workflow · Medical file handling</p>
  <p>
    <a href="https://github.com/Brajesh31/ATOM-Hospital/actions/workflows/ci.yml"><img src="https://github.com/Brajesh31/ATOM-Hospital/actions/workflows/ci.yml/badge.svg?branch=main" alt="PHP and JavaScript CI" /></a>
    <a href="https://github.com/Brajesh31/ATOM-Hospital/actions/workflows/documentation.yml"><img src="https://github.com/Brajesh31/ATOM-Hospital/actions/workflows/documentation.yml/badge.svg?branch=main" alt="Documentation validation" /></a>
    <a href="https://github.com/Brajesh31/ATOM-Hospital/actions/workflows/security.yml"><img src="https://github.com/Brajesh31/ATOM-Hospital/actions/workflows/security.yml/badge.svg?branch=main" alt="Security hygiene" /></a>
    <img src="https://img.shields.io/badge/PHP-Backend-777BB4?logo=php&logoColor=white" alt="PHP" />
    <img src="https://img.shields.io/badge/JavaScript-Frontend-F7DF1E?logo=javascript&logoColor=black" alt="JavaScript" />
    <img src="https://img.shields.io/badge/SQLite-Database-003B57?logo=sqlite&logoColor=white" alt="SQLite" />
    <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-2ea44f.svg" alt="MIT License" /></a>
    <a href="https://github.com/Brajesh31/ATOM-Hospital/commits/main"><img src="https://img.shields.io/github/last-commit/Brajesh31/ATOM-Hospital?logo=github" alt="Last commit" /></a>
    <a href="https://github.com/Brajesh31/ATOM-Hospital/stargazers"><img src="https://img.shields.io/github/stars/Brajesh31/ATOM-Hospital?logo=github" alt="GitHub stars" /></a>
  </p>
</div>

---

> [!CAUTION]
> **Not safe for real patient data or production use.** The inspected source contains plaintext-password behavior, a hardcoded administrator fallback, password material returned to the browser, missing server-side authorization in multiple endpoints, caller-controlled medical file paths, and potentially unsafe HTML rendering. This repository is an educational prototype, not a validated clinical information system. Use synthetic records only. Do not use it for clinical decisions.

## 🧭 Contents

- [Overview](#-overview)
- [Roles and workflows](#-roles-and-workflows)
- [Technology and architecture](#-technology-and-architecture)
- [Features](#-features)
- [Project structure](#-project-structure)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Data and database](#-data-and-database)
- [Security status](#-security-status)
- [Testing and CI](#-testing-and-ci)
- [Documentation](#-documentation)
- [Roadmap](#-roadmap)
- [Contributing and license](#-contributing-and-license)

## 🌍 Overview

ATOM Hospital is a browser-based hospital-management prototype. Its interface is built from HTML, CSS and JavaScript/jQuery. PHP scripts receive POST requests and interact with SQLite databases. Separate pages are provided for administrator, doctor, doctor assistant, nurse, receptionist and laboratory roles.

The repository is a legacy, multi-page application rather than a modern framework application. There is no Composer or npm manifest, no unified build command, and no centralized API contract.

## 👥 Roles and workflows

| Role | Workspace | Main workflow present in source |
|---|---|---|
| Administrator | admin/admin.html | Manage users and assistants, review patients/archive and charts |
| Doctor | workspace/patient.html | View/claim assigned patients, order tests, review records, archive |
| Doctor assistant | workspace/assistpatient.html | Work with associated doctor's patients and medical tests |
| Nurse | workspace/nurse.html | View/search patients and support test workflow |
| Receptionist | workspace/receptionist.html | Register, search, update, delete and archive patients |
| Laboratory | workspace/laboratory.html | View test orders, upload results and complete work |

These are UI/workflow distinctions. They are not proof that the backend consistently enforces those role boundaries.

## ✨ Features visible in the current source

- Staff login and role-based page routing.
- Admin user and doctor-assistant management.
- Patient registration, listing, searching and assignment.
- Patient archive actions and archive summaries.
- Laboratory test orders and result file uploads.
- Separate role-oriented workspaces.
- SQLite persistence split into user, clinic, laboratory and archive files.
- Dashboard summary/chart logic.
- Medical file listing and upload UI.

## 🧱 Technology and architecture

| Layer | Technologies |
|---|---|
| Frontend | HTML5, CSS, JavaScript, jQuery |
| Backend | PHP |
| Database | SQLite3 |
| UI utilities | SweetAlert2, Chart.js, Font Awesome |
| Web server | Apache / Nginx with PHP-FPM / PHP development server |
| File storage | Filesystem paths used by medical upload workflow |

### Current request flow

~~~mermaid
flowchart TD
  Browser["Browser: HTML / CSS / JavaScript"] -->|POST| PHP["PHP endpoints"]
  PHP --> Users["users.db"]
  PHP --> Clinic["clinic.db"]
  PHP --> Lab["laboratory.db"]
  PHP --> Archive["archive.db"]
  PHP --> Files["Medical file storage"]
  PHP -->|JSON or text response| Browser
~~~

### Current login flow and security boundary

~~~mermaid
sequenceDiagram
  participant U as User
  participant B as Browser
  participant L as bridge/login.php
  participant DB as users.db
  U->>B: Submit credentials
  B->>L: POST username/password
  L->>DB: Query account
  DB-->>L: Account and role
  L-->>B: Role and credential-derived fields
  Note over B: Current code stores response in localStorage
  B->>U: Redirect to workspace
~~~

The diagram describes existing behavior, not an approved authentication architecture. A browser-controlled token is not an authorization boundary.

## 🗂️ Project structure

~~~text
ATOM-Hospital/
├── admin/             # Admin UI, JS, PHP and charts
├── bridge/            # Role/workflow PHP endpoints
├── database/          # SQLite files: users, clinic, laboratory, archive
├── docs/               # Technical and security documentation
│   ├── ADRs/
│   └── diagrams/
├── js/                 # Browser workflow scripts
├── logo/               # Logo and favicon assets
├── medical/            # Medical file UI and upload/list endpoints
├── style/              # Role-specific CSS
├── workspace/          # Role-specific dashboard pages
├── .github/workflows/  # Syntax, documentation and security hygiene CI
└── index.html          # Login page
~~~

See [project structure](docs/project-structure.md).

## 🧰 Requirements

- PHP with SQLite3 and Fileinfo extensions.
- Apache, Nginx with PHP-FPM, or PHP's built-in development server.
- A modern browser with JavaScript enabled.
- Git.
- Writable local SQLite storage for development.

## 🚀 Installation

### Clone

~~~bash
git clone https://github.com/Brajesh31/ATOM-Hospital.git
cd ATOM-Hospital
~~~

### Verify PHP extensions

~~~bash
php -v
php -m
~~~

Confirm SQLite3 and fileinfo are enabled.

### Start a local development server

~~~bash
php -S 127.0.0.1:8000
~~~

Open `http://127.0.0.1:8000/index.html`.

This is development-only. Do not expose PHP's built-in server to a public network. Use synthetic records and an isolated copy of the SQLite files.

See the detailed [installation guide](docs/installation.md).

## 🗄️ Data and database

| Database | Observed responsibility |
|---|---|
| database/users.db | User accounts, roles/status and doctor-assistant relationships |
| database/clinic.db | Active patient details and assignments |
| database/laboratory.db | Medical test orders and file references |
| database/archive.db | Archived patient records and outcomes |

Some table definitions are created by PHP endpoints at runtime. The schema is not managed through a formal migration system. Do not assume the checked-in databases are clean fixtures.

Read [database documentation](docs/database.md) and [backup/recovery](docs/backup-recovery.md).

## 🔐 Security status

**Not production ready.** The checked-in source has serious issues:

- `bridge/login.php` compares plaintext password values, returns password-derived data, and contains a hardcoded `admin`/`admin` fallback.
- `bridge/cheker.php` accepts a password-hash marker as a client-provided credential and contains an administrator marker.
- `admin/admin.php` exposes a `get_password` action.
- Several endpoints accept user names/IDs/roles from POST data rather than deriving identity from a trusted server-side session.
- `medical/upload.php` accepts a client-supplied directory path and creates/writes to it.
- `medical/medical.php` accepts a directory path and calls `scandir`.
- Several frontend scripts interpolate database values into HTML strings.

**Do not use real patient information.** Documentation and passing syntax tests do not fix these security defects. See the [security review](docs/security-review.md).

## ✅ Testing and CI

GitHub Actions checks PHP syntax, JavaScript syntax, local Markdown links/images/fences, and repository secret hygiene. These checks verify syntax and repository hygiene only. There is not yet a complete automated integration, authorization, privacy, or clinical workflow test suite.

Local syntax checks:

~~~bash
find . -name '*.php' -not -path './.git/*' -print0 | xargs -0 -n1 php -l
find js admin -name '*.js' -print0 | xargs -0 -n1 node --check
~~~

## 📚 Documentation

| Guide | Coverage |
|---|---|
| [Documentation index](docs/README.md) | Full guide navigation |
| [Project overview](docs/project-overview.md) | Scope, roles, audience and limitations |
| [Architecture](docs/architecture.md) | Runtime components and request flow |
| [Role workflows](docs/role-workflows.md) | Admin, doctor, nurse, assistant, reception and lab |
| [API reference](docs/api.md) | PHP endpoint and action inventory |
| [Database](docs/database.md) | SQLite files and inferred schemas |
| [Security review](docs/security-review.md) | Concrete findings and remediation sequence |
| [Privacy](docs/privacy.md) | Sensitive data handling and retention |
| [Medical files](docs/medical-files.md) | Upload and download risks |
| [Backup and recovery](docs/backup-recovery.md) | SQLite backup and restoration |
| [Installation](docs/installation.md) | Local PHP setup |
| [Development](docs/development.md) | Engineering workflow |
| [Testing](docs/testing.md) | Syntax, regression and security test strategy |
| [Deployment](docs/deployment.md) | Production readiness requirements |
| [Troubleshooting](docs/troubleshooting.md) | Common setup and runtime problems |
| [Limitations](docs/limitations.md) | Unsupported and unsafe areas |
| [Glossary](docs/glossary.md) | Project terms |
| [Diagrams](docs/diagrams/README.md) | Editable Mermaid source |

## 🗺️ Roadmap

1. Remove hardcoded administrator access and password-return functionality.
2. Implement server-side sessions and endpoint-level role/resource authorization.
3. Migrate to hash-only password storage and remove plaintext credentials.
4. Replace client-supplied file paths with private record-based storage.
5. Add CSRF defenses and safe output rendering.
6. Add synthetic database fixtures and integration/security tests.
7. Add schema migrations, audit logs and backup/restore automation.
8. Conduct independent privacy, security and clinical-safety reviews before any real-world use.

See [ROADMAP.md](ROADMAP.md).

## 🤝 Contributing and license

Read [CONTRIBUTING.md](CONTRIBUTING.md), [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md), and [SECURITY.md](SECURITY.md) before proposing changes. Do not include real patient data in pull requests, logs, screenshots, fixtures or documentation.

Distributed under the [MIT License](LICENSE). Maintained by **Brajesh Kumar**, [@Brajesh31](https://github.com/Brajesh31).

Last updated: 2026-11-17 03:36 UTC
