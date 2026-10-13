# Development

## Conventions

Keep role-specific browser logic in `js/`, styles in `style/`, PHP request handlers in `bridge/`, and dashboard markup in `workspace/` or `admin/`. Preserve parameterized SQLite statements and improve validation rather than trusting browser input.

## Local checks

Exercise login for each role, user CRUD, patient registration and assignment, archiving, test ordering, results upload, and logout behavior. Verify both success and failure responses.

There is no package manager, build command, or automated test suite. PHP syntax checks such as `php -l path/to/file.php` are useful when PHP is installed.
