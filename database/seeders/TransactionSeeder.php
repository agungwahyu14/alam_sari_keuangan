<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = User::where('role', 'karyawan')->get();
        $services = Service::all();

        if ($employees->isEmpty() || $services->isEmpty()) {
            $this->command->error('Please run UserSeeder and ServiceSeeder first!');
            return;
        }

        $transactions = [];

        // Generate transactions for the last 3 months
        for ($monthOffset = 2; $monthOffset >= 0; $monthOffset--) {
            $currentMonth = Carbon::now()->subMonths($monthOffset);
            
            // Generate 15-25 income transactions per month
            for ($i = 0; $i < rand(15, 25); $i++) {
                $employee = $employees->random();
                $service = $services->random();
                $date = $currentMonth->copy()->addDays(rand(1, $currentMonth->daysInMonth));
                
                $transactions[] = [
                    'type' => 'income',
                    'amount' => $service->price,
                    'description' => null,
                    'transaction_date' => $date->format('Y-m-d'),
                    'user_id' => $employee->id,
                    'service_id' => $service->id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }

            // Generate 5-10 expense transactions per month
            $expenseTypes = [
                'Pembelian Alat Cukur' => rand(150000, 300000),
                'Listrik & Air' => rand(200000, 400000),
                'Sewa Tempat' => rand(1000000, 1500000),
                'Pembelian Shampo' => rand(50000, 100000),
                'Pembelian Pomade' => rand(75000, 150000),
                'Maintenance Alat' => rand(100000, 250000),
                'Internet & Telepon' => rand(150000, 200000),
                'Supplies & Peralatan' => rand(80000, 180000),
            ];

            for ($i = 0; $i < rand(5, 10); $i++) {
                $expenseType = array_rand($expenseTypes);
                $amount = $expenseTypes[$expenseType];
                $date = $currentMonth->copy()->addDays(rand(1, $currentMonth->daysInMonth));
                
                $transactions[] = [
                    'type' => 'expense',
                    'amount' => $amount,
                    'description' => $expenseType,
                    'transaction_date' => $date->format('Y-m-d'),
                    'user_id' => null,
                    'service_id' => null,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }
        }

        // Insert all transactions
        Transaction::insert($transactions);

        $this->command->info('Transactions seeded successfully!');
        $this->command->info('Generated ' . count($transactions) . ' transactions for the last 3 months');
    }
}