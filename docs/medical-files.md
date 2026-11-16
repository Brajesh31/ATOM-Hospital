# 🗂️ Medical file lifecycle and upload risks

## Current implementation

The medical page reads a path from its URL query and asks medical/medical.php to list files under that path. The upload browser code sends a caller-provided path to medical/upload.php. That PHP endpoint creates the requested directory and moves uploaded files there.

~~~mermaid
flowchart TD
  A[Role workspace] --> B[Open medical page with path query]
  B --> C[medical.js reads path]
  C --> D[POST path to medical.php]
  D --> E[scandir caller-provided directory]
  E --> F[Return filenames]
  B --> G[User selects file]
  G --> H[POST file and path to upload.php]
  H --> I[Validate extension/MIME/size]
  I --> J[Write into caller-selected folder]
~~~

## Security issues

- File path comes from the browser.
- Directory creation is based on that value.
- Directory listing uses a supplied path.
- Raw names may be inserted into HTML.
- File access is not consistently tied to a server-verified user and patient.
- Uploads may be placed in a web-accessible directory.
- The upload response array is overwritten instead of consistently accumulating per-file outcomes.

## Safer target design

1. Browser sends an opaque patient/record ID, not a path.
2. Server authenticates and checks role plus patient assignment.
3. Server resolves the authorized record and fixed private storage root.
4. Validate upload error, count, size, Fileinfo MIME and extension/content pairing.
5. Use random server-generated filenames.
6. Store content outside the public document root.
7. Store metadata: safe display name, storage key, MIME, size, uploader, timestamp and record relation.
8. Download endpoint repeats authorization checks and streams the file with safe headers.
9. Audit access without logging medical content.

Use synthetic documents only during development.
