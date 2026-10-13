# PHP endpoints

The project exposes form and JSON-style endpoints rather than a versioned REST API.

| Endpoint | Main responsibility |
| --- | --- |
| `bridge/login.php` | Looks up a user and returns role routing data |
| `admin/admin.php` | User, assistant, patient, archive, and chart actions |
| `bridge/patient.php` | Patient retrieval, assignment, archiving, and test ordering |
| `bridge/receptionist.php` | Reception workflow and patient operations |
| `bridge/nurse.php` | Nurse patient workflow |
| `bridge/laboratory.php` | Laboratory order and result workflow |
| `bridge/assistpatient.php` | Doctor-assistant workflow |
| `bridge/cheker.php` | Additional account/status checks |
| `medical/upload.php` | Multi-file upload handling |

Inputs are currently sent as POST fields. Treat all fields, IDs, role names, paths, and upload metadata as untrusted. Endpoint authorization must not depend on `localStorage` values.
