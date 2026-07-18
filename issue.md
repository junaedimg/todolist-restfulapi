## Issue: Review & Perbaikan OpenAPI Spec User

### Deskripsi
Melakukan review terhadap `docs/user-api.yaml` dan mencocokkan dengan database migration yang ada. Ditemukan beberapa ketidaksesuaian dan kekurangan pada spec.

---

### Masalah

**1. Request/Response tidak sesuai database**
- Spec pake `username` tapi DB cuma punya `email` — login gak bisa jalan
- Response user cuma `username` + `name` — padahal DB punya `id`, `email`, `created_at`, `updated_at`
- Register tidak menerima `email` — padahal column `email` di DB required

**2. Endpoint tidak lengkap**
- Tidak ada endpoint untuk update profile
- Logout pake `DELETE`, seharusnya `POST` (logout bukan hapus resource)

**3. Struktur OpenAPI tidak standar**
- Auth pake parameter `Authorization` manual di tiap endpoint — harusnya pakai `securitySchemes`
- Ada wrapper schema `DataUserResponse`, `TokenResponse`, `MessageResponse` yang tidak perlu
- `bearerFormat: JWT` tidak sesuai (Laravel Sanctum pake token biasa)

**4. Response error tidak lengkap**
- Login cuma punya response `400` — kalau password salah harusnya `401`

---

### Solusi

**A. Database — tambah kolom `username`**
File: `database/migrations/0001_01_01_000000_create_users_table.php`

```php
$table->string('username')->unique()->after('name');
```

**B. OpenAPI Spec — rewrite total**
- Semua schema disesuaikan dengan DB (`id`, `username`, `email`, `name`, `created_at`, `updated_at`)
- Auth pakai `securitySchemes: BearerAuth`
- Wrapper schema dihapus, di-inline di response path
- Logout ganti `DELETE` → `POST`
- Login tambah response `401`

---

### Endpoints Final

| Method | Path | Auth | Deskripsi |
|---|---|---|---|
| `POST` | `/api/register` | No | Register (name, username, email, password) |
| `POST` | `/api/login` | No | Login (login field + password) — 401 jika gagal |
| `POST` | `/api/users/logout` | Bearer | Logout |
| `GET` | `/api/users/current` | Bearer | Get current user |
| `PATCH` | `/api/users/current` | Bearer | Update profile (name, username, email) |

### Flow Login
Request `{ "login": "...", "password": "..." }`
- Jika `login` mengandung `@` → cari by `email`
- Selain itu → cari by `username`

---

### Files Changed

| File | Perubahan |
|---|---|
| `database/migrations/0001_01_01_000000_create_users_table.php` | Tambah kolom `username` |
| `docs/user-api.yaml` | Rewrite total |
| `docs/user-api.json` | Rewrite total (sinkron dengan YAML) |
| `README.md` | Update sesuai perubahan |
