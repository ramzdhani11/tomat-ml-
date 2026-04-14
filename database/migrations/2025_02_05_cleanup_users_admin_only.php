<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Hapus user non-admin dan bersihkan database
     * Sistem hanya mendukung admin user saja
     */
    public function up(): void
    {
        // Hapus semua user dengan role 'user'
        DB::table('users')->where('role', 'user')->delete();
        
        // Set default role admin untuk semua user yang tersisa
        DB::table('users')->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        // Tidak ada rollback untuk data yang dihapus
        // Pastikan backup database sebelum jalankan migration ini
    }
};
