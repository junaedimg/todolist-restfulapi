# Todo RESTful API

RESTful API sederhana untuk mengelola data Todo yang dibuat sebagai media pembelajaran mengenai:

- RESTful API
- OpenAPI Specification (Swagger)
- Authentication menggunakan Bearer Token
- Request & Response Validation
- HTTP Status Code
- API Documentation

API ini menyediakan fitur dasar seperti registrasi user, login, logout, mendapatkan data user yang sedang login, serta pembaruan profil user.

## Tech Stack

- **Backend:** Laravel 13
- **Database:** MySQL / PostgreSQL / SQLite

## Database

### Users Table

| Column | Type | Constraints |
|---|---|---|
| id | bigIncrements | Primary Key |
| name | string | Required |
| username | string | Unique, Required |
| email | string | Unique, Required |
| email_verified_at | timestamp | Nullable |
| password | string | Required |
| remember_token | string | Nullable |
| created_at | timestamp | |
| updated_at | timestamp | |

### Migrations

- `0001_01_01_000000_create_users_table.php` — Users, password reset tokens, sessions

## API Documentation

Dokumentasi lengkap (request/response schema) tersedia di:

📄 [`docs/user-api.yaml`](docs/user-api.yaml)

Gunakan Swagger Editor atau Redoc untuk visualisasi:

- [Swagger Editor](https://editor.swagger.io/) — Import file `docs/user-api.yaml`
- [Redoc](https://redocly.github.io/redoc/) — Import file `docs/user-api.yaml`

## Endpoints

### User

| Method | Endpoint | Auth | Deskripsi |
|---|---|---|---|
| POST | `/api/register` | No | Registrasi user baru |
| POST | `/api/login` | No | Login (username atau email + password) |
| POST | `/api/users/logout` | Bearer | Logout (hapus token) |
| GET | `/api/users/current` | Bearer | Mendapatkan data user saat ini |
| PATCH | `/api/users/current` | Bearer | Memperbarui profil (name, username, email) |

### Login Flow

Request `{ "login": "...", "password": "..." }`:
- Jika `login` mengandung `@` → cari user by `email`
- Selain itu → cari user by `username`

## HTTP Status Code

- **200 OK** — Request berhasil.
- **201 Created** — Resource berhasil dibuat.
- **400 Bad Request** — Request tidak valid atau validasi gagal.
- **401 Unauthorized** — Token tidak ada/tidak valid, atau login gagal.
- **404 Not Found** — Resource tidak ditemukan.
- **500 Internal Server Error** — Terjadi kesalahan pada server.

## Response Format

### Success Response

```json
{
    "data": {}
}
```

### Error Response

```json
{
    "errors": {}
}
```

### Validation Error Response

```json
{
    "errors": {
        "field": ["error message"]
    }
}
```

### Unauthorized Response

```json
{
    "errors": {
        "message": "Unauthorized"
    }
}
```

## Authentication

```http
Authorization: Bearer <token>
```
