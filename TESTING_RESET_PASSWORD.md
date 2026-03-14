# 🎯 QUICK START - Testing Reset Password

## Step 1: Setup Email (Pilih salah satu)

### Opsi A: Mailtrap (RECOMMENDED untuk testing)

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@mancraft.com"
MAIL_FROM_NAME="Mancraft Finance"
```

### Opsi B: Gmail

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email-anda@gmail.com
MAIL_PASSWORD=your-16-digit-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@mancraft.com"
MAIL_FROM_NAME="Mancraft Finance"
```

## Step 2: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

## Step 3: Testing

### Test 1: Akses halaman forgot password

```
http://localhost:8000/forgot-password
```

### Test 2: Masukkan email user yang ada

Contoh: email dari tabel users

### Test 3: Cek email masuk

-   Jika Mailtrap: cek inbox di mailtrap.io
-   Jika Gmail: cek inbox Gmail

### Test 4: Klik link di email

Format: `http://localhost:8000/reset-password/{token}?email=xxx`

### Test 5: Masukkan password baru

-   Password minimal 8 karakter
-   Konfirmasi password harus sama

### Test 6: Login dengan password baru

```
http://localhost:8000/login
```

## 🔍 Quick Debug

### Cek user di database:

```bash
php artisan tinker
> User::first()
> User::where('email', 'test@example.com')->first()
```

### Test kirim email manual:

```bash
php artisan tinker
> use Illuminate\Support\Facades\Mail;
> Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });
```

### Cek config email:

```bash
php artisan tinker
> config('mail.mailers.smtp')
```

## ✅ Checklist

-   [ ] File `.env` sudah dikonfigurasi dengan benar
-   [ ] `php artisan config:clear` sudah dijalankan
-   [ ] Ada user di database untuk testing
-   [ ] Halaman `/forgot-password` bisa diakses
-   [ ] Email terkirim (cek Mailtrap atau inbox)
-   [ ] Link di email berfungsi
-   [ ] Form reset password muncul dengan benar
-   [ ] Password berhasil direset
-   [ ] Bisa login dengan password baru

## 📋 Testing Checklist Detail

### Skenario 1: Happy Path

1. ✅ User buka `/forgot-password`
2. ✅ User input email yang terdaftar
3. ✅ Muncul SweetAlert "Email Terkirim"
4. ✅ Email diterima dengan link reset
5. ✅ Klik link, redirect ke form reset
6. ✅ Input password baru (2x)
7. ✅ Berhasil reset, redirect ke login
8. ✅ Login dengan password baru berhasil

### Skenario 2: Error Handling

1. ❌ Input email tidak terdaftar → Error "Email tidak terdaftar"
2. ❌ Input format email salah → Error "Format email tidak valid"
3. ❌ Token expired/invalid → Error "Token tidak valid"
4. ❌ Password tidak match → Error "Konfirmasi password tidak cocok"
5. ❌ Password terlalu pendek → Error validasi

## 🚀 Production Checklist

Sebelum deploy ke production:

-   [ ] Ganti MAIL provider ke SendGrid/Mailgun/SES
-   [ ] Update APP_URL ke domain production
-   [ ] Set QUEUE_CONNECTION ke redis/database
-   [ ] Test email dari production server
-   [ ] Monitor email deliverability
-   [ ] Setup email logging/tracking

---

💡 **Tip**: Selalu test dengan Mailtrap dulu sebelum production!
