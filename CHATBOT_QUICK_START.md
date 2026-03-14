# 🚀 Quick Start - Chatbot Bantuan Onboarding

## ⚡ Instalasi & Setup (Sudah Selesai!)

Fitur chatbot sudah **siap digunakan**! Berikut adalah yang sudah dikerjakan:

### ✅ Checklist Implementasi

-   [x] Migration & Model `ChatbotFaq`
-   [x] Admin CRUD Controller & Routes
-   [x] ChatbotService untuk AI logic
-   [x] Alpine.js Widget Component
-   [x] Integration pada Guest Layout
-   [x] Sample FAQ Data (10 entries)
-   [x] Frontend Assets Build

---

## 🎯 Cara Menggunakan

### 1️⃣ Akses Admin Panel (Untuk Kelola FAQ)

```bash
# Login sebagai admin
# Navigasi ke: http://localhost:8000/admin/chatbot
```

**Fitur Admin Panel**:

-   ➕ Tambah FAQ baru
-   ✏️ Edit FAQ existing
-   🗑️ Hapus FAQ
-   🔄 Toggle status aktif/nonaktif
-   🏷️ Kelola keywords untuk pencarian lebih baik

---

### 2️⃣ Test Chatbot Widget (Untuk User)

```bash
# Buka halaman: http://localhost:8000/login
# atau: http://localhost:8000/register
```

**Cara Test**:

1. Lihat **floating button** 💬 di pojok kanan bawah
2. Klik button tersebut
3. Chat window akan muncul
4. Coba tanya:
    - "Bagaimana cara login?"
    - "Cara membuat transaksi baru"
    - "Download laporan PDF"

---

## 📦 File-File Penting

### Backend Files:

```
app/
├── Models/
│   └── ChatbotFaq.php                    # Model FAQ
├── Http/Controllers/
│   ├── Admin/
│   │   └── ChatbotFaqController.php      # Admin CRUD
│   └── ChatbotController.php             # API Endpoint
├── Services/
│   └── ChatbotService.php                # AI Logic Engine
└── View/Components/
    └── ChatbotWidget.php                 # Component Class
```

### Frontend Files:

```
resources/views/
├── admin/chatbot/
│   └── index.blade.php                   # Admin Panel View
├── components/
│   └── chatbot-widget.blade.php          # Widget UI
└── layouts/
    └── guest.blade.php                   # Integration Point
```

### Database Files:

```
database/
├── migrations/
│   └── 2025_12_17_221559_create_chatbot_faqs_table.php
└── seeders/
    └── ChatbotFaqSeeder.php
```

---

## 🔑 Route Endpoints

### Admin Routes (Require Admin Auth):

```php
GET    /admin/chatbot          # List FAQ
POST   /admin/chatbot          # Create FAQ
PUT    /admin/chatbot/{id}     # Update FAQ
DELETE /admin/chatbot/{id}     # Delete FAQ
```

### Public API:

```php
POST   /chatbot/send           # Send message to chatbot
```

---

## 🧪 Testing Commands

### 1. Test Database:

```bash
# Lihat data FAQ
php artisan tinker
>>> App\Models\ChatbotFaq::count()
>>> App\Models\ChatbotFaq::active()->get()
```

### 2. Test API via CURL:

```bash
# Dapatkan CSRF token dari halaman login dulu
curl -X POST http://localhost:8000/chatbot/send \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{"message":"Bagaimana cara login?"}'
```

### 3. Test Frontend:

```bash
# Jalankan development server
php artisan serve

# Di browser, buka:
# http://localhost:8000/login
# Klik chatbot button dan test interaksi
```

---

## 🎨 Customize Widget

### Ubah Warna (Edit: `chatbot-widget.blade.php`)

**Ganti ke Warna Biru**:

```css
/* Dari: */
background: linear-gradient(135deg, #10b981 0%, #047857 100%);

/* Ke: */
background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
```

**Ganti ke Warna Merah**:

```css
background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);
```

### Ubah Posisi Widget

```css
/* Pojok Kiri Bawah */
.chatbot-widget-container {
    bottom: 20px;
    left: 20px; /* Ganti dari 'right' ke 'left' */
}
```

---

## 📊 Sample FAQ Data

Data sudah di-seed dengan 10 pertanyaan umum:

1. ✅ Cara login ke sistem
2. ✅ Cara membuat transaksi baru
3. ✅ Perbedaan Pemasukan dan Pengeluaran
4. ✅ Cara melihat laporan keuangan
5. ✅ Cara mengelola data layanan
6. ✅ Hak akses Admin vs Karyawan
7. ✅ Cara menambah karyawan baru
8. ✅ Fungsi nomor rekening
9. ✅ Cara mengubah password
10. ✅ Cara download laporan PDF

---

## 🐛 Troubleshooting

### Chatbot tidak muncul?

```bash
# 1. Clear cache
php artisan cache:clear
php artisan view:clear

# 2. Rebuild assets
npm run build

# 3. Restart server
php artisan serve
```

### Bot tidak merespons?

1. Buka Console Browser (F12)
2. Lihat error di tab Console
3. Pastikan tidak ada error CSRF token
4. Cek Network tab untuk API request

### Pencarian kurang akurat?

1. Login ke admin panel
2. Edit FAQ yang relevan
3. Tambahkan lebih banyak keywords
4. Gunakan sinonim kata

---

## 💡 Tips Membuat FAQ Berkualitas

### ✅ DO:

-   Gunakan bahasa sederhana dan jelas
-   Sertakan sinonim dalam keywords
-   Buat jawaban yang komprehensif namun ringkas
-   Uji pertanyaan dengan variasi kata

### ❌ DON'T:

-   Jangan gunakan jargon teknis berlebihan
-   Jangan buat jawaban terlalu panjang (max 500 karakter)
-   Jangan lupa aktifkan FAQ setelah dibuat
-   Jangan biarkan keywords kosong

---

## 🔐 Security Notes

✅ **Sudah Implementasi**:

-   CSRF Protection pada semua API calls
-   Input validation & sanitization
-   Admin-only routes dengan middleware
-   SQL Injection prevention (Eloquent ORM)
-   XSS Protection (Blade templating)

---

## 📞 Kontak & Support

**Dokumentasi Lengkap**: Lihat `CHATBOT_DOCUMENTATION.md`

**Developer**: Senior Laravel Developer

---

## 🎉 Status: READY TO USE!

Semua fitur sudah terimplementasi dan siap digunakan. Silakan test dan customize sesuai kebutuhan!

**Next Steps**:

1. Login sebagai admin
2. Tambahkan lebih banyak FAQ sesuai kebutuhan bisnis
3. Test dengan user baru untuk feedback
4. Monitor pertanyaan yang sering ditanyakan

**Happy Coding! 🚀**
