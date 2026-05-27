<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KategoriSeeder::class, // Jalankan kategori lebih dulu (referensi untuk tabel lain)
            BukuSeeder::class,
            AnggotaSeeder::class,
        ]);
    }
}
