<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fa; min-height: 100vh; }
        .navbar-brand { font-weight: 700; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .todo-check { width: 1.2em; height: 1.2em; cursor: pointer; }
        .todo-item { transition: background .15s; }
        .todo-item:hover { background: #f8f9fa; }
        .completed .todo-title { text-decoration: line-through; color: #6c757d; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        #auth-section, #app-section { display: none; }
        .tab-link { cursor: pointer; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand navbar-dark bg-primary mb-4" id="main-nav" style="display:none">
    <div class="container">
        <span class="navbar-brand">📋 Todo App</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white" id="nav-username"></span>
            <button class="btn btn-outline-light btn-sm" onclick="logout()">Logout</button>
        </div>
    </div>
</nav>

<div class="container py-4">

    {{-- AUTH SECTION --}}
    <div id="auth-section" class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h3 class="text-center mb-3" id="auth-title">Login</h3>

                <ul class="nav nav-pills nav-justified mb-3">
                    <li class="nav-item"><button class="nav-link active tab-link" data-tab="login">Login</button></li>
                    <li class="nav-item"><button class="nav-link tab-link" data-tab="register">Register</button></li>
                </ul>

                {{-- LOGIN --}}
                <div id="form-login">
                    <div class="mb-3">
                        <input class="form-control" id="login-login" placeholder="Username atau Email">
                    </div>
                    <div class="mb-3">
                        <input class="form-control" id="login-password" type="password" placeholder="Password">
                    </div>
                    <button class="btn btn-primary w-100" onclick="login()">Login</button>
                    <div class="alert alert-danger mt-2 d-none" id="login-error"></div>
                </div>

                {{-- REGISTER --}}
                <div id="form-register" style="display:none">
                    <div class="mb-3">
                        <input class="form-control" id="reg-name" placeholder="Nama Lengkap">
                    </div>
                    <div class="mb-3">
                        <input class="form-control" id="reg-username" placeholder="Username">
                    </div>
                    <div class="mb-3">
                        <input class="form-control" id="reg-email" placeholder="Email">
                    </div>
                    <div class="mb-3">
                        <input class="form-control" id="reg-password" type="password" placeholder="Password (min 8)">
                    </div>
                    <button class="btn btn-success w-100" onclick="register()">Register</button>
                    <div class="alert alert-success mt-2 d-none" id="reg-success"></div>
                    <div class="alert alert-danger mt-2 d-none" id="reg-error"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- APP SECTION --}}
    <div id="app-section">

        {{-- PROFILE --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="avatar bg-primary text-white d-flex align-items-center justify-content-center" id="profile-avatar">J</div>
                    <div>
                        <h5 class="mb-0" id="profile-name"></h5>
                        <small class="text-muted" id="profile-username"></small>
                    </div>
                </div>
                <button class="btn btn-outline-primary btn-sm" onclick="toggleEditProfile()">✏ Edit Profil</button>

                <div id="edit-profile-form" style="display:none" class="mt-3 border-top pt-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input class="form-control form-control-sm" id="edit-name" placeholder="Nama">
                        </div>
                        <div class="col-md-4">
                            <input class="form-control form-control-sm" id="edit-username" placeholder="Username">
                        </div>
                        <div class="col-md-4">
                            <input class="form-control form-control-sm" id="edit-email" placeholder="Email">
                        </div>
                    </div>
                    <div class="mt-2 d-flex gap-2">
                        <button class="btn btn-primary btn-sm" onclick="updateProfile()">Simpan</button>
                        <button class="btn btn-secondary btn-sm" onclick="toggleEditProfile()">Batal</button>
                    </div>
                    <div class="alert alert-danger mt-2 d-none" id="profile-error"></div>
                </div>
            </div>
        </div>

        {{-- TODO --}}
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">📝 Tambah Todo</h5>
                <div class="row g-2">
                    <div class="col-md-5">
                        <input class="form-control" id="todo-title" placeholder="Judul todo">
                    </div>
                    <div class="col-md-5">
                        <input class="form-control" id="todo-desc" placeholder="Deskripsi (opsional)">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100" onclick="createTodo()">Tambah</button>
                    </div>
                </div>
                <div class="alert alert-danger mt-2 d-none" id="todo-error"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title d-flex justify-content-between">
                    <span>📋 Daftar Todo</span>
                    <span class="badge bg-primary fs-6" id="todo-count">0</span>
                </h5>
                <div id="todo-list">
                    <p class="text-muted text-center" id="todo-empty">Belum ada todo. Tambahkan di atas!</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API = 'http://localhost:8000/api';
let token = localStorage.getItem('token');

function getHeaders() {
    const h = { 'Content-Type': 'application/json' };
    if (token) h['Authorization'] = 'Bearer ' + token;
    return h;
}

function api(path, opts = {}) {
    return fetch(API + path, { headers: getHeaders(), ...opts })
        .then(r => r.json().then(d => ({ ok: r.ok, status: r.status, data: d })));
}

function showAlert(el, msg, type = 'danger') {
    el.textContent = msg;
    el.className = 'alert alert-' + type + ' mt-2';
    el.classList.remove('d-none');
}
function hideAlert(el) { el.classList.add('d-none'); }

// --- TAB ---
document.querySelectorAll('.tab-link').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.tab-link').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('form-login').style.display = this.dataset.tab === 'login' ? 'block' : 'none';
        document.getElementById('form-register').style.display = this.dataset.tab === 'register' ? 'block' : 'none';
        document.getElementById('auth-title').textContent = this.dataset.tab === 'login' ? 'Login' : 'Register';
    });
});

// --- AUTH ---
function login() {
    const login = document.getElementById('login-login').value;
    const password = document.getElementById('login-password').value;
    const errEl = document.getElementById('login-error');
    hideAlert(errEl);
    api('/login', { method: 'POST', body: JSON.stringify({ login, password }) }).then(res => {
        if (res.ok) {
            token = res.data.data.token;
            localStorage.setItem('token', token);
            loadApp();
        } else {
            showAlert(errEl, res.data.errors?.message || 'Login gagal');
        }
    });
}

function register() {
    const name = document.getElementById('reg-name').value;
    const username = document.getElementById('reg-username').value;
    const email = document.getElementById('reg-email').value;
    const password = document.getElementById('reg-password').value;
    const errEl = document.getElementById('reg-error');
    const sucEl = document.getElementById('reg-success');
    hideAlert(errEl);
    hideAlert(sucEl);
    api('/register', { method: 'POST', body: JSON.stringify({ name, username, email, password }) }).then(res => {
        if (res.ok) {
            showAlert(sucEl, 'Registrasi berhasil! Silakan login.', 'success');
            document.getElementById('reg-name').value = '';
            document.getElementById('reg-username').value = '';
            document.getElementById('reg-email').value = '';
            document.getElementById('reg-password').value = '';
        } else {
            const msgs = Object.values(res.data.errors || {}).flat().join(', ');
            showAlert(errEl, msgs || 'Registrasi gagal');
        }
    });
}

function logout() {
    api('/users/logout', { method: 'POST' }).then(() => {
        token = null;
        localStorage.removeItem('token');
        showAuth();
    });
}

// --- PROFILE ---
function loadProfile() {
    api('/users/current').then(res => {
        if (!res.ok) return showAuth();
        const u = res.data.data;
        document.getElementById('profile-name').textContent = u.name;
        document.getElementById('nav-username').textContent = u.name;
        document.getElementById('profile-username').textContent = '@' + u.username;
        document.getElementById('profile-avatar').textContent = u.name.charAt(0).toUpperCase();
        document.getElementById('edit-name').value = u.name;
        document.getElementById('edit-username').value = u.username;
        document.getElementById('edit-email').value = u.email;
    });
}

function toggleEditProfile() {
    const f = document.getElementById('edit-profile-form');
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
}

function updateProfile() {
    const errEl = document.getElementById('profile-error');
    hideAlert(errEl);
    const body = {};
    const name = document.getElementById('edit-name').value;
    const username = document.getElementById('edit-username').value;
    const email = document.getElementById('edit-email').value;
    if (name) body.name = name;
    if (username) body.username = username;
    if (email) body.email = email;
    api('/users/current', { method: 'PATCH', body: JSON.stringify(body) }).then(res => {
        if (res.ok) {
            toggleEditProfile();
            loadProfile();
        } else {
            const msgs = Object.values(res.data.errors || {}).flat().join(', ');
            showAlert(errEl, msgs || 'Gagal update profil');
        }
    });
}

// --- TODO ---
function loadTodos() {
    api('/todos').then(res => {
        if (!res.ok) return showAuth();
        const todos = res.data.data;
        const list = document.getElementById('todo-list');
        const count = document.getElementById('todo-count');
        count.textContent = todos.length;
        if (!todos.length) {
            list.innerHTML = '<p class="text-muted text-center" id="todo-empty">Belum ada todo. Tambahkan di atas!</p>';
            return;
        }
        list.innerHTML = todos.map(t => `
            <div class="todo-item d-flex align-items-center gap-3 p-2 border-bottom ${t.is_completed ? 'completed' : ''}" data-id="${t.id}">
                <input type="checkbox" class="todo-check" ${t.is_completed ? 'checked' : ''} onchange="toggleTodo(${t.id}, this.checked)">
                <div class="flex-grow-1">
                    <div class="todo-title fw-medium">${escHtml(t.title)}</div>
                    ${t.description ? '<small class="text-muted">' + escHtml(t.description) + '</small>' : ''}
                </div>
                <button class="btn btn-outline-primary btn-sm" onclick="editTodo(${t.id})">✏</button>
                <button class="btn btn-outline-danger btn-sm" onclick="deleteTodo(${t.id})">✕</button>
            </div>
        `).join('');
    });
}

function createTodo() {
    const title = document.getElementById('todo-title').value;
    const description = document.getElementById('todo-desc').value;
    const errEl = document.getElementById('todo-error');
    hideAlert(errEl);
    if (!title.trim()) return showAlert(errEl, 'Judul wajib diisi');
    api('/todos', { method: 'POST', body: JSON.stringify({ title, description }) }).then(res => {
        if (res.ok) {
            document.getElementById('todo-title').value = '';
            document.getElementById('todo-desc').value = '';
            loadTodos();
        } else {
            const msgs = Object.values(res.data.errors || {}).flat().join(', ');
            showAlert(errEl, msgs || 'Gagal tambah todo');
        }
    });
}

function toggleTodo(id, completed) {
    api('/todos/' + id, { method: 'PATCH', body: JSON.stringify({ is_completed: completed }) }).then(r => {
        if (r.ok) loadTodos();
    });
}

function editTodo(id) {
    const newTitle = prompt('Judul baru:');
    if (newTitle === null) return;
    const newDesc = prompt('Deskripsi baru (kosongkan jika tidak diubah):');
    const body = { title: newTitle };
    if (newDesc !== null && newDesc !== '') body.description = newDesc;
    api('/todos/' + id, { method: 'PATCH', body: JSON.stringify(body) }).then(r => {
        if (r.ok) loadTodos();
    });
}

function deleteTodo(id) {
    if (!confirm('Hapus todo ini?')) return;
    api('/todos/' + id, { method: 'DELETE' }).then(r => {
        if (r.ok) loadTodos();
    });
}

function escHtml(str) {
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

// --- VIEWS ---
function showAuth() {
    document.getElementById('auth-section').style.display = 'flex';
    document.getElementById('app-section').style.display = 'none';
    document.getElementById('main-nav').style.display = 'none';
}

function showApp() {
    document.getElementById('auth-section').style.display = 'none';
    document.getElementById('app-section').style.display = 'block';
    document.getElementById('main-nav').style.display = 'flex';
}

function loadApp() {
    showApp();
    loadProfile();
    loadTodos();
}

// --- INIT ---
if (token) {
    loadApp();
} else {
    showAuth();
}
</script>
</body>
</html>
