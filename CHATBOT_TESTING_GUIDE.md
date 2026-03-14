# 🚀 Cara Akses & Test Chatbot

## 📍 Quick Access URLs

### 1️⃣ **Admin Panel** (Kelola FAQ)

```
URL: http://localhost:8000/admin/chatbot
Requirement: Login sebagai Admin
```

**Login Credentials Admin** (jika sudah ada UserSeeder):

-   Email: Lihat database table `users` dengan role = 'admin'
-   Password: Password yang di-set saat seeding

---

### 2️⃣ **Chatbot Widget** (Test sebagai User)

Widget otomatis muncul di:

-   ✅ `http://localhost:8000/login`
-   ✅ `http://localhost:8000/register`
-   ✅ `http://localhost:8000/forgot-password`
-   ✅ `http://localhost:8000/reset-password/{token}`

---

## 🎯 Testing Workflow

### Scenario 1: Admin Setup FAQ

```bash
1. Jalankan server: php artisan serve
2. Buka browser: http://localhost:8000
3. Login sebagai admin
4. Navigasi ke: http://localhost:8000/admin/chatbot
5. Klik "Tambah FAQ"
6. Isi form:
   - Pertanyaan: "Bagaimana cara membuat laporan?"
   - Jawaban: "Masuk ke menu Laporan, pilih jenis laporan..."
   - Keywords: "laporan, report, buat"
   - Centang "Aktifkan FAQ ini"
7. Klik Simpan
8. FAQ baru muncul di list
```

### Scenario 2: User Test Chatbot

```bash
1. Logout dari admin (atau buka incognito window)
2. Buka: http://localhost:8000/login
3. Lihat floating button 💬 di pojok kanan bawah
4. Klik button tersebut
5. Chat window terbuka dengan welcome message
6. Test dengan pertanyaan:
   - "Bagaimana cara login?" ✅
   - "Cara buat transaksi" ✅
   - "Download PDF laporan" ✅
   - "test random question" ❌ (default response)
7. Observe:
   - Typing indicator animation
   - Bot response time (~1 second)
   - Message history
   - Suggested questions buttons
```

---

## 📝 Sample FAQ Questions (Already Seeded)

Coba tanyakan pertanyaan ini untuk test accuracy:

| User Input                 | Expected Match                                  | Accuracy  |
| -------------------------- | ----------------------------------------------- | --------- |
| "cara login"               | Bagaimana cara login ke sistem?                 | High ✅   |
| "buat transaksi baru"      | Bagaimana cara membuat transaksi baru?          | High ✅   |
| "pemasukan vs pengeluaran" | Apa perbedaan antara Pemasukan dan Pengeluaran? | High ✅   |
| "lihat laporan"            | Bagaimana cara melihat laporan keuangan?        | High ✅   |
| "kelola layanan"           | Bagaimana cara mengelola data layanan?          | Medium ✅ |
| "siapa bisa akses admin"   | Siapa yang bisa akses halaman Admin?            | Medium ✅ |
| "tambah pegawai"           | Bagaimana cara menambah karyawan baru?          | High ✅   |
| "nomor rekening untuk apa" | Apa fungsi nomor rekening pada data karyawan?   | Medium ✅ |
| "ganti password"           | Bagaimana cara mengubah password saya?          | High ✅   |
| "download pdf"             | Bagaimana cara download laporan PDF?            | High ✅   |

---

## 🎥 Demo Steps (For Presentation)

### Part 1: Admin Management (2 menit)

```
1. Show admin panel at /admin/chatbot
2. Demo: Tambah FAQ baru
3. Demo: Edit FAQ existing
4. Demo: Toggle active status
5. Show: Keyword management feature
```

### Part 2: User Experience (3 menit)

```
1. Open /login page
2. Show: Floating button (minimal, non-intrusive)
3. Click button → Chat window appears
4. Show: Welcome message & suggested questions
5. Demo: Click suggested question (quick reply)
6. Demo: Type custom question
7. Show: Typing indicator animation
8. Show: Bot response with relevant answer
9. Demo: Continue conversation
10. Show: Responsive design (resize browser)
```

### Part 3: Technical Deep Dive (2 menit)

```
1. Show code: ChatbotService.php (scoring algorithm)
2. Explain: 3 scoring criteria
3. Show: Alpine.js state management
4. Explain: API endpoint flow
```

---

## 🧪 Advanced Testing (Developer)

### Test API Endpoint via Browser Console:

```javascript
// Buka browser console (F12) di halaman login
fetch("/chatbot/send", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
    },
    body: JSON.stringify({ message: "Bagaimana cara login?" }),
})
    .then((res) => res.json())
    .then((data) => console.log(data));

// Expected Response:
// {
//   "user_message": "Bagaimana cara login?",
//   "bot_response": "Untuk login ke sistem...",
//   "matched_question": "Bagaimana cara login ke sistem?",
//   "confidence": "high",
//   "timestamp": "14:30"
// }
```

### Test Database Queries:

```bash
php artisan tinker

# Count active FAQs
>>> App\Models\ChatbotFaq::active()->count()
=> 10

# Test search manually
>>> $service = new App\Services\ChatbotService();
>>> $result = $service->findAnswer('cara login');
>>> dd($result);

# Get suggested questions
>>> $service->getSuggestedQuestions(3)
```

### Test with CURL:

```bash
# Get CSRF token first from /login page
CSRF_TOKEN="your_csrf_token_here"

curl -X POST http://localhost:8000/chatbot/send \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: $CSRF_TOKEN" \
  -d '{"message":"Bagaimana cara login?"}'
```

---

## 🐛 Troubleshooting Guide

### Problem: Floating button tidak muncul

**Solution**:

```bash
# 1. Clear cache
php artisan cache:clear
php artisan view:clear

# 2. Rebuild assets
npm run build

# 3. Hard refresh browser (Cmd+Shift+R / Ctrl+F5)
```

### Problem: Bot tidak merespons

**Check**:

1. Browser Console (F12) → lihat error JavaScript
2. Network tab → cek API request `/chatbot/send`
3. Pastikan CSRF token ada di meta tag
4. Cek database: `SELECT * FROM chatbot_faqs WHERE is_active = 1`

### Problem: Jawaban tidak relevan

**Solution**:

1. Login ke admin panel
2. Edit FAQ yang kurang akurat
3. Tambahkan lebih banyak keywords
4. Gunakan sinonim kata dalam keywords
5. Test lagi dengan pertanyaan yang sama

### Problem: Widget posisi tidak sesuai

**Solution**:
Edit file: `resources/views/components/chatbot-widget.blade.php`

```css
.chatbot-widget-container {
    position: fixed;
    bottom: 20px; /* Adjust this */
    right: 20px; /* Adjust this */
    z-index: 9999;
}
```

---

## 📸 Expected UI Screenshots

### 1. Admin Panel:

```
┌────────────────────────────────────────────┐
│  💬 Chatbot FAQ Management   [+ Tambah FAQ]│
├────────────────────────────────────────────┤
│  #  | Pertanyaan | Jawaban | Keywords |...│
│  1  | Bagaimana cara... | Untuk login... │
│  2  | Cara membuat... | Masuk ke menu...│
│ ...                                        │
└────────────────────────────────────────────┘
```

### 2. Login Page with Chatbot:

```
┌────────────────────────────────────────────┐
│          🔐 Login                          │
│  Email: [_______________]                  │
│  Password: [_______________]               │
│  [Login Button]                            │
│                                            │
│                              [💬] ← Floating
└────────────────────────────────────────────┘
```

### 3. Chat Window Opened:

```
                  ┌───────────────────────┐
                  │ 🤖 Asisten Onboarding │
                  ├───────────────────────┤
                  │ 🤖 Halo! Saya siap... │
                  │                       │
                  │ Pertanyaan sering:    │
                  │ [Cara login?]        │
                  │ [Buat transaksi]     │
                  │                       │
                  │ 👤 Cara login?       │
                  │ 🤖 Untuk login...    │
                  ├───────────────────────┤
                  │ [Ketik pesan...] [→] │
                  └───────────────────────┘
```

---

## ✅ Pre-Launch Checklist

Sebelum deploy ke production, pastikan:

-   [ ] Migration sudah dijalankan
-   [ ] Seeder sudah dijalankan (atau FAQ manual sudah dibuat)
-   [ ] Assets sudah di-build (`npm run build`)
-   [ ] Test admin CRUD (create, read, update, delete)
-   [ ] Test chatbot dengan 10+ pertanyaan berbeda
-   [ ] Test responsive design (mobile & desktop)
-   [ ] Test di browser berbeda (Chrome, Firefox, Safari)
-   [ ] Review security (CSRF, validation, middleware)
-   [ ] Update FAQ sesuai bisnis logic aplikasi
-   [ ] Train admin cara mengelola FAQ

---

## 🎓 Tips untuk Admin

### Membuat FAQ yang Efektif:

1. **Pertanyaan**: Gunakan bahasa natural yang user gunakan

    - ✅ "Bagaimana cara login?"
    - ❌ "Proses autentikasi user"

2. **Jawaban**: Jelas, ringkas, actionable

    - ✅ "Masuk ke menu X, klik tombol Y, isi data Z"
    - ❌ "Silakan lihat dokumentasi lengkap di..."

3. **Keywords**: Include sinonim dan typo umum

    - Contoh: "login, masuk, sign in, akses, log in"

4. **Testing**: Setelah tambah FAQ, test langsung di chatbot

---

## 🚀 Go Live!

Fitur chatbot sudah siap digunakan!

**Next Steps**:

1. ✅ Test semua functionality
2. ✅ Customize warna/posisi jika perlu
3. ✅ Tambahkan FAQ sesuai kebutuhan bisnis
4. ✅ Train admin team cara mengelola FAQ
5. ✅ Monitor pertanyaan yang sering ditanyakan
6. ✅ Improve FAQ based on feedback

---

**Happy Testing! 🎉**
