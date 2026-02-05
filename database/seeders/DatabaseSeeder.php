<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create default admin user
        User::create([
            'name' => 'Admin Klinik',
            'email' => 'admin@klinik.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);
        
        // Create default petugas
        User::create([
            'name' => 'Petugas Pendaftaran',
            'email' => 'petugas@klinik.com',
            'password' => Hash::make('password123'),
            'role' => 'petugas'
        ]);
        
        // Create default dokter
        User::create([
            'name' => 'Dr. Andi Wijaya',
            'email' => 'dokter@klinik.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter'
        ]);
        
        // Create default kasir
        User::create([
            'name' => 'Kasir Utama',
            'email' => 'kasir@klinik.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir'
        ]);
        
        // Seed other data if needed
        // $this->call([
        //     MedicineSeeder::class,
        //     ServiceSeeder::class,
        // ]);
    }
}