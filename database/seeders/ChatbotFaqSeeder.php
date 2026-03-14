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
                'answer' => 'Untuk membuat transaksi baru, masuk ke menu Transaksi, klik tombol "Tambah Transaksi", pilih jenis transaksi (Pemasukan/Pengeluaran), pilih layanan, masukkan nominal, dan klik Simpan.',
                'keywords' => ['transaksi', 'tambah', 'buat', 'pemasukan', 'pengeluaran'],
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
                'question' => 'Bagaimana cara mengelola data layanan?',
                'answer' => 'Hanya admin yang bisa mengelola data layanan. Masuk ke menu Layanan, kemudian Anda bisa menambah, mengedit, atau menghapus layanan. Setiap layanan memiliki nama dan deskripsi.',
                'keywords' => ['layanan', 'service', 'kelola', 'tambah', 'edit', 'hapus'],
                'is_active' => true,
            ],
            [
                'question' => 'Siapa yang bisa akses halaman Admin?',
                'answer' => 'Hanya user dengan role "admin" yang bisa mengakses fitur-fitur admin seperti kelola layanan, kelola karyawan, dan melihat semua transaksi. Karyawan biasa hanya bisa mengelola transaksi mereka sendiri.',
                'keywords' => ['admin', 'role', 'akses', 'karyawan', 'hak akses'],
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara menambah karyawan baru?',
                'answer' => 'Untuk menambah karyawan baru (hanya admin), masuk ke menu Karyawan, klik "Tambah Karyawan", isi data seperti nama, email, password, nomor rekening, dan pilih role (Admin/Karyawan). Klik Simpan.',
                'keywords' => ['karyawan', 'tambah', 'user', 'pegawai', 'employee'],
                'is_active' => true,
            ],
            [
                'question' => 'Apa fungsi nomor rekening pada data karyawan?',
                'answer' => 'Nomor rekening digunakan untuk keperluan pembayaran gaji atau transfer. Data ini penting untuk proses penggajian karyawan.',
                'keywords' => ['rekening', 'bank', 'gaji', 'transfer', 'pembayaran'],
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
