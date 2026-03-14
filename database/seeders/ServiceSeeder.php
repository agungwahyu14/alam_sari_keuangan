<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Potong Rambut Reguler',
                'price' => 25000,
            ],
            [
                'name' => 'Potong Rambut Premium',
                'price' => 50000,
            ],
            [
                'name' => 'Cukur Jenggot',
                'price' => 15000,
            ],
            [
                'name' => 'Hair Wash & Styling',
                'price' => 30000,
            ],
            [
                'name' => 'Hair Treatment',
                'price' => 75000,
            ],
            [
                'name' => 'Paket Lengkap',
                'price' => 100000,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('Services seeded successfully!');
    }
}