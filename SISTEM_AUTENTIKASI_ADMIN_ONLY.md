# 🔐 Sistem Autentikasi Admin-Only - Dokumentasi

## 📋 Ringkas Perubahan

Sistem autentikasi telah dirapi menjadi **ADMIN-ONLY** yang konsisten dan aman untuk Tugas Akhir:

✅ Hanya 1 tipe pengguna: ADMIN  
✅ Tidak ada public registration  
✅ Login hanya untuk admin  
✅ Admin baru ditambah melalui dashboard  
✅ Role otomatis 'admin', tidak dapat dipilih  
✅ Query hanya admin  
✅ Kode lebih rapi dan aman  

---

## 🔧 Perubahan di Controller

### 1. **AdminController.php** - store() method

**Sebelum:**
```php
'role' => 'required|in:admin'  // Role dari input
'password' => 'required|string|min:6',  // Min 6 karakter
$role = $request->role  // Ambil dari input
```

**Sesudah:**
```php
// Role TIDAK ada di validation, tidak ada di request
'password' => 'required|string|min:8',  // Min 8 karakter (lebih aman)
'role' => 'admin'  // SELALU hardcoded 'admin'
```

**Alasan:**
- Role tidak bisa dipilih user, otomatis 'admin'
- Password lebih kuat (8 karakter)
- Mencegah user mengirim role dengan nilai lain

---

### 2. **AdminController.php** - update() method

**Sebelum:**
```php
'role' => 'required|in:admin'  // Dari input
$role = $request->role  // Bisa diubah
```

**Sesudah:**
```php
// Role TIDAK ada di validation
// Role TIDAK diupdate (hanya name & email)
if ($admin->role !== 'admin') {
    return error;  // Cek pastikan role admin
}
```

**Alasan:**
- Role tidak boleh diubah
- Hanya admin yang bisa dikelola
- Pastikan integritas data

---

### 3. **AdminController.php** - destroy() method

**Sebelum:**
```php
// Cek hanya id yang sedang login
```

**Sesudah:**
```php
if ($admin->role !== 'admin') {
    return error;  // Hanya admin yang bisa dihapus
}
// Plus: Cek id yang sedang login
```

**Alasan:**
- Extra validation: pastikan yang dihapus hanya admin
- Keamanan berlapis

---

### 4. **AdminController.php** - index() method

**Sebelum:**
```php
User::where('role', 'admin')->get();
\Log::info('Admins fetched...');  // Debug log
```

**Sesudah:**
```php
User::where('role', 'admin')
    ->orderBy('created_at', 'desc')  // Sort terbaru
    ->get();
// Log dihapus (clean code)
```

**Alasan:**
- Lebih rapi tanpa debug log
- Sorting lebih user-friendly (admin terbaru di atas)

---

### 5. **UploadController.php** - adminLogin() method

**Sebelum:**
```php
$user = DB::table('users')->where('email', $email)->first();
// Ambil user dengan email apapun
```

**Sesudah:**
```php
$user = DB::table('users')
    ->where('email', $email)
    ->where('role', 'admin')  // HANYA admin
    ->first();
```

**Alasan:**
- Double-check: query hanya ambil admin
- Jika ada user non-admin, tidak bisa login
- Keamanan berlapis

---

## 🎨 Perubahan di View

### **manage-admin.blade.php** - Form Modal

**Sebelum:**
```blade
<div class="mb-6">
    <label>Role</label>
    <select name="role" required>
        <option value="admin">Admin</option>
    </select>
</div>
```

**Sesudah:**
```blade
<!-- Role field dihapus dari form -->
<input type="hidden" name="role" value="admin">
<!-- Hidden input, otomatis 'admin' -->
```

**Alasan:**
- User tidak perlu memilih role
- Otomatis 'admin'
- Form lebih simple dan fokus
- Tidak ada kebingungan

---

## 📝 Ringkas Baris-Baris Penting

### AdminController.php

**Line 19 (store method):**
```php
'role' => 'admin',  // SELALU 'admin', bukan dari request
```

**Line 40 (update method):**
```php
if ($admin->role !== 'admin') {
    return response()->json(['error' => 'Hanya admin yang dapat dikelola'], 422);
}
// Pastikan hanya admin yang update
```

**Line 59 (update method):**
```php
$admin->update([
    'name' => $request->name,
    'email' => $request->email,
    // Role TIDAK diupdate
]);
```

**Line 72 (destroy method):**
```php
if ($admin->role !== 'admin') {
    return response()->json(['error' => 'Hanya admin yang dapat dihapus'], 422);
}
```

---

### UploadController.php

**Line 211 (adminLogin method):**
```php
$user = \DB::table('users')
    ->where('email', $email)
    ->where('role', 'admin')  // HANYA admin bisa login
    ->first();
```

---

### manage-admin.blade.php

**Line 145:**
```blade
<input type="hidden" name="role" value="admin">
```

---

## ✅ Validasi Sistem

### Admin Creation Flow

```
User (Admin yang login)
    ↓
Click "Tambah Admin"
    ↓
Form Modal terbuka
    ├─ Input: Nama
    ├─ Input: Email
    ├─ Input: Password
    ├─ Role: HIDDEN (selalu 'admin')
    ↓
Submit form
    ↓
AdminController::store()
    ├─ Cek session (login?)
    ├─ Validasi input
    ├─ SET role = 'admin' (hardcoded)
    ├─ Create user
    ↓
Success: Admin baru dengan role 'admin'
```

### Admin Update Flow

```
User (Admin yang login)
    ↓
Click "Edit Admin"
    ↓
Form Modal terbuka
    ├─ Input: Nama (bisa diubah)
    ├─ Input: Email (bisa diubah)
    ├─ Input: Password (optional)
    ├─ Role: HIDDEN
    ↓
Submit form
    ↓
AdminController::update()
    ├─ Cek session
    ├─ Validasi input (role TIDAK ada)
    ├─ Cek $admin->role === 'admin'
    ├─ Update hanya name & email
    ↓
Success: Admin updated (role tetap 'admin')
```

### Admin Delete Flow

```
User (Admin yang login)
    ↓
Click "Hapus Admin"
    ↓
Confirm dialog
    ↓
AdminController::destroy()
    ├─ Cek session
    ├─ Cek role === 'admin'
    ├─ Cek tidak sedang login
    ├─ Delete
    ↓
Success: Admin deleted
```

### Admin Login Flow

```
User (belum login)
    ↓
Go to /admin/login
    ↓
Form input:
    ├─ Email
    ├─ Password
    ↓
Submit
    ↓
UploadController::adminLogin()
    ├─ Validasi input
    ├─ Query: WHERE email & WHERE role='admin'
    ├─ Cek password & role
    ├─ Set session
    ↓
Success: Login ke dashboard (atau error jika bukan admin)
```

---

## 🔒 Keamanan

### Validasi Berlapis

| Layer | Check |
|-------|-------|
| 1 | Session check (logged in?) |
| 2 | Input validation |
| 3 | Role validation (role === 'admin') |
| 4 | Permission check (current user check) |

### Password Security

| Aspek | Implementasi |
|-------|---------------|
| Minimum length | 8 karakter (naik dari 6) |
| Hashing | bcrypt (Laravel default) |
| Storing | Hashed, tidak plain text |
| Reset | Tidak ada (manual via admin) |

### Query Safety

```php
// Sebelum
WHERE role = 'admin'  // Tergantung role user

// Sesudah
WHERE email = $email AND role = 'admin'  // Hanya admin
```

---

## 📋 Checklist Implementasi

- [x] AdminController::store() - role hardcoded 'admin'
- [x] AdminController::update() - role tidak bisa diubah
- [x] AdminController::destroy() - validasi role
- [x] AdminController::index() - query dengan role filter
- [x] UploadController::adminLogin() - query hanya admin
- [x] manage-admin.blade.php - role hidden field
- [x] Password minimum 8 karakter
- [x] Debug log dihapus
- [x] Kode lebih rapi dan konsisten

---

## 🎯 Testing

### Test 1: Tambah Admin
```
1. Login sebagai admin
2. Pergi ke "Kelola Admin"
3. Click "Tambah Admin"
4. Isi form (name, email, password)
5. Cek di database: role harus 'admin'
✅ Expected: Admin baru dengan role='admin'
```

### Test 2: Edit Admin
```
1. Click "Edit" admin
2. Ubah name & email
3. Submit
4. Cek database: role tetap 'admin'
✅ Expected: Name & email updated, role unchanged
```

### Test 3: Hapus Admin
```
1. Click "Hapus" admin (bukan yang logged in)
2. Confirm
✅ Expected: Admin deleted
❌ Should fail: Jika admin yang logged in
```

### Test 4: Login Admin
```
1. Logout
2. Login dengan email admin
3. Masukkan password
✅ Expected: Login berhasil
❌ Should fail: Email yang bukan admin
```

---

## 📊 Database Structure (Setelah Implementasi)

```sql
users table:
├─ id: INT
├─ name: VARCHAR
├─ email: VARCHAR (UNIQUE)
├─ email_verified_at: TIMESTAMP
├─ password: VARCHAR (hashed)
├─ role: VARCHAR = 'admin' (SELALU admin)
├─ remember_token: VARCHAR
├─ created_at: TIMESTAMP
└─ updated_at: TIMESTAMP

PENTING:
- role SELALU 'admin' untuk semua user
- Tidak ada user dengan role 'user'
- Email UNIQUE (tidak bisa duplikat)
```

---

## 🎓 Best Practices yang Diterapkan

✅ **Validation**: Input divalidasi sebelum proses  
✅ **Authorization**: Session & role check  
✅ **Secure Password**: Minimum 8 karakter  
✅ **Consistent Query**: Role filter di semua query  
✅ **Clean Code**: Log debug dihapus  
✅ **Single Responsibility**: Setiap method fokus satu tugas  
✅ **Error Handling**: Return proper HTTP status & messages  
✅ **DRY**: Tidak ada duplikasi logic  

---

## 🚀 Next Steps

### Optional Enhancements

1. **Password Reset**
   - Tambah forgot password untuk admin
   - Reset via email link (secure token)

2. **Activity Logging**
   - Log setiap action admin (create, update, delete)
   - Simpan di activity_logs table

3. **Audit Trail**
   - Track siapa yang membuat/update/delete admin
   - Timestamp untuk setiap action

4. **Session Timeout**
   - Auto logout setelah N menit idle
   - Config di config/session.php

5. **2FA (Two-Factor Authentication)**
   - SMS atau email OTP
   - Extra security untuk admin

---

## 📞 Ringkas Perubahan File

| File | Baris | Perubahan |
|------|-------|-----------|
| AdminController.php | 23-24 | Hapus debug log, add sorting |
| AdminController.php | 30-34 | Hapus role validation, min 8 pass |
| AdminController.php | 38 | Role hardcoded 'admin' |
| AdminController.php | 40-47 | Add role check, hapus role update |
| AdminController.php | 72-74 | Add role validation pada destroy |
| UploadController.php | 211-213 | Add role='admin' to query |
| manage-admin.blade.php | 145 | Change select to hidden input |

---

**Status:** ✅ IMPLEMENTASI SELESAI & SIAP UNTUK TA  
**Tanggal:** February 2026

Sistem autentikasi Anda sekarang **CLEAN, SECURE, dan ADMIN-ONLY**! 🎉
