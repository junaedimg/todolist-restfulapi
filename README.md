# Todo RESTful API

RESTful API sederhana untuk mengelola data Todo. Dibangun menggunakan Laravel sebagai media pembelajaran mengenai:

- RESTful API Design
- OpenAPI Specification (Swagger)
- Authentication menggunakan Bearer Token
- Request & Response Validation
- HTTP Status Code
- API Documentation

## Fitur

### User Management
- Registrasi user baru
- Login dengan username atau email
- Logout (hapus token)
- Lihat profil user saat ini
- Update profil (name, username, email)

### Todo Management
- Buat todo baru
- Lihat daftar todo milik user yang login
- Lihat detail todo
- Update todo (title, description, status selesai)
- Hapus todo

## Tech Stack

- **Backend:** Laravel 13
- **Database:** SQLite (default) / MySQL / PostgreSQL
- **Auth:** Laravel Sanctum (Bearer Token)
- **Frontend:** Bootstrap 5 + Vanilla JS (single-page)

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

### Todos Table

| Column | Type | Constraints |
|---|---|---|
| id | bigIncrements | Primary Key |
| user_id | foreignId | `constrained()->cascadeOnDelete()` |
| title | string | Required |
| description | text | Nullable |
| is_completed | boolean | Default `false` |
| created_at | timestamp | |
| updated_at | timestamp | |

Relasi: Setiap todo dimiliki oleh satu user. Jika user dihapus, semua todo-nya ikut terhapus (`cascadeOnDelete`).

### Migrations

| File | Deskripsi |
|---|---|
| `0001_01_01_000000_create_users_table.php` | Users, password reset tokens, sessions |
| `0001_01_01_000003_create_todos_table.php` | Todos |
| `2026_07_18_134648_create_personal_access_tokens_table.php` | Token Sanctum |

## Frontend (Single-Page UI)

Akses `http://localhost:8000/` di browser untuk UI lengkap:

| Fitur | Cara Pakai |
|---|---|
| Register | Tab Register, isi form, klik Register |
| Login | Tab Login, isi username/email + password |
| Tambah Todo | Isi judul + deskripsi, klik Tambah |
| Selesai / Batal | Centang / uncentang checkbox |
| Edit Todo | Klik ikon ✏, isi prompt |
| Hapus Todo | Klik ikon ✕, konfirmasi |
| Edit Profil | Klik "Edit Profil", ubah data, Simpan |

## API Documentation

Dokumentasi lengkap (request/response schema) tersedia di:

| File | Deskripsi |
|---|---|
| 📄 [`docs/user-api.yaml`](docs/user-api.yaml) | Dokumentasi API User |
| 📄 [`docs/todo-api.yaml`](docs/todo-api.yaml) | Dokumentasi API Todo |
| 📄 [`docs/user-api.json`](docs/user-api.json) | User API (JSON) |
| 📄 [`docs/todolist-api.json`](docs/todolist-api.json) | Todo API (JSON) |

Gunakan Swagger Editor atau Redoc untuk visualisasi:

- [Swagger Editor](https://editor.swagger.io/) — Import file `.yaml` atau `.json`
- [Redoc](https://redocly.github.io/redoc/) — Import file `.yaml` atau `.json`

## Endpoints

### User

| Method | Endpoint | Auth | Deskripsi |
|---|---|---|---|
| POST | `/api/register` | No | Registrasi user baru |
| POST | `/api/login` | No | Login (username atau email + password) |
| POST | `/api/users/logout` | Bearer | Logout (hapus token) |
| GET | `/api/users/current` | Bearer | Mendapatkan data user saat ini |
| PATCH | `/api/users/current` | Bearer | Memperbarui profil (name, username, email) |

### Todo

| Method | Endpoint | Auth | Deskripsi |
|---|---|---|---|
| GET | `/api/todos` | Bearer | Mendapatkan daftar todo milik user |
| POST | `/api/todos` | Bearer | Membuat todo baru |
| GET | `/api/todos/{id}` | Bearer | Mendapatkan detail todo |
| PATCH | `/api/todos/{id}` | Bearer | Memperbarui todo (title, description, is_completed) |
| DELETE | `/api/todos/{id}` | Bearer | Menghapus todo |

### Login Flow

Request `{ "login": "...", "password": "..." }`:
- Jika `login` mengandung `@` → cari user by `email`
- Selain itu → cari user by `username`

## HTTP Status Code

| Code | Deskripsi | Digunakan Pada |
|---|---|---|
| **200 OK** | Request berhasil | Login, logout, get/update user, list/detail/update/delete todo |
| **201 Created** | Resource berhasil dibuat | Register user, create todo |
| **400 Bad Request** | Validasi request gagal | Register, login, update user, create/update todo |
| **401 Unauthorized** | Token tidak valid/kadaluarsa, atau login gagal | Logout, get/update user, semua endpoint todo |
| **404 Not Found** | Resource tidak ditemukan | Get/update/delete todo (ID tidak valid) |

## Response Format

Semua response mengikuti format standar berikut:

### Success dengan Object

```json
{
    "data": {
        "id": 1,
        "username": "jojobizarre",
        "email": "jojo@example.com",
        "name": "Jojo Bizarre",
        "created_at": "2026-07-18T12:00:00Z",
        "updated_at": "2026-07-18T12:00:00Z"
    }
}
```

### Success dengan Array

```json
{
    "data": [
        {
            "id": 1,
            "title": "Belajar Laravel",
            "is_completed": false
        }
    ]
}
```

### Success dengan Message

```json
{
    "data": {
        "message": "Logout success"
    }
}
```

### Success Login

```json
{
    "data": {
        "token": "1|abc123def456..."
    }
}
```

### Validation Error

```json
{
    "errors": {
        "title": ["The title field is required."],
        "email": ["The email must be a valid email address."]
    }
}
```

### Unauthorized / Not Found Error

```json
{
    "errors": {
        "message": "Unauthorized"
    }
}
```

```json
{
    "errors": {
        "message": "Todo not found"
    }
}
```

## Authentication

Semua endpoint kecuali `/api/register` dan `/api/login` memerlukan Bearer Token.

```http
Authorization: Bearer <token>
```

## Getting Started

### 1. Clone & Install

```bash
git clone <repo-url>
cd todolist-restfulapi
composer install
```

### 2. Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database (SQLite default)

```bash
php artisan migrate --force
```

### 4. Jalankan Server

```bash
php artisan serve
```

Buka `http://localhost:8000` — UI siap digunakan.

### 5. Testing API (Postman)

Import file dari folder `docs/` ke Postman / Swagger Editor:

| File | Kegunaan |
|---|---|
| `docs/user-api.yaml` | API User |
| `docs/todo-api.yaml` | API Todo |
