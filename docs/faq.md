# FAQ

## Is this a clinical system?

No. It is an educational prototype and must not process real patient information.

## Which database is used?

SQLite, split across user, clinic, laboratory, and archive database files.

## Does login provide secure authentication?

No. The current flow is client-directed and retains insecure credential behavior. See [SECURITY.md](../SECURITY.md).

## How do I run it?

Serve it from a PHP-enabled web server and open `index.html` through that server.

## Where are medical files stored?

The upload endpoint accepts a caller-provided path. This is unsafe for production; isolate and authorize upload destinations before deployment.
