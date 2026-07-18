## Issue: Todo API — Migration & OpenAPI Spec

### Deskripsi
Menambahkan fitur CRUD Todo yang meliputi migration table dan OpenAPI spec.

---

### Masalah
- Belum ada table `todos` di database
- Belum ada dokumentasi API untuk fitur Todo

---

### Solusi

**A. Database — buat table `todos`**

File: `database/migrations/0001_01_01_000003_create_todos_table.php`

| Column | Type | Constraints |
|---|---|---|
| id | bigIncrements | Primary Key |
| user_id | foreignId | `constrained()->cascadeOnDelete()` |
| title | string | Required |
| description | text | Nullable |
| is_completed | boolean | Default false |
| created_at | timestamp | |
| updated_at | timestamp | |

Relasi: Setiap todo dimiliki oleh satu user (`user_id → users.id`). Jika user dihapus, semua todo-nya ikut terhapus (cascadeOnDelete).

**B. OpenAPI Spec — `docs/todo-api.yaml`**

Semua endpoint memerlukan autentikasi Bearer Token.

### Endpoints

| Method | Path | Deskripsi |
|---|---|---|
| `GET` | `/api/todos` | List semua todo milik user yang login |
| `POST` | `/api/todos` | Buat todo baru (title required, description optional) |
| `GET` | `/api/todos/{id}` | Detail todo berdasarkan ID |
| `PATCH` | `/api/todos/{id}` | Update title, description, atau is_completed |
| `DELETE` | `/api/todos/{id}` | Hapus todo |

### Response Codes

| Code | Keterangan |
|---|---|
| 200 | Success (list, detail, update, delete) |
| 201 | Created (create) |
| 400 | Validation error |
| 401 | Unauthorized |
| 404 | Todo tidak ditemukan |

### Schema

**CreateTodoRequest:**
```json
{
    "title": "Belajar Laravel",
    "description": "Mempelajari RESTful API dengan Laravel"
}
```

**UpdateTodoRequest:**
```json
{
    "title": "Belajar Laravel Lanjutan",
    "description": "Mempelajari Eloquent ORM",
    "is_completed": true
}
```

**TodoResponse:**
```json
{
    "id": 1,
    "user_id": 1,
    "title": "Belajar Laravel",
    "description": "Mempelajari RESTful API dengan Laravel",
    "is_completed": false,
    "created_at": "2026-07-18T12:00:00Z",
    "updated_at": "2026-07-18T12:00:00Z"
}
```

---

### Files Changed

| File | Perubahan |
|---|---|
| `database/migrations/0001_01_01_000003_create_todos_table.php` | Baru — create table todos |
| `docs/todo-api.yaml` | Baru — dokumentasi API Todo |
| `docs/todolist-api.json` | Baru — JSON version dari todo-api.yaml |
