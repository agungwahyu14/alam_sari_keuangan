<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;

class MockupController extends Controller
{
    /**
     * Pastikan pengguna terautentikasi sebagai Admin untuk mengakses sistem persis tampilan asli
     */
    private function ensureAuthenticatedAdmin()
    {
        if (!\Auth::check()) {
            $admin = User::where('role', 'admin')->first() ?? User::first();
            if ($admin) {
                \Auth::login($admin);
            }
        }
    }

    /**
     * Hub utama navigasi mockup seluruh halaman
     */
    public function index()
    {
        $this->ensureAuthenticatedAdmin();

        $mockupPages = [
            [
                'id' => '4.2.1 / 4.4.1',
                'title' => 'Tampilan Halaman Login dan Autentikasi',
                'route' => route('mockup.login'),
                'description' => 'Halaman login dan otentikasi akun pengguna sistem keuangan Alam Sari.',
                'badge' => 'Auth'
            ],
            [
                'id' => '4.2.2 / 4.4.2',
                'title' => 'Tampilan Dashboard Utama',
                'route' => route('mockup.dashboard'),
                'description' => 'Tampilan ringkasan metrik statistik keuangan, grafik arus kas, transaksi terbaru, dan chatbot AI.',
                'badge' => 'Dashboard'
            ],
            [
                'id' => '4.2.3 / 4.4.3',
                'title' => 'Tampilan Kelola Data Master Properti',
                'route' => route('mockup.properti'),
                'description' => 'Tampilan kelola inventaris data master unit properti, modal tambah/edit, dan status ketersediaan.',
                'badge' => 'Master Data'
            ],
            [
                'id' => '4.2.4 / 4.4.4',
                'title' => 'Tampilan Pencatatan Transaksi Pemasukan & Termin Notaris',
                'route' => route('mockup.pemasukan'),
                'description' => 'Tampilan form input transaksi dana masuk (penjualan properti, pembayaran termin bertahap, biaya notaris).',
                'badge' => 'Pemasukan'
            ],
            [
                'id' => '4.2.5 / 4.4.5',
                'title' => 'Tampilan Pencatatan Transaksi Pengeluaran & Komisi Perantara',
                'route' => route('mockup.pengeluaran'),
                'description' => 'Tampilan form input transaksi dana keluar (komisi agen 5%, biaya operasional, dan perizinan).',
                'badge' => 'Pengeluaran'
            ],
            [
                'id' => '4.2.6 / 4.4.6',
                'title' => 'Tampilan Histori Transaksi dan Arus Kas',
                'route' => route('mockup.histori-transaksi'),
                'description' => 'Tampilan tabel rekam jejak histori transaksi keuangan DataTables lengkap dengan penyaring data.',
                'badge' => 'Histori Transaksi'
            ],
            [
                'id' => '4.2.7 / 4.4.7',
                'title' => 'Tampilan Cetak Laporan Keuangan',
                'route' => route('mockup.cetak-laporan'),
                'description' => 'Tampilan panel cetak dan pratinjau PDF laporan keuangan resmi (Arus Kas, Laba Rugi, Pendapatan).',
                'badge' => 'Laporan PDF'
            ]
        ];

        return view('mockup.index', compact('mockupPages'));
    }

    /**
     * 4.4.1 Tampilan Halaman Login dan Autentikasi (Sama Persis Tampilan Asli)
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * 4.4.2 Tampilan Dashboard Utama (Sama Persis Tampilan Asli)
     */
    public function dashboard()
    {
        $this->ensureAuthenticatedAdmin();
        return app(DashboardController::class)->index();
    }

    /**
     * 4.4.3 Tampilan Kelola Data Master Properti (Sama Persis Tampilan Asli)
     */
    public function properti()
    {
        $this->ensureAuthenticatedAdmin();
        return app(ServiceController::class)->index();
    }

    /**
     * 4.4.4 Tampilan Pencatatan Transaksi Pemasukan & Termin Notaris (Sama Persis Tampilan Asli)
     */
    public function pemasukan()
    {
        $this->ensureAuthenticatedAdmin();
        return app(TransactionController::class)->index();
    }

    /**
     * 4.4.5 Tampilan Pencatatan Transaksi Pengeluaran & Komisi Perantara (Sama Persis Tampilan Asli)
     */
    public function pengeluaran()
    {
        $this->ensureAuthenticatedAdmin();
        return app(TransactionController::class)->index();
    }

    /**
     * 4.4.6 Tampilan Histori Transaksi dan Arus Kas (Sama Persis Tampilan Asli)
     */
    public function historiTransaksi()
    {
        $this->ensureAuthenticatedAdmin();
        return app(TransactionController::class)->index();
    }

    /**
     * 4.4.7 Tampilan Cetak Laporan Keuangan (Sama Persis Tampilan Asli)
     */
    public function cetakLaporan()
    {
        $this->ensureAuthenticatedAdmin();
        return app(LaporanController::class)->index();
    }
}
