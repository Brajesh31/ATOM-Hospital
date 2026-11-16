# 👥 Role workflows

These workflows describe current code paths, not a secure access-control model.

## Login and landing page

~~~mermaid
flowchart TD
  A[Login form] --> B[POST credentials to bridge/login.php]
  B --> C[Query users.db]
  C --> D{Credentials/status accepted?}
  D -->|No| E[Display failure]
  D -->|Yes| F[Return role and redirect path]
  F --> G[Browser stores response in localStorage]
  G --> H[Redirect to role workspace]
~~~

**Security note:** current flow sends password-derived fields to the browser and uses localStorage. Replace this with server-side sessions before real use.

## Receptionist

Register patients, load active records, search, update assignment/demographics, then delete or archive where needed. The bridge handler performs mutations. Enforce role and CSRF controls server-side.

## Doctor

Load patients, claim/assign a patient, view assigned patients, order tests, view medical records, and archive assigned patients. The server must derive doctor identity from the authenticated session instead of accepting doctor name from POST.

## Doctor assistant

Resolve assistant-to-doctor mapping, load associated patients, create test orders, view medical records and archive through the assistant workflow. Check association server-side on every resource action.

## Nurse

Load and search patients, view test status and submit supported test workflow actions. Enforce patient access permissions server-side.

## Laboratory

Load outstanding orders, open the linked record, upload results, and mark work finished. Current upload/file listing accepts caller-controlled paths and must be redesigned.

## Administrator

Review users, add/update/delete users, manage assistant mappings, review patient/archive lists and charts. The current admin handler has a password-return action; remove it, not merely hide the UI.
