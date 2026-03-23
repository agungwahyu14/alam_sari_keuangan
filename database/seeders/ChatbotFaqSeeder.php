<?php

namespace Database\Seeders;

use App\Models\ChatbotFaq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatbotFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara login ke sistem?',
                'answer' => 'Untuk login ke sistem, masukkan email dan password yang telah diberikan oleh admin. Jika Anda lupa password, klik link "Lupa Password" untuk reset.',
                'keywords' => ['login', 'masuk', 'akses', 'email', 'password'],
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara membuat transaksi baru?',
                'answer' => 'Untuk membuat transaksi baru, masuk ke menu Transaksi, klik tombol "Tambah Transaksi", pilih jenis transaksi (Pemasukan/Pengeluaran), pilih properti (jika ada), masukkan nominal, dan jika ada agen yang membantu penjualan, isi data agen untuk komisi 5%.',
                'keywords' => ['transaksi', 'tambah', 'buat', 'pemasukan', 'pengeluaran', 'properti'],
                'is_active' => true,
            ],
            [
                'question' => 'Apa perbedaan antara Pemasukan dan Pengeluaran?',
                'answer' => 'Pemasukan adalah transaksi yang menambah saldo (pendapatan), sedangkan Pengeluaran adalah transaksi yang mengurangi saldo (biaya/pembelian).',
                'keywords' => ['pemasukan', 'pengeluaran', 'perbedaan', 'jenis', 'transaksi'],
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara melihat laporan keuangan?',
                'answer' => 'Untuk melihat laporan keuangan, masuk ke menu Laporan. Di sana Anda bisa melihat berbagai laporan seperti Arus Kas (Cash Flow), Laba Rugi (Profit Loss), dan laporan transaksi lengkap. Anda juga bisa download dalam format PDF.',
                'keywords' => ['laporan', 'report', 'keuangan', 'cash flow', 'laba rugi'],
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara mengelola data properti?',
                'answer' => 'Hanya admin yang bisa mengelola data properti. Masuk ke menu Properti, kemudian Anda bisa menambah, mengedit, atau menghapus properti. Setiap properti memiliki nama, tipe (rumah/tanah/ruko/apartemen/villa/gudang), lokasi, harga, status (tersedia/pending/terjual), dan deskripsi.',
                'keywords' => ['properti', 'aset', 'kelola', 'tambah', 'edit', 'hapus', 'rumah', 'tanah', 'ruko'],
                'is_active' => true,
            ],
            [
                'question' => 'Siapa yang bisa akses halaman Admin?',
                'answer' => 'Hanya user dengan role "admin" yang bisa mengakses fitur-fitur admin seperti kelola properti, kelola agen, dan melihat semua transaksi. Agen hanya bisa mengelola transaksi mereka sendiri dan menerima komisi 5% dari penjualan.',
                'keywords' => ['admin', 'role', 'akses', 'agen', 'hak akses'],
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara menambah agen baru?',
                'answer' => 'Untuk menambah agen baru (hanya admin), masuk ke menu Agen, klik "Tambah Agen", isi data seperti nama, email, password, nomor rekening, dan pilih role (Admin/Agen). Klik Simpan. Agen properti bekerja freelance dan hanya mendapat komisi 5% dari setiap penjualan properti.',
                'keywords' => ['agen', 'tambah', 'user', 'broker', 'freelance'],
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana sistem komisi agen properti bekerja?',
                'answer' => 'Alam Sari Properti memberikan komisi sebesar 5% kepada agen atau perantara yang membantu penjualan aset properti. Saat membuat transaksi penjualan, Anda bisa memasukkan data agen dan sistem akan otomatis menghitung komisi 5% dari nilai transaksi.',
                'keywords' => ['komisi', 'agen', '5%', 'perantara', 'penjualan', 'broker'],
                'is_active' => true,
            ],
            [
                'question' => 'Apa fungsi nomor rekening pada data agen?',
                'answer' => 'Nomor rekening digunakan untuk keperluan transfer komisi 5% dari setiap penjualan properti. Agen properti di Alam Sari bekerja freelance dan tidak mendapat gaji tetap, hanya komisi per transaksi.',
                'keywords' => ['rekening', 'bank', 'komisi', 'transfer', 'pembayaran', 'agen'],
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara mengubah password saya?',
                'answer' => 'Untuk mengubah password, masuk ke menu Profile (klik nama Anda di pojok kanan atas), kemudian pilih "Update Password". Masukkan password lama dan password baru, lalu simpan.',
                'keywords' => ['password', 'ubah', 'ganti', 'update', 'profile'],
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara download laporan PDF?',
                'answer' => 'Di setiap halaman laporan (Cash Flow, Profit Loss, dll), terdapat tombol "Download PDF". Klik tombol tersebut untuk mendownload laporan dalam format PDF.',
                'keywords' => ['pdf', 'download', 'unduh', 'laporan', 'cetak'],
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            ChatbotFaq::create($faq);
        }
    }
}
