<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin.test@gmail.com',
        ], [
            'name' => 'Admin Demo',
            'phone' => '081100000001',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);

        User::updateOrCreate([
            'email' => 'petugas.test@gmail.com',
        ], [
            'name' => 'Petugas Demo',
            'phone' => '081100000002',
            'role' => 'petugas',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);
    }
}
