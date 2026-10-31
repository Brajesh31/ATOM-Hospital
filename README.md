# ATOM Hospital

ATOM Hospital is a browser-based hospital-management prototype built with HTML, CSS, JavaScript, PHP, and SQLite. It provides separate workspaces for administrators, doctors, doctor assistants, nurses, receptionists, and laboratory staff.

> **Prototype warning:** This application is not production-ready. The current authentication flow exposes plaintext credentials, includes a hardcoded `admin`/`admin` fallback, stores a client-side token in `localStorage`, and does not consistently enforce server-side authorization. Do not use real patient or staff data.

## Features

- Role-based login redirection for hospital staff.
- Admin user and assistant management.
- Patient registration, search, assignment, and archiving.
- Laboratory test ordering and result upload.
- Separate dashboards for clinical, reception, laboratory, and administration workflows.
- SQLite databases for users, clinic records, laboratory orders, and archived patients.
- Chart.js, jQuery, SweetAlert2, Font Awesome, and local browser assets.

## Technology

- Frontend: HTML, CSS, vanilla JavaScript, and jQuery.
- Backend: PHP endpoints served by Apache or another PHP-capable web server.
- Persistence: SQLite3.
- No package manager or unified build system is present.

## Local installation

1. Install PHP with the SQLite3 extension and a web server such as XAMPP, WAMP, or LAMP.
2. Clone or copy the repository into the server document root.
3. Ensure the `database/` directory and upload destinations are writable by the web-server process.
4. Open `http://localhost/ATOM-Hospital/index.html`.
5. Use disposable local accounts and sample data only.

The PHP endpoints create some tables when they first run, but the committed SQLite files are part of the project’s current data layout. Back them up before testing destructive operations.

## Repository layout

```text
admin/       Administration UI, CRUD endpoints, and charts
bridge/      PHP endpoints connecting browser workflows to SQLite
database/    users, clinic, laboratory, and archive SQLite files
js/          Role-specific browser logic
logo/        Branding and favicon assets
medical/     Medical records and upload endpoints
style/       Stylesheets for workspaces
workspace/   Role-specific HTML dashboards
index.html   Login page and client-side redirect flow
```

## Roles

The current routing maps Admin, Doctor, Doctor assistant, Nurse, Receptionist, and Laboratory users to different workspace pages. This is a UI convention, not a sufficient authorization boundary; every sensitive operation needs server-side identity and permission checks before production use.

## Development

Serve the repository through PHP rather than opening the HTML files directly, because the browser calls relative PHP endpoints. Manually test login, each role’s dashboard, patient assignment, archiving, laboratory ordering, upload validation, and admin CRUD actions. There is no automated test suite or lint configuration currently.

See [docs/architecture.md](docs/architecture.md), [docs/api.md](docs/api.md), and [docs/database.md](docs/database.md) for implementation details.

## Security

Do not commit credentials, production SQLite databases, patient data, or uploaded medical files. Before deployment, replace plaintext passwords with one-way hashes, remove the hardcoded admin fallback, use secure server-side sessions, add CSRF and authorization controls, validate upload paths and file contents, and keep medical files outside the public web root. See [SECURITY.md](SECURITY.md).

## Contributing and license

Read [CONTRIBUTING.md](CONTRIBUTING.md) and [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) before proposing changes. ATOM Hospital is released under the MIT License; see [LICENSE](LICENSE).

Last updated: 2026-10-31 05:01 UTC
