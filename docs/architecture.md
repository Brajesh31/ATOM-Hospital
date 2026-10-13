# Architecture

ATOM Hospital is a static browser interface backed by small PHP bridge endpoints and several SQLite databases.

## Request flow

`index.html` collects credentials and posts them with jQuery to `bridge/login.php`. The endpoint reads `database/users.db`, returns role information, and the browser stores the response (minus one password field) in `localStorage` before redirecting to a workspace page.

Role pages in `workspace/`, `admin/`, and `medical/` call PHP endpoints in `bridge/` or `medical/`. Those endpoints open the relevant SQLite files, execute queries, and return JSON or text responses.

## Boundaries

- Browser UI: HTML, CSS, JavaScript, jQuery.
- PHP bridge: database queries, role operations, uploads, and JSON responses.
- SQLite: users/assistants, patients, laboratory orders, and archive records.
- Filesystem: uploaded medical documents and image assets.

The current browser token is not a security boundary. Server-side authentication and authorization must be added before trusting any role-sensitive request.
