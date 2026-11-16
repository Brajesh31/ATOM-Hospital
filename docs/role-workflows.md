# 👥 Role workflows

These workflows describe the current code paths, not a secure access-control model.

## Login and landing page

~~~mermaid
flowchart TD
  A[Login form in index.html] --> B[POST credentials to bridge/login.php]
  B --> C[Query users.db]
  C --> D{Credentials/status accepted?}
  D -->|No| E[Display failure state]
  D -->|Yes| F[Return role and redirect path]
  F --> G[Browser stores response in localStorage]
  G --> H[Redirect to role workspace]
~~~

**Security note:** the current flow sends password-derived fields to the browser and uses localStorage. Replace this with server-side sessions before real use.

## Receptionist

1. Register a patient.
2. Load active patient list.
3. Search by name, room or doctor where supported.
4. Update demographics/assignment.
5. Delete or archive a record.
6. Review archive outcomes.

The bridge/receptionist.php endpoint performs database mutations. These operations require server-side role and CSRF checks.

## Doctor

1. Load the patient list.
2. Claim/assign a patient.
3. Load the doctor's assigned patients.
4. View available medical-test records.
5. Order laboratory tests.
6. Archive an assigned patient.

The current code often accepts doctor name as a POST value. The server must derive doctor identity from the authenticated session instead.

## Doctor assistant

1. Resolve the assistant-to-doctor relationship.
2. Load the associated doctor's patients.
3. Create test orders.
4. View medical records.
5. Archive a patient through the assistant workflow.

The association must be checked on the server for every resource action, not trusted from the browser.

## Nurse

1. Load patients.
2. Search patient names.
3. View medical test status.
4. Submit test workflow actions where supported.

The current endpoint accepts patient IDs and returns record-related data. Enforce assignment and role permissions server-side.

## Laboratory

1. Load outstanding medical test orders.
2. Open the associated record.
3. Upload result files.
4. Mark work finished.

The upload and file listing APIs currently accept client-controlled paths. Replace those with server-side record lookup and private storage.

## Administrator

1. Review user list and roles.
2. Add, update or delete user records.
3. Manage doctor-assistant relationships.
4. Review patients, archives and summary charts.

The current admin handler has a password-return action. This must be removed, not merely hidden in the UI.
