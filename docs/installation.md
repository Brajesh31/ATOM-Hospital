# 📦 Installation and local setup

## Requirements

- PHP runtime with SQLite3 and Fileinfo extensions.
- Apache, Nginx with PHP-FPM, or PHP's built-in development server.
- Modern browser with JavaScript enabled.
- Git.

Check the runtime and extensions:

~~~bash
php -v
php -m
~~~

Confirm SQLite3 and fileinfo appear in the module list.

## Clone

~~~bash
git clone https://github.com/Brajesh31/ATOM-Hospital.git
cd ATOM-Hospital
~~~

## Important database warning

The repository contains SQLite database binaries. Treat them as potentially sensitive. Back them up before local testing, use an isolated disposable copy, and never expose the database directory through a public web server.

## Run locally

From the repository root:

~~~bash
php -S 127.0.0.1:8000
~~~

Open http://127.0.0.1:8000/index.html.

This is development-only. Do not bind PHP's built-in server to a public interface.

## Directory permissions

PHP requires access to SQLite databases and configured upload destinations. Do not grant recursive world-writable permissions. Restrict writes to the minimum directories and user needed for development.

## Smoke test

1. Open the login page.
2. Use a disposable local test database/account.
3. Inspect server logs if a request fails.
4. Check each workspace route.
5. Exercise registration/search/archive/laboratory workflows with synthetic records.
6. Test upload only with harmless synthetic sample files.
7. Do not use real patient information.

There is no Composer package manifest, Node package manifest, framework build step, or automated database migration system in the current repository.
