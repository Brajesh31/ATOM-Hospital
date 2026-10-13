# Installation

## Requirements

- PHP with the SQLite3 extension.
- Apache, Nginx with PHP-FPM, or another PHP-capable server.
- A browser with JavaScript enabled.

## Setup

1. Copy or clone the repository into the web server’s document root.
2. Confirm PHP can read the `database/` files.
3. Grant the PHP process only the minimum write access needed for SQLite journals and controlled uploads.
4. Browse to `/ATOM-Hospital/index.html`.

Do not serve the project through a static-only server: the role workflows require PHP endpoints. Use disposable databases and test accounts locally.
