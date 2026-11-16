# 🔌 PHP endpoint reference

ATOM Hospital uses POST-driven PHP scripts. The endpoints are not a versioned REST API and the parameters currently vary by role.

## Endpoint inventory

| Endpoint | Actions observed in source | Data store |
|---|---|---|
| bridge/login.php | credential lookup and role redirect response | users.db |
| bridge/cheker.php | credential/hash marker lookup | users.db |
| admin/admin.php | users, passwords, assistants, patients, archive, charts | users.db, clinic.db, archive.db |
| bridge/receptionist.php | patient CRUD, doctors, archive, search | clinic.db, archive.db, users.db |
| bridge/patient.php | patient list, assigned patients, assignment, archive, test orders | clinic.db, archive.db, laboratory.db |
| bridge/nurse.php | patient list/search, medical test actions | clinic.db, laboratory.db |
| bridge/assistpatient.php | assistant/doctor mapping, patient actions and test orders | clinic.db, laboratory.db |
| bridge/laboratory.php | laboratory patient/order operations | laboratory.db and patient data |
| medical/upload.php | multi-file upload | caller-selected filesystem path |
| medical/medical.php | directory listing | caller-selected filesystem path |

## Contract limitations

The API is not centrally documented in OpenAPI and no uniform JSON envelope, HTTP status convention, authentication middleware, or request schema is present. Different front-end callers parse both JSON strings and objects.

## Security requirements for every endpoint

- Require server-side authentication.
- Enforce role and resource ownership/assignment server-side.
- Ignore client-provided identity/role values.
- Validate HTTP method and content type.
- Validate required fields, type, range and length.
- Use prepared statements.
- Validate CSRF token on state-changing requests.
- Return a consistent JSON response and appropriate HTTP status.
- Do not return passwords, hashes, database errors, or internal paths.

## Example target response contract

~~~json
{
  "success": true,
  "data": {},
  "error": null
}
~~~

This is a proposed consistency target, not the current response format.
