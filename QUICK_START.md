# 🚀 QUICK START - 5 Menit Setup Reset Password

## 📝 Yang Sudah Dibuat

✅ Semua controller, view, dan route sudah siap!
✅ Database migration sudah ada!
✅ Tinggal konfigurasi email dan test!

## ⚡ 3 Langkah Setup

### LANGKAH 1: Daftar Mailtrap (2 menit)

1. Buka browser → [mailtrap.io](https://mailtrap.io/)
2. Klik "Sign Up" (gratis, tidak perlu credit card)
3. Verify email Anda
4. Login → buat inbox baru
5. Klik inbox → pilih "SMTP Settings"
6. Copy credentials yang muncul

### LANGKAH 2: Update .env (1 menit)

Buka file `.env` di project, cari section `MAIL_` dan update:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=paste_username_dari_mailtrap
MAIL_PASSWORD=paste_password_dari_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@mancraft.com"
MAIL_FROM_NAME="Mancraft Finance"

APP_URL=http://localhost:8000
```

Jalankan:

```bash
php artisan config:clear
```

### LANGKAH 3: Test (2 menit)

```bash
# Start server
php artisan serve
```

Buka browser:

```
http://localhost:8000/forgot-password
```

1. Input email user yang ada di database
2. Klik "Kirim Link Reset Password"
3. Buka Mailtrap inbox → lihat email masuk
4. Klik link di email
5. Masukkan password baru
6. Login dengan password baru → DONE! ✅

## 🎯 Test Cepat dengan Command

```bash
php artisan tinker

# Lihat user yang ada
> User::first()

# Test kirim email
> use Illuminate\Support\Facades\Password;
> Password::sendResetLink(['email' => 'admin@example.com']);
# Output: "passwords.sent" = SUCCESS ✅
```

## ❓ Troubleshooting 1 Menit

### Email tidak terkirim?

```bash
php artisan config:clear
php artisan cache:clear
```

Cek `storage/logs/laravel.log`

### Tidak ada user untuk test?

```bash
php artisan tinker
> User::factory()->create(['email' => 'test@example.com', 'password' => bcrypt('password123')])
```

### Lupa credentials Mailtrap?

Login ke Mailtrap → Inbox → SMTP Settings

## 📚 Dokumentasi Lengkap

Kalau perlu detail lebih lanjut, baca file ini:

-   `RESET_PASSWORD_SUMMARY.md` - Overview lengkap
-   `EMAIL_PROVIDERS_GUIDE.md` - Setup email provider lain
-   `TESTING_RESET_PASSWORD.md` - Panduan testing detail
-   `IMPLEMENTATION_CHECKLIST.md` - Checklist lengkap

## ✨ Done!

Selamat! Fitur reset password sudah jalan! 🎉

**Total waktu setup: ~5 menit**

---

### 🎁 Bonus: Test URL

Setelah server jalan (`php artisan serve`):

-   Login: http://localhost:8000/login
-   Forgot Password: http://localhost:8000/forgot-password
-   Dashboard: http://localhost:8000/dashboard

### 🔐 Default User (kalau ada)

Cek dengan:

```bash
php artisan tinker
> User::first()
```

---

**Questions?** Baca `RESET_PASSWORD_SETUP.md` untuk panduan lengkap!
