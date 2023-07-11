# API Overview

Base URL: `/api`

## Auth
- `POST /auth/login`
- `POST /auth/logout`
- `GET /auth/me`

## Module Pattern
For each module resource (`/flights`, `/aircraft`, ...):
- `GET /` list/search
- `POST /` create
- `GET /{id}` show
- `PUT /{id}` update
- `DELETE /{id}` soft-delete/archive path
- `POST /{id}/activate`
- `POST /{id}/deactivate`
- `POST /{id}/archive`
- `POST /bulk`
- `GET /statistics`
- `GET /export`
- `GET /{id}/timeline`
- `POST /{id}/clone`
- `POST /{id}/sync-external`
