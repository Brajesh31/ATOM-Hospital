# 🏥 Project overview

## Purpose

ATOM Hospital is a browser-based hospital-management prototype using HTML, CSS, JavaScript, PHP, and SQLite. It contains separate screens and request handlers for hospital staff workflows.

## Current roles visible in the code

| Role | Workspace | Observed workflow |
|---|---|---|
| Admin | admin/admin.html | User/assistant management, patient/archive views, charts |
| Doctor | workspace/patient.html | Browse/claim patients, view assigned records, order tests, archive |
| Doctor assistant | workspace/assistpatient.html | Work with a doctor's patients, request tests, view medical records |
| Nurse | workspace/nurse.html | Patient list/search and medical test workflow |
| Receptionist | workspace/receptionist.html | Register, search, update, delete and archive patients |
| Laboratory | workspace/laboratory.html | View test orders, upload results, finish work |

Role labels and workflow descriptions are derived from the checked-in page and PHP handler names. They do not imply secure access enforcement.

## Technology

- Browser UI: HTML, CSS, vanilla JavaScript and jQuery.
- Backend: PHP scripts invoked through POST requests.
- Persistence: four SQLite database files.
- Browser libraries: jQuery, SweetAlert2, Chart.js, Font Awesome and related CDN assets.
- Filesystem: branding images and medical upload destinations.

There is no Composer package manifest, Node package manifest, single build pipeline, or framework entrypoint in the inspected repository.

## Current capabilities

The code contains workflows for user administration, role-based page routing, patient registration and assignment, patient archive operations, laboratory test orders, file upload/list display, and dashboard charts.

## Important boundary

This is a prototype and must not be used with real patient or staff data. It has plaintext-password paths, an insecure hardcoded admin fallback, a client-controlled localStorage token, missing server-side role checks on multiple handlers, unsafe caller-selected file paths, and output escaping gaps. See security-review.md.

## Intended users

The repository is suitable for developers studying a PHP/SQLite workflow prototype, students exploring role-based dashboard structure, and contributors improving the security and maintainability of a small legacy application.

## Out of scope

The repository does not establish clinical validity, regulatory compliance, production-grade security, audit readiness, multi-site tenancy, electronic prescribing, interoperability, or integration with external hospital systems.
