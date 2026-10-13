# Troubleshooting

## PHP endpoints return source or 404

Use Apache, PHP’s built-in server, or PHP-FPM. Do not open the HTML file with `file://`; relative PHP requests will not work.

## SQLite errors

Check that the PHP SQLite3 extension is enabled and that the web-server process can read and write the database directory as required. Inspect sanitized server logs for the failing endpoint.

## Login redirects incorrectly

Inspect the JSON response from `bridge/login.php` and confirm the user role matches a known workspace. Clear the browser’s `localStorage` during local testing.

## Upload fails

Check the target directory, server upload limits, MIME detection support, and file permissions. Never solve an upload failure by granting broad write access to the web root.
