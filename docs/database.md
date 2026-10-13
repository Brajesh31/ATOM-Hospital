# Database

The repository contains four SQLite databases:

- `users.db`: users, roles, statuses, and doctor-assistant relationships.
- `clinic.db`: active patient records and assignment data.
- `laboratory.db`: medical test orders and result paths.
- `archive.db`: archived patient records and end states.

Some PHP files create tables with `CREATE TABLE IF NOT EXISTS`. The schema is therefore partly encoded in the endpoints and partly represented by the committed database files.

## Operational guidance

Back up databases before testing delete/archive actions. Use transactions for multi-database moves, add indexes and constraints deliberately, and avoid storing plaintext passwords or unnecessary medical information.
