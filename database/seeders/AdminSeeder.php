<?php
/**
 * Seed akun awal (admin, staf, kades) via php artisan db:seed. WAJIB ganti password setelah login pertama.
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@kaki-air.desa.id'],
            [
                'name' => 'Administrator Kaki Air',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Staf
        User::updateOrCreate(
            ['email' => 'staf@kaki-air.desa.id'],
            [
                'name' => 'Staf Pelayanan',
                'password' => Hash::make('password'),
                'role' => 'staf',
            ]
        );

        // Kepala Desa
        User::updateOrCreate(
            ['email' => 'kades@kaki-air.desa.id'],
            [
                'name' => 'Kepala Desa Kaki Air',
                'password' => Hash::make('password'),
                'role' => 'kepala_desa',
            ]
        );
    }
}
