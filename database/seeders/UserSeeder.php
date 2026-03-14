<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@mancraft.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Sample Employees
        $employees = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@mancraft.com',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@mancraft.com',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@mancraft.com',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ],
            [
                'name' => 'Dewi Sartika',
                'email' => 'dewi@mancraft.com',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ],
            [
                'name' => 'Rizky Pratama',
                'email' => 'rizky@mancraft.com',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ]
        ];

        foreach ($employees as $employee) {
            User::create([
                'name' => $employee['name'],
                'email' => $employee['email'],
                'email_verified_at' => now(),
                'password' => $employee['password'],
                'role' => $employee['role'],
            ]);
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin credentials: admin@mancraft.com / admin123');
        $this->command->info('Employee credentials: [name]@mancraft.com / karyawan123');
    }
}