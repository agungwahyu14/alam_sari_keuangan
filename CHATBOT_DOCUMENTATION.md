# 💬 Chatbot Bantuan Onboarding - Dokumentasi

## 📋 Ringkasan Fitur

Fitur Chatbot Bantuan Onboarding adalah asisten virtual berbasis AI yang membantu pegawai baru memahami cara penggunaan Sistem Manajemen Keuangan. Chatbot ini menggunakan **Alpine.js** untuk interaktivitas frontend dan **Laravel** untuk backend processing.

---

## 🏗️ Arsitektur & Struktur Kode

### 1. **Database & Model**

#### Migration: `create_chatbot_faqs_table`

```
chatbot_faqs
├── id (bigint, primary key)
├── question (text) - Pertanyaan FAQ
├── answer (text) - Jawaban lengkap
├── keywords (json) - Array keyword untuk pencarian
├── is_active (boolean) - Status aktif/nonaktif
├── created_at (timestamp)
└── updated_at (timestamp)
```

#### Model: `ChatbotFaq`

-   **Location**: `app/Models/ChatbotFaq.php`
-   **Features**:
    -   Mass assignment protection dengan `$guarded`
    -   Auto-casting `keywords` ke array dan `is_active` ke boolean
    -   Scope `active()` untuk filter data aktif

---

### 2. **Admin Management (CRUD)**

#### Controller: `ChatbotFaqController`

-   **Location**: `app/Http/Controllers/Admin/ChatbotFaqController.php`
-   **Methods**:
    -   `index()` - Tampilkan daftar FAQ dengan pagination
    -   `store()` - Simpan FAQ baru dengan validasi
    -   `update()` - Update FAQ existing
    -   `destroy()` - Hapus FAQ

#### Routes:

```php
// Admin only - require middleware 'admin'
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('chatbot', ChatbotFaqController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});
```

#### Admin Panel View:

-   **Location**: `resources/views/admin/chatbot/index.blade.php`
-   **Features**:
    -   Daftar FAQ dengan pagination
    -   Modal tambah/edit FAQ
    -   Toggle status aktif/nonaktif
    -   Keyword management
    -   Responsive design dengan Tailwind CSS

**URL Akses**: `/admin/chatbot`

---

### 3. **Chatbot Service (Logic Engine)**

#### Service Class: `ChatbotService`

-   **Location**: `app/Services/ChatbotService.php`
-   **Algorithm**: Advanced scoring system dengan 3 kriteria

##### Scoring System:

1. **Question Similarity (Bobot 3x)**
    - Menggunakan `similar_text()` PHP function
    - Membandingkan input user dengan pertanyaan di database
2. **Keyword Matching (Bobot 2x max)**
    - Cek apakah keyword ada dalam input user
    - Setiap keyword match = +0.5 score
3. **Word Overlap (Bobot 1x)**
    - Hitung jumlah kata yang sama
    - Score = overlap count / max word count

##### Methods:

-   `findAnswer($userInput)` - Cari jawaban terbaik
-   `normalizeText($text)` - Normalisasi teks (lowercase, remove special chars)
-   `searchWithScoring($input)` - Sistem scoring untuk matching
-   `calculateSimilarity($str1, $str2)` - Hitung similarity 0-1
-   `getSuggestedQuestions($limit)` - Ambil suggested questions random

##### Threshold:

-   Minimum score: **30%** untuk dianggap match
-   Jika tidak ada match: return default response

---

### 4. **Frontend Widget Component**

#### Blade Component Class:

-   **Location**: `app/View/Components/ChatbotWidget.php`
-   **Fungsi**: Load suggested questions dari database

#### View Template:

-   **Location**: `resources/views/components/chatbot-widget.blade.php`
-   **Technology**: Alpine.js + Tailwind CSS

##### Alpine.js State Management:

```javascript
{
    isOpen: false,          // Status chat window (buka/tutup)
    isTyping: false,        // Loading indicator saat bot mengetik
    userInput: '',          // Input dari user
    messages: [],           // Array pesan (user & bot)
    suggestedQuestions: []  // Pertanyaan yang disarankan
}
```

##### Key Methods:

-   `toggleChat()` - Toggle buka/tutup chat window
-   `sendMessage()` - Kirim pesan ke backend via AJAX
-   `sendSuggestedQuestion()` - Gunakan suggested question
-   `scrollToBottom()` - Auto-scroll ke pesan terbaru

##### UI Components:

1. **Floating Button** - Icon chat di pojok kanan bawah
2. **Chat Window** - Window chat dengan header, messages area, dan input
3. **Messages Area** - Tampilan pesan user dan bot
4. **Suggested Questions** - Tombol quick reply
5. **Typing Indicator** - Animated dots saat bot mengetik

---

### 5. **API Endpoint**

#### Controller: `ChatbotController`

-   **Location**: `app/Http/Controllers/ChatbotController.php`
-   **Method**: `sendMessage(Request $request)`

#### Route:

```php
Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])
    ->name('chatbot.send');
```

#### Request Format:

```json
{
    "message": "Bagaimana cara login?"
}
```

#### Response Format:

```json
{
    "user_message": "Bagaimana cara login?",
    "bot_response": "Untuk login ke sistem...",
    "matched_question": "Bagaimana cara login ke sistem?",
    "confidence": "high",
    "timestamp": "14:30"
}
```

---

### 6. **Integration**

#### Guest Layout Integration:

-   **Location**: `resources/views/layouts/guest.blade.php`
-   **Code**:

```blade
<!-- Chatbot Widget -->
<x-chatbot-widget />
```

Widget ini otomatis muncul di:

-   ✅ Login Page (`/login`)
-   ✅ Register Page (`/register`)
-   ✅ Forgot Password Page
-   ✅ Reset Password Page

---

## 🚀 Cara Penggunaan

### A. Untuk Admin - Kelola FAQ

1. **Login sebagai Admin**
2. **Akses Admin Panel**: Navigasi ke `/admin/chatbot`
3. **Tambah FAQ Baru**:
    - Klik tombol "Tambah FAQ"
    - Isi pertanyaan, jawaban, dan keywords (opsional)
    - Centang "Aktifkan FAQ" jika ingin langsung aktif
    - Klik Simpan
4. **Edit FAQ**:
    - Klik icon pensil pada FAQ yang ingin diedit
    - Ubah data yang diperlukan
    - Klik Update
5. **Hapus FAQ**:
    - Klik icon trash
    - Konfirmasi penghapusan

**Tips Membuat FAQ Efektif**:

-   Gunakan bahasa yang jelas dan mudah dipahami
-   Tambahkan keywords yang relevan untuk meningkatkan akurasi pencarian
-   Pisahkan keywords dengan koma: `login, masuk, akses`
-   Buat jawaban yang lengkap namun tidak terlalu panjang

---

### B. Untuk User - Menggunakan Chatbot

1. **Buka halaman Login/Register**
2. **Klik floating button** chatbot (icon 💬) di pojok kanan bawah
3. **Pilih Suggested Question** atau ketik pertanyaan sendiri
4. **Kirim pesan** dengan menekan Enter atau klik tombol kirim
5. **Bot akan merespons** dalam 1-2 detik dengan jawaban yang relevan

**Contoh Pertanyaan**:

-   "Bagaimana cara login?"
-   "Cara membuat transaksi baru"
-   "Apa itu pemasukan dan pengeluaran?"
-   "Download laporan PDF"

---

## 🎨 Customization

### Mengubah Warna Theme:

Edit file `resources/views/components/chatbot-widget.blade.php`:

```css
/* Ubah gradient hijau menjadi biru */
background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
```

### Mengubah Posisi Widget:

```css
.chatbot-widget-container {
    bottom: 20px; /* Jarak dari bawah */
    right: 20px; /* Jarak dari kanan */
    /* Untuk pojok kiri: left: 20px; */
}
```

### Mengubah Ukuran Chat Window:

```css
.chatbot-window {
    width: 380px; /* Lebar */
    height: 550px; /* Tinggi */
}
```

---

## 🧪 Testing

### Test Manual:

1. **Test Admin Panel**:

    ```bash
    # Login sebagai admin
    # Buka /admin/chatbot
    # Coba tambah, edit, hapus FAQ
    ```

2. **Test Chatbot Widget**:

    ```bash
    # Buka /login atau /register
    # Klik chatbot button
    # Test dengan berbagai pertanyaan:
    - Exact match: "Bagaimana cara login ke sistem?"
    - Partial match: "cara login"
    - Keywords: "password"
    - No match: "test random question"
    ```

3. **Test API Endpoint**:
    ```bash
    curl -X POST http://localhost:8000/chatbot/send \
      -H "Content-Type: application/json" \
      -H "X-CSRF-TOKEN: YOUR_TOKEN" \
      -d '{"message":"Bagaimana cara login?"}'
    ```

---

## 📊 Sample FAQ Data

10 sample FAQ telah dibuat melalui seeder:

1. Cara login ke sistem
2. Cara membuat transaksi baru
3. Perbedaan Pemasukan dan Pengeluaran
4. Cara melihat laporan keuangan
5. Cara mengelola data layanan
6. Siapa yang bisa akses Admin
7. Cara menambah karyawan baru
8. Fungsi nomor rekening
9. Cara mengubah password
10. Cara download laporan PDF

**Run Seeder**:

```bash
php artisan db:seed --class=ChatbotFaqSeeder
```

---

## 🔧 Troubleshooting

### Chatbot tidak muncul:

1. Pastikan Alpine.js sudah terinstall: `npm list alpinejs`
2. Build assets: `npm run build` atau `npm run dev`
3. Clear cache: `php artisan cache:clear`

### Bot tidak merespons:

1. Cek console browser untuk error JavaScript
2. Pastikan route `/chatbot/send` accessible
3. Cek CSRF token tersedia di meta tag

### Pencarian tidak akurat:

1. Tambahkan lebih banyak keywords pada FAQ
2. Gunakan sinonim dalam keywords
3. Turunkan threshold di `ChatbotService.php` (default 0.3)

---

## 📈 Future Enhancements

Beberapa ide pengembangan fitur:

1. **AI Integration**: Integrasi dengan OpenAI GPT untuk jawaban yang lebih natural
2. **Analytics**: Tracking pertanyaan yang sering ditanyakan
3. **Multilanguage**: Support bahasa Inggris
4. **Voice Input**: Input suara untuk accessibility
5. **File Upload**: Upload screenshot untuk troubleshooting
6. **Chat History**: Simpan riwayat chat user
7. **Feedback System**: User bisa rate jawaban bot (helpful/not helpful)
8. **Auto-learn**: Bot belajar dari pertanyaan yang tidak terjawab

---

## 👨‍💻 Developer Notes

### Code Quality:

-   ✅ PSR-12 Coding Standard
-   ✅ Type Hints pada semua methods
-   ✅ Comprehensive comments
-   ✅ Separation of Concerns (Service Layer)
-   ✅ Reusable Components

### Security:

-   ✅ CSRF Protection pada AJAX requests
-   ✅ Input Validation & Sanitization
-   ✅ Admin middleware protection
-   ✅ SQL Injection prevention (Eloquent ORM)

### Performance:

-   ✅ Database indexing pada `is_active` column
-   ✅ Pagination pada admin panel
-   ✅ Efficient scoring algorithm
-   ✅ Lazy loading suggested questions

---

## 📝 Changelog

### Version 1.0.0 (Initial Release)

-   ✅ Database migration & model
-   ✅ Admin CRUD untuk FAQ management
-   ✅ ChatbotService dengan scoring algorithm
-   ✅ Alpine.js widget component
-   ✅ Integration pada guest layout
-   ✅ 10 sample FAQ data
-   ✅ Responsive design
-   ✅ Typing indicator animation

---

## 📞 Support

Jika ada pertanyaan atau issue, silakan hubungi:

-   **Developer**: Senior Laravel Developer
-   **Documentation**: Lengkap dengan code comments

---

**🎉 Selamat! Fitur Chatbot Bantuan Onboarding sudah siap digunakan!**
