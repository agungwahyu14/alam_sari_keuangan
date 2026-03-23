<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds - Users for Alam Sari Properti.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Alam Sari',
            'email' => 'admin@alamsari.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'bank_account' => '1234567890',
        ]);

        // Create Sample Property Agents (Freelance - Commission Only)
        $agents = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@alamsari.com',
                'password' => Hash::make('agen123'),
                'role' => 'agen',
                'bank_account' => '1234567891',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@alamsari.com',
                'password' => Hash::make('agen123'),
                'role' => 'agen',
                'bank_account' => '1234567892',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@alamsari.com',
                'password' => Hash::make('agen123'),
                'role' => 'agen',
                'bank_account' => '1234567893',
            ],
            [
                'name' => 'Dewi Sartika',
                'email' => 'dewi@alamsari.com',
                'password' => Hash::make('agen123'),
                'role' => 'agen',
                'bank_account' => '1234567894',
            ],
            [
                'name' => 'Rizky Pratama',
                'email' => 'rizky@alamsari.com',
                'password' => Hash::make('agen123'),
                'role' => 'agen',
                'bank_account' => '1234567895',
            ],
            [
                'name' => 'Diana Putri',
                'email' => 'diana@alamsari.com',
                'password' => Hash::make('agen123'),
                'role' => 'agen',
                'bank_account' => '1234567896',
            ],
            [
                'name' => 'Eko Wijaya',
                'email' => 'eko@alamsari.com',
                'password' => Hash::make('agen123'),
                'role' => 'agen',
                'bank_account' => '1234567897',
            ],
        ];

        foreach ($agents as $agent) {
            User::create([
                'name' => $agent['name'],
                'email' => $agent['email'],
                'email_verified_at' => now(),
                'password' => $agent['password'],
                'role' => $agent['role'],
                'bank_account' => $agent['bank_account'] ?? null,
            ]);
        }

        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('👤 Admin: admin@alamsari.com / admin123');
        $this->command->info('👥 Property Agents (Commission 5%): [name]@alamsari.com / agen123');
        $this->command->info('📊 Total: 1 admin + ' . count($agents) . ' freelance agents');
    }
}