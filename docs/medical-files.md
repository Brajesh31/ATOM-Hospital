# 🗂️ Medical file lifecycle and upload risks

## Current implementation path

The medical page reads a path from its URL query and asks medical/medical.php to list files under that path. The upload browser code sends a caller-provided path to medical/upload.php. The PHP upload endpoint creates the requested directory and moves uploaded files there.

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

- File path is supplied by the browser.
- Directory creation is performed from the supplied value.
- Directory listing is based on a supplied path.
- Raw file names may become HTML in UI code.
- File access is not consistently tied to a server-verified user and patient.
- Uploaded medical content may be placed in a web-accessible directory.
- Upload response handling overwrites earlier per-file responses.

## Safer target design

1. Browser sends an opaque patient/medical-record ID, not a directory path.
2. Server authenticates user and checks role plus patient assignment.
3. Server resolves the authorized record and fixed private storage root.
4. Server validates upload error, file count, byte size, MIME via Fileinfo, and allowed extension/content pairing.
5. Server uses random server-generated filenames.
6. Server stores content outside public document root.
7. Metadata records the original safe display name, storage key, MIME, size, uploader, timestamps and patient relation.
8. Download endpoint repeats authorization checks and streams the file with safe headers.
9. Access and changes are audited without logging medical content.

## Handling guidance

Do not place real clinical files in this repository or its public assets. Use disposable synthetic files for development. A file extension or browser MIME value is not proof that a file is safe.
