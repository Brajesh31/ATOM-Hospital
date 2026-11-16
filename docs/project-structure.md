# 🗂️ Repository structure

This is the current source layout, excluding generated build artifacts.

~~~text
ATOM-Hospital/
├── admin/
│   ├── admin.html
│   ├── admin.js
│   ├── admin.php
│   ├── chart.js
│   └── style.css
├── bridge/
│   ├── assistpatient.php
│   ├── cheker.php
│   ├── laboratory.php
│   ├── login.php
│   ├── nurse.php
│   ├── patient.php
│   └── receptionist.php
├── database/
│   ├── archive.db
│   ├── clinic.db
│   ├── laboratory.db
│   └── users.db
├── docs/
│   ├── ADRs/
│   ├── diagrams/
│   └── technical, workflow and security guides
├── js/
│   ├── assistpatient.js
│   ├── laboratory.js
│   ├── logout.js
│   ├── medical.js
│   ├── nurse.js
│   ├── password.js
│   ├── patient.js
│   └── receptionist.js
├── logo/
├── medical/
│   ├── medical.html
│   ├── medical.php
│   └── upload.php
├── style/
├── workspace/
├── index.html
├── README.md
├── SECURITY.md
└── LICENSE
~~~

## Directory responsibilities

| Directory | Responsibility |
|---|---|
| admin/ | Administrator interface, user CRUD, chart UI and PHP handler |
| bridge/ | PHP POST endpoints used by role-specific JavaScript |
| database/ | SQLite database files for accounts, clinic, laboratory and archive |
| docs/ | Architecture, role workflows, API, database, security and operations guides |
| js/ | Browser-side interaction logic for staff roles and medical features |
| logo/ | Branding images and favicons |
| medical/ | Medical record file list and upload UI/endpoints |
| style/ | Stylesheets for role workspaces |
| workspace/ | Admin/doctor/assistant/nurse/reception/laboratory HTML screens |

## Data handling warning

The SQLite files are binary databases, not source fixtures. Treat their contents as potentially sensitive. Do not commit production database copies or patient records. The repository should eventually move data files to deployment-managed storage and retain only schema/migration scripts and synthetic fixtures.
