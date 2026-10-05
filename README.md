# Sistem Manajemen Keuangan dan Penjualan Properti Alam Sari

Aplikasi berbasis web untuk pengelolaan administrasi keuangan, pencatatan penjualan unit properti, kalkulasi otomatis komisi perantara (agen freelance), penyusunan laporan arus kas dan laba rugi, serta bantuan interaktif melalui chatbot FAQ cerdas.

---

## 1. Ikhtisar Sistem

Sistem Manajemen Keuangan Alam Sari dirancang untuk mendigitalkan dan mengotomatiskan siklus pencatatan keuangan pada bisnis properti real estate. Sistem ini melayani dua tingkat pengguna utama:
- **Admin**: Memiliki kendali penuh atas data master properti, data pengguna/agen, seluruh pencatatan transaksi masuk dan keluar, serta penerbitan laporan keuangan resmi tingkat perusahaan.
- **Agen**: Memiliki akses untuk mencatat penjualan, memantau riwayat transaksi yang terkait dengan akun mereka, dan melihat kalkulasi komisi (5%) secara transparan.

---

## 2. Arsitektur Aplikasi

Aplikasi dibangun menggunakan pola arsitektur **Model-View-Controller (MVC)** dengan pengayaan **Service Layer** pada framework Laravel 11.

```
[ Klien / Browser ]
        │
        ▼  HTTP Request / AJAX
[ Route / Middleware ] (web.php: Auth, Admin Middleware)
        │
        ▼
[ Controller ] (DashboardController, TransactionController, ServiceController, LaporanController, ChatbotController)
   ├── Business Logic / Service Layer (ChatbotService, DomPDF)
   └── Data Access Layer / Eloquent ORM
        │
        ▼
   [ Database (MySQL / SQLite) ]
   (users, services, transactions, chatbot_faqs)
        │
        ▼
[ View / Blade Templates ] (Tailwind CSS, DataTables, Chart.js)
        │
        ▼  HTML / JSON Response / PDF Stream
[ Tampilan Antarmuka Klien ]
```

### Komponen Arsitektur:
1. **Routing & Middleware Layer (`routes/web.php`)**:
   - `auth`: Memastikan hanya pengguna terotentikasi yang dapat mengakses modul utama.
   - `admin`: Membatasi rute data master properti (`/layanan`), data agen (`/agen`), dan laporan global hanya untuk administrator.
2. **Controller Layer (`app/Http/Controllers/`)**:
   - `DashboardController`: Menghitung metrik performa (omzet, pengeluaran, laba bersih, tren pertumbuhan bulanan).
   - `ServiceController`: Pengelolaan data inventaris unit properti (CRUD, status ketersediaan, filter DataTables).
   - `TransactionController`: Pencatatan transaksi debit/kredit dan penghitungan komisi agen 5%.
   - `LaporanController`: Pemrosesan agregasi data arus kas, laba rugi, dan konversi ke dokumen PDF menggunakan DomPDF.
   - `ChatbotController`: Endpoint komunikasi percakapan FAQ otomatis.
   - `MockupController`: Endpoint terpusat navigasi halaman mockup demonstrasi aplikasi.
3. **Service Layer (`app/Services/`)**:
   - `ChatbotService`: Logika pemrosesan teks, normalisasi string, dan algoritma kecocokan pertanyaan pengguna dengan basis pengetahuan database FAQ.
4. **Data Model Layer (`app/Models/`)**:
   - `User`: Entitas pengguna dengan atribut `role` (`admin` / `agen`) dan relasi `hasMany` ke `Transaction`.
   - `Service`: Entitas data properti dengan atribut `property_type`, `location`, `price`, dan `status` (`tersedia`, `terjual`).
   - `Transaction`: Entitas transaksi finansial dengan atribut `type` (`income` / `expense`), `amount`, `agent_commission` (5%), dan relasi ke `User` dan `Service`.
   - `ChatbotFaq`: Entitas basis pengetahuan tanya-jawab untuk chatbot.
5. **View Layer (`resources/views/`)**:
   - Blade Template engine terintegrasi Tailwind CSS, FontAwesome 6, Chart.js untuk visualisasi data, dan jQuery DataTables untuk paginasi data server-side.

---

## 3. Skema Basis Data dan Relasi

### Tabel `users`
- `id` (Primary Key)
- `name` (String)
- `email` (String, Unique)
- `password` (Hashed String)
- `role` (Enum/String: `admin`, `agen`)
- `bank_account` (String, Nullable)
- `created_at`, `updated_at` (Timestamp)

### Tabel `services` (Data Properti)
- `id` (Primary Key)
- `name` (String - Nama unit/properti)
- `property_type` (String - Tipe perumahan/ruko/tanah)
- `location` (String - Alamat/lokasi unit)
- `price` (BigInteger - Harga jual properti)
- `status` (String: `tersedia`, `terjual`)
- `description` (Text, Nullable)
- `created_at`, `updated_at` (Timestamp)

### Tabel `transactions`
- `id` (Primary Key)
- `user_id` (Foreign Key -> `users.id`, Nullable)
- `service_id` (Foreign Key -> `services.id`, Nullable)
- `type` (String: `income`, `expense`)
- `amount` (BigInteger - Nilai nominal)
- `description` (Text, Nullable)
- `transaction_date` (Date)
- `agent_id` (Foreign Key -> `users.id`, Nullable)
- `agent_name` (String, Nullable)
- `agent_commission` (Decimal 15,2 - Nilai rupiah komisi)
- `commission_rate` (Decimal 5,2 - Persentase komisi, default 5.00%)
- `created_at`, `updated_at` (Timestamp)

### Tabel `chatbot_faqs`
- `id` (Primary Key)
- `question` (String - Pertanyaan rujukan)
- `answer` (Text - Jawaban sistem)
- `category` (String - Kategori modul)
- `is_active` (Boolean)
- `created_at`, `updated_at` (Timestamp)

---

## 4. Alur Kerja Sistem (Business Workflow)

### A. Alur Autentikasi dan Otorisasi
1. Pengguna membuka halaman `/login`.
2. Sistem memverifikasi kredensial akun dan memuat session.
3. Berdasarkan atribut `role`:
   - Admin diarahkan ke antarmuka komprehensif (seluruh data properti, transaksi perusahaan, kelola agen, cetak laporan).
   - Agen diarahkan ke antarmuka terbatas (hanya transaksi dan komisi pribadi).

### B. Alur Penjualan Properti dan Komisi Agen
1. Admin mendaftarkan unit baru pada Master Data Properti (`/layanan`) dengan status `tersedia`.
2. Ketika unit berhasil dipasarkan oleh agen:
   - Transaksi Pemasukan (`income`) dicatat melalui menu `/transaksi`.
   - Sistem menautkan transaksi dengan unit properti (`service_id`) dan agen yang bersangkutan (`agent_id`).
   - Sistem secara otomatis menghitung `agent_commission` sebesar 5% dari nilai transaksi masuk.
   - Status unit properti diperbarui menjadi `terjual`.

### C. Alur Pengeluaran dan Pembayaran Komisi
1. Transaksi pengeluaran (`expense`) dicatat untuk mencakup:
   - Pencairan komisi agen perantara.
   - Biaya operasional kantor dan administrasi legalitas/notaris.
2. Setiap transaksi pengeluaran langsung mengurangi saldo kas dan laba bersih perusahaan secara riil.

### D. Alur Pelaporan Keuangan
1. Pengguna mengakses modul `/laporan`.
2. Pengguna menentukan parameter periode bulan dan tahun.
3. Sistem mengumpulkan data transaksi dan menyajikan:
   - **Laporan Arus Kas (Cash Flow)**: Rekapitulasi pergerakan kas masuk dan keluar beserta saldo akhir.
   - **Laporan Laba Rugi (Profit & Loss)**: Analisis pendapatan kotor dikurangi seluruh beban operasional dan komisi.
   - **Laporan Pendapatan Layanan (Service Revenue)**: Distribusi pendapatan per kategori unit properti.
4. Laporan dapat diunduh langsung dalam format dokumen PDF siap cetak.

### E. Alur Asisten Chatbot FAQ
1. Pengguna mengetikkan pertanyaan pada widget chatbot di pojok antarmuka.
2. Request dikirim ke `ChatbotController::sendMessage`.
3. `ChatbotService` melakukan tokenisasi, penghapusan kata umum, dan pencocokan kemiripan string dengan koleksi `chatbot_faqs`.
4. Bot mengembalikan jawaban paling relevan secara instan beserta indikator skor keyakinan (*confidence level*).

---

## 5. Rincian Modul dan Fitur

1. **Dashboard Utama (`/dashboard`)**:
   - Metrik KPI: Total Pemasukan, Total Pengeluaran, Laba Bersih, dan Total Unit/Karyawan.
   - Visualisasi grafik arus kas bulanan interaktif menggunakan Chart.js.
   - Riwayat transaksi terbaru dan ringkasan distribusi properti.

2. **Master Data Properti (`/layanan`)**:
   - Pendaftaran, pembaruan, dan penghapusan unit properti.
   - Pelabelan otomatis tipe properti, lokasi, nilai jual, dan status ketersediaan.
   - Integrasi DataTables server-side untuk pencarian cepat dan pengurutan data.

3. **Manajemen Transaksi (`/transaksi`)**:
   - Form pencatatan transaksi masuk dan keluar dengan validasi terstruktur.
   - Perhitungan komisi agen otomatis 5%.
   - Filter transaksi berdasarkan rentang tanggal, jenis transaksi, dan nama agen.

4. **Manajemen Agen (`/agen`)**:
   - Pendaftaran akun agen freelance, data rekening bank, dan nomor kontak.
   - Pemantauan akumulasi penjualan dan total komisi per agen.

5. **Pusat Laporan & Cetak PDF (`/laporan`)**:
   - Tampilan ringkasan performa finansial bulanan.
   - Ekspor berkas PDF standar resmi (Arus Kas, Laba Rugi, Pendapatan Layanan).

6. **Asisten Chatbot AI FAQ**:
   - Menyediakan panduan operasional penggunaan aplikasi bagi pengguna tanpa perlu membuka buku manual.

7. **Hub Mockup Demonstrasi (`/mockup`)**:
   - Halaman indeks navigasi untuk keperluan pengujian UI dan dokumentasi akademik/skripsi.

---

## 6. Persyaratan Sistem

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.0 & NPM
- Ekstensi PHP: `pdo`, `pdo_mysql` / `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `gd`
- Database: MySQL 8.0+ atau SQLite 3

---

## 7. Panduan Instalasi dan Menjalankan Aplikasi

1. **Kloning Proyek**:
   ```bash
   git clone <url-repository>
   cd manajemen_keuangan
   ```

2. **Instal Dependensi Backend dan Frontend**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   Salin berkas `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Sesuaikan parameter database di berkas `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=alam_sari_keuangan
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi Basis Data**:
   ```bash
   php artisan migrate --seed
   ```

6. **Build Asset Frontend**:
   ```bash
   npm run build
   ```
   *(Atau `npm run dev` untuk mode pengembangan).*

7. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser pada alamat: `http://127.0.0.1:8000`.

---

## 8. Struktur Direktori Utama

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ChatbotController.php      # Controller Chatbot FAQ
│   │   │   ├── DashboardController.php    # Controller Analisis & Dashboard
│   │   │   ├── LaporanController.php      # Controller Laporan Keuangan & PDF
│   │   │   ├── MockupController.php       # Controller Navigasi Mockup Skripsi
│   │   │   ├── ServiceController.php      # Controller Master Data Properti
│   │   │   ├── TransactionController.php  # Controller Transaksi Keuangan
│   │   │   └── UserController.php         # Controller Kelola Data Agen
│   │   └── Middleware/
│   │       └── AdminMiddleware.php        # Proteksi hak akses Administrator
│   ├── Models/
│   │   ├── ChatbotFaq.php                 # Model FAQ Chatbot
│   │   ├── Service.php                    # Model Master Properti
│   │   ├── Transaction.php                # Model Transaksi & Komisi
│   │   └── User.php                       # Model Pengguna & Hak Akses
│   └── Services/
│       └── ChatbotService.php             # Layanan pencocokan teks tanya-jawab
├── database/
│   ├── migrations/                        # Skema tabel basis data
│   └── seeders/                           # Data awal aplikasi
├── resources/
│   └── views/
│       ├── auth/                          # Template login dan otentikasi
│       ├── dashboard.blade.php            # Tampilan Dashboard metrik keuangan
│       ├── laporan/                       # Template tampilan dan cetak PDF laporan
│       ├── layanan/                       # Template kelola inventaris properti
│       ├── mockup/                        # Template demonstrasi mockup skripsi
│       ├── transaksi/                     # Template input & histori transaksi
│       └── layouts/                       # Layout utama sistem
├── routes/
│   ├── auth.php                           # Rute autentikasi
│   └── web.php                            # Rute aplikasi utama
└── tests/
    └── Feature/
        ├── Auth/                          # Pengujian autentikasi
        └── DashboardTest.php              # Pengujian respons dan komponen dashboard
```
