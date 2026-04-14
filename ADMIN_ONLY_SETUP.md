# 🔐 Admin-Only System Setup Guide

## 📋 Ringkas

Sistem **Klasifikasi Kematangan Tomat** adalah sistem **admin-only** (bukan multi-user publik).

**Fitur:**
- ✅ Login dengan email & password untuk admin
- ✅ Dashboard untuk manage admin accounts
- ✅ Upload & klasifikasi tomat (hanya admin)
- ✅ View riwayat & statistik (hanya admin)

---

## 🗄️ Database Structure

### Users Table
```sql
id (INT)
name (VARCHAR)
email (VARCHAR) - UNIQUE
password (VARCHAR) - hashed
role (VARCHAR) - always 'admin'
email_verified_at (TIMESTAMP)
remember_token (VARCHAR)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

**Note:** Hanya ada role **'admin'**, tidak ada role 'user'

---

## 🚀 Setup Steps

### Step 1: Jalankan Migration Cleanup
```bash
php artisan migrate
```

Ini akan:
- ❌ Hapus semua user dengan role 'user'
- ✅ Set role 'admin' untuk semua user yang tersisa

### Step 2: Verify Database
```bash
php artisan tinker

# Check existing admins
>>> User::all()

# Check total users
>>> User::count()
```

### Step 3: Start Server
```bash
php artisan serve
```

### Step 4: Login
```
URL: http://localhost:8000/admin/login
Email: admin1@gmail.com (atau admin yang ada di database)
Password: sesuai database
```

---

## 👨‍💼 Admin Management

### Access Admin Panel
1. Login dengan akun admin
2. Pergi ke "Kelola Akun Admin"
3. Kelola admin accounts

### Tambah Admin Baru
```
1. Click "Tambah Admin"
2. Isi form:
   - Nama: [nama lengkap]
   - Email: [email admin]
   - Password: [password admin]
   - Role: Admin (fixed)
3. Click "Simpan"
```

### Edit Admin
```
1. Click icon "Edit" di row admin
2. Update informasi
3. Click "Simpan"
```

### Hapus Admin
```
1. Click icon "Trash" di row admin
2. Confirm delete
3. Admin account terhapus
```

---

## 🔒 Security Notes

### Password Requirements
- Minimal 6 karakter (bisa custom di validation)
- Di-hash dengan bcrypt
- Tidak bisa di-reset via email (admin-only)

### Admin-Only Access
Semua fitur dilindungi oleh session check:
```php
if (!session('admin_logged_in')) {
    return redirect()->route('admin.login');
}
```

### Logout
- Hapus session: admin_logged_in, admin_user_id, admin_name
- Redirect ke login page

---

## 📁 Files Modified

### Created
- `database/migrations/2025_02_05_cleanup_users_admin_only.php`
- `ADMIN_ONLY_SETUP.md` (ini file)

### Updated
- `app/Models/User.php` - Tambah 'role' di fillable
- `resources/views/login.blade.php` - Update link

### Existing (tidak diubah)
- `app/Http/Controllers/AdminController.php` - Sudah mendukung admin-only
- `app/Http/Controllers/UploadController.php` - Sudah session-protected
- `routes/web.php` - Sudah protected routes

---

## ✅ Verification Checklist

- [ ] Database migration sudah berjalan
- [ ] Tidak ada user dengan role 'user' di database
- [ ] Semua user punya role 'admin'
- [ ] Login berhasil dengan admin account
- [ ] Admin management panel accessible
- [ ] Bisa tambah/edit/hapus admin
- [ ] Upload & klasifikasi bekerja
- [ ] Logout bekerja dengan baik

---

## 📊 Database Query Examples

### View semua admins
```sql
SELECT * FROM users WHERE role = 'admin';
```

### Count total admins
```sql
SELECT COUNT(*) FROM users WHERE role = 'admin';
```

### Delete user tertentu
```sql
DELETE FROM users WHERE id = 5;
```

### Update user role
```sql
UPDATE users SET role = 'admin' WHERE id = 10;
```

---

## 🐛 Troubleshooting

### ❌ Login gagal
**Check:**
- Email ada di database
- Password sesuai (case-sensitive)
- Check logs: `storage/logs/laravel.log`

### ❌ Admin panel tidak accessible
**Check:**
- Sudah login dulu
- Session active (check cookies)
- Cek middleware session di kernel

### ❌ Migration error
**Check:**
- Database connection di `.env` benar
- Table users sudah ada
- Jalankan: `php artisan migrate:status`

---

## 🎯 Workflow Aplikasi

```
┌─────────────────┐
│  Public Website │
│  (Upload/View)  │
└────────┬────────┘
         │
         ↓
┌─────────────────┐
│  Admin Login    │
│  (Protected)    │
└────────┬────────┘
         │
    Login Success
         │
         ↓
┌─────────────────────────────────────┐
│        Admin Dashboard              │
├─────────────────────────────────────┤
│ • Kelola Admin                      │
│ • Upload & Klasifikasi              │
│ • Riwayat Klasifikasi              │
│ • Statistik Sistem                 │
└────────┬────────────────────────────┘
         │
    Logout
         │
         ↓
    Back to Login
```

---

## 📋 Admin Responsibilities

Admin bertanggung jawab untuk:
1. **User Management** - Tambah/edit/hapus admin
2. **Uploads** - Upload gambar tomat & klasifikasi
3. **History** - Monitor semua klasifikasi
4. **Statistics** - Lihat statistik sistem

---

## 🔧 Kustomisasi

### Ubah password requirement
File: `app/Http/Controllers/AdminController.php`
```php
'password' => 'required|string|min:6',  // Ubah min:6 ke min:8, dll
```

### Ubah login session timeout
File: `config/session.php`
```php
'lifetime' => 120,  // Default 120 menit
```

### Ubah table name atau columns
Update di:
- `User.php` model
- Migration files
- AdminController.php

---

## 📞 Support

Jika ada masalah:
1. Check file `ADMIN_ONLY_SETUP.md` (ini file)
2. Check logs: `storage/logs/laravel.log`
3. Debug dengan `php artisan tinker`
4. Review `AdminController.php` & `UploadController.php`

---

**Status:** ✅ Admin-Only System  
**Last Updated:** February 2026  
**Version:** 1.0

---

## 🎓 Quick Commands Reference

```bash
# Migrate database
php artisan migrate

# Start server
php artisan serve

# Debug dengan tinker
php artisan tinker

# View users
>>> User::all()

# Find user by email
>>> User::where('email', 'admin@gmail.com')->first()

# Create user
>>> User::create(['name' => 'Admin', 'email' => 'a@g.com', 'password' => Hash::make('pass'), 'role' => 'admin'])

# Clear app cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# View routes
php artisan route:list
```

---

Selamat! Aplikasi siap untuk development sebagai **admin-only system**. 🎉
