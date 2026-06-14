# Todo RESTful API

RESTful API sederhana untuk mengelola data Todo yang dibuat sebagai media pembelajaran mengenai:

- RESTful API
- OpenAPI Specification (Swagger)
- Authentication menggunakan Bearer Token
- Request & Response Validation
- HTTP Status Code
- API Documentation

API ini menyediakan fitur dasar seperti registrasi user, login, logout, mendapatkan data user yang sedang login, serta pengelolaan data todo.

## HTTP Status Code

- **200 OK** — Request berhasil.
- **201 Created** — Resource berhasil dibuat.
- **400 Bad Request** — Request tidak valid atau validasi gagal.
- **401 Unauthorized** — Token tidak ada atau tidak valid.
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
