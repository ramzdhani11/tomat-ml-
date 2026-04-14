#!/bin/bash

# 🚀 QUICK START - Admin-Only System

echo "═══════════════════════════════════════════════════════════"
echo "  ADMIN-ONLY SYSTEM - QUICK START GUIDE"
echo "═══════════════════════════════════════════════════════════"
echo ""

# Step 1
echo "Step 1️⃣  - Jalankan Database Migration"
echo "───────────────────────────────────────────────────────────"
echo ""
echo "Perintah:"
echo "  php artisan migrate"
echo ""
echo "Apa yang dilakukan:"
echo "  ✅ Hapus semua user dengan role 'user'"
echo "  ✅ Set role 'admin' untuk semua user"
echo "  ✅ Persiapkan database untuk admin-only"
echo ""
read -p "✅ Migration selesai? (y/n): " answer
if [ "$answer" != "y" ]; then
    echo "Jalankan migration terlebih dahulu!"
    exit 1
fi
echo ""

# Step 2
echo "Step 2️⃣  - Verify Database"
echo "───────────────────────────────────────────────────────────"
echo ""
echo "Perintah tinker:"
echo "  php artisan tinker"
echo "  >>> User::all()"
echo ""
echo "Expected output:"
echo "  Semua user memiliki role = 'admin'"
echo "  Tidak ada user dengan role 'user'"
echo ""
read -p "✅ Database verified? (y/n): " answer
if [ "$answer" != "y" ]; then
    echo "Check database menggunakan tinker"
    exit 1
fi
echo ""

# Step 3
echo "Step 3️⃣  - Start Development Server"
echo "───────────────────────────────────────────────────────────"
echo ""
echo "Perintah:"
echo "  php artisan serve"
echo ""
echo "Server akan berjalan di: http://localhost:8000"
echo ""

# Step 4
echo "Step 4️⃣  - Test Login"
echo "───────────────────────────────────────────────────────────"
echo ""
echo "URL: http://localhost:8000/admin/login"
echo ""
echo "Login dengan:"
echo "  Email: (salah satu admin email dari database)"
echo "  Password: (password admin)"
echo ""

# Step 5
echo "Step 5️⃣  - Test Admin Management"
echo "───────────────────────────────────────────────────────────"
echo ""
echo "Setelah login, akses:"
echo "  http://localhost:8000/admin/manage-admin"
echo ""
echo "Fitur:"
echo "  ✅ Tambah Admin Baru"
echo "  ✅ Edit Admin Existing"
echo "  ✅ Hapus Admin"
echo ""

# Summary
echo ""
echo "═══════════════════════════════════════════════════════════"
echo "  ✅ SETUP COMPLETE!"
echo "═══════════════════════════════════════════════════════════"
echo ""
echo "Sistem ini adalah:"
echo "  🔐 Admin-Only System (bukan multi-user publik)"
echo "  👤 Hanya admin yang bisa login"
echo "  🚀 Fokus pada klasifikasi tomat"
echo ""
echo "Fitur Utama:"
echo "  ✅ Login Admin"
echo "  ✅ Kelola Admin Accounts"
echo "  ✅ Upload Gambar Tomat"
echo "  ✅ Klasifikasi Otomatis"
echo "  ✅ Riwayat Klasifikasi"
echo "  ✅ Statistik Sistem"
echo ""
echo "Dokumentasi:"
echo "  📄 ADMIN_ONLY_SETUP.md - Complete guide"
echo "  📄 ADMIN_ONLY_CHANGES.md - Summary of changes"
echo ""
echo "Selamat! Aplikasi siap untuk development 🎉"
echo ""
echo "═══════════════════════════════════════════════════════════"
