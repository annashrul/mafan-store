<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Tambahkan ini

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin User',          // Nama admin
            'email' => 'admin@example.com',  // Email admin
            'password' => Hash::make('password'), // Password yang di-hash
            'email_verified_at' => now(),    // Verifikasi email otomatis
            'remember_token' => Str::random(10), // Token remember
            'level' => 'admin',              // Level admin, tambahkan jika ada
        ]);
    }
}
