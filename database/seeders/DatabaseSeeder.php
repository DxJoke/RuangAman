<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder untuk menyediakan data referensi awal dan akun default pengujian.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles & Permissions (Fondasi Bersama)
        // 2. Default Status Kasus: Pending, Proses, Selesai, Ditolak (PBI-04)
        // 3. Kategori Artikel Default (PBI-11)
        // 4. Default Users: Admin Kasus, Konselor Contoh, Pelapor Demo
    }
}
