<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds - Property Listing for Alam Sari Properti.
     */
    public function run(): void
    {
        $properties = [
            [
                'name' => 'Rumah Minimalis Modern Cluster Elite',
                'property_type' => 'rumah',
                'location' => 'Bandung, Jawa Barat',
                'price' => 850000000,
                'status' => 'available',
                'description' => 'Rumah 2 lantai dengan 3 kamar tidur, 2 kamar mandi, carport, taman depan dan belakang. Lokasi strategis dekat sekolah dan pusat perbelanjaan.',
            ],
            [
                'name' => 'Tanah Kavling Siap Bangun',
                'property_type' => 'tanah',
                'location' => 'Cianjur, Jawa Barat',
                'price' => 450000000,
                'status' => 'available',
                'description' => 'Tanah kavling 300m² dengan sertifikat SHM, jalan depan 8 meter, cocok untuk investasi atau bangun rumah.',
            ],
            [
                'name' => 'Ruko 3 Lantai Strategis',
                'property_type' => 'ruko',
                'location' => 'Jakarta Barat',
                'price' => 2500000000,
                'status' => 'pending',
                'description' => 'Ruko 3 lantai di jalan protokol, cocok untuk usaha retail atau kantor. Lebar 5m, panjang 15m.',
            ],
            [
                'name' => 'Apartemen Studio Furnished',
                'property_type' => 'apartemen',
                'location' => 'Surabaya, Jawa Timur',
                'price' => 375000000,
                'status' => 'available',
                'description' => 'Apartemen studio tower B lantai 15, fully furnished dengan view kota. Fasilitas lengkap: kolam renang, gym, security 24 jam.',
            ],
            [
                'name' => 'Villa Mewah View Gunung',
                'property_type' => 'villa',
                'location' => 'Bogor, Jawa Barat',
                'price' => 3200000000,
                'status' => 'available',
                'description' => 'Villa eksklusif dengan 5 kamar tidur, kolam renang private, taman luas 500m². View langsung ke Gunung Salak.',
            ],
            [
                'name' => 'Gudang Industri Besar',
                'property_type' => 'gudang',
                'location' => 'Tangerang, Banten',
                'price' => 1800000000,
                'status' => 'sold',
                'description' => 'Gudang 800m² dengan tinggi 8 meter, loading dock, kantor administrasi, area parkir luas untuk truk.',
            ],
            [
                'name' => 'Rumah Subsidi Type 36',
                'property_type' => 'rumah',
                'location' => 'Bekasi, Jawa Barat',
                'price' => 185000000,
                'status' => 'available',
                'description' => 'Rumah subsidi ready stock, 2 kamar tidur, 1 kamar mandi, carport. Lingkungan aman dan nyaman.',
            ],
            [
                'name' => 'Tanah Pertanian Produktif',
                'property_type' => 'tanah',
                'location' => 'Garut, Jawa Barat',
                'price' => 650000000,
                'status' => 'available',
                'description' => 'Tanah pertanian 2000m² dengan sumber air melimpah, cocok untuk perkebunan atau peternakan.',
            ],
        ];

        foreach ($properties as $property) {
            Service::create($property);
        }

        $this->command->info('Properties seeded successfully! Total: ' . count($properties) . ' properties.');
    }
}