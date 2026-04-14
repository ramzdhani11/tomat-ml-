# 🔑 Admin Credentials - Setup Guide

## ⚠️ IMPORTANT BEFORE YOU START

Sebelum menjalankan migration, pastikan Anda punya backup database atau tahu email/password admin yang ingin dipertahankan.

---

## 📋 Current Admins in Database

**Lihat daftar admin saat ini dengan:**

```bash
php artisan tinker

# Lihat semua user
>>> User::all()

# Lihat user dengan role admin
>>> User::where('role', 'admin')->get()

# Lihat user dengan role user (akan dihapus)
>>> User::where('role', 'user')->get()
```

---

## 🗑️ What Migration Will Do

**Cleanup Migration (`2025_02_05_cleanup_users_admin_only.php`) akan:**

```php
// 1. Hapus semua user dengan role 'user'
DB::table('users')->where('role', 'user')->delete();

// 2. Set role 'admin' untuk semua user yang tersisa
DB::table('users')->update(['role' => 'admin']);
```

---

## ⚡ Langkah-Langkah Setup

### BACKUP DULU! (Sangat Penting)

```bash
# Export database (sebelum migration)
# MySQL:
mysqldump -u root -p klasifikasi_tomat > backup_before_cleanup.sql

# Atau backup via phpMyAdmin
```

### Jalankan Migration

```bash
cd c:\Project\klasifikasi-tomat
php artisan migrate
```

### Verify Hasil

```bash
php artisan tinker

# Check semua user adalah admin
>>> User::pluck('role')
=> Illuminate\Support\Collection {
     0 => "admin",
     1 => "admin",
     2 => "admin",
   }

# Count total
>>> User::count()
=> 3

# Check no 'user' role exists
>>> User::where('role', 'user')->count()
=> 0  // Should be 0
```

---

## 👥 Admin Accounts to Keep

**Tentukan mana admin yang ingin dipertahankan:**

| Email | Password | Keep? |
|-------|----------|-------|
| admin1@gmail.com | ????? | ✅ |
| admin@gmail.com | ????? | ✅ |
| roihan@gmail.com | ????? | ⚠️ ? |
| tomat@gmail.com | ????? | ⚠️ ? |

---

## 🔧 If You Need to Keep Non-Admin Users

**Jika ada user 'user' yang ingin dipertahankan, jangan jalankan migration!**

Alternatif:

### Option A: Ubah role menjadi admin (Recommended)
```sql
UPDATE users SET role = 'admin' WHERE email = 'user_email@gmail.com';
```

### Option B: Jalankan manual cleanup
```sql
-- Hapus user tertentu saja
DELETE FROM users WHERE id = 5;

-- Set admin role untuk sisanya
UPDATE users SET role = 'admin' WHERE role != 'admin';
```

---

## ✅ Default Admin Credentials

**Setelah migration selesai:**

```
Login URL: http://localhost:8000/admin/login

Admin Accounts (dari database):
- admin1@gmail.com (password: sesuai database)
- admin@gmail.com (password: sesuai database)
- roihan@gmail.com (password: sesuai database) - jika dipertahankan
```

---

## 🆕 Tambah Admin Baru Setelah Setup

Gunakan admin panel di: `/admin/manage-admin`

```
1. Login dengan admin existing
2. Pergi ke "Kelola Akun Admin"
3. Click "Tambah Admin"
4. Isi form:
   - Nama: [nama lengkap]
   - Email: [email admin baru]
   - Password: [password]
   - Role: Admin (fixed)
5. Click "Simpan"
```

Atau gunakan tinker:

```bash
php artisan tinker

>>> User::create([
    'name' => 'Admin Baru',
    'email' => 'admin.baru@gmail.com',
    'password' => Hash::make('password123'),
    'role' => 'admin',
    'email_verified_at' => now()
])
```

---

## 🔐 Reset Admin Password

**Jika lupa password admin:**

```bash
php artisan tinker

>>> $admin = User::where('email', 'admin@gmail.com')->first()
>>> $admin->update(['password' => Hash::make('new_password')])
>>> exit
```

---

## 📊 Database Snapshot Sebelum Migration

**Jalankan ini sebelum migration:**

```bash
php artisan tinker

# Export semua user sebelum cleanup
>>> $users = User::all();
>>> $users->toJson()

# Copy hasil untuk backup
```

---

## ⚠️ Troubleshooting

### Q: Migration gagal
**A:**
```bash
php artisan migrate:status  # Lihat status migration
php artisan migrate --step  # Run one migration at a time
php artisan migrate:reset   # Reset semua migration
php artisan migrate         # Jalankan lagi
```

### Q: Lupa password admin
**A:**
```bash
php artisan tinker
>>> User::find(1)->update(['password' => Hash::make('new_password')])
```

### Q: Perlu restore dari backup
**A:**
```bash
# Restore database dari SQL backup
mysql -u root -p klasifikasi_tomat < backup_before_cleanup.sql
```

### Q: Ingin undo migration
**A:**
```bash
php artisan migrate:rollback --step=1
# Jika perlu rollback semua:
php artisan migrate:reset
```

---

## 📋 Admin Setup Checklist

- [ ] Backup database sebelum migration
- [ ] Tentukan admin mana yang ingin dipertahankan
- [ ] Jalankan `php artisan migrate`
- [ ] Verify dengan `php artisan tinker`
- [ ] Test login dengan admin account
- [ ] Test add/edit/delete admin
- [ ] Test upload & classification
- [ ] Semuanya working ✅

---

## 🎯 Summary

**Admin-Only System sudah siap!**

```
✅ Database akan di-cleanup (remove 'user' role)
✅ Semua user akan menjadi 'admin'
✅ System fokus pada administrator saja
✅ Manual admin creation via panel atau tinker
✅ No public user registration
```

---

**Next:** Jalankan `php artisan migrate` dan ikuti verification steps! 🚀
