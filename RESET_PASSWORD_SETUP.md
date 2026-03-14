# ============================================

# PANDUAN KONFIGURASI RESET PASSWORD

# ============================================

## 📧 KONFIGURASI EMAIL DI .ENV

Tambahkan atau update konfigurasi berikut di file `.env` Anda:

```env
# ============================================
# KONFIGURASI EMAIL (PILIH SALAH SATU)
# ============================================

# --- OPSI 1: Gmail (Paling Mudah untuk Testing) ---
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email-anda@gmail.com
MAIL_PASSWORD=your-app-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@mancraft.com"
MAIL_FROM_NAME="Mancraft Finance"

# --- OPSI 2: Mailtrap (Untuk Development/Testing) ---
# MAIL_MAILER=smtp
# MAIL_HOST=sandbox.smtp.mailtrap.io
# MAIL_PORT=2525
# MAIL_USERNAME=your-mailtrap-username
# MAIL_PASSWORD=your-mailtrap-password
# MAIL_ENCRYPTION=tls
# MAIL_FROM_ADDRESS="noreply@mancraft.com"
# MAIL_FROM_NAME="Mancraft Finance"

# --- OPSI 3: SendGrid (Untuk Production) ---
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.sendgrid.net
# MAIL_PORT=587
# MAIL_USERNAME=apikey
# MAIL_PASSWORD=your-sendgrid-api-key
# MAIL_ENCRYPTION=tls
# MAIL_FROM_ADDRESS="noreply@mancraft.com"
# MAIL_FROM_NAME="Mancraft Finance"

# --- OPSI 4: Mailgun (Untuk Production) ---
# MAIL_MAILER=mailgun
# MAILGUN_DOMAIN=your-domain.com
# MAILGUN_SECRET=your-mailgun-api-key
# MAIL_FROM_ADDRESS="noreply@mancraft.com"
# MAIL_FROM_NAME="Mancraft Finance"

# ============================================
# APP URL (PENTING!)
# ============================================
APP_URL=http://localhost:8000
# Untuk production: APP_URL=https://yourdomain.com
```

## 🔐 CARA MENDAPATKAN APP PASSWORD GMAIL

Jika menggunakan Gmail, Anda TIDAK bisa menggunakan password biasa. Ikuti langkah ini:

1. Buka [Google Account Settings](https://myaccount.google.com/)
2. Pilih **Security** di menu kiri
3. Aktifkan **2-Step Verification** (jika belum)
4. Setelah 2FA aktif, cari **App passwords**
5. Generate password untuk "Mail" dan "Other (Custom name)"
6. Salin 16-digit password yang diberikan
7. Paste ke `MAIL_PASSWORD` di `.env` (tanpa spasi)

## 🧪 TESTING DENGAN MAILTRAP (RECOMMENDED)

Untuk testing tanpa mengirim email real:

1. Daftar gratis di [Mailtrap.io](https://mailtrap.io/)
2. Buat inbox baru
3. Salin credentials SMTP
4. Paste ke `.env` Anda
5. Semua email akan tertangkap di Mailtrap inbox (tidak terkirim ke user real)

## ✅ CARA TESTING FITUR RESET PASSWORD

### 1. Jalankan aplikasi

```bash
php artisan serve
```

### 2. Buka halaman lupa password

```
http://localhost:8000/forgot-password
```

### 3. Test flow lengkap:

-   Masukkan email yang terdaftar
-   Klik "Kirim Link Reset Password"
-   Cek inbox (atau Mailtrap jika testing)
-   Klik link di email
-   Masukkan password baru
-   Login dengan password baru

## 🔍 TROUBLESHOOTING

### Error: "Failed to authenticate on SMTP"

**Solusi:**

-   Pastikan MAIL_USERNAME dan MAIL_PASSWORD benar
-   Jika Gmail, gunakan App Password bukan password biasa
-   Cek MAIL_PORT dan MAIL_ENCRYPTION sesuai provider

### Error: "Connection could not be established"

**Solusi:**

```bash
# Clear config cache
php artisan config:clear
php artisan cache:clear

# Test koneksi email
php artisan tinker
> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

### Email tidak terkirim

**Solusi:**

-   Cek queue: pastikan QUEUE_CONNECTION=sync di .env untuk testing
-   Cek logs: `storage/logs/laravel.log`
-   Verifikasi MAIL_FROM_ADDRESS valid

### Link expired atau invalid token

**Solusi:**

-   Default expired 60 menit, ubah di `config/auth.php`:

```php
'passwords' => [
    'users' => [
        'expire' => 60, // ubah sesuai kebutuhan (dalam menit)
    ],
],
```

## 📝 KEAMANAN & BEST PRACTICES

### ✅ Yang sudah diimplementasikan:

-   Token disimpan di database dengan hash
-   Token expired otomatis (default 60 menit)
-   Validasi email sebelum kirim
-   Rate limiting untuk prevent abuse
-   Password harus minimal 8 karakter
-   Password confirmation untuk prevent typo

### 🔒 Rekomendasi Production:

```php
// config/auth.php - Password rules
'password_timeout' => 10800, // 3 jam
'passwords' => [
    'users' => [
        'expire' => 60, // Token expired 1 jam
    ],
],

// Tambahan di ForgotPasswordController untuk rate limiting
// Sudah otomatis handle Laravel dengan throttle middleware
```

## 📚 ROUTES YANG TERSEDIA

```php
// Guest routes (tidak perlu login)
GET  /forgot-password           -> Form input email
POST /forgot-password           -> Proses kirim email
GET  /reset-password/{token}    -> Form reset password
POST /reset-password            -> Proses reset password

// Auth routes (perlu login)
GET  /login                     -> Form login
POST /login                     -> Proses login
```

## 🎨 KUSTOMISASI EMAIL TEMPLATE

File template email ada di:
`app/Notifications/ResetPasswordNotification.php`

Untuk custom HTML email lebih lanjut:

```bash
# Publish vendor views
php artisan vendor:publish --tag=laravel-notifications

# Edit template di: resources/views/vendor/notifications/email.blade.php
```

## 📞 SUPPORT

Jika ada pertanyaan atau error:

1. Cek `storage/logs/laravel.log`
2. Jalankan `php artisan config:clear`
3. Test email dengan Mailtrap dulu sebelum production
4. Pastikan APP_URL sesuai dengan URL aplikasi

---

✨ **Fitur Reset Password sudah siap digunakan!**
